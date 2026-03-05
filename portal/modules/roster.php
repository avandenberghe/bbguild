<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Roster portal module.
 * Displays the guild roster as a table with filters and pagination.
 */

namespace avathar\bbguild\portal\modules;

use avathar\bbguild\model\player\player;
use avathar\bbguild\model\games\game_registry;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\pagination;
use phpbb\path_helper;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;

class roster extends module_base
{
	protected int $columns = 4; // center only
	protected string $name = 'BBGUILD_PORTAL_ROSTER';
	protected string $image_src = '';

	protected driver_interface $db;
	protected template $template;
	protected user $user;
	protected config $config;
	protected pagination $pagination;
	protected request $request;
	protected path_helper $path_helper;
	protected game_registry $game_registry;
	protected string $players_table;
	protected string $ranks_table;
	protected string $classes_table;
	protected string $races_table;
	protected string $language_table;
	protected string $guild_table;
	protected string $factions_table;

	public function __construct(
		driver_interface $db,
		template $template,
		user $user,
		config $config,
		pagination $pagination,
		request $request,
		path_helper $path_helper,
		game_registry $game_registry,
		string $players_table,
		string $ranks_table,
		string $classes_table,
		string $races_table,
		string $language_table,
		string $guild_table,
		string $factions_table
	)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->path_helper = $path_helper;
		$this->game_registry = $game_registry;
		$this->players_table = $players_table;
		$this->ranks_table = $ranks_table;
		$this->classes_table = $classes_table;
		$this->races_table = $races_table;
		$this->language_table = $language_table;
		$this->guild_table = $guild_table;
		$this->factions_table = $factions_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		$game_id = $this->get_guild_game_id();
		$ext_path_images = $this->get_game_images_path($game_id);

		// Read filter values from request
		$start = $this->request->variable('start', 0);
		$filter = $this->request->variable('filter', $this->user->lang['ALL']);
		$player_filter = $this->request->variable('player_name', '', true);

		// Determine filter type
		$query_by_armor = false;
		$query_by_class = false;
		$class_id = 0;
		$armor_types = $this->get_armor_types();
		$class_names = $this->get_class_names($game_id);

		if ($filter != $this->user->lang['ALL'])
		{
			if (array_key_exists($filter, $armor_types))
			{
				$filter = preg_replace('/ Armor/', '', (string) $filter);
				$query_by_armor = true;
			}
			else if (array_key_exists($filter, $class_names))
			{
				$query_by_class = true;
				$t = explode('_', $filter);
				$class_id = count($t) > 1 ? (int) $t[2] : 0;
			}
		}

		// Build filter dropdown
		$this->build_filter_dropdown($game_id, $filter, $armor_types, $class_names);

		// Get player list (listing mode only for portal)
		$players = new player(
			$this->players_table,
			$this->ranks_table,
			$this->classes_table,
			$this->races_table,
			$this->language_table,
			$this->guild_table,
			$this->factions_table
		);
		$players->game_id = $game_id;

		$characters = $players->getplayerlist(
			$start, 0, $query_by_armor, $query_by_class, $filter,
			$game_id, $this->guild_id, $class_id, 0, 0, 200, false, $player_filter, 0
		);

		// Assign roster rows
		foreach ($characters[0] as $char)
		{
			$this->template->assign_block_vars('portal_roster_row', [
				'PLAYER_ID'   => $char['player_id'],
				'GAME'        => $char['game_id'],
				'COLORCODE'   => $char['colorcode'],
				'CLASS'       => $char['class_name'],
				'NAME'        => $char['player_name'],
				'RACE'        => $char['race_name'],
				'RANK'        => $char['player_rank'],
				'LVL'         => $char['player_level'],
				'ARMORY'      => $char['player_armory_url'],
				'PHPBBUID'    => $char['username'],
				'ACHIEVPTS'   => $char['player_achiev'],
				'CLASS_IMAGE' => $ext_path_images . 'class_images/' . basename($char['class_image']),
				'RACE_IMAGE'  => $ext_path_images . 'race_images/' . basename($char['race_image']),
			]);
		}

		// Pagination
		$base_url = $this->request->variable('REQUEST_URI', '', true, request::SERVER);
		$player_count = (int) $characters[2];
		$per_page = (int) $this->config['bbguild_user_llimit'];

