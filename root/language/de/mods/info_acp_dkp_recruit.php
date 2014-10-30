<?php
/**
 * bbdkp acp language file for  guilds - german
 *
 *
 * @copyright 2009 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4.0
 * @translation killerpommes
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
$lang = array_merge($lang, array(
	'ACP_DKP_RECRUIT'		=> 'Rekrutierungen',
 	'ACP_DKP_RECRUIT_ADD'	=> 'Rekrutierung hinzufügen',
	'ACP_DKP_RECRUIT_EDIT'	=> 'Rekrutierung bearbeiten',
	'ACP_DKP_RECRUIT_LIST'	=> 'Rekrutierungen',
));
