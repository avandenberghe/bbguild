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
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>
 * @author	  Andreas Vandenberghe <sajaki9@gmail.com>
 * @copyright Copyright (c) 2011, Chris Saylor, Daniel Cannon,  Andreas Vandenberghe
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link	  https://github.com/bbDKP/WoWAPI
 * @link 	  http://blizzard.github.com/api-wow-docs
 * @version   1.0.4
 */
namespace bbdkp\controller\wowapi;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (! defined('EMED_BBDKP'))
{
	$user->add_lang(array('mods/dkp_admin'));
	\trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}


/**
 * Resource skeleton
 *
 *   @package bbdkp
 */
abstract class Resource extends \bbdkp\admin\Admin
{

	/**
	 * List of region urls
	 * @var string
	 */
	protected $api_url = array(
		'eu' => 'https://eu.api.battle.net/wow/',
		'us' => 'https://us.api.battle.net/wow/',
		'kr' => 'https://kr.api.battle.net/wow/',
		'tw' => 'https://tw.api.battle.net/wow/',
		'sea' => 'https://sea.api.battle.net/wow/'
	);

	/**
	 * List of possible locales
	 * @var string
	 */
	protected $locales_allowed = array(
		'eu' => array('en_GB', 'de_DE', 'es_ES', 'fr_FR', 'it_IT', 'pl_PL', 'pt_PT', 'ru_RU'),
		'us' => array('en_US', 'es_MX', 'pt_BR'),
		'kr' => array('ko_KR'),
		'tw' => array('zh_TW'),
		'sea' => array('en_US'),
	);

	/**
	 * Battlenet region
	 *
	 * @var string
	 */
	public $region;

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
	 * Methods allowed by this resource (or available).
	 *
	 * @var array
	 */
	protected $methods_allowed;

	/**
	 * realm api constructor
	 * @param string $region Server region
	 */
	public function __construct($region='us')
	{
		global $user;

		if (empty($this->methods_allowed))
		{
			trigger_error($user->lang['NO_METHODS']);
		}
		$this->region = $region;
	}

	/**
	 * Consumes the resource by method and returns the results of the request.
	 *
	 * @param string $method Request method
	 * @param array $params Parameters
	 * @return array Request data
	 */
	public function consume($method, $params=array())
	{
		global $user;

		//check if has api
		if($this->apikey == '')
		{
			trigger_error($user->lang['WOWAPI_KEY_MISSING']);
		}

		//check if locale is allowed
		if (!in_array($this->locale, $this->locales_allowed[$this->region] ))
		{
			trigger_error($user->lang['WOWAPI_LOCALE_NOTALLOWED']);
		}

		// either a valid method is required or an asterisk
		if (!in_array($method, $this->methods_allowed)  && !in_array('*', $this->methods_allowed) )
		{
			trigger_error($user->lang['WOWAPI_METH_NOTALLOWED']);
		}

		//get base url
		$url = $this->api_url[$this->region];

		//append method
		$classname = get_class($this);
		if (preg_match('@\\\\([\w]+)$@', $classname, $matches))
		{
			$classname = strtolower($matches[1]);
		}

		$url .= $classname . '/'. $method;

		//process parameters
		if (isset($params['data']) && !empty($params['data']))
		{
			if (is_array($params['data']))
			{
				$optfields = '';
				foreach($params['data'] as $key => $value)
				{
					$optfields .= $key.'='.$value.'&';
				}
				$optfields = rtrim($data, '&');
			}
			else
			{
				$optfields = $params['data'];
			}

			$url .= '&' . $optfields;
		}

		//append locale
		$url .= '&locale=' . $this->locale;

		//append apikey
		$url .= '&apikey=' . $this->apikey;

		$data = $this->Curl($url, false, false, true);
		return $data;
	}


}