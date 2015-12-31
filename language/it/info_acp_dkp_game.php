<?php
/**
 * bbdkp acp language file for Game, Race and Class  (FR)
 * @author lucasari
 * @package phpBB Extension - bbdkp
 * @copyright 2011 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.avathar.be/bbdkp
 * @version 2.0
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
    'ACP_DKP_GAME'			=> 'Impostazioni Giochi',
	'ACP_DKP_FACTION_ADD'		=> 'Aggiungi Fazione',
	'ACP_DKP_RACE_ADD'		=> 'Aggiungi Razza',
	'ACP_DKP_CLASS_ADD'		=> 'Aggiungi Classe',  
	'ACP_DKP_GAME_LIST'		=> 'Lista Giochi',
	'ACP_DKP_GAME_EDIT'		=> 'Edita Gioco', 
));
