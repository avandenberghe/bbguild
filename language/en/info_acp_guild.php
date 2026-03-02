<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge(
	$lang, array(
	'ACP_BBGUILD_GUILD'        => 'Guilds',
	 'ACP_BBGUILD_GUILD_ADD'    => 'Add Guild',
	'ACP_BBGUILD_GUILD_EDIT'    => 'Edit Guild',
	'ACP_BBGUILD_GUILD_LIST'    => 'Guild List',
	)
);
