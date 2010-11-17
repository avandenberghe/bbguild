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
			'version'	=> '1.1.3',
			'modes'		=> array(
				'adddkpsys'		=> array('title' => 'ACP_DKP_POOL_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => false),
				'listdkpsys'	=> array('title' => 'ACP_DKP_POOL_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => true ),
				'lootsystem'	=> array('title' => 'ACP_DKP_LOOTSYSTEM', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS'), 'display' => true),
				'lootsystem_options'	=> array('title' => 'ACP_DKP_LOOTSYSTEMOPTIONS', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS'), 'display' => false),
				'lootsystem_explain'	=> array('title' => 'ACP_DKP_LOOTSYSTEMEXPLAIN', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS'), 'display' => false),
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
