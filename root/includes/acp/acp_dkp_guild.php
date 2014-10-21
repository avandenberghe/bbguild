<?php
/**
 * Guild ACP file
 *
 * @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4
 */
// don't add this file to namespace bbdkp
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
    exit();
}
if (! defined('EMED_BBDKP'))
{
    $user->add_lang(array('mods/dkp_admin'));
    trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}

// Include the base class
if (!class_exists('\bbdkp\admin\Admin'))
{
    require("{$phpbb_root_path}includes/bbdkp/admin/admin.$phpEx");
}

// include ranks class
if (!class_exists('\bbdkp\controller\guilds\Ranks'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Ranks.$phpEx");
}

if (!class_exists('\bbdkp\controller\games\Game'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/games/Game.$phpEx");
}

//include the guilds class
if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}

use \bbdkp\controller\guilds\Guilds;

/**
 * This class manages guilds
 *
 *   @package bbdkp
 */
class acp_dkp_guild extends \bbdkp\admin\Admin
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
     * current rul
     * @var string
     */
    public  $url_id;

    /**
     * main acp function
     * @param integer $id
     * @param string $mode
     */
    public function main ($id, $mode)
    {
        global $user, $template, $db, $phpbb_admin_path, $phpEx;
        $this->tpl_name = 'dkp/acp_' . $mode;
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds") . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';

        switch ($mode)
        {

            /***************************************
             List Guilds
            ***************************************/
            case 'listguilds':

                $this->BuildTemplateListGuilds();
                break;

            /*************************************
             *  Add Guild
             *************************************/
            case 'addguild':

                $addguild = new Guilds();

                $add = (isset($_POST['newguild'])) ? true : false;
                if ($add)
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

                $form_key = 'addguild';
                add_form_key($form_key);

                break;

            /*************************************
             *  Edit Guild
             *************************************/
            case 'editguild':

                $this->url_id = request_var(URI_GUILD, 0);
                $updateguild = new Guilds($this->url_id);
                $memberadd = (isset($_POST['memberadd'])) ? true : false;
                if ($memberadd)
                {
                    redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addmember&amp;" . URI_GUILD . "=" . $this->url_id  ));
                }

                $action = request_var('action', '');
                switch($action)
                {
                    case 'guildranks':
                        $updaterank = (isset($_POST['updaterank'])) ? true : false;
                        $deleterank = (isset($_GET['deleterank'])) ? true : false;
                        $addrank = (isset($_POST['addrank'])) ? true : false;
                        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildranks&amp;" . URI_GUILD . '=' . $updateguild->guildid) . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
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
                        $submit = (isset($_POST['updateguild'])) ? true : false;
                        $delete = (isset($_POST['deleteguild'])) ? true : false;
                        $armory = (isset($_POST['armory'])) ? true : false;
                        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildedit&amp;" . URI_GUILD . '=' . $updateguild->guildid) . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
                        // POST check

                        if ($submit)
                        {
                            if (! check_form_key('editguild'))
                            {
                                trigger_error('FORM_INVALID');
                            }
                        }

                        if ($submit)
                        {
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
     * @param $addguild
     */
    private function AddGuild(\bbdkp\controller\guilds\Guilds $addguild)
    {
        global $user;

        if (!check_form_key('addguild')) {
            trigger_error('FORM_INVALID');
        }

        $addguild->name = utf8_normalize_nfc(request_var('guild_name', '', true));
        $addguild->realm = utf8_normalize_nfc(request_var('realm', '', true));
        $addguild->region = request_var('region_id', '');
        $addguild->game_id = request_var('game_id', '');
        $addguild->showroster = (isset($_POST['showroster'])) ? true : false;
        $addguild->min_armory = request_var('min_armorylevel', 0);
        $addguild->armory_enabled = request_var('armory_enabled', 0);
        $addguild->recstatus = request_var('switchon_recruitment', 0);

        if ($addguild->MakeGuild() == true)
        {
            $addguild->Guildupdate($addguild, array());
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
    private function UpdateDefaultGuild(\bbdkp\controller\guilds\Guilds $updateguild)
    {
        global $user;
        $id = request_var('defaultguild', 0);
        $updateguild->update_guilddefault($id);
        $success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $id);
        trigger_error($success_message . $this->link, E_USER_NOTICE);
    }

    /**
     * @param $updateguild
     * @param $updateArmory
     * @return void
     */
    private function UpdateGuild(\bbdkp\controller\guilds\Guilds $updateguild, $updateArmory)
    {
        global $user;

        $updateguild->guildid = $this->url_id;
        $updateguild->Getguild();
        $old_guild = new \bbdkp\controller\guilds\Guilds($this->url_id);
        $old_guild->Getguild();

        $updateguild->game_id = request_var('game_id', '');
        $updateguild->name = utf8_normalize_nfc(request_var('guild_name', '', true));
        $updateguild->realm = utf8_normalize_nfc(request_var('realm', '', true));
        $updateguild->region = request_var('region_id', ' ');
        $updateguild->showroster = request_var('showroster', 0);
        $updateguild->min_armory = request_var('min_armorylevel', 0);
        $updateguild->recstatus = request_var('switchon_recruitment', 0);
        $updateguild->armory_enabled = request_var('armory_enabled', 0);

        //in the request we expect the file name here including extension, no path
        $updateguild->emblempath = "images/bbdkp/guildemblem/". utf8_normalize_nfc(request_var('guild_emblem', '', true));

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
    private function DeleteGuild(\bbdkp\controller\guilds\Guilds $updateguild)
    {

        global $user, $template;
        if (confirm_box(true))
        {
            $deleteguild = new \bbdkp\controller\guilds\Guilds(request_var('guildid', 0));
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
    private function AddRank(\bbdkp\controller\guilds\Guilds $updateguild)
    {
        global $user;

        $newrank = new \bbdkp\controller\guilds\Ranks($updateguild->guildid);
        $newrank->RankName = utf8_normalize_nfc(request_var('nrankname', '', true));
        $newrank->RankId = request_var('nrankid', 0);
        $newrank->RankGuild = $updateguild->guildid;
        $newrank->RankHide = (isset($_POST['nhide'])) ? 1 : 0;
        $newrank->RankPrefix = utf8_normalize_nfc(request_var('nprefix', '', true));
        $newrank->RankSuffix = utf8_normalize_nfc(request_var('nsuffix', '', true));
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
    private function UpdateRank(\bbdkp\controller\guilds\Guilds $updateguild)
    {
        global $user;
        $newrank = new \bbdkp\controller\guilds\Ranks($updateguild->guildid);
        $oldrank = new \bbdkp\controller\guilds\Ranks($updateguild->guildid);

        // template
        $modrank = utf8_normalize_nfc(request_var('ranks', array(0 => ''), true));
        foreach ($modrank as $rank_id => $rank_name)
        {
            $oldrank->RankId = $rank_id;
            $oldrank->RankGuild = $updateguild->guildid;
            $oldrank->Getrank();

            $newrank->RankId = $rank_id;
            $newrank->RankGuild = $oldrank->RankGuild;
            $newrank->RankName = $rank_name;
            $newrank->RankHide = (isset($_POST['hide'][$rank_id])) ? 1 : 0;

            $rank_prefix = utf8_normalize_nfc(request_var('prefix', array((int) $rank_id => ''), true));
            $newrank->RankPrefix = $rank_prefix[$rank_id];

            $rank_suffix = utf8_normalize_nfc(request_var('suffix', array((int) $rank_id => ''), true));
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
        global $user;

        if (confirm_box(true))
        {
            $guildid = request_var('hidden_guildid', 0);
            $rank_id = request_var('hidden_rank_id', 999);
            $deleterank = new \bbdkp\controller\guilds\Ranks($guildid, $rank_id);
            $deleterank->Rankdelete(false);
        }
        else
        {
            // delete the rank only if there are no members left
            $rank_id = request_var('ranktodelete', 999);
            $guildid = request_var(URI_GUILD, 0);
            $old_guild = new \bbdkp\controller\guilds\Guilds($guildid);
            $deleterank = new \bbdkp\controller\guilds\Ranks($guildid, $rank_id);

            $s_hidden_fields = build_hidden_fields(array(
                'deleterank' => true,
                'hidden_rank_id' => $rank_id,
                'hidden_guildid' => $guildid
            ));

            confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_RANKS'],
                $deleterank->RankName, $old_guild->name), $s_hidden_fields);
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

        $game          = new \bbdkp\controller\games\Game;
        $game->game_id = $updateguild->game_id;
        $game->Get();

        $template->assign_vars(array(
            'F_ENABLGAMEEARMORY' => $game->getArmoryEnabled(),
            'F_ENABLEARMORY'     => $updateguild->armory_enabled,

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

            'U_EDIT_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=editguild&amp;" . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRANKS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildranks&amp;" . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRECRUITMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildrecruitment&amp;" . URI_GUILD . '=' . $updateguild->guildid)
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
        $listranks          = new \bbdkp\controller\guilds\Ranks($updateguild->guildid);
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
                'U_DELETE_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;deleterank=1&amp;ranktodelete=" . $row['rank_id'] . "&amp;" . URI_GUILD . "=" . $updateguild->guildid)
            ));
        }
        $db->sql_freeresult($result);

        $game          = new \bbdkp\controller\games\Game;
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
            'U_ADD_RANK'         => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;addrank=1&amp;guild=" . $updateguild->guildid),
            // Language
            'L_TITLE'            => ($this->url_id < 0) ? $user->lang['ACP_ADDGUILD'] : $user->lang['ACP_EDITGUILD'],
            'L_EXPLAIN'          => ($this->url_id < 0) ? $user->lang['ACP_ADDGUILD_EXPLAIN'] : $user->lang['ACP_EDITGUILD_EXPLAIN'],
            'L_ADD_GUILD_TITLE'  => ($this->url_id < 0) ? $user->lang['ADD_GUILD'] : $user->lang['EDIT_GUILD'],
            // Javascript messages
            'MSG_NAME_EMPTY'     => $user->lang['FV_REQUIRED_NAME'],
            'EMBLEM'             => $updateguild->emblempath,
            'EMBLEMFILE'         => basename($updateguild->emblempath),
            'S_ADD'              => ($this->url_id < 0) ? true : false,
            'U_EDIT_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=editguild&amp;" . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRANKS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildranks&amp;" . URI_GUILD . '=' . $updateguild->guildid),
            'U_EDIT_GUILDRECRUITMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;action=guildrecruitment&amp;" . URI_GUILD . '=' . $updateguild->guildid)
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
        global $user, $template, $phpbb_admin_path, $phpEx, $db;
        if (count($this->games) == 0)
        {
            trigger_error($user->lang['ERROR_NOGAMES'], E_USER_WARNING);
        }
        $updateguild = new \bbdkp\controller\guilds\Guilds();
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
            redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=addguild"));
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
        $show_all        = ((isset($_GET['show'])) && request_var('show', '') == 'all') ? true : false;
        $sql = 'SELECT id, name, realm, region, roster, game_id FROM ' . GUILD_TABLE . ' WHERE id > 0 ORDER BY ' . $current_order['sql'];
        if (!($guild_result = $db->sql_query($sql)))
        {
            trigger_error($user->lang['ERROR_GUILDNOTFOUND'], E_USER_WARNING);
        }
        $lines = 0;
        while ($row = $db->sql_fetchrow($guild_result))
        {
            $guild_count++;
            $listguild = new \bbdkp\controller\guilds\Guilds($row['id']);
            $template->assign_block_vars('guild_row', array(
                    'ID'           => $listguild->guildid,
                    'NAME'         => $listguild->name,
                    'REALM'        => $listguild->realm,
                    'REGION'       => $listguild->region,
                    'GAME'         => $listguild->game_id,
                    'MEMBERCOUNT'  => $listguild->membercount,
                    'SHOW_ROSTER'  => ($listguild->showroster == 1 ? 'yes' : 'no'),
                    'U_VIEW_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;" . URI_GUILD . '=' . $listguild->guildid)
                )
            );
            $previous_data = $row[$previous_source];
        }
        $form_key = 'listguilds';
        add_form_key($form_key);
        $template->assign_vars(array(
            'U_GUILDLIST'            => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild") . '&amp;mode=listguilds',
            'U_ADDGUILD'             => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild") . '&amp;mode=addguild',
            'U_GUILD'                => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild") . '&amp;mode=editguild',
            'L_TITLE'                => $user->lang['ACP_LISTGUILDS'],
            'L_EXPLAIN'              => $user->lang['ACP_LISTGUILDS_EXPLAIN'],
            'BUTTON_VALUE'           => $user->lang['DELETE_SELECTED_GUILDS'],
            'O_ID'                   => $current_order['uri'][0],
            'O_NAME'                 => $current_order['uri'][1],
            'O_REALM'                => $current_order['uri'][2],
            'O_REGION'               => $current_order['uri'][3],
            'O_ROSTER'               => $current_order['uri'][4],
            'U_LIST_GUILD'           => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds"),
            'GUILDMEMBERS_FOOTCOUNT' => sprintf($user->lang['GUILD_FOOTCOUNT'], $guild_count)));
        $this->page_title = 'ACP_LISTGUILDS';
    }
}

