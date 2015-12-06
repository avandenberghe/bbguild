<?php
/**
 * bbDKP Permission Set English
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
$lang['permission_type']['bbdkp_'] = 'Permessi bbDKP';


// bbDKP Permissions
$lang = array_merge($lang, array(
	'acl_a_dkp'		=> array('lang' => 'Può accedere a modulo bbDKP ACP', 'cat' => 'bbdkp'),
	'acl_u_dkp'		=> array('lang' => 'Può visualizzare le pagine DKP', 'cat' => 'bbdkp'),
	'acl_u_dkpucp'	=> array('lang' => 'Può selezionare il proprio personaggio da UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_charadd'	=> array('lang' => 'Può aggiungere il proprio personaggio da UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_charupdate'	=> array('lang' => 'Può aggiornare il proprio personaggio da UCP', 'cat' => 'bbdkp'),
	'acl_u_dkp_chardelete'	=> array('lang' => 'Può cancellare il proprio personaggio da  UCP', 'cat' => 'bbdkp'),
));

