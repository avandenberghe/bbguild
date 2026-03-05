<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (Spanish)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	// Module names
	'BBGUILD_PORTAL_MOTD'            => 'Mensaje del día',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Noticias del gremio',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Reclutamiento',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Actividad reciente',
	'BBGUILD_PORTAL_CUSTOM'          => 'Bloque personalizado',

	// Module content
	'NO_RECRUITS'                    => 'No hay posiciones de reclutamiento abiertas.',
	'NO_ACTIVITY'                    => 'No hay actividad reciente.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'No hay módulos de portal configurados para este gremio.',

	// Activity feed
	'LOOTED'                         => 'obtuvo',
	'FORNPOINTS'                     => ' por %s puntos.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Configuración del bloque personalizado',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Título del bloque',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Contenido del bloque',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Gestiona los módulos del portal para cada gremio. Añade, elimina, reordena y activa/desactiva bloques.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Seleccionar gremio',
	'ACP_PORTAL_MODULES'             => 'Módulos del portal',
	'ACP_PORTAL_MODULE_NAME'         => 'Módulo',
	'ACP_PORTAL_COLUMN'              => 'Columna',
	'ACP_PORTAL_ORDER'               => 'Orden',
	'ACP_PORTAL_COLUMN_TOP'          => 'Arriba',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Centro',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Derecha',
	'ACP_PORTAL_ADD_MODULE'          => 'Añadir módulo',
	'ACP_PORTAL_NO_MODULES'          => 'No hay módulos del portal configurados para este gremio. Añade uno a continuación.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Módulo del portal añadido.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'No se pudo añadir el módulo. Es posible que el módulo no esté permitido en la columna seleccionada.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Módulo del portal eliminado.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Mover a columna anterior',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Mover a columna siguiente',
]);
