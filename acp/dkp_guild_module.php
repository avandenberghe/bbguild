<?php
/**
 * Guild ACP file
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;
use sajaki\bbdkp\model\admin\Admin;
use sajaki\bbdkp\model\games\Game;
use sajaki\bbdkp\model\player\Guilds;
use sajaki\bbdkp\model\player\Ranks;

/**
 * This class manages guilds
 *
 *   @package bbdkp
 */
class dkp_guild_module extends Admin
{
    /**
     * url action
     * @var string
     */
    public $u_action;

    /**
     * trigger url
     * @var string
     */
    public $link = ' ';

    /**
     * current url
     * @var string
     */
    public  $url_id;

    /**
     * ACP guild function
     * @param int $id the id of the node who parent has to be returned by function
     * @param int $mode id of the submenu
     */
    public function main ($id, $mode)
    {
        global $user, $template, $db, $phpbb_admin_path, $phpEx;
        global $request, $phpbb_container;
        $form_key = 'sajaki/bbdkp';
        add_form_key ( $form_key );
        $this->tpl_name = 'acp_' . $mode;
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=listguilds') . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';

        switch ($mode)
        {
            case 'listguilds':
                $this->BuildTemplateListGuilds();
                break;
            case 'addguild':
                $addguild = new Guilds();

                if ($request->is_set_post('newguild'))
                {
                    $this->AddGuild($addguild);
                }

                foreach ($this->regions as $key => $regionname)
                {
                    $template->assign_block_vars('region_row', array(
                        'VALUE' => $key ,
                        'SELECTED' => ($addguild->region == $key) ? ' selected="selected"' : '' ,
                        'OPTION' => (! empty($regionname)) ? $regionname : '(None)'));
                }

                if(isset($this->games))
                {
                    foreach ($this->games as $key => $gamename)
                    {
                        $template->assign_block_vars('game_row', array(
                            'VALUE' => $key ,
                            'SELECTED' => ($addguild->game_id == $key) ? ' selected="selected"' : '' ,
                            'OPTION' => (! empty($gamename)) ? $gamename : '(None)'));
                    }
                }
                else
                {
                    trigger_error('ERROR_NOGAMES', E_USER_WARNING );
                }

                $template->assign_vars(array(
                    'F_ENABLEARMORY'	=> $addguild->armory_enabled,
                    'RECSTATUS'         => true,
                ));
                $this->page_title = $user->lang['ACP_ADDGUILD'];
                break;
            case 'editguild':

                $this->url_id = $request->variable(URI_GUILD, 0);
                $updateguild = new Guilds($this->url_id);
                if ($request->is_set_post('memberadd'))
                {
                    redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_mm_module&amp;mode=mm_addmember&amp;' . URI_GUILD . "=" . $this->url_id  ));
                }

                $action = $request->variable('action', '');
                switch($action)
                {
                    case 'guildranks':
                        $updaterank = $request->is_set_post('updaterank');
                        $deleterank = ($request->variable('deleterank', '')) != '' ? true : false;
                        $addrank = $request->is_set_post('addrank');
                        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildranks&amp;' . URI_GUILD . '=' . $updateguild->guildid) . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
                        if ($updaterank || $addrank)
                        {
                            if (! check_form_key('editguildranks'))
                            {
                                trigger_error('FORM_INVALID');
                            }
                        }
                        if ($addrank)
                        {
                            $this->AddRank($updateguild);
                        }
                        if ($addrank)
                        {
                            $this->AddRank($updateguild);
                        }
                        if ($updaterank)
                        {
                            $this->UpdateRank($updateguild);
                        }
                        if ($deleterank)
                        {
                            $this->DeleteRank();
                        }

                        $this->tpl_name = 'dkp/acp_editguild_ranks';
                        $this->BuildTemplateEditGuildRanks($updateguild);
                        break;

                    default:
                        $submit = ($request->is_set_post('updateguild')) ? true : false;
                        $delete = ($request->is_set_post('deleteguild')) ? true : false;
                        $armory = ($request->is_set_post('armory')) ? true : false;
                        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildedit&amp;' . URI_GUILD . '=' . $updateguild->guildid) . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
                        // POST check
                        if ($submit)
                        {
                            if (! check_form_key('sajaki/bbdkp'))
                            {
                                trigger_error('FORM_INVALID', E_USER_NOTICE);
                            }
                            $this->UpdateGuild($updateguild, false);
                        }

                        if($armory)
                        {
                            $this->UpdateGuild($updateguild, true);
                        }

                        if ($delete)
                        {
                            $this->DeleteGuild($updateguild);
                        }
                        // start template loading
                        $this->BuildTemplateEditGuild($updateguild);
                        break;
                }

                break;

            default:
                $this->page_title = 'ACP_DKP_MAINPAGE';
                $success_message = 'Error';
                trigger_error($success_message . $this->link, E_USER_WARNING);
        }
    }

