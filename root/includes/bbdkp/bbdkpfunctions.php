<?php
/**
* eqdkp help functions
* 
* @package bbDKP.includes
* @version $Id$
* @copyright (c) 2009 bbDKP 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * Pagination function altered from functions.php used in viewmember.php because we need two linked paginations
 *
 * Pagination routine, generates page number sequence
 * tpl_prefix is for using different pagination blocks at one page
*/
function generate_pagination2($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $tpl_prefix = '')
{
	global $template, $user;

	// Make sure $per_page is a valid value
	$per_page = ($per_page <= 0) ? 1 : $per_page;
	$total_pages = ceil($num_items / $per_page);

	$seperator = '<span class="page-sep">' . $user->lang['COMMA_SEPARATOR'] . '</span>';
	
	if ($total_pages == 1 || !$num_items)
	{
		return false;
	}

	$on_page = floor($start_item / $per_page) + 1;
	$url_delim = (strpos($base_url, '?') === false) ? '?' : '&amp;';

	$page_string = ($on_page == 1) ? '<strong>1</strong>' : '<a href="' . $base_url . '">1</a>';

	if ($total_pages > 5)
	{
		$start_cnt = min(max(1, $on_page - 4), $total_pages - 5);
		$end_cnt = max(min($total_pages, $on_page + 4), 6);

		$page_string .= ($start_cnt > 1) ? ' ... ' : $seperator;

		for ($i = $start_cnt + 1; $i < $end_cnt; $i++)
		{
			  $page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
			if ($i < $end_cnt - 1)
			{
				$page_string .= $seperator;
			}
		}

		$page_string .= ($end_cnt < $total_pages) ? ' ... ' : $seperator;
	}
	else
	{
		$page_string .= $seperator;

		for ($i = 2; $i < $total_pages; $i++)
		{
			$page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
			if ($i < $total_pages)
			{
				$page_string .= $seperator;
			}
		}
	}

	$page_string .= ($on_page == $total_pages) ? '<strong>' . $total_pages . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($total_pages - 1) * $per_page) . '">' . $total_pages . '</a>';
	if ($add_prevnext_text)
	{
		if ($on_page != 1)
		{
			$page_string = '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($on_page - 2) * $per_page) . '">' . $user->lang['PREVIOUS'] . '</a>&nbsp;&nbsp;' . $page_string;
		}

		if ($on_page != $total_pages)
		{
			  $page_string .= '&nbsp;&nbsp;<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . ($on_page * $per_page) . '">' . $user->lang['NEXT'] . '</a>';
		}
	}

	$template->assign_vars(array(
		$tpl_prefix . 'BASE_URL'		=> $base_url,
		'A_' . $tpl_prefix . 'BASE_URL'	=> addslashes($base_url),
		$tpl_prefix . 'PER_PAGE'		=> $per_page,

		$tpl_prefix . 'PREVIOUS_PAGE'	=> ($on_page == 1) ? '' : $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($on_page - 2) * $per_page),
		$tpl_prefix . 'NEXT_PAGE'		=> ($on_page == $total_pages) ? '' : $base_url . "{$url_delim}" . $tpl_prefix  . "	=" . ($on_page * $per_page),
		$tpl_prefix . 'TOTAL_PAGES'		=> $total_pages,
	));

	return $page_string;
}

/**
* Returns the appropriate CSS class to use based on
* a number's range
*
* @param $item The number
* @param $percentage Treat the number like a percentage?
* @return mixed CSS Class / false
*/
function color_item($item, $percentage = false)
{
    if ( !is_numeric($item) )
    {
        return false;
    }

    if ( !$percentage )
    {
        if ( $item < 0 )
        {
            $class = 'negative';
        }
        elseif ( $item > 0)
        {
            $class = 'positive';
        }
        else
        {
            $class = 'neutral';
        }
    }
    elseif ( $percentage )
    {
        if ( ($item >= 0) && ($item <= 34) )
        {
            $class = 'negative';
        }
        elseif ( ($item >= 35) && ($item <= 66) )
        {
            $class = 'neutral';
        }
        elseif ( ($item >= 67) && ($item <= 100) )
        {
            $class = 'positive';
        }
        else
        {
            $class = 'neutral';
        }
    }

    return $class;
}

