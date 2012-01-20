<?php
/**
 * bbDkp Permission Set (German-Informal)
 * 
 * @author sajaki
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
  * @translation various unknown authors, killerpommes
 * 
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
$lang['permission_cat']['bbdkp'] = 'bbDKP';

// Adding new permission set
$lang['permission_type']['bbdkp_'] = 'bbDKP Berechtigungen';


// bbDkp Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'Hat Zugriff auf den bbDKP Administrationsbereich', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'Kann die bbDKP Seiten sehen', 'cat' => 'bbdkp'),
	'acl_u_dkpucp'	=> array('lang' => 'Kann Charaktere in Benutzermenü zuweisen', 'cat' => 'bbdkp'),
	'acl_u_dkp_charadd'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü hinzufügen', 'cat' => 'bbdkp'),
	'acl_u_dkp_charupdate'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü bearbeiten', 'cat' => 'bbdkp'),
	'acl_u_dkp_chardelete'	=> array('lang' => 'Kann eigene Charaktere im Benutzermenü löschen', 'cat' => 'bbdkp'),
));

?>
