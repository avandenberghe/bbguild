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
	'UCP_DKP_CHARACTERS'        => 'Charactères',
	'UCP_DKP'            => 'bbDKP',
	'UCP_DKP_CHARACTER_LIST'    => 'Mes Charactères',
	'UCP_DKP_CHARACTER_ADD'        => 'Ajouter Charactère',
	)
);