/*
* Switches the sorting order of a supplied array, prerserving key values
* The array is in the format [number][0/1] (0 = the default, 1 = the opposite)
* Returns an array containing the code to use in an SQL query and the code to
* use to pass the sort value through the URI.  URI is in the format
* (number).(0/1)
*
* checks that the 2nd element is either 0 or 1
* @param $sort_order Sorting order array
* @param $arg header variable
* @return array SQL/URI information
*/
function switch_order($sort_order, $arg = URI_ORDER)
{
    $uri_order = ( isset($_GET[$arg]) ) ? request_var($arg, 0.0) : '0.0';
    
    $uri_order = explode('.', $uri_order);
    
    $element1 = ( isset($uri_order[0]) ) ? $uri_order[0] : 0;
    $element2 = ( isset($uri_order[1]) ) ? $uri_order[1] : 0;
	// check if correct input
    if ( $element2 != 1 )
    {
        $element2 = 0;
    }

    foreach($sort_order as $key => $value )
    {
        if ( $element1 == $key )
        {
            $uri_element2 = ( $element2 == 0 ) ? 1 : 0;
        }
        else
        {
            $uri_element2 = 0;
        }
        $current_order['uri'][$key] = $key . '.' . $uri_element2;
    }

    $current_order['uri']['current'] = $element1.'.'.$element2;
    $current_order['sql'] = $sort_order[$element1][$element2];

    return $current_order;
}

/*****
* makes a hash of a filename
*
****/
function hash_filename($filename)
{
    if ( isset($filename) && $filename != "" )
    {
		$hash = md5_file($filename);
    }
    return($hash);
}

function stripmultslashes($string)
{
    $string = preg_replace("#(\\\){1,}(\"|\&quot;)#", '"', $string);
    $string = preg_replace("#(\\\){1,}(\'|\&\#039)#", "'", $string);
    
    return $string;
}

function sanitize_tags($data)
{
    if ( is_array($data) )
    {
        foreach ( $data as $k => $v )
        {
            $data[$k] = sanitize_tags($v);
        }
    }
    else
    {
        $data = str_replace('<', '&lt;', $data);
        $data = str_replace('>', '&gt;', $data);
    }

    return $data;
}


/**
* Create a bar graph
* 
* @param $width
* @param $show_number Show number in middle of bar?
* @param $class Background class for bar
* @return string Bar HTML
*/
function create_bar($width, $show_text = '', $class = 'row2')
{
    $bar = '';
    
    if ( strstr($width, '%') )
    {
        $width = intval(str_replace('%', '', $width));
        if ( $width > 0 )
        {
            $width = ( intval($width) <= 100 ) ? $width . '%' : '100%';
        }
    }
    
    if ( $width > 0 )
    {
        $bar = '<table width="' . $width . '" border="0" cellpadding="0" cellspacing="0">';
        $bar .= '<tr><td width="100%" align="center" class="' . $class . '" nowrap="nowrap">';
    
        if ( $show_text != '' )
        {
            $bar .= '<span class="small">' . $show_text . '</span>';
        }
    
        $bar .= '</td></tr></table>';
    }
    
    return $bar;
}


/******
 * returns raid attendance percentage for a member/pool
 *  @param $query_by_pool = boolean
 *  @param $dkpsys_id = int
 *  @param $days = int
 *  @param $member_id = int
 * 
 * used by listmembers.php and viewmember.php
 * 
 */
