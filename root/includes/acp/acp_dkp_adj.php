<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.8
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
	$user->add_lang(array('mods/dkp_admin'));
	trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}
if (!class_exists('Adjust'))
{
	require("{$phpbb_root_path}includes/bbdkp/Adjustments/Adjust.$phpEx");
}
if (!class_exists('Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}

// Include the abstract base
if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}


/**
 * This class manages guildmembers dkp adjustments
 * 
 */
class acp_dkp_adj extends \bbdkp\Admin
{
	public  $u_action;
	private $old_adjustment;
	private $adjustment;
	private $link;

	public function main ($id, $mode)
	{
		global $db, $user, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang(array('mods/dkp_admin', 'mods/dkp_common'));
		$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=mainpage") . '"><h3>' . $user->lang['RETURN_DKPINDEX'] . '</h3></a>';
		switch ($mode)
		{
			case 'listiadj':
				$showadd = (isset($_POST['addiadj'])) ? true : false;
				if ($showadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_adj&amp;mode=addiadj"));
					break;
				}
				
				/**  DKPSYS drop-down query ***/
				$adjust = new \bbdkp\Adjust();
				$result = $adjust->listAdjPools();
				$adjust->adjustment_dkpid = 0;
				$submit = (isset($_POST['dkpsys_id']) || isset($_GET['dkpsys_id'])) ? true : false;
				if ($submit)
				{
					$adjust->adjustment_dkpid = request_var('dkpsys_id', 0);
				}
				else
				{
					while ($row = $db->sql_fetchrow($result))
					{
						if ($row['dkpsys_default'] == "Y")
						{
							$adjust->adjustment_dkpid = $row['dkpsys_id'];
						}
					}
					
					if ($adjust->adjustment_dkpid == 0)
					{
						$result = $db->sql_query_limit($sql, 1);
						while ($row = $db->sql_fetchrow($result))
						{
							$adjust->adjustment_dkpid = $row['dkpsys_id'];
						}
					}
				}
				$db->sql_freeresult($result);
				$result = $adjust->listAdjPools();
				
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('dkpsys_row', array(
						'VALUE' => $row['dkpsys_id'] , 
						'SELECTED' => ($row['dkpsys_id'] == $adjust->adjustment_dkpid) ? ' selected="selected"' : '' , 
						'OPTION' => (! empty($row['dkpsys_name'])) ? $row['dkpsys_name'] : '(None)'));
				}
				$db->sql_freeresult($result);
				
				/*** end DKPSYS drop-down ***/
				$sort_order = array(
					0 => array('adjustment_date desc' , 'adjustment_date') , 
					1 => array('adjustment_dkpid' , 'adjustment_dkpid desc') , 
					2 => array('dkpsys_name' , 'dkpsys_name desc') , 
					3 => array('member_name' , 'member_name desc') , 
					4 => array('adjustment_reason' , 'adjustment_reason desc') , 
					5 => array('adjustment_value desc' , 'adjustment_value') , 
					6 => array('adjustment_added_by' , 'adjustment_added_by desc'));
					
				$members = new \bbdkp\Members();
				$member_filter = utf8_normalize_nfc(request_var('member_name', '', true));
				if ($member_filter != '')
				{
					$member_id_filter = $members->get_member_id(trim($member_filter));
				}
				
				$result2 = $adjust->countadjust($member_id_filter);
				$total_adjustments = (int) $db->sql_fetchfield('total_adjustments');
				$db->sql_freeresult($result2);
				
				$u_list_adjustments = append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_adj&amp;mode=listiadj") . '&amp;' . URI_PAGE;
				$current_order = $this->switch_order($sort_order);
				$start = request_var('start', 0);
				
				$result = $adjust->listadj($current_order['sql'], $member_id_filter);
				$hasrows = false;
				$total_adjustments = 0;
				while ($adj = $db->sql_fetchrow($result))
				{
					$hasrows = true;
					$total_adjustments +=1; 
				}
				$db->sql_freeresult($result);
				
				$result = $adjust->listadj($current_order['sql'], $member_id_filter, $start);
				while ($adj = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('adjustments_row', array(
						'U_ADD_ADJUSTMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_adj&amp;mode=addiadj") . '&amp;' . URI_ADJUSTMENT . '=' . $adj['adjustment_id'] . '&amp;' . URI_DKPSYS . '=' . $adj['adjustment_dkpid'] , 
						'DATE' => date($config['bbdkp_date_format'], $adj['adjustment_date']) , 
						'DKPID' => $adj['adjustment_dkpid'] , 
						'DKPPOOL' => $adj['dkpsys_name'] , 
						'COLORCODE' => ($adj['colorcode'] == '') ? '#123456' : $adj['colorcode'] , 
						'CLASS_IMAGE' => (strlen($adj['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $adj['imagename'] . ".png" : '' , 
						'S_CLASS_IMAGE_EXISTS' => (strlen($adj['imagename']) > 1) ? true : false , 
						'U_VIEW_MEMBER' => (isset($adj['member_name'])) ? append_sid("{$phpbb_root_path}dkp.$phpEx", "page=viewmember&amp;" . URI_NAMEID . '=' . $adj['member_id'] . '&amp;' . URI_DKPSYS . '=' . $adj['adjustment_dkpid']) : '' , 
						'MEMBER' => (isset($adj['member_name'])) ? $adj['member_name'] : '' , 
						'REASON' => (isset($adj['adjustment_reason'])) ? $adj['adjustment_reason'] : '' , 
						'ADJUSTMENT' => number_format($adj['adjustment_value'],2) , 
						'ADJ_DECAY' => $adj['adj_decay'] , 
						'CAN_DECAY' => $adj['can_decay'] , 
						'ADJUSTMENT_NET' => number_format($adj['adjustment_value'] - $adj['adj_decay'], 2) , 
						'DECAY_TIME' => ($adj['decay_time'] != 0 ?  date($config['bbdkp_date_format'], $adj['decay_time']) : '') , 
						'C_ADJUSTMENT' => ($adj['adjustment_value'] > 0 ? "positive" : "negative") , 
						'ADDED_BY' => (isset($adj['adjustment_added_by'])) ? $adj['adjustment_added_by'] : ''));
					
				}
				$db->sql_freeresult($result);
				$listadj_footcount = sprintf($user->lang['LISTADJ_FOOTCOUNT'], $total_adjustments, $config['bbdkp_user_alimit']);
				
				$pagination = \generate_pagination(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_adj&amp;mode=listiadj&amp;dkpsys_id=" . 
					$adjust->adjustment_dkpid) . '&amp;' . URI_PAGE, 
					$total_adjustments, 
					$config['bbdkp_user_alimit'], 
					$start, true);
				
				$template->assign_vars(array(
					'L_TITLE' => $user->lang['ACP_LISTIADJ'] , 
					'L_EXPLAIN' => $user->lang['ACP_LISTIADJ_EXPLAIN'] , 
					'S_SHOW' => ($hasrows == true) ? true : false , 
					'O_DATE' => $current_order['uri'][0] , 
					'O_DKPID' => $current_order['uri'][1] , 
					'O_DKPPOOL' => $current_order['uri'][2] , 
					'O_MEMBER' => $current_order['uri'][3] , 
					'O_REASON' => $current_order['uri'][4] , 
					'O_ADJUSTMENT' => $current_order['uri'][5] , 
					'O_ADDED_BY' => $current_order['uri'][6] , 
					'U_LIST_ADJUSTMENTS' => $u_list_adjustments , 
					'MEMBER_NAME' => $member_filter , 
					'START' => $start , 
					'S_GROUP_ADJ' => false , 
					'LISTADJ_FOOTCOUNT' => $listadj_footcount , 
					'ADJUSTMENT_PAGINATION' => $pagination, 
					'TOTAL_ADJUSTMENTS' => 'Total Adjustments', 
					'PAGE_NUMBER'    => on_page($total_adjustments, $config['bbdkp_user_alimit'], $start),
						));
				
				$this->page_title = 'ACP_LISTIADJ';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
				
			case 'addiadj':
				$form_key = 'acp_dkp_adj';
				add_form_key($form_key);
				
				/***  DKPSYS drop-down ***/
				$dkpsys_id = 1;
				$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default 
                    FROM ' . DKPSYS_TABLE . '
                    ORDER BY dkpsys_name';
				$resultdkpsys = $db->sql_query($sql);
				
				$showadj = new \bbdkp\Adjust;
				
				$adjust_id = request_var(URI_ADJUSTMENT, 0);
				$dkpsys_id = request_var(URI_DKPSYS, 0);
				
				if ($adjust_id != 0 && $dkpsys_id != 0)
				{
					$showadj->get($adjust_id);
					
					while ($row2 = $db->sql_fetchrow($resultdkpsys))
					{
						$template->assign_block_vars('adj_dkpid_row', array(
							'VALUE' => $row2['dkpsys_id'] , 
							'SELECTED' => ($row2['dkpsys_id'] == $showadj->adjustment_dkpid ? ' selected="selected"' : '' ), 
							'OPTION' => (! empty($row2['dkpsys_name'])) ? $row2['dkpsys_name'] : '(None)'));
					}
					
				}
				else
				{
					// we dont have a GET so put default dkp pool in pulldown
					while ($row2 = $db->sql_fetchrow($resultdkpsys))
					{
						//dkpsys_default
						$template->assign_block_vars('adj_dkpid_row', array(
							'VALUE' => $row2['dkpsys_id'] , 
							'SELECTED' => ($row2['dkpsys_default'] == 'Y') ? ' selected="selected"' : '' , 
							'OPTION' => (! empty($row2['dkpsys_name'])) ? $row2['dkpsys_name'] : '(None)'));
						if ($row2['dkpsys_default'] == 'Y')
						{
							$dkpsys_id = $row2['dkpsys_id'];
						}
					}
				}
				
				$submit = (isset($_POST['add'])) ? true : false;
				$update = (isset($_POST['update'])) ? true : false;
				$delete = (isset($_POST['delete'])) ? true : false;
				if ($submit || $update)
				{
					if (! check_form_key('acp_dkp_adj'))
					{
						trigger_error('FORM_INVALID');
					}
				}
				
				
				if ($submit)
				{
					// check form
					$errors_exist = $this->error_check_i();
					// Errors exist, redisplay the form
					if ($errors_exist)
					{
						trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);
					}
					
					
					$newadjust = new \bbdkp\Adjust;
					
					$temp = str_replace(".", "", request_var('adjustment_value', 0.0));
					$temp2 = (float) str_replace(",", ".", $temp);
						
					$newadjust->adjustment_value = $temp2;
					$newadjust->adjustment_reason = utf8_normalize_nfc(request_var('adjustment_reason', '', true));
					$newadjust->can_decay = request_var('adj_decayable', 1);
					$newadjust->adj_decay = 0;
					$newadjust->decay_time = 0;
					$newadjust->adjustment_date = $this->time;
					$newadjust->adjustment_dkpid = request_var('adj_dkpid', 0);
					$newadjust->adjustment_groupkey = $this->gen_group_key($this->time, $newadjust->adjustment_reason, $newadjust->adjustment_value);
					$newadjust->adjustment_added_by = $user->data['username'];
					
					$members = request_var('member_names', array(0 => 0), true);
					$member_names = array(); 
					foreach ($members as $member_id)
					{
						$member = new \bbdkp\Members;
						$member->member_id = $member_id;
						$member->Get();
						$newadjust->member_id = $member_id;
						$newadjust->member_name = $member->member_name;
						$member_names[] = $member->member_name;
						$newadjust->add();
					}
					
					//
					// Logging
					//
					$log_action = array(
						'header' => 'L_ACTION_INDIVADJ_ADDED' , 
						'L_ADJUSTMENT' => $newadjust->adjustment_value  , 
						'L_REASON' => $newadjust->adjustment_reason , 
						'L_MEMBERS' => implode(', ', $member_names) , 
						'L_ADDED_BY' => $newadjust->adjustment_added_by );
					
					$this->log_insert(array(
						'log_type' => $log_action['header'], 
						'log_action' => $log_action));
					
					$success_message = sprintf($user->lang['ADMIN_ADD_IADJ_SUCCESS'], $config['bbdkp_dkp_name'], $newadjust->adjustment_value, implode(', ', $member_names));
					trigger_error($success_message . $this->link);
					
				}
				
				if ($update)
				{
					$errors_exist = $this->error_check_i();
					// Errors exist, redisplay the form
					if ($errors_exist)
					{
						trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);
					}
					
					$oldadjust = new \bbdkp\Adjust;
					$oldadjust->adjustment_id = request_var('hidden_id', 0);
					$oldadjust->get($oldadjust->adjustment_id);
					
					$updadjust = new \bbdkp\Adjust;
					$temp = str_replace(".", "", request_var('adjustment_value', 0.0));
					$temp2 = (float) str_replace(",", ".", $temp);
					$updadjust->adjustment_value = $temp2;
					$updadjust->adjustment_reason = utf8_normalize_nfc(request_var('adjustment_reason', '', true));
					$updadjust->can_decay = request_var('adj_decayable', 1);
					$updadjust->adj_decay = 0;
					$updadjust->decay_time = 0;
					$updadjust->adjustment_date = $this->time;
					$updadjust->adjustment_dkpid = request_var('adj_dkpid', 0);
					$updadjust->adjustment_added_by = $user->data['username'];
					$updadjust->adjustment_groupkey = $updadjust->gen_group_key($this->time, $updadjust->adjustment_reason, $updadjust->adjustment_value);
					
					$members = request_var('member_names', array(0 => 0), true);
					
					// remove old adjustment
					$oldadjust->delete();
					foreach ($members as $member_id)
					{
						$member = new \bbdkp\Members;
						$member->member_id = $member_id;
						$member->Get();
						$updadjust->member_id = $member_id;
						$updadjust->member_name = $member->member_name;
						$member_names[] = $member->member_name;
						$updadjust->add();
					}
					
					//
					// Logging
					//
					$log_action = array(
						'header' => 'L_ACTION_INDIVADJ_UPDATED' , 
						'id' => $adjust_id , 
						'L_ADJUSTMENT_BEFORE' => $this->old_adjustment['adjustment_value'] , 
						'L_REASON_BEFORE' => $this->old_adjustment['adjustment_reason'] , 
						'L_MEMBERS_BEFORE' => implode(', ', $this->old_adjustment['member_names']) , 
						'L_ADJUSTMENT_AFTER' => $updadjust->adjustment_value  , 
						'L_REASON_AFTER' => $updadjust->adjustment_reason , 
						'L_MEMBERS_AFTER' => implode(', ', $member_names) , 
						'L_UPDATED_BY' => $user->data['username']);
					
					$this->log_insert(array(
						'log_type' => $log_action['header'] , 
						'log_action' => $log_action));
					
					$success_message = sprintf($user->lang['ADMIN_UPDATE_IADJ_SUCCESS'], $config['bbdkp_dkp_name'], $updadjust->adjustment_value, implode(', ', $member_names));
					trigger_error($success_message . $this->link);
				}
				
				
				
				if ($delete)
				{
					if (confirm_box(true))
					{
						// get form vars
						$adjust_id = request_var('xhidden_id', 0);
						$deleteadj = new \bbdkp\Adjust;
						$deleteadj->get($adjust_id);
						$deleteadj->delete(); 
						
						// Logging
						$log_action = array(
							'header' => 'L_ACTION_INDIVADJ_DELETED' , 
							'id' => $adjust_id , 
							'L_ADJUSTMENT' => $deleteadj->adjustment_value , 
							'L_REASON' => $deleteadj->adjustment_reason , 
							'L_MEMBERS' =>  $deleteadj->member_name );
						
						$this->log_insert(array(
							'log_type' => $log_action['header'] , 
							'log_action' => $log_action));
						//
						// Success messages
						$success_message = sprintf($user->lang['ADMIN_DELETE_IADJ_SUCCESS'], $config['bbdkp_dkp_name'], 
								$deleteadj->adjustment_value , $deleteadj->member_name);
						
						trigger_error($success_message . $this->link);
						
					}
					else
					{
						$s_hidden_fields = build_hidden_fields(array(
							'delete' => true , 
							'xhidden_id' => request_var('hidden_id', 0) , 
							));
						$template->assign_vars(array(
							'S_HIDDEN_FIELDS' => $s_hidden_fields));
						confirm_box(false, $user->lang['CONFIRM_DELETE_IADJ'], $s_hidden_fields);
					}
				}
				
				/* mark members as selected */
				$sql = 'SELECT member_id, member_name FROM ' . MEMBER_LIST_TABLE . ' ORDER BY member_name';
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					if ($adjust_id)
					{
						//editmode
						$selected = (@in_array($row['member_id'],  $showadj->members_samegroupkey  )) ? ' selected="selected"' : '';
					}
					else
					{
						//newmode
						$selected = (@in_array($row['member_id'],
						 utf8_normalize_nfc(request_var('member_names', array(0 => 0))))) ? ' selected="selected"' : '';
					}
					
					$template->assign_block_vars('members_row', array(
						'VALUE' => $row['member_id'] , 
						'SELECTED' => $selected , 
						'OPTION' => $row['member_name']));
				}
				
				$db->sql_freeresult($result);
				$template->assign_vars(array(
					'L_TITLE' =>  ($showadj->adjustment_id == 0) ? $user->lang['ADD_IADJ_TITLE'] : $user->lang['EDIT_IADJ_TITLE'], 
					'L_EXPLAIN' => $user->lang['ACP_ADDIADJ_EXPLAIN'] , 
					// Form vars
					'F_ADD_ADJUSTMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_adj&amp;mode=addiadj") , 
					'ADJUSTMENT_ID' => $showadj->adjustment_id, 
					'DKP_ID' => $showadj->adjustment_dkpid , 
					// Form values
					'ADJUSTMENT_VALUE' => number_format($showadj->adjustment_value, 2) , 
					'ADJUSTMENT_REASON' => $showadj->adjustment_reason , 
					'ADJUSTMENT_DECAY' => number_format($showadj->adj_decay, 2) ,
					'MO' => date('m', $this->time) , 
					'D' => date('d', $this->time) , 
					'Y' => date('Y', $this->time) , 
					'H' => date('h', $this->time) , 
					'MI' => date('i', $this->time) , 
					'S' => date('s', $this->time) , 
					'CAN_DECAY_NO_CHECKED' => ( $showadj->can_decay == 0) ? ' checked="checked"' : '' , 
					'CAN_DECAY_YES_CHECKED' => ($showadj->can_decay == 1) ? ' checked="checked"' : '' , 
					// Form validation
					'FV_MEMBERS' => $this->fv->generate_error('member_names') , 
					'FV_ADJUSTMENT' => $this->fv->generate_error('adjustment_value') , 
					'FV_MO' => $this->fv->generate_error('mo') , 
					'FV_D' => $this->fv->generate_error('d') , 
					'FV_Y' => $this->fv->generate_error('y') , 
					// Javascript messages
					'MSG_VALUE_EMPTY' => $user->lang['FV_REQUIRED_ADJUSTMENT'] , 
					// Buttons
					'S_ADD' => (! $showadj->adjustment_id) ? true : false));
				
				$this->page_title = 'ACP_ADDIADJ';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
		}
	}


	/** 
	 * validationfunction for adjustment values : required and numeric, date is in range
	 * @access private 
	 */
	private function error_check_i ()
	{
		global $user;
		if (! isset($_POST['member_names']))
		{
			$this->fv->errors['member_names'] = $user->lang['FV_REQUIRED_MEMBERS'];
		}
		$this->fv->is_number(request_var('adjustment_value', 0.00), $user->lang['FV_NUMBER_ADJUSTMENT']);
		$this->fv->is_filled(request_var('adjustment_value', 0.00), $user->lang['FV_REQUIRED_ADJUSTMENT']);
		$this->fv->is_within_range(request_var('mo', 0), 1, 12, $user->lang['FV_RANGE_MONTH']);
		$this->fv->is_within_range(request_var('d', 0), 1, 31, $user->lang['FV_RANGE_DAY']);
		$this->fv->is_within_range(request_var('y', 0), 1998, 2015, $user->lang['FV_RANGE_YEAR']);
		$this->time = mktime(0, 0, 0, request_var('mo', 0), request_var('d', 0), request_var('y', 0));
		return $this->fv->is_error();
	}

	


}
?>