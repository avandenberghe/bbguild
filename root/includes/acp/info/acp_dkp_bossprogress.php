<?php
/**
* This class manages Bossprogress 
*  
* Powered by bbdkp © 2009 The bbDkp Project Team
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDkp project.
* 
* 
* @package bbDkp.acp
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* $Id$
* 
*  
**/

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

class acp_dkp_bossprogress_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_bossprogress',
			'title'		=> 'ACP_DKP_BOSS',
			'version'	=> '1.1.0',
			'modes'		=> array(
				'bossbase'			=> array('title' => 'ACP_DKP_BOSS_BOSSBASE', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_BOSS')),
				'bossbase_offset'	=> array('title' => 'ACP_DKP_BOSS_OFFSET', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_BOSS')),
				'bossprogress'		=> array('title' => 'ACP_DKP_BOSS_CONFIG', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_BOSS')),
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
