<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Permissions migration
 */

namespace avathar\bbguild\migrations\basics;

class permissions extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\config'];
	}

	public function effectively_installed()
	{
		$sql = 'SELECT COUNT(auth_option_id) AS cnt
			FROM ' . ACL_OPTIONS_TABLE . "
			WHERE auth_option = 'a_bbguild'";
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count > 0;
	}

	public function update_data()
	{
		$data = [
			['permission.add', ['a_bbguild', true]],
			['permission.add', ['u_bbguild', true]],
			['permission.add', ['u_charclaim', true]],
			['permission.add', ['u_charadd', true]],
			['permission.add', ['u_chardelete', true]],
			['permission.add', ['u_charupdate', true]],
		];

		// Admin roles can access bbguild ACP
		if ($this->role_exists('ROLE_ADMIN_FULL'))
		{
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_FULL', 'a_bbguild']];
		}

		if ($this->role_exists('ROLE_ADMIN_STANDARD'))
		{
			$data[] = ['permission.permission_set', ['ROLE_ADMIN_STANDARD', 'a_bbguild']];
		}

		// Standard user can view bbguild pages
		if ($this->role_exists('ROLE_USER_STANDARD'))
		{
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', ['u_bbguild']]];
		}

		// Full user can view pages and manage characters
		if ($this->role_exists('ROLE_USER_FULL'))
		{
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', ['u_bbguild', 'u_charclaim', 'u_charadd', 'u_chardelete', 'u_charupdate']]];
		}

		return $data;
	}

	public function revert_data()
	{
		return [
			['permission.remove', ['u_charupdate']],
			['permission.remove', ['u_chardelete']],
			['permission.remove', ['u_charadd']],
			['permission.remove', ['u_charclaim']],
			['permission.remove', ['u_bbguild']],
			['permission.remove', ['a_bbguild']],
		];
	}

	protected function role_exists($role)
	{
		$sql = 'SELECT COUNT(role_id) AS role_count
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_count = $this->db->sql_fetchfield('role_count');
		$this->db->sql_freeresult($result);

		return $role_count > 0;
	}
}
