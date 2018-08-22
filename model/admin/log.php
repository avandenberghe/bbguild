<?php
/**
 * Logging class file
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\model\admin;

use avathar\bbguild\model\admin\constants;

/**
 * Singleton Logging class
 *
 * @package bbguild
 */
class log
{
	public $bb_logs_table;
	protected $db;

	/**
	 * number of logs
	*
	 * @var int
	 */
	protected $total_logs;

	/**
	 * logging key-values
	*
	 * @var array
	 */
	public  $valid_action_types = array(
		constants::DKPSYS_ADDED => 'DKPSYS_ADDED' ,
		constants::DKPSYS_UPDATED => 'DKPSYS_UPDATED' ,
		constants::DKPSYS_DELETED =>'DKPSYS_DELETED' ,
		constants::EVENT_ADDED =>'EVENT_ADDED' ,
		constants::EVENT_UPDATED =>'EVENT_UPDATED' ,
		constants::EVENT_DELETED =>'EVENT_DELETED' ,
		constants::HISTORY_TRANSFER =>'HISTORY_TRANSFER' ,
		constants::INDIVADJ_ADDED =>'INDIVADJ_ADDED' ,
		constants::INDIVADJ_UPDATED =>'INDIVADJ_UPDATED' ,
		constants::INDIVADJ_DELETED =>'INDIVADJ_DELETED' ,
		constants::ITEM_ADDED =>'ITEM_ADDED' ,
		constants::ITEM_UPDATED =>'ITEM_UPDATED' ,
		constants::CLASS_UPDATED =>'ITEM_DELETED' ,
		constants::PLAYER_ADDED =>'PLAYER_ADDED' ,
		constants::PLAYER_UPDATED =>'PLAYER_UPDATED' ,
		constants::PLAYER_DELETED =>'PLAYER_DELETED' ,
		constants::RANK_ADDED =>'RANK_ADDED' ,
		constants::RANK_UPDATED =>'RANK_UPDATED' ,
		constants::RANK_DELETED =>'RANK_DELETED' ,
		constants::NEWS_ADDED =>'NEWS_ADDED' ,
		constants::NEWS_UPDATED =>'NEWS_UPDATED' ,
		constants::NEWS_DELETED =>'NEWS_DELETED' ,
		constants::RAID_ADDED =>'RAID_ADDED' ,
		constants::RAID_UPDATED =>'RAID_UPDATED' ,
		constants::RAID_DELETED =>'RAID_DELETED' ,
		constants::ACTION_DELETED =>'ACTION_DELETED' ,
		constants::RT_CONFIG_UPDATED =>'RT_CONFIG_UPDATED' ,
		constants::DECAYSYNC =>'DECAYSYNC' ,
		constants::DECAYOFF =>'DECAYOFF' ,
		constants::ZSYNC =>'ZSYNC' ,
		constants::DKPSYNC =>'DKPSYNC' ,
		constants::DEFAULT_DKP_CHANGED =>'DEFAULT_DKP_CHANGED' ,
		constants::GUILD_ADDED =>'GUILD_ADDED' ,
		constants::PLAYERDKP_UPDATED =>'PLAYERDKP_UPDATED' ,
		constants::CLASS_UPDATED =>'PLAYERDKP_DELETED',
		constants::GAME_ADDED => 'GAME_ADDED',
		constants::GAME_DELETED => 'GAME_DELETED',
		constants::SETTINGS_CHANGED => 'SETTINGS_CHANGED',
		constants::PORTAL_CHANGED => 'PORTAL_CHANGED',
		constants::FACTION_DELETED => 'FACTION_DELETED',
		constants::LOG_DELETED => 'LOG_DELETED',
		constants::FACTION_ADDED => 'FACTION_ADDED',
		constants::RACE_ADDED => 'RACE_ADDED',
		constants::RACE_DELETED => 'RACE_DELETED',
		constants::CLASS_ADDED => 'CLASS_ADDED',
		constants::CLASS_DELETED => 'CLASS_DELETED',
		constants::RACE_UPDATED => 'RACE_UPDATED',
		constants::CLASS_UPDATED => 'CLASS_UPDATED',
		constants::PLAYER_DEACTIVATED => 'PLAYER_DEACTIVATED',
		constants::GUILD_UPDATED => 'GUILD_UPDATED',
		constants::ARMORY_DOWN => 'ARMORY_DOWN',
		constants::FACTION_UPDATED => 'FACTION_UPDATED',
		constants::ROLE_ADDED => 'ROLE_ADDED',
		constants::ROLE_UPDATED => 'ROLE_UPDATED',
		constants::ROLE_DELETED => 'ROLE_DELETED',
		constants::BATTLENET_ACCOUNT_INACTIVE => 'BATTLENET_ACCOUNT_INACTIVE',
	);

