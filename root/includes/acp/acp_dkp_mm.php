<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */


/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}
if (! defined('EMED_BBDKP'))
{
	$user->add_lang(array(
		'mods/dkp_admin'));
	trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}

/**
 * This class manages member general info
 *
 */
class acp_dkp_mm extends bbDKP_Admin
{
	public $u_action;
	public $member;
	public $old_member;
	public $link = ' ';

	public function main ($id, $mode)
	{
		global $user, $template, $db, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$user->add_lang(array('mods/dkp_admin'));
		$user->add_lang(array('mods/dkp_common'));

		switch ($mode)
		{
			/***************************************
			*
			* List members
			*
			/***************************************/
			case 'mm_listmembers':

				// Include the base class
				if (!class_exists('Members'))
				{
					require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
				}

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx",
				"i=dkp_mm&amp;mode=mm_listmembers") . '"><h3>Return to Index</h3></a>';

				// add member button redirect
				$showadd = (isset($_POST['memberadd'])) ? true : false;
				if ($showadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addmember"));
					break;
				}

				// set activation flag
				$activate = (isset($_POST['deactivate'])) ? true : false;
				if ($activate)
				{
					if (! check_form_key('mm_listmembers'))
					{
						trigger_error('FORM_INVALID');
					}

					$activatemember = new includes\bbdkp\Members();
					$activate_members = request_var('activate_id', array(0));
					$member_window = request_var('hidden_member', array(0));
					$activatemember->activate($activate_members, $member_window);
					unset($activatemember);
				}

				// batch delete
				$del_batch = (isset($_POST['delete'])) ? true : false;
				if ($del_batch)
				{
					$members_tbdel = request_var('delete_id', array(0));
					$this->member_batch_delete($members_tbdel);
					unset($members_tbdel);
				}

				// guild dropdown query
				$sql = 'SELECT id, name, realm, region
                       FROM ' . GUILD_TABLE . '
                       ORDER BY id desc';
				$resultg = $db->sql_query($sql);

				// show other guild
				$submit = (isset ( $_POST ['member_guild_id'] ) || isset ( $_GET ['member_guild_id'] ) ) ? true : false;
				/* check if page was posted back */
				if ($submit)
				{
					// user selected dropdow - get guildid
					$guild_id = request_var('member_guild_id', 0);
					// fill popup and set selected to Post value
					while ($row = $db->sql_fetchrow($resultg))
					{
						$template->assign_block_vars('guild_row', array(
							'VALUE' => $row['id'] ,
							'SELECTED' => ($row['id'] == $guild_id) ? ' selected="selected"' : '' ,
							'OPTION' => (! empty($row['name'])) ? $row['name'] : '(None)'));
					}
					$db->sql_freeresult($resultg);
				}
				else // default pageloading
				{
					$sql = 'SELECT id FROM ' . GUILD_TABLE . ' ORDER BY id DESC';
					$result = $db->sql_query_limit($sql, 1);
					while ($row = $db->sql_fetchrow($result))
					{
						$guild_id = $row['id'];
					}
					$db->sql_freeresult($result);
					// fill popup and set selected to default selection
					while ($row = $db->sql_fetchrow($resultg))
					{
						$template->assign_block_vars('guild_row', array(
							'VALUE' => $row['id'] ,
							'SELECTED' => ($row['id'] == $guild_id) ? ' selected="selected"' : '' ,
							'OPTION' => $row['name']));
					}
					$db->sql_freeresult($resultg);
				}
				$previous_data = '';

				if (!class_exists('Guild'))
				{
					require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
				}

				$Guild = new includes\bbdkp\Guild($guild_id);

				//get window
				$start = request_var('start', 0, false);
				$sort_order = array(
					0 => array('member_name' , 'member_name desc') ,
					1 => array('username' , 'username desc') ,
					2 => array('member_level' , 'member_level desc') ,
					3 => array('member_class' , 'member_class desc') ,
					4 => array('rank_name' , 'rank_name desc') ,
					5 => array('member_joindate' , 'member_joindate desc') ,
					6 => array('member_outdate' , 'member_outdate desc') ,
					7 => array('member_race' ,	'member_race desc'));

				$current_order = switch_order($sort_order);
				$sort_index = explode('.', $current_order['uri']['current']);
				$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
				$show_all = ((isset($_GET['show'])) && request_var('show', '') == 'all') ? true : false;

				$members_result = $Guild->listmembers($current_order['sql'], $start);
				if (! ($members_result))
				{
					trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
				}
				$lines = 0;
				$member_count = 0;

				while ($row = $db->sql_fetchrow($members_result))
				{
					$phpbb_user_id = (int) $row['phpbb_user_id'];
					$race_image = (string) (($row['member_gender_id'] == 0) ? $row['image_male'] : $row['image_female']);
					$member_count += 1;
					$lines +=1;
					$template->assign_block_vars('members_row', array(
						'S_READONLY' => ($row['rank_id'] == 90 || $row['rank_id'] == 99) ? true : false ,
						'STATUS' => ($row['member_status'] == 1) ? 'checked="checked" ' : '' ,
						'ID' => $row['member_id'] ,
						'COUNT' => $member_count ,
						'NAME' => $row['rank_prefix'] . $row['member_name'] . $row['rank_suffix'] ,
						'USERNAME' => get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']) ,
						'RANK' => $row['rank_name'] ,
						'LEVEL' => ($row['member_level'] > 0) ? $row['member_level'] : '&nbsp;' ,
						'ARMOR' => (! empty($row['armor_type'])) ? $row['armor_type'] : '&nbsp;' ,
						'COLORCODE' => ($row['colorcode'] == '') ? '#123456' : $row['colorcode'] ,
						'CLASS_IMAGE' => (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '' ,
						'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false ,
						'RACE_IMAGE' => (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '' ,
						'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false ,
						'CLASS' => ($row['member_class'] != 'NULL') ? $row['member_class'] : '&nbsp;' ,
						'JOINDATE' => date($config['bbdkp_date_format'], $row['member_joindate']) ,
						'OUTDATE' => ($row['member_outdate'] == 0) ? '' : date($config['bbdkp_date_format'], $row['member_outdate']) ,
						'U_VIEW_USER' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=users&amp;icat=13&amp;mode=overview&amp;u=$phpbb_user_id") ,
						'U_VIEW_MEMBER' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_mm&amp;mode=mm_addmember&amp;' . URI_NAMEID . '=' . $row['member_id']) ,
						'U_DELETE_MEMBER' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_mm&amp;mode=mm_addmember&amp;delete=1&amp;' . URI_NAMEID . '=' . $row['member_id'])));
					$previous_data = $row[$previous_source];
				}

				$db->sql_freeresult($members_result);
				$footcount_text = sprintf($user->lang['LISTMEMBERS_FOOTCOUNT'], $lines);
				$memberpagination = generate_pagination(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri']['current'] . "&amp;member_guild_id=".$guild_id), $Guild->membercount, $config['bbdkp_user_llimit'], $start, true);
				$form_key = 'mm_listmembers';
				add_form_key($form_key);

				$template->assign_vars(array(
					'GUILDID' => $guild_id,
					'START' => $start,
					'F_MEMBERS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm") . '&amp;mode=mm_addmember' ,
					'F_MEMBERS_LIST' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm") . '&amp;mode=mm_listmembers' ,
					'L_TITLE' => $user->lang['ACP_MM_LISTMEMBERS'] ,
					'L_EXPLAIN' => $user->lang['ACP_MM_LISTMEMBERS_EXPLAIN'] ,
					'O_NAME' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][0] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_USERNAME' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][1] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_LEVEL' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][2] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_CLASS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][3] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][4] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_JOINDATE' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][5] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'O_OUTDATE' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;o=" . $current_order['uri'][6] . "&amp;" . URI_GUILD . "=" . $guild_id) ,
					'U_LIST_MEMBERS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers&amp;") ,
					'LISTMEMBERS_FOOTCOUNT' => $footcount_text ,
					'MEMBER_PAGINATION' => $memberpagination));
				$this->page_title = 'ACP_MM_LISTMEMBERS';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;

			/***************************************/
			// add member
			/***************************************/
			case 'mm_addmember':
				// Include the base class
				if (!class_exists('Members'))
				{
					require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
				}

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listmembers") . '"><h3>' . $user->lang['RETURN_MEMBERLIST'] . '</h3></a>';

				$add = (isset($_POST['add'])) ? true : false;
				$update = (isset($_POST['update'])) ? true : false;
				$delete = (isset($_GET['delete']) || isset($_POST['delete'])) ? true : false;

				if ($add || $update)
				{
					if (! check_form_key('mm_addmember'))
					{
						trigger_error('FORM_INVALID');
					}
				}

				// add guildmember handler
				if ($add)
				{

					$newmember = new includes\bbdkp\Members();
					$newmember->game_id = request_var('game_id', '');
					$newmember->member_name = utf8_normalize_nfc(request_var('member_name', '', true));
					$newmember->member_guild_id = request_var('member_guild_id', 0);
					$newmember->member_rank_id = request_var('member_rank_id', 99);
					$newmember->member_level = request_var('member_level', 0);
					$newmember->member_race_id = request_var('member_race_id', 0);
					$newmember->member_class_id = request_var('member_class_id', 0);
					$newmember->member_gender_id = isset($_POST['gender']) ? request_var('gender', '') : '0';
					$newmember->member_comment = utf8_normalize_nfc(request_var('member_comment', '', true));
					$newmember->member_joindate = mktime(0, 0, 0, request_var('member_joindate_mo', 0), request_var('member_joindate_d', 0), request_var('member_joindate_y', 0));
					$newmember->member_outdate = 0;
					if (request_var('member_outdate_mo', 0) + request_var('member_outdate_d', 0) != 0)
					{
						$newmember->member_outdate = mktime(0, 0, 0, request_var('member_outdate_mo', 0), request_var('member_outdate_d', 0), request_var('member_outdate_y', 0));
					}
					$newmember->member_achiev = 0;
					$newmember->member_armory_url = utf8_normalize_nfc(request_var('member_armorylink', '', true));
					$newmember->phpbb_user_id = request_var('phpbb_user_id', 0);
					$newmember->Make();

					if ($newmember->member_id > 0)
					{
						//record added. now update some stats
						$success_message = sprintf($user->lang['ADMIN_ADD_MEMBER_SUCCESS'], ucwords($newmember->member_name));
						trigger_error($success_message . $this->link, E_USER_NOTICE);
					}
					else
					{
						$failure_message = sprintf($user->lang['ADMIN_ADD_MEMBER_FAIL'], ucwords($newmember->member_name), $member_id);
						trigger_error($failure_message . $this->link, E_USER_WARNING);
					}

					unset($newmember);
				}

				//
				// update guild member handler
				//
				if ($update)
				{

					$updatemember = new includes\bbdkp\Members();
					$updatemember->member_id = request_var('hidden_member_id', 0);
					if ($updatemember->member_id == 0)
					{
						$updatemember->member_id = request_var(URI_NAMEID, 0);
					}
					$updatemember->Get();

					$old_member = $updatemember;
					$updatemember->member_gender_id = isset($_POST['gender']) ? request_var('gender', '') : '0';
					$updatemember->member_name = utf8_normalize_nfc(request_var('member_name', '', true));
					$updatemember->member_rank_id = request_var('member_rank_id', 99);
					$updatemember->member_level = request_var('member_level', 0);
					$updatemember->member_joindate = mktime(0, 0, 0, request_var('member_joindate_mo', 0), request_var('member_joindate_d', 0), request_var('member_joindate_y', 0));
					$updatemember->member_outdate = 0;
					if (request_var('member_outdate_mo', 0) + request_var('member_outdate_d', 0) != 0)
					{
						$updatemember->member_outdate  = mktime(0, 0, 0, request_var('member_outdate_mo', 0), request_var('member_outdate_d', 0), request_var('member_outdate_y', 0));
					}

					$updatemember->member_status = request_var('activated', 0) > 0 ? 1 : 0;
					$updatemember->phpbb_user_id = request_var('phpbb_user_id', 0);

					$updatemember->Update($old_member);

					$success_message = sprintf($user->lang['ADMIN_UPDATE_MEMBER_SUCCESS'], $updatemember->member_name);
					trigger_error($success_message . $this->link);
				}


				//
				// delete guildmember
				//
				if ($delete)
				{

					if (confirm_box(true))
					{
						// recall hidden vars
						$deletemember = new includes\bbdkp\Members();
						$deletemember->member_id = request_var('del_member_id', 0);
						$deletemember->Get();
						$deletemember->Delete();
						$success_message = sprintf($user->lang['ADMIN_DELETE_MEMBERS_SUCCESS'], $deletemember->member_name);
						trigger_error($success_message . $this->link);
					}
					else
					{
						$deletemember = new includes\bbdkp\Members();
						$deletemember->member_id = request_var('del_member_id', 0);
						$deletemember->Get();
						$s_hidden_fields = build_hidden_fields(array(
							'delete' => true ,
							'del_member_id' => $deletemember->member_id));

						confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_MEMBER'], $deletemember->member_name), $s_hidden_fields);
					}
					$S_ADD = true;
					unset($deletemember);
				}

				/*
				 * fill template
				 */
				$editmember = new includes\bbdkp\Members();
				$editmember->member_id = request_var('hidden_member_id', 0);
				if ($editmember->member_id == 0)
				{
					$editmember->member_id = request_var(URI_NAMEID, 0);
				}

				if ($editmember->member_id > 0)
				{
					// edit mode
					// build member array if clicked on name in listing
					if( $editmember->Get() == false)
					{
						trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
					}
					$S_ADD = false;
				}
				else
				{
					// add mode
					$S_ADD = true;
				}

				//guild dropdown
				$sql = 'SELECT a.id, a.name, a.realm, a.region
				FROM ' . GUILD_TABLE . ' a, ' . MEMBER_RANKS_TABLE . ' b
				where a.id = b.guild_id
				group by a.id, a.name, a.realm, a.region
				order by a.id desc';
				$result = $db->sql_query($sql);
				if ($editmember->member_id > 0)
				{
					while ($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('guild_row', array(
							'VALUE' => $row['id'] ,
							'SELECTED' => ($this->member['member_guild_id'] == $row['id']) ? ' selected="selected"' : '' ,
							'OPTION' => $row['name']));
					}
				}
				else
				{
					$i = 0;
					while ($row = $db->sql_fetchrow($result))
					{
						if ($i == 0)
						{
							$noguild_id = (int) $row['id'];
						}
						$template->assign_block_vars('guild_row', array(
							'VALUE' => $row['id'] ,
							'SELECTED' => '' ,
							'OPTION' => $row['name']));
						$i += 1;
					}
				}
				$db->sql_freeresult($result);

				// Rank drop-down -> for initial load
				// reloading is done from ajax to prevent redraw
				//
				// this only shows the VISIBLE RANKS
				// if you want to add someone to an unvisible rank make the rank visible first,
				// add him and then make rank invisible again.
				//
				if ($editmember->member_id <> 0)
				{
					$sql = 'SELECT rank_id, rank_name
					FROM ' . MEMBER_RANKS_TABLE . '
					WHERE rank_hide = 0
					AND rank_id < 90
					AND guild_id =	' . $editmember->member_guild_id . ' ORDER BY rank_id';
					$result = $db->sql_query($sql);
					while ($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('rank_row', array(
							'VALUE' => $row['rank_id'] ,
							'SELECTED' => ($editmember->member_guild_id == $row['rank_id']) ? ' selected="selected"' : '' ,
							'OPTION' => (! empty($row['rank_name'])) ? $row['rank_name'] : '(None)'));
					}
				}
				else
				{
					// no member is set, get the ranks from the highest numbered guild
					$sql = 'SELECT rank_id, rank_name
					FROM ' . MEMBER_RANKS_TABLE . '
					WHERE rank_hide = 0
					AND rank_id < 90
					AND guild_id = ' . $noguild_id . ' ORDER BY rank_id desc';
					$result = $db->sql_query($sql);
					while ($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('rank_row', array(
							'VALUE' => $row['rank_id'] ,
							'SELECTED' => '' ,
							'OPTION' => (! empty($row['rank_name'])) ? $row['rank_name'] : '(None)'));
					}
				}

				// phpbb User dropdown
				$phpbb_user_id = $editmember->member_id > 0  ? $editmember->phpbb_user_id : 0;
				$sql_array = array(
					'SELECT' => ' u.user_id, u.username ' ,
					'FROM' => array(
						USERS_TABLE => 'u') ,
					// exclude bots and guests, order by name -- ticket  129
					'WHERE' => " u.group_id != 6 and u.group_id != 1 " ,
					'ORDER_BY' => " u.username ASC");
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);
				$s_phpbb_user = '<option value="0"' . (($phpbb_user_id == 0) ? ' selected="selected"' : '') . '>--</option>';
				while ($row = $db->sql_fetchrow($result))
				{
					$selected = ($row['user_id'] == $phpbb_user_id) ? ' selected="selected"' : '';
					$s_phpbb_user .= '<option value="' . $row['user_id'] . '"' . $selected . '>' . $row['username'] . '</option>';
				}

				// Game dropdown
				// list installed games
				$installed_games = array();
				foreach ($this->games as $gameid => $gamename)
				{
					//add value to dropdown when the game config value is 1
					if ($config['bbdkp_games_' . $gameid] == 1)
					{
						$template->assign_block_vars('game_row', array(
							'VALUE' => $gameid ,
							'SELECTED' => ($this->member['game_id'] == $gameid) ? ' selected="selected"' : '' ,
							'OPTION' => $gamename));
						$installed_games[] = $gameid;
					}
				}

				//
				// Race dropdown
				// reloading is done from ajax to prevent redraw
				$gamepreset = ( $editmember->member_id > 0  ? $editmember->game_id : $installed_games[0]);
				$sql_array = array(
					'SELECT' => '  r.race_id, l.name as race_name ' ,
					'FROM' => array(
						RACE_TABLE => 'r' ,
						BB_LANGUAGE => 'l') ,
					'WHERE' => " r.race_id = l.attribute_id
								AND r.game_id = '" . $gamepreset . "'
								AND l.attribute='race'
								AND l.game_id = r.game_id
								AND l.language= '" . $config['bbdkp_lang'] . "'");
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);
				if ($editmember->member_id > 0)
				{
					while ($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('race_row', array(
							'VALUE' => $row['race_id'] ,
							'SELECTED' => ($editmember->member_race_id == $row['race_id']) ? ' selected="selected"' : '' ,
							'OPTION' => (! empty($row['race_name'])) ? $row['race_name'] : '(None)'));
					}
				}
				else
				{
					while ($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('race_row', array(
							'VALUE' => $row['race_id'] ,
							'SELECTED' => '' ,
							'OPTION' => (! empty($row['race_name'])) ? $row['race_name'] : '(None)'));
					}
				}
				$db->sql_freeresult($result);


				//
				// Class dropdown
				// reloading is done from ajax to prevent redraw
				$sql_array = array(
					'SELECT' => ' c.class_id, l.name as class_name, c.class_hide,
									  c.class_min_level, class_max_level, c.class_armor_type , c.imagename ' ,
					'FROM' => array(
						CLASS_TABLE => 'c' ,
						BB_LANGUAGE => 'l') ,
					'WHERE' => " l.game_id = c.game_id  AND c.game_id = '" . $gamepreset . "'
					AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ");
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['class_min_level'] <= 1)
					{
						$option = (! empty($row['class_name'])) ? $row['class_name'] . "
						 Level (" . $row['class_min_level'] . " - " . $row['class_max_level'] . ")" : '(None)';
					}
					else
					{
						$option = (! empty($row['class_name'])) ? $row['class_name'] . "
						 Level " . $row['class_min_level'] . "+" : '(None)';
					}
					if ($editmember->member_id <> 0)
					{
						$template->assign_block_vars('class_row', array(
							'VALUE' => $row['class_id'] ,
							'SELECTED' => ($editmember->member_class_id == $row['class_id']) ? ' selected="selected"' : '' ,
							'OPTION' => $option));
					}
					else
					{
						$template->assign_block_vars('class_row', array(
							'VALUE' => $row['class_id'] ,
							'SELECTED' => '' ,
							'OPTION' => $option));
					}
				}
				$db->sql_freeresult($result);

