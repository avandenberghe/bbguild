<?php
/**
 * list of Members per Dkp pool
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
define ( 'IN_PHPBB', true );
$phpbb_root_path = (defined ( 'PHPBB_ROOT_PATH' )) ? PHPBB_ROOT_PATH : './';
$phpEx = substr ( strrchr ( __FILE__, '.' ), 1 );
include ($phpbb_root_path . 'common.' . $phpEx);
global $config;
$user->session_begin ();
$auth->acl ( $user->data );
$user->add_lang ( array ('mods/dkp_common' ) );
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
$user->setup ();
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

$list_p1 = (isset ( $config ['bbdkp_list_p1'] ) == true) ? $config ['bbdkp_list_p1'] : 30;
$list_p2 = (isset ( $config ['bbdkp_list_p2'] ) == true) ? $config ['bbdkp_list_p2'] : 90;

/* begin dkpsys pulldown */

// pulldown
$query_by_pool = false;
$defaultpool = 99; 

$dkpvalues[0] = $user->lang['ALL']; 
$dkpvalues[1] = '--------'; 
$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE;
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

$dkpsys_id = 0; 
if(isset( $_POST ['pool']) or isset( $_POST ['getdksysid']) or isset ( $_GET [URI_DKPSYS] ) )
{
	if (isset( $_POST ['pool']) )
	{
		$pulldownval = request_var('pool',  $user->lang['ALL']);
		if(is_numeric($pulldownval))
		{
			$query_by_pool = true;
			$dkpsys_id = intval($pulldownval); 	
		}
	}
	
	if (isset( $_POST ['getdksysid']) )
	{
		$query_by_pool = true;
		$dkpsys_id = request_var('getdksysid', 0); 
		
	}
	
	if (isset ( $_GET [URI_DKPSYS] ))
	{
		$query_by_pool = true;
		$dkpsys_id = request_var(URI_DKPSYS, 0); 
	}
}
else 
{
	$query_by_pool = true;
	$dkpsys_id = $defaultpool; 
}

foreach ( $dkpvalues as $key => $value )
{
	if(!is_array($value))
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value, 
			'SELECTED' => ($value == $dkpsys_id && $value != '--------') ? ' selected="selected"' : '',
			'DISABLED' => ($value == '--------' ) ? ' disabled="disabled"' : '',  
			'OPTION' => $value, 
		));
	}
	else 
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value['id'], 
			'SELECTED' => ($dkpsys_id == $value['id']) ? ' selected="selected"' : '', 
			'OPTION' => $value['text'], 
		));
		
	}
}


$query_by_pool = ($dkpsys_id != 0) ? true : false;
/**** end dkpsys pulldown  ****/

/***** begin armor-class pulldown ****/
$filtervalues [] = $user->lang['ALL']; 
$filtervalues [] = '--------';

// generic armor list
$sql = 'SELECT class_armor_type FROM ' . CLASS_TABLE . ' GROUP BY class_armor_type';
$result = $db->sql_query ( $sql );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$filtervalues [] = $row ['class_armor_type'];
	$armor_type [] = $row ['class_armor_type'];
}
$db->sql_freeresult ( $result );
$filtervalues [] = '--------';


// followup with classlist
$sql = 'SELECT class_name, class_id, class_min_level, class_max_level FROM ' . CLASS_TABLE . '';
$sql .= ' GROUP BY class_name order by class_id';
$result = $db->sql_query ( $sql );

while ( $row = $db->sql_fetchrow ( $result ) )
{
	$filtervalues [] = $row ['class_name'];
	$classname [] = $row ['class_name'];
}
$db->sql_freeresult ( $result );


$query_by_armor = 0;
$query_by_class = 0;
$submitfilter = (isset ( $_GET ['filter'] ) or isset ( $_POST ['filter'] )) ? true : false;
if ($submitfilter)
{
	$filter = request_var ( 'filter', '' );
	
	if ($filter == "All")
	{
		// select all
		$query_by_armor = 0;
		$query_by_class = 0;
	} 
		elseif (in_array ( $filter, $armor_type ))
	{
		// looking for an armor type
		$filter = preg_replace ( '/ Armor/', '', $filter );
		$query_by_armor = 1;
		$query_by_class = 0;
	} 
		elseif (in_array ( $filter, $classname ))
	{
		// looking for a class
		$query_by_class = 1;
		$query_by_armor = 0;
	}
}
 else
{
	// select all
	$query_by_armor = 0;
	$query_by_class = 0;
	$filter = 'All';
}

