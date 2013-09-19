<?php
/**
 * @package bbDKP
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

$query_by_pool = '';
$query_by_armor = '';
$query_by_class = '';
$filter = '';

$dkpsys_id = $this->dkppulldown();
$classarray = $this->armor();
$startd = request_var ( 'startdkp', 0 );
$arg='';
if ($query_by_pool)
{
    $arg = '&amp;' . URI_DKPSYS. '=' . $dkpsys_id;
}

if(	$query_by_armor or $query_by_class)
{
	$arg .= '&amp;filter=' . $filter; 
}
else 
{
	$arg .= '&amp;filter=all';
}

$u_listmembers = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=standings' . $arg );

$installed_games = array();
foreach($this->games as $gameid => $gamename)
{
	if ($config['bbdkp_games_' . $gameid] == 1)
	{
		$installed_games[$gameid] = $gamename; 
	} 
}

$show_all = ((isset ( $_GET ['show'] )) && (request_var ( 'show', '' ) == 'all')) ? true : false;

$memberarray = $this->get_standings($dkpsys_id, $installed_games, $startd, $show_all);

// Obtain a list of columns for sorting the array
if (count ($memberarray))
{
	foreach ($memberarray as $key => $member)
	{
		$member_name [$key] = $member ['member_name'];
		$rank_name [$key] = $member ['rank_name'];  
		$member_level [$key] = $member ['member_level']; 
		$member_class [$key] = $member ['member_class']; 
		$armor_type [$key] = $member ['armor_type']; 
		$member_raid_value [$key] = $member ['member_raid_value'];
		
		if($config['bbdkp_timebased'] == 1)
		{
			$member_time_bonus [$key] ['member_time_bonus'] = $member ['member_time_bonus'];
			
		}
		if($config['bbdkp_zerosum'] == 1)
		{
			$member_zerosum_bonus [$key] ['member_zerosum_bonus'] = $member ['member_zerosum_bonus'];
		}
		
		$member_earned [$key] = $member ['member_earned']; //*
		$member_adjustment [$key] = $member ['member_adjustment']; //*
		
		if($config['bbdkp_decay'] == 1)
		{
			$member_raid_decay[$key]['member_raid_decay'] = $member['member_raid_decay']; 
			$member_item_decay[$key]['member_item_decay'] = $member['member_item_decay']; 
		}
		
		if($config['bbdkp_epgp'] == 1)
		{
			$ep[$key]['ep'] = $member['ep']; 
			$gp[$key]['gp'] = $member['gp']; 
			$pr[$key]['pr'] = $member['pr']; 
		}
		
		$member_spent [$key] = $member ['member_spent'];
		$member_current [$key] = $member ['member_current']; 
		$member_lastraid [$key] = $member ['member_lastraid']; 
		$attendanceP1 [$key] = $member ['attendanceP1']; 
	}
	
	
	// do the multi-dimensional sorting
	$sortorder = request_var ( URI_ORDER, 0 );
	switch ($sortorder)
	{
		case - 1 : //name
			array_multisort ( $member_name, SORT_DESC, $memberarray );
			break;
		case 1 : //name
			array_multisort ( $member_name, SORT_ASC, $memberarray );
			break;
		case - 2 : //rank
			array_multisort ( $rank_name, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case 2 : //rank
			array_multisort ( $rank_name, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 3 : //level
			array_multisort ( $member_level, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case 3 : //level
			array_multisort ( $member_level, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case 4 : //class
			array_multisort ( $member_class, SORT_ASC, $member_level, SORT_ASC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 4 : //class
			array_multisort ( $member_class, SORT_DESC, $member_level, SORT_DESC, $member_name, SORT_DESC, $memberarray );
			break;
		case 5 : //armor
			array_multisort ( $member_name, SORT_ASC, $memberarray );
			break;
		case - 5 : //armor
			array_multisort ( $member_name, SORT_DESC, $memberarray );
			break;
			
		case 6 : //member_raid_value
			array_multisort ( $member_raid_value, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 6 : //member_raid_value
			array_multisort ( $member_raid_value , SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;			
			
		case 7 : //bbdkp_dkphour
			array_multisort ( $member_time_bonus, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 7 : //bbdkp_dkphour
			array_multisort ( $member_time_bonus , SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;			

		case 8 : //member_zerosum_bonus
			array_multisort ( $member_zerosum_bonus, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 8 : //member_zerosum_bonus
			array_multisort ( $member_zerosum_bonus , SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;

		case 9 : //earned
			array_multisort ( $member_earned, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 9 : //earned
			array_multisort ( $member_earned, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 10 : //adjustment
			array_multisort ( $member_adjustment, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 10 : //adjustment
			array_multisort ( $member_adjustment, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 11 : //ep decay
			array_multisort ( $member_raid_decay, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 11 : //ep decay
			array_multisort ( $member_raid_decay, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;			
			
		case 12 : //ep 
			array_multisort ( $ep, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 12 : //ep 
			array_multisort ( $ep, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 13 : //spent
			array_multisort ( $member_spent, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 13 : //spent
			array_multisort ( $member_spent, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 14 : //item_decay 
			array_multisort ( $member_item_decay, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 14 : //item_decay 
			array_multisort ( $member_item_decay, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 15 : // gp
			array_multisort ( $gp, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 15 : // gp 
			array_multisort ( $gp, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 16 : // Pr
			array_multisort ( $pr, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 16 : // Pr 
			array_multisort ( $pr, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;			
			
		case 17 : //current
			array_multisort ( $member_current, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 17 : //current
			array_multisort ( $member_current, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
			
		case 18 : //lastraid
			array_multisort ( $member_lastraid, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 18 : //lastraid
			array_multisort ( $member_lastraid, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;

		case 19 : //raidattendance P1
			array_multisort ( $attendanceP1, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 19 : //raidattendance P1
			array_multisort ( $attendanceP1, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;

	}
}

// loop sorted member array and dump to template
foreach ( $memberarray as $key => $member )
{
	
	$u_rank_search = append_sid ( "{$phpbb_root_path}dkp.$phpEx" , 'page=standings&amp;rank=' . urlencode ( $member ['rank_name'] ) );
	
	// append inactive switch
	$u_rank_search .= (($config ['bbdkp_hide_inactive'] == 1) && (! $show_all)) ? '&amp;show=' : '&amp;show=all';
	
	// append armor or class filter
	$u_rank_search .= ($filter != $user->lang['ALL']) ? '&amp;filter=' . $filter : '';
	
	$templatearray = array (
		'COLORCODE' 	=> $member ['colorcode'],
		'DKPNAME'		=> $member ['dkpsys_name'],  
		'CLASS_IMAGE' 	=> $member['class_image'],
		'S_CLASS_IMAGE_EXISTS' =>  $member['class_image_exists'],
		'RACE_IMAGE' 	=> $member['race_image'],
		'S_RACE_IMAGE_EXISTS' =>  $member['race_image_exists'],
		'DKPCOLOUR1' 	=> ($member ['member_adjustment'] >= 0) ? 'positive' : 'negative', 
		'DKPCOLOUR2' 	=> ($member ['member_current'] >= 0) ? 'positive' : 'negative', 
		'ID' 			=> $member ['member_id'], 
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
		'EARNED' => $member ['member_earned'], 
		'ADJUSTMENT' => $member ['member_adjustment'], 
		'SPENT' => $member ['member_spent'],
		'CURRENT' => $member ['member_current'], 
		'LASTRAID' => (! empty ( $member ['member_lastraid'] )) ? 
			date ( 'd.m.y', $member ['member_lastraid'] ) : 
			'&nbsp;', 
		'RAIDS_P1_DAYS' => $member ['attendanceP1'], 
		'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx",
			'page=viewmember' .  
			'&amp;' . URI_NAMEID . '=' . $member ['member_id'] . 
			'&amp;' . URI_DKPSYS . '=' . $member ['member_dkpid']), 
	);
		
		if($config['bbdkp_timebased'] == 1)
		{
			$templatearray['TIMEBONUS'] = $member ['member_time_bonus'];
		}
		if($config['bbdkp_zerosum'] == 1)
		{
			$templatearray['ZEROSUM'] = $member ['member_zerosum_bonus'];
		}
				
		if($config['bbdkp_decay'] == 1)
		{
			$templatearray['RAIDDECAY'] = $member ['member_raid_decay'];
			$templatearray['ITEMDECAY'] = $member ['member_item_decay'];
		}
		
		if($config['bbdkp_epgp'] == 1)
		{
			$templatearray['EP'] = $member ['ep'];
			$templatearray['GP'] = $member ['gp'];
			$templatearray['PR'] = $member ['pr'];
		}
		
		$template->assign_block_vars ( 'members_row', $templatearray);
		
}

//
$this->leaderboard ( $memberarray, $classarray );

// Added to the end of the sort links
$uri_addon = '';
$uri_addon .= '&amp;filter=' . urlencode ( $filter );
$uri_addon .= (isset ( $_GET ['show'] )) ? '&amp;show=' . request_var ( 'show', '' ) : '';

/* sorting links */
$sortlink = array ();
for($i = 1; $i <= 20; $i ++)
{
	if (isset ( $sortorder ) && $sortorder == $i)
	{
		$j = - $i;
	} 
	else
	{
		$j = $i;
	}
	
	{
		if ($query_by_pool)
		{
			$sortlink [$i] = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=standings&amp;' . URI_ORDER. '=' . $j . $uri_addon . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id );
		} 
		else
		{
			$sortlink [$i] = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=standings&amp;' . URI_ORDER. '=' . $j . $uri_addon . '&amp;' . URI_DKPSYS . '=' . $user->lang['ALL'] );
		}
	}

}

