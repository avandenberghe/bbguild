<?php
/**
 * statistics page
 * 
 * @package bbDKP
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

/* begin dkpsys pulldown */

// pulldown
$query_by_pool = false;
$defaultpool = 99; 

$dkpvalues[0] = $user->lang['ALL']; 
$dkpvalues[1] = '--------'; 
// find only pools with dkp records
$sql_array = array(
	'SELECT'    => 'a.dkpsys_id, a.dkpsys_name, a.dkpsys_default', 
	'FROM'		=> array( 
				DKPSYS_TABLE => 'a', 
				MEMBER_DKP_TABLE => 'd',
				), 
	'WHERE'  => ' a.dkpsys_id = d.member_dkpid', 
	'GROUP_BY'  => 'a.dkpsys_id'
); 
$sql = $db->sql_build_query('SELECT', $sql_array);
$result = $db->sql_query ( $sql );
$index = 3;
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$dkpvalues[$index]['id'] = $row ['dkpsys_id']; 
	$dkpvalues[$index]['text'] = $row ['dkpsys_name']; 
	if (strtoupper ( $row ['dkpsys_default'] ) == 'Y')
	{
		$defaultpool = $row ['dkpsys_id'];
	}
	$index +=1;
}
$db->sql_freeresult ( $result );

$dkp_id = 0; 
if(isset( $_POST ['pool']) or isset( $_POST ['getdksysid']) or isset ( $_GET [URI_DKPSYS] ) )
{
	if (isset( $_POST ['pool']) )
	{
		$pulldownval = request_var('pool',  $user->lang['ALL']);
		if(is_numeric($pulldownval))
		{
			$query_by_pool = true;
			$dkp_id = intval($pulldownval); 	
		}
	}
	elseif (isset ( $_GET [URI_DKPSYS] ))
	{
		$query_by_pool = true;
		$dkp_id = request_var(URI_DKPSYS, 0); 
	}
}
else 
{
	$query_by_pool = true;
	$dkp_id = $defaultpool; 
}

foreach ( $dkpvalues as $key => $value )
{
	if(!is_array($value))
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value, 
			'SELECTED' => ($value == $dkp_id && $value != '--------') ? ' selected="selected"' : '',
			'DISABLED' => ($value == '--------' ) ? ' disabled="disabled"' : '',  
			'OPTION' => $value, 
		));
	}
	else 
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value['id'], 
			'SELECTED' => ($dkp_id == $value['id']) ? ' selected="selected"' : '', 
			'OPTION' => $value['text'], 
		));
		
	}
}


$query_by_pool = ($dkp_id != 0) ? true : false;
/**** end dkpsys pulldown  ****/


/**** column sorting *****/
$sort_order = array(
     0 => array('member_name', 'member_name desc'),
     1 => array('member_firstraid', 'member_firstraid desc'),
     2 => array('member_lastraid', 'member_lastraid desc'),
     3 => array('member_raidcount desc', 'member_raidcount'),
     4 => array('member_earned desc', 'member_earned'),
     5 => array('earned_per_day desc', 'earned_per_day'),
     6 => array('earned_per_raid desc', 'earned_per_raid'),
     7 => array('member_spent desc', 'member_spent'),
     8 => array('spent_per_day desc', 'spent_per_day'),
     9 => array('spent_per_raid desc', 'spent_per_raid'),
    10 => array('lost_to_adjustment desc', 'lost_to_adjustment'),
    11 => array('lost_to_spent desc', 'lost_to_spent'),
    12 => array('member_current desc', 'member_current')
);

$current_order = switch_order($sort_order);
$sort_index = explode('.', $current_order['uri']['current']);
$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
$previous_data = '';


// get raidcount
$sql = 'SELECT count(*) as raidcount FROM ' . RAIDS_TABLE . ' r, ' . EVENTS_TABLE . ' e where r.event_id = e.event_id ';
if ($query_by_pool)
{
    $sql .= ' AND event_dkpid = '. $dkp_id; 
}
$result = $db->sql_query($sql);
$total_raids = (int) $db->sql_fetchfield('raidcount',0,$result);   
$db->sql_freeresult ( $result );

$show_all = ( (isset($_GET['show'])) && (request_var('show', '') == "all") ) ? true : false;


