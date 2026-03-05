<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Guild context — shared guild data, sidebar menu, header template vars.
 */

namespace avathar\bbguild\views;

use avathar\bbguild\model\admin\constants;
use avathar\bbguild\model\player\guilds;

class guild_context
{
	/** @var \avathar\bbguild\controller\view_controller */
	public $view_controller;

	/** @var \phpbb\request\request */
	public $request;

	/** @var \phpbb\user */
	public $user;

	/** @var \phpbb\template\template */
	public $template;

	/** @var \phpbb\db\driver\driver_interface */
	public $db;

	/** @var \phpbb\config\config */
	public $config;

	/** @var \phpbb\controller\helper */
	public $helper;

	/** @var int */
	public $guild_id;

	/** @var guilds */
	public $guild;

	/** @var array */
	private $guildlist;

	/**
	 * @param \avathar\bbguild\controller\view_controller $view_controller
	 * @param int|string $guild_id
	 */
	public function __construct($view_controller, $guild_id)
	{
		$this->view_controller = $view_controller;
		$this->request = $view_controller->request;
		$this->config = $view_controller->config;
		$this->user = $view_controller->user;
		$this->template = $view_controller->template;
		$this->db = $view_controller->db;
		$this->helper = $view_controller->helper;
		$this->guild_id = $guild_id;

		$this->resolve_guild();
		$this->build_template_vars();
	}

	/**
	 * Resolve the guild from request or default.
	 */
	private function resolve_guild(): void
	{
		$this->guild_id = $this->request->variable(
			constants::URI_GUILD,
			$this->request->variable('hidden_guild_id', $this->guild_id)
		);
		$this->guildlist = $this->load_guild_data();
	}

	/**
	 * Load guild data and build the guild dropdown.
	 */
	private function load_guild_data(): array
	{
		global $phpbb_container;
		$bbguild_cache = $phpbb_container->get('cache.driver');
		$bbguild_log = $phpbb_container->get('avathar.bbguild.log');

		$this->guild = new guilds(
			$this->db,
			$this->user,
			$this->config,
			$bbguild_cache,
			$bbguild_log,
			$this->view_controller->bb_players_table,
			$this->view_controller->bb_ranks_table,
			$this->view_controller->bb_classes_table,
			$this->view_controller->bb_races_table,
			$this->view_controller->bb_language_table,
			$this->view_controller->bb_guild_table,
			$this->view_controller->bb_factions_table,
			$this->guild_id
		);

		$guildlist = $this->guild->guildlist(1);

		if (count($guildlist) > 0)
		{
			foreach ($guildlist as $g)
			{
				if ($this->guild_id == 0)
				{
					if ($g['guilddefault'] == 1)
					{
						$this->guild_id = $g['id'];
					}
					else if ($g['playercount'] > 1)
					{
						$this->guild_id = $g['id'];
					}
					if ($this->guild_id == 0 && $g['id'] > 0)
					{
						$this->guild_id = $g['id'];
					}
				}

				if ($g['id'] > 0)
				{
					$this->template->assign_block_vars('guild_row', [
						'VALUE'    => $g['id'],
						'SELECTED' => ($g['id'] == $this->guild_id) ? ' selected="selected"' : '',
						'OPTION'   => $g['name'],
					]);
				}
			}
		}
		else
		{
			trigger_error('ERROR_NOGUILD', E_USER_WARNING);
		}

		$this->guild->setGuildid($this->guild_id);
		$this->guild->get_guild();

		return $guildlist;
	}

	/**
	 * Assign template variables for guild header.
	 */
	private function build_template_vars(): void
	{
		$this->template->assign_vars([
			'FACTION'         => $this->guild->getFaction(),
			'FACTION_NAME'    => $this->guild->getFactionname(),
			'FACTION_CSS'     => preg_replace('/[^a-z0-9]/', '', strtolower((string) $this->guild->getFactionname())) ?: 'other',
			'GAME_ID'         => $this->guild->getGameId(),
			'GUILD_ID'        => $this->guild_id,
			'GUILD_NAME'      => $this->guild->getName(),
			'REALM'           => $this->guild->getRealm(),
			'REGION'          => $this->guild->getRegion(),
			'PLAYERCOUNT'     => $this->guild->getPlayercount(),
			'ARMORY_URL'      => '',
			'MIN_ARMORYLEVEL' => $this->guild->getMinArmory(),
			'SHOW_ROSTER'     => $this->guild->getShowroster(),
			'EMBLEM'          => $this->view_controller->ext_path_images . 'guildemblem/' . basename((string) $this->guild->getEmblempath()),
			'EMBLEMFILE'      => basename((string) $this->guild->getEmblempath()),
			'S_EMBLEM_EXISTS' => !empty($this->guild->getEmblempath()) && file_exists($this->view_controller->ext_path . 'images/guildemblem/' . basename((string) $this->guild->getEmblempath())),
			'ARMORY'          => '',
			'ACHIEV'          => '',
		]);
	}
}
