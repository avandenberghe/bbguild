<?php
/**
 * Recruitment ACP file
 *
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;

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

//include the Recruitment class
if (!class_exists('\bbdkp\controller\guilds\Recruitment'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Recruitment.$phpEx");
}
use \bbdkp\controller\guilds\Guilds;

/**
 * This class manages guilds
 *
 *   @package bbdkp
 */
class dkp_recruit_module extends \bbdkp\admin\Admin
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
     *
     */
    private  $apply_installed;

    /**
     * main acp function
     * @param integer $id
     * @param string $mode
     */
    public function main ($id, $mode)
    {
        global $user, $config, $template, $db, $phpbb_admin_path, $phpEx;
        $this->tpl_name = 'dkp/acp_' . $mode;
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_recruit&amp;mode=listrecruit") . '"><h3>'.$user->lang['RETURN_RECLIST'].'</h3></a>';

        $this->apply_installed = false;
        $plugin_versioninfo = (array) parent::get_plugin_info(request_var('versioncheck_force', false));

        if(isset($plugin_versioninfo['apply']))
        {
            $this->apply_installed = true;
        }

        $guild_id = request_var(URI_GUILD, 1);
        $Guild = new \bbdkp\controller\guilds\Guilds();
        $guildlist   = $Guild->guildlist(1);
        foreach ($guildlist as $g)
        {
            $template->assign_block_vars('guild_row', array(
                'VALUE'    => $g['id'],
                'SELECTED' => ($guild_id == $g['id']) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)'));
        }
        $Guild->guildid= $guild_id;
        $Guild->Getguild();

        $template->assign_vars(array(
            'APPLY_INSTALLED'       => $this->apply_installed ? 1 : 0,
            'GUILD_EMBLEM'          => $Guild->emblempath,
            'U_VIEW_GUILD'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=editguild&amp;" . URI_GUILD . '=' . $Guild->guildid),
            'U_ADDRECRUIT'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_recruit&amp;mode=addrecruit&amp;" . URI_GUILD . '=' . $Guild->guildid),
            'U_RECRUITLIST'         => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_recruit&amp;mode=listrecruit&amp;" . URI_GUILD . '=' . $Guild->guildid),
            'U_EDITRECRUIT'         => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_recruit&amp;mode=editrecruit"),
            'U_LIST_GUILD'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds"),
        ));

        switch ($mode)
        {
            /***************************************
             * List recruitments
             ***************************************/
            case 'listrecruit':

                $this->BuildTemplateListRecruits($guild_id );
                break;

            /*************************************
             *  Add recruit
             *************************************/
            case 'addrecruit':

                $recruit = new \bbdkp\controller\guilds\Recruitment();
                $recruit->setGuildId($Guild->guildid);
                $recruit->setLastUpdate($this->time);

                $add = (isset($_POST['add'])) ? true : false;
                $update = (isset($_POST['update'])) ? true : false;

                $action = request_var('action', '');

                if($this->apply_installed)
                {
                    //if apply is installed then fetch list of templates
                    $result = $db->sql_query ( 'SELECT * FROM ' . APPTEMPLATELIST_TABLE );
                    $templates = array();
                    while ( $row = $db->sql_fetchrow ( $result ) )
                    {
                        $templates[$row ['template_id']] = $row ['template_name'];
                    }
                    $db->sql_freeresult ( $result );
                }


                if($action=='delete')
                {
                    $recruit->id = request_var('id', 0);
                    $recruit->get($recruit->id);
                    $recruit->delete();

                    $success_message = sprintf($user->lang['ADMIN_DELETE_RECRUITMENT_SUCCESS'], $recruit->id);
                    trigger_error($success_message . $this->link, E_USER_WARNING);

                }
                elseif ($action=='edit')
                {
                    $recruit->id = request_var('id', 0);
                    $recruit->get($recruit->id);

                    $template->assign_vars(array(
                            'S_UPDATE'              => true,
                            'RECRUIT_ID'            => $recruit->id,
                            'RECSTATUS'             => $recruit->getStatus() == '1' ? 'checked="checked"' : '',
                            'RECRUIT_STATUS'        => $recruit->getStatus() == '1' ? $user->lang['RECRUIT_OPEN'] : $user->lang['RECRUIT_CLOSED'],
                            'NUMPOSITIONS'          => $recruit->getPositions(),
                            'APPLICANTS'            => $recruit->getApplicants(),
                            'RECRUIT_LEVEL'         => $recruit->getLevel(),
                            'APPLICANTS'            => $recruit->getApplicants(),
                            'NOTE'                  => $recruit->getNote(),
                        )
                    );
                }
                else
                {
                    //add
                    $template->assign_vars(array(
                        'RECSTATUS'             => 'checked="checked"',
                        'S_ADD'                 => true,
                        'NUMPOSITIONS'          => '1',
                        'RECRUIT_LEVEL'         => $Guild->min_armory,
                        )
                    );

                }

                if($this->apply_installed)
                {
                    foreach($templates as $template_id => $value)
                    {
                        $template->assign_block_vars('applytemplates_row', array(
                            'VALUE'    => $template_id,
                            'SELECTED' => ($template_id ==  $recruit->getApplytemplateid()) ? ' selected="selected"' : '',
                            'OPTION'   => $value
                        ));

                    }
                }

                if ($add || $update)
                {

                    if (!check_form_key('addrecruit'))
                    {
                        trigger_error('FORM_INVALID');
                    }

                    $recruit->id = request_var('hidden_recruit_id', 0);
                    $recruit->role_id = request_var('role', 0);
                    $recruit->setClassId(request_var('class_id', 0));
                    $recruit->setPositions(request_var('numpositions', 0));
                    $recruit->setApplicants(request_var('applicants', 0));
                    $recruit->setStatus(request_var('recruitstatus', '') == 'on' ? 1 : 0 );
                    $recruit->setLevel(request_var('recruit_level', 0));
                    $recruit->setNote(utf8_normalize_nfc(request_var('note', '', true)));
                    $recruit->setApplytemplateid(request_var('applytemplateid', 1));

                }

                if ($add)
                {
                    $recruit->Make();

                    $success_message = sprintf($user->lang['ADMIN_ADD_RECRUITMENT_SUCCESS'], $recruit->id);
                    trigger_error($success_message . $this->link, E_USER_NOTICE);

                }
                elseif($update)
                {
                    $recruit->update();
                    $success_message = sprintf($user->lang['ADMIN_UPDATE_RECRUITMENT_SUCCESS'], $recruit->id);
                    trigger_error($success_message . $this->link, E_USER_NOTICE);

                }
                else
                {
                    $this->BuildDropDowns($Guild, $recruit);
                }



                $form_key = 'addrecruit';
                add_form_key($form_key);


                $this->page_title = $user->lang['ACP_ADDRECRUITS'];
                break;

            default:
                $this->page_title = 'ACP_DKP_MAINPAGE';
                $success_message = 'Error';
                trigger_error($success_message . $this->link, E_USER_WARNING);
        }
    }

    /**
     * list the recruitments
     * @param $guild_id
     *
     */
    private function BuildTemplateListRecruits($guild_id)
    {
        global $user, $template, $phpbb_root_path, $phpbb_admin_path, $phpEx, $db;
        if (count($this->games) == 0)
        {
            trigger_error($user->lang['ERROR_NOGAMES'], E_USER_WARNING);
        }

        $recruits = new \bbdkp\controller\guilds\Recruitment();
        $recruits->setGuildId($guild_id);
        $result   = $recruits->ListRecruitments(1);
        $recruit_count= 0;
        while ($row = $db->sql_fetchrow($result))
        {
            $recruit_count++;
            $template->assign_block_vars('recruit_row', array(
                    'ID'                => $row['id'],
                    'GUILD_ID'          => $row['guild_id'],
                    'ROLE_ID'           => $row['role_id'],
                    'CLASS_ID'          => $row['class_id'],
                    'CLASS_NAME'        => $row['class_name'],
                    'COLOR_CODE'        => $row['colorcode'],
                    'CLASS_IMAGE'       => $row['imagename'],
                    'S_CLASS_IMAGE_EXISTS' => (strlen ( $row ['imagename'] ) > 1) ? true : false,
                    'CLASS_IMAGE'       => (strlen ( $row ['imagename'] ) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row ['imagename'] . ".png" : '',
                    'POSITIONS'         => $row['positions'],
                    'APPLICANTS'        => $row['applicants'],
                    'STATUS'            => $row['status'] == '1' ? $user->lang['RECRUIT_OPEN'] : $user->lang['RECRUIT_CLOSED'],
                    'NOTE'              => $row['note'],
                    'ROLE_COLOR'        => $row['role_color'],
                    'ROLE_NAME'         => $row['role_name'],
                    'APPLYTEMPLATE_ID'  => $row['applytemplate_id'],
                    'U_DELETE_RECRUIT'  => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_recruit&amp;mode=addrecruit&amp;action=delete&amp;id=' . $row['id']),
                    'U_VIEW_RECRUIT'    => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_recruit&amp;mode=addrecruit&amp;action=edit&amp;id=' . $row['id']),
                )
            );

        }

        // if apply is installed insert an extra column in recruitment listing to indicate the template to be used for that recruitment.
        $template->assign_vars(array(
            'RECRUIT_FOOTCOUNT'     => sprintf($user->lang['RECRUIT_FOOTCOUNT'], $recruit_count),
        ));
        $this->page_title = 'ACP_LISTRECRUITS';
    }

    /**
     * @param $Guild
     * @param $recruit
     */
    private function BuildDropDowns($Guild, $recruit)
    {
        global $config, $db, $template;

        // Class dropdown
        // reloading is done from ajax to prevent redraw
        $sql_array = array(
            'SELECT'   => ' c.class_id, l.name as class_name, c.class_hide,
									  c.class_min_level, class_max_level, c.class_armor_type, c.imagename ',
            'FROM'     => array(
                CLASS_TABLE => 'c',
                BB_LANGUAGE => 'l'),
            'WHERE'    => " l.game_id = c.game_id  AND c.game_id = '" . $Guild->game_id . "'
					AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",
            'ORDER_BY' => 'l.name asc'
        );
        $sql    = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result))
        {
            $template->assign_block_vars('class_row', array(
                'VALUE'    => $row['class_id'],
                'SELECTED' => ($recruit->getClassId() == $row['class_id']) ? ' selected="selected"' : '',
                'OPTION'   => $row['class_name']));
        }
        $db->sql_freeresult($result);
        // get roles
        $Roles           = new \bbdkp\controller\games\Roles();
        $Roles->game_id  = $Guild->game_id;
        $Roles->guild_id = $Guild->guildid;
        $listroles       = $Roles->listroles();
        foreach ($listroles as $roleid => $Role)
        {
            $template->assign_block_vars('role_row', array(
                'VALUE'    => $Role['role_id'],
                'SELECTED' => ($recruit->role_id == $Role['role_id']) ? ' selected="selected"' : '',
                'OPTION'   => $Role['rolename']));
        }
    }
}

