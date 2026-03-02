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
 * info class for acp module player
 *
 *   @package bbguild
 */
class player_info
{
	/**
	 * phpbb module function
	 */
	public function module()
	{
		return array(
			'filename'    => '\avathar\bbguild\acp\player_module',
			'title'        => 'ACP_BBGUILD_PLAYER',
			'version'    => '2.0.0',
			'modes'        => array(
				'addplayer'        => array(
					'title' => 'ACP_BBGUILD_PLAYER_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_PLAYER'), 'display' => false ),
				'listplayers'    => array(
					'title' => 'ACP_BBGUILD_PLAYER_LIST',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_PLAYER')),
			),
		);
	}

}
