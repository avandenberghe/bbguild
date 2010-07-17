<?php
/**
 * List Items
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

// Start session management
$user->session_begin ();
$auth->acl ( $user->data );
$user->setup ();
$user->add_lang ( array ('mods/dkp_common' ) );
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang ['BBDKPDISABLED'], E_USER_WARNING );
}

$bbDkp_Admin = new bbDkp_Admin ( );
if ($bbDkp_Admin->bbtips == true)
{
	if (! class_exists ( 'bbtips' ))
	{
		require ($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx);
	}
	$bbtips = new bbtips ( );
}

/**** begin dkpsys pulldown  ****/
$query_by_pool = false;
$defaultpool = 99;

$dkpvalues [0] = $user->lang ['ALL'];
$dkpvalues [1] = '--------';
$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE;
$result = $db->sql_query ( $sql );
$index = 3;
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$dkpvalues [$index] ['id'] = $row ['dkpsys_id'];
	$dkpvalues [$index] ['text'] = $row ['dkpsys_name'];
	if (strtoupper ( $row ['dkpsys_default'] ) == 'Y')
	{
		$defaultpool = $row ['dkpsys_id'];
	}
	$index += 1;
}
$db->sql_freeresult ( $result );

$dkpsys_id = 0;
if (isset ( $_POST ['pool'] ) or isset ( $_POST ['getdksysid'] ) or isset ( $_GET [URI_DKPSYS] ))
{
	if (isset ( $_POST ['pool'] ))
	{
		$pulldownval = request_var ( 'pool', $user->lang ['ALL'] );
		if (is_numeric ( $pulldownval ))
		{
			$query_by_pool = true;
			$dkpsys_id = intval ( $pulldownval );
		}
	}
	if (isset ( $_POST ['getdksysid'] ))
	{
		$query_by_pool = true;
		$dkpsys_id = request_var ( 'getdksysid', 0 );
	
	}
	if (isset ( $_GET [URI_DKPSYS] ))
	{
		$query_by_pool = true;
		$dkpsys_id = request_var ( URI_DKPSYS, 0 );
	}
}

foreach ( $dkpvalues as $key => $value )
{
	if (! is_array ( $value ))
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value, 
			'SELECTED' => ($value == $dkpsys_id && $value != '--------') ? ' selected="selected"' : '', 
			'DISABLED' => ($value == '--------') ? ' disabled="disabled"' : '', 
			'OPTION' => $value ) );
	} else
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value ['id'], 
			'SELECTED' => ($dkpsys_id == $value ['id']) ? ' selected="selected"' : '', 
			'OPTION' => $value ['text'] ) );
	
	}
}

$query_by_pool = ($dkpsys_id != 0) ? true : false;
/**** end dkpsys pulldown  ****/

/**
 *
 * Item Purchase History (all items)
 * Item Value listing  (item values)
 *
 **/

$mode = request_var ( 'page', 'values' );

$sort_order = array (
	0 => array ('item_date desc', 'item_date' ), 
	1 => array ('item_buyer', 'item_buyer desc' ), 
	2 => array ('item_name', 'item_name desc' ), 
	3 => array ('raid_name', 'raid_name desc' ), 
	4 => array ('item_value desc', 'item_value' ) );

$current_order = switch_order ( $sort_order );

switch ($mode)
{
	case 'values' :
		$u_list_items = append_sid ( "{$phpbb_root_path}listitems.$phpEx" );
		$sql3 = 'SELECT COUNT(DISTINCT item_name) as itemcount FROM ' . ITEMS_TABLE;
		$s_history = false;
		break;
	case 'history' :
		$u_list_items = append_sid ( "{$phpbb_root_path}listitems.$phpEx", 'page=history' );
		$sql3 = 'SELECT count(*) as itemcount FROM ' . ITEMS_TABLE;
		$s_history = true;
		break;
}

// select discinct!
if ($query_by_pool)
{
	$sql3 .= ' where item_dkpid = ' . $dkpsys_id . ' ';
}
$result3 = $db->sql_query ( $sql3 );
$total_items = $db->sql_fetchfield ( 'itemcount', 1, $result3 );
$db->sql_freeresult ( $result3 );

