<?php
/**
 * This acp class manages Manual Raids
 * 
 * @package bbDkp.acp
 * @author Ippehe, Sajaki
 * @version $Id$
 * @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' )) 
{
	exit ();
}
if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

class acp_dkp_raid extends bbDkp_Admin 
{

	public function main($id, $mode) 
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang ( array ('mods/dkp_admin' ) );
		$user->add_lang ( array ('mods/dkp_common' ) );
		$this->link = '<br /><a href="' . append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=listraids" ) . 
		'"><h3>'.$user->lang['RETURN_DKPINDEX'].'</h3></a>';

		//do event test.
		$sql = 'SELECT count(*) as eventcount FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b 
			where a.dkpsys_id = b.event_dkpid ';
		$result = $db->sql_query ( $sql );
		$eventcount = $db->sql_fetchfield('eventcount');
		$db->sql_freeresult( $result );
		if($eventcount==0)
		{
			trigger_error ( $user->lang['ERROR_NOEVENTSDEFINED'], E_USER_WARNING );
		}
		
		switch ($mode) 
		{
			case 'addraid' :
				/* newpage */
				$submit = (isset ( $_POST ['add'] )) ? true : false;
				if($submit)
				{
					// add raid to database
					$this->addraid();
				}
				// show add raid form
				$this->newraid();
				$this->page_title = 'ACP_DKP_RAID_ADD';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
				
			case 'editraid' :
			
				$update = (isset ( $_POST ['update'] )) ? true : false;
				$delete = (isset ( $_POST ['delete'] )) ? true : false;
				$additem = (isset ( $_POST ['additem'] )) ? true : false;
				$addattendee = (isset ( $_POST ['addattendee'] )) ? true : false;
				$editraider = (isset ( $_GET ['editraider'] )) ? true : false;
				$deleteraider = (isset ( $_GET ['deleteraider'] )) ? true : false;
				$raid_id = request_var ( 'hidden_id', 0 );

				/* handle actions */
				if($update)
				{
					//update raid
					$this->updateraid($raid_id);
					$this->displayraid($raid_id);
				}
				elseif($delete)
				{
					//delete the raid
					$this->deleteraid($raid_id);
				}
				elseif($additem)
				{
					//show form for adding items
					meta_refresh(0, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_item&amp;mode=additem&amp;' . URI_RAID .'=' . $raid_id));
				}
				elseif($addattendee)
				{
					//adds raider
					$this->addattendee($raid_id);
					$this->displayraid($raid_id);
				}
				elseif($editraider)
				{
					//show the form for editing a raider (get params from $get)
					$raid_id = request_var (URI_RAID, 0);
					$this->editraider($raid_id);
				}
				elseif($deleteraider)
				{
					//show the form for editing a raider (get params from $get)
					$raid_id = request_var (URI_RAID, 0);
					$this->deleteraider($raid_id);
				}
				else
				{
					// show edit form
					$raid_id = request_var (URI_RAID, 0);
					$this->displayraid($raid_id);
				}
				
				$this->page_title = 'ACP_DKP_RAID_EDIT';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
				
			case 'listraids' :
				
				// list all raids
				$this->listraids();
				$this->page_title = 'ACP_DKP_RAID_LIST';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;		
		}
	
	}
	
	/*
	 * new raid
	 */
	private function newraid()
	{
		global $db, $user, $config, $template, $phpEx ;
		$dkpsys_id=0; 
		if (isset($_GET[URI_DKPSYS]) )
		{
			//user clicked on add raid from event editscreen
			$dkpsys_id = request_var ( URI_DKPSYS, 0 );
		}
		
		if (isset ( $_POST['dkpsys_id']) )
		{
			// getting dkp from pulldown
			$dkpsys_id = request_var ( 'dkpsys_id', 0 );
		}
	
		if($dkpsys_id==0)
		{
			//get default dkp pool
			$sql1 = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default 
	                 FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . " b 
					 where a.dkpsys_id = b.event_dkpid and dkpsys_default = 'Y' ";
			$result1 = $db->sql_query ($sql1);
			// get the default dkp value (dkpsys_default = 'Y') from DB
			while ( $row = $db->sql_fetchrow ( $result1 ) ) 
			{
				$dkpsys_id = $row['dkpsys_id'];
			}
			$db->sql_freeresult( $result1 );
		}
		
		if($dkpsys_id==0)
		{ 	
			// get first row
			$sql1 = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default 
                      FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b 
					  where a.dkpsys_id = b.event_dkpid';
			$result1 = $db->sql_query_limit ( $sql1, 1, 0 );
			while ( $row = $db->sql_fetchrow ( $result1 ) ) 
			{
				$dkpsys_id = $row['dkpsys_id'];
			}
			$db->sql_freeresult( $result1 );
		}
		
		//fill dkp dropdown
		$sql = 'SELECT dkpsys_id, dkpsys_name FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b 
				where a.dkpsys_id = b.event_dkpid group by dkpsys_id, dkpsys_name ORDER BY dkpsys_name';
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$template->assign_block_vars ( 'dkpsys_row', array (
				'VALUE' 	=> $row['dkpsys_id'], 
				'SELECTED' 	=> ($row['dkpsys_id'] == $dkpsys_id) ? ' selected="selected"' : '', 
				'OPTION' 	=> (! empty ( $row['dkpsys_name'] )) ? $row['dkpsys_name'] : '(None)' ) 
			);
		}
		$db->sql_freeresult($result);
		
		/* event listbox */
		// calculate number format
		$max_value = 0.00;
		$sql = 'SELECT max(event_value) AS max_value FROM ' . EVENTS_TABLE . ' where event_dkpid = ' . $dkpsys_id; 
		$result = $db->sql_query ($sql);
		$max_value = (float) $db->sql_fetchfield('max_value', 0, $result);
		$float = @explode ( '.', $max_value );
		$format = '%0' . @strlen ( $float [0] ) . '.2f';
		$db->sql_freeresult($result);
		
		$sql = ' SELECT event_id, event_name, event_value 
		FROM ' . EVENTS_TABLE . ' WHERE event_dkpid = ' . $dkpsys_id . ' ORDER BY event_name';
		$result = $db->sql_query($sql);
		$eventvalue= 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$select_check = false;
			if (isset ($_GET[URI_EVENT]))
			{
				$select_check = ( $row['event_id'] == request_var(URI_EVENT, 0)) ? true : false;
				$eventvalue = $row['event_value']; 
			}
			
			$template->assign_block_vars ( 
				'events_row', array (
					'VALUE' => $row['event_id'], 
					'SELECTED' => ($select_check) ? ' selected="selected"' : '', 
					'OPTION' => $row['event_name'] . ' - (' . sprintf ( $format, $row['event_value'] ) . ')' 
			));
		}
		
		$db->sql_freeresult($result);
		
		/* getting left memberlist only with rank not hidden */
		$sql_array = array(
    		'SELECT'    => 'm.member_id ,m.member_name',
 
	    	'FROM'      => array(
    		    MEMBER_LIST_TABLE 	  => 'm',
        		MEMBER_RANKS_TABLE    => 'r', 
    			),
 
    		'WHERE'     =>  'm.member_guild_id = r.guild_id
    	    				 AND m.member_rank_id = r.rank_id
    	    				 AND r.rank_hide != 1', 
    		'ORDER_BY' => 'm.member_name',
		);
		
		$membercount = 0; 
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$membercount++;
			$template->assign_block_vars ( 'members_row', array (
				'VALUE' 	=> $row['member_id'], 
				'OPTION' 	=> $row['member_name'] ) );
		}
		$db->sql_freeresult( $result );
		
		if ($membercount==0)
		{
			// if no members defined yet stop here
			trigger_error ( $user->lang['ERROR_NOGUILDMEMBERSDEFINED'], E_USER_WARNING );
		}
		
		// build presets for raiddate and hour pulldown
		$now = getdate();
		$s_raid_day_options = '<option value="0">--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$day = $now['mday'] ;
			$selected = ($i == $day ) ? ' selected="selected"' : '';
			$s_raid_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_month_options = '<option value="0">--</option>';
		for ($i = 1; $i < 13; $i++)
		{
			$month = $now['mon'] ;
			$selected = ($i == $month ) ? ' selected="selected"' : '';
			$s_raid_month_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_year_options = '<option value="0">--</option>';
		for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
		{
			$yr = $now['year'] ;
			$selected = ($i == $yr ) ? ' selected="selected"' : '';
			$s_raid_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		//start raid time
		$s_raid_hh_options = '<option value="0">--</option>';
		for ($i = 0; $i < 24; $i++)
		{
			$hh = $now['hours'] ;
			$selected = ($i == $hh ) ? ' selected="selected"' : '';
			$s_raid_hh_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_mi_options = '<option value="0">--</option>';
		for ($i = 0; $i <= 59; $i++)
		{
			$mi = $now['minutes'] ;
			$selected = ($i == $mi ) ? ' selected="selected"' : '';
			$s_raid_mi_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_s_options = '<option value="0">--</option>';
		for ($i = 0; $i <= 59; $i++)
		{
			$s = $now['seconds'] ;
			$selected = ($i == $s ) ? ' selected="selected"' : '';
			$s_raid_s_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		//end raid time
		$hourduration = max(0, round( (float) $config['bbdkp_standardduration'],0));
		$minutesduration = max(0, ((float) $config['bbdkp_standardduration'] - floor((float) $config['bbdkp_standardduration'])) * 60 );
		$endtime = mktime(idate("H") + $hourduration, idate("i") + $minutesduration);
		
		$s_raidend_hh_options = '<option value="0">--</option>';
		for ($i = 0; $i < 24; $i++)
		{
			$hh = idate('H', $endtime);
			$selected = ($i == $hh ) ? ' selected="selected"' : '';
			$s_raidend_hh_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		$s_raidend_mi_options = '<option value="0">--</option>';
		for ($i = 0; $i <= 59; $i++)
		{
			$mi = idate('i', $endtime);
			$selected = ($i == $mi ) ? ' selected="selected"' : '';
			$s_raidend_mi_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raidend_s_options = '<option value="0">--</option>';
		for ($i = 0; $i <= 59; $i++)
		{
			$s = $now['seconds'] ;
			$selected = ($i == $s ) ? ' selected="selected"' : '';
			$s_raidend_s_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		//difference between start & end in milliseconds
	    $timediff = $endtime - mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) ; 
	    $b = date('r', mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']));
	    $e = date('r', $endtime);
	    	
		// express in minutes
		$timediff=round($timediff/60, 2) ;
		$time_bonus = 0; 
		//if we have a $config interval bigger than 0 minutes then calculate time bonus
		if(	(int) $config['bbdkp_timeunit'] > 0)
		{
			$time_bonus = round($config['bbdkp_dkptimeunit'] * $timediff / $config['bbdkp_timeunit'], 2) ;	
		}
		
		add_form_key('acp_dkp_addraid');
		
		$template->assign_vars ( array (
				'L_TITLE' 			=> $user->lang ['ACP_ADDRAID'], 
				'L_EXPLAIN' 		=> $user->lang ['ACP_ADDRAID_EXPLAIN'], 
				'F_ADD_RAID' 		=> append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=addraid" ), 
				'U_ADD_EVENT' 		=> append_sid ( "index.$phpEx", "i=dkp_event&amp;mode=addevent" ), 
				'RAID_VALUE'		=> $eventvalue, 

				//raiddate
				'S_RAIDDATE_DAY_OPTIONS'	=> $s_raid_day_options,
				'S_RAIDDATE_MONTH_OPTIONS'	=> $s_raid_month_options,
				'S_RAIDDATE_YEAR_OPTIONS'	=> $s_raid_year_options,
				
				//start
				'S_RAIDSTART_H_OPTIONS'		=> $s_raid_hh_options,
				'S_RAIDSTART_MI_OPTIONS'	=> $s_raid_mi_options,
				'S_RAIDSTART_S_OPTIONS'		=> $s_raid_s_options,

				//end
				'S_RAIDEND_H_OPTIONS'		=> $s_raidend_hh_options,
				'S_RAIDEND_MI_OPTIONS'		=> $s_raidend_mi_options,
				'S_RAIDEND_S_OPTIONS'		=> $s_raidend_s_options,
				'RAID_DURATION' 			=> $config['bbdkp_standardduration'],
				'DKPTIMEUNIT'				=> $config['bbdkp_dkptimeunit'], 
				'TIMEUNIT' 					=> $config['bbdkp_timeunit'],
		 		'DKPPERTIME'				=> sprintf($user->lang['DKPPERTIME'], $config['bbdkp_dkptimeunit'], $config['bbdkp_timeunit'] ), 
				// Form values
				'RAID_DKPSYSID' 			=> $dkpsys_id, 
				'TIME_BONUS'				=> $time_bonus, 
		
              	'L_DATE' => $user->lang ['DATE'] . ' dd/mm/yyyy', 
				'L_TIME' => $user->lang ['TIME'] . ' hh:mm:ss', 
				
				// Javascript messages
				'MSG_ATTENDEES_EMPTY' => $user->lang ['FV_REQUIRED_ATTENDEES'], 
				'MSG_NAME_EMPTY' 	  => $user->lang ['FV_REQUIRED_EVENT_NAME'], 
				'MSG_GAME_NAME' 	  => $config ['bbdkp_default_game'], 
		));
	}
	
	/*
	 * displays a raid
	 */
	private function displayraid($raid_id)
	{
		global $db, $user, $config, $template, $phpEx ;
		
		/*** get general raid info  ***/
		$sql_array = array (
			'SELECT' => ' d.dkpsys_name, e.event_dkpid, e.event_id, e.event_name, e.event_value, 
						  r.raid_id, r.raid_start, r.raid_end, r.raid_note, 
						  r.raid_added_by, r.raid_updated_by ', 
			'FROM' => array (
				DKPSYS_TABLE 		=> 'd' ,
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',
				), 
			'WHERE' => " d.dkpsys_id = e.event_dkpid and r.event_id = e.event_id and r.raid_id=" . ( int ) $raid_id, 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$raid = array (
				'dkpsys_name' 		=> $row['dkpsys_name'],
				'event_dkpid' 		=> $row['event_dkpid'],
				'event_id' 			=> $row['event_id'], 
				'event_name' 		=> $row['event_name'], 
				'event_value' 		=> $row['event_value'],
				'raid_start' 		=> $row['raid_start'],
				'raid_end' 			=> $row['raid_end'], 
				'raid_note' 		=> $row['raid_note'], 
				'raid_added_by' 	=> $row['raid_added_by'], 
				'raid_updated_by' 	=> $row['raid_updated_by'] );
		}
		$db->sql_freeresult ($result);
		
		/* event pulldown */
		$max_value = 0.00;
		$sql = 'SELECT max(event_value) AS max_value FROM ' . EVENTS_TABLE . ' where event_dkpid = ' . $raid['event_dkpid']; 
		$result = $db->sql_query ($sql);
		$max_value = (float) $db->sql_fetchfield('max_value', 0, $result);
		$float = @explode ( '.', $max_value );
		$format = '%0' . @strlen ( $float [0] ) . '.2f';
		$db->sql_freeresult($result);
		
		$sql = ' SELECT  event_id, event_name, event_value 
				 FROM ' . EVENTS_TABLE . ' WHERE event_dkpid = ' . $raid['event_dkpid'] . ' ORDER BY event_name';
		$result = $db->sql_query($sql);
		$event_value = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$select_check = ( $row['event_id'] == $raid['event_id']) ? true : false;
			if (isset ($_POST[URI_EVENT]))
			{
				$select_check = ( $row['event_id'] == request_var(URI_EVENT, 0)) ? true : false;
			}
			
			$template->assign_block_vars ( 
				'events_row', array (
					'VALUE' => $row['event_id'], 
					'SELECTED' => ($select_check) ? ' selected="selected"' : '', 
					'OPTION' => $row['event_name'] . ' - (' . sprintf ( $format, $row['event_value'] ) . ')' 
			));
			
		}
		$db->sql_freeresult($result);
		
		
		// get raid detail
		$sort_order = array (
				0 => array ('member_name desc', 'member_name' ),
				1 => array ('raid_value', 'raid_value desc' ), 
				2 => array ('time_bonus', 'time_bonus desc' ), 
				3 => array ('zerosum_bonus desc', 'zerosum_bonus' ),
				4 => array ('raid_decay desc', 'raid_decay desc' ),
				5 => array ('total desc', 'total' ),
				);
		$current_order = switch_order ( $sort_order );	
		$sql_array = array(
    		'SELECT'    => 'm.member_id ,m.member_name, r.raid_value, r.time_bonus, r.zerosum_bonus, r.raid_decay, (r.raid_value + r.time_bonus + r.zerosum_bonus - r.raid_decay) as total  ',
	    	'FROM'      => array(
    		    MEMBER_LIST_TABLE 	  => 'm',
        		RAID_DETAIL_TABLE    => 'r', 
    			),
 
    		'WHERE'     =>  'm.member_id = r.member_id and r.raid_id = ' . (int) $raid_id  , 
    		'ORDER_BY' 	=>  $current_order ['sql'],
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		$raid_details = array ();
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$raid_details[$row['member_id']]['member_id'] = $row['member_id'];
			$raid_details[$row['member_id']]['member_name'] = $row['member_name'];
			$raid_details[$row['member_id']]['raid_value'] = $row['raid_value'];
			$raid_details[$row['member_id']]['time_bonus'] = $row['time_bonus'];
			$raid_details[$row['member_id']]['zerosum_bonus'] = $row['zerosum_bonus'];
			$raid_details[$row['member_id']]['raid_decay'] = $row['raid_decay'];
		}
		$db->sql_freeresult( $result );
		$raid['raid_detail'] = $raid_details;
		
		$raid_value = 0.00;
		$time_bonus = 0.00;
		$zerosum_bonus = 0.00;
		$raid_decay = 0.00;
		$raid_total = 0.00;
		$countattendees = 0;
		foreach($raid_details as $member_id => $raid_detail)
		{
			// fill attendees listbox 
			$template->assign_block_vars ( 'raid_attendees_row', array (
				'VALUE' => $member_id, 
				'OPTION' => $raid_detail['member_name'] ) );
			// fill attendees table
			$template->assign_block_vars ('raids_row', array (
				'U_VIEW_ATTENDEE' => append_sid ("index.$phpEx" , "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;" . URI_NAMEID . "={$member_id}&amp;" . URI_DKPSYS. "=" . $raid['event_dkpid']), 
				'U_EDIT_ATTENDEE' => append_sid ("index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;editraider=1&amp;". URI_RAID . "=" .$raid_id . "&amp;" . URI_NAMEID . "=" . $member_id),
				'U_DELETE_ATTENDEE' => append_sid ("index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;deleteraider=1&amp;". URI_RAID . "=" .$raid_id . "&amp;" . URI_NAMEID . "=" . $member_id),
				'NAME' 		 => $raid_detail['member_name'], 
				'RAIDVALUE'  => $raid_detail['raid_value'], 
				'TIMEVALUE'  => $raid_detail['time_bonus'],
				'ZSVALUE' 	 => $raid_detail['zerosum_bonus'],
				'DECAYVALUE' => $raid_detail['raid_decay'], 
				'TOTAL' 	 => $raid_detail['raid_value'] + $raid_detail['time_bonus']  + $raid_detail['zerosum_bonus'] - $raid_detail['raid_decay'], 
				)
			);
			$raid_value += $raid_detail['raid_value'];
			$time_bonus += $raid_detail['time_bonus'];
			$zerosum_bonus += $raid_detail['zerosum_bonus'];
			$raid_decay += $raid_detail['raid_decay'];
			$raid_total = $raid_value + $time_bonus + $zerosum_bonus - $raid_decay;
			$countattendees += 1;
		}
		
		// build presets for raiddate and hour pulldown
		$now = getdate();
		$s_raid_day_options = '<option value="0">--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$day = isset($raid['raid_start']) ? date('j', $raid['raid_start']) : $now['mday'] ;
			$selected = ($i == $day ) ? ' selected="selected"' : '';
			$s_raid_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_month_options = '<option value="0">--</option>';
		for ($i = 1; $i < 13; $i++)
		{
			$month = isset($raid['raid_start']) ? date('n', $raid['raid_start']) : $now['mon'] ;
			$selected = ($i == $month ) ? ' selected="selected"' : '';
			$s_raid_month_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_year_options = '<option value="0">--</option>';
		for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
		{
			$yr = isset($raid['raid_start']) ?  date('Y',$raid['raid_start']) : $now['year'] ;
			$selected = ($i == $yr ) ? ' selected="selected"' : '';
			$s_raid_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		//start raid time
		$s_raid_hh_options = '<option value="0"	>--</option>';
		for ($i = 1; $i < 24; $i++)
		{
			$hh = isset($raid['raid_start']) ? date('H', $raid['raid_start']) : $now['hours'] ;
			$selected = ($i == $hh ) ? ' selected="selected"' : '';
			$s_raid_hh_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_mi_options = '<option value="0">--</option>';
		for ($i = 1; $i <= 59; $i++)
		{
			$mi = isset($raid['raid_start']) ? date('i', $raid['raid_start']) : $now['minutes'] ;
			$selected = ($i == $mi ) ? ' selected="selected"' : '';
			$s_raid_mi_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_s_options = '<option value="0">--</option>';
		for ($i = 1; $i <= 59; $i++)
		{
			$s = isset($raid['raid_start']) ?  date('s',$raid['raid_start']) : $now['seconds'] ;
			$selected = ($i == $s ) ? ' selected="selected"' : '';
			$s_raid_s_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		//end raid time
		$s_raidend_hh_options = '<option value="0"	>--</option>';
		for ($i = 1; $i < 24; $i++)
		{
			$hh = isset($raid['raid_end']) ? date('H', $raid['raid_end']) : $now['hours'] ;
			$selected = ($i == $hh ) ? ' selected="selected"' : '';
			$s_raidend_hh_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raidend_mi_options = '<option value="0">--</option>';
		for ($i = 1; $i <= 59; $i++)
		{
			$mi = isset($raid['raid_end']) ? date('i', $raid['raid_end']) : $now['minutes'] ;
			$selected = ($i == $mi ) ? ' selected="selected"' : '';
			$s_raidend_mi_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raidend_s_options = '<option value="0">--</option>';
		for ($i = 1; $i <= 59; $i++)
		{
			$s = isset($raid['raid_end']) ?  date('s',$raid['raid_end']) : $now['seconds'] ;
			$selected = ($i == $s ) ? ' selected="selected"' : '';
			$s_raidend_s_options .= "<option value=\"$i\"$selected>$i</option>";
		}
				
		/* getting left memberlist only with rank not hidden */
		$sql_array = array(
    		'SELECT'    => 'm.member_id ,m.member_name',
 
	    	'FROM'      => array(
    		    MEMBER_LIST_TABLE 	  => 'm',
        		MEMBER_RANKS_TABLE    => 'r', 
    			),
 
    		'WHERE'     =>  'm.member_guild_id = r.guild_id
    	    				 AND m.member_rank_id = r.rank_id
    	    				 AND r.rank_hide != 1', 
    		'ORDER_BY' => 'm.member_name',
		);
		
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$template->assign_block_vars ( 'members_row', array (
				'VALUE' 	=> $row['member_id'], 
				'OPTION' 	=> $row['member_name'] ) );
		}
		$db->sql_freeresult( $result );
		
		// sql to get all members not participating in this raid
		// semi-join between the members and raiders
		$s_memberlist_options = '';
		$sql_array = array(
	    'SELECT'    => 	' l.member_id, l.member_name ', 
	    'FROM'      => array(
				MEMBER_LIST_TABLE 		=> 'l',
					),
		'WHERE'		=> ' NOT EXISTS ( SELECT NULL FROM ' . RAID_DETAIL_TABLE . ' ra WHERE l.member_id = ra.member_id and ra.raid_id = ' . $raid_id . ' ) and l.member_status = 1 ' ,
		'ORDER_BY'	=> 'member_name asc ',
	    );
	    
	    $sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result) )
		{
			$s_memberlist_options .= '<option value="' . $row['member_id'] . '"> ' . $row['member_name'] . '</option>';                    
		}
		$db->sql_freeresult($result);
		
		add_form_key('acp_dkp_addraid');
		
		$template->assign_vars ( array (
			'S_SHOWADDATTENDEE'	=> ($s_memberlist_options == '') ? false: true, 
			'S_MEMBERLIST_OPTIONS'  	=> $s_memberlist_options, 
			'L_TITLE' 			=> $user->lang ['ACP_ADDRAID'], 
			'F_EDIT_RAID' 		=> append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id ),
			'F_ADDATTENDEE' 	=> append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=addattendee&amp;". URI_RAID . "=" .$raid_id ),
			'RAIDTITLE' 		=> sprintf($user->lang['RAIDDESCRIPTION'], $raid_id, $raid['event_name'], 
							   $user->format_date($raid['raid_start'])), 
			'EVENT_VALUE'		=> $event_value, 
			'RAID_VALUE' 		=> $raid_value, 
			'INDIVIDUAL_RAID_VALUE' 		=> $raid_value / $countattendees,
			'TIMEVALUE' 		=> $time_bonus,
			'ZSVALUE' 			=> $zerosum_bonus,
			'DECAYVALUE' 		=> $raid_decay,
			'TOTAL'				=> $raid_total,
			'RAID_NOTE' 		=> $raid['raid_note'], 
			'RAID_ID' 			=> $raid_id, 
			'EVENT_DKPID'		=> $raid['event_dkpid'], 
			'RAID_DKPPOOL' 		=> $raid['dkpsys_name'], 
			'DKPTIMEUNIT'				=> $config['bbdkp_dkptimeunit'], 
			'TIMEUNIT' 					=> $config['bbdkp_timeunit'],
	 		'DKPPERTIME'				=> sprintf($user->lang['DKPPERTIME'], $config['bbdkp_dkptimeunit'], $config['bbdkp_timeunit'] ), 
							   
							   
			'S_RAIDDATE_DAY_OPTIONS'	=> $s_raid_day_options,
			'S_RAIDDATE_MONTH_OPTIONS'	=> $s_raid_month_options,
			'S_RAIDDATE_YEAR_OPTIONS'	=> $s_raid_year_options,
			
			//start
			'S_RAIDSTART_H_OPTIONS'		=> $s_raid_hh_options,
			'S_RAIDSTART_MI_OPTIONS'	=> $s_raid_mi_options,
			'S_RAIDSTART_S_OPTIONS'		=> $s_raid_s_options,
			
			//end
			'S_RAIDEND_H_OPTIONS'		=> $s_raidend_hh_options,
			'S_RAIDEND_MI_OPTIONS'		=> $s_raidend_mi_options,
			'S_RAIDEND_S_OPTIONS'		=> $s_raidend_s_options,
			
			'O_NAME' 			  => $current_order ['uri'] [0], 
			'O_RAIDVALUE' 		  => $current_order ['uri'] [1],
			'O_TIMEVALUE' 		  => $current_order ['uri'] [2],
			'O_ZSVALUE' 		  => $current_order ['uri'] [3],
			'O_DECAYVALUE' 		  => $current_order ['uri'] [4],
			'O_TOTALVALUE' 		  => $current_order ['uri'] [5], 

			'LISTRAIDS_FOOTCOUNT' => sprintf ( $user->lang ['LISTATTENDEES_FOOTCOUNT'], $countattendees),

			'L_DATE' => $user->lang ['DATE'] . ' dd/mm/yyyy', 
			'L_TIME' => $user->lang ['TIME'] . ' hh:mm:ss', 
			
			// Javascript messages
			'MSG_ATTENDEES_EMPTY' => $user->lang ['FV_REQUIRED_ATTENDEES'], 
			
			'S_ADDRAIDER'   	  => false,
							   		   
				) );
				
	}
	
	/*
	 * this function adds attendee 
	 */ 
	private function addattendee($raid_id)
	{
		global $db; 
        $raid_value = request_var('raid_value', 0.00); 
        $time_bonus = request_var('time_bonus', 0.00); 
		$dkpid = request_var('hidden_dkpid', 0); 
		$member_id =  request_var('attendee_id', 0); 
		$raid_start = mktime(request_var('sh', 0), request_var('smi', 0), request_var('ss', 0), request_var('mo', 0), request_var('d', 0), request_var('Y', 0)); 
		
		$db->sql_transaction('begin');
		
		$raid_detail = array(
                'raid_id'      => (int) $raid_id,
                'member_id'   =>  $member_id,
	            'raid_value'   => $raid_value,
	            'time_bonus'   => $time_bonus,
				);
		$db->sql_multi_insert(RAID_DETAIL_TABLE, $raid_detail);
		
		$this->update_dkprecord($raid_value, $time_bonus, $raid_start, $dkpid, $member_id ); 
		
		$db->sql_transaction('commit');
		
		return true;
	}

	/*
	 * this function deletes attendee 
	 */ 
	private function deleteraider($raid_id)
	{
		global $db, $user, $config, $template, $phpEx;
		$attendee_id = request_var(URI_NAMEID, 0); 
		if (confirm_box(true))
		{
			$raid_id = request_var('raid_idx', 0); 
			//recall vars into sql statement
			$db->sql_transaction('begin');
			
			// delete from raiddetail
			$sql = 'DELETE FROM ' . RAID_DETAIL_TABLE . ' WHEREs raid_id=' . $raid_id . ' and member_id = ' . request_var('attendee', 0) ;  
			$db->sql_query($sql);
			
			// amend their account
			// tbd
			$db->sql_transaction('commit');
			
			$link = '<br /><a href="' . append_sid ("index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id) . '"><h3>'.$user->lang['RETURN_RAID'].'</h3></a>'; 
			trigger_error( sprintf( $user->lang['ADMIN_RAID_ATTENDEE_DELETED_SUCCESS'],  utf8_normalize_nfc(request_var('attendeename', '', true)) , $raid_id) . $link, E_USER_WARNING);
		}
		else
		{
			$sql = 'SELECT member_name from ' . MEMBER_LIST_TABLE . ' where member_id = ' . $attendee_id; 
			$result = $db->sql_query($sql);
			$member_name = (string) $db->sql_fetchfield('member_name');
			$db->sql_freeresult($result);
			$s_hidden_fields = build_hidden_fields(array(
				'deleteraider'		=> true,
				'raid_idx'		=> $raid_id,
				'attendee'		=> $attendee_id,
				'attendeename'	=> $member_name,
				)
			);
			confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_ATTENDEE'], $member_name, $raid_id), $s_hidden_fields);
		}
		
		return true;
	}
	
	/*
	 * this function shows editform 
	 * 
	 * tbd
	 */ 
	private function editraider($raid_id)
	{
		global $db, $user, $config, $template, $phpEx;

		
		return true;
	}
	
	
	/*
	 * lists all raids
	 */
	private function listraids()
	{
		global $db, $user, $config, $template, $phpEx;
		// add dkpsys button redirect
		$showadd = (isset($_POST['raidadd'])) ? true : false;
        if($showadd)
        {
			redirect(append_sid("index.$phpEx", "i=dkp_raid&amp;mode=addraid"));            		
         	break;
        }
            	
		$dkpsys_id = 1;
		$submit = (isset ( $_POST ['dkpsys_id'] )) ? true : false;
		if ($submit)
		{
			$sql = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default 
	                     FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b 
					  where a.dkpsys_id = b.event_dkpid order by dkpsys_name ';
			$result = $db->sql_query ( $sql );

			// get dkp pool value from popup
			$dkpsys_id = request_var ( 'dkpsys_id', 0 );
			// fill popup and set selected to Post value
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$template->assign_block_vars ( 'dkpsys_row', 
					array (
					'VALUE' => $row['dkpsys_id'], 
					'SELECTED' => ($row['dkpsys_id'] == $dkpsys_id) ? ' selected="selected"' : '', 
					'OPTION' => (! empty ( $row['dkpsys_name'] )) ? $row['dkpsys_name'] : '(None)' ) );
				
			}
			$db->sql_freeresult( $result );
		
		} 
		else 
		{
			$sql = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default 
                    FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b 
				  where a.dkpsys_id = b.event_dkpid order by dkpsys_name ';
			$result = $db->sql_query ($sql);		
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				if($row['dkpsys_default'] == "Y"  )
				{
					$dkpsys_id = $row['dkpsys_id'];
				}
				$template->assign_block_vars ( 'dkpsys_row', 
					array (
					'VALUE' => $row['dkpsys_id'], 
					'SELECTED' => ($row['dkpsys_default'] == "Y") ? ' selected="selected"' : '', 
					'OPTION' => (! empty ( $row['dkpsys_name'] )) ? $row['dkpsys_name'] : '(None)' ) );
			}
			$db->sql_freeresult( $result );
		}
		
		/*** end DKPSYS drop-down ***/
		
		$sql_array = array (
			'SELECT' => ' count(*) as raidcount', 
			'FROM' => array (
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',		
				), 
			'WHERE' => " r.event_id = e.event_id and e.event_dkpid = " . ( int ) $dkpsys_id, 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$total_raids = (int) $db->sql_fetchfield('raidcount');
		$db->sql_freeresult ($result);
		
		$start = request_var ( 'start', 0, false );
		$sort_order = array (
				0 => array ('raid_start desc', 'raid_start' ),
				0 => array ('raid_end desc', 'raid_end' ),
				1 => array ('event_name', 'event_name desc' ), 
				2 => array ('raid_note', 'raid_note desc' ), 
				3 => array ('raid_value desc', 'raid_value' ),
				4 => array ('time_value desc', 'time_value' ),
				5 => array ('zs_value desc', 'zs_value' ),
				6 => array ('raiddecay desc', 'raiddecay' ),
				7 => array ('total desc', 'total' ),
				);
		
		$current_order = switch_order ( $sort_order );		
		$sql_array = array (
			'SELECT' => ' sum(ra.raid_value) as raid_value, sum(ra.time_bonus) as time_value, 
						  sum(ra.zerosum_bonus) as zs_value, sum(ra.raid_decay) as raiddecay, 
						  sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay) as total, 
						  e.event_dkpid, e.event_name,  
						  r.raid_id, r.raid_start, r.raid_note, 
						  r.raid_added_by, r.raid_updated_by ', 
			'FROM' => array (
				RAID_DETAIL_TABLE	=> 'ra' ,
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',		
				), 
			'WHERE' => "  ra.raid_id = r.raid_id and r.event_id = e.event_id and e.event_dkpid = " . ( int ) $dkpsys_id,
			'GROUP_BY' => 'e.event_dkpid, e.event_name,  
						  r.raid_id,  r.raid_start, r.raid_note, 
						  r.raid_added_by, r.raid_updated_by',	
			'ORDER_BY' => $current_order ['sql'], 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$raids_result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_rlimit'], $start );
		if (! $raids_result) 
		{
			trigger_error ( $user->lang['ERROR_INVALID_RAID'], E_USER_WARNING );
		}
		
		while ( $row = $db->sql_fetchrow ( $raids_result ) ) 
		{
			$template->assign_block_vars ( 'raids_row', array (
				'DATE' => (! empty ( $row['raid_start'] )) ? date ( $config ['bbdkp_date_format'], $row['raid_start'] ) : '&nbsp;', 
				'U_VIEW_RAID' => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=editraid&amp;" . URI_RAID . "={$row['raid_id']}" ), 
				'NAME' => $row['event_name'], 
				'NOTE' => (! empty ( $row['raid_note'] )) ? $row['raid_note'] : '&nbsp;', 
				'RAIDVALUE'  => $row['raid_value'], 
				'TIMEVALUE'  => $row['time_value'],
				'ZSVALUE' 	 => $row['zs_value'],
				'DECAYVALUE' => $row['raiddecay'], 
				'TOTAL' 	 => $row['total'] 
				)
			);
		}
		
		$template->assign_vars ( array (
			'L_TITLE' 			  => $user->lang ['ACP_LISTRAIDS'], 
			'L_EXPLAIN' 		  => $user->lang ['ACP_LISTRAIDS_EXPLAIN'], 
			'O_DATE' 			  => $current_order ['uri'] [0], 
			'O_NAME' 			  => $current_order ['uri'] [1], 
			'O_NOTE' 			  => $current_order ['uri'] [2], 
			'O_RAIDVALUE' 		  => $current_order ['uri'] [3],
			'O_TIMEVALUE' 		  => $current_order ['uri'] [4],
			'O_ZSVALUE' 		  => $current_order ['uri'] [5],
			'O_DECAYVALUE' 		  => $current_order ['uri'] [6],
			'O_TOTALVALUE' 		  => $current_order ['uri'] [7], 
			'U_LIST_RAIDS' 		  => append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=listraids" ), 
			'START' 			  => $start, 
			'LISTRAIDS_FOOTCOUNT' => sprintf ( $user->lang ['LISTRAIDS_FOOTCOUNT'], $total_raids, $config ['bbdkp_user_rlimit'] ), 
			'RAID_PAGINATION' 	  => generate_pagination ( append_sid 
					( "index.$phpEx", "i=dkp_raid&amp;mode=listraids&amp;o=" . $current_order ['uri'] ['current']) , 
					$total_raids, $config ['bbdkp_user_rlimit'], $start ) ) );
		
			
				
	}
	

	/*
	 * add raid to database
	 */
	private function addraid()
	{
		global $db, $user, $config, $template, $phpEx ;
		if (confirm_box ( true )) 
		{
			// recall hidden vars
			$this->raid = array(
				'raid_note' 		=> utf8_normalize_nfc (request_var ( 'hidden_raid_note', ' ', true )), 
				'raid_event'		=> utf8_normalize_nfc (request_var ( 'hidden_raid_name', ' ', true )), 
				'raid_value' 		=> request_var ('hidden_raid_value', 0.00 ), 
				'raid_timebonus'	=> request_var ('hidden_raid_timebonus', 0.00 ),
				'raid_start' 		=> request_var ('hidden_startraid_date', 0), 
				'raid_end'			=> request_var ('hidden_endraid_date', 0),
				'event_id' 			=> request_var ('hidden_event_id', 0),
				'raid_attendees' 	=> request_var ('hidden_raid_attendees', array ( 0 => 0 )), 
			); 
			
			// Get event info
			$sql = "SELECT event_id, event_name, event_dkpid, event_value FROM " . EVENTS_TABLE . "  WHERE 
	                event_id = " . $this->raid['event_id'];
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow ($result) ) 
			{
				if ($this->raid['raid_value'] == 0.00)
				{
					$this->raid['raid_value'] = max ( $row['event_value'], 0.00 );
				}
				$this->raid['event_dkpid'] = $row['event_dkpid'];
				$this->raid['event_name'] = $row['event_name'];
			}
			$db->sql_freeresult( $result );
			
			/*
			 * start transaction
			 */
			$db->sql_transaction('begin');
			//
			// Insert the raid
			// raid id is auto-increment so it is increased automatically
			//
			$query = $db->sql_build_array ( 'INSERT', array (
					'event_id' 		=> (int) $this->raid['event_id'], 
					'raid_start' 	=> (int) $this->raid['raid_start'],
					'raid_end' 		=> (int) $this->raid['raid_end'], 
					'raid_note' 	=> (string) $this->raid['raid_note'], 
					'raid_added_by' => (string) $user->data['username'] ) 
			);
			
			$db->sql_query ( "INSERT INTO " . RAIDS_TABLE . $query );
			$this->raid ['raid_id'] = $db->sql_nextid();
			// Attendee handling
			
			// Insert the raid detail
			$raiddetail = $this->add_raiddetail($this->raid);
	
			//
			// pass the raidmembers array, raid value, and dkp pool.
			$this->add_dkp ($this->raid);
			
			//commit
			$db->sql_transaction('commit');
			
			//
			// Logging
			//
			$log_action = array (
				'header' => 'L_ACTION_RAID_ADDED', 
				'id' 			=> $this->raid ['raid_id'], 
				'L_EVENT' 		=> $this->raid['event_name'], 
				'L_ATTENDEES' 	=> implode ( ', ', $this->raid ['raid_attendees'] ), 
				'L_NOTE' 		=> $this->raid ['raid_note'], 
				'L_VALUE' 		=> $this->raid['raid_value'], 
				'L_ADDED_BY' 	=> $user->data ['username'] );
			
			$this->log_insert ( array (
				'log_type' 		=> $log_action ['header'], 
				'log_action' 	=> $log_action ) );
			
			$success_message = sprintf ( $user->lang ['ADMIN_ADD_RAID_SUCCESS'], 
				$user->format_date($this->time), $this->raid['event_name'] ) . '<br />';
				
			//
			// Update active / inactive player status if needed
			//
			if ($config ['bbdkp_hide_inactive'] == 1) 
			{
				$success_message .= '<br /><br />' . $user->lang ['ADMIN_RAID_SUCCESS_HIDEINACTIVE'];
				$success_message .= ' ' . (($this->update_player_status ( $this->raid['event_dkpid'] )) ? 
					strtolower ( $user->lang ['DONE'] ) : strtolower ( $user->lang ['ERROR'] ));
			}
			
			//
			// Success message
			//
			trigger_error ( $success_message . $this->link, E_USER_NOTICE );
				
		}
		else
		{
			$event_id = request_var ( 'event_id', 0 );
			if (($event_id == 0)) 
			{
				trigger_error ( $user->lang ['ERROR_INVALID_EVENT_PROVIDED'], E_USER_WARNING );
			}
			
			// store raidinfo as hidden vars
			
			$s_hidden_fields = build_hidden_fields(array(
					'event_id'					=> $event_id, //for when user says no
					'hidden_raid_note' 			=> utf8_normalize_nfc ( request_var ( 'raid_note', ' ', true ) ), 
					'hidden_event_id' 			=> $event_id,
					'hidden_raid_event'			=> utf8_normalize_nfc ( request_var ( 'event_name',	' ', true  ) ), 
					'hidden_raid_value' 		=> request_var ( 'raid_value', 0.00 ),
					'hidden_raid_timebonus' 	=> request_var ( 'time_bonus', 0.00 ),
					'hidden_startraid_date' 	=> mktime(request_var('sh', 0), request_var('smi', 0), request_var('ss', 0), 
					  					   			request_var('mo', 0), request_var('d', 0), request_var('Y', 0)), 
					'hidden_endraid_date' 		=> mktime(request_var('eh', 0), request_var('emi', 0), request_var('es', 0), 
					  					   			request_var('emo', 0), request_var('ed', 0), request_var('eY', 0)), 
					'hidden_raid_attendees' 	=> request_var ( 'raid_attendees', array ( 0 => 0 )), 
					'add'    					=> true, 
			)
			);
			
			$sql='select event_name from ' . EVENTS_TABLE . ' where event_id = ' . $event_id; 
			$result = $db->sql_query($sql);
			$eventname = (string) $db->sql_fetchfield('event_name');
			$db->sql_freeresult($result);
			
			confirm_box(false, sprintf($user->lang['CONFIRM_CREATE_RAID'], $eventname) , $s_hidden_fields);				
			
		}
		
		
	}
	
	/*
	 * update a raid
	 */
	private function updateraid($raid_id)
	{
		global $db, $user, $config, $template, $phpEx;
		if(!check_form_key('acp_dkp_addraid'))
		{
			trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);	
		}
		
		// get old raid data
		$sql_array = array (
			'SELECT' => ' e.event_dkpid, e.event_name,  
						  r.raid_id, r.raid_date, r.raid_note, 
						  r.raid_value, r.raid_added_by, r.raid_updated_by ', 
			'FROM' => array (
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',		
				), 
			'WHERE' => "  r.event_id = e.event_id and r.raid_id = " . (int) $raid_id, 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		
		$result = $db->sql_query ($sql);
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$this->old_raid = array (
				'event_id' 		=> (int) $row['event_id'], 
				'event_dkpid' 	=> (int) $row['event_dkpid'],
			 	'event_name' 	=> (string) $row['event_name'],
				'raid_value' 	=> (float) $row['raid_value'], 
				'raid_note' 	=> (string) $row['raid_note'], 
				'raid_date' 	=> (int) $row['raid_date'], 
			);
		}
		$db->sql_freeresult( $result );
		
		// get old attendee list
		$this->old_raid ['raid_attendees'] = array();
		
		$sql = ' SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' 
	             WHERE raid_id= ' . (int) $raid_id . ' ORDER BY member_id' ;
		
		$result = $db->sql_query($sql);
		if ($result)
		{
			while ( $row = $db->sql_fetchrow ($result)) 
			{
				$this->old_raid ['raid_attendees'] [] = $row['member_id'];
			}
		}
		else 
		{
			// no attendees found, should never get here
			trigger_error ( $user->lang['ERROR_RAID_NOATTENDEES'] . $this->link, E_USER_WARNING );
		}
		$db->sql_freeresult( $result );
		
		// get updated data		
		$this->raid = array (
			'raid_attendees' 		=> request_var ( 'raid_attendees', array (0 => 0)), 
			'event_id' 	 	 		=> request_var ( 'event_id', 0 ), 
			'raid_value' 	 		=> request_var ( 'raid_value', 0.00 ),  
			'raid_note' 	 		=> utf8_normalize_nfc ( request_var ( 'raid_note', ' ', true ) ), 
			'raid_date' 	 		=> mktime(request_var('h', 0), request_var('mi', 0), request_var('s', 0), 
			  					request_var('mo', 0), request_var('d', 0), request_var('Y', 0)), 
		);
		
		$db->sql_transaction('begin');
		
		// Remove the old attendees from the raid
		$db->sql_query ( 'DELETE FROM ' . RAID_DETAIL_TABLE . " WHERE raid_id = " . (int) $raid_id );
		
		// Insert the new attendees in attendee table
		$this->add_raiddetail ( $this->raid['raid_attendees'], $raid_id );

		// decrease raidcount with one old raidparticipants, 
		// decrease dkp from old raidparticipants
		$this->remove_dkp ( $this->old_raid ['raid_attendees'] , $this->old_raid ['raid_value'], $this->old_raid['event_dkpid'] );

		// update last raiddate for old raidparticipants
		$this->remove_raiddate( $this->old_raid ['raid_attendees'] , $this->old_raid ['raid_date'], $this->old_raid['event_dkpid'] );
		
		// Add or update the new dkp to new particpants
		// add to raidcount
		// update last raiddate for new raidparticipants
		$this->add_dkp ( $this->raid['raid_attendees'] , $this->raid['raid_value'], $this->old_raid['event_dkpid'], $this->raid['raid_date'] );
		
		$db->sql_transaction('commit');
		
		// RAIDS_TABLE
		// Update the raid
		$query = $db->sql_build_array ( 'UPDATE', array (
			'event_id' 			=> (int) $this->raid['event_id'],
			'raid_date' 		=> $this->raid['raid_date'],
			'raid_note' 		=> $this->raid['raid_note'], 
			'raid_value' 		=> $this->raid['raid_value'], 
			'raid_updated_by' 	=> $user->data ['username'] ) );
		$db->sql_query ( 'UPDATE ' . RAIDS_TABLE . ' SET ' . $query . " WHERE raid_id = " . ( int ) $raid_id );
		

		//
		// Logging
		//
		$log_action = array (
			'header' => 'L_ACTION_RAID_UPDATED', 
			'id' => $raid_id, 
			'L_EVENT_BEFORE' => $this->old_raid ['event_id'], 
			'L_ATTENDEES_BEFORE' => implode ( ', ', $this->old_raid ['raid_attendees'] ), 
			'L_NOTE_BEFORE' => $this->old_raid ['raid_note'], 
			'L_VALUE_BEFORE' => $this->old_raid ['raid_value'], 
			'L_EVENT_AFTER' => $this->raid['event_id'], 
			'L_ATTENDEES_AFTER' => implode ( ', ', $this->raid['raid_attendees'] ), 
			'L_NOTE_AFTER' => utf8_normalize_nfc ( request_var ( 'raid_note', ' ', true ) ), 
			'L_VALUE_AFTER' => $this->raid ['raid_value'], 
			'L_UPDATED_BY' => $user->data ['username'] );
		
		$this->log_insert ( array (
			'log_type' => $log_action ['header'], 
			'log_action' => $log_action ));
		
		//
		// Success message
		//
		$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_RAID_SUCCESS'], 
			request_var ( 'mo', ' ' ), request_var ( 'd', ' ' ), request_var ( 'Y', ' ' ), 
			utf8_normalize_nfc ( request_var ( 'raid_name', ' ', true ) ) );
		
		// Update player status if needed
		if ($config ['bbdkp_hide_inactive'] == 1) 
		{
			$success_message .= '<br /><br />' . $user->lang ['ADMIN_RAID_SUCCESS_HIDEINACTIVE'];
			$success_message .= ' ' . (($this->update_player_status ( $dkpsys_id )) ? strtolower ( $user->lang ['DONE'] ) : 
			strtolower ( $user->lang ['ERROR'] ));
		}
		
		trigger_error ( $success_message . $this->link, E_USER_NOTICE );
		
	}
	
	/*
	 * delete a raid
	 */
	private function deleteraid($raid_id)
	{
		global $db, $user, $config, $template, $phpEx ;
		
		$sql_array = array (
			'SELECT' => ' e.event_id, event_dkpid, e.event_name, r.raid_date, r.raid_value ', 
			'FROM' => array (
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',		
				), 
			'WHERE' => " r.event_id = e.event_id and r.raid_id=" . ( int ) $raid_id, 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$this->old_raid = array (
				'event_id' 		=> $row['event_id'],
				'event_dkpid' 	=> $row['event_dkpid'], 
				'event_name' 	=> $row['event_name'], 
				'raid_date' 	=> $row['raid_date'],  
				'raid_value' 	=> (float) $row['raid_value'], 
			);
		}
		$db->sql_freeresult( $result );

		if (confirm_box ( true )) 
		{
			$this->old_raid ['raid_attendees'] = array();
			$sql = ' SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' 
		             WHERE raid_id= ' . (int) $raid_id . ' ORDER BY member_id' ;
			
			$result = $db->sql_query($sql);
			if ($result)
			{
				while ( $row = $db->sql_fetchrow ($result)) 
				{
					$this->old_raid ['raid_attendees'] [] = $row['member_id'];
				}
			}
			else
			{
				
				// not possible after 1.1
				$db->sql_query ( 'DELETE FROM ' . RAIDS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
				$db->sql_query ( 'DELETE FROM ' . RAID_ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
				$log_action = array (
						'header' => 'L_ACTION_RAID_DELETED', 
						'id' => $raid_id, 
						'L_EVENT' => $this->old_raid ['event_name'], 
						'L_NOTE' => $this->old_raid ['raid_note'] );
					
					$this->log_insert ( array (
						'log_type' => $log_action ['header'], 
						'log_action' => $log_action ) );
					
					$success_message = $user->lang ['ADMIN_DELETE_RAID_SUCCESS'] . ' ' . 
						$user->lang['WARNING_NOATTENDEES'];
					trigger_error ( $success_message . $this->link, E_USER_WARNING );
			}

			// Remove the old attendees from the raid
			$db->sql_query ( 'DELETE FROM ' . RAID_DETAIL_TABLE . " WHERE raid_id = " . (int) $raid_id );
		
			// decrease raidcount with one old raidparticipants, 
			// decrease dkp from old raidparticipants
			$this->remove_dkp ( $this->old_raid ['raid_attendees'] , $this->old_raid ['raid_value'], $this->old_raid['event_dkpid'] );
	
			// update last raiddate for old raidparticipants
			$this->remove_raiddate( $this->old_raid ['raid_attendees'] , $this->old_raid ['raid_date'], $this->old_raid['event_dkpid'] );
					
			// Remove cost of items from this raid from buyers
			$this->remove_loot($raid_id);
			
			// Remove the raid itself
			$db->sql_query ( 'DELETE FROM ' . RAIDS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
			
			// Logging
			$log_action = array (
				'header' => 
				'L_ACTION_RAID_DELETED', 
				'id' => $raid_id, 
				'L_EVENT' => $this->old_raid ['event_id'], 
				'L_ATTENDEES' => str_replace ( ',', ', ', $this->old_raid ['raid_attendees'] ), 
				'L_NOTE' => $this->old_raid ['raid_note'], 
				'L_VALUE' => $this->old_raid ['raid_value'] );
			
			$this->log_insert ( array ('log_type' => $log_action ['header'], 'log_action' => $log_action ) );
			
			//
			// Success message
			//
			$success_message = $user->lang ['ADMIN_DELETE_RAID_SUCCESS'];
			
			// Update player status if needed
			if ($config ['bbdkp_hide_inactive'] == 1) 
			{
				$success_message .= '<br /><br />' . $user->lang ['ADMIN_RAID_SUCCESS_HIDEINACTIVE'];
				$success_message .= ' ' . (($this->update_player_status ( $dkpsys_id )) ? 
				strtolower ( $user->lang ['DONE'] ) : 
				strtolower ( $user->lang ['ERROR'] ));
			}
			
			trigger_error ( $success_message . $this->link, E_USER_NOTICE );
		
		} 
		else 
		{
			$s_hidden_fields = build_hidden_fields ( array (
				'delete' 			=> true, 
				'raid'				=> $this->old_raid,
				'hidden_id' 		=> request_var ( 'hidden_id', 0 ), 
				'hidden_eventid' 	=> request_var ( 'hidden_eventid', 0 ) ) );
			
			$template->assign_vars ( array ('S_HIDDEN_FIELDS' => $s_hidden_fields ) );
			confirm_box ( false, $user->lang ['CONFIRM_DELETE_RAID'], $s_hidden_fields );
		}
	
	}	
	
    /**
    * raid_detail handler : Insert raid detail
    * @param $members_array array of member_id
    * @param raid_value
    * @param time_bonus
    * @param $raid_id
    */
    private function add_raiddetail($raid)
    {
        if(sizeof($raid['raid_attendees']) == 0)
    	{
    		return;	
    	}
    	
        global $db;
        $raid_detail = array();
        foreach ( $raid['raid_attendees'] as $member_id )
        {
            $raid_detail[] = array(
                'raid_id'      => (int) $raid['raid_id'],
                'member_id'   => (int)  $member_id,
	            'raid_value'   => (int) $raid['raid_value'],
	            'time_bonus'   => (int) $raid['raid_timebonus'],
				);
        }
        $db->sql_multi_insert(RAID_DETAIL_TABLE, $raid_detail);
        return $raid_detail;
    }
   
   
	/**
    * function add_dkp : 
    * adds raid value as earned to each raider, 
    * increase raidcount
    * set last and first raiddates for the attending raiders
    *
    * @param $raid (array)
		'raid_note' 		 
		'raid_event'		 
		'raid_value' 		 
		'raid_timebonus'	
		'raid_start' 		 
		'raid_end'			
		'event_id' 			
		'raid_attendees' 	(array) 
		'event_dkpid'
		'event_name' 
    */
    private function add_dkp($raid)
    {

		global $db, $user;
        // we loop new raidmember array
        foreach ( (array) $raid['raid_attendees'] as $member_id )
        {
            // has dkp record ?
			$sql = 'SELECT count(member_id) as present
				FROM ' . MEMBER_DKP_TABLE . '  
				WHERE member_id = ' . $member_id . ' 
				AND member_dkpid = ' . $raid['event_dkpid'];

			 $result = $db->sql_query($sql);
             $present = (int) $db->sql_fetchfield('present', false, $result);
             $db->sql_freeresult($result);
             if ($present == 1)
             {
                 //update
                  $sql = 'SELECT member_lastraid, member_firstraid FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . ' AND  member_dkpid = ' . $raid['event_dkpid'];  
                  $result = $db->sql_query($sql);
                  while ($row = $db->sql_fetchrow($result) )  
                  {
 					$this->update_dkprecord($raid['raid_value'], $raid['raid_timebonus'], $raid['raid_start'] , $raid['event_dkpid'], $member_id);  
                  }
                  $db->sql_freeresult($result);
             }
             elseif ($present == 0)
             {
				add_dkprecord($raid['raid_value'] , $raid['raid_timebonus'], $raid['raid_start'], $raid['event_dkpid'], $member_id); 
             }
        }
    }
    
    /*
     * updates dkp record
     */
    private function update_dkprecord($raid_value, $timebonus, $raidstart, $dkpid, $member_id)
    {
    	global $db, $user; 
  	    $sql  = 'UPDATE ' . MEMBER_DKP_TABLE . ' m
	       SET m.member_earned = m.member_earned + ' . (float) $raid_value . ' + ' . (float) $timebonus . ' , 
	       m.member_raid_value = m.member_raid_value + ' . (float) $raid_value . ', 
	       m.member_time_bonus = m.member_time_bonus + ' . (float) $timebonus . ', ';
	       
	       // Do update their lastraid if it's earlier than this raid's starting time
	       if ( $row['member_lastraid'] < $raidstart )
	       {
	          $sql .= 'm.member_lastraid = ' . $raidstart. ', ';
	       }
	       
	       // Do update their firstraid if it's later than this raid's starting time
	       if ( $row['member_firstraid'] > $raidstart )
	       {
	          $sql .= 'm.member_firstraid = ' . $raidstart . ', ';
	       }
	       
	       $sql .= ' m.member_raidcount = m.member_raidcount + 1
	       WHERE m.member_dkpid = ' . (int)  $dkpid . '
	       AND m.member_id = ' . (int) $member_id;
       $db->sql_query($sql);
       return true;
    }
    
    /*
     * adds dkp record
     */
    private function add_dkprecord($raid_value, $timebonus, $raidstart, $dkpid, $member_id)
    {
    	global $db, $user; 
         // insert new dkp record
         $query = $db->sql_build_array('INSERT', array(
            'member_dkpid'       =>  $dkpid,
            'member_id'          => $member_id,
            'member_earned'      => (float) $raid_value + (float) $timebonus,
         	'member_raid_value'  => (float) $raid_value ,
         	'member_time_bonus'  => (float) $timebonus ,
            'member_status'      => 1,
            'member_firstraid'   => $raidstart,
            'member_lastraid'    => $raidstart,
            'member_raidcount'   => 1
          ));
         $db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
         return true;
    	
    }
 
	/**
    * remove_dkp : removes raid value
    *
    * @param $members_array
    * @param $raid_value
    * @param $dkpid
		 decrease number of raids by one 
		 decrease earned by old value 
    */
    private function remove_dkp($oldmembers_array, $oldraid_value, $olddkpid)
    {
        if(sizeof($oldmembers_array) == 0)
    	{
    		return;	
    	}
    	
        global $db, $user;
        // we loop old raidmember array
        foreach ( (array) $members_array['member_id'] as $member_id )
        {
        	//get last raid
            $sql = 'SELECT m.member_lastraid, m.member_raidcount, member_earned 
            FROM ' . MEMBER_DKP_TABLE . " 
            WHERE member_id = ' . $member_id . ' 
            AND  member_dkpid = " . $dkpid;  
            $result = $db->sql_query($sql);

            while ($row = $db->sql_fetchrow($result) )  
            {
             	$member_raidcount = $row['member_raidcount'];
             	$earned = $row['member_earned'];
             	$last_raid = $row['member_lastraid'];
            }
            
            $newraidcount = max(0, $member_raidcount - 1);
            $newdkp = $earned - $oldraid_value;
            
            $query = $db->sql_build_array ( 'UPDATE', array (
				'member_raidcount' 		=> $newraidcount,
				'member_earned' 		=> $newdkp, 
            ));
            
			$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $query . " 
					WHERE member_dkpid = " . ( int ) $member_id );
		
            
            
             $db->sql_freeresult($result);
        }
    }
    
	/**
    * remove_dkp : removes raid date from old participants, sets to the closest date found in attendeelist
    *
    * @param $members_array
    * @param $oldraid_date
    * @param $dkpid
    */
    private function remove_raiddate($oldmembers_array, $olddkpid)
    {
    	
    	if(sizeof($oldmembers_array) == 0)
    	{
    		return;	
    	}
    	
        global $db, $user;
        
        // scan attendeelist
        $sql_array = array (
		'SELECT' => 'MIN( r.raid_date ) AS member_firstraid, 
					 MAX(r.raid_date) AS member_lastraid, 
					 ra.member_id ', 
		'FROM' => array (
			RAIDS_TABLE => 'r', 
			RAID_DETAIL_TABLE => 'ra' ,
			EVENTS_TABLE => 'e' 
			), 
		'WHERE' => ' ra.raid_id = r.raid_id 
					AND r.event_id = e.event_id 
					AND e.event_dkpid = ' . $olddkpid . '
					AND ' . $db->sql_in_set ( 'ra.member_id',  $oldmembers_array ), 
		'GROUP_BY' => 'member_id'
		);
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		$battledate = array();
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$battledate['member_id'] = array(
				'member_firstraid' => 'member_firstraid',
				'member_lastraid' => 'member_lastraid',
			);
			
		}
		$db->sql_freeresult ($result);
			
        // we loop old raidmember array
        foreach ( (array) $oldmembers_array['member_id'] as $member_id )
        {
        	//get last raid
            $sql = 'SELECT m.member_lastraid, m.member_firstraid 
            FROM ' . MEMBER_DKP_TABLE . " 
            WHERE member_id = ' . $member_id . ' 
            AND  member_dkpid = " . $dkpid;  
            $result = $db->sql_query($sql);

            $first_raid =0;
			$last_raid =0;
            
			while ($row = $db->sql_fetchrow($result) )  
            {
             	$first_raid = $row['member_firstraid'];
             	$last_raid = $row['member_lastraid'];
            }
			$db->sql_freeresult ($result);
			
            if ( isset($battledate[$member_id]['member_firstraid']))
            {
            	if( $first_raid > $battledate[$member_id]['member_firstraid'])
            	{
	            	$first_raid = $battledate[$member_id]['member_firstraid'];
            	}
            	
            }
            else 
            {
            	// no firstraid
            	$first_raid = 0;
            }
            
            if ( isset($battledate[$member_id]['member_lastraid']))
            {
            	if( $last_raid > $battledate[$member_id]['member_lastraid'])
            	{
	            	$last_raid = $battledate[$member_id]['member_lastraid'];
            	}
            }
            else 
            {
            	// no lastraid
            	$last_raid = 0;
            }            
            
            $query = $db->sql_build_array ( 'UPDATE', array (
				'member_firstraid' 		=> $first_raid,
				'member_lastraid' 		=> $last_raid, 
            ));
            
			$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $query . ' 
	             WHERE member_id = ' . $member_id . ' 
	             AND  member_dkpid = ' . $dkpid);
        }
    }
    

	/***
	 * Set active or inactive based on last raid. only for current raids dkp pool
	 * Update active inactive player status column member_status
	 * active = 1 inactive = 0
	 * @return bool
	 * @param $dkpid int
	 *
	 */
	private function update_player_status($dkpid)
	{
		global $db, $user, $config;
		
		$inactive_time = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ) - $config ['bbdkp_inactive_period'], date ( 'Y' ) );
		
		$active_members = array ();
		$inactive_members = array ();
		
		// Don't do active/inactive adjustments if we don't need to.
		if (($config ['bbdkp_active_point_adj'] != 0) || ($config ['bbdkp_inactive_point_adj'] != 0))
		{
			// adapt status and set adjustment points 
			$sql_array = array (
				'SELECT' => 'a.member_id, b.member_name, a.member_status, a.member_lastraid', 
				'FROM' => array (
					MEMBER_DKP_TABLE => 'a', 
					MEMBER_LIST_TABLE => 'b' 
					), 
				'WHERE' => ' a.member_id = b.member_id AND a.member_dkpid =' . $dkpid 
			);
			
			$sql = $db->sql_build_query ( 'SELECT', $sql_array );
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow ( $result ) )
			{
				unset ( $adj_value ); // destroy local
				unset ( $adj_reason );
				
				// Active -> Inactive
				if (((float) $config ['bbdkp_inactive_point_adj'] != 0.00) && ($row['member_status'] == 1) && ($row['member_lastraid'] < $inactive_time))
				{
					$adj_value = $config ['bbdkp_inactive_point_adj'];
					$adj_reason = 'Inactive adjustment';
					$inactive_members [] = $row['member_id'];
					$inactive_membernames [] = $row['member_name'];
				} // Inactive -> Active
				elseif (( (float) $config ['bbdkp_active_point_adj'] != 0.00) && ($row['member_status'] == 0) && ($row['member_lastraid'] >= $inactive_time))
				{
					$adj_value = $config ['bbdkp_active_point_adj'];
					$adj_reason = 'Active adjustment';
					$active_members [] = $row['member_id'];
					$active_membernames [] = $row['member_name'];
				}
				
				//
				// Insert individual adjustment
				if ((isset ( $adj_value )) && (isset ( $adj_reason )))
				{
					$group_key = $this->gen_group_key ( $this->time, $adj_reason, $adj_value );
					$query = $db->sql_build_array ( 'INSERT', 
						array (
							'adjustment_dkpid' 		=> $dkpid, 
							'adjustment_value' 		=> $adj_value, 
							'adjustment_date' 		=> $this->time, 
							'member_id' 			=> $row['member_id'], 
							'adjustment_reason' 	=> $adj_reason, 
							'adjustment_group_key' 	=> $group_key, 
							'adjustment_added_by' 	=> $user->data ['username'] ));
					
					$db->sql_query ( 'INSERT INTO ' . ADJUSTMENTS_TABLE . $query );
				}
			}
			
			// Update members to inactive and put dkp adjustment
			if (sizeof ( $inactive_members ) > 0)
			{
				$adj_value = (float) $config ['bbdkp_inactive_point_adj'];
				$adj_reason = 'Inactive adjustment';
					
				$sql_ary = array(
				    'member_status'      => 0, 
				    'member_adjustment'  => 'member_adjustment + ' . $adj_value,
				);
				 
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				    SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
	                WHERE member_dkpid = ' . $dkpid . '  AND ' . $db->sql_in_set ( 'member_id', $inactive_members ) . ')';
				$db->sql_query($sql);

				$log_action = array (
					'header' 		=> 'L_ACTION_INDIVADJ_ADDED', 
					'L_ADJUSTMENT' 	=> $config ['bbdkp_inactive_point_adj'], 
					'L_MEMBERS' 	=> implode ( ', ', $inactive_membernames ), 
					'L_REASON' 		=> $user->lang['INACTIVE_POINT_ADJ'],  
					'L_ADDED_BY'	=> $user->data ['username'] );
				
				$this->log_insert ( array (
					'log_type' 		=> $log_action ['header'], 
					'log_action' 	=> $log_action ));
			 }
			
			// Update active members' adjustment
			if (sizeof ( $active_members ) > 0)
			{
				$adj_value = (float) $config ['bbdkp_active_point_adj'];
				
				$sql_ary = array(
				    'member_status'      => 1, 
				    'member_adjustment'  => 'member_adjustment + ' . $adj_value,
				);
				 
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				    SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
	                WHERE member_dkpid = ' . $dkpid . '  AND ' . $db->sql_in_set ( 'member_id', $active_members ) . ')';
				$db->sql_query($sql);
				
				$log_action = array (
					'header' 		=> 'L_ACTION_INDIVADJ_ADDED', 
					'L_ADJUSTMENT' 	=> $config ['bbdkp_active_point_adj'], 
					'L_MEMBERS' 	=> implode ( ', ', $active_membernames ), 
					'L_REASON' 		=> $user->lang['ACTIVE_POINT_ADJ'], 
					'L_ADDED_BY' 	=> $user->data ['username'] );
				$this->log_insert ( array ('log_type' => $log_action ['header'], 'log_action' => $log_action ) );
			}
		}
		else
		{
			// only adapt status 
			
			// Active -> Inactive
			$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . " SET member_status = 0 WHERE member_dkpid = " . $dkpid . "
	     		AND (member_lastraid <  " . $inactive_time . ") AND (member_status= 1)" );
			
			// Inactive -> Active
			$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . " SET member_status = 1 WHERE member_dkpid = " . $dkpid . "  
	   			AND (member_lastraid >= " . $inactive_time . ") AND (member_status= 0)" );
		}
		
		return true;
	}
	
	private function remove_loot($raid_id)
	{
		global $db;
		
		$sql = 'SELECT item_id, member_id, item_value FROM ' . RAID_ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id;
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow( $result ) ) 
			{
				$item_value = (! empty ( $row['item_value'] )) ? $row['item_value'] : 0.00;
				
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
	           		SET member_spent = member_spent - ' . $item_value . '
			        WHERE member_dkpid = ' . $this->old_raid['event_dkpid'] . ' 
	       	        AND member_id = ' . $row['member_id'];
				$db->sql_query ( $sql );
			}
			$db->sql_freeresult( $result );
						
			//
			// Delete associated items
			//
			$db->sql_query ( 'DELETE FROM ' . RAID_ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
	}

}

?>
