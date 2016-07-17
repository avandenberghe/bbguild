<?php
/**
 *
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace bbdkp\bbguild;

use phpbb\extension\base;

/**
 * Class ext
 *
 * @package bbdkp\bbguild
 */
class ext extends base
{

	/**
	 * Check whether or not the extension can be enabled.
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension:
	 *
	 * Requires phpBB 3.1.3 due to usage of container aware migrations.
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$condition = array();
		$config = $this->container->get('config');
		$condition[] = phpbb_version_compare($config['version'], '3.1.3', '>=');
		$condition[] = phpbb_version_compare(phpversion(), '5.4.39', '>=');
		$condition[] = @extension_loaded('gd');
		$condition[] = @extension_loaded('curl');
		$success = (!in_array(false, $condition));
		return $success;
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
		global $user, $config;
		ini_set('max_execution_time', 300);

		return parent::enable_step($old_state);
	}

}
