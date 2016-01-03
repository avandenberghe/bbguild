<?php
/**
 * bbDKP Permission Set English
 * @author lucasari
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
$lang['permission_type']['bbguild_'] = 'Permessi bbDKP';


// bbDKP Permissions
$lang = array_merge($lang, array(
	'acl_a_bbguild'		=> array('lang' => 'Può accedere a modulo bbDKP ACP', 'cat' => 'bbguild'),
	'acl_f_bbguild'		=> array('lang' => 'Può visualizzare le pagine DKP', 'cat' => 'bbguild'),
	'acl_u_charclaim'	=> array('lang' => 'Può selezionare il proprio personaggio da UCP', 'cat' => 'bbguild'),
	'acl_u_charadd'	=> array('lang' => 'Può aggiungere il proprio personaggio da UCP', 'cat' => 'bbguild'),
	'acl_u_charupdate'	=> array('lang' => 'Può aggiornare il proprio personaggio da UCP', 'cat' => 'bbguild'),
	'acl_u_chardelete'	=> array('lang' => 'Può cancellare il proprio personaggio da  UCP', 'cat' => 'bbguild'),
));
