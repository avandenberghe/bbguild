<?php
/**
 * bbDKP Permission Set English
 *
 * @package phpBB Extension - bbguild
 * @copyright 2009 bbguild <https://github.com/bbDKP>
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
$lang['permission_cat']['bbguild'] = 'bbGuild';

// Adding new permission set
$lang['permission_type']['bbguild_'] = 'bbGuild Permissions';


// bbDKP Permissions
$lang = array_merge($lang, array(
	'acl_a_bbguild'		=> array('lang' => 'Can access bbDKP ACP', 'cat' => 'bbguild'),
	'acl_u_bbguild'		=> array('lang' => 'Can see DKP pages', 'cat' => 'bbguild'),
	'acl_u_charclaim'	=> array('lang' => 'Can claim characters in UCP', 'cat' => 'bbguild'),
	'acl_u_charadd'	=> array('lang' => 'Can add own characters in UCP', 'cat' => 'bbguild'),
	'acl_u_charupdate'	=> array('lang' => 'Can update own characters in UCP', 'cat' => 'bbguild'),
	'acl_u_chardelete'	=> array('lang' => 'Can delete own characters in UCP', 'cat' => 'bbguild'),
));
