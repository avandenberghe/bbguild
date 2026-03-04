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
	'ACP_BBGUILD_NEWS'            => 'Nieuwsbeheer',
	'ACP_ADD_NEWS_EXPLAIN'     => 'Hier kunt u guildnieuws toevoegen of wijzigen.',
	'ACP_BBGUILD_NEWS_ADD'        => 'Nieuws toevoegen',
	'ACP_BBGUILD_NEWS_LIST'        => 'Nieuws',
	'ACP_BBGUILD_NEWS_LIST_EXPLAIN'    => 'Lijst van nieuwsberichten',
	)
);