				// set the genderdefault to male if a new form is opened, otherwise take rowdata.
				$genderid = $editmember->member_id > 0 ? $editmember->member_gender_id : '0';


				// build presets for joindate pulldowns
				$now = getdate();
				$s_memberjoin_day_options = '<option value="0"	>--</option>';
				for ($i = 1; $i < 32; $i ++)
				{
					$day = $editmember->member_id > 0 ? $editmember->member_joindate_d : $now['mday'];
					$selected = ($i == $day) ? ' selected="selected"' : '';
					$s_memberjoin_day_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				$s_memberjoin_month_options = '<option value="0">--</option>';
				for ($i = 1; $i < 13; $i ++)
				{
					$month = $editmember->member_id > 0 ? $editmember->member_joindate_mo : $now['mon'];
					$selected = ($i == $month) ? ' selected="selected"' : '';
					$s_memberjoin_month_options .= "<option value=\"$i\"$selected>$i</option>";
				}
				$s_memberjoin_year_options = '<option value="0">--</option>';
				for ($i = $now['year'] - 10; $i <= $now['year']; $i ++)
				{
					$yr = $editmember->member_id > 0 ? $editmember->member_joindate_y : $now['year'];
					$selected = ($i == $yr) ? ' selected="selected"' : '';
					$s_memberjoin_year_options .= "<option value=\"$i\"$selected>$i</option>";
				}


				// build presets for outdate pulldowns
				$s_memberout_day_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';
				for ($i = 1; $i < 32; $i ++)
				{
					if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
					{
						$day = $editmember->member_outdate_d;
						$selected = ($i == $day) ? ' selected="selected"' : '';
					}
					else
					{
						$selected = '';
					}
					$s_memberout_day_options .= "<option value=\"$i\"$selected>$i</option>";
				}


				$s_memberout_month_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';

				for ($i = 1; $i < 13; $i ++)
				{
					if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
					{
						$month = $editmember->member_outdate_mo;
						$selected = ($i == $month) ? ' selected="selected"' : '';
					}
					else
					{
						$selected = '';
					}
					$s_memberout_month_options .= "<option value=\"$i\"$selected>$i</option>";
				}

				$s_memberout_year_options = '<option value="0"' . ($editmember->member_id > 0 ? (($editmember->member_outdate != 0) ? '' : ' selected="selected"') : ' selected="selected"') . '>--</option>';

				for ($i = $now['year'] - 10; $i <= $now['year'] + 10; $i ++)
				{
					if ($editmember->member_id > 0 && $editmember->member_outdate != 0)
					{
						$yr = $editmember->member_outdate_y;
						$selected = ($i == $yr) ? ' selected="selected"' : '';
					}
					else
					{
						$selected = '';
					}
					$s_memberout_year_options .= "<option value=\"$i\"$selected>$i</option>";
				}

				unset($now);

				$form_key = 'mm_addmember';
				add_form_key($form_key);
				$template->assign_vars(array(
					'L_TITLE' => $user->lang['ACP_MM_ADDMEMBER'] ,
					'L_EXPLAIN' => $user->lang['ACP_MM_ADDMEMBER_EXPLAIN'] ,
					'F_ADD_MEMBER' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addmember&amp;") ,
					'STATUS' => $editmember->member_id > 0 ? (($editmember->member_status == 1) ? 'checked="checked" ' : '') : 'checked="checked" ' ,
					'MEMBER_NAME' => $editmember->member_id > 0 ? $editmember->member_name : '' ,
					'MEMBER_ID' => $editmember->member_id > 0 ? $editmember->member_id : '' ,
					'MEMBER_LEVEL' => $editmember->member_id > 0 ? $editmember->member_level : '' ,
					'MALE_CHECKED' => ($genderid == '0') ? ' checked="checked"' : '' ,
					'FEMALE_CHECKED' => ($genderid == '1') ? ' checked="checked"' : '' ,
					'MEMBER_COMMENT' => $editmember->member_id > 0 ? $editmember->member_comment : '' ,
					'S_CAN_HAVE_ARMORY' => $editmember->member_id > 0 ? ($editmember->game_id == 'wow' || $editmember->game_id == 'aion' ? true : false) : false ,
					'MEMBER_URL' => $editmember->member_id > 0 ? $editmember->member_armory_url : '' ,
					'MEMBER_PORTRAIT' => $editmember->member_id > 0 ? $editmember->member_portrait_url : '' ,
					'S_MEMBER_PORTRAIT_EXISTS' => (strlen($editmember->member_portrait_url) > 1) ? true : false ,
					'S_CAN_GENERATE_ARMORY' => $editmember->member_id > 0 ? ($editmember->game_id == 'wow' ? true : false) : false ,
					'COLORCODE' => ($this->member['colorcode'] == '') ? '#123456' : $editmember->colorcode ,
					'CLASS_IMAGE' => $editmember->class_image ,
					'S_CLASS_IMAGE_EXISTS' => (strlen($editmember->class_image) > 1) ? true : false ,
					'RACE_IMAGE' => $editmember->race_image ,
					'S_RACE_IMAGE_EXISTS' => (strlen($editmember->race_image) > 1) ? true : false ,
					'S_JOINDATE_DAY_OPTIONS' => $s_memberjoin_day_options ,
					'S_JOINDATE_MONTH_OPTIONS' => $s_memberjoin_month_options ,
					'S_JOINDATE_YEAR_OPTIONS' => $s_memberjoin_year_options ,
					'S_OUTDATE_DAY_OPTIONS' => $s_memberout_day_options ,
					'S_OUTDATE_MONTH_OPTIONS' => $s_memberout_month_options ,
					'S_OUTDATE_YEAR_OPTIONS' => $s_memberout_year_options ,
					'S_PHPBBUSER_OPTIONS' => $s_phpbb_user ,
					// javascript
					'LA_ALERT_AJAX' => $user->lang['ALERT_AJAX'] ,
					'LA_ALERT_OLDBROWSER' => $user->lang['ALERT_OLDBROWSER'] ,
					'LA_MSG_NAME_EMPTY' => $user->lang['FV_REQUIRED_NAME'] ,
					'UA_FINDRANK' => append_sid($phpbb_admin_path . "style/dkp/findrank.$phpEx") ,
					'UA_FINDCLASSRACE' => append_sid($phpbb_admin_path . "style/dkp/findclassrace.$phpEx") ,
					'S_ADD' => $S_ADD));
				$this->page_title = 'ACP_MM_ADDMEMBER';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;

		    /**
			 * ranks setup
		     */
			case 'mm_ranks':

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_ranks") . '"><h3>'. $user->lang['RETURN_RANK']. '</h3></a>';

				$sql = 'SELECT max(id) as idmax FROM ' . GUILD_TABLE;
				$result = $db->sql_query($sql);
				$maxguildid = (int) $db->sql_fetchfield('idmax');
				$db->sql_freeresult($result);


				$update = (isset($_POST['update'])) ? true : false;
				$deleterank = (isset($_GET['deleterank'])) ? true : false;
				$add = (isset($_POST['add'])) ? true : false;

				if ($add || $update)
				{
					if (! check_form_key('mm_ranks'))
					{
						trigger_error('FORM_INVALID');
					}
				}

				if ($add)
				{
				    $newrank = new includes\bbdkp\Ranks();
					$newrank->RankName = utf8_normalize_nfc(request_var('nrankname', '', true));
					$newrank->RankId = request_var('nrankid', 0);
					$newrank->GuildId = request_var('guild_id', $maxguildid);
					$newrank->RankHide = (isset($_POST['nhide'])) ? 1 : 0;
					$newrank->RankPrefix = utf8_normalize_nfc(request_var('nprefix', '', true));
					$newrank->RankSuffix = utf8_normalize_nfc(request_var('nsuffix', '', true));
					$newrank->Make();

					$success_message = $user->lang['ADMIN_RANKS_ADDED_SUCCESS'];
					trigger_error($success_message . $this->link);
				}

				if ($update)
				{
					$newrank = new includes\bbdkp\Ranks();
					$oldrank = new includes\bbdkp\Ranks();
					// template
					$modrank = utf8_normalize_nfc(request_var('ranks', array(0 => ''), true));
					foreach ($modrank as $rank_id => $rank_name)
					{
					    $oldrank->Rankid = $rank_id;
					    $oldrank->GuildId = request_var('guild_id', $maxguildid);
					    $oldrank->Get();

					    $newrank->Rankid = $rank_id;
					    $newrank->GuildId = $oldrank->GuildId;
					    $newrank->RankName = $rank_name;
					    $newrank->RankHide = (isset($_POST['hide'][$rank_id])) ? 1 : 0;

						$rank_prefix = utf8_normalize_nfc(request_var('prefix', array(
							(int) $rank_id => ''), true));
						$newrank->RankPrefix = $rank_prefix[$rank_id];

						$rank_suffix = utf8_normalize_nfc(request_var('suffix', array(
							(int) $rank_id => ''), true));
					    $newrank->RankSuffix = $rank_suffix[$rank_id];

						// compare old with new,
						if ($old_rank != $newrank)
						{
						    $newrank->Update($old_rank);
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
						$rank_id = request_var('ranktodelete', 'x');
						$guild_id = request_var('guild_id', 'x');
						// delete the rank only if there are no members left
						$sql = 'SELECT count(*) as countm FROM ' . MEMBER_LIST_TABLE . '
						where member_rank_id = ' . $rank_id . ' and member_guild_id = ' . $guild_id;
						$result = $db->sql_query($sql);
						$countm = $db->sql_fetchfield('countm');
						$db->sql_freeresult($result);
						if ($countm != 0)
						{
							trigger_error($user->lang['ERROR_RANKMEMBERS'] . $this->link, E_USER_WARNING);
						}
						$sql = "select a.rank_name, b.name  from " . MEMBER_RANKS_TABLE . ' a , ' . GUILD_TABLE . ' b
						where a.guild_id = b.id and a.rank_id = ' . $rank_id . ' and b.id = ' . $guild_id;
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
							'hidden_guild_id' => $guild_id ,
							'hidden_guild_name' => $guild_name ,
							'hidden_rank_name' => $old_rank_name));
						confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_RANKS'], $old_rank_name, $guild_name), $s_hidden_fields);
					}
				}


