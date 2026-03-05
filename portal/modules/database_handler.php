<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal\modules;

use phpbb\db\driver\driver_interface;

/**
 * Database operations for portal modules.
 * All queries are scoped by guild_id.
 */
class database_handler
{
	const MOVE_DIRECTION_UP = -1;
	const MOVE_DIRECTION_DOWN = 1;
	const MOVE_DIRECTION_RIGHT = 1;
	const MOVE_DIRECTION_LEFT = -1;
	const MODULE_ENABLED = 1;
	const DEFAULT_ICON_SIZE = 16;

	protected driver_interface $db;
	protected string $modules_table;

	public function __construct(driver_interface $db, string $modules_table)
	{
		$this->db = $db;
		$this->modules_table = $modules_table;
	}

	/**
	 * Get the modules table name.
	 */
	public function get_table_name(): string
	{
		return $this->modules_table;
	}

	/**
	 * Get module data from database.
	 *
	 * @return array|false
	 */
	public function get_module_data(int $module_id)
	{
		$sql = 'SELECT *
			FROM ' . $this->modules_table . '
			WHERE module_id = ' . (int) $module_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$module_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $module_data;
	}

	/**
	 * Get all modules for a guild, ordered by column and order.
	 */
	public function get_modules(int $guild_id): array
	{
		$modules = [];
		$sql = 'SELECT *
			FROM ' . $this->modules_table . '
			WHERE guild_id = ' . (int) $guild_id . '
			ORDER BY module_column ASC, module_order ASC';
		$result = $this->db->sql_query($sql, 3600);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$modules[] = $row;
		}
		$this->db->sql_freeresult($result);

		return $modules;
	}

	/**
	 * Reset module settings to defaults.
	 */
	public function reset_module(module_interface $module, int $module_id): int
	{
		$sql_ary = [
			'module_name'         => $module->get_name(),
			'module_image_src'    => $module->get_image(),
			'module_group_ids'    => '',
			'module_image_height' => self::DEFAULT_ICON_SIZE,
			'module_image_width'  => self::DEFAULT_ICON_SIZE,
			'module_status'       => self::MODULE_ENABLED,
		];
		$sql = 'UPDATE ' . $this->modules_table . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE module_id = ' . (int) $module_id;
		$this->db->sql_query($sql);

		return (int) $this->db->sql_affectedrows();
	}

	/**
	 * Move module vertically (up/down) within its column.
	 */
	public function move_module_vertical(int $module_id, array $module_data, int $direction, int $step = 1): bool
	{
		$direction = (int) $direction;
		$step = (int) $step;

		if ($direction === self::MOVE_DIRECTION_DOWN)
		{
			$current_increment = ' + ' . $step;
			$other_increment = ' - ' . $step;
		}
		else
		{
			$current_increment = ' - ' . $step;
			$other_increment = ' + ' . $step;
		}

		$sql = 'UPDATE ' . $this->modules_table . '
			SET module_order = module_order' . $other_increment . '
			WHERE module_order = ' . ($module_data['module_order'] + ($direction * $step)) . '
				AND module_column = ' . (int) $module_data['module_column'] . '
				AND guild_id = ' . (int) $module_data['guild_id'];
		$this->db->sql_query($sql);
		$updated = (bool) $this->db->sql_affectedrows();

		if ($updated)
		{
			$sql = 'UPDATE ' . $this->modules_table . '
				SET module_order = module_order' . $current_increment . '
				WHERE module_id = ' . (int) $module_id;
			$this->db->sql_query($sql);
		}

		return $updated;
	}

	/**
	 * Move module horizontally (between columns).
	 */
	public function move_module_horizontal(int $module_id, array $module_data, int $move_action): void
	{
		$guild_id = (int) $module_data['guild_id'];
		$new_column = (int) ($module_data['module_column'] + $move_action);

		// Make room in target column
		$sql = 'UPDATE ' . $this->modules_table . '
			SET module_order = module_order + 1
			WHERE module_order >= ' . (int) $module_data['module_order'] . '
				AND module_column = ' . $new_column . '
				AND guild_id = ' . $guild_id;
		$this->db->sql_query($sql);
		$updated = $this->db->sql_affectedrows();

		// Move module to target column
		$sql = 'UPDATE ' . $this->modules_table . '
			SET module_column = ' . $new_column . '
			WHERE module_id = ' . (int) $module_id;
		$this->db->sql_query($sql);

		// Close gap in source column
		$sql = 'UPDATE ' . $this->modules_table . '
			SET module_order = module_order - 1
			WHERE module_order >= ' . (int) $module_data['module_order'] . '
				AND module_column = ' . (int) $module_data['module_column'] . '
				AND guild_id = ' . $guild_id;
		$this->db->sql_query($sql);

		// If module was appended at the end
		if (!$updated)
		{
			$sql = 'SELECT MAX(module_order) as new_order
				FROM ' . $this->modules_table . '
				WHERE module_order < ' . (int) $module_data['module_order'] . '
					AND module_column = ' . $new_column . '
					AND guild_id = ' . $guild_id;
			$this->db->sql_query($sql);
			$new_order = (int) $this->db->sql_fetchfield('new_order') + 1;

			$sql = 'UPDATE ' . $this->modules_table . '
				SET module_order = ' . $new_order . '
				WHERE module_id = ' . (int) $module_id;
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Add a new module to a guild's portal.
	 */
	public function add_module(string $classname, int $column, int $order, int $guild_id, string $name, string $image = ''): int
	{
		$sql_ary = [
			'guild_id'            => $guild_id,
			'module_classname'    => $classname,
			'module_column'       => $column,
			'module_order'        => $order,
			'module_name'         => $name,
			'module_image_src'    => $image,
			'module_image_width'  => self::DEFAULT_ICON_SIZE,
			'module_image_height' => self::DEFAULT_ICON_SIZE,
			'module_group_ids'    => '',
			'module_status'       => self::MODULE_ENABLED,
		];

		$sql = 'INSERT INTO ' . $this->modules_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);

		return (int) $this->db->sql_nextid();
	}

	/**
	 * Delete a module.
	 */
	public function delete_module(int $module_id, array $module_data): void
	{
		$sql = 'DELETE FROM ' . $this->modules_table . '
			WHERE module_id = ' . (int) $module_id;
		$this->db->sql_query($sql);

		// Close the gap
		$sql = 'UPDATE ' . $this->modules_table . '
			SET module_order = module_order - 1
			WHERE module_column = ' . (int) $module_data['module_column'] . '
				AND module_order > ' . (int) $module_data['module_order'] . '
				AND guild_id = ' . (int) $module_data['guild_id'];
		$this->db->sql_query($sql);
	}

	/**
	 * Copy default layout (guild_id=0) to a new guild.
	 */
	public function seed_guild_layout(int $guild_id): void
	{
		$defaults = $this->get_modules(0);
		foreach ($defaults as $order => $row)
		{
			$this->add_module(
				$row['module_classname'],
				(int) $row['module_column'],
				(int) $row['module_order'],
				$guild_id,
				$row['module_name'],
				$row['module_image_src'] ?? ''
			);
		}
	}
}
