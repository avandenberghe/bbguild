<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * ACP info module
 *
 */

namespace avathar\bbguild\acp;

/**
 * info class for acp module game
 *
 *   @package bbguild
 */
class game_info
{
	/**
	 * phpbb module function
	 */
	public function module()
	{
		return array(
			'filename'    => '\avathar\bbguild\acp\game_module',
			'title'        => 'ACP_BBGUILD_MAINPAGE',
			'version'    => '2.0.0',
			'modes'        => array(
				'listgames'        => array(
					'title' => 'ACP_BBGUILD_GAME_LIST',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => true),
				'editgames'        => array(
					'title' => 'ACP_BBGUILD_GAME_EDIT',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => false),
				'addfaction'    => array(
					'title' => 'ACP_BBGUILD_FACTION_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => false),
				'addrace'        => array(
					'title' => 'ACP_BBGUILD_RACE_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => false),
				'addclass'        => array(
					'title' => 'ACP_BBGUILD_CLASS_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => false),
				'addrole'        => array(
					'title' => 'ACP_BBGUILD_ROLE_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_GAME') ,
					'display' => false),
			),
		);
	}

}
