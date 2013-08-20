<?php
/**
* @author ippehe
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
class acp_dkp_item_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_item',
			'title'		=> 'ACP_DKP_ITEM',
			'version'	=> '1.3.0',
			'modes'		=> array(
				'additem'			=> array('title' => 'ACP_DKP_ITEM_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS'), 'display' => false ),
				'listitems'			=> array('title' => 'ACP_DKP_ITEM_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_RAIDS') , 'display' => true ),
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
