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

                    
                    $sql1 = 'UPDATE ' . MEMBER_DKP_TABLE . "
                        SET member_status = '1' 
                        WHERE  member_dkpid  = " . $dkpsys_id . ' 
                        AND ' . $db->sql_in_set('member_id', $active_members, false, true);

                    $sql2 = 'UPDATE ' . MEMBER_DKP_TABLE . "
                        SET member_status = '0' 
                        WHERE  member_dkpid  = " . $dkpsys_id . ' 
                        AND ' . $db->sql_in_set('member_id', $active_members, true, true);

                    $db->sql_query($sql1);
					 $db->sql_query($sql2);
                    
                    
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
								AND l.attribute_id = c.c_index AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'    		
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
						'U_VIEW_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp") . '&amp;'. URI_NAME . '='.$row['member_name'].  '&amp;'. URI_DKPSYS . '='.$row['member_dkpid']
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
								
					if (isset($_GET[URI_NAME]) && isset($_GET[URI_DKPSYS]) )  
					{
						$sql_array = array(
					    'SELECT'    => '
					    	a.*, 
							(m.member_earned-m.member_spent+m.member_adjustment) AS member_current, 
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
							c.class_armor_type AS armor_type ',
					 
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
					            'ON'    => "r1.attribute_id = a.member_race_id AND r1.language= '" . $config['bbdkp_lang'] . "' AND r1.attribute = 'race'" 
					            )
					        ),
					 
					    'WHERE'     =>  "(a.member_rank_id = r.rank_id) 
					    				AND (a.member_guild_id = r.guild_id)  
										AND (a.member_id = m.member_id) 
										AND (a.member_class_id = c.class_id)  
										AND (m.member_dkpid = s.dkpsys_id)   
										AND l.attribute_id = c.c_index AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'    
										AND (s.dkpsys_id = " . request_var(URI_DKPSYS, 0) . ')' . 
									   "AND (a.member_name = '" . $db->sql_escape(  utf8_normalize_nfc(request_var(URI_NAME, '', true))) . "')",
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
								'member_rank'		 => $row['rank_name']
							);	
					}
				
					if ( !empty($this->member['member_name']) )  // ??
					{
						// Get their correct earned/spent
						
						$sql = $db->sql_query('SELECT sum(r.raid_value) AS member_summa
								FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra 
								WHERE ra.raid_id = r.raid_id 
								AND ra.member_name='" . $db->sql_escape($this->member['member_name']) . "'
								AND r.raid_dkpid=" . (int) $this->member['member_dkpid'] );
						
						while ( $row = $db->sql_fetchrow($sql) )
						{
							$correct_earned = $row['member_summa'];
						}
						$db->sql_freeresult($sql);
						
						$sql  = $db->sql_query('SELECT sum(item_value) AS member_summa 
								FROM ' . ITEMS_TABLE . " 
								WHERE item_buyer='" . $db->sql_escape($this->member['member_name']) . "'
								AND item_dkpid='" . (int) $this->member['member_dkpid'] . "'");
						
						while ( $row = $db->sql_fetchrow($sql) )
						{
							$correct_spent = $row['member_summa'];
						}
						$db->sql_freeresult($sql);
					}

					$update	 = (isset($_POST['update'])) ? true : false;
					$delete	 = (isset($_POST['delete'])) ? true : false;	
									
					if ($update)
					{
					
						$sql = 'SELECT *
								FROM ' . MEMBER_DKP_TABLE . " WHERE member_id = 
								(SELECT member_id from " . MEMBER_LIST_TABLE . " 
								WHERE member_name = '" . $db->sql_escape( utf8_normalize_nfc( request_var('hidden_name', '', true))) . "')  
								AND member_dkpid= " . (int) request_var('hidden_dkpid', 0) ;
						
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
			
						$query = $db->sql_build_array('UPDATE', array(
								'member_earned'     => request_var('member_earned',0.00),
								'member_spent'      => request_var('member_spent', 0.00),
								'member_adjustment' => request_var('member_adjustment', 0.00))
						);
						
						$db->sql_query('UPDATE ' . MEMBER_DKP_TABLE . ' 
										SET ' . $query . ' 
						        WHERE member_id = ' . $this->old_member['member_id'] . '
								AND member_dkpid= ' . (int) request_var('hidden_dkpid',0) );
									
						$log_action = array(
							'header'              => 'ACTION_MEMBERDKP_UPDATED',
							'L_NAME_BEFORE'       => request_var('hidden_name',''),
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
							utf8_normalize_nfc( request_var('hidden_name', '', true))  );
							trigger_error($success_message . $link);
						
						
						/******************/
						$template->assign_vars(array(
							'L_TITLE'		=> $user->lang['ACP_DKP_EDITMEMBERDKP'],
							'L_EXPLAIN'		=> $user->lang['ACP_MM_EDITMEMBERDKP_EXPLAIN'],
							'F_EDIT_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;"),
							'MEMBER_NAME'           => $this->member['member_name'],
				
							'V_MEMBER_NAME'         => ( isset($_POST['add']) ) ? '' : $this->member['member_name'],
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
						    
							if (( (isset($_POST['hidden_name'])) and (isset($_POST['hidden_dkpid']))   ) == true) 
							{
								
								$del_member = utf8_normalize_nfc(request_var('hidden_name', '', true));
								$del_dkpid = request_var('hidden_dkpid', 0);
								// get data on dkp to be deleted
								$sql_array = array(
								    'SELECT'    => 'd.member_id, d.member_earned, d.member_spent, d.member_adjustment',
								 
								    'FROM'      => array(
								        MEMBER_LIST_TABLE 	=> 'm',
								        MEMBER_DKP_TABLE    => 'd'
								    ),
								 
								    'WHERE'     =>  "m.member_name = '" . $db->sql_escape($del_member) . "'
								       				AND d.member_dkpid = " . $del_dkpid . '
								   					AND d.member_id = m.member_id' 
									);
								 
								$sql = $db->sql_build_query('SELECT', $sql_array);
								$result = $db->sql_query($sql);
								while ( $row = $db->sql_fetchrow($result) )
								{
								$this->old_member = array(
										'member_id'			=> (int) $row['member_id'],
										'member_name'		=> $del_member,
										'member_earned'     => (float) $row['member_earned'],
										'member_spent'      => (float) $row['member_spent'],
										'member_adjustment' => (float) $row['member_adjustment']);
								}
								$db->sql_freeresult($result);
								
								if (confirm_box(true))
								{
									
									$names = $del_member;

									//remove member from attendees table but only if linked to raids in selected dkp pool
									$sql = 'DELETE FROM ' . RAID_ATTENDEES_TABLE . '
											WHERE member_id= ' . $this->old_member['member_id'] . ' 
											AND RAID_ID IN( SELECT RAID_ID from ' . RAIDS_TABLE . ' where raid_dkpid = ' . (int) $del_dkpid . ')';
									$db->sql_query($sql);
							
									// delete player loot
									$sql = 'DELETE FROM ' . ITEMS_TABLE . "
											WHERE item_buyer='" . $db->sql_escape(  $del_member) . "'
											AND item_dkpid= " .   $del_dkpid;
									$db->sql_query($sql);
	
									//delete player adjustments
									$sql = 'DELETE FROM ' . ADJUSTMENTS_TABLE . "
											WHERE member_id ='" . $this->old_member['member_id'] . "'
											AND adjustment_dkpid= " .  $del_dkpid ;
									$db->sql_query($sql);

									//delete player dkp points
									$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . 
											$this->old_member['member_id'] . ' AND member_dkpid= ' .  $del_dkpid ;									
									$db->sql_query($sql);
										
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
										'hidden_name'	=> $del_member,
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
							
							/******** no post **********/
							$template->assign_vars(array(
								'L_TITLE'		=> $user->lang['ACP_DKP_EDITMEMBERDKP'],
								'L_EXPLAIN'		=> $user->lang['ACP_MM_EDITMEMBERDKP_EXPLAIN'],
								
								'F_EDIT_MEMBER' => append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;"),
								'MEMBER_NAME'           => $this->member['member_name'],
					
								'V_MEMBER_NAME'         => ( isset($_POST['add']) ) ? '' : $this->member['member_name'],
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
								'MEMBER_RANK'           => $this->member['member_rank'],
					
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
			/***************************************/
			
			case 'mm_transfer':
			    
			    $this->transfer = array(
            		'from' => utf8_normalize_nfc(request_var('transfer_from',' ',true)),    
            		'to'   =>  utf8_normalize_nfc(request_var('transfer_to',' ',true)),     
        		);
        		
				$submit	 = (isset($_POST['update'])) ? true : false;
				
				if ($submit)
				{
				    
				    // make small array of dkp pools 
					$dkp = array();
					$sql1 = 'SELECT * FROM ' . DKPSYS_TABLE; 
					$result1 = $db->sql_query($sql1);
					while ($row1 = $db->sql_fetchrow($result1) )
					{
						$dkp[] = $row1['dkpsys_id'];
					}
					
					$db->sql_freeresult($result1);
					if(count($dkp) == 0) 
					{
					    trigger_error($user->lang['ERROR_NODKP'], E_USER_WARNING);
					} 
						
					$member_from = utf8_normalize_nfc(request_var('transfer_from', '', true));
					$member_to   = utf8_normalize_nfc(request_var('transfer_to', '', true));
	
					if ($member_from == $member_to)
					{
					     trigger_error($user->lang['ERROR_TRFSAME'], E_USER_WARNING);
					}
					
					// from array
					$sql_array = array(
					   'SELECT'    => 'd.member_id, m.member_name',
					   'FROM'      => array(
					        MEMBER_LIST_TABLE 	=> 'm',
					        MEMBER_DKP_TABLE    => 'd'),
					   'WHERE'     =>  "m.member_name = '" . $db->sql_escape($member_from) . "'
					   					AND d.member_id = m.member_id" , 
					   'GROUP_BY'	=>  'd.member_id, m.member_name', 
					   'ORDER_BY'	=>  'm.member_name'
					    );
					$sql = $db->sql_build_query('SELECT', $sql_array);					
					$result = $db->sql_query($sql, 0);
					
					$member_id_from = 0;
					while ( $rowa = $db->sql_fetchrow($result))
					{
						$member_id_from = (int) $rowa['member_id'];
					}
					
					// to array
					$sql = 'SELECT member_name, member_id
							FROM ' . MEMBER_LIST_TABLE . "
							WHERE member_name='". $db->sql_escape($member_to)."'";
					$result = $db->sql_query($sql, 0);
                   
					$member_id_to =0;					
					while ( $rowb = $db->sql_fetchrow($result) )
					{
						$member_id_to = (int) $rowb['member_id'];
					}
					
					if (($member_id_from == 0) or ($member_id_to == 0) ) 
					{
						$_message = $USER->LANG['ERROR_FROMTO']; 
						trigger_error($_message . $link, E_USER_WARNING);
					}
					$db->sql_freeresult($result);

					
					// start 
					// get itemvalue from
					
					$totalspent= 0.00;
					$sql = 'SELECT SUM(item_value) AS item_sum_from
							FROM ' . ITEMS_TABLE . "
							WHERE item_buyer='". $db->sql_escape($member_from) ."'";
					$result = $db->sql_query($sql, 0);
					$totalspent += (float) $db->sql_fetchfield('item_sum_from');
				    $db->sql_freeresult($result);

					// transfer items
					$sql = 'UPDATE ' . ITEMS_TABLE . "
							  SET item_buyer='". $db->sql_escape($member_to) ."'
							WHERE item_buyer='". $db->sql_escape($member_from) ."'";
					$db->sql_query($sql);

					// get adjustment from
					$sql = 'SELECT SUM(adjustment_value) AS member_summa
							FROM ' . ADJUSTMENTS_TABLE . "
							WHERE member_id=". (int) $member_id_to;
					$result = $db->sql_query($sql, 0);
					$total_iadj = 0.00; 
					while ($row = $db->sql_fetchrow($result) )
					{
						$total_iadj += $row['member_summa'];
					}
					$db->sql_freeresult($result);
					
					// Transfer adjustments
					$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . '
							SET member_id='. $member_id_to . '
							WHERE member_id= '. $member_id_from ;
					$db->sql_query($sql);
					
					// dkp 
					foreach ((array) $dkp as $dkpid)
					{
                        $sql = 'SELECT count(*) as memberpoolcount FROM ' . MEMBER_DKP_TABLE . ' 
                        WHERE member_id = '  . $member_id_from . ' and member_dkpid = ' .  $dkpid ; 
                        $result = $db->sql_query($sql, 0);
                        $total_rowfrom = (int) $db->sql_fetchfield('memberpoolcount');
                        $db->sql_freeresult($result);
                        
                        $sql = 'SELECT count(*) as memberpoolcount FROM ' . MEMBER_DKP_TABLE . ' 
                        WHERE member_id = '  . $member_id_to . ' and member_dkpid = ' .  $dkpid ; 
                        $result = $db->sql_query($sql, 0);
                        $total_rowto = (int) $db->sql_fetchfield('memberpoolcount');
                        $db->sql_freeresult($result);
                        
                        if ($total_rowfrom == 1 && $total_rowto == 0 )
      			        {
     			             // insert new
    						$sql = "SELECT member_dkpid, member_earned, member_spent, member_adjustment, member_status, 
    						member_firstraid, member_lastraid, member_raidcount  FROM " . MEMBER_DKP_TABLE . " 
    						WHERE member_id = " . (int) $member_id_from  . " and member_dkpid = " . $dkpid;
						
    						$result = $db->sql_query($sql, 0);
						
                        	while ( $row = $db->sql_fetchrow($result) )
    						{
    							$query = $db->sql_build_array('INSERT', array(
    			                    'member_dkpid'		    => $row['member_dkpid'],
    			                    'member_id'		   		=> $member_id_to,
    			                    'member_earned'	    	=> $row['member_earned'],
    					 			'member_spent'		    => $row['member_spent'],
    								'member_adjustment'		=> $row['member_adjustment'],
    			                    'member_status'	    	=> $row['member_status'],
    			                    'member_firstraid'	    => $row['member_firstraid'],
    			                    'member_lastraid'	    => $row['member_lastraid'],
    			                    'member_raidcount'		=> $row['member_raidcount']
    			                    )
    			                );
    			                
    			                $db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
    						}
    						$db->sql_freeresult($result);
      			            
      			        }
      			        
					    if ($total_rowfrom == 1 && $total_rowto == 1 )
      			        {
      			            // update 
      			            unset($result); 
    						$sql = "SELECT member_dkpid, member_earned, member_spent, member_adjustment, member_status, 
    						member_firstraid, member_lastraid, member_raidcount  FROM " . MEMBER_DKP_TABLE . " 
    						WHERE member_id = " . (int) $member_id_from  . " and member_dkpid = " . $dkpid;
    						$result = $db->sql_query($sql, 0);
                        	while ( $row = $db->sql_fetchrow($result) )
    						{
			                    $member_earned	  = $row['member_earned'];
					 			$member_spent	  = $row['member_spent'];
								$member_adjustment = $row['member_adjustment'];
			                    $member_status	    = $row['member_status'];
			                    $member_firstraid	= $row['member_firstraid'];
			                    $member_lastraid	= $row['member_lastraid'];
    						}
    						$db->sql_freeresult($result);
    						
                           /* calculate new earned and raidcount
                           * we avoid double counting raids :
                           * we're only adding raidcount if the to_member did not participate in the same raid as the from_member 
                           * we're only adding earned if the to_member did not participate in the same raid as the from_member
							*/                               
                           $raidcount_addon = 0; 
                           $earned_addon = 0.00; 
    						$sql = 'SELECT raid_id, member_name, member_id
									FROM ' . RAID_ATTENDEES_TABLE . "
									WHERE member_name='". $db->sql_escape($member_from) . "'
									AND raid_id in (select raid_id from " . RAIDS_TABLE . ' where raid_dkpid = ' . $dkpid . ')';
							$result = $db->sql_query($sql,0);
							while ( $row0 = $db->sql_fetchrow($result) )
							{
									// Check if the TO attended the same raid
									$sql1 = 'SELECT member_name
											FROM ' . RAID_ATTENDEES_TABLE . "
											WHERE raid_id='".$row0['raid_id']."'
											AND member_name='". $db->sql_escape($member_to). "'";
									$sql2='';			
									if ( $db->sql_affectedrows($db->sql_query($sql1,0)) == 0 )
									{
									    // in this raid only member_from participated. so we will add this raid to the raidcount of member_to
										$sql2 = 'UPDATE ' . RAID_ATTENDEES_TABLE . "
												SET member_name='". $db->sql_escape($member_to) ."', member_id='". $member_id_to ."'
												WHERE raid_id='". $row0['raid_id'] ."'
												AND member_name='". $db->sql_escape($member_from) ."'";
										$db->sql_query($sql2);
										// add to raidcount
										$raidcount_addon++; 
										
									    // in this raid only member_from participated. so we will add this earned to the earned of member_to										
									    $sql2 = 'SELECT raid_value from ' . RAIDS_TABLE . "
												WHERE raid_id=". $row0['raid_id']; 
									    $resultx = $db->sql_query($sql,0);
                                       $earned_addon += (float) $db->sql_fetchfield('raid_value', false, $resultx);
                                       $db->sql_freeresult($resultx);
										
									}
							}	
									
							$sql = "SELECT member_dkpid, member_earned, member_spent, member_adjustment, member_status, 
    						member_firstraid, member_lastraid, member_raidcount  FROM " . MEMBER_DKP_TABLE . " 
    						WHERE member_id = " . (int) $member_id_to  . " and member_dkpid = " . $dkpid;
    						$result = $db->sql_query($sql,0);
                        	while ( $row = $db->sql_fetchrow($result) )
    						{
    						    $newfirstraid = ($member_firstraid < $row['member_firstraid'] ) ? $member_firstraid : $row['member_firstraid']; 
    						    $newlastraid  = ($member_lastraid > $row['member_lastraid'] ) ? $member_lastraid : $row['member_lastraid'] ; 
    							$sql_ary =  array(
    			                    'member_earned'	    	=> $row['member_earned'] + $earned_addon,
    					 			 'member_spent'		    => $row['member_spent'] + $member_spent,
    								 'member_adjustment'	=> $row['member_adjustment'] + $member_adjustment,
    			                    'member_status'	    	=> $member_status,
    			                    'member_firstraid'	    => $newfirstraid,
    			                    'member_lastraid'	    => $newlastraid, 
    			                    'member_raidcount'		=> $row['member_raidcount'] + $raidcount_addon, 
    			                    ); 
    						}
					
    						// update member_to
                           $sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
                                SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
                                WHERE member_id = ' . (int) $member_id_to  . ' and member_dkpid = ' . $dkpid;
                           $db->sql_query($sql);
                            
                           // Delete the member_from
        					$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . '
        							WHERE member_id= '. (int) $member_id_from  . ' and member_dkpid = ' . $dkpid;
        					$db->sql_query($sql);
                            
                        }
                        
					}
					// end dkp loop
                    
					// Delete the member_from
					$sql = 'DELETE FROM ' . MEMBER_LIST_TABLE . "
							WHERE member_name='". $db->sql_escape($member_from) ."'";
					$db->sql_query($sql);
					
					// Delete any remaining raids that the FROM attended
					$sql = 'DELETE FROM ' . RAID_ATTENDEES_TABLE . "
							WHERE member_name='". $db->sql_escape($member_from) ."'";
					$db->sql_query($sql);
                    
					$log_action = array(
						'header'   => 'L_ACTION_HISTORY_TRANSFER',
						'L_FROM' => $member_from,
						'L_TO'   => $member_to);
					$this->log_insert(array(
						'log_type'   => $log_action['header'],
						'log_action' => $log_action)
					);
					
					$success_message = sprintf($user->lang['ADMIN_TRANSFER_HISTORY_SUCCESS'], $member_from, $member_to, $member_from);
					trigger_error($success_message . $link);
				}
				// end submit handler
				
				// from member dkp table 
				$sqlfrom = 'SELECT member_name FROM ' . MEMBER_LIST_TABLE . ' where member_id in (select member_id from ' . MEMBER_DKP_TABLE . ' ) 
						ORDER BY member_name';
				$resultfrom = $db->sql_query($sqlfrom);
				$maara = 0;
				while ( $row = $db->sql_fetchrow($resultfrom) )
				{
					$maara++;
					$template->assign_block_vars('transfer_from_row', array(
						'VALUE'    => $row['member_name'],
						'SELECTED' => ( $this->transfer['from'] == $row['member_name'] ) ? ' selected="selected"' : '',
						'OPTION'   => $row['member_name'])
					);
					
				}
				
				// to member table 
				$sqlto = "SELECT member_name 
						FROM " . MEMBER_LIST_TABLE . " where member_rank_id not in (select rank_id 
						from " . MEMBER_RANKS_TABLE . " where rank_hide = '1') ORDER BY member_name";
				
				$resultto = $db->sql_query($sqlto);
				$teller_to = 0;
				while ( $row = $db->sql_fetchrow($resultto) )
				{
					$teller_to++;				
					$template->assign_block_vars('transfer_to_row', array(
						'VALUE'    => $row['member_name'],
						'SELECTED' => ( $this->transfer['to'] == $row['member_name'] ) ? ' selected="selected"' : '',
						'OPTION'   => $row['member_name'])
					);
				}
				

				
				$template->assign_vars(array(
					'L_TITLE'					=> $user->lang['ACP_MM_TRANSFER'],
					'L_EXPLAIN'					=> $user->lang['TRANSFER_MEMBER_HISTORY_DESCRIPTION'],
					'F_TRANSFER' 				=> append_sid("index.$phpEx", "i=dkp_mdkp&amp;mode=mm_transfer"),
					'L_SELECT_1_OF_X_MEMBERS'   => sprintf($user->lang['SELECT_1OFX_MEMBERS'], $maara),
					'L_SELECT_1_OF_Y_MEMBERS'   => sprintf($user->lang['SELECT_1OFX_MEMBERS'], $teller_to),
					)
				);
				$db->sql_freeresult($resultfrom);
        		$db->sql_freeresult($resultto);
				
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
