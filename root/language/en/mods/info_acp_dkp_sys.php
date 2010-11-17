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
	'ACP_DKP_POOL_ADD'		=> 'Add DKP Pool',  
	'ACP_DKP_POOL_LIST'		=> 'DKP Pools',
	'ACP_DKP_LOOTSYSTEM'	=> 'Loot Systems',
	'ACP_DKP_LOOTSYSTEM_EXPLAIN'	=> 'Here you can select the loot distribution system',
	'ACP_DKP_LOOTSYSTEMOPTIONS'	=> 'Loot System options',
	'ACP_DKP_LOOTSYSTEMEXPLAIN'	=> 'A short guide to Loot systems',
));

?>