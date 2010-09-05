<?php
/**
 * bbdkp acp language file for mainmenu (FR)
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
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
	'ACP_DKP_MAINPAGE'		=> 'Réglages générals',  
	'ACP_DKP_PANEL'		    => 'Statistiques DKP',  
	'ACP_DKP_CONFIG'		=> 'Réglages générals',
	'ACP_DKP_INDEX'			=> 'Réglages portail',
	'ACP_DKP_LOGS'			=> 'Consulter les Logs',
));

?>