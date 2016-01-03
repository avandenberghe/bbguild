<?php
/**
 * bbGuild Permission Set - german
 *
 * @package phpBB Extension - bbguild
 * @copyright 2010 bbguild <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.avathar.be/bbguild
 * @version 2.0
 * @translation various unknown authors, killerpommes
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Define categories
$lang['permission_cat']['bbguild'] = 'bbGuild';

// Adding new permission set
$lang['permission_type']['bbguild_'] = 'bbGuild Berechtigungen';


// bbDkp Permissions
$lang = array_merge($lang, array(
	'acl_a_bbguild'		=> array('lang' => 'Hat Zugriff auf den bbGuild Administrationsbereich', 'cat' => 'bbguild'),
	'acl_u_bbguild'		=> array('lang' => 'Kann die bbGuild Seiten sehen', 'cat' => 'bbguild'),
	'acl_u_charclaim'	=> array('lang' => 'Kann Charaktere in Benutzermenü zuweisen', 'cat' => 'bbguild'),
	'acl_u_charadd'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü hinzufügen', 'cat' => 'bbguild'),
	'acl_u_charupdate'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü bearbeiten', 'cat' => 'bbguild'),
	'acl_u_chardelete'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü löschen', 'cat' => 'bbguild'),
));
