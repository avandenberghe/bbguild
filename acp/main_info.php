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
 * info class for acp module dkp
 *
 *   @package bbguild
 */
class main_info
{
	/**
	 * phpbb module function
	 */
	public function module()
	{
		return array(
			'filename'    => '\avathar\bbguild\acp\main_module',
			'title'        => 'ACP_BBGUILD_MAINPAGE',
			'version'    => '2.0.0',
			'modes'        => array(
				'panel'        => array(
					'title' => 'ACP_BBGUILD_PANEL',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_MAINPAGE')),
				'config'    => array(
					'title' => 'ACP_BBGUILD_CONFIG',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_MAINPAGE')),
				'logs'    => array(
					'title' => 'ACP_BBGUILD_LOGS',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_MAINPAGE')),
			),
		);
	}
}