$allmember_count = 0; 
// calculate pagination
$sortorder = request_var ( URI_ORDER, 0 );
$dkppagination = $this->generate_pagination2($u_listmembers . '&amp;o=' . $sortorder , 
$allmember_count , $config ['bbdkp_user_llimit'], $startd, true, 'startdkp'  );

// footcount link
if (($config ['bbdkp_hide_inactive'] == 1) && (! $show_all))
{
	$flink = '<a href="' . append_sid ( "{$phpbb_root_path}dkp.$phpEx", 
		'page=standings' .	
		'&amp;' . URI_ORDER . '=' . $j . '&amp;show=all' . 
		'&amp;' . URI_DKPSYS . '=' . $dkpsys_id ) . '" class="rowfoot">';
	$footcount_text = sprintf ( $user->lang ['LISTMEMBERS_ACTIVE_FOOTCOUNT'], count($memberarray) , $flink );
} 
else
{
	$footcount_text = sprintf ( $user->lang ['LISTMEMBERS_FOOTCOUNT'], count($memberarray) );
}

$template->assign_vars ( array (
	'F_MEMBERS' => $u_listmembers, 
	'F_DKPSYS_NAME' => (isset ( $dkpsysname ) == true) ? $dkpsysname : $user->lang['ALL'], 
	'F_DKPSYS_ID' => (isset ( $dkpsys_id ) == true) ? $dkpsys_id : 0, 
	'O_NAME' => $sortlink [1], 
	'O_RANK' => $sortlink [2], 
	'O_LEVEL' => $sortlink [3], 
	'O_CLASS' => $sortlink [4], 
	'O_ARMOR' => $sortlink [5], 
	'O_MEMBER_RAID_VALUE' => $sortlink [6], 
	'O_EARNED' => $sortlink [9],
	'O_ADJUSTMENT' => $sortlink [10], 
	'O_SPENT' => $sortlink [13],
	'O_CURRENT' => $sortlink [17],
	'O_LASTRAID' => $sortlink [18], 
	'O_RAIDS_P1_DAYS' => $sortlink [19], 
	'O_RAIDS_P2_DAYS' => $sortlink [20], 
	'RAIDS_P1_DAYS' => sprintf ( $user->lang ['RAIDS_X_DAYS'], $config ['bbdkp_list_p1'] ), 
	'S_SHOWZS' 		=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
	'S_SHOWDECAY' 	=> ($config['bbdkp_decay'] == '1') ? true : false,
	'S_SHOWEPGP' 	=> ($config['bbdkp_epgp'] == '1') ? true : false,
 	'S_SHOWTIME' 	=> ($config['bbdkp_timebased'] == '1') ? true : false,
	'S_QUERYBYPOOL' => $query_by_pool, 
	'S_DISPLAY_STANDINGS' => true,
	'DKPPAGINATION' 	=> $dkppagination ,
	'FOOTCOUNT' => (isset ( $_POST ['compare'] )) ? 
		sprintf ( $footcount_text, sizeof (request_var ( 'compare_ids', array ('' => 0 )))) : 
		$footcount_text, 
));

 
if($config['bbdkp_timebased'] == 1) 
{
	$template->assign_var('O_DKP_HOUR', $sortlink [7]);
	
}

if($config['bbdkp_zerosum'] == 1)
{
	$template->assign_var('O_ZEROSUM_BONUS', $sortlink [8]);
}
if($config['bbdkp_decay'] == 1)
{
	$template->assign_vars ( array (
	'O_EPDECAY' => $sortlink [11],
	'O_ITEMDECAY' => $sortlink [14]
	));
}
if($config['bbdkp_epgp'] == 1)
{
	$template->assign_vars ( array (
	'O_EP' => $sortlink [12],
	'O_GP' => $sortlink [15],
	'O_PR' => $sortlink [16],
	));
	
}

// Output page
page_header ( $user->lang ['LISTMEMBERS_TITLE'] );

// end 
?>