<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 */
namespace bbdkp;
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

if (!class_exists('\bbdkp\Events'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Events.$phpEx");
}

if (!class_exists('\bbdkp\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}

if (!class_exists('\bbdkp\Raids'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Raids.$phpEx");
}
if (!class_exists('\bbdkp\Raiddetail'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Raiddetail.$phpEx");
}
if (!class_exists('\bbdkp\Loot'))
{
	require("{$phpbb_root_path}includes/bbdkp/Loot/Loot.$phpEx");
}
/**
 * Raid controller class
 * 
 * @package 	bbDKP
 */
class RaidController  extends \bbdkp\Admin
{
	/*
	|--------------------------------------------------------------------------
	| Raid Controller
	|--------------------------------------------------------------------------
	| here are all the routines controlling the raid workflow
	|
	*/
	
	public $dkpid; 
	public $dkpsys;
	public $guildid;
	public $eventinfo;
	public $game_id;
	public $memberlist; 
	public $totalraidcount;
	
	public $raidlist;
	public $raidlistorder; 
	
	public $raid; 
	public $raiddetail;
	public $raiddetailorder;
	
	public $lootlist;
	public $lootlistorder;
	public $nonattendees;
	
	public function __construct($dkpid = 0) 
	{
		parent::__construct();
		
		global $db, $user; 
		$this->dkpid = $dkpid;
		
		// get dkp pools
		$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default
            FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . " b
			WHERE a.dkpsys_id = b.event_dkpid AND b.event_status = 1 
            AND a.dkpsys_status = 'Y'";
		$result = $db->sql_query($sql);
		$this->dkpsys = array(); 
		while ($row = $db->sql_fetchrow($result) )
		{
			$this->dkpsys[$row['dkpsys_id']] = array(
					'id' => $row['dkpsys_id'],
					'name' => $row['dkpsys_name'],
					'default' => $row['dkpsys_default']);
		}
		$db->sql_freeresult($result);

	}
	
	public function init_newraid()
	{
		global $user;
		$events = new \bbdkp\Events();
		$events->countevents($this->dkpid);
		if($events->total_events == 0)
		{
			trigger_error ( $user->lang['ERROR_NOEVENTSDEFINED'], E_USER_WARNING );
		}
		$events->listevents(0, 'event_name', $this->dkpid);
		$this->eventinfo = $events->events;  
		
		$members = new \bbdkp\Members();
		$members->listallmembers($this->game_id, $this->guildid);
		$this->memberlist = $members->gamememberlist;
		if(count($this->memberlist) == 0)
		{
			trigger_error ( $user->lang['ERROR_NOGUILDMEMBERSDEFINED'], E_USER_WARNING );
		}
		
	}
	
	/**
	 * fetch the raid and pass it t the view
	 * @param unknown_type $raid_id
	 * @return \bbdkp\Raids
	 */
	public function displayraid($raid_id)
	{
		$this->raid = new \bbdkp\Raids($raid_id);
		
		$events = new \bbdkp\Events();
		$events->countevents($this->raid->event_dkpid);
		$events->listevents(0, 'event_name', $this->raid->event_dkpid);
		$this->eventinfo = $events->events;
		
		$sort_order = array (
				0 => array ('member_name desc', 'member_name desc' ),
				1 => array ('raid_value', 'raid_value desc' ),
				2 => array ('time_bonus', 'time_bonus desc' ),
				3 => array ('zerosum_bonus', 'zerosum_bonus desc' ),
				4 => array ('raid_decay', 'raid_decay desc' ),
				5 => array ('total desc', 'total desc' ),
		);
		
		$this->raiddetailorder = $this->switch_order ( $sort_order );
		
		$raiddetail = new \bbdkp\Raiddetail($this->raid->raid_id);
		$raiddetail->Get($this->raid->raid_id, 0, $this->raiddetailorder['sql']);
		$this->raiddetail = $raiddetail->raid_details; 
		
		$raiddetail->GetNonAttendees(); 
		$this->nonattendees = $raiddetail->nonattendees;   
		
		// loot detail
		$isort_order = array (
				0 => array ('m.member_name', 'm.member_name desc' ),
				1 => array ('i.item_name', 'item_name desc' ),
				2 => array ('i.item_value ', 'item_value desc' ),
		);
		$this->lootlistorder = $this->switch_order ($isort_order, 'ui');
		$lootlist = new \bbdkp\Loot($this->raid->raid_id); 
		$this->lootlist = $lootlist->get($this->raid->raid_id, $this->lootlistorder['sql']);
		
		
	}

	/**
	 * add a raid
	 * @param array $raidinfo
		'raid_note' 		=> utf8_normalize_nfc (request_var ( 'hidden_raid_note', ' ', true )),
		'raid_event'		=> utf8_normalize_nfc (request_var ( 'hidden_raid_name', ' ', true )),
		'raid_value' 		=> request_var ('hidden_raid_value', 0.00 ),
		'raid_timebonus'	=> request_var ('hidden_raid_timebonus', 0.00 ),
		'raid_start' 		=> request_var ('hidden_startraid_date', 0),
		'raid_end'			=> request_var ('hidden_endraid_date', 0),
		'event_id' 			=> request_var ('hidden_event_id', 0),
		'raid_attendees' 	=> request_var ('hidden_raid_attendees', array ( 0 => 0 )),
	 */
	public function add_raid(array $raidinfo)
	{
		global $user, $config; 
		
		$raid = new \bbdkp\Raids(); 
		$raid->event_id	  = $raidinfo['event_id'];
		 
		$event = new \bbdkp\Events($raid->event_id); 
		$raid->event_dkpid = $event->dkpsys_id;
		$raid->event_name  = $event->event_name; 
		$raid->dkpsys_name = $event->dkpsys_name;
		
		if($raidinfo['raid_value'] == 0.00)
		{
			$raid->event_value = $event->event_value;
		}
		$raid->raid_start 		= $raidinfo['raid_start'];
		$raid->raid_end 		= $raidinfo['raid_end'];
		$raid->raid_note 		= $raidinfo['raid_note'];
		$raid->raid_added_by 	= (string) $user->data['username'];  
		$raid->raid_updated_by 	= '';
		
		// Attendee handling
		if(sizeof($raidinfo['raid_attendees']) == 0)
		{
			return;
		}

		$raid->Create(); 
		
		$raiddetail = new \bbdkp\Raiddetail($raid->raid_id);
		foreach ( $raidinfo['raid_attendees'] as $member_id )
		{
			$raiddetail->member_id = (int) $member_id; 
			$raiddetail->raid_value = (float) $raidinfo['raid_value'];
			$raiddetail->time_bonus = (float) $raidinfo['raid_timebonus'];
			$raiddetail->create(); 
		}
		
		$log_action = array (
				'header' => 'L_ACTION_RAID_ADDED',
				'L_RAID_ID'		=> $raid->raid_id,
				'L_EVENT' 		=> $raid->event_name,
				'L_ATTENDEES' 	=> implode ( ', ', $raidinfo['raid_attendees'] ),
				'L_NOTE' 		=> $raid->raid_note,
				'L_VALUE' 		=> $raid->event_value,
				'L_ADDED_BY' 	=> $user->data ['username']);
		
		$this->log_insert (array(
			'log_type' 		=> $log_action ['header'],
			'log_action' 	=> $log_action ));
		
		return  $raid->raid_id; 
	}
	

	/**
	 * duplicates a passed raid x
	 * 
	 * @todo attached loot.
	 * @param int $old_raid_id
	 */
	public function duplicateraid($old_raid_id)
	{
		global $db, $user, $config, $phpbb_admin_path, $phpEx;
		
		$new_raid = new \bbdkp\Raids($old_raid_id);
		$new_raid_id  = $new_raid->Create(); 
		
		$newraiddetail = new \bbdkp\Raiddetail($old_raid_id);
		$rdarray = $newraiddetail->Get($old_raid_id);

		$newraiddetail->raid_id = $new_raid_id; 
		foreach ( $rdarray as $member_id => $detail)
		{
			$newraiddetail->member_id = (int) $member_id;
			$newraiddetail->raid_value = (float) $detail['raid_value'];
			$newraiddetail->time_bonus = (float) $detail['time_bonus'];
			$newraiddetail->zerosum_bonus = (float) $detail['zerosum_bonus'];
			$newraiddetail->raid_decay = (float) $detail['raid_decay'];
			$newraiddetail->create();
		}
		
		return $new_raid->raid_id; 
	
	}
	
	
	
	public function update_raid(array $raidinfo)
	{
		global $user, $config;
		$old_raid = new \bbdkp\Raids($raidinfo['raid_id']);
		$new_raid = new \bbdkp\Raids($raidinfo['raid_id']);
		
		$new_raid->event_id = $raidinfo['event_id']; 
		$new_raid->raid_start = $raidinfo['raid_start'];
		$new_raid->raid_end = $raidinfo['raid_end'];
		$new_raid->raid_note = $raidinfo['raid_note'];
		
		$new_raid->update(); 
		
		// update raid_value & raid_bonus for attendees 
		$raiddetail = new \bbdkp\Raiddetail($raidinfo['raid_id']);
		$raiddetail->Get($raidinfo['raid_id']); 
		
		foreach ($raiddetail->raid_details as $member_id => $attendee)
		{
			$raiddetail->member_id = (int) $member_id;
			$raiddetail->raid_value = (float) $attendee['raid_value'];
			$raiddetail->time_bonus = (float) $attendee['time_bonus'];
			$raiddetail->zerosum_bonus = (float) $attendee['zerosum_bonus'];
			$raiddetail->update();
			 
		}
		
		$log_action = array (
				'header' => 'L_ACTION_RAID_UPDATED',
				'id' 			=> $raidinfo['raid_id'],
				'L_EVENT_BEFORE' => $old_raid->event_id,
				'L_EVENT_AFTER'  => $new_raid->event_id,
				'L_UPDATED_BY' 	 => $user->data['username'] );
		
		$this->log_insert ( array (
				'log_type' => $log_action ['header'],
				'log_action' => $log_action ));
	}
	
	
	
	/**
	 * delete raid detail, delete raid 
	 * @param int $raid_id
	 */
	public function delete_raid($raid_id)
	{
		global $db, $config, $user, $phpEx, $phpbb_root_path;
		$old_raid = new \bbdkp\Raids($raid_id);
		$old_raid->Get(); 
		$old_raid->Delete();
		
		$raiddetail = new \bbdkp\Raiddetail($raid_id);
		$raiddetail->Get($raid_id);
		
		$raiddetail->deleteRaid($raid_id); 
		
		// Logging
		$log_action = array (
				'header' =>
				'L_ACTION_RAID_DELETED',
				'L_RAID_ID'  => $old_raid->raid_id,
				'L_EVENT' 	 => $old_raid->event_id,
		);
		$this->log_insert ( array (
				'log_type' => $log_action ['header'],
				'log_action' => $log_action ));
		
	}
	
	/**
	 * adds 1 attendee to a raid
	 * @param unknown_type $raid_id
	 * @param unknown_type $raid_value
	 * @param unknown_type $time_bonus
	 * @param unknown_type $dkpid
	 * @param unknown_type $member_id
	 * @param unknown_type $raid_start
	 * @return boolean
	 */
	public function addraider($raid_id, $raid_value, $time_bonus, $dkpid, $member_id, $raid_start)
	{
		$raiddetail = new \bbdkp\Raiddetail($raid_id);
		$raiddetail->raid_value = $raid_value; 
		$raiddetail->time_bonus = $time_bonus;
		$raiddetail->dkpid = $dkpid;
		$raiddetail->member_id = $member_id;
		$raiddetail->create(); 
		
		$this->add_dkp ($raid_value, $time_bonus, $raid_start, $dkpid, $member_id);
			
		return true;
		
	}
	
	public function deleteraider($raid_id, $member_id)
	{
		$raid = new \bbdkp\Raids($raid_id);
		$raiddetail = new \bbdkp\Raiddetail($raid_id);
		$raiddetail->Get($raid_id, $member_id); 
		$raiddetail->delete(); 
		
		// update last & firstdates
		$this->update_raiddate($member_id, $dkpid);
		//$this->remove_dkp($member_id, $oraid_value, $otime_bonus, $zerosum, $dkpid, $decay);
		
	}
	
	
	/**
	 * get list of raids in a dkp pool
	 * 
	 * @param int $dkpsys_id
	 * @param int $start
	 * @param char $order
	 */
	public function listraids($dkpsys_id, $start = 0)
	{
		
		global $user, $config, $db, $phpEx;
		
		$sort_order = array (
				0 => array ('r.raid_id desc', 'raid_id' ),
				1 => array ('r.raid_end desc', 'raid_end' ),
				2 => array ('e.event_name', 'event_name desc' ),
				3 => array ('r.raid_note', 'raid_note desc' ),
				4 => array ('sum(ra.raid_value) desc', 'sum(ra.raid_value)' ),
				5 => array ('sum(ra.time_value) desc', 'sum(ra.time_value)' ),
				6 => array ('sum(ra.zs_value) desc', 'sum(ra.zs_value)' ),
				7 => array ('sum(ra.raiddecay) desc', 'sum(ra.raiddecay)' ),
				8 => array ('sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay) desc', 'sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay)' ),
		);
		
		$this->raidlistorder = $this->switch_order ( $sort_order ); 
		
		$raids = new \bbdkp\Raids();
		$raids_result = $raids->getRaids($this->raidlistorder['sql'], $dkpsys_id, $start); 	
		$this->totalraidcount = $raids->countraids($dkpsys_id); 
		
		$this->raidlist = array(); 
		while ( $row = $db->sql_fetchrow ($raids_result) )
		{
			$this->raidlist[$row['raid_id']] = array(
				'raid_id' => $row['raid_id'], 
				'date' => (! empty ( $row['raid_start'] )) ? date ( $config ['bbdkp_date_format'], $row['raid_start'] ) : '&nbsp;',
				'name' => $row['event_name'],
				'note' => (! empty ( $row['raid_note'] )) ? $row['raid_note'] : '&nbsp;',
				'raidvalue'  => $row['raid_value'],
				'timevalue'  => $row['time_value'],
				'zsvalue' 	 => $row['zs_value'],
				'decayvalue' => $row['raiddecay'],
				'total' 	 => $row['total'],
				'viewlink'   => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=editraid&amp;" . URI_RAID . "={$row['raid_id']}" ),
				'copylink'   => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=listraids&amp;action=duplicate&amp;" . URI_RAID . "={$row['raid_id']}" ),
				'deletelink' => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=listraids&amp;action=delete&amp;" . URI_RAID . "={$row['raid_id']}" ),
			);
			
		}
		$db->sql_freeresult ($raids_result);
		
	}
	
	
	/**
	 * function to decay one specific raid
	 * calling this function multiple time will not lead to cumulative decays, just the delta is applied.
	 *
	 * @param int $raid_id the raid id to decay
	 * @param int $dkpid dkpid for adapting accounts
	 */
	private function decayraid($raid_id, $dkpid)
	{
		global $config, $db;
		//loop raid detail, pass earned and timediff to decay function, update raid detail
	
		//get old raidinfo
		$sql_array = array (
				'SELECT' => ' r.raid_start, ra.member_id, (ra.raid_value + ra.time_bonus + ra.zerosum_bonus) as earned, ra.raid_decay ',
				'FROM' => array (
						RAIDS_TABLE 		=> 'r' ,
						RAID_DETAIL_TABLE	=> 'ra' ,
				),
				'WHERE' => " r.raid_id = ra.raid_id and r.raid_id=" . ( int ) $raid_id,
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		$raidstart = 0;
		$raid = array();
		while ( ($row = $db->sql_fetchrow ( $result )) )
		{
			$raidstart =  $row['raid_start'];
			$raid[$row['member_id']] = array (
					'member_id' 	=> $row['member_id'],
					'earned' 		=> $row['earned'],
					'raid_decay' 	=> $row['raid_decay'],
			);
		}
		$db->sql_freeresult ($result);
	
		//get timediff
		$now = getdate();
		$timediff = mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) - $raidstart  ;
	
		// loop raid detail
		foreach($raid as $member_id => $raiddetail)
		{
			// get new decay : may be different per player due to it being calculated on earned
			$decay = $this->decay($raiddetail['earned'], $timediff, 1);
				
			// update raid detail to new decay value
			$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET raid_decay = ' . $decay[0] . ', decay_time = ' . $decay[1] . ' WHERE raid_id = ' . ( int ) $raid_id . '
			and member_id = ' . $raiddetail['member_id'] ;
			$db->sql_query ( $sql );
				
			// update dkp account, deduct old, add new decay
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_raid_decay = member_raid_decay - ' . $raiddetail['raid_decay'] . ' + ' . $decay[0] . "
				WHERE member_id = " . ( int ) $member_id . '
				and member_dkpid = ' . $dkpid ;
			$db->sql_query ( $sql );
		}
	
		//now loop raid items detail
		$sql = 'SELECT i.item_id, i.member_id, i.item_value, i.item_decay FROM ' . RAID_ITEMS_TABLE . ' i where i.raid_id = ' .  $raid_id;
		$result = $db->sql_query ($sql);
		$items= array();
		while ( ($row = $db->sql_fetchrow ( $result )) )
		{
			$items[$row['item_id']] = array (
					'member_id'		=> $row['member_id'],
					'item_value' 	=> $row['item_value'],
					'item_decay' 	=> $row['item_decay'],
			);
		}
		$db->sql_freeresult ($result);
	
		foreach($items as $item_id => $item)
		{
			// get new itemdecay
			$itemdecay = $this->decay($item['item_value'], $timediff, 2);
				
			//  update item detail to new decay value
			$sql = 'UPDATE ' . RAID_ITEMS_TABLE . ' SET item_decay = ' . $itemdecay[0] . ', decay_time = ' . $itemdecay[1] . ' WHERE item_id = ' . $item_id;
			$db->sql_query ( $sql);
				
			// update dkp account, deduct old, add new decay
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_item_decay = member_item_decay - ' . $item['item_decay'] . ' + ' . $itemdecay[0] . "
				WHERE member_id = " . ( int ) $item['member_id'] . ' and member_dkpid = ' . $dkpid ;
			$db->sql_query ( $sql );
		}
	
		return true;
	
	
	}
	
	
	
	

}

?>