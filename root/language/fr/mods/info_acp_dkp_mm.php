<?php
/**
 * bbdkp acp language file for Guild and Members (FR)
 * 
 * 
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
    'ACP_DKP_MEMBER'	    => 'Gestion Guild et membres',
 	'ACP_DKP_GUILD_ADD'	    => 'Ajouter Guilde',  
	'ACP_DKP_GUILD_LIST'	=> 'Guildes',   
	'ACP_DKP_MEMBER_ADD'	=> 'Ajouter membre',  
	'ACP_DKP_MEMBER_LIST'	=> 'Membres',
	'ACP_DKP_MEMBER_RANK'	=> 'Grades',
	'ACP_DKP_ARMORYUPDATER'	=> 'Mise à jour Armurerie',	
));

?>