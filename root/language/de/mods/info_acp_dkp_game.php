<?php
/**
 * bbdkp acp language file for Game, Race and Class (German-Informal)
 * 
 * 
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * @translation unknown author, killerpommes
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
    'ACP_DKP_GAME'			=> 'Spieleeinstellungen',
	'ACP_DKP_FACTION_ADD'	=> 'Faktion hinzufügen',
	'ACP_DKP_RACE_ADD'		=> 'Rasse zufügen',
	'ACP_DKP_CLASS_ADD'		=> 'Klasse zufügen',  
	'ACP_DKP_GAME_LIST'		=> 'Spielliste',
	'ACP_DKP_GAME_EDIT'		=> 'Spiel bearbeiten',
));

?>