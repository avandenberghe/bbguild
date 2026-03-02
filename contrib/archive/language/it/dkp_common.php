<?php
/**
 * DKP language keys archived from bbguild common.php [Italian]
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
	'DKP_VALUE' => 'Vale %s',
	'EP' => 'EP',
	'EPLONG' => 'Punti Efficienza',
	'EPNET' => 'EP net',
	'EPNETLONG' => 'Net Effort Points',
	'GP' => 'GP',
	'GPLONG' => 'Punti Equipaggiamanto',
	'GPNET' => 'GP net',
	'PR' => 'PR',
	'PRLONG' => 'Fattore Priorità',
	'RAIDDECAY' => 'Decadimento Incursione',
	'ADJDECAY' => 'Modifiche decadimento',
	'DECAY' => 'Decadimento',
	'ZSVALUE' => 'SommaZero',
	'ZEROSUM' => 'Bonus SommaZero',

	// Standings / Points
	'ADJUSTMENT' => 'Modifiche',
	'ADJUSTMENTS' => 'Correzioni',
	'ADJUSTMENT_HISTORY' => 'Storico Correzioni',
	'INDIV_ADJ' => 'Corre. Individ.',
	'NETADJUSTMENT' => 'Net',
	'NETSPENT' => 'Net',
	'ALL' => 'Tutti',
	'CURRENT' => 'Correnti',
	'EARNED' => 'Acquisiti',
	'SPENT' => 'Spesi',
	'POOL' => 'Gruppi Punteggio',
	'TOTAL_ADJUSTMENTS' => 'Modifiche Totale',
	'TOTAL_EARNED' => 'Totale Acquisito',
	'TOTAL_SPENT' => 'Total Speso',
	'NO_ADJUSTMENTS' => 'Nessuna Modifica',
	'PER_DAY' => 'Al Giorno',
	'PER_RAID' => 'Per Incursione',
	'PCT_EARNED_LOST_TO' => '% Acquisita e persa',
	'FORNPOINTS' => ' for %s points.',
	'MAY_BE_NEGATIVE_NOTE' => 'può essere negativo',
	'NODKPACCOUNTS' => 'Nessunt account DKP trovato',
	'LEADERBOARDSTAT' => 'Leaderboard vs. Raidcount',

	// Loot / Items
	'RECENTLOOT' => 'Ultimi Oggetti',
	'ITEM' => 'Oggetto',
	'ITEMS' => 'Oggetti',
	'ITEMVALUE' => 'Valore Oggetto',
	'ITEMDECAY' => 'Decadimento Oggetto',
	'ITEMTOTAL' => 'Valore Totale',
	'ITEM_PURCHASE_HISTORY' => 'Storico Acquisto Oggetti',
	'BUYER' => 'Compratore',
	'BUYERS' => 'Compratori',
	'DROPS' => 'Bottino',
	'LOOTS' => 'Bottini',
	'LOOTDIST_CLASS' => 'Distribuzione bottino per Classi',
	'LOOTED' => 'obtained',
	'LOOT_FACTOR' => 'Coefficiente Bottino',
	'LASTLOOT' => 'Ultimo Bottino',
	'NO_LOOT' => 'No Bottino',
	'PURCHASE_HISTORY_FOR' => 'Storico Acquisti per %s',
	'TOTAL_ITEMS' => 'Totale Bottino',
	'ENTER_NEW_GAMEITEMID' => 'ID Oggetto (gioco)',

	// Raid values
	'RAIDVALUE' => 'Valore Incursione',
	'RAIDBONUS' => 'Bonus Incursione',
	'TIME_BONUS' => 'Orario Bonus',
	'TIMEVALUE' => 'Valore Orario',
	'TOTAL_RAIDS' => 'Totale Incursioni',
	'NO_RAIDS' => 'Nessuna Incursione',
	'RAID_ON' => 'Incursione del %s il %s',

	// Attendance tracking
	'RAID_ATTENDANCE_HISTORY' => 'Storico Presenze Incursioni',
	'ATTENDANCE_BY_EVENT' => 'Presenze per evento',
	'ATTENDANCE_LIFETIME' => 'ATTENDANCE_LIFETIME',
	'RAIDS_LIFETIME' => 'Scadenza (%s - %s)',
	'RAIDS_X_DAYS' => 'Ultimi %d Giorni',
	'COMPARE_PLAYERS' => 'Confronta Personaggi',
	'BOSSKILLCOUNT' => 'Bosskills',
	'IRCTLIFE' => 'Ind.Conteggio vita',
	'GRCTLIFE' => 'Tut.Conteggio vita',
	'ATTLIFE' => 'Conteggio Life',
	'IRCT90' => 'Ind.Conteggio 90d',
	'GRCT90' => 'Tut.Conteggio 90d',
	'ATT90' => 'Conteggio 90d',
	'IRCT60' => 'Ind.Conteggio 60d',
	'GRCT60' => 'Tut.Conteggio 60d',
	'ATT60' => 'Conteggio 60d',
	'IRCT30' => 'Ind.Conteggio 30d',
	'GRCT30' => 'Tut.Conteggio 30d',
	'ATT30' => 'Conteggio 30d',

	// DKP page titles
	'LISTADJ_TITLE' => 'Lista Modifiche',
	'LISTEVENTS_TITLE' => 'Valore Evento',
	'LISTIADJ_TITLE' => 'Lista Modifiche Individuali',
	'LISTITEMS_TITLE' => 'Valore Oggetti',
	'LISTPURCHASED_TITLE' => 'Storico Oggetti',
	'LISTRAIDS_TITLE' => 'Lista Incursioni',
	'STATS_TITLE' => '%s Statistiche',
	'TITLE_PREFIX' => '%s %s',
	'VIEWEVENT_TITLE' => 'Visualizza Storico Incursioni di %s',
	'VIEWITEM_TITLE' => 'Visualizza Storico Acquisti per %s',
	'VIEWPLAYER_TITLE' => 'Storico per %s',
	'VIEWRAID_TITLE' => 'Lista Incursioni',
	'TRANSFER_PLAYER_HISTORY' => 'Storico Trasferimenti Pesonaggio',
	'RECORDED_RAID_HISTORY' => 'Storico Incursioni',
	'RECORDED_DROP_HISTORY' => 'Storico Acquisti',

	// DKP navigation
	'LIST_EVENTS' => 'Elenca Eventi',
	'LIST_INDIVADJ' => 'Elenca Modifiche Individuali',
	'LIST_ITEMS' => 'Elenco Oggetti',
	'LIST_RAIDS' => 'Elenco Incursioni',
	'SELECT_EXISTING' => 'Seleziona Esistente',
	'OF_RAIDS' => '%d',
	'OF_RAIDS_CHAR' => '%s',
	'EVENTNOTE' => 'Note: only lists attended raids or obtained loot from active events.',

	// Page foot counts
	'LISTEVENTS_FOOTCOUNT' => '... trovati %d eventi',
	'LISTIADJ_FOOTCOUNT' => '... trovate %d modifiche individuali / %d per pagina',
	'LISTITEMS_FOOTCOUNT' => '... trovati %d oggetti unici / %d per pagina',
	'LISTNEWS_FOOTCOUNT' => '... trovate %d Notizie sui Loot',
	'LISTPLAYERS_ACTIVE_FOOTCOUNT' => '... trovato %d mebri attivi / %sshow totale</a>',
	'LISTPLAYERS_COMPARE_FOOTCOUNT' => '... confronta %d mebri',
	'LISTPURCHASED_FOOTCOUNT' => '... trovato %d oggetto(i) / %d per pagina',
	'LISTPURCHASED_FOOTCOUNT_SHORT' => '... trovato %d oggetto(i)',
	'LISTRAIDS_FOOTCOUNT' => '... trovato %d incursione(i) / %d per pagina',
	'STATS_ACTIVE_FOOTCOUNT' => '... trovato %d mebri attivi / %sshow sul totale</a>',
	'STATS_FOOTCOUNT' => '... trovato %d membro(i) /%sshow attivi</a>',
	'VIEWEVENT_FOOTCOUNT' => '... trovato %d incursione(i)',
	'VIEWITEM_FOOTCOUNT' => '... trovato %d oggetto(i)',
	'VIEWPLAYER_ADJUSTMENT_FOOTCOUNT' => '... trovato %d modifica(che) individuale(i)',
	'VIEWPLAYER_ITEM_FOOTCOUNT' => '... trovato %d oggetto(i) acquistati / %d per pagina',
	'VIEWPLAYER_RAID_FOOTCOUNT' => '... trovato %d partecipazioni a incursioni / %d per pagina',
	'VIEWPLAYER_EVENT_FOOTCOUNT' => '... trovato %d partecipazioni ad eventi',
	'VIEWRAID_ATTENDEES_FOOTCOUNT' => '... trovato %d partceipante(i)',
	'VIEWRAID_DROPS_FOOTCOUNT' => '... trovato %d bottino(i)',

	// DKP form elements
	'LOG_ADD_DATA' => 'Add Data to Form',
	'PROCEED' => 'Procedi',
	'UPGRADE' => 'Aggiorna',

	// DKP permissions
	'NOAUTH_U_EVENT_LIST' => 'Non hai il permesso di visualizzare lista eventi.',
	'NOAUTH_U_EVENT_VIEW' => 'Non hai il permesso di visualizzare eventi.',
	'NOAUTH_U_ITEM_LIST' => 'Non hai il permesso di elencare gli oggetti.',
	'NOAUTH_U_ITEM_VIEW' => 'Non hai il permesso di vedere gli oggetti.',
));
