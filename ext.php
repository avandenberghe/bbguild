<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Extension entry point
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
		$condition['phpbb'][0] = phpbb_version_compare($config['version'], '3.3.0', '>=');
		$condition['phpbb'][1] ='phpbb >= 3.3.0';
		$condition['php'][0] = version_compare(PHP_VERSION , '7.4.0', '>=') ? 1: 0;
		$condition['php'][1] ='php >= 7.4.0';
		$condition['gd'][0] = extension_loaded('gd');
		$condition['gd'][1] ='gd extension is loaded';
		$condition['curl'][0] = extension_loaded('curl');
		$condition['curl'][1] ='curl extension is loaded';
		$output= '';
		if($this->result == 0)
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
	 * Auto-disable child game-plugin extensions before bbGuild core is disabled.
	 *
	 * Without this, disabling core while a child (e.g. bbguild_wow) is still
	 * enabled crashes the DI container because the child's services.yml
	 * references core parameters that no longer exist.
	 *
	 * @param mixed $old_state
	 * @return mixed
	 */
	public function disable_step($old_state)
	{
		if ($old_state === false)
		{
			$ext_manager = $this->container->get('ext.manager');

			$child_extensions = array(
				'avathar/bbguild_wow',
				'avathar/bbguild_eq2',
			);

			foreach ($child_extensions as $child)
			{
				if ($ext_manager->is_enabled($child))
				{
					while ($ext_manager->disable_step($child))
					{
						// run all disable steps
					}
				}
			}

			return 'children_disabled';
		}

		return parent::disable_step($old_state);
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
