<?php
/**
 * achievement acp file
 *
 * @package   bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @author    Sajaki
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace bbdkp\bbguild\acp;

use bbdkp\bbguild\model\admin\admin;
use bbdkp\bbguild\model\player\guilds;
use bbdkp\bbguild\model\games\rpg\achievement;

/**
 * This class manages player general info
 * @todo finish this module
 * @package bbdkp\bbguild\acp
 */
class achievement_module extends admin
{
	/**
	 * trigger link
	 *
	 * @var string
	 */
	public $link = '';

	protected $phpbb_container;
	/**
	 * @var \phpbb\request\request
	 **/
	protected $request;
	/**
	 * @var \phpbb\template\template
	 **/
	protected $template;
	/**
	 * @var \phpbb\user
	 **/
	protected $user;
	/**
	 * @var \phpbb\db\driver\driver_interface
	 */
	protected $db;

	public $id;
	public $mode;
	public $auth;

	public $achievement;

	/**
	 * @param $id
	 * @param $mode
	 */
	public function main($id, $mode)
	{
		global $user, $db, $template, $phpbb_admin_path, $phpEx;
		global $request, $phpbb_container, $auth;

		$this->id = $id;
		$this->mode = $mode;
		$this->request=$request;
		$this->template=$template;
		$this->user=$user;
		$this->db=$db;
		$this->phpbb_container = $phpbb_container;
		$this->auth=$auth;
		parent::__construct();

		$form_key = 'bbdkp/bbguild';
		add_form_key($form_key);
		$this->tpl_name   = 'acp_' . $mode;

		if (! $this->auth->acl_get('a_bbguild'))
		{
			trigger_error($user->lang['NOAUTH_A_PLAYERS_MAN']);
		}

		switch ($mode)
		{
			/**
			 * List achievement for this guild
			 */
			case 'listachievements':
				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-achievement_module&amp;mode=listachievements') . '"><h3>Return to Index</h3></a>';
				$Guild = new guilds();

				$guildlist = $Guild->guildlist(1);
				if (count((array) $guildlist) == 0  )
				{
					trigger_error('ERROR_NOGUILD', E_USER_WARNING);
				}

				if (count((array) $guildlist) == 1 )
				{
					$Guild->setGuildid($guildlist[0]['id']);
					$Guild->setName($guildlist[0]['name']);
					if ($Guild->getGuildid() == 0 && $Guild->getName() == 'Guildless' )
					{
						trigger_error('ERROR_NOGUILD', E_USER_WARNING);
					}
				}

				foreach ($guildlist as $g)
				{
					$Guild->setGuildid($g['id']);
					break;
				}

				// batch delete
				$del_batch = $this->request->is_set_post('delete');
				if ($del_batch)
				{
					$this->achievement_batch_delete();
				}

				// guild dropdown query
				$getguild_dropdown = $this->request->is_set_post('player_guild_id');
				if ($getguild_dropdown)
				{
					// user selected dropdown - get guildid
					$Guild->setGuildid($this->request->variable('player_guild_id', 0));
				}

				$sortlink = isset($_GET[URI_GUILD])  ? true : false;
				if ($sortlink)
				{
					// user selected dropdown - get guildid
					$Guild->setGuildid($this->request->variable(URI_GUILD, 0));
				}

				$Guild->get_guild();

				$this->achievement = new achievement($Guild->getGameId(),0);

				// add achievement button redirect
				$showadd = $this->request->is_set_post('achievementadd');
				if ($showadd)
				{
					$a = $this->request->variable('achievement_guild_id', $this->request->variable('hidden_guildid', 0));
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-achievement_module&amp;mode=addachievement&amp;guild_id=' . $a));
					break;
				}

				// pageloading
				$this->BuildTemplateListAchievements($mode, $Guild);
				break;

			/***************************************/
			// add achievement
			/***************************************/
			case 'addachievement' :
				$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-achievement_module&amp;mode=listachievements') . '"><h3>' . $this->user->lang['RETURN_PLAYERLIST'] . '</h3></a>';

				$add = $this->request->is_set_post('add');
				$update = $this->request->is_set_post('update');
				$delete = $this->request->variable('delete', '')  != '' ? true : false;

				if ($add || $update)
				{
					if (! check_form_key('bbdkp/bbguild'))
					{
						trigger_error('FORM_INVALID');
					}
				}

				if ($add)
				{
					$this->Addplayer();

				}

				if ($update)
				{
					$this->UpdatePlayer();
				}

				if ($delete)
				{
					if (confirm_box(true))
					{
						$deleteplayer = $this->DeletePlayer();
					}
					else
					{
						$deleteplayer = new player();
						$deleteplayer->player_id = $this->request->variable('player_id', 0);
						$deleteplayer->Getplayer();
						$s_hidden_fields = build_hidden_fields(
							array(
								'delete' => true ,
								'del_player_id' => $deleteplayer->player_id)
						);

						confirm_box(false, sprintf($this->user->lang['CONFIRM_DELETE_PLAYER'], $deleteplayer->player_name), $s_hidden_fields);
					}
					unset($deleteplayer);
				}

				$this->BuildTemplateAddEditplayers($mode);
				break;

			default:
				$this->page_title = 'ACP_LISTACHIEV';
				$success_message = $this->user->lang['L_ERROR'];
				trigger_error($success_message . $this->link, E_USER_WARNING);
		}
	}