function percentage_raidcount($query_by_pool, $dkpsys_id, $days, $member_id)
{
    global $db;
    
    $start_date = mktime(0, 0, 0, date('m'), date('d')-$days, date('Y')); 
    $end_date = time(); 
    
	// get member joindate
 	$sql = 'SELECT member_joindate  FROM ' . MEMBER_LIST_TABLE . ' WHERE member_id = ' . $member_id; 
	$result = $db->sql_query($sql);
	$joindate = $db->sql_fetchfield('member_joindate',0,$result);
	$db->sql_freeresult($result);
	
	// member joined in the last $days ?
	if ($joindate > $start_date)
	{
		// then count from join date
		$start_date = $joindate; 
	}
    
	// get member raidcount
	$individual_raid_count = 0;
	
	$sql_array = array(
	    'SELECT'    => 	' COUNT(*) as raidcount  ', 
	    'FROM'      => array(
			EVENTS_TABLE			=> 'e', 	        
			RAIDS_TABLE 			=> 'r',
	        RAID_DETAIL_TABLE	=> 'ra', 
	    	),
	    'WHERE'		=> ' 
	    	r.event_id = e.event_id
	    	AND ra.raid_id = r.raid_id
            AND ra.member_id =' . $member_id . '
            AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date,
    );
	
	if ($query_by_pool == true)
	{
		$sql_array['WHERE'] .= ' AND e.event_dkpid = ' . $dkpsys_id; 
	}
	$sql = $db->sql_build_query('SELECT', $sql_array);
	
	
    $result = $db->sql_query($sql);
    $individual_raid_count = (int) $db->sql_fetchfield('raidcount',0,$result);
    $db->sql_freeresult($result);

    // total raid count for this pool or globally
	$raid_count = 0;
	$sql_array = array(
	    'SELECT'    => 	' COUNT(*) as raidcount  ', 
	    'FROM'      => array(
			EVENTS_TABLE			=> 'e', 	        
			RAIDS_TABLE 			=> 'r'	         
	    	),
	    'WHERE'		=> 'r.event_id = e.event_id
            AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date,
    );
	
	if ($query_by_pool == true)
	{
		$sql_array['WHERE'] .= ' AND e.event_dkpid = ' . $dkpsys_id; 
	}
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	$raid_count = (int) $db->sql_fetchfield('raidcount',0,$result);
	$db->sql_freeresult($result);

    // calculate ratio 
    $percent_of_raids = ($raid_count > 0 ) ?  round(($individual_raid_count / $raid_count) * 100,2) : 0;
    return (float) $percent_of_raids ; 
}


/****
 * returns number of raids of one member in a pool
 *  @param $query_by_pool = boolean
 *  @param $dkpsys_id = int
 *  @param $days = int
 *  @param $member_id = int
 * 
 */
function memberraid_count($dkpsys_id, $days, $member_id, $all)
{  

    global $db;
    
    $start_date = mktime(0, 0, 0, date('m'), date('d')-$days, date('Y')); 
    $end_date = time(); 
    	
	$sql_array = array(
	    'SELECT'    => 	' COUNT(*) as memberraidcount  ', 
	    'FROM'      => array(
			EVENTS_TABLE			=> 'e', 	        
			RAIDS_TABLE 			=> 'r',
	        RAID_DETAIL_TABLE	=> 'ra', 
	    	),
	    'WHERE'		=> ' 
	    	r.event_id = e.event_id
	    	AND ra.raid_id = r.raid_id
            AND ra.member_id =' . $member_id . '
            AND e.event_dkpid = ' . $dkpsys_id . ' 
            AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date,
    );
	
	if ($all == false)
	{
		$sql_array['WHERE'] .= ' AND (r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date . ')'; 
	}
	$sql = $db->sql_build_query('SELECT', $sql_array);
    
    $result = $db->sql_query($sql);
    $individual_raid_count = (int) $db->sql_fetchfield('memberraidcount',0,$result);   
	$db->sql_freeresult($result);

    return $individual_raid_count;
}

/*
 * returns total number of raids in a pool
 *  @param $query_by_pool = boolean
 *  @param $dkpsys_id = int
 *  @param $days = int
 *  @param $member_id = int
 * 
 */
function poolraid_count($dkp_pool, $days, $all )
{  
    global $db;

    $start_date = mktime(0, 0, 0, date('m'), date('d')-$days, date('Y')); 
    $end_date = time(); 
    
    // total raid count for this pool or globally
	$raid_count = 0;
	$sql_array = array(
	    'SELECT'    => 	' COUNT(*) as poolraidcount  ', 
	    'FROM'      => array(
			EVENTS_TABLE			=> 'e', 	        
			RAIDS_TABLE 			=> 'r'	         
	    	),
	    'WHERE'		=> 'r.event_id = e.event_id
            AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date,
    );
	
	if ($all == false)
	{
		$sql_array['WHERE'] .= ' AND (raid_start BETWEEN ' . $start_date . ' AND ' . $end_date . ')'; 
	}
	$sql = $db->sql_build_query('SELECT', $sql_array);
	
	$result = $db->sql_query($sql);
    $raid_count = (int) $db->sql_fetchfield('poolraidcount',0,$result);   
    $db->sql_freeresult($result);

    return $raid_count;
}
?>