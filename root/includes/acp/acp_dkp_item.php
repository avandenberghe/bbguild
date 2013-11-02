<?php
/**
 * Loot ACP file
 * 
 * @package \bbDKP\acp\dkp_item
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
if (!class_exists('\bbdkp\RaidController'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/RaidController.$phpEx");
}
//include the guilds class
if (!class_exists('\bbdkp\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
}
/**
 * This ACP class manages Game Loot
 * 
 * @package \bbDKP\acp\dkp_item
 */
class acp_dkp_item extends \bbdkp\Admin
{
	/**
	 * url in triggers
	 * @var string
	 */
	private $link;
	
	/**
	 * instance of lootcontroller class
	 * @var \bbdkp\Lootcontroller
	 */
	private $LootController;
	
	/**
	 * instance of RaidController class
	 * @var  \bbdkp\RaidController
	 */
	private $RaidController; 
	
	/**
	 * Main ACP function
	 * @param integer $id
	 * @param string $mode
	 */
	public function main($id, $mode) 
	{
		global $db, $user, $template;
		global $phpbb_admin_path, $phpEx;
		
		$this->link = '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&mode=listitems" ) . '"><h3>'. $user->lang['RETURN_DKPINDEX'] .'</h3></a>';

		$this->LootController = new \bbdkp\Lootcontroller();
		$this->RaidController = new \bbdkp\RaidController();
		$this->tpl_name = 'dkp/acp_' . $mode;
		
		switch ($mode) 
		{
			case 'additem' :
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
					$raid_id = request_var('hidden_raid_id', 0);
					$item_buyers = request_var('item_buyers', array(0 => 0));
					$item_value = request_var( 'item_value' , 0.0) ; 
					$item_name = utf8_normalize_nfc(request_var('item_name','', true));
					
					$this->LootController->addloot($raid_id, $item_buyers, $item_value, $item_name); 
					
					$success_message = sprintf ( $user->lang ['ADMIN_ADD_ITEM_SUCCESS'], $item_name, count($item_buyers), $item_value );
					$this->link = append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "=" .$raid_id );
					meta_refresh(1, $this->link);
					trigger_error ( $success_message . '<br /><a href="' . $this->link . '"><h3>'.$user->lang['RETURN_RAID'].'</h3></a> ' , E_USER_NOTICE );
					
				}
				if ($update) 
				{
					// get data
					$item_id = request_var('hidden_item_id', 0);
					$dkp_id = request_var('hidden_dkp_id', 0);
					$raid_id = request_var('hidden_raid_id', 0);
					$item_name = utf8_normalize_nfc(request_var('item_name','', true));
					$itemgameid  = request_var( 'item_gameid' , '') ;
					$itemvalue 	 = request_var( 'item_value' , 0.0) ;
					$itemdecay  = request_var( 'item_decay' , 0.00) ;
					$item_buyers = request_var('item_buyers', array(0 => 0));
					$itemdate= request_var('hidden_raiddate', 0);
					
					$this->LootController->updateloot($item_id, $dkp_id, $raid_id, $item_buyers, $item_value, $item_name, $itemdate, $itemgameid   ); 
				
					$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_ITEM_SUCCESS'], $item_name,
							(is_array($item_buyers) ? implode ( ', ',$item_buyers) : trim($item_buyers)  ) , $itemvalue );
						
