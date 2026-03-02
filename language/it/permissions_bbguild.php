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
	'ACP_BBGUILD'            => array('lang' => 'bbGuild Permissions'),
	'ACL_A_BBGUILD'            => array('lang' => 'Può accedere a modulo bbDKP ACP'),
	'ACL_U_BBGUILD'            => array('lang' => 'Può visualizzare le pagine DKP'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Può selezionare il proprio personaggio da UCP'),
	'ACL_U_CHARADD'            => array('lang' => 'Può aggiungere il proprio personaggio da UCP.'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Può aggiornare il proprio personaggio da UCP'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Può cancellare il proprio personaggio da UCP.'),
	)
);
