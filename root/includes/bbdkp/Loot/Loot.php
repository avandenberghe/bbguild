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

/**
 * this class manages the Item transaction table (phpbb_bbdkp_raid_items)
 * 
 * Items do not have to be tied to raids
 * Items can be crafted, bought from vendors or gotten from the guild bank, etc. 
 * In that case the raid_id is '0' 
 * 
 * @package 	bbDKP
 */
class Loot 
{
	
	/**
	 * CREATE TABLE `phpbb_bbdkp_raid_items` (
		  `item_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		  `raid_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
		  `item_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
		  `item_date` int(11) unsigned NOT NULL DEFAULT '0',
		  `item_added_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `item_updated_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `item_group_key` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `item_gameid` varchar(8) NOT NULL DEFAULT '',
		  `item_value` decimal(11,2) NOT NULL DEFAULT '0.00',
		  `item_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
		  `item_zs` tinyint(1) unsigned NOT NULL DEFAULT '0',
		  `decay_time` decimal(11,2) NOT NULL DEFAULT '0.00',
		  PRIMARY KEY (`item_id`),
		  KEY `raid_id` (`raid_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
	 */
	

	/**
	 * primary key
	 * @var unknown_type
	 */
	public $item_id;
	
	/**
	 * raid in which item dropped.
	 * non unique key
	 * @var unknown_type
	 */
	public $raid_id; 
	
	/**
	 * @var unknown_type
	 */
	public $dkpid;

	/**
	 * given name
	 */
	public $item_name;

	/**
	 * to who does it belong ? if 0 then it belongs to noone
	 * @var unknown_type
	 */
	public $member_id; 
	
	/**
	 * 
	 * @var unknown_type
	 */
	public $member_name; 
	
	/**
	 * when was it acquired ?
	 * @var unknown_type
	 */
	public $item_date; 
	
	
	/**
	 * who added it to the inventory ?
	 * @var string
	 */
	public $item_added_by; 
	
	/**
	 * who updated it ?  
	 */
	public $item_updated_by; 
	
	/**
	 * if item was added to multiple persons at the same time then the group_key will be shared. 
	 */
	public $item_group_key; 
	
	/**
	 * what game does item belong to ? 
	 */
	public $game_id;
	
	/**
	 * how much is it bought for ? 
	 * prix d'achat
	 */
	public $item_value;
	
	/**
	 * how much did it depreciate ?
	 */
	public $item_decay; 
	
	/**
	 * if the item purchaseprice was offset by an equal "zero sum" earning then this flag is 1 
	 */
	public $item_zs; 
	
	
	/**
	 * last time of depreciation 
	 * @var int
	 */
	public $decay_time;
	
	/**
	 * array with loot details
	 * @var unknown_type
	 */
	public $lootdetails; 
	
	function __construct($raid_id = 0) 
	{
		$this->raid_id = $raid_id;
	}
	
	public function insert()
	{
		global $db; 
		
		$sql_ary = array (
				'item_name' 		=> (string) $this->item_name ,
				'member_id' 		=> (int) $this->member_id,
				'raid_id' 			=> (int) $this->raid_id,
				'item_value' 		=> (float) $this->item_value,
				'item_decay' 		=> (float) $this->item_decay,
				'item_date' 		=> (int) $this->item_date,
				'item_group_key' 	=> (string) $this->item_group_key,
				'item_gameid' 		=> $this->game_id,
				'item_zs'			=> (int) $this->item_zs,
				'item_added_by' 	=> (string) $this->item_added_by
		);
		
		$sql = 'INSERT INTO ' . RAID_ITEMS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
		
		$db->sql_query($sql);
		
		
		
		
	}
	
	public function delete()
	{
		global $db;
		$sql = 'DELETE FROM ' . RAID_ITEMS_TABLE . ' WHERE item_id = ' . $this->item_id ;
		$db->sql_query ($sql);
	}
	
	
	
