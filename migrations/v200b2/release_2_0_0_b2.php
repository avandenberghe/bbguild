<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release 2.0.0-b2: schema fixes, player_spec column, guest permissions, version stamp.
 */

namespace avathar\bbguild\migrations\v200b2;

class release_2_0_0_b2 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild\migrations\basics\modules',
			'\avathar\bbguild\migrations\basics\permissions',
		];
	}

	public function effectively_installed()
	{
		return isset($this->config['bbguild_version'])
			&& version_compare($this->config['bbguild_version'], '2.0.0-b2', '>=');
	}

	public function update_schema()
	{
		$schema = [
			'change_columns' => [
				$this->table_prefix . 'bb_news' => [
					'bbcode_bitfield' => ['VCHAR:255', ''],
					'bbcode_options'  => ['UINT', 7],
				],
			],
		];

		// Add player_spec column for upgrades (fresh installs already have it in basics/schema)
		if (!$this->db_tools->sql_column_exists($this->table_prefix . 'bb_players', 'player_spec'))
		{
			$schema['add_columns'] = [
				$this->table_prefix . 'bb_players' => [
					'player_spec' => ['VCHAR:100', ''],
				],
			];
		}

		return $schema;
	}

	public function revert_schema()
	{
		return [
			'change_columns' => [
				$this->table_prefix . 'bb_news' => [
					'bbcode_bitfield' => ['VCHAR:20', ''],
					'bbcode_options'  => ['VCHAR:8', ''],
				],
			],
			'drop_columns' => [
				$this->table_prefix . 'bb_players' => [
					'player_spec',
				],
			],
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'set_version']]],
			['permission.permission_set', ['GUESTS', 'u_bbguild', 'group']],
		];
	}

	public function revert_data()
	{
		return [
			['permission.permission_unset', ['GUESTS', 'u_bbguild', 'group']],
			['config.remove', ['bbguild_version']],
		];
	}

	public function set_version()
	{
		$this->config->set('bbguild_version', '2.0.0-b2');
	}
}
