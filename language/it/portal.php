<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (Italian)
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
	'BBGUILD_PORTAL_MOTD'            => 'Messaggio del giorno',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Notizie della gilda',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Reclutamento',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Attività recenti',
	'BBGUILD_PORTAL_CUSTOM'          => 'Blocco personalizzato',

	// Module content
	'NO_RECRUITS'                    => 'Nessuna posizione di reclutamento aperta.',
	'NO_ACTIVITY'                    => 'Nessuna attività recente.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'Nessun modulo portale configurato per questa gilda.',

	// Activity feed
	'LOOTED'                         => 'ha ottenuto',
	'FORNPOINTS'                     => ' per %s punti.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Impostazioni blocco personalizzato',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Titolo del blocco',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Contenuto del blocco',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Gestisci i moduli del portale per ogni gilda. Aggiungi, rimuovi, riordina e attiva/disattiva i blocchi.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Seleziona gilda',
	'ACP_PORTAL_MODULES'             => 'Moduli del portale',
	'ACP_PORTAL_MODULE_NAME'         => 'Modulo',
	'ACP_PORTAL_COLUMN'              => 'Colonna',
	'ACP_PORTAL_ORDER'               => 'Ordine',
	'ACP_PORTAL_COLUMN_TOP'          => 'Alto',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Centro',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Destra',
	'ACP_PORTAL_ADD_MODULE'          => 'Aggiungi modulo',
	'ACP_PORTAL_NO_MODULES'          => 'Nessun modulo del portale configurato per questa gilda. Aggiungine uno qui sotto.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Modulo del portale aggiunto.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Impossibile aggiungere il modulo. Il modulo potrebbe non essere consentito nella colonna selezionata.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Modulo del portale rimosso.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Sposta nella colonna precedente',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Sposta nella colonna successiva',
]);
