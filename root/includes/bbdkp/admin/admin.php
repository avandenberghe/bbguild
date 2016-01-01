<?php
/**
 * bbDKP Admin class file
 *
 *   @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4.5
 *
 */
namespace bbdkp\admin;
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\admin\log'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin/log.$phpEx");
}
/**
 *
 * bbDKP Admin foundation
 *   @package bbdkp
 */
class Admin
{
	/**
	 * bbdkp timestamp
	 * @var integer
	 */
    public $time = 0;
    /**
     * game ragions
     * @var array
     */
    public $regions;

    /**
     * supported languages. The game related texts (class names etc) are not stored in language files but in the database.
     * supported languages are en, fr, de : to add a new language you need to a) make language files b) make db installers in new language c) adapt this array
     *
     * @var array
     */
    public $languagecodes;

    /**
     * bbtips is installed ?
     * @var boolean
     */
    public $bbtips = false;

    /**
     * installed games
     * @var array
     */
    public $games;

    /**
     * Admin class constructor
     */
	public function __construct()
	{
		global $phpbb_root_path, $phpEx, $user;

		$user->add_lang ( array ('mods/dkp_admin' ) );
		$user->add_lang ( array ('mods/dkp_common' ) );

		if(!defined("EMED_BBDKP"))
		{
			trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
		}

		// Check for required extensions
		if (!function_exists('curl_init'))
		{
			trigger_error($user->lang['CURL_REQUIRED'], E_USER_WARNING);
		}

		if (!function_exists('json_decode'))
		{
			trigger_error($user->lang['JSON_REQUIRED'], E_USER_WARNING);
		}

		$this->regions = array(
				'cn' => $user->lang['REGIONCN'],
				'eu' => $user->lang['REGIONEU'],
				'kr' => $user->lang['REGIONKR'],
				'sea' => $user->lang['REGIONSEA'],
				'tw' => $user->lang['REGIONTW'],
				'us' => $user->lang['REGIONUS'],
				);

        //sort alphabetically
        asort($this->regions);

		$this->languagecodes = array(
				'de' => $user->lang['LANG_DE'],
				'en' => $user->lang['LANG_EN'],
				'fr' => $user->lang['LANG_FR'],
                'it' => $user->lang['LANG_IT']
        );

	    $boardtime = getdate(time() + $user->timezone + $user->dst - date('Z'));

	    $this->time = $boardtime[0];

        if (!class_exists('\bbdkp\controller\games\Game'))
        {
            require("{$phpbb_root_path}includes/bbdkp/controller/games/Game.$phpEx");
        }
        $listgames = new \bbdkp\controller\games\Game;
        $this->games = $listgames->games;
        unset($listgames);

	}

    /**
     * creates a unique key, used as adjustments, import, items and raid identifier
     *
     * @param string $part1
     * @param string $part2
     * @param string $part3
     * @return string $group_key
     */
    public final function gen_group_key($part1, $part2, $part3)
    {
			// Get the first 10-11 digits of each password_hash
			$part1 = substr(password_hash($part1, PASSWORD_DEFAULT), 0, 10);
			$part2 = substr(password_hash($part2, PASSWORD_DEFAULT), 0, 11);
			$part3 = substr(password_hash($part3, PASSWORD_DEFAULT), 0, 11);
			// Group the hashes together and create a new hash based on uniqid()
			$group_key = $part1 . $part2 . $part3;
			$group_key = password_hash(uniqid($group_key), PASSWORD_DEFAULT);

			return $group_key;
    }

    /**
	 * connects to remote site and gets xml or html using Curl
	 * @param char $url
	 * @param bool $return_Server_Response_Header default false
	 * @param bool $loud default false
	 * @param bool $json default true
	 * @return array response
     */
  	public final function curl($url, $return_Server_Response_Header = false, $loud= false, $json=true)
	{

		global $user;

        $data = array(
            'response'		    => '',
            'response_headers'  => '',
            'error'				=> '',
        );

		if ( function_exists ( 'curl_init' ))
		{
			 /* Create a CURL handle. */
			if (($curl = curl_init($url)) === false)
			{
				trigger_error('curl_init Failed' , E_USER_WARNING);
			}

			// set options
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_FOLLOWLOCATION, true,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_VERBOSE => true,
				CURLOPT_HEADER => $return_Server_Response_Header,
                CURLOPT_HEADER => false
			));

			$response = curl_exec($curl);
			$headers = curl_getinfo($curl);

            if($response === FALSE || $response === "" )
            {
                trigger_error(curl_error($curl), E_USER_WARNING);
            }
            else
            {
                $data = array(
                    'response'		    => $json && $this->isJSON($response) ? json_decode($response, true) : $response,
                    'response_headers'  => (array) $headers,
                    'error'				=> '',
                );
            }

