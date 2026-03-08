<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release 2.0.0-b2: schema fix + version stamp.
 */

namespace avathar\bbguild\migrations\v200b2;

class release_2_0_0_b2 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\modules'];
	}

	public function effectively_installed()
	{
		return isset($this->config['bbguild_version'])
			&& version_compare($this->config['bbguild_version'], '2.0.0-b2', '>=');
	}

	public function update_schema()
	{
		return [
			'change_columns' => [
				$this->table_prefix . 'bb_news' => [
					'bbcode_bitfield' => ['VCHAR:255', ''],
					'bbcode_options'  => ['UINT', 7],
				],
			],
		];
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
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'set_version']]],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['bbguild_version']],
		];
	}

	public function set_version()
	{
		$this->config->set('bbguild_version', '2.0.0-b2');
	}
}
