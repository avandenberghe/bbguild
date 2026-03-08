<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal;

use phpbb\language\language;
use phpbb\path_helper;
use phpbb\template\template;
use phpbb\user;

/**
 * Helper for the portal renderer. Handles module loading, language,
 * template variable assignment, and group-based access checks.
 */
class module_helper
{
	/** @var int Module disabled status */
	const MODULE_DISABLED = 0;

	protected columns $portal_columns;
	protected language $language;
	protected template $template;
	protected user $user;
	protected path_helper $path_helper;
	protected module_registry $module_registry;
	protected string $phpbb_root_path;
	protected string $php_ext;
	protected string $ext_path;

	public function __construct(
		columns $portal_columns,
		language $language,
		template $template,
		user $user,
		path_helper $path_helper,
		module_registry $module_registry,
		string $phpbb_root_path,
		string $php_ext
	)
	{
		$this->portal_columns = $portal_columns;
		$this->language = $language;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->module_registry = $module_registry;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->ext_path = $this->path_helper->get_web_root_path() . 'ext/avathar/bbguild/';
	}

	/**
	 * Get the portal module object for a DB row, checking status and group access.
	 *
	 * @param array $row Module database row
	 * @return \avathar\bbguild\portal\modules\module_interface|false
	 */
	public function get_portal_module(array $row)
	{
		if ((int) $row['module_status'] === self::MODULE_DISABLED)
		{
			return false;
		}

		$module = $this->module_registry->get_module($row['module_classname']);
		if (!is_object($module))
		{
			return false;
		}

		if (!$this->check_group_access($row))
		{
			return false;
		}

		return $module;
	}

	/**
	 * Check if user's default group is allowed to view this module.
	 */
	protected function check_group_access(array $row): bool
	{
		if (empty($row['module_group_ids']))
		{
			return true;
		}

		$group_ary = array_map('intval', explode(',', $row['module_group_ids']));
		return in_array((int) $this->user->data['group_id'], $group_ary, true);
	}

	/**
	 * Load the language file for a module.
	 */
	public function load_module_language(modules\module_interface $module): void
	{
		$language_file = $module->get_language();
		if ($language_file === false)
		{
			return;
		}

		if (is_array($language_file))
		{
			$this->language->add_lang($language_file['file'], $language_file['vendor']);
		}
		else
		{
			$this->language->add_lang('portal/' . $language_file, 'avathar/bbguild');
		}
	}

	/**
	 * Assign template block vars for a module in its column.
	 */
	public function assign_module_vars(array $row, $template_module): void
	{
		$column_name = $this->portal_columns->number_to_string((int) $row['module_column']);
		if ($column_name === '')
		{
			return;
		}

		// Icon data from DB row, with fallback
		$fa_icon = $row['module_icon'] ?? '';
		$fa_size = (int) ($row['module_icon_size'] ?? 16);
		$fa_enabled = !empty($fa_icon);
		$image_src = $row['module_image_src'] ?? '';
		$image_width = (int) ($row['module_image_width'] ?? 16);
		$image_height = (int) ($row['module_image_height'] ?? 16);

		if (is_array($template_module))
		{
			$image_src_path = !empty($template_module['image_src'])
				? $this->ext_path . 'styles/all/theme/images/portal/' . $template_module['image_src']
				: (!empty($image_src) ? $this->ext_path . 'styles/all/theme/images/portal/' . $image_src : '');

			$this->template->assign_block_vars('modules_' . $column_name, [
				'TEMPLATE_FILE' => $this->parse_template_file($template_module['template']),
				'IMAGE_SRC'     => $image_src_path,
				'TITLE'         => $template_module['title'] ?? '',
				'CODE'          => $template_module['code'] ?? '',
				'MODULE_ID'     => $row['module_id'],
				'IMAGE_WIDTH'   => $image_width,
				'IMAGE_HEIGHT'  => $image_height,
				'FA_ICON'       => $fa_icon,
				'FA_SIZE'       => $fa_size,
				'FA_ENABLED'    => $fa_enabled,
			]);
		}
		else
		{
			$title = isset($this->user->lang[$row['module_name']])
				? $this->user->lang[$row['module_name']]
				: $row['module_name'];

			$image_src_path = !empty($image_src)
				? $this->ext_path . 'styles/all/theme/images/portal/' . $image_src
				: '';

			$this->template->assign_block_vars('modules_' . $column_name, [
				'TEMPLATE_FILE' => $this->parse_template_file($template_module),
				'IMAGE_SRC'     => $image_src_path,
				'TITLE'         => $title,
				'MODULE_ID'     => $row['module_id'],
				'IMAGE_WIDTH'   => $image_width,
				'IMAGE_HEIGHT'  => $image_height,
				'FA_ICON'       => $fa_icon,
				'FA_SIZE'       => $fa_size,
				'FA_ENABLED'    => $fa_enabled,
			]);
		}
	}

	/**
	 * Prefix default module templates with the portal path.
	 * Templates from other extensions use @vendor/ext syntax and are left as-is.
	 */
	protected function parse_template_file(string $template_file): string
	{
		if (strpos($template_file, '@') === false)
		{
			return 'portal/modules/' . $template_file;
		}

		return $template_file;
	}
}
