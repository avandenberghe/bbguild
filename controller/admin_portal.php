<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * ACP controller for portal module management
 */

namespace avathar\bbguild\controller;

use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\cache\service as cache_service;
use avathar\bbguild\portal\columns;
use avathar\bbguild\portal\module_registry;
use avathar\bbguild\portal\portal_config;
use avathar\bbguild\portal\modules\database_handler;
use avathar\bbguild\portal\modules\manager;

class admin_portal
{
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
	protected portal_config $portal_config;
	protected string $guild_table;
	protected string $u_action = '';
	protected string $action_param = 'action';

	public function __construct(
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
		portal_config $portal_config,
		string $guild_table
	)
	{
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
		$this->portal_config = $portal_config;
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
	public function display(string $action_param = 'action'): string
	{
		$this->action_param = $action_param;
		add_form_key('acp_bbguild_portal');

		$guild_id = $this->request->variable('guild_id', 0);
		$action = $this->request->variable($action_param, '');
		$module_id = $this->request->variable('module_id', 0);

		// Handle actions
		$redirect_url = $this->u_action . '&guild_id=' . $guild_id;
		switch ($action)
		{
			case 'move_up':
				$this->module_manager->move_module_vertical($module_id, database_handler::MOVE_DIRECTION_UP);
				redirect($redirect_url);
				break;

			case 'move_down':
				$this->module_manager->move_module_vertical($module_id, database_handler::MOVE_DIRECTION_DOWN);
				redirect($redirect_url);
				break;

			case 'move_left':
				$this->module_manager->move_module_horizontal($module_id, database_handler::MOVE_DIRECTION_LEFT);
				redirect($redirect_url);
				break;

			case 'move_right':
				$this->module_manager->move_module_horizontal($module_id, database_handler::MOVE_DIRECTION_RIGHT);
				redirect($redirect_url);
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
						$this->action_param => 'delete',
						'module_id' => $module_id,
						'guild_id'  => $guild_id,
					]));
				}
				break;

			case 'toggle':
				$this->toggle_module($module_id);
				redirect($redirect_url);
				break;

			case 'configure':
				$this->display_module_config($module_id, $guild_id);
				return 'acp_portal_config';

			case 'save_config':
				if (!check_form_key('acp_bbguild_portal'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}
				$this->save_module_config($module_id, $guild_id);
				trigger_error($this->language->lang('ACP_PORTAL_MODULE_UPDATED') . adm_back_link($this->u_action . '&guild_id=' . $guild_id), E_USER_NOTICE);
				return 'acp_editguild_portal';

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

		// Show modules for selected guild
		if ($guild_id)
		{
			$this->build_module_list($guild_id);
			$this->build_add_module_form($guild_id);
		}

		$this->template->assign_vars([
			'U_ACTION'         => $this->u_action,
			'GUILD_ID'         => $guild_id,
			'S_BBGUILD'        => true,
			'ACTION_PARAM'     => $this->action_param,
		]);

		return 'acp_editguild_portal';
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

			$ap = $this->action_param;
			$this->template->assign_block_vars('module_row', [
				'MODULE_ID'     => $row['module_id'],
				'MODULE_NAME'   => $display_name,
				'MODULE_COLUMN' => $this->language->lang('ACP_PORTAL_COLUMN_' . strtoupper($column_name)),
				'MODULE_ORDER'  => $row['module_order'],
				'MODULE_STATUS' => (int) $row['module_status'],
				'S_ENABLED'     => (int) $row['module_status'] === 1,
				'U_CONFIGURE'   => $this->u_action . '&amp;' . $ap . '=configure&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_UP'     => $this->u_action . '&amp;' . $ap . '=move_up&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_DOWN'   => $this->u_action . '&amp;' . $ap . '=move_down&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_LEFT'   => $this->u_action . '&amp;' . $ap . '=move_left&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_MOVE_RIGHT'  => $this->u_action . '&amp;' . $ap . '=move_right&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_DELETE'      => $this->u_action . '&amp;' . $ap . '=delete&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
				'U_TOGGLE'      => $this->u_action . '&amp;' . $ap . '=toggle&amp;module_id=' . $row['module_id'] . '&amp;guild_id=' . $guild_id,
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

	/**
	 * Display module configuration form.
	 */
	protected function display_module_config(int $module_id, int $guild_id): void
	{
		add_form_key('acp_bbguild_portal');

		$module_data = $this->database_handler->get_module_data($module_id);
		if ($module_data === false)
		{
			trigger_error('NO_MODULE', E_USER_WARNING);
		}

		$module_obj = $this->module_registry->get_module($module_data['module_classname']);

		// Build group options
		$selected_groups = !empty($module_data['module_group_ids'])
			? explode(',', $module_data['module_group_ids'])
			: [];

		$sql = 'SELECT group_id, group_name, group_type FROM ' . GROUPS_TABLE . ' ORDER BY group_name ASC';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$group_name = ($row['group_type'] == GROUP_SPECIAL)
				? $this->language->lang('G_' . $row['group_name'])
				: $row['group_name'];

			$this->template->assign_block_vars('group_options', [
				'VALUE'    => $row['group_id'],
				'LABEL'    => $group_name,
				'SELECTED' => in_array($row['group_id'], $selected_groups),
			]);
		}
		$this->db->sql_freeresult($result);

		// Module-specific settings from get_template_acp()
		$has_module_settings = false;
		if ($module_obj)
		{
			$module_obj->set_guild_context($guild_id);
			$acp_settings = $module_obj->get_template_acp($module_id);
			if (!empty($acp_settings))
			{
				$has_module_settings = true;
				foreach ($acp_settings as $setting)
				{
					$value = $this->portal_config->get(
						$setting['key'] ?? '',
						$guild_id,
						$setting['default'] ?? ''
					);

					$tpl_row = [
						'KEY'          => $setting['key'] ?? '',
						'LABEL'        => isset($setting['label']) ? $this->language->lang($setting['label']) : '',
						'EXPLAIN'      => isset($setting['explain']) ? $this->language->lang($setting['explain']) : '',
						'TYPE'         => $setting['type'] ?? 'text',
						'VALUE'        => $value,
						'LABEL_INLINE' => $setting['label_inline'] ?? '',
					];

					if (isset($setting['options']) && is_array($setting['options']))
					{
						foreach ($setting['options'] as $opt_value => $opt_label)
						{
							$tpl_row['options'][] = [
								'VALUE'    => $opt_value,
								'LABEL'    => $this->language->lang($opt_label),
								'SELECTED' => ($value == $opt_value),
							];
						}
					}

					$this->template->assign_block_vars('module_settings', $tpl_row);
				}
			}
		}

		$image_src = $module_data['module_image_src'] ?? '';
		$ext_path = generate_board_url() . '/ext/avathar/bbguild/';

		$this->template->assign_vars([
			'U_ACTION'             => $this->u_action,
			'MODULE_ID'            => $module_id,
			'GUILD_ID'             => $guild_id,
			'MODULE_NAME'          => $module_data['module_name'],
			'MODULE_STATUS'        => (int) $module_data['module_status'],
			'MODULE_IMAGE_SRC'     => $image_src,
			'MODULE_IMAGE_PREVIEW' => !empty($image_src)
				? $ext_path . 'styles/all/theme/images/portal/' . $image_src
				: '',
			'MODULE_ICON'          => $module_data['module_icon'] ?? '',
			'MODULE_ICON_SIZE'     => (int) ($module_data['module_icon_size'] ?? 16),
			'S_HAS_MODULE_SETTINGS' => $has_module_settings,
			'S_BBGUILD'            => true,
			'ACTION_PARAM'         => $this->action_param,
		]);

	}

	/**
	 * Save module configuration.
	 */
	protected function save_module_config(int $module_id, int $guild_id): void
	{
		if ($this->request->is_set('cancel'))
		{
			return;
		}

		$module_data = $this->database_handler->get_module_data($module_id);
		if ($module_data === false)
		{
			return;
		}

		// Save base module settings
		$group_ids = $this->request->variable('module_group_ids', [0]);
		$group_ids_str = implode(',', array_filter($group_ids));

		$sql_ary = [
			'module_name'         => $this->request->variable('module_name', '', true),
			'module_status'       => $this->request->variable('module_status', 1),
			'module_image_src'    => $this->request->variable('module_image_src', ''),
			'module_icon'         => $this->request->variable('module_icon', ''),
			'module_icon_size'    => $this->request->variable('module_icon_size', 16),
			'module_group_ids'    => $group_ids_str,
		];

		$sql = 'UPDATE ' . $this->database_handler->get_table_name() . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE module_id = ' . (int) $module_id;
		$this->db->sql_query($sql);

		// Save module-specific config values
		$config_values = $this->request->variable('config', [''], true);
		foreach ($config_values as $key => $value)
		{
			$this->portal_config->set($key, $value, $guild_id);
		}

		$this->cache->destroy('sql', $this->database_handler->get_table_name());
	}
}
