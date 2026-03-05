<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal\modules;

use avathar\bbguild\portal\columns;
use avathar\bbguild\portal\controller_helper;
use avathar\bbguild\portal\module_registry;
use avathar\bbguild\portal\portal_config;
use phpbb\cache\service as cache_service;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\user;

/**
 * Module management: add, delete, move, reset modules in a guild's portal.
 * Used by the ACP portal module.
 */
class manager
{
	protected cache_service $cache;
	protected driver_interface $db;
	protected controller_helper $controller_helper;
	protected columns $portal_columns;
	protected module_registry $module_registry;
	protected constraints_handler $constraints_handler;
	protected database_handler $database_handler;
	protected portal_config $portal_config;
	protected request_interface $request;
	protected user $user;
	protected string $modules_table;

	protected ?module_interface $module = null;
	protected string $u_action = '';

	public function __construct(
		cache_service $cache,
		driver_interface $db,
		controller_helper $controller_helper,
		columns $portal_columns,
		module_registry $module_registry,
		constraints_handler $constraints_handler,
		database_handler $database_handler,
		portal_config $portal_config,
		request_interface $request,
		user $user,
		string $modules_table
	)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->controller_helper = $controller_helper;
		$this->portal_columns = $portal_columns;
		$this->module_registry = $module_registry;
		$this->constraints_handler = $constraints_handler;
		$this->database_handler = $database_handler;
		$this->portal_config = $portal_config;
		$this->request = $request;
		$this->user = $user;
		$this->modules_table = $modules_table;
	}

	/**
	 * Set ACP action URL.
	 */
	public function set_u_action(string $u_action): self
	{
		$this->u_action = $u_action;
		return $this;
	}

	/**
	 * Get module data for management operations.
	 *
	 * @return array|false
	 */
	public function get_move_module_data(int $module_id)
	{
		return $this->database_handler->get_module_data($module_id);
	}

	/**
	 * Move module vertically (up or down).
	 */
	public function move_module_vertical(int $module_id, int $direction): bool
	{
		$module_data = $this->get_move_module_data($module_id);
		if ($module_data === false)
		{
			return false;
		}

		$result = $this->database_handler->move_module_vertical($module_id, $module_data, $direction);
		if ($result)
		{
			$this->cache->destroy('sql', $this->modules_table);
		}

		return $result;
	}

	/**
	 * Move module horizontally (between columns).
	 */
	public function move_module_horizontal(int $module_id, int $direction): bool
	{
		$module_data = $this->get_move_module_data($module_id);
		if ($module_data === false)
		{
			return false;
		}

		$module = $this->module_registry->get_module($module_data['module_classname']);
		if (!$module instanceof module_interface)
		{
			return false;
		}

		if (!$this->constraints_handler->can_move_horizontally($module_data, $direction))
		{
			return false;
		}

		// Check if target column is allowed
		$target_column = $this->portal_columns->number_to_string((int) $module_data['module_column'] + $direction);
		if (!($module->get_allowed_columns() & $this->portal_columns->string_to_constant($target_column)))
		{
			return false;
		}

		$this->database_handler->move_module_horizontal($module_id, $module_data, $direction);
		$this->cache->destroy('sql', $this->modules_table);

		return true;
	}

	/**
	 * Add a module to a guild's portal.
	 */
	public function add_module(string $classname, int $column, int $guild_id): int
	{
		$module = $this->module_registry->get_module($classname);
		if (!$module instanceof module_interface)
		{
			return 0;
		}

		if (!$this->constraints_handler->can_add_module($module, $column))
		{
			return 0;
		}

		// Get last order in this column for this guild
		$modules = $this->database_handler->get_modules($guild_id);
		$last_order = 0;
		foreach ($modules as $row)
		{
			if ((int) $row['module_column'] === $column)
			{
				$last_order = max($last_order, (int) $row['module_order']);
			}
		}

		$module_id = $this->database_handler->add_module(
			$classname,
			$column,
			$last_order + 1,
			$guild_id,
			$module->get_name(),
			$module->get_image()
		);

		if ($module_id)
		{
			$module->set_guild_context($guild_id);
			$module->install($module_id);
			$this->cache->destroy('sql', $this->modules_table);
		}

		return $module_id;
	}

	/**
	 * Delete a module from a guild's portal.
	 */
	public function delete_module(int $module_id): bool
	{
		$module_data = $this->get_move_module_data($module_id);
		if ($module_data === false)
		{
			return false;
		}

		$module = $this->module_registry->get_module($module_data['module_classname']);
		if ($module instanceof module_interface)
		{
			$module->set_guild_context((int) $module_data['guild_id']);
			$module->uninstall($module_id);
		}

		$this->database_handler->delete_module($module_id, $module_data);
		$this->cache->destroy('sql', $this->modules_table);

		return true;
	}

	/**
	 * Reset a module to its default settings.
	 */
	public function reset_module(int $module_id): bool
	{
		$module_data = $this->get_move_module_data($module_id);
		if ($module_data === false)
		{
			return false;
		}

		$module = $this->module_registry->get_module($module_data['module_classname']);
		if (!$module instanceof module_interface)
		{
			return false;
		}

		$this->database_handler->reset_module($module, $module_id);
		$module->set_guild_context((int) $module_data['guild_id']);
		$module->install($module_id);
		$this->cache->purge();

		return true;
	}
}
