<?php
/**
 * Raid controller file
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\model\raids;
use sajaki\bbdkp\model\admin\Admin;
use sajaki\bbdkp\model\player\Members;
use sajaki\bbdkp\model\raids\Events;
use sajaki\bbdkp\model\raids\Raids;
use sajaki\bbdkp\model\raids\Raiddetail;
use sajaki\bbdkp\model\loot\Loot;

/**
 * Raid controller class : the routines controlling the raid workflow
 * @package sajaki\bbdkp\model\raids
 */
class RaidController  extends Admin
{
	/**
	 * dkp pool to which this raid belongs
	 * @var integer
	 */
	public $dkpid;
	/**
	 * array of dkp pool
	 * @var array
	 */
	public $dkpsys;
	/**
	 * guild id
	 * @var integer
	 */
	public $guildid;
	/**
	 * array with events
	 * @var array
	 */
	public $eventinfo;
	/**
	 * game id for this raid
	 * @var string
	 */
	public $game_id;
	/**
	 * Guild memberlist
	 * @var array
	 */
	public $memberlist;
	/**
	 * raid count
	 * @var integer
	 */
	public $totalraidcount;
	/**
	 * list of raids in a dkp pool
	 * @var array
	 */
	public $raidlist;
	/**
	 * order of raids in raid list
	 * @var array
	 */
	public $raidlistorder;

    /**
	 * instance of Raid class
     * @var
     */
	public $raid;

	/**
	 * instance of Raid detail class
     * $var Raiddetail
	 */
	public $raiddetail;

	/**
	 * order of Raid detail array
	 * @var Raiddetail
	 */
	public $raiddetailorder;
	/**
	 * raid loot array
	 * @var array
	 */
	public $lootlist;
	/**
	 * order of raid loot array
	 * @var array
	 */
	public $lootlistorder;
	/**
	 * array of benched
	 * @var array
	 */
	public $nonattendees;

