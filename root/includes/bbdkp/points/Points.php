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

//include the abstract base
if (!interface_exists('\bbdkp\iPoints'))
{
	require ("{$phpbb_root_path}includes/bbdkp/Points/iPoints.$phpEx");
}

/**
 *  Points Class
 *  this class manages the points table where all transaction tables are centralised (phpbb_bbdkp_memberdkp)
 *  
 * @package 	bbDKP
 */
class Points implements iPoints 
{	
	public $member_id; // pk
	public $member_dkpid;  //pk
	public $member_raid_value;
	public $member_time_bonus;
	public $member_zerosum_bonus;
	public $member_earned;
	public $member_raid_decay;
	public $member_spent;
	public $member_item_decay;
	public $member_adjustment;
	public $member_status;
	public $member_firstraid;
	public $member_lastraid;
	public $member_raidcount;
	public $adj_decay;
	
	function __construct() 
	{
	
	}
	
	/**
	 * Recalculates zero sum points
	 * -- loops all raids, may run a while
	 * @param $mode one for recalculating, 0 for setting zerosum to zero.
	 * @see \bbdkp\iPoints::sync_zerosum()
	 */
	public function sync_zerosum($mode)
	{
		global $user, $db, $config;
	
		switch ($mode)
		{
			case 0:
				// set all to 0
				//  update raid detail table to 0
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET zerosum_bonus = 0 ' ;
				$db->sql_query ( $sql );
	
				// update dkp account
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_zerosum_bonus = 0, member_earned = member_raid_value + member_time_bonus';
				$db->sql_query ( $sql );
	
				if (!class_exists('\bbdkp\Admin'))
				{
					require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
				}
				$bbdkp = new \bbdkp\Admin();
				
				$log_action = array (
						'header' 		=> 'L_ACTION_ZSYNC',
						'L_USER' 		=>  $user->data['user_id'],
						'L_USERCOLOUR' 	=>  $user->data['user_colour'],
							
				);
				$bbdkp->log_insert ( array (
						'log_type' 		=> $log_action ['header'],
						'log_action' 	=> $log_action ) );
				
				unset($bbdkp);
				
				trigger_error ( sprintf($user->lang ['RESYNC_ZEROSUM_DELETED']) , E_USER_NOTICE );
	
				return true;
				break;
	
			case 1:
				// redistribute
	
				//  update raid detail table to 0
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET zerosum_bonus = 0 ' ;
				$db->sql_query ( $sql );
	
				// update dkp account
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_zerosum_bonus = 0, member_earned = member_raid_value + member_time_bonus';
				$db->sql_query ( $sql );
	
	
				// loop raids having items
				$sql = 'SELECT e.event_dkpid, r.raid_id FROM '.
						RAIDS_TABLE. ' r, ' .
						EVENTS_TABLE . ' e, ' .
						RAID_ITEMS_TABLE . ' i
					WHERE e.event_id = r.event_id
					AND r.raid_id = i.raid_id
					GROUP BY e.event_dkpid, r.raid_id' ;
				$result = $db->sql_query ($sql);
				$countraids=0;
				$raids = array();
				while ( ($row = $db->sql_fetchrow ( $result )) )
				{
					$raids[$row['raid_id']]['dkpid']=$row['event_dkpid'];
					$countraids++;
				}
				$db->sql_freeresult ( $result);
	
				foreach($raids as $raid_id => $raid)
				{
					$numraiders = 0;
					$sql = 'SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' WHERE raid_id = ' . $raid_id;
					$result = $db->sql_query($sql);
					while ( $row = $db->sql_fetchrow ($result))
					{
						if ($row['member_id'] != $config['bbdkp_bankerid'])
						{
							$raids[$raid_id]['raiders'][]= $row['member_id'];
						}
						$numraiders++;
					}
					$raids[$raid_id]['numraiders'] = $numraiders;
	
					$db->sql_freeresult ( $result);
						
					$sql = 'SELECT member_id, item_value, item_id FROM ' . RAID_ITEMS_TABLE . ' WHERE raid_id = ' . $raid_id;
					$result = $db->sql_query($sql);
					$numbuyers=0;
					while ( $row = $db->sql_fetchrow ($result))
					{
						$raids[$raid_id]['item'][$row['item_id']]['buyer'] = $row['member_id'];
						$raids[$raid_id]['item'][$row['item_id']]['item_value'] = $row['item_value'];
	
						$distributed = round($row['item_value'] / max(1, $numraiders), 2);
						$raids[$raid_id]['item'][$row['item_id']]['distributed_value']= $distributed;
	
						// rest of division
						$restvalue = $row['item_value'] - ($numraiders * $distributed);
						$raids[$raid_id]['item'][$row['item_id']]['rest_value'] = $restvalue;
	
						$numbuyers++;
	
					}
						
					$db->sql_freeresult ( $result);
					$raids[$raid_id]['numbuyers'] = $numbuyers;
				}
	
				//now process the raid array with following structure
				/*
				* "$raids[1]"	Array [5]
				dkpid	(string:1) 1
				raiders	Array [4]
				0	(string:1) 2
				1	(string:1) 3
				2	(string:1) 4
				3	(string:1) 5
				numraiders	(int) 4
				item	Array [2]
				1	Array [4]
				buyer	(string:1) 5
				item_value	(string:5) 15.00
				distributed_value	(double) 3.75
				rest_value	(double) 0
				2	Array [4]
				buyer	(string:1) 4
				item_value	(string:5) 15.00
				distributed_value	(double) 3.75
				rest_value	(double) 0
				numbuyers	(int) 2
				*/
	
				$itemcount = 0;
				$accountupdates=0;
				foreach($raids as $raid_id => $raid)
				{
					$accountupdates += $raid['numraiders'];
						
					$items = $raid['item'];
					foreach($items as $item_id => $item)
					{
						// distribute this item value as income to all raiders
						$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET zerosum_bonus = zerosum_bonus + ' . (float) $item['distributed_value'] . '
						WHERE raid_id = ' . (int) $raid_id . ' AND ' . $db->sql_in_set('member_id', $raid['raiders']);
						$db->sql_query ( $sql );
						$itemcount ++;
		
						// update their dkp account aswell
							$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
							SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $item['distributed_value']  .  ',
							member_earned = member_earned + ' . (float) $item['distributed_value']  .  '
								WHERE member_dkpid = ' . (int) $raid['dkpid']  . '
								  	AND ' . $db->sql_in_set('member_id', $raid['raiders']   ) ;
								  	$db->sql_query ( $sql );
	
	
						// give rest value to the buyer in raiddetail or to the guild bank
							if($item['rest_value']!=0 )
						{
							$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
							SET zerosum_bonus = zerosum_bonus + ' . (float) $item['rest_value']  .  '
							WHERE raid_id = ' . (int) $raid_id . '
								AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $item['buyer'])  ;
							$db->sql_query ( $sql );
				
							$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
									SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $item['rest_value']  .  ',
												member_earned = member_earned + ' . (float) $item['rest_value']  .  '
												WHERE member_dkpid = ' . (int) $raid['dkpid']  . '
								  	AND member_id = ' .  ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $item['buyer']);
									  	$db->sql_query ( $sql );
						}
					}
				}
				
				$bbdkp = new \bbdkp\Admin();
				$log_action = array (
					'header' 		=> 'L_ACTION_ZSYNC',
					'L_USER' 		=>  $user->data['user_id'],
					'L_USERCOLOUR' 	=>  $user->data['user_colour'],
								);
				$bbdkp->log_insert ( array (
					'log_type' 		=> $log_action ['header'],
					'log_action' 	=> $log_action ) );
	
				trigger_error ( sprintf($user->lang ['RESYNC_ZEROSUM_SUCCESS'], $itemcount, $accountupdates ) , E_USER_NOTICE );
	
				return $countraids;
	
				break;
													
		}
	
		}
}

?>