	/**
	 * List achievements
	 *
	 * @param $mode
	 * @param guilds $Guild
	 */
	private function BuildTemplateListAchievements($mode, guilds $Guild)
	{
		global  $config, $phpbb_admin_path, $phpEx;

		// fill popup and set selected to default selection

		$guildlist = $Guild->guildlist(0);
		foreach ($guildlist as $g)
		{
			$this->template->assign_block_vars(
				'guild_row', array(
					'VALUE'    => $g['id'],
					'SELECTED' => ($g['id'] == $Guild->getGuildid()) ? ' selected="selected"' : '',
					'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)')
			);
		}

		$previous_data = '';
		//get window
		$start    = $this->request->variable('start', 0, false);

		$sort_order = array(
			0 => array('achievement_id', 'achievement_id desc'),
			1 => array('title', 'title desc'),
			2 => array('description', 'description desc'),
			3 => array('points', 'points desc'),
		);

		$current_order   = $this->switch_order($sort_order);
		$sort_index      = explode('.', $current_order['uri']['current']);
		$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);
		$show_all        = ((isset($_GET['show'])) && $this->request->variable('show', '') == 'all') ? true : false;

		$result       = $Guild->list_players($current_order['sql'], 0, 0, $minlevel, $maxlevel, $selectactive, $selectnonactive, $player_filter);
		$player_count = 0;

		while ($row = $this->db->sql_fetchrow($result))
		{
			$player_count += 1;
		}
		if (!($result))
		{
			trigger_error($this->user->lang['ERROR_PLAYERNOTFOUND'], E_USER_WARNING);
		}
		$this->db->sql_freeresult($result);
		$players_result = $Guild->list_players($current_order['sql'], $start, 1, $minlevel, $maxlevel, $selectactive, $selectnonactive, $player_filter);
		$lines          = 0;
		while ($row = $this->db->sql_fetchrow($players_result))
		{
			$phpbb_user_id = (int) $row['phpbb_user_id'];
			$race_image    = (string) (($row['player_gender_id'] == 0) ? $row['image_male'] : $row['image_female']);
			$lines += 1;
			$this->template->assign_block_vars(
				'players_row', array(
					'S_READONLY'           => ($row['rank_id'] == 90 || $row['rank_id'] == 99) ? true : false,
					'STATUS'               => ($row['player_status'] == 1) ? 'checked="checked" ' : '',
					'ID'                   => $row['player_id'],
					'COUNT'                => $player_count,
					'NAME'                 => $row['rank_prefix'] . $row['player_name'] . $row['rank_suffix'],
					'USERNAME'             => get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
					'RANK'                 => $row['rank_name'],
					'LEVEL'                => ($row['player_level'] > 0) ? $row['player_level'] : '&nbsp;',
					'ARMOR'                => (!empty($row['armor_type'])) ? $row['armor_type'] : '&nbsp;',
					'COLORCODE'            => ($row['colorcode'] == '') ? '#254689' : $row['colorcode'],
					'CLASS_IMAGE'          => (strlen($row['imagename']) > 1) ? $this->ext_path . 'images/class_images/' . $row['imagename'] . '.png' : '',
					'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
					'RACE_IMAGE'           => (strlen($race_image) > 1) ? $this->ext_path . 'images/race_images/' . $race_image . '.png' : '',
					'S_RACE_IMAGE_EXISTS'  => (strlen($race_image) > 1) ? true : false,
					'CLASS'                => ($row['player_class'] != 'NULL') ? $row['player_class'] : '&nbsp;',
					'LAST_UPDATE'          => ($row['last_update'] == 0) ? '' : date($config['bbguild_date_format'] . ' H:i:s', $row['last_update']),
					'U_VIEW_USER'          => append_sid("{$phpbb_admin_path}index.$phpEx", "i=users&amp;icat=13&amp;mode=overview&amp;u=$phpbb_user_id"),
					'U_VIEW_PLAYER'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=addplayer&amp;' . URI_NAMEID . '=' . $row['player_id']),
					'U_DELETE_PLAYER'      => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=addplayer&amp;delete=1&amp;' . URI_NAMEID . '=' . $row['player_id']))
			);
			$previous_data = $row[$previous_source];
		}
		$this->db->sql_freeresult($players_result);

		$footcount_text   = sprintf($this->user->lang['LISTPLAYERS_FOOTCOUNT'], $player_count);

		$playerpagination = $this->phpbb_container->get('pagination');

		$pagination_url = append_sid(
			"{$phpbb_admin_path}index.$phpEx",
			'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri']['current'] .
			'&amp;' . URI_GUILD . '=' . $Guild->getGuildid() .
			'&amp;minlevel=' . $minlevel .
			'&amp;maxlevel=' . $maxlevel .
			'&amp;active=' . $selectactive .
			'&amp;nonactive=' . $selectnonactive
		);

