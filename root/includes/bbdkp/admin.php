<?php
namespace bbdkp;
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;
require_once ("{$phpbb_root_path}includes/bbdkp/iAdmin.$phpEx");

/**
 * bbDKP Admin foundation
 * 
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * 
 */
class Admin implements \bbdkp\iAdmin
{
    public $time = 0;
    public $bbtips = false; 
    public $games; 
    
	public function __construct()
	{
		global $user, $phpbb_root_path, $phpEx, $config, $user; 
		
		$user->add_lang ( array ('mods/dkp_admin' ) );
		$user->add_lang ( array ('mods/dkp_common' ) );
		if(!defined("EMED_BBDKP"))
	    {
	        trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
	    }
	    
	    $this->games = array (
			'wow' 	=> $user->lang ['WOW'], 
			'lotro' => $user->lang ['LOTRO'], 
			'eq' 	=> $user->lang ['EQ'], 
			'daoc' 	=> $user->lang ['DAOC'], 
			'vanguard' => $user->lang ['VANGUARD'], 
			'eq2' 	=> $user->lang ['EQ2'], 
			'warhammer' => $user->lang ['WARHAMMER'], 
			'aion' 	=> $user->lang ['AION'], 
			'FFXI' 	=> $user->lang ['FFXI'], 
			'rift' 	=> $user->lang ['RIFT'], 
			'swtor' => $user->lang ['SWTOR'], 
			'lineage2' => $user->lang ['LINEAGE2'],
	    	'tera' 	=> $user->lang ['TERA'],
	    	'gw2' 	=> $user->lang ['GW2'],
	    );
	    
	    $boardtime = array(); 
	    $boardtime = getdate(time() + $user->timezone + $user->dst - date('Z'));
	    $this->time = $boardtime[0]; 
	    $this->fv = new Form_Validate;
	    
	    if (isset($config['bbdkp_plugin_bbtips_version']))
	    {
	    	//check if config value and parser file exist.
	    	if($config['bbdkp_plugin_bbtips_version'] >= '0.3.1' && file_exists($phpbb_root_path. 'includes/bbdkp/bbtips/parse.' . $phpEx))
	    	{
	    		$this->bbtips = true;
	    	}
	    }
	   
	    
	}
    
    /**
	 * creates a unique key, used as adjustments, import, items and raid identifier
	 *
	 * @param $part1
	 * @param $part2
 	 * @param $part3
 	 * 
 	 * @return $group_key
	 */
    public function gen_group_key($part1, $part2, $part3)
    {
        // Get the first 10-11 digits of each md5 hash
        $part1 = substr(md5($part1), 0, 10);
        $part2 = substr(md5($part2), 0, 11);
        $part3 = substr(md5($part3), 0, 11);
        
        // Group the hashes together and create a new hash based on uniqid()
        $group_key = $part1 . $part2 . $part3;
        $group_key = md5(uniqid($group_key));
        
        return $group_key;
    }

