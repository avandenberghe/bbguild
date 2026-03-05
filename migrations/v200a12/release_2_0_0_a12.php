<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release 2.0.0-b1 version stamp
 */

namespace avathar\bbguild\migrations\v200a12;

class release_2_0_0_a12 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguild\migrations\v200a11\release_2_0_0_a11',
			'\avathar\bbguild\migrations\v200a12\portal_schema',
			'\avathar\bbguild\migrations\v200a12\portal_data',
			'\avathar\bbguild\migrations\v200a12\portal_acp_module',
		];
	}

	public function effectively_installed()
	{
		return isset($this->config['bbguild_version'])
			&& version_compare($this->config['bbguild_version'], '2.0.0-b1', '>=');
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
			['config.update', ['bbguild_version', '2.0.0-a11']],
		];
	}

	public function set_version()
	{
		$this->config->set('bbguild_version', '2.0.0-b1');
	}
}
