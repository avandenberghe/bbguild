<?php
/**
 * bbDkp Permission Set English
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
$lang['permission_cat']['bbdkp'] = 'bbDkp';

// Adding new permission set
$lang['permission_type']['bbdkp_'] = 'bbDkp Permissions';


// bbDkp Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'bbDKP - accès PCA bbDKP', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'bbDkp - accès pages DKP', 'cat' => 'bbdkp'),
));

?>
