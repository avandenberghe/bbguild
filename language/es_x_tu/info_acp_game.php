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
	'ACP_BBGUILD_GAME'            => 'Configuración del juego',
	'ACP_BBGUILD_FACTION_ADD'        => 'Añadir facción',
	'ACP_BBGUILD_RACE_ADD'        => 'Añadir raza',
	'ACP_BBGUILD_ROLE_ADD'        => 'Añadir rol',
	'ACP_BBGUILD_CLASS_ADD'        => 'Añadir clase',
	'ACP_BBGUILD_GAME_LIST'        => 'Lista de juegos',
	'ACP_BBGUILD_GAME_EDIT'        => 'Editar juego',
	)
);
