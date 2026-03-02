<?php
/**
 * DKP language keys archived from bbguild common.php [French]
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
	'DKP_VALUE' => '%s Valeur',
	'EP' => 'EP',
	'EPLONG' => 'Points d\'effort',
	'EPNET' => 'EP net',
	'EPNETLONG' => 'Points d\'effort net',
	'GP' => 'GP',
	'GPLONG' => 'Points de Gear',
	'GPNET' => 'GP net',
	'PR' => 'PR',
	'PRLONG' => 'Ratio Priorité',
	'RAIDDECAY' => 'Amortissement Raid',
	'ADJDECAY' => 'Amortissement des ajustement',
	'DECAY' => 'Amortissement',
	'ZSVALUE' => 'Somme Zero',
	'ZEROSUM' => 'bonus Somme Zero',

	// Standings / Points
	'ADJUSTMENT' => 'Ajustment',
	'ADJUSTMENTS' => 'Ajustements',
	'ADJUSTMENT_HISTORY' => 'Historique d\'ajustements',
	'INDIV_ADJ' => 'Hist. Ajust.',
	'NETADJUSTMENT' => 'Net',
	'NETSPENT' => 'Net',
	'ALL' => 'Tous',
	'CURRENT' => 'Courant',
	'EARNED' => 'Gagné',
	'SPENT' => 'Dépensé',
	'POOL' => 'Groupe DKP',
	'TOTAL_ADJUSTMENTS' => 'Total Ajustments',
	'TOTAL_EARNED' => 'Total Gagné',
	'TOTAL_SPENT' => 'Total Dépensé',
	'NO_ADJUSTMENTS' => 'pas d\'ajustments trouvés',
	'PER_DAY' => 'Par Journée',
	'PER_RAID' => 'Par Raid',
	'PCT_EARNED_LOST_TO' => '% Gagné Perdu à',
	'FORNPOINTS' => ' for %s points.',
	'MAY_BE_NEGATIVE_NOTE' => 'peut être négatif',
	'NODKPACCOUNTS' => 'Pas de comptes DKP trouvés',
	'LEADERBOARDSTAT' => 'Points vs. Nombre de Raids',

	// Loot / Items
	'RECENTLOOT' => 'Objets récents',
	'ITEM' => 'Objet',
	'ITEMS' => 'Objets',
	'ITEMVALUE' => 'Valeur Butin',
	'ITEMDECAY' => 'Amortissement Butin',
	'ITEMTOTAL' => 'Veleur totale',
	'ITEM_PURCHASE_HISTORY' => 'Historique d\'achats',
	'BUYER' => 'Acheteur',
	'BUYERS' => 'Acheteurs',
	'DROPS' => 'Butin',
	'LOOTS' => 'Trésors',
	'LOOTDIST_CLASS' => 'Distribution par classe du butin.',
	'LOOTED' => 'obtained',
	'LOOT_FACTOR' => 'Facteur de Loot',
	'LASTLOOT' => 'Dernier Loot',
	'NO_LOOT' => 'Pas de butin',
	'PURCHASE_HISTORY_FOR' => 'Historique d\'achats for %s',
	'TOTAL_ITEMS' => 'Total Objets',
	'ENTER_NEW_GAMEITEMID' => 'ID d\'objet',

	// Raid values
	'RAIDVALUE' => 'Valeur',
	'RAIDBONUS' => 'Bonus Raid',
	'TIME_BONUS' => 'Bonus Temps',
	'TIMEVALUE' => 'Points par temps',
	'TOTAL_RAIDS' => 'Total Raids',
	'NO_RAIDS' => 'Pas de Raids',
	'RAID_ON' => 'Raid sur %s le %s',

	// Attendance tracking
	'RAID_ATTENDANCE_HISTORY' => 'Historique Participation',
	'ATTENDANCE_BY_EVENT' => 'Participation par Evènement',
	'ATTENDANCE_LIFETIME' => 'Participation totale',
	'RAIDS_LIFETIME' => 'à Vie (%s - %s)',
	'RAIDS_X_DAYS' => 'Derniers %d Jours',
	'COMPARE_PLAYERS' => 'Comparer Membres',
	'BOSSKILLCOUNT' => 'Bosscount',
	'IRCTLIFE' => '#Raids',
	'GRCTLIFE' => '# Raids Guilde',
	'ATTLIFE' => 'Partipation',
	'IRCT90' => '#Raids 90d',
	'GRCT90' => '#Raids Guilde 90d',
	'ATT90' => 'Participation 90d',
	'IRCT60' => '#Raids 60d',
	'GRCT60' => '#Raids Guilde 60d',
	'ATT60' => 'Participation 60d',
	'IRCT30' => '#Raids 30d',
	'GRCT30' => '#Raids Guilde 30d',
	'ATT30' => 'Participation 30d',

	// DKP page titles
	'LISTADJ_TITLE' => 'Liste ajustements',
	'LISTEVENTS_TITLE' => 'Valeurs d\'évènements',
	'LISTIADJ_TITLE' => 'Liste d\'ajustements',
	'LISTITEMS_TITLE' => 'Valeurs d\'objets',
	'LISTPURCHASED_TITLE' => 'Historique d\'objets',
	'LISTRAIDS_TITLE' => 'Liste des Raids',
	'STATS_TITLE' => '%s Stats',
	'TITLE_PREFIX' => '%s %s',
	'VIEWEVENT_TITLE' => 'Vue de l\'historique des Raids de %s',
	'VIEWITEM_TITLE' => 'Vue de l\'historique des achats de %s',
	'VIEWPLAYER_TITLE' => 'Historique de %s',
	'VIEWRAID_TITLE' => 'Vue du Raid',
	'TRANSFER_PLAYER_HISTORY' => 'Transfert Historique Membre',
	'RECORDED_RAID_HISTORY' => 'Historique des Raids de %s',
	'RECORDED_DROP_HISTORY' => 'Historique des Achats par %s',

	// DKP navigation
	'LIST_EVENTS' => 'Liste Evènements',
	'LIST_INDIVADJ' => 'Liste Ajustements',
	'LIST_ITEMS' => 'Liste Objets',
	'LIST_RAIDS' => 'Liste Raids',
	'SELECT_EXISTING' => 'Sélectionner existant',
	'OF_RAIDS' => '%d',
	'OF_RAIDS_CHAR' => '%s',
	'EVENTNOTE' => 'Note: se montrent uniquement les évenerments actifs.',

	// Page foot counts
	'LISTEVENTS_FOOTCOUNT' => '... trouvé %d évènements / %d par page',
	'LISTIADJ_FOOTCOUNT' => '... trouvé %d ajustment(s) / %d par page',
	'LISTITEMS_FOOTCOUNT' => '... trouvé %d Objets uniques / %d par page',
	'LISTNEWS_FOOTCOUNT' => '... trouvé %d Nouvelles / %d par page',
	'LISTPLAYERS_ACTIVE_FOOTCOUNT' => '... trouvé %d membres actifs / %s montrer tous</a>',
	'LISTPLAYERS_COMPARE_FOOTCOUNT' => '... Comparaison %d membres',
	'LISTPURCHASED_FOOTCOUNT' => '... trouvé %d objets(s) / %d par page',
	'LISTPURCHASED_FOOTCOUNT_SHORT' => '... trouvé %d item(s)',
	'LISTRAIDS_FOOTCOUNT' => '... trouvé %d raid(s) / %d par page',
	'STATS_ACTIVE_FOOTCOUNT' => '... trouvé %d membres actif(s) / %s montrer tous</a>',
	'STATS_FOOTCOUNT' => '... trouvé %d membres(s)',
	'VIEWEVENT_FOOTCOUNT' => '... trouvé %d raid(s)',
	'VIEWITEM_FOOTCOUNT' => '... trouvé %d Objets(s)',
	'VIEWPLAYER_ADJUSTMENT_FOOTCOUNT' => '... trouvé %d ajustement(s)',
	'VIEWPLAYER_ITEM_FOOTCOUNT' => '... trouvé %d objet(s) achetés(s) / %d par page',
	'VIEWPLAYER_RAID_FOOTCOUNT' => '... trouvé %d raid(s) participé(s) / %d par page',
	'VIEWPLAYER_EVENT_FOOTCOUNT' => '... trouvé %d évènement(s) participé(s)',
	'VIEWRAID_ATTENDEES_FOOTCOUNT' => '... trouvé %d participant(s)',
	'VIEWRAID_DROPS_FOOTCOUNT' => '... trouvé %d dépouilles(s)',

	// DKP form elements
	'LOG_ADD_DATA' => 'Ajouter Donnée',
	'PROCEED' => 'Procédez',
	'UPGRADE' => 'Mise à jour',

	// DKP permissions
	'NOAUTH_U_EVENT_LIST' => 'Vous n\'avez pas la permission de voir la liste des évènements.',
	'NOAUTH_U_EVENT_VIEW' => 'Vous n\'avez pas la permission de voir cet évènement.',
	'NOAUTH_U_ITEM_LIST' => 'Vous n\'avez pas la permission de voir la liste des objets.',
	'NOAUTH_U_ITEM_VIEW' => 'Vous n\'avez pas la permission de voir cet objet.',
));
