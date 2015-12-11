<?php
/**
 * bbdkp acp language file for file for Items (en)
 * @author lucasari
 * @package phpBB Extension - bbdkp
 * @copyright 2011 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.bbdkp.com
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
	'ACP_DKP_ITEM'			=> 'Gestione Oggetti',  
	'ACP_DKP_ITEM_ADD'		=> 'Aggiungi Oggetto',
	'ACP_DKP_ITEM_LIST'		=> 'Oggetti',
	'ACP_DKP_ITEM_EDIT'		=> 'Modifica Oggetti', 
	'ACP_DKP_ITEM_SEARCH'		=> 'Ricerca Oggetti',
	'ACP_DKP_ITEM_VIEW'		=> 'Visualizza Oggetti',
));
