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

	public function getRaids($order, $dkpsys_id, $start)
	{
		global $config, $db;

		$sql_array = array (
			'SELECT' => ' sum(ra.raid_value) as raid_value, sum(ra.time_bonus) as time_value,
				sum(ra.zerosum_bonus) as zs_value, sum(ra.raid_decay) as raiddecay,
				sum(ra.raid_value + ra.time_bonus  +ra.zerosum_bonus - ra.raid_decay) as total,
				e.event_dkpid, e.event_name,
				r.raid_id, r.raid_start, r.raid_end, r.raid_note,
				r.raid_added_by, r.raid_updated_by ',
			'FROM' => array (
					RAID_DETAIL_TABLE	=> 'ra' ,
					RAIDS_TABLE 		=> 'r' ,
					EVENTS_TABLE 		=> 'e',
			),
			'WHERE' => "  ra.raid_id = r.raid_id AND e.event_status = 1 AND r.event_id = e.event_id AND e.event_dkpid = " . ( int ) $dkpsys_id,
			'GROUP_BY' => 'e.event_dkpid, e.event_name,
						r.raid_id,  r.raid_start, r.raid_end, r.raid_note,
						r.raid_added_by, r.raid_updated_by',
			'ORDER_BY' => $order,
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		return  $db->sql_query_limit ( $sql, $config ['bbdkp_user_rlimit'], $start );

	}

	/**
	 * returns raid attendance percentage for a range member/pool
	*  @param bool $query_by_pool
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

}
?>