	/**
	 * only these tags can be entered in logs
	 * if tags are not in list then it's not logged
	*
	 * @var array
	 */
	private $valid_tags = array(
				'L_NAME' ,
				'L_EARNED_BEFORE' ,
				'L_EARNED_AFTER' ,
				'L_SPENT_BEFORE' ,
				'L_SPENT_AFTER' ,
				'L_REALM_BEFORE',
				'L_REALM_AFTER',
				'L_NAME_BEFORE' ,
				'L_NAME_AFTER',
				'L_RACE_BEFORE',
				'L_RACE_AFTER',
				'L_LEVEL_BEFORE',
				'L_LEVEL_AFTER',
				'L_LEVEL_BEFORE',
				'L_LEVELAFTER',
				'L_RANK_BEFORE',
				'L_RANK_AFTER',
				'L_CLASS_BEFORE',
				'L_CLASS_AFTER',
				'L_GENDER_BEFORE',
				'L_GENDER_AFTER',
				'L_ACHIEV_BEFORE',
				'L_ACHIEV_AFTER',
				'L_ORIGIN',
				'L_REALM',
				'L_RAIDS' ,
				'L_LOG_ID',
				'L_EVENT',
				'L_EVENT_ID',
				'L_EVENT_BEFORE',
				'L_VALUE_BEFORE',
				'L_VALUE_AFTER',
				'L_HEADLINE',
				'L_HEADLINE_BEFORE',
				'L_BUYER',
				'L_BUYERS',
				'L_BUYERS_BEFORE',
				'L_ADJUSTMENT',
				'L_ADJUSTMENT_BEFORE',
				'L_PLAYERS',
				'L_PLAYERS_AFTER',
				'L_FROM',
				'L_TO',
				'L_DKPSYS_NAME',
				'L_DKPSYSNAME_BEFORE',
				'L_DKPSYSNAME_AFTER',
				'L_DKPSYS_STATUS',
				'L_GAME',
				'L_SETTINGS',
				'L_FACTION',
				'L_ADDED_BY',
				'L_UPDATED_BY',
				'L_RACE',
				'L_CLASS',
				'L_LEVEL',
				'L_WRONG_KEY',
				'id',
				'L_RAID_ID',
				'L_VALUE',
				'L_DAYSAGO',
				'L_ROLE',
				'L_GUILD',
				'L_ACTION_BATTLENET_ACCOUNT_INACTIVE'

	);

	public function __construct($bb_logs_table, \phpbb\db\driver\driver_interface $db)
	{
		$this->bb_logs_table = $bb_logs_table;
		$this->db = $db;
	}

	/**
	 * @return int
	 */
	public function getTotalLogs()
	{
		return $this->logcount();
	}

	/**
	 * get this log entry
	 *
	 * @param  int $log_id
	 * @return mixed
	 */
	public function get_logentry($log_id)
	{
		$sql_array = array(
			'SELECT'     => 'l.*, u.username, u.user_id, u.user_colour' ,
			'FROM'         =>     array($this->bb_logs_table => 'l') ,
			'LEFT_JOIN' => array(
				array(
					'FROM' => array(USERS_TABLE => 'u') ,
					'ON' => 'u.user_id=l.log_userid')) ,
			'WHERE' => 'log_id=' . (int) $log_id);
		$total_sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($total_sql);
		$log = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		$log['colouruser'] = get_username_string('full', $log['user_id'], $log['username'], $log['user_colour']);

		$log['log_type'] = str_replace('L_ACTION_', '', $log['log_type']);
		$log['log_type'] = str_replace('L_ERROR_', '', $log['log_type']);
		$log['log_type'] = str_replace('L_', '', $log['log_type']);

		$log['log_action'] = str_replace('L_ACTION_', '', $log['log_action']);
		$log['log_action'] = str_replace('L_ERROR_', '', $log['log_action']);
		$log['log_action'] = str_replace('L_', '', $log['log_action']);

		$log['log_result'] = str_replace('L_', '', $log['log_result']);

		return $log;

	}

