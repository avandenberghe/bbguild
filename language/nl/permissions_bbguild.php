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
	'ACP_BBGUILD'            => array('lang' => 'bbGuild-rechten'),
	'ACL_A_BBGUILD'            => array('lang' => 'Heeft toegang tot bbGuild-beheer'),
	'ACL_U_BBGUILD'            => array('lang' => 'Kan bbGuild-pagina\'s bekijken'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Kan personages claimen/vrijgeven'),
	'ACL_U_CHARADD'            => array('lang' => 'Kan eigen personages toevoegen.'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Kan eigen personages bijwerken.'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Kan zelf toegevoegde personages verwijderen.'),
	)
);
