<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbguild\acp;

/**
 * info class for acp module mm
*   @package bbguild
*/
class mm_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbguild\acp\mm_module',
			'title'		=> 'ACP_BBGUILD_MEMBER',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'mm_addmember'	    => array('title' => 'ACP_BBGUILD_MEMBER_ADD', 'auth' => 'acl_a_bbguild', 'cat' => array('ACP_BBGUILD_MEMBER'), 'display' => false ),
				'mm_listmembers'	=> array('title' => 'ACP_BBGUILD_MEMBER_LIST', 'auth' => 'acl_a_bbguild', 'cat' => array('ACP_BBGUILD_MEMBER')),
			),
		);
	}

}

