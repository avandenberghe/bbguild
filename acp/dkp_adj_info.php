<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\controller;

/**
 * info class for acp module dkp_adj
 *   @package bbdkp
 */
class dkp_adj_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_adj',
		    'title'	=> 'ACP_DKP_MDKP',
			'version'	=> '1.3.0',
			'modes'		=> array(			
				'addiadj'	=> array('title' => 'ACP_DKP_ADDADJ', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MDKP'), 'display' => false),
				'listiadj'	=> array('title' => 'ACP_DKP_LISTADJ', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MDKP'), 'display' => true),
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
