<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal\modules;

use avathar\bbguild\portal\columns;

/**
 * Handles module positioning constraints (which columns a module can be placed in).
 * Simplified for 3-column layout (top, center, right).
 */
class constraints_handler
{
	protected columns $portal_columns;

	/** @var array<string, string[]> Tracks which columns each module class occupies */
	public array $module_column = [];

	public function __construct(columns $portal_columns)
	{
		$this->portal_columns = $portal_columns;
	}

	/**
	 * Set the module-to-column mapping for constraint checking.
	 *
	 * @param array<string, string[]> $module_column
	 */
	public function set_module_column(array $module_column = []): void
	{
		$this->module_column = $module_column;
	}

	/**
	 * Check if a module can be added to a given column (by number).
	 */
	public function can_add_module(module_interface $module, int $column): bool
	{
		$column_name = $this->portal_columns->number_to_string($column);
		if ($column_name === '')
		{
			return false;
		}
		return (bool) ($module->get_allowed_columns() & $this->portal_columns->string_to_constant($column_name));
	}

	/**
	 * Check if module can be moved horizontally.
	 */
	public function can_move_horizontally(array $module_data, int $direction): bool
	{
		if (!isset($module_data['module_column']))
		{
			return false;
		}

		if ($direction === database_handler::MOVE_DIRECTION_RIGHT)
		{
			return $module_data['module_column'] < $this->portal_columns->string_to_number('bottom');
		}

		return $module_data['module_column'] > $this->portal_columns->string_to_number('top');
	}

	/**
	 * Check if module already exists in a column group (prevents duplicates
	 * for non-custom modules).
	 */
	public function check_module_already_exists(string $column, string $module_class): bool
	{
		if (!isset($this->module_column[$module_class]))
		{
			return true; // No conflict
		}

		// Center group: top + center + bottom
		if (in_array($column, ['center', 'top', 'bottom']))
		{
			if (in_array('center', $this->module_column[$module_class]) ||
				in_array('top', $this->module_column[$module_class]) ||
				in_array('bottom', $this->module_column[$module_class]))
			{
				return false;
			}
		}

		// Right is its own group
		if ($column === 'right' && in_array('right', $this->module_column[$module_class]))
		{
			return false;
		}

		return true;
	}
}
