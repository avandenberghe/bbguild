<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
// don't add this file to namespace bbdkp
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
if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}
if (!class_exists('\bbdkp\LootController'))
{
	require("{$phpbb_root_path}includes/bbdkp/Loot/Lootcontroller.$phpEx");
}

/**
 * This ACP class manages Game Loot
 * 
 * @package bbDKP
 */
class acp_dkp_item extends \bbdkp\Admin
{
	public $u_action;
	private $link;
	private $LootController;
	
	public function main($id, $mode) 
	{
		global $db, $user, $template;
		global $phpbb_admin_path, $phpEx;
		
		$this->link = '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&mode=listitems" ) . '"><h3>'. $user->lang['RETURN_DKPINDEX'] .'</h3></a>';

		$this->LootController = new \bbdkp\Lootcontroller();
		
		switch ($mode) 
		{
			case 'edititem' :
				// $_POST treatment
				$submit = (isset ( $_POST ['add'] )) ? true : false;
				$update = (isset ( $_POST ['update'] )) ? true : false;
				$delete = (isset ( $_GET ['deleteitem'] ) || isset($_POST['deleteitem']) ) ? true : false;
				
		        if ( $submit || $update )
                {
                   	if (!check_form_key('additem'))
					{
						trigger_error('FORM_INVALID');
					}
       			}

       			if ($submit) 
				{
					$this->additem(); 
				}
				if ($update) 
				{
					$this->updateitem();  
				}
				if ($delete) 
				{
					// from item acp
					$this->deleteitem(true);
				}
				
				$this->displayloot();
				$this->page_title = 'ACP_ADDITEM';
				$this->tpl_name = 'dkp/acp_additem';
				
				break;
			
			case 'listitems' :
				$this->listitems();
				$this->page_title = 'ACP_LISTITEMS';
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
	private function displayloot()
	{
		global $db, $user, $config, $template, $phpEx, $phpbb_admin_path;
		
		// fetch params from $_GET
		$raid_id = request_var(URI_RAID, 0);
		$item_id = request_var(URI_ITEM, 0);

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
			$raiddateformat = $user->format_date($row['raid_start']);
			$raidstart = $row['raid_start']; 
			$template->assign_block_vars ( 'raids_row', array (
				'VALUE' 	=> $row['raid_id'], 
				'SELECTED' 	=> ($raid_id == $row['raid_id']) ? ' selected="selected"' : '', 
				'OPTION' 	=> date ( $config['bbdkp_date_format'], $row['raid_start'] ) . ' - ' .  $row['event_name'] . ' ' . $row['raid_note'])  );
		}
		$db->sql_freeresult ( $result );

		// fetch item info
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
				'select_item_id' 	=> utf8_normalize_nfc(request_var('select_item_id', ''),true) ,  
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
		
		// process sql for left select selection pane
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
		
		$form_key = 'additem';
		add_form_key($form_key);
					
		//constant template variables
		$template->assign_vars ( array (
		'U_BACK'			=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id ),
		'L_TITLE' 			=> $user->lang ['ACP_ADDITEM'], 
		'L_EXPLAIN' 		=> $user->lang ['ACP_ADDITEM_EXPLAIN'],
		'F_ADD_ITEM' 		=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=edititem&amp;" . URI_RAID . '=' . $raid_id ), 
		'ITEM_VALUE' 		=> isset($this->item['item_value']) ? $this->item['item_value'] : 0.00,
		'S_SHOWZS' 			=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
		'S_SHOWDECAY' 		=> ($config['bbdkp_decay'] == '1') ? true : false,
		
		// Language
		'MSG_NAME_EMPTY' 	=> $user->lang ['FV_REQUIRED_ITEM_NAME'],
		'MSG_VALUE_EMPTY' 	=> $user->lang ['FV_REQUIRED_VALUE'], 

		'LA_ALERT_AJAX'		  => $user->lang['ALERT_AJAX'],
		'LA_ALERT_OLDBROWSER' => $user->lang['ALERT_OLDBROWSER'],
		'LA_MSG_NAME_EMPTY'	  => $user->lang['FV_REQUIRED_NAME'],
		'UA_FINDITEMS'		  => append_sid($phpbb_admin_path . "style/dkp/finditem.$phpEx"),
		'ADMPATH' 			  => $phpbb_admin_path
		)
		
		);

		if($item_id)
		{
			$template->assign_vars ( array (
				'ITEMTITLE'		=> sprintf($user->lang['LOOTUPD'], $raidtitle, $raiddateformat  ) , 
				'RAID_DATE'		=> $raidstart, 
				'ITEM_ZS'		=> ($this->item['item_zs'] == 1) ? ' checked="checked"' : '',  
				'ITEM_DECAY'	=> $this->item['item_decay'],
				'ITEM_NAME' 	=> isset($this->item['item_name']) ? $this->item['item_name'] : '' , 
				'ITEM_GAMEID' 	=> isset($this->item['item_gameid']) ? $this->item['item_gameid'] : '' ,
				'S_ADD' 		=> false, 
				'ITEM_ID' 		=> $item_id, 
				'ITEM_DKPID'	=> $dkpid,
				'ITEM_RAIDID'	=> $raid_id,
				
			));
		}
		else 
		{
			$template->assign_vars ( array (
				'ITEMTITLE'		=> sprintf($user->lang['LOOTADD'], $raidtitle, $raiddate  ) , 
				'S_ADD' 		=> true, 
				'ITEM_RAIDID'	=> $raid_id,
				'ITEM_DKPID'	=> $dkpid,
			));			
		}
		
		
	}
	
