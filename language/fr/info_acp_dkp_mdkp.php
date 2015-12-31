<?php
/**
 * bbdkp acp language file for DKP accounts (FR)
 *
 * @package phpBB Extension - bbdkp
 * @copyright 2009 bbdkp <https://github.com/bbDKP>
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
  	'ACP_DKP_MDKP'	        	=> 'Gestion Comptes DKP', 
    	'ACP_DKP_EDITMEMBERDKP'		=> 'Editer compte DKP',
	'ACP_DKP_LISTMEMBERDKP'		=> 'Liste des comptes',  
	'ACP_DKP_MEMBER_TRF'		=> 'Transfert de compte',
));