	public function update()
	{
		$this->delete();
		$this->insert();		
	}
	
	
	/**
	 * get one item from raid
	 * @param unknown_type $item_id
	 */	
	public function Getloot($item_id)
	{
		global $db;
		
		$sql = 'SELECT * FROM ' . 
					RAID_ITEMS_TABLE . ' i, ' . 
					MEMBER_LIST_TABLE . ' m, ' .
					RAIDS_TABLE . ' r, ' .
					EVENTS_TABLE . ' e 
				WHERE i.member_id = m.member_id 
				AND i.raid_id = r.raid_id
				AND r.event_id = e.event_id
				AND i.item_id= ' . (int) $item_id;
		
		$result = $db->sql_query ( $sql );
		
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$this->item_id = $item_id; 
			$this->dkpid = $row['event_dkpid']; 
			$this->item_name = (string) $row['item_name'];
			$this->member_id = (int) 	$row['member_id']; 
			$this->member_name = (string)	$row['member_name']; 
			$this->game_id = $row['game_id'];
			$this->raid_id = (int) 	$row['raid_id']; 
			$this->item_date = (int) 	$row['item_date']; 
			$this->item_value = (float) $row['item_value']; 
			$this->item_decay =   (float) $row['item_decay']; 
			$this->item_zs = (bool)  $row['item_zs']; 
		}		
		$db->sql_freeresult ($result);
		
		$a = 1; 
		
	}
	
	
	/**
	 * get array of all loot for the raid or for a member
	 * @param unknown_type $raid_id
	 * @param unknown_type $order
	 * @param unknown_type $member_id
	 * @return unknown
	 */
	public function Get($raid_id, $order = ' i.member_id' , $member_id = 0)
	{
		global $config, $db;
	
		$sql_array = array(
				'SELECT'    => 'i.item_id, i.item_name, i.item_gameid, i.member_id, i.item_zs,
	    				m.member_name, c.colorcode, c.imagename, m.member_gender_id,
	    				a.image_female, a.image_male, i.item_date, i.raid_id, 
						i.item_value, i.item_decay, i.item_value - i.item_decay as item_total',
				'FROM'      => array(
						CLASS_TABLE 		=> 'c',
						RACE_TABLE  		=> 'a',
						MEMBER_LIST_TABLE 	=> 'm',
						RAID_ITEMS_TABLE    => 'i',
				),
				'WHERE'     =>  "c.game_id = m.game_id and c.class_id = m.member_class_id
	    				 AND m.member_race_id =  a.race_id and a.game_id = m.game_id
	    				 and m.member_id = i.member_id and i.raid_id = " . (int) $raid_id,
				'ORDER_BY'  => $order ,
		);
		
		if($member_id > 0)
		{
			$sql_array['WHERE'] .= " AND m.member_id = '" . $member_id ."'";
		}
		
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
	
		$lootlist = array ();
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
	
			$this->lootdetails[$row['item_id']]['item_id'] = $row['item_id'];
			$this->lootdetails[$row['item_id']]['item_name'] = $row['item_name'];
			$this->lootdetails[$row['item_id']]['gameid'] = $row['item_gameid'];
			$this->lootdetails[$row['item_id']]['member_id'] = $row['member_id'];
			$this->lootdetails[$row['item_id']]['colorcode'] = $row['colorcode'];
			$this->lootdetails[$row['item_id']]['imagename'] = $row['imagename'];
			$this->lootdetails[$row['item_id']]['raceimage'] = $race_image;
			$this->lootdetails[$row['item_id']]['member_name'] = $row['member_name'];
			$this->lootdetails[$row['item_id']]['item_value'] = $row['item_value'];
			$this->lootdetails[$row['item_id']]['item_total'] = $row['item_total'];
			$this->lootdetails[$row['item_id']]['item_zs'] = $row['item_zs'];
			$this->lootdetails[$row['item_id']]['item_decay'] = $row['item_decay'];
		}
	
		return $this->lootdetails;
	
	}
	
	
	
	
}

?>