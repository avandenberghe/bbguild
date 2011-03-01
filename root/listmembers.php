<?php
/**
 * list of Members per Dkp pool
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
define ( 'IN_PHPBB', true );
$phpbb_root_path = (defined ( 'PHPBB_ROOT_PATH' )) ? PHPBB_ROOT_PATH : './';
$phpEx = substr ( strrchr ( __FILE__, '.' ), 1 );
include ($phpbb_root_path . 'common.' . $phpEx);
global $config;
$user->session_begin ();
$auth->acl ( $user->data );
$user->add_lang ( array ('mods/dkp_common' ) );
// if not authorised redirect to portal
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
$list_p2 = (isset ( $config ['bbdkp_list_p2'] ) == true) ? $config ['bbdkp_list_p2'] : 60;
$list_p3 = (isset ( $config ['bbdkp_list_p3'] ) == true) ? $config ['bbdkp_list_p3'] : 90;
/* begin dkpsys pulldown */

// pulldown
$query_by_pool = false;
$defaultpool = 99; 

$dkpvalues[0] = $user->lang['ALL']; 
$dkpvalues[1] = '--------'; 
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

$dkpsys_id = 0;
if(isset( $_POST ['pool']) or isset ( $_GET [URI_DKPSYS] ) )
{
	//from pulldown
	if (isset( $_POST ['pool']) )
	{
		$pulldownval = request_var('pool',  $user->lang['ALL']);
		if(is_numeric($pulldownval))
		{
			$query_by_pool = true;
			$dkpsys_id = intval($pulldownval); 	
		}
	}
	//from uri
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
	$filtervalues [] = $user->lang[$row ['class_armor_type']];
	$armor_type [] = $user->lang[$row ['class_armor_type']];
}
$db->sql_freeresult ( $result );
$filtervalues [] = '--------';


// get classlist
   $sql_array = array(
    'SELECT'    => 	' l.name as class_name, c.class_min_level, c.class_max_level, c.imagename ', 
    'FROM'      => array(
        CLASS_TABLE 	=> 'c',
        BB_LANGUAGE		=> 'l', 
    	),
    'WHERE'		=> " c.class_id > 0 and l.attribute_id = c.class_id 
     AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",   				    	
	'ORDER_BY'	=> ' c.class_id ',
    );
    
$sql = $db->sql_build_query('SELECT', $sql_array);   
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
    'SELECT'    => 	'm.member_dkpid, d.dkpsys_name, m.member_id, m.member_status, m.member_lastraid, 
    				sum(m.member_raid_value) as member_raid_value, 
    				sum(m.member_earned) as member_earned, 
    				sum(m.member_adjustment) as member_adjustment, 
    				sum(m.member_spent) as member_spent, 
					sum(m.member_earned + m.member_adjustment - m.member_spent) AS member_current,
   					l.member_name, l.member_level, l.member_race_id ,l.member_class_id, l.member_rank_id ,
       				r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix, 
       				l1.name AS member_class, c.class_id, 
       				c.colorcode, c.class_armor_type AS armor_type, c.imagename, 
       				l.member_gender_id, a.image_female_small, a.image_male_small, 
					c.class_min_level AS min_level,
					c.class_max_level AS max_level', 
 
    'FROM'      => array(
        MEMBER_DKP_TABLE 	=> 'm',
        DKPSYS_TABLE 		=> 'd',
        MEMBER_LIST_TABLE 	=> 'l',
        MEMBER_RANKS_TABLE  => 'r',
        RACE_TABLE  		=> 'a',
        CLASS_TABLE    		=> 'c',
        BB_LANGUAGE			=> 'l1', 
    	),
 
    'WHERE'     =>  "(m.member_id = l.member_id)  
    		AND l1.attribute_id =  c.class_id AND l1.language= '" . $config['bbdkp_lang'] . "' AND l1.attribute = 'class' 
			AND (c.class_id = l.member_class_id) 
			AND (l.member_race_id =  a.race_id)
			AND (r.rank_id = l.member_rank_id) 
			AND (m.member_dkpid = d.dkpsys_id) 
			AND (l.member_guild_id = r.guild_id) " ,
    'GROUP_BY' => 'm.member_dkpid, d.dkpsys_name, m.member_id, m.member_status, m.member_lastraid, 
   				l.member_name, l.member_level, l.member_race_id ,l.member_class_id, l.member_rank_id ,
       			r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix, 
       			l1.name, c.class_id, 
       			c.colorcode, c.class_armor_type , c.imagename, 
       			l.member_gender_id, a.image_female_small, a.image_male_small, 
				c.class_min_level ,
				c.class_max_level ', 
);

