<?php
/**
* @version 1.2.9
* @copyright (c) 2009 bbdkp https://github.com/bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @author sajaki9@gmail.com
* 
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_dkp_mdkp_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_mdkp',
			'title'		=> 'ACP_DKP_MDKP',
			'version'	=> '1.2.9',
			'modes'		=> array(
				'mm_editmemberdkp'	=> array('title' => 'ACP_DKP_EDITMEMBERDKP',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MDKP'), 'display' => false ),
				'mm_listmemberdkp'	=> array('title' => 'ACP_DKP_LISTMEMBERDKP', 'auth' => 'acl_a_dkp',  'cat' => array('ACP_DKP_MDKP'), 'display' => true),
		        'mm_transfer'	    => array('title' => 'ACP_DKP_MEMBER_TRF', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>
