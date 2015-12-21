<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;

/**
 * info class for acp module dkp_mm
*   @package bbdkp
*/
class dkp_mm_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_mm_module',
			'title'		=> 'ACP_DKP_MEMBER',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'mm_addmember'	    => array('title' => 'ACP_DKP_MEMBER_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false ),
				'mm_listmembers'	=> array('title' => 'ACP_DKP_MEMBER_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
			),
		);
	}

}

