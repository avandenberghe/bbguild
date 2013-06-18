<?php
/**
 *
 * @package 	bbDKP
 * @link 		http://www.bbdkp.com
 * @author 		Sajaki@gmail.com
 * @copyright 	2013 bbdkp
 * @license 	http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 	1.2.9
 * @since 		1.2.9
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
require ("{$phpbb_root_path}includes/bbdkp/Adjustments/iAdjust.$phpEx");

// Include the abstract base
if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

/**
 * Adjust
 * 
 * phpbb_bbdkp_adjustments Class
 * 
 * @package 	bbDKP
 */
class Adjust extends Admin implements iAdjust
{
	public $adjustment_id;
	public $member_id = 0;
	public $member_name = '';
	public $adjustment_dkpid = 0;
	public $adjustment_value = 0.0;
	public $adjustment_date;
	public $adjustment_reason = '';
	public $adjustment_added_by = '';
	public $adjustment_updated_by = '';
	public $adjustment_groupkey = '';
	public $adj_decay = 0.0;
	public $can_decay = 0;
	public $decay_time = 0;
	public $members_samegroupkey = array();

	/**
	 * add a new dkp adjustment
	 *
	 */
	public function add()
	{
		global $user, $db;
		// no global scope
		if ($this->member_id == 0)
		{
			trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
		}
		//
		// does member have a dkp record ?
		//
		$sql = 'SELECT count(member_id) as membercount FROM  ' . MEMBER_DKP_TABLE . '
		WHERE member_id = ' . $this->member_id . '
		AND member_dkpid = ' . $this->adjustment_dkpid;
		$result = $db->sql_query($sql);
		$membercount = (int) $db->sql_fetchfield('membercount');

		$db->sql_transaction ( 'begin' );

		if ($membercount == 1)
		{
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
				SET member_adjustment = member_adjustment + ' . $this->adjustment_value . "
                WHERE member_id='" . $this->member_id . "'
        		AND member_dkpid = " . $this->adjustment_dkpid;
			$db->sql_query($sql);
			unset($sql);
		}
		elseif ($membercount == 0)
		{
			
			$query = $db->sql_build_array('INSERT', array(
					'member_dkpid' => $this->adjustment_dkpid ,
					'member_id' => $this->member_id ,
					'member_earned' => 0.00 ,
					'member_spent' => 0.00 ,
					'member_adjustment' => $this->adjustment_value ,
					'member_status' => 1 ,
					'member_firstraid' => 0 ,
					'member_lastraid' => 0 ,
					'member_raidcount' => 0));
			$db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
		}

		$query = $db->sql_build_array('INSERT', array(
				'adjustment_dkpid' => $this->adjustment_dkpid ,
				'adjustment_value' => $this->adjustment_value ,
				'adjustment_date' => $this->time ,
				'member_id' => $this->member_id ,
				'adjustment_reason' => $this->adjustment_reason ,
				'adjustment_group_key' => $this->adjustment_groupkey ,
				'can_decay' => $this->can_decay ,
				'adjustment_added_by' => $user->data['username']));

		$db->sql_query('INSERT INTO ' . ADJUSTMENTS_TABLE . $query);

		$db->sql_transaction('commit');
	}


	/**
	 * get an adjustment from database
	 * @see \bbdkp\iAdjust::get()
	 */
	public function get($adjust_id)
	{
		global $user, $db;

		$sql_array = array(
				'SELECT' => 'a.adjustment_id,
							a.adjustment_value,
							a.adjustment_dkpid,
							a.adjustment_date,
							a.adjustment_reason,
							a.member_id,
							m.member_name,
							a.adjustment_group_key,
							a.adjustment_added_by,
							a.adjustment_updated_by,
							a.adj_decay,
							a.decay_time,
							a.can_decay' ,
				'FROM' => array(
						ADJUSTMENTS_TABLE => 'a' ,
						MEMBER_LIST_TABLE => 'm') ,
				'WHERE' => 'a.member_id = m.member_id
					AND a.adjustment_id = ' . $adjust_id
			);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		if (! $row = $db->sql_fetchrow($result))
		{
			trigger_error($user->lang['ERROR_INVALID_ADJUSTMENT'], E_USER_NOTICE);
		}
		$db->sql_freeresult($result);
		
		$this->adjustment_id = $row['adjustment_id']; 
		$this->adjustment_value = $row['adjustment_value'];
		$this->adjustment_dkpid = $row['adjustment_dkpid'];
		$this->adjustment_date = $row['adjustment_date'];
		$this->adjustment_reason = $row['adjustment_reason'];
		$this->member_id = $row['member_id'];
		$this->member_name = $row['member_name'];
		$this->adjustment_group_key = $row['adjustment_group_key'];
		$this->adjustment_added_by = $row['adjustment_added_by'];
		$this->adjustment_updated_by = $row['adjustment_updated_by'];
		$this->adj_decay = $row['adj_decay'];
		$this->decay_time = $row['decay_time'];
		$this->can_decay = $row['can_decay'];
		
		
		$members = array();
		$sql = 'SELECT member_id from ' . ADJUSTMENTS_TABLE . " WHERE adjustment_group_key = '" . $this->adjustment_group_key . "'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$members[] = $row['member_id']; 
		}
		
