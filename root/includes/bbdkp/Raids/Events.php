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

/**
 * Events are Types of raids. Raids are hooked up to Events
 * example of events: 
 * 		10-man raids, 
 * 		progress raids, 
 * 		recurring farm raid , ...
 * 		RBG ...
 * 		Flashpoints, ...
 * 
 * it's advised to not create an event for each instance
 * because instances are implemented with the location class		
 * 
 * 
 * @package 	bbDKP
 * 
 */
class Events extends \bbdkp\Admin
{
	
	public $event_id;
	private $dkpsys_id;
	private $dkpsys_name;
	private $event_name; 
	private $event_value;
	private $event_color;
	private $event_imagename;
	private $event_status;
	
	public $total_events;
	public $dkpsys;
	public $events; 
	
	function __construct($event_id = 0) 
	{
		global $db; 
		parent::__construct();
		if($event_id != 0)
		{
			$this->get($event_id); 
		}
		else
		{
			$this->event_id = 0;
			$this->dkpsys_id = 0;
			$this->dkpsys_name = '';
			$this->event_name = '';
			$this->event_value = 0.0;
			$this->event_color = #FFF;
			$this->event_imagename = '';
			$this->event_status = '';
		}
		
		// dkp pools
		$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE . ' ORDER BY dkpsys_name';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result) )
		{
			$this->dkpsys[] = array(
					'id' => $row['dkpsys_id'],
					'name' => $row['dkpsys_name'],
					'default' => $row['dkpsys_default']);
		}
		$db->sql_freeresult($result);
		
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
		trigger_error($user->lang['ERROR'] . '  '. $property, E_USER_WARNING);
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
				
				switch ($property)
				{
					case 'event_name':
						if (strlen($value) < 3)
						{
							\trigger_error($user->lang['ERROR_INVALID_EVENT_PROVIDED'] . $this->link, E_USER_WARNING);
						}
						elseif (strlen($value) > 255)
						{
							$this->$property =  (strlen($value) > 255) ? substr($value,0, 250).'...' : $string;
						}
						break;
					default:
						$this->$property = $value;
						break;
				}
			}
			else
			{
				trigger_error($user->lang['ERROR'] . '  '. $property, E_USER_WARNING);
			}
	}
}
	

	/**
	 * 
	 * @param unknown_type $dkpid
	 * @param unknown_type $event_id
	 */
	public function get($event_id = 0)
	{
		global $db, $user; 

		$sql = 'SELECT b.dkpsys_name, b.dkpsys_id, a.event_name, a.event_value,
				a.event_id, a.event_color, a.event_imagename, a.event_status
				FROM ' . EVENTS_TABLE . ' a, ' . DKPSYS_TABLE . " b
				WHERE a.event_id = " . (int) $event_id . " 
				AND b.dkpsys_id = a.event_dkpid ";
	
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		if ($row)
		{
			$this->event_id = $row['event_id']; 
			$this->dkpsys_id = $row['dkpsys_id']; 
			$this->dkpsys_name = $row['dkpsys_name']; 
			$this->event_name = $row['event_name']; 
			$this->event_value = $row['event_value']; 
			$this->event_color = $row['event_color']; 
			$this->event_imagename = $row['event_imagename']; 
			$this->event_status = $row['event_status']; 
			$this->events = array(
				'event_dkpsys_name'	 	 => $this->dkpsys_name,
				'dkpsys_id'		 		 => $this->dkpsys_id,
				'event_name'			 => $this->event_name ,
				'event_color'			 => $this->event_color,
				'event_imagename'		 => $this->event_imagename,
				'event_value'			 => $this->event_value,
				'event_id'				 => $this->event_id,
				'event_status'			 => $this->event_status,
			);
			unset($row); 
		}
		$db->sql_freeresult($result);
		
	}
	
	
	/**
	 * adds an event to Database
	 */
	public function add()
	{
		global $user, $db; 
		// check existing
		$result = $db->sql_query("SELECT count(*) as evcount from " . EVENTS_TABLE .
				" WHERE UPPER(event_name) = '" . strtoupper($db->sql_escape( $this->event_name ))	."' ;");
		$eventexistsrow = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
			
		if($eventexistsrow['evcount'] > 0 )
		{
			trigger_error($user->lang['ERROR_RESERVED_EVENTNAME']	. $this->link, E_USER_WARNING);
		}
			
		$query = $db->sql_build_array('INSERT', array(
				'event_dkpid'		=> $this->dkpsys_id,
				'event_name'		=> $this->event_name,
				'event_imagename'	=> $this->event_imagename,
				'event_color'		=> $this->event_color,
				'event_value'		=> $this->event_value,
				'event_added_by' 	=> $user->data['username'])
		);
		$db->sql_query('INSERT INTO ' . EVENTS_TABLE . $query);
		
		/* get new key */
		$this->event_id = $db->sql_nextid();
		
		$log_action = array(
				'header'		=> 'L_ACTION_EVENT_ADDED',
				'id'			=> $this->event_id,
				'L_NAME'		=> $this->event_name,
				'L_VALUE'		=> $this->event_value,
				'L_ADDED_BY' 	=> $user->data['username']);
		
		$this->log_insert(array(
				'log_type'	=> $log_action['header'],
				'log_action' => $log_action)
		);
		
	}
	
	
	/**
	 * updates Database
	 */
	public function update(\bbdkp\Events $oldevent)
	{
		global $user, $db;
		
		// Update the event record
		$query = $db->sql_build_array('UPDATE', array(
				'event_dkpid' => $this->dkpsys_id,
				'event_name'=> $this->event_name,
				'event_imagename' => $this->event_imagename,
				'event_color' => $this->event_color,
				'event_value' => $this->event_value));
		
		$sql = 'UPDATE ' . EVENTS_TABLE . ' SET ' . $query . ' WHERE event_id=' . (int) $this->event_id;
		$db->sql_query($sql);
		
		if ($this->dkpsys_id != $oldevent->dkpsys_id)
		{
			//@sync
		}
		
		//
		// Logging
		//
		$log_action = array(
				'header'		 => 'L_ACTION_EVENT_UPDATED',
				'id'		 	 => $this->event_id,
				'L_NAME_BEFORE'  => $oldevent->event_name,
				'L_VALUE_BEFORE' => $oldevent->event_value,
				'L_NAME_AFTER'   => $this->event_name,
				'L_VALUE_AFTER'  => $oldevent->event_value,
				'L_UPDATED_BY'   => $user->data['username']);
		
		$this->log_insert(array(
				'log_type' => $log_action['header'],
				'log_action' => $log_action)
			);
		
	}
	
	/**
	 * delete event
	 */
	public function delete()
	{
		global $db, $user;
		
		$sql = 'SELECT * FROM ' . RAIDS_TABLE . ' a, ' . EVENTS_TABLE . ' b
				WHERE b.event_id = a.event_id and b.event_dkpid = ' . (int) $this->dkpsys_id . ' and a.event_id = ' . $this->event_id;  
		
		// check for existing events, raids on this event
		$result = $db->sql_query ( $sql );
		if ($row = $db->sql_fetchrow ( $result ))
		{
			trigger_error ( $user->lang ['FV_RAIDEXIST'], E_USER_WARNING );
		}
		
		$sql = 'DELETE FROM ' . EVENTS_TABLE . ' WHERE event_id = ' . $this->event_id ;
		$db->sql_query($sql);
		
		$log_action = array(
				'header'	=> 'L_ACTION_EVENT_DELETED',
				'id'		=> $this->event_id,
				'L_NAME'	=> $this->event_name,
				'L_VALUE' 	=> $this->event_value );
		
		$this->log_insert(array(
				'log_type' => $log_action['header'],
				'log_action' => $log_action)
		);
		
		
		
		
	}
	
	/**
	 * counts number of events, evtl per dkp pool
	 * 
	 */
	public function countevents($dkpid = 0)
	{
		global $db; 
		$sql = 'SELECT count(*) as eventcount FROM ' . DKPSYS_TABLE . ' a , ' . EVENTS_TABLE . ' b
			where a.dkpsys_id = b.event_dkpid AND b.event_status = 1 ';
		if($this->dkpsys_id !=0)
		{
			$sql .= ' AND a.dkpsys_id = ' . $this->dkpsys_id . ' '; 	
		}
		elseif ($dkpid != 0)
		{
			$sql .= ' AND a.dkpsys_id = ' . $dkpid. ' ';
		}
		$result = $db->sql_query($sql);
		$this->total_events = (int) $db->sql_fetchfield('eventcount');
		$db->sql_freeresult($result);
		
	}

	/**
	 * counts number of events, evtl per dkp pool
	 *
	 */
	public function getmax($dkpid = 0)
	{
		global $db;
		$this->dkpsys_id = $dkpid; 
		$sql = 'SELECT max(event_value) AS max_value FROM ' . EVENTS_TABLE . ' where event_status = 1 AND event_dkpid = ' . $this->dkpsys_id;
		$result = $db->sql_query ($sql);
		$max_value = (float) $db->sql_fetchfield('max_value', false, $result);
		$db->sql_freeresult($result);
		return $max_value; 
	}
	
	
	
	/**
	 *
	 */
	public function listevents($start = 0, $order = 'b.dkpsys_id, a.event_name' , $dkpid=0  )
	{
		global $config, $db, $user;
	
		$sql = 'SELECT b.dkpsys_name, b.dkpsys_id, a.event_name, a.event_value, a.event_id, a.event_color, a.event_imagename, a.event_status
				FROM ' . EVENTS_TABLE . ' a, ' . DKPSYS_TABLE . " b
				WHERE b.dkpsys_id = a.event_dkpid";
			
		if($dkpid != 0)
		{
			$sql .= " AND b.dkpsys_id = " . $dkpid;
		}
			
		$sql .= " ORDER BY " . $order;
			
		$result = $db->sql_query_limit($sql, $config['bbdkp_user_elimit'], $start,0);
	
		while ($row = $db->sql_fetchrow($result) )
		{
			$this->events[$row['event_id']] =  array(
					'event_id'				 => $row['event_id'],
					'dkpsys_id'		 		 => $row['dkpsys_id'],
					'event_name'			 => $row['event_name'],
					'dkpsys_name'	 	 	 => $row['dkpsys_name'],
					'event_value'			 => $row['event_value'],
					'event_color'			 => $row['event_color'],
					'event_imagename'		 => $row['event_imagename'],
					'event_status'			 => $row['event_status'],
			);
		}
		$db->sql_freeresult($result);
	}
	
	
	
	/**
	 * mark an event selection active or unactive
	 * @param unknown_type $action
	 * @param unknown_type $selected_events
	 */
	public function activateevents($action = 1, $selected_events)
	{
		global $db;
		$db->sql_transaction ( 'begin' );
		
		$sql = 'UPDATE ' . EVENTS_TABLE . "
           SET event_status = '" . $action . "'
           WHERE " . $db->sql_in_set ( 'event_id', $selected_events, false, true );
		$db->sql_query ( $sql);
		
		$db->sql_transaction ( 'commit' );
	}
	
}

?>