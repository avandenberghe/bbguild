<?php
/**
 * DKP language keys archived from bbguild common.php [German]
 *
 * These keys were part of the original bbDKP/EQDkp loot point system.
 * They are preserved here for use in a future bbDKP plugin extension
 * based on bbDKP and EQDKPPlus (https://github.com/casey-mccarthy/eqdkp-plus-documentation).
 *
 * @package   phpBB Extension - bbguild
 * @copyright 2009 bbguild
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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

	// Point systems
	'DKP' => 'DKP',
	'DKP_VALUE' => '%s Wert',
	'EP' => 'LP',
	'EPLONG' => 'Leistungspunkte',
	'EPNET' => 'LP net',
	'EPNETLONG' => 'Netto LP',
	'GP' => 'RP',
	'GPLONG' => 'Rüstungs Punkte',
	'GPNET' => 'RP netto',
	'PR' => 'Priorität',
	'PRLONG' => 'Prioritätenschlüssel',
	'RAIDDECAY' => 'Raid Entwertung',
	'ADJDECAY' => 'Ajustierungsentwertung',
	'DECAY' => 'Entwert',
	'ZSVALUE' => 'Nullsumme',
	'ZEROSUM' => 'Nullsummenbonus',

	// Standings / Points
	'ADJUSTMENT' => 'Korrektur',
	'ADJUSTMENTS' => 'Korrekturen',
	'ADJUSTMENT_HISTORY' => 'Korrekturen Verlauf',
	'INDIV_ADJ' => 'Korr.',
	'NETADJUSTMENT' => 'Netto',
	'NETSPENT' => 'Netto',
	'ALL' => 'Alle',
	'CURRENT' => 'Jetzt',
	'EARNED' => 'Bekommen',
	'SPENT' => 'Ausgegeben',
	'POOL' => 'DKP Pool',
	'TOTAL_ADJUSTMENTS' => 'Gesamt Ajustierungen',
	'TOTAL_EARNED' => 'Gesamt verdient',
	'TOTAL_SPENT' => 'Gesamt ausgegeben',
	'NO_ADJUSTMENTS' => 'Keine Korrekturen',
	'PER_DAY' => 'Pro Tag',
	'PER_RAID' => 'Pro Raid',
	'PCT_EARNED_LOST_TO' => '% Verdientes verringert durch',
	'FORNPOINTS' => ' for %s points.',
	'MAY_BE_NEGATIVE_NOTE' => 'darf negativ sein',
	'NODKPACCOUNTS' => 'Keine DKP Konten gefunden',
	'LEADERBOARDSTAT' => 'Leaderboard gegen Raidcount',

	// Loot / Items
	'RECENTLOOT' => 'Letzte Beute',
	'ITEM' => 'Gegenstand',
	'ITEMS' => 'Gegenstände',
	'ITEMVALUE' => 'Item Wert',
	'ITEMDECAY' => 'Item Entwert',
	'ITEMTOTAL' => 'Totalwert',
	'ITEM_PURCHASE_HISTORY' => 'Item Historie',
	'BUYER' => 'Käufer',
	'BUYERS' => 'Käufer',
	'DROPS' => 'Beute',
	'LOOTS' => 'Beute',
	'LOOTDIST_CLASS' => 'Beute-Klassenverteilung',
	'LOOTED' => 'obtained',
	'LOOT_FACTOR' => 'Beute Faktor',
	'LASTLOOT' => 'Letztes Beute',
	'NO_LOOT' => 'Keine Beute',
	'PURCHASE_HISTORY_FOR' => 'Kauf-Historie für %s',
	'TOTAL_ITEMS' => 'Gesamt Gegenstände',
	'ENTER_NEW_GAMEITEMID' => 'Gegenstand ID',

	// Raid values
	'RAIDVALUE' => 'Raid Wert',
	'RAIDBONUS' => 'Raid Bonus',
	'TIME_BONUS' => 'Zeitbonus',
	'TIMEVALUE' => 'Zeitwert',
	'TOTAL_RAIDS' => 'Gesamt Raids',
	'NO_RAIDS' => 'Keine Raids',
	'RAID_ON' => 'Raid von %s in %s',

	// Attendance tracking
	'RAID_ATTENDANCE_HISTORY' => 'Raidbeteiligungs Historie',
	'ATTENDANCE_BY_EVENT' => 'Beteiligung bei Ereignis',
	'ATTENDANCE_LIFETIME' => 'Alle',
	'RAIDS_LIFETIME' => 'Lebensdauer (%s - %s)',
	'RAIDS_X_DAYS' => 'Letzte %d Tage',
	'COMPARE_PLAYERS' => 'Vergleiche Mitglieder',
	'BOSSKILLCOUNT' => 'Anzahl Bosskills',
	'IRCTLIFE' => 'Raidanzahl',
	'GRCTLIFE' => 'Gilden Raidanzahl',
	'ATTLIFE' => 'Anwesenheit',
	'IRCT90' => 'Raidanzahl 90d',
	'GRCT90' => 'Gilden Raidanzahl 90d',
	'ATT90' => 'Anwesenheit 90d',
	'IRCT60' => 'Raidanzahl 60d',
	'GRCT60' => 'Gilden Raidanzahl 60d',
	'ATT60' => 'Anwesenheit 60d',
	'IRCT30' => 'Raidanzahl 30d',
	'GRCT30' => 'Gilden Raidanzahl 30d',
	'ATT30' => 'Anwesenheit 30d',

	// DKP page titles
	'LISTADJ_TITLE' => 'Korrekturen',
	'LISTEVENTS_TITLE' => 'Ereignisse',
	'LISTIADJ_TITLE' => 'Korrekturen',
	'LISTITEMS_TITLE' => 'Gegenstand Werte',
	'LISTPURCHASED_TITLE' => 'Gegenstand Historie',
	'LISTRAIDS_TITLE' => 'Raids',
	'STATS_TITLE' => '%s Statistiken',
	'TITLE_PREFIX' => '%s %s',
	'VIEWEVENT_TITLE' => 'Gepeicherte Raid Historie für %s',
	'VIEWITEM_TITLE' => 'Kauf Historie für %s',
	'VIEWPLAYER_TITLE' => 'Raid Historie für %s',
	'VIEWRAID_TITLE' => 'Raid Zusammenfassung',
	'TRANSFER_PLAYER_HISTORY' => 'Mitglieder Historie',
	'RECORDED_RAID_HISTORY' => 'Gespeicherte Raid-Historie für %s',
	'RECORDED_DROP_HISTORY' => 'Gespeicherte Kauf-Historie für %s',

	// DKP navigation
	'LIST_EVENTS' => 'Ereignisse zeigen',
	'LIST_INDIVADJ' => 'Individuelle Korrekturen anzeigen',
	'LIST_ITEMS' => 'Gegenstände zeigen',
	'LIST_RAIDS' => 'Raids zeigen',
	'SELECT_EXISTING' => 'Wähle vorhandene',
	'OF_RAIDS' => '%d',
	'OF_RAIDS_CHAR' => '%s',
	'EVENTNOTE' => 'Notiz: Nur Raids und Beute von aktive Ereignisse werden gezeigt',

	// Page foot counts
	'LISTEVENTS_FOOTCOUNT' => '... %d Ereignisse gefunden / %d pro Seite',
	'LISTIADJ_FOOTCOUNT' => '... %d Korrektionen gefunden / %d pro Seite',
	'LISTITEMS_FOOTCOUNT' => '... %d einmalige Gegenstände / %d pro Seite',
	'LISTNEWS_FOOTCOUNT' => '... %d Nachrichten gefunden',
	'LISTPLAYERS_ACTIVE_FOOTCOUNT' => '... %d aktive(s) Mitglied(er) gefunden / %s Zeige alle</a>',
	'LISTPLAYERS_COMPARE_FOOTCOUNT' => '... vergleiche %d Mitglieder',
	'LISTPURCHASED_FOOTCOUNT' => '... %d Gegenstände gefunden / %d pro Seite',
	'LISTPURCHASED_FOOTCOUNT_SHORT' => '... %d Gegenstände gefunden',
	'LISTRAIDS_FOOTCOUNT' => '... %d raid(s) gefunden / %d pro Seite',
	'STATS_ACTIVE_FOOTCOUNT' => '... %d Active(s) Mitglied(er) gefunden / %s zeige alle</a>',
	'STATS_FOOTCOUNT' => '... %d Mitglieder gefunden',
	'VIEWEVENT_FOOTCOUNT' => '... %d Raid(s) gefunden',
	'VIEWITEM_FOOTCOUNT' => '... %d Gegenstände gefunden',
	'VIEWPLAYER_ADJUSTMENT_FOOTCOUNT' => '... %d Punktekorrektur(en) gefunden',
	'VIEWPLAYER_ITEM_FOOTCOUNT' => '... %d gekaufte Gegenstände gefunden / %d pro Seite',
	'VIEWPLAYER_RAID_FOOTCOUNT' => '... %d teilgenommene(n) Raid(s) gefunden / %d pro Seite',
	'VIEWPLAYER_EVENT_FOOTCOUNT' => '... %d teilgenommen(es) Ereignis(se)',
	'VIEWRAID_ATTENDEES_FOOTCOUNT' => '... %d Teilnehmer gefunden',
	'VIEWRAID_DROPS_FOOTCOUNT' => '... %d Drop(s) gefunden',

	// DKP form elements
	'LOG_ADD_DATA' => 'Daten hinzufügen',
	'PROCEED' => 'Fortfahren',
	'UPGRADE' => 'Upgrade',

	// DKP permissions
	'NOAUTH_U_EVENT_LIST' => 'Du hast keine Berechtigung Ereignisse aufzulisten.',
	'NOAUTH_U_EVENT_VIEW' => 'Du hast keine Berechtigung Ereignisse zu sehen.',
	'NOAUTH_U_ITEM_LIST' => 'Du hast keine Berechtigung Gegenstände aufzulisten.',
	'NOAUTH_U_ITEM_VIEW' => 'Du hast keine Berechtigung Gegenstände zu sehen.',
));
