<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Portal schema migration — adds bb_portal_modules and bb_portal_config tables.
 */

namespace avathar\bbguild\migrations\v200a12;

class portal_schema extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\v200a11\release_2_0_0_a11'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'bb_portal_modules');
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'bb_portal_modules' => [
					'COLUMNS' => [
						'module_id'           => ['UINT', null, 'auto_increment'],
						'guild_id'            => ['USINT', 0],
						'module_classname'    => ['VCHAR:255', ''],
						'module_column'       => ['TINT:3', 0],
						'module_order'        => ['TINT:3', 0],
						'module_name'         => ['VCHAR:255', ''],
						'module_image_src'    => ['VCHAR:255', ''],
						'module_image_width'  => ['USINT', 16],
						'module_image_height' => ['USINT', 16],
						'module_group_ids'    => ['VCHAR:255', ''],
						'module_status'       => ['TINT:1', 1],
					],
					'PRIMARY_KEY' => 'module_id',
					'KEYS' => [
						'guild_col_order' => ['INDEX', ['guild_id', 'module_column', 'module_order']],
					],
				],
				$this->table_prefix . 'bb_portal_config' => [
					'COLUMNS' => [
						'config_name'  => ['VCHAR:255', ''],
						'config_value' => ['MTEXT', ''],
						'guild_id'     => ['USINT', 0],
					],
					'PRIMARY_KEY' => ['config_name', 'guild_id'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'bb_portal_modules',
				$this->table_prefix . 'bb_portal_config',
			],
		];
	}
}