$start = request_var ( 'start', 0 );

switch ($mode)
{
	case 'values' :
		$listitems_footcount = sprintf ( $user->lang ['LISTITEMS_FOOTCOUNT'], $total_items, $config ['bbdkp_user_ilimit'] );
		break;
	case 'history' :
		$listitems_footcount = sprintf ( $user->lang ['LISTPURCHASED_FOOTCOUNT'], $total_items, $config ['bbdkp_user_ilimit'] );
		break;
}

if ($query_by_pool)
{
	$pagination = generate_pagination ( append_sid ( "{$phpbb_root_path}listitems.$phpEx", 
	'page=' . $mode . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id . '&amp;o=' . $current_order ['uri'] ['current'] ), 
	$total_items, $config ['bbdkp_user_ilimit'], $start, true );
} else
{
	$pagination = generate_pagination ( append_sid ( "{$phpbb_root_path}listitems.$phpEx", 
	'page=' . $mode . '&amp;' . URI_DKPSYS . '=All&amp;o=' . $current_order ['uri'] ['current'] ), 
	$total_items, $config ['bbdkp_user_ilimit'], $start, true );
}

switch ($mode)
{
	case 'values' :
		$sql_array = array (
			'SELECT' => 'i.item_dkpid, i.item_id, i.item_name, i.item_buyer, i.item_gameid, 
						 i.item_date, i.raid_id, min(i.item_value) AS item_value, r.raid_name', 
			'FROM' => array (ITEMS_TABLE => 'i', RAIDS_TABLE => 'r' ), 
			'WHERE' => ' i.raid_id = r.raid_id', 
			'GROUP_BY' => 'i.item_name', 
			'ORDER_BY' => $current_order ['sql'] );
		
		break;
	
	case 'history' :
		
		$sql_array = array (
			'SELECT' => 'i.item_dkpid, i.item_id, i.item_name, i.item_buyer, i.item_date, i.raid_id, i.item_value, i.item_gameid, 
				 r.raid_name, m.member_id, m.member_dkpid, l.member_class_id, l.member_name', 
    		'FROM' => array (
				ITEMS_TABLE => 'i', 
				RAIDS_TABLE => 'r', 
				MEMBER_DKP_TABLE => 'm', 
				MEMBER_LIST_TABLE => 'l'), 

			'WHERE' => ' i.raid_id = r.raid_id
    					AND i.item_buyer = l.member_name
           				AND l.member_id = m.member_id 
           				AND i.item_dkpid = m.member_dkpid', 
           	'ORDER_BY' => $current_order ['sql'] );
		
		break;
}

if ($query_by_pool)
{
	$sql_array ['WHERE'] .= ' AND r.raid_dkpid = ' . $dkpsys_id . ' ';
}

$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$items_result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_ilimit'], $start );

// Regardless of which listitem page they're on, we're essentially 
// outputting the same stuff. Purchase History just has a buyer column.
if (! $items_result)
{
	$user->add_lang ( array ('mods/dkp_admin' ) );
	trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'], E_USER_WARNING );
}

