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
    'ACP_DKP_MEMBER'	    => 'Guild and Member management',
 	'ACP_DKP_GUILD_ADD'	    => 'Add Guild',  
	'ACP_DKP_GUILD_LIST'	=> 'List Guilds',   
	'ACP_DKP_MEMBER_ADD'	=> 'Add member',  
	'ACP_DKP_MEMBER_LIST'	=> 'List members',
	'ACP_DKP_MEMBER_RANK'	=> 'Guild Ranks',
	'ACP_DKP_ARMORYUPDATER'	=> 'Armory updater',	
));

?>