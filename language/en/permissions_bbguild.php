<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
$lang = array_merge(
	$lang, array(
	'ACP_BBGUILD'            => array('lang' => 'bbGuild Permissions'),
	'ACL_A_BBGUILD'            => array('lang' => 'Can access bbGuild ACP'),
	'ACL_U_BBGUILD'            => array('lang' => 'Can see bbGuild pages'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Can claim/unclaim Characters'),
	'ACL_U_CHARADD'            => array('lang' => 'Can add own Characters.'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Can update own Characters.'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Can delete self-added Characters.'),
	)
);
