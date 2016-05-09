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

use bbdkp\bbguild\model\admin\admin;

/**
 * Resource skeleton
 *
 *   @package bbguild
 */
abstract class battlenet_resource extends admin
{

	/**
	 * List of region urls
	 *
	 * @var string
	 */
	protected $api_url = array(
		'eu' => 'https://eu.api.battle.net/wow/',
		'us' => 'https://us.api.battle.net/wow/',
		'kr' => 'https://kr.api.battle.net/wow/',
		'tw' => 'https://tw.api.battle.net/wow/',
		'sea' => 'https://us.api.battle.net/wow/'
	);

	/**
	 * List of possible locales
	 *
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
	 */
	public $apikey;

	/**
	 * Battle.net private API key
	 */
	public $privkey;

	/**
	 * Methods allowed by this resource (or available).
	 *
	 * @var array
	 */
	protected $methods_allowed;

	/**
	 * @type int
	 */
	private $cacheTtl;

	/**
	 * @type
	 */
	public $cache;

	/**
	 * @type string
	 */
	protected $endpoint;

	/**
	 * realm api constructor
	 *
	 * @param string $region Server region
	 * @param \phpbb\cache\service $cache
	 * @param int                  $cacheTtl
	 *
	 */
	public function __construct(\phpbb\cache\service $cache, $region = 'us', $cacheTtl = 3600)
	{
		global $user;
		if (empty($this->methods_allowed))
		{
			trigger_error($user->lang['NO_METHODS']);
		}
		$this->region = $region;
		$this->cache = $cache;
		$this->cacheTtl = $cacheTtl;

	}

	/**
	 * Consumes the resource by method and returns the results of the request.
	 *
	 * @param  string $method Request method
	 * @param  array  $params Parameters
	 * @return array Request data
	 */
	public function consume($method, array $params)
	{
		global $user;

		//check if has api
		if ($this->apikey == '')
		{
			trigger_error($user->lang['WOWAPI_KEY_MISSING']);
		}

		//check if default locale is allowed given the guild region
		if (!in_array($this->locale, $this->locales_allowed[$this->region]))
		{
			if ($this->region != '')
			{
				//get standard locale for guild region
				$this->locale = $this->locales_allowed[$this->region][0];
			}
			else
			{
				trigger_error(sprintf($user->lang['WOWAPI_LOCALE_NOTALLOWED'], $this->locale));
			}
		}

		// either a valid method is required or an asterisk
		if (!in_array($method, $this->methods_allowed)  && !in_array('*', $this->methods_allowed) )
		{
			trigger_error($user->lang['WOWAPI_METH_NOTALLOWED']);
		}

		//get base url
		$requestUri = $this->api_url[$this->region];
		$requestUri .= $this->endpoint . '/'. $method;

		//append locale
		$requestUri .= '?locale=' . $this->locale;

		//process parameters
		if (isset($params['data']) && !empty($params['data']))
		{
			if (is_array($params['data']))
			{
				$requestUri .= http_build_query($params['data']);
			}
			else
			{
				$requestUri .= '&' . $params['data'];
			}

		}

		//append apikey
		$requestUri .= '&apikey=' . $this->apikey;
		$cachesignature = base64_encode($requestUri);

		if (! $data = $this->_getCachedResult($cachesignature))
		{
			$date = date('D, d M Y G:i:s T', time());
			$string_to_sign = "GET\n".$date."\n".$requestUri."\n";
			$signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->privkey, true));
			$header = array('Host: ' .$this->region, 'Date: ' . $date, 'Authorization: BNET ' . $this->apikey. ':' . $signature);
			$data = $this->curl($requestUri, $header, false, true);
			$this->cache->put($cachesignature, $data, $this->cacheTtl);
		}

		return $data;
	}

	/**
	 * Attempt to get a cache result based on a request URI
	 *
	 * @param string $cachesignature
	 * @return boolean
	 */
	protected function _getCachedResult($cachesignature)
	{
		if (! $this->cache->get($cachesignature))
		{
			return false;
		}
		return $this->cache->get($cachesignature);
	}

}
