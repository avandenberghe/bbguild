<?php
/**
 * members acp file
 *
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @author Ippehe, Malfate, Sajaki
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbguild\acp;
use sajaki\bbguild\model\admin\Admin;
use sajaki\bbguild\model\player\Members;
use sajaki\bbguild\model\player\Guilds;
use sajaki\bbguild\model\games\rpg\Roles;
use sajaki\bbguild\model\player\Ranks;

/**
 * This class manages member general info
 *
 * @package sajaki\bbguild\acp
 */
class mm_module extends Admin
{
    /**
     * trigger link
     * @var string
     */
    public $link = '';

    protected $phpbb_container;
    /** @var \phpbb\request\request **/
    protected $request;
    /** @var \phpbb\template\template **/
    protected $template;
    /** @var \phpbb\user **/
    protected $user;
    /** @var \phpbb\db\driver\driver_interface */
    protected $db;

    public $id;
    public $mode;

    public function main ($id, $mode)
    {
        global $user, $db, $template, $phpbb_admin_path, $phpEx;
        global $request, $phpbb_container;

        $this->id = $id;
        $this->mode = $mode;
        $this->request=$request;
        $this->template=$template;
        $this->user=$user;
        $this->db=$db;
        $this->phpbb_container = $phpbb_container;

        parent::__construct();

        $form_key = 'sajaki/bbguild';
        add_form_key($form_key);
        $this->tpl_name   = 'acp_' . $mode;

        switch ($mode)
        {
            /**
             * List members
             */
            case 'mm_listmembers':

                $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx",'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers') . '"><h3>Return to Index</h3></a>';
                $Guild = new Guilds();

                $guildlist = $Guild->guildlist(1);
                if( count((array) $guildlist) == 0  )
                {
                    trigger_error('ERROR_NOGUILD', E_USER_WARNING );
                }

                if( count((array) $guildlist) == 1 )
                {
                    $Guild->guildid = $guildlist[0]['id'];
                    $Guild->name = $guildlist[0]['name'];
                    if ($Guild->guildid == 0 && $Guild->name == 'Guildless' )
                    {
                        trigger_error('ERROR_NOGUILD', E_USER_WARNING );
                    }
                }

                foreach ($guildlist as $g)
                {
                    $Guild->guildid = $g['id'];
                    break;
                }

                $activate = $this->request->is_set_post('deactivate');
                if ($activate)
                {
                    $this->ActivateList();
                }

                // batch delete
                $del_batch = $this->request->is_set_post('delete');
                if ($del_batch)
                {
                    $this->member_batch_delete();
                }

                // guild dropdown query
                $getguild_dropdown = isset ( $_POST ['member_guild_id'] )  ? true : false;
                if ($getguild_dropdown)
                {
                    // user selected dropdown - get guildid
                    $Guild->guildid = $this->request->variable('member_guild_id', 0);
                }

                $sortlink = isset ( $_GET [URI_GUILD] )  ? true : false;
                if($sortlink)
                {
                    // user selected dropdown - get guildid
                    $Guild->guildid = $this->request->variable(URI_GUILD, 0);
                }

                $charapicall = $this->request->is_set_post('charapicall');
                if($charapicall)
                {
                    if (confirm_box(true))
                    {
                        list($i, $log) = $this->CallCharacterAPI();
                        trigger_error(sprintf($this->user->lang['CHARAPIDONE'], $i, $log), E_USER_NOTICE);
                    }
                    else
                    {
                        $s_hidden_fields = build_hidden_fields(array(
                            'charapicall' => true ,
                            'hidden_guildid' => $this->request->variable('member_guild_id', 0),
                            'hidden_minlevel' => $this->request->variable('hidden_minlevel', $this->request->variable('minlevel', 0)),
                            'hidden_maxlevel' => $this->request->variable('maxlevel', $this->request->variable('hidden_maxlevel', 200)),
                            'hidden_active' => $this->request->variable('active', $this->request->variable('hidden_active', 0)),
                            'hidden_nonactive' => $this->request->variable('nonactive', $this->request->variable('hidden_nonactive', 0)),
                            'hidden_member_name' => $this->request->variable('member_name', $this->request->variable('hidden_member_name', '', true), true)
                        ));
                        confirm_box(false, $this->user->lang['WARNING_BATTLENET'], $s_hidden_fields);

                    }
                }

                // add member button redirect
                $showadd = $this->request->is_set_post('memberadd');
                if ($showadd)
                {
                    $a = $this->request->variable('member_guild_id', $this->request->variable('hidden_guildid', 0));
                    redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_addmember&amp;guild_id=' . $a ));
                    break;
                }

                // pageloading
                $this->BuildTemplateListMembers($mode, $Guild);
                break;

            /***************************************/
            // add member
            /***************************************/
            case 'mm_addmember' :

                $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers') . '"><h3>' . $this->user->lang['RETURN_MEMBERLIST'] . '</h3></a>';

                $add = $this->request->is_set_post('add');
                $update = $this->request->is_set_post('update');
                $delete = $this->request->variable('delete', '')  != '' ? true : false;

                if ($add || $update)
                {
                    if (! check_form_key('sajaki/bbguild'))
                    {
                        trigger_error('FORM_INVALID');
                    }
                }

                if ($add)
                {
                    $this->Addmember();

                }

                if ($update)
                {
                    $this->UpdateMember();
                }

                if ($delete)
                {
                    if (confirm_box(true))
                    {
                        $deletemember = $this->DeleteMember();
                    }
                    else
                    {
                        $deletemember = new Members();
                        $deletemember->member_id = $this->request->variable('member_id', 0);
                        $deletemember->Getmember();
                        $s_hidden_fields = build_hidden_fields(array(
                            'delete' => true ,
                            'del_member_id' => $deletemember->member_id));

                        confirm_box(false, sprintf($this->user->lang['CONFIRM_DELETE_MEMBER'], $deletemember->member_name), $s_hidden_fields);
                    }
                    unset($deletemember);
                }

                $this->BuildTemplateAddEditmembers($mode);
                break;

            default:
                $this->page_title = 'ACP_BBGUILD_MEMBER_ADD';
                $success_message = $this->user->lang['L_ERROR'];
                trigger_error($success_message . $this->link, E_USER_WARNING);
        }
    }

