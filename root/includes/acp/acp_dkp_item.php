<?php
/***
* This class manages Items 
*
* @package bbDkp.acp
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

class acp_dkp_item extends bbDkp_Admin 
{
	var $u_action;
	var $link;
	function main($id, $mode) 
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang ( array ('mods/dkp_admin' ) );
		$user->add_lang ( array ('mods/dkp_common' ) );
		
		$this->link = '<br /><a href="' . append_sid ( "index.$phpEx", "i=dkp_item&mode=listitems" ) . '"><h3>'. $user->lang['RETURN_DKPINDEX'] .'</h3></a>';
		
		// Get item name from the appropriate field
		switch ($mode) 
		{
			case 'edititem' :
				// $_POST treatment
				$submit = (isset ( $_POST ['add'] )) ? true : false;
				$update = (isset ( $_POST ['update'] )) ? true : false;
				$delete = (isset ( $_GET ['itemdelete'] ) || isset($_POST['delete']) ) ? true : false;
				
		        if ( $submit || $update || $delete )
                {
                   	if (!check_form_key('additem'))
					{
						trigger_error('FORM_INVALID');
					}
       			}
        			
				//fetch $_GET from meta refresh in acp_dkp_raid or $_POST from postback
				$raid_id 	 = request_var(URI_RAID, 0); 
				$itemvalue 	 = request_var( 'item_value' , 0.0) ; 
				$itemgameid  = request_var( 'item_gameid' , 0) ;
				$item_buyers = request_var('item_buyers', array(0 => 0));
				$item_name = (isset ( $_POST ['item_name'] )) ? 
					utf8_normalize_nfc(request_var('item_name','', true)) : 
				    utf8_normalize_nfc(request_var( 'select_item_name', '', true));
				
				if ($submit) 
				{
					$this->additem($item_buyers, $raid_id, $itemvalue, $item_name, $itemgameid); 
				}
				if ($update) 
				{
					$this->updateitem($item_buyers, $raid_id, $itemvalue, $item_name, $itemgameid );  
				}
				if ($delete) 
				{
					$this->deleteitem(false);
				}
				$this->displayloot($raid_id, $itemvalue, $item_buyers, $item_name, $itemgameid );
				$this->page_title = 'ACP_ADDITEM';
				$this->tpl_name = 'dkp/acp_additem';
				
				break;
			
			case 'listitems' :
				$this->listitems();
				$this->page_title = 'ACP_LISTITEMS';
				$this->tpl_name = 'dkp/acp_' . $mode;
				
				break;
			
			case 'search' :
				$items_array = array ();
				
				if (isset( $_POST ['query'] )) 
				{
				    $bbdkp_items = array (); 
					//
					// Get item names from our standard items table
					//
					$sql = 'SELECT item_name FROM ' . RAID_ITEMS_TABLE . ' WHERE item_name ' . 
			 		$db->sql_like_expression($db->any_char . $db->sql_escape(utf8_normalize_nfc(request_var('query', ' ', true))) . $db->any_char) . ' ORDER BY item_name';
					$result = $db->sql_query ( $sql );
					while ( $row = $db->sql_fetchrow ( $result ) ) 
					{
						$bbdkp_items [] = $row['item_name'];
					}
					$db->sql_freeresult ( $result );
					
					// Build the drop-down
					$items_array = array_unique ( $bbdkp_items );
					sort ( $items_array );
					reset ( $items_array );
					$itemrow = 0;
					foreach ( $items_array as $item_name ) 
					{
						++$itemrow;
						$template->assign_block_vars ( 'items_row', array (
						'VALUE' =>  $item_name, 
						'OPTION' =>  $item_name  ) );
					}
					
					if ($itemrow != 0) 
					{
						// add results to select box
						$template->assign_vars ( 
						array (
							'S_RESULT' => true, 
							'L_RESULTS' => sprintf ( $user->lang ['RESULTS'], sizeof ($items_array), utf8_normalize_nfc(request_var('query', ' ', true))  ), 
						));
					}
				}
				
				$template->assign_vars ( array (
					'F_SEARCH_ITEM' => append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=search" ), 
					'L_LINKKI' => append_sid ( "index.$phpEx", 'i=dkp_item&amp;mode=viewitem&amp;' ), 
					'ONLOAD' => ' onload="javascript:document.post.query.focus()"', 
					 
				));
					
				$this->page_title = 'ACP_SEARCH_ITEM';
				$this->tpl_name = 'dkp/acp_' . $mode;
				
				break;
			
			case 'viewitem' :
				if (isset($_GET['item'])) 
				{
					
					$sql = 'SELECT * FROM ' . RAID_ITEMS_TABLE . ' WHERE item_name ' .
			 		$db->sql_like_expression($db->any_char . $db->sql_escape(utf8_normalize_nfc(request_var('item', ' ', true)))  . $db->any_char) ; 
					
					$result = $db->sql_query ( $sql );
					while ( $row = $db->sql_fetchrow ( $result ) ) 
					{
						$bbdkp_item = $row['item_name'];
					}
					$db->sql_freeresult ( $result );
					$bbdkp_item = str_replace ( '<table class="borderless" width="100%" cellspacing="0" cellpadding="0">', " ", $bbdkp_item );
					$bbdkp_item = str_replace ( "<table cellpadding='0' border='0' class='borderless'>", "", $bbdkp_item );
					
					$template->assign_block_vars ( 'items_row',
						 array ('VALUE' => $bbdkp_item ) 
					 );
				
				}
				
				$this->page_title = 'ACP_VIEW_ITEM';
				$this->tpl_name = 'dkp/acp_' . $mode;
				
			break;
		
		}
	
	} // end main
	
	/**
	 * template filling for item
	 * index.php?i=dkp_item&mode=additem&item=2
	 * index.php?i=dkp_item&mode=additem&raid_id=1;
	 */
	function displayloot($raid_id, $itemvalue, $item_buyers, $item_name, $itemgameid )
	{
		global $db, $user, $config, $template, $phpEx;

		//fetch raidinfo
		$sql_array = array(
			    'SELECT'    => 'r.raid_id, e.event_dkpid, e.event_name, r.raid_start, r.raid_note  ',
			    'FROM'    	=> array(EVENTS_TABLE => 'e', 
									 RAIDS_TABLE => 'r', 
									  ),
			    'WHERE'     =>  'r.event_id = e.event_id and r.raid_id = ' . (int) $raid_id
			);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$dkpid = 0;
		$raidtitle = '';
		$raiddate = '';
		$result = $db->sql_query ($sql);
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$dkpid = $row['event_dkpid']; 
			$raidtitle = $row['event_name']; 
			$raiddate = $user->format_date($row['raid_start']); 
			$template->assign_block_vars ( 'raids_row', array (
				'VALUE' 	=> $row['raid_id'], 
				'SELECTED' 	=> ($raid_id == $row['raid_id']) ? ' selected="selected"' : '', 
				'OPTION' 	=> date ( $config['bbdkp_date_format'], $row['raid_start'] ) . ' - ' .  $row['event_name'] . ' ' . $row['raid_note'])  );
		}
		$db->sql_freeresult ( $result );

		// fetch item info
		$item_id = request_var(URI_ITEM, 0); 
		$buyer_source = ''; 
		if($item_id != 0)
		{	
			//get groupkey and base info from item
			$sql_array = array(
			    'SELECT'    => 'i.item_name, i.member_id, i.raid_id, i.item_value, i.item_date, i.item_gameid, i.item_group_key, i.item_decay, i.item_zs  ',
			    'FROM'    	=> array(RAID_ITEMS_TABLE => 'i'),
			    'WHERE'     =>  'i.item_id = ' . (int) $item_id
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query ( $sql );
			if (! $row = $db->sql_fetchrow ( $result )) 
			{
				trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'] );
			}

			$db->sql_freeresult ( $result );
			$this->item = array (
				'select_item_name' 	=> utf8_normalize_nfc(request_var('select_item_name', ''),true) ,  
				'item_name' 		=> $row['item_name'],
			    'item_gameid' 		=> $row['item_gameid'],
				'raid_id' 			=> $row['raid_id'], 
				'item_value' 		=> $row[ 'item_value'],
				'item_group_key'	=> $row[ 'item_group_key'],
				'item_decay'		=> $row[ 'item_decay'],
				'item_zs'			=> $row[ 'item_zs'],
			);
			
			//get all buyers who bought the same item, they share item_group_key
			$buyers = array ();
			$sql_array = array(
			    'SELECT'    => 'i.member_id, l.member_name, i.item_group_key  ',
			    'FROM'    	=> array(RAID_ITEMS_TABLE => 'i', 
									 MEMBER_LIST_TABLE => 'l', 
									  ),
			    'WHERE'     =>  "i.member_id = l.member_id and i.item_group_key = '" . (string) $this->item ['item_group_key'] . "'"  
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query ( $sql );
			while ($row = $db->sql_fetchrow ( $result )) 
			{
				$buyers [] = $row['member_id'];
			}
			
			if (isset($_POST['member_id']))
			{
				$this->item ['member_id'] = request_var('item_buyers', array(0 => 0),true); 
			}
			else 
			{
				$this->item ['item_buyers']  = $buyers; 
			}
			unset ( $buyers );
			$buyer_source = $this->item ['item_buyers']; 
			$db->sql_freeresult ( $result );
	
			//left selection member pane : select all members from the raid where this item dropped
			$sql = 'SELECT a.member_id, l.member_name from ' . RAID_DETAIL_TABLE . ' a, ' . RAID_ITEMS_TABLE . ' b,  ' . MEMBER_LIST_TABLE . ' l 
			where a.member_id = l.member_id  and a.raid_id = b.raid_id
			and b.item_id = ' . (int) $item_id . ' ORDER BY l.member_name ';
		
		}
		else 
		{
			//no itemid
			if ($raid_id != 0)
			{
				//left selection member pane : get all raidatendees
				$sql = 'SELECT a.member_id, l.member_name from ' . RAID_DETAIL_TABLE . ' a , ' . MEMBER_LIST_TABLE . ' l 
				where a.member_id = l.member_id and a.raid_id = ' .  $raid_id . ' ORDER BY l.member_name '; 
			}
		
			if(isset ( $_POST ['item_buyers'] ))
			{
				$buyer_source = request_var('item_buyers', array(0 => 0)); 
			}
		}
		
		// process sql for left select ion pane
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			//left
			$template->assign_block_vars ( 
				'raiders_row', array (
				'VALUE' => $row['member_id'], 
				'OPTION' => $row['member_name'] ) );

			//right
			if (@in_array ( $row['member_id'], $buyer_source )) 
			{
				// fill buyer block if it is in raidmember block
				$template->assign_block_vars ( 
				'buyers_row', array (
				'VALUE' => $row['member_id'], 
				'OPTION' => $row['member_name'] ) );
			}
		}
		$db->sql_freeresult ( $result );
		
		/*************************
		*  Build item drop-down
		****************************/
		$max_value = 0;
		$result = $db->sql_query ('SELECT max(item_value) AS max_value FROM ' . RAID_ITEMS_TABLE );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$max_value = $row['max_value'];
		}
		$float = @explode ( '.', $max_value );
		$floatlen = @strlen ( $float [0] );
		$format = '%0' . $floatlen . '.2f';
		
		$sql = 'SELECT i.item_id, i.item_value, i.item_name, i.item_gameid FROM ' . 
			RAID_ITEMS_TABLE . ' i,  ' . RAIDS_TABLE . ' r,  ' . EVENTS_TABLE . ' e
	        where i.raid_id=r.raid_id and r.event_id=e.event_id and e.event_dkpid = ' . $dkpid . ' 
			ORDER BY item_name, item_date DESC';
			
		$result = $db->sql_query ( $sql );
		$item_name = utf8_normalize_nfc(request_var('item_name','', true)); 
		$item_select_name = utf8_normalize_nfc(request_var( 'select_item_name', '', true));
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			
				$template->assign_block_vars ( 'items_row', array (
				'VALUE' 	=> $row['item_id'], 
				'SELECTED' 	=> (($row['item_name'] == $item_name) || ($row['item_name'] == $item_select_name)) ? ' selected="selected"' : '', 
				'OPTION' 	=> $row['item_name'] . ' (' . sprintf ( $format, $row['item_value'] ) . ' dkp) ', 
				
				) );						
		}
		
		$db->sql_freeresult ( $result );
				
		$form_key = 'additem';
		add_form_key($form_key);
					
		
		//constant template variables
		$template->assign_vars ( array (
		'U_BACK'			=> append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id ),
		'L_TITLE' 			=> $user->lang ['ACP_ADDITEM'], 
		'L_EXPLAIN' 		=> $user->lang ['ACP_ADDITEM_EXPLAIN'],
		'F_ADD_ITEM' 		=> append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=edititem&amp;" . URI_RAID . '=' . $raid_id ), 
		'ITEM_ID'			=> $item_id, 
		'ITEM_VALUE' 		=> isset($this->item['item_value']) ? $this->item['item_value'] : 0.00,
		
		// Language
		'MSG_NAME_EMPTY' 	=> $user->lang ['FV_REQUIRED_ITEM_NAME'],
		'MSG_RAID_ID_EMPTY' => $user->lang ['FV_REQUIRED_RAIDID'], 
		'MSG_VALUE_EMPTY' 	=> $user->lang ['FV_REQUIRED_VALUE'], 
		'ITEM_VALUE_LENGTH' => ($floatlen + 3), // The first three digits plus '.00';

		)
		
		);

		if($item_id)
		{
			$template->assign_vars ( array (
				'ITEMTITLE'		=> sprintf($user->lang['LOOTUPD'], $raidtitle, $raiddate  ) , 
				'ITEM_ZS'		=> ($this->item['item_zs'] == 1) ? ' checked="checked"' : '',  
				'ITEM_DECAY'	=> $this->item['item_decay'],
				'ITEM_NAME' 	=> isset($this->item['item_name']) ? $this->item['item_name'] : '' , 
				'ITEM_GAMEID' 	=> isset($this->item['item_gameid']) ? $this->item['item_gameid'] : '' ,
				'S_ADD' 		=> false, 
				'ITEM_ID' 		=> $item_id, 
			));
		}
		else 
		{
			$template->assign_vars ( array (
				'ITEMTITLE'		=> sprintf($user->lang['LOOTADD'], $raidtitle, $raiddate  ) , 
				'S_ADD' 		=> true
			));			
		}
		
		
	}
	
	function listitems()
	{
		global $db, $user, $config, $template, $phpEx, $phpbb_root_path;
				
		// add member button redirect
		$showadd = (isset($_POST['itemadd'])) ? true : false;
	    if($showadd)
	    {
			redirect(append_sid("index.$phpEx", "i=dkp_item&amp;mode=additem"));            		
	        break;
	    }
            	
		if ($this->bbtips == true)
		{
			if ( !class_exists('bbtips')) 
			{
				require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
			}
			$bbtips = new bbtips;
		}
		
		// select all dkp pools that have items				
		$sql_array = array(
	    'SELECT'    => 'd.dkpsys_id, d.dkpsys_name, d.dkpsys_default',
	    'FROM'      => array(
	        DKPSYS_TABLE => 'd',
	        EVENTS_TABLE => 'e',
	        RAIDS_TABLE => 'r',
	        RAID_ITEMS_TABLE    => 'i'
	    ),
	    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    				and e.event_id = r.event_id 
	    				and r.raid_id = i.raid_id',  
	    'GROUP_BY'  => 'd.dkpsys_id', 
		);
 
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		$row = $db->sql_fetchrow ($result);
		
		if($row)
		{
			$dkpid = 1; 
			$submitdkp = (isset ( $_POST ['dkpsys_id'] )) ? true : false;
			if ($submitdkp) 
			{
				// get dkp pool value from popup
				$dkpid = request_var ( 'dkpsys_id', 0 );
			}
			else
			{
				// just select first row
				$result = $db->sql_query_limit ( $sql, 1 );
				while ( $row = $db->sql_fetchrow ( $result ) ) 
				{
					$dkpid = $row['dkpsys_id'];
				} 
				$db->sql_freeresult ( $result );
			}
			$result = $db->sql_query ( $sql );
			
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$template->assign_block_vars ( 'dkpsys_row', 
					array (
					'VALUE' => $row['dkpsys_id'], 
					'SELECTED' => ($row['dkpsys_id'] == $dkpid) ? ' selected="selected"' : '', 
					'OPTION' => (! empty ( $row['dkpsys_name'] )) ? $row['dkpsys_name'] : '(None)' ) );
			}
			$db->sql_freeresult ( $result );
			
			// select all raids that have items	for pool			
			$sql_array = array(
			    'SELECT'    => 'r.raid_id, e.event_name, r.raid_start, raid_note  ',
			    'FROM'    	=> array(DKPSYS_TABLE => 'd', 
									 EVENTS_TABLE => 'e',
	        						 RAIDS_TABLE => 'r'
									 ),
			    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    						and e.event_id = r.event_id 
			    				and d.dkpsys_id = ' . $dkpid ,
				'ORDER_BY'  =>  'r.raid_start DESC '
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			
			$submitraid = (isset ( $_POST ['raid_id']) || isset ( $_GET ['raid_id']) ) ? true : false;
			$raid_id = 0;
			if ($submitraid)
			{
				$raid_id  = request_var(URI_RAID, 0);  
			}
			else
			{
				//get 1st raid
				$result = $db->sql_query_limit ( $sql, 1 );
				while ( $row = $db->sql_fetchrow ( $result ) ) 
				{
					$raid_id = (int) $row['raid_id'];
				}
				$db->sql_freeresult ( $result );
			}
			
			// populate raid selectbox
			$result = $db->sql_query ($sql);
			while ( $row = $db->sql_fetchrow ( $result ) )
			{
				$template->assign_block_vars ( 'raids_row', array (
					'VALUE' => $row['raid_id'], 
					'SELECTED' => ($raid_id == $row['raid_id']) ? ' selected="selected"' : '', 
					'OPTION' => $user->format_date($row['raid_start']) . ' - ' .  $row['event_name'] . ' ' . $row['raid_note'] ) );
			}
			$db->sql_freeresult ( $result );
			$sql1 = 'SELECT count(*) as countitems FROM ' . RAID_ITEMS_TABLE . ' where raid_id = ' .  $raid_id; 
	
			$result1 = $db->sql_query ( $sql1 );
			$total_items = (int) $db->sql_fetchfield('countitems', false,$result1 );
			$db->sql_freeresult ( $result1 );
	
			$start = request_var('start', 0);
			
	   		$sort_order = array (
				0 => array ('i.item_date desc', 'item_date' ), 
				1 => array ('l.member_name', 'member_name desc' ), 
				2 => array ('i.item_name', 'item_name desc' ), 
				3 => array ('e.event_name', 'event_name desc' ), 
				4 => array ('i.item_value desc', 'item_value' ),
				5 => array ('d.dkpsys_name desc', 'dkpsys_name' )
				);
			$current_order = switch_order ($sort_order);
			
	       $pagination = generate_pagination ( 
	       append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;" ) .
	        '&amp;o=' . $current_order ['uri'] ['current'], $total_items, $config['bbdkp_user_ilimit'], $start );
			
			//prepare item list sql
			$sql_array = array(
		    'SELECT'    => 'd.dkpsys_name, e.event_dkpid, e.event_name, e.event_color, e.event_imagename, i.item_id, i.item_name, i.item_gameid, 
		    				i.member_id, l.member_name, i.item_date, i.raid_id, i.item_value, e.event_name ',
		    'FROM'      => array(
		        DKPSYS_TABLE   => 'd', 
				EVENTS_TABLE   => 'e',
		        RAIDS_TABLE    => 'r',
		        MEMBER_LIST_TABLE => 'l', 
		        RAID_ITEMS_TABLE    => 'i',
		    ),
		    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    					and e.event_id = r.event_id 
	    					and i.member_id = l.member_id 
	    					and r.raid_id = i.raid_id 
		     				and d.dkpsys_id = ' . $dkpid . ' 
		     				AND i.raid_id = ' .  $raid_id,  
		    'ORDER_BY'  => $current_order ['sql'], 
			);
	
			$sql = $db->sql_build_query('SELECT', $sql_array);					
			$items_result = $db->sql_query ( $sql );
			
			$listitems_footcount = sprintf ($user->lang ['LISTPURCHASED_FOOTCOUNT_SHORT'], $total_items);
			
			while ( $item = $db->sql_fetchrow ( $items_result ) ) 
			{
			    if ($this->bbtips == true)
				{
					if ($item['item_gameid'] > 0 )
					{
						$item_name = $bbtips->parse('[itemdkp]' . $item['item_gameid']  . '[/itemdkp]'); 
					}
					else 
					{
						$item_name = $bbtips->parse('[itemdkp]' . $item['item_name']  . '[/itemdkp]');
					}
			
				}
				else
				{
					$item_name = $item['item_name'];
				}
	
				$template->assign_block_vars ( 'items_row', array (
				'DATE' 			=> (! empty ( $item ['item_date'] )) ? $user->format_date($item['item_date']) : '&nbsp;', 
				'BUYER' 		=> (! empty ( $item ['member_name'] )) ? $item ['member_name'] : '&lt;<i>Not Found</i>&gt;', 
				'ITEMNAME'      => $item_name, 
				'RAIDDKP' 		=> (! empty ( $item ['dkpsys_name'] )) ? $item ['dkpsys_name']  : '&lt;<i>Not Found</i>&gt;',
				'RAID' 			=> (! empty ( $item ['event_name'] )) ?  $item ['event_name']  : '&lt;<i>Not Found</i>&gt;', 
				'EVENTCOLOR'    => (! empty ( $item ['event_color'] )) ? $item ['event_color']  : '',
				'U_VIEW_BUYER' 	=> (! empty ( $item ['member_name'] )) ? append_sid ( "index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;member_id={$item['member_id']}&amp;" . URI_DKPSYS . "={$item['event_dkpid']}") : '' ,
				'U_VIEW_RAID' 	=> (! empty ( $item ['event_name'] )) ? append_sid ( "index.$phpEx", "i=dkp_raid&amp;mode=addraid&amp;" . URI_DKPSYS . "={$item['event_dkpid']}&amp;" . URI_RAID . "={$raid_id}" ) : '', 
				'U_VIEW_ITEM' 	=> append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=additem&amp;" . URI_ITEM . "={$item['item_id']}&amp;" . URI_RAID . "={$raid_id}" ),
				'U_DELETE_ITEM' => append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=additem&amp;itemdelete=Y&amp;" . URI_ITEM . "={$item['item_id']}" ),
				'VALUE' 		=> $item ['item_value']));
			}
			
			$db->sql_freeresult ( $items_result );
			
			$template->assign_vars ( array (
				'S_SHOW' 		=> true,
				'F_LIST_ITEM' 	=>   append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=listitems" ), 
				'L_TITLE' 		=> $user->lang ['ACP_LISTITEMS'], 
				'L_EXPLAIN' 	=> $user->lang ['ACP_LISTITEMS_EXPLAIN'], 
				'O_DATE' 		=> $current_order ['uri'][0], 
				'O_BUYER' 		=> $current_order ['uri'][1], 
				'O_NAME' 		=> $current_order ['uri'][2], 
				'O_RAID' 		=> $current_order ['uri'][3], 
				'O_VALUE' 		=> $current_order ['uri'][4], 
				'O_RAIDDKP' 	=> $current_order ['uri'][5], 
				'U_LIST_ITEMS' 	=> append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;start={$start}&amp;" . URI_RAID . '=' . $raid_id), 
				'S_BBTIPS' 		=> $this->bbtips, 
				'START' 		=> $start, 
				'LISTITEMS_FOOTCOUNT' => $listitems_footcount, 
				'ITEM_PAGINATION' => $pagination 
			));
		}
		else 
		{
			$template->assign_vars ( array (
				'F_LIST_ITEM' 	=>  append_sid ( "index.$phpEx", "i=dkp_item&amp;mode=listitems" ), 
				'L_TITLE' 		=> $user->lang ['ACP_LISTITEMS'], 
				'L_EXPLAIN' 	=> $user->lang ['ACP_LISTITEMS_EXPLAIN'], 
				'S_SHOW' 		=> false,
				
				'S_BBTIPS' 		=> $this->bbtips, 
			));			
		}
		

	}
	
	/***
	 * adding new items to a raid
	 * adds an item to the database
	 * increases buyer account spent
	 * 
	 * called from : acp item adding
	 * 
	 * @param $dkpid = the dkp pool
	 * @param raidid = the raid to which we add the item
	 * @param itemvalue (float) the item cost
	 * @param item_buyers = array with buyers
	 * @param itemname
	 * 
	 */
	function additem($item_buyers, $raid_id, $itemvalue, $item_name, $itemgameid)
	{
		global $db, $user, $config, $template, $phpEx, $phpbb_root_path;
		$errors_exist = $this->error_check ();
		if ($errors_exist) 
		{
			$this->fv->displayerror($this->fv->errors);
		}
		
		// Find out the item date based on the raid it's associated with
		$loottime = 0;
		$sql = 'SELECT raid_start FROM ' . RAIDS_TABLE . ' WHERE raid_id =' . (int) $raid_id;
		$result = $db->sql_query ( $sql); 
		$row = $db->sql_fetchrow ($result); 
		if($row)
		{
			$loottime = $row['raid_start'];
		}
	
		//
		// Generate random group key
		$group_key = $this->gen_group_key ( $item_name, $loottime, $raid_id + rand(10,100) );
		
		//
		// Add item to selected members
		$this->add_new_item ($item_name, $item_buyers, $group_key, $itemvalue, $raid_id, $loottime, $itemgameid);
		
		//
		// Logging
		//
		$log_action = array (
		'header' 		=> 'L_ACTION_ITEM_ADDED',
		'L_NAME' 		=> $item_name, 
		'L_BUYERS' 		=> implode ( ', ', $item_buyers  ),
		'L_RAID_ID' 	=> $raid_id, 
		'L_VALUE'   	=> $itemvalue , 
		'L_ADDED_BY' 	=> $user->data['username']);
		
		$this->log_insert ( array (
			'log_type' => $log_action ['header'], 
			'log_action' => $log_action ) );
		
		//
		// Success message
		//
		$success_message = sprintf ( $user->lang ['ADMIN_ADD_ITEM_SUCCESS'], $item_name, implode ( ', ', $item_buyers  ), $itemvalue );
		trigger_error ( $success_message . $this->link, E_USER_NOTICE );
	}

	/**
	 * does the actual item-adding database operations
	 * called from : item acp adding, updating
	 * 
	 * @param item_name
	 * @param item_buyers = array with buyers
	 * @param group key : hash
	 * @param raidid = the raid to which we add the item
	 * @param itemvalue (float) the item cost
	 * @param raidid
	 * @param $itemgameid : if this is zero we dont care
	 * 
	 */
	function add_new_item($item_name, $item_buyers, $group_key, $itemvalue, $raid_id, $loottime, $itemgameid) 
	{

		global $db, $user, $config;
		$query = array ();
		
		$sql = "select e.event_dkpid from " . EVENTS_TABLE . " e , " . RAIDS_TABLE . " r 
		where r.raid_id = " . $raid_id . " and e.event_id = r.event_id"; 
		$result = $db->sql_query($sql);
		$dkpid = (int) $db->sql_fetchfield('event_dkpid', false, $result);
		$db->sql_freeresult ( $result);
		
		$db->sql_transaction('begin');
		
		// increase dkp spent value for buyers 
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' d 				
				SET d.member_spent = d.member_spent + ' . (float) $itemvalue  .  ' 
				WHERE d.member_dkpid = ' . (int) $dkpid  . ' 
			  	AND ' . $db->sql_in_set('member_id', $item_buyers) ;
		$db->sql_query ( $sql );
		
		$sql = 'select member_id from ' . RAID_DETAIL_TABLE . ' where raid_id = ' . $raid_id; 
		$result = $db->sql_query($sql);
		$raiders = array();
		while ( $row = $db->sql_fetchrow ($result))
		{
			$raiders[]= $row['member_id'];
		} 
		$db->sql_freeresult ( $result);
		
		$numraiders = count($raiders);
		$distributed = round($itemvalue/max(1, $numraiders), 2);
		// rest of division
		$restvalue = $itemvalue - ($numraiders * $distributed); 
		
		// Add purchase(s) to items table
		// note : itemid is generated with pk autoincrease
		foreach ( $item_buyers as $key => $this_member_id ) 
		{
			$query [] = array (
   				'item_name' 		=> (string) $item_name , 
   				'member_id' 		=> (int) $this_member_id, 
   				'raid_id' 			=> (int) $raid_id, 
   				'item_value' 		=> (float) $itemvalue, 
   				'item_date' 		=> (int) $loottime, 
   				'item_group_key' 	=> (string) $group_key, 
				'item_gameid' 		=> (int) $itemgameid,
				'item_zs'			=> (int) $config['bbdkp_zerosum'], 
   				'item_added_by' 	=> (string) $user->data ['username'] 
   				);
    				
			//if zerosum flag is set then distribute item value over raiders
			if($config['bbdkp_zerosum'] == 1)
			{
				// also increase raid detail table
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '  				
						SET zerosum_bonus = zerosum_bonus + ' . (float) $distributed . ' 
						WHERE raid_id = ' . (int) $raid_id;
				$db->sql_query ( $sql );
				
				// allocate dkp itemvalue bought to all raiders
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '  				
						SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $distributed  .  ', 
						member_earned = member_earned + ' . (float) $distributed  .  ' 
						WHERE member_dkpid = ' . (int) $dkpid  . ' 
					  	AND ' . $db->sql_in_set('member_id', $raiders) ;
				$db->sql_query ( $sql );
				
				// give rest value to buyer in raiddetail
				if($restvalue!=0 )
				{
					$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '  				
							SET zerosum_bonus = zerosum_bonus + ' . (float) $restvalue  .  '   
							WHERE raid_id = ' . (int) $raid_id . '  
						  	AND member_id = ' . $this_member_id; 
					$db->sql_query ( $sql );					
					
					$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '  				
							SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $restvalue  .  ', 
							member_earned = member_earned + ' . (float) $restvalue  .  ' 
							WHERE member_dkpid = ' . (int) $dkpid  . ' 
						  	AND member_id = ' . $this_member_id; 
					$db->sql_query ( $sql );					
				}
				
			}
			
				
		}
		$db->sql_multi_insert(RAID_ITEMS_TABLE, $query);
		
		$db->sql_transaction('commit');
		
		return true;
	}
	
	/**
	 * Deletes item(s) to which item group key belongs 
	 * 
	 * @groupdelete : if true then all groupid items also deleted
	 */	
	function deleteitem($groupdelete = false)
	{
		global $db, $user, $config, $template, $phpEx, $phpbb_root_path;
		// get itemid after confirm
		$item_id = request_var('hidden_item_id', 0); 
		
		if($item_id==0)
		{	
			//get from listing
			$item_id = request_var(URI_ITEM, 0);
		}
		
		if($item_id==0)
		{	//if still 0 give up
			trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'] , E_USER_WARNING);
		}
		
		$sql = "select distinct e.event_dkpid from " . EVENTS_TABLE . " e , " . RAIDS_TABLE . " r, " . RAID_ITEMS_TABLE . " i 
				where i.item_id = " . $item_id . " 
				and e.event_id = r.event_id and i.raid_id = r.raid_id "; 
		$result = $db->sql_query($sql);
		$dkpid = (int) $db->sql_fetchfield('event_dkpid', false, $result);
		$db->sql_freeresult ( $result);
		
		$item_ids = array ();
		$old_buyers = array ();
		//
		// Build the item_ids, old_buyers and old_item arrays
		//
		if ($groupdelete)
		{
				$sql_array = array(
				    'SELECT'    => 'i2.* ',
				    'FROM'    	=> array(RAID_ITEMS_TABLE => 'i2'),
				    'LEFT_JOIN' => array(
						array(
							'FROM'	=> array(RAID_ITEMS_TABLE => 'i1'),
							'ON'	=> ' i1.item_group_key = i2.item_group_key '
						)), 					    		
				    'WHERE'     =>  'i1.item_id= '. $item_id,
				);
				$sql = $db->sql_build_query('SELECT', $sql_array);
		}
		else 
		{
			$sql = 'SELECT * FROM ' . RAID_ITEMS_TABLE . ' WHERE item_id= ' . $item_id;
		}
		
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$item_ids [] 	= $row['item_id'];
			$old_buyers []  =   $row['member_id'] ;
			
			$old_item = array (
			'item_name' 	=>  (string) $row['item_name'] , 
			'item_buyers' 	=>  (array) $old_buyers, 
			'raid_id' 		=>  (int) 	$row['raid_id'] , 
			'item_date' 	=>  (int) 	$row['item_date'] , 
			'item_value' 	=>  (float) $row['item_value']  );
		}

		$item_buyers = implode ( ', ', $old_item ['item_buyers'] );
		
		if (confirm_box ( true )) 
		{
			// delete items and decrease buyer spend
			$this->delete_old_item($dkpid, $item_ids, $item_buyers, $old_item) ; 

			$log_action = array (
				'header' 	=> 'L_ACTION_ITEM_DELETED',
				'L_NAME' 	=> $old_item ['item_name'], 
				'L_BUYERS' 	=> $item_buyers, 
				'L_RAID_ID' => $old_item ['raid_id'], 
				'L_VALUE' 	=> $old_item ['item_value'] );
			
			$this->log_insert ( array (
				'log_type' 		=> $log_action ['header'], 
				'log_action' 	=> $log_action ) );
			
			$success_message = sprintf ( $user->lang ['ADMIN_DELETE_ITEM_SUCCESS'], 
			$old_item ['item_name'], $item_buyers, $old_item ['item_value'] );
			
			trigger_error ( $success_message . $this->link, E_USER_NOTICE );
		
		} 
		else
		{
			$s_hidden_fields = build_hidden_fields ( array (
				'delete' 		=> true, 
				'hidden_item_id' => $item_id,
				'item_ids' 		=> $item_ids, 
				'item_buyers' 	=> $item_buyers, 
				'old_item' 		=> $old_item
			));

			$template->assign_vars ( array (
				'S_HIDDEN_FIELDS' => $s_hidden_fields ) );
			confirm_box ( false, sprintf($user->lang ['CONFIRM_DELETE_ITEM'], $old_item ['item_name'], $item_buyers  ), $s_hidden_fields );
		}
				
	}

	/**
	 * does the actual item-deleting database operations
	 * called from : item acp adding, updating 
	 * 
	 * @param item_ids = array with itemids to delete for multiple buyers
	 * @param item_buyers = array with buyers
	 * @param $old_item = array with single item info
	 * 
	 */
	function delete_old_item($dkpid, $item_ids, $item_buyers, $old_item) 
	{
		global $db, $user;
		$query = array ();
		
		$db->sql_transaction('begin');
		
		// 1) Remove the item purchase from the items table
		$sql = 'DELETE FROM ' . RAID_ITEMS_TABLE . ' WHERE ' . $db->sql_in_set('item_id', $item_ids);
		$db->sql_query ( $sql );
			
		// decrease dkp spent value from buyers with purchase value
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' d
				SET d.member_spent = d.member_spent - ' . $old_item ['item_value'] .  ' 
				WHERE d.member_dkpid = ' . (int) $dkpid . ' 
			  	AND ' . $db->sql_in_set('member_id', $item_buyers) ;
		$db->sql_query ( $sql );
		
		$db->sql_transaction('commit');
		
		return true;

	}
	
	/***
	 * updating item buyer 
	 * 
	 * 
	 * @param dkpid, 
	 * @param raid_id 
	 * @param item_id
	 * 
	 * new data **
	 * @param item_buyers, 
	 * @param itemvalue*  
	 * @param itemname* 
	 * @param itemgameid*
	 * 
	 */

	function updateitem( $item_buyers, $raid_id, $itemvalue, $item_name, $itemgameid )  
	{
		global $db, $user, $config, $template, $phpEx, $phpbb_root_path;
		$errors_exist = $this->error_check ();
		if ($errors_exist) 
		{
			$this->fv->displayerror($this->fv->errors);
		}
		$item_id = request_var('hidden_item_id', 0); 
		$item_ids = array ();
		$old_buyers = array ();
		
		$sql = "select e.event_dkpid from " . EVENTS_TABLE . " e , " . RAIDS_TABLE . " r where r.raid_id = " . $raid_id . " and e.event_id = r.event_id"; 
		$result = $db->sql_query($sql);
		$dkpid = (int) $db->sql_fetchfield('event_dkpid', false, $result);
		$db->sql_freeresult ( $result);
		
		//
		// Build the item_ids, old_buyers and old_item arrays
		//
		$sql_array = array(
		    'SELECT'    => 'i2.* ',
		    'FROM'    	=> array(RAID_ITEMS_TABLE => 'i2'),
		    'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(RAID_ITEMS_TABLE => 'i1'),
					'ON'	=> ' i1.item_group_key = i2.item_group_key '
				)), 					    		
		    'WHERE'     =>  'i1.item_id= '. $item_id,
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ($result)) 
		{
			$item_ids 	[] = $row['item_id'];
			$old_buyers [] = $row['member_id'];
			
			$old_item = array (
			'item_name' 	=>  (string) $row['item_name'] , 
			'item_buyers' 	=>  (array) $old_buyers, 
			'raid_id' 		=>  (int) $row['raid_id'] , 
			'item_date' 	=>  (int) $row['item_date'] , 
			'item_value' 	=>  (float) $row['item_value'],
			'item_decay' 	=>  (float) $row['item_decay'],   
			'item_zs'		=>  (int) $row['item_zs'], 
			'item_added_by' =>  (string) $user->data ['username'], 			
			);
		}
		$this->delete_old_item($dkpid, $item_ids, $old_item ['item_buyers'], $old_item) ; 
		
		//generate hash 
		$group_key = $this->gen_group_key ( $item_name, $old_item ['item_date'], $item_id + rand(10,100) );

		//
		// Add new item to selected members
		//
		$this->add_new_item ( $item_name, $item_buyers, $group_key, $itemvalue, $old_item ['raid_id'], $old_item ['item_date'], $itemgameid);
		
		//
		// Logging
		//
		$log_action = array (
		'header' => 		'L_ACTION_ITEM_UPDATED',
		'L_NAME_BEFORE' 	=> $old_item ['item_name'],
		'L_BUYERS_BEFORE' 	=> implode ( ', ', $old_item ['item_buyers'] ), 
		'L_RAID_ID_BEFORE' 	=> $old_item ['raid_id'], 
		'L_VALUE_BEFORE' 	=> $old_item ['item_value'], 
		'L_NAME_AFTER' 		=> $item_name , 
		'L_BUYERS_AFTER' 	=> (is_array($item_buyers) ? implode ( ', ', $item_buyers  ) : trim($item_buyers)), 
		'L_RAID_ID_AFTER' 	=> $raid_id , 
		'L_VALUE_AFTER' 	=> $itemvalue , 
		'L_UPDATED_BY' 		=> $user->data ['username'] );
		
		$this->log_insert ( array (
		'log_type' => $log_action ['header'], 
		'log_action' => $log_action ) );
		
		//
		// Success message
		//
		$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_ITEM_SUCCESS'], $old_item ['item_name'], 
			(is_array($old_item ['item_buyers']) ? implode ( ', ', $old_item ['item_buyers']  ) : trim($old_item ['item_buyers'])) ,
			$old_item ['item_value'] );
			
		trigger_error ( $success_message . $this->link, E_USER_NOTICE );
	}
	
	/*
	 * checks items for errors
	 */
	function error_check() 
	{
		global $user;
		
		if (! isset ( $_POST ['item_buyers'] ))
		{
			trigger_error ( $user->lang ['FV_REQUIRED_BUYERS'], E_USER_WARNING );
		}
		
		$this->fv->is_filled ( 
		array 
		(
			request_var('raid_id',0) 		=> $user->lang ['FV_REQUIRED_RAIDID'], 
			request_var('item_value',0.00)	=> $user->lang ['FV_REQUIRED_VALUE'] ) 
		);
		
		if (isset ( $_POST ['item_name'] )) 
		{
			$this->item ['item_name'] = utf8_normalize_nfc(request_var('item_name', ' ', true));
		} 
		
		elseif ( isset($_POST ['select_item_name'])) 
		{
			$this->item ['item_name'] = utf8_normalize_nfc(request_var('select_item_name', ' ', true));
		} 
		else 
		{
			$this->fv->errors ['item_name'] = $user->lang ['FV_REQUIRED_ITEM_NAME'];
		}
		return $this->fv->is_error ();
	}
	
	 /***
     * Zero-sum DKP function
     * will increase earned points for members present at loot time (== bosskill time) or present in Raid, depending on Settings
     * ex. player A pays 100dkp for item A
     * there are 15 players in raid
     * so each raider gets 100/15 = earned bonus 6.67 
     * 
     * called from _loot_add
     * 
     * @param $raiders : list of raiders
     * @param 
     * @param $itemvalue : all itemvalue 
     * 
     * returns the sql to update
     * 
     */
    function zero_balance($itemvalue)
    {
    	global $db;
    	
    	$zerosumdkp = round( $itemvalue / count($this->bossattendees[$this->batchid][$boss] , 2)); 
    	
    	$sql = ' UPDATE ' . MEMBER_DKP_TABLE . ' d, ' . MEMBER_LIST_TABLE  . ' m 
			SET d.member_earned = d.member_earned + ' . (float) $zerosumdkp  .  ' 
			WHERE d.member_dkpid = ' . (int) $this->dkp  . ' 
		  	 AND  d.member_id =  m.member_id 
		  	 AND ' . $db->sql_in_set('m.member_name', $this->bossattendees[$this->batchid][$boss]  ) ;

    	return $sql; 
    }
    
    
	

} // end class

?>