    /**
	 * connects to remote site and gets xml or html using Curl, fopen, or fsockopen
	 * @param char $url
	 * @param char $loud default false
	 * @return xml
	 */
  public function read_php($url, $return_Server_Response_Header = false, $loud= false) 
	{
		$errmsg1= '';
		$errmsg2= '';
		$errmsg3= '';
		$errstrfsk='';
		$read_phperror=false;
		$xml_data= '';
	    
	    if ( function_exists ( 'curl_init' )) 
		{
			 /* Create a CURL handle. */
			if (($curl = curl_init($url)) === false)
			{
				trigger_error('curl_init Failed' , E_USER_WARNING);   
			}
			
			$useragent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9) Gecko/2008061004 Firefox/3.0';
			//$useragent='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0';
			//$useragent="Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";
			@curl_setopt ( $curl, CURLOPT_USERAGENT, $useragent );

			@curl_setopt ( $curl, CURLOPT_URL, $url );
			if ($return_Server_Response_Header == true)
			{   
			    // only for html, leave this default false if you want xml (like from armory or wowhead items)
    			@curl_setopt ( $curl, CURLOPT_HEADER, 1);
			}
			else 
			{   
			    // only for html, leave this default false if you want xml (like from armory or wowhead items)
    			@curl_setopt ( $curl, CURLOPT_HEADER, 0);
			}
			
			@curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
		    
			$headers = array(
				'Accept: text/xml,application/xml,application/xhtml+xml',
				'Accept-Charset: utf-8,ISO-8859-1'
				);
			@curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			
			
			if (!(ini_get("safe_mode") || ini_get("open_basedir"))) 
			{
				@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			}
			
			@curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
			
			
			if (curl_errno ( $curl )) 
			{
				$errnum = curl_errno ($curl);
				/*
                      CURLE_OK = 0,
                      CURLE_UNSUPPORTED_PROTOCOL,     1
                      CURLE_FAILED_INIT,              2
                      CURLE_URL_MALFORMAT,            3
                      CURLE_URL_MALFORMAT_USER,       4 - NOT USED
                      CURLE_COULDNT_RESOLVE_PROXY,    5
                      CURLE_COULDNT_RESOLVE_HOST,     6
                      CURLE_COULDNT_CONNECT,          7
                      CURLE_FTP_WEIRD_SERVER_REPLY,   8
                    */
				switch ($errnum) 
				{
				    case "0" :
				         $read_phperror = false; 
				        
					case "28" :
				        $read_phperror = true; 
					    $errmsg1 = 'cURL error :' . $url . ": No response after 30 second timeout : err " . $errnum . "  ";
						break;
					case "1" :
				        $read_phperror = true;
				        $errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : UNSUPPORTED_PROTOCOL ";					
						break;
					case "2" :
   				        $read_phperror = true;
						$errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : FAILED_INIT ";				
						break;
					case "3" :
   				        $read_phperror = true;				    
					    $errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : URL_MALFORMAT ";					
						break;
					case "5" :
   				        $read_phperror = true;
						$errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : COULDNT_RESOLVE_PROXY ";
						break;
					case "6" :
   				        $read_phperror = true;
					    $errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : COULDNT_RESOLVE_HOST ";		
						break;
					case "7" :
   				        $read_phperror = true;
					    $errmsg1 = 'cURL error :' . $url . " : error " . $errnum . " : COULDNT_CONNECT ";
				}
			}
			$xml_data = @curl_exec ($curl);
			@curl_close ($curl);
		}
		
		if ( strlen (rtrim ($xml_data) ) == 0) 
		{
		
			// for file_get_contents to work allow_url_fopen must be set
		    // safe mode must be OFF
			if (@ini_get('allow_url_fopen') and !(@ini_get("safe_mode"))) 
			{
				ini_set ( 'user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko/2008052906 Firefox/3.0' );
				$xml_data = @file_get_contents (rtrim ($url));
				$status_code = isset($http_response_header [0]) ? $http_response_header [0] : 0; 
				switch ($status_code) 
				{
					case 200 :
					    $read_phperror = false; 
					    // success
						break;
					case 503 :
					    $read_phperror = true; 
						$errmsg2 = 'file_get_contents error : HTTP error status 503 :  Service unavailable. An internal problem prevented Blizzard from returning Armory data to you.';				
						break;
					case 403 :
					    $read_phperror = true; 
						$errmsg2 = 'file_get_contents error : HTTP status 403 : Forbidden. You do not have permission to access this resource, or are over your rate limit.';		
						break;
					case 400 :
					    $read_phperror = true; 
						$errmsg2 = 'file_get_contents error : HTTP status 400. Bad request using file_get_contents. The parameters passed did not match as expected. The exact error is returned in the XML response.';
						break;
					case 500 :
					    $read_phperror = true; 
						$errmsg2 = 'file_get_contents error : HTTP status 500.  Internal Server Error. The other side is down.';
						break; 
					case 0 : 
						$read_phperror = true;
						$errmsg2 = 'file_get_contents error : No response header. The other side is down.';
					default :
					    $read_phperror = true; 
						$errmsg2 = 'file_get_contents error : Unexpected HTTP status of : ' . $status_code . '.';
				}
			}
		
		}
			
