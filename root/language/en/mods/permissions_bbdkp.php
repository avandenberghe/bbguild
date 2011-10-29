<?php
/**
 * bbDKP Permission Set English
 * 
 * @author sajaki
 * @package bbDKP
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
$lang['permission_type']['bbdkp_'] = 'bbDKP Permissions';


// bbDKP Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'bbDKP - can access bbDKP ACP', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'bbDKP - can see DKP pages', 'cat' => 'bbdkp'),
	'acl_u_dkpucp'	=> array('lang' => 'bbDKP - can claim characters in UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_charadd'	=> array('lang' => 'bbDKP - can add own characters in UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_charupdate'	=> array('lang' => 'bbDKP - can update own characters in UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_chardelete'	=> array('lang' => 'bbDKP - can delete own characters in UCP', 'cat' => 'bbdkp'),
));

?>
