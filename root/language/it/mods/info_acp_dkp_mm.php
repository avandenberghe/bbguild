<?php
/**
 * bbdkp acp language file for Guild and Members (FR)
 * @author lucasari
 * @author Sajaki@bbdkp.com
 * @copyright 2014 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.1
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
    	'ACP_DKP_MEMBER'	=> 'Gestione Gilda e Personaggi',
	'ACP_DKP_MEMBER_ADD'	=> 'Aggiungi Personaggi',  
	'ACP_DKP_MEMBER_LIST'	=> 'Roster',
));

