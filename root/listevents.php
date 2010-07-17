<?php
/**
 * List all Events
 * 
 * @package bbDkp
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
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

/*** get dkp pools ***/
$sql = 'SELECT dkpsys_id, dkpsys_name FROM ' . DKPSYS_TABLE . ' ORDER BY dkpsys_name';
if ( !($dkppool_result = $db->sql_query($sql)) )
{
   $user->add_lang(array('mods/dkp_admin'));
   trigger_error ( $user->lang['ERROR_INVALID_DKPSYSTEM_PROVIDED'] , E_USER_WARNING ); 
}
while ( $pool = $db->sql_fetchrow($dkppool_result) )
{
	$template->assign_block_vars(
    	'dkpsys_row', array(
    	'NAME' => $pool['dkpsys_name'],
    	'ID' => $pool['dkpsys_id']
    ));

	/*** get events ***/
	$sql = 'SELECT event_dkpid, event_id, event_name, event_value
	        FROM ' . EVENTS_TABLE . ' where event_dkpid = ' . (int) $pool['dkpsys_id'] ;
	
	if ( !($events_result = $db->sql_query_limit($sql, $config['bbdkp_user_elimit'], $start)))
	{
		$user->add_lang(array('mods/dkp_admin'));
	    trigger_error($user->lang['ERROR_EMPTY_EVENTNAME']);
	}
	while ( $event = $db->sql_fetchrow($events_result) )
	{
	    $template->assign_block_vars(
	    	'dkpsys_row.events_row', array(
	        	'U_VIEW_EVENT' =>  append_sid("{$phpbb_root_path}viewevent.$phpEx", '&amp;' . URI_EVENT . '='.$event['event_id'] . '&amp;'.URI_DKPSYS.'='.$event['event_dkpid']) ,
	        	'NAME' => $event['event_name'],
	        	'VALUE' => $event['event_value'])
	    );
	}
	$db->sql_freeresult($events_result);	

}
$db->sql_freeresult($dkppool_result);

$sql2 = 'SELECT count(*) as eventcount FROM ' . EVENTS_TABLE;
$result = $db->sql_query($sql2);
$total_events  = (int) $db->sql_fetchfield('eventcount', 0,$result);
$db->sql_freeresult($result);

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
    'U_LISTITEMS'         => append_sid("{$phpbb_root_path}listitems.$phpEx"),  
   	'U_LISTITEMHIST'      => append_sid("{$phpbb_root_path}listitems.$phpEx?&amp;page=history"),
    'U_LISTMEMBERS'       => append_sid("{$phpbb_root_path}listmembers.$phpEx"),
   	'U_LISTEVENTS'        => append_sid("{$phpbb_root_path}listevents.$phpEx"),
   	'U_LISTRAIDS'         => append_sid("{$phpbb_root_path}listraids.$phpEx"),
   	'U_VIEWITEM'          => append_sid("{$phpbb_root_path}viewitem.$phpEx"),
   	'U_BP'                => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
   	'U_ROSTER'            => append_sid("{$phpbb_root_path}roster.$phpEx"),
   	'U_ABOUT'             => append_sid("{$phpbb_root_path}about.$phpEx"),
 	'U_STATS'             => append_sid("{$phpbb_root_path}stats.$phpEx"),
    'U_VIEWNEWS'          => append_sid("{$phpbb_root_path}viewnews.$phpEx"),
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