		if ( strlen (rtrim ($xml_data) ) == 0) 
		{
				$url_array = parse_url ($url);
				$remote = @fsockopen ( $url_array ['host'], 80, $errno, $errstr, 5 );
				if (! $remote) 
				{
				    $read_phperror = true; 
					$errmsg3 = "fsockopen error : socket opening failed : " . $errno . ' ' . $errstr; 
				} 
				else 
				{
				    $read_phperror = false; 
					$out = "GET " . $url_array ['path'] . "?" . $url_array ['query'] . " HTTP/1.0\r\n";
					$out .= "Host: " . $url_array ['host'] . " \r\n";
					$out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4\r\n";
					$out .= "Accept: text/xml\r\n\r\n"; 
					$out .= "Connection: Close\r\n\r\n";
					 fwrite ( $remote, $out );
					
				    // Get rid of the HTTP headers
					while ( $remote && ! feof ( $remote ) ) 
					{
						$headerbuffer = fgets ( $remote, 1024 );
						if (urlencode ( $headerbuffer ) == "%0D%0A") 
						{
							break;
						}
					}
                    // now get xml data
					$received = '';
					while ( ! feof ( $remote ))
					{
					    $received .= fgets ( $remote, 128 );
					}
					fclose($remote);
					// extract xml					
					$start = strpos($received, "<?xml");
                   $endTag = "</page>";
                   $end = strpos($received, $endTag) + strlen($endTag);
                   $xml_data = substr($received, $start, $end-$start);
					
				}
		}
		
		if ($loud == true)
		{
		    if ( $read_phperror == true  )
		    {
		         trigger_error($errmsg1 . '<br />' . $errmsg2 . '<br />' . $errmsg3 , E_USER_WARNING);   
		    }
		}
		
