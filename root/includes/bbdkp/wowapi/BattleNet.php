<?php
/**
 * Battle.net WoW API PHP SDK
 *
 * This software is not affiliated with Battle.net, and all references
 * to Battle.net and World of Warcraft are copyrighted by Blizzard Entertainment.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *   @package bbdkp
 * @author	  Andreas Vandenberghe <sajaki9@gmail.com>
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>  
 * @copyright Copyright (c) 2011, Chris Saylor, Daniel Cannon, Andreas Vandenberghe
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link 	  http://blizzard.github.com/api-wow-docs
 * @link	  https://github.com/bbDKP/WoWAPI
 * @version   1.0.4 
 */
namespace bbdkp\wowapi;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * Battle.net WoW API PHP SDK
 * 
 * @package bbdkp
 * @throws Exception If requirements are not met.
 */
class BattleNet
{
	/**
	 * acceptable regions for WoW
	 * @var array
	 */
	protected $region = array(
		'us', 'eu', 'kr', 'tw', 'cn', 'sea'
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
	 * WoWAPI Class constructor
	 * 
	 * @param string $API
	 * @param string $region
	 */
	public function __construct($API, $region) 
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
		
		switch ($API)
		{
			case 'realm':
				if (!class_exists('\bbdkp\wowapi\Realm')) 
				{
					require($phpbb_root_path . "includes/bbdkp/wowapi/API/Realm.$phpEx");
				}
				$this->Realm = new \bbdkp\wowapi\Realm($region);
				break;
			case 'guild':
				if (!class_exists('\bbdkp\wowapi\Guild')) 
				{
					require($phpbb_root_path . "includes/bbdkp/wowapi/API/Guild.$phpEx");
				}				
				$this->Guild = new \bbdkp\wowapi\Guild($region);
				break;
			case 'character':
				if (!class_exists('\bbdkp\wowapi\Character')) 
				{
					require($phpbb_root_path . "includes/bbdkp/wowapi/API/Character.$phpEx");
				}				
				$this->Character = new \bbdkp\wowapi\Character($region);
				break;
				
		}
	}
}
