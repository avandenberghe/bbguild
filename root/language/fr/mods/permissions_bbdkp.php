<?php
/**
 * bbDkp Permission Set French
 * 
 * @author sajaki
 * 
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
$lang['permission_type']['bbdkp_'] = 'Permissions bbDKP';


// bbDkp Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'Accès PCA bbDKP', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'Accès pages DKP', 'cat' => 'bbdkp'),
	'acl_u_dkpucp'	=> array('lang' => 'Peut lier des charactères à son compte phpBB en PCU', 'cat' => 'bbdkp'),
	'acl_u_dkp_charadd'	=> array('lang' => 'Peut créer ses charactères en PCU', 'cat' => 'bbdkp'),
	'acl_u_dkp_charupdate'	=> array('lang' => 'Peut mettre à jour ses charactères en PCU', 'cat' => 'bbdkp'),
	'acl_u_dkp_chardelete'	=> array('lang' => 'Peut supprimer ses charactères en PCU', 'cat' => 'bbdkp'),
	));

?>
