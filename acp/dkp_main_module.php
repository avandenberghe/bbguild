<?php
/**
 * bbDKP Mainpage ACP
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sajaki\bbdkp\acp;

class dkp_main_module extends \sajaki\bbdkp\model\admin\Admin
{
    public $u_action;
    public $link;

    /**
     * @param $id
     * @param $mode
     */
    function main ($id, $mode)
    {
        global $db, $user, $template, $request, $phpbb_admin_path, $cache;
        global $config, $phpEx, $phpbb_container;

        $form_key = 'sajaki/bbdkp';
        add_form_key($form_key);

        $this->page_title = 'ACP_DKP_MAINPAGE';
        $this->tpl_name = 'acp_' . $mode;
        $this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module') . '"><h3>' . $user->lang['ACP_DKP'] . '</h3></a>';

        switch ($mode)
        {
            // MAINPAGE
            case 'dkp_panel':
                // get inactive members
                $sql = 'SELECT count(*) as member_count FROM ' . MEMBER_LIST_TABLE . " WHERE member_status='0'";
                $result = $db->sql_query($sql);
                $total_members_inactive = (int) $db->sql_fetchfield('member_count');
                //get the active members
                $sql = 'SELECT count(*) as member_count FROM ' . MEMBER_LIST_TABLE . " WHERE member_status='1'";
                $result = $db->sql_query($sql);
                $total_members_active = (int) $db->sql_fetchfield('member_count');
                // active member kpi
                $total_members = $total_members_active . ' / ' . $total_members_inactive;
                //members having a dkp account
                $sql = 'SELECT count(*) as dkp_count  FROM ' . MEMBER_DKP_TABLE;
                $result = $db->sql_query($sql);
                $total_dkpcount = (int) $db->sql_fetchfield('dkp_count');
                //number of open dkp pools
                $sql = 'SELECT count(*) as pool_count  FROM ' . DKPSYS_TABLE;
                $result = $db->sql_query($sql);
                $total_poolcount = (int) $db->sql_fetchfield('pool_count');
                // members having an adjustment
                $sql = 'SELECT count(*) as adjustment_count  FROM ' . ADJUSTMENTS_TABLE;
                $result = $db->sql_query($sql);
                $total_adjustmentcount = (int) $db->sql_fetchfield('adjustment_count');
                // available events
                $sql = 'SELECT count(*) as event_count  FROM ' . BBEVENTS_TABLE;
                $result = $db->sql_query($sql);
                $total_eventcount = (int) $db->sql_fetchfield('event_count');
                //number of guilds
                $sql = 'SELECT count(*) as guild_count  FROM ' . GUILD_TABLE;
                $result = $db->sql_query($sql);
                $total_guildcount = (int) $db->sql_fetchfield('guild_count');
                // number of raids
                $total_raids = 0;
                $sql = 'SELECT count(*) as raid_count  FROM ' . RAIDS_TABLE;
                $result = $db->sql_query($sql);
                // statistic on number of raids run
                $total_raids = (int) $db->sql_fetchfield('raid_count');
                $days = ((time() - $config['bbdkp_eqdkp_start']) / 86400);
                $raids_per_day = sprintf("%.2f", ($total_raids / $days));
                if ($raids_per_day > $total_raids)
                {
                    $raids_per_day = $total_raids;
                }
                //total number of loot
                $sql = 'SELECT count(*) as item_count FROM ' . RAID_ITEMS_TABLE;
                $total_items = (int) $db->sql_fetchfield('item_count');
                $db->sql_freeresult($result);
                //loot frequency
                $items_per_day = sprintf("%.2f", ($total_items / $days));
                if ($items_per_day > $total_items)
                {
                    $items_per_day = $total_items;
                }
                //start date
                if ($config['bbdkp_eqdkp_start'] != 0)
                {
                    $bbdkp_started = date($config['bbdkp_date_format'], $config['bbdkp_eqdkp_start']);
                }
                else
                {
                    $bbdkp_started = '';
                }

                $latest_version_info = parent::get_productversion($request->variable('versioncheck_force', false));
                if ($latest_version_info === false)
                {
                    $template->assign_var('S_VERSIONCHECK_FAIL', true);
                }
                else
                {
                    if(phpbb_version_compare($latest_version_info, BBDKP_VERSION, '='))
                    {
                        $template->assign_vars(array(
                            'S_VERSION_UP_TO_DATE'	=> true,
                        ));
                    }
                    elseif(phpbb_version_compare($latest_version_info, BBDKP_VERSION, '>'))
                    {
                        // you have an old version
                        $template->assign_vars(array(
                            'BBDKP_NOT_UP_TO_DATE_TITLE' => sprintf($user->lang['NOT_UP_TO_DATE_TITLE'], 'bbDKP'),
                            'S_PRERELEASE'	=> false,
                            'BBDKP_LATESTVERSION' => $latest_version_info,
                            'BBDKPVERSION' => $user->lang['BBDKP_YOURVERSION'] . BBDKP_VERSION ,
                            'UPDATEINSTR' => $user->lang['BBDKP_LATESTVERSION'] . $latest_version_info . ', <a href="' . $user->lang['WEBURL'] . '">' . $user->lang['DOWNLOAD'] . '</a>'));

                    }
                    else
                    {
                        // you have a prerelease or development version
                        $template->assign_vars(array(
                            'BBDKP_NOT_UP_TO_DATE_TITLE' => sprintf($user->lang['PRELELEASE_TITLE'], 'bbDKP'),
                            'BBDKP_LATESTVERSION' => $latest_version_info,
                            'S_PRERELEASE'	=> true,
                            'BBDKPVERSION' => $user->lang['BBDKP_YOURVERSION'] . BBDKP_VERSION ,
                            'UPDATEINSTR' => $user->lang['BBDKP_LATESTVERSION'] . $latest_version_info . ', <a href="' . $user->lang['WEBURL'] . '">' . $user->lang['DOWNLOAD'] . '</a>'));
                    }
                }

                // read verbose log
                $logs = \sajaki\bbdkp\model\admin\log::Instance();
                $listlogs = $logs->read_log('', false, true, '', '');
                if(isset($listlogs))
                {
                    foreach ($listlogs as $key => $log)
                    {
                        $template->assign_block_vars('actions_row', array(
                            'U_VIEW_LOG' 	=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs&amp;' . URI_LOG . '=' . $log['log_id']  ) ,
                            'LOGDATE' 		=> $log['datestamp'],
                            'ACTION' 		=> $log['log_line'],
                        ));
                    }
                }

                $template->assign_vars(array(
                    'GLYPH' => $this->ext_path . "adm/images/glyphs/view.gif" ,
                    'NUMBER_OF_MEMBERS' => $total_members ,
                    'NUMBER_OF_RAIDS' => $total_raids ,
                    'NUMBER_OF_ITEMS' => $total_items ,
                    'NUMBER_OF_MEMBERDKP' => $total_dkpcount ,
                    'NUMBER_OF_DKPSYS' => $total_poolcount ,
                    'NUMBER_OF_GUILDS' => $total_guildcount ,
                    'NUMBER_OF_EVENTS' => $total_eventcount ,
                    'NUMBER_OF_ADJUSTMENTS' => $total_adjustmentcount ,
                    'RAIDS_PER_DAY' => $raids_per_day ,
                    'ITEMS_PER_DAY' => $items_per_day ,
                    'BBDKP_STARTED' => $bbdkp_started,
                    'BBDKP_VERSION'	=> BBDKP_VERSION,
                    'U_VERSIONCHECK_FORCE' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_panel&amp;versioncheck_force=1'),
                    'GAMES_INSTALLED' => count($this->games) > 0 ? implode(", ", $this->games) : $user->lang['NA'],
                ));
                break;

            /**
             *
             * DKP CONFIG
             */
            case 'dkp_config':

                if ($request->is_set_post('updateconfig'))
                {
                    if (! check_form_key('acp_dkp'))
                    {
                        trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);
                    }
                    $day = $request->variable('bbdkp_start_dd', 0);
                    $month = $request->variable('bbdkp_start_mm', 0);
                    $year = $request->variable('bbdkp_start_yy', 0);
                    $bbdkp_start = mktime(0, 0, 0, $month, $day, $year);
                    $settings = array(
                        'bbdkp_default_realm' => $request->variable('realm', '', true),
                        'bbdkp_default_region' => $request->variable('region', '', true),
                        'bbdkp_eqdkp_start' => $bbdkp_start,
                        'bbdkp_date_format' => $request->variable('date_format', ''),
                        'bbdkp_dkp_name' => $request->variable('dkp_name', '', true),
                        'bbdkp_lang' => $request->variable('language', 'en'),
                        'bbdkp_user_nlimit' => $request->variable('bbdkp_user_nlimit', 0),
                        'bbdkp_user_llimit' => $request->variable('bbdkp_user_llimit', 0),
                        'bbdkp_maxchars' => $request->variable('maxchars', 2),
                        'bbdkp_roster_layout' => $request->variable('rosterlayout', 0),
                        'bbdkp_show_achiev' => $request->variable('showachievement', 0),
                        'bbdkp_minrosterlvl' => $request->variable('bbdkp_minrosterlvl', 0),
                        'bbdkp_hide_inactive' =>  ($request->is_set_post('hide_inactive')) ? $request->variable('hide_inactive', '') : '0',
                        'bbdkp_inactive_period' => $request->variable('inactive_period', 0),
                        'bbdkp_list_p1' => $request->variable('list_p1', 0),
                        'bbdkp_list_p2' => $request->variable('list_p2', 0),
                        'bbdkp_list_p3' => $request->variable('list_p3', 0),
                        'bbdkp_user_elimit' => $request->variable('bbdkp_user_elimit', 0),
                        'bbdkp_event_viewall' => ($request->is_set_post('event_viewall')) ? $request->variable('event_viewall', '') : '0',
                        'bbdkp_user_elimit' => $request->variable('bbdkp_user_elimit', 0),
                        'bbdkp_user_alimit' => $request->variable('bbdkp_user_alimit', 0),
                        'bbdkp_active_point_adj' => $request->variable('bbdkp_active_point_adj', 0.0),
                        'bbdkp_inactive_point_adj' => $request->variable('bbdkp_inactive_point_adj', 0.0),
                        'bbdkp_starting_dkp' => $request->variable('starting_dkp', 0.0),
                        'bbdkp_user_ilimit' => $request->variable('bbdkp_user_ilimit', 0),
                        'bbdkp_user_rlimit' => $request->variable('bbdkp_user_rlimit', 0),

                    );
                    // reg id
                    $config->set('bbdkp_regid', 1, true);

                    $config->set('bbdkp_default_realm', $settings['bbdkp_default_realm'], true);
                    $config->set('bbdkp_default_region', $settings['bbdkp_default_region'], true);
                    $config->set('bbdkp_dkp_name',  $settings['bbdkp_dkp_name'], true);
                    $config->set('bbdkp_eqdkp_start', $settings['bbdkp_eqdkp_start'], true);

                    $config->set('bbdkp_user_nlimit', $settings['bbdkp_user_nlimit'] , true);
                    $config->set('bbdkp_date_format', $settings['bbdkp_date_format'] , true);
                    $config->set('bbdkp_lang', $settings['bbdkp_lang'] , true);
                    $config->set('bbdkp_maxchars', $settings['bbdkp_maxchars'], true);

                    //roster
                    $config->set('bbdkp_minrosterlvl', $settings['bbdkp_minrosterlvl'], true);
                    $config->set('bbdkp_roster_layout', $settings['bbdkp_roster_layout'], true);
                    $config->set('bbdkp_show_achiev', $settings['bbdkp_show_achiev'], true);

                    //standings
                    $config->set('bbdkp_hide_inactive', $settings['bbdkp_hide_inactive'], true);
                    $config->set('bbdkp_inactive_period', $settings['bbdkp_inactive_period'], true);
                    $config->set('bbdkp_list_p1', $settings['bbdkp_list_p1'] , true);
                    $config->set('bbdkp_list_p2', $settings['bbdkp_list_p2'] , true);
                    $config->set('bbdkp_list_p3', $settings['bbdkp_list_p3'] , true);
                    $config->set('bbdkp_user_llimit', $settings['bbdkp_user_llimit'], true);

                    //events
                    $config->set('bbdkp_user_elimit', $settings['bbdkp_user_elimit'], true);
                    $config->set('bbdkp_event_viewall', $settings['bbdkp_event_viewall'], true);

                    //raids
                    $config->set('bbdkp_user_rlimit', $settings['bbdkp_user_rlimit'], true);

                    //items
                    $config->set('bbdkp_user_ilimit', $settings['bbdkp_user_ilimit'], true);

                    //adjustments
                    $config->set('bbdkp_user_alimit', $settings['bbdkp_user_alimit'], true);
                    $config->set('bbdkp_active_point_adj',  $settings['bbdkp_active_point_adj'], true);
                    $config->set('bbdkp_inactive_point_adj',  $settings['bbdkp_inactive_point_adj'], true);
                    $config->set('bbdkp_starting_dkp', $settings['bbdkp_starting_dkp'] , true);

                    // Purge config cache
                    $cache->destroy('config');

                    //
                    // Logging
                    //
                    $log_action = array(
                        'header' => 'L_ACTION_SETTINGS_CHANGED'	 ,
                        'L_SETTINGS' => json_encode ($settings),
                    );

                    $this->log_insert(array(
                        'log_type' =>  'L_ACTION_SETTINGS_CHANGED',
                        'log_action' => $log_action));

                    trigger_error($user->lang['ACTION_SETTINGS_CHANGED']. $this->link, E_USER_NOTICE);
                }

                if ($request->is_set_post('register'))
                {
                    $regdata = array(
                        'domainname'	=> $request->variable('domainname', ''),
                        'phpbbversion'	=> $request->variable('phpbbversion', ''),
                        'bbdkpversion' 	=> $request->variable('bbdkpversion', ''),
                    );
                    $this->post_register_request($regdata);
                }

                $s_lang_options = '';
                foreach ($this->languagecodes as $lang => $langname)
                {
                    $selected = ($config['bbdkp_lang'] == $lang) ? ' selected="selected"' : '';
                    $s_lang_options .= '<option value="' . $lang . '" ' . $selected . '> ' . $langname . '</option>';
                }

                $template->assign_block_vars('hide_row', array(
                    'VALUE' => "YES" ,
                    'SELECTED' => ($config['bbdkp_hide_inactive'] == 1) ? ' selected="selected"' : '' ,
                    'OPTION' => "YES"));

                $template->assign_block_vars('hide_row', array(
                    'VALUE' => "NO" ,
                    'SELECTED' => ($config['bbdkp_hide_inactive'] == 0) ? ' selected="selected"' : '' ,
                    'OPTION' => "NO"));

                // Default Region
                foreach ($this->regions as $regionid => $regionvalue)
                {
                    $template->assign_block_vars('region_row', array(
                        'VALUE' => $regionid ,
                        'SELECTED' => ($regionid == $config['bbdkp_default_region']) ? ' selected="selected"' : '' ,
                        'OPTION' => $regionvalue));
                }

                //roster layout
                $rosterlayoutlist = array(
                    0 => $user->lang['ARM_STAND'] ,
                    1 => $user->lang['ARM_CLASS']);
                foreach ($rosterlayoutlist as $lid => $lname)
                {
                    $template->assign_block_vars('rosterlayout_row', array(
                        'VALUE' => $lid ,
                        'SELECTED' => ($lid == $config['bbdkp_roster_layout']) ? ' selected="selected"' : '' ,
                        'OPTION' => $lname));
                }

                $template->assign_vars(array(
                    'REGID' => isset($config['bbdkp_regid']) ? $config['bbdkp_regid'] : '',
                    'S_BBDKPREGISTERED' => isset($config['bbdkp_regid']) ? $config['bbdkp_regid'] : '',
                    'U_REGISTER' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_config&amp;action=register'),

                    'REALM' => $config['bbdkp_default_realm'] ,
                    'EQDKP_START_DD' => date('d', $config['bbdkp_eqdkp_start']) ,
                    'EQDKP_START_MM' => date('m', $config['bbdkp_eqdkp_start']) ,
                    'EQDKP_START_YY' => date('Y', $config['bbdkp_eqdkp_start']) ,
                    'DATE_FORMAT' => $config['bbdkp_date_format'] ,
                    'DKP_NAME' => $config['bbdkp_dkp_name'] ,
                    'DEFAULT_GAME' =>  count($this->games) > 0 ? implode(", ", $this->games) : $user->lang['NA'],
                    'S_LANG_OPTIONS' => $s_lang_options,
                    'USER_NLIMIT' => $config['bbdkp_user_nlimit'] ,
                    'USER_LLIMIT' => $config['bbdkp_user_llimit'] ,
                    'MAXCHARS' => $config['bbdkp_maxchars'] ,
                    'F_SHOWACHIEV' => $config['bbdkp_show_achiev'] ,
                    'MINLEVEL' => $config['bbdkp_minrosterlvl'],

                    'HIDE_INACTIVE_YES_CHECKED' => ($config['bbdkp_hide_inactive'] == '1') ? ' checked="checked"' : '' ,
                    'HIDE_INACTIVE_NO_CHECKED' => ($config['bbdkp_hide_inactive'] == '0') ? ' checked="checked"' : '' ,
                    'INACTIVE_PERIOD' => $config['bbdkp_inactive_period'] ,
                    'LIST_P1' => $config['bbdkp_list_p1'] ,
                    'LIST_P2' => $config['bbdkp_list_p2'] ,
                    'LIST_P3' => $config['bbdkp_list_p3'] ,

                    'USER_ELIMIT' => $config['bbdkp_user_elimit'] ,
                    'EVENT_VIEWALL_YES_CHECKED' => ($config['bbdkp_event_viewall'] == '1') ? ' checked="checked"' : '' ,
                    'EVENT_VIEWALL_NO_CHECKED' => ($config['bbdkp_event_viewall'] == '0') ? ' checked="checked"' : '' ,

                    'USER_RLIMIT' => $config['bbdkp_user_rlimit'] ,
                    'USER_ILIMIT' => $config['bbdkp_user_ilimit'] ,

                    'USER_ALIMIT' => $config['bbdkp_user_alimit'] ,
                    'STARTING_DKP' => $config['bbdkp_starting_dkp'] ,
                    'INACTIVE_POINT' => $config['bbdkp_inactive_point_adj'] ,
                    'ACTIVE_POINT' => $config['bbdkp_active_point_adj'] ,

                    'U_ADDCONFIG' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_config&amp;action=addconfig'),
                    'PHPBBVER' => $config['version'],
                    'BBDKPVER' => BBDKP_VERSION,
                ));

                add_form_key('acp_dkp');
                $this->page_title = 'ACP_DKP_CONFIG';

                break;

            /**
             * PORTAL CONFIG
             */
            case 'dkp_index':
                $submit = $request->is_set_post('update');
                if ($submit)
                {
                    if (! check_form_key('acp_dkp_portal'))
                    {
                        trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);
                    }
                    if (isset($config['bbdkp_gameworld_version']))
                    {
                        $config->set('bbdkp_portal_bossprogress', $request->variable('show_bosspblock', 0), true);
                    }
                    $config->set('bbdkp_news_forumid', $request->variable('news_id', 0), true);
                    $config->set('bbdkp_n_news', $request->variable('n_news', 0), true);
                    $config->set('bbdkp_n_items', $request->variable('n_items', 0), true);
                    $config->set('bbdkp_recruitment', $request->variable('bbdkp_recruitment', 0), true);
                    $config->set('bbdkp_portal_loot', $request->variable('show_lootblock', 0), true);
                    $config->set('bbdkp_portal_recruitment', $request->variable('show_recrblock', 0), true);
                    $config->set('bbdkp_portal_links', $request->variable('show_linkblock', 0), true);
                    $config->set('bbdkp_portal_menu', $request->variable('show_menublock', 0), true);
                    $config->set('bbdkp_portal_welcomemsg', $request->variable('show_welcomeblock', 0), true);
                    $config->set('bbdkp_portal_recent', $request->variable('show_recenttopics', 0), true);
                    $config->set('bbdkp_portal_rtlen', $request->variable('n_rclength', 0), true);
                    $config->set('bbdkp_portal_rtno', $request->variable('n_rcno', 0), true);
                    $config->set('bbdkp_portal_newmembers', $request->variable('show_newmembers', 0), true);
                    $config->set('bbdkp_portal_maxnewmembers', $request->variable('num_newmembers', 0), true);
                    $config->set('bbdkp_portal_whoisonline', $request->variable('show_onlineblock', 0), true);
                    $config->set('bbdkp_portal_onlineblockposition', $request->variable('onlineblockposition', 0), true);

                    $cache->destroy('config');
                    $welcometext = $request->variable('welcome_message', '', true);

                    $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
                    $allow_bbcode = $allow_urls = $allow_smilies = true;
                    generate_text_for_storage($welcometext, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

                    $sql = 'UPDATE ' . WELCOME_MSG_TABLE . " SET
							welcome_msg = '" . (string) $db->sql_escape($welcometext) . "' ,
							welcome_timestamp = " . (int) time() . " ,
							bbcode_bitfield = 	'" . (string) $bitfield . "' ,
							bbcode_uid = 		'" . (string) $uid . "'
							WHERE welcome_id = 1";
                    $db->sql_query($sql);
                    trigger_error($user->lang['ADMIN_PORTAL_SETTINGS_SAVED'] . $this->link, E_USER_NOTICE);
                }

                // get welcome msg
                $sql = 'SELECT welcome_msg, bbcode_bitfield, bbcode_uid FROM ' . WELCOME_MSG_TABLE;
                $db->sql_query($sql);
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result))
                {
                    $welcometext = $row['welcome_msg'];
                    $bitfield = $row['bbcode_bitfield'];
                    $uid = $row['bbcode_uid'];
                }

                $textarr = generate_text_for_edit($welcometext, $uid, $bitfield, 7);
                // number of news and items to show on front page
                $n_news = $config['bbdkp_n_news'];
                $n_items = $config['bbdkp_n_items'];

                add_form_key('acp_dkp_portal');
                if (isset($config['bbdkp_gameworld_version']))
                {
                    $template->assign_vars(array(
                        'S_BP_SHOW' => true ,
                        'SHOW_BOSS_YES_CHECKED' => ($config['bbdkp_portal_bossprogress'] == '1') ? ' checked="checked"' : '' ,
                        'SHOW_BOSS_NO_CHECKED' => ($config['bbdkp_portal_bossprogress'] == '0') ? ' checked="checked"' : ''));
                }
                else
                {
                    $template->assign_var('S_BP_SHOW', false);
                }

                $template->assign_vars(array(
                    'WELCOME_MESSAGE' => $textarr['text'] ,
                    'N_NEWS' => $n_news,
                    'FORUM_NEWS_OPTIONS' => make_forum_select($config['bbdkp_news_forumid'], false, false, true) ,
                    'SHOW_WELCOME_YES_CHECKED' => ($config['bbdkp_portal_welcomemsg'] == '1') ? 'checked="checked"' : '' ,
                    'SHOW_WELCOME_NO_CHECKED' => ($config['bbdkp_portal_welcomemsg'] == '0') ? 'checked="checked"' : '' ,
                    'SHOW_ONLINE_YES_CHECKED' => ($config['bbdkp_portal_whoisonline'] == '1') ? 'checked="checked"' : '' ,
                    'SHOW_ONLINE_NO_CHECKED' => ($config['bbdkp_portal_whoisonline'] == '0') ? 'checked="checked"' : '' ,

                    'SHOW_ONLINE_BOTTOM_CHECKED' => ($config['bbdkp_portal_onlineblockposition'] == '1') ? 'checked="checked"' : '' ,
                    'SHOW_ONLINE_SIDE_CHECKED' => ($config['bbdkp_portal_onlineblockposition'] == '0') ? 'checked="checked"' : '' ,

                    'SHOW_REC_YES_CHECKED' => ($config['bbdkp_portal_recruitment'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_REC_NO_CHECKED' => ($config['bbdkp_portal_recruitment'] == '0') ? ' checked="checked"' : '' ,
                    'SHOW_LOOT_YES_CHECKED' => ($config['bbdkp_portal_loot'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_LOOT_NO_CHECKED' => ($config['bbdkp_portal_loot'] == '0') ? ' checked="checked"' : '' ,
                    'N_ITEMS' => $n_items ,
                    'N_RTNO' 	=> $config['bbdkp_portal_rtno'],
                    'N_RTLENGTH' 	=> $config['bbdkp_portal_rtlen'],
                    'SHOW_RT_YES_CHECKED' => ($config['bbdkp_portal_recent'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_RT_NO_CHECKED' => ($config['bbdkp_portal_recent'] == '0') ? ' checked="checked"' : '' ,
                    'SHOW_LINK_YES_CHECKED' => ($config['bbdkp_portal_links'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_LINK_NO_CHECKED' => ($config['bbdkp_portal_links'] == '0') ? ' checked="checked"' : '' ,
                    'SHOW_MENU_YES_CHECKED' => ($config['bbdkp_portal_menu'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_MENU_NO_CHECKED' => ($config['bbdkp_portal_menu'] == '0') ? ' checked="checked"' : '',
                    'SHOW_NEWM_YES_CHECKED' => ($config['bbdkp_portal_newmembers'] == '1') ? ' checked="checked"' : '' ,
                    'SHOW_NEWM_NO_CHECKED' => ($config['bbdkp_portal_newmembers'] == '0') ? ' checked="checked"' : '' ,
                    'N_NUMNEWM' => $config['bbdkp_portal_maxnewmembers'],
                ));
                $this->page_title = $user->lang['ACP_INDEXPAGE'];

                break;

            /**
             * DKP LOGS
             *
             **/
            case 'dkp_logs':
                $this->page_title = 'ACP_DKP_LOGS';

                $logs =  \sajaki\bbdkp\model\admin\log::Instance();
                $log_id = $request->variable(URI_LOG, 0);
                $search = $request->variable('search', 0);
                if ($log_id)
                {
                    $action = 'view';
                }
                else
                {
                    $action = 'list';
                }


                switch ($action)
                {
                    case 'list':

                        $deletemark = ($request->is_set_post('delmarked')) ? true : false;
                        $marked = $request->variable('mark', array(0));
                        $search_term = $request->variable('search', '');
                        $start = $request->variable('start', 0);

                        if ($deletemark)
                        {
                            global $db, $user, $phpEx;
                            //if marked array isnt empty
                            if (sizeof($marked) && is_array($marked))
                            {
                                if (confirm_box(true))
                                {
                                    $marked = $request->variable('mark', array(0));
                                    $logs = \sajaki\bbdkp\model\admin\log::Instance();
                                    $log_action = array(
                                        'header' => 'L_ACTION_LOG_DELETED' ,
                                        'L_ADDED_BY' => $user->data['username'] ,
                                        'L_LOG_ID' => implode(",", $logs->delete_log($marked)  ));

                                    $this->log_insert(array(
                                        'log_type' => $log_action['header'] ,
                                        'log_action' => $log_action));

                                    //redirect to listing
                                    $meta_info = append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs');
                                    meta_refresh(3, $meta_info);
                                    $message = '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs') . '">' . $user->lang['RETURN_LOG'] . '</a><br />' . sprintf($user->lang['ADMIN_LOG_DELETE_SUCCESS'], implode($marked));
                                    trigger_error($message, E_USER_WARNING);
                                }
                                else
                                {
                                    // display confirmation
                                    confirm_box(false, $user->lang['CONFIRM_DELETE_BBDKPLOG'], build_hidden_fields(array(
                                        'delmarked' => true ,
                                        'mark' 		=> $marked)));
                                }
                                // they hit no
                                $message = '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs') . '">' . $user->lang['RETURN_LOG'] . '</a><br />' . sprintf($user->lang['ADMIN_LOG_DELETE_FAIL'], implode($marked));
                                trigger_error($message, E_USER_WARNING);
                            }
                        }

                        $sort_order = array(
                            0 => array('log_id desc' ,'log_id') ,
                            1 => array('log_date desc' ,'log_date') ,
                            2 => array('log_type' ,'log_type desc') ,
                            3 => array('username' ,'username dsec') ,
                            4 => array('log_ipaddress' ,'log_ipaddress desc') ,
                            5 => array('log_result' , 'log_result desc'));

                        $current_order = $this->switch_order($sort_order);
                        $verbose = true;
                        $listlogs = $logs->read_log($current_order['sql'], $search, $verbose, $search_term, $start);

                        foreach ($listlogs as $key => $log)
                        {
                            $template->assign_block_vars('logs_row', array(
                                'ID'		=> $log['log_id'],
                                'DATE' 		=> $log['datestamp'],
                                'TYPE' 		=> $log['log_type'],
                                'U_VIEW_LOG' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs&amp;' . URI_LOG . '=' . $log['log_id'] . '&amp;search=' . $search_term . '&amp;start=' . $start ) ,
                                'VERBOSE'	=> $verbose,
                                'IMGPATH'    => $this->ext_path . 'adm/images/glyphs/view.gif',
                                'USER' 		=> $log['username'],
                                'ACTION' 	=> $log['log_line'],
                                'IP' 		=> $log['log_ipaddress'],
                                'RESULT' 	=> $log['log_result'],
                                'C_RESULT' 	=> $log['cssresult'] ,
                                'ENCODED_TYPE' => urlencode($log['log_type']) ,
                                'ENCODED_USER' => urlencode($log['username']) ,
                                'ENCODED_IP' => urlencode($log['log_ipaddress'])));
                        }
                        $logcount = $logs->getTotalLogs();

                        $pagination = $phpbb_container->get('pagination');

                        $pagination_url = append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs&amp;') . '&amp;search=' . $search_term . '&amp;o=' . $current_order['uri']['current'];
                        $pagination->generate_template_pagination($pagination_url, 'pagination', 'page', $logcount, USER_LLIMIT, $start);

                        $template->assign_vars(array(
                            'S_LIST' 	=> true ,
                            'L_TITLE' 	=> $user->lang['ACP_DKP_LOGS'] ,
                            'L_EXPLAIN' => $user->lang['ACP_DKP_LOGS_EXPLAIN'] ,
                            'O_DATE' 	=> $current_order['uri'][0] ,
                            'O_TYPE' 	=> $current_order['uri'][1] ,
                            'O_USER' 	=> $current_order['uri'][2] ,
                            'O_IP' 		=> $current_order['uri'][3] ,
                            'O_RESULT' 	=> $current_order['uri'][4] ,
                            'U_LOGS' 	=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs&amp;') . '&amp;search=' . $search_term . '&amp;start=' . $start ,
                            'U_LOGS_SEARCH' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs'),
                            'CURRENT_ORDER' => $current_order['uri']['current'] ,
                            'START' => $start ,
                            'VIEWLOGS_FOOTCOUNT' => sprintf($user->lang['VIEWLOGS_FOOTCOUNT'], $logcount, USER_LLIMIT) ,
                            'PAGE_NUMBER'        => $pagination->on_page($logcount, USER_LLIMIT, $start)
                        ));
                        break;

                    case 'view':
                        $viewlog = $logs->get_logentry($log_id);
                        $log_actionxml = $viewlog['log_action'];
                        $search_term = $request->variable('search', '');
                        $start = $request->variable('start', 0);
                        $log_action = (array) simplexml_load_string($log_actionxml);
                        // loop the action elements and fill template
                        foreach ($log_action as $key => $value)
                        {
                            switch(strtolower($key))
                            {
                                case 'usercolour':
                                case 'id':
                                    break;
                                case 'header':
                                    if (in_array($log_action['header'], $logs::$valid_action_types))
                                    {
                                        $log_actionstr = $logs->getLogMessage($log_action['header'], false);
                                    }
                                    break;
                                default:
                                    $template->assign_block_vars('logaction_row', array(
                                        'KEY' 	=> 	(isset($user->lang[$key]))  ? $user->lang[$key] . ': ' : $key,
                                        'VALUE' => 	$value));


                            }
                        }


                        // fill constant template elements
                        $template->assign_vars(array(
                            'S_LIST' => false ,
                            'L_TITLE' => $user->lang['ACP_DKP_LOGS'] ,
                            'L_EXPLAIN' => $user->lang['ACP_DKP_LOGS_EXPLAIN'] ,
                            'LOG_DATE' => (! empty($viewlog['log_date'])) ? $user->format_date($viewlog['log_date'])  : '&nbsp;' ,
                            'LOG_USERNAME' => $viewlog['colouruser'],
                            'LOG_IP_ADDRESS' => $viewlog['log_ipaddress'] ,
                            'LOG_SESSION_ID' => $viewlog['log_sid'] ,
                            'LOG_RESULT' => $viewlog['log_result'] ,
                            'LOG_ACTION' => $log_actionstr, ));

                        break;
                }

                $template->assign_vars(array(
                    'U_BACK'    => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_main_module&amp;mode=dkp_logs') . '&amp;search=' . $search_term . '&amp;start=' . $start . '&amp;' ,
                ));
                break;
        }


    }
}