$sql_array = array(
    'SELECT'    => 	'l.member_name,	l.member_class_id, r.rank_prefix, r.rank_suffix, 
		c1.name as classr_name, c.colorcode, 
		m.member_id, 
		m.member_dkpid, 
		m.member_earned, 
		m.member_spent, 
		m.member_adjustment,
        (m.member_earned-m.member_spent+m.member_adjustment) AS member_current,
        m.member_firstraid, 
        m.member_lastraid, 
        m.member_raidcount,
        ((m.member_spent/m.member_earned)*100) AS lost_to_spent,
        ((m.member_adjustment-(m.member_adjustment*2))/m.member_earned)*100 AS lost_to_adjustment,
        (m.member_earned / ((('.time().' - m.member_firstraid)+86400) / 86400) ) AS earned_per_day,
        (('.time().' - member_firstraid) / 86400) AS zero_check,
        m.member_spent   / ((('.time().' - m.member_firstraid)+86400) / 86400) AS spent_per_day,
        m.member_earned/m.member_raidcount AS earned_per_raid,
        m.member_spent/m.member_raidcount AS spent_per_raid', 
 
    'FROM'      => array(
        CLASS_TABLE 		=> 'c',
        MEMBER_DKP_TABLE 	=> 'm',
        MEMBER_LIST_TABLE 	=> 'l',
        MEMBER_RANKS_TABLE  => 'r',
        BB_LANGUAGE			=> 'c1'
    	),
 
    'WHERE'     =>  "l.member_id=m.member_id 
        AND l.member_guild_id = r.guild_id 
        AND l.member_rank_id = r.rank_id
        AND l.member_class_id = c.class_id 
    	AND c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'" ,
    	
    	
    'ORDER_BY' => $current_order['sql'], 
);

if ($query_by_pool)
{
	$sql_array['WHERE'] .= ' AND m.member_dkpid = ' . $dkp_id . ' ';
}

if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
{
    $sql_array['WHERE'] .= " AND m.member_status='1'";
}

$sql = $db->sql_build_query('SELECT', $sql_array);

if ( !($members_result = $db->sql_query($sql)) )
{
    trigger_error ($user->lang['MNOTFOUND']);
}

$member_count = 0;

while ( $row = $db->sql_fetchrow($members_result) )
{
	$member_count++;
	
	$colorcode = $row['colorcode'];
	
    // Default the values of these in case they have no earned or spent or adjustment   
    $row['earned_per_day'] = ( ( (!empty($row['earned_per_day']) ) && ( $row['zero_check'] > '0.01') )) ? $row['earned_per_day'] : '0.00';
    $row['earned_per_raid'] = (!empty($row['earned_per_raid'])) ? $row['earned_per_raid'] : '0.00';
    
    $row['spent_per_day'] = ( ( (!empty($row['spent_per_day']) ) && ($row['zero_check'] > '0.01') )) ? $row['spent_per_day'] : '0.00';
    $row['spent_per_raid'] = (!empty($row['spent_per_raid'])) ? $row['spent_per_raid'] : '0';
    
    $row['lost_to_adjustment'] = (!empty($row['lost_to_adjustment'])) ? $row['lost_to_adjustment'] : '0.00';
    $row['lost_to_spent'] = (!empty($row['lost_to_spent'])) ? $row['lost_to_spent'] : '0.00';
	  
    // Find out how many days it's been since their first raid
    $days_since_start = 0;
    $days_since_start = round((time() - $row['member_firstraid']) / 86400);

    // Find the percentage of raids they've been on
    $attended_percent = ( $total_raids > 0 ) ? round(($row['member_raidcount'] / $total_raids) * 100) : 0;

    $cssdkpcolour2 = ($row['member_current'] >= 0)? 'style="font-size :8pt; color: green; text-align: right;"' : 'style="font-size :8pt; color: red; text-align: right;"' ;
    

    $template->assign_block_vars('stats_row', array(
    	'COLORCODE'				=> $colorcode,
    	'ID'            		=> $row['member_id'],
	    'COUNT'         		=> ($row[$previous_source] == $previous_data) ? '&nbsp;' : $member_count,
        'U_VIEW_MEMBER' 		=> append_sid("{$phpbb_root_path}viewmember.$phpEx" , URI_DKPSYS . '=' . $row['member_dkpid'] . '&amp;' . URI_NAMEID . '='.$row['member_id']),    
        'NAME' 					=> $row['rank_prefix'] . $row['member_name'] . $row['rank_suffix'],
        'FIRST_RAID' 			=> ( !empty($row['member_firstraid']) ) ? date($config['bbdkp_date_format'], $row['member_firstraid']) : '&nbsp;',
        'LAST_RAID' 			=> ( !empty($row['member_lastraid']) ) ? date($config['bbdkp_date_format'], $row['member_lastraid']) : '&nbsp;',
        'ATTENDED_COUNT' 		=> $row['member_raidcount'],
        'C_ATTENDED_PERCENT' 	=> $attended_percent, true,
        'ATTENDED_PERCENT' 		=> $attended_percent,
        'EARNED_TOTAL' 			=> $row['member_earned'],
        'EARNED_PER_DAY' 		=> sprintf("%.2f", $row['earned_per_day']),
        'EARNED_PER_RAID' 		=> sprintf("%.2f", $row['earned_per_raid']),
        'SPENT_TOTAL' 			=> $row['member_spent'],
        'SPENT_PER_DAY' 		=> sprintf("%.2f", $row['spent_per_day']),
        'SPENT_PER_RAID' 		=> sprintf("%.2f", $row['spent_per_raid']),
        'LOST_TO_ADJUSTMENT'	=> sprintf("%.2f", $row['lost_to_adjustment']),
        'LOST_TO_SPENT' 		=> sprintf("%.2f", $row['lost_to_spent']),
        'C_CURRENT' 			=> $row['member_current'],
        'CURRENT' 				=> $row['member_current'], 
        'DKPCOLOUR'				=> $cssdkpcolour2
    )
    );

    $previous_data = $row[$previous_source];
}