	/**
	 * makes an entry in the bbguild log table
	 * log_action is an xml containing the log
	 *
	 * @param  array $values
	 * @return boolean
	 */
	public function log_insert(array $values)
	{
		global $user;
		/**
		 * log_id        int(11)        UNSIGNED    No        auto_increment
		 * log_date      int(11)            No    0
		 * log_type      varchar(255)    utf8_bin        No
		 * log_action    text    utf8_bin        No
		 * log_ipaddress varchar(15)    utf8_bin        No
		 * log_sid       varchar(32)    utf8_bin        No
		 * log_result    varchar(255)    utf8_bin        No
		 * log_userid    mediumint(8)    UNSIGNED    No    0
		 */
		$log_fields = array('log_date', 'log_type', 'log_action', 'log_ipaddress', 'log_sid', 'log_result', 'log_userid');

		/**
		 * //example usage
		 * $log_action = array(
		'header' => ACTION_INDIVADJ_DELETED ,
		'id' => $adjust_id ,
		'L_ADJUSTMENT' => $deleteadj->adjustment_value ,
		'L_REASON' => $deleteadj->adjustment_reason ,
		'L_PLAYERS' =>  $deleteadj->player_name );

		$this->log_insert(array(
		'log_type' => $log_action['header'] ,
		'log_action' => $log_action));
		 */

		// Default our log values
		$defaultlog = array(
			'log_date'      => time(),
			'log_type'      => null,
			'log_action'    => null,
			'log_ipaddress' => $user->ip,
			'log_sid'       => $user->session_id,
			'log_result'    => 'L_SUCCESS',
			'log_userid'    => $user->data['user_id']);

		if (count($values) > 0 )
		{
			// If they set the value, we use theirs, otherwise we use the default
			foreach ($log_fields as $field)
			{
				$values[$field] = ( isset($values[$field]) ) ? $values[$field] : $defaultlog[$field];

				switch ($field)
				{
				case 'log_type':
					$log_type = str_replace('L_ACTION_', '', $values['log_type']);
					if (!in_array($log_type,  (array) $this->valid_action_types))
					{
						$log_type = str_replace('L_ERROR_', '', $values['log_type']);
						if (!in_array($log_type,  (array) $this->valid_action_types))
						{
							//wrong logging type, can't log
							return false;
						}
					}

					break;
				case 'log_action':

					//check log tags
					foreach ($values['log_action'] as $key => $value)
					{
						//check tags but skip the header
						if ($key != 'header')
						{
							if (!in_array($key, (array) $this->valid_tags))
							{
								//wrong logging type
								$key = 'L_WRONG_KEY';
							}
						}

					}

					// serialise log entries
					//$values['log_action'] = json_encode( (array) $values['log_action']);

					// make xml with
					$str_action= '<log>';
					foreach ($values['log_action'] as $key => $value)
					{
						$str_action .= '<' . $key . '>' . $value . '</' . $key . '>';
					}
					$str_action .= '</log>';
					$str_action = substr($str_action, 0, strlen($str_action));
					// Take the newlines and tabs (or spaces > 1) out
					$str_action = preg_replace('/[[:space:]]{2,}/', '', $str_action);
					$str_action = str_replace("\t", '', $str_action);
					$str_action = str_replace("\n", '', $str_action);
					$str_action = preg_replace("#(\\\){1,}#", "\\", $str_action);
					$values['log_action'] = $str_action;
					break;
				}
			}
			$query = $this->db->sql_build_array('INSERT', $values);
			$sql = 'INSERT INTO ' . $this->bb_logs_table . $query;
			$this->db->sql_query($sql);
			return true;
		}
		return false;
	}


