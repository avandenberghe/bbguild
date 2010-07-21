<?php
/**
 * Views detail of an item
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 * @todo : ---> add embedded item description  <----
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

if (isset($_GET[URI_ITEM]) )
{
    $sort_order = array(
        0 => array('i.item_date desc', 	'i.item_date'),
        1 => array('i.item_buyer', 		'i.item_buyer desc'),
        2 => array('i.item_value desc', 'i.item_value')
    );

    $current_order = switch_order($sort_order);
    $itemid = request_var(URI_ITEM, 0);
    
    // We want to view items by name and not id, so get the name
    $sql = 'SELECT item_name, item_gameid FROM ' . ITEMS_TABLE . " WHERE item_id = " . $itemid ;
    $result = $db->sql_query($sql);
	if (! $result)
	{
		$user->add_lang ( array ('mods/dkp_admin' ) );
		trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'], E_USER_WARNING );
	}
	
	$total_items = 0;
	while ( $row = $db->sql_fetchrow($result) )
    {
	    $item_name = $row['item_name'];
	    $item_gameid = (int) $row['item_gameid'];
	    $total_items++;
    } 
    $db->sql_freeresult ( $result );
    
    if ( empty($item_name) )
    {
		$user->add_lang ( array ('mods/dkp_admin' ) );
		trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'], E_USER_WARNING );
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
	
     $sql_array = array(
	    'SELECT'    => 	'i.item_dkpid, i.item_id, i.item_name, i.item_value, i.item_date, i.raid_id, i.item_buyer,
	    				 i.item_gameid, r.raid_name, m.member_id, m.member_dkpid, l.member_class_id, l.member_name', 
	    'FROM'      => array(
     			ITEMS_TABLE 		=> 'i', 
		        RAIDS_TABLE 		=> 'r',
		        MEMBER_DKP_TABLE 	=> 'm',
		        MEMBER_LIST_TABLE 	=> 'l',
	    	),
	 
	    'WHERE'     =>  "i.item_buyer = l.member_name
    					AND l.member_id = m.member_id 
    					AND i.item_dkpid = m.member_dkpid
    					AND r.raid_id = i.raid_id 
				        AND i.item_name='". $db->sql_escape($item_name) . "'",
	    'ORDER_BY'	=> $current_order['sql'], 
	    );

	$sql = $db->sql_build_query('SELECT', $sql_array);
	
    if ( !($result = $db->sql_query($sql)) )
    {
        trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'], E_USER_WARNING );
    }
	$title = $user->lang['ITEM'] . ' : '. $item_name;
	
    if ($bbDkp_Admin->bbtips == true)
	{
		if ($item_gameid > 0 )
		{
			$item_name = '<strong>' . $bbtips->parse('[itemdkp]' . $item_gameid  . '[/itemdkp]') . '</strong>' ; 
		}
		else 
		{
			$item_name = '<strong>' . $bbtips->parse ( '[itemdkp]' . $item_name . '[/itemdkp]' . '</strong>'  );
		}
		
	}
	else
	{
		$item_name = '<strong>' . $item_name . '</strong>';
	}    
    
	while ( $item = $db->sql_fetchrow($result) )
    {
        $template->assign_block_vars('items_row', array(
            'DATE' => ( !empty($item['item_date']) ) ? date('d.m.y', $item['item_date']) : '&nbsp;',
            'BUYER' => ( !empty($item['item_buyer']) ) ? $item['item_buyer'] : '&nbsp;',
            'U_VIEW_BUYER' => append_sid("{$phpbb_root_path}viewmember.$phpEx" , URI_NAME . '='.$item['item_buyer']. '&amp;' . URI_DKPSYS . '=' . $item['item_dkpid']) ,
            'U_VIEW_RAID' => append_sid("{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '='.$item['raid_id']) ,
            'RAID' => ( !empty($item['raid_name']) ) ? $item['raid_name'] : '&lt;<i>Not Found</i>&gt;',
            'VALUE' => $item['item_value'])
        );
    }
    $db->sql_freeresult ( $result );
    
    // breadcrumbs
    $navlinks_array = array(
    array(
     'DKPPAGE' => $user->lang['MENU_ITEMVAL'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}listitems.$phpEx"),
    ),

    array(
     'DKPPAGE' => $user->lang['MENU_VIEWITEM'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}viewitem.$phpEx" , URI_ITEM . '='. $itemid),
    ),
    );

    foreach( $navlinks_array as $name )
    {
	    $template->assign_block_vars('dkpnavlinks', array(
	    'DKPPAGE' => $name['DKPPAGE'],
	    'U_DKPPAGE' => $name['U_DKPPAGE'],
	    ));
    }
    
    $template->assign_vars(array(
		'L_PURCHASE_HISTORY_FOR' => sprintf($user->lang['PURCHASE_HISTORY_FOR'], '<strong>' . $item_name. '</strong>'),
        'O_DATE' 				=> $current_order['uri'][0],
        'O_BUYER'				 => $current_order['uri'][1],
        'O_VALUE'			 	=> $current_order['uri'][2],
        'U_VIEW_ITEM' 			=> append_sid("{$phpbb_root_path}viewitem.$phpEx" , URI_ITEM . '='. $itemid) ,
        'VIEWITEM_FOOTCOUNT' => sprintf($user->lang['VIEWITEM_FOOTCOUNT'], $total_items)
        )
    );

// Output page
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/viewitem.html')
);

page_footer();
}
else
{
   		$user->add_lang ( array ('mods/dkp_admin' ) );
		trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'], E_USER_WARNING );
}
?>
