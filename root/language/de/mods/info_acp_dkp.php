<?php
/**
 * bbdkp acp language file for mainmenu (German-Informal)
 * 
 * @package bbDKP
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
    'ACP_CAT_DKP'			=> 'bbDKP',
	'ACP_DKP_MAINPAGE'		=> 'Allgemeine Einstellungen',  
	'ACP_DKP_PANEL'		    => 'Adminmenü',  
	'ACP_DKP_CONFIG'		=> 'Haupteinstellungen',
	'ACP_DKP_INDEX'			=> 'Portaleinstellungen',
	'ACP_DKP_LOGS'			=> 'Zeige Protokoll',
));

?>