while ( $item = $db->sql_fetchrow ( $items_result ) )
{
	
	if ($bbDkp_Admin->bbtips == true)
	{

		if ($item['item_gameid'] > 0 )
		{
			$valuename = $bbtips->parse('[itemdkp]' . $item['item_gameid']  . '[/itemdkp]'); 
		}
		else 
		{
			$valuename = $bbtips->parse ( '[itemdkp]' . $item ['item_name'] . '[/itemdkp]' );
		}
				
	} 
	else
	{
		$valuename = $item ['item_name'];
	}
	
	$template->assign_block_vars ( 'items_row', array (
		'DATE' => (! empty ( $item ['item_date'] )) ? date ( 'd.m.y', $item ['item_date'] ) : '&nbsp;', 
		'BUYER' => (! empty ( $item ['item_buyer'] )) ? $item ['item_buyer'] : '&lt;<i>Not Found</i>&gt;', 
		'ITEMNAME' => $valuename, 
		'U_VIEW_ITEM' => append_sid ( "{$phpbb_root_path}viewitem.$phpEx", URI_ITEM . '=' . $item ['item_id'] ), 
		'RAID' => (! empty ( $item ['raid_name'] )) ? $item ['raid_name'] : '&lt;<i>Not Found</i>&gt;', 
		'U_VIEW_RAID' => append_sid ( "{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '=' . $item ['raid_id'] ), 
		'VALUE' => $item ['item_value'],
		'CSSCLASS' 		=> ($mode == 'history') ? $config ['bbdkp_default_game'] . 'class' . $item ['member_class_id'] : '', 
		'U_VIEW_BUYER' 	=> ($mode == 'history') ?  append_sid ( "{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . 
			$item ['item_buyer'] . '&amp;' . URI_DKPSYS . '=' . $item ['item_dkpid'] ) : '', 
	
		));
	

}
$db->sql_freeresult ( $items_result );

$sortlink = array ();
for($i = 0; $i <= 4; $i ++)
{
	if ($query_by_pool)
	{
		$sortlink [$i] = append_sid ( "{$phpbb_root_path}listitems.$phpEx", 'page=' . $mode .
		 '&amp;o=' . $current_order ['uri'] [$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id );
	} else
	{
		$sortlink [$i] = append_sid ( "{$phpbb_root_path}listitems.$phpEx", 'page=' . $mode .
		 '&amp;o=' . $current_order ['uri'] [$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=All' );
	}
}

// breadcrumbs menu                                      
$navlinks_array = array (
	array (
	'DKPPAGE' => ($mode == 'history') ? $user->lang ['MENU_ITEMHIST'] : $user->lang ['MENU_ITEMVAL'], 
	'U_DKPPAGE' => $u_list_items ) );

foreach ( $navlinks_array as $name )
{
	$template->assign_block_vars ( 'dkpnavlinks', array (
	'DKPPAGE' => $name ['DKPPAGE'], 
	'U_DKPPAGE' => $name ['U_DKPPAGE'] ) );
}

$template->assign_vars ( array (
	'F_LISTITEM' => $u_list_items, 
	'U_LISTITEMS' => append_sid ( "{$phpbb_root_path}listitems.$phpEx" ), 
	'U_LISTITEMHIST' => append_sid ( "{$phpbb_root_path}listitems.$phpEx?&amp;page=history" ), 
	'U_LISTMEMBERS' => append_sid ( "{$phpbb_root_path}listmembers.$phpEx" ), 
	'U_LISTEVENTS' => append_sid ( "{$phpbb_root_path}listevents.$phpEx" ), 
	'U_LISTRAIDS' => append_sid ( "{$phpbb_root_path}listraids.$phpEx" ), 
	'U_VIEWITEM' => append_sid ( "{$phpbb_root_path}viewitem.$phpEx" ), 
	'U_BP' => append_sid ( "{$phpbb_root_path}bossprogress.$phpEx" ), 
	'U_ROSTER' => append_sid ( "{$phpbb_root_path}roster.$phpEx" ), 
	'U_ABOUT' => append_sid ( "{$phpbb_root_path}about.$phpEx" ), 
	'U_STATS' => append_sid ( "{$phpbb_root_path}stats.$phpEx" ), 
	'U_VIEWNEWS' => append_sid ( "{$phpbb_root_path}viewnews.$phpEx" ), 
	'O_DATE' => $sortlink [0], 
	'O_BUYER' => $sortlink [1], 
	'O_NAME' => $sortlink [2], 
	'O_RAID' => $sortlink [3], 
	'O_VALUE' => $sortlink [4], 
	'S_HISTORY' => $s_history, 
	'LISTITEMS_FOOTCOUNT' => $listitems_footcount, 
	'ITEM_PAGINATION' => $pagination ) 
);

$title = ($mode == 'history') ? $user->lang ['MENU_ITEMHIST'] : $user->lang ['MENU_ITEMVAL'];

// Output page
page_header ( $title );

$template->set_filenames ( array ('body' => 'dkp/listitems.html' ) );

page_footer ();
?>
