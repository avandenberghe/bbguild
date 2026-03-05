<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal;

/**
 * Handles column numbering for the 3-column portal layout.
 * Left sidebar is bbGuild's guild navigation (not a portal column).
 *
 * Database storage: top=1, center=2, right=3, bottom=4
 * Bitmask constants: top=1, center=4, right=8, bottom=16
 */
class columns
{
	/** @var array Column name → database number */
	protected array $column_map = [
		'top'    => 1,
		'center' => 2,
		'right'  => 3,
		'bottom' => 4,
	];

	/** @var array Column name → bitmask constant */
	protected array $constant_map = [
		'top'    => 1,
		'center' => 4,
		'right'  => 8,
		'bottom' => 16,
	];

	/**
	 * Convert column database number to string name.
	 */
	public function number_to_string(int $column): string
	{
		$name = array_search($column, $this->column_map, true);
		return $name !== false ? $name : '';
	}

	/**
	 * Convert column string name to database number.
	 */
	public function string_to_number(string $column): int
	{
		return $this->column_map[$column] ?? 0;
	}

	/**
	 * Convert column string name to bitmask constant.
	 */
	public function string_to_constant(string $column): int
	{
		return $this->constant_map[$column] ?? 0;
	}

	/**
	 * Get all column names.
	 *
	 * @return string[]
	 */
	public function get_column_names(): array
	{
		return array_keys($this->column_map);
	}
}
