<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;
/**
 * info class for acp module dkp_mdkp
*   @package bbdkp
*/
class dkp_mdkp_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_mdkp_module',
			'title'		=> 'ACP_DKP_MDKP',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'mm_editmemberdkp'	=> array('title' => 'ACP_DKP_EDITMEMBERDKP',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MDKP'), 'display' => false ),
				'mm_listmemberdkp'	=> array('title' => 'ACP_DKP_LISTMEMBERDKP', 'auth' => 'acl_a_dkp',  'cat' => array('ACP_DKP_MDKP'), 'display' => true),
		        'mm_transfer'	    => array('title' => 'ACP_DKP_MEMBER_TRF', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
			),
		);
	}

}

?>
