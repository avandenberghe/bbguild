<?php
/**
 * bbDKP WoW Battle.net API
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

use bbdkp\bbguild\model\api\battlenet_realm;
use bbdkp\bbguild\model\api\battlenet_guild;
use bbdkp\bbguild\model\api\battlenet_character;

/**
 * Battle.net WoW API PHP SDK
 *
 * @package bbguild
 */
class battle_net
{

	public $cache;
	/**
	 * acceptable regions for WoW
	*
	 * @var array
	 */
	protected $region = array(
	'us', 'eu', 'kr', 'tw', 'sea'
	);

	/**
	 * Implemented API's
	*
	 * @var array
	 */
	protected $api = array(
	'guild', 'realm', 'character'
	);


	/**
	 * Realm object instance
	 */
	public $Realm;

	/**
	 * Guild object instance
	 *
	 * @var battlenet_guild
	 */
	public $guild;


	/**
	 * Character object instance
	 *
	 * @var battlenet_character
	 */
	public $character;

	/**
	 * locale
	 *
	 * @var string
	 */
	public $locale;

	/**
	 * Battle.net API key
	 */
	public $apikey;
	/**
	 * @type int
	 */
	private $cacheTtl;

	/**
	 * BattleNet constructor.
	 *
	 * @param                      $API
	 * @param                      $region
	 * @param                      $apikey
	 * @param                      $locale
	 * @param                      $privkey
	 * @param                      $ext_path
	 * @param \phpbb\cache\service $cache
	 * @param int                  $cacheTtl
	 */
	public function __construct($API, $region, $apikey, $locale, $privkey, $ext_path,
	                            \phpbb\cache\service $cache, $cacheTtl = 3600)
	{
		global $user;


		// check for correct API call
		if (!in_array($API, $this->api))
		{
			trigger_error($user->lang['WOWAPI_API_NOTIMPLEMENTED']);
		}

		if (!in_array($region, $this->region))
		{
			trigger_error($user->lang['WOWAPI_REGION_NOTALLOWED']);
		}

		$this->api      = $API;
		$this->region   = $region;
		$this->ext_path = $ext_path;
		$this->cache    = $cache;
		$this->cacheTtl = $cacheTtl;

		switch ($this->api)
		{
		case 'realm':
			$this->Realm = new battlenet_realm($this->cache,$region, $this->cacheTtl);
			$this->Realm->apikey = $apikey;
			$this->Realm->locale = $locale;
			$this->Realm->privkey = $privkey;

			break;
		case 'guild':
			$this->guild          = new battlenet_guild($this->cache,$region, $this->cacheTtl);
			$this->guild->apikey  = $apikey;
			$this->guild->locale  = $locale;
			$this->guild->privkey = $privkey;
			break;
		case 'character':
			$this->character          = new battlenet_character($this->cache,$region, $this->cacheTtl);
			$this->character->apikey  = $apikey;
			$this->character->locale  = $locale;
			$this->character->privkey = $privkey;
			break;

		}

	}
}
