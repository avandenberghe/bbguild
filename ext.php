<?php
/**
 *
 * @package bbguild v2.0
 * @copyright 2018 avathar.be
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace avathar\bbguild;

use phpbb\extension\base;

/**
 * Class ext
 *
* @package avathar\bbguild
 */
class ext extends base
{

	private $result;

	/**
	 * Check whether or not the extension can be enabled.
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$condition = array();
		$config = $this->container->get('config');
		$log = $this->container->get('log');
		$user = $this->container->get('user');
		$condition['phpbb'][0] = phpbb_version_compare($config['version'], '3.2.2', '>=');
		$condition['phpbb'][1] ='phpbb >= 3.2.2';
		$condition['php'][0] = version_compare(PHP_VERSION , '5.4.39', '>=') ? 1: 0;
		$condition['php'][1] ='php >= 5.4.39';
		$condition['gd'][0] = extension_loaded('gd');
		$condition['gd'][1] ='gd extension is loaded';
		$condition['curl'][0] = extension_loaded('curl');
		$condition['curl'][1] ='curl extension is loaded';
		$output= '';
		if ($this->result == 0)
		{
			foreach ($condition as $key => $val)
			{
				$this->result += (int) $val;
				$output = $key . ' ' . (($val == true) ? ': OK' : ': KO') . '<br />';
				$log->add('admin', $user->data['user_id'], $user->ip, $this->result . ' ' . $output, time(), [$key[1]]);
			}
			if ($this->result < 4)
			{
				trigger_error($output,  E_USER_WARNING);
			}
		}
		return true;
	}


	/**
	 * override enable step
	 * enable_step is executed on enabling an extension until it returns false.
	 *
	 * Calls to this function can be made in subsequent requests, when the
	 * function is invoked through a webserver with a too low max_execution_time.
	 *
	 * @param	mixed	$old_state	The return value of the previous call
	 *								of this method, or false on the first call
	 * @return	mixed				Returns false after last step, otherwise
	 *								temporary state which is passed as an
	 *								argument to the next step
	 */
	public function enable_step($old_state)
	{
		ini_set('max_execution_time', 300);

		return parent::enable_step($old_state);
	}
}
