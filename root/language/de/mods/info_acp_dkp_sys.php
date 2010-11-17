<?php
/**
 * bbdkp acp language file for mainmenu
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
	'ACP_DKP_POOL_ADD'		=> 'DKP Pool zugfügen',  
	'ACP_DKP_POOL_LIST'		=> 'DKP Pools',
	'ACP_DKP_LOOTSYSTEM'	=> 'Loot Systeme',
	'ACP_DKP_LOOTSYSTEM_EXPLAIN'	=> 'Hier kannst du das Itemsystem einstellen.',
	'ACP_DKP_LOOTSYSTEMOPTIONS'	=> 'Loot Einstellungen'
	'ACP_DKP_LOOTSYSTEMEXPLAIN'	=> 'Eine kurze übersicht von Lootsysteme',

));

?>