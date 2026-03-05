<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Portal data migration — seeds default module layout for guild_id=0 (template).
 * When a new guild is created, this layout is copied to the guild.
 */

namespace avathar\bbguild\migrations\v200a12;

class portal_data extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\v200a12\portal_schema'];
	}

	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(*) as cnt
			FROM ' . $this->table_prefix . 'bb_portal_modules
			WHERE guild_id = 0';
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count > 0;
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'seed_default_layout']]],
			['config.add', ['bbguild_portal_right_width', 200]],
		];
	}

	/**
	 * Seed the default portal layout (guild_id=0 template).
	 * Column numbers: top=1, center=2, right=3
	 */
	public function seed_default_layout()
	{
		$modules = [
			// Center column: MOTD first, then guild news
			['module_classname' => '\avathar\bbguild\portal\modules\motd',       'module_column' => 2, 'module_order' => 1, 'module_name' => 'BBGUILD_PORTAL_MOTD'],
			['module_classname' => '\avathar\bbguild\portal\modules\guild_news',     'module_column' => 2, 'module_order' => 2, 'module_name' => 'BBGUILD_PORTAL_GUILD_NEWS'],
			['module_classname' => '\avathar\bbguild\portal\modules\activity_feed', 'module_column' => 2, 'module_order' => 3, 'module_name' => 'BBGUILD_PORTAL_ACTIVITY_FEED'],

			// Right column: recruitment
			['module_classname' => '\avathar\bbguild\portal\modules\recruitment',   'module_column' => 3, 'module_order' => 1, 'module_name' => 'BBGUILD_PORTAL_RECRUITMENT'],
		];

		foreach ($modules as $module)
		{
			$sql_ary = array_merge($module, [
				'guild_id'            => 0,
				'module_image_src'    => '',
				'module_image_width'  => 16,
				'module_image_height' => 16,
				'module_group_ids'    => '',
				'module_status'       => 1,
			]);

			$sql = 'INSERT INTO ' . $this->table_prefix . 'bb_portal_modules ' .
				$this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);
		}
	}

	public function revert_data()
	{
		return [
			['config.remove', ['bbguild_portal_right_width']],
		];
	}
}
