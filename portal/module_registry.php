<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal;

use phpbb\di\service_collection;

/**
 * Registry of all available portal modules, discovered via tagged services.
 */
class module_registry
{
	/** @var array<string, \avathar\bbguild\portal\modules\module_interface> */
	protected array $modules = [];

	public function __construct(service_collection $module_collection)
	{
		foreach ($module_collection as $module)
		{
			$class_name = '\\' . get_class($module);
			if (!isset($this->modules[$class_name]))
			{
				$this->modules[$class_name] = $module;
			}
		}
	}

	/**
	 * Get a module by its fully-qualified class name.
	 *
	 * @return \avathar\bbguild\portal\modules\module_interface|false
	 */
	public function get_module(string $class_name)
	{
		return $this->modules[$class_name] ?? false;
	}

	/**
	 * Get all registered modules.
	 *
	 * @return array<string, \avathar\bbguild\portal\modules\module_interface>
	 */
	public function get_all_modules(): array
	{
		return $this->modules;
	}
}
