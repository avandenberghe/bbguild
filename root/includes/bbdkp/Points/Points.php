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
 *  Points Class
 *  this class manages the points summary table where the 3 
 *  transaction tables are centralised (phpbb_bbdkp_memberdkp)
 *  	transaction tables : raid_detail, adjustments, items
 *  
 * @package 	bbDKP
 */
class Points
{	
	/*
	CREATE TABLE `phpbb_bbdkp_memberdkp` (
	`member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`member_dkpid` smallint(4) unsigned NOT NULL DEFAULT '0',
	`member_raid_value` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_time_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_zerosum_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_earned` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_raid_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_spent` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_item_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_adjustment` decimal(11,2) NOT NULL DEFAULT '0.00',
	`member_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`member_firstraid` int(11) unsigned NOT NULL DEFAULT '0',
	`member_lastraid` int(11) unsigned NOT NULL DEFAULT '0',
	`member_raidcount` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`adj_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
	PRIMARY KEY (`member_dkpid`,`member_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
	*/
	
	public $member_id; // pk
	public $dkpid;  //pk
	
	/**
	 * raid value (from event)
	 * @var unknown_type
	 */
	public $raid_value;

	/**
	 * time bonus received for being on time in raid
	 * @var unknown_type
	 */
	public $time_bonus;
	
	/**
	 * zerosum bonus earned when loot was given to even out debit/credit
	 * @var unknown_type
	 */
	public $zerosum_bonus;
	
	/**
	 * sum of raid_value, time bonus and zero sum bonus
	 * @var unknown_type
	 */
	public $total_earned;
	
	/**
	 * depreciation of earned amount
	 * @var unknown_type
	 */
	public $earned_decay;
	
	/**
	 * holds value spent on item purchases
	 * @var unknown_type
	 */
	public $spent;
	
	/**
	 * purchase depreciation
	 * @var unknown_type
	 */
	public $item_decay;
	
	/**
	 * holds adjustment value
	 * @var unknown_type
	 */
	public $adjustment;
	
	/**
	 * depreciated adjustment
	 * @var unknown_type
	 */
	public $adj_decay;
	
	/**
	 * sum of $earned - $spent + $adjustment, 
	 * @var unknown_type
	 */
	public $total; 
	
	/**
	 * sum of earned, spent, adjustment decay
	 */
	public $total_decayed;
	
	/**
	 * net total after decay
	 * @var unknown_type
	 */
	public $total_net;
	
	/**
	 * status of this account
	 * @var unknown_type
	 */
	public $status;
	
	/**
	 * start date of this account
	 * @var unknown_type
	 */
	public $firstraid;
	
	/**
	 * last raid recording
	 * @var unknown_type
	 */
	public $lastraid;
	
	/**
	 * number of raids attended
	 * @var unknown_type
	 */
	public $raidcount;
	
	
	function __construct() 
	{
	
	}
	
	public function has_account($member_id, $dkpid)
	{
		global $db; 
		
		$sql = 'SELECT count(member_id) as present FROM ' . MEMBER_DKP_TABLE . '
				WHERE member_id = ' . $member_id . '
				AND member_dkpid = ' . $dkpid;
		$result = $db->sql_query($sql);
		$present = (int) $db->sql_fetchfield('present', false, $result);
		$db->sql_freeresult($result);
		
		if($present > 0)
		{
			return true;
		}
		
		return false; 
		
	}
	
	/**
	 * read account
	 */
	public function read_account()
	{
		global $db;
	
		$sql = 'SELECT * FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . (int) $this->member_id . ' AND member_dkpid = ' . $this->dkpid;
		$result = $db->sql_query ($sql);
		while ( ($row = $db->sql_fetchrow ( $result )) )
		{
				$this->dkpid = $row['member_dkpid']; 
				$this->member_id = $row['member_id'];
				$this->status = $row['member_status'];
				
				$this->raid_value = (float)  $row['member_raid_value'];  	
				$this->time_bonus  = (float)  $row['member_time_bonus']; 
				$this->zerosum_bonus = (float)  $row['member_zerosum_bonus'];
				$this->member_earned = $this->raid_value + $this->time_bonus + $this->zerosum_bonus; 
				$this->earned_decay = (float) $row['member_raid_decay'];
				 
				$this->spent = (float) $row['member_spent']; 
				$this->item_decay = (float) $row['member_item_decay']; 
				
				$this->adjustment = (float) $row['member_adjustment']; 
				$this->adj_decay = (float) $row['adj_decay']; 
				
				$this->total = $this->member_earned - $this->spent + $this->adjustment;
				$this->total_decayed = $this->earned_decay - $this->item_decay + $this->adj_decay; 
				
				$this->total_net = $this->total - $this->total_decayed;  
				
				$this->firstraid = $row['member_firstraid']; 				
				$this->lastraid = $row['member_lastraid'];
				$this->raidcount = $row['member_raidcount'];
		}
		$db->sql_freeresult ($result);
	}
	
	/**
	 * Opens a new account
	 */
	public function open_account()
	{
		global $db;
		
		$query = $db->sql_build_array('INSERT', array(
			'member_dkpid'       	=> $this->dkpid,
			'member_id'          	=> $this->member_id,
			'member_status'      	=> $this->status, 
			'member_raid_value'  	=> $this->raid_value ,
			'member_time_bonus'  	=> $this->time_bonus ,
			'member_zerosum_bonus'  => $this->zerosum_bonus,
			'member_earned'      	=> $this->raid_value + $this->time_bonus + $this->zerosum_bonus,
			'member_raid_decay' 	=> $this->earned_decay,	
			'member_spent'      	=> $this->spent,
			'member_item_decay' 	=> $this->item_decay, 
			'member_adjustment'     => $this->adjustment,
			'adj_decay' 			=> $this->adj_decay,
			'member_firstraid'   	=> $this->firstraid,
			'member_lastraid'    	=> $this->lastraid,
			'member_raidcount'   	=> $this->raidcount
		));
		
		$db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
	}
	

	public function update_account()
	{
		global $user, $db;
		
		$query = $db->sql_build_array ( 'UPDATE', array (
			'member_dkpid'       	=> $this->dkpid,
			'member_id'          	=> $this->member_id,
			'member_status'      	=> $this->status, 
			'member_raid_value'  	=> $this->raid_value ,
			'member_time_bonus'  	=> $this->time_bonus ,
			'member_zerosum_bonus'  => $this->zerosum_bonus,
			'member_earned'      	=> $this->raid_value + $this->time_bonus + $this->zerosum_bonus,
			'member_raid_decay' 	=> $this->earned_decay,
			'member_spent'      	=> $this->spent,
			'member_item_decay' 	=> $this->item_decay, 
			'member_adjustment'     => $this->adjustment,
			'adj_decay' 			=> $this->adj_decay,
			'member_firstraid'   	=> $this->firstraid,
			'member_lastraid'    	=> $this->lastraid,
			'member_raidcount'   	=> $this->raidcount
		)); 
		
		$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $query . " WHERE member_id = " . (int) $this->member_id . ' AND member_dkpid = ' . $this->dkpid );
		
	}
	
	public function delete_account()
	{
		global $db; 
		
		$sql = "DELETE FROM " . MEMBER_DKP_TABLE . " WHERE member_id = " . (int) $this->member_id . " AND member_dkpid = " . $this->dkpid ; 
		$db->sql_query($sql);
		
	}
	

}

?>