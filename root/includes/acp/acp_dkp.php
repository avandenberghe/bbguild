<?php
/**
 * 
 * @package bbDKP.acp
 * @version $Id$
 * @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB')) 
{
    exit();
}

if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}
/**
 * This acp class manages setting configs, logging
 * @author Ippehe, Sajaki
 *
 */
class acp_dkp extends bbDKP_Admin
{
    /**
     * main Settings function
     * 
     */
    function main ($id, $mode)
    {
        global $db, $user, $auth, $template, $sid, $cache;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
        
        $user->add_lang(array('mods/dkp_admin'));
        $user->add_lang(array('mods/dkp_common'));
        $link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=mainpage") . '"><h3>' . $user->lang['RETURN_DKPINDEX'] . '</h3></a>';
        
        switch ($mode) 
        {
        	
            /**
             * MAINPAGE
             */
            case 'mainpage':
                
                $sql1 = 'SELECT count(*) as member_count FROM ' . MEMBER_LIST_TABLE . " where member_status='0'";
                $result1 = $db->sql_query($sql1);
                $total_members_inactive = (int) $db->sql_fetchfield('member_count');
                $db->sql_freeresult($result1);

                $sql6 = 'SELECT count(*) as member_count FROM ' . MEMBER_LIST_TABLE . " where member_status='1'";
                $result6 = $db->sql_query($sql6);
                $total_members_active = (int) $db->sql_fetchfield('member_count');
                $db->sql_freeresult($result6);
                $total_members = $total_members_active . ' / ' . $total_members_inactive;

                $sql4 = 'SELECT count(*) as dkp_count  FROM ' . MEMBER_DKP_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_dkpcount = (int) $db->sql_fetchfield('dkp_count');
                $db->sql_freeresult($result4);
                
                $sql4 = 'SELECT count(*) as pool_count  FROM ' . DKPSYS_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_poolcount = (int) $db->sql_fetchfield('pool_count');
                $db->sql_freeresult($result4);

                $sql4 = 'SELECT count(*) as adjustment_count  FROM ' . ADJUSTMENTS_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_adjustmentcount = (int) $db->sql_fetchfield('adjustment_count');
                $db->sql_freeresult($result4);
                
                $sql4 = 'SELECT count(*) as event_count  FROM ' . EVENTS_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_eventcount = (int) $db->sql_fetchfield('event_count');
                $db->sql_freeresult($result4);
                
                $sql4 = 'SELECT count(*) as guild_count  FROM ' . GUILD_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_guildcount = (int) $db->sql_fetchfield('guild_count');
                $db->sql_freeresult($result4);
                
                $total_raids = 0;
                $sql4 = 'SELECT count(*) as raid_count  FROM ' . RAIDS_TABLE;
                $result4 = $db->sql_query($sql4);
                $total_raids = (int) $db->sql_fetchfield('raid_count');
                $db->sql_freeresult($result4);
                
                $days = ((time() - $config['bbdkp_eqdkp_start']) / 86400);
                $raids_per_day = sprintf("%.2f", ($total_raids / $days));
                
                $sql6 = 'SELECT count(*) as item_count FROM ' . RAID_ITEMS_TABLE;
                $result6 = $db->sql_query($sql6);
                $total_items = (int) $db->sql_fetchfield('item_count');
                $db->sql_freeresult($result6);
                
                $items_per_day = sprintf("%.2f", ($total_items / $days));
                if ($raids_per_day > $total_raids) 
                {
                    $raids_per_day = $total_raids;
                }
                if ($items_per_day > $total_items) 
                {
                    $items_per_day = $total_items;
                } 
                
                // Log Actions
                $sql6 = 'SELECT count(*) as log_count FROM ' . LOGS_TABLE;
                $result6 = $db->sql_query($sql6);
                $total_logs = (int) $db->sql_fetchfield('log_count');
                $db->sql_freeresult($result6);
                
                $s_logs = false;
                if ($total_logs > 0) 
                {
                    $sql = 'SELECT l.*, u.username
    							FROM ' . LOGS_TABLE . ' l, ' . USERS_TABLE . ' u
    							WHERE u.user_id=l.log_userid
    							ORDER BY l.log_date DESC'; 
    				$result =$db->sql_query_limit($sql, 30);
    				
                    while ($row = $db->sql_fetchrow($result)) 
                    {
                        switch ($row['log_type']) 
                        {
                            case 'L_ACTION_DKPSYS_ADDED':
                                $logline = sprintf($user->lang['VLOG_DKPSYS_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_DKPSYS_NAME'), 
                                            $this->getaction($row['log_action'],'L_DKPSYS_STATUS') );
                                break;
                            case 'L_ACTION_DKPSYS_UPDATED':
                                $logline = sprintf($user->lang['VLOG_DKPSYS_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_DKPSYSNAME_BEFORE'), 
                                            $this->getaction($row['log_action'],'L_DKPSYSNAME_AFTER') );
                                break;
                            case 'L_ACTION_DKPSYS_DELETED':
                                $logline = sprintf($user->lang['VLOG_DKPSYS_DELETED'], $row['username'],  
                                            $this->getaction($row['log_action'],'L_DKPSYS_NAME') );
                                break;
                                
                            case 'L_ACTION_EVENT_ADDED':
                                $logline = sprintf($user->lang['VLOG_EVENT_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME'), 
                                            $this->getaction($row['log_action'],'L_VALUE') );
                                break;
                            case 'L_ACTION_EVENT_UPDATED':
                                $logline = sprintf($user->lang['VLOG_EVENT_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME_BEFORE') );
                                break;
                                
                            case 'L_ACTION_EVENT_DELETED':
                                $logline = sprintf($user->lang['VLOG_EVENT_DELETED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME')   );
                                break;
                                
                            case 'L_ACTION_HISTORY_TRANSFER':
                                $logline = sprintf($user->lang['VLOG_HISTORY_TRANSFER'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_FROM') , 
                                            $this->getaction($row['log_action'],'L_TO') ) ;
                                break;
                                
                            case 'L_ACTION_INDIVADJ_ADDED':
                                $logline = sprintf($user->lang['VLOG_INDIVADJ_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_ADJUSTMENT')  , 
                                            count(explode(', ', $this->getaction($row['log_action'],'L_MEMBERS')  )));
                                break;
                            case 'L_ACTION_INDIVADJ_UPDATED':
                                $logline = sprintf($user->lang['VLOG_INDIVADJ_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_ADJUSTMENT_BEFORE'), 
                                            $this->getaction($row['log_action'],'L_MEMBERS_AFTER') );
                                break;
                            case 'L_ACTION_INDIVADJ_DELETED':
                                $logline = sprintf($user->lang['VLOG_INDIVADJ_DELETED'], $row['username'], 
                                           $this->getaction($row['log_action'],'L_ADJUSTMENT'), 
                                           $this->getaction($row['log_action'],'L_MEMBERS'))  ;
                                break;
                                
                            case 'L_ACTION_ITEM_ADDED':
                                $logline = sprintf($user->lang['VLOG_ITEM_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'], 'L_NAME') , 
                                            count(explode(', ',  $this->getaction($row['log_action'],'L_BUYERS'))), 
                                            $this->getaction($row['log_action'],'L_VALUE'));
                                break;
                            case 'L_ACTION_ITEM_UPDATED':
                                $logline = sprintf($user->lang['VLOG_ITEM_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME_BEFORE') ,
                                            count(explode(', ', $this->getaction($row['log_action'],'L_BUYERS_BEFORE') )));
                                break;
                            case 'L_ACTION_ITEM_DELETED':
                                $logline = sprintf($user->lang['VLOG_ITEM_DELETED'], $row['username'],
                                            $this->getaction($row['log_action'],'L_NAME}') ,
                                            count(explode(', ', $this->getaction($row['log_action'],'L_BUYERS') )));
                                break;
                                
                            case 'L_ACTION_MEMBER_ADDED':
                                $logline = sprintf($user->lang['VLOG_MEMBER_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME') );
                                break;
                            case 'L_ACTION_MEMBER_UPDATED':
                                $logline = sprintf($user->lang['VLOG_MEMBER_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME_BEFORE'));
                                break;
                                                            
                            case 'L_ACTION_MEMBER_DELETED':
                                $logline = sprintf($user->lang['VLOG_MEMBER_DELETED'], $row['username'],   
                                            $this->getaction($row['log_action'],'L_NAME') );
                                break;
                                
                                
                            case 'L_ACTION_RANK_DELETED':
                                $logline = sprintf($user->lang['VLOG_RANK_DELETED'], $row['username'],   
                                            $this->getaction($row['log_action'],'L_NAME') );
                                break;           
                            
                            case 'L_ACTION_RANK_ADDED':
                                $logline = sprintf($user->lang['VLOG_RANK_ADDED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME') );
                                break;
                            
                            case 'L_ACTION_RANK_UPDATED':
                                $logline = sprintf($user->lang['VLOG_RANK_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_NAME_BEFORE'),
                                            $this->getaction($row['log_action'],'L_NAME_AFTER')
                                            );
                                break;
                                
                            case 'L_ACTION_NEWS_ADDED':
                                $logline = sprintf($user->lang['VLOG_NEWS_ADDED'], $row['username'],
                                            $this->getaction($row['log_action'],'L_HEADLINE') ); 
                                break;
                            case 'L_ACTION_NEWS_UPDATED':
                                $logline = sprintf($user->lang['VLOG_NEWS_UPDATED'], $row['username'], 
                                            $this->getaction($row['log_action'],'L_HEADLINE_BEFORE'));
                                break;
                                
                            case 'L_ACTION_NEWS_DELETED':
                                $logline = sprintf($user->lang['VLOG_NEWS_DELETED'], $row['username'],
                                             $this->getaction($row['log_action'],'L_HEADLINE'));
                                break;
                            case 'L_ACTION_RAID_ADDED':
                                $logline = sprintf($user->lang['VLOG_RAID_ADDED'], $row['username'], 
                                             $this->getaction($row['log_action'],'L_EVENT'));
                                break;
                            case 'L_ACTION_RAID_UPDATED':
                                $logline = sprintf($user->lang['VLOG_RAID_UPDATED'], $row['username'], 
                                             $this->getaction($row['log_action'],'L_EVENT_BEFORE'));
                                break;
                            case 'L_ACTION_RAID_DELETED':
                                $logline = sprintf($user->lang['VLOG_RAID_DELETED'], $row['username'], 
                                             $this->getaction($row['log_action'],'L_EVENT'));
                                break;
                            case 'L_ACTION_LOG_DELETED':
                                $logline = sprintf($user->lang['VLOG_LOG_DELETED'], $row['username'],
                                			$this->getaction($row['log_action'],'L_LOG_ID'));
                                break;
                                
                            case 'L_ACTION_RT_CONFIG_UPDATED':
                                $logline = sprintf($user->lang['VLOG_RT_CONFIG_UPDATED'], $row['username']);
                                break;

                        }
                        unset($log_action);
                        // Show the log if we have a valid line for it
                        if (isset($logline)) 
                        {
                            $template->assign_block_vars('actions_row', array(
                            	'U_VIEW_LOG' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp&amp;mode=dkp_logs&amp;' . URI_LOG . '=' . $row['log_id'])  , 
                            	'ACTION' => $logline));
                        }
                        unset($logline);
                    }
                    $db->sql_freeresult($result);
                    $s_logs = true;
                }
                
                if ($config['bbdkp_eqdkp_start'] != 0) 
                {
                    $bbdkp_started = date($config['bbdkp_date_format'], $config['bbdkp_eqdkp_start']);
                } 
                else 
                {
                    $bbdkp_started = '';
                }
                
        		// Get current and latest version
        		$errstr = '';
        		$errno = 0;
		
                $installed_version = $config['bbdkp_version'];
                $info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version.txt', $errstr, $errno);

                if ($info === false)
        		{
        			// version file reference does not exist, show error
                    $template->assign_vars(array(
        				'S_UP_TO_DATE' => false , 
                       'BBDKPVERSION' => $user->lang['YOURVERSION']  . $installed_version ,
                    	'UPDATEINSTR' => $user->lang['VERSION_NOTONLINE']
                       ));
        			
        		}
        		else
        		{
	        		$info = explode("\n", $info);
			        $latest_version = trim($info[0]);
			
			        // is the installed version >= the latest version ? then you are up to date
			        $up_to_date = (version_compare(
			            str_replace('rc', 'RC', strtolower($installed_version)), 
			            str_replace('rc', 'RC', strtolower($latest_version)), 
			            '=')) ? true : false;
			        		
	        	   if ($up_to_date) 
	               {
	                    // your version is the same or even newer than the official version
	                    $template->assign_vars(array(
	        				'S_UP_TO_DATE' => true , 
	                        'BBDKPVERSION' => 'bbDKP ' . $config['bbdkp_version']
	                    ));
	                   
	               } 
	               else               
	               {
	                   // you have an old version
	                    $template->assign_vars(array(
	        				'S_UP_TO_DATE' => false , 
	                       'BBDKPVERSION' => $user->lang['YOURVERSION']  . $installed_version ,
	                    	'UPDATEINSTR' => $user->lang['LATESTVERSION'] . $latest_version . ', <a href=' . $user->lang['WEBURL'] . ">" . 
	                    		$user->lang['DOWNLOAD'] . '</a>'
	                    ));
	 
	               }

        		}
        		
               $template->assign_vars(array(
				'S_LOGS' => $s_logs ,
                'GLYPH' => "$phpbb_admin_path/images/glyphs/view.gif",
				'NUMBER_OF_MEMBERS' => $total_members , 
				'NUMBER_OF_RAIDS' => $total_raids , 
				'NUMBER_OF_ITEMS' => $total_items ,
				'NUMBER_OF_MEMBERDKP' => $total_dkpcount, 
				'NUMBER_OF_DKPSYS' => $total_poolcount , 
				'NUMBER_OF_GUILDS' => $total_guildcount , 
				'NUMBER_OF_EVENTS' => $total_eventcount,
				'NUMBER_OF_ADJUSTMENTS' => $total_adjustmentcount,  
				'RAIDS_PER_DAY' => $raids_per_day , 
				'ITEMS_PER_DAY' => $items_per_day , 
				'BBDKP_STARTED' => $bbdkp_started )
				                
                );  
                
                $this->page_title = 'ACP_DKP_MAINPAGE';
                $this->tpl_name = 'dkp/acp_mainpage';

                break;
            
            /**************
			 * DKP CONFIG
			 **************/
            case 'dkp_config':
            	
                $games = array(
                    'wow'        => $user->lang['WOW'], 
                    'lotro'      => $user->lang['LOTRO'], 
                    'eq'         => $user->lang['EQ'], 
                    'daoc'       => $user->lang['DAOC'], 
                    'vanguard' 	 => $user->lang['VANGUARD'],
                    'eq2'        => $user->lang['EQ2'],
                    'warhammer'  => $user->lang['WARHAMMER'],
                    'aion'       => $user->lang['AION'],
                    'FFXI'       => $user->lang['FFXI']
                );
                
                $submit = (isset($_POST['update'])) ? true : false;
                $timebonus_synchronise = (isset($_POST['timebonus_synchronise'])) ? true : false;
                $zerosum_synchronise = (isset($_POST['zerosum_synchronise'])) ? true : false;
                $decay_synchronise = (isset($_POST['decay_synchronise'])) ? true : false;

                if ($submit || $timebonus_synchronise || $zerosum_synchronise )
                {
                	if (!check_form_key('acp_dkp'))
                	{
                		trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);	
                	}
        		}
        		             	
                if ($submit) 
                {
                    set_config('bbdkp_guildtag', utf8_normalize_nfc(request_var('guildtag', '', true)), true);
                    set_config('bbdkp_default_realm', utf8_normalize_nfc(request_var('realm', '', true)), true);
                    set_config('bbdkp_default_region', utf8_normalize_nfc(request_var('region', '', true)), true);
                    set_config('bbdkp_dkp_name', request_var('dkp_name', ''), true);

					$day = request_var('bbdkp_start_dd', 0);
                	$month = request_var('bbdkp_start_mm', 0);
                	$year = request_var('bbdkp_start_yy', 0);
                	$bbdkp_start = mktime(0, 0, 0, $month, $day, $year);
                    set_config('bbdkp_eqdkp_start', $bbdkp_start, true);
                    set_config('bbdkp_user_nlimit', request_var('bbdkp_user_nlimit', 0), true);
                    set_config('bbdkp_roster_layout', request_var('rosterlayout', 0), true);
                    set_config('bbdkp_show_achiev', request_var('showachievement', 0), true);
                    set_config('bbdkp_date_format', request_var('date_format', ''), true);
                    set_config('bbdkp_lang', request_var('language', 'en'), true);
                    set_config('bbdkp_show_3darmory', request_var('show3darmory', 0), true);
                    
                    //standings
                    set_config('bbdkp_hide_inactive', (isset($_POST['hide_inactive'])) ? request_var('hide_inactive', '') : '0', true);
                    set_config('bbdkp_inactive_period', request_var('inactive_period', 0), true);
                    set_config('bbdkp_list_p1', request_var('list_p1', 0), true);
                    set_config('bbdkp_list_p2', request_var('list_p2', 0), true);
                    set_config('bbdkp_list_p3', request_var('list_p3', 0), true);

                    //events					
                    set_config('bbdkp_user_elimit', request_var('bbdkp_user_elimit', 0), true);
                    
                    //adjustments
                    set_config('bbdkp_user_alimit', request_var('bbdkp_user_alimit', 0), true);
                    set_config('bbdkp_active_point_adj', request_var('bbdkp_active_point_adj', 0.0), true);
                    set_config('bbdkp_inactive_point_adj', request_var('bbdkp_inactive_point_adj', 0.0), true);
                    set_config('bbdkp_starting_dkp', request_var('starting_dkp', 0.0), true);
                    
                    //items
                    set_config('bbdkp_user_ilimit', request_var('bbdkp_user_ilimit', 0), true);

					//raids                    
                    set_config('bbdkp_user_rlimit', request_var('bbdkp_user_rlimit', 0), true);
                    
                    //epgp
                    set_config('bbdkp_epgp', request_var('epgp_activate', 0), true);
                    set_config('bbdkp_basegp', request_var('basegp', 0), true);

                    //decay         
                    set_config('bbdkp_decay', request_var('decay_activate', 0), true);           
					set_config('bbdkp_itemdecaypct', request_var('itemdecaypct', 0), true);
                    set_config('bbdkp_raiddecaypct',   request_var('raiddecaypct', 0), true);
                    set_config('bbdkp_decayfrequency', request_var('decayfreq', 0), true);
                    set_config('bbdkp_decayfreqtype',  request_var('decayfreqtype', 0), true);

                    //time
                    set_config('bbdkp_timebased', request_var('timebonus_activate', 0.00), true);
                    set_config('bbdkp_dkptimeunit', request_var('dkptimeunit', 0.00), true);
                    set_config('bbdkp_timeunit', request_var('timeunit', 0.00), true);
                    set_config('bbdkp_standardduration', request_var('standardduration', 0.00), true);
                    
                    //zerosum
                    set_config('bbdkp_zerosum', request_var('zerosum_activate', 0), true);
                    set_config('bbdkp_bankerid', request_var('zerosumbanker', 0), true);
                    set_config('bbdkp_zerosumdistother', request_var('zerosumdistother', 0), true);
                    
                    $cache->destroy('config');
                    trigger_error('Settings saved.' . $link, E_USER_NOTICE);
                }
                
         		if ($timebonus_synchronise) 
                {
                }	
         		
                if ($timebonus_synchronise) 
                {
                	
                }	
                
                if($decay_synchronise)
                {
                	
                	if (confirm_box ( true )) 
					{
						// decay this item
						if ( !class_exists('acp_dkp_raid')) 
						{
							require($phpbb_root_path . 'includes/acp/acp_dkp_raid.' . $phpEx); 
						}
						$acp_dkp_raid = new acp_dkp_raid;
						$count = $acp_dkp_raid->sync_decay($config['bbdkp_decay']);
						
						trigger_error ( sprintf($user->lang ['RESYNC_DECAY_SUCCESS'], $count) . $link , E_USER_NOTICE );
					}
					else 
					{
									
						$s_hidden_fields = build_hidden_fields ( array (
							'decay_synchronise' 	  => true, 
						));
			
						$template->assign_vars ( array (
							'S_HIDDEN_FIELDS' => $s_hidden_fields ) );
						
						confirm_box ( false, sprintf($user->lang['RESYNC_DECAY_CONFIRM'] ), $s_hidden_fields );
						
					}
					
                }
                
                $languages = array(
                	'de'	=> $user->lang['LANG_DE'], 
                	'en' 	=> $user->lang['LANG_EN'],
                	'fr'	=> $user->lang['LANG_FR'],
                );
                
                $s_lang_options = '';
                foreach ( $languages as $lang => $langname )
				{
                   	$selected = ($config['bbdkp_lang'] == $lang) ? ' selected="selected"' : '';
					$s_lang_options .= '<option value="' . $lang . '" '.$selected.'> ' . $langname . '</option>';  					
					                  
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
                $installed_game = $games[$config['bbdkp_default_game']];
                $regions = array(
                	'EU'     			=> $user->lang['REGIONEU'], 
                	'US'     			=> $user->lang['REGIONUS'],  
                );

                foreach($regions as $regionid => $regionvalue)
    		    {
        			$template->assign_block_vars ( 'region_row', array (
    	    			'VALUE' => $regionid, 
    		    		'SELECTED' => ($regionid == $config['bbdkp_default_region'] ) ? ' selected="selected"' : ''  ,
    			    	'OPTION' =>   $regionvalue  ));  
    		    }
    		    
    		    //roster layout
                $rosterlayoutlist = array(
    				0   => $user->lang['ARM_STAND'], 
    				1   => $user->lang['ARM_CLASS'], 
				 );
				    
				foreach($rosterlayoutlist as $lid => $lname)
    		    {
        			$template->assign_block_vars ( 'rosterlayout_row', array (
    	    			'VALUE' => $lid, 
    		    		'SELECTED' => ($lid == $config['bbdkp_roster_layout'] ) ? ' selected="selected"' : ''  ,
    			    	'OPTION' =>   $lname  ));  
    		    }
    		    
    		    
    		    $freqtypes = array(
                	0	=> $user->lang['FREQ0'], 
                	1 	=> $user->lang['FREQ1'], 
                	2	=> $user->lang['FREQ2']
                );
         		
                $s_freqtype_options = '';
                foreach ( $freqtypes as $key => $type )
				{
                   	$selected = ($config['bbdkp_decayfreqtype'] == $key) ? ' selected="selected"' : '';
					$s_freqtype_options .= '<option value="' . $key . '" '.$selected.'> ' . $type . '</option>';  					     
				}
				
				$s_bankerlist_options = ''; 
				$sql = 'select member_id, member_name from ' . MEMBER_LIST_TABLE . " where member_status = '1'"; 
				$result = $db->sql_query ($sql);
				while ($row = $db->sql_fetchrow ($result))
				{
				 	$selected = ($config['bbdkp_bankerid'] == $row['member_id']) ? ' selected="selected"' : '';
					$s_bankerlist_options .= '<option value="' . $row['member_id'] . '" '.$selected.'> ' . $row['member_name'] . '</option>';  
				}

    		    add_form_key('acp_dkp');
                
                $template->assign_vars(array(
                	'S_LANG_OPTIONS'	=> $s_lang_options, 
                	'SHOW3DARMORY'		=> $config['bbdkp_show_3darmory'], 
                	'GUILDTAG' 			=> $config['bbdkp_guildtag'] , 
                	'REALM' 			=> $config['bbdkp_default_realm'] , 
                	'EQDKP_START_DD' 	=> date('d', $config['bbdkp_eqdkp_start']) , 
                	'EQDKP_START_MM' 	=> date('m', $config['bbdkp_eqdkp_start']) , 
                	'EQDKP_START_YY' 	=> date('Y', $config['bbdkp_eqdkp_start']) ,
                	'DATE_FORMAT' 		=> $config['bbdkp_date_format'] , 
                	'DKP_NAME' 			=> $config['bbdkp_dkp_name'] ,
                	'DEFAULT_GAME' 		=> $installed_game ,
                	'HIDE_INACTIVE_YES_CHECKED' => ($config['bbdkp_hide_inactive'] == '1') ? ' checked="checked"' : '' , 
                	'HIDE_INACTIVE_NO_CHECKED' => ($config['bbdkp_hide_inactive'] == '0') ? ' checked="checked"' : '' , 
                	'USER_ELIMIT' 		=> $config['bbdkp_user_elimit'] , 
                	'USER_NLIMIT' 		=> $config['bbdkp_user_nlimit'] , 
                	'INACTIVE_PERIOD' 	=> $config['bbdkp_inactive_period'] , 
                	'LIST_P1' 			=> $config['bbdkp_list_p1'] , 
                	'LIST_P2' 			=> $config['bbdkp_list_p2'] , 
                    'LIST_P3' 			=> $config['bbdkp_list_p3'] ,
                	'F_SHOWACHIEV'  	=> $config['bbdkp_show_achiev'], 
                	'S_FREQTYPE_OPTIONS' => $s_freqtype_options,
                	'USER_ALIMIT' 		=> $config['bbdkp_user_alimit'] , 
                	'STARTING_DKP'		=>	$config['bbdkp_starting_dkp'],
                	'INACTIVE_POINT' 	=> $config['bbdkp_inactive_point_adj'] ,
                	'ACTIVE_POINT' 		=> $config['bbdkp_active_point_adj'] , 
                	'USER_ILIMIT' 		=> $config['bbdkp_user_ilimit'] , 
                	'USER_RLIMIT' 		=> $config['bbdkp_user_rlimit'] , 

                	//epgp
                	'F_EPGPACTIVATE'	=> $config['bbdkp_epgp'],
                	'BASEGP'			=> $config['bbdkp_basegp'] , 
						
                	//decay
                	'F_DECAYACTIVATE'	=> $config['bbdkp_decay'] ,
                	'ITEMDECAYPCT'		=> $config['bbdkp_itemdecaypct'] ,
                	'RAIDDECAYPCT' 	 	=> $config['bbdkp_raiddecaypct'] ,
        			'DECAYFREQ'			=> $config['bbdkp_decayfrequency'] ,
					'S_FREQTYPE_OPTIONS' =>	$s_freqtype_options,
                		 
					//time dkp
					'F_TIMEBONUSACTIVATE' => $config['bbdkp_timebased'] ,
                	'DKPTIMEUNIT'		=> $config['bbdkp_dkptimeunit'] ,
		            'TIMEUNIT'			=> $config['bbdkp_timeunit'] ,
		            'STANDARDDURATION'	=> $config['bbdkp_standardduration'] ,

                	//zs
         			'F_ZEROSUMACTIVATE'	=> $config['bbdkp_zerosum'], 
                	'S_BANKER_OPTIONS'	=> $s_bankerlist_options, 
                	'F_ZEROSUM_DISTOTHER' => $config['bbdkp_zerosumdistother'],
                ));
                
                $this->page_title = 'ACP_DKP_CONFIG';
                $this->tpl_name = 'dkp/acp_' . $mode;
                break;
            
             /*************************
             * PORTAL CONFIG
             **************************/
            case 'dkp_indexpageconfig':
                
                $submit = (isset($_POST['update'])) ? true : false;
                
                if ($submit) 
                {
                    if (!check_form_key('acp_dkp_portal'))
                	{
                		trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);	
                	}
                	
                    set_config('bbdkp_news_forumid', request_var('news_id', 0), true);
                    set_config('bbdkp_recruit_forumid', request_var('rec_id', 0), true);
                    set_config('bbdkp_n_news', request_var('n_news', 0), true);
                    set_config('bbdkp_n_items',  request_var('n_items', 0), true);
                    set_config('bbdkp_recruitment',  request_var('bbdkp_recruitment', 0), true);
                    set_config('bbdkp_portal_loot', request_var('show_lootblock', 0), true);
                    set_config('bbdkp_portal_bossprogress', request_var('show_bosspblock', 0), true);
                    set_config('bbdkp_portal_recruitment', request_var('show_recrblock', 0), true);
                    set_config('bbdkp_portal_links', request_var('show_linkblock', 0), true);
                    set_config('bbdkp_portal_menu', request_var('show_menublock', 0), true);	
                    
                    $sql = "SELECT class_id FROM " . CLASS_TABLE . " where class_id > 0 order by class_id ";
                    $result = $db->sql_query($sql);
                    while ($row = $db->sql_fetchrow($result)) 
                    {
                        $sql = 'UPDATE ' . CLASS_TABLE . ' SET tank = ' . request_var('T' . $row['class_id'], 0) . ' where class_id = ' . $row['class_id'];
                        $db->sql_query($sql);
                        $sql = 'UPDATE ' . CLASS_TABLE . ' SET heal = ' . request_var('H' . $row['class_id'], 0) . ' where class_id = ' . $row['class_id'];
                        $db->sql_query($sql);
                        $sql = 'UPDATE ' . CLASS_TABLE . ' SET dps = ' . request_var('D' . $row['class_id'], 0) . ' where class_id = ' . $row['class_id'];
                        $db->sql_query($sql);
                    }
                    trigger_error($user->lang['ADMIN_PORTAL_SETTINGS_SAVED'] . $link, E_USER_NOTICE);
                }

                // get forum config
                $news_forumid = $config['bbdkp_news_forumid'];
                $recruit_forumid  = $config['bbdkp_recruit_forumid'];
                
                // get list of forums
                $sql = "SELECT * FROM " . FORUMS_TABLE . "";
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result)) 
                {
                    $forum_ids[$row['forum_name']] = $row['forum_id'];
                }
                
                // loop forum list for match, add to template
                foreach ($forum_ids as $d_name => $d_value) 
                {
                    $template->assign_block_vars('news_id', array('VALUE' => $d_value , 
                    	'SELECTED' => ($d_value == $news_forumid) ? ' selected="selected"' : '' , 
                    	'OPTION' => $d_name));
                }
                      
                foreach ($forum_ids as $d_name => $d_value) 
                {
                    $template->assign_block_vars('rec_id', array('VALUE' => $d_value , 
                    'SELECTED' => ($d_value == $recruit_forumid) ? ' selected="selected"' : '' , 
                    'OPTION' => $d_name));
                }

                // number of news and items to show on front page
                $n_news = $config['bbdkp_n_news']; 
                $n_items = $config['bbdkp_n_items'];  
                
                // recruitment statuses               
                $recstatus = array(0 => "Closed" ,1 => "Open");
                            
                foreach ($recstatus as $d_value => $d_name) 
                {
                    $template->assign_block_vars('recruitment_status_row', array(
                    	'VALUE' => $d_value , 
                    	'SELECTED' => ($d_value == $config['bbdkp_recruitment']) ? ' selected="selected"' : '' , 
                    	'OPTION' => $d_name));
                }
                
                
                $classrecstatus = array(
					0 => "N/A",
					1 => "Closed", 
					2 => "Low", 
					3 => "Medium", 
					4 => "High", 
                 ); 
                 
                $classreccolor = array(
					0 => "bullet_white.png",
					1 => "bullet_white.png", 
					2 => "bullet_yellow.png", 
					3 => "bullet_red.png" , 
					4 => "bullet_purple.png" , 
                 ); 

                 // get recruitment statuses from class table

                $sql_array = array(
				    'SELECT'    => 	' c.class_id, l.name as class_name,
				    				  c.imagename, c.dps, c.tank, c.heal ', 
				    'FROM'      => array(
				        CLASS_TABLE 	=> 'c',
				        BB_LANGUAGE		=> 'l', 
				    	),
				    'WHERE'		=> " c.class_id > 0 and l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",   				    	
					'ORDER_BY'	=> ' c.class_id ',
				    );
				    
				$sql = $db->sql_build_query('SELECT', $sql_array);
                
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result)) 
                {
                    
                    $class[$row['class_id']] =$row['class_name'] ; 
                    //class constants                    
                     $template->assign_block_vars('recruitment', array(
                        'CLASSID' => $row['class_id'],
                        'CLASS' => $row['class_name'],                     
                        'TANKCOLOR' => $classreccolor[$row['tank']] ,
                        'HEALCOLOR' => $classreccolor[$row['heal']] ,
                        'DPSCOLOR' =>  $classreccolor[$row['dps']],
					    ));
					    
					    foreach ($classrecstatus as $prio => $description) 
                        {
                            $template->assign_block_vars('recruitment.tankstatus_row', array(
                        	'VALUE' => $prio , 
                        	'SELECTED' => ($prio == $row['tank']) ? ' selected="selected"' : '' , 
                        	'OPTION' =>  $description, 
                            ));
                            
                           $template->assign_block_vars('recruitment.healstatus_row', array(
                        	'VALUE' => $prio , 
                        	'SELECTED' => ($prio == $row['heal']) ? ' selected="selected"' : '' , 
                        	'OPTION' => $description,
                           ));
                           
                            $template->assign_block_vars('recruitment.dpsstatus_row', array(
                        	'VALUE' => $prio , 
                        	'SELECTED' => ($prio == $row['dps']) ? ' selected="selected"' : '' , 
                        	'OPTION' => $description,
                           ));
                        }
                }
                
                add_form_key('acp_dkp_portal');
                
                $template->assign_vars(array(
                    'N_NEWS' => $n_news , 
                    'SHOW_REC_YES_CHECKED' => ($config['bbdkp_portal_recruitment'] == '1') ? ' checked="checked"' : '' , 
                	'SHOW_REC_NO_CHECKED' => ($config['bbdkp_portal_recruitment'] == '0') ? ' checked="checked"' : '' , 

                    'SHOW_LOOT_YES_CHECKED' => ($config['bbdkp_portal_loot'] == '1') ? ' checked="checked"' : '' , 
                	'SHOW_LOOT_NO_CHECKED' => ($config['bbdkp_portal_loot'] == '0') ? ' checked="checked"' : '' , 
                	'N_ITEMS' => $n_items, 

                    'SHOW_BOSS_YES_CHECKED' => ($config['bbdkp_portal_bossprogress'] == '1') ? ' checked="checked"' : '' , 
                	'SHOW_BOSS_NO_CHECKED' => ($config['bbdkp_portal_bossprogress'] == '0') ? ' checked="checked"' : '' , 
                	
                    'SHOW_LINK_YES_CHECKED' => ($config['bbdkp_portal_links'] == '1') ? ' checked="checked"' : '' , 
                	'SHOW_LINK_NO_CHECKED' => ($config['bbdkp_portal_links'] == '0') ? ' checked="checked"' : '' , 
                
               	    'SHOW_MENU_YES_CHECKED' => ($config['bbdkp_portal_menu'] == '1') ? ' checked="checked"' : '' , 
                	'SHOW_MENU_NO_CHECKED' => ($config['bbdkp_portal_menu'] == '0') ? ' checked="checked"' : '' , 
                ));
                
                
                $this->page_title = $user->lang['ACP_INDEXPAGE'];
                $this->tpl_name = 'dkp/acp_' . $mode;
                break;
                
                
			/**************
			 * 
			 * DKP LOGS
			 * 
			 **************/
            case 'dkp_logs':
                $this->page_title = 'ACP_DKP_LOGS';
                $this->tpl_name = 'dkp/acp_' . $mode;
                
                $valid_action_types = array(
                	'L_ACTION_DEFAULT_DKP_CHANGED' => $user->lang['ACTION_DEFAULT_DKP_CHANGED'],  
                	'L_ACTION_DKPSYS_ADDED' => $user->lang['ACTION_DKPSYS_ADDED'] ,
                	'L_ACTION_DKPSYS_UPDATED' => $user->lang['ACTION_DKPSYS_UPDATED'] ,
                	'L_ACTION_DKPSYS_DELETED' => $user->lang['ACTION_DKPSYS_DELETED'] ,
                	'L_ACTION_EVENT_ADDED' => $user->lang['ACTION_EVENT_ADDED'] ,
                	'L_ACTION_EVENT_UPDATED' => $user->lang['ACTION_EVENT_UPDATED'] ,
                	'L_ACTION_EVENT_DELETED' => $user->lang['ACTION_EVENT_DELETED'] ,
                    'L_ACTION_GUILD_ADDED' => $user->lang['ACTION_GUILD_ADDED'] ,
                	'L_ACTION_INDIVADJ_ADDED' => $user->lang['ACTION_INDIVADJ_ADDED'] ,
                	'L_ACTION_INDIVADJ_UPDATED' => $user->lang['ACTION_INDIVADJ_UPDATED'] ,
                	'L_ACTION_INDIVADJ_DELETED' => $user->lang['ACTION_INDIVADJ_DELETED'] ,
                	'L_ACTION_ITEM_ADDED' => $user->lang['ACTION_ITEM_ADDED'] ,
                	'L_ACTION_ITEM_UPDATED' => $user->lang['ACTION_ITEM_UPDATED'] ,
                	'L_ACTION_ITEM_DELETED' => $user->lang['ACTION_ITEM_DELETED'] ,
                	'L_ACTION_LOG_DELETED' => $user->lang['ACTION_LOG_DELETED'],
                	'L_ACTION_MEMBER_ADDED' => $user->lang['ACTION_MEMBER_ADDED'] ,
                	'L_ACTION_MEMBER_UPDATED' => $user->lang['ACTION_MEMBER_UPDATED'] ,
                	'L_ACTION_MEMBER_DELETED' => $user->lang['ACTION_MEMBER_DELETED'] ,
                	'L_ACTION_MEMBERDKP_UPDATED' => $user->lang['ACTION_MEMBERDKP_UPDATED'] ,
                	'L_ACTION_MEMBERDKP_DELETED' => $user->lang['ACTION_MEMBERDKP_DELETED'] ,
                	'L_ACTION_RANK_ADDED' => $user->lang['ACTION_RANK_ADDED'] ,
                	'L_ACTION_RANK_UPDATED' => $user->lang['ACTION_RANK_UPDATED'] ,
                	'L_ACTION_RANK_DELETED' => $user->lang['ACTION_RANK_DELETED'] ,
                	'L_ACTION_NEWS_ADDED' => $user->lang['ACTION_NEWS_ADDED'] ,
                	'L_ACTION_NEWS_UPDATED' => $user->lang['ACTION_NEWS_UPDATED'] ,
                	'L_ACTION_NEWS_DELETED' => $user->lang['ACTION_NEWS_DELETED'] ,
                	'L_ACTION_RAID_ADDED' => $user->lang['ACTION_RAID_ADDED'] ,
                	'L_ACTION_RAID_UPDATED' => $user->lang['ACTION_RAID_UPDATED'] ,
                	'L_ACTION_RAID_DELETED' => $user->lang['ACTION_RAID_DELETED'] , 
                	'L_ACTION_CTRT_CONFIG_UPDATED' => $user->lang['ACTION_RT_CONFIG_UPDATED'],  
                	); 

                $log_id = (isset($_GET[URI_LOG])) ? request_var(URI_LOG, 0) : false;
                
                $search = (isset($_GET['search'])) ? true : false;
                
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
                        
                        /*deleting logs */
                        $deletemark = (!empty($_POST['delmarked'])) ? true : false;
						$marked		= request_var('mark', array(0));
						if($deletemark)
						{
							$this->delete_log($marked);
						}
						
                        //find number of logs
                        $sql_array = array(
    						'SELECT'    => 'count(*) as logcount ',
                            'FROM'     => array(
                                LOGS_TABLE  => 'l', 
                                USERS_TABLE => 'u', 
                                ),
                            'WHERE' => 'u.user_id = l.log_userid'
                         );
                        $total_sql = $db->sql_build_query('SELECT', $sql_array);
                        $result4 = $db->sql_query($total_sql);
                        $total_logs = (int) $db->sql_fetchfield('logcount', false, $result4);
                        $db->sql_freeresult($result4);
                        unset($sql_array); 
                        
                        $sort_order = array(
                        0 => array('log_date desc' , 'log_date') , 
                        1 => array('log_type' , 'log_type desc') , 
                        2 => array('username' , 'username dsec') , 
                        3 => array('log_ipaddress' , 'log_ipaddress desc') , 
                        4 => array('log_result' , 'log_result desc'));
                        
                        $current_order = switch_order($sort_order);
                        
                        $sql_array = array(
    						'SELECT'    => 'log_id, log_date, log_type, username, log_ipaddress, log_result ',
                            'FROM'     => array(
                                LOGS_TABLE  => 'l', 
                                USERS_TABLE => 'u', 
                                ),
                            'WHERE' => 'u.user_id=l.log_userid'
                            
                         );
                        
                       $addon_sql = '';
                       $search_term = '';
                        
                       // If they're looking for something specific, we have to figure out what that is
                       if ($search) 
                       {
                            $search_term = request_var('search', '');

                            // Check if it's an action
                            if (array_search($search_term, $valid_action_types)) 
                            {
                                $sql_array['WHERE'] = " u.user_id=l.log_userid and l.log_type='" . $db->sql_escape(array_search($search_term, $valid_action_types)) . "'";
                            } 
                            
                            // Check it's an IP
                            elseif (preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $search_term)) 
                            {
                                $sql_array['WHERE'] = "  u.user_id=l.log_userid and l.log_ipaddress='" . $db->sql_escape($search_term) . "'";
                            } 
                            
                            // Still going? It's a username
                            elseif ($search_term != '')
                            {
                                $sql_array['WHERE'] = " u.user_id=l.log_userid and u.user_id='" . $db->sql_escape($search_term) . "'";
                            }
                            
                            else
                            {
                                //empty searchterm, dont add criterium
                            }
                       }
                        
                       $sql_array['ORDER_BY'] = $current_order['sql'];
                       
                       $total_sql = $db->sql_build_query('SELECT', $sql_array);
                        
                       $start = request_var('start', 0);
                        
                       $result4 = $db->sql_query_limit($total_sql, USER_LLIMIT, $start);

                       while ($log = $db->sql_fetchrow($result4)) 
                        {
                            $log['log_type'] = $this->lang_replace($log['log_type']);
                            
                            $log['log_result'] = $this->lang_replace($log['log_result']);
                            
                            $template->assign_block_vars('logs_row', array(
                            	'DATE'         => (! empty($log['log_date'])) ? date('d.m.y - H:i', $log['log_date']) : '&nbsp;' , 
                            	'TYPE'         => (! empty($log['log_type'])) ? $log['log_type'] : '&nbsp;' , 
                            	'U_VIEW_LOG'   => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs") . '&amp;' . URI_LOG . '=' . $log['log_id'] , 
                            	'ID'           => $log['log_id'] ,
                            	'USER'         => $log['username'] , 
                            	'IP'	       => $log['log_ipaddress'] , 
                            	'RESULT'       => $log['log_result'] , 
                            	'C_RESULT'     => ($log['log_result'] == $user->lang['SUCCESS']) ? 'positive' : 'negative' , 
                            	'ENCODED_TYPE' => urlencode($log['log_type']) , 
                            	'ENCODED_USER' => urlencode($log['username']) , 
                            	'ENCODED_IP'   => urlencode($log['log_ipaddress']))
                            );
                        }
                        
                        $db->sql_freeresult($result4);
                        $template->assign_vars(array(
                        	'S_LIST' 		=> true , 
                        	'L_TITLE' 		=> $user->lang['ACP_DKP_LOGS'] ,
                        	'L_EXPLAIN' 	=> $user->lang['ACP_DKP_LOGS_EXPLAIN'] ,
                        	'O_DATE' 		=> $current_order['uri'][0] , 
                        	'O_TYPE' 		=> $current_order['uri'][1] , 
                        	'O_USER' 		=> $current_order['uri'][2] , 
                        	'O_IP'	 		=> $current_order['uri'][3] , 
                        	'O_RESULT' 		=> $current_order['uri'][4] , 
                        	'U_LOGS' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs&amp;") . '&amp;search=' . $search_term . '&amp;start=' . $start . '&amp;' , 
                        	'U_LOGS_SEARCH' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs&amp;") . '&amp;' , 
                        	'CURRENT_ORDER' => $current_order['uri']['current'] , 'START' => $start , 
                        	'VIEWLOGS_FOOTCOUNT' => sprintf($user->lang['VIEWLOGS_FOOTCOUNT'], $total_logs, USER_LLIMIT) , 
                        	'VIEWLOGS_PAGINATION' => generate_pagination(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs&amp;") . '&amp;search=' . $search_term . '&amp;o=' . $current_order['uri']['current'], $total_logs, USER_LLIMIT, $start))
                        );
                        break;
                        
                    case 'view':

                        // get logged action by logid
                       $sql_array = array(
    						'SELECT'    => 'l.*, u.username',
                            'FROM'     => array(
                                LOGS_TABLE  => 'l'
                                ),
                            'LEFT_JOIN' => array(
                                array(
                                    'FROM'  => array(USERS_TABLE => 'u'),
                                    'ON'    => 'u.user_id=l.log_userid'
                                ), 
                             ),
                            'WHERE'=> 'log_id=' . (int) $log_id 
                        );

                        $total_sql = $db->sql_build_query('SELECT', $sql_array);
                        $result = $db->sql_query($total_sql);
                       
                        $log = $db->sql_fetchrow($result);
                        $db->sql_freeresult($result);
                        
                        preg_match_all("/{.*?}/", $log['log_action'], $to_replace);

                        $xml = $log['log_action'];
                        //$xml = str_replace("L_", '', $xml);

                        // transform xml into array
                        $array_temp = (array) simplexml_load_string($xml);

                        // get each element
                        $log_action = array();
                        foreach ($array_temp as $key => $value) 
                        {
                            if (is_array($value))
                            {
                                $value = (array) $value;
                                $log_action[$key] = trim($value[0]);
                            }
                            else
                            {
                                $log_action[$key] = trim($value);
                            }

                        }
                        
                        // loop the elements and fill template
                        foreach ($log_action as $k => $v) 
                        {
                            // only select non-header rows
                            if ($k != 'header') 
                            {
                                $template->assign_block_vars('log_row', array(
                                 	'KEY' => $this->lang_replace($k) . ':' ,
                                 	'VALUE' =>  $v
                                ));
                            }
                        }
                        
                        // fill constant template elements
                        $template->assign_vars(array(
                        	'S_LIST'         => false , 
                        	'L_TITLE'        => $user->lang['ACP_DKP_LOGS'] ,
                        	'L_EXPLAIN'      => $user->lang['ACP_DKP_LOGS_EXPLAIN'] ,
                        	'LOG_DATE'       => (! empty($log['log_date'])) ? date($config['bbdkp_date_format'], $log['log_date']) : '&nbsp;' , 
                        	'LOG_USERNAME'   => (! empty($log['username'])) ? $log['username'] : '&nbsp;' , 
                        	'LOG_IP_ADDRESS' => $log['log_ipaddress'] , 
                        	'LOG_SESSION_ID' => $log['log_sid'] , 
                        	'LOG_ACTION'     => (! empty($log_action['header'])) ? $valid_action_types[ $log_action['header']] : '&nbsp;'));
                     
                       break;
                }
                break;

            
			
        }
    }
    
	/**
	 * gets the verbose log entry
	 */
   function getaction($haystack,$tag)
   {  
        $found=''; 
   		
   		$array_temp = (array) simplexml_load_string($haystack);
        foreach ($array_temp as $key => $value) 
        {
        	if ($key == $tag)
        	{
 	           $found = $value;
        	}
        }
        return $found;
   
   }
   
   /**
    * Replaces an language array key preceded by 'L_' with its value or if not found, with the key
    * 
    */
    function lang_replace ($variable)
    {
        global $user;
        preg_match("/L_(.+)/", $variable, $to_replace);
        if ((isset($to_replace[1])) && (isset($user->lang[$to_replace[1]]))) 
        {
            $variable = str_replace('L_' . $to_replace[1], $user->lang[$to_replace[1]], $variable);
        }
        return $variable;
    }
                
    /**
     * 
     * deletes marked bbDKP log entries
     */
	function delete_log ($marked)
    {
    	global $db, $user, $phpEx;
    	
    	//if marked array isnt empty
    	if (sizeof($marked) && is_array($marked))
    	{
		    if (confirm_box(true))
			{
				//they hit yes
				$sql = 'DELETE FROM ' . LOGS_TABLE . ' WHERE 1=1 ';

				$sql_in = array();
				foreach ($marked as $mark)
				{
					$sql_in[] = $mark;
				}
				$sql .= ' AND ' . $db->sql_in_set('log_id', $sql_in);
				$db->sql_query($sql);
				
				//ACTION_LOG_DELETED
				$log_action = array(
                	'header'  => 	'L_ACTION_LOG_DELETED',
                	'L_ADDED_BY' => $user->data['username'],
                  	'L_LOG_ID'   => implode(",", $sql_in), 
					);
                  
                  $this->log_insert(
                  array(
                  	'log_type'   => $log_action['header'],
                  	'log_action' => $log_action)
                  );
                unset($sql_in);     
                            
				//redirect to listing
				$meta_info = append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs");
				meta_refresh(3, $meta_info);
	
				$message =  '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs") . '">' .
				$user->lang['RETURN_LOG'] . '</a><br />' . 
					sprintf($user->lang['ADMIN_LOG_DELETE_SUCCESS'],implode($marked));
				trigger_error($message, E_USER_WARNING);
					 
			}
			else
			{
				// display confirmation 
				confirm_box(false, $user->lang['CONFIRM_DELETE_BBDKPLOG'], build_hidden_fields(array(
					'delmarked'	=> true,
					'mark'		=> $marked))
				);
			}
			
			// they hit no
			$message =  '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=dkp_logs") . '">' . $user->lang['RETURN_LOG'] .'</a><br />' . 
				sprintf($user->lang['ADMIN_LOG_DELETE_FAIL'],implode($marked));
			
			trigger_error($message, E_USER_WARNING);
			
    	}
			
    }
        
}
?>