					trigger_error ( $success_message . $this->link, E_USER_NOTICE );
					
				}
				if ($delete) 
				{
			
					if (confirm_box ( true )) 
					{
						//retrieve info
						$old_items = request_var('hidden_old_item', array(0 => array(''=>'')));
			
						foreach($old_items as $item_id => $old_item)
						{
							$this->LootController->deleteloot($item_id);
						}
						
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
						
						$lootinfo = $this->LootController->getitemdeleteinfo($item_id, $dkp_id); 
						
						$s_hidden_fields = build_hidden_fields ( array (
							'deleteitem' 	  => true, 
							'hidden_old_item' => $lootinfo[1]
						));
			
						$template->assign_vars ( array (
							'S_HIDDEN_FIELDS' => $s_hidden_fields));
						
						confirm_box ( false, sprintf($user->lang ['CONFIRM_DELETE_ITEM'], $lootinfo[2] , $lootinfo[0] ), $s_hidden_fields );
					}
				}
				
				$this->displayloot();
				$this->page_title = 'ACP_ADDITEM';
				
				break;
			
			case 'listitems' :
				if(count($this->games) == 0)
				{
					trigger_error($user->lang['ERROR_NOGAMES'], E_USER_WARNING);
				}
				$this->listitems();
				$this->page_title = 'ACP_LISTITEMS';
				
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
		'F_ADD_ITEM' 		=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=additem&amp;" . URI_RAID . '=' . $raid_id ), 
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
		
		// guild dropdown
		$submit = isset ( $_POST ['member_guild_id'] )  ? true : false;
		$Guild = new \bbdkp\Guilds();
		$guildlist = $Guild->guildlist();
		
		if($submit)
		{
			$Guild->guildid = request_var('member_guild_id', 0);
		}
		else
		{
			foreach ($guildlist as $g)
			{
				$Guild->guildid = $g['id'];
				$Guild->name = $g['name'];
				if ($Guild->guildid == 0 && $Guild->name == 'Guildless' )
				{
					trigger_error('ERROR_NOGUILD', E_USER_WARNING );
				}
				break;
			}
		}
		
		foreach ($guildlist as $g)
		{
			$template->assign_block_vars('guild_row', array(
					'VALUE' => $g['id'] ,
					'SELECTED' => ($g['id'] == $Guild->guildid) ? ' selected="selected"' : '' ,
					'OPTION' => (! empty($g['name'])) ? $g['name'] : '(None)'));
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
        
        // select all dkp pools that have raids 		
		$sql_array = array(
	    'SELECT'    => 'd.dkpsys_id, d.dkpsys_name, d.dkpsys_default',
	    'FROM'      => array(
	        DKPSYS_TABLE => 'd',
	        EVENTS_TABLE => 'e',
	        RAIDS_TABLE => 'r',
	    ),
	    'WHERE'     =>  'd.dkpsys_id = e.event_dkpid 
	    				and e.event_id = r.event_id ' , 
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
		
		// user clicks on master raid list
		$raid_id  = request_var('raid_id', 0);
		
		$lootcount  = $this->LootController->Countloot(0, 0,$Guild->guildid ); 
	
		if ($lootcount  > 0)
		{
			
			$total_raids = (int) $this->RaidController->guildraidcount($dkpsys_id,  $Guild->guildid); 
			
			$start = request_var ('start', 0, false );
			$sort_order = array (
				0 => array ('raid_id desc', 'raid_id' ),
				1 => array ('event_name desc', 'event_name' ),
			);
			$current_order = $this->switch_order ( $sort_order );

			// populate raid master grid
			$this->RaidController->listraids($dkpsys_id, $start, 0, $Guild->guildid);
			foreach ($this->RaidController->raidlist as $id => $raid)
			{
				$template->assign_block_vars ( 'raids_row', array (
					'EVENTCOLOR'    => (! empty ( $raid ['event_color'] )) ? $raid ['event_color']  : '',
					'U_VIEW_RAID' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_raid&amp;mode=editraid&amp;". URI_RAID . "={$id}" ), 
					'ID' 	=> $id,
					'DATE' 	=> $user->format_date($raid['raid_start']), 
					'RAIDNAME' => $raid['event_name'],
					'RAIDNOTE' => $raid['raid_note'],
					'ONCLICK' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=listitems&amp;" . URI_DKPSYS . "={$dkpsys_id}&amp;" . URI_RAID . "={$id}&amp;start=" .$start ),
				));

				if($raid_id == $id)
				{
					$raid_name =  $raid['event_name'];
					$raid_date =  $user->format_date($raid['raid_start']);
				}
			}

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
			
			$items_result = $this->LootController->listRaidLoot($dkpsys_id, $raid_id, $current_order['sql']);
			 
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
            	'CLASS_IMAGE' 	=> (strlen($item['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $item['imagename'] . ".png" : '',  
				'S_CLASS_IMAGE_EXISTS' => (strlen($item['imagename']) > 1) ? true : false, 				
				'DATE' 			=> (! empty ( $item ['item_date'] )) ? $user->format_date($item['item_date'], $config['bbdkp_date_format']) : '&nbsp;', 
				'BUYER' 		=> (! empty ( $item ['member_name'] )) ? $item ['member_name'] : '&lt;<i>Not Found</i>&gt;', 
				'ITEMNAME'      => $item_name, 
				'RAID' 			=> (! empty ( $item ['event_name'] )) ?  $item ['event_name']  : '&lt;<i>Not Found</i>&gt;', 
				'U_VIEW_BUYER' 	=> (! empty ( $item ['member_name'] )) ? append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp&amp;member_id={$item['member_id']}&amp;" . URI_DKPSYS . "={$item['event_dkpid']}") : '' ,
				'U_VIEW_ITEM' 	=> append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_item&amp;mode=additem&amp;" . URI_ITEM . "={$item['item_id']}&amp;" . URI_RAID . "={$raid_id}" ),
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
	

} // end class

?>