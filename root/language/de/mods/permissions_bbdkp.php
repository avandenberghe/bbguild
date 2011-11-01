<?php
/**
 * bbDkp Permission Set German
 * 
 * @author sajaki
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
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
$lang['permission_type']['bbdkp_'] = 'bbDKP Zugriffserlaubnisse';


// bbDkp Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'Hat Zugriff auf den bbDKP Administrationsbereich', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'Kann die bbDKP seiten sehen', 'cat' => 'bbdkp'),
	'acl_u_dkpucp'	=> array('lang' => 'Kann Charactere im PB binden', 'cat' => 'bbdkp'),
	'acl_u_dkp_charadd'	=> array('lang' => 'Kann eigene Charactere im PB erstellen', 'cat' => 'bbdkp'),
	'acl_u_dkp_charupdate'	=> array('lang' => 'Kann eigene Charactere im PB ändern', 'cat' => 'bbdkp'),
	'acl_u_dkp_chardelete'	=> array('lang' => 'Kann eigene Charactere im PB löschen', 'cat' => 'bbdkp'),	
));

?>