if ( ($config['bbdkp_hide_inactive'] == 1) && (!$show_all) )
{
    $footcount_text = sprintf($user->lang['STATS_ACTIVE_FOOTCOUNT'], $db->sql_affectedrows($members_result),
    '<a href="' . append_sid("{$phpbb_root_path}stats.$phpEx" , 'o='.$current_order['uri']['current']. '&amp;show=all' ) . '" class="rowfoot">');
}
else
{
    $footcount_text = sprintf($user->lang['STATS_FOOTCOUNT'], $db->sql_affectedrows($members_result));
}



/************************
 *  
 *  Class Statistics 
 *  
 **********/

$classes = array();

// Find total # members with a dkp record
$sql = 'SELECT count(member_id) AS members FROM ' . MEMBER_DKP_TABLE ;
if ($query_by_pool)
{
    $sql .= ' where member_dkpid = '. $dkp_id . ' ';
}
$result = $db->sql_query($sql);
$total_members = (int) $db->sql_fetchfield('members');

// Find total # drops 
$sql_array = array (
	'SELECT' => ' count(item_id) AS items ', 
	'FROM' => array (
		EVENTS_TABLE => 'e', 
		RAIDS_TABLE => 'r', 
		RAID_ITEMS_TABLE => 'i', 
		), 
	'WHERE' => ' e.event_id = r.event_id 
			  AND i.raid_id = r.raid_id
			  AND item_value != 0', 
	'GROUP_BY' => 'i.item_name', 
);
if ($query_by_pool)
{
  $sql_array['WHERE'] .= ' and event_dkpid = '. $dkp_id . ' ';
}
$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query($sql);
$total_drops = (int) $db->sql_fetchfield('items');
$db->sql_freeresult($result);

// get #classcount, #drops per class
$sql_array = array(
    'SELECT'    => 	'c1.name as class_name,  c.class_id , c.colorcode, 
    	c.imagename, count(m.member_id) AS class_count, count(i.item_id) as itemcount ', 
    'FROM'      => array(
       MEMBER_DKP_TABLE => 'm',
        CLASS_TABLE 		=> 'c',
        MEMBER_DKP_TABLE 	=> 'm',
        MEMBER_LIST_TABLE  	=> 'l',
        BB_LANGUAGE			=> 'c1'
    	),
    
    'LEFT_JOIN' => array(
        array(
            'FROM'  => array(RAID_ITEMS_TABLE => 'i'),
            'ON'    => 'm.member_id=i.member_id'
        )
    ),
    
    'WHERE'     =>  "m.member_id = l.member_id 
        AND l.member_class_id = c.class_id 
    	AND c1.attribute_id = c.class_id 
    	AND c1.language= '" . $config['bbdkp_lang'] . "' 
    	AND c1.attribute = 'class'" ,
    	
    'GROUP_BY' => ' c1.name, c.class_id ',	
     
);

