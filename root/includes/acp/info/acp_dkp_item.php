<?php
/**
* This class manages Items 
* 
* Powered by bbdkp, ported from Eqdkp
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDkp project.
*
* @package bbDkp.acp
* @author ippehe
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


class acp_dkp_item_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_item',
			'title'		=> 'ACP_DKP_ITEM',
			'version'	=> '1.1.0',
			'modes'		=> array(
				'additem'			=> array('title' => 'ACP_DKP_ITEM_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_ITEM'), 'display' => '1' ),
				'listitems'			=> array('title' => 'ACP_DKP_ITEM_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_ITEM') , 'display' => '1' ),
				'search'			=> array('title' => 'ACP_DKP_ITEM_SEARCH', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_ITEM'), 'display' => '0'  ),
				'viewitem'			=> array('title' => 'ACP_DKP_ITEM_VIEW', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_ITEM') , 'display' => '0' ),
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
