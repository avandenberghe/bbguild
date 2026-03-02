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
	'ACP_BBGUILD_GAME'            => 'Spieleinstellungen',
	'ACP_BBGUILD_FACTION_ADD'        => 'Faktion hinzufügen',
	'ACP_BBGUILD_RACE_ADD'        => 'Rasse hinzufügen',
	'ACP_BBGUILD_ROLE_ADD'        => 'Rolle hinzufügen',
	'ACP_BBGUILD_CLASS_ADD'        => 'Klasse hinzufügen',
	'ACP_BBGUILD_GAME_LIST'        => 'Spielliste',
	'ACP_BBGUILD_GAME_EDIT'        => 'Spiel bearbeiten',
	)
);
