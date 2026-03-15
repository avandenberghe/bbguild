<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Add game_edition column to bb_guild for WoW Classic support.
 */

namespace avathar\bbguild\migrations\v200b3;

class add_game_edition extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild\migrations\basics\schema',
		];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'bb_guild', 'game_edition');
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'bb_guild' => [
					'game_edition' => ['VCHAR:20', 'retail'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'bb_guild' => [
					'game_edition',
				],
			],
		];
	}
}
