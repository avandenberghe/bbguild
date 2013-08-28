<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
// don't add this file to namespace bbdkp
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}
if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}
if (!class_exists('\bbdkp\Events'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Events.$phpEx");
}

/**
 * This acp class manages Events.
 * 
 * @package bbDKP
 */
 class acp_dkp_event extends \bbdkp\Admin
{
	public $u_action;
	public $link;
	public $event; 
	
	/** 
	* main ACP dkp event function
 	* 
	* @package bbDKP
	* @param int $id the id of the node who parent has to be returned by function 
	* @param int $mode id of the submenu
	* @access public 
	*/
	public function main($id, $mode)
	{
		global $user, $template, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$this->link = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_event&amp;mode=listevents") . '"><h3>'. $user->lang['RETURN_DKPINDEX'] . '</h3></a>';

		$form_key = 'acp_dkp_event';
		add_form_key($form_key);
					
		switch ($mode)
		{
			case 'addevent':
			$update = false;
			$event  = new \bbdkp\Events(  request_var(URI_EVENT, 0 ));
			
			if(isset($event->dkpsys))
			{
				foreach ($event->dkpsys as $pool)
				{
					$template->assign_block_vars('event_dkpid_row', array(
						'VALUE' 	=> $pool['id'],
						'SELECTED' 	=> ($pool['id'] == $event->dkpsys_id) ? ' selected="selected"' : (  ( $pool['default'] == 'Y' ) ? ' selected="selected"' : '' ), 
						'OPTION'	=> $pool['name'])
					);
				}
			}
			else
			{
				trigger_error('ERROR_NOPOOLS', E_USER_WARNING );
			}
		 
			$add = (isset($_POST['add'])) ? true : false;
			$submit	= (isset($_POST['update'])) ? true : false;
			$delete	= (isset($_POST['delete'])) ? true : false; 
			$addraid = (isset($_POST['newraid'])) ? true : false; 

			if ( $add || $submit || $addraid)
			{
					if (!check_form_key('acp_dkp_event'))
					{
						trigger_error('FORM_INVALID');
					}
			}
			
			if ($addraid)
			{
				redirect(append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=addraid&amp;".URI_DKPSYS . '=' . 
				$event->dkpsys_id . '&amp;' . URI_EVENT . '=' . $event->event_id ));
			}
	
			if ($add)
			{
				$this->add_event();
			}
				 
			if ($submit)
			{
				$this->update_event();
			}	

			if ($delete)
			{	
				$this->delete_event();
			}
			
			/* if bossprogress is installed */
			
			/*
			if (isset($config['bbdkp_bp_version']))
			{
				if (isset($this->event))
				{
					$s_zonelist_options = '<option value="--">--</option>';		
				}
				else 
				{
					$s_zonelist_options = '<option value="--" selected="selected">--</option>';
				}

                $installed_games = array();
                foreach($this->games as $gameid => $gamename)
                {
                	//add value to dropdown when the game config value is 1
                	if ($config['bbdkp_games_' . $gameid] == 1)
                	{
                		$installed_games[] = $gameid; 
                	} 
                }
                
				// list of zones
				$sql_array = array(
				'SELECT'	=>	' z.id, l.name ', 
				'FROM'		=> array(
						ZONEBASE		=> 'z',
						BB_LANGUAGE		=> 'l',
							),
				'WHERE'		=> " z.id = l.attribute_id 
								AND l.attribute='zone' 
								AND l.game_id = z.game
								AND l.language= '" . $config['bbdkp_lang'] ."' 
								AND " . $db->sql_in_set('l.game_id', $installed_games), 
				'ORDER_BY'	=> 'sequence desc, id desc ',
				);
				
				$sql = $db->sql_build_query('SELECT', $sql_array);					
				$result = $db->sql_query($sql);
				while ( $row = $db->sql_fetchrow($result) )
				{
					if (!isset($this->event))
					{
						$s_zonelist_options .= '<option value="' . $row['name'] . '"> ' . $row['name'] . '</option>';	
					}
					else
					{
						$select = ($row['name'] == $this->event['event_name'] ) ? ' selected="selected" ' : ' ';
						$s_zonelist_options .= '<option value="' . $row['name'] . '" ' . $select . ' > ' . $row['name'] . '</option>';
					}
										
				}
					
				$template->assign_vars(array(
						'S_ZONEEVENT_OPTIONS'		=> $s_zonelist_options,
						'S_BP_SHOW'	=> true,
					));
			}
			else 
			{
					
				*/
				$template->assign_vars(array(
						'S_BP_SHOW'	=> false,
					));
				
				/*
			}
				*/
					
			$template->assign_vars(array(
					'L_TITLE'			=> $user->lang['ACP_ADDEVENT'],
					'L_EXPLAIN' 		=> $user->lang['ACP_ADDEVENT_EXPLAIN'],
					'EVENT_ID'			=> $event->event_id,
					'EVENT_DKPPOOLNAME'	=> $event->dkpsys_name,
					'EVENT_NAME'		=> $event->event_name,
					'S_EVENT_STATUS'	=> $event->event_status == 1 ? true : false, 
					'EVENT_VALUE'		=> $event->event_value,
					'EVENT_COLOR'		=> ($event->event_color == '') ? '#FFFFFF' : $event->event_color,
					'EVENT_IMAGENAME'	=> $event->event_imagename,
					'IMAGEPATH' 			=> $phpbb_root_path . "images/event_images/" . $event->event_imagename . ".png",   
                   	'S_EVENT_IMAGE_EXISTS' 	=> (strlen($event->event_imagename) > 1) ? true : false,       			
					'L_DKP_VALUE'		=> sprintf($user->lang['DKP_VALUE'], $config['bbdkp_dkp_name']),
					'MSG_NAME_EMPTY'=> $user->lang['FV_REQUIRED_NAME'],
					'MSG_VALUE_EMPTY' => $user->lang['FV_REQUIRED_VALUE'],
					'S_ADD' => $event->event_id  == 0 ? true : false
					)
				);
				 
				$this->page_title = 'ACP_ADDEVENT';
				$this->tpl_name = 'dkp/acp_'. $mode;
			 
			break;
 
			case 'listevents':

				$showadd = (isset($_POST['eventadd'])) ? true : false;
				$event  = new \bbdkp\Events();
				
				if($showadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_event&amp;mode=addevent"));					
					break;
				}
				
				$activate = (isset ( $_POST ['deactivate'] )) ? true : false;
				if ($activate)
				{
					// all events in this window
					$all_events = explode(',', request_var ( 'idlist', '') );
					// all checked events in this window
					$active_events = request_var ( 'activate_ids', array (0));
					//activate selected events
					$event->activateevents(1, $active_events);
					//deactivate unselected events
					$event->activateevents(0,  array_diff($all_events, $active_events) );
					
				}
				
				$sort_order = array(
					0 => array('dkpsys_name', 'dkpsys_name desc'),
					1 => array('event_name', 'dkpsys_name, event_name desc'),
					2 => array('event_value desc', 'dkpsys_name, event_value desc'), 
					3 => array('event_status desc', 'dkpsys_name, event_status, event_name desc'), 
				);
				$current_order = $this->switch_order($sort_order);
				$start = request_var('start',0);
				$event->listevents($start, $current_order['sql']);  
			 
			 	$idlist = array();
			 	
			 	if(isset($event->events))
			 	{
				 	foreach ($event->events as $id => $listevent)
				 	{
						$template->assign_block_vars('events_row', array(
	                    	'EVENT_ID' => $listevent ['event_id'],
							'U_VIEW_EVENT' =>append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_event&amp;mode=addevent&amp;" . URI_EVENT ."={$id}"),
							'DKPSYS_EVENT' => $listevent['dkpsys_name'],
							'COLOR' => $listevent['event_color'],
							'IMAGEPATH' 	=> $phpbb_root_path . "images/event_images/" . $listevent['event_imagename'] . ".png", 
	                    	'S_EVENT_IMAGE_EXISTS' => (strlen($listevent['event_imagename']) > 1) ? true : false, 
	                    	'S_EVENT_STATUS' => ($listevent ['event_status'] == 1) ? 'checked="checked" ' : '', 
							'IMAGENAME' => $listevent['event_imagename'],
							'NAME' => $listevent['event_name'],
							'VALUE' => $listevent['event_value'])
						);
						$idlist[] = $listevent ['event_id'];
				 	}
			 	}
			    
				$template->assign_vars(array(
					'IDLIST'		=> implode(",", $idlist), 
					'L_TITLE'		=> $user->lang['ACP_LISTEVENTS'],
					'L_EXPLAIN'		=> $user->lang['ACP_LISTEVENTS_EXPLAIN'],
					'O_DKPSYS'		=> $current_order['uri'][0],
					'O_NAME'		=> $current_order['uri'][1],
					'O_VALUE'		=> $current_order['uri'][2], 
					'U_LIST_EVENTS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_event&amp;mode=listevents&amp;"),		
					'START'			=> $start,
					'LISTEVENTS_FOOTCOUNT' => sprintf($user->lang['LISTEVENTS_FOOTCOUNT'], $event->total_events, $config['bbdkp_user_elimit']),
					'EVENT_PAGINATION'	=> generate_pagination(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_event&amp;mode=listevents&amp;" . 
					URI_ORDER . '='.$current_order['uri']['current']), $event->total_events, $config['bbdkp_user_elimit'],$start, true))

				);

				$this->page_title = 'ACP_LISTEVENTS';
				$this->tpl_name = 'dkp/acp_'. $mode;
			 
			break;

	 
		}
	}
	
	/**
	 * adds an event to the database
	 *
	 */
	function add_event()
	{
		global $user, $config, $db;
		$event  = new \bbdkp\Events();
		$event->dkpsys_id = request_var('event_dkpid',0);
		$event->event_name = utf8_normalize_nfc(request_var('event_name','', true));

		$event->event_imagename = utf8_normalize_nfc(request_var('event_image','', true));
		$event->event_color = utf8_normalize_nfc(request_var('event_color','', true));
		$event->event_value= request_var('event_value', 0.0);
		
		/*
		if (isset($config['bbdkp_bp_version']))
		{
			if (isset($config['bbdkp_bp_version']))
			{
				$zone= utf8_normalize_nfc(request_var('zoneevent','', true));
				if ($zone != "--")
				{
					$event->event_name= $zone;
				}
			}
		}
		*/

		$event->add();
		
		$success_message = sprintf($user->lang['ADMIN_ADD_EVENT_SUCCESS'], $event->event_value , $event->event_name );
		trigger_error($success_message . $this->link);
		
	}

	/**
	 * updates an existing event
	 *
	 */
	function update_event()
	{
		global $db, $user, $phpbb_root_path, $phpEx;

		// get old event name, value from db
		$oldevent = new \bbdkp\Events(request_var(URI_EVENT,0));
		$newvent = new \bbdkp\Events(request_var(URI_EVENT,0));
		  
		$newvent->event_name = utf8_normalize_nfc(request_var('event_name','', true));
		if (strlen($newvent->event_name) < 3)
		{
			 trigger_error($user->lang['ERROR_INVALID_EVENT_PROVIDED'] . $this->link, E_USER_WARNING);
		}
		
		/*
		if (isset($config['bbdkp_bp_version']))
		{
			$zone= utf8_normalize_nfc(request_var('zoneevent','', true));
			if ($zone != "--")
			{
					$new_event_name = $zone;
			}
		}
		*/
		
		$newvent->dkpsys_id = request_var('event_dkpid', '');
		if ($newvent->dkpsys_id == '')
		{
			trigger_error($user->lang['ERROR_INVALID_EVENT_PROVIDED'] . $this->link, E_USER_WARNING);
		}
		
		$newvent->event_imagename = utf8_normalize_nfc(request_var('event_image','', true)); 
		$newvent->event_color  = utf8_normalize_nfc(request_var('event_color','', true)); 
		$newvent->event_value = request_var('event_value', 0.0); 
		
		$newvent->update($oldevent); 
		$success_message = sprintf($user->lang['ADMIN_UPDATE_EVENT_SUCCESS'], $newvent->event_value, $newvent->event_name);
		unset($newvent);
		unset($oldevent);
		trigger_error($success_message . $this->link);
	}
	
	/**
	 * deletes an event
	 *
	 */
	function delete_event()
	{

		global $template, $db, $user;
		if(isset($_GET[URI_EVENT]))
		{
			
			// give a warning that raids cant be without event
			if (confirm_box(true))
			{
				$event = new \bbdkp\Events();
				$event->event_id = request_var(URI_EVENT,0);
				$event->get($event->event_id); 
				$clean_event_name = str_replace("'","", $this->event['event_name']);
				$event->delete(); 
				$success_message = sprintf($user->lang['ADMIN_DELETE_EVENT_SUCCESS'], $event->event_value, $event->event_name);
				trigger_error($success_message . adm_back_link($this->u_action));
			}
			else
			{
				$event = new \bbdkp\Events();
				$event->event_id = request_var(URI_EVENT,0); 
				$s_hidden_fields = build_hidden_fields(array(
					'delete'	=> true,
					'event_id'	=> request_var(URI_EVENT,0) ,
					)
				);
				$template->assign_vars(array(
					'S_HIDDEN_FIELDS'	 => $s_hidden_fields)
				);
				confirm_box(false, $user->lang['CONFIRM_DELETE_EVENT'], $s_hidden_fields);
			}
		}
	}
	
}

?>