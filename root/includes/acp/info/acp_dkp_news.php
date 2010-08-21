<?php
/**
* This class manages the news page
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


class acp_dkp_news_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_news',
			'title'		=> 'ACP_DKP_NEWS',
			'version'	=> '1.1.0',
			'modes'		=> array(
				'addnews'			=> array('title' => 'ACP_DKP_NEWS_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_NEWS') , 'display' => false),
				'listnews'			=> array('title' => 'ACP_DKP_NEWS_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_NEWS')),
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
