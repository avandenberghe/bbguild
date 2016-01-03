<?php
/**
 * bbDkp Permission Set French
 *
 * @package phpBB Extension - bbguild
 * @copyright 2011 bbguild <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.avathar.be/bbguild
 * @version 2.0
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
$lang['permission_cat']['bbguild'] = 'bbDKP';

// Adding new permission set
$lang['permission_type']['bbguild_'] = 'Permissions bbDKP';


// bbDKP Permissions
$lang = array_merge($lang, array(
	'acl_a_bbguild'		=> array('lang' => 'Accès PCA bbDKP', 'cat' => 'bbguild'),
	'acl_u_bbguild'		=> array('lang' => 'Accès pages DKP', 'cat' => 'bbguild'),
	'acl_u_charclaim'	=> array('lang' => 'Peut lier des charactères à son compte phpBB en PCU', 'cat' => 'bbguild'),
	'acl_u_charadd'	=> array('lang' => 'Peut créer ses charactères en PCU', 'cat' => 'bbguild'),
	'acl_u_charupdate'	=> array('lang' => 'Peut mettre à jour ses charactères en PCU', 'cat' => 'bbguild'),
	'acl_u_chardelete'	=> array('lang' => 'Peut supprimer ses charactères en PCU', 'cat' => 'bbguild'),
	));
