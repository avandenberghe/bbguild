<?php
/**
 * List all Events
 * 
 * @package bbDKP
 * @copyright (c) 2009 bbDKP <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */


/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');
$user->add_lang(array('mods/dkp_common'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

$sort_order = 
array(
    0 => array('event_name', 'event_dkpid, event_name desc'),
    1 => array('event_value desc', 'event_dkpid, event_value desc')
);
$current_order = switch_order($sort_order);
$total_events= 0;
$start = request_var('start', 0);

/*** get dkp pools having events with raids ***/
$sql_array = array (
	'SELECT' => ' dkpsys_id, dkpsys_name ', 
	'FROM' => array (
		DKPSYS_TABLE		=> 'd',
		EVENTS_TABLE 		=> 'e',		
		RAIDS_TABLE 		=> 'r' , 
		), 
	'WHERE' => 'd.dkpsys_id = e.event_dkpid 
				and r.event_id = e.event_id ',
	'ORDER_BY' => 'dkpsys_name'
);
$sql = $db->sql_build_query('SELECT', $sql_array);
$dkppool_result = $db->sql_query($sql); 
$total_events = 0;
while ( $pool = $db->sql_fetchrow($dkppool_result) )
{
	$template->assign_block_vars(
    	'dkpsys_row', array(
    	'NAME' => $pool['dkpsys_name'],
    	'ID' => $pool['dkpsys_id']
    ));

	/*** get events ***/
	$sql = 'SELECT event_dkpid, event_id, event_name, event_value
	        FROM ' . EVENTS_TABLE . ' where event_dkpid = ' . (int) $pool['dkpsys_id'];
	
	$sql_array = array (
		'SELECT' => ' e.event_dkpid, e.event_id, e.event_name, e.event_value, count(r.raid_id) as raidcount, max(raid_start) as newest, min(raid_start) as oldest ', 
		'FROM' => array (
			EVENTS_TABLE 		=> 'e',		
			RAIDS_TABLE 		=> 'r', 
			), 
		'WHERE' => 'e.event_dkpid = ' . (int) $pool['dkpsys_id'] . 
					' and r.event_id = e.event_id ',
		'ORDER_BY' => 'e.event_name'
	);
	$sql = $db->sql_build_query('SELECT', $sql_array);	
	
	$events_result = $db->sql_query_limit($sql, $config['bbdkp_user_elimit'], $start);
	while ( $event = $db->sql_fetchrow($events_result))
	{
		$total_events ++;
	    $template->assign_block_vars(
	    	'dkpsys_row.events_row', array(
	        	'U_VIEW_EVENT' =>  append_sid("{$phpbb_root_path}viewevent.$phpEx", '&amp;' . URI_EVENT . '='.$event['event_id'] . '&amp;'.URI_DKPSYS.'='.$event['event_dkpid']) ,
	        	'NAME' 		=> $event['event_name'],
	        	'VALUE' 	=> $event['event_value'], 
	        	'RAIDCOUNT' => $event['raidcount'],
	        	'OLDEST' 	=> date("d-m-y", $event['oldest'])  ,
	    		'NEWEST' 	=> date("d-m-y", $event['newest']))
	    );

	}
	$db->sql_freeresult($events_result);
}
$db->sql_freeresult($dkppool_result);

$navlinks_array = array(
array(
 'DKPPAGE' => $user->lang['MENU_EVENTS'],
 'U_DKPPAGE' => append_sid("{$phpbb_root_path}listevents.$phpEx"),
));

foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
	'DKPPAGE' => $name['DKPPAGE'],
	'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

$template->assign_vars(array(
    'O_NAME' => $current_order['uri'][0],
    'O_VALUE' => $current_order['uri'][1],
    'U_LIST_EVENTS' => append_sid("{$phpbb_root_path}listevents.$phpEx"), 
    'START' => $start,
    'LISTEVENTS_FOOTCOUNT' => sprintf($user->lang['LISTEVENTS_FOOTCOUNT'], $total_events, $config['bbdkp_user_elimit']),
    'EVENT_PAGINATION' => generate_pagination( append_sid("{$phpbb_root_path}listevents.$phpEx", '&amp;o='.$current_order['uri']['current']),
             $total_events, $config['bbdkp_user_elimit'], $start))
);

$title = $user->lang['EVENTS'];

// Output page
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/listevents.html')
);

page_footer();
?>