    /**
     * function to batch delete members, called from listing
     *
     */
    private function member_batch_delete ()
    {
        $members_to_delete = $this->request->variable('delete_id', array(0));

        if (! is_array($members_to_delete))
        {
            return;
        }

        if (sizeof($members_to_delete) == 0)
        {
            return;
        }

        if (confirm_box(true))
        {
            // recall hidden vars
            $members_to_delete = $this->request->variable('delete_id', array(0 => 0));
            $member_names = $this->request->variable('members', array(0 => ''), true);
            foreach ($members_to_delete as $memberid => $value)
            {
                $delmember = new Members();
                $delmember->member_id = $memberid;
                $delmember->Getmember();
                $delmember->Deletemember();
                unset($delmember);
            }
            $str_members = implode($member_names, ',');
            $success_message = sprintf($this->user->lang['ADMIN_DELETE_MEMBERS_SUCCESS'], $str_members);
            trigger_error($success_message . $this->link, E_USER_NOTICE);
        }
        else
        {
            $sql = "SELECT member_name, member_id FROM " . MEMBER_LIST_TABLE . " WHERE " . $this->db->sql_in_set('member_id', array_keys($members_to_delete));
            $result = $this->db->sql_query($sql);
            while ($row = $this->db->sql_fetchrow($result))
            {
                $member_names[] = $row['member_name'];
            }
            $this->db->sql_freeresult($result);
            $s_hidden_fields = build_hidden_fields(array(
                'delete' => true ,
                'delete_id' => $members_to_delete ,
                'members' => $member_names));
            $str_members = implode($member_names, ', ');

            confirm_box(false, sprintf($this->user->lang['CONFIRM_DELETE_MEMBER'], $str_members), $s_hidden_fields);
        }
    }

