<?php
/**
 * @package bbguild v2.0
 * @copyright 2018 avathar.be
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\acp;

/**
 * info class for acp module guild
 *
 *   @package bbguild
 */
class recruit_info
{
	/**
	 * phpbb module function
	 */
	public function module()
	{
		return array(
			'filename'    => '\avathar\bbguild\acp\recruit_module',
			'title'        => 'ACP_BBGUILD_RECRUIT',
			'version'    => '2.0.0',
			'modes'        => array(
				'addrecruit'        => array(
					'title' => 'ACP_BBGUILD_RECRUIT_ADD',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_PLAYER'),
					'display' => false),
				'editrecruit'        => array(
					'title' => 'ACP_BBGUILD_RECRUIT_EDIT',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_PLAYER'),
					'display' => false),
				'listrecruit'        => array(
					'title' => 'ACP_BBGUILD_RECRUIT_LIST',
					'auth' => 'ext_avathar/bbguild && acl_a_board && acl_a_bbguild',
					'cat' => array('ACP_BBGUILD_PLAYER'),
					'display' => true),
			));
	}

}
