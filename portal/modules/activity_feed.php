<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Activity Feed portal module.
 * Shows recent guild activity from the Battle.net API (loot, achievements).
 * Only active when guild has armory enabled.
 */

namespace avathar\bbguild\portal\modules;

use avathar\bbguild\model\games\game;
use avathar\bbguild\model\games\game_registry;
use avathar\bbguild\model\player\guilds;
use phpbb\cache\driver\driver_interface as cache_interface;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;

class activity_feed extends module_base
{
	protected int $columns = 4; // center only
	protected string $name = 'BBGUILD_PORTAL_ACTIVITY_FEED';
	protected string $image_src = '';

	protected driver_interface $db;
	protected template $template;
	protected user $user;
	protected config $config;
	protected cache_interface $cache;
	protected game_registry $game_registry;
	protected string $players_table;
	protected string $ranks_table;
	protected string $classes_table;
	protected string $races_table;
	protected string $language_table;
	protected string $guild_table;
	protected string $factions_table;

	/** @var int Max feed items */
	protected int $max_items = 25;

	public function __construct(
		driver_interface $db,
		template $template,
		user $user,
		config $config,
		cache_interface $cache,
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
		$this->cache = $cache;
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
		// We need a guild object to call the API
		$log = null; // Activity feed doesn't need logging
		$guild = new guilds(
			$this->db,
			$this->user,
			$this->config,
			$this->cache,
			$log,
			$this->players_table,
			$this->ranks_table,
			$this->classes_table,
			$this->races_table,
			$this->language_table,
			$this->guild_table,
			$this->factions_table,
			$this->guild_id
		);
		$guild->get_guild();

		if (!$guild->isArmoryEnabled())
		{
			$this->template->assign_var('S_PORTAL_HAS_ACTIVITY', false);
			return 'activity_feed_center.html';
		}

		$game = new game();
		$game->game_id = $guild->getGameId();
		$game->get_game();

		$provider = $this->game_registry->get($guild->getGameId());
		$data = $guild->Call_Guild_API(['news'], $game, $provider);

		$has_activity = false;
		if ($data && isset($data['news']))
		{
			$i = 0;
			foreach ($data['news'] as $id => $news)
			{
				$i++;
				if ($i > $this->max_items)
				{
					break;
				}

				$timestamp = (!empty($news['timestamp'])) ? $this->date_diff($news['timestamp']) : '';

				switch ($news['type'])
				{
					case 'itemCraft':
					case 'itemLoot':
						$has_activity = true;
						$this->template->assign_block_vars('portal_activity', [
							'TYPE'      => 'ITEM',
							'ID'        => $id,
							'VERB'      => $this->user->lang('LOOTED'),
							'CHARACTER' => $news['character'],
							'TIMESTAMP' => $timestamp,
							'ITEM'      => $news['itemId'] ?? '',
							'CONTEXT'   => $news['context'] ?? '',
						]);
						break;

					case 'playerAchievement':
						$has_activity = true;
						$this->template->assign_block_vars('portal_activity', [
							'TYPE'        => 'ACHI',
							'ID'          => $id,
							'VERB'        => $this->user->lang('ACHIEVED'),
							'CHARACTER'   => $news['character'],
							'TIMESTAMP'   => $timestamp,
							'ACHIEVEMENT' => $news['achievement']['id'] ?? 0,
							'TITLE'       => $news['achievement']['title'] ?? '',
							'POINTS'      => sprintf($this->user->lang['FORNPOINTS'] ?? '%d points', $news['achievement']['points'] ?? 0),
						]);
						break;
				}
			}
		}

		$this->template->assign_var('S_PORTAL_HAS_ACTIVITY', $has_activity);

		return 'activity_feed_center.html';
	}

	/**
	 * Calculate relative time difference from epoch (in milliseconds).
	 */
	protected function date_diff(int $epoch): string
	{
		$epoch = (int) ($epoch / 1000);
		$datetime1 = new \DateTime("@$epoch");
		$datetime2 = new \DateTime();
		$interval = date_diff($datetime1, $datetime2);

		if ($interval->format('%i%h%d%m%y') === '00000')
		{
			return $interval->format('%s') . ' Seconds';
		}
		if ($interval->format('%h%d%m%y') === '0000')
		{
			return $interval->format('%i') . ' Minutes';
		}
		if ($interval->format('%d%m%y') === '000')
		{
			return $interval->format('%h') . ' Hours';
		}
		if ($interval->format('%m%y') === '00')
		{
			return $interval->format('%d') . ' Days';
		}
		if ($interval->format('%y') === '0')
		{
			return $interval->format('%m') . ' Months';
		}

		return $interval->format('%y') . ' Years';
	}
}
