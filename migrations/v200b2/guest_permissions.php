<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Grant u_bbguild (view guild pages) to Guests group by default.
 * Board admins can revoke this in ACP > Permissions > Group permissions.
 */

namespace avathar\bbguild\migrations\v200b2;

class guest_permissions extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild\migrations\basics\permissions',
			'\avathar\bbguild\migrations\v200b2\release_2_0_0_b2',
		];
	}

	public function update_data()
	{
		return [
			['permission.permission_set', ['GUESTS', 'u_bbguild', 'group']],
		];
	}

	public function revert_data()
	{
		return [
			['permission.permission_unset', ['GUESTS', 'u_bbguild', 'group']],
		];
	}
}