		$this->members_samegroupkey = $members;
		unset($members);
	

	}

	
	/**
	 * deletes adjustment
	 * @see \bbdkp\iAdjust::delete()
	 */
	function delete()
	{
		global $db;

		$db->sql_transaction ( 'begin' );
		
		$sql = 'DELETE FROM ' . ADJUSTMENTS_TABLE . ' WHERE adjustment_id = ' . $this->adjustment_id;
		$db->sql_query($sql);
		
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
			SET member_adjustment = member_adjustment - ' . (float) $this->adjustment_value . ',
			adj_decay = adj_decay - ' . (float) $this->adj_decay . '
			WHERE  member_dkpid = ' . $this->adjustment_dkpid . ' 
			AND member_id = ' . $this->member_id; 
		
		$db->sql_query($sql);
		
		$db->sql_transaction('commit');
	}
	
	
	/**
	 *
	 * returns list of adjustments to admin page
	 * @see \bbdkp\iAdjust::listadj()
	 */
	function listadj($order, $member_id, $start=0)
	{
		global $user, $db, $config;
		$order = (string) $order;
		$member_id = (int) $member_id;

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
						AND a.adjustment_dkpid 	= ' . (int) $this->adjustment_dkpid . '
						AND a.member_id = l.member_id
						AND a.member_id IS NOT NULL ' ,
				'ORDER_BY' => $order);

		if ($member_id != 0)
		{
			$sql_array['WHERE'] .= ' AND a.member_id = ' . $member_id;
		}

		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		if ($start > 0)
		{
			$result = $db->sql_query_limit($sql, $config['bbdkp_user_alimit'], $start, 0);
		}
		else
		{
			$result = $db->sql_query ( $sql );
		}
		return $result;

	}

	/**
	 * Counts adjustments for a pool/member
	 *
	 * @param int $member_id
	 * @return unknown
	 */
	function countadjust($member_id)
	{
		$member_id = (int) $member_id;
		global $user, $db;
		$sql = 'SELECT count(*) as total_adjustments
					FROM ' . ADJUSTMENTS_TABLE . '
					WHERE member_id IS NOT NULL
					and adjustment_dkpid 	= ' . (int) $adjust->adjustment_dkpid;
		if ($member_id != 0)
		{
			$sql .= ' and member_id  = ' . $member_id;
		}
		$result = $db->sql_query($sql);
		return $result;

	}

	/**
	 * Lists the pools with adjustments
	 * @return unknown
	 */
	function listAdjPools()
	{
		global $user, $db;
		$sql = 'SELECT dkpsys_id, dkpsys_name , dkpsys_default
		          FROM ' . DKPSYS_TABLE . ' a, ' . ADJUSTMENTS_TABLE . ' j
		          WHERE a.dkpsys_id = j.adjustment_dkpid
		          GROUP BY dkpsys_id, dkpsys_name , dkpsys_default';
		$result = $db->sql_query($sql);
		return $result;
	}
	
	

	/**
	 * function to decay one specific adjustment
	 *
	 * @param int adj_id the adjustment id to decay
	 * @param int $dkpid dkpid for adapting accounts
	 * @param unknown_type $member_id
	 * @param unknown_type $adjdate
	 * @param unknown_type $value
	 * @param unknown_type $olddecay
	 * @return boolean
	 */
	public function decayadj ($adjust_id)
	{
		global $user, $config, $db;
		$oldadj = $this->get($adjust_id);
	
		$now = getdate();
		$timediff = mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) - $this->adjustment_date;
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
		$this->adj_decay = round($this->adjustment_value * (1 - pow(1 - $i, $n)), 2);
	
		$db->sql_transaction ( 'begin' );
		
		// update adj detail to new decay value
		$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . '
			SET adj_decay = ' . $this->adj_decay . ", decay_time = " . $n . "
			WHERE adjustment_id = " . (int) $adjust_id;
		$db->sql_query($sql);
	
		// update dkp account, deduct old, add new decay
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET adj_decay = adj_decay - '  . $oldadj->adj_decay . ' + ' . $this->adj_decay . "
			WHERE member_id = " . (int) $this->member_id . '
			and member_dkpid = ' . $this->adjustment_dkpid;
	
		$db->sql_query($sql);
		
		$db->sql_transaction('commit');
	
		unset ($oldadj);
	
		return true;
	}
	

	/**
	 * Recalculates and updates adjustment decay
	 * @param $mode 1 for recalculating, 0 for setting decay to zero.
	 * @see \bbdkp\iAdjust::sync_adjdecay()
	 */
	public function sync_adjdecay ($mode, $origin = '')
	{
		global $user, $db;
		switch ($mode)
		{
			case 0:
				//  Decay = OFF : set all decay to 0
				//  update item detail to new decay value
				
				$db->sql_transaction('begin');
				
				$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . ' SET adj_decay = 0 ';
				$db->sql_query($sql);
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET adj_decay = 0 ';
				$db->sql_query($sql);
				
				$db->sql_transaction('commit');
				
				if ($origin == 'cron')
				{
					$origin = $user->lang['DECAYCRON'];
				}
				return true;
				break;
			case 1:
				// Decay is ON : synchronise
				// loop all ajustments
				$sql = 'SELECT adjustment_dkpid, adjustment_id, member_id , adjustment_date, adjustment_value, adj_decay FROM ' . ADJUSTMENTS_TABLE . ' WHERE can_decay = 1';
				$result = $db->sql_query($sql);
				$countadj = 0;
				while (($row = $db->sql_fetchrow($result)))
				{
					$this->decayadj($row['adjustment_id']);
					$countadj ++;
				}
				$db->sql_freeresult($result);
				return $countadj;
				break;
		}
	}
	
	
}
?>