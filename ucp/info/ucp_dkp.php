<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * info class for ucp module dkp
 *   @package bbguild
 */
class ucp_info
{	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
			'filename'	=> 'ucp_dkp',
			'title'		=> 'UCP_DKP',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'characters'	=> array('title' => 'UCP_DKP_CHARACTERS', 'auth' => 'acl_u_dkp', 'cat' => array('UCP_DKP')),
				'characteradd'	=> array('title' => 'UCP_DKP_CHARACTER_ADD', 'auth' => 'acl_u_dkp', 'cat' => array('UCP_DKP')),				
				),
			);
	}
	/**
	 * phpbb module function
	 */
	function install()
	{
	}
	/**
	 * phpbb module function
	 */
	function uninstall()
	{
	}

}