    /**
     * Add a new member
     *
     */
    private function Addmember()
    {
        global $phpbb_admin_path, $phpEx;

        $newmember = new Members();
        $newmember->game_id = $this->request->variable('game_id', '');
        $newmember->member_name = $this->request->variable('member_name', '', true);
        $newmember->member_title = $this->request->variable('member_title', '', true);
        $newmember->member_guild_id = $this->request->variable('member_guild_id', 0);
        $newmember->member_rank_id = $this->request->variable('member_rank_id', 99);
        $newmember->member_level = $this->request->variable('member_level', 1);
        $newmember->member_realm = $this->request->variable('realm', '');
        $newmember->member_region = $this->request->variable('region_id', '');
        if (!in_array($newmember->member_region, $newmember->regionlist))
        {
            $newmember->member_region = '';
        }
        $newmember->member_race_id = $this->request->variable('member_race_id', 1);
        $newmember->member_class_id = $this->request->variable('member_class_id', 1);
        $newmember->member_role = $this->request->variable('member_role', 0);
        $newmember->member_gender_id = $this->request->variable('gender', 0);
        $newmember->member_comment = $this->request->variable('member_comment', '', true);
        $newmember->member_joindate = mktime(0, 0, 0, $this->request->variable('member_joindate_mo', 0), $this->request->variable('member_joindate_d', 0), $this->request->variable('member_joindate_y', 0));
        $newmember->member_outdate = 0;
        if ($this->request->variable('member_outdate_mo', 0) + $this->request->variable('member_outdate_d', 0) != 0)
        {
            $newmember->member_outdate = mktime(0, 0, 0, $this->request->variable('member_outdate_mo', 0), $this->request->variable('member_outdate_d', 0), $this->request->variable('member_outdate_y', 0));
        }
        $newmember->member_achiev = 0;
        $newmember->member_armory_url = $this->request->variable('member_armorylink', '', true);
        $newmember->phpbb_user_id = $this->request->variable('phpbb_user_id', 0);
        $newmember->member_status = $this->request->variable('activated', '') == 'on' ? 1 : 0;

        $newmember->Armory_getmember();
        $newmember->Makemember();

        if ($newmember->member_id > 0)
        {
            //record added. now update some stats
            meta_refresh(2, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $newmember->member_guild_id));
            $success_message = sprintf($this->user->lang['ADMIN_ADD_MEMBER_SUCCESS'], ucwords($newmember->member_name), date("F j, Y, g:i a"));

            $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $newmember->member_guild_id) . '"><h3>' . $this->user->lang['RETURN_MEMBERLIST'] . '</h3></a>';
            trigger_error($success_message . $this->link, E_USER_NOTICE);

        }
        else
        {
            meta_refresh(2, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $newmember->member_guild_id));

            $failure_message = sprintf($this->user->lang['ADMIN_ADD_MEMBER_FAIL'], ucwords($newmember->member_name));
            trigger_error($failure_message . $this->link, E_USER_WARNING);
        }
    }

    /**
     * Update bbguild member
     *
     */
    private function UpdateMember()
    {
        global $phpbb_admin_path, $phpEx;

        $updatemember = new Members();
        $updatemember->member_id = $this->request->variable('hidden_member_id', 0);

        if ($updatemember->member_id == 0)
        {
            $updatemember->member_id = $this->request->variable(URI_NAMEID, 0);
        }
        $updatemember->Getmember();

        $updatemember->game_id = $this->request->variable('game_id', '');
        $updatemember->member_class_id = $this->request->variable('member_class_id', 0);
        $updatemember->member_race_id = $this->request->variable('member_race_id', 0);
        $updatemember->member_role = $this->request->variable('member_role', 0);
        $updatemember->member_realm = $this->request->variable('realm', '');
        $updatemember->member_region = $this->request->variable('region_id', '');

        if (!in_array($updatemember->member_region, $updatemember->regionlist))
        {
            $updatemember->member_region = '';
        }

        $updatemember->member_name = $this->request->variable('member_name', '', true);
        $updatemember->member_gender_id = $this->request->variable('gender', 0);
        $updatemember->member_title = $this->request->variable('member_title', '', true);
        $updatemember->member_guild_id = $this->request->variable('member_guild_id', 0);
        $updatemember->member_rank_id = $this->request->variable('member_rank_id', 99);
        $updatemember->member_level = $this->request->variable('member_level', 0);
        $updatemember->member_joindate = mktime(0, 0, 0, $this->request->variable('member_joindate_mo', 0), $this->request->variable('member_joindate_d', 0), $this->request->variable('member_joindate_y', 0));
        $updatemember->member_outdate = mktime ( 0, 0, 0, 12, 31, 2030 );

        if ($this->request->variable('member_outdate_mo', 0) + $this->request->variable('member_outdate_d', 0) != 0)
        {
            $updatemember->member_outdate = mktime(0, 0, 0, $this->request->variable('member_outdate_mo', 0), $this->request->variable('member_outdate_d', 0), $this->request->variable('member_outdate_y', 0));
        }

        $updatemember->member_achiev = $this->request->variable('member_achiev', 0);
        $updatemember->member_comment = $this->request->variable('member_comment', '', true);
        $updatemember->phpbb_user_id = $this->request->variable('phpbb_user_id', 0);

        if ($updatemember->member_rank_id < 90)
        {
            $updatemember->Armory_getmember();
        }

        $updatemember->member_status = $this->request->variable('activated', '') == 'on' ? 1 : 0;

        $old_member = new Members();
        $old_member->member_id = $updatemember->member_id;
        $old_member->Getmember();
        $updatemember->Updatemember($old_member);

        meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $updatemember->member_guild_id));
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $updatemember->member_guild_id) . '"><h3>' . $this->user->lang['RETURN_MEMBERLIST'] . '</h3></a>';
        $success_message = sprintf($this->user->lang['ADMIN_UPDATE_MEMBER_SUCCESS'], $updatemember->member_name);
        trigger_error($success_message . $this->link);

    }

    /**
     *
     * Delete bbguild member
     *
     */
    private function DeleteMember()
    {
        global $phpbb_admin_path, $phpEx;
        $deletemember = new Members();
        $deletemember->member_id = $this->request->variable('del_member_id', 0);
        $deletemember->Getmember();
        $deletemember->Deletemember();
        $success_message = sprintf($this->user->lang['ADMIN_DELETE_MEMBERS_SUCCESS'], $deletemember->member_name);

        meta_refresh(1, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' . URI_GUILD . "=" . $deletemember->member_guild_id));
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;' .
                URI_GUILD . "=" . $deletemember->member_guild_id) . '"><h3>' . $this->user->lang['RETURN_MEMBERLIST'] . '</h3></a>';

        trigger_error($success_message . $this->link, E_USER_WARNING);

    }

    /**
     * Activates/deactivates the selected members
     */
    private function ActivateList()
    {
        if (!check_form_key('sajaki/bbguild'))
        {
            trigger_error('FORM_INVALID');
        }
        $activatemember = new Members();
        $activate_members = $this->request->variable('activate_id', array(0));
        $member_window = $this->request->variable('hidden_member', array(0));
        $activatemember->Activatemembers($activate_members, $member_window);
        unset($activatemember);
    }

    /**
     * Call the Character API
     *
     */
    private function CallCharacterAPI()
    {
        $Guild = new Guilds();
        $Guild->guildid = $this->request->variable('hidden_guildid', 0);
        $Guild->Getguild();

        $minlevel = $this->request->variable('hidden_minlevel', 0);
        $maxlevel = $this->request->variable('hidden_maxlevel', 200);
        $selectactive = $this->request->variable('hidden_active', 0);
        $selectnonactive = $this->request->variable('hidden_nonactive', 0);
        $member_filter = $this->request->variable('hidden_member_name', '', true) ;

        $members_result = $Guild->listmembers('member_id', 0, 0 , $minlevel, $maxlevel, $selectactive, $selectnonactive, $member_filter, true);

        $log = '';
        $i = 0;
        $j=0;
        while ($row = $this->db->sql_fetchrow($members_result))
        {
            if ($j > 100)
            {
                break;
            }
            $member = new Members($row['member_id']);

            $last_update = $member->last_update;

            $diff = \round( \abs ( (\time() - $last_update)) / 86400, 2) ;

            // 1 days ago ? call armory
            if($diff > 1)
            {
                $i += 1;
                if ($log != '') $log .= ', ';
                $old_member = new Members($row['member_id']);


                if (isset($member))
                {
                    if ($member->member_rank_id < 90)
                    {
                        $member->Armory_getmember();
                    }
                    $member->Updatemember($old_member);
                }

                unset($old_member);
                $log .= $row['member_name'];
            }

            unset($member);
            $j++;

        }
        $this->db->sql_freeresult($members_result);
        unset ($members_result);
        return array($i, $log);


    }

    /**
     * List Members
     *
     * @param $mode
     * @param Guilds $Guild
     */
    private function BuildTemplateListMembers($mode, Guilds $Guild)
    {
        global  $config, $phpbb_admin_path, $phpEx;

        // fill popup and set selected to default selection
        $Guild->Getguild();
        $guildlist = $Guild->guildlist(0);
        foreach ($guildlist as $g)
        {
            $this->template->assign_block_vars('guild_row', array(
                'VALUE'    => $g['id'],
                'SELECTED' => ($g['id'] == $Guild->guildid) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)'));
        }
        $previous_data = '';
        //get window
        $start    = $this->request->variable('start', 0, false);
        $minlevel = $this->request->variable('minlevel', 0);
        $maxlevel = $this->request->variable('maxlevel', 200);

        if ($this->request->is_set_post('search') || isset($_GET['active']) || isset($_GET['nonactive']))
        {
            $selectactive    = $this->request->variable('active', 0);
            $selectnonactive = $this->request->variable('nonactive', 0);
        }
        else
        {
            // set standard
            $selectactive    = 1;
            $selectnonactive = 1;
        }
        $member_filter = $this->request->variable('member_name', '', true);
        $sort_order = array(
            0 => array('member_name', 'member_name desc'),
            1 => array('username', 'username desc'),
            2 => array('member_level', 'member_level desc'),
            3 => array('member_class', 'member_class desc'),
            4 => array('rank_name', 'rank_name desc'),
            5 => array('last_update', 'last_update desc'),
            7 => array('member_id', 'member_id desc')
        );
        $current_order   = $this->switch_order($sort_order);
        $sort_index      = explode('.', $current_order['uri']['current']);
        $previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
        $show_all        = ((isset($_GET['show'])) && $this->request->variable('show', '') == 'all') ? true : false;

        $result       = $Guild->listmembers($current_order['sql'], 0, 0, $minlevel, $maxlevel, $selectactive, $selectnonactive, $member_filter);
        $member_count = 0;

        while ($row = $this->db->sql_fetchrow($result))
        {
            $member_count += 1;
        }
        if (!($result))
        {
            trigger_error($this->user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
        }
        $this->db->sql_freeresult($result);
        $members_result = $Guild->listmembers($current_order['sql'], $start, 1, $minlevel, $maxlevel, $selectactive, $selectnonactive, $member_filter);
        $lines          = 0;
        while ($row = $this->db->sql_fetchrow($members_result))
        {
            $phpbb_user_id = (int)$row['phpbb_user_id'];
            $race_image    = (string)(($row['member_gender_id'] == 0) ? $row['image_male'] : $row['image_female']);
            $lines += 1;
            $this->template->assign_block_vars('members_row', array(
                'S_READONLY'           => ($row['rank_id'] == 90 || $row['rank_id'] == 99) ? true : false,
                'STATUS'               => ($row['member_status'] == 1) ? 'checked="checked" ' : '',
                'ID'                   => $row['member_id'],
                'COUNT'                => $member_count,
                'NAME'                 => $row['rank_prefix'] . $row['member_name'] . $row['rank_suffix'],
                'USERNAME'             => get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
                'RANK'                 => $row['rank_name'],
                'LEVEL'                => ($row['member_level'] > 0) ? $row['member_level'] : '&nbsp;',
                'ARMOR'                => (!empty($row['armor_type'])) ? $row['armor_type'] : '&nbsp;',
                'COLORCODE'            => ($row['colorcode'] == '') ? '#254689' : $row['colorcode'],
                'CLASS_IMAGE'          => (strlen($row['imagename']) > 1) ? $this->ext_path . "images/class_images/" . $row['imagename'] . ".png" : '',
                'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
                'RACE_IMAGE'           => (strlen($race_image) > 1) ? $this->ext_path . "images/race_images/" . $race_image . ".png" : '',
                'S_RACE_IMAGE_EXISTS'  => (strlen($race_image) > 1) ? true : false,
                'CLASS'                => ($row['member_class'] != 'NULL') ? $row['member_class'] : '&nbsp;',
                'LAST_UPDATE'          => ($row['last_update'] == 0) ? '' : date($config['bbguild_date_format'] . ' H:i:s', $row['last_update']),
                'U_VIEW_USER'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=users&amp;icat=13&amp;mode=overview&amp;u=$phpbb_user_id"),
                'U_VIEW_MEMBER'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_addmember&amp;' . URI_NAMEID . '=' . $row['member_id']),
                'U_DELETE_MEMBER'      => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_addmember&amp;delete=1&amp;' . URI_NAMEID . '=' . $row['member_id'])));
            $previous_data = $row[$previous_source];
        }
        $this->db->sql_freeresult($members_result);

        $footcount_text   = sprintf($this->user->lang['LISTMEMBERS_FOOTCOUNT'], $member_count);

        $memberpagination = $this->phpbb_container->get('pagination');

        $pagination_url = append_sid("{$phpbb_admin_path}index.$phpEx",
            'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri']['current'] .
            "&amp;" . URI_GUILD . "=" . $Guild->guildid .
            "&amp;minlevel=" . $minlevel .
            "&amp;maxlevel=" . $maxlevel .
            "&amp;active="   . $selectactive .
            "&amp;nonactive=" . $selectnonactive);

        $memberpagination->generate_template_pagination($pagination_url, 'pagination', 'start', $member_count, $config['bbguild_user_llimit'], $start, true);

        $this->template->assign_vars(array(
            'F_SELECTACTIVE'        => $selectactive,
            'F_SELECTNONACTIVE'     => $selectnonactive,
            'GUILDID'               => $Guild->guildid,
            'GUILDNAME'             => $Guild->name,
            'MINLEVEL'              => $minlevel,
            'MAXLEVEL'              => $maxlevel,
            'START'                 => $start,
            'MEMBER_NAME'           => $member_filter,
            'F_MEMBERS'             => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module') . '&amp;mode=mm_addmember',
            'F_MEMBERS_LIST'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module') . '&amp;mode=mm_listmembers',
            'L_TITLE'               => $this->user->lang['ACP_MM_LISTMEMBERS'],
            'L_EXPLAIN'             => $this->user->lang['ACP_MM_LISTMEMBERS_EXPLAIN'],
            'O_NAME'                => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][0] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_USERNAME'            => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][1] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_LEVEL'               => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][2] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_CLASS'               => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][3] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_RANK'                => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][4] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_LAST_UPDATE'         => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][5] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'O_ID'                  => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;o=' . $current_order['uri'][7] . "&amp;" . URI_GUILD . "=" . $Guild->guildid . "&amp;minlevel=" . $minlevel .
                "&amp;maxlevel=" . $maxlevel .
                "&amp;active=" . $selectactive .
                "&amp;nonactive=" . $selectnonactive),
            'U_LIST_MEMBERS'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_listmembers&amp;'),
            'LISTMEMBERS_FOOTCOUNT' => $footcount_text,
            'U_VIEW_GUILD'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=guild&amp;mode=editguild&amp;" . URI_GUILD . '=' . $Guild->guildid),
            'S_WOW'                 => ($Guild->game_id == 'wow') ? true : false,
            'PAGE_NUMBER'           => $memberpagination->on_page($member_count, $config['bbguild_user_llimit'], $start),
            'GUILD_EMBLEM'          => $Guild->emblempath,
            'GUILD_NAME'            => $Guild->name,
        ));
        $this->page_title = 'ACP_BBGUILD_MEMBER_LIST';

    }

    /**
     * Build addmember template
     *
     * @param $mode
     */
    private function BuildTemplateAddEditmembers($mode)
    {
        global $config, $phpbb_admin_path, $phpEx;

        $member_id  = $this->request->variable('hidden_member_id', $this->request->variable(URI_NAMEID, 0));
        $editmember = new Members($member_id);
        $S_ADD = ($member_id > 0) ? false : true;
        if ($S_ADD)
        {
            // set defaults
            $editmember->member_guild_id = $this->request->variable(URI_GUILD, 0);
        }
        $Guild     = new Guilds($editmember->member_guild_id);
        $guildlist = $Guild->guildlist();

        if ($S_ADD)
        {
            $editmember->game_id          = $Guild->game_id;
            $editmember->member_rank_id   = $Guild->raidtrackerrank;
            $editmember->member_status    = 1;
            $editmember->member_gender_id = 0;
        }
        foreach ($guildlist as $g)
        {
            //populate guild popup
            $this->template->assign_block_vars('guild_row', array(
                'VALUE'    => $g['id'],
                'SELECTED' => ($g['id'] == $editmember->member_guild_id) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)'));
        }

        // Game dropdown
        if (isset($this->games))
        {
            foreach ($this->games as $gameid => $gamename)
            {
                $this->template->assign_block_vars('game_row', array(
                    'VALUE'    => $gameid,
                    'SELECTED' => ($editmember->game_id == $gameid) ? ' selected="selected"' : '',
                    'OPTION'   => $gamename));
            }
        }
        else
        {
            trigger_error('ERROR_NOGAMES', E_USER_WARNING);
        }

        foreach ($this->regions as $key => $regionname)
        {
            $this->template->assign_block_vars('region_row', array(
                'VALUE'    => $key,
                'SELECTED' => ($editmember->member_region == $key) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($regionname)) ? $regionname : '(None)'));
        }
        // Rank drop-down -> for initial load
        // reloading is done from ajax to prevent redraw
        $Ranks  = new Ranks($editmember->member_guild_id);
        $result = $Ranks->listranks();
        while ($row = $this->db->sql_fetchrow($result))
        {
            $this->template->assign_block_vars('rank_row', array(
                'VALUE'    => $row['rank_id'],
                'SELECTED' => ($editmember->member_rank_id == $row['rank_id']) ? ' selected="selected"' : '',
                'OPTION'   => (!empty($row['rank_name'])) ? $row['rank_name'] : '(None)'));
        }
        // Race dropdown
        // reloading is done from ajax to prevent redraw
        $sql_array = array(
            'SELECT'   => '  r.race_id, l.name as race_name ',
            'FROM'     => array(
                RACE_TABLE  => 'r',
                BB_LANGUAGE => 'l'),
            'WHERE'    => " r.race_id = l.attribute_id
								AND r.game_id = '" . $editmember->game_id . "'
								AND l.attribute='race'
								AND l.game_id = r.game_id
								AND l.language= '" . $config['bbguild_lang'] . "'",
            'ORDER_BY' => 'l.name asc');
        $sql       = $this->db->sql_build_query('SELECT', $sql_array);
        $result    = $this->db->sql_query($sql);
        if ($editmember->member_id > 0)
        {
            while ($row = $this->db->sql_fetchrow($result))
            {
                $this->template->assign_block_vars('race_row', array(
                    'VALUE'    => $row['race_id'],
                    'SELECTED' => ($editmember->member_race_id == $row['race_id']) ? ' selected="selected"' : '',
                    'OPTION'   => (!empty($row['race_name'])) ? $row['race_name'] : '(None)'));
            }
        } else
        {
            while ($row = $this->db->sql_fetchrow($result))
            {
                $this->template->assign_block_vars('race_row', array(
                    'VALUE'    => $row['race_id'],
                    'SELECTED' => '',
                    'OPTION'   => (!empty($row['race_name'])) ? $row['race_name'] : '(None)'));
            }
        }
        $this->db->sql_freeresult($result);

        //
        // Class dropdown
        // reloading is done from ajax to prevent redraw
        $sql_array = array(
            'SELECT'   => ' c.class_id, l.name as class_name, c.class_hide,
									  c.class_min_level, class_max_level, c.class_armor_type , c.imagename ',
            'FROM'     => array(
                CLASS_TABLE => 'c',
                BB_LANGUAGE => 'l'),
            'WHERE'    => " l.game_id = c.game_id  AND c.game_id = '" . $editmember->game_id . "'
					AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbguild_lang'] . "' AND l.attribute = 'class' ",
            'ORDER_BY' => 'l.name asc'
        );

        $sql       = $this->db->sql_build_query('SELECT', $sql_array);
        $result    = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            if ($row['class_min_level'] <= 1)
            {
                $option = (!empty($row['class_name'])) ? $row['class_name'] . "
						 Level (" . $row['class_min_level'] . " - " . $row['class_max_level'] . ")" : '(None)';
            } else
            {
                $option = (!empty($row['class_name'])) ? $row['class_name'] . "
						 Level " . $row['class_min_level'] . "+" : '(None)';
            }
            if ($editmember->member_id <> 0)
            {
                $this->template->assign_block_vars('class_row', array(
                    'VALUE'    => $row['class_id'],
                    'SELECTED' => ($editmember->member_class_id == $row['class_id']) ? ' selected="selected"' : '',
                    'OPTION'   => $option));
            } else
            {
                $this->template->assign_block_vars('class_row', array(
                    'VALUE'    => $row['class_id'],
                    'SELECTED' => '',
                    'OPTION'   => $option));
            }
        }
        $this->db->sql_freeresult($result);

        // get roles
        $Roles = new Roles();
        $Roles->game_id = $Guild->game_id;
        $Roles->guild_id = $editmember->member_guild_id;
        $listroles = $Roles->listroles();
        foreach($listroles as $roleid => $Role )
        {
             $this->template->assign_block_vars('role_row', array(
                 'VALUE' => $Role['role_id'] ,
                 'SELECTED' => ($editmember->member_role == $Role['role_id']) ? ' selected="selected"' : '' ,
                 'OPTION' => $Role['rolename'] ));
        }


        // build presets for joindate pulldowns
        $now                      = getdate();
        $s_memberjoin_day_options = '<option value="0"	>--</option>';
        for ($i = 1; $i < 32; $i++)
        {
            $day      = $editmember->member_id > 0 ? $editmember->member_joindate_d : $now['mday'];
            $selected = ($i == $day) ? ' selected="selected"' : '';
            $s_memberjoin_day_options .= "<option value=\"$i\"$selected>$i</option>";
        }
        $s_memberjoin_month_options = '<option value="0">--</option>';
        for ($i = 1; $i < 13; $i++)
        {
            $month    = $editmember->member_id > 0 ? $editmember->member_joindate_mo : $now['mon'];
            $selected = ($i == $month) ? ' selected="selected"' : '';
            $s_memberjoin_month_options .= "<option value=\"$i\"$selected>$i</option>";
        }
        $s_memberjoin_year_options = '<option value="0">--</option>';
        for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
        {
            $yr       = $editmember->member_id > 0 ? $editmember->member_joindate_y : $now['year'];
            $selected = ($i == $yr) ? ' selected="selected"' : '';
            $s_memberjoin_year_options .= "<option value=\"$i\"$selected>$i</option>";
        }

        // build presets for outdate pulldowns
        $s_memberout_day_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';
        for ($i = 1; $i < 32; $i++)
        {
            if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
            {
                $day      = $editmember->member_outdate_d;
                $selected = ($i == $day) ? ' selected="selected"' : '';
            } else
            {
                $selected = '';
            }
            $s_memberout_day_options .= "<option value=\"$i\"$selected>$i</option>";
        }
        $s_memberout_month_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';
        for ($i = 1; $i < 13; $i++)
        {
            if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
            {
                $month    = $editmember->member_outdate_mo;
                $selected = ($i == $month) ? ' selected="selected"' : '';
            } else
            {
                $selected = '';
            }
            $s_memberout_month_options .= "<option value=\"$i\"$selected>$i</option>";
        }
        $s_memberout_year_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';
        for ($i = $now['year'] - 10; $i <= $now['year'] + 10; $i++)
        {
            if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
            {
                $yr       = $editmember->member_outdate_y;
                $selected = ($i == $yr) ? ' selected="selected"' : '';
            } else
            {
                $selected = '';
            }
            $s_memberout_year_options .= "<option value=\"$i\"$selected>$i</option>";
        }

        // phpbb User dropdown
        $phpbb_user_id = $editmember->member_id > 0 ? $editmember->phpbb_user_id : 0;
        $sql_array     = array(
            'SELECT'   => ' u.user_id, u.username ',
            'FROM'     => array(
                USERS_TABLE => 'u'),
            // exclude bots and guests, order by name -- ticket  129
            'WHERE'    => " u.group_id != 6 and u.group_id != 1 ",
            'ORDER_BY' => " u.username ASC");
        $sql           = $this->db->sql_build_query('SELECT', $sql_array);
        $result        = $this->db->sql_query($sql);
        $s_phpbb_user  = '<option value="0"' . (($phpbb_user_id == 0) ? ' selected="selected"' : '') . '>--</option>';
        while ($row = $this->db->sql_fetchrow($result))
        {
            $selected = ($row['user_id'] == $phpbb_user_id) ? ' selected="selected"' : '';
            $s_phpbb_user .= '<option value="' . $row['user_id'] . '"' . $selected . '>' . $row['username'] . '</option>';
        }
        unset($now);

        $this->page_title = 'ACP_MM_ADDMEMBER';
        $this->template->assign_vars(array(
            'L_TITLE'                  => $this->user->lang['ACP_MM_ADDMEMBER'],
            'L_EXPLAIN'                => $this->user->lang['ACP_MM_ADDMEMBER_EXPLAIN'],
            'F_ADD_MEMBER'             => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbguild\acp\mm_module&amp;mode=mm_addmember&amp;'),
            'STATUS'                   => $editmember->member_status == 1 ? 'checked="checked"' : '',
            'MEMBER_NAME'              => $editmember->member_name,
            'MEMBER_ID'                => $editmember->member_id,
            'MEMBER_LEVEL'             => $editmember->member_level,
            'REALM'                    => $editmember->member_realm,
            'DEACTIVATE_REASON'        => $editmember->deactivate_reason == '' ? '' : $this->user->lang[$editmember->deactivate_reason],
            'STATUS_LOCK'              => $editmember->deactivate_reason == '' ? false : true,
            'MEMBER_ACHIEV'            => $editmember->member_achiev,
            'MEMBER_TITLE'             => $editmember->member_title,
            'MALE_CHECKED'             => ($editmember->member_gender_id == '0') ? ' checked="checked"' : '',
            'FEMALE_CHECKED'           => ($editmember->member_gender_id == '1') ? ' checked="checked"' : '',
            'MEMBER_COMMENT'           => $editmember->member_comment,
            'S_CAN_HAVE_ARMORY'        => $editmember->game_id == 'wow' || $editmember->game_id == 'aion' ? true : false,
            'MEMBER_URL'               => $editmember->member_armory_url,
            'MEMBER_PORTRAIT'          => $editmember->member_portrait_url,
            'S_MEMBER_PORTRAIT_EXISTS' => (strlen($editmember->member_portrait_url) > 1) ? true : false,
            'S_CAN_GENERATE_ARMORY'    => $editmember->game_id == 'wow' ? true : false,
            'COLORCODE'                => ($editmember->colorcode == '') ? '#254689' : $editmember->colorcode,
            'CLASS_IMAGE'              => $editmember->class_image,
            'S_CLASS_IMAGE_EXISTS'     => (strlen($editmember->class_image) > 1) ? true : false,
            'RACE_IMAGE'               => $editmember->race_image,
            'S_RACE_IMAGE_EXISTS'      => (strlen($editmember->race_image) > 1) ? true : false,
            'S_JOINDATE_DAY_OPTIONS'   => $s_memberjoin_day_options,
            'S_JOINDATE_MONTH_OPTIONS' => $s_memberjoin_month_options,
            'S_JOINDATE_YEAR_OPTIONS'  => $s_memberjoin_year_options,
            'S_OUTDATE_DAY_OPTIONS'    => $s_memberout_day_options,
            'S_OUTDATE_MONTH_OPTIONS'  => $s_memberout_month_options,
            'S_OUTDATE_YEAR_OPTIONS'   => $s_memberout_year_options,
            'S_PHPBBUSER_OPTIONS'      => $s_phpbb_user,
            'TITLE_NAME'               => ($editmember->game_id == 'wow') ? sprintf($editmember->member_title, $editmember->member_name) : '',
            // javascript
            'LA_ALERT_AJAX'            => $this->user->lang['ALERT_AJAX'],
            'LA_ALERT_OLDBROWSER'      => $this->user->lang['ALERT_OLDBROWSER'],
            'LA_MSG_NAME_EMPTY'        => $this->user->lang['FV_REQUIRED_NAME'],
            'UA_FINDRANK'              => append_sid($phpbb_admin_path . "style/dkp/findrank.$phpEx"),
            'UA_FINDCLASSRACE'         => append_sid($phpbb_admin_path . "style/dkp/findclassrace.$phpEx"),
            'S_ADD'                    => $S_ADD));


    }
}
