<?php
/**
 * bbGuild - ACP/UCP module migration
 *
 * @package   avathar\bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\migrations\basics;

class modules extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\permissions'];
	}

	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(module_id) AS cnt
			FROM ' . MODULES_TABLE . "
			WHERE module_basename = '" . $this->db->sql_escape('\avathar\bbguild\acp\main_module') . "'
				AND module_class = 'acp'";
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count > 0;
	}

	public function update_data()
	{
		return [
			// Categories
			['module.add', ['acp', 0, 'ACP_CAT_BBGUILD']],
			['module.add', ['acp', 'ACP_CAT_BBGUILD', 'ACP_BBGUILD_MAINPAGE']],
			['module.add', ['acp', 'ACP_CAT_BBGUILD', 'ACP_BBGUILD_PLAYER']],
			['module.add', ['ucp', 0, 'UCP_BBGUILD']],

			// ACP modules — main
			['module.add', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\main_module',
				'modes'           => ['panel', 'config', 'logs'],
			]]],
			['module.add', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\game_module',
				'modes'           => ['listgames', 'editgames', 'addfaction', 'addrace', 'addclass', 'addrole'],
			]]],

			// ACP modules — player
			['module.add', ['acp', 'ACP_BBGUILD_PLAYER', [
				'module_basename' => '\avathar\bbguild\acp\guild_module',
				'modes'           => ['addguild', 'editguild', 'listguilds'],
			]]],
			['module.add', ['acp', 'ACP_BBGUILD_PLAYER', [
				'module_basename' => '\avathar\bbguild\acp\player_module',
				'modes'           => ['addplayer', 'listplayers'],
			]]],
			// UCP module
			['module.add', ['ucp', 'UCP_BBGUILD', [
				'module_basename' => '\avathar\bbguild\ucp\bbguild_module',
				'modes'           => ['char', 'add'],
			]]],
		];
	}

	public function revert_data()
	{
		return [
			// Remove modules first
			['module.remove', ['ucp', 'UCP_BBGUILD', [
				'module_basename' => '\avathar\bbguild\ucp\bbguild_module',
			]]],
			['module.remove', ['acp', 'ACP_BBGUILD_PLAYER', [
				'module_basename' => '\avathar\bbguild\acp\player_module',
			]]],
			['module.remove', ['acp', 'ACP_BBGUILD_PLAYER', [
				'module_basename' => '\avathar\bbguild\acp\guild_module',
			]]],
			['module.remove', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\game_module',
			]]],
			['module.remove', ['acp', 'ACP_BBGUILD_MAINPAGE', [
				'module_basename' => '\avathar\bbguild\acp\main_module',
			]]],

			// Remove categories
			['module.remove', ['ucp', 0, 'UCP_BBGUILD']],
			['module.remove', ['acp', 'ACP_CAT_BBGUILD', 'ACP_BBGUILD_PLAYER']],
			['module.remove', ['acp', 'ACP_CAT_BBGUILD', 'ACP_BBGUILD_MAINPAGE']],
			['module.remove', ['acp', 0, 'ACP_CAT_BBGUILD']],
		];
	}
}