	/**
	 * adding new items to a raid
	 * adds an item to the database
	 * increases buyer account spent
	 * 
	 * called from : acp item adding
	 * 
	 */
	private function additem()
	{
		global $user, $config, $phpEx, $phpbb_admin_path;

		$raid_id = request_var('hidden_raid_id', 0);
		$item_buyers = request_var('item_buyers', array(0 => 0));
		$itemvalue 	 = request_var( 'item_value' , 0.0) ; 
		$item_name = utf8_normalize_nfc(request_var('item_name','', true));
		$item_name_db = utf8_normalize_nfc(request_var('item_name_db','', true));
		$item_name = (strlen($item_name) > 0) ? $item_name : $item_name_db;
		$itemgameid  = request_var( 'item_gameid' , ''); 

		$this->LootController->addloot($raid_id, $item_buyers, $item_value, $item_name, $itemgameid); 
		
		//
		// Success message
		//
		$success_message = sprintf ( $user->lang ['ADMIN_ADD_ITEM_SUCCESS'], $item_name, implode ( ', ', $item_buyers  ), $itemvalue );
		$this->link = append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id );
		meta_refresh(1, $this->link);
		trigger_error ( $success_message . '<br /><a href="' . $this->link . '"><h3>'.$user->lang['RETURN_RAID'].'</h3></a> ' , E_USER_NOTICE );
		
		
	}

	
	
	/**
	 * Deletes item 
	 * @groupdelete : if true then all groupid items also deleted
	 *  
	 */	
	private function deleteitem($groupdelete = false)
	{
		global $db, $user, $config, $template;

		if (confirm_box ( true )) 
		{
			//retrieve info
			$old_items = request_var('hidden_old_item', array(0 => array(''=>'')));
			
			// delete items and decrease buyer spend
			$db->sql_transaction('begin');
		
			foreach($old_items as $old_item)
			{
				$this->deleteitem_db($old_item);
			}
			
			// commit on mysql only works with innodb, on myisam this is ignored due to lack of atomicity
			// oracle/postgre/mssql just work
			$db->sql_transaction('commit');
			
			// log action
			$log_action = array (
				'header' 	=> 'L_ACTION_ITEM_DELETED',
				'L_NAME' 	=> $old_item ['item_name'], 
				'L_BUYER' 	=> $old_item ['member_name'],
				'L_RAID_ID' => $old_item ['raid_id'], 
				'L_VALUE' 	=> $old_item ['item_value'] );
			
			$this->log_insert ( array (
				'log_type' 		=> $log_action ['header'], 
				'log_action' 	=> $log_action ) );
			
			$success_message = sprintf ( $user->lang ['ADMIN_DELETE_ITEM_SUCCESS'], 
			$old_item ['item_name'], $old_item ['member_name'], $old_item ['item_value'] );
			
			trigger_error ( $success_message . $this->link, E_USER_NOTICE );
		
		}
		else
		{
			
			$item_id = request_var('hidden_item_id', 0);
			$dkp_id = request_var('hidden_dkp_id', 0);
			
			if($item_id == 0)
			{	
				trigger_error ( $user->lang ['ERROR_INVALID_ITEM_PROVIDED'] , E_USER_WARNING);
			}
			
			if ($groupdelete)
			{
				// many buyers
				$sql_array = array(
					'SELECT' 	=> 'i2.* , m.member_name ',
					'FROM' 		=> array(
						RAID_ITEMS_TABLE => 'i2', 
						MEMBER_LIST_TABLE => 'm'
						),
					'LEFT_JOIN' => array(
						array(
						'FROM' => array(RAID_ITEMS_TABLE => 'i1'),
						'ON' => ' i1.item_group_key = i2.item_group_key '
						)),
					'WHERE' => 'i2.member_id = m.member_id and i1.item_id= '. $item_id,
					);
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query ( $sql );
			
			}
			else 
			{
				// one buyer
				$sql = 'SELECT i.* , m.member_name FROM ' . RAID_ITEMS_TABLE . ' i, ' . MEMBER_LIST_TABLE . ' m WHERE i.member_id = m.member_id 
				and i.item_id= ' . (int) $item_id;
				$result = $db->sql_query ( $sql );
				
			}
			
			$item_buyers = '[';
			while ( $row = $db->sql_fetchrow ( $result ) ) 
			{
				$old_item[$row['item_id']] = array (
				'item_id' 		=>  (int) 	 $row['item_id'] , 
				'dkpid'			=>  $dkp_id,
				'item_name' 	=>  (string) $row['item_name'] , 
				'member_id' 	=>  (int) 	 $row['member_id'] , 
				'member_name' 	=>  (string) $row['member_name'] ,
				'raid_id' 		=>  (int) 	 $row['raid_id'], 
				'item_date' 	=>  (int) 	 $row['item_date'] , 
				'item_value' 	=>  (float)  $row['item_value'], 
				'item_decay' 	=>  (float)  $row['item_decay'],
				'item_zs' 		=>  (bool)   $row['item_zs'],  
				);
				
				//for confirm question
				$item_buyers = $item_buyers . ' ' . $row['member_name'] . ' '; 
				$item_name = $row['member_name'];
			}
			$db->sql_freeresult ($result);
			$item_buyers .= ']';
			
			$s_hidden_fields = build_hidden_fields ( array (
				'deleteitem' 	  => true, 
				'hidden_old_item' => $old_item
			));

			$template->assign_vars ( array (
				'S_HIDDEN_FIELDS' => $s_hidden_fields ) );
			confirm_box ( false, sprintf($user->lang ['CONFIRM_DELETE_ITEM'], $item_name, $item_buyers  ), $s_hidden_fields );
		}
				
	}
	
	


	/***
	 * updating item buyer 
	 * 
	 */
	private function updateitem()  
	{
		global $db, $user;
		$errors_exist = $this->error_check ();
		if ($errors_exist) 
		{
			$this->fv->displayerror($this->fv->errors);
		}
		
		// get data
		$item_id = request_var('hidden_item_id', 0);
		$dkp_id = request_var('hidden_dkp_id', 0);
		
		$old_buyers = array ();
		$sql_array = array(
			'SELECT' 	=> 'i2.* , m.member_name ',
			'FROM' 		=> array(
				RAID_ITEMS_TABLE => 'i2', 
				MEMBER_LIST_TABLE => 'm'
				),
			'LEFT_JOIN' => array(
				array(
				'FROM' => array(RAID_ITEMS_TABLE => 'i1'),
				'ON' => ' i1.item_group_key = i2.item_group_key '
				)),
			'WHERE' => 'i2.member_id = m.member_id and i1.item_id= '. $item_id,
			);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		
		while ( $row = $db->sql_fetchrow ($result)) 
		{
			$old_item = array (
			'item_id' 		=>  (int) 	 $row['item_id'] , 
			'dkpid'			=>  $dkp_id,
			'item_name' 	=>  (string) $row['item_name'] , 
			'member_id' 	=>  (int) 	 $row['member_id'] , 
			'member_name' 	=>  (string) $row['member_name'] ,
			'raid_id' 		=>  (int) 	 $row['raid_id'], 
			'item_date' 	=>  (int) 	 $row['item_date'] , 
			'item_value' 	=>  (float)  $row['item_value'], 
			'item_decay' 	=>  (float)  $row['item_decay'],
			'item_zs' 		=>  (bool)   $row['item_zs'],  
			);
			
			$this->deleteitem_db($old_item);
		}
		$db->sql_freeresult ( $result );
		
		$raid_id = request_var('hidden_raid_id', 0);
		
		$item_name = utf8_normalize_nfc(request_var('item_name','', true));
		$item_name_db = utf8_normalize_nfc(request_var('item_name_db','', true));
		$item_name = (strlen($item_name) > 0) ? $item_name : $item_name_db;

		$itemgameid  = request_var( 'item_gameid' , '') ;

		$itemvalue 	 = request_var( 'item_value' , 0.0) ; 
		
		// if user wants to manually edit item decay but this is uncommon...
		$itemdecay  = request_var( 'item_decay' , 0.00) ;
		
		$item_buyers = request_var('item_buyers', array(0 => 0));
		
		$itemdate= request_var('hidden_raiddate', 0);
		
		//generate new hash
		$group_key = $this->gen_group_key ( $item_name, $itemdate, $item_id + rand(10,100) );

		//
		// Add new item to newly selected members
		$this->add_new_item_db($item_name, $item_buyers, $group_key, $itemvalue, $raid_id, $itemdate, $itemgameid, $itemdecay); 
		
		//
		// Logging
		// 
		$log_action = array (
		'header' => 		'L_ACTION_ITEM_UPDATED',
		'L_NAME_BEFORE' 	=> $old_item ['item_name'],
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
		$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_ITEM_SUCCESS'], $item_name, 
			(is_array($item_buyers) ? implode ( ', ',$item_buyers) : trim($item_buyers)  ) , $itemvalue );
			
		trigger_error ( $success_message . $this->link, E_USER_NOTICE );
	}	
	
	
	/**
	 * list items for a pool. master-detail form
	 *
	 */
	private function listitems()
	{
		global $db, $user, $config, $template, $phpEx,$phpbb_admin_path, $phpbb_root_path;
				
		if( count((array) $this->LootController->dkpsys) == 0 )
		{
			trigger_error('ERROR_NOPOOLS', E_USER_WARNING );
		}
		
		// add member button redirect
		if ($this->bbtips == true)
		{
			if ( !class_exists('bbtips')) 
			{
				require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
			}
			$bbtips = new bbtips;
		}
		
		/***  DKPSYS drop-down query ***/
        $dkpsys_id = 0;
        
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
	    'GROUP_BY'  => 'd.dkpsys_id, d.dkpsys_name, d.dkpsys_default', 
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql );
		
		$submit = (isset ( $_POST ['dkpsys_id'] )) ? true : false;
		if ($submit)
		{
			$dkpsys_id = request_var ( 'dkpsys_id', 0 );
		} 
		else 
		{ 
			
			while ( $row = $db->sql_fetchrow ($result)) 
			{
				if($row['dkpsys_default'] == "Y"  )
				{
					$dkpsys_id = $row['dkpsys_id'];
				}
			}
			
			if ($dkpsys_id == 0)
			{
				$result = $db->sql_query_limit ( $sql, 1 );
				while ( $row = $db->sql_fetchrow ( $result ) ) 
				{
					$dkpsys_id = $row['dkpsys_id'];
				}
			}
		}
		$poolhasitems = false;
		
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) ) 
		{
			$template->assign_block_vars ( 'dkpsys_row', 
				array (
				'VALUE' => $row['dkpsys_id'], 
				'SELECTED' => ($row['dkpsys_id'] == $dkpsys_id) ? ' selected="selected"' : '', 
				'OPTION' => (! empty ( $row['dkpsys_name'] )) ? $row['dkpsys_name'] : '(None)' ) );
			$poolhasitems = true;
		}
		$db->sql_freeresult( $result );
		/***  end drop-down query ***/
		
		if($poolhasitems==true)
		{
			//get raidcount with items
			$sql_array = array (
			'SELECT' => ' count(*) as raidcount', 
			    'FROM'    	=> array(DKPSYS_TABLE => 'd', 
									 EVENTS_TABLE => 'e',
	        						 RAIDS_TABLE => 'r'
									 ),
			    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    						and e.event_id = r.event_id 
			    				and d.dkpsys_id = ' . $dkpsys_id ,
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
			$result = $db->sql_query($sql);
			$total_raids = (int) $db->sql_fetchfield('raidcount');
			$db->sql_freeresult ($result);
			//$total_raids == 7
			
			$start = request_var ('start', 0, false );
			$sort_order = array (
				0 => array ('raid_id desc', 'raid_id' ),
				1 => array ('event_name desc', 'event_name' ),
			);
			$current_order = $this->switch_order ( $sort_order );
			
			// select all raids for pool			
			$sql_array = array(
			    'SELECT'    => 'r.raid_id, e.event_name, e.event_color, e.event_imagename, e.event_dkpid, 
			    				r.raid_start, raid_note  ',
			    'FROM'    	=> array(DKPSYS_TABLE => 'd', 
									 EVENTS_TABLE => 'e',
	        						 RAIDS_TABLE => 'r'
									 ),
			    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    						and e.event_id = r.event_id 
			    				and d.dkpsys_id = ' . $dkpsys_id ,
				'ORDER_BY'  =>  $current_order ['sql']
			);
			$sql = $db->sql_build_query('SELECT', $sql_array);
							
			$submitraid = (isset ( $_POST ['raid_id']) || isset ( $_GET ['raid_id']) ) ? true : false;
			$raid_id = 0;
			if ($submitraid)
			{
				$raid_id  = request_var('raid_id', 0);  
			}
			else
			{
				//get 1st raid from this window
				$result = $db->sql_query_limit ( $sql, 1, $start );
				while ($row = $db->sql_fetchrow ($result)) 
				{
					$raid_id = (int) $row['raid_id'];
				}
				$db->sql_freeresult ($result);
			}
			
			// populate raid master grid
			$result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_rlimit'], $start );
			while ( $row = $db->sql_fetchrow ( $result ) )
			{
				$template->assign_block_vars ( 'raids_row', array (
					'EVENTCOLOR'    => (! empty ( $row ['event_color'] )) ? $row ['event_color']  : '',
					'U_VIEW_RAID' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "={$row['raid_id']}" ), 
					'ID' 	=> $row['raid_id'],
					'DATE' 	=> $user->format_date($row['raid_start']), 
					'RAIDNAME' => $row['event_name'],
					'RAIDNOTE' => $row['raid_note'],
					'ONCLICK' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;" . URI_DKPSYS . "={$dkpsys_id}&amp;" . URI_RAID . "={$row['raid_id']}&amp;start=" .$start ),
				));

				if($raid_id == $row['raid_id'])
				{
					$raid_name =  $row['event_name'];
					$raid_date =  $user->format_date($row['raid_start']);
				}
			}
			$db->sql_freeresult ( $result );
			
			$raidpgination = generate_pagination (append_sid ("{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;" . URI_DKPSYS . "=". $dkpsys_id ."&amp;o=" . $current_order ['uri'] ['current']) ,
			$total_raids, $config ['bbdkp_user_rlimit'], $start, true );
			
			// detail grid for items
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
			$current_order = $this->switch_order ($sort_order);
			
			//prepare item list sql
			$sql_array = array(
		    'SELECT'    => 'd.dkpsys_name, i.item_id, i.item_name, i.item_gameid, e.event_dkpid, 
		    				i.member_id, l.member_name, c.colorcode, c.imagename, i.item_date, i.raid_id, i.item_value, e.event_name ',
		    'FROM'      => array(
		        DKPSYS_TABLE   => 'd', 
				EVENTS_TABLE   => 'e',
		        RAIDS_TABLE    => 'r',
		        MEMBER_LIST_TABLE => 'l', 
		        CLASS_TABLE 		=> 'c', 
		        RAID_ITEMS_TABLE    => 'i',
		    ),
		    'WHERE'     =>  'c.class_id = l.member_class_id   
		    				and d.dkpsys_id = e.event_dkpid 
	    					and e.event_id = r.event_id 
	    					and i.member_id = l.member_id 
	    					AND l.game_id = c.game_id
	    					and r.raid_id = i.raid_id 
		     				and d.dkpsys_id = ' . $dkpsys_id . ' 
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
					if ($item['item_gameid'] == 'wow' )
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
				'COLORCODE'  	=> ($item['colorcode'] == '') ? '#123456' : $item['colorcode'],
            	'CLASS_IMAGE' 	=> (strlen($item['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $item['imagename'] . ".png" : '',  
				'S_CLASS_IMAGE_EXISTS' => (strlen($item['imagename']) > 1) ? true : false, 				
				'DATE' 			=> (! empty ( $item ['item_date'] )) ? $user->format_date($item['item_date'], $config['bbdkp_date_format']) : '&nbsp;', 
				'BUYER' 		=> (! empty ( $item ['member_name'] )) ? $item ['member_name'] : '&lt;<i>Not Found</i>&gt;', 
				'ITEMNAME'      => $item_name, 
				'RAID' 			=> (! empty ( $item ['event_name'] )) ?  $item ['event_name']  : '&lt;<i>Not Found</i>&gt;', 
				'U_VIEW_BUYER' 	=> (! empty ( $item ['member_name'] )) ? append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;member_id={$item['member_id']}&amp;" . URI_DKPSYS . "={$item['event_dkpid']}") : '' ,
				'U_VIEW_ITEM' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=edititem&amp;" . URI_ITEM . "={$item['item_id']}&amp;" . URI_RAID . "={$raid_id}" ),
				'VALUE' 		=> $item ['item_value'],
				
				));
			}
			
			$db->sql_freeresult ( $items_result );
			
			$template->assign_vars ( array (
				'ICON_VIEWLOOT'	=> '<img src="' . $phpbb_admin_path . 'images/glyphs/view.gif" alt="' . $user->lang['ITEMS'] . '" title="' . $user->lang['ITEMS'] . '" />',
				'S_SHOW' 		=> true,
				'F_LIST_ITEM' 	=>   append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems" ), 
				'L_TITLE' 		=> $user->lang ['ACP_LISTITEMS'], 
				'L_EXPLAIN' 	=> $user->lang ['ACP_LISTITEMS_EXPLAIN'], 
				'O_DATE' 		=> $current_order ['uri'][0], 
				'O_BUYER' 		=> $current_order ['uri'][1], 
				'O_NAME' 		=> $current_order ['uri'][2], 
				'O_RAID' 		=> $current_order ['uri'][3], 
				'O_VALUE' 		=> $current_order ['uri'][4], 
				'U_LIST_ITEMS' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;start={$start}&amp;" . URI_RAID . '=' . $raid_id), 
				'S_BBTIPS' 		=> $this->bbtips, 
				'START' 		=> $start, 
				'LISTITEMS_FOOTCOUNT' => $listitems_footcount, 
				'RAID_PAGINATION'	=> $raidpgination
			));
		}
		else 
		{
			$template->assign_vars ( array (
				'F_LIST_ITEM' 	=>  append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems" ), 
				'L_TITLE' 		=> $user->lang ['ACP_LISTITEMS'], 
				'L_EXPLAIN' 	=> $user->lang ['ACP_LISTITEMS_EXPLAIN'], 
				'S_SHOW' 		=> false,
				'S_BBTIPS' 		=> $this->bbtips, 
			));			
		}
		

	}
	
	
	/*
	 * checks items for errors
	 */
	private function error_check() 
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
		
		elseif ( isset($_POST ['select_item_id'])) 
		{
			$this->item ['item_name'] = utf8_normalize_nfc(request_var('select_item_id', ' ', true));
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
     * called from raidtracker_loot_add
     * 
     * @param $raiders : list of raiders
     * @param 
     * @param $itemvalue : all itemvalue 
     * 
     * returns the sql to update
     * 
     */
    public function zero_balance($itemvalue)
    {
    	global $db;
    	
    	$zerosumdkp = round( $itemvalue / count($this->bossattendees[$this->batchid][$boss] , 2)); 
		/* note: other dmbs not tested/suppported*/    	
		switch ($db->sql_layer)
		{
			case 'mysqli':
			case 'mysql4':
			case 'mysql':
				    $sql = ' UPDATE ' . MEMBER_DKP_TABLE . ' d, ' . MEMBER_LIST_TABLE  . ' m 
					SET d.member_earned = d.member_earned + ' . (float) $zerosumdkp  .  ' 
					WHERE d.member_dkpid = ' . (int) $this->dkp  . ' 
		  	 		AND  d.member_id =  m.member_id 
		  	 		AND ' . $db->sql_in_set('m.member_name', $this->bossattendees[$this->batchid][$boss]  ) ;
			break;
			
			case 'oracle': 
				$sql= 'UPDATE (
				  SELECT d.member_earned
				  FROM ' . MEMBER_DKP_TABLE . ' d
			      INNER JOIN ' . MEMBER_LIST_TABLE  . ' m ON d.member_id =  m.member_id 
				   WHERE d.member_dkpid = ' . (int) $this->dkp  . ' 
				   AND ' . $db->sql_in_set('m.member_name', $this->bossattendees[$this->batchid][$boss]) . ') t
				SET t.member_earned = t.member_earned + ' . (float) $zerosumdkp  ;				
				
			case 'mssql':
			case 'mssql_odbc':
			case 'mssqlnative':	
					$sql= 'UPDATE d
					SET d.member_earned = d.member_earned + ' . (float) $zerosumdkp  .  ' 
					FROM ' . MEMBER_DKP_TABLE . ' d
					   INNER JOIN ' . MEMBER_LIST_TABLE  . ' m 
					   ON d.member_id =  m.member_id 
					   WHERE d.member_dkpid = ' . (int) $this->dkp  . ' 
					   AND ' . $db->sql_in_set('m.member_name', $this->bossattendees[$this->batchid][$boss]);
			break;
		}
    	return $sql; 
    }
    
	


} // end class

?>