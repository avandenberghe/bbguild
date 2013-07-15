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
class acp_dkp_event_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_event',
			'title'		=> 'ACP_DKP_EVENT',
			'version'	=> '1.3.0',
			'modes'		=> array(
				'addevent'			=> array('title' => 'ACP_DKP_EVENT_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_EVENT') , 'display' => false),
				'listevents'		=> array('title' => 'ACP_DKP_EVENT_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_EVENT')),
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
