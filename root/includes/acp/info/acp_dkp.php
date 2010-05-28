<?php
/**
* language file for modules
* 
* @package bbDkp.acp
* @author Ippehe, Sajaki
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

class acp_dkp_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp',
			'title'		=> 'ACP_DKP_MAINPAGE',
			'version'	=> '1.1.0',
			'modes'		=> array(
				'mainpage'				=> array('title' => 'ACP_DKP_PANEL', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_config'			=> array('title' => 'ACP_DKP_CONFIG', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_indexpageconfig'   => array('title' => 'ACP_DKP_INDEX', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
				'dkp_logs'				=> array('title' => 'ACP_DKP_LOGS', 	'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MAINPAGE')),
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
