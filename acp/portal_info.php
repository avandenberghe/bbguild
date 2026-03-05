<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * ACP info module for portal
 */

namespace avathar\bbguild\acp;

class portal_info
{
	public function module()
	{
		return [
			'filename'	=> '\avathar\bbguild\acp\portal_module',
			'title'		=> 'ACP_BBGUILD_PORTAL',
			'version'	=> '2.0.0',
			'modes'		=> [
				'portal' => [
					'title' => 'ACP_BBGUILD_PORTAL',
					'auth'  => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat'   => ['ACP_BBGUILD_MAINPAGE'],
				],
			],
		];
	}
}
