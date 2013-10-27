<?php
/**
 * Guild ACP file
 * 
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
if (! defined('IN_PHPBB'))
{
	exit();
}
if (! defined('EMED_BBDKP'))
{
	$user->add_lang(array('mods/dkp_admin'));
	trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}

// Include the base class
if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

// include ranks class
if (!class_exists('\bbdkp\Ranks'))
{
	require("{$phpbb_root_path}includes/bbdkp/ranks/Ranks.$phpEx");
}

//include the guilds class
if (!class_exists('\bbdkp\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
}


//include the guilds class
if (!class_exists('\bbdkp\Roles'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Roles.$phpEx");
}

/**
 * This class manages guilds
 *  
 * @package bbDKP
 */
class acp_dkp_guild extends \bbdkp\Admin
{
	/**
	 * url action
	 * @var string
	 */
	public $u_action;
	
	/**
	 * trigger url
	 * @var string
	 */
	public $link = ' ';
	
	/**
	 * current rul
	 * @var string
	 */
	public  $url_id;
	
	/**
	 * main acp function
	 * @param integer $id
	 * @param string $mode
	 */
	public function main ($id, $mode)
	{
		global $user, $template, $db, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$this->tpl_name = 'dkp/acp_' . $mode;
		switch ($mode)
		{

			/***************************************/
			// List Guilds
			/***************************************/
			case 'listguilds':

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds") . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';

				$updateguild = new \bbdkp\Guilds();
				$guildlist = $updateguild->guildlist();
				foreach ($guildlist as $g)
				{
					$template->assign_block_vars('defaultguild_row', array(
							'VALUE' => $g['id'] ,
							'SELECTED' => ($g['guilddefault'] == '1') ? ' selected="selected"' : '' ,
							'OPTION' => (! empty($g['name'])) ? $g['name'] : '(None)'));
				}
				
				$guilddefaultupdate = (isset($_POST['upddefaultguild'])) ? true : false;
				if($guilddefaultupdate)
				{
					$id = request_var('defaultguild', 0); 
					$updateguild->update_guilddefault($id	); 
					$success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $id);
						trigger_error($success_message . $this->link, E_USER_NOTICE);
				}
				
				$guildadd = (isset($_POST['guildadd'])) ? true : false;
				if ($guildadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=addguild"));
					break;
				}
				
				$sort_order = array(
					0 => array(	'id' , 'id desc') ,
					1 => array('name' , 'name desc') ,
					2 => array('realm desc' , 'realm desc') ,
					3 => array('region' , 'region desc') ,
					4 => array('roster' , 'roster desc'));

				$current_order = $this->switch_order($sort_order);
				$guild_count = 0;
				$previous_data = '';
				$sort_index = explode('.', $current_order['uri']['current']);
				$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
				$show_all = ((isset($_GET['show'])) && request_var('show', '') == 'all') ? true : false;

				$sql = 'SELECT id, name, realm, region, roster, game_id FROM ' . GUILD_TABLE . ' WHERE id > 0 ORDER BY ' . $current_order['sql'];
				if (! ($guild_result = $db->sql_query($sql)))
				{
					trigger_error($user->lang['ERROR_GUILDNOTFOUND'], E_USER_WARNING);
				}
				$lines = 0;
				while ($row = $db->sql_fetchrow($guild_result))
				{
					$guild_count ++;
					$listguild = new \bbdkp\Guilds($row['id']);

					$template->assign_block_vars('guild_row', array(
						'ID' => $listguild->guildid ,
						'NAME' => $listguild->name ,
						'REALM' => $listguild->realm ,
						'REGION' => $listguild->region ,
						'GAME' => $listguild->game_id ,
						'MEMBERCOUNT' => $listguild->membercount ,
						'SHOW_ROSTER' => ($listguild->showroster == 1 ? 'yes' : 'no') ,
						'U_VIEW_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=addguild&amp;" . URI_GUILD . '=' . $listguild->guildid))
					);
					$previous_data = $row[$previous_source];
				}

				$form_key = 'listguilds';
				add_form_key($form_key);
				$template->assign_vars(array(
					'F_GUILDLIST' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild") . '&amp;mode=listguilds' ,
					'F_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild") . '&amp;mode=addguild' ,
					'L_TITLE' => $user->lang['ACP_LISTGUILDS'] ,
					'L_EXPLAIN' => $user->lang['ACP_LISTGUILDS_EXPLAIN'] ,
					'BUTTON_VALUE' => $user->lang['DELETE_SELECTED_GUILDS'] ,
					'O_ID' => $current_order['uri'][0] ,
					'O_NAME' => $current_order['uri'][1] ,
					'O_REALM' => $current_order['uri'][2] ,
					'O_REGION' => $current_order['uri'][3] ,
					'O_ROSTER' => $current_order['uri'][4] ,
					'U_LIST_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds") ,
					'GUILDMEMBERS_FOOTCOUNT' => sprintf($user->lang['GUILD_FOOTCOUNT'], $guild_count)));
				$this->page_title = 'ACP_LISTGUILDS';
				break;

			/*************************************
			 *  Edit / Add Guild 
			 *************************************/
			case 'addguild':

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=listguilds") . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
				/* select data */

				if (isset($_GET[URI_GUILD]))
				{
					$this->url_id = request_var(URI_GUILD, 0);
					$add= false; 
				}
				else
				{
					$this->url_id = -1; 
				}

				$memberadd = (isset($_POST['memberadd'])) ? true : false;
				if ($memberadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addmember&amp;" . URI_GUILD . "=" . $this->url_id  ));
					break;
				}
				
				$updateguild = new \bbdkp\Guilds($this->url_id);

				$add = (isset($_POST['addguild'])) ? true : false;
				$submit = (isset($_POST['updateguild'])) ? true : false;
				$delete = (isset($_POST['deleteguild'])) ? true : false;
				$getarmorymembers = (isset($_POST['armory'])) ? true : false;
				  
				$updaterank = (isset($_POST['updaterank'])) ? true : false;
				$deleterank = (isset($_GET['deleterank'])) ? true : false;
				$addrank = (isset($_POST['addrank'])) ? true : false;
				
				$addrecruitment = (isset($_POST['addrecruitment'])) ? true : false;
				$updateroles = (isset($_POST['updateroles'])) ? true : false;
				
				// POST check
				if ($add || $submit || $getarmorymembers || $updaterank || $addrank || $addrecruitment)
				{
					if (! check_form_key('dbT2TvCZNZHjckSvbTPc'))
					{
						trigger_error('FORM_INVALID');
					}
				}
				
				if ($add)
				{
					$updateguild->name = utf8_normalize_nfc(request_var('guild_name', '', true));
					$updateguild->realm = utf8_normalize_nfc(request_var('realm', '', true));
					$updateguild->region = request_var('region_id', '');
					$updateguild->game_id = request_var('game_id', '');
					$updateguild->showroster = (isset($_POST['showroster'])) ? true : false;
					$updateguild->min_armory = request_var('min_armorylevel', 0);
					
					if ($updateguild->MakeGuild() == true)
					{
						$updateguild->Guildupdate($updateguild, array());
						$success_message = sprintf($user->lang['ADMIN_ADD_GUILD_SUCCESS'], $updateguild->name);
						trigger_error($success_message . $this->link, E_USER_NOTICE);
					}
					else
					{
						$success_message = sprintf($user->lang['ADMIN_ADD_GUILD_FAIL'], $updateguild->name);
						trigger_error($success_message . $this->link, E_USER_WARNING);
					}
				}

				//updating
				if ($submit || $getarmorymembers)
				{
					$updateguild->guildid = $this->url_id;
					$updateguild->Getguild();
					$old_guild = new \bbdkp\Guilds($this->url_id);
					$old_guild->Getguild();

					$updateguild->game_id = request_var('game_id', '');
					$updateguild->name = utf8_normalize_nfc(request_var('guild_name', ' ', true));
					$updateguild->realm = utf8_normalize_nfc(request_var('realm', ' ', true));
					$updateguild->region = request_var('region_id', ' ');
					$updateguild->showroster = request_var('showroster', 0);
					$updateguild->min_armory = request_var('min_armorylevel', 0);
					$updateguild->recstatus = request_var('switchon_recruitment', 0);
						
					//@todo complete for other games?
					$updateguild->aionlegionid = 0;
					$updateguild->aionserverid = 0;
					$armoryparams = array();
					if ($getarmorymembers)
					{
						$armoryparams = array('members');
					}
					$updateguild->Guildupdate($old_guild, $armoryparams);

					$success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $this->url_id);
					trigger_error($success_message . $this->link);
				}

					
				if ($delete)
				{
					if (confirm_box(true))
					{
						$deleteguild = new \bbdkp\Guilds(request_var('guild_id', 0));
						$deleteguild->Getguild();
						$deleteguild->Guildelete();
						$success_message = sprintf($user->lang['ADMIN_DELETE_GUILD_SUCCESS'], $deleteguild->guild_id);
						trigger_error($success_message . adm_back_link($this->u_action), E_USER_NOTICE);
					}
					else
					{
						$s_hidden_fields = build_hidden_fields(array(
							'deleteguild' => true ,
							'guild_id' => $updateguild->guildid));
						$template->assign_vars(array(
							'S_HIDDEN_FIELDS' => $s_hidden_fields));
						confirm_box(false, $user->lang['CONFIRM_DELETE_GUILD'], $s_hidden_fields);
					}
				}
						
				if ($addrank)
				{
					$newrank = new \bbdkp\Ranks($updateguild->guildid);
					$newrank->RankName = utf8_normalize_nfc(request_var('nrankname', '', true));
					$newrank->RankId = request_var('nrankid', 0);
					$newrank->RankGuild = $updateguild->guildid; 
					$newrank->RankHide = (isset($_POST['nhide'])) ? 1 : 0;
					$newrank->RankPrefix = utf8_normalize_nfc(request_var('nprefix', '', true));
					$newrank->RankSuffix = utf8_normalize_nfc(request_var('nsuffix', '', true));
					$newrank->Makerank();
					$success_message = $user->lang['ADMIN_RANKS_ADDED_SUCCESS'];
					trigger_error($success_message . $this->link);
				}
				
				if ($updaterank)
				{
					$newrank = new \bbdkp\Ranks($updateguild->guildid);
					$oldrank = new \bbdkp\Ranks($updateguild->guildid);
					// template
					$modrank = utf8_normalize_nfc(request_var('ranks', array(0 => ''), true));
					foreach ($modrank as $rank_id => $rank_name)
					{
						$oldrank->RankId = $rank_id;
						$oldrank->RankGuild = $updateguild->guildid; 
						$oldrank->Getrank();
				
						$newrank->RankId = $rank_id;
						$newrank->RankGuild = $oldrank->RankGuild;
						$newrank->RankName = $rank_name;
						$newrank->RankHide = (isset($_POST['hide'][$rank_id])) ? 1 : 0;
				
						$rank_prefix = utf8_normalize_nfc(request_var('prefix', array(
								(int) $rank_id => ''), true));
						$newrank->RankPrefix = $rank_prefix[$rank_id];
				
						$rank_suffix = utf8_normalize_nfc(request_var('suffix', array(
								(int) $rank_id => ''), true));
						$newrank->RankSuffix = $rank_suffix[$rank_id];
				
						// compare old with new,
						if ($oldrank != $newrank)
						{
							$newrank->Rankupdate($oldrank);
						}
					}
					$success_message = $user->lang['ADMIN_RANKS_UPDATE_SUCCESS'];
					trigger_error($success_message . $this->link);
				}
				
				if ($deleterank)
				{
					if (confirm_box(true))
					{
						$guild_id = request_var('hidden_guild_id', 'x');
						$rank_id = request_var('hidden_rank_id', 'x');
						$guild_name = request_var('hidden_guild_name', 'x');
						$old_rank_name = request_var('hidden_rank_name', 'x');
						// hardcoded exclusion of ranks 90/99
						$sql = 'DELETE FROM ' . MEMBER_RANKS_TABLE . ' WHERE rank_id != 90 and rank_id != 99 and rank_id=' .
								$rank_id . ' and guild_id = ' . $guild_id;
						$db->sql_query($sql);
						// log the action
						$log_action = array(
								'header' => 'L_ACTION_RANK_DELETED' ,
								'id' => (int) $rank_id ,
								'L_NAME' => $old_rank_name ,
								'L_ADDED_BY' => $user->data['username']);
						$this->log_insert(array(
								'log_type' => $log_action['header'] ,
								'log_action' => $log_action));
					}
					else
					{
						// delete the rank only if there are no members left
						$rank_id = request_var('ranktodelete', 'x');
						
						$sql = 'SELECT count(*) as countm FROM ' . MEMBER_LIST_TABLE . '
						where member_rank_id = ' . $rank_id . ' and member_guild_id = ' . $updateguild->guildid;
						$result = $db->sql_query($sql);
						$countm = $db->sql_fetchfield('countm');
						if ($countm != 0)
						{
							trigger_error($user->lang['ERROR_RANKMEMBERS'] . $this->link, E_USER_WARNING);
						}
						$db->sql_freeresult($result);
						
						$sql = "SELECT a.rank_id, a.rank_name 
								FROM " . MEMBER_RANKS_TABLE . ' a , ' . GUILD_TABLE . ' b
								WHERE a.guild_id = b.id 
								AND a.rank_id = ' . $rank_id . ' 
								AND b.id = ' . $updateguild->guildid;
						$result = $db->sql_query($sql);
						while ($row = $db->sql_fetchrow($result))
						{
							$old_rank_name = $row['rank_name'];
							$guild_name = $row['name'];
						}
						$db->sql_freeresult($result);

						$s_hidden_fields = build_hidden_fields(array(
								'deleterank' => true ,
								'hidden_rank_id' => $rank_id ,
								'hidden_guild_id' => $updateguild->guildid,
								'hidden_guild_name' => $updateguild->name ,
								'hidden_rank_name' => $old_rank_name));
						
						confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_RANKS'], $old_rank_name, $updateguild->name), $s_hidden_fields);
					}
				}
				
				if($addrecruitment)
				{
					// insert a row in roles table
					$addrole = new \bbdkp\Roles(
							$this->url_id, 
							request_var('recruitrole' , ''),
							request_var('recruitclass' , 0), 
							request_var('recruitneeded' , 0)
							);
					$addrole->make(); 
					unset($addrole); 
				}
				
				if($updateroles)
				{
					$updaterole = new \bbdkp\Roles(); 
					$modroles = utf8_normalize_nfc(request_var('needed', array(0 => 0), true));
					foreach ($modroles as $id => $needed)
					{
						$updaterole->id = $id; 
						$updaterole->needed = $needed;
						$updaterole->update(); 
					}
				}
				
				// start template loading

				if ($updateguild->guildid != 0)
				{
					foreach ($this->regions as $key => $regionname)
					{
						$template->assign_block_vars('region_row', array(
								'VALUE' => $key ,
								'SELECTED' => ($updateguild->region == $key) ? ' selected="selected"' : '' ,
								'OPTION' => (! empty($regionname)) ? $regionname : '(None)'));
					}
					//add value to dropdown when the game config value is 1
					
					if(isset($this->games))
					{
						foreach ($this->games as $key => $gamename)
						{
							$template->assign_block_vars('game_row', array(
									'VALUE' => $key ,
									'SELECTED' => ($updateguild->game_id == $key) ? ' selected="selected"' : '' ,
									'OPTION' => (! empty($gamename)) ? $gamename : '(None)'));
						}
						
					}
					else
					{
						trigger_error('ERROR_NOGAMES', E_USER_WARNING );
					}
					
				
				}
				else
				{
					// NEW PAGE
					foreach ($this->regions as $key => $regionname)
					{
						$template->assign_block_vars('region_row', array(
								'VALUE' => $key ,
								'SELECTED' => '' ,
								'OPTION' => (! empty($regionname)) ? $regionname : '(None)'));
					}
				
					//add value to dropdown when the game config value is 1
					if(isset($this->games))
					{
						foreach ($this->games as $key => $gamename)
						{
							$template->assign_block_vars('game_row', array(
									'VALUE' => $key ,
									'SELECTED' => '' ,
									'OPTION' => (! empty($gamename)) ? $gamename : '(None)'));
						}
					}
					else
					{
						trigger_error('ERROR_NOGAMES', E_USER_WARNING ); 
					}
					
				}
				
				// list the ranks for this guild
				$listranks = new \bbdkp\Ranks($updateguild->guildid);
				$listranks->game_id = $updateguild->game_id; 
				$result = $listranks->listranks();
				while ($row = $db->sql_fetchrow($result))
				{
					$prefix = $row['rank_prefix'];
					$suffix = $row['rank_suffix'];
					$template->assign_block_vars('ranks_row', array(
						'RANK_ID' => $row['rank_id'] ,
						'RANK_NAME' => $row['rank_name'] ,
						'RANK_PREFIX' => $prefix ,
						'RANK_SUFFIX' => $suffix ,
						'HIDE_CHECKED' => ($row['rank_hide'] == 1) ? 'checked="checked"' : '' ,
						'S_READONLY' => ($row['rank_id'] == 90) ? true : false ,
						'U_DELETE_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", 
							"i=dkp_guild&amp;mode=addguild&amp;deleterank=1&amp;ranktodelete=" . 
							$row['rank_id'] . "&amp;guild=" . $updateguild->guildid)
					));
				}
				$db->sql_freeresult($result);

