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
class acp_dkp_guild_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_guild',
			'title'		=> 'ACP_DKP_GUILD',
			'version'	=> '1.3.0',
			'modes'		=> array(
				'addguild'	    => array('title' => 'ACP_DKP_GUILD_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
				'listguilds'	    => array('title' => 'ACP_DKP_GUILD_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
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
