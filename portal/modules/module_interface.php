<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal\modules;

/**
 * Interface for portal modules (blocks).
 *
 * Allowed column constants (sum to combine):
 *   top    = 1
 *   center = 4
 *   right  = 8
 */
interface module_interface
{
	/**
	 * Get bitmask of allowed columns for this module.
	 *
	 * @return int Sum of column constants (top=1, center=4, right=8)
	 */
	public function get_allowed_columns(): int;

	/**
	 * Get module display name (language key).
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get default module image filename.
	 *
	 * @return string
	 */
	public function get_image(): string;

	/**
	 * Get module language file.
	 *
	 * @return string|array|false Language file path, array with 'vendor' + 'file', or false
	 */
	public function get_language();

	/**
	 * Get template for side column (right).
	 *
	 * @param int $module_id
	 * @return string|array|null Template file or null if not supported
	 */
	public function get_template_side(int $module_id);

	/**
	 * Get template for center/top columns.
	 *
	 * @param int $module_id
	 * @return string|array|null Template file or null if not supported
	 */
	public function get_template_center(int $module_id);

	/**
	 * Get ACP settings definition.
	 *
	 * @param int $module_id
	 * @return array
	 */
	public function get_template_acp(int $module_id): array;

	/**
	 * Called when module is installed (added to portal).
	 *
	 * @param int $module_id
	 * @return bool
	 */
	public function install(int $module_id): bool;

	/**
	 * Called when module is uninstalled (removed from portal).
	 *
	 * @param int $module_id
	 * @return bool
	 */
	public function uninstall(int $module_id): bool;

	/**
	 * Whether this module can appear multiple times.
	 *
	 * @return bool
	 */
	public function can_multi_include(): bool;

	/**
	 * Set the guild context for this module.
	 *
	 * @param int $guild_id
	 * @return void
	 */
	public function set_guild_context(int $guild_id): void;
}
