<?php
namespace bbdkp;
/**

 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 *
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

//include the abstract base interface
if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}
if (!class_exists('\bbdkp\Loot'))
{
	require("{$phpbb_root_path}includes/bbdkp/Loot/Loot.$phpEx");
}

/**
 * this class manages the loot transaction table (phpbb_bbdkp_raid_items)
 * @package 	bbDKP
 *
 */
class LootController  extends \bbdkp\Admin
{
	private $loot;
	
	public $dkpsys;
	
	function __construct() 
	{
		global $db; 
		parent::__construct();
		$this->loot = new \bbdkp\loot(); 
		
		// get dkp pools
		$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default
            FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . " b
			WHERE a.dkpsys_id = b.event_dkpid AND b.event_status = 1";
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
	 * adds 1 attendee to a raid
	 * @param unknown_type $raid_id
	 * @param unknown_type $raid_value
	 * @param unknown_type $time_bonus
	 * @param unknown_type $dkpid
	 * @param unknown_type $member_id
	 * @param unknown_type $raid_start
	 * @return boolean
	 */
	public function addloot($raid_id, $item_buyers, $item_value, $item_name, $loottime, $itemgameid = 0 )
	{
		global $config; 
		$this->loot = new \bbdkp\Loot();
		$this->loot->raid_id = $raid_id;
		$this->loot->item_value = $item_value; 
		$this->loot->item_name = $item_name;
		$this->loot->dkpid = $dkpid;
		$this->loot->item_date = $this->loottime;
		
		$group_key = $this->gen_group_key ( $this->loot->item_name, $loottime, $this->lootraid_id + rand(10,100) );
		
		$decayarray = array();
		$decayarray[0] = 0;
		$decayarray[1] = 0;
		
		if ($config['bbdkp_decay'] == '1')
		{
			if ( !class_exists('acp_dkp_raid'))
			{
				require($phpbb_root_path . 'includes/acp/acp_dkp_raid.' . $phpEx);
			}
			$acp_dkp_raid = new acp_dkp_raid;
			//diff between now and the raidtime
			$now = getdate();
			$timediff = mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) - $loottime;
			$decayarray = $acp_dkp_raid->decay($itemvalue, $timediff, 2);
		}
		
		$this->add_dkp ($raid_value, $time_bonus, $raid_start, $dkpid, $member_id);
		//
		// Add item to selected members
		$this->add_new_item_db ($item_name, $item_buyers, $group_key, $itemvalue, $raid_id, $loottime, $itemgameid, $decayarray[0]);
		
		//
		// Logging
		//
		$log_action = array (
		'header' 		=> 'L_ACTION_ITEM_ADDED',
		'L_NAME' 		=> $item_name,
		'L_BUYERS' 		=> implode ( ', ', $item_buyers  ),
				'L_RAID_ID' 	=> $raid_id,
				'L_VALUE'   	=> $itemvalue ,
				'L_ADDED_BY' 	=> $user->data['username']);
		
				$this->log_insert ( array (
				'log_type' => $log_action ['header'],
				'log_action' => $log_action ) );
		
		
		return true;
		
	}
	
	public function deleteloot()
	{
		
	}
	