// dump filtervalues to dropdown template
foreach ( $filtervalues as $fid => $fname )
{
	$template->assign_block_vars ( 'filter_row', array (
		'VALUE' => $fname, 
		'SELECTED' => ($fname == $filter && $fname !=  '--------' ) ? ' selected="selected"' : '',
		'DISABLED' => ($fname == '--------' ) ? ' disabled="disabled"' : '', 
		'OPTION' => (! empty ( $fname )) ? $fname : '(All)' ) );
}

/***** end armor - class pulldown ****/


/* table select */


$sql_array = array(
    'SELECT'    => 	'm.member_dkpid, d.dkpsys_name, m.member_id, m.member_status,  m.member_lastraid, 
   					m.member_earned, m.member_spent, m.member_adjustment, 
   					(m.member_earned-m.member_spent+m.member_adjustment) AS member_current, 
   					l.member_name, l.member_level, l.member_race_id ,l.member_class_id, l.member_rank_id ,
       				r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix, 
       				c.class_name AS member_class, c.class_id, 
       				c.class_armor_type AS armor_type,
					c.class_min_level AS min_level,
					c.class_max_level AS max_level', 
 
    'FROM'      => array(
        MEMBER_DKP_TABLE 	=> 'm',
        DKPSYS_TABLE 		=> 'd',
        MEMBER_LIST_TABLE 	=> 'l',
        MEMBER_RANKS_TABLE  => 'r',
        CLASS_TABLE    		=> 'c',
    	),
 
    'WHERE'     =>  '(m.member_id = l.member_id)  
			AND (c.class_id = l.member_class_id) 
			AND (r.rank_id = l.member_rank_id) 
			AND (m.member_dkpid = d.dkpsys_id) 
			AND (l.member_guild_id = r.guild_id) ' ,
);

if  (isset($_POST['compare']) && isset($_POST['compare_ids']))
{
	 $compare =  request_var('compare_ids', array('' => 0)) ;
	 $sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('m.member_id', $compare, false, true);
}

if ($query_by_pool)
{
	$sql_array['WHERE'] .= ' AND m.member_dkpid = ' . $dkpsys_id . ' ';
}

if (isset ( $_GET ['rank'] ))
{
	$sql_array['WHERE'] .= " AND r.rank_name='" . request_var ( 'rank', '' ) . "'";
}

if ($query_by_class == 1)
{
	$sql_array['WHERE'] .= " AND c.class_name =  '" . $db->sql_escape ( $filter ) . "' ";
}

if ($query_by_armor == 1)
{
	$sql_array['WHERE'] .= " AND c.class_armor_type =  '" . $db->sql_escape ( $filter ) . "'";
}

$sql = $db->sql_build_query('SELECT', $sql_array);

if (! ($members_result = $db->sql_query ( $sql )))
{
	trigger_error ($user->lang['MNOTFOUND']);
}

$member_count = 0;

$memberarray = array ();

while ( $row = $db->sql_fetchrow ( $members_result ) )
{
	$member_display = true;
	
	//check if inactive members will be shown
	if ($config ['bbdkp_hide_inactive'] == '1')
	{
		if ($row ['member_status'] == '0')
		{
			$member_display = false;
		}
	}
	
	//also check if the rank can be shown
	if ($member_display)
	{
		//hide inactive rank
		$member_display = ($row ['rank_hide'] == '1') ? false : true;
	}
	
	//finally add to member array
	if ($member_display)
	{
		++$member_count;
		$memberarray [$member_count] ['class_id'] = $row ['class_id'];
		$memberarray [$member_count] ['member_id'] = $row ['member_id'];
		$memberarray [$member_count] ['dkpsys_name'] = $row ['dkpsys_name'];
		$memberarray [$member_count] ['count'] = $member_count;
		$memberarray [$member_count] ['member_name'] = $row ['member_name'];
		$memberarray [$member_count] ['member_status'] = $row ['member_status'];
		$memberarray [$member_count] ['rank_prefix'] = $row ['rank_prefix'];
		$memberarray [$member_count] ['rank_suffix'] = $row ['rank_suffix'];
		$memberarray [$member_count] ['rank_name'] = $row ['rank_name'];
		$memberarray [$member_count] ['rank_hide'] = $row ['rank_hide'];
		$memberarray [$member_count] ['member_level'] = $row ['member_level'];
		$memberarray [$member_count] ['member_class'] = $row ['member_class'];
		$memberarray [$member_count] ['armor_type'] = $row ['armor_type'];
		$memberarray [$member_count] ['member_earned'] = $row ['member_earned'];
		$memberarray [$member_count] ['member_spent'] = $row ['member_spent'];
		$memberarray [$member_count] ['member_adjustment'] = $row ['member_adjustment'];
		$memberarray [$member_count] ['member_current'] = $row ['member_current'];
		$memberarray [$member_count] ['member_lastraid'] = $row ['member_lastraid'];
		$memberarray [$member_count] ['attendanceP1'] = percentage_raidcount ( true, $row ['member_dkpid'], $list_p1, $row ['member_id'] );
		$memberarray [$member_count] ['attendanceP2'] = percentage_raidcount ( true,  $row ['member_dkpid'], $list_p2, $row ['member_id'] );
		$memberarray [$member_count] ['member_dkpid'] = $row ['member_dkpid'];
	}
}
$db->sql_freeresult ( $members_result );