if($config['bbdkp_timebased'] == 1)
{
	$sql_array[ 'SELECT'] .= ', sum(m.member_time_bonus) as member_time_bonus ';
}

if($config['bbdkp_zerosum'] == 1)
{
	$sql_array[ 'SELECT'] .= ', sum(m.member_zerosum_bonus) as member_zerosum_bonus';
}

if($config['bbdkp_decay'] == 1)
{
	$sql_array[ 'SELECT'] .= ', sum(m.member_raid_decay) as member_raid_decay, sum(m.member_item_decay) as member_item_decay ';
}

if($config['bbdkp_epgp'] == 1)
{
	$sql_array[ 'SELECT'] .= ', sum(m.member_earned - m.member_raid_decay + m.member_adjustment) AS ep, sum(m.member_spent - m.member_item_decay ) AS gp, 
	case when sum(m.member_spent - m.member_item_decay) = 0 then sum(m.member_earned - m.member_raid_decay + m.member_adjustment)  
	else round(sum(m.member_earned - m.member_raid_decay + m.member_adjustment) / sum(' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay),2) end as er ' ;
}

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
	$sql_array['WHERE'] .= " AND l1.name =  '" . $db->sql_escape ( $filter ) . "' ";
}

if ($query_by_armor == 1)
{
	$sql_array['WHERE'] .= " AND c.class_armor_type =  '" . $db->sql_escape ( $filter ) . "'";
}

// default sorting
if($config['bbdkp_epgp'] == 1)
{
	$sql_array[ 'ORDER_BY'] = 'case when sum(m.member_spent - m.member_item_decay) = 0 then sum(m.member_earned - m.member_raid_decay + m.member_adjustment)  
	else round(sum(m.member_earned - m.member_raid_decay + m.member_adjustment) / sum(' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay),2) end desc ' ;
}
else 
{
	$sql_array[ 'ORDER_BY'] = 'sum(m.member_earned + m.member_adjustment - m.member_spent) desc, l.member_name asc ' ;
}


$sql = $db->sql_build_query('SELECT_DISTINCT', $sql_array);

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
	$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male_small'] : $row['image_female_small']);
	
	//finally add to member array
	if ($member_display)
	{
		++$member_count;
		$memberarray [$member_count] ['class_id'] = $row ['class_id'];
		$memberarray [$member_count] ['dkpsys_name'] = $row ['dkpsys_name']; 
		$memberarray [$member_count] ['member_id'] = $row ['member_id'];
		$memberarray [$member_count] ['count'] = $member_count;
		$memberarray [$member_count] ['member_name'] = $row ['member_name'];
		$memberarray [$member_count] ['member_status'] = $row ['member_status'];
		$memberarray [$member_count] ['rank_prefix'] = $row ['rank_prefix'];
		$memberarray [$member_count] ['rank_suffix'] = $row ['rank_suffix'];
		$memberarray [$member_count] ['rank_name'] = $row ['rank_name'];
		$memberarray [$member_count] ['rank_hide'] = $row ['rank_hide'];
		$memberarray [$member_count] ['member_level'] = $row ['member_level'];
		$memberarray [$member_count] ['member_class'] = $row ['member_class'];
		$memberarray [$member_count] ['colorcode'] = $row ['colorcode'];
		$memberarray [$member_count] ['class_image'] = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';
		$memberarray [$member_count] ['class_image_exists'] = (strlen($row['imagename']) > 1) ? true : false; 
		$memberarray [$member_count] ['race_image'] = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
		$memberarray [$member_count] ['race_image_exists'] = (strlen($race_image) > 1) ? true : false; 		
		
		$memberarray [$member_count] ['armor_type'] = $row ['armor_type'];
		$memberarray [$member_count] ['member_raid_value'] = $row ['member_raid_value'];
		if($config['bbdkp_timebased'] == 1)
		{
			$memberarray [$member_count] ['member_time_bonus'] = $row ['member_time_bonus'];
			
		}
		if($config['bbdkp_zerosum'] == 1)
		{
			$memberarray [$member_count] ['member_zerosum_bonus'] = $row ['member_zerosum_bonus'];
		}
		$memberarray [$member_count] ['member_earned'] = $row ['member_earned'];
		
		$memberarray [$member_count] ['member_adjustment'] = $row ['member_adjustment'];
		if($config['bbdkp_decay'] == 1)
		{
			$memberarray [$member_count] ['member_raid_decay'] = $row ['member_raid_decay'];
			$memberarray [$member_count] ['member_item_decay'] = $row ['member_item_decay'];
		}
		
		$memberarray [$member_count] ['member_spent'] = $row ['member_spent'];
		$memberarray [$member_count] ['member_current'] = $row ['member_current'];
		
		if($config['bbdkp_epgp'] == 1)
		{
			$memberarray [$member_count] ['ep'] = $row ['ep'];
			$memberarray [$member_count] ['gp'] = $row ['gp'];
			$memberarray [$member_count] ['er'] = $row ['er'];
		}
		
		$memberarray [$member_count] ['member_lastraid'] = $row ['member_lastraid'];
		$memberarray [$member_count] ['attendanceP1'] = percentage_raidcount ( true, $row ['member_dkpid'], $list_p1, $row ['member_id'] );
		$memberarray [$member_count] ['attendanceP2'] = percentage_raidcount ( true,  $row ['member_dkpid'], $list_p2, $row ['member_id'] );
		$memberarray [$member_count] ['member_dkpid'] = $row ['member_dkpid'];
	}
}
$db->sql_freeresult ( $members_result );

// Obtain a list of columns for sorting
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
			$er[$key]['er'] = $member['er']; 
		}
		
		$member_spent [$key] = $member ['member_spent']; //*
		$member_current [$key] = $member ['member_current'];  //*
		$member_lastraid [$key] = $member ['member_lastraid']; //*
		$attendanceP1 [$key] = $member ['attendanceP1']; //*
		$attendanceP2 [$key] = $member ['attendanceP2']; //*
	}
	
	
	// do the custom sorting
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
			array_multisort ( $er, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 16 : // Pr 
			array_multisort ( $er, SORT_ASC, $member_name, SORT_DESC, $memberarray );
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
			
		case 20 : //raidattendance P2
			array_multisort ( $attendanceP2, SORT_DESC, $member_name, SORT_ASC, $memberarray );
			break;
		case - 20 : //raidattendance P2
			array_multisort ( $attendanceP2, SORT_ASC, $member_name, SORT_DESC, $memberarray );
			break;
	}
}