	/**
	 * does the actual item-adding database operations
	 * called from : item acp adding, updating item acp
	 * closed box - no need for other params than passed
	 *
	 * @param item_name
	 * @param item_buyers = array with buyers
	 * @param group key : hash
	 * @param raidid = the raid to which we add the item
	 * @param itemvalue (float) the item cost
	 * @param raidid
	 * @param item_decay = decay to be applied on item
	 * @param $itemgameid : if this is zero we dont care
	 *
	 */
	private function add_new_item_db($item_name, $item_buyers, $group_key, $itemvalue, $raid_id, $loottime, $itemgameid, $itemdecay)
	{
	
		global $db, $user, $config;
		$query = array ();
	
		$sql = "SELECT e.event_dkpid FROM " . EVENTS_TABLE . " e , " . RAIDS_TABLE . " r
		where r.raid_id = " . $raid_id . " AND e.event_id = r.event_id";
		$result = $db->sql_query($sql);
		$dkpid = (int) $db->sql_fetchfield('event_dkpid');
		$db->sql_freeresult ( $result);
	
		// start transaction
		$db->sql_transaction('begin');
	
		// increase dkp spent value for buyers
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				SET member_spent = member_spent + ' . (float) $itemvalue  .  ' ,
					member_item_decay = member_item_decay + ' . (float) $itemdecay .  '
				WHERE member_dkpid = ' . (int) $dkpid  . '
			  	AND ' . $db->sql_in_set('member_id', $item_buyers) ;
		$db->sql_query ( $sql );
	
		$sql = 'SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' WHERE raid_id = ' . $raid_id;
		$result = $db->sql_query($sql);
		unset($raiders);
		$raiders = array();
		while ( $row = $db->sql_fetchrow ($result))
		{
			// don't add the guildbank to the list of raiders
			if ($row['member_id'] != $config['bbdkp_bankerid'])
			{
				$raiders[]= $row['member_id'];
			}
				
		}
		$db->sql_freeresult ( $result);
	
		$numraiders = count($raiders);
		$distributed = round($itemvalue/max(1, $numraiders), 2);
		// rest of division
		$restvalue = $itemvalue - ($numraiders * $distributed);
	
		// Add purchase(s) to items table
		// note : itemid is generated with primary key autoincrease
		// item decay is not redistributed to zerosum earnings, because that is depreciated using raid decay
		foreach ( $item_buyers as $key => $this_member_id )
		{
			$query [] = array (
					'item_name' 		=> (string) $item_name ,
					'member_id' 		=> (int) $this_member_id,
					'raid_id' 			=> (int) $raid_id,
					'item_value' 		=> (float) $itemvalue,
					'item_decay' 		=> (float) $itemdecay,
					'item_date' 		=> (int) $loottime,
					'item_group_key' 	=> (string) $group_key,
					'item_gameid' 		=> $itemgameid,
					'item_zs'			=> (int) $config['bbdkp_zerosum'],
					'item_added_by' 	=> (string) $user->data ['username']
			);
	
			//if zerosum flag is set and if the bank is not set to the looter then distribute item value over raiders
			if($config['bbdkp_zerosum'] == 1 && $config['bbdkp_bankerid'] != $this_member_id )
			{
				// increase raid detail table
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET zerosum_bonus = zerosum_bonus + ' . (float) $distributed . '
						WHERE raid_id = ' . (int) $raid_id . ' AND ' . $db->sql_in_set('member_id', $raiders);
				$db->sql_query ( $sql );
	
				// allocate dkp itemvalue bought to all raiders
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
						SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $distributed  .  ',
						member_earned = member_earned + ' . (float) $distributed  .  '
						WHERE member_dkpid = ' . (int) $dkpid  . '
					  	AND ' . $db->sql_in_set('member_id', $raiders) ;
				$db->sql_query ( $sql );
	
				// give rest value to buyer or guildbank
				if($restvalue!=0 )
				{
						
					$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
							SET zerosum_bonus = zerosum_bonus + ' . (float) $restvalue  .  '
							WHERE raid_id = ' . (int) $raid_id . '
						  	AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $this_member_id);
					$db->sql_query ( $sql );
						
					$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
							SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $restvalue  .  ',
							member_earned = member_earned + ' . (float) $restvalue  .  '
							WHERE member_dkpid = ' . (int) $dkpid  . '
						  	AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $this_member_id);
					$db->sql_query ( $sql );
				}
			}
		}
		$db->sql_multi_insert(RAID_ITEMS_TABLE, $query);
	
		$db->sql_transaction('commit');
	
		return true;
	}
	
	public function Getloot($item_id)
	{
		$loot = new \bbdkp\Loot(); 
		$this->loot->Getloot($item_id); 
		return $loot;
	}
	
	
	/**
	 * counts the number of loot of this attendee in raid
	 * @param unknown_type $raid_id
	 * @param unknown_type $member_id
	 * @return number
	 */
	public function Countloot($raid_id, $member_id)
	{
		global $db; 
		$sql = 'SELECT count(*) as countitems FROM ' . RAID_ITEMS_TABLE . ' where member_id = ' . $member_id . ' and raid_id = ' .  $raid_id;
		$result = $db->sql_query($sql);
		$countitems = (int) $db->sql_fetchfield('countitems');
		$db->sql_freeresult($result);
		return $countitems;  
	}
	
	public function delete_raid($raid_id)
	{
		global $db;
		$sql = 'SELECT i.*, m.member_name FROM ' .
				RAID_ITEMS_TABLE . ' i, ' .
				MEMBER_LIST_TABLE . ' m
				WHERE i.member_id = m.member_id
				and raid_id = ' . (int) $raid_id;
		$result = $db->sql_query ( $sql );
	
		// loop the items collection
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$old_item = array (
					'item_id' 		=>  (int) $row['item_id'] ,
					'dkpid'			=>  $oldraid['event_dkpid'],
					'item_name' 	=>  (string) $row['item_name'] ,
					'member_id' 	=>  (int) 	$row['member_id'] ,
					'member_name' 	=>  (string) $row['member_name'] ,
					'raid_id' 		=>  (int) 	$row['raid_id'],
					'item_date' 	=>  (int) 	$row['item_date'] ,
					'item_value' 	=>  (float) $row['item_value'],
					'item_decay' 	=>  (float) $row['item_decay'] ,
					'item_zs' 		=>  (bool)   $row['item_zs'],
			);
	
			$this->delete($old_item);
	
		}
		$db->sql_freeresult ($result);
	}
	
	
	/*
	 * delete : does one item deletion in database
	*
	* @param : $old_item
	*
	*  array structure required for @param :
	*	'item_id' 		=>  (int) $item_id ,
	*	'dkpid'			=>  $dkp_id,
	*	'item_name' 	=>  (string) $row['item_name'] ,
	*	'member_id' 	=>  (int) 	 $row['member_id'] ,
	*	'member_name' 	=>  (string) $row['member_name'] ,
	*	'raid_id' 		=>  (int) 	 $row['raid_id'],
	*	'item_date' 	=>  (int) 	 $row['item_date'] ,
	*	'item_value' 	=>  (float)  $row['item_value'],
	*	'item_decay' 	=>  (float)  $row['item_decay'],
	*	'item_zs' 		=>  (bool)   $row['item_zs'],
	*
	*/
	public function delete($old_item)
	{
		global $config, $db;
		$db->sql_transaction('begin');
	
		// 1) Remove the item purchase from the items table
		$sql = 'DELETE FROM ' . RAID_ITEMS_TABLE . ' WHERE item_id = ' . $old_item ['item_id'] ;
		$db->sql_query ($sql);
			
		// decrease dkp spent value and decay from buyer
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				SET member_spent = member_spent - ' . $old_item ['item_value'] .  ' ,
				    member_item_decay = member_item_decay - ' . $old_item ['item_decay'] .  '
				WHERE member_dkpid = ' . (int) $old_item ['dkpid']  . '
			  	AND ' . $db->sql_in_set('member_id', $old_item ['member_id']) ;
		$db->sql_query ( $sql );
	
		// if zerosum was given then remove item value from earned value
		if ($old_item ['item_zs'] == true)
		{
			$sql = 'SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' WHERE raid_id = ' . $old_item ['raid_id'] ;
			$result = $db->sql_query($sql);
			unset($raiders);
			$raiders = array();
			while ( $row = $db->sql_fetchrow ($result))
			{
				if ($row['member_id'] != $config['bbdkp_bankerid'])
				{
					$raiders[]= $row['member_id'];
				}
			}
			$db->sql_freeresult ( $result);
				
			$numraiders = count($raiders);
			$distributed = round( $old_item ['item_value']/ max(1, $numraiders), 2);
				
			// decrease raid detail table
			$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
					SET zerosum_bonus = zerosum_bonus - ' . (float) $distributed . '
					WHERE raid_id = ' . (int) $old_item ['raid_id'] . ' AND ' . $db->sql_in_set('member_id', $raiders);
			$db->sql_query ( $sql );
				
			// deallocate dkp itemvalue bought to all raiders
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
					SET member_zerosum_bonus = member_zerosum_bonus - ' . (float) $distributed  .  ',
					member_earned = member_earned - ' . (float) $distributed  .  '
					WHERE member_dkpid = ' . (int) $old_item ['dkpid']  . '
				  	AND ' . $db->sql_in_set('member_id', $raiders) ;
			$db->sql_query ( $sql );
				
			// handle the rest amount
			$restvalue = $old_item ['item_value'] - ($numraiders * $distributed);
				
			if ($restvalue !=0)
			{
				// deduct it from the buyer
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET zerosum_bonus = zerosum_bonus - ' . (float) $restvalue  .  '
						WHERE raid_id = ' . (int) $old_item ['raid_id'] . '
					  	AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $old_item ['member_id']);
				$db->sql_query ( $sql );
	
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
						SET member_zerosum_bonus = member_zerosum_bonus - ' . (float) $restvalue  .  ',
						member_earned = member_earned - ' . (float) $restvalue  .  '
						WHERE member_dkpid = ' . (int) $old_item ['dkpid']  . '
					  	AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $old_item ['member_id']);
				$db->sql_query ( $sql );
	
			}
		}
	
		$db->sql_transaction('commit');
	
		$log_action = array (
				'header' 	=> 'L_ACTION_ITEM_DELETED',
				'L_NAME' 	=> $old_item ['item_name'],
				'L_BUYER' 	=> $old_item ['member_name'],
				'L_RAID_ID' => $old_item ['raid_id'],
				'L_VALUE' 	=> $old_item ['item_value'] );
		
		$this->log_insert ( array (
				'log_type' 		=> $log_action ['header'],
				'log_action' 	=> $log_action ) );
		
		return true;
	
	}
}

?>