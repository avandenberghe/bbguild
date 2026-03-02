<?php
/**
 * DKP language keys archived from bbguild common.php [English]
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
	'DKP_VALUE' => '%s Value',
	'EP' => 'EP',
	'EPLONG' => 'Effort points',
	'EPNET' => 'EP net',
	'EPNETLONG' => 'Net Effort Points',
	'GP' => 'GP',
	'GPLONG' => 'Gear points',
	'GPNET' => 'GP net',
	'PR' => 'PR',
	'PRLONG' => 'Priority ratio',
	'RAIDDECAY' => 'Raid Decay',
	'ADJDECAY' => 'Adjustment decay',
	'DECAY' => 'Decay',
	'ZSVALUE' => 'Zerosum',
	'ZEROSUM' => 'Zerosum bonus',

	// Standings / Points
	'ADJUSTMENT' => 'Adjustment',
	'ADJUSTMENTS' => 'Adjustments',
	'ADJUSTMENT_HISTORY' => 'Adjustments History',
	'INDIV_ADJ' => 'Indiv. Adj.',
	'NETADJUSTMENT' => 'Net',
	'NETSPENT' => 'Net',
	'ALL' => 'All',
	'CURRENT' => 'Current',
	'EARNED' => 'Earned',
	'SPENT' => 'Spent',
	'POOL' => 'Point Pool',
	'TOTAL_ADJUSTMENTS' => 'Total Adjustments',
	'TOTAL_EARNED' => 'Total Earned',
	'TOTAL_SPENT' => 'Total Spent',
	'NO_ADJUSTMENTS' => 'No Adjustments',
	'PER_DAY' => 'Per Day',
	'PER_RAID' => 'Per Raid',
	'PCT_EARNED_LOST_TO' => '% Earned Lost to',
	'FORNPOINTS' => ' for %s points.',
	'MAY_BE_NEGATIVE_NOTE' => 'may be negative',
	'NODKPACCOUNTS' => 'No DKP accounts found',
	'LEADERBOARDSTAT' => 'Leaderboard vs. Raidcount',

	// Loot / Items
	'RECENTLOOT' => 'Recent Loot',
	'ITEM' => 'Item',
	'ITEMS' => 'Items',
	'ITEMVALUE' => 'Item Value',
	'ITEMDECAY' => 'Item Decay',
	'ITEMTOTAL' => 'Total value',
	'ITEM_PURCHASE_HISTORY' => 'Item Purchase History',
	'BUYER' => 'Buyer',
	'BUYERS' => 'Buyers',
	'DROPS' => 'Drops',
	'LOOTS' => 'Loots',
	'LOOTDIST_CLASS' => 'Loot distribution per Class',
	'LOOTED' => 'obtained',
	'LOOT_FACTOR' => 'Loot Factor',
	'LASTLOOT' => 'Last Loot',
	'NO_LOOT' => 'No Loot',
	'PURCHASE_HISTORY_FOR' => 'Purchase History for %s',
	'TOTAL_ITEMS' => 'Total Items',
	'ENTER_NEW_GAMEITEMID' => 'Game item ID',

	// Raid values
	'RAIDVALUE' => 'Raid Value',
	'RAIDBONUS' => 'Raid Bonus',
	'TIME_BONUS' => 'Time bonus',
	'TIMEVALUE' => 'Time Value',
	'TOTAL_RAIDS' => 'Total Raids',
	'NO_RAIDS' => 'No Raids',
	'RAID_ON' => 'Raid on %s on %s',

	// Attendance tracking
	'RAID_ATTENDANCE_HISTORY' => 'Raid Attendance History',
	'ATTENDANCE_BY_EVENT' => 'Attendance by Event',
	'ATTENDANCE_LIFETIME' => 'Lifetime Attendance',
	'RAIDS_LIFETIME' => 'Lifetime (%s - %s)',
	'RAIDS_X_DAYS' => 'Last %d Days',
	'COMPARE_PLAYERS' => 'Compare Players',
	'BOSSKILLCOUNT' => 'Bosskills',
	'IRCTLIFE' => 'Ind.Raidcount Life',
	'GRCTLIFE' => 'All.Raidcount Life',
	'ATTLIFE' => 'Attendance Life',
	'IRCT90' => 'Ind.Raidcount 90d',
	'GRCT90' => 'All.Raidcount 90d',
	'ATT90' => 'Attendance 90d',
	'IRCT60' => 'Ind.Raidcount 60d',
	'GRCT60' => 'All.Raidcount 60d',
	'ATT60' => 'Attendance 60d',
	'IRCT30' => 'Ind.Raidcount 30d',
	'GRCT30' => 'All.Raidcount 30d',
	'ATT30' => 'Attendance 30d',

	// DKP page titles
	'LISTADJ_TITLE' => 'Adjustment Listing',
	'LISTEVENTS_TITLE' => 'Event Values',
	'LISTIADJ_TITLE' => 'Individual Adjustment Listing',
	'LISTITEMS_TITLE' => 'Item Values',
	'LISTPURCHASED_TITLE' => 'Item History',
	'LISTRAIDS_TITLE' => 'Raids Listing',
	'STATS_TITLE' => '%s Stats',
	'TITLE_PREFIX' => '%s %s',
	'VIEWEVENT_TITLE' => 'Viewing Recorded Raid History for %s',
	'VIEWITEM_TITLE' => 'Viewing Purchase History for %s',
	'VIEWPLAYER_TITLE' => 'History for %s',
	'VIEWRAID_TITLE' => 'Raid Summary',
	'TRANSFER_PLAYER_HISTORY' => 'Transfer Player History',
	'RECORDED_RAID_HISTORY' => 'Raid History',
	'RECORDED_DROP_HISTORY' => 'Purchase History',

	// DKP navigation
	'LIST_EVENTS' => 'List Events',
	'LIST_INDIVADJ' => 'List Individual Adjustments',
	'LIST_ITEMS' => 'List Items',
	'LIST_RAIDS' => 'List Raids',
	'SELECT_EXISTING' => 'Select Existing',
	'OF_RAIDS' => '%d',
	'OF_RAIDS_CHAR' => '%s',
	'EVENTNOTE' => 'Note: only lists attended raids or obtained loot from active events.',

	// Page foot counts
	'LISTEVENTS_FOOTCOUNT' => '... found %d events',
	'LISTIADJ_FOOTCOUNT' => '... found %d individual adjustment(s) / %d per page',
	'LISTITEMS_FOOTCOUNT' => '... found %d unique items / %d per page',
	'LISTNEWS_FOOTCOUNT' => '... found %d News Items',
	'LISTPLAYERS_ACTIVE_FOOTCOUNT' => '... found %d active players / %sshow all</a>',
	'LISTPLAYERS_COMPARE_FOOTCOUNT' => '... comparing %d players',
	'LISTPURCHASED_FOOTCOUNT' => '... found %d item(s) / %d per page',
	'LISTPURCHASED_FOOTCOUNT_SHORT' => '... found %d item(s)',
	'LISTRAIDS_FOOTCOUNT' => '... found %d raid(s) / %d per page',
	'STATS_ACTIVE_FOOTCOUNT' => '... found %d active player(s) / %sshow all</a>',
	'STATS_FOOTCOUNT' => '... found %d player(s) /%sshow active</a>',
	'VIEWEVENT_FOOTCOUNT' => '... found %d raid(s)',
	'VIEWITEM_FOOTCOUNT' => '... found %d item(s)',
	'VIEWPLAYER_ADJUSTMENT_FOOTCOUNT' => '... found %d individual adjustment(s)',
	'VIEWPLAYER_ITEM_FOOTCOUNT' => '... found %d purchased item(s) / %d per page',
	'VIEWPLAYER_RAID_FOOTCOUNT' => '... found %d attended raid(s) / %d per page',
	'VIEWPLAYER_EVENT_FOOTCOUNT' => '... found %d attended event(s)',
	'VIEWRAID_ATTENDEES_FOOTCOUNT' => '... found %d attendee(s)',
	'VIEWRAID_DROPS_FOOTCOUNT' => '... found %d drop(s)',

	// DKP form elements
	'LOG_ADD_DATA' => 'Add Data to Form',
	'PROCEED' => 'Proceed',
	'UPGRADE' => 'Upgrade',

	// DKP permissions
	'NOAUTH_U_EVENT_LIST' => 'You do not have permission to list events.',
	'NOAUTH_U_EVENT_VIEW' => 'You do not have permission to view events.',
	'NOAUTH_U_ITEM_LIST' => 'You do not have permission to list items.',
	'NOAUTH_U_ITEM_VIEW' => 'You do not have permission to view items.',
));