    /**
     * @param Guilds $addguild
     */
    private function AddGuild(Guilds $addguild)
    {
        global $user, $request;

        if (!check_form_key('sajaki/bbdkp'))
        {
            trigger_error('FORM_INVALID');
        }

        $addguild->name = $request->variable('guild_name', '', true);
        $addguild->realm = $request->variable('realm', '', true);
        $addguild->region = $request->variable('region_id', '');
        $addguild->game_id = $request->variable('game_id', '');
        $addguild->showroster = $request->is_set_post('showroster');
        $addguild->min_armory = $request->variable('min_armorylevel', 0);
        $addguild->armory_enabled = $request->variable('armory_enabled', 0);
        $addguild->recstatus = $request->variable('switchon_recruitment', 0);
        $addguild->recruitforum = $request->variable('recruitforum', 0);

        if ($addguild->MakeGuild() == true)
        {
            $success_message = sprintf($user->lang['ADMIN_ADD_GUILD_SUCCESS'], $addguild->name);
            trigger_error($success_message . $this->link, E_USER_NOTICE);
        }
        else
        {
            $success_message = sprintf($user->lang['ADMIN_ADD_GUILD_FAIL'], $addguild->name);
            trigger_error($success_message . $this->link, E_USER_WARNING);
        }
    }

