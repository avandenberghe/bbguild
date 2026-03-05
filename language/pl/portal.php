<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (Polish)
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
	'BBGUILD_PORTAL_MOTD'            => 'Wiadomość dnia',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Aktualności gildii',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Rekrutacja',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Ostatnia aktywność',
	'BBGUILD_PORTAL_CUSTOM'          => 'Własny blok',

	// Module content
	'NO_RECRUITS'                    => 'Brak otwartych pozycji rekrutacyjnych.',
	'NO_ACTIVITY'                    => 'Brak ostatniej aktywności.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'Nie skonfigurowano modułów portalu dla tej gildii.',

	// Activity feed
	'LOOTED'                         => 'zdobył',
	'FORNPOINTS'                     => ' za %s punktów.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Ustawienia własnego bloku',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Tytuł bloku',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Zawartość bloku',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Zarządzaj modułami portalu dla każdej gildii. Dodawaj, usuwaj, zmieniaj kolejność i włączaj/wyłączaj bloki.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Wybierz gildię',
	'ACP_PORTAL_MODULES'             => 'Moduły portalu',
	'ACP_PORTAL_MODULE_NAME'         => 'Moduł',
	'ACP_PORTAL_COLUMN'              => 'Kolumna',
	'ACP_PORTAL_ORDER'               => 'Kolejność',
	'ACP_PORTAL_COLUMN_TOP'          => 'Góra',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Środek',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Prawa',
	'ACP_PORTAL_ADD_MODULE'          => 'Dodaj moduł',
	'ACP_PORTAL_NO_MODULES'          => 'Brak modułów portalu skonfigurowanych dla tej gildii. Dodaj jeden poniżej.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Moduł portalu został dodany.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Nie można dodać modułu portalu. Moduł może nie być dozwolony w wybranej kolumnie.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Moduł portalu został usunięty.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Przenieś do poprzedniej kolumny',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Przenieś do następnej kolumny',
]);
