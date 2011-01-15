<?php
/**
* This class manages member DKP
* 
* @package bbDkp.acp
* @author sajaki9@gmail.com
* @version $Id$
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

class acp_dkp_mdkp extends bbDkp_Admin
{
	var $u_action;

	function main($id, $mode) 
	{
		global $db, $user, $auth, $template, $sid, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang(array('mods/dkp_admin'));
		$user->add_lang(array('mods/dkp_common'));
		$link = '<br /><a href="'.append_sid("index.$phpEx", "i=dkp_mdkp&mode=mm_listmemberdkp") . '"><h3>'.
			$user->lang['RETURN_DKPINDEX'].'</h3></a>'; 
		
		switch ($mode)
		{
			/************************************
				  LIST DKP
			*************************************/
			case 'mm_listmemberdkp':
				
				/* initialise */
				$dkpsys_id = null;
				
				/***  sort  ***/
				$sort_order = array(
					0 => array('dkpsys_name', 'dkpsys_name desc'),	
					1 => array('member_name', 'member_name desc'),
					2 => array('member_earned desc', 'member_earned'),
					3 => array('member_spent desc', 'member_spent'),
					4 => array('member_adjustment desc', 'member_adjustment'),
					5 => array('member_current desc', 'member_current'),
					6 => array('member_lastraid desc', 'member_lastraid'),
					7 => array('member_level desc', 'member_level'),
					8 => array('member_class', 'member_class desc'),
					9 => array('rank_name', 'rank_name desc'),
					10 => array('class_armor_type', 'class_armor_type desc'),
					11 => array('member_status', 'm.member_status desc')
				);
				$current_order = switch_order($sort_order);
				
				$member_count = 0;
				$previous_data = '';
				$sort_index = explode('.', $current_order['uri']['current']);
				$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
				
				
				/* check if page was posted back */
				$submit_dkpsys = (isset($_POST['submit_dkpsys'])) ? true : false;
				$submit_activate = (isset($_POST['submit_activate'])) ? true : false;
				$getit =  (isset($_GET[URI_DKPSYS])) ? true : false;
				
				/***  DKPSYS drop-down query ***/
				$eq_dkpsys = array();
				$sql = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default  
						FROM ' . DKPSYS_TABLE .' 
						ORDER BY dkpsys_name';
				$resultdkpsys = $db->sql_query($sql);
				
				if ($submit_activate)
				{
				    $dkpsys_id = request_var('dkpsys', 0);
				    while ( $row = $db->sql_fetchrow($resultdkpsys) )
					{
					$template->assign_block_vars('dkpsys_row', array(
						'VALUE' => $row['dkpsys_id'],
						'SELECTED' => ( $row['dkpsys_id'] == $dkpsys_id ) ? ' selected="selected"' : '',
						'OPTION'   => ( !empty($row['dkpsys_name']) ) ? $row['dkpsys_name'] : '(None)')
					);
					$eq_dkpsys[] = null;	
					}
					$db->sql_freeresult($resultdkpsys);	
					// process the activation change
					
					$active_members = request_var('activate_ids', array(0) ); 

                    $db->sql_transaction('begin'); 
                    
                    $sql1 = 'UPDATE ' . MEMBER_DKP_TABLE . "
                        SET member_status = '1' 
                        WHERE  member_dkpid  = " . $dkpsys_id . ' 
                        AND ' . $db->sql_in_set('member_id', $active_members, false, true);
					$db->sql_query($sql1);
                    
					$sql2 = 'UPDATE ' . MEMBER_DKP_TABLE . "
                        SET member_status = '0' 
                        WHERE  member_dkpid  = " . $dkpsys_id . ' 
                        AND ' . $db->sql_in_set('member_id', $active_members, true, true);
					$db->sql_query($sql2);
					
					$db->sql_transaction('commit'); 

				}
			
				if ($submit_dkpsys)
				{
					// get dkp pool value from popup
					$dkpsys_id = request_var('dkpsys_id', 0);
					
					// fill popup and set selected to Post value
					while ( $row = $db->sql_fetchrow($resultdkpsys) )
					{
					$template->assign_block_vars('dkpsys_row', array(
						'VALUE' => $row['dkpsys_id'],
						'SELECTED' => ( $row['dkpsys_id'] == $dkpsys_id ) ? ' selected="selected"' : '',
						'OPTION'   => ( !empty($row['dkpsys_name']) ) ? $row['dkpsys_name'] : '(None)')
					);
					$eq_dkpsys[] = null;	
					}
					
					$db->sql_freeresult($resultdkpsys);
				
				}
				elseif ($getit)
				{
					// get dkp pool value from popup $_GET
					$dkpsys_id = request_var(URI_DKPSYS, 0);
					
					// fill popup and set selected to Post value
					while ( $row = $db->sql_fetchrow($resultdkpsys) )
					{
					$template->assign_block_vars('dkpsys_row', array(
						'VALUE' => $row['dkpsys_id'],
						'SELECTED' => ( $row['dkpsys_id'] == $dkpsys_id ) ? ' selected="selected"' : '',
						'OPTION'   => ( !empty($row['dkpsys_name']) ) ? $row['dkpsys_name'] : '(None)')
					);
					$eq_dkpsys[] = null;	
					}
					
					$db->sql_freeresult($resultdkpsys);
				
				}
				else 
				// default pageloading 
				{
					// fill popup and set selected to default selection
					while ( $row = $db->sql_fetchrow($resultdkpsys) )
					{
					$template->assign_block_vars('dkpsys_row', array(
						'VALUE' => $row['dkpsys_id'],
						'SELECTED' => ( $row['dkpsys_default'] == "Y" ) ? ' selected="selected"' : '',
						'OPTION'   => ( !empty($row['dkpsys_name']) ) ? $row['dkpsys_name'] : '(None)')
					);
					$eq_dkpsys[] = null;	
					}

					// get dkp pool value from table
					
					/***  DKPSYS table  ***/
					$sql1 = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default  
						FROM ' . DKPSYS_TABLE . " 
						WHERE dkpsys_default = 'Y' 
						ORDER BY dkpsys_name";
					if ( !($result1 = $db->sql_query($sql1)) )
					
					{
						// theres no default dkp pool so just take first row in table
						$sql1 = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default  
						FROM ' . DKPSYS_TABLE ;	
						$result1 = $db->sql_query_limit($sql1, 1, 0);
						while ( $row = $db->sql_fetchrow($result1) )
						{
							$dkpsys_id = $row['dkpsys_id'];
						}						
					}
					else 
					{
						// get the default dkp value from DB 
						while ( $row = $db->sql_fetchrow($result1) )
						{
							$dkpsys_id = $row['dkpsys_id'];		
						}
						
					}
					$db->sql_freeresult($result1);
				}
				
				if ($dkpsys_id == null)
				/* in case of null then choose 0 > no dkpsys created yet */
				{
					$dkpsys_id = 0;				
				}
					
				
				$sql_array = array(
				    'SELECT'    => 'm.member_id,  
									a.member_name, 
									a.member_level, 
									(m.member_earned-m.member_spent+m.member_adjustment) AS member_current, 
									m.member_dkpid, 
									m.member_earned, 
									m.member_spent,
									m.member_status,    
									m.member_adjustment, 
									m.member_lastraid,
									s.dkpsys_name, 
									l.name AS member_class, 
									r.rank_name, 
									r.rank_prefix, 
									r.rank_suffix, 
									c.class_armor_type AS armor_type',
				 
				    'FROM'      => array(
				        MEMBER_LIST_TABLE 	=> 'a',
				        MEMBER_DKP_TABLE 	=> 'm', 
				        MEMBER_RANKS_TABLE  => 'r', 
				        CLASS_TABLE    		=> 'c', 
				        BB_LANGUAGE			=> 'l', 
				        DKPSYS_TABLE    	=> 's', 
					    ),
				 
				    'WHERE'     =>  "(a.member_rank_id = r.rank_id)
				    			AND (a.member_guild_id = r.guild_id)   
								AND (a.member_id = m.member_id) 
								AND (a.member_class_id = c.class_id)  
								AND (m.member_dkpid = s.dkpsys_id)   
								AND l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'    		
								AND (s.dkpsys_id = " . (int) $dkpsys_id . ')' ,
				
					'ORDER_BY' => $current_order['sql'], 
					);
 
				$sql = $db->sql_build_query('SELECT', $sql_array);
				
				if ( !($members_result = $db->sql_query($sql)) )
				{
					trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
				}
				$lines = 0;
				
				while ( $row = $db->sql_fetchrow($members_result) )
				{
					++$member_count;
					++$lines;
					$template->assign_block_vars('members_row', array(
					    'STATUS'        => ($row['member_status']== 1) ? 'Checked ' : '',
						'ID'            => $row['member_id'],
						'DKPID'         => $row['member_dkpid'],
						'DKPSYS_S'      => $dkpsys_id ,
						'DKPSYS_NAME'   => $row['dkpsys_name'],
						
						'NAME'          => $row['rank_prefix'] . $row['member_name'] . $row['rank_suffix'],
						'RANK'          => $row['rank_name'],
						'LEVEL'         => ( $row['member_level'] > 0 ) ? $row['member_level'] : '&nbsp;',
						'ARMOR'         => ( !empty($row['armor_type']) ) ? $row['armor_type'] : '&nbsp;',
						'CLASS'         => ( $row['member_class'] != 'NULL' ) ? $row['member_class'] : '&nbsp;',
						'EARNED'        => $row['member_earned'],
						'SPENT'         => $row['member_spent'],
						'ADJUSTMENT'    => $row['member_adjustment'],
						'CURRENT'       => $row['member_current'],
						'LASTRAID'      => ( !empty($row['member_lastraid']) ) ? date($config['bbdkp_date_format'], $row['member_lastraid']) : '&nbsp;',
						'C_ADJUSTMENT'  => $row['member_adjustment'],
						'C_CURRENT'     => $row['member_current'],
						'C_LASTRAID'    => 'neutral',
						'U_VIEW_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp") . '&amp;member_id='.$row['member_id'].  '&amp;'. URI_DKPSYS . '='.$row['member_dkpid']
						)
					); 
					
				}
				
				$db->sql_freeresult($members_result);
				
				/***  Labels  ***/
				
				$footcount_text = sprintf($user->lang['LISTMEMBERS_FOOTCOUNT'], $lines);
				
				$template->assign_vars(array(
					'F_MEMBERS' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_listmemberdkp&amp;") . '&amp;mode=mm_editmemberdkp',
					'L_TITLE'		=> $user->lang['ACP_DKP_LISTMEMBERDKP'],
					'L_EXPLAIN'		=> $user->lang['ACP_MM_LISTMEMBERDKP_EXPLAIN'],
					'BUTTON_NAME' => 'delete',
					'BUTTON_VALUE' => $user->lang['DELETE_SELECTED_MEMBERS'],
					'O_STATUS' => $current_order['uri'][11],
					'O_DKPSYS' => $current_order['uri'][0],
					'O_NAME' => $current_order['uri'][1],
					'O_RANK' => $current_order['uri'][9],
					'O_LEVEL' => $current_order['uri'][7],
					'O_CLASS' => $current_order['uri'][8],
					'O_ARMOR' => $current_order['uri'][10],
					'O_EARNED' => $current_order['uri'][2],
					'O_SPENT' => $current_order['uri'][3],
					'O_ADJUSTMENT' => $current_order['uri'][4],
					'O_CURRENT' => $current_order['uri'][5],
					'O_LASTRAID' => $current_order['uri'][6],
					'U_LIST_MEMBERDKP' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;" . URI_DKPSYS . "=". $dkpsys_id . "&amp;mode=mm_listmemberdkp&amp;") .'&amp;mod=list&amp;',		
					'S_NOTMM' => false,
					'LISTMEMBERS_FOOTCOUNT' => $footcount_text, 
		            'DKPSYS' => $dkpsys_id
					)
				);			
							
				$this->page_title = 'ACP_DKP_LISTMEMBERDKP';
				$this->tpl_name = 'dkp/acp_'. $mode;
			break;
			
			
			/************************************
				  DKP EDIT
			*************************************/
			case 'mm_editmemberdkp':
			    // invisible module
					$S_ADD = false;
					if (isset($_GET['member_id']) && isset($_GET[URI_DKPSYS]) )  
					{
						$sql_array = array(
					    'SELECT'    => '
					    	a.*, 
							(m.member_earned-m.member_spent+m.member_adjustment) AS member_current, 
							m.member_id, 
							m.member_dkpid, 
							m.member_earned, 
							m.member_spent,  
							m.member_adjustment, 
							m.member_lastraid,
							r1.name AS member_race,
							s.dkpsys_name, 
							l.name AS member_class, 
							r.rank_name, 
							r.rank_prefix, 
							r.rank_suffix, 
							c.class_armor_type AS armor_type ,
							c.colorcode, 
							c.imagename ', 
					 
					    'FROM'      => array(
					        MEMBER_LIST_TABLE 	=> 'a',
					        MEMBER_DKP_TABLE    => 'm',
					        MEMBER_RANKS_TABLE  => 'r',
							CLASS_TABLE 		=> 'c', 
							BB_LANGUAGE			=> 'l', 
					        DKPSYS_TABLE    	=> 's',
					    ),
					    
					     'LEFT_JOIN' => array(
					        array(
					            'FROM'  => array(BB_LANGUAGE => 'r1'),
					            'ON'    => "r1.attribute_id = a.member_race_id AND r1.language= '" . 
					        		$config['bbdkp_lang'] . "' AND r1.attribute = 'race'" 
					            )
					        ),
					 
					    'WHERE'     =>  " a.member_rank_id = r.rank_id 
					    				AND a.member_guild_id = r.guild_id  
										AND a.member_id = m.member_id 
										AND a.member_class_id = c.class_id  
										AND m.member_dkpid = s.dkpsys_id   
										AND l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'    
										AND s.dkpsys_id = " . request_var(URI_DKPSYS, 0) . '   
									    AND a.member_id = ' . request_var('member_id', 0),
						);
					 
						$sql = $db->sql_build_query('SELECT', $sql_array);
																		
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						
						$this->member = array(
								'member_id'         => $row['member_id'],
								'member_dkpid'		=> $row['member_dkpid'],  
								'member_dkpname'	=> $row['dkpsys_name'],  
								'member_name'       => $row['member_name'],
								'member_earned'     => $row['member_earned'],  
								'member_spent'      => $row['member_spent'],    
								'member_adjustment' => $row['member_adjustment'],  
								'member_current'    => $row['member_current'],  
								'member_race_id'    => $row['member_race_id'], 
								'member_race'       => $row['member_race'],
								'member_class_id'   => $row['member_class_id'],
								'member_class'      => $row['member_class'],
								'member_level'      => $row['member_level'], 
								'member_rank_id'    => $row['member_rank_id'],
								'member_rank'		=> $row['rank_name'],
								'imagename'			=> $row['imagename'],
								'colorcode'			=> $row['colorcode'], 
							);	
					}
				
					if ( !empty($this->member['member_name']) )  // ??
					{
						// Get their correct earned/spent
						$sql_array = array(
						    'SELECT'    => 'sum(r.raid_value) AS member_summa',
						    'FROM'      => array(
						        EVENTS_TABLE => 'e',
						        RAIDS_TABLE => 'r',
						        RAID_ATTENDEES_TABLE    => 'ra'
						    ),
						    'WHERE'     =>  ' ra.raid_id = r.raid_id 
						    	and e.event_id = r.event_id
								AND ra.member_id=' . $this->member['member_id'] . '
								AND e.event_dkpid=' . (int) $this->member['member_dkpid'],
						);
						 
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$correct_earned = $row['member_summa'];
						}
						$db->sql_freeresult($sql);
						
						$sql_array = array(
						    'SELECT'    => 'sum(item_value) AS member_summa',
						    'FROM'      => array(
							        DKPSYS_TABLE  => 'd',
							        EVENTS_TABLE  => 'e',
							        RAIDS_TABLE   => 'r',
							        ITEMS_TABLE   => 'i'
						    ),
						    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
					    				and e.event_id = r.event_id 
					    				and r.raid_id = i.raid_id 
										and i.member_id=' . $this->member['member_id'] . '
										and e.event_dkpid=' . (int) $this->member['member_dkpid'],
						);
						
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$correct_spent = $row['member_summa'];
						}
						$db->sql_freeresult($sql);
					}

					$update	 = (isset($_POST['update'])) ? true : false;
					$delete	 = (isset($_POST['delete'])) ? true : false;	
		            if ( $update || $delete )
                    {
                    	if (!check_form_key('mm_editmemberdkp'))
						{
							trigger_error('FORM_INVALID');
						}
        			}
        			
					if ($update)
					{
					
						$sql_array = array(
						    'SELECT'    => 'm.member_id, m.member_earned, m.member_spent, m.member_adjustment',
						    'FROM'      => array(
							        MEMBER_DKP_TABLE => 'm',
							        MEMBER_LIST_TABLE => 'l',
						    ),
						    'WHERE'     =>  'm.member_id = l.member_id and m.member_id=' . request_var('hidden_id', 0) . ' 
						    				 and m.member_dkpid=' . request_var('hidden_dkpid', 0),
						);
						
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);
						while ( $row = $db->sql_fetchrow($result) )
						{
    						$this->old_member = array(
								'member_id'			=> $row['member_id'],
								'member_earned'     => $row['member_earned'],
								'member_spent'      => $row['member_spent'],
								'member_adjustment' => $row['member_adjustment']);
						}
						$db->sql_freeresult($result);
						
						$db->sql_transaction('begin'); 
						$query = $db->sql_build_array('UPDATE', array(
								'member_earned'     => request_var('member_earned',0.00),
								'member_spent'      => request_var('member_spent', 0.00),
								'member_adjustment' => request_var('member_adjustment', 0.00))
						);
						
						$db->sql_query('UPDATE ' . MEMBER_DKP_TABLE . ' 
										SET ' . $query . ' 
						        WHERE member_id = ' . $this->old_member['member_id'] . '
								AND member_dkpid= ' . (int) request_var('hidden_dkpid',0) );
						
						$db->sql_transaction('commit'); 
									
						$log_action = array(
							'header'              => 'ACTION_MEMBERDKP_UPDATED',
							'L_EARNED_BEFORE'     => $this->old_member['member_earned'],
							'L_SPENT_BEFORE'      => $this->old_member['member_spent'],
							'L_ADJUSTMENT_BEFORE' => $this->old_member['member_adjustment'],
							'L_EARNED_AFTER'      => request_var('member_earned',0.00),
							'L_SPENT_AFTER'       => request_var('member_spent', 0.00),
							'L_ADJUSTMENT_AFTER'  => request_var('member_adjustment', 0.00),
							'L_UPDATED_BY'        => $user->data['username']);
							
						$this->log_insert(array(
							'log_type'   => $log_action['header'],
							'log_action' => $log_action)
						);
						
						$success_message = sprintf($user->lang['ADMIN_UPDATE_MEMBERDKP_SUCCESS'],  
							request_var('hidden_id', 0)   );
							trigger_error($success_message . $link);
						
						
						/******************/
						$template->assign_vars(array(
							'L_TITLE'		=> $user->lang['ACP_DKP_EDITMEMBERDKP'],
							'L_EXPLAIN'		=> $user->lang['ACP_MM_EDITMEMBERDKP_EXPLAIN'],
							'F_EDIT_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;"),
							'MEMBER_NAME'           => $this->member['member_name'],
				
							'V_MEMBER_ID'         => ( isset($_POST['add']) ) ? '' : $this->member['member_id'],
							'V_MEMBER_DKPID'        => ( isset($_POST['add']) ) ? '' : $this->member['member_dkpid'],
				
							'MEMBER_ID'             => $this->member['member_id'],
							'MEMBER_EARNED'         => $this->member['member_earned'],
							'MEMBER_SPENT'          => $this->member['member_spent'],
							'MEMBER_ADJUSTMENT'     => $this->member['member_adjustment'],
							'MEMBER_CURRENT'        => ( !empty($this->member['member_current']) ) ? $this->member['member_current'] : '0.00',
							'MEMBER_LEVEL'          => $this->member['member_level'],
							'MEMBER_DKPID'          => $this->member['member_dkpid'],
							'MEMBER_DKPNAME'        => $this->member['member_dkpname'],
							'MEMBER_RACE'           => $this->member['member_race'],
							'MEMBER_CLASS'          => $this->member['member_class'],
							'COLORCODE'          	=> $this->member['colorcode'],
							'IMAGENAME'          	=> (strlen($this->member['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $this->member['imagename'] . ".png" : '', 
							'MEMBER_RANK'           => $this->member['member_rank'],
				
							'CORRECT_MEMBER_EARNED' => ( !empty($correct_earned) ) ? $correct_earned : '0.00',
							'CORRECT_MEMBER_SPENT'  => ( !empty($correct_spent) ) ? $correct_spent : '0.00',
							'C_MEMBER_CURRENT'      => $this->member['member_current'],
							'MSG_NAME_EMPTY' => $user->lang['FV_REQUIRED_NAME'],
						
							)
						);
				
							$this->page_title = 'ACP_DKP_EDITMEMBERDKP';
							$this->tpl_name = 'dkp/acp_'. $mode;

					}	
					
					elseif ($delete)
						{	
						    
							if (( (isset($_POST['hidden_id'])) and (isset($_POST['hidden_dkpid']))   ) == true) 
							{
								
								$del_member = request_var('hidden_id', 0) ; 
								$del_dkpid = request_var('hidden_dkpid', 0);
								
								// get data on dkp to be deleted
								$sql_array = array(
								    'SELECT'    => 'm.member_name, d.member_id, d.member_earned, d.member_spent, d.member_adjustment',
								    'FROM'      => array(
								        MEMBER_LIST_TABLE 	=> 'm',
								        MEMBER_DKP_TABLE    => 'd'
								    ),
								 
								    'WHERE'     =>  "m.member_id = ' . $del_member . '
								       				AND d.member_dkpid = " . $del_dkpid . '
								   					AND d.member_id = m.member_id' 
									);
								 
								$sql = $db->sql_build_query('SELECT', $sql_array);
								$result = $db->sql_query($sql);
								while ( $row = $db->sql_fetchrow($result) )
								{
								$this->old_member = array(
										'member_id'			=> $del_member,
										'member_name'		=> $row['member_name'],
										'member_earned'     => (float) $row['member_earned'],
										'member_spent'      => (float) $row['member_spent'],
										'member_adjustment' => (float) $row['member_adjustment']);
								}
								$db->sql_freeresult($result);
								
								if (confirm_box(true))
								{
									// begin transaction
									
									$db->sql_transaction('begin'); 
									
									$names = $del_member;
									//remove member from attendees table but only if linked to raids in selected dkp pool
									$sql = 'DELETE FROM ' . RAID_ATTENDEES_TABLE . '
											WHERE member_id= ' . $del_member . ' 
											AND raid_id IN( SELECT r.raid_id from ' . RAIDS_TABLE . ' r, ' . EVENTS_TABLE .' e 
												where r.event_id = e.event_id and e.event_dkpid = ' . (int) $del_dkpid . ')';
									$db->sql_query($sql);
								
									// delete player loot
									/*
									 *  not crossdb compatible but works in ansi sql
									$sql = 'DELETE i  
											FROM ' . ITEMS_TABLE . ' i 
											INNER JOIN ' . RAIDS_TABLE . ' r 
											ON r.raid_id = i.raid_id 
											INNER JOIN ' . EVENTS_TABLE . ' e 
											ON e.event_id = r.event_id and e.event_dkpid = ' .  $del_dkpid . ' 
											WHERE i.member_id = ' . $del_member;
									$db->sql_query($sql);
									*/
									$sql = 'DELETE FROM ' . ITEMS_TABLE . ' where member_id = ' . $del_member . ' and raid_id in ( 
									select raid_id from ' . RAIDS_TABLE . ' r , ' . EVENTS_TABLE . ' e where r.event_id  = e.event_id and e.event_dkpid = ' . (int) $del_dkpid . ')'; 
									$db->sql_query($sql);
									
									//delete player adjustments
									$sql = 'DELETE FROM ' . ADJUSTMENTS_TABLE . '
											WHERE member_id =' . $del_member . '
											AND adjustment_dkpid= ' .  $del_dkpid ;
									$db->sql_query($sql);

									//delete player dkp points
									$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . 
											$del_member . ' AND member_dkpid= ' .  $del_dkpid ;									
									$db->sql_query($sql);
									
									//commit
									$db->sql_transaction('commit');
									
									$log_action = array(
										'header'         => 'ACTION_MEMBERDKP_DELETED', 
										'L_NAME'       => $this->old_member['member_name'],
										'L_EARNED'     => $this->old_member['member_earned'],
										'L_SPENT'      => $this->old_member['member_spent'],
										'L_ADJUSTMENT' => $this->old_member['member_adjustment']);
									
									$this->log_insert(array(
										'log_type'   => $log_action['header'],
										'log_action' => $log_action)
									);
								

									$success_message = sprintf($user->lang['ADMIN_DELETE_MEMBERDKP_SUCCESS'], $del_member, $del_dkpid);
									trigger_error($success_message . $link);
								}
								else
								{
									$s_hidden_fields = build_hidden_fields(
									array(
										'delete'	=> true,
										'hidden_id'		=> $del_member,
										'hidden_dkpid'	=> $del_dkpid, 
										'old_member'	=> $this->old_member, 
										)
									);
									confirm_box(false, $user->lang['CONFIRM_DELETE_MEMBERDKP'], $s_hidden_fields);
									
								}
							}
							else
							{
								$success_message = sprintf($user->lang['ADMIN_DELETE_MEMBERDKP_FAILED'], 'UNKNOWN', 'UNKNOWN');
								trigger_error($success_message . $link, E_USER_WARNING);
							}
							
							redirect(append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_listmemberdkp&amp;"));	
											
						}
						else
						{
							$form_key = 'mm_editmemberdkp';
							add_form_key($form_key);
		
							/******** no post **********/
							$template->assign_vars(array(
								'L_TITLE'		=> $user->lang['ACP_DKP_EDITMEMBERDKP'],
								'L_EXPLAIN'		=> $user->lang['ACP_MM_EDITMEMBERDKP_EXPLAIN'],
								
								'F_EDIT_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;"),
								'MEMBER_NAME'           => $this->member['member_name'],
					
								'V_MEMBER_ID'         => ( isset($_POST['add']) ) ? '' : $this->member['member_id'],
								'V_MEMBER_DKPID'         => ( isset($_POST['add']) ) ? '' : $this->member['member_dkpid'],
					
								'MEMBER_ID'             => $this->member['member_id'],
								'MEMBER_EARNED'         => $this->member['member_earned'],
								'MEMBER_SPENT'          => $this->member['member_spent'],
								'MEMBER_ADJUSTMENT'     => $this->member['member_adjustment'],
								'MEMBER_CURRENT'        => ( !empty($this->member['member_current']) ) ? $this->member['member_current'] : '0.00',
								'MEMBER_LEVEL'          => $this->member['member_level'],
								'MEMBER_DKPID'          => $this->member['member_dkpid'],
								'MEMBER_DKPNAME'        => $this->member['member_dkpname'],
								'MEMBER_RACE'           => $this->member['member_race'],
								'MEMBER_CLASS'          => $this->member['member_class'],
								'COLORCODE'          	=> $this->member['colorcode'],
								'MEMBER_RANK'           => $this->member['member_rank'],
								'IMAGENAME'          	=> (strlen($this->member['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $this->member['imagename'] . ".png" : '',   
								'CORRECT_MEMBER_EARNED' => ( !empty($correct_earned) ) ? $correct_earned : '0.00',
								'CORRECT_MEMBER_SPENT'  => ( !empty($correct_spent) ) ? $correct_spent : '0.00',
								'C_MEMBER_CURRENT'      => $this->member['member_current'],
								'L_ADD_MEMBER_TITLE'    => $user->lang['EDITMEMBER_DKP_TITLE'],
								'MSG_NAME_EMPTY'        => $user->lang['FV_REQUIRED_NAME'],
								)
							);
							/***********************************/
							$this->page_title = 'ACP_DKP_EDITMEMBERDKP';
							$this->tpl_name = 'dkp/acp_'. $mode;
				
						}
			
				
				
			break;
			
			/***************************************/
			// member dkp transfer
			// this transfers dkp from one member to another.
			// the old account will still exist
			/***************************************/
			
			case 'mm_transfer':
				$submit	 = (isset($_POST['transfer'])) ? true : false;
				if ($submit)
				{
	        		if (confirm_box(true))
					{
						//fetch hidden variables
						$member_from = request_var('hidden_idfrom', 0);
						$member_to = request_var('hidden_idto', 0);
						
						//declare transfer array
						$transfer = array();

						/* 1) collect and transfer adjustments to new owner */
						$sql = 'SELECT sum(adjustment_value) as adjustments, adjustment_dkpid FROM ' . 
							ADJUSTMENTS_TABLE . ' 
							where member_id = ' .  $member_from . ' 
							GROUP BY adjustment_dkpid';
						$result = $db->sql_query($sql, 0);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$transfer[$row['adjustment_dkpid']]['adjustments'] = (float) $row['adjustments'] ;
						}
						$db->sql_freeresult($result);
						
						/* 2) calculate $member_from item cost by dkp pool to transfer to new dkp account */
						$sql = 'SELECT sum(i.item_value) as itemvalue, e.event_dkpid FROM ' . 
							ITEMS_TABLE . ' i,  ' . RAIDS_TABLE . ' r,  ' . EVENTS_TABLE . ' e
		        			where e.event_id=r.event_id
		        			and r.raid_id=i.raid_id 
		        			and i.member_id = ' .  $member_from . ' 
							GROUP BY e.event_dkpid';
						$result = $db->sql_query($sql, 0);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$transfer[$row['event_dkpid']]['itemcost'] = (float) $row['itemvalue'] ;
						}
						$db->sql_freeresult($result);
					
						/* 3) calculate battlepoints earned, raidcount, first, last raiddate by dkp pool to transfer to new dkp account 
						 exclude raids where the member_to was also participating to avoid double counting raids */
						$sql = 'SELECT sum(r.raid_value) as raidvalue, 
									   max(r.raid_date) as maxraiddate, 
									   min(r.raid_date) as minraiddate, 
									   count(a.member_id) as raidcount, 
									   e.event_dkpid 
							FROM ' . RAID_ATTENDEES_TABLE . ' a,  ' . RAIDS_TABLE . ' r,  ' . EVENTS_TABLE . ' e
		        			WHERE e.event_id = r.event_id
		        			AND r.raid_id = a.raid_id 
		        			AND a.member_id = ' .  $member_from . ' 
		        			AND a.raid_id not in( select raid_id from ' . RAID_ATTENDEES_TABLE . ' where member_id = '. $member_to . ')
							GROUP BY e.event_dkpid';
						$result = $db->sql_query($sql, 0);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$transfer[$row['event_dkpid']]['raidvalue'] = (float) $row['raidvalue'] ;
							$transfer[$row['event_dkpid']]['maxraiddate'] = (int) $row['maxraiddate'] ;
							$transfer[$row['event_dkpid']]['minraiddate'] = (int) $row['minraiddate'] ;
							$transfer[$row['event_dkpid']]['raidcount'] = (int) $row['raidcount'] ;
						}
						$db->sql_freeresult($result);

						// begin transaction
						$db->sql_transaction('begin'); 
						
						/* 4) now update dkp table */
						// loop the transfer array
						foreach ($transfer as $dkpid => $data) 
						{ 
								// check if pool exists for $member_to
							    $sql = 'SELECT count(*) as memberpoolcount FROM ' . MEMBER_DKP_TABLE . ' 
		                        WHERE member_id = '  . $member_to . ' and member_dkpid = ' .  $dkpid ; 
		                        $result = $db->sql_query($sql, 0);
		                        $total_rowto = (int) $db->sql_fetchfield('memberpoolcount');
		                        $db->sql_freeresult($result);
							    
		                        if ($total_rowto == 1)
		                        {
		                        	// get old data
		                        	$sql = 'SELECT member_earned, member_spent, member_adjustment, member_firstraid, member_lastraid, member_raidcount  
		                        	FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . (int) $member_to  . ' and member_dkpid = ' . $dkpid;
		    						$result = $db->sql_query($sql,0);
		    						while ( $row = $db->sql_fetchrow($result) )
		    						{
		    							$oldmember_earned	  = (float) $row['member_earned'];
					 					$oldmember_spent	  = (float) $row['member_spent'];
										$oldmember_adjustment = (float) $row['member_adjustment'];
			                    		$oldmember_firstraid  = (int) $row['member_firstraid'];
			                    		$oldmember_lastraid	  = (int) $row['member_lastraid'];
			                    		$oldmember_raidcount  = (int) $row['member_raidcount'];
			                    		
			                    		if(isset($data['minraiddate']))
			                    		{
			                    			$newfirstraid = ( $oldmember_firstraid <= $data['minraiddate'] ) ? $oldmember_firstraid : $data['minraiddate'];
			                    		}
			                    		else
			                    		{
			                    			$newfirstraid = $oldmember_firstraid; 	
			                    		}
			                    		
			                    		if(isset($data['maxraiddate']))
			                    		{
			                    			$newlastraid = ( $oldmember_lastraid <= $data['maxraiddate'] ) ? $oldmember_lastraid : $data['maxraiddate'];
			                    		}
			                    		else
			                    		{
			                    			$newlastraid = $oldmember_lastraid; 	
			                    		}
    						    		
		    						}
		    						$db->sql_freeresult($result);
		    						
		    						//build update query
		                        	$query = $db->sql_build_array('UPDATE', array(
	    			                    'member_earned'	    	=> $oldmember_earned + (isset( $data['raidvalue']) ? $data['raidvalue'] : 0.00) ,  
	    					 			'member_spent'		    => $oldmember_spent + (isset( $data['itemcost']) ? $data['itemcost'] : 0.00), 
	    								'member_adjustment'		=> $oldmember_adjustment + (isset( $data['adjustments']) ? $data['adjustments'] : 0.00), 
	    			                    'member_firstraid'	    => $newfirstraid, 
	    			                    'member_lastraid'	    => $newlastraid, 
	    			                    'member_raidcount'		=> $oldmember_raidcount + (isset( $data['raidcount']) ? $data['raidcount'] : 0))  
    			                    ); 
    			                    
           			                $sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $query . ' WHERE member_id = ' . $member_to . ' and member_dkpid = ' .  $dkpid ; 
           			                $db->sql_query($sql); 
		                        	
		                        }
		                        // the only other case possible : insert a new record 
		                        elseif  ($total_rowto == 0)
		                        {
		                        	//insert
		                        	$query = $db->sql_build_array('INSERT', array(
	    			                    'member_dkpid'		    => $dkpid,
	    			                    'member_id'		   		=> $member_to,
	    			                    'member_earned'	    	=> (isset( $data['raidvalue']) ? $data['raidvalue'] : 0.00) ,  
	    					 			'member_spent'		    => (isset( $data['itemcost']) ? $data['itemcost'] : 0.00), 
	    								'member_adjustment'		=> (isset( $data['adjustments']) ? $data['adjustments'] : 0.00), 
	    			                    'member_status'	    	=> 1,
	    			                    'member_firstraid'	    => (isset( $data['minraiddate']) ? $data['minraiddate'] : 0), 
	    			                    'member_lastraid'	    => (isset( $data['maxraiddate']) ? $data['maxraiddate'] : 0), 
	    			                    'member_raidcount'		=> (isset( $data['raidcount']) ? $data['raidcount'] : 0))  
    			                    ); 
    			                    $sql = 'INSERT INTO ' . MEMBER_DKP_TABLE . $query; 
           			                $db->sql_query($sql);
		                        }

		                        // finally delete the member_from account
		       					$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . '
		       							WHERE member_id= '. (int) $member_from  . ' and member_dkpid = ' . $dkpid;
		       					$db->sql_query($sql);
						}
						/* 5) transfer old attendee name to new member */
						// if $member_from participated in a raid the $member_to did too, delete the entry. (unique key) 
						$sql = 'select raid_id from ' . RAID_ATTENDEES_TABLE . ' where member_id = '. $member_to; 
						$result = $db->sql_query($sql, 0);
						while ( $row = $db->sql_fetchrow($result) )
						{
							$raid_id[] = $row['raid_id'];
						}
						$sql = 'DELETE FROM ' . RAID_ATTENDEES_TABLE . '  
								WHERE member_id='. $member_from . '
								AND ' . $db->sql_in_set('raid_id', $raid_id, false, true);  
								
						$db->sql_query($sql);
						
						// 6) now update the remaining raids where old member participated (the last 'not in' condition is not necessary)
						$sql = 'UPDATE ' . RAID_ATTENDEES_TABLE . ' SET member_id ='. $member_to . ' 
								WHERE member_id='. $member_from . '
								AND ' . $db->sql_in_set('raid_id', $raid_id, true, true);
						
						/* 7) transfer items to new owner */
						$sql = 'UPDATE ' . ITEMS_TABLE . ' SET member_id ='. $member_to . ' WHERE member_id='. $member_from;
						$db->sql_query($sql);
                        
                        // 8) update the adjustments table
						$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . ' SET member_id='. $member_to . ' WHERE member_id= '. $member_from ;
						$db->sql_query($sql);
				
						//commit 
						$db->sql_transaction('commit');
						
						//pick up this info from the hidden variables
						$member_from_name = utf8_normalize_nfc(request_var('name_from', '', true));
						$member_to_name = utf8_normalize_nfc(request_var('name_to', '', true));
						
						//log the action
						$log_action = array(
							'header'   => 'L_ACTION_HISTORY_TRANSFER',
							'L_FROM' => $member_from_name,
							'L_TO'   => $member_to_name
						);

						$this->log_insert(array(
							'log_type'   => $log_action['header'],
							'log_action' => $log_action)
						);
					
						$success_message = sprintf($user->lang['ADMIN_TRANSFER_HISTORY_SUCCESS'], $member_from_name, $member_to_name, $member_from_name);
						trigger_error($success_message . $link);
					
					}
					else 
					{
						// check if user trues to transfer from one to the same 
						$member_from = request_var('transfer_from', 0);
						$member_to = request_var('transfer_to', 0);
						if ($member_from == $member_to)
						{
						     trigger_error($user->lang['ERROR_TRFSAME'], E_USER_WARNING);
						}
					
						// prepare some logging information 
						$sql = 'select member_name from ' . MEMBER_LIST_TABLE . ' where member_id =  ' . $member_from; 
						$result = $db->sql_query($sql, 0);
						$member_from_name = (string) $db->sql_fetchfield('member_name');
						$db->sql_freeresult($result);
						
						$sql = 'select member_name from ' . MEMBER_LIST_TABLE . ' where member_id =  ' . $member_to; 
						$result = $db->sql_query($sql, 0);
						$member_to_name = (string) $db->sql_fetchfield('member_name');						
						$db->sql_freeresult($result);

						$s_hidden_fields = build_hidden_fields(array(
								'transfer'    		=> true, 
								'name_from'			=> $member_from_name,
								'name_to'			=> $member_to_name,
								'hidden_idfrom'		=> $member_from,
								'hidden_idto'		=> $member_to, 
								)
							);
						confirm_box(false, sprintf($user->lang['CONFIRM_TRANSFER_MEMBERDKP'], $member_from_name, $member_to_name ), $s_hidden_fields);
						
					}
					
					
				}
				// end submit handler
				
				// build template
				// from member dkp table 
				$sql = 'SELECT m.member_id, l.member_name FROM ' . MEMBER_LIST_TABLE . ' l, ' . MEMBER_DKP_TABLE . ' m 
						where m.member_id = l.member_id GROUP BY m.member_id ORDER BY l.member_name';
				$resultfrom = $db->sql_query($sql);
				$maara = 0;
				while ( $row = $db->sql_fetchrow($resultfrom) )
				{
					$maara++;
					$template->assign_block_vars('transfer_from_row', array(
						'VALUE'    => $row['member_id'],
						'SELECTED' => ( $this->transfer['from'] == $row['member_id'] ) ? ' selected="selected"' : '',
						'OPTION'   => $row['member_name'])
					);
					
				}
				$db->sql_freeresult($resultfrom);
				// to member table 
				$sql = 'SELECT m.member_id, l.member_name FROM ' . MEMBER_LIST_TABLE . ' l, ' . MEMBER_DKP_TABLE . ' m, ' . MEMBER_RANKS_TABLE . ' k   
						where l.member_rank_id = k.rank_id and k.rank_hide != 1 and m.member_id = l.member_id GROUP BY m.member_id  ORDER BY l.member_name';
				$resultto = $db->sql_query($sql);
				$teller_to = 0;
				while ( $row = $db->sql_fetchrow($resultto) )
				{
					$teller_to++;				
					$template->assign_block_vars('transfer_to_row', array(
						'VALUE'    => $row['member_id'],
						'SELECTED' => ( $this->transfer['to'] == $row['member_id'] ) ? ' selected="selected"' : '',
						'OPTION'   => $row['member_name'])
					);
				}
        		$db->sql_freeresult($resultto);
        		
        		$show = true;
        		if ($maara ==0)
        		{
        			$show=false;
        		}
        		
				$template->assign_vars(array(
					'L_TITLE'					=> $user->lang['ACP_MM_TRANSFER'],
					'ERROR_MSG'					=> $user->lang['ERROR_NODKPACCOUNT'],
					'L_EXPLAIN'					=> $user->lang['TRANSFER_MEMBER_HISTORY_DESCRIPTION'],
					'S_SHOW'					=> $show,  
					'F_TRANSFER' 				=> append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_transfer"),
					'L_SELECT_1_OF_X_MEMBERS'   => sprintf($user->lang['SELECT_1OFX_MEMBERS'], $maara),
					'L_SELECT_1_OF_Y_MEMBERS'   => sprintf($user->lang['SELECT_1OFX_MEMBERS'], $teller_to),
					)
				);
				$this->page_title = 'ACP_MM_TRANSFER';
				$this->tpl_name = 'dkp/acp_'. $mode;
				
			break;

			default:
			
			$this->page_title = 'ACP_DKP_MAINPAGE';
			$this->tpl_name = 'dkp/acp_mainpage';
			$success_message = 'Error';
			trigger_error($success_message . $link);
			
		}
	}
}

?>