// Obtain a list of columns
if (count ($memberarray))
{
	foreach ($memberarray as $key => $member)
	{
		$member_name [$key] = $member ['member_name'];
		$rank_name [$key] = $member ['rank_name'];
		$member_level [$key] = $member ['member_level'];
		$member_class [$key] = $member ['member_class'];
		$armor_type [$key] = $member ['armor_type'];
		$member_earned [$key] = $member ['member_earned'];
		$member_spent [$key] = $member ['member_spent'];
		$member_adjustment [$key] = $member ['member_adjustment'];
		$member_current [$key] = $member ['member_current'];
		$member_lastraid [$key] = $member ['member_lastraid'];
		$attendanceP1 [$key] = $member ['attendanceP1'];
		$attendanceP2 [$key] = $member ['attendanceP2'];
	}
	
	// do the custom sorting
	$sortorder = request_var ( URI_ORDER, 0 );
	switch ($sortorder)
	{
		case - 1 : //name
			array_multisort ( $member_name, SORT_DESC, $memberarray );
			break;
		case - 2 : //rank
			array_multisort ( $rank_name, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 3 : //level
			array_multisort ( $member_level, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 4 : //class
			array_multisort ( $member_class, SORT_DESC, $member_level, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 5 : //type
			array_multisort ( $member_name, SORT_DESC, $memberarray );
			break;
		case - 6 : //earned
			array_multisort ( $member_earned, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 7 : //spent
			array_multisort ( $member_spent, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 8 : //adjustment
			array_multisort ( $member_adjustment, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 9 : //current
			array_multisort ( $member_current, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 10 : //lastraid
			array_multisort ( $member_lastraid, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 11 : //raidattendance P1
			array_multisort ( $attendanceP1, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 12 : //raidattendance P2
			array_multisort ( $attendanceP2, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
		case - 13 : //dkpid
			array_multisort ( $member_name, SORT_DESC, $memberarray );
			break;
		
		case 1 : //name
			array_multisort ( $member_name, SORT_ASC, $memberarray );
			break;
		case 2 : //rank
			array_multisort ( $rank_name, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case 3 : //level
			array_multisort ( $member_level, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case 4 : //class
			array_multisort ( $member_class, SORT_ASC, $member_level, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case 5 : //type
			array_multisort ( $member_name, SORT_ASC, $memberarray );
			break;
		case 6 : //earned
			array_multisort ( $member_earned, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 7 : //spent
			array_multisort ( $member_spent, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 8 : //adjustment
			array_multisort ( $member_adjustment, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 9 : //current
			array_multisort ( $member_current, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 10 : //lastraid
			array_multisort ( $member_lastraid, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 11 : //raidattendance P1
			array_multisort ( $attendanceP1, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 12 : //raidattendance P2
			array_multisort ( $attendanceP2, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case 13 : //dkpid
			array_multisort ( $member_name, SORT_ASC, $memberarray );
			break;
	}
}

$show_all = ((isset ( $_GET ['show'] )) && (request_var ( 'show', '' ) == 'all')) ? true : false;


// dump to template
foreach ( $memberarray as $key => $member )
{
	
	$u_rank_search = append_sid ( "{$phpbb_root_path}listmembers.$phpEx" . '?rank=' . urlencode ( $member ['rank_name'] ) );
	
	// append inactive switch
	$u_rank_search .= (($config ['bbdkp_hide_inactive'] == 1) && (! $show_all)) ? '&amp;show=' : '&amp;show=all';
	
	// append armor or class filter
	$u_rank_search .= ($filter != 'All') ? '&amp;filter=' . $filter : '';
	
	$template->assign_block_vars ( 'members_row', array (
		'CSSCLASS' 		=> $config ['bbdkp_default_game'] . 'class' . $member ['class_id'], 
		'DKPCOLOUR1' 	=> ($member ['member_adjustment'] >= 0) ? 'green' : 'red', 
		'DKPCOLOUR2' 	=> ($row ['member_current'] >= 0) ? 'green' : 'red', 
		'ID' 			=> $member ['member_id'], 
		'MEMBER_DKPPOOL' => $member ['dkpsys_name'], 
		'COUNT' 		=> $member ['count'], 
		'NAME' 			=> $member ['rank_prefix'] . (($member ['member_status'] == '0') ? 
			'<em>' . $member ['member_name'] . '</em>' : 
			$member ['member_name']) . $member ['rank_suffix'], 
		'RANK_NAME' => $member ['rank_name'],
		'RANK_HIDE' => $member ['rank_hide'],
		'RANK_SEARCH' => $u_rank_search, 
		'LEVEL' => ($member ['member_level'] > 0) ? $member ['member_level'] : '&nbsp;', 
		'CLASS' => (! empty ( $member ['member_class'] )) ? $member ['member_class'] : '&nbsp;', 
		'ARMOR' => (! empty ( $member ['armor_type'] )) ? $member ['armor_type'] : '&nbsp;', 
		'EARNED' => $member ['member_earned'], 'SPENT' => $member ['member_spent'], 
		'ADJUSTMENT' => $member ['member_adjustment'], 
		'CURRENT' => $member ['member_current'], 
		'LASTRAID' => (! empty ( $member ['member_lastraid'] )) ? 
			date ( 'd.m.y', $member ['member_lastraid'] ) : 
			'&nbsp;', 
		'RAIDS_P1_DAYS' => $member ['attendanceP1'], 
		'RAIDS_P2_DAYS' => $member ['attendanceP2'], 
		'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}viewmember.$phpEx", 
			'&amp;' . URI_NAME . '=' . $member ['member_name'] . 
			'&amp;' . URI_DKPSYS . '=' . $member ['member_dkpid']) ) );
}


$s_showlb = true;
leaderboard ( $dkpsys_id, $query_by_pool );

// Added to the end of the sort links
$uri_addon = '';
$uri_addon .= '&amp;filter=' . urlencode ( $filter );
$uri_addon .= (isset ( $_GET ['show'] )) ? '&amp;show=' . request_var ( 'show', '' ) : '';

/* sorting links */
$sortlink = array ();
for($i = 1; $i <= 13; $i ++)
{
	if (isset ( $sortorder ) && $sortorder == $i)
	{
		$j = - $i;
	} else
	{
		$j = $i;
	}
	
	{
		if ($query_by_pool)
		{
			$sortlink [$i] = append_sid ( "{$phpbb_root_path}listmembers.$phpEx", URI_ORDER. '=' . $j . $uri_addon . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id );
		} else
		{
			$sortlink [$i] = append_sid ( "{$phpbb_root_path}listmembers.$phpEx", URI_ORDER. '=' . $j . $uri_addon . '&amp;' . URI_DKPSYS . '=All' );
		}
	}

}

// footcount link
if (($config ['bbdkp_hide_inactive'] == 1) && (! $show_all))
{
	$flink = '<a href="' . append_sid ( "{$phpbb_root_path}listmembers.$phpEx", 
		'&amp;' . URI_ORDER . '=' . $j . '&amp;show=all' . 
		'&amp;' . URI_DKPSYS . '=' . $dkpsys_id ) . '" class="rowfoot">';
	$footcount_text = sprintf ( $user->lang ['LISTMEMBERS_ACTIVE_FOOTCOUNT'], $member_count, $flink );
} 
else
{
	$footcount_text = sprintf ( $user->lang ['LISTMEMBERS_FOOTCOUNT'], $member_count );
}

$template->assign_vars ( array ('F_MEMBERS' => append_sid ( "{$phpbb_root_path}listmembers.$phpEx" ), 

	'F_DKPSYS_NAME' => (isset ( $dkpsysname ) == true) ? $dkpsysname : 'All', 
	'F_DKPSYS_ID' => (isset ( $dkpsys_id ) == true) ? $dkpsys_id : 0, 
	'O_NAME' => $sortlink [1], 
	'O_RANK' => $sortlink [2], 
	'O_LEVEL' => $sortlink [3], 
	'O_CLASS' => $sortlink [4], 
	'O_ARMOR' => $sortlink [5], 
	'O_EARNED' => $sortlink [6], 
	'O_SPENT' => $sortlink [7], 
	'O_ADJUSTMENT' => $sortlink [8], 
	'O_CURRENT' => $sortlink [9], 
	'O_LASTRAID' => $sortlink [10], 
	'O_RAIDS_P1_DAYS' => $sortlink [11], 
	'O_RAIDS_P2_DAYS' => $sortlink [12], 
	'O_POOL' => $sortlink [13], 
	'RAIDS_P1_DAYS' => sprintf ( $user->lang ['RAIDS_X_DAYS'], $list_p1 ), 
	'RAIDS_P2_DAYS' => sprintf ( $user->lang ['RAIDS_X_DAYS'], $list_p2 ), 
	'S_SHOWLEAD' => $s_showlb, 
	
	'FOOTCOUNT' => (isset ( $_POST ['compare'] )) ? 
		sprintf ( $footcount_text, sizeof (request_var ( 'compare_ids', array ('' => 0 )))) : 
		$footcount_text )

 );

// Output page
page_header ( $user->lang ['LISTMEMBERS_TITLE'] );
$template->set_filenames ( array ('body' => 'dkp/listmembers.html' ) );
page_footer ();

// end 

function leaderboard($dkpsys_id, $query_by_pool)
{
	// get needed global vars
	global $db, $template, $config;
	global $phpbb_root_path, $phpEx;
	
	$sql = 'SELECT class_id, class_name FROM ' . CLASS_TABLE . ' where class_id != 0 order by class_name';
	$result = $db->sql_query ( $sql );
	$classes = array ();
	
	while ( $row = $db->sql_fetchrow ( $result ) )
	{
		$cssclass = $config ['bbdkp_default_game'] . 'class' . $row ['class_id'];
		$template->assign_block_vars ( 'class', 
			array ('CLASSNAME' 		=> $row ['class_name'], 
					'CLASSIMGPATH' 	=> $config ['bbdkp_default_game'] . '_' . $row ['class_name'] . '_small.png', 
					'CSSCLASS' => $config ['bbdkp_default_game'] . 'class' . $row ['class_id'] ) 
			);
		
		$sql_array = array(
		    'SELECT'    => 	'm.member_dkpid , m.member_id, m.member_status, 
    						(m.member_earned-m.member_spent+m.member_adjustment) AS member_current, l.member_name,
        					r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix ', 
		 
		    'FROM'      => array(
		        MEMBER_DKP_TABLE 	=> 'm',
		        MEMBER_LIST_TABLE 	=> 'l',
		        MEMBER_RANKS_TABLE  => 'r',
		    	),
		 
		    'WHERE'     =>  ' (m.member_id = l.member_id) 
				        AND ( l.member_class_id = ' . $db->sql_escape ( $row ['class_id'] ) . ' )  
				        AND (r.rank_id = l.member_rank_id) 
				        AND (r.guild_id = l.member_guild_id)
				        AND rank_hide = 0'
		);
		
		if ($query_by_pool)
		{
			$sql_array['WHERE'] .= ' AND m.member_dkpid = ' . $dkpsys_id . ' ';
		}
		
		if ($config ['bbdkp_hide_inactive'] == '1')
		{
			// are we hiding inactive members ?
			$sql_array['WHERE'] .= " AND m.member_status <> '0'";
		}
		
		$sql_array['ORDER_BY'] = "member_current DESC";
		
		$sql = $db->sql_build_query('SELECT', $sql_array);
		
		$result2 = $db->sql_query ( $sql );
		while ( $dkprow = $db->sql_fetchrow ( $result2 ) )
		{
			
			//dkp data per class
			$template->assign_block_vars ( 'class.dkp_row', array (
				'NAME' => $dkprow ['rank_prefix'] . (($dkprow ['member_status'] == '0') ? '<em>' . $dkprow ['member_name'] . '</em>' : $dkprow ['member_name']) . $dkprow ['rank_suffix'], 
				'CURRENT' => $dkprow ['member_current'], //point color
				'DKPCOLOUR' => ($dkprow ['member_current'] >= 0) ? 'style="font-size :8pt; color: green; text-align: right;"' : 'style="font-size :8pt; color: red; text-align: right;"', 
				'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}viewmember.$phpEx", '&amp;' . 
						URI_NAME . '=' . $dkprow ['member_name'] . '&amp;' . 
						URI_DKPSYS . '=' . $dkprow['member_dkpid'] ) ) );
		}
		$db->sql_freeresult ( $result2 );
	}
}

?>