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
	'ACP_BBGUILD'            => array('lang' => 'Permisos de bbGuild'),
	'ACL_A_BBGUILD'            => array('lang' => 'Puede acceder al panel de administración de bbGuild'),
	'ACL_U_BBGUILD'            => array('lang' => 'Puede ver las páginas de bbGuild'),
	'ACL_U_CHARCLAIM'        => array('lang' => 'Puede reclamar/liberar personajes'),
	'ACL_U_CHARADD'            => array('lang' => 'Puede añadir sus propios personajes.'),
	'ACL_U_CHARUPDATE'        => array('lang' => 'Puede actualizar sus propios personajes.'),
	'ACL_U_CHARDELETE'        => array('lang' => 'Puede eliminar personajes añadidos por uno mismo.'),
	)
);
