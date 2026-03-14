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
	'ACL_A_BBGUILD'            => array('lang' => 'Can manage bbGuild settings, guilds, players, and games in the ACP'),
	'ACL_U_BBGUILD'            => array('lang' => 'Can view guild portal pages and player profiles — granted to guests by default'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Can claim or unclaim an existing guild character as their own'),
	'ACL_U_CHARADD'            => array('lang' => 'Can add new characters to a guild'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Can edit their own characters (name, class, level, etc.)'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Can delete their own characters'),
	)
);
