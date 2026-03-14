<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Add player_spec column to bb_players table.
 * This column was originally added by the WoW plugin but is used
 * in core queries, so it belongs in the core schema.
 */

namespace avathar\bbguild\migrations\v200b2;

class player_spec_column extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\v200b2\release_2_0_0_b2'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'bb_players', 'player_spec');
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_spec' => ['VCHAR:100', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_spec',
				],
			],
		];
	}
}