    /**
     * Raidcontroller constructor
     * @param int|number $dkpid
     */
	public function __construct($dkpid = 0)
	{
		parent::__construct();

		global $db;
		$this->dkpid = $dkpid;

		// get dkp pools
		$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default
            FROM ' . DKPSYS_TABLE . ' a , ' . BBEVENTS_TABLE . " b
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

	/**
	 * prepares objects to create new raid
	 */
	public function init_newraid()
	{
		global $user;
		$events = new Events();
		$events->countevents($this->dkpid);
		if($events->total_events == 0)
		{
			trigger_error ( $user->lang['ERROR_NOEVENTSDEFINED'], E_USER_WARNING );
		}
		$events->listevents(0, 'event_name', $this->dkpid, 1, false);
		$this->eventinfo = $events->events;

		$members = new Members();
		$members->listallmembers($this->guildid);
		$this->memberlist = $members->guildmemberlist;
		if(count($this->memberlist) == 0)
		{
			trigger_error ( $user->lang['ERROR_NOGUILDMEMBERSDEFINED'], E_USER_WARNING );
		}

	}

    /**
	 * fetch the raid and pass it to the view
     * @param $raid_id
     */
	public function displayraid($raid_id)
	{
		global $db;
		$this->raid = new Raids($raid_id);

		$events = new Events();
		$events->countevents($this->raid->event_dkpid);
		$events->listevents(0, 'event_name', $this->raid->event_dkpid, 1, false);
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

		$raiddetail = new Raiddetail($this->raid->raid_id);
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
		$lootlist = new Loot();
		$this->lootlist = $db->sql_fetchrowset($lootlist->GetAllLoot($this->lootlistorder['sql'], 0, 0, $this->raid->raid_id));

	}

    /**
     * Add a raid
     *
     *  raidinfo array
     *     raid_note    string
     *     raid_note     string
     *     event_id    int
     *     raid_start    int
     *     raid_end    int
     *  raiddetail array
     *   member_id        int
     *     raid_value    float
     *     raid_timebonus    float
     *
     * @param array $raidinfo
     * @param array $raiddetails
     * @return int|null|number
     */
	public function add_raid($raidinfo, $raiddetails)
	{
		global $user;
		$i = 1;
		$raidvalue_avg = 0.0;
		if(sizeof($raidinfo) == 0)
		{
			return null;
		}

		if(sizeof($raiddetails) == 0)
		{
			return null;
		}

		//raid handling
		$raid = new Raids();
		$raid->event_id	  		= $raidinfo['event_id'];
		$event = new Events($raid->event_id);
		$raid->event_dkpid 		= $event->dkpsys_id;
		$raid->event_name  		= $event->event_name;
		$raid->dkpsys_name 		= $event->dkpsys_name;
		$raid->raid_start 		= $raidinfo['raid_start'];
		$raid->raid_end 		= $raidinfo['raid_end'];
		$raid->raid_note 		= $raidinfo['raid_note'];
		$raid->raid_added_by 	= (string) $user->data['username'];
		$raid->raid_updated_by 	= '';
		$raid->Create();

		// Attendee handling
		$raiddetail = new Raiddetail($raid->raid_id);
		$named_attendees = '';
		foreach ( $raiddetails as $key => $attendee )
		{
			$member = new Members($attendee['member_id']);
			$named_attendees .= $member->member_name;
			$raiddetail->member_id = (int) $attendee['member_id'];
			if($attendee['raid_value'] == 0.00)
			{
				$raiddetail->raid_value = $event->event_value;
			}
			else
			{
				$raiddetail->raid_value = (float) $attendee['raid_value'];
			}
			$raiddetail->time_bonus = (float) $attendee['time_bonus'];
			$raiddetail->zerosum_bonus = (float) 0.0; // zero sum is not allocated from raid creation but by loot distribution
			$raiddetail->raid_decay = (float) 0.0; // raid is not decayed from raid creation but by post process
			$raiddetail->create();
			$raidvalue_avg += $raiddetail->raid_value +$raiddetail->time_bonus;
			$i+=1;
		}
		unset ($member);
		$raidvalue_avg = $raidvalue_avg / $i;

		$log_action = array (
				'header' => 'L_ACTION_RAID_ADDED',
				'L_RAID_ID'		=> $raid->raid_id,
				'L_EVENT' 		=> $raid->event_name,
				'L_ATTENDEES' 	=> $named_attendees,
				'L_NOTE' 		=> $raid->raid_note,
				'L_VALUE' 		=> $raidvalue_avg,
				'L_ADDED_BY' 	=> $user->data ['username']);

		$this->log_insert (array(
			'log_type' 		=> $log_action ['header'],
			'log_action' 	=> $log_action ));

		return  $raid->raid_id;
	}


    /**
     * duplicates a passed raid x
     * @todo attached loot.
     *
     * @param int $old_raid_id
     * @return int|number
     */
	public function duplicateraid($old_raid_id)
	{

		$new_raid = new Raids($old_raid_id);
		$new_raid_id  = $new_raid->Create();

		$newraiddetail = new Raiddetail($old_raid_id);
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


	/**
	 * update raid
	 * @param array $raidinfo
	 */
	public function update_raid(array $raidinfo)
	{
		global $user;
		$old_raid = new Raids($raidinfo['raid_id']);
		$new_raid = new Raids($raidinfo['raid_id']);

		$new_raid->event_id = $raidinfo['event_id'];
		$new_raid->raid_start = $raidinfo['raid_start'];
		$new_raid->raid_end = $raidinfo['raid_end'];
		$new_raid->raid_note = $raidinfo['raid_note'];

		$new_raid->update();

		// update raid_value & raid_bonus for attendees
		$raiddetail = new Raiddetail($raidinfo['raid_id']);
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
		$old_raid = new Raids($raid_id);
		$old_raid->Get();
		$old_raid->Delete();

		$raiddetail = new Raiddetail($raid_id);
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
	 *
	 * @param int $raid_id
	 * @param float $raid_value
	 * @param float $time_bonus
	 * @param int $dkpid
	 * @param int $member_id
	 * @param int $raid_start
	 * @return boolean
	 */
	public function addraider($raid_id, $raid_value, $time_bonus, $dkpid, $member_id, $raid_start)
	{
		$raiddetail = new Raiddetail($raid_id);
		$raiddetail->raid_value = $raid_value;
		$raiddetail->time_bonus = $time_bonus;
		$raiddetail->dkpid = $dkpid;
		$raiddetail->member_id = $member_id;
		$raiddetail->create();
		return true;

	}

    /**
     * delete 1 raider from raid
     * @param int $raid_id
     * @param int $member_id
     * @return bool
     */
	public function deleteraider($raid_id, $member_id)
	{
		$raiddetail = new Raiddetail($raid_id);
		$raiddetail->Get($raid_id, $member_id);
		$raiddetail->delete();
		return true;
	}


	/**
	 * get list of raids in a dkp pool, return raidcount
	 *
	 * @param int $dkpsys_id
	 * @param int $start
	 * @param int $member_id
	 * @param int $guild_id
	 * @return number
	 */
	public function listraids($dkpsys_id=0, $start = 0, $member_id=0, $guild_id=0)
	{

		global $config, $db, $phpEx;

		$sort_order = array (
				0 => array ('r.raid_id desc', 'raid_id' ),
				1 => array ('r.raid_end desc', 'raid_end' ),
				2 => array ('e.event_name', 'event_name desc' ),
				3 => array ('r.raid_note', 'raid_note desc' ),
				4 => array ('sum(ra.raid_value) desc', 'sum(ra.raid_value)' ),
				5 => array ('sum(ra.time_value) desc', 'sum(ra.time_value)' ),
				6 => array ('sum(ra.zs_value) desc', 'sum(ra.zs_value)' ),
				7 => array ('sum(ra.raiddecay) desc', 'sum(ra.raiddecay)' ),
				8 => array ('sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay) desc',
						'sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay)' ),
		);

		$this->raidlistorder = $this->switch_order ( $sort_order );

		$raids = new Raids();
		$raids_result = $raids->getRaids($this->raidlistorder['sql'], $dkpsys_id, 0, $start, $member_id, $this->guildid);
		if($member_id>0)
		{
            //@todo fix
			$this->totalraidcount = $raids->raidcount($dkpsys_id, $days, $member_id, 0, true);
		}
		else
		{
			$this->totalraidcount = $raids->countraids($dkpsys_id, $member_id);
		}

		$this->raidlist = array();
		while ( $row = $db->sql_fetchrow ($raids_result) )
		{
			$this->raidlist[$row['raid_id']] = array(
				'raid_id' => $row['raid_id'],
				'date' => (! empty ( $row['raid_start'] )) ? date ( $config ['bbdkp_date_format'], $row['raid_start'] ) : '&nbsp;',
				'event_name' => $row['event_name'],
				'event_color' => $row['event_color'],
				'event_imagename' => $row['event_imagename'],
				'raid_start'	=> $row['raid_start'],
				'raid_note'		=> $row['raid_note'],
				'note' => (! empty ( $row['raid_note'] )) ? $row['raid_note'] : '&nbsp;',
				'raidvalue'  => $row['raid_value'],
				'timevalue'  => $row['time_value'],
				'zsvalue' 	 => $row['zs_value'],
				'decayvalue' => $row['raiddecay'],
				'total' 	 => $row['net_earned'],
				'viewlink'   => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=editraid&amp;" . URI_RAID . "={$row['raid_id']}" ),
				'copylink'   => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=listraids&amp;action=duplicate&amp;" . URI_RAID . "={$row['raid_id']}" ),
				'deletelink' => append_sid ( "index.$phpEx?i=dkp_raid&amp;mode=listraids&amp;action=delete&amp;" . URI_RAID . "={$row['raid_id']}" ),
			);

		}
		$db->sql_freeresult ($raids_result);

		return $this->totalraidcount;
	}


	/**
	 * return Raid count for a guild & dkp pool
	 * @param int $dkpsys_id
	 * @param int $guild_id
	 * @return number
	 */
	public function guildraidcount($dkpsys_id, $guild_id)
	{
		$raids = new Raids();
		$this->totalraidcount = $raids->countraids2($dkpsys_id, $guild_id);
		return $this->totalraidcount;
	}








}

