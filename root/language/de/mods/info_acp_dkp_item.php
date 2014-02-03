<?php
/**
 * bbdkp acp language file for mainmenu (German-Informal)
 * 
 * 
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * @translation various unknown authors, killerpommes
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
	'ACP_DKP_ITEM'	=> 'Gegenstandsverwaltung',  
	'ACP_DKP_ITEM_ADD'		=> 'Gegenstand hinzufügen',
	'ACP_DKP_ITEM_LIST'		=> 'Gegenstände',
	'ACP_DKP_ITEM_SEARCH'	=> 'Gegenstandssuche',
	'ACP_DKP_ITEM_VIEW'		=> 'Gegenstand zeigen',
));
