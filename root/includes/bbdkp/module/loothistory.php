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
if (!class_exists('\bbdkp\Loot'))
{
	require("{$phpbb_root_path}includes/bbdkp/Loot/Loot.$phpEx");
}
if (!class_exists('\bbdkp\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}

$mode = request_var ( 'mode', 'values' );

$loot = new \bbdkp\loot();

$total_items = $loot->countloot('history', $this->dkpsys_id); 

if ($this->dkpsys_id > 0)
{
	$u_list_items = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;guild_id=' . $this->guild_id . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id );
} 
else
{
	$u_list_items = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;guild_id=' . $this->guild_id);
}

$listitems_footcount = sprintf ( $user->lang ['LISTPURCHASED_FOOTCOUNT'], $total_items , $config ['bbdkp_user_ilimit'] );

$sort_order = array (
	0 => array ('item_date desc', 'item_date asc' ), 
	1 => array ('item_name asc', 'item_name desc' ), 
	2 => array ('event_name asc', 'event_name desc' ), 
	3 => array ('item_value desc', 'item_value asc' ) );
$sort_order[4] = array ('member_name asc', 'member_name desc' ); 	
$current_order = $this->switch_order ($sort_order);
$start = request_var ( 'start', 0 );

$pagination = generate_pagination ( $u_list_items . '&amp;o=' . $current_order ['uri'] ['current'] , $total_items, $config ['bbdkp_user_ilimit'], $start, true );

$items_result = $loot->GetAllLoot($current_order['sql'], $this->dkpsys_id,0,$start,0); 

$number_items = 0;
$item_value = 0.00;
$item_decay = 0.00;
$item_total = 0.00;

while ( $item = $db->sql_fetchrow ( $items_result ) )
{
	
	if ($this->bbtips == true)
	{

		if ($item['item_gameid'] == 'wow' )
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
	
	$member = new \bbdkp\Members($item ['member_id']);

	$template->assign_block_vars ( 'items_row', array (
		'DATE' 			=> (! empty ( $item ['item_date'] )) ? date($config['bbdkp_date_format'], $item ['item_date'] ) : '&nbsp;', 
		'ITEMNAME' 		=> $valuename, 
		'U_VIEW_ITEM' 	=> append_sid ( "{$phpbb_root_path}dkp.$phpEx", "page=viewitem&amp;" . URI_ITEM . '=' . $item ['item_id'] ), 
		'RAID' 			=> (! empty ( $item ['event_name'] )) ? $item ['event_name'] : '&lt;<i>'. $user->lang['NOT_AVAILABLE'] .'</i>&gt;', 
		'U_VIEW_RAID' 	=> append_sid ( "{$phpbb_root_path}dkp.$phpEx", "page=viewraid&amp;" . URI_RAID . '=' . $item ['raid_id'] ), 
		'EVENTCOLOR' => ( !empty($item['event_color']) ) ? $item['event_color'] : '#123456',
		
		'ITEM_ZS'      	=> ($item['item_zs'] == 1) ? ' checked="checked"' : '',
		'ITEMVALUE' 	=> sprintf("%.2f", $item['item_value'])   ,
		'DECAYVALUE' 	=> sprintf("%.2f", $item['item_decay']),
		'TOTAL' 		=> sprintf("%.2f", $item['item_net']),
		'BUYER' 		=> $item ['member_name'], 
	
		'U_VIEW_BUYER' 	=> append_sid ( "{$phpbb_root_path}dkp.$phpEx", "page=viewmember&amp;" . URI_NAMEID . '=' . $item ['member_id'] . '&amp;' . URI_DKPSYS . '=' . $item ['event_dkpid'] ), 
		'RACE_IMAGE' 	=> $member->race_image,  
		'S_RACE_IMAGE_EXISTS' => (strlen($member->race_image) > 1) ? true : false,

		'CLASSCOLOR' 	=> $member->colorcode, 
		'CLASS_IMAGE' 	=> $member->class_image,  
		'S_CLASS_IMAGE_EXISTS' => (strlen($member->class_image) > 1) ? true : false, 				
	));	
	unset($member);  
	$number_items++; 
	$item_value += $item['item_value'];
	$item_decay += $item['item_decay'];
	$item_total += $item['item_net'];		

}
$db->sql_freeresult ( $items_result );

// breadcrumbs menu                                      
$navlinks_array = array (
	array (
	'DKPPAGE' => $user->lang ['MENU_ITEMHIST'], 
	'U_DKPPAGE' => $u_list_items ) );

foreach ( $navlinks_array as $name )
{
	$template->assign_block_vars ( 'dkpnavlinks', array (
	'DKPPAGE' => $name ['DKPPAGE'], 
	'U_DKPPAGE' => $name ['U_DKPPAGE'] ) );
}

$template->assign_vars ( array (
	'F_LISTITEM' => $u_list_items, 
	'O_DATE' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;o=' . $current_order ['uri'] [0] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id>0), 
	'O_NAME' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;o=' . $current_order ['uri'] [3] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id>0),  
	'O_RAID' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;o=' . $current_order ['uri'] [1] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id>0),
	'O_VALUE' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=loothistor&amp;o=' . $current_order ['uri'] [2] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id>0), 
	'S_SHOWZS' 		=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
	'S_SHOWTIME' 	=> ($config['bbdkp_timebased'] == '1') ? true : false,
	'S_SHOWDECAY' 	=> ($config['bbdkp_decay'] == '1') ? true : false,
	'S_SHOWEPGP' 	=> ($config['bbdkp_epgp'] == '1') ? true : false,
	'TOTAL_ITEMVALUE' 	=> sprintf("%.2f",$item_value)   ,
	'TOTAL_ITEMDECAY' 	=> sprintf("%.2f", $item_decay),
	'TOTAL_ITEMTOTAL' 	=> sprintf("%.2f", $item_total),
	'LISTITEMS_FOOTCOUNT' => $listitems_footcount, 
	'ITEM_PAGINATION' => $pagination ,
	'S_DISPLAY_LOOTHISTORY' => true,
));

$template->assign_vars ( array (
'O_BUYER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=listitems&amp;mode=' . 
	$mode . '&amp;o=' . $current_order ['uri'] [4] . '&amp;start=' . 
	$start . '&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id > 0 )));

$title = $user->lang ['MENU_ITEMHIST']; 

// Output page
page_header ( $title );
?>