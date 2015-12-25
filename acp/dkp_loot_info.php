<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;

/**
 * info class for acp module dkp_item
*   @package bbdkp
*/
class dkp_loot_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_loot_module',
            'title'		=> 'ACP_DKP_LOOT',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'additem'			=> array('title' => 'ACP_DKP_LOOT_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS'), 'display' => false ),
				'listitems'			=> array('title' => 'ACP_DKP_LOOT_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => true ),
				),
		);
	}

}
