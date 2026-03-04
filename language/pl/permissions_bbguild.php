<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
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
$lang = array_merge(
	$lang, array(
	'ACP_BBGUILD'            => array('lang' => 'Uprawnienia bbGuild'),
	'ACL_A_BBGUILD'            => array('lang' => 'Ma dostęp do panelu administracyjnego bbGuild'),
	'ACL_U_BBGUILD'            => array('lang' => 'Może przeglądać strony bbGuild'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Może przypisywać/zwalniać postacie'),
	'ACL_U_CHARADD'            => array('lang' => 'Może dodawać własne postacie.'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Może aktualizować własne postacie.'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Może usuwać samodzielnie dodane postacie.'),
	)
);
