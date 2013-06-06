<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * @since 1.2.9
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
require_once ("{$phpbb_root_path}includes/bbdkp/members/iMembers.$phpEx");

/**
 *  phpbb_bbdkp_adjustments Class
 *  
 * 	phpbb_bbdkp_adjustments
 *	adjustment_id PK
 *	member_id index
 *	adjustment_dkpid index
 *	adjustment_value 
 *	adjustment_date 
 *	adjustment_reason
 *	adjustment_added_by
 *	adjustment_updated_by
 *	adjustment_group_key
 *	adj_decay
 *	can_decay
 *	decay_time
 *
 */
class Adjust implements iAdjust 
{
	public $adjustment_id; 
	public $member_id = 0;
	public $adjustment_dkpid = 0;
	public $adjustment_value = 0.0;
	public $adjustment_date;
	public $adjustment_reason = ''; 
	public $adjustment_added_by = '';
	public $adjustment_updated_by = '';
	public $adjustment_groupkey = '';
	public $adj_decay;
	public $can_decay;
	public $decay_time; 
		
	/**
	 */
	function __construct() 
	{
	
	}
	
	/**
	 * add a new dkp adjustment
	 *
	 */
	public function Add ($group_key)
	{
		global $user, $db;
		// no global scope
		$member_id = (int) $member_id;
		$adjval = (float) $adjval;
		$dkpsys_id = (int) $dkpid;
		if ($member_id == 0)
		{
			trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
		}
		//
		// does member have a dkp record ?
		//
		$sql = 'SELECT count(member_id) as membercount FROM  ' . MEMBER_DKP_TABLE . '
		WHERE member_id = ' . $member_id . '
		AND member_dkpid = ' . $dkpsys_id;
		$result = $db->sql_query($sql);
		$membercount = (int) $db->sql_fetchfield('membercount');
		if ($membercount == 1)
		{
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				SET member_adjustment = member_adjustment + ' . $adjval . "
                WHERE member_id='" . $member_id . "'
        		AND member_dkpid = " . $dkpsys_id;
			$db->sql_query($sql);
			unset($sql);
		}
		elseif ($membercount == 0)
		{	
			$query = $db->sql_build_array('INSERT', array(
				'member_dkpid' => $dkpsys_id ,
				'member_id' => $member_id ,
				'member_earned' => 0.00 ,
				'member_spent' => 0.00 ,
				'member_adjustment' => $adjval ,
				'member_status' => 1 ,
				'member_firstraid' => 0 ,
				'member_lastraid' => 0 ,
				'member_raidcount' => 0));
				$db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
		}
	
		//
		// Add the adjustment to the database
		//
		$query = $db->sql_build_array('INSERT', array(
		'adjustment_dkpid' => $dkpsys_id ,
		'adjustment_value' => $adjval ,
		'adjustment_date' => $this->time ,
		'member_id' => $member_id ,
		'adjustment_reason' => $adjreason ,
		'adjustment_group_key' => $group_key ,
		'can_decay' => $candecay ,
		'adjustment_added_by' => $user->data['username']));
		$db->sql_query('INSERT INTO ' . ADJUSTMENTS_TABLE . $query);
	}
	
	
	
	/**
	 * function to decay one specific adjustment
	 * @param int adj_id the adjustment id to decay
	 * @param int $dkpid dkpid for adapting accounts
	 */
	private function decayadj ($olddecay)
	{
		global $config, $db;
		$now = getdate();
		$timediff = mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) - $adjdate;
		$i = (float) $config['bbdkp_adjdecaypct'] / 100;
		// get decay frequency
		$freq = $config['bbdkp_decayfrequency'];
		if ($freq == 0)
		{
			//frequency can't be 0. throw error
			trigger_error($user->lang['FV_FREQUENCY_NOTZERO'], E_USER_WARNING);
		}
		//pick decay frequency type (0=days, 1=weeks, 2=months) and convert timediff to that
		$t = 0;
		switch ($config['bbdkp_decayfreqtype'])
		{
			case 0:
				//days
				$t = (float) $timediff / 86400;
				break;
			case 1:
				//weeks
				$t = (float) $timediff / (86400 * 7);
				break;
			case 2:
				//months
				$t = (float) $timediff / (86400 * 30.44);
				break;
		}
		// take the integer part of time and interval division base 10,
		// since we only decay after a set interval
		$n = intval($t / $freq, 10);
		//calculate rounded adjustment decay, defaults to rounds half up PHP_ROUND_HALF_UP, so 9.495 becomes 9.50
		$decay = round($value * (1 - pow(1 - $i, $n)), 2);
		// update adj detail to new decay value
		$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . '
			SET adj_decay = ' . $decay . ", decay_time = " . $n . "
			WHERE adjustment_id = " . (int) $adj_id;
		$db->sql_query($sql);
		// update dkp account, deduct old, add new decay
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET adj_decay = adj_decay - ' . $olddecay . ' + ' . $decay . "
			WHERE member_id = " . (int) $member_id . '
			and member_dkpid = ' . $dkpid;
		$db->sql_query($sql);
		return true;
	}
	

	/**
	 * returns list of adjustments to admin page
	 * @see \bbdkp\iAdjust::listadj()
	 */
	function listadj($order)
	{
		global $user, $db;
		$sql_array = array(
				'SELECT' => 'a.adjustment_dkpid, a.adjustment_reason,
			    				b.dkpsys_name, a.adjustment_id, a.adj_decay, a.decay_time, a.can_decay,
			    				a.adjustment_value, a.member_id, l.member_name,
			    				a.adjustment_date, a.adjustment_added_by, c.colorcode, c.imagename ' ,
				'FROM' => array(
						ADJUSTMENTS_TABLE => 'a' ,
						DKPSYS_TABLE => 'b' ,
						MEMBER_LIST_TABLE => 'l' ,
						CLASS_TABLE => 'c') ,
				'WHERE' => '
			    		b.dkpsys_id = a.adjustment_dkpid
			    		AND c.class_id = l.member_class_id
			    		AND l.game_id= c.game_id
						AND a.adjustment_dkpid 	= ' . (int) $this->dkpsys_id . '
						AND a.member_id = l.member_id
						AND a.member_id IS NOT NULL ' ,
				'ORDER_BY' => $order);
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		return $result;
		
	}
}

?>