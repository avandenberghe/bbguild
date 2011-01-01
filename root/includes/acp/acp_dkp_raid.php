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
	var $u_action;
	var $link; 

	function main($id, $mode) 
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang ( array ('mods/dkp_admin' ) );
		$user->add_lang ( array ('mods/dkp_common' ) );
		$this->link = '<br /><a href="' . append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=listraids" ) . '"><h3>'.$user->lang['RETURN_DKPINDEX'].'</h3></a>';
		$dkpsys_id=0;
		$raid_id=0;
		
		switch ($mode) 
		{
			case 'addraid' :
				if (isset ( $_GET [URI_RAID] ) ) 
				{
					/* editing a raid */
					$raid_id = request_var (URI_RAID, 0);
					$this->displayraid($raid_id);
				} 
				elseif (isset ( $_POST['add']) || isset ($_POST['update']) || isset ($_POST ['delete']) || isset ($_POST ['additem']) ) 
				{
					/* commit update delete */
					$raid_id = request_var ( 'hidden_id', 0 );
					$submit = (isset ( $_POST ['add'] )) ? true : false;
					$update = (isset ( $_POST ['update'] )) ? true : false;
					$delete = (isset ( $_POST ['delete'] )) ? true : false;
					$additem = (isset ( $_POST ['additem'] )) ? true : false;
					
					if($submit)
					{
						$event_id = request_var ( 'event_id', 0 );
						if (($event_id == 0)) 
						{
							trigger_error ( $user->lang ['ERROR_INVALID_EVENT_PROVIDED'], E_USER_WARNING );
						}
						$this->addraid($event_id);
					}
					
					if($update)
					{
						$this->updateraid($raid_id);
					}
					
					if($delete)
					{
						$this->deleteraid($raid_id);
					}
					
					if($additem)
					{
						meta_refresh(0, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_item&amp;mode=additem&raid=' . $raid_id));
					}
					
					$this->displayraid($raid_id);
	
				} 
				else 
				{
					/* newpage */
					$this->displayraid(0);
				}
				
				$this->page_title = 'ACP_ADDRAID';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;

			case 'listraids' :
				$this->listraids();
				$this->page_title = 'ACP_LISTRAIDS';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;		
		}
	
	}
	
	
	/*
	 * add raid
	 */
	function addraid($event_id)
	{
		global $db, $user, $config, $template, $phpEx ;
		if(!check_form_key('acp_dkp_addraid'))
		{
			trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);	
		}
		/* add a new raid */
		$this->raid = array (
			'raid_date' 		=> mktime(request_var('h', 0), request_var('mi', 0), request_var('s', 0), 
			  					   		request_var('mo', 0), request_var('d', 0), request_var('Y', 0)), 
			'event_id' 			=> $event_id, 
			'raid_attendees' 	=> utf8_normalize_nfc ( request_var ( 'raid_attendees', array ( 0 => '' ), true ) ), 
			'raid_note' 		=> utf8_normalize_nfc ( request_var ( 'raid_note', ' ', true ) ), 
			'raid_event'		=> utf8_normalize_nfc ( request_var ( 'raid_name',	' ', true  ) ), 
			'raid_value' 		=> request_var ( 'raid_value', 0.00 ) 
		);
		
		//
		// Get the raid value, dkpid
		// raid value passed is zero, getting default event value from database
		$sql = "SELECT event_id, event_name, event_dkpid, event_value FROM " . EVENTS_TABLE . "  WHERE 
                  event_id = " . $event_id;
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ($result) ) 
		{
			if ($this->raid['raid_value'] == 0.00)
			{
				$this->raid['raid_value'] = max ( $row ['event_value'], 0.00 );
			}
			$this->raid['event_dkpid'] = $row ['event_dkpid'];
			$this->raid['event_name'] = $row ['event_name'];
		}
		$db->sql_freeresult ( $result );
		
		//
		// Insert the raid
		// raid id is auto-increment so it is increased automatically
		//
		$query = $db->sql_build_array ( 'INSERT', array (
				'event_id' 		=> (int) $this->raid['event_id'], 
				'raid_date' 	=> (int) $this->raid['raid_date'], 
				'raid_note' 	=> (string) $this->raid['raid_note'], 
				'raid_value' 	=> (float) $this->raid['raid_value'], 
				'raid_added_by' => (string) $user->data['username'] ) 
		);
		
		$db->sql_query ( "INSERT INTO " . RAIDS_TABLE . $query );
		$this_raid_id = $db->sql_nextid();
		
		// Attendee handling
		
		// Insert the attendees in the raid attendees table
		$attendees = $this->add_attendees ( $this->raid ['raid_attendees'], $this_raid_id );

		//
		// pass the raidmembers array, raid value, and dkp pool.
		$this->handle_memberdkp ($attendees, $this->raid['raid_value'], $this->raid['event_dkpid'] );
		
		
		//
		// Logging
		//
		$log_action = array (
			'header' => 'L_ACTION_RAID_ADDED', 
			'id' 			=> $this_raid_id, 
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
	
	
	/*
	 * update a raid
	 */
	function updateraid($raid_id)
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
			'ORDER_BY' => $current_order ['sql'], 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		
		$result = $db->sql_query ($sql);
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$this->old_raid = array (
				'event_id' 		=> (int) $row ['event_id'], 
				'event_dkpid' 	=> (int) $row ['event_dkpid'],
			 	'event_name' 	=> (string) $row ['event_name'],
				'raid_value' 	=> (float) $row ['raid_value'], 
				'raid_note' 	=> (string) $row ['raid_note'], 
				'raid_date' 	=> (int) $row ['raid_date'], 
			);
		}
		$db->sql_freeresult ( $result );
		
		// get old attendee list
		$this->old_raid ['raid_attendees'] = array();
		
		$sql = ' SELECT member_id FROM ' . RAID_ATTENDEES_TABLE . ' 
	             WHERE raid_id= ' . (int) $raid_id . ' ORDER BY member_id' ;
		
		$result = $db->sql_query($sql);
		if ($result)
		{
			while ( $row = $db->sql_fetchrow ($result)) 
			{
				$this->old_raid ['raid_attendees'] [] = $row ['member_id'];
			}
		}
		else 
		{
			// no attendees found, should never get here
			trigger_error ( $user->lang['ERROR_RAID_NOATTENDEES'] . $this->link, E_USER_WARNING );
		}
		$db->sql_freeresult ( $result );
		
		// get updated data		
		$this->raid = array (
			'raid_attendeenames' 	=> utf8_normalize_nfc ( request_var ( 'raid_attendees', array ( 0 => '' ), true ) ), 
			'event_id' 	 	 		=> request_var ( 'event_id', 0 ), 
			'raid_value' 	 		=> request_var ( 'raid_value', 0.00 ),  
			'raid_note' 	 		=> utf8_normalize_nfc ( request_var ( 'raid_note', ' ', true ) ), 
			'raid_date' 	 		=> mktime(request_var('h', 0), request_var('mi', 0), request_var('s', 0), 
			  					request_var('mo', 0), request_var('d', 0), request_var('Y', 0)), 
		);
		
		
		// Remove the attendees from the old raid
		$db->sql_query ( 'DELETE FROM ' . RAID_ATTENDEES_TABLE . " WHERE raid_id = " . (int) $raid_id );
		
		if (sizeof($this->old_raid ['raid_attendees']) > 0)
		{
			//  MEMBER_DKP_TABLE
			// decrease number of raids by one for old raid participants
			// decrease earned by old value one for old raid participants
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET 
	               member_earned = member_earned - ' . $this->old_raid ['raid_value'] . ',
	               member_raidcount = member_raidcount - 1 
	               WHERE member_dkpid = ' . $dkpsys_id . ' 
	               AND member_id in ( SELECT member_id from ' . 
					MEMBER_LIST_TABLE . ' WHERE ' . 
					$db->sql_in_set ( 'member_name', $this->old_raid ['raid_attendees'] ) . ")";
			$db->sql_query ( $sql );
		}
		
		// MEMBER_DKP_TABLE
		// Add the new, updated raid to attendees' earned
		$this->handle_memberdkp ( $this->raid['raid_attendees'] , $raid_value, $dkpsys_id );
		
		// RAIDS_TABLE
		// Update the raid
		$query = $db->sql_build_array ( 'UPDATE', array (
			'event_id' 			=> (int) $this->raid['event_id'],
			'raid_date' 		=> $this->raid['raid_date'],
			'raid_note' 		=> $this->raid['raid_note'], 
			'raid_value' 		=> $raid_value, 
			'raid_updated_by' 	=> $user->data ['username'] ) );
		
		$db->sql_query ( 'UPDATE ' . RAIDS_TABLE . ' SET ' . $query . " WHERE raid_id = " . ( int ) $raid_id );
		
		// Insert in attendee table
		$this->add_attendees ( $this->raid['raid_attendees'], $raid_id );
		// Update firstraid / lastraid
		$update_firstraid = array (); // Members who need their firstraid updated
		$update_lastraid = array (); // Members who need their lastraid updated
		
		$sql = 'SELECT b.member_name, a.member_firstraid, a.member_lastraid, a.member_raidcount 
                     FROM ' . MEMBER_DKP_TABLE . ' a , ' . MEMBER_LIST_TABLE . ' b 
                     WHERE a.member_dkpid = ' . $dkpsys_id . " 
                     AND a.member_id = b.member_id  
                     AND " .  $db->sql_in_set ( 'b.member_name', $this->raid['raid_attendees'] ); 
		
		$result = $db->sql_query ( $sql );
		
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			// If the raid's date changed...
			if ($this->time != $this->old_raid ['raid_date']) 
			{
				// If the raid's old date is their firstraid, update their firstraid
				if ($row ['member_firstraid'] == $this->old_raid ['raid_date']) 
				{
					$update_firstraid [] = $row ['member_name'];
				}
				
				// If the raid's old date is their lastraid, update their lastraid
				if ($row ['member_lastraid'] == $this->old_raid ['raid_date']) 
				{
					$update_lastraid [] = $row ['member_name'];
				}
			}
		}
		$db->sql_freeresult ( $result );
		
		
		// Find members who were deleted from this raid and revert their first/last					

		if (sizeof($this->old_raid ['raid_attendees']) > 0)
		{
			foreach ( $this->old_raid ['raid_attendees'] as $oldmember_name ) 
			{
				if (! in_array ( $oldmember_name, $this->raid['raid_attendees'] )) 
				{
					$update_firstraid [] = $oldmember_name;
					$update_lastraid [] = $oldmember_name;
				}
			}
		}
		
		// eliminate duplicates
		$update_firstraid = array_unique ( $update_firstraid );
		$update_lastraid = array_unique ( $update_lastraid );
		
		//sort
		sort ( $update_firstraid );
		sort ( $update_lastraid );
		
		// reset pointer to first element
		reset ( $update_firstraid );
		reset ( $update_lastraid );
		
		$queries = array ();
		
		// Update selected firstraids if needed
		// this raid == $this->time
		if (sizeof ( $update_firstraid ) > 0) 
		{
			$sql = 'SELECT MIN(r.raid_date) AS member_firstraid, ra.member_id
                        FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra
                        WHERE ra.raid_id = r.raid_id 
                        AND " . $db->sql_in_set ( 'ra.member_name', $update_lastraid ) . ' 
                        AND r.raid_date > 0 
                        GROUP BY ra.member_id';
			$result = $db->sql_query ( $sql );
			
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$queries [] = 'UPDATE ' . MEMBER_DKP_TABLE . "
                                  SET member_firstraid = '" . $row ['member_firstraid'] . "'
                                  WHERE member_dkpid = " . $dkpsys_id . " 
                                  and member_id = " . $row ['member_id'];
			
			}
			$db->sql_freeresult ( $result );
		}
		
		// Updated selected lastraids if needed
		if (sizeof ( $update_lastraid ) > 0) 
		{
			$sql = 'SELECT MAX(r.raid_date) AS member_lastraid, ra.member_id
                        FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra
                        WHERE ra.raid_id = r.raid_id
                        AND " . $db->sql_in_set ( 'ra.member_name', $update_lastraid ) . ' 
                        AND r.raid_date > 0
                        GROUP BY ra.member_id';
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow ( $result ) ) 

			{
				$queries [] = 'UPDATE ' . MEMBER_DKP_TABLE . "
                                  SET member_lastraid = '" . $row ['member_lastraid'] . "'
                                  WHERE member_dkpid = " . $dkpsys_id . " 
                                  and member_id = " . $row ['member_id'];
			
			}
			$db->sql_freeresult ( $result );
		}
		
		foreach ( $queries as $sql ) 
		{
			$db->sql_query ( $sql );
		}
		unset ( $queries, $sql );
		
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
			'L_VALUE_AFTER' => $raid_value, 
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
	function deleteraid($raid_id)
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
				'event_id' 		=> $row ['event_id'],
				'event_dkpid' 	=> $row ['event_dkpid'], 
				'event_name' 	=> $row ['event_name'], 
				'raid_date' 	=> $row ['raid_date'],  
				'raid_value' 	=> (float) $row ['raid_value'], 
			);
		}
		$db->sql_freeresult ( $result );
	
		if (confirm_box ( true )) 
		{
			// get participating members
			$sql = 'SELECT member_name FROM ' . RAID_ATTENDEES_TABLE . "  
					WHERE raid_id = " . ( int ) $raid_id . "  
					ORDER BY member_name";
			$attendees = array ();
			$result = $db->sql_query ($sql);
			while ( $row = $db->sql_fetchrow ($result)) 
			{
				$attendees [] = $row ['member_name'];
			}
			
			if (count ( $attendees ) == 0) 
			{
				// not possible after 1.1
				$db->sql_query ( 'DELETE FROM ' . RAIDS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
				$db->sql_query ( 'DELETE FROM ' . ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
				//
				// Logging
				//
				$log_action = array (
					'header' => 'L_ACTION_RAID_DELETED', 
					'id' => $raid_id, 
					'L_EVENT' => $this->old_raid ['raid_name'], 
					'L_NOTE' => $this->old_raid ['raid_note'] );
				
				$this->log_insert ( array (
					'log_type' => $log_action ['header'], 
					'log_action' => $log_action ) );
				
				$success_message = $user->lang ['ADMIN_DELETE_RAID_SUCCESS'] . ' ' . 
					$user->lang['WARNING_NOATTENDEES'];
				trigger_error ( $success_message . $this->link, E_USER_WARNING );
			}

			$this->old_raid ['raid_attendees'] = $attendees;
			
			//
			// Take the value away from the attendees
			//
		
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
                SET member_earned = member_earned - ' . $this->old_raid ['raid_value'] . ',
                member_raidcount = member_raidcount - 1
		        WHERE member_dkpid = ' . $this->old_raid['event_dkpid'] . ' 
                AND member_id in (  
                SELECT member_id from ' . MEMBER_LIST_TABLE . ' 
                WHERE ' . $db->sql_in_set ( 'member_name', $attendees ) . ' ) ';
			
			$db->sql_query ( $sql );
			
			//
			// Remove cost of items from this raid from buyers
			//
			$sql = 'SELECT item_id, member_id, item_value FROM ' . ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id;
			$result = $db->sql_query ( $sql );
			
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$item_value = (! empty ( $row ['item_value'] )) ? $row ['item_value'] : '0.00';
				
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
           		SET member_spent = member_spent - ' . $item_value . '
		        WHERE member_dkpid = ' . $this->old_raid['event_dkpid'] . ' 
       	        AND member_id = ' . $row ['member_id'];
				
				$db->sql_query ( $sql );
			
			}
			$db->sql_freeresult ( $result );
			
			//
			// Delete associated items
			//
			$db->sql_query ( 'DELETE FROM ' . ITEMS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
			
			//
			// Delete attendees
			//
			$db->sql_query ( 'DELETE FROM ' . RAID_ATTENDEES_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
			
			//
			// Remove the raid itself
			//
			$db->sql_query ( 'DELETE FROM ' . RAIDS_TABLE . " WHERE raid_id= " . ( int ) $raid_id );
			
			//
			// Update firstraid / lastraid 
			//
			$update_firstraid = array (); // Members who need their firstraid updated
			$update_lastraid = array (); // Members who need their lastraid updated
			$zero_firstlast = array (); // Members who only attended one raid: this one - reset their first/last raid

			$sql = 'SELECT a.member_id, b.member_name, a.member_firstraid, a.member_lastraid, a.member_raidcount
               FROM ' . MEMBER_DKP_TABLE . ' a , ' . MEMBER_LIST_TABLE . ' b 
               WHERE a.member_dkpid = ' . $this->old_raid['event_dkpid'] . ' 
                       AND a.member_id = b.member_id  
               AND a.member_id in ( 
               SELECT member_id from ' . MEMBER_LIST_TABLE . ' 
               WHERE ' . $db->sql_in_set ( 'member_name', $attendees ) . ' ) ';
			$result = $db->sql_query ( $sql );
			
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				// We already updated their raidcount to reflect this deleted raid; 
				// so if it's 0, this was their only raid;
				if ($row ['member_raidcount'] == 0) 
				{
					$zero_firstlast [] = $row ['member_name'];
				} 
				else 
				{
					// If the raid's old date is their firstraid, update their firstraid
					if ($row ['member_firstraid'] == $this->old_raid ['raid_date']) 
					{
						$update_firstraid [] = $row ['member_name'];
					}
					// If the raid's old date is their lastraid, update their lastraid
					if ($row ['member_lastraid'] == $this->old_raid ['raid_date']) 
					{
						$update_lastraid [] = $row ['member_name'];
					}
				}
			}
			
			$db->sql_freeresult ( $result );
			
			unset ( $attendees );
			
			// Zero the first/last raids if this was their only raid
			if (sizeof ( $zero_firstlast ) > 0) 
			{
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
                SET member_firstraid = 0,  member_lastraid = 0
                WHERE member_dkpid = ' . $this->old_raid['event_dkpid'] . ' 
            	AND member_id in ( 
                SELECT member_id from ' . MEMBER_LIST_TABLE . ' 
				 WHERE ' . $db->sql_in_set ( 'member_name', $zero_firstlast ) . '  )';
				
				$db->sql_query ( $sql );
			}
			
			$queries = array ();
			// Update selected firstraids if needed
			if (sizeof ( $update_firstraid ) > 0) 
			{
				$sql = 'SELECT MIN( r.raid_date ) AS member_firstraid, ra.member_name
                    FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra
                    WHERE ra.raid_id = r.raid_id
                    AND " . $db->sql_in_set ( 'ra.member_name', $update_firstraid ) . '  
                    AND r.raid_date > 0
                    GROUP BY ra.member_name';
				
				$result = $db->sql_query ( $sql );
				while ( $row = $db->sql_fetchrow ( $result ) ) 
				{
					$queries [] = 'UPDATE ' . MEMBER_DKP_TABLE . "
                            SET member_firstraid = '" . $row ['member_firstraid'] . "'
                            WHERE member_dkpid = " . $this->old_raid['event_dkpid'] . " 
              			   AND member_id in ( 
              			   SELECT member_id from " . MEMBER_LIST_TABLE . " 
                            WHERE member_name = '" . $row ['member_name'] . "')";
				}
				$db->sql_freeresult ( $result );
			}

			// Updated selected lastraids if needed
			if (sizeof ( $update_lastraid ) > 0) 
			{
				$sql = 'SELECT MAX(r.raid_date) AS member_lastraid, ra.member_name
                    FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . " ra
                    WHERE ra.raid_id = r.raid_id
                    AND " . $db->sql_in_set ( 'ra.member_name', $update_firstraid ) . '
                    AND r.raid_date > 0
                    GROUP BY ra.member_name';
				
				$result = $db->sql_query ( $sql );
				while ( $row = $db->sql_fetchrow ( $result ) ) {
					$queries [] = 'UPDATE ' . MEMBER_DKP_TABLE . "
                           SET member_lastraid = '" . $row ['member_lastraid'] . "'
                           WHERE member_dkpid = " . $this->old_raid['event_dkpid'] . " 
              			   AND member_id in ( 
              			   SELECT member_id from " . MEMBER_LIST_TABLE . " 
                           WHERE member_name = '" . $db->sql_escape ( $row ['member_name'] ) . "')";
				}
				$db->sql_freeresult ( $result );
			}
			foreach ( $queries as $sql ) 
			{
				$db->sql_query ( $sql );
			}
			unset ( $queries, $sql );
			
			//
			// Logging
			//
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
	
	/*
	 * displays a raid
	 */
	function displayraid($raid_id)
	{
		global $db, $user, $config, $template, $phpEx ;
		
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
				'VALUE' 	=> $row ['member_id'], 
				'OPTION' 	=> $row ['member_name'] ) );
		}
		$db->sql_freeresult ( $result );
		
		$raid_value = 0.00;
		$attendeesdisplay = '';
		
		/* getting dkp pulldown */
		if (isset ( $_POST ['dkpsys_id'] ))
		{
			$dkpsys_id = request_var ( 'dkpsys_id', 0 );
		}
		elseif ($raid_id == 0)
		{
			//get from db
			$sql1 = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default 
	                            FROM ' . DKPSYS_TABLE . '
	                            WHERE dkpsys_default = "Y"
	                            ORDER BY dkpsys_name';
			$result1 = $db->sql_query ( $sql1 );
			if ($result1) 
			{
				// get the default dkp value (dkpsys_default = 'Y') from DB
				while ( $row = $db->sql_fetchrow ( $result1 ) ) 
				{
					$dkpsys_id = $row ['dkpsys_id'];
				}
			}
			else 
			{
				// theres no default dkp pool so just take first row 
				$sql1 = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default 
	                         FROM ' . DKPSYS_TABLE;
				$result1 = $db->sql_query_limit ( $sql1, 1, 0 );
				while ( $row = $db->sql_fetchrow ( $result1 ) ) 
				{
					$dkpsys_id = $row ['dkpsys_id'];
				}
			}
			$db->sql_freeresult ( $result1 );
		}
		elseif ($raid_id != 0)
		{
			// get pool from raid 
			$sql1 = 'SELECT a.event_dkpid FROM ' . EVENTS_TABLE . ' a , ' . RAIDS_TABLE . ' b 
				where a.event_id = b.event_id and b.raid_id = '. $raid_id ;
			$result1 = $db->sql_query_limit ( $sql1, 1, 0 );
			while ( $row = $db->sql_fetchrow ( $result1 ) ) 
			{
				$dkpsys_id = $row ['event_dkpid'];
			}
			$db->sql_freeresult ( $result1 );
			
		}

		$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE . ' ORDER BY dkpsys_name';
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$template->assign_block_vars ( 'dkpsys_row', array (
				'VALUE' 	=> $row ['dkpsys_id'], 
				'SELECTED' 	=> ($row ['dkpsys_id'] == $dkpsys_id) ? ' selected="selected"' : '', 
				'OPTION' 	=> (! empty ( $row ['dkpsys_name'] )) ? $row ['dkpsys_name'] : '(None)' ) 
			);
		}
		$db->sql_freeresult ( $result );
		
		/* get max value from event list */ 
		$sql = 'SELECT max(event_value) as event_value FROM ' . EVENTS_TABLE . ' WHERE event_dkpid=' . $dkpsys_id;  
		$result = $db->sql_query($sql);
		$max_value = (float) $db->sql_fetchfield('event_value', 0, $result);
		$db->sql_freeresult ( $result );

		if ($raid_id != 0) 
		{
			/*** prepare to display data for this raid  ***/
			$sql_array = array (
				'SELECT' => ' e.event_id, e.event_name, e.event_value, 
							  r.raid_id, r.raid_date, r.raid_note, 
							  r.raid_value, r.raid_added_by, r.raid_updated_by ', 
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
				$this->raid = array (
					'event_id' 			=> $row ['event_id'], 
					'event_name' 		=> $row ['event_name'], 
					'event_value' 		=> $row ['event_value'],
					'raid_date' 		=> $row ['raid_date'], 
					'raid_note' 		=> $row ['raid_note'], 
					'raid_value' 		=> $row ['raid_value'], 
					'raid_added_by' 	=> $row ['raid_added_by'], 
					'raid_updated_by' 	=> $row ['raid_updated_by'] );
			}
			$db->sql_freeresult ($result);
			
			// display raid attendees
			$attendees = array ();
			$sql_array = array(
	    		'SELECT'    => 'm.member_id ,m.member_name',
		    	'FROM'      => array(
	    		    MEMBER_LIST_TABLE 	  => 'm',
	        		RAID_ATTENDEES_TABLE    => 'r', 
	    			),
	 
	    		'WHERE'     =>  'm.member_id = r.member_id
	    	    				 AND r.raid_id = ' . (int) $raid_id  , 
	    		'ORDER_BY' => 'm.member_name',
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$template->assign_block_vars ( 'raid_attendees_row', array (
					'VALUE' => $row ['member_id'], 
					'OPTION' => $row ['member_name'] ) );
				
				$attendees [] = array (
					'member_id' => $row ['member_id'], 
					'member_name' => $row ['member_id']
				);
			}
			
			$db->sql_freeresult ( $result );
			$this->raid ['raid_attendees'] = $attendees;
			$preset_value = $this->raid ['event_value'];
			$raid_value = $this->raid ['raid_value'];
			$db->sql_freeresult ( $result );
			
			$template->assign_vars ( array (
			  'RAIDTITLE' 			=> sprintf($user->lang['RAIDDESCRIPTION'], $raid_id, $this->raid['event_name'], 
									   $user->format_date($this->raid['raid_date'])), 
			  'RAID_VALUE' 			=> $this->raid['raid_value'], 
			  'RAID_NOTE' 			=> $this->raid['raid_note'], 
			  'RAID_ID' 			=> $raid_id, 
			  'S_ADD' => false ));
	
		}
		else 
		{
			// no raid 
			$template->assign_vars ( array (
			 'RAIDTITLE' => sprintf($user->lang['NEWRAIDDESCRIPTION']), 
		     'RAID_VALUE' 		=> $max_value, 
			 'S_ADD' => true ));
		}
		
		/* event listbox */
		$max_value = 0.00;
		$sql = 'SELECT max(event_value) AS max_value FROM ' . EVENTS_TABLE . ' where event_dkpid = ' . $dkpsys_id; 
		$result = $db->sql_query ($sql);
		$max_value = (float) $db->sql_fetchfield('max_value', 0, $result);
		$float = @explode ( '.', $max_value );
		$format = '%0' . @strlen ( $float [0] ) . '.2f';
		$db->sql_freeresult ( $result );
		
		$sql = ' SELECT  event_id, event_name, event_value 
				 FROM ' . EVENTS_TABLE . 
		    	' WHERE EVENT_DKPID = ' . $dkpsys_id . ' ORDER BY event_name';
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$select_check = false;
			if (isset ( $this->raid )) 
			{
				$select_check = ( $row ['event_id'] == $this->raid ['event_id']) ? true : false;
			} 
			else 
			{
				$select_check = false;
			}
			
			$template->assign_block_vars ( 
				'events_row', array (
					'VALUE' => $row ['event_id'], 
					'SELECTED' => ($select_check) ? ' selected="selected"' : '', 
					'OPTION' => $row ['event_name'] . ' - (' . sprintf ( $format, $row ['event_value'] ) . ')' 
			));
		}
		$db->sql_freeresult ($result);
		
		// build presets for raiddate and hour pulldown
		$now = getdate();
		$s_raid_day_options = '<option value="0">--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$day = isset($this->raid['raid_date']) ? date('j', $this->raid['raid_date']) : $now['mday'] ;
			$selected = ($i == $day ) ? ' selected="selected"' : '';
			$s_raid_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_month_options = '<option value="0">--</option>';
		for ($i = 1; $i < 13; $i++)
		{
			$month = isset($this->raid['raid_date']) ? date('n', $this->raid['raid_date']) : $now['mon'] ;
			$selected = ($i == $month ) ? ' selected="selected"' : '';
			$s_raid_month_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_year_options = '<option value="0">--</option>';
		for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
		{
			$yr = isset($this->raid['raid_date']) ?  date('Y',$this->raid['raid_date']) : $now['year'] ;
			$selected = ($i == $yr ) ? ' selected="selected"' : '';
			$s_raid_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		
		$s_raid_hh_options = '<option value="0"	>--</option>';
		for ($i = 1; $i < 24; $i++)
		{
			$hh = isset($this->raid['raid_date']) ? date('H', $this->raid['raid_date']) : $now['hours'] ;
			$selected = ($i == $hh ) ? ' selected="selected"' : '';
			$s_raid_hh_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_mi_options = '<option value="0">--</option>';
		for ($i = 1; $i < 59; $i++)
		{
			$mi = isset($this->raid['raid_date']) ? date('i', $this->raid['raid_date']) : $now['minutes'] ;
			$selected = ($i == $mi ) ? ' selected="selected"' : '';
			$s_raid_mi_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_raid_s_options = '<option value="0">--</option>';
		for ($i = 1; $i < 59; $i++)
		{
			$s = isset($this->raid['raid_date']) ?  date('s',$this->raid['raid_date']) : $now['seconds'] ;
			$selected = ($i == $s ) ? ' selected="selected"' : '';
			$s_raid_s_options .= "<option value=\"$i\"$selected>$i</option>";
		}
		
		
		add_form_key('acp_dkp_addraid');
		
		$template->assign_vars ( array (
				'L_TITLE' 			=> $user->lang ['ACP_ADDRAID'], 
				'L_EXPLAIN' 		=> $user->lang ['ACP_ADDRAID_EXPLAIN'], 
				'F_ADD_RAID' 		=> append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=addraid" ), 
				'U_ADD_EVENT' 		=> append_sid ( "index.$phpEx", "i=dkp_event&amp;mode=addevent" ), 

				'S_RAIDDATE_DAY_OPTIONS'	=> $s_raid_day_options,
				'S_RAIDDATE_MONTH_OPTIONS'	=> $s_raid_month_options,
				'S_RAIDDATE_YEAR_OPTIONS'	=> $s_raid_year_options,
				'S_RAIDDATE_H_OPTIONS'		=> $s_raid_hh_options,
				'S_RAIDDATE_MI_OPTIONS'		=> $s_raid_mi_options,
				'S_RAIDDATE_S_OPTIONS'		=> $s_raid_s_options,
		
				// Form values
				'RAID_DKPSYSID' 	=> $dkpsys_id, 
		
              	'L_DATE' => $user->lang ['DATE'] . ' dd/mm/yyyy', 
				'L_TIME' => $user->lang ['TIME'] . ' hh:mm:ss', 
		
				// Javascript messages
				'MSG_ATTENDEES_EMPTY' => $user->lang ['FV_REQUIRED_ATTENDEES'], 
				'MSG_NAME_EMPTY' 	  => $user->lang ['FV_REQUIRED_EVENT_NAME'], 
				'MSG_GAME_NAME' 	  => $config ['bbdkp_default_game'], 

				) );
	}
	 
	/*
	 * lists all raids
	 */
	function listraids()
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
			        FROM ' . DKPSYS_TABLE . ' ORDER BY dkpsys_name';
			$result = $db->sql_query ( $sql );

			// get dkp pool value from popup
			$dkpsys_id = request_var ( 'dkpsys_id', 0 );
			// fill popup and set selected to Post value
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$template->assign_block_vars ( 'dkpsys_row', 
					array (
					'VALUE' => $row ['dkpsys_id'], 
					'SELECTED' => ($row ['dkpsys_id'] == $dkpsys_id) ? ' selected="selected"' : '', 
					'OPTION' => (! empty ( $row ['dkpsys_name'] )) ? $row ['dkpsys_name'] : '(None)' ) );
				
			}
			$db->sql_freeresult ( $result );
		
		} 
		else 
		{
			$sql = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default 
			        FROM ' . DKPSYS_TABLE . ' ORDER BY dkpsys_name';
			$result = $db->sql_query ($sql);		
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				if($row ['dkpsys_default'] == "Y"  )
				{
					$dkpsys_id = $row ['dkpsys_id'];
				}
				$template->assign_block_vars ( 'dkpsys_row', 
					array (
					'VALUE' => $row ['dkpsys_id'], 
					'SELECTED' => ($row ['dkpsys_default'] == "Y") ? ' selected="selected"' : '', 
					'OPTION' => (! empty ( $row ['dkpsys_name'] )) ? $row ['dkpsys_name'] : '(None)' ) );
			}
			$db->sql_freeresult ( $result );
		}
		
		/*** end DKPSYS drop-down ***/
		$sort_order = array (
				0 => array ('raid_date desc', 'raid_date' ), 
				1 => array ('raid_name', 'raid_name desc' ), 
				2 => array ('raid_note', 'raid_note desc' ), 
				3 => array ('raid_value desc', 'raid_value' ));
		
		$current_order = switch_order ( $sort_order );
		
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
		
		$sql_array = array (
			'SELECT' => ' e.event_dkpid, e.event_name,  
						  r.raid_id, r.raid_date, r.raid_note, 
						  r.raid_value, r.raid_added_by, r.raid_updated_by ', 
			'FROM' => array (
				RAIDS_TABLE 		=> 'r' , 
				EVENTS_TABLE 		=> 'e',		
				), 
			'WHERE' => "  r.event_id = e.event_id and e.event_dkpid = " . ( int ) $dkpsys_id,
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
				'DATE' => (! empty ( $row ['raid_date'] )) ? 
						date ( $config ['bbdkp_date_format'], $row ['raid_date'] ) : '&nbsp;', 
				'U_VIEW_RAID' => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=addraid&amp;" . URI_RAID . "={$row['raid_id']}" ), 
				'NAME' => $row ['event_name'], 
				'NOTE' => (! empty ( $row ['raid_note'] )) ? $row ['raid_note'] : '&nbsp;', 
				'VALUE' => $row ['raid_value'] ) );
		}
		
		$template->assign_vars ( array (
			'L_TITLE' 			  => $user->lang ['ACP_LISTRAIDS'], 
			'L_EXPLAIN' 		  => $user->lang ['ACP_LISTRAIDS_EXPLAIN'], 
			'O_DATE' 			  => $current_order ['uri'] [0], 
			'O_NAME' 			  => $current_order ['uri'] [1], 
			'O_NOTE' 			  => $current_order ['uri'] [2], 
			'O_VALUE' 			  => $current_order ['uri'] [3], 
			'U_LIST_RAIDS' 		  => append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=listraids" ), 
			'START' 			  => $start, 
			'LISTRAIDS_FOOTCOUNT' => sprintf ( $user->lang ['LISTRAIDS_FOOTCOUNT'], $total_raids, $config ['bbdkp_user_rlimit'] ), 
			'RAID_PAGINATION' 	  => generate_pagination ( append_sid 
					( "index.$phpEx", "i=dkp_raid&amp;mode=listraids&amp;o=" . $current_order ['uri'] ['current']) , 
					$total_raids, $config ['bbdkp_user_rlimit'], $start ) ) );
		
			
				
	}
	
    /**
    * RAID_ATTENDEES_TABLE handler : Insert members into raid attendees table
    * @param $members_array Array of members
    * @param $raid_id
    */
    function add_attendees(&$members_array, $raid_id)
    {
        global $db;
        $attendees = array();
        foreach ( $members_array as $member_name )
        {
            $sql = "SELECT member_id FROM " . MEMBER_LIST_TABLE . " WHERE member_name = '" . $db->sql_escape($member_name) . "'";
            $result = $db->sql_query($sql);
            while ( $row = $db->sql_fetchrow($result) )
            {
                $member_id = $row['member_id'];
            }
            $db->sql_freeresult($result);
            $attendees[] = array(
                'raid_id'      => (int) $raid_id,
                'member_id'   => (int) $member_id,
				);
        }
        $db->sql_multi_insert(RAID_ATTENDEES_TABLE, $attendees);
       
        return $attendees;
    }
   
   
	/**
    * MEMBER_DKP_TABLE table handler : Update existing members / add new members
    *
    * @param $members_array
    * @param $raid_value
    * @param $time_check
    */
    function handle_memberdkp($members_array, $raid_value, $dkpid)
    {
        global $db, $user;
        // we loop new raidmember array
        foreach ( (array) $members_array['member_id'] as $member_id )
        {
            // has dkp record ?
			$sql = 'SELECT count(member_id) as present
				FROM ' . MEMBER_DKP_TABLE . '  
				WHERE member_id = ' . $member_id . ' 
				AND member_dkpid = ' . $dkpid;
			      
             $result = $db->sql_query($sql);
             $present = (int) $db->sql_fetchfield('present', false, $result);
             $db->sql_freeresult($result);
             if ($present == 1)
             {
                 //update
                  $sql = 'SELECT m.member_lastraid
                  FROM ' . MEMBER_DKP_TABLE . " 
                  WHERE member_id = ' . $member_id . ' 
                  AND  member_dkpid = " . $dkpid;  
                  $result = $db->sql_query($sql);

                  while ($row = $db->sql_fetchrow($result) )  
                  {
                     $sql  = 'UPDATE ' . MEMBER_DKP_TABLE . ' m
                     SET m.member_earned = m.member_earned + ' . $raid_value . ', ';
                     
                     // Do not update their lastraid if it's greater than this raid's date
                     if ( $row['member_lastraid'] < $this->time )
                     {
                        $sql .= 'm.member_lastraid = ' . $this->time . ', ';
                     }
                     $sql .= ' m.member_raidcount = m.member_raidcount + 1
                     WHERE m.member_dkpid = ' . (int) $dkpid . '
                     AND m.member_id = ' . (int) $row['member_id'];
                     $db->sql_query($sql);
                   }
                   $db->sql_freeresult($result);
             }
             elseif ($present == 0)
             {
                // insert new dkp record
                $query = $db->sql_build_array('INSERT', array(
                    'member_dkpid'       => $dkpid,
                    'member_id'          => $member_id,
                    'member_earned'      => $raid_value,
                    'member_status'      => 1,
                    'member_firstraid'   => $this->time,
                    'member_lastraid'    => $this->time,
                    'member_raidcount'   => 1
                    )
                );
                $db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
               
             }
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
	function update_player_status($dkpid)
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
				if (((float) $config ['bbdkp_inactive_point_adj'] != 0.00) && ($row ['member_status'] == 1) && ($row ['member_lastraid'] < $inactive_time))
				{
					$adj_value = $config ['bbdkp_inactive_point_adj'];
					$adj_reason = 'Inactive adjustment';
					$inactive_members [] = $row ['member_id'];
					$inactive_membernames [] = $row ['member_name'];
				} // Inactive -> Active
				elseif (( (float) $config ['bbdkp_active_point_adj'] != 0.00) && ($row ['member_status'] == 0) && ($row ['member_lastraid'] >= $inactive_time))
				{
					$adj_value = $config ['bbdkp_active_point_adj'];
					$adj_reason = 'Active adjustment';
					$active_members [] = $row ['member_id'];
					$active_membernames [] = $row ['member_name'];
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

	
	
	

}

?>
