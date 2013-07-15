<?php
/**

* @version 1.3.0
* @copyright (c) 2009 bbdkp https://github.com/bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @package acp
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
class acp_dkp_mm_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_mm',
			'title'		=> 'ACP_DKP_MEMBER',
			'version'	=> '1.3.0',
			'modes'		=> array(
				'mm_addguild'	    => array('title' => 'ACP_DKP_GUILD_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
				'mm_listguilds'	    => array('title' => 'ACP_DKP_GUILD_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
				'mm_ranks'	        => array('title' => 'ACP_DKP_MEMBER_RANK', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
				'mm_addmember'	    => array('title' => 'ACP_DKP_MEMBER_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false ),
				'mm_listmembers'	=> array('title' => 'ACP_DKP_MEMBER_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
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
