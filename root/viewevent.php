<?php
/**
 * Views detail of an event
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
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

if ( isset($_GET[URI_EVENT]) && isset($_GET[URI_DKPSYS])  )
{
    $sort_order = array(
        0 => array('raid_date desc', 'raid_date'),
        1 => array('raid_note', 'raid_note desc'),
        2 => array('raid_value desc', 'raid_value')
    );
    
    $eventid = request_var(URI_EVENT, 0); 
    $dkpid = request_var(URI_DKPSYS, 0); 
     
    $current_order = switch_order($sort_order);

	$sql_array = array(
	    'SELECT'    => 	'd.dkpsys_name, event_dkpid, e.event_id, e.event_name, e.event_value, e.event_added_by, e.event_updated_by', 
	 
	    'FROM'      => array(
	        EVENTS_TABLE 	=> 'e',
	        DKPSYS_TABLE 	=> 'd',
	    	),
	 
	    'WHERE'     =>  ' e.event_dkpid = d.dkpsys_id  
	    		and e.event_dkpid=' . $dkpid . '
           		and e.event_id=' . $eventid ,
	);
	    
	$sql = $db->sql_build_query('SELECT', $sql_array);
    $result = $db->sql_query($sql); 
   
    // Check for a valid event
    if ( !$event = $db->sql_fetchrow($result) )
    {	
    	$user->add_lang(array('mods/dkp_admin'));
    	trigger_error ( $user->lang['ERROR_INVALID_EVENT_PROVIDED'] , E_USER_WARNING );
    }
   
    $db->sql_freeresult($result);
    
    // Init vars used to get averages and totals
    $total_drop_count = 0;
    $total_attendees_count = 0;
    $total_earned = 0;
    
    // Reduce queries
    $raids     = array();
    $raid_ids  = array('0');
    $items     = array();
    $attendees = array();
    
    $unset_raid_zero = false; 
    
    // Find the raids for this event
    $sql = 'SELECT raid_name, raid_id, raid_date, raid_note, raid_value 
            FROM ' . RAIDS_TABLE . "
            WHERE raid_name='" . $db->sql_escape($event['event_name']) . "' 
            and raid_dkpid=" .  $dkpid  . " ORDER BY " . $current_order['sql'];
    $result = $db->sql_query($sql);
    
    while ( $row = $db->sql_fetchrow($result) )
    {
        $raids[ $row['raid_id'] ] = array(
            'raid_id'    => $row['raid_id'],
            'raid_date'  => $row['raid_date'],
            'raid_note'  => $row['raid_note'],
            'raid_value' => $row['raid_value']);
            
        if ( $row['raid_id'] > 0 )
        {
            if ( !$unset_raid_zero )
            {
                unset($raid_ids[0]);
                $unset_raid_zero = true;
            }
            
            $raid_ids[] = $row['raid_id'];
        }
    }
    $db->sql_freeresult($result);
    
    // Find the item drops for each raid
    // raid-id is primary key
    $sql = 'SELECT raid_id, count(item_id) AS count 
            FROM ' . ITEMS_TABLE . ' 
            WHERE ' . $db->sql_in_set('raid_id', $raid_ids) . ' GROUP BY raid_id';
    
    $result = $db->sql_query($sql);
    
    while ( $row = $db->sql_fetchrow($result) )
    {
        $items[ $row['raid_id'] ] = $row['count'];
    }
    $db->sql_freeresult($result);
    
    // Find the attendees at each raid
    $sql = 'SELECT raid_id, count(member_name) AS count 
            FROM ' . RAID_ATTENDEES_TABLE . ' 
            WHERE ' . $db->sql_in_set('raid_id', $raid_ids) . ' 
            GROUP BY raid_id';
    $result = $db->sql_query($sql);
    
    while ( $row = $db->sql_fetchrow($result) )
    {
        $attendees[ $row['raid_id'] ] = $row['count'];
    }
    $db->sql_freeresult($result);
    
    // Loop through the raids for this event
    $total_raid_count = sizeof($raids);
    foreach ( $raids as $raid_id => $raid )
    {
        $drop_count = ( isset($items[ $raid['raid_id'] ]) ) ? $items[ $raid['raid_id'] ] : '0';
        $attendees_count = ( isset($attendees[ $raid['raid_id'] ]) ) ? $attendees[ $raid['raid_id'] ] : '0';

          $template->assign_block_vars('raids_row', array(
            'U_VIEW_RAID' => append_sid("{$phpbb_root_path}viewraid.$phpEx" , URI_RAID . '='.$raid['raid_id']),
            'DATE'        => ( !empty($raid['raid_id']) ) ? date('d.m.y', $raid['raid_date']) : '&nbsp;',
            'ATTENDEES'   => $attendees_count,
            'DROPS'       => $drop_count,
            'NOTE'        => ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
            'VALUE'       => $raid['raid_value'])
        );
        
        // Add the values of this row to our totals
        $total_drop_count += $drop_count;
        $total_attendees_count += $attendees_count;
        $total_earned += $raid['raid_value'];       
    }
 
    // Prevent div by 0
    $average_attendees = ( $total_raid_count > 0 ) ? floor($total_attendees_count / $total_raid_count) : '0';
    $average_drops     = ( $total_drop_count > 0 ) ? floor($total_drop_count / $total_raid_count)      : '0';
    
    //
    // Items
    $start = request_var('start' ,0);
        
    $sql = 'SELECT item_date, raid_id, item_name, item_buyer, member_id, item_id, item_value, item_dkpid
            FROM ' . ITEMS_TABLE . ' WHERE ' . $db->sql_in_set('raid_id', $raid_ids) . ' ORDER BY item_date DESC ';
    
    $result = $db->sql_query_limit($sql, $config['bbdkp_user_ilimit'], $start);
    
	$bbDkp_Admin = new bbDkp_Admin;
	if ($bbDkp_Admin->bbtips == true)
	{
		if ( !class_exists('bbtips')) 
		{
			require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
		}
		$bbtips = new bbtips;
	}

    while ( $row = $db->sql_fetchrow($result) )
    {
		if ($bbDkp_Admin->bbtips == true)
		{
			$item_name = '<b>' . $bbtips->parse('[itemdkp]' . $row['item_name']  . '[/itemdkp]') . '</b>'; 
		}
		else
		{
			$item_name = '<b>' . $row['item_name']. '</b>';
		}
		
        $template->assign_block_vars('items_row', array(
          'DATE'          => date('d.m.y', $row['item_date']),
          'U_VIEW_RAID'   => append_sid("{$phpbb_root_path}viewraid.$phpEx" , URI_RAID . '=' . $row['raid_id']) ,
          'BUYER'         => $row['item_buyer'],
          'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}viewmember.$phpEx" , URI_NAMEID . '=' . $row['member_id'] . '&amp;' . URI_DKPSYS . '='. $row['item_dkpid']) ,
          'NAME'          => $item_name, 
          'U_VIEW_ITEM'   => append_sid("{$phpbb_root_path}viewitem.$phpEx" , URI_ITEM . '=' . $row['item_id']) ,
          'SPENT'         => sprintf("%.2f", $row['item_value']))
      );
    }
       
    $total_items = 0;
    $result = $db->sql_query($sql);
    while ( $row = $db->sql_fetchrow($result) )
    {
    	$total_items++;
    }
    
    $selfurl = append_sid("{$phpbb_root_path}viewevent.$phpEx" , URI_EVENT . '='.  $eventid . '&amp;' . URI_DKPSYS . '='. $dkpid ) ;
    $pagination = generate_pagination($selfurl, $total_drop_count, $config['bbdkp_user_ilimit'], $start, true);
    
    // build breadcrumbs menu                              
    $navlinks_array = array(
    array(
     'DKPPAGE' => $user->lang['MENU_EVENTS'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}listevents.$phpEx"),
    ),

    array(
     'DKPPAGE' => $user->lang['MENU_VIEWEVENT'],
     'U_DKPPAGE' => $selfurl ,
    ),
    );

    foreach( $navlinks_array as $name )
    {
    $template->assign_block_vars('dkpnavlinks', array(
    'DKPPAGE' => $name['DKPPAGE'],
    'U_DKPPAGE' => $name['U_DKPPAGE'],
    ));
    }
        
    $template->assign_vars(array(
        'O_DATE'  => $current_order['uri'][0],
        'O_NOTE'  => $current_order['uri'][1],
        'O_VALUE' => $current_order['uri'][2],
        'U_VIEW_EVENT'        => $selfurl ,
    	'DKPPOOL'			  => ( !empty($event['dkpsys_name']) ) ? $event['dkpsys_name'] : 'N/A',
        'EVENT_ADDED_BY'      => ( !empty($event['event_added_by']) ) ? $event['event_added_by'] : 'N/A',
        'EVENT_UPDATED_BY'    => ( !empty($event['event_updated_by']) ) ? $event['event_updated_by'] : 'N/A',
        'AVERAGE_ATTENDEES'   => $average_attendees,
        'AVERAGE_DROPS'       => $average_drops,
        'TOTAL_EARNED'        => sprintf("%.2f", $total_earned),
        'VIEWEVENT_FOOTCOUNT' => sprintf($user->lang['VIEWEVENT_FOOTCOUNT'], $total_raid_count),
        'L_RECORDED_RAID_HISTORY' => sprintf($user->lang['RECORDED_RAID_HISTORY'], $event['event_name']),
        'L_RECORDED_DROP_HISTORY' => sprintf($user->lang['RECORDED_DROP_HISTORY'], $event['event_name']),
        'ITEM_FOOTCOUNT'      => sprintf($user->lang['VIEWITEM_FOOTCOUNT'], $total_items, $total_items),
        
        'START' 		=> $start,
    	'ITEM_PAGINATION' => $pagination
    
    
    )
    );

	// Output page
	page_header($user->lang['MENU_VIEWEVENT']);
	
	$template->set_filenames(array(
		'body' => 'dkp/viewevent.html')
	);
	
	page_footer();
}
else
{
	$user->add_lang(array('mods/dkp_admin'));
    trigger_error($user->lang['ERROR_EMPTY_EVENTNAME']);
}
?>