				// list the recruitment status per role/class for this guild
				// get clas distribution
				$classdistribution = $updateguild->classdistribution(); 
				
				foreach ($updateguild->possible_recstatus as $d_value => $d_name)
				{
					$template->assign_block_vars('recruitment_status_row', array(
							'VALUE' => $d_value ,
							'SELECTED' => ($d_value == $updateguild->recstatus) ? ' selected="selected"' : '' ,
							'OPTION' => $d_name));
				}
				
				$listroles = new \bbdkp\Roles();
				$listroles->guild_id = $updateguild->guildid; 
				foreach ($listroles->roles as $role => $rolename)
				{
					$template->assign_block_vars('rolelist_row', array(
							'VALUE' => $role ,
							'SELECTED' => '' ,
							'OPTION' => $rolename));
				}
				
				foreach ($classdistribution as $class_id => $class)
				{
					$template->assign_block_vars('classlist_row', array(
							'VALUE' => $class_id ,
							'SELECTED' => '' ,
							'OPTION' => $class['classname']));
				}				
				
				$result = $listroles->listroles();

				$current = 0; 
				$needed = 0;
				$difference = 0;
				while($row = $db->sql_fetchrow($result))
				{
					
					$role = isset($row['role']) ? 
							( isset($user->lang[$row['role']]) ? $user->lang[$row['role']]  : $row['role']  ) : 
							$listroles->roles['NA'];

					$current += (int) $classdistribution[$row['class_id']]['classcount'];
					$needed += (int) isset($row['needed']) ? (int) $row['needed'] : 0;
					
					$css = 'positive';
					if (((int) $classdistribution[$row['class_id']]['classcount'] - ((int) isset($row['needed']) ? (int) $row['needed'] : 0) ) < 0) 
					{
						$css = 'negative'; 
					}
					 
					$template->assign_block_vars('roles_row', array(
						'GUILD_ID' 	=> $row['guild_id'] ,
						'GAME_ID' 	=> $row['game_id'] ,
						'ROLEID' 	=> $row['roleid'], 
						'ROLE' 		=> $role, 
						'STIJL' 	=> $css, 
						'CLASS_ID' 	=> $row['class_id'] ,
						'CLASS' 	=> $row['class_name'] ,
						'IMAGENAME' 	=> $row['imagename'] ,
						'COLORCODE' => $row['colorcode'] ,
						'CLASS_IMAGE' => (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row['imagename'] . ".png" : '' ,
						'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false ,
						'CURRENT'		=> $classdistribution[$row['class_id']]['classcount'],
						'NEEDED' 	=> isset($row['needed']) ? $row['needed'] : '0' ,
						'DIFFERENCE'	=> (int) $classdistribution[$row['class_id']]['classcount'] - (isset($row['needed']) ? (int) $row['needed'] : 0) ,
					));
					
					
				}
				$db->sql_freeresult($result);
				
				
				//print all other static info
				$template->assign_vars(array(
					// Form values
					'CURRENT' => $current,
					'NEEDED' => $needed,
					'DIFFERENCE' => ($current - $needed),
					'RECSTATUS' => $updateguild->recstatus,
					'GAME_ID'	=> $updateguild->game_id,
					'GUILD_ID' => $updateguild->guildid,
					'GUILD_NAME' => $updateguild->name,
					'REALM' => $updateguild->realm,
					'REGION' => $updateguild->region,
					'MEMBERCOUNT' => $updateguild->membercount ,
					'ARMORY_URL' => $updateguild->guildarmoryurl ,
					'MIN_ARMORYLEVEL' => $updateguild->min_armory ,
					'SHOW_ROSTER' => ($updateguild->showroster == 1) ? 'checked="checked"' : '',
					'U_ADD_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_guild&amp;mode=addguild&amp;addrank=1&amp;guild=" . $updateguild->guildid), 
					// Language
					'L_TITLE' =>  ($this->url_id < 0 ) ? $user->lang['ACP_ADDGUILD'] : $user->lang['ACP_EDITGUILD'] , 
					'L_EXPLAIN' => ($this->url_id < 0 ) ?  $user->lang['ACP_ADDGUILD_EXPLAIN'] : $user->lang['ACP_EDITGUILD_EXPLAIN'] ,
					'L_ADD_GUILD_TITLE' => ($this->url_id < 0) ? $user->lang['ADD_GUILD'] : $user->lang['EDIT_GUILD'] ,
					// Javascript messages
					'MSG_NAME_EMPTY' => $user->lang['FV_REQUIRED_NAME'] ,
					'S_ADD' => ($this->url_id < 0 ) ? true : false));

				// extra 
				if($updateguild->game_id == 'wow')
				{
					$template->assign_vars(array(
							'S_WOW' 	=> true, 
							'EMBLEM'	=> $updateguild->emblempath,
							'EMBLEMFILE' => basename($updateguild->emblempath),
							'ARMORY'	=> $updateguild->guildarmoryurl,
							'ACHIEV'	=> $updateguild->achievementpoints,
					));
				}
				
				$form_key = 'dbT2TvCZNZHjckSvbTPc';
				add_form_key($form_key);
				
				$this->page_title = $user->lang['ACP_ADDGUILD'];
				
				break;

			default:
				$this->page_title = 'ACP_DKP_MAINPAGE';
				$success_message = 'Error';
				trigger_error($success_message . $this->link, E_USER_WARNING);
		}
	}


}
?>
