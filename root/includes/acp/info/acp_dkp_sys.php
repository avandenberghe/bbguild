<?php
/**
* This acp class manages DKP pools
* 
* @package bbDkp.acp
* @author Sajaki
* @version $Id$
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
* @package module_install
*/

class acp_dkp_sys_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_sys',
			'title'		=> 'ACP_DKP_RAIDS',
			'version'	=> '1.1.0',
			'modes'		=> array(
				'adddkpsys'		=> array('title' => 'ACP_DKP_POOL_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS')),
				'listdkpsys'	=> array('title' => 'ACP_DKP_POOL_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS')),
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
