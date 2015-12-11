<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\controller;


/**
 * info class for acp module dkp_raid
*   @package bbdkp
*/
class dkp_raid_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_raid_module',
			'title'		=> 'ACP_DKP_RAIDS',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'addraid'		=> array('title' => 'ACP_DKP_RAID_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => false),
				'editraid'		=> array('title' => 'ACP_DKP_RAID_EDIT', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => false),
				'listraids'		=> array('title' => 'ACP_DKP_RAID_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS')),
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

?>