		return $xml_data;
	}
	
	

	/**
	 * Pagination function altered from functions.php used in viewmember.php because we need two linked paginations
	 *
	 * Pagination routine, generates page number sequence
	 * tpl_prefix is for using different pagination blocks at one page
	 */
	function generate_pagination2($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $tpl_prefix = '')
	{
		global $template, $user;
	
		// Make sure $per_page is a valid value
		$per_page = ($per_page <= 0) ? 1 : $per_page;
		$total_pages = ceil($num_items / $per_page);
	
		$seperator = '<span class="page-sep">' . $user->lang['COMMA_SEPARATOR'] . '</span>';
	
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
	
			$page_string .= ($start_cnt > 1) ? ' ... ' : $seperator;
	
			for ($i = $start_cnt + 1; $i < $end_cnt; $i++)
			{
				$page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
				if ($i < $end_cnt - 1)
				{
					$page_string .= $seperator;
				}
			}
	
			$page_string .= ($end_cnt < $total_pages) ? ' ... ' : $seperator;
		}
		else
		{
			$page_string .= $seperator;
	
			for ($i = 2; $i < $total_pages; $i++)
			{
				$page_string .= ($i == $on_page) ? '<strong>' . $i . '</strong>' : '<a href="' . $base_url . "{$url_delim}" . $tpl_prefix  . "=" . (($i - 1) * $per_page) . '">' . $i . '</a>';
				if ($i < $total_pages)
				{
					$page_string .= $seperator;
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
	
	/*
	 * Switches the sorting order of a supplied array, prerserving key values
	* The array is in the format [number][0/1] (0 = the default, 1 = the opposite)
	* Returns an array containing the code to use in an SQL query and the code to
	* use to pass the sort value through the URI.  URI is in the format
	* (number).(0/1)
	*
	* checks that the 2nd element is either 0 or 1
	* @param $sort_order Sorting order array
	* @param $arg header variable
	* @return array SQL/URI information
	*/
	function switch_order($sort_order, $arg = URI_ORDER)
	{
		$uri_order = ( isset($_GET[$arg]) ) ? request_var($arg, 0.0) : '0.0';
	
		$uri_order = explode('.', $uri_order);
	
		$element1 = ( isset($uri_order[0]) ) ? $uri_order[0] : 0;
		$element2 = ( isset($uri_order[1]) ) ? $uri_order[1] : 0;
		// check if correct input
		if ( $element2 != 1 )
		{
			$element2 = 0;
		}
	
		foreach($sort_order as $key => $value )
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
	 * Create a bar graph
	 *
	 * @param $width
	 * @param $show_number Show number in middle of bar?
	 * @param $class Background class for bar
	 * @return string Bar HTML
	 */
	function create_bar($width, $show_text = '', $color = '#AA0033')
	{
		$bar = '';
	
		if ( strstr($width, '%') )
		{
			$width = intval(str_replace('%', '', $width));
			if ( $width > 0 )
			{
				$width = ( intval($width) <= 100 ) ? $width . '%' : '100%';
			}
		}
	
		if ( $width > 0 )
		{
			$bar = '<table width="' . $width . '" border="0" cellpadding="0" cellspacing="0">';
			$bar .= '<tr><td style="text-align:left; background-color:' . $color .'; width: 100%; white-space: nowrap"  >';
	
			if ( $show_text != '' )
			{
				$bar .= '<span style="color:#EEEEEE" class="small">' . $show_text . '</span>';
			}
	
			$bar .= '</td></tr></table>';
		}
	
		return $bar;
	}
	
	
	/**
	 * makes an entry in the bbdkp log table
	 * log_action is an xml containing the log
	 *
	 * log_id	int(11)		UNSIGNED	No		auto_increment
	 * log_date	int(11)			No	0
	 * log_type	varchar(255)	utf8_bin		No
	 * log_action	text	utf8_bin		No
	 * log_ipaddress	varchar(15)	utf8_bin		No
	 * log_sid	varchar(32)	utf8_bin		No
	 * log_result	varchar(255)	utf8_bin		No
	 * log_userid	mediumint(8)	UNSIGNED	No	0
	 */
	public function log_insert($values = array())
	{
		global $db, $user;
		$log_fields = array('log_date', 'log_type', 'log_action', 'log_ipaddress', 'log_sid', 'log_result', 'log_userid');
	
	
		// Default our log values
		$defaultlog = array(
				'log_date'      => time(),
				'log_type'      => NULL,
				'log_action'    => NULL,
				'log_ipaddress' => $user->ip,
				'log_sid'       => $user->session_id,
				'log_result'    => 'L_SUCCESS',
				'log_userid'    => $user->data['user_id']);
	
		if ( sizeof($values) > 0 )
		{
			// If they set the value, we use theirs, otherwise we use the default
			foreach ( $log_fields as $field )
			{
				$values[$field] = ( isset($values[$field]) ) ? $values[$field] : $defaultlog[$field];
	
				if ( $field == 'log_action' )
				{
					// make xml with log actions
					$str_action="<log>";
					foreach ( $values['log_action'] as $key => $value )
					{
						$str_action .= "<" . $key . ">" . $value . "</" . $key . ">";
					}
					$str_action .="</log>";
					$str_action = substr($str_action, 0, strlen($str_action));
					// Take the newlines and tabs (or spaces > 1) out
					$str_action = preg_replace("/[[:space:]]{2,}/", '', $str_action);
					$str_action = str_replace("\t", '', $str_action);
					$str_action = str_replace("\n", '', $str_action);
					$str_action = preg_replace("#(\\\){1,}#", "\\", $str_action);
					$values['log_action'] = $str_action;
				}
			}
			$query = $db->sql_build_array('INSERT', $values);
			$sql = 'INSERT INTO ' . LOGS_TABLE . $query;
			$db->sql_query($sql);
			return true;
		}
		return false;
	}
	
	
}


class Form_Validate
{
    var $errors = array();   

   /**
    * Constructor
    *
    * Initiates the error list
    */
    function form_validate()
    {
        $this->_reset_error_list();
    }

    /**
    * Resets the error list
    *
    * @access private
    */
    function _reset_error_list()
    {
        $this->errors = array();
    }

    /**
    * Returns the array of errors
    *
    * @return array Errors
    */
    function get_errors()
    {
        return $this->errors;
    }

    /**
    * Checks if errors exist
    *
    * @return bool
    */
    function is_error()
    {
        if ( @sizeof($this->errors) > 0 )
        {
            return true;
        }

        return false;
    }

    /**
    * Returns a string with the appropriate error message
    *
    * @param $field Field to generate an error for
    * @return string Error string
    */
    function generate_error($field)
    {

        if ( $field != '' )
        {
            if ( !empty($this->errors[$field]) )
            {
                $error = $this->errors[$field];
                return $error;
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }
    
    /*
     * displays the error in phpbb 
     */
	function displayerror($errors)
	{
		global $user;
		
		$out='';
		foreach ($errors as $error)
		{
			$out .= $error . '<br />';				
		}
		
		trigger_error ( $user->lang['FORM_ERROR'] . $out, E_USER_WARNING );
	}
    
	
	

    // Begin validator methods
    // Note: The validation methods can accept arrays for the $field param
    // and the validation will be performed on each key/val pair.
    // If an array if used for validation, the method will always return true

    /**
    * Checks if a field is filled out
    *
    * @param $field Field name to check
    * @param $message Error message to insert
    * @return bool
    */
    function is_filled($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_filled($v, $message);
            }
            return true;
        }
        else
        {
            $value = $field;
            if ( trim($value) == '' )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

    /**
    * Checks if a field is numeric
    *
    * @param $field Field name to check
    * @param $message Error message to insert
    * @return bool
    */
    function is_number($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_number($v, $message);
            }
            return true;
        }
        else
        {
            $value = str_replace(' ','', $field);
            if ( !is_numeric($value) )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

    /**
    * Checks if a field is alphabetic
    *
    * @param $field string or array
    * @param $message Error message to insert
    * @return bool
    */
    function is_alpha($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_alpha($v, $message);
            }
            return true;
        }
        else
        {
            $value = $field;
            if ( preg_match("/[A-Za-z]+/i", $value) ==0 )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

    /**
    * Checks if a field is a valid hexadecimal color code (#FFFFFF)
    *
    * @param $field Field name to check
    * @param $message Error message to insert
    * @return bool
    */
    function is_hex_code($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_hex_code($v, $message);
            }
            return true;
        }
        else
        {
            $value = $field;
            if ( !preg_match("/(#)?[0-9A-Fa-f]{6}$/", $value) )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

    /**
    * Checks if a field is within a minimum and maximum range
    * NOTE: Will NOT accept an array of fields
    *
    * @param $field Field name to check
    * @param $min Minimum value
    * @param $max Maximum value
    * @param $message Error message to insert
    * @return bool
    */
    function is_within_range($field, $min, $max, $message = '')
    {
        $value = $field;
        if ( (!is_numeric($value)) || ($value < $min) || ($value > $max) )
        {
            $this->errors[] = $message;
            return false;
        }
        return true;
    }

    /**
    * Checks if a field has a valid e-mail address pattern
    *
    * @param $field Field name to check
    * @param $message Error message to insert
    * @return bool
    */
    function is_email_address($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_email_address($v, $message);
            }
            return true;
        }
        else
        {
            $value = $field;
            if ( !preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $value) )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

    /**
    *  Checks if a field has a valid IP address pattern
    *
    * @param $field Field name to check
    * @param $message Error message to insert
    * @return bool
    */
    function is_ip_address($field, $message = '')
    {
        if ( is_array($field) )
        {
            foreach ( $field as $k => $v )
            {
                $this->is_ip_address($v, $message);
            }
            return true;
        }
        else
        {
            $value =$field;
            if ( !preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", $value) )
            {
                $this->errors[] = $message;
                return false;
            }
            return true;
        }
    }

  

}



?>