		if ($per_page > 0)
		{
			$this->pagination->generate_template_pagination(
				$base_url, 'roster_pagination', 'start',
				$player_count, $per_page, $start, true
			);
		}

		// Sort URLs
		if (isset($characters[1]))
		{
			$this->template->assign_vars([
				'O_NAME'   => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][0],
				'O_CLASS'  => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][2],
				'O_RANK'   => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][3],
				'O_LEVEL'  => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][4],
				'O_PHPBB'  => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][5],
				'O_ACHI'   => '?' . \avathar\bbguild\model\admin\constants::URI_ORDER . '=' . $characters[1]['uri'][6],
			]);
		}

		$this->template->assign_vars([
			'S_PORTAL_HAS_ROSTER'  => count($characters[0]) > 0,
			'S_SHOWACH'            => $this->config['bbguild_show_achiev'],
			'ROSTER_FOOTCOUNT'     => count($characters[0]),
			'ROSTER_PLAYER_NAME'   => $player_filter,
			'ROSTER_GUILD_ID'      => $this->guild_id,
		]);

		return 'roster_center.html';
	}

	/**
	 * Get the game_id for the current guild.
	 */
	protected function get_guild_game_id(): string
	{
		$sql = 'SELECT game_id FROM ' . $this->guild_table . ' WHERE id = ' . (int) $this->guild_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row ? (string) $row['game_id'] : '';
	}

	/**
	 * Get game-specific images web path.
	 */
	protected function get_game_images_path(string $game_id): string
	{
		$web_root = $this->path_helper->get_web_root_path();
		$provider = $this->game_registry->get($game_id);
		if ($provider !== null)
		{
			$path = $provider->get_images_path();
			$pos = strpos($path, 'ext/');
			if ($pos !== false)
			{
				return $web_root . substr($path, $pos);
			}
		}
		return $web_root . 'ext/avathar/bbguild/images/';
	}

	/**
	 * Get armor types from classes table.
	 */
	protected function get_armor_types(): array
	{
		$types = [];
		$sql = 'SELECT class_armor_type FROM ' . $this->classes_table . ' GROUP BY class_armor_type';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$key = strtoupper($row['class_armor_type']);
			if ($key !== '')
			{
				$types[$key] = $this->user->lang[$key] ?? $key;
			}
		}
		$this->db->sql_freeresult($result);
		return $types;
	}

	/**
	 * Get class names for filter dropdown.
	 */
	protected function get_class_names(string $game_id): array
	{
		$names = [];
		$sql_array = [
			'SELECT'   => 'c.game_id, c.class_id, l.name as class_name',
			'FROM'     => [
				$this->classes_table  => 'c',
				$this->language_table => 'l',
				$this->players_table  => 'i',
			],
			'WHERE'    => "c.class_id > 0 AND l.attribute_id = c.class_id AND c.game_id = l.game_id
				AND l.language = '" . $this->db->sql_escape($this->config['bbguild_lang']) . "' AND l.attribute = 'class'
				AND i.player_class_id = c.class_id AND i.game_id = c.game_id
				AND i.game_id = '" . $this->db->sql_escape($game_id) . "'
				AND i.player_guild_id = " . (int) $this->guild_id,
			'GROUP_BY' => 'c.game_id, c.class_id, l.name',
			'ORDER_BY' => 'c.game_id, c.class_id',
		];
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$names[$row['game_id'] . '_class_' . $row['class_id']] = $row['class_name'];
		}
		$this->db->sql_freeresult($result);
		return $names;
	}

	/**
	 * Build filter dropdown template vars.
	 */
	protected function build_filter_dropdown(string $game_id, string $filter, array $armor_types, array $class_names): void
	{
		$values = [];
		$values['all'] = $this->user->lang['ALL'];
		$values['separator1'] = '--------';
		foreach ($armor_types as $key => $label)
		{
			$values[$key] = $label;
		}
		$values['separator2'] = '--------';
		foreach ($class_names as $key => $label)
		{
			$values[$key] = $label;
		}

		foreach ($values as $fid => $fname)
		{
			$this->template->assign_block_vars('roster_filter_row', [
				'VALUE'    => $fid,
				'SELECTED' => ($fid == $filter && $fname != '--------') ? ' selected="selected"' : '',
				'DISABLED' => ($fname == '--------') ? ' disabled="disabled"' : '',
				'OPTION'   => !empty($fname) ? $fname : $this->user->lang['ALL'],
			]);
		}
	}
}
