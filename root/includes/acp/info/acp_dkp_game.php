<?php
/**
* @package acp
* @version 1.2.9
* @copyright (c) 2009 bbdkp https://github.com/bbDKP
* @license http://opensource.org/licenses/gpl-license.php GNU Public License

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

/**
 * @package acp
 */
class acp_dkp_game_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_dkp_game',
			'title'		=> 'ACP_DKP_GAME',
			'version'	=> '1.2.9',
			'modes'		=> array(
				'listgames'		=> array('title' => 'ACP_DKP_GAME_LIST',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => true),
				'addfaction'	=> array('title' => 'ACP_DKP_FACTION_ADD',   'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				'addrace'		=> array('title' => 'ACP_DKP_RACE_ADD',   'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				'addclass'		=> array('title' => 'ACP_DKP_CLASS_ADD',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
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
