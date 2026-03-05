<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Add guild_id column to bb_motd and bb_news tables for guild-scoped content.
 */

namespace avathar\bbguild\migrations\v200b2;

class motd_guild_id extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\v200a12\release_2_0_0_a12'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'bb_motd', 'guild_id');
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'bb_motd' => [
					'guild_id' => ['UINT', 0, 'after' => 'motd_id'],
				],
				$this->table_prefix . 'bb_news' => [
					'guild_id' => ['UINT', 0, 'after' => 'news_id'],
				],
			],
			'add_index' => [
				$this->table_prefix . 'bb_motd' => [
					'guild_id' => ['INDEX', ['guild_id']],
				],
				$this->table_prefix . 'bb_news' => [
					'guild_id' => ['INDEX', ['guild_id']],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_keys' => [
				$this->table_prefix . 'bb_motd' => ['guild_id'],
				$this->table_prefix . 'bb_news' => ['guild_id'],
			],
			'drop_columns' => [
				$this->table_prefix . 'bb_motd' => ['guild_id'],
				$this->table_prefix . 'bb_news' => ['guild_id'],
			],
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'set_existing_guild_ids']]],
		];
	}

	/**
	 * Set guild_id=1 on existing rows (they belong to the default guild).
	 */
	public function set_existing_guild_ids()
	{
		$this->db->sql_query('UPDATE ' . $this->table_prefix . 'bb_motd SET guild_id = 1 WHERE guild_id = 0');
		$this->db->sql_query('UPDATE ' . $this->table_prefix . 'bb_news SET guild_id = 1 WHERE guild_id = 0');
	}
}
