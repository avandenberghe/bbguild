<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * ACP controller for portal module management
 */

namespace avathar\bbguild\controller;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\cache\service as cache_service;
use avathar\bbguild\portal\columns;
use avathar\bbguild\portal\module_registry;
use avathar\bbguild\portal\modules\database_handler;
use avathar\bbguild\portal\modules\manager;

class admin_portal
{
	protected auth $auth;
	protected cache_service $cache;
	protected config $config;
	protected driver_interface $db;
	protected language $language;
	protected request $request;
	protected template $template;
	protected user $user;
	protected columns $portal_columns;
	protected module_registry $module_registry;
	protected database_handler $database_handler;
	protected manager $module_manager;
	protected string $guild_table;
	protected string $u_action = '';

	public function __construct(
		auth $auth,
		cache_service $cache,
		config $config,
		driver_interface $db,
		language $language,
		request $request,
		template $template,
		user $user,
		columns $portal_columns,
		module_registry $module_registry,
		database_handler $database_handler,
		manager $module_manager,
		string $guild_table
	)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->portal_columns = $portal_columns;
		$this->module_registry = $module_registry;
		$this->database_handler = $database_handler;
		$this->module_manager = $module_manager;
		$this->guild_table = $guild_table;
	}

	public function set_u_action(string $u_action): self
	{
		$this->u_action = $u_action;
		return $this;
	}

	/**
	 * Main display: handles actions + renders the module list.
	 */
	public function display(): void
	{
		if (!$this->auth->acl_get('a_bbguild'))
		{
			trigger_error($this->language->lang('NOAUTH_A_CONFIG_MAN'), E_USER_WARNING);
		}

		add_form_key('acp_bbguild_portal');

		$guild_id = $this->request->variable('guild_id', 0);
		$action = $this->request->variable('action', '');
		$module_id = $this->request->variable('module_id', 0);

		// Handle actions
		switch ($action)
		{
			case 'move_up':
				$this->module_manager->move_module_vertical($module_id, database_handler::MOVE_DIRECTION_UP);
				break;

			case 'move_down':
				$this->module_manager->move_module_vertical($module_id, database_handler::MOVE_DIRECTION_DOWN);
				break;

			case 'move_left':
				$this->module_manager->move_module_horizontal($module_id, database_handler::MOVE_DIRECTION_LEFT);
				break;

			case 'move_right':
				$this->module_manager->move_module_horizontal($module_id, database_handler::MOVE_DIRECTION_RIGHT);
				break;

			case 'delete':
				if (confirm_box(true))
				{
					$this->module_manager->delete_module($module_id);
					trigger_error($this->language->lang('ACP_PORTAL_MODULE_DELETED') . adm_back_link($this->u_action . '&guild_id=' . $guild_id), E_USER_NOTICE);
				}
				else
				{
					confirm_box(false, $this->language->lang('CONFIRM_OPERATION'), build_hidden_fields([
						'action'    => 'delete',
						'module_id' => $module_id,
						'guild_id'  => $guild_id,
					]));
				}
				break;

			case 'toggle':
				$this->toggle_module($module_id);
				break;

			case 'add':
				if (!check_form_key('acp_bbguild_portal'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}
				$classname = $this->request->variable('module_classname', '');
				$column = $this->request->variable('module_column', 2);
				if ($classname && $guild_id)
				{
					$new_id = $this->module_manager->add_module($classname, $column, $guild_id);
					if ($new_id)
					{
						trigger_error($this->language->lang('ACP_PORTAL_MODULE_ADDED') . adm_back_link($this->u_action . '&guild_id=' . $guild_id), E_USER_NOTICE);
					}
					else
					{
						trigger_error($this->language->lang('ACP_PORTAL_MODULE_ADD_FAILED') . adm_back_link($this->u_action . '&guild_id=' . $guild_id), E_USER_WARNING);
					}
				}
				break;
		}

		// Build guild selector
		$this->build_guild_selector($guild_id);

		// Show modules for selected guild
		if ($guild_id)
		{
			$this->build_module_list($guild_id);
			$this->build_add_module_form($guild_id);
		}

		$this->template->assign_vars([
			'U_ACTION'       => $this->u_action,
			'GUILD_ID'       => $guild_id,
			'S_GUILD_SELECTED' => $guild_id > 0,
			'S_BBGUILD'      => true,
		]);
	}

	/**
	 * Build guild dropdown.
	 */
	protected function build_guild_selector(int &$guild_id): void
	{
		$sql = 'SELECT id, name FROM ' . $this->guild_table . ' ORDER BY name ASC';
		$result = $this->db->sql_query($sql);

		$guilds = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$guilds[] = $row;
			$this->template->assign_block_vars('guild_options', [
				'VALUE'    => $row['id'],
				'LABEL'    => $row['name'],
				'SELECTED' => ((int) $row['id'] === $guild_id),
			]);
		}
		$this->db->sql_freeresult($result);

		// Auto-select first guild if none selected
		if (!$guild_id && count($guilds) > 0)
		{
			$guild_id = (int) $guilds[0]['id'];
			// Re-assign with selected flag
			$this->template->destroy_block_vars('guild_options');
			foreach ($guilds as $row)
			{
				$this->template->assign_block_vars('guild_options', [
					'VALUE'    => $row['id'],
					'LABEL'    => $row['name'],
					'SELECTED' => ((int) $row['id'] === $guild_id),
				]);
			}
		}
	}

	/**
	 * Build the list of modules for a guild.
	 */
	protected function build_module_list(int $guild_id): void
	{
		$modules = $this->database_handler->get_modules($guild_id);

		foreach ($modules as $row)
		{
			$column_name = $this->portal_columns->number_to_string((int) $row['module_column']);
			$module_obj = $this->module_registry->get_module($row['module_classname']);
			$display_name = $module_obj
				? ($this->language->lang($module_obj->get_name()) ?: $row['module_name'])
				: $row['module_classname'];

			$this->template->assign_block_vars('module_row', [
				'MODULE_ID'     => $row['module_id'],
				'MODULE_NAME'   => $display_name,
				'MODULE_COLUMN' => $this->language->lang('ACP_PORTAL_COLUMN_' . strtoupper($column_name)),
				'MODULE_ORDER'  => $row['module_order'],
				'MODULE_STATUS' => (int) $row['module_status'],
				'S_ENABLED'     => (int) $row['module_status'] === 1,
				'U_MOVE_UP'     => $this->u_action . '&amp;action=move_up&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_DOWN'   => $this->u_action . '&amp;action=move_down&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_LEFT'   => $this->u_action . '&amp;action=move_left&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_RIGHT'  => $this->u_action . '&amp;action=move_right&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_DELETE'      => $this->u_action . '&amp;action=delete&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_TOGGLE'      => $this->u_action . '&amp;action=toggle&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
			]);
		}
	}

	/**
	 * Build the add-module dropdown with available modules.
	 */
	protected function build_add_module_form(int $guild_id): void
	{
		$available = $this->module_registry->get_all_modules();

		foreach ($available as $classname => $module)
		{
			$display_name = $this->language->lang($module->get_name()) ?: $module->get_name();
			$this->template->assign_block_vars('available_modules', [
				'CLASSNAME' => $classname,
				'NAME'      => $display_name,
			]);
		}

		// Column options
		foreach ($this->portal_columns->get_column_names() as $name)
		{
			$this->template->assign_block_vars('column_options', [
				'VALUE' => $this->portal_columns->string_to_number($name),
				'LABEL' => $this->language->lang('ACP_PORTAL_COLUMN_' . strtoupper($name)),
			]);
		}
	}

	/**
	 * Toggle module enabled/disabled.
	 */
	protected function toggle_module(int $module_id): void
	{
		$module_data = $this->database_handler->get_module_data($module_id);
		if ($module_data === false)
		{
			return;
		}

		$new_status = (int) $module_data['module_status'] === 1 ? 0 : 1;

		$sql = 'UPDATE ' . $this->database_handler->get_table_name() . '
			SET module_status = ' . $new_status . '
			WHERE module_id = ' . (int) $module_id;
		$this->db->sql_query($sql);
		$this->cache->destroy('sql', $this->database_handler->get_table_name());
	}
}
