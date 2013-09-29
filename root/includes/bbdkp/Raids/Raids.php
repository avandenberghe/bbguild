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
if (!class_exists('\bbdkp\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}

/**
 * This class controls raid acp.
 *
 * @package 	bbDKP
 *
 */
class Raids extends \bbdkp\Admin
{
	//header fields
	public $raid_id;
	private $event_id;
	private $event_dkpid;
	private $dkpsys_name;
	private $event_name;
	private $event_value;
	private $raid_start;
	private $raid_end;
	private $raid_note;
	private $raid_added_by;
	private $raid_updated_by;

	function __construct($raid_id = 0)
	{
		parent::__construct();

		$this->raid_id = $raid_id;
		if($raid_id != 0)
		{
			$this->get();
		}
		else
		{
			$this->dkpsys_name 		= '';
			$this->event_dkpid 		= 0;
			$this->event_id			= 0;
			$this->event_name 		= '';
			$this->event_value 		= 0.0;
			$this->raid_start 		= 0;
			$this->raid_end 		= 0;
			$this->raid_note 		= '';
			$this->raid_added_by 	= '';
			$this->raid_updated_by 	= '';
		}
	}

	/**
	 *
	 * @param string $property
	 */
	public function __get($property)
	{
		global $user;

		if (property_exists($this, $property))
		{
			return $this->$property;
		}
		else
		{
			trigger_error($user->lang['ERROR'] . ' ' . get_class($this) . ' : ' . $property, E_USER_WARNING);
		}
	}

	/**
	 *
	 * @param unknown_type $property
	 * @param unknown_type $value
	 */
	public function __set($property, $value)
	{
		global $user;
		switch ($property)
		{
			default:
				if (property_exists($this, $property))
				{
					switch($property)
					{
						case 'raid_note':
							// limit this to 255
							$this->$property = (strlen($value) > 250) ? substr($value,0, 250).'...' : $value;
							break;
						default:
							$this->$property = $value;
							break;
					}
				}
				else
				{
					trigger_error($user->lang['ERROR'] . ': ' . get_class($this) . '=>'. $property, E_USER_WARNING);
				}
		}
	}


	/**
	 * inserts a raid postfacto
	 */
	public function Create()
	{
		global $db;
		$query = $db->sql_build_array ( 'INSERT', array (
			'event_id' 		=> $this->event_id,
			'raid_start' 	=> $this->raid_start,
			'raid_end' 		=> $this->raid_end,
			'raid_note' 	=> $this->raid_note,
			'raid_added_by' => $this->raid_added_by)
		);

		$db->sql_query ( "INSERT INTO " . RAIDS_TABLE . $query );
		$this->raid_id = $db->sql_nextid();
		return 	$this->raid_id;

	}

	/**
	 * deletes a raid from database
	 */
	public function Delete()
	{
		global $db;
		$db->sql_query ('DELETE FROM ' . RAIDS_TABLE . " WHERE raid_id= " . (int) $this->raid_id);
	}

	public function update()
	{
		global $user, $db;
		// Update the raid
		$query = $db->sql_build_array ( 'UPDATE', array (
			'event_id' 			=> (int) $this->event_id,
			'raid_start' 		=> $this->raid_start,
			'raid_end' 			=> $this->raid_end,
			'raid_note' 		=> $this->raid_note,
			'raid_updated_by' 	=> $user->data ['username'] ) );

		$db->sql_query ( 'UPDATE ' . RAIDS_TABLE . ' SET ' . $query . " WHERE raid_id = " . (int) $this->raid_id );
	}

	/**
	 * get general raid info
	 */
	public function Get()
	{
		global $db;

		$sql_array = array (
				'SELECT' => ' d.dkpsys_name, e.event_dkpid, e.event_id, e.event_name, e.event_value,
							r.raid_id, r.raid_start, r.raid_end, r.raid_note,
							r.raid_added_by, r.raid_updated_by ',
				'FROM' => array (
						DKPSYS_TABLE 		=> 'd' ,
						RAIDS_TABLE 		=> 'r' ,
						EVENTS_TABLE 		=> 'e',
				),
				'WHERE' => " d.dkpsys_id = e.event_dkpid and r.event_id = e.event_id and r.raid_id=" . (int) $this->raid_id,
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		$row = $db->sql_fetchrow ( $result );

		if($row)
		{
			$this->dkpsys_name 		= $row['dkpsys_name'];
			$this->event_dkpid 		= $row['event_dkpid'];
			$this->event_id			= $row['event_id'];
			$this->event_name 		= $row['event_name'];
			$this->event_value 		= $row['event_value'];
			$this->raid_start 		= $row['raid_start'];
			$this->raid_end 		= $row['raid_end'];
			$this->raid_note 		= $row['raid_note'];
			$this->raid_added_by 	= $row['raid_added_by'];
			$this->raid_updated_by 	= $row['raid_updated_by'];
		}
		$db->sql_freeresult ($result);
		unset($row);
	}

	/**
	 * raid list used by acp and viewmember.php
	 * @param string $order
	 * @param int $dkpsys_id
	 * @param int $start
	 * @param int $member_id 
	 */
	public function getRaids($order = 'r.raid_start DESC', $dkpsys_id=0, $start=0, $member_id=0)
	{
		global $config, $db;

		$sql_array = array (
			'SELECT' => ' 
				e.event_dkpid, 
				e.event_name, 
				r.raid_id,  
				r.raid_start, 
				r.raid_end, 
				r.raid_note,
				r.raid_added_by, 
				r.raid_updated_by,
				SUM(ra.raid_value) AS raid_value, 
				SUM(ra.time_bonus) AS time_value,
				SUM(ra.zerosum_bonus) AS zs_value, 
				SUM(ra.raid_decay) AS raiddecay,
				SUM(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay) AS net_earned ',
			'FROM' => array (
					EVENTS_TABLE 		=> 'e',
					RAIDS_TABLE 		=> 'r' ,
					RAID_DETAIL_TABLE	=> 'ra' ,
			),
			'WHERE' => "  ra.raid_id = r.raid_id 
						AND e.event_status = 1 
						AND r.event_id = e.event_id 
						AND e.event_dkpid = " . (int) $dkpsys_id,
			'GROUP_BY' => 'e.event_dkpid, e.event_name,
						r.raid_id,  r.raid_start, r.raid_end, r.raid_note,
						r.raid_added_by, r.raid_updated_by',
			'ORDER_BY' => $order,
		);

		if($member_id > 0)
		{
			$sql_array['WHERE'] .= ' AND ra.member_id=' . $member_id; 
		}
					
		$sql = $db->sql_build_query('SELECT', $sql_array);
		return  $db->sql_query_limit ( $sql, $config ['bbdkp_user_rlimit'], $start );

	}
	
	/**
	* returns raid attendance percentage for a range member/pool
	*  @param $dkpsys_id = int
	*  @param $days = int
	*  @param $member_id = int
	*  @param $mode= 0 -> indiv raidcount, 1 -> total rc, 2 -> attendancepct
	*  @param $all : if true then get count forever, otherwise since x days
	* used by listmembers.php and viewmember.php
	*
	*/
	public function raidcount($dkpsys_id, $days, $member_id=0, $mode=1, $all = false)
	{
		$start_date = mktime(0, 0, 0, date('m'), date('d')-$days, date('Y'));
		// member joined in the last $days ?

		if($member_id > 0)
		{
			$attendee = new \bbdkp\Members($member_id);
			$joindate = $attendee->get_joindate($member_id);
			if ($all==true || $joindate > $start_date)
			{
				// then count from join date
				$start_date = $joindate;
			}
			unset($attendee);
		}

		$end_date = time();

		switch ($mode)
		{
			case 0:
				// get member raidcount
				return $this->getraidcount($start_date, $end_date, $dkpsys_id, $all, $member_id);
				break;

			case 1:
				// get total pool raidcount
				return $this->getraidcount($start_date, $end_date, $dkpsys_id, $all);
				break;

			case 2:
				$memberraidcount = $this->getraidcount($start_date, $end_date, $dkpsys_id, $all, $member_id);
				$raid_count = $this->getraidcount($start_date, $end_date, $dkpsys_id, $all);
				$percent_of_raids = ($raid_count > 0 ) ?  round(($memberraidcount / $raid_count) * 100,2) : 0;
				return (float) $percent_of_raids;
				break;
		}

	}

	/**
	 * calculate raid count for the whole pool between 2 raid dates
	 * no caching
	 *
	 * @param int $start_date first raiddate (join date)
	 * @param int $end_date last raiddate
	 * @param int $dkpsys_id
	 * @param bool $all
	 * @param int $member_id optional
	 * @return number
	 */
	private function getraidcount($start_date, $end_date, $dkpsys_id, $all, $member_id = 0)
	{
		global $db;
		$sql_array = array(
			'SELECT'    => 	' COUNT(*) as raidcount  ',
			'FROM'      => array(
				EVENTS_TABLE			=> 'e',
				RAIDS_TABLE 			=> 'r',
				RAID_DETAIL_TABLE	    => 'ra',
			),
			'WHERE'		=> 'r.event_id = e.event_id AND ra.raid_id = r.raid_id '
		);

		if ($member_id > 0)
		{
			$sql_array['WHERE'] .= ' AND ra.member_id = ' . (int) $member_id;
		}

		if ($all == true)
		{
			$sql_array['WHERE'] .= ' AND r.raid_start >= ' . $start_date;
		}
		else
		{
			$sql_array['WHERE'] .= ' AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date;
		}

		if ($dkpsys_id > 0)
		{
			$sql_array['WHERE'] .= ' AND e.event_dkpid = ' . $dkpsys_id;
		}
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		$raid_count = (int) $db->sql_fetchfield('raidcount');
		$db->sql_freeresult($result);

		return $raid_count;
	}

	/**
	 * counts total raidcount in a dkp pool
	 * @return number
	 */
	public function countraids($dkpsys_id)
	{
			global $db;
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

			return $total_raids;
	}
	
	
	/**
	 * Guild Raid Attendance Statistics
	 *
	 * @param unknown_type $time
	 * @param unknown_type $u_stats
	 * @param unknown_type $guild_id
	 */
	public function attendance_statistics($time, $u_stats, $guild_id, $query_by_pool, $dkp_id, $show_all)
	{
		/* get overall raidcount for 4 intervals */
		global $db, $template, $phpEx, $phpbb_root_path, $config, $user;
	
		$rcall = $this->get_overallraidcount(0, $time,$guild_id, $query_by_pool, $dkp_id);
		$rc90 = $this->get_overallraidcount((int) $config['bbdkp_list_p3'], $time, $guild_id, $query_by_pool, $dkp_id);
		$rc60 = $this->get_overallraidcount((int) $config['bbdkp_list_p2'], $time, $guild_id, $query_by_pool, $dkp_id);
		$rc30 = $this->get_overallraidcount((int) $config['bbdkp_list_p1'], $time, $guild_id, $query_by_pool, $dkp_id);
	
		$att_sort_order = array (
				0 => array ("sum(CASE e.days WHEN 'lifetime' THEN e.attendance END ) DESC", "sum(CASE e.days WHEN 'lifetime' THEN e.attendance END ) ASC" ),
				1 => array ("sum(CASE e.days WHEN '".$config['bbdkp_list_p3']."' THEN e.attendance END ) desc", "sum(CASE e.days WHEN '".$config['bbdkp_list_p3']."' THEN e.attendance END ) asc" ),
				2 => array ("sum(CASE e.days WHEN '".$config['bbdkp_list_p2']."' THEN e.attendance END ) desc", "sum(CASE e.days WHEN '".$config['bbdkp_list_p2']."' THEN e.attendance END ) asc" ),
				3 => array ("sum(CASE e.days WHEN '".$config['bbdkp_list_p1']."' THEN e.attendance END ) desc", "sum(CASE e.days WHEN '".$config['bbdkp_list_p1']."' THEN e.attendance END ) asc" ),
				4 => array ("e.member_name asc", "e.member_name desc" ),
		);
	
		$att_current_order = $this->switch_order ( $att_sort_order );
	
		/** attendance SQL */
		$sql = "SELECT
			c.game_id, c.colorcode,  c.imagename,
			e.event_dkpid,
			e.member_name,
			e.member_id,
			e.member_firstraid,
			e.member_lastraid,
			sum(CASE e.days WHEN 'lifetime' THEN e.gloraidcount END) AS gloraidcountlife,
			sum(CASE e.days WHEN 'lifetime' THEN e.iraidcount END ) AS iraidcountlife,
			sum(CASE e.days WHEN 'lifetime' THEN e.attendance END ) AS attendancelife,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p3']."' THEN e.gloraidcount END ) AS gloraidcount90,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p3']."' THEN e.iraidcount END ) AS iraidcount90,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p3']."' THEN e.attendance END) AS attendance90,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p2']."' THEN e.gloraidcount END ) AS gloraidcount60,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p2']."' THEN e.iraidcount END) AS iraidcount60,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p2']."' THEN e.attendance END ) AS attendance60,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p1']."' THEN e.gloraidcount END ) AS gloraidcount30,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p1']."' THEN e.iraidcount END ) AS iraidcount30,
			sum(CASE e.days WHEN '".$config['bbdkp_list_p1']."' THEN e.attendance END ) AS attendance30
		FROM
			(
				SELECT
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id,
					'lifetime' AS days,
					count(rd.member_id) AS iraidcount,
					". (string) $rcall . " AS gloraidcount,
					round(count(rd.member_id) / " . (string) $rcall . " * 100,2) AS attendance
				FROM
					" . MEMBER_LIST_TABLE . " l,
					" . MEMBER_DKP_TABLE ." d,
					" . RAID_DETAIL_TABLE . " rd,
					" . EVENTS_TABLE . " ev,
					" . RAIDS_TABLE . " r
				WHERE rd.member_id = d.member_id
				AND ev.event_dkpid = d.member_dkpid";
		if ($query_by_pool)
		{
			$sql .= " AND d.member_dkpid = " . $dkp_id;
		}
		if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
		{
			$sql .= " AND d.member_status='1'";
		}
		$sql .= "
				AND ev.event_id = r.event_id
				AND r.raid_id = rd.raid_id
				AND l.member_id = rd.member_id AND l.member_guild_id = " . $guild_id . "
				AND l.member_joindate < r.raid_start
				GROUP BY
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id
			UNION ALL
				SELECT
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id,
					'". (int) $config['bbdkp_list_p3'] ."' AS days,
					count(rd.member_id) AS iraidcount,
					". (string) $rc90 . " AS gloraidcount,
					round(count(rd.member_id)/ ". (string) $rc90 . " * 100,2) AS attendance
					FROM
						" . MEMBER_LIST_TABLE . " l,
						" . MEMBER_DKP_TABLE ." d,
						" . RAID_DETAIL_TABLE . " rd,
						" . EVENTS_TABLE . " ev,
						" . RAIDS_TABLE . " r
					WHERE
						rd.member_id = d.member_id
					AND ev.event_dkpid = d.member_dkpid ";
		if ($query_by_pool)
		{
			$sql .= " AND d.member_dkpid = " . $dkp_id;
		}
		$sql .= " AND ev.event_id = r.event_id
					AND r.raid_id = rd.raid_id
					AND( - r.raid_start + " . $time . "  )/(3600 * 24) < ". (int) $config['bbdkp_list_p3'];
		if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
		{
			$sql .= " AND d.member_status='1'";
		}
		$sql .= "
					AND l.member_id = rd.member_id AND l.member_guild_id = " . $guild_id . "
					AND l.member_joindate < r.raid_start
					GROUP BY
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id
			UNION ALL
				SELECT
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id,
					'". (int) $config['bbdkp_list_p2'] ."' AS days,
					count(rd.member_id) AS iraidcount,
					". (string) $rc60 . " AS gloraidcount,
					round( count(rd.member_id)/ ". (string) $rc60 . " * 100, 2 ) AS attendance
				FROM
					" . MEMBER_LIST_TABLE . " l,
					" . MEMBER_DKP_TABLE ." d,
					" . RAID_DETAIL_TABLE . " rd,
					" . EVENTS_TABLE . " ev,
					" . RAIDS_TABLE . " r
				WHERE
					rd.member_id = d.member_id
				AND ev.event_dkpid = d.member_dkpid";
		if ($query_by_pool)
		{
			$sql .= " AND d.member_dkpid = " . $dkp_id;
		}
		$sql .= " AND ev.event_id = r.event_id
				AND r.raid_id = rd.raid_id
				AND(  - r.raid_start + " . $time . " ) /(3600 * 24) < ". (int) $config['bbdkp_list_p2'];
		if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
		{
			$sql .= " AND d.member_status='1'";
		}
		$sql .= " AND l.member_id = rd.member_id AND l.member_guild_id = " . $guild_id . "
				AND l.member_joindate < r.raid_start
				GROUP BY
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id
			UNION ALL
				SELECT
					d.member_lastraid,
					d.member_firstraid,
					ev.event_dkpid,
					l.member_name,
					rd.member_id,
					'". (int) $config['bbdkp_list_p1'] ."' AS days,
					count(rd.member_id) AS iraidcount,
					'". (string) $rc30 . "' AS gloraidcount,
					round(count(rd.member_id)/ ". (string) $rc30 . " * 100,2) AS attendance
				FROM
					" . MEMBER_LIST_TABLE . " l,
					" . MEMBER_DKP_TABLE ." d,
					" . RAID_DETAIL_TABLE . " rd,
					" . EVENTS_TABLE . " ev,
					" . RAIDS_TABLE . " r
				WHERE
					rd.member_id = d.member_id
				AND ev.event_dkpid = d.member_dkpid";
		if ($query_by_pool)
		{
			$sql .= " AND d.member_dkpid = " . $dkp_id;
		}
		$sql .= " AND ev.event_id = r.event_id
				AND r.raid_id = rd.raid_id
				AND( - r.raid_start + " . $time . " )/(3600 * 24) < ". (int) $config['bbdkp_list_p1'];
		if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
		{
			$sql .= " AND d.member_status='1'";
		}
		$sql .= " AND l.member_id = rd.member_id AND l.member_guild_id = " . $guild_id . "
				AND l.member_joindate < r.raid_start
			GROUP BY
				d.member_lastraid,
				d.member_firstraid,
				ev.event_dkpid,
				l.member_name,
				rd.member_id
		) e INNER JOIN " . MEMBER_LIST_TABLE . " l on  e.member_id = l.member_id
			INNER JOIN " . CLASS_TABLE . " c on c.class_id = l.member_class_id and c.game_id = l.game_id
		GROUP BY
			c.game_id, c.colorcode,  c.imagename,
			e.event_dkpid,
			e.member_name,
			e.member_id,
			e.member_firstraid,
			e.member_lastraid
		ORDER BY " . $att_current_order ['sql'];
	
		$attendance = 0;
		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result))
		{
			$attendance++;
		}
		
		$u_stats = append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=stats&amp;' .URI_GUILD . '=' . $guild_id);
		
		$startatt = request_var ( 'startatt', 0 );
		$result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_llimit'], $startatt );
	
		if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
		{
			$footcount_text = sprintf($user->lang['STATS_ACTIVE_FOOTCOUNT'], $db->sql_affectedrows($result),
					'<a href="' . $u_stats . '&amp;o='.$att_current_order['uri']['current']. '&amp;show=all" class="rowfoot">');
	
			$attpagination = $this->generate_pagination2($u_stats. '&amp;o=' . $att_current_order ['uri'] ['current'] ,
					$attendance, $config ['bbdkp_user_llimit'], $startatt, true, 'startatt'  );
	
		}
	
		else
		{
			$footcount_text = sprintf($user->lang['STATS_FOOTCOUNT'], $db->sql_affectedrows($result),
					'<a href="' . $u_stats. '&amp;o='.$att_current_order['uri']['current'] . '" class="rowfoot">');
	
			$attpagination = $this->generate_pagination2($u_stats . '&amp;o=' . $att_current_order ['uri'] ['current']. '&amp;show=all' ,
					$attendance, $config ['bbdkp_user_llimit'], $startatt, true, 'startatt'  );
	
		}
	
		$attendance=0;
		while ( $row = $db->sql_fetchrow($result) )
		{
	
			$template->assign_block_vars('attendance_row', array(
					'NAME' 					=> $row['member_name'],
					'U_VIEW_MEMBER' 		=> append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=viewmember&amp;' .URI_DKPSYS . '=' . $dkp_id . '&amp;' . URI_NAMEID . '='.$row['member_id']),
					'COLORCODE'				=> $row['colorcode'],
					'ID'            		=> $row['member_id'],
					'FIRSTRAID' 			=> $row['member_firstraid'],
					'LASTRAID' 				=> $row['member_lastraid'],
					'GRCTLIFE' 				=> $row['gloraidcountlife'],
					'IRCTLIFE' 				=> $row['iraidcountlife'],
					'ATTLIFESTR' 			=> sprintf("%.2f%%", $row['attendancelife']),
					'ATTLIFE' 				=> sprintf("%.2f", $row['attendancelife']),
					'GRCT90' 				=> $row['gloraidcount90'],
					'IRCT90' 				=> $row['iraidcount90'],
					'ATT90STR' 				=> sprintf("%.2f%%", $row['attendance90']),
					'ATT90' 				=> sprintf("%.2f", $row['attendance90']),
					'GRCT60' 				=> $row['gloraidcount60'],
					'IRCT60' 				=> $row['iraidcount60'],
					'ATT60STR' 				=> sprintf("%.2f%%", $row['attendance60']),
					'ATT60' 				=> sprintf("%.2f", $row['attendance60']),
					'GRCT30' 				=> $row['gloraidcount30'],
					'IRCT30' 				=> $row['iraidcount30'],
					'ATT30STR' 				=> sprintf("%.2f%%", $row['attendance30']),
					'ATT30' 				=> sprintf("%.2f", $row['attendance30']),
			)
			);
		}
	
		/* send information to template */
		$template->assign_vars(array(
				'RAIDS_X1_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'],  $config['bbdkp_list_p3']),
				'RAIDS_X2_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'],  $config['bbdkp_list_p2']),
				'RAIDS_X3_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'],  $config['bbdkp_list_p1']),
				'O_LIF' 		  => $att_current_order ['uri'] [0],
				'O_90' 			  => $att_current_order ['uri'] [1],
				'O_60' 			  => $att_current_order ['uri'] [2],
				'O_30' 			  => $att_current_order ['uri'] [3],
				'O_MEMBER' 		  => $att_current_order ['uri'] [4],
				'ATTPAGINATION' 	=> $attpagination ,
				'S_DISPLAY_STATS'		=> true,
				'U_STATS' => $u_stats . '&amp;startatt='. $startatt,
				'SHOW' => ( isset($_GET['show']) ) ? request_var('show', '') : '',
				'TOTAL_MEMBERS' 	=> $attendance,
				'ATTEND_FOOTCOUNT' 	=> $footcount_text,
		)
		);
	
	}
	
	/**
	 * gets overall raid count in interval of N days before today
	 * 
	 * @param int $interval
	 * @param int $time
	 * @param int $dkp_id
	 *
	 */
	private function get_overallraidcount($interval, $time, $guild_id, $query_by_pool, $dkp_id)
	{
		global $db;
		// get raidcount
		$sql_array = array (
				'SELECT' => ' count(r.raid_id) AS raidcount ',
				'FROM' => array (
						EVENTS_TABLE => 'e',
						RAIDS_TABLE => 'r',
						RAID_DETAIL_TABLE => 'd',
						MEMBER_LIST_TABLE => 'l'
				),
				'WHERE' => ' e.event_id = r.event_id
						AND d.raid_id = r.raid_id
						AND l.member_id = d.member_id
						AND l.member_guild_id = ' . $guild_id,
				'GROUP_BY' => 'r.raid_id'
		);
	
		if ($query_by_pool)
		{
			$sql_array['WHERE'] .= ' and event_dkpid = '. (int) $dkp_id . ' ';
		}
	
		if ($interval > 0)
		{
			$sql_array['WHERE'] .= " AND ( - r.raid_start + " . (int) $time . " ) / (3600 * 24) < ". (int) $interval;
		}
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query($sql);
		$rc = (int) $db->sql_fetchfield('raidcount');
	
		$db->sql_freeresult($result);
	
		return $rc;
	}

}
?>