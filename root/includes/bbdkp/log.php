<?php
namespace bbdkp;
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
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
require_once ("{$phpbb_root_path}includes/bbdkp/guilds/iGuilds.$phpEx");

/**
 * Singleton bbDKP Logging class
 * @author Nix
 *
 */
class log
{
	/**
	 * number of logs
	 * @var unknown_type
	 */
	protected $total_logs;
	
	const DKPSYS_ADDED = 1;
	const DKPSYS_UPDATED = 2;
	const DKPSYS_DELETED = 3; 
	const EVENT_ADDED = 4;
	const EVENT_UPDATED = 5;
	const EVENT_DELETED = 6; 
	const HISTORY_TRANSFER = 7; 
	const INDIVADJ_ADDED = 8; 
	const INDIVADJ_UPDATED = 9; 
	const INDIVADJ_DELETED = 10; 
	const ITEM_ADDED = 11; 
	const ITEM_UPDATED = 12; 
	const ITEM_DELETED= 13; 
	const MEMBER_ADDED = 14; 
	const MEMBER_UPDATED = 15; 
	const MEMBER_DELETED = 16; 
	const RANK_ADDED = 17; 
	const RANK_UPDATED = 18; 
	const RANK_DELETED = 19; 
	const NEWS_ADDED = 20; 
	const NEWS_UPDATED = 21; 
	const NEWS_DELETED = 22; 
	const RAID_ADDED = 23; 
	const RAID_UPDATED = 24; 
	const RAID_DELETED = 25; 
	const ACTION_DELETED = 26; 
	const RT_CONFIG_UPDATED = 27; 
	const DECAYSYNC = 28; 
	const DECAYOFF = 29; 
	const ZSYNC = 30; 
	const DKPSYNC = 31; 
	const DEFAULT_DKP_CHANGED = 32; 
	const GUILD_ADDED = 33; 
	const MEMBERDKP_UPDATED = 34; 
	const MEMBERDKP_DELETED = 35; 
	
	private static $instance;
	
	private $valid_action_types = array(
			1 => 'DKPSYS_ADDED' ,
			2 => 'DKPSYS_UPDATED' ,
			3 =>'DKPSYS_DELETED' ,
			4 =>'EVENT_ADDED' ,
			5 =>'EVENT_UPDATED' ,
			6 =>'EVENT_DELETED' ,
			7 =>'HISTORY_TRANSFER' ,
			8 =>'INDIVADJ_ADDED' ,
			9 =>'INDIVADJ_UPDATED' ,
			10 =>'INDIVADJ_DELETED' ,
			11 =>'ITEM_ADDED' ,
			12 =>'ITEM_UPDATED' ,
			13 =>'ITEM_DELETED' ,
			14 =>'MEMBER_ADDED' ,
			15 =>'MEMBER_UPDATED' ,
			16 =>'MEMBER_DELETED' ,
			17 =>'RANK_ADDED' ,
			18 =>'RANK_UPDATED' ,
			19 =>'RANK_DELETED' ,
			20 =>'NEWS_ADDED' ,
			21 =>'NEWS_UPDATED' ,
			22 =>'NEWS_DELETED' ,
			23 =>'RAID_ADDED' ,
			24 =>'RAID_UPDATED' ,
			25 =>'RAID_DELETED' ,
			26 =>'ACTION_DELETED' ,
			27 =>'RT_CONFIG_UPDATED' ,
			28 =>'DECAYSYNC' ,
			29 =>'DECAYOFF' ,
			30 =>'ZSYNC' ,
			31 =>'DKPSYNC' ,
			32 =>'DEFAULT_DKP_CHANGED' ,
			33 =>'GUILD_ADDED' ,
			34 =>'MEMBERDKP_UPDATED' ,
			35 =>'MEMBERDKP_DELETED');
	
	/**
	 * only these tags can be entered in logs
	 * @var unknown_type
	 */
	private $valid_tags = array('L_NAME' ,'L_EARNED_BEFORE' ,'L_EARNED_AFTER' ,'L_SPENT_BEFORE' , 
				'L_REALM_BEFORE', 'L_NAME_BEFORE' , 'L_REALM_AFTER', 'L_NAME_AFTER', 'L_ORIGIN', 'L_REALM', 'L_RAIDS' , 'L_LOG_ID', 
				'L_EVENT', 'L_EVENT_BEFORE', 'L_HEADLINE', 'L_HEADLINE_BEFORE', 
				'L_BUYERS', 'L_BUYERS_BEFORE', 'L_ADJUSTMENT', 'L_ADJUSTMENT_BEFORE', 
				'L_MEMBERS', 'L_MEMBERS_AFTER', 'L_FROM', 'L_TO', 'L_DKPSYS_NAME', 
				'L_DKPSYSNAME_BEFORE', 'L_DKPSYSNAME_AFTER', 'L_DKPSYS_STATUS');
	
