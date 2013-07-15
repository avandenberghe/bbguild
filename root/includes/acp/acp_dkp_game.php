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
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
if (! defined ( 'EMED_BBDKP' ))
{
	$user->add_lang ( array ('mods/dkp_admin' ) );
	trigger_error ( $user->lang ['BBDKPDISABLED'], E_USER_WARNING );
}
if (!class_exists('Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/Admin.$phpEx");
}

if (!class_exists('\bbdkp\Faction'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/factions/Faction.$phpEx");
}
if (!class_exists('\bbdkp\Classes'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/classes/Classes.$phpEx");
}
if (!class_exists('\bbdkp\Races'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/races/Races.$phpEx");
}


/**
 * 
 * This class manages Game settings
 * 
 * @package bbDKP
 */
class acp_dkp_game extends \bbdkp\Admin
{

	public $u_action;
	
	private $link;
	/** 

	 * main ACP game function
	 * @param int $id the id of the node who parent has to be returned by function 
	 * @param int $mode id of the submenu
	 * @access public 

	 */
	function main($id, $mode)
	{
		global $db, $user, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang (array ('mods/dkp_admin'));
		$user->add_lang (array ('mods/dkp_common'));
		
		$form_key = 'acp_dkp_game';
		add_form_key ( $form_key );
		
		switch ($mode)
		{
			case 'listgames' :
				
				$this->link = '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames" ) . '"><h3>' . 
								$user->lang ['RETURN_DKPINDEX'] . '</h3></a>';
				//game dropdown
				$installed_games = array ();
				$listgames = new \bbdkp\Game;
				
				//pressed button ? redirect
				$newpresetgame = (isset ( $_POST ['addgame1'] )) ? true : false;
				$newcustomgame = (isset ( $_POST ['addgame2'] )) ? true : false;
				if ($newpresetgame || $newcustomgame)
				{
					// ask for permission
					if (confirm_box ( true ))
					{
						$editgame = new \bbdkp\Game;
						$editgame->game_id = request_var ( 'hidden_game_id','' );
						$editgame->install();
						trigger_error ( sprintf ( $user->lang ['ADMIN_RESET_GAME_SUCCESS'], $editgame->name ) . $this->link, E_USER_NOTICE );
					}
					else
					{
						// get field content
						$listgames->game_id = request_var('ngame_id' , ''); 
						if($newpresetgame)
						{
							$listgames->name =  $listgames->preinstalled_games[$listgames->game_id] ;
						}
						elseif($newcustomgame)
						{
							$listgames->name =  utf8_normalize_nfc(request_var('ngame_name', '', true)); 
						}
						
						$s_hidden_fields = build_hidden_fields ( array (
								'addgame1' => $newpresetgame,
								'addgame2' => $newcustomgame,
								'hidden_game_id' => $listgames->game_id,
								'hidden_game_name' => $listgames->name,
								
						));
						confirm_box ( false, sprintf ( $user->lang ['CONFIRM_INSTALL_GAME'], $listgames->name ), $s_hidden_fields );
					}
				}
	
				///template load
				
				//populate dropdown with installable games
				$can_install_count = 0; 
				$not_installed = array_diff($listgames->preinstalled_games, $listgames->installed_games); 
				foreach ($not_installed  as $key => $game)
			    {
			    	//only uninstalled
					$can_install_count +=1; 
			        $template->assign_block_vars('gamelistrow', array(
			            'VALUE'      => $key,
			            'OPTION'     => $game,
			        	'SELECTED'   => '',
			        ));
			    }
			    
			    //list installed games
			    $sort_order = array(
			    		0 => array(	'id' , 'id desc') ,
			    		1 => array('game_id' , 'game_id desc') ,
			    		2 => array('game_name' , 'game_name desc')); 
			    $current_order = $this->switch_order($sort_order);
			    $sort_index = explode('.', $current_order['uri']['current']);
			    $previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
			     
			    $result = $listgames->listgames($current_order['sql']);
			    while ($row = $db->sql_fetchrow($result))
			    {
			    	$template->assign_block_vars('gamerow', array(
			    			'ID' => $row['id'] ,
			    			'NAME' => $row['game_name'] ,
			    			'GAME_ID' => $row['game_id'] ,
			    			'U_VIEW_GAME' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;" . URI_GAME . '=' . $row['game_id'] ), 
			    			'STATUS' => $row['status'],
			    	));
			    	$previous_data = $row[$previous_source];
			    		
			    }
			    $db->sql_freeresult($result);
			    
			    $template->assign_vars ( array (
			    		'U_LIST_GAME' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames") ,
			    		'CANINSTALL' => ($can_install_count == 0) ? false : true,
			    		'O_ID' => $current_order['uri'][0] ,
			    		'O_GAMEID' => $current_order['uri'][1] ,
			    		'O_GAMENAME' => $current_order['uri'][2] ,
					));
			    
			    $form_key = '30U05YJ4IfeHxY';
			    add_form_key($form_key);
			    
			    $this->page_title = 'ACP_LISTGAME';
			    $this->tpl_name = 'dkp/acp_' . $mode;

			    
			    
			    break;
			    
			case 'editgames' :
				
				$editgame = new \bbdkp\Game;
				$editgame->game_id = request_var(URI_GAME, '');
				$editgame->Get(); 
				
				$gamereset = (isset ( $_POST ['gamereset'] )) ? true : false;
				$gamedelete = (isset ( $_POST ['gamedelete'] )) ? true : false;
				
				$addrace = (isset ( $_POST ['showraceadd'] )) ? true : false;
				$addclass = (isset ( $_POST ['showclassadd'] )) ? true : false;
				$addfaction = (isset ( $_POST ['showfactionadd'] )) ? true : false;
				
				$deletefaction = (isset ( $_GET ['factiondelete'] )) ? true : false;
				$racedelete = (isset ( $_GET ['racedelete'] )) ? true : false;
				$classdelete = (isset ( $_GET ['classdelete'] )) ? true : false;
				
				$raceedit = (isset ( $_GET ['raceedit'] )) ? true : false;
				$classedit = (isset ( $_GET ['classedit'] )) ? true : false;
				
				
				if($gamereset)
				{
					// ask for permission
					if (confirm_box ( true ))
					{
						$editgame = new \bbdkp\Game; 
						$editgame->game_id = request_var ( 'hidden_game_id','' );
						$editgame->get();
						$editgame->Delete();
						$editgame->install();
						trigger_error ( sprintf ( $user->lang ['ADMIN_RESET_GAME_SUCCESS'], $editgame->name ) . $this->link, E_USER_WARNING );
					}
					else
					{
						// get field content
						$s_hidden_fields = build_hidden_fields ( array (
								'gamereset' => true,
								'hidden_game_id' => $editgame->game_id,
						));
						confirm_box ( false, sprintf ( $user->lang ['CONFIRM_RESET_GAME'], $editgame->name ), $s_hidden_fields );
					}
				}
				
				if($gamedelete)
				{
					// ask for permission
					if (confirm_box ( true ))
					{
						$deletegame = new \bbdkp\Game;
						$deletegame->game_id = request_var ( 'hidden_game_id','' );
						$deletegame->name = request_var ( 'hidden_game_name','' );
						$deletegame->Delete();
						trigger_error ( sprintf ( $user->lang ['ADMIN_DELETE_GAME_SUCCESS'], $deletegame->name ) . $this->link, E_USER_WARNING );
					}
					else
					{
						// get field content
						$s_hidden_fields = build_hidden_fields ( array (
								'gamedelete' => true,
								'hidden_game_name' => $editgame->name,
								'hidden_game_id' => $editgame->game_id,
						));
						confirm_box ( false, sprintf ( $user->lang ['CONFIRM_DELETE_GAME'], $editgame->name ), $s_hidden_fields );
					}	
				}
				
				if ($addfaction)
				{
					redirect ( append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addfaction" ) );
					break;
				}
				
				// user pressed delete faction

				if ($deletefaction)
				{
					global $db, $cache, $user;
					$faction = new \bbdkp\Faction();
					$faction->game_id = $editgame->game_id;
					$faction->faction_id = request_var ( 'id', 0 );
					$faction->get();
					
					// ask for permission
					if (confirm_box ( true ))
					{
						$faction = new \bbdkp\Faction();
						$faction->game_id = request_var ( 'hidden_game_id','' );
						$faction->faction_id = request_var ( 'hidden_faction_id', 0 );
						$faction->get();
						$faction->Delete();
						trigger_error ( sprintf ( $user->lang ['ADMIN_DELETE_FACTION_SUCCESS'], $faction->faction_name ) . $this->link, E_USER_WARNING );
					}
					else
					{
						// get field content
						$s_hidden_fields = build_hidden_fields ( array (
								'factiondelete' => true, 
								'hidden_faction_id' => $faction->faction_id, 
								'hidden_game_id' => $faction->game_id, 
								));
						confirm_box ( false, sprintf ( $user->lang ['CONFIRM_DELETE_FACTION'], $faction->faction_name ), $s_hidden_fields );
					}
						
				}
				
				// user pressed race add / edit, load acp_addrace	

				if ($raceedit || $addrace)
				{
					
					// Game dropdown

					if (isset ( $_GET ['id'] ))
					{
						$listraces = new \bbdkp\Races();
												
						$listraces->race_id = request_var ( 'id', 0 );
						$listraces->game_id = $editgame->game_id;
						$result = $listraces->listraces();	
						
						while ( $row = $db->sql_fetchrow ( $result ) )
						{
							$factionid = $row['race_faction_id'];
							$race_name = $row['race_name'];
							$race_imagename_m =$row['image_male'];
							$race_imagename_f = $row['image_female'];
						}
						$db->sql_freeresult ( $result );
						
						// list installed games

						$installed_games = array ();
						foreach ( $this->games as $gid => $gamename )
						{
							//add value to dropdown when the game config value is 1

							if ($config ['bbdkp_games_' . $gid] == 1)
							{
								$template->assign_block_vars ( 'game_row', array (
									'VALUE' => $gid, 'SELECTED' => ($listraces->game_id == $gid) ? ' selected="selected"' : '', 'OPTION' => $gamename ) );
								$installed_games [] = $gid;
							}
						}
						
						// faction dropdown
						$sql_array = array (
							'SELECT' => ' f.faction_name, f.faction_id ', 
							'FROM' => array (FACTION_TABLE => 'f' ), 
							'WHERE' => " f.game_id = '" . $listraces->game_id . "'", 
							'ORDER_BY' => 'faction_id ASC ' );
						
						$sql = $db->sql_build_query ( 'SELECT', $sql_array );
						$result = $db->sql_query ( $sql );
						$s_faction_options = '';
						while ( $row = $db->sql_fetchrow ( $result ) )
						{
							$selected = ($row ['faction_id'] == $factionid) ? ' selected="selected"' : '';
							$s_faction_options .= '<option value="' . $row ['faction_id'] . '" ' . $selected . '> ' . $row ['faction_name'] . '</option>';
						}
						$db->sql_freeresult ( $result );
						
						// send parameters to template

						$template->assign_vars ( array (
							'GAME_ID' => $listraces->game_id, 
							'RACE_ID' => $listraces->race_id, 
							'RACE_NAME' => $race_name, 
							'S_FACTIONLIST_OPTIONS' => $s_faction_options, 
							'S_RACE_IMAGE_M_EXISTS' => (strlen ( $race_imagename_m ) > 1) ? true : false, 
							'RACE_IMAGENAME_M' => $race_imagename_m, 
							'RACE_IMAGE_M' => (strlen ( $race_imagename_m ) > 1) ? $phpbb_root_path . "images/race_images/" . $race_imagename_m . ".png" : '', 
							'S_RACE_IMAGE_F_EXISTS' => (strlen ( $race_imagename_f ) > 1) ? true : false, 
							'RACE_IMAGENAME_F' => $race_imagename_f, 
							'RACE_IMAGE_F' => (strlen ( $race_imagename_f ) > 1) ? $phpbb_root_path . "images/race_images/" . $race_imagename_f . ".png" : '', 
							'S_ADD' => FALSE, 
							'U_ACTION' => append_sid ( "{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addrace' ), 
							'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'] ) );
					}
					else
					{
						
						// build add form
						$installed_games = array ();
						foreach ( $this->games as $gameid => $gamename )
						{
							//add value to dropdown when the game config value is 1

							if ($config ['bbdkp_games_' . $gameid] == 1)
							{
								$template->assign_block_vars ( 'game_row', array (
									'VALUE' => $gameid, 'SELECTED' => '', 'OPTION' => $gamename ) );
								$installed_games [] = $gameid;
							}
						}
						
						$sql_array = array (
							'SELECT' => ' f.faction_name, f.faction_id ', 
							'FROM' => array (FACTION_TABLE => 'f' ), 
							'ORDER_BY' => 'faction_id asc ' );
						$sql = $db->sql_build_query ( 'SELECT', $sql_array );
						$result = $db->sql_query ( $sql );
						$s_faction_options = '';
						while ( $row = $db->sql_fetchrow ( $result ) )
						{
							$s_faction_options .= '<option value="' . $row ['faction_id'] . '" > ' . $row ['faction_name'] . '</option>';
						}
						$db->sql_freeresult ( $result );
						$template->assign_vars ( array (
							'GAME_ID' => $installed_games [0], 
							'S_FACTIONLIST_OPTIONS' => $s_faction_options, 
							'S_ADD' => TRUE, 
							'U_ACTION' => append_sid ( "{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addrace' ), 
							'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'] ) );
					}
					
					$template->assign_vars ( array (
						'LA_ALERT_AJAX' => $user->lang ['ALERT_AJAX'], 
						'LA_ALERT_OLDBROWSER' => $user->lang ['ALERT_OLDBROWSER'], 
						'UA_FINDFACTION' => append_sid ( $phpbb_admin_path . "style/dkp/findfaction.$phpEx" ) ) );
					
					$this->page_title = 'ACP_LISTGAME';
					$this->tpl_name = 'dkp/acp_addrace';
					break;
				}
				
				// user pressed delete race

				if ($racedelete)
				{
					// ask for permission
					if (confirm_box(true))
					{
						$deleterace = new \bbdkp\Races();
						$deleterace->race_id = request_var ( 'hidden_raceid', 0 );
						$deleterace->game_id = request_var ( 'hidden_game_id', '' );
						$deleterace->get();
						$deleterace->Delete();
						$success_message = sprintf($user->lang['ADMIN_DELETE_RACE_SUCCESS'], $deleterace->race_name);
						trigger_error($success_message . adm_back_link($this->u_action), E_USER_NOTICE);
					}
					else
					{
						$deleterace = new \bbdkp\Races;
						$deleterace->race_id = request_var ( 'id', 0 );
						$deleterace->game_id = request_var ( 'game_id', '' );
						$deleterace->get();
						
						$s_hidden_fields = build_hidden_fields(array(
								'racedelete' => true ,
								'hidden_raceid' => $deleterace->race_id, 
								'hidden_gameid' => $editgame->game_id
								));
						
						$template->assign_vars(array(
								'S_HIDDEN_FIELDS' => $s_hidden_fields));
						confirm_box(false, sprintf ( $user->lang ['CONFIRM_DELETE_RACE'], $deleterace->race_name )  , $s_hidden_fields);
					}
					
				}
				
				if ($classedit || $addclass)
				{
					// Load template for adding/editing

					$armortype = array (
						'CLOTH' => $user->lang ['CLOTH'], 
						'ROBE' => $user->lang ['ROBE'], 
						'LEATHER' => $user->lang ['LEATHER'], 
						'AUGMENTED' => $user->lang ['AUGMENTED'], 
						'MAIL' => $user->lang ['MAIL'], 
						'HEAVY' => $user->lang ['HEAVY'], 
						'PLATE' => $user->lang ['PLATE'] );
					
					if (isset ( $_GET ['id'] ))
					{
						//edit this class_id
						$listclasses = new \bbdkp\Classes;
						$listclasses->class_id = request_var ( 'id', 0 );
						$listclasses->game_id = request_var ( 'game_id', '' );
						$result = $listclasses->listclasses();
						
						while ( $row = $db->sql_fetchrow ($result))
						{
							$c_index = $row['c_index'];
							$class_id = (int) $row['class_id'];
							$class_name = (string) $row['class_name'];
							$class_min_level = (int) $row['class_min_level'];
							$class_max_level = (int) $row['class_max_level'];
							$class_armor_type = (string) $row['class_armor_type'];
							$class_imagename = (string) $row['imagename'];
							$class_colorcode = (string) $row['colorcode'];		
						}
						$db->sql_freeresult ( $result );
						
						// list installed games

						$installed_games = array ();
						foreach ( $this->games as $id => $gamename )
						{
							//add value to dropdown when the game config value is 1

							if ($config ['bbdkp_games_' . $id] == 1)
							{
								$template->assign_block_vars ( 'game_row', array (
									'VALUE' => $id, 'SELECTED' => ($listclasses->game_id == $id) ? ' selected="selected"' : '', 'OPTION' => $gamename ) );
								$installed_games [] = $id;
							}
						}
						
						//list armor types

						$s_armor_options = '';
						foreach ( $armortype as $armor => $armorname )
						{
							$selected = ($armor == $class_armor_type) ? ' selected="selected"' : '';
							$s_armor_options .= '<option value="' . $armor . '" ' . $selected . '> ' . $armorname . '</option>';
						}
						
						// send parameters to template

						$template->assign_vars ( array (
							'GAME_ID' => $listclasses->game_id, 
							'C_INDEX' => $c_index, 
							'CLASS_ID' => $class_id, 
							'CLASS_NAME' => $class_name, 
							'CLASS_MIN' => $class_min_level, 
							'CLASS_MAX' => $class_max_level, 
							'S_ARMOR_OPTIONS' => $s_armor_options, 
							'CLASS_IMAGENAME' => $class_imagename, 
							'COLORCODE' => ($class_colorcode == '') ? '#123456' : $class_colorcode, 
							'CLASS_IMAGE' => (strlen ( $class_imagename ) > 1) ? $phpbb_root_path . "images/class_images/" . $class_imagename . ".png" : '', 
							'S_CLASS_IMAGE_EXISTS' => (strlen ( $class_imagename ) > 1) ? true : false, 
							'S_ADD' => FALSE, 
							'U_ACTION' => append_sid ( "{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addclass' ), 
							'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'], 
							'MSG_ID_EMPTY' => $user->lang ['FV_REQUIRED_ID'] ) );
					
					}
					else
					{
						$installed_games = array ();
						foreach ( $this->games as $gameid => $gamename )
						{
							//add value to dropdown when the game config value is 1

							if ($config ['bbdkp_games_' . $gameid] == 1)
							{
								$template->assign_block_vars ( 'game_row', array (
									'VALUE' => $gameid, 'SELECTED' => '', 'OPTION' => $gamename ) );
								$installed_games [] = $gameid;
							}
						}
						
						// new class

						$s_armor_options = '';
						foreach ( $armortype as $armor => $armorname )
						{
							$s_armor_options .= '<option value="' . $armor . '" > ' . $armorname . '</option>';
						}
						// send parameters to template

						$template->assign_vars ( array (
							'S_ARMOR_OPTIONS' => $s_armor_options, 
							'S_ADD' => TRUE, 
							'U_ACTION' => append_sid ( "{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addclass' ), 
							'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'], 
							'MSG_ID_EMPTY' => $user->lang ['FV_REQUIRED_ID'] ) );
					
					}
					
					$this->page_title = 'ACP_LISTGAME';
					$this->tpl_name = 'dkp/acp_addclass';
					break;
				}
				
				// user pressed delete class in the listing

				if ($classdelete)
				{
					// ask for permission
					if (confirm_box ( true ))
					{
						$deleteclass = new \bbdkp\Classes();
						$deleteclass->class_id = request_var ( 'hidden_class_id', 0 );
						$deleteclass->game_id = request_var ( 'hidden_game_id', '' );
						$deleteclass->get();
						$deleteclass->Delete();
							
						trigger_error ( sprintf ( $user->lang ['ADMIN_DELETE_CLASS_SUCCESS'], $class_id ) . $this->link, E_USER_WARNING );
					}
					else
					{
						$deleteclass = new \bbdkp\Classes();
						$deleteclass->class_id = request_var ( 'id', 0 );
						$deleteclass->game_id = $editgame->game_id; 
						$deleteclass->get();
						
						// get field content
						$s_hidden_fields = build_hidden_fields ( array (
							'classdelete' => true, 
							'hidden_game_id' => $deleteclass->game_id, 
							'hidden_class_id' => $deleteclass->class_id) 
						);
						
						confirm_box ( false, sprintf ( $user->lang ['CONFIRM_DELETE_CLASS'], $deleteclass->classname ), $s_hidden_fields );
					}
						
					
				}
				
				$this->showgame($editgame); 
				
				$this->page_title = 'ACP_ADDGAME';
				$this->tpl_name = 'dkp/acp_' . $mode;
				
				break;
			
			case 'addfaction' :
				$addnew = (isset ( $_POST ['factionadd'] )) ? true : false;
				
				if ($addnew)
				{
					if (! check_form_key ( 'acp_dkp_game' ))
					{
						trigger_error ( 'FORM_INVALID' );
					}
					
					$faction = new \bbdkp\Faction();
					$faction->game_id = request_var ( 'game_id', request_var ( 'hidden_game_id', '' ) );
					$faction->faction_name = utf8_normalize_nfc ( request_var ( 'factionname', '', true ) );
					$faction->Make();
					unset($faction);
				}
				
				$installed_games = array ();
				foreach ( $this->games as $gameid => $gamename )
				{
					//add value to dropdown when the game config value is 1
					if ($config ['bbdkp_games_' . $gameid] == 1)
					{
						$template->assign_block_vars ( 'game_row', array (
							'VALUE' => $gameid, 'SELECTED' => '', 'OPTION' => $gamename ) );
						$hidden_game_id = $gameid;
						$installed_games [] = $gameid;
					}
				}
				// send parameters to template

				$template->assign_vars ( array (
					'GAME_ID' => $hidden_game_id, 
					'U_ACTION' => append_sid ( "{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addfaction' ), 
					'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'] ) );
				
				$this->page_title = 'ACP_LISTGAME';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
			
			case 'addrace' :
				$raceadd = (isset ( $_POST ['add'] )) ? true : false;
				$raceupdate = (isset ( $_POST ['update'] )) ? true : false;
				
				if ($raceadd || $raceupdate)
				{
					if (! check_form_key ( 'acp_dkp_game' ))
					{
						trigger_error ( 'FORM_INVALID' );
					}
				}
				
				if ($raceadd)
				{				
					$race = new \bbdkp\Races();
					$race->game_id = request_var ( 'game_id', request_var ( 'hidden_game_id', '' ) );
					$race->race_id = request_var ( 'race_id', 0 );
					$race->racename = utf8_normalize_nfc ( request_var ( 'racename', '', true ) );
					$race->race_faction_id = request_var ( 'faction', 0 );
					$race->image_male = utf8_normalize_nfc ( request_var ( 'image_male', '', true ) );
					$race->image_female = utf8_normalize_nfc ( request_var ( 'image_female', '', true ) );
					$race->Make();
					trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_RACE_SUCCESS'], $race->race_name ) . $this->link, E_USER_NOTICE );
				}
				
				if ($raceupdate)
				{
					$race = new \bbdkp\Races();
					$race->game_id = request_var ( 'game_id', request_var ( 'hidden_game_id', '' ) );
					$race->race_id = request_var ( 'race_id', 0 );
					$race->Get();
					$oldrace = $race;
					
					$race->racename = utf8_normalize_nfc ( request_var ( 'racename', '', true ) );
					$race->race_faction_id = request_var ( 'faction', 0 );
					$race->image_male = utf8_normalize_nfc ( request_var ( 'image_male', '', true ) );
					$race->image_female = utf8_normalize_nfc ( request_var ( 'image_female', '', true ) );
					$race->Update($oldrace);
					trigger_error ( sprintf ( $user->lang ['ADMIN_UPDATE_RACE_SUCCESS'], $race->racename ) . $this->link, E_USER_NOTICE );
				}
				
				$this->page_title = 'ACP_LISTGAME';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
			
			case 'addclass' :
				
				$classadd = (isset ( $_POST ['add'] )) ? true : false;
				$classupdate = (isset ( $_POST ['update'] )) ? true : false;
				
				if ($classadd || $classupdate)
				{
					if (! check_form_key ( 'acp_dkp_game' ))
					{
						trigger_error ( 'FORM_INVALID' );
					}
				}
				
				if ($classadd)
				{
					$newclass = new \bbdkp\Classes();
					$newclass->game_id = request_var ( 'game_id', '' );
					$newclass->classname = utf8_normalize_nfc ( request_var ( 'class_name', '', true ) );
					$newclass->class_id = request_var ( 'class_id', 0 );
					$newclass->min_level = request_var ( 'class_level_min', 0 );
					$newclass->max_level = request_var ( 'class_level_max', 0 );
					$newclass->armor_type = request_var ( 'armory', '' );
					$newclass->imagename = request_var ( 'image', '' );
					$newclass->colorcode = request_var ( 'classcolor', '' );
					$newclass->faction_id = '';
					$newclass->dps = '';
					$newclass->heal = '';
					$newclass->tank = '';
					$newclass->Make();
					
					trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_CLASS_SUCCESS'], $newclass->classname ) . $this->link, E_USER_NOTICE );
					
					
				}
				
				if ($classupdate)
				{

					$newclass = new \bbdkp\Classes();
					$newclass->game_id = request_var ( 'game_id_hidden', '' );
					$newclass->class_id = request_var ( 'class_id0', 0 );
					$newclass->c_index = request_var ( 'c_index', 0 );
					$newclass->Get();
					$oldclass = $newclass;
					
					$newclass->class_id = request_var ( 'class_id', 0 );				
					$newclass->classname = utf8_normalize_nfc ( request_var ( 'class_name', '', true ) );
					$newclass->min_level = request_var ( 'class_level_min', 0 );
					$newclass->max_level = request_var ( 'class_level_max', 0 );
					$newclass->armor_type = request_var ( 'armory', '' );
					$newclass->imagename = request_var ( 'image', '' );
					$newclass->colorcode = request_var ( 'classcolor', '' );
					$newclass->faction_id = '';
					$newclass->dps = '';
					$newclass->heal = '';
					$newclass->tank = '';
					$newclass->Update($oldclass);
					
					trigger_error ( sprintf ( $user->lang ['ADMIN_UPDATE_CLASS_SUCCESS'], $newclass->classname ) . $this->link, E_USER_NOTICE );
					
				}
				$this->page_title = 'ACP_LISTGAME';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;
			
		
		}
	}
	
	/**
	 * lists game parameters
	 * 
	 * @param \bbdkp\game $editgame
	 */
	private function showgame( \bbdkp\game $editgame)
	{
		
		global $db, $user, $phpbb_admin_path, $phpbb_root_path, $phpEx, $config, $template;
		
		//populate dropdown
		foreach ($editgame->installed_games as $key => $game)
		{
			$template->assign_block_vars('gamelistrow', array(
					'VALUE'      => $key,
					'OPTION'     => $game,
					'SELECTED'   => $editgame->game_id == $key ? ' selected="selected"' : '' , 
			));
		}
		
		// list the factions

		$total_factions = 0;
		$sql_array = array (
			'SELECT' => 'game_id, f_index, f.faction_id, f.faction_name  ', 
			'FROM' => array (FACTION_TABLE => 'f' ), 
			'WHERE' => " game_id = '" . $editgame->game_id . "'", 
			'ORDER_BY' => 'game_id, faction_id' );
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$total_factions ++;
			$template->assign_block_vars ( 'faction_row', array (
				
			'ID' => $row ['f_index'], 
			'FACTIONGAME' => $row ['game_id'], 
			'FACTIONID' => $row ['faction_id'], 
			'FACTIONNAME' => $row ['faction_name'], 
			'U_DELETE' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;factiondelete=1&amp;id={$row['f_index']}" ) ) );
		}
		
		$db->sql_freeresult ( $result );
		unset ( $row, $result );
		
		
		// list the races
		$sort_order = array (
			0 => array (
			'game_id asc, race_id asc', 'game_id desc, race_id asc' ), 1 => array (
			'race_id', 'race_id desc' ), 2 => array (
			'race_name', 'race_name desc' ), 3 => array (
			'faction_name desc', 'faction_name, race_name desc' ) );
		
		$current_order = $this->switch_order ( $sort_order );
		$total_races = 0;
		$sql_array = array (
			'SELECT' => ' r.game_id, r.race_id, l.name as race_name, r.race_faction_id, r.race_hide, f.faction_name , r.image_female, r.image_male ', 
			'FROM' => array (RACE_TABLE => 'r', FACTION_TABLE => 'f', BB_LANGUAGE => 'l' ), 
			'WHERE' => " r.race_faction_id = f.faction_id  AND f.game_id = r.game_id
					AND r.game_id = '" . $editgame->game_id . "'
		    		AND l.attribute_id = r.race_id AND l.game_id = r.game_id and l.language= '" . $config ['bbdkp_lang'] . "' 
		    		AND l.attribute = 'race' ", 'ORDER_BY' => $current_order ['sql'] );
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$total_races ++;
			$template->assign_block_vars ( 'race_row', array (
				'U_VIEW_RACE' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addrace&amp;r=" . $row ['race_id'] ), 
				'GAME' => $user->lang [strtoupper ( $row ['game_id'] )], 
				'RACEID' => $row ['race_id'], 
				'RACENAME' => $row ['race_name'], 
				'FACTIONNAME' => $row ['faction_name'], 
				'RACE_IMAGE_M' => (strlen ( $row ['image_male'] ) > 1) ? $phpbb_root_path . "images/race_images/" . $row ['image_male'] . ".png" : '', 
				'RACE_IMAGE_F' => (strlen ( $row ['image_female'] ) > 1) ? $phpbb_root_path . "images/race_images/" . $row ['image_female'] . ".png" : '', 
				'S_RACE_IMAGE_M_EXISTS' => (strlen ( $row ['image_male'] ) > 1) ? true : false, 
				'S_RACE_IMAGE_F_EXISTS' => (strlen ( $row ['image_female'] ) > 1) ? true : false, 
				'U_DELETE' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;racedelete=1&amp;id={$row['race_id']}&amp;game_id={$row['game_id']}" ), 
				'U_EDIT' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;raceedit=1&amp;id={$row['race_id']}&amp;game_id={$row['game_id']}" ) ) );
		}
		$db->sql_freeresult ( $result );
		unset ( $row, $result );
		
		// list the classes
		$sort_order2 = array (
			0 => array (
			'game_id asc, class_id asc', 'game_id desc, class_id asc' ), 1 => array (
			'class_id', 'class_id desc' ), 2 => array (
			'class_name', 'class_name desc' ), 3 => array (
			'class_armor_type', 'class_armor_type, class_id desc' ), 4 => array (
			'class_min_level', 'class_min_level, class_id desc' ), 5 => array (
			'class_max_level', 'class_max_level, class_id desc' ) );
		$current_order2 = $this->switch_order ( $sort_order2, "o1" );
		
		$total_classes = 0;
		$sql_array = array (
			'SELECT' => 'c.game_id, c.c_index, c.class_id, l.name as class_name, c.class_hide, c.class_min_level, class_max_level, c.class_armor_type , 
			c.imagename, c.colorcode ', 
			'FROM' => array (CLASS_TABLE => 'c', BB_LANGUAGE => 'l' ), 
			'WHERE' => " l.game_id = c.game_id AND l.attribute_id = c.class_id AND l.language= '" . $config ['bbdkp_lang'] . "' 
				AND l.attribute = 'class' AND c.game_id = '" . $editgame->game_id . "' ", 
			'ORDER_BY' => $current_order2 ['sql'] );
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$total_classes ++;
			$template->assign_block_vars ( 'class_row', array (
				'U_VIEW_CLASS' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addclass&amp;r=" . $row ['class_id'] ), 
				'GAME' => $user->lang [strtoupper ( $row ['game_id'] )], 
				'C_INDEX' => $row ['c_index'], 
				'CLASSID' => $row ['class_id'], 
				'CLASSNAME' => $row ['class_name'], 
				'COLORCODE' => $row ['colorcode'], 
				'CLASSARMOR' => (isset ( $user->lang [$row ['class_armor_type']] ) ? $user->lang [$row ['class_armor_type']] : ' '), 
				'CLASSMIN' => $row ['class_min_level'], 
				'CLASSMAX' => $row ['class_max_level'], 
				'CLASSHIDE' => $row ['class_hide'], 
				'S_CLASS_IMAGE_EXISTS' => (strlen ( $row ['imagename'] ) > 1) ? true : false, 
				'CLASSIMAGE' => (strlen ( $row ['imagename'] ) > 1) ? $phpbb_root_path . "images/class_images/" . $row ['imagename'] . ".png" : '', 
				'U_DELETE' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;classdelete=1&amp;id={$row['class_id']}&amp;game_id={$row['game_id']}" ), 
				'U_EDIT' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;classedit=1&amp;id={$row['class_id']}&amp;game_id={$row['game_id']}" ) ) );
		}
		$db->sql_freeresult ( $result );
		unset ( $row, $result );
		
		$template->assign_vars ( array (
				'EDITGAME' => sprintf($user->lang['ACP_EDITGAME'], $editgame->name  ) , 
				'GAME_ID' => $editgame->game_id,  
				'URI_GAME' => URI_GAME,
				'O_RACEGAMEID' => $current_order ['uri'] [0], 
				'O_RACEID' => $current_order ['uri'] [1], 
				'O_RACENAME' => $current_order ['uri'] [2], 
				'O_FACTIONNAME' => $current_order ['uri'] [3], 
				
				'O_CLASSGAMEID' => $current_order2 ['uri'] [0], 
				'O_CLASSID' => $current_order2 ['uri'] [1], 
				'O_CLASSNAME' => $current_order2 ['uri'] [2], 
				'O_CLASSARMOR' => $current_order2 ['uri'] [3], 
				'O_CLASSMIN' => $current_order2 ['uri'] [4], 
				'O_CLASSMAX' => $current_order2 ['uri'] [5], 
				
				'U_ADD_GAMES' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=editgames&amp;" ), 
				'LISTFACTION_FOOTCOUNT' => sprintf ( $user->lang ['LISTFACTION_FOOTCOUNT'], $total_factions ), 
				'LISTRACE_FOOTCOUNT' => sprintf ( $user->lang ['LISTRACE_FOOTCOUNT'], $total_races ), 
				'LISTCLASS_FOOTCOUNT' => sprintf ( $user->lang ['LISTCLASS_FOOTCOUNT'], $total_classes ), 
				'U_ACTION' => $this->u_action ) );
	}
}

?>