<?php
/**
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\model\admin;

/**
 * utiity class
 *
 *   @package bbguild
 */
class util
{
	/**
	 * bbguild timestamp
	 *
	 * @var integer
	 */
	public $time = 0;

	/** @var \phpbb\request\request */
	public $request;

	/**
	 * Admin class constructor
	 */
	public function __construct(\phpbb\request\request $request)
	{
		$this->request = $request;
	}

	/**
	 * get a timestamp foe events
	 *
	 */
	public function get_timestamp()
	{
		$boardtime = getdate(time()); //Returns new DateTime object
		$this->time = $boardtime[0];
	}

	/**
	 * creates unique key
	 *
	 * @param  string $part1
	 * @param  string $part2
	 * @param  string $part3
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
	 * Switch array order
	 * Switches the sorting order of a supplied array, prerserving key values
	 * The array is in the format [number][0/1] (0 = the default, 1 = the opposite)
	 * Returns an array containing the code to use in an SQL query and the code to
	 * use to pass the sort value through the URI.  URI is in the format
	 * checks that the 2nd element is either 0 or 1
	 *
	 * @param  $sort_order
	 * @param  string     $arg
	 * @param  string     $defaultorder
	 * @return mixed
	 */
	public final function switch_order($sort_order, $arg = constants::URI_ORDER, $defaultorder = '0.0')
	{

		$uri_order = $this->request->variable($arg, $defaultorder);
		$uri_order = explode('.', $uri_order);

		$element1 = ( isset($uri_order[0]) ) ? $uri_order[0] : 0;
		$element2 = ( isset($uri_order[1]) ) ? $uri_order[1] : 0;
		// check if correct input
		if ($element2 != 1 )
		{
			$element2 = 0;
		}

		foreach ((array) $sort_order as $key => $value)
		{
			$uri_element2 = 0;
			if ($element1 == $key )
			{
				$uri_element2 = ( $element2 == 0 ) ? 1 : 0;
			}
			$current_order['uri'][$key] = $key . '.' . $uri_element2;
		}

		$current_order['uri']['current'] = $element1.'.'.$element2;
		$current_order['sql'] = $sort_order[$element1][$element2];

		return $current_order;
	}


	/**
	 * remove end of a string
	 *
	 * @param  string $string
	 * @param  string $stringToRemove
	 * @return string
	 */
	public function remove_from_end($string, $stringToRemove)
	{
		$stringToRemoveLen = strlen($stringToRemove);
		$stringLen = strlen($string);
		$pos = $stringLen - $stringToRemoveLen;
		return substr($string, 0, $pos);
	}

}
