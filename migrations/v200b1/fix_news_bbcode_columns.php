<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Fix bb_news bbcode columns to match phpBB standard types.
 */

namespace avathar\bbguild\migrations\v200b1;

class fix_news_bbcode_columns extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\schema'];
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
}
