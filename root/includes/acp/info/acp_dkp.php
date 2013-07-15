<?php
/**
* 
* @author Ippehe, Sajaki
* @copyright (c) 2009 bbdkp https://github.com/bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.3.0
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
class acp_dkp_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp',
			'title'		=> 'ACP_DKP_MAINPAGE',
			'version'	=> '1.3.0',
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
