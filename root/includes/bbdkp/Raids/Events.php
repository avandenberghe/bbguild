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
 * example of events: 10-man raids, progress raids, farm raid, ...
 * 
 * @package 	bbDKP
 * 
 */
class Events extends \bbdkp\Admin
{
	
	protected $event_id;
	protected $dkpsys_id;
	protected $dkpsys_name;
	protected $event_name; 
	protected $event_value;
	protected $event_color;
	protected $event_imagename;
	protected $event_status;
	public $dkpsys;
	public $event; 
	
	function __construct($event_id = 0) 
	{
		global $db; 
		
		if($event_id == 0)
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
		switch ($property)
		{
			default:
				if (property_exists($this, $property))
				{
					$this->$property = $value;
				}
				else
				{
					trigger_error($user->lang['ERROR'] . '  '. $property, E_USER_WARNING);
				}
		}
	}
	

	public function get($event_id)
	{
		global $db, $user; 
		
		// we have a GET
		$sql = 'SELECT b.dkpsys_name, b.dkpsys_id, a.event_name, a.event_value, a.event_id, a.event_color, a.event_imagename, a.event_status
				FROM ' . EVENTS_TABLE . ' a, ' . DKPSYS_TABLE . " b
				WHERE a.event_id = " . (int) $event_id . " AND b.dkpsys_id = a.event_dkpid";
		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if (!$row)
		{
			trigger_error($user->lang['ERROR_INVALID_EVENT_PROVIDED'], E_USER_WARNING);
		}
		else
		{
			$this->event_id = $row['event_id']; 
			$this->dkpsys_id = $row['dkpsys_id']; 
			$this->dkpsys_name = $row['dkpsys_name']; 
			$this->event_name = $row['event_name']; 
			$this->event_value = $row['event_value']; 
			$this->event_color = $row['event_color']; 
			$this->event_imagename = $row['event_imagename']; 
			$this->event_status = $row['event_status']; 
			$this->event = array(
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
		
		
	}
}

?>