	/**
	 * Call this method to get singleton log instance
	 *
	 * @return UserFactory
	 */
	public static function Instance()
	{
		if (!isset(self::$instance)) 
		{
            
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
        
	}
	
	public function __clone()
	{
		//cloning not allowed
		global $user; 
		trigger_error($user->lang['ERROR'], E_USER_ERROR);
	}
	
	public function __wakeup()
	{
		//cannot deserialise
		global $user;
		trigger_error($user->lang['ERROR'], E_USER_ERROR);
	}
	
	/**
	 * nobody else can instance this
	 */
	private function __construct()
	{
		
		
	}
	
	/**
	 *
	 * @param string $fieldName
	 */
	public function __get($fieldName)
	{
		switch ($fieldName)
		{
			case 'total_logs':
				return  $this->logcount();
		}
		
	}
	

	private function logcount()
	{
		global $db; 
		$sql6 = 'SELECT count(*) as log_count FROM ' . LOGS_TABLE;
		$result6 = $db->sql_query($sql6);
		$this->total_logs = (int) $db->sql_fetchfield('log_count');
		$db->sql_freeresult($result6);
		unset($result6); 
		return $this->total_logs; 
		
	}
	/**
	 * makes an entry in the bbdkp log table
	 * log_action is an xml containing the log
	 * 
	 * @param unknown_type $values
	 * @return boolean
	 */
	public static function log_insert($values = array())
	{
		global $db, $user;
		/**
		 *
		 * log_id	int(11)		UNSIGNED	No		auto_increment
		 * log_date	int(11)			No	0
		 * log_type	varchar(255)	utf8_bin		No
		 * log_action	text	utf8_bin		No
		 * log_ipaddress	varchar(15)	utf8_bin		No
		 * log_sid	varchar(32)	utf8_bin		No
		 * log_result	varchar(255)	utf8_bin		No
		 * log_userid	mediumint(8)	UNSIGNED	No	0
		 */
		$log_fields = array('log_date', 'log_type', 'log_action', 'log_ipaddress', 'log_sid', 'log_result', 'log_userid');
	
		/**
		 * example
		 * $log_action = array(
				'header' => ACTION_INDIVADJ_DELETED , 
				'id' => $adjust_id , 
				'L_ADJUSTMENT' => $deleteadj->adjustment_value , 
				'L_REASON' => $deleteadj->adjustment_reason , 
				'L_MEMBERS' =>  $deleteadj->member_name );
						
			$this->log_insert(array(
				'log_type' => $log_action['header'] , 
				'log_action' => $log_action));
		*/
	
		// Default our log values
		$defaultlog = array(
				'log_date'      => time(),
				'log_type'      => NULL,
				'log_action'    => NULL,
				'log_ipaddress' => $user->ip,
				'log_sid'       => $user->session_id,
				'log_result'    => 'L_SUCCESS',
				'log_userid'    => $user->data['user_id']);
	
		if ( sizeof($values) > 0 )
		{
			// If they set the value, we use theirs, otherwise we use the default
			foreach ( $log_fields as $field )
			{
				$values[$field] = ( isset($values[$field]) ) ? $values[$field] : $defaultlog[$field];
				
				switch ($field)
				{
					case 'log_type': 
						if (!array_key_exists($values['log_type'], $this->valid_action_types))
						{
							//wrong logging type
							return false;
						}
					case 'log_action': 
						
						//check log tags
						foreach ( $values['log_action'] as $key => $value )
						{
							if (!array_key_exists($value, $this->valid_tags))
							{
								//wrong logging type
								return false;
							}
								
						}
						
						// serialise log entries
						//$values['log_action'] = json_encode( (array) $values['log_action']);
						
						// make xml with 
						$str_action="<log>";
						foreach ( $values['log_action'] as $key => $value )
						{
							$str_action .= "<" . $key . ">" . $value . "</" . $key . ">";
						}
						$str_action .="</log>";
						$str_action = substr($str_action, 0, strlen($str_action));
						// Take the newlines and tabs (or spaces > 1) out
						$str_action = preg_replace("/[[:space:]]{2,}/", '', $str_action);
						$str_action = str_replace("\t", '', $str_action);
						$str_action = str_replace("\n", '', $str_action);
						$str_action = preg_replace("#(\\\){1,}#", "\\", $str_action);
						$values['log_action'] = $str_action;
						
						
				}
			}
			$query = $db->sql_build_array('INSERT', $values);
			$sql = 'INSERT INTO ' . LOGS_TABLE . $query;
			$db->sql_query($sql);
			return true;
		}
		return false;
	}
	
	/**
	 * read simple log
	 * 
	 * @param string $order
	 * @param string $search
	 * @param bool $verbose
	 * @return array $outlog
	 */
	public function read_log($order= '', $search = false, $verbose = false, $search_term = '', $start = '')
	{
		global $user, $db; 
		
		$sql_array = array(
				'SELECT' => 'l.*, u.username, u.user_colour ' ,
				'FROM' => array(
						LOGS_TABLE => 'l' ,
						USERS_TABLE => 'u') ,
				'WHERE' => 'u.user_id=l.log_userid');
		
		// If they're looking for something specific, we have to figure out what that is
		if ($search)
		{
			
			// Check if it's an action
			if (array_search($search_term, $this->valid_action_types))
			{
				$sql_array['WHERE'] = " u.user_id=l.log_userid AND l.log_type='" . $db->sql_escape(array_search($search_term, $this->valid_action_types)) . "'";
			}
			// Check it's an IP
			elseif (preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $search_term))
			{
				$sql_array['WHERE'] = "  u.user_id=l.log_userid AND l.log_ipaddress='" . $db->sql_escape($search_term) . "'";
			}
			// Still going? It's a username
			elseif ($search_term != '')
			{
				$sql_array['WHERE'] = " u.user_id=l.log_userid AND u.user_id='" . $db->sql_escape($search_term) . "'";
			}
			else
			{
				//empty searchterm, dont add criterium
			}
		}
		
		if($verbose == false)
		{
			$sql_array['ORDER_BY'] = $order;
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query_limit($sql, 30);
		}
		else
		{
			$sql_array['ORDER_BY'] = 'log_id DESC';
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query_limit($sql, USER_LLIMIT, $start);
		}
		
		while ($row = $db->sql_fetchrow($result))
		{
			//$log = json_decode( $row['log_action']);
			$log = $this->getxmltag($row['log_action']);
			$log_type = str_replace( 'L_ACTION_', '', $row['log_type']);
			$logline = $this->get_logmessage($log_type, $row['log_action'], $row['log_userid'], $row['username'], $row['user_colour'], $verbose);
			$outlog[] = array( 
				'log_id' 		=> $row['log_id'], 
				'datestamp' 	=> date('d.m.y - H:i:s', $row['log_date']), 
				'log_line' 		=> $logline, 
				'log_type'		=> $log_type, 
				'username'		=> get_username_string('full', $row['log_userid'], $row['username'], $row['user_colour']), 
				'log_ipaddress'	=> $row['log_ipaddress'],
				'log_result'	=> $user->lang[str_replace( 'L_', '', $row['log_result'])],
				'cssresult'		=> (str_replace( 'L_', '', $row['log_result']) == 'SUCCESS') ? 'positive' : 'negative',
				'encoded_type' 	=> urlencode($row['log_type']) ,
				'encoded_user' 	=> urlencode($row['username']) ,
				'encoded_ip' 	=> urlencode($row['log_ipaddress'])
			);
		}
		
		return $outlog;
		
	}
	
	
	/**
	 * returns logline
	 */
	private function get_logmessage($log_type, $log_action, $log_userid, $username, $user_colour, $verbose = false)
	{
			//$log = json_decode($log_action);
			$logline = '';
			$log = $this->getxmltag($log_action);
			$userstring = get_username_string('full', $log_userid, $username, $user_colour);		
				
			switch ( $log_type )
			{
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
					$logline = sprintf($this->getLogMessage('HISTORY_TRANSFER', $verbose), $userstring, $log['L_FROM'] , $log['L_TO'] );
					break;
				case 'INDIVADJ_ADDED':
					$logline = sprintf($this->getLogMessage('INDIVADJ_ADDED', $verbose), $userstring, $log['L_ADJUSTMENT'], count($log['L_MEMBERS']), $log['L_MEMBERS']);
					break;
				case 'INDIVADJ_UPDATED':
					$logline = sprintf($this->getLogMessage('INDIVADJ_UPDATED', $verbose), $userstring, $log['L_ADJUSTMENT_BEFORE'], $log['L_MEMBERS_AFTER']);
					break;
				case 'INDIVADJ_DELETED':
					$logline = sprintf($this->getLogMessage('INDIVADJ_DELETED', $verbose), $userstring, $log['L_ADJUSTMENT'], $log['L_MEMBERS']);
					break;
				case 'ITEM_ADDED':
					$logline = sprintf($this->getLogMessage('ITEM_ADDED', $verbose), $userstring, $log['L_ADJUSTMENT'], $log['L_MEMBERS'], count( explode(', ', $log['L_BUYERS'])), $log['L_VALUE']  );
					break;
				case 'ITEM_UPDATED':
					$logline = sprintf($this->getLogMessage('ITEM_UPDATED', $verbose), $userstring, $log['L_NAME_BEFORE'], count(explode(', ', $log['L_BUYERS_BEFORE'] )));
					break;
				case 'ITEM_DELETED':
					$logline = sprintf($this->getLogMessage('ITEM_DELETED', $verbose), $userstring, $log['L_NAME'], count(explode(', ', $log['L_BUYERS'] )));
					break;
				case 'MEMBER_ADDED':
					$logline = sprintf($this->getLogMessage('MEMBER_ADDED', $verbose), $userstring, $log['L_NAME']);
					break;
				case 'MEMBER_UPDATED':
					$logline = sprintf($this->getLogMessage('MEMBER_UPDATED', $verbose), $userstring, $log['L_NAME']);
					break;
				case 'MEMBER_DELETED':
					$logline = sprintf($this->getLogMessage('MEMBER_DELETED', $verbose), $userstring, $log['L_NAME']);
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
					$logline = sprintf($this->getLogMessage('NEWS_ADDED', $verbose), $userstring, $log['L_HEADLINE'] );
					break;
				case 'NEWS_UPDATED':
					$logline = sprintf($this->getLogMessage('NEWS_UPDATED', $verbose), $userstring, $log['L_HEADLINE_BEFORE'] );
					break;
				case 'NEWS_DELETED':
					$logline = sprintf($this->getLogMessage('NEWS_DELETED', $verbose), $userstring, $log['L_HEADLINE'] );
					break;
				case 'RAID_ADDED':
					$logline = sprintf($this->getLogMessage('RAID_ADDED', $verbose), $userstring, $log['L_EVENT'] );
					break;
				case 'RAID_UPDATED':
					$logline = sprintf($this->getLogMessage('RAID_UPDATED', $verbose), $userstring, $log['L_EVENT_BEFORE'] );
					break;
				case 'RAID_DELETED':
					$logline = sprintf($this->getLogMessage('RAID_DELETED', $verbose), $userstring, $log['L_EVENT'] );
					break;
				case 'LOG_DELETED':
					$logline = sprintf($this->getLogMessage('LOG_DELETED', $verbose), $userstring, $log['L_LOG_ID'] );
					break;
				case 'RT_CONFIG_UPDATED':
					$logline = sprintf($this->getLogMessage('RT_CONFIG_UPDATED', $verbose), $userstring );
					break;
				case 'DECAYSYNC':
					$logline = sprintf($this->getLogMessage('DECAYSYNC', $verbose), $userstring, $log['L_RAIDS'] ) . ' ' . $log['L_ORIGIN'] ;
					break;
				case 'DECAYOFF':
					$logline = sprintf($this->getLogMessage('DECAYOFF', $verbose), $userstring, $log['L_ORIGIN']);
					break;
				case 'ZSYNC':
					$logline = sprintf($this->getLogMessage('ZSYNC', $verbose), $userstring, $log['L_RAIDS'] ) . ' '. $log['L_ORIGIN'];
					break;
				case 'DKPSYNC':
					$logline = sprintf($this->getLogMessage('DKPSYNC', $verbose), $userstring) . ' '. $log['L_ORIGIN'];
					break;
				case 'DEFAULT_DKP_CHANGED':
					$logline = sprintf($this->getLogMessage('DEFAULT_DKP_CHANGED', $verbose), $userstring , $log['L_ORIGIN']) ;
					break;
				case 'GUILD_ADDED':
					$logline = sprintf($this->getLogMessage('GUILD_ADDED', $verbose), $userstring , $log['L_REALM'] . '-' . $log['L_NAME'] ) ;
					break;
				case 'GUILD_UPDATED':
					$logline = sprintf($this->getLogMessage('GUILD_UPDATED', $verbose), $userstring, $log['L_REALM_BEFORE'] . '-' .
							$log['L_NAME_BEFORE'], $log['L_REALM_AFTER'] . '-' . $log['L_NAME_AFTER'] );
							break;
				case 'GUILD_DELETED':
					$logline = sprintf($this->getLogMessage('GUILD_DELETED', $verbose), $userstring , $log['L_NAME'] ) ;
					break;
				case 'MEMBERDKP_UPDATED':
					$logline = sprintf($this->getLogMessage('MEMBERDKP_UPDATED', $verbose), $userstring, $log['L_NAME'],
					$log['L_EARNED_BEFORE'], $log['L_EARNED_AFTER'], $log['L_SPENT_BEFORE'], $log['L_SPENT_AFTER']);
					break;
			}
			
			return $logline;   

	}
	
	/**
	 * returns log tags from xml
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
	
	/**
	 * Get error message of a value. It's actually the lang value of the constant's name
	 * @param integer $value
	 * @return string
	 */
	private function getLogMessage($value, $verbose = false)
	{
		global $user;
		if($verbose)
		{
			return $user->lang['VLOG_' . $this->valid_action_types[constant("self::$value")] ];
		}
		else
		{
			return $user->lang['ACTION_' . $this->valid_action_types[constant("self::$value")] ];
		}
	}
	
	
	
	
	
	
	
}

?>