    /**
     * update the default flag
     * @param $updateguild
     */
    private function UpdateDefaultGuild(Guilds $updateguild)
    {
        global $user, $request;
        $id = $request->variable('defaultguild', 0);
        $updateguild->update_guilddefault($id);
        $success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $id);
        trigger_error($success_message . $this->link, E_USER_NOTICE);
    }

    /**
     * @param $updateguild
     * @param $updateArmory
     * @return void
     */
    private function UpdateGuild(Guilds $updateguild, $updateArmory)
    {
        global $user, $request;

        $updateguild->guildid = $this->url_id;
        $updateguild->Getguild();
        $old_guild = new Guilds($this->url_id);
        $old_guild->Getguild();

        $updateguild->game_id = $request->variable('game_id', '');
        $updateguild->name = $request->variable('guild_name', '', true);
        $updateguild->realm = $request->variable('realm', '', true);
        $updateguild->region = $request->variable('region_id', ' ');
        $updateguild->showroster = $request->variable('showroster', 0);
        $updateguild->min_armory = $request->variable('min_armorylevel', 0);
        $updateguild->recstatus = $request->variable('switchon_recruitment', 0);
        $updateguild->armory_enabled = $request->variable('armory_enabled', 0);
        $updateguild->recruitforum = $request->variable('recruitforum', 0);

        //in the request we expect the file name here including extension, no path
        $updateguild->emblempath = $this->ext_path . "images/guildemblem/". $request->variable('guild_emblem', '', true);

        $updateguild->aionlegionid = 0;
        $updateguild->aionserverid = 0;

        $GuildAPIParameters= array();

        if($updateArmory)
        {
            $GuildAPIParameters = array('members');
        }

        if($updateguild->Guildupdate($old_guild, $GuildAPIParameters))
        {
            $success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $this->url_id);
            trigger_error($success_message . $this->link, E_USER_NOTICE);
        }
        else
        {
            $success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_FAILED'], $this->url_id);
            trigger_error($success_message . $this->link, E_USER_WARNING);
        }

    }

    /**
     *
     * delete a guild
     * @param $updateguild
     */
    private function DeleteGuild(Guilds $updateguild)
    {
        global $user, $template, $request;

        if (confirm_box(true))
        {
            $deleteguild = new Guilds($request->variable('guildid', 0));
            $deleteguild->Getguild();
            $deleteguild->Guildelete();
            $success_message = sprintf($user->lang['ADMIN_DELETE_GUILD_SUCCESS'], $deleteguild->guildid);
            trigger_error($success_message . $this->link, E_USER_NOTICE);

        }
        else
        {
            $s_hidden_fields = build_hidden_fields(array(
                'deleteguild' => true,
                'guildid' => $updateguild->guildid));

            $template->assign_vars(array(
                'S_HIDDEN_FIELDS' => $s_hidden_fields));

            confirm_box(false, $user->lang['CONFIRM_DELETE_GUILD'], $s_hidden_fields);

        }
    }

    /**
     * Add a guild rank
     * @param $updateguild
     */
    private function AddRank(Guilds $updateguild)
    {
        global $request, $user;

        $newrank = new Ranks($updateguild->guildid);
        $newrank->RankName = $request->variable('nrankname', '', true);
        $newrank->RankId = $request->variable('nrankid', 0);
        $newrank->RankGuild = $updateguild->guildid;
        $newrank->RankHide = $request->is_set_post('nhide');
        $newrank->RankPrefix = $request->variable('nprefix', '', true);
        $newrank->RankSuffix = $request->variable('nsuffix', '', true);
        $newrank->Makerank();
        $success_message = $user->lang['ADMIN_RANKS_ADDED_SUCCESS'];
        trigger_error($success_message . $this->link);
    }

    /**
     * Update a rank
     * @param $updateguild
     *
     * @return int|string
     */
    private function UpdateRank(Guilds $updateguild)
    {
        global $request, $user;
        $newrank = new Ranks($updateguild->guildid);
        $oldrank = new Ranks($updateguild->guildid);

        // template
        $modrank = $request->variable('ranks', array(0 => ''), true);
        foreach ($modrank as $rank_id => $rank_name)
        {
            $oldrank->RankId = $rank_id;
            $oldrank->RankGuild = $updateguild->guildid;
            $oldrank->Getrank();

            $newrank->RankId = $rank_id;
            $newrank->RankGuild = $oldrank->RankGuild;
            $newrank->RankName = $rank_name;
            $newrank->RankHide = (isset($_POST['hide'][$rank_id])) ? 1 : 0;

            $rank_prefix = $request->variable('prefix', array((int) $rank_id => ''), true);
            $newrank->RankPrefix = $rank_prefix[$rank_id];

            $rank_suffix = $request->variable('suffix', array((int) $rank_id => ''), true);
            $newrank->RankSuffix = $rank_suffix[$rank_id];

            // compare old with new,
            if ($oldrank != $newrank)
            {
                $newrank->Rankupdate($oldrank);
            }
        }
        $success_message = $user->lang['ADMIN_RANKS_UPDATE_SUCCESS'];
        trigger_error($success_message . $this->link);
        return $rank_id;
    }

    /**
     * delete a guild rank
     */
    private function DeleteRank()
    {
        global $request, $user;

        if (confirm_box(true))
        {
            $guildid = $request->variable('hidden_guildid', 0);
            $rank_id = $request->variable('hidden_rank_id', 999);
            $deleterank = new Ranks($guildid, $rank_id);
            $deleterank->Rankdelete(false);
        }
        else
        {
            // delete the rank only if there are no members left
            $rank_id = $request->variable('ranktodelete', 999);
            $guildid = $request->variable(URI_GUILD, 0);
            $old_guild = new Guilds($guildid);
            $deleterank = new Ranks($guildid, $rank_id);

            $s_hidden_fields = build_hidden_fields(array(
                'deleterank' => true,
                'hidden_rank_id' => $rank_id,
                'hidden_guildid' => $guildid
            ));

            confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_RANKS'], $deleterank->RankName, $old_guild->name), $s_hidden_fields);
        }
    }

    /**
     * @param $updateguild
     */
    private function BuildTemplateEditGuild($updateguild)
    {
        global $phpEx, $template, $db, $phpbb_admin_path, $user;

        foreach ($this->regions as $key => $regionname)
        {
            $template->assign_block_vars('region_row', array(
                'VALUE'    => $key,
                'SELECTED' => ($updateguild->region == $key) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($regionname)) ? $regionname : '(None)'));
        }

        if (isset($this->games))
        {
            foreach ($this->games as $key => $gamename)
            {
                $template->assign_block_vars('game_row', array(
                    'VALUE'    => $key,
                    'SELECTED' => ($updateguild->game_id == $key) ? ' selected="selected"' : '',
                    'OPTION'   => (!empty($gamename)) ? $gamename : '(None)'));
            }
        }
        else
        {
            trigger_error('ERROR_NOGAMES', E_USER_WARNING);
        }

        $game          = new Game;
        $game->game_id = $updateguild->game_id;
        $game->Get();


        $template->assign_vars(array(
            'F_ENABLGAMEEARMORY' => $game->getArmoryEnabled(),
            'F_ENABLEARMORY'     => $updateguild->armory_enabled,
            'RECRUITFORUM_OPTIONS' => make_forum_select ($updateguild->recruitforum, false, false, true ),
            'RECSTATUS'          => $updateguild->recstatus,
            'GAME_ID'            => $updateguild->game_id,
            'GUILDID'            => $updateguild->guildid,
            'GUILD_NAME'         => $updateguild->name,
            'REALM'              => $updateguild->realm,
            'REGION'             => $updateguild->region,
            'MEMBERCOUNT'        => $updateguild->membercount,
            'ARMORY_URL'         => $updateguild->guildarmoryurl,
            'MIN_ARMORYLEVEL'    => $updateguild->min_armory,
            'SHOW_ROSTER'        => ($updateguild->showroster == 1) ? 'checked="checked"' : '',
            'ARMORYSTATUS'       => $updateguild->armoryresult,
            // Language
            'L_TITLE'            => $user->lang['ACP_EDITGUILD'],
            'L_EXPLAIN'          => $user->lang['ACP_EDITGUILD_EXPLAIN'],
            'L_EDIT_GUILD_TITLE'  => $user->lang['EDIT_GUILD'],
            // Javascript messages
            'MSG_NAME_EMPTY'     => $user->lang['FV_REQUIRED_NAME'],
            'EMBLEM'             => $updateguild->emblempath,
            'EMBLEMFILE'         => basename($updateguild->emblempath),

            'U_EDIT_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", '"i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=editguild&amp;' . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRANKS' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildranks&amp;'. URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRECRUITMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildrecruitment&amp;' . URI_GUILD . '=' . $updateguild->guildid)
        ));

        // extra
        if ($updateguild->game_id == 'wow')
        {
            $template->assign_vars(array(
                'S_WOW'  => true,
                'ARMORY' => $updateguild->guildarmoryurl,
                'ACHIEV' => $updateguild->achievementpoints,
            ));
        }

        $form_key = 'editguild';
        add_form_key($form_key);
        $this->page_title = $user->lang['ACP_EDITGUILD'];
    }

    /**
     * list the ranks for this guild
     * @param $updateguild
     */
    private function BuildTemplateEditGuildRanks($updateguild)
    {
        global $phpEx, $template, $db, $phpbb_admin_path, $user;
        // everything from rank 90 is readonly
        $listranks          = new Ranks($updateguild->guildid);
        $listranks->game_id = $updateguild->game_id;
        $result             = $listranks->listranks();
        while ($row = $db->sql_fetchrow($result))
        {
            $prefix = $row['rank_prefix'];
            $suffix = $row['rank_suffix'];
            $template->assign_block_vars('ranks_row', array(
                'RANK_ID'       => $row['rank_id'],
                'RANK_NAME'     => $row['rank_name'],
                'RANK_PREFIX'   => $prefix,
                'RANK_SUFFIX'   => $suffix,
                'HIDE_CHECKED'  => ($row['rank_hide'] == 1) ? 'checked="checked"' : '',
                'S_READONLY'    => ($row['rank_id'] >= 90) ? true : false,
                'U_DELETE_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;deleterank=1&amp;ranktodelete=' . $row['rank_id'] . "&amp;" . URI_GUILD . "=" . $updateguild->guildid)
            ));
        }
        $db->sql_freeresult($result);

        $game          = new Game;
        $game->game_id = $updateguild->game_id;
        $game->Get();

        $template->assign_vars(array(
            // Form values
            'S_GUILDLESS'        => ($updateguild->guildid == 0) ? true : false,
            'F_ENABLGAMEEARMORY' => $game->getArmoryEnabled(),
            'F_ENABLEARMORY'     => $updateguild->armory_enabled,
            'GAME_ID'            => $updateguild->game_id,
            'GUILDID'            => $updateguild->guildid,
            'GUILD_NAME'         => $updateguild->name,
            'U_ADD_RANK'         => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;addrank=1&amp;guild=' . $updateguild->guildid),
            // Language
            'L_TITLE'            => ($this->url_id < 0) ? $user->lang['ACP_ADDGUILD'] : $user->lang['ACP_EDITGUILD'],
            'L_EXPLAIN'          => ($this->url_id < 0) ? $user->lang['ACP_ADDGUILD_EXPLAIN'] : $user->lang['ACP_EDITGUILD_EXPLAIN'],
            'L_ADD_GUILD_TITLE'  => ($this->url_id < 0) ? $user->lang['ADD_GUILD'] : $user->lang['EDIT_GUILD'],
            // Javascript messages
            'MSG_NAME_EMPTY'     => $user->lang['FV_REQUIRED_NAME'],
            'EMBLEM'             => $updateguild->emblempath,
            'EMBLEMFILE'         => basename($updateguild->emblempath),
            'S_ADD'              => ($this->url_id < 0) ? true : false,
            'U_EDIT_GUILD'              => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=editguild&amp;' . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRANKS'         => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildranks&amp;' . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRECRUITMENT'   => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;action=guildrecruitment&amp;' . URI_GUILD . '=' . $updateguild->guildid)
        ));

        $form_key = 'editguildranks';
        add_form_key($form_key);
        $this->page_title = $user->lang['ACP_EDITGUILD'];
    }

    /**
     *
     * list the guilds
     */
    private function BuildTemplateListGuilds()
    {
        global $user, $template, $phpbb_admin_path, $phpEx, $db, $request;
        if (count($this->games) == 0)
        {
            trigger_error($user->lang['ERROR_NOGAMES'], E_USER_WARNING);
        }

        $updateguild = new Guilds();
        $guildlist   = $updateguild->guildlist(1);
        foreach ($guildlist as $g)
        {
            $template->assign_block_vars('defaultguild_row', array(
                'VALUE'    => $g['id'],
                'SELECTED' => ($g['guilddefault'] == '1') ? ' selected="selected"' : '',
                'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)'));
        }
        $guilddefaultupdate = (isset($_POST['upddefaultguild'])) ? true : false;
        if ($guilddefaultupdate)
        {
            $this->UpdateDefaultGuild($updateguild);
        }
        $guildadd = (isset($_POST['addguild'])) ? true : false;
        if ($guildadd)
        {
            redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=addguild'));
        }

        $sort_order = array(
            0 => array('id', 'id desc'),
            1 => array('name', 'name desc'),
            2 => array('realm desc', 'realm desc'),
            3 => array('region', 'region desc'),
            4 => array('roster', 'roster desc'));
        $current_order   = $this->switch_order($sort_order);
        $guild_count     = 0;
        $previous_data   = '';
        $sort_index      = explode('.', $current_order['uri']['current']);
        $previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
        $show_all        = ((isset($_GET['show'])) && $request->variable('show', '') == 'all') ? true : false;

        $sql = 'SELECT id, name, realm, region, roster, game_id FROM ' . GUILD_TABLE . ' WHERE id > 0 ORDER BY ' . $current_order['sql'];
        if (!($guild_result = $db->sql_query($sql)))
        {
            trigger_error($user->lang['ERROR_GUILDNOTFOUND'], E_USER_WARNING);
        }
        $lines = 0;
        while ($row = $db->sql_fetchrow($guild_result))
        {
            $guild_count++;
            $listguild = new Guilds($row['id']);
            $template->assign_block_vars('guild_row', array(
                    'ID'           => $listguild->guildid,
                    'NAME'         => $listguild->name,
                    'REALM'        => $listguild->realm,
                    'REGION'       => $listguild->region,
                    'GAME'         => $listguild->game_id,
                    'MEMBERCOUNT'  => $listguild->membercount,
                    'SHOW_ROSTER'  => ($listguild->showroster == 1 ? 'yes' : 'no'),
                    'U_VIEW_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=editguild&amp;' . URI_GUILD . '=' . $listguild->guildid)
                )
            );
            $previous_data = $row[$previous_source];
        }
        $form_key = 'listguilds';
        add_form_key($form_key);
        $template->assign_vars(array(
            'U_GUILDLIST'            => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module') . '&amp;mode=listguilds',
            'U_ADDGUILD'             => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module') . '&amp;mode=addguild',
            'U_GUILD'                => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module') . '&amp;mode=editguild',
            'L_TITLE'                => $user->lang['ACP_LISTGUILDS'],
            'L_EXPLAIN'              => $user->lang['ACP_LISTGUILDS_EXPLAIN'],
            'BUTTON_VALUE'           => $user->lang['DELETE_SELECTED_GUILDS'],
            'O_ID'                   => $current_order['uri'][0],
            'O_NAME'                 => $current_order['uri'][1],
            'O_REALM'                => $current_order['uri'][2],
            'O_REGION'               => $current_order['uri'][3],
            'O_ROSTER'               => $current_order['uri'][4],
            'U_LIST_GUILD'           => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_guild_module&amp;mode=listguilds'),
            'GUILDMEMBERS_FOOTCOUNT' => sprintf($user->lang['GUILD_FOOTCOUNT'], $guild_count)));
        $this->page_title = 'ACP_LISTGUILDS';
    }
}
