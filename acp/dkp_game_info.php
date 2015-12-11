<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\controller;

/**
 * info class for acp module dkp_game
 *   @package bbdkp
 */
class dkp_game_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_game_module',
			'title'		=> 'ACP_DKP_GAME',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'listgames'		=> array('title' => 'ACP_DKP_GAME_LIST',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => true),
				'editgames'		=> array('title' => 'ACP_DKP_GAME_EDIT',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				'addfaction'	=> array('title' => 'ACP_DKP_FACTION_ADD',   'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				'addrace'		=> array('title' => 'ACP_DKP_RACE_ADD',   'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				'addclass'		=> array('title' => 'ACP_DKP_CLASS_ADD',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
                'addrole'		=> array('title' => 'ACP_DKP_ROLE_ADD',  'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_GAME') , 'display' => false),
				),
		);
	}
	/**
	 * phpbb module function
	 */
	function install()
	{
	}
	/**
	 * phpbb module function
	 */
	function uninstall()
	{
	}
}

?>
