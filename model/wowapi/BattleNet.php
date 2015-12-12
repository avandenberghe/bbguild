<?php
/**
 * bbDKP WoW Battle.net API
 *
 * @package bbdkp v2.0
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author	  Andreas Vandenberghe <sajaki9@gmail.com>
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, 2015 Chris Saylor, Daniel Cannon, Andreas Vandenberghe
 * @link 	  https://dev.battle.net/
 * @link	  https://github.com/bbDKP
 *
 */

namespace sajaki\bbdkp\model\wowapi;

/**
 * Battle.net WoW API PHP SDK
 * 
 * @package bbdkp
 */
class BattleNet
{
	/**
	 * acceptable regions for WoW
	 * @var array
	 */
	protected $region = array(
		'us', 'eu', 'kr', 'tw', 'sea'
	);
	
	/**
	 * Implemented API's
	 * @var array
	 */
	protected $API = array(
		'guild', 'realm', 'character'
	);


	/**
	 * Realm object instance
	 *
	 */
	public $Realm;
	
	/**
	 * Guild object instance
	 *
	 * @var class
	 */
	public $Guild;

	
	/**
	 * Character object instance
	 *
	 * @var class
	 */
	public $Character;

	/**
	 * locale
	 *
	 * @var string
	 */
	public $locale;

	/**
	 * Battle.net API key
	 *
	 */
	public $apikey;

	/**
	 * WoWAPI Class constructor
	 * 
	 * @param string $API
	 * @param string $region
	 * @param string $apikey
	 * @param string $locale
     * @param string $privkey
	 *
	 */
	public function __construct($API, $region, $apikey, $locale, $privkey)
	{
		global $user, $phpEx, $phpbb_root_path;


		// check for correct API call
		if (!in_array($API, $this->API))
		{
			trigger_error($user->lang['WOWAPI_API_NOTIMPLEMENTED']);
		}

		if (!in_array($region, $this->region))
		{
			trigger_error($user->lang['WOWAPI_REGION_NOTALLOWED']);
		}

		$this->API = $API;
		$this->region = $region;

		switch ($this->API)
		{
			case 'realm':
				if (!class_exists('\bbdkp\controller\wowapi\Realm'))
				{
					require($phpbb_root_path . "includes/bbdkp/controller/wowapi/Realm.$phpEx");
				}
				$this->Realm = new \bbdkp\controller\wowapi\Realm($region);
				$this->Realm->apikey = $apikey;
				$this->Realm->locale = $locale;
				$this->Realm->privkey = $privkey;
				break;
			case 'guild':
				if (!class_exists('\bbdkp\controller\wowapi\Guild'))
				{
					require($phpbb_root_path . "includes/bbdkp/controller/wowapi/Guild.$phpEx");
				}
				$this->Guild = new \bbdkp\controller\wowapi\Guild($region);
				$this->Guild->apikey = $apikey;
				$this->Guild->locale = $locale;
				$this->Guild->privkey = $privkey;
				break;
			case 'character':
				if (!class_exists('\bbdkp\controller\wowapi\Character'))
				{
					require($phpbb_root_path . "includes/bbdkp/controller/wowapi/Character.$phpEx");
				}
				$this->Character = new \bbdkp\controller\wowapi\Character($region);
				$this->Character->apikey = $apikey;
				$this->Character->locale = $locale;
				$this->Character->privkey = $privkey;
				break;

		}

	}
}
