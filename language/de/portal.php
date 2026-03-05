<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (German)
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
	'BBGUILD_PORTAL_MOTD'            => 'Nachricht des Tages',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Gildenneuigkeiten',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Rekrutierung',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Aktivitäten',
	'BBGUILD_PORTAL_CUSTOM'          => 'Eigener Block',

	// Module content
	'NO_RECRUITS'                    => 'Keine offenen Rekrutierungspositionen.',
	'NO_ACTIVITY'                    => 'Keine aktuellen Aktivitäten.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'Keine Portalmodule für diese Gilde konfiguriert.',

	// Activity feed
	'LOOTED'                         => 'hat erhalten',
	'FORNPOINTS'                     => ' für %s Punkte.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Eigener Block Einstellungen',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Blocktitel',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Blockinhalt',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Portalmodule pro Gilde verwalten. Blöcke hinzufügen, entfernen, sortieren und aktivieren/deaktivieren.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Gilde auswählen',
	'ACP_PORTAL_MODULES'             => 'Portalmodule',
	'ACP_PORTAL_MODULE_NAME'         => 'Modul',
	'ACP_PORTAL_COLUMN'              => 'Spalte',
	'ACP_PORTAL_ORDER'               => 'Reihenfolge',
	'ACP_PORTAL_COLUMN_TOP'          => 'Oben',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Mitte',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Rechts',
	'ACP_PORTAL_ADD_MODULE'          => 'Modul hinzufügen',
	'ACP_PORTAL_NO_MODULES'          => 'Keine Portalmodule für diese Gilde konfiguriert. Fügen Sie unten eines hinzu.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Portalmodul wurde hinzugefügt.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Portalmodul konnte nicht hinzugefügt werden. Das Modul ist in der gewählten Spalte möglicherweise nicht erlaubt.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Portalmodul wurde entfernt.',
	'ACP_PORTAL_MOVE_LEFT'           => 'In vorherige Spalte verschieben',
	'ACP_PORTAL_MOVE_RIGHT'          => 'In nächste Spalte verschieben',
]);
