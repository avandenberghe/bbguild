<?php
/**
 * bbDKP Admin class file
 *
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\model\admin;

/**
 *
 * bbDKP Admin foundation
 *   @package bbdkp
 */
class Admin
{
    private $phpbb_root_path;
    private $php_ext;
    private $path;

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
     * extension path
     * @var
     */
    public $ext_path;

    /**
     * Admin class constructor
     */
    public function __construct()
    {
        global $user, $phpEx, $phpbb_extension_manager;
        $this->ext_path = $phpbb_extension_manager->get_extension_path('sajaki/bbdkp', true);
        $user->add_lang_ext('sajaki/bbdkp', array('dkp_admin','dkp_common'));
        include_once($this->ext_path . 'model/admin/constants_bbdkp.' . $phpEx);

        // Check for required extensions
        if (!function_exists('curl_init'))
        {
            trigger_error($user->lang['CURL_REQUIRED'], E_USER_WARNING);
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

        $a = $user;

        //$boardtime = getdate(time() + $user->timezone + $user->dst - date('Z'));
        //$boardtime = getdate(time());
        $boardtime = (!empty($user->time_now) && is_int($user->time_now)) ? $user->time_now : time();
        $this->time = $boardtime[0];

        if (!class_exists('\sajaki\bbdkp\model\games\Game'))
        {
            require("{$this->ext_path}/model/games/Game.$phpEx");
        }
        //$listgames = new \sajaki\bbdkp\model\games\Game;
        // $this->games = $listgames->games;
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

        $regcode = hash("sha256", serialize(array($regdata['domainname'],$regdata['phpbbversion'], $regdata['bbdkpversion'])));

        // bbdkp registration url
        $url = "https://www.avathar.be/services/registerbbdkp.php";
        // Create URL parameter string
        $fields_string = '';
        foreach( $regdata as $key => $value )
        {
            $fields_string .= $key.'='.$value.'&';
        }
        $fields_string .= 'regcode='.$regcode;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, 4 );
        curl_setopt( $ch, CURLOPT_HEADER , false);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
        $result = curl_exec($ch);
        curl_close( $ch );

        $this->get_register_request($regdata, $url, $regcode);
    }


    /**
     * GET reauests for registration ID
     * @param array $regdata
     * @param string $url
     * @param string $regcode
     */
    private final function get_register_request($regdata, $url, $regcode)
    {
        global $cache, $config;

        $fields_string = '';
        foreach( $regdata as $key => $value )
        {
            $fields_string .= $key.'='.$value.'&';
        }
        $fields_string .= 'regcode='.$regcode;

        $url .= '?' . $fields_string;

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
    public final function get_productversion($force_update = false, $warn_fail = false, $ttl = 86400)
    {
        global $user, $cache;

        //get latest productversion from cache
        $latest_version_a = $cache->get('latest_bbdkp');

        //if update is forced or cache expired then make the call to refresh latest productversion
        if ($latest_version_a === false || $force_update)
        {
            $data = $this->curl(BBDKP_VERSIONURL . 'bbdkp.json', false, false, false);
            if (empty($data))
            {
                $cache->destroy('latest_bbdkp');
                if ($warn_fail)
                {
                    trigger_error($user->lang['VERSION_NOTONLINE'], E_USER_WARNING);
                }
                return false;
            }

            $response = $data['response'];
            $latest_version = json_decode($response, true);
            $latest_version_a = $latest_version['stable']['3.1']['current'];

            //put this info in the cache
            $cache->put('latest_bbdkp' , $latest_version_a, $ttl);
        }

        return $latest_version_a;
    }

    private function version_check(\phpbb\extension\metadata_manager $md_manager, $force_update = false, $force_cache = false)
    {
        global $cache, $config, $user;
        $meta = $md_manager->get_metadata('all');
        if (!isset($meta['extra']['version-check']))
        {
            throw new \RuntimeException($this->user->lang('NO_VERSIONCHECK'), 1);
        }
        $version_check = $meta['extra']['version-check'];
        $version_helper = new \phpbb\version_helper($cache, $config, new \phpbb\file_downloader(), $user);
        $version_helper->set_current_version($meta['version']);
        $version_helper->set_file_location($version_check['host'], $version_check['directory'], $version_check['filename']);
        $version_helper->force_stability($this->config['extension_force_unstable'] ? 'unstable' : null);
        return $updates = $version_helper->get_suggested_updates($force_update, $force_cache);
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
        global $request;
        $uri_order = ( isset($_GET[$arg]) ) ? $request->variable($arg, 0.0) : $defaultorder;

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
        $logs = \sajaki\bbdkp\model\admin\log::Instance();
        return $logs->log_insert($values);

    }

}

?>
