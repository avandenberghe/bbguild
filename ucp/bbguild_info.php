<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace avathar\bbguild\ucp;

/**
 * Class bbguild_info
 */
class bbguild_info
{
	/**
	 * @return array
	 */
	public function module()
	{
		return array(
			'filename'    => '\avathar\bbguild\ucp\bbguild_module',
			'title'        => 'UCP_BBGUILD',
			'version'    => '2.0.0',
			'modes'        => array(
				'char'    => array(
					'title' => 'UCP_CHARACTERS',
					'auth' => 'ext_avathar/bbguild && acl_u_charclaim',
					'cat' => array('UCP_BBGUILD')),
				'add'    => array(
					'title' => 'UCP_CHARACTER_ADD',
					'auth' => 'ext_avathar/bbguild && acl_u_charadd',
					'cat' => array('UCP_BBGUILD')),
			),
		);
	}
}
