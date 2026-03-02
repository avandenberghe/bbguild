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
	'ACP_BBGUILD_RECRUIT'        => 'Recrutements',
	 'ACP_BBGUILD_RECRUIT_ADD'    => 'Ajouter recrutement',
	'ACP_BBGUILD_RECRUIT_EDIT'    => 'Modifier recrutement',
	'ACP_BBGUILD_RECRUIT_LIST'    => 'Recrutements',
	)
);
