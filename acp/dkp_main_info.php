<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;
/**
* info class for acp module dkp
*   @package bbdkp
*/
class dkp_main_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
			'filename'	=> '\sajaki\bbdkp\acp\dkp_main_module',
			'title'		=> 'ACP_DKP_MAINPAGE',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'dkp_panel'				=> array('title' => 'ACP_DKP_PANEL', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_config'			=> array('title' => 'ACP_DKP_CONFIG', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_index'   		=> array('title' => 'ACP_DKP_INDEX', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_logs'				=> array('title' => 'ACP_DKP_LOGS', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				),
		);
	}
}