		$playerpagination->generate_template_pagination($pagination_url, 'pagination', 'start', $player_count, $config['bbguild_user_llimit'], $start, true);

		$this->template->assign_vars(
			array(
				'F_SELECTACTIVE'        => $selectactive,
				'F_SELECTNONACTIVE'     => $selectnonactive,
				'GUILDID'               => $Guild->getGuildid(),
				'GUILDNAME'             => $Guild->getName(),
				'MINLEVEL'              => $minlevel,
				'MAXLEVEL'              => $maxlevel,
				'START'                 => $start,
				'PLAYER_NAME'           => $player_filter,
				'F_PLAYERS'             => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module') . '&amp;mode=addplayer',
				'F_PLAYERS_LIST'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module') . '&amp;mode=listplayers',
				'L_TITLE'               => $this->user->lang['ACP_LISTACHIEV'],
				'L_EXPLAIN'             => $this->user->lang['ACP_MM_LISTPLAYERS_EXPLAIN'],
				'O_NAME'                => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][0] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_USERNAME'            => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][1] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_LEVEL'               => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][2] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_CLASS'               => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][3] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_RANK'                => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][4] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_LAST_UPDATE'         => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][5] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'O_ID'                  => append_sid(
					"{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=list_players&amp;o=' . $current_order['uri'][7] . '&amp;' . URI_GUILD . '=' . $Guild->getGuildid() . '&amp;minlevel=' . $minlevel .
					'&amp;maxlevel=' . $maxlevel .
					'&amp;active=' . $selectactive .
					'&amp;nonactive=' . $selectnonactive
				),
				'U_LIST_PLAYERS'        => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-player_module&amp;mode=listplayers&amp;'),
				'LISTPLAYERS_FOOTCOUNT' => $footcount_text,
				'U_VIEW_GUILD'          => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-bbdkp-bbguild-acp-guild_module&amp;mode=editguild&amp;action=editguild&amp;' . URI_GUILD . '=' . $Guild->getGuildid()),
				'S_WOW'                 => ($Guild->getGameId() == 'wow') ? true : false,
				'PAGE_NUMBER'           => $playerpagination->on_page($player_count, $config['bbguild_user_llimit'], $start),
				'GUILD_EMBLEM'          => $Guild->getEmblempath(),
				'GUILD_NAME'            => $Guild->getName(),
			)
		);
		$this->page_title = 'ACP_BBGUILD_PLAYER_LIST';

	}

	/**
	 * function to batch delete players, called from listing
	 */
	private function achievement_batch_delete()
	{
		$players_to_delete = $this->request->variable('delete_id', array(0));

		if (! is_array($players_to_delete))
		{
			return;
		}

		if (count($players_to_delete) == 0)
		{
			return;
		}

		if (confirm_box(true))
		{
			// recall hidden vars
			$players_to_delete = $this->request->variable('delete_id', array(0 => 0));
			$player_names = $this->request->variable('players', array(0 => ''), true);
			foreach ($players_to_delete as $playerid => $value)
			{
				$delplayer = new player();
				$delplayer->player_id = $playerid;
				$delplayer->Getplayer();
				$delplayer->Deleteplayer();
				unset($delplayer);
			}
			$str_players = implode($player_names, ',');
			$success_message = sprintf($this->user->lang['ADMIN_DELETE_PLAYERS_SUCCESS'], $str_players);
			trigger_error($success_message . $this->link, E_USER_NOTICE);
		}
		else
		{
			$sql = 'SELECT player_name, player_id FROM ' . PLAYER_LIST_TABLE . ' WHERE ' . $this->db->sql_in_set('player_id', array_keys($players_to_delete));
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$player_names[] = $row['player_name'];
			}
			$this->db->sql_freeresult($result);
			$s_hidden_fields = build_hidden_fields(
				array(
					'delete' => true ,
					'delete_id' => $players_to_delete ,
					'players' => $player_names)
			);
			$str_players = implode($player_names, ', ');

			confirm_box(false, sprintf($this->user->lang['CONFIRM_DELETE_PLAYER'], $str_players), $s_hidden_fields);
		}
	}



}
