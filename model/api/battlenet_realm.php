<?php
/**
 * Battle.net WoW API PHP SDK
 *
 * @package   bbguild v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki9@gmail.com>
 * @author    Chris Saylor
 * @author    Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, 2015 Chris Saylor, Daniel Cannon, Andreas Vandenberghe
 * @link      https://dev.battle.net/
 * @link      https://github.com/bbDKP
 */

namespace bbdkp\bbguild\model\api;

use bbdkp\bbguild\model\api\battlenet_resource;

/**
 * Realm resource.
 *
 *   @package bbguild
 */
class battlenet_realm extends battlenet_resource
{
	/**
	 * allowed realm api methods
	 *
	 * @var array
	 */
	protected $methods_allowed = array(
		'status'
	);

	/**
	 * Get status results for all realms.
	 *
	 * @return array
	 */
	public function getAllRealmStatus()
	{
		return $this->consume('status');
	}

	/**
	 * Get status results for specified realm(s).
	 *
	 * @param  mixed $realms String or array of realm(s)
	 * @return mixed
	 */

	public function getRealmStatus(array $realms)
	{
		global $user;
		$data = array();

		if (count($realms) == 0)
		{
			trigger_error($user->lang['WOWAPI_NO_REALMS']);
		}
		else if (!is_array($realms))
		{
			$data = $this->consume(
				'status', array(
					'data' => 'realm='.$realms
				)
			);
		}
		else
		{
			$realm_str = 'realms=';
			foreach ($realms as $key => $realm)
			{
				$realm_str .= ($key == 0 ? '' : ',') . rawurlencode($realm);
			}
			$data = $this->consume(
				'status', array(
					'data' => $realm_str
				)
			);
		}
		return $data;
	}
}
