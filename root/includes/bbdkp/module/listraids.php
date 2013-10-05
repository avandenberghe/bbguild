<?php
/**
 * 
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
 
/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}
if (!class_exists('\bbdkp\Raids'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Raids.$phpEx");
}
$raids = new \bbdkp\Raids();
$start = request_var('start', 0);   
$total_raids=0;

// get sort order 
$sort_order = array
(
    0 => array('raid_start desc', 'raid_start'),
    1 => array('dkpsys_name', 'dkpsys_name desc'),
    2 => array('event_name', 'event_name desc'),
    3 => array('raid_note', 'raid_note desc'),
    4 => array('raid_value desc', 'raid_value')
);
 
$current_order = $this->switch_order($sort_order);
//total raids in the last year
$total_raids = $raids->raidcount($this->dkpsys_id, 365, 0, 1, true);

if ($this->query_by_pool)
{
    $pagination = generate_pagination( append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=listraids&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id . 
    '&amp;o='.  $current_order['uri']['current'] ), $total_raids, $config['bbdkp_user_rlimit'], $start, true);
    
    $u_list_raids =  append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=listraids&amp;' . URI_DKPSYS . '='. $this->dkpsys_id . '&amp;guild_id=' . $this->guild_id);
}
else 
{
    $pagination = generate_pagination( append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=listraids&amp;' . URI_DKPSYS .  '=All&amp;o='.  
    $current_order['uri']['current'] ), $total_raids, $config['bbdkp_user_rlimit'], $start, true);
    
    $u_list_raids =  append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=listraids&amp;guild_id=' . $this->guild_id);
}

$raids_result = $raids->getRaids('r.raid_start DESC', $this->dkpsys_id, 0, $start, 0);
while ( $raid = $db->sql_fetchrow($raids_result))
{
	$template->assign_block_vars('raids_row', array(
			'DATE'			=> ( !empty($raid['raid_start']) ) ? date($config['bbdkp_date_format'], $raid['raid_start']) : '&nbsp;',
			'NAME'			=> $raid['event_name'],
	        'U_VIEW_RAID'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewraid&amp;' . URI_RAID . '='.$raid['raid_id']),
	    	'U_VIEW_EVENT' 	=> append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=viewevent&amp;' . URI_EVENT . '='.  $raid['event_id'] . '&amp;' . URI_DKPSYS . '=' . $raid['event_dkpid']),
			'POOL' 			=> 	$this->dkpsys_name, 
	    	'EVENTCOLOR' 	=> ( !empty($raid['event_color']) ) ? $raid['event_color'] : '#123456',
			'NOTE'			=> ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
	    	'ATTENDEES' 	=> $raid['attendees'],
	        'RAIDVALUE' 	=> $raid['raid_value'],
	        'TIMEBONUS' 	=> $raid['time_value'],
		    'ZSBONUS' 		=> $raid['zs_value'],
		    'DECAYVALUE' 	=> $raid['raiddecay'],
			'TOTAL'		 	=> $raid['net_earned'],
		    
        )
    );
}

$sortlink = array();
for ($i=0; $i<=4; $i++)
{
    if ($this->query_by_pool)
    {
        $sortlink[$i] = append_sid($phpbb_root_path . 'dkp.'.$phpEx, 'page=listraids&amp;o=' . $current_order['uri'][$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id ); 
    }
    else 
    {
        $sortlink[$i] = append_sid($phpbb_root_path  . 'dkp.'.$phpEx, 'page=listraids&amp;o=' . $current_order['uri'][$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=All'  ); 
    }
}
// breadcrumbs
$template->assign_block_vars('dkpnavlinks', array(
'DKPPAGE' 		=> $user->lang['MENU_RAIDS'],
'U_DKPPAGE' 	=> $u_list_raids,
));


$template->assign_vars(array(
    'S_SHOWZS' 			=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
	'S_SHOWTIME' 		=> ($config['bbdkp_timebased'] == '1') ? true : false,
	'S_SHOWDECAY' 		=> ($config['bbdkp_decay'] == '1') ? true : false,
    
    'O_DATE'  => $sortlink[0],
    'O_POOL'  => $sortlink[1],
    'O_NAME'  => $sortlink[2],
    'O_NOTE'  => $sortlink[3],
    'O_VALUE' => $sortlink[4],
    
    'U_LIST_RAIDS' => $u_list_raids , 
    'LISTRAIDS_FOOTCOUNT' => sprintf($user->lang['LISTRAIDS_FOOTCOUNT'], $total_raids, $config['bbdkp_user_rlimit']),

    'START' => $start,
    'RAID_PAGINATION' => $pagination, 
	'S_DISPLAY_RAIDS' => true
));

// Output page
page_header($user->lang['RAIDS']);

?>