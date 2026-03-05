<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Remove standalone portal ACP module (merged into Settings page).
 */

namespace avathar\bbguild\migrations\v200b2;

class remove_portal_acp_module extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\v200b1\release_2_0_0_b1'];
	}

	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(module_id) AS cnt
			FROM ' . MODULES_TABLE . "
			WHERE module_basename = '" . $this->db->sql_escape('\avathar\bbguild\acp\portal_module') . "'
				AND module_class = 'acp'";
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count === 0;
	}

	public function update_data()
	{
		return [
			['module.remove', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\portal_module',
			]]],
		];
	}

	public function revert_data()
	{
		return [
			['module.add', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\portal_module',
				'modes'           => ['portal'],
			]]],
		];
	}
}
