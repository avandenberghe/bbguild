<?php
/**
 * View individual member
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
$user->add_lang(array('mods/dkp_common','mods/dkp_admin'));
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

if 	(isset($_GET[URI_NAME]) && isset($_GET[URI_DKPSYS])) 
{
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
	 )); 
	    
	$member_name= request_var(URI_NAME, '', true);
	$dkp_pool=request_var(URI_DKPSYS, 0);
	
	
    /*****************************
    /***   general info      *****
    ******************************/
	$sql_array = array(
    'SELECT'    => 	'b.member_dkpid, d.dkpsys_name, a.member_id, a.member_name, 
    				 b.member_earned, b.member_spent, b.member_adjustment, 
    				(b.member_earned-b.member_spent+b.member_adjustment) AS member_current,
				    b.member_firstraid, b.member_lastraid, b.member_raidcount ', 
    'FROM'      => array(
        MEMBER_LIST_TABLE => 'a',
        MEMBER_DKP_TABLE => 'b',
        DKPSYS_TABLE => 'd',
    	),
 
    'WHERE'     =>  'a.member_id= b.member_id
    				AND b.member_dkpid = d.dkpsys_id
					AND b.member_dkpid= ' . (int) $dkp_pool . " 
    				AND a.member_name='". $db->sql_escape($member_name) ."'" ,);

    $sql = $db->sql_build_query('SELECT', $sql_array);
    
    if ( !($member_result = $db->sql_query($sql)) )
    {
        trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
    }
	
    // Make sure they provided a valid member name
    if ( !$member = $db->sql_fetchrow($member_result) )
    {
 		trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
    }
    $db->sql_freeresult($member_result);
    
    $range1 = $config['bbdkp_list_p1'];
    $range2 = $config['bbdkp_list_p2']; 
    $range3 = 300;  

    $mc1 = memberraid_count($dkp_pool, $range1, $member['member_id'], false);
    $mc2 = memberraid_count($dkp_pool, $range2, $member['member_id'], false);
    $mc3 = memberraid_count($dkp_pool, $range3, $member['member_id'], false);
    $mclife = memberraid_count($dkp_pool, 0, $member['member_id'], true);

    $pc1 = poolraid_count($dkp_pool, $range1, false);
    $pc2 = poolraid_count($dkp_pool, $range2, false);
    $pc3 = poolraid_count($dkp_pool, $range3, false);
    $pclife = poolraid_count($dkp_pool, 0, true);
        
    $pct1 =  percentage_raidcount(true, $dkp_pool, $range1, $member['member_id']);
    $pct2 =  percentage_raidcount(true, $dkp_pool, $range2, $member['member_id']);
    $pct3 =  percentage_raidcount(true, $dkp_pool, $range3, $member['member_id']);
    $pctlife = ( $pclife > 0 ) ? round(($mclife / $pclife) * 100, 1) : 0;
    
    $percent_of_raids = array(
        'x1'       => $mc1 .'/'. $pc1 .':'. $pct1,
        'x2'       => $mc2 .'/'. $pc2 .':'. $pct2,
        'x3'       => $mc3 .'/'. $pc3 .':'. $pct3,
        'lifetime' => $mclife .'/'. $pclife .':'. $pctlife
    );
    
    $dkppoolname= $member['dkpsys_name'];
    
    $template->assign_vars(array(
   		'GUILDTAG' =>  $config['bbdkp_guildtag'],
        'NAME'     =>  $member['member_name'],
        'PROFILE'  =>  $member['member_name'],
    	'POOL'		=> $dkppoolname , 

        'RAIDS_X1_DAYS'   => sprintf($user->lang['RAIDS_X_DAYS'], $range1),
        'RAIDS_X2_DAYS'   => sprintf($user->lang['RAIDS_X_DAYS'], $range2),
        'RAIDS_X3_DAYS'   => sprintf($user->lang['RAIDS_X_DAYS'], $range3),
        'RAIDS_LIFETIME'  => sprintf($user->lang['RAIDS_LIFETIME'],
               date($config['bbdkp_date_format'], $member['member_firstraid']),
               date($config['bbdkp_date_format'], $member['member_lastraid'])),
                                                
        'EARNED'         => $member['member_earned'],
        'SPENT'          => $member['member_spent'],
        'ADJUSTMENT'     => $member['member_adjustment'],
        'CURRENT'        => $member['member_current'],
                                                
        'RAIDS_X1_DAYS'  => $percent_of_raids['x1'],
        'RAIDS_X2_DAYS'  => $percent_of_raids['x2'],
        'RAIDS_X3_DAYS'  => $percent_of_raids['x3'],
        'RAIDS_LIFETIME' => $percent_of_raids['lifetime'],

		'C_ADJUSTMENT'     => $member['member_adjustment'],
        'C_CURRENT'        => $member['member_current'],
        'C_RAIDS_X1_DAYS'  => $percent_of_raids['x1'],
        'C_RAIDS_X2_DAYS'  => $percent_of_raids['x2'],
        'C_RAIDS_X3_DAYS'  => $percent_of_raids['x3'],
        'C_RAIDS_LIFETIME' => $percent_of_raids['lifetime'],

        'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . $member['member_name'] .'&amp;' . URI_DKPSYS . '=' . $dkp_pool . '&amp;')
    ));
    
    
    /*****************************
    /***   Raid Attendance   *****
    *****************************/
	// how many raids per page
    $raidlines = $config['bbdkp_user_rlimit'] ;
    
    $sort_order = array(
        0 => array('raid_name', 'raid_name desc'),
        1 => array('raid_count desc', 'raid_count')
    );
    $current_order = switch_order($sort_order);
    
    // Find $current_earned based on the page.  This prevents us having to pass the
    // current earned as a GET variable which could result in user error
    if (!isset($_GET['rstart']))  
    {
        $current_earned = $member['member_earned'];
        $rstart=0; 
    }
    else
    {
        $rstart = request_var('rstart',0) ;
        $current_earned = $member['member_earned'];
        
       	$sql_array = array(
	    	'SELECT'    => 	'sum(r.raid_value) as earned_result, count(*) as raidlines', 
	    	'FROM'      => array(
		        RAIDS_TABLE => 'r',
		        RAID_ATTENDEES_TABLE => 'ra',
		    	),
	 
	    	'WHERE'     =>  "(ra.raid_id = r.raid_id)
               AND (ra.member_name='" . $db->sql_escape($member['member_name']) ."')
               AND (r.raid_dkpid=" . (int) $dkp_pool . ' )', 
           );
               
    	$sql = $db->sql_build_query('SELECT', $sql_array);
    	$result = $db->sql_query($sql);
    	
        $current_earned = (int) $db->sql_fetchfield('earned_result');
        $raidlines = (int) $db->sql_fetchfield('raidlines');
        
        $db->sql_freeresult($earned_result);
        
    }
    
    // raid lines
    $sql_array = array(
    	'SELECT'    => 	'r.raid_id, r.raid_name, r.raid_date, r.raid_note, r.raid_value ', 
    	'FROM'      => array(
	        RAIDS_TABLE => 'r',
	        RAID_ATTENDEES_TABLE => 'ra',
	    	),
 
    	'WHERE'     =>  "(ra.raid_id = r.raid_id)
              AND (ra.member_name='" . $db->sql_escape($member['member_name']) ."')
              AND (r.raid_dkpid=" . (int) $dkp_pool . ' )', 
	    'ORDER_BY'  => 'r.raid_date DESC',
          );
              
   	$sql = $db->sql_build_query('SELECT', $sql_array);
    
    if (!$raids_result = $db->sql_query_limit($sql, $raidlines, $rstart))
    {
       trigger_error ($user->lang['MNOTFOUND']);
    }
    while ( $raid = $db->sql_fetchrow($raids_result) )
    {
        $template->assign_block_vars('raids_row', array(
            'DATE'           => ( !empty($raid['raid_date']) ) ? date($config['bbdkp_date_format'], $raid['raid_date']) : '&nbsp;',
            'U_VIEW_RAID'    => append_sid("{$phpbb_root_path}viewraid.$phpEx" , URI_RAID . '='.$raid['raid_id']),
            'NAME'           => ( !empty($raid['raid_name']) ) ? $raid['raid_name'] : '&lt;<em>'.$user->lang['EMPTYRAIDNAME'].'</em>&gt;',
            'NOTE'           => ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
            'EARNED'         => $raid['raid_value'],
            'CURRENT_EARNED' => sprintf("%.2f", $current_earned))
        );
        $current_earned -= $raid['raid_value'];
    }
    
    // get number of attended raids
    $db->sql_freeresult($raids_result);

	$total_attended_raids = memberraid_count($dkp_pool, 0, $member['member_id'], true); 
    
    /**********************************
    /***   Item purchase history  *****
    ***********************************/
    $itemlines = $config['bbdkp_user_ilimit'] ;
     
    if (!isset($_GET['istart']))
    {
        $current_spent = $member['member_spent'];
        $istart=0; 
    }
    else
    {
        $istart = request_var('istart', 0);
        $current_spent = $member['member_spent'];
        
        $sql = 'SELECT item_value FROM ' . ITEMS_TABLE . " WHERE item_buyer='" . $db->sql_escape($member['member_name']) . "' 
				AND  item_dkpid= " . (int) $dkp_pool . ' ORDER BY item_date DESC' ;
        $spent_result = $db->sql_query_limit($sql, $itemlines, $istart);
                
        if ( !$spent_result  )
        {
           trigger_error("Error in database");
        }
        
        while ( $cs_row = $db->sql_fetchrow($spent_result) )
        {
            $current_spent -= $cs_row['item_value'];
        }
        $db->sql_freeresult($spent_result);
    }

    
    // inner join the raids and items table
     $sql_array = array(
    	'SELECT'    => 	'i.item_id, i.item_name, i.item_value, i.item_date, i.raid_id, r.raid_name ', 
    	'FROM'      => array(
	        ITEMS_TABLE => 'i',
	        RAIDS_TABLE => 'r',
	    	),
 
    	'WHERE'     =>  "( r.raid_id = i.raid_id )
              AND ( i.item_buyer='" . $db->sql_escape($member['member_name']) . "')
              AND ( i.item_dkpid= " . (int) $dkp_pool . ' ) 
              AND ( r.raid_dkpid=' .  (int) $dkp_pool . ' )', 
	    	
	    'ORDER_BY'  => 'i.item_date DESC',
          );
              
   	$sql = $db->sql_build_query('SELECT', $sql_array);
    
    $items_result = $db->sql_query_limit($sql, $itemlines, $istart);
    if ( !$items_result)
    {
        trigger_error ($user->lang['MNOTFOUND']);
    }
    
	$bbDkp_Admin = new bbDkp_Admin;
	if ($bbDkp_Admin->bbtips == true)
	{
		if ( !class_exists('bbtips')) 
		{
			require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
		}
		$bbtips = new bbtips;
	}
	
    while ( $item = $db->sql_fetchrow($items_result) )
    {
		if ($bbDkp_Admin->bbtips == true)
		{
			$item_name = '<strong>' . $bbtips->parse('[itemdkp]' . $item['item_name']  . '[/itemdkp]') . '</strong>'; 
		}
		else
		{
			$item_name = '<strong>' . $item['item_name'] . '</strong>';
		}
		
        $template->assign_block_vars('items_row', array(
            'DATE'          => ( !empty($item['item_date']) ) ? date($config['bbdkp_date_format'], $item['item_date']) : '&nbsp;',
            'U_VIEW_ITEM'   => append_sid("{$phpbb_root_path}viewitem.$phpEx", URI_ITEM . '=' . $item['item_id']),
            'U_VIEW_RAID'   => append_sid("{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '=' . $item['raid_id']),
            'NAME'          => $item_name, 
            'RAID'          => ( !empty($item['raid_name']) ) ? $item['raid_name'] : '&lt;<i>Not Found</i>&gt;',
            'SPENT'         => $item['item_value'],
            'CURRENT_SPENT' => sprintf("%.2f", $current_spent))
        );
        $current_spent -= $item['item_value'];
    }
    $db->sql_freeresult($items_result);

    $sql6 = 'SELECT count(*) as itemcount FROM ' . ITEMS_TABLE . " 
    WHERE  item_dkpid= " .  $dkp_pool . " and item_buyer='" . $db->sql_escape($member['member_name']) . "'";
  	$result6 = $db->sql_query($sql6);
	$total_purchased_items = $db->sql_fetchfield('itemcount');
	$db->sql_freeresult($result6);  
	    
    $raidpag  = generate_pagination2(append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_DKPSYS.'='.$dkp_pool.
    '&amp;name='.$member['member_name']. '&amp;istart='.$istart), 
    $total_attended_raids, $raidlines, $rstart, 1, 'rstart');
	     
    $itpag =   generate_pagination2(append_sid("{$phpbb_root_path}viewmember.$phpEx" ,URI_DKPSYS.'='.$dkp_pool.
    '&amp;name='.$member['member_name'].  '&amp;rstart='.$rstart),
    $total_purchased_items,  $itemlines, $istart, 1 ,'istart');
	
	$template->assign_vars(array(

		'RAID_PAGINATION'      => $raidpag, 
        'RSTART' 		       => $rstart,   
        'RAID_FOOTCOUNT'      => sprintf($user->lang['VIEWMEMBER_RAID_FOOTCOUNT'], $total_attended_raids, $raidlines),
	
		'ITEM_PAGINATION'      => $itpag, 
        'ISTART'               => $istart, 
        'ITEM_FOOTCOUNT'       => sprintf($user->lang['VIEWMEMBER_ITEM_FOOTCOUNT'], $total_purchased_items, $itemlines),
		'ITEMS'					=> ( is_null($total_purchased_items) ) ? false : true,
    ));
    
	/****************************************
    ***** Individual Adjustment History   ***
    ****************************************/
    $sql = 'SELECT adjustment_value, adjustment_date, adjustment_reason
            FROM ' . ADJUSTMENTS_TABLE . '
            WHERE member_id = (select member_id from ' . MEMBER_LIST_TABLE . " where member_name='" . $db->sql_escape($member['member_name']) . "')
            AND ( adjustment_dkpid = " . (int) $dkp_pool . " )
            ORDER BY adjustment_date DESC";
            
    if ( !($adjustments_result = $db->sql_query($sql)) )
    {
        trigger_error ($user->lang['MNOTFOUND']);
    }
	
    $adjust	= null;
    
	while ( $adjustment = $db->sql_fetchrow($adjustments_result) )
    {
    	$adjust++;
        $template->assign_block_vars('adjustments_row', array(
            'DATE'                    => ( !empty($adjustment['adjustment_date']) ) ? date($config['bbdkp_date_format'], $adjustment['adjustment_date']) : '&nbsp;',
            'REASON'                  => ( !empty($adjustment['adjustment_reason']) ) ? $adjustment['adjustment_reason'] : '&nbsp;',
            'C_INDIVIDUAL_ADJUSTMENT' => $adjustment['adjustment_value'],
            'INDIVIDUAL_ADJUSTMENT'   => $adjustment['adjustment_value'])
        );
    }
    
       $template->assign_vars(array(
       
		'ADJUSTMENT_FOOTCOUNT' => sprintf($user->lang['VIEWMEMBER_ADJUSTMENT_FOOTCOUNT'], $adjust),
		'HASADJUSTMENT'			=> ( is_null($adjust) ) ? false : true,

    ));
    
	/****************************************
    ***** 		ATTENDANCE BY EVENT		   ***
    ****************************************/
    $raid_counts = array();

    // Find the count for each for for this member
    $sql = 'SELECT e.event_id, r.raid_name, count(ra.raid_id) AS raid_count
            FROM ' . EVENTS_TABLE . ' e, ' . RAID_ATTENDEES_TABLE . ' ra, ' . RAIDS_TABLE . " r
            WHERE (e.event_name = r.raid_name) 
            AND (e.event_dkpid = " . (int) $dkp_pool . ") 
            AND (r.raid_id = ra.raid_id)  
            AND (ra.member_name = '" . $db->sql_escape($member['member_name']) . "')
            AND (r.raid_date >= " . (int) $member['member_firstraid'] . ")
            GROUP BY ra.member_name, r.raid_name";
    
    $result = $db->sql_query($sql);
    while ( $row = $db->sql_fetchrow($result) )
    {
        // The count now becomes the percent
        $raid_counts[ $row['raid_name'] ] = $row['raid_count'];
        $event_ids[ $row['raid_name'] ] = $row['event_id'];
    }
    $db->sql_freeresult($result);

    // Find the count for reach raid
    $sql = 'SELECT raid_name, count(raid_id) AS raid_count
            FROM ' . RAIDS_TABLE . '
            WHERE raid_dkpid= ' . $dkp_pool . ' 
            and raid_date >= ' . (int) $member['member_firstraid'] . '
            GROUP BY raid_name';
    
    $result = $db->sql_query($sql);
    while ( $row = $db->sql_fetchrow($result) )
    {
        if ( isset($raid_counts[$row['raid_name']]) )
        {
            $percent = round(($raid_counts[ $row['raid_name'] ] / $row['raid_count']) * 100);
            $raid_counts[$row['raid_name']] = array(
            	'percent' => $percent, 
            	'count' => $raid_counts[ $row['raid_name'] ]);
            unset($percent);
        }
    }
    $db->sql_freeresult($result);

    // Since we can't sort in SQL for this case, we have to sort
    // by the array
    switch ( $current_order['sql'] )
    {
        // Sort by key
        case 'raid_name':
            ksort($raid_counts);
            break;
        case 'raid_name desc':
            krsort($raid_counts);
            break;
        // Sort by value (keeping relational keys in-tact)
        case 'raid_count':
            asort($raid_counts);
            break;
        case 'raid_count desc':
            arsort($raid_counts);
            break;
    }
    
    reset($raid_counts);
    foreach ( $raid_counts as $event => $data )
    {
        $template->assign_block_vars('event_row', array(
            'EVENT'        => $event,
            'U_VIEW_EVENT' => append_sid("{$phpbb_root_path}viewevent.$phpEx", URI_EVENT . '=' . $event_ids[$event] . '&' . URI_DKPSYS . '=' . $dkp_pool) ,
            'BAR'          => create_bar($data['percent'] . '%', $data['count'] . ' (' . $data['percent'] . '%)'))
        );
    }
  
    $navlinks_array = array(
    array(
     'DKPPAGE' => $user->lang['MENU_STANDINGS'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}listmembers.$phpEx"),
    ),

    array(
     'DKPPAGE' => sprintf($user->lang['MENU_VIEWMEMBER'], $member['member_name']) ,
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . $member['member_name'] .'&amp;' . URI_DKPSYS . '=' . $dkp_pool . '&amp;'), 
    ),
    );

    foreach( $navlinks_array as $name )
    {
	    $template->assign_block_vars('dkpnavlinks', array(
	    	'DKPPAGE' 	=> $name['DKPPAGE'],
	    	'U_DKPPAGE' => $name['U_DKPPAGE'],
	    ));
    }
    
    $template->assign_vars(array(
        'L_EVENTS_FOOTCOUNT'			=> sprintf($user->lang['VIEWMEMBER_EVENT_FOOTCOUNT'], count($raid_counts)), 
        'O_EVENT'   => $current_order['uri'][0],
        'O_PERCENT' => $current_order['uri'][1],
        'S_COMP' => ( isset($s_comp) ) ? false : true,
        
    ));
     unset($raid_counts, $event_ids);
    $db->sql_freeresult($adjustments_result);
    
    // Output page
    page_header($user->lang['MEMBER']);
    $template->set_filenames(array(
    	'body' => 'dkp/viewmember.html')
    );
    page_footer();

}
else
{
    trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
}

?>
