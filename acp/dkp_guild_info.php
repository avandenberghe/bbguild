<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\controller;

/**
 * info class for acp module dkp_guild
*   @package bbdkp
*/
class dkp_guild_info
{
	/**
	 * phpbb module function
	 */
	function module()
	{
		return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_guild_module',
			'title'		=> 'ACP_DKP_GUILD',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'addguild'	    => array('title' => 'ACP_DKP_GUILD_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
				'editguild'	    => array('title' => 'ACP_DKP_GUILD_EDIT', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
				'listguilds'	    => array('title' => 'ACP_DKP_GUILD_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER')),
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