				// template filling
				$sql = 'SELECT id, name FROM ' . GUILD_TABLE . ' ORDER BY id desc';
				$resultg = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($resultg))
				{
					$template->assign_block_vars('guild_row', array(
						'VALUE' => $row['id'] ,
						'SELECTED' => ($row['id'] == $guild_id) ? ' selected="selected"' : '' ,
						'OPTION' => $row['name']));
				}
				$db->sql_freeresult($resultg);

				// rank 99 is the out-rank
				$sql = 'SELECT rank_id, rank_name, rank_hide, rank_prefix, rank_suffix, guild_id FROM ' . MEMBER_RANKS_TABLE . '
	        		WHERE guild_id = ' . $guild_id . '
	        		ORDER BY rank_id, rank_hide  ASC ';

				$result = $db->sql_query($sql);
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
						'S_READONLY' => ($row['rank_id'] == 90 || $row['rank_id'] == 99) ? true : false ,
						'U_DELETE_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_ranks&amp;deleterank=1&amp;ranktodelete=" . $row['rank_id'] . "&amp;guild_id=" . $guild_id)));
				}
				$db->sql_freeresult($result);
				$form_key = 'mm_ranks';
				add_form_key($form_key);
				$template->assign_vars(array(
					'F_EDIT_RANKS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_ranks") ,
					'GUILD_ID' => $guild_id));
				$this->page_title = 'ACP_MM_RANKS';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;


			/***************************************/
			// List Guilds
			/***************************************/
			case 'mm_listguilds':

				if (!class_exists('Guild'))
				{
					require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
				}

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listguilds") . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
				$showadd = (isset($_POST['guildadd'])) ? true : false;

				if ($showadd)
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addguild"));
					break;
				}
				$sort_order = array(
					0 => array(	'id' , 'id desc') ,
					1 => array('name' , 'name desc') ,
					2 => array('realm desc' , 'realm desc') ,
					3 => array('region' , 'region desc') ,
					4 => array('roster' , 'roster desc'));

				$current_order = switch_order($sort_order);
				$guild_count = 0;
				$previous_data = '';
				$sort_index = explode('.', $current_order['uri']['current']);
				$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
				$show_all = ((isset($_GET['show'])) && request_var('show', '') == 'all') ? true : false;

				// we select only guilds with id greater than zero
				$sql = 'SELECT id, name, realm, region, roster FROM ' . GUILD_TABLE . ' where id > 0  ORDER BY ' . $current_order['sql'];
				if (! ($guild_result = $db->sql_query($sql)))
				{
					trigger_error($user->lang['ERROR_GUILDNOTFOUND'], E_USER_WARNING);
				}
				$lines = 0;
				while ($row = $db->sql_fetchrow($guild_result))
				{
					$guild_count ++;
					$listguild = new includes\bbdkp\Guild($row['id']);

					$template->assign_block_vars('guild_row', array(
						'ID' => $listguild->guildid ,
						'NAME' => $listguild->name ,
						'REALM' => $listguild->realm ,
						'REGION' => $listguild->region ,
						'MEMBERCOUNT' => $listguild->membercount ,
						'SHOW_ROSTER' => ($listguild->showroster == 1 ? 'yes' : 'no') ,
						'U_VIEW_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_addguild&amp;" . URI_GUILD . '=' . $listguild->guildid)));
					$previous_data = $row[$previous_source];
				}

				$form_key = 'mm_listguilds';
				add_form_key($form_key);
				$template->assign_vars(array(
					'F_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm") . '&amp;mode=mm_addguild' ,
					'L_TITLE' => $user->lang['ACP_MM_LISTGUILDS'] ,
					'L_EXPLAIN' => $user->lang['ACP_MM_LISTGUILDS_EXPLAIN'] ,
					'BUTTON_VALUE' => $user->lang['DELETE_SELECTED_GUILDS'] ,
					'O_ID' => $current_order['uri'][0] ,
					'O_NAME' => $current_order['uri'][1] ,
					'O_REALM' => $current_order['uri'][2] ,
					'O_REGION' => $current_order['uri'][3] ,
					'O_ROSTER' => $current_order['uri'][4] ,
					'U_LIST_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listguilds") ,
					'GUILDMEMBERS_FOOTCOUNT' => sprintf($user->lang['GUILD_FOOTCOUNT'], $guild_count)));
				$this->page_title = 'ACP_MM_LISTGUILDS';
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;

			/*************************************
			 * ************ Add Guild ************
			 *************************************/
			case 'mm_addguild':

				if (!class_exists('Guild'))
				{
					require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
				}

				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_mm&amp;mode=mm_listguilds") . '"><h3>'.$user->lang['RETURN_GUILDLIST'].'</h3></a>';
				/* select data */
				$update = false;

				if (isset($_GET[URI_GUILD]))
				{
					$this->url_id = request_var(URI_GUILD, 0);
				}

				$updateguild = new includes\bbdkp\Guild($this->url_id);

				if ($updateguild->guildid != 0)
				{
					// we have a GET
					$update = true;

						foreach ($updateguild->regionlist as $key => $value)
						{
							$template->assign_block_vars('region_row', array(
								'VALUE' => $value ,
								'SELECTED' => ($updateguild->region == $key) ? ' selected="selected"' : '' ,
								'OPTION' => (! empty($key)) ? $key : '(None)'));
						}
				}
				else
				{
					// NEW PAGE
					foreach ($updateguild->regionlist as $key => $value)
					{
						$template->assign_block_vars('region_row', array(
							'VALUE' => $value ,
							'SELECTED' => '' ,
							'OPTION' => (! empty($key)) ? $key : '(None)'));
					}
				}

				$add = (isset($_POST['add'])) ? true : false;
				$submit = (isset($_POST['update'])) ? true : false;
				$delete = (isset($_POST['delete'])) ? true : false;
				if ($add || $submit)
				{
					if (! check_form_key('addguild'))
					{
						trigger_error('FORM_INVALID');
					}
				}

				if ($add)
				{
					$updateguild->name = utf8_normalize_nfc(request_var('guild_name', '', true));
					$updateguild->realm = utf8_normalize_nfc(request_var('realm', '', true));
					$updateguild->region = request_var('region_id', '');
					$updateguild->showroster = (isset($_POST['showroster'])) ? true : false;
					$updateguild->Make();

					if ($updateguild->Make() > 0)
					{
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
				if ($submit)
				{
					if($updateguild->guildid == 0)
					{
						trigger_error($user->lang['ERROR_INVALID_GUILD_PROVIDED'], E_USER_WARNING);
					}

					$updateguild->guildid = $this->url_id;
					$updateguild->Get();
					$old_guild = $updateguild;

					$updateguild->name = utf8_normalize_nfc(request_var('guild_name', ' ', true));
					$updateguild->realm = utf8_normalize_nfc(request_var('realm', ' ', true));
					$updateguild->region = request_var('region_id', ' ');
					$updateguild->showroster = request_var('showroster', 0);
					//@todo complete for other games
					$updateguild->aionlegionid = 0;
					$updateguild->aionserverid = 0;
					$updateguild->Update($old_guild);

					$success_message = sprintf($user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $this->url_id);
					trigger_error($success_message . $this->link);
				}

				if ($delete)
				{
					if (confirm_box(true))
					{
						$deleteguild = new includes\bbdkp\Guild(request_var('guild_id', 0));
						$deleteguild->Get();
						$deleteguild->Delete();
						$success_message = sprintf($user->lang['ADMIN_DELETE_GUILD_SUCCESS'], $deleteguild->guild_id);
						trigger_error($success_message . adm_back_link($this->u_action), E_USER_NOTICE);
					}
					else
					{
						$s_hidden_fields = build_hidden_fields(array(
							'delete' => true ,
							'guild_id' => $updateguild->guildid));
						$template->assign_vars(array(
							'S_HIDDEN_FIELDS' => $s_hidden_fields));
						confirm_box(false, $user->lang['CONFIRM_DELETE_GUILD'], $s_hidden_fields);
					}
				}

				$form_key = 'addguild';
				add_form_key($form_key);

				$template->assign_vars(array(
					// Form values
					'GUILD_ID' => $updateguild->guildid ,
					'GUILD_NAME' => $updateguild->name ,
					'REALM' => $updateguild->realm  ,
					'REGION' => $updateguild->region,
					'MEMBERCOUNT' => $updateguild->membercount ,
					'SHOW_ROSTER' => ($updateguild->showroster == 1) ? 'checked="checked"' : '',
					// Language
					'L_TITLE' => $user->lang['ACP_MM_ADDGUILD'] ,
					'L_EXPLAIN' => $user->lang['ACP_MM_ADDGUILD_EXPLAIN'] ,
					'L_ADD_GUILD_TITLE' => (! $this->url_id) ? $user->lang['ADD_GUILD'] : $user->lang['EDIT_GUILD'] ,
					// Javascript messages
					'MSG_NAME_EMPTY' => $user->lang['FV_REQUIRED_NAME'] ,
					'S_ADD' => (! $this->url_id) ? true : false));
				$this->page_title = $user->lang['ACP_MM_ADDGUILD'];
				$this->tpl_name = 'dkp/acp_' . $mode;
				break;

			default:
				$this->page_title = 'ACP_DKP_MAINPAGE';
				$this->tpl_name = 'dkp/acp_mainpage';
				$success_message = 'Error';
				trigger_error($success_message . $this->link, E_USER_WARNING);
		}
	}

	/**
	 * function to batch delete members, called from listing
	 *
	 * @param array $members_to_delete
	 */
	public function member_batch_delete ($members_to_delete)
	{
		global $db, $user;

		if (! is_array($members_to_delete))
		{
			return;
		}

		if (sizeof($members_to_delete) == 0)
		{
			return;
		}

		if (confirm_box(true))
		{
			// recall hidden vars
			$members_to_delete = request_var('delete_id', array(0 => 0));
			$member_names = utf8_normalize_nfc(request_var('members', array(0 => ' '), true));
			foreach ($members_to_delete as $memberid)
			{
				$delmember = new includes\bbdkp\Members();
				$delmember->member_id = $memberid;
				$delmember->get();
				$delmember->Delete();
				unset($delmember);
			}
			$str_members = implode($member_names, ',');
			$success_message = sprintf($user->lang['ADMIN_DELETE_MEMBERS_SUCCESS'], $str_members);
			trigger_error($success_message . $this->link, E_USER_NOTICE);
		}
		else
		{
			$sql = "SELECT member_name, member_id FROM " . MEMBER_LIST_TABLE . " WHERE " . $db->sql_in_set('member_id', array_keys($members_to_delete));
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$member_names[] = $row['member_name'];
			}
			$db->sql_freeresult($result);
			$s_hidden_fields = build_hidden_fields(array(
				'delete' => true ,
				'delete_id' => $members_to_delete ,
				'members' => $member_names));
			$str_members = implode($member_names, ',');
			confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_MEMBER'], $str_members), $s_hidden_fields);
		}
	}





	}
?>
