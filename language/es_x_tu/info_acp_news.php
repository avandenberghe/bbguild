<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge(
	$lang, array(
	'ACP_BBGUILD_NEWS'            => 'Gestión de noticias',
	'ACP_ADD_NEWS_EXPLAIN'     => 'Aquí puedes añadir o modificar noticias de la hermandad.',
	'ACP_BBGUILD_NEWS_ADD'        => 'Añadir noticia',
	'ACP_BBGUILD_NEWS_LIST'        => 'Noticias',
	'ACP_BBGUILD_NEWS_LIST_EXPLAIN'    => 'Lista de noticias',
	)
);