if ($query_by_pool)
{
     $sql_array['WHERE'] .= ' AND m.member_dkpid = '. $dkp_id . ' ';
}
$sql = $db->sql_build_query('SELECT', $sql_array);
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result) )
{
	// get class count and pct
	$class_count = $row['class_count'];
	$classpct = (float) ($total_members > 0) ? ($row['class_count'] / $total_members) * 100  : 0;
	
	// get drops per class and pct
    $class_drop_pct = ( $total_drops > 0 ) ? round(( (int) $row['itemcount'] / $total_drops) * 100) : 0;

    // class factor is the absolute ratio of #classdrops to #classcount
    // so it's the average droprate per class 
    $class_factor = ( $row['class_count'] > 0 ) ? round(( (int) $row['itemcount'] / $row['class_count'])) : 0;
    
    //the loot factor is the ratio of class drops pct to class pct. 
    // this should be close to 100, meaning  that this class gets an even amount of loot.
    // if loot factor is > 100 then this class gets above proportional loot
	// if loot factor is < 100 then this class gets below proportional loot
    // positive interval is [60% to 140%], anything outside that is a serious inbalance.
    $loot_factor = ( $classpct > 0 ) ? round( ( ( $class_drop_pct / $classpct) ) * 100) : '0';

    if ($query_by_pool)
    {
        $lmlink =  append_sid("{$phpbb_root_path}listmembers.$phpEx" , 'filter=class_' . $row['class_id'] . '&amp;' . URI_DKPSYS .'=' . $dkp_id); 
    }
    else 
    {
        $lmlink =  append_sid("{$phpbb_root_path}listmembers.$phpEx" , 'filter=class_' . $row['class_id']);
    }
    
    $template->assign_block_vars('class_row', array(
    	'U_LIST_MEMBERS' 	=> $lmlink ,
		'COLORCODE'  	=> ($row['colorcode'] == '') ? '#123456' : $row['colorcode'],
    	'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',  
		'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false, 		
        'CLASS_NAME'	=> $row['class_name'],

        'CLASS_COUNT' 		=> (int) $class_count,
        'CLASS_PCT' 		=> sprintf("%s %%", $classpct ),
    	
        'LOOT_COUNT' 		=> (int) $row['itemcount'],
    	'CLASS_DROP_PCT'	=> sprintf("%s %%", $class_drop_pct  ),
    
    	'CLASS_FACTOR'		=> sprintf("%s", $class_factor),
    
    	'LOOT_FACTOR'		=> sprintf("%s %%", $loot_factor),
    	'C_LOOT_FACTOR'		=> ($loot_factor < 	60 || $loot_factor > 140 ) ? 'negative' : 'positive', 
    
		)
    );
}


$sortlink = array();
for ($i=0; $i<=12; $i++)
{
    if ($query_by_pool)
    {
        $sortlink[$i] = append_sid("{$phpbb_root_path}stats.$phpEx", 'o=' . $current_order['uri'][$i] . '&amp;' . URI_DKPSYS . '=' . $dkp_id ); 
    }
    else 
    {
        $sortlink[$i] = append_sid("{$phpbb_root_path}stats.$phpEx", 'o=' . $current_order['uri'][$i] . '&amp;' . URI_DKPSYS . '=All'  ); 
    }
}

    
$navlinks_array = array(
array(
 'DKPPAGE' => $user->lang['MENU_STATS'],
 'U_DKPPAGE' => append_sid("{$phpbb_root_path}stats.$phpEx"),
)); 

foreach( $navlinks_array as $name )
{
	 $template->assign_block_vars('dkpnavlinks', array(
	 'DKPPAGE' => $name['DKPPAGE'],
	 'U_DKPPAGE' => $name['U_DKPPAGE'],
	 ));
}
    
$template->assign_vars(array(

	'F_STATS' => append_sid("{$phpbb_root_path}stats.$phpEx"),

    'O_NAME'       => $sortlink[0] ,
    'O_FIRSTRAID' => $sortlink[1] ,
	'O_LASTRAID' =>  $sortlink[2],
    'O_RAIDCOUNT' =>  $sortlink[3] ,
    'O_EARNED' => $sortlink[4] ,
    'O_EARNED_PER_DAY' => $sortlink[5] , 
    'O_EARNED_PER_RAID' => $sortlink[6] , 
    'O_SPENT' => $sortlink[7] , 
    'O_SPENT_PER_DAY' => $sortlink[8] , 
    'O_SPENT_PER_RAID' => $sortlink[9] , 
    'O_LOST_TO_ADJUSTMENT' => $sortlink[10] , 
    'O_LOST_TO_SPENT' => $sortlink[11] , 
    'O_CURRENT' => $sortlink[12] , 

	'U_STATS' => append_sid("{$phpbb_root_path}stats.$phpEx"),
    'SHOW' => ( isset($_GET['show']) ) ? request_var('show', '') : '',
    'STATS_FOOTCOUNT' => $footcount_text,
	'TOTAL_MEMBERS' 	=> $total_members, 
	'TOTAL_DROPS' 		=> $total_drops, 
	


    )
);

$title = $user->lang['MENU_STATS'];

// Output page
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/stats.html')
);

page_footer();

?>