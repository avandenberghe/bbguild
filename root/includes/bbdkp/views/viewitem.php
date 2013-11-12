<?php
/**
* Viewitem module. shows one loot to user
*  @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.8
 */

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

$item_id = request_var(URI_ITEM, 0);
$loot = new \bbdkp\controller\loot\Loot($item_id);
$purchased_items = $loot->Loothistory($loot->item_name);

$title = $user->lang['ITEM'] . ' : '. $loot->item_name;

if ($this->bbtips == true)
{
	if ($item_gameid == 'wow' )
	{
		$item_name = '<strong>' . $bbtips->parse('[itemdkp]' . $loot->wowhead_id  . '[/itemdkp]') . '</strong>' ;
	}
	else
	{
		$item_name = '<strong>' . $bbtips->parse ( '[itemdkp]' . $loot->item_name . '[/itemdkp]' . '</strong>'  );
	}

}
else
{
	$item_name = '<strong>' . $loot->item_name . '</strong>';
}

$template->assign_block_vars('items_row', array(
	'DATE' => ( !empty($item['item_date']) ) ? date('d.m.y', $item['item_date']) : '&nbsp;',
	'CLASSCOLOR' => ( !empty($item['member_name']) ) ? $item['colorcode'] : '',
	'CLASSIMAGE' => ( !empty($item['member_name']) ) ? $item['imagename'] : '',
	'BUYER' => ( !empty($item['member_name']) ) ? $item['member_name'] : '&nbsp;',
	'U_VIEW_BUYER' => append_sid("{$phpbb_root_path}dkp.$phpEx" , "page=viewmember&amp;" . URI_NAMEID . '='.$item['member_id']. '&amp;' . URI_DKPSYS . '=' . $item['event_dkpid']) ,
	'U_VIEW_RAID' => append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewraid&amp;' . URI_RAID . '='.$item['raid_id']) ,
	'RAID' => ( !empty($item['event_name']) ) ? $item['event_name'] : '&lt;<i>Not Found</i>&gt;',
	'VALUE' => $item['item_value'])
);

$navlinks_array = array(
array(
     'DKPPAGE' => $user->lang['MENU_ITEMVAL'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx", "page=listitems"),
),

array(
     'DKPPAGE' => $user->lang['MENU_VIEWITEM'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx" , "page=viewitem&amp;" . URI_ITEM . '='. $item_id),
));

foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
		'DKPPAGE' => $name['DKPPAGE'],
		'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

$template->assign_vars(array(
	'L_PURCHASE_HISTORY_FOR' => sprintf($user->lang['PURCHASE_HISTORY_FOR'], '<strong>' . $item_name. '</strong>'),
	'O_DATE' 				 => $current_order['uri'][0],
	'O_BUYER'				 => $current_order['uri'][1],
	'O_VALUE'			 	 => $current_order['uri'][2],
	'U_VIEW_ITEM' 			 => append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=viewitem&amp;' . URI_ITEM . '='. $item_id) ,
	'VIEWITEM_FOOTCOUNT' 	 => sprintf($user->lang['VIEWITEM_FOOTCOUNT'], $total_items),
	'S_DISPLAY_VIEWITEM' 	 => true,
));

// Output page
page_header($title);

?>