$show_all = ((isset ( $_GET ['show'] )) && (request_var ( 'show', '' ) == 'all')) ? true : false;


// loop member array and dump to template
foreach ( $memberarray as $key => $member )
{
	
	$u_rank_search = append_sid ( "{$phpbb_root_path}listmembers.$phpEx" . '?rank=' . urlencode ( $member ['rank_name'] ) );
	
	// append inactive switch
	$u_rank_search .= (($config ['bbdkp_hide_inactive'] == 1) && (! $show_all)) ? '&amp;show=' : '&amp;show=all';
	
	// append armor or class filter
	$u_rank_search .= ($filter != 'All') ? '&amp;filter=' . $filter : '';
	
	$templatearray = array (
		'COLORCODE' 	=> $member ['colorcode'],
		'DKPNAME'		=> $member ['dkpsys_name'],  
		'CLASS_IMAGE' 	=> $member['class_image'],
		'S_CLASS_IMAGE_EXISTS' =>  $member['class_image_exists'],
		'RACE_IMAGE' 	=> $member['race_image'],
		'S_RACE_IMAGE_EXISTS' =>  $member['race_image_exists'],
		'DKPCOLOUR1' 	=> ($member ['member_adjustment'] >= 0) ? 'green' : 'red', 
		'DKPCOLOUR2' 	=> ($row ['member_current'] >= 0) ? 'green' : 'red', 
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
		'RAIDS_P2_DAYS' => $member ['attendanceP2'], 
		'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}viewmember.$phpEx", 
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
			$templatearray['ER'] = $member ['er'];
		}
		
		$template->assign_block_vars ( 'members_row', $templatearray);
		
}


