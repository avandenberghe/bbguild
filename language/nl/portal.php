<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (Dutch)
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
	'BBGUILD_PORTAL_MOTD'            => 'Bericht van de dag',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Gildenieuws',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Rekrutering',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Activiteitenoverzicht',
	'BBGUILD_PORTAL_CUSTOM'          => 'Aangepast blok',

	// Module content
	'NO_RECRUITS'                    => 'Geen open rekruteringsposities.',
	'NO_ACTIVITY'                    => 'Geen recente activiteit.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'Geen portaalmodules geconfigureerd voor deze gilde.',

	// Activity feed
	'LOOTED'                         => 'heeft verkregen',
	'FORNPOINTS'                     => ' voor %s punten.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Aangepast blok instellingen',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Bloktitel',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Blokinhoud',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Beheer portaalmodules per gilde. Voeg toe, verwijder, herorden en schakel blokken in/uit.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Selecteer gilde',
	'ACP_PORTAL_MODULES'             => 'Portaalmodules',
	'ACP_PORTAL_MODULE_NAME'         => 'Module',
	'ACP_PORTAL_COLUMN'              => 'Kolom',
	'ACP_PORTAL_ORDER'               => 'Volgorde',
	'ACP_PORTAL_COLUMN_TOP'          => 'Boven',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Midden',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Rechts',
	'ACP_PORTAL_ADD_MODULE'          => 'Module toevoegen',
	'ACP_PORTAL_NO_MODULES'          => 'Geen portaalmodules geconfigureerd voor deze gilde. Voeg er hieronder een toe.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Portaalmodule is toegevoegd.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Portaalmodule kon niet worden toegevoegd. De module is mogelijk niet toegestaan in de geselecteerde kolom.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Portaalmodule is verwijderd.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Verplaats naar vorige kolom',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Verplaats naar volgende kolom',
]);