	/**
	 * delete log from Database
	 *
	 * @param $marked
	 * @return array
	 */
	public function delete_log($marked)
	{
		//they hit yes
		$sql = 'DELETE FROM ' . $this->bb_logs_table . ' WHERE 1=1 ';
		$sql_in = array();
		foreach ($marked as $mark)
		{
			$sql_in[] = $mark;
		}
		$sql .= ' AND ' . $this->db->sql_in_set('log_id', $sql_in);
		$this->db->sql_query($sql);

		return $sql_in;

	}


	/**
	 * read simple log
	 *
	 * @param string $order
	 * @param bool   $search
	 * @param bool   $verbose
	 * @param string $search_term
	 * @param string $start
	 * @return array
	 */
	public function read_log($order = '', $search = false, $verbose = false, $search_term = '', $start = '')
	{
		global $user;

		$sql_array = array(
		'SELECT' => 'l.*, u.username, u.user_colour ' ,
		'FROM' => array(
			$this->bb_logs_table => 'l' ,
		USERS_TABLE => 'u') ,
		'WHERE' => 'u.user_id=l.log_userid');

		// If they're looking for something specific, we have to figure out what that is
		if ($search)
		{

			// Check if it's a valid log type
			if (in_array($search_term, $this->valid_action_types))
			{
				$sql_array['WHERE'] = " u.user_id=l.log_userid
                    AND ( l.log_type='" . $this->db->sql_escape('L_ACTION_' . $search_term) . "'
                    OR  l.log_type='" . $this->db->sql_escape('L_ERROR_' . $search_term) . "')";
			}
			// Check it's an IP
			else if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $search_term))
			{
				$sql_array['WHERE'] = "  u.user_id=l.log_userid AND l.log_ipaddress='" . $this->db->sql_escape($search_term) . "'";
			}
			// Still going? It's a username
			else if ($search_term != '')
			{
				$sql_array['WHERE'] = " u.user_id=l.log_userid AND u.user_id='" . $this->db->sql_escape($search_term) . "'";
			}
			else
			{
				//empty searchterm, dont add criterium
			}
		}

		if ($verbose == false)
		{
			$sql_array['ORDER_BY'] = $order;
			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query_limit($sql, 30);
		}
		else
		{
			$sql_array['ORDER_BY'] = 'log_id DESC';
			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query_limit($sql, constants::USER_LLIMIT, $start);
		}

		$outlog = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$log = $this->getxmltag($row['log_action']);
			$log_type = str_replace('L_ACTION_', '', $row['log_type']);
			$log_type = str_replace('L_ERROR_', '', $log_type);
			$logline = $this->get_logmessage($log_type, $row['log_action'], $row['log_userid'], $row['username'], $row['user_colour'], $verbose);
			$outlog[] = array(
			'log_id'        => $row['log_id'],
			'datestamp'     => date('d.m.y - H:i:s', $row['log_date']),
			'log_line'         => $logline,
			'log_type'        => $log_type,
			'username'        => get_username_string('full', $row['log_userid'], $row['username'], $row['user_colour']),
			'log_ipaddress'    => $row['log_ipaddress'],
			'log_result'    => $user->lang[str_replace('L_', '', $row['log_result'])],
			'cssresult'        => (str_replace('L_', '', $row['log_result']) == 'SUCCESS') ? 'positive' : 'negative',
			'encoded_type'     => urlencode($row['log_type']) ,
			'encoded_user'     => urlencode($row['username']) ,
			'encoded_ip'     => urlencode($row['log_ipaddress'])
			);
		}

		return $outlog;

	}


	/**
	 * Get error message of a value. It's actually the lang value of the constant's name
	 *
	 * @param  integer $value
	 * @param  boolean $verbose
	 * @return string
	 */
	public function getLogMessage($value, $verbose = false)
	{
		global $user;
		if ($verbose)
		{
			return $user->lang['VLOG_' . $value];
		}
		else
		{
			return $user->lang['ACTION_' . $value];
		}

	}


	/**
	 * Get number of logs
	 *
	 * @return int  total_logs
	 */
	private function logcount()
	{
		$sql6 = 'SELECT count(*) as log_count FROM ' . $this->bb_logs_table;
		$result6 = $this->db->sql_query($sql6);
		$this->total_logs = (int) $this->db->sql_fetchfield('log_count');
		$this->db->sql_freeresult($result6);
		unset($result6);
		return $this->total_logs;

	}


	/**
	 * returns logline
	 *
	 * @param  string $log_type
	 * @param  string $log_action
	 * @param  string $log_userid
	 * @param  string $username
	 * @param  string $user_colour
	 * @param  bool   $verbose
	 * @return string
	 */
	private function get_logmessage($log_type, $log_action, $log_userid, $username, $user_colour, $verbose = false)
	{
		global $user;

		$logline = '';
		$log = $this->getxmltag($log_action);
		$userstring = get_username_string('full', $log_userid, $username, $user_colour);

		switch ( $log_type )
		{
		case 'LOG_DELETED':
			$logline = sprintf($this->getLogMessage('LOG_DELETED', $verbose), $userstring, $log['L_LOG_ID']);
			break;
		case 'DKPSYS_ADDED':
			$logline = sprintf($this->getLogMessage('DKPSYS_ADDED', $verbose), $userstring, $log['L_DKPSYS_NAME'], $log['L_DKPSYS_STATUS']);
			break;
		case 'DKPSYS_UPDATED':
			$logline = sprintf($this->getLogMessage('DKPSYS_UPDATED', $verbose), $userstring, $log['L_DKPSYSNAME_BEFORE'], $log['L_DKPSYSNAME_AFTER']);
			break;
		case 'DKPSYS_DELETED':
			$logline = sprintf($this->getLogMessage('DKPSYS_DELETED', $verbose), $userstring, $log['L_DKPSYS_NAME']);
			break;
		case 'EVENT_ADDED':
			$logline = sprintf($this->getLogMessage('EVENT_ADDED', $verbose), $userstring, $log['L_NAME'], $log['L_VALUE']);
			break;
		case 'EVENT_UPDATED':
			$logline = sprintf($this->getLogMessage('EVENT_UPDATED', $verbose), $userstring, $log['L_NAME_BEFORE']);
			break;
		case 'EVENT_DELETED':
			$logline = sprintf($this->getLogMessage('EVENT_DELETED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'HISTORY_TRANSFER':
			$logline = sprintf($this->getLogMessage('HISTORY_TRANSFER', $verbose), $userstring, $log['L_FROM'], $log['L_TO']);
			break;
		case 'INDIVADJ_ADDED':
			$logline = sprintf($this->getLogMessage('INDIVADJ_ADDED', $verbose), $userstring, $log['L_ADJUSTMENT'], count(explode(',', $log['L_PLAYERS'])), $log['L_PLAYERS']);
			break;
		case 'INDIVADJ_UPDATED':
			$logline = sprintf($this->getLogMessage('INDIVADJ_UPDATED', $verbose), $userstring, $log['L_ADJUSTMENT_BEFORE'], $log['L_PLAYERS_AFTER']);
			break;
		case 'INDIVADJ_DELETED':
			$logline = sprintf($this->getLogMessage('INDIVADJ_DELETED', $verbose), $userstring, $log['L_ADJUSTMENT'], $log['L_PLAYERS']);
			break;
		case 'ITEM_ADDED':
			$logline = sprintf(
				$this->getLogMessage('ITEM_ADDED', $verbose), $userstring,
				isset($log['L_NAME']) ? $log['L_NAME'] : '',
				isset($log['L_BUYERS']) ? $log['L_BUYERS'] : '',
				$log['L_VALUE']
			);
			break;
		case 'ITEM_UPDATED':
			$logline = sprintf($this->getLogMessage('ITEM_UPDATED', $verbose), $userstring, $log['L_NAME_BEFORE'], $log['L_VALUE_AFTER']);
			break;
		case 'ITEM_DELETED':
			$logline = sprintf($this->getLogMessage('ITEM_DELETED', $verbose), $userstring, $log['L_NAME'], (isset($log['L_BUYER']) ? $log['L_BUYER'] : ''), (isset($log['L_VALUE']) ? $log['L_VALUE'] : '0'));
			break;
		case 'PLAYER_ADDED':
			$logline = sprintf($this->getLogMessage('PLAYER_ADDED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'PLAYER_UPDATED':
			$logline = sprintf($this->getLogMessage('PLAYER_UPDATED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'PLAYER_DELETED':
			$logline = sprintf($this->getLogMessage('PLAYER_DELETED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'RANK_DELETED':
			$logline = sprintf($this->getLogMessage('RANK_DELETED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'RANK_ADDED':
			$logline = sprintf($this->getLogMessage('RANK_ADDED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'RANK_UPDATED':
			$logline = sprintf($this->getLogMessage('RANK_UPDATED', $verbose), $userstring, $log['L_NAME_BEFORE'], $log['L_NAME_AFTER']);
			break;
		case 'NEWS_ADDED':
			$logline = sprintf($this->getLogMessage('NEWS_ADDED', $verbose), $userstring, $log['L_HEADLINE']);
			break;
		case 'NEWS_UPDATED':
			$logline = sprintf($this->getLogMessage('NEWS_UPDATED', $verbose), $userstring, $log['L_HEADLINE_BEFORE']);
			break;
		case 'NEWS_DELETED':
			$logline = sprintf($this->getLogMessage('NEWS_DELETED', $verbose), $userstring, $log['L_HEADLINE']);
			break;
		case 'RAID_ADDED':
			$logline = sprintf($this->getLogMessage('RAID_ADDED', $verbose), $userstring, $log['L_EVENT']);
			break;
		case 'RAID_UPDATED':
			$logline = sprintf($this->getLogMessage('RAID_UPDATED', $verbose), $userstring, $log['L_EVENT_BEFORE']);
			break;
		case 'RAID_DELETED':
			$logline = sprintf($this->getLogMessage('RAID_DELETED', $verbose), $userstring, $log['L_EVENT']);
			break;
		case 'RT_CONFIG_UPDATED':
			$logline = sprintf($this->getLogMessage('RT_CONFIG_UPDATED', $verbose), $userstring);
			break;
		case 'DECAYSYNC':

			$origin = '';
			if (isset($log['L_ORIGIN']))
			{
				$origin = $log['L_ORIGIN'];
			}

			$logline = sprintf($this->getLogMessage('DECAYSYNC', $verbose), $userstring, isset($log['L_RAIDS']) ? $log['L_RAIDS'] : 0) . ' ' . $origin;
			break;
		case 'DECAYOFF':

			$origin = '';
			if (isset($log['L_ORIGIN']))
			{
				$origin = $log['L_ORIGIN'];
			}

			$logline = sprintf($this->getLogMessage('DECAYOFF', $verbose), $userstring, $origin);
			break;
		case 'ZSYNC':
			$origin = '';
			if (isset($log['L_ORIGIN']))
			{
				$origin = $log['L_ORIGIN'];
			}

			$logline = sprintf($this->getLogMessage('ZSYNC', $verbose), $userstring, isset($log['L_RAIDS']) ? $log['L_RAIDS'] :0) . ' '. $origin;
			break;
		case 'DKPSYNC':
			$origin = '';
			if (isset($log['L_ORIGIN']))
			{
				$origin = $log['L_ORIGIN'];
			}

			$logline = sprintf($this->getLogMessage('DKPSYNC', $verbose), $userstring) . ' '. $origin;
			break;
		case 'DEFAULT_DKP_CHANGED':
			$origin = '';
			if (isset($log['L_ORIGIN']))
			{
				$origin = $log['L_ORIGIN'];
			}

			$logline = sprintf($this->getLogMessage('DEFAULT_DKP_CHANGED', $verbose), $userstring, $origin);
			break;
		case 'GUILD_ADDED':
			$logline = sprintf($this->getLogMessage('GUILD_ADDED', $verbose), $userstring, $log['L_REALM'] . '-' . $log['L_NAME']);
			break;
		case 'GUILD_UPDATED':
			$logline = sprintf(
				$this->getLogMessage('GUILD_UPDATED', $verbose), $userstring, $log['L_REALM_BEFORE'] . '-' .
				$log['L_NAME_BEFORE'], $log['L_REALM_AFTER'] . '-' . $log['L_NAME_AFTER']
			);
			break;
		case 'GUILD_DELETED':
			$logline = sprintf($this->getLogMessage('GUILD_DELETED', $verbose), $userstring, $log['L_NAME']);
			break;
		case 'PLAYERDKP_UPDATED':
			$logline = sprintf(
				$this->getLogMessage('PLAYERDKP_UPDATED', $verbose), $userstring, $log['L_NAME'],
				$log['L_EARNED_BEFORE'], $log['L_EARNED_AFTER'], $log['L_SPENT_BEFORE'], $log['L_SPENT_AFTER']
			);
			break;
		case 'GAME_ADDED':
			$logline = sprintf($this->getLogMessage('GAME_ADDED', $verbose), $userstring, isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'GAME_DELETED':
			$logline = sprintf($this->getLogMessage('GAME_DELETED', $verbose), $userstring, isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'SETTINGS_CHANGED':
			$logline = sprintf($this->getLogMessage('SETTINGS_CHANGED', $verbose), $userstring, $log['L_SETTINGS']);
			break;
		case 'FACTION_ADDED':
			$logline = sprintf($this->getLogMessage('FACTION_ADDED', $verbose), $userstring, $log['L_FACTION'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'FACTION_DELETED':
			$logline = sprintf($this->getLogMessage('FACTION_DELETED', $verbose), $userstring, $log['L_FACTION'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'RACE_ADDED':
			$logline = sprintf($this->getLogMessage('RACE_ADDED', $verbose), $userstring, $log['L_RACE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'RACE_DELETED':
			$logline = sprintf($this->getLogMessage('RACE_DELETED', $verbose), $userstring, $log['L_RACE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'RACE_UPDATED':
			$logline = sprintf($this->getLogMessage('RACE_UPDATED', $verbose), $userstring, $log['L_RACE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'CLASS_ADDED':
			$logline = sprintf($this->getLogMessage('CLASS_ADDED', $verbose), $userstring, $log['L_CLASS'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'CLASS_DELETED':
			$logline = sprintf($this->getLogMessage('CLASS_DELETED', $verbose), $userstring, $log['L_CLASS'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'CLASS_UPDATED':
			$logline = sprintf($this->getLogMessage('CLASS_UPDATED', $verbose), $userstring, $log['L_CLASS'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'PLAYER_DEACTIVATED':
			$logline = sprintf($this->getLogMessage('PLAYER_DEACTIVATED', $verbose), $userstring, $log['L_NAME'], '');
			break;
		case 'ARMORY_DOWN':
			$logline = sprintf($this->getLogMessage('ARMORY_DOWN', $verbose), $userstring, ' ', ' ');
			break;
		case 'BATTLENET_ACCOUNT_INACTIVE':
			$logline = sprintf($this->getLogMessage('BATTLENET_ACCOUNT_INACTIVE', $verbose), $userstring, isset($user->lang[strtoupper($log['L_GUILD'])]) ? $user->lang[strtoupper($log['L_GUILD'])] : $log['L_GUILD'], ' ');
			break;
		case 'FACTION_UPDATED':
			$logline = sprintf($this->getLogMessage('FACTION_UPDATED', $verbose), $userstring, $log['L_FACTION'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'ROLE_ADDED':
			$logline = sprintf($this->getLogMessage('ROLE_ADDED', $verbose), $userstring, $log['L_ROLE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'ROLE_UPDATED':
			$logline = sprintf($this->getLogMessage('ROLE_UPDATED', $verbose), $userstring, $log['L_ROLE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		case 'ROLE_DELETED':
			$logline = sprintf($this->getLogMessage('ROLE_DELETED', $verbose), $userstring, $log['L_ROLE'], isset($user->lang[strtoupper($log['L_GAME'])]) ? $user->lang[strtoupper($log['L_GAME'])] : $log['L_GAME']);
			break;
		}

		return $logline;

	}


	/**
	 * returns log tags from xml
	 *
	 * @param $haystack
	 * @return array
	 */
	private function getxmltag($haystack)
	{
		$found = array();
		$array_temp = (array) @simplexml_load_string($haystack);
		foreach ($array_temp as $tag => $value)
		{
			if (in_array($tag, $this->valid_tags))
			{
				$found[$tag] = $value;
			}
		}
		return $found;
	}







}