$s_showlb = true;
leaderboard ( $dkpsys_id, $query_by_pool );

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
	} else
	{
		$j = $i;
	}
	
	{
		if ($query_by_pool)
		{
			$sortlink [$i] = append_sid ( "{$phpbb_root_path}listmembers.$phpEx", URI_ORDER. '=' . $j . $uri_addon . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id );
		} 
		else
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

$template->assign_vars ( array (
	'F_MEMBERS' => append_sid ( "{$phpbb_root_path}listmembers.$phpEx" ), 
	'F_DKPSYS_NAME' => (isset ( $dkpsysname ) == true) ? $dkpsysname : 'All', 
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
	'RAIDS_P1_DAYS' => sprintf ( $user->lang ['RAIDS_X_DAYS'], $list_p1 ), 
	'RAIDS_P2_DAYS' => sprintf ( $user->lang ['RAIDS_X_DAYS'], $list_p2 ), 
	'S_SHOWLEAD' => $s_showlb,
	'S_SHOWZS' 		=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
	'S_SHOWDECAY' 	=> ($config['bbdkp_decay'] == '1') ? true : false,
	'S_SHOWEPGP' 	=> ($config['bbdkp_epgp'] == '1') ? true : false,
 	'S_SHOWTIME' 	=> ($config['bbdkp_timebased'] == '1') ? true : false,
	'S_QUERYBYPOOL' => $query_by_pool, 
	'FOOTCOUNT' => (isset ( $_POST ['compare'] )) ? 
		sprintf ( $footcount_text, sizeof (request_var ( 'compare_ids', array ('' => 0 )))) : 
		$footcount_text )
 );

 
if($config['bbdkp_timebased'] == 1) {
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
$template->set_filenames ( array ('body' => 'dkp/listmembers.html' ) );
page_footer ();

// end 
/**
 * this function builds a grid with PR or earned (after dacay)
 */
function leaderboard($dkpsys_id, $query_by_pool)
{
	// get needed global vars
	global $db, $template, $config;
	global $phpbb_root_path, $phpbb_admin_path, $phpEx;

    $sql_array = array(
	    'SELECT'    => 	' c.class_id, l.name as class_name, c.imagename, c.colorcode ', 
	    'FROM'      => array(
	        CLASS_TABLE 	=> 'c',
	        BB_LANGUAGE		=> 'l', 
	    	),
	    'WHERE'		=> "class_id != 0 AND l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",   				    	
		'ORDER_BY'	=> 'l.name ',
    );
	$sql = $db->sql_build_query('SELECT', $sql_array);
	
	$result = $db->sql_query ( $sql );
	$classes = array ();
	
	while ( $row = $db->sql_fetchrow ( $result ) )
	{
		$cssclass = $config ['bbdkp_default_game'] . 'class' . $row ['class_id'];
		$template->assign_block_vars ( 'class', 
			array (
				'CLASSNAME' 	=> $row ['class_name'], 
				'CLASSIMGPATH'	=> (strlen($row['imagename']) > 1) ? $row['imagename'] . ".png" : '',
				'COLORCODE' 	=> $row['colorcode']
				) 
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
		
		if($config['bbdkp_epgp'] == 1)
		{
			$sql_array[ 'SELECT'] .= ', case when (m.member_spent - m.member_item_decay) = 0 then (m.member_earned - m.member_raid_decay + m.member_adjustment)  
				else round((m.member_earned - m.member_raid_decay + m.member_adjustment) / (' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay),2) end as pr ' ;
		}
		
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
			$dkprowarray= array (
				'NAME' => $dkprow ['rank_prefix'] . (($dkprow ['member_status'] == '0') ? '<em>' . $dkprow ['member_name'] . '</em>' : $dkprow ['member_name']) . $dkprow ['rank_suffix'], 
				'CURRENT' => $dkprow ['member_current'], //point color
				'DKPCOLOUR' => ($dkprow ['member_current'] >= 0) ? 'style="font-size :8pt; color: green; text-align: right;"' : 'style="font-size :8pt; color: red; text-align: right;"', 
				'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}viewmember.$phpEx", '&amp;' . 
						URI_NAMEID . '=' . $dkprow ['member_id'] . '&amp;' . 
						URI_DKPSYS . '=' . $dkprow['member_dkpid'] ) );
				
			if($config['bbdkp_epgp'] == 1)
			{
				$dkprowarray[ 'PR'] = $dkprow ['pr'] ;
			}
				
			$template->assign_block_vars ( 'class.dkp_row', $dkprowarray );
		}
		$db->sql_freeresult ( $result2 );
	}
}

?>