			curl_close ($curl);
			return $data;

		}

		//report errors?
		if($loud == true)
		{
			trigger_error($data['error'], E_USER_WARNING);
		}
		return $data['response'];

	}

    /**
     * @param $string
     * @return boolcheck if is json
     */
    function isJSON($string){
        return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * sends POST request to bbdkp.com for registration
     * @param array $regdata
     */
    public final function post_register_request($regdata)
    {
        $rndhash = base_convert(mt_rand(5, 60466175) . mt_rand(5, 60466175), 10, 36);
        // bbdkp registration url
        $url = "http://www.avathar.be/services/registerbbdkp.php";
        // Create URL parameter string
        $fields_string = '';
        foreach( $regdata as $key => $value )
        {
            $fields_string .= $key.'='.$value.'&';
        }
        $fields_string .= 'rndhash='.$rndhash;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, 4 );
        curl_setopt( $ch, CURLOPT_HEADER , false);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
        $result = curl_exec($ch);
        curl_close( $ch );

        $this->get_register_request($rndhash);
    }

    /**
     * GET requests for registration ID
     * @param array $regdata
     * @param string $url
     * @param string $regcode
     */
    private final function get_register_request($regcode)
    {
        global $cache, $config;
        $url = 'http://www.avathar.be/services/registerbbdkp.php?rndhash=' . $regcode;
        $data = $this->Curl($url, 'GET');
        $regID = isset($data['response']) ? $data['response']['registration']: '';
        $config->set('bbdkp_regid', $regID, true);
        $cache->destroy('config');
        trigger_error('Registration Successful : ' . $config['bbdkp_regid'], E_USER_NOTICE );
    }


	/**
	 * retrieve latest bbdkp productversion
	 * @param string $product productname
	 * @param bool $force_update Ignores cached data. Defaults to false.
	 * @param bool $warn_fail Trigger a warning if obtaining the latest version information fails. Defaults to false.
	 * @param int $ttl Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
	 * @return string | false Version info on success, false on failure.
 	 */
	public final function get_productversion($product, $force_update = false, $warn_fail = false, $ttl = 86400)
	{
		global $cache;
		//get latest productversion from cache
        $latest = $cache->get('version_' . $product);

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest === false || $force_update)
		{
			$errstr = '';

			$data = $this->curl(BBDKP_VERSIONURL . 'version_' . $product .'.txt' , false, false, false);

			if (empty($data))
			{
				$cache->destroy($product. '_version');
				if ($warn_fail)
				{
					trigger_error($errstr, E_USER_WARNING);
				}
				return false;
			}
            $latest = $data['response'];
			//put this info in the cache
			$cache->put('version_' . $product , $latest, $ttl);
		}
		return $latest;
	}

    /**
     * get plugin info
     * @param bool $force_update
     * @param int $ttl
     * @return array|bool
     */
    public final function get_plugin_info($force_update = false, $ttl = 86400)
	{
		global $cache, $db;
		//get latest productversion from cache
		$plugins = $cache->get('bbdkpplugins');

		//if update is forced or cache expired then make the query to refresh latest productversion
		if ($plugins === false || $force_update)
		{
			$plugins = array();
			$sql = 'SELECT name, value, version, installdate FROM ' . BBDKPPLUGINS_TABLE . ' ORDER BY installdate DESC ';
			$result = $db->sql_query ($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$data = $this->curl(BBDKP_VERSIONURL . 'version_' . $row['name'] .'.txt' , false, false, false);

				//get latest
				$plugins[$row['name']] = array(
					'name' => $row['name'],
					'value' => $row['value'],
					'version' => $row['version'],
					'latest' =>  empty($info) ? '?' : $data['response'],
					'installdate' => $row['installdate']
				);
			}
			$db->sql_freeresult($result);

			$cache->destroy('bbdkpplugins');
			$cache->put( 'bbdkpplugins', $plugins, $ttl);
		}
		return $plugins;

	}

	/**
	 * Pagination function altered from functions.php used in viewmember.php because we need two linked paginations
	 *
	 * @param string $base_url
	 * @param integer $num_items
	 * @param integer $per_page
	 * @param integer $start_item
	 * @param boolean $add_prevnext_text
	 * @param string $tpl_prefix  using different pagination blocks at one page
	 * @return boolean|string
	 */
	public final function generate_pagination2($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $tpl_prefix = '')
	{
		global $template, $user;

		// Make sure $per_page is a valid value
		$per_page = ($per_page <= 0) ? 1 : $per_page;
		$total_pages = ceil($num_items / $per_page);

		$separator = '<span class="page-sep">' . $user->lang['COMMA_SEPARATOR'] . '</span>';

		if ($total_pages == 1 || !$num_items)
		{
			return false;
		}

		$on_page = floor($start_item / $per_page) + 1;
		$url_delim = (strpos($base_url, '?') === false) ? '?' : '&amp;';

		$page_string = ($on_page == 1) ? '<strong>1</strong>' : '<a href="' . $base_url . '">1</a>';

		if ($total_pages > 5)
		{
			$start_cnt = min(max(1, $on_page - 4), $total_pages - 5);
			$end_cnt = max(min($total_pages, $on_page + 4), 6);

			$page_string .= ($start_cnt > 1) ? ' ... ' : $separator;

			for ($i = $start_cnt + 1; $i < $end_cnt; $i++)
			{
				$page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
				if ($i < $end_cnt - 1)
				{
					$page_string .= $separator;
				}
			}

			$page_string .= ($end_cnt < $total_pages) ? ' ... ' : $separator;
		}
		else
		{
			$page_string .= $separator;

			for ($i = 2; $i < $total_pages; $i++)
			{
				$page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
				if ($i < $total_pages)
				{
					$page_string .= $separator;
				}
			}
		}

		$page_string .= ($on_page == $total_pages) ? '<strong>' . $total_pages . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($total_pages - 1) * $per_page) . '">' . $total_pages . '</a>';
		if ($add_prevnext_text)
		{
			if ($on_page != 1)
			{
				$page_string = '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($on_page - 2) * $per_page) . '">' . $user->lang['PREVIOUS'] . '</a>&nbsp;&nbsp;' . $page_string;
			}

			if ($on_page != $total_pages)
			{
				$page_string .= '&nbsp;&nbsp;<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . ($on_page * $per_page) . '">' . $user->lang['NEXT'] . '</a>';
			}
		}

		$template->assign_vars(array(
				$tpl_prefix . 'BASE_URL'		=> $base_url,
				'A_' . $tpl_prefix . 'BASE_URL'	=> addslashes($base_url),
				$tpl_prefix . 'PER_PAGE'		=> $per_page,

				$tpl_prefix . 'PREVIOUS_PAGE'	=> ($on_page == 1) ? '' : $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($on_page - 2) * $per_page),
				$tpl_prefix . 'NEXT_PAGE'		=> ($on_page == $total_pages) ? '' : $base_url . "{$url_delim}" . $tpl_prefix  . "=" . ($on_page * $per_page),
				$tpl_prefix . 'TOTAL_PAGES'		=> $total_pages,
		));

		return $page_string;
	}

    /**
     * Switch array order
     * Switches the sorting order of a supplied array, prerserving key values
     * The array is in the format [number][0/1] (0 = the default, 1 = the opposite)
     * Returns an array containing the code to use in an SQL query and the code to
     * use to pass the sort value through the URI.  URI is in the format
     * checks that the 2nd element is either 0 or 1
     *
     * @param array $sort_order
     * @param string $arg
     * @param int $forcedorder
     * @return mixed
     */
    public final function switch_order($sort_order, $arg = URI_ORDER, $defaultorder = '0.0')
	{
		$uri_order = ( isset($_GET[$arg]) ) ? request_var($arg, 0.0) : $defaultorder;

		$uri_order = explode('.', $uri_order);

		$element1 = ( isset($uri_order[0]) ) ? $uri_order[0] : 0;
		$element2 = ( isset($uri_order[1]) ) ? $uri_order[1] : 0;
		// check if correct input
		if ( $element2 != 1 )
		{
			$element2 = 0;
		}

		foreach((array) $sort_order as $key => $value )
		{
			if ( $element1 == $key )
			{
				$uri_element2 = ( $element2 == 0 ) ? 1 : 0;
			}
			else
			{
				$uri_element2 = 0;
			}
			$current_order['uri'][$key] = $key . '.' . $uri_element2;
		}

		$current_order['uri']['current'] = $element1.'.'.$element2;
		$current_order['sql'] = $sort_order[$element1][$element2];

		return $current_order;
	}


	/**
	 * remove end of a string
	 * @param string $string
	 * @param string $stringToRemove
	 * @return string
	 */
	public function removeFromEnd($string, $stringToRemove)
	{
		$stringToRemoveLen = strlen($stringToRemove);
		$stringLen = strlen($string);
		$pos = $stringLen - $stringToRemoveLen;
		$out = substr($string, 0, $pos);
		return $out;
	}

    /**
     * makes an entry in the bbdkp log table
     * log_action is an xml containing the log
     *
     * log_id    int(11)        UNSIGNED    No        auto_increment
     * log_date    int(11)            No    0
     * log_type    varchar(255)    utf8_bin        No
     * log_action    text    utf8_bin        No
     * log_ipaddress    varchar(15)    utf8_bin        No
     * log_sid    varchar(32)    utf8_bin        No
     * log_result    varchar(255)    utf8_bin        No
     * log_userid    mediumint(8)    UNSIGNED    No    0
     *
     * @param array $values
     * @return bool
     */
	public final function log_insert($values = array())
	{
		// log
		$logs = \bbdkp\admin\log::Instance();
		return $logs->log_insert($values);

	}

}

?>
