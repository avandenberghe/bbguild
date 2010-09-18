<?php
/**
 * bbdkp common language file - German
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}


// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
'BBDKPDISABLED' => 'bbDKP is gerade deaktiviert.', 

//---- Portal blocks ----- 
'PORTAL'	=> 'Portal', 
'RECENTLOOT' => 'Letzter Loot', 
'REMEMBERME' => 'Erinnere mich', 
'INFORUM'	=> 'in', 
'DKP'	=> 'DKP', 
'NEWS' => 'Nachrichten',
'COMMENT' => 'Kommentare',
'NEWS_PER_PAGE' => 'Nachrichten pro Seite',
'ERROR_INVALID_NEWS_PROVIDED' => 'Ungültige Nachricht.',
'BOSSPROGRESS' => 'Bossvorgang', 	

// Main Menu
'MENU' => 'Menü', 
'MENU_ADMIN_PANEL' => 'ACP',
'MENU_BOSS' => 'Bossvorgang',
'MENU_EVENTS' => 'Ereignisse',
'MENU_ITEMVAL' => 'Item Werte',
'MENU_ITEMHIST' => 'Item Ankäufe',
'MENU_NEWS' => 'Nachrichten',
'MENU_RAIDS' => 'Raids',
'MENU_ROSTER'	=> 'Mitgliedsbuch',
'MENU_STATS' => 'Statistiken',
'MENU_SUMMARY' => 'Übersicht',
'MENU_STANDINGS' => 'Anzeigetafel',
'MENU_VIEWMEMBER' => 'Mitglied',
'MENU_VIEWITEM' => 'Gegenstand',
'MENU_VIEWRAID' => 'View Raid',
'MENU_VIEWEVENT' => 'Ereignis',

//links
'MENU_LINKS' => 'Web Links',
'LINK1' => 'http://www.bbdkp.com', 
'LINK1T' => 'Powered By: bbDKP',
'LINK2' => 'http://everquest2.station.sony.com/', 
'LINK2T' => 'Everquest 2', 
'LINK3' => 'http://www.playonline.com/ff11us/index.shtml', 
'LINK3T' => 'FFXI', 
'LINK4' => 'http://www.lotro.com', 
'LINK4T' => 'Lord of the Rings', 
'LINK5' => 'http://uk.aiononline.com', 
'LINK5T' => 'Aion Online', 
'LINK6' => 'http://www.worldofwarcraft.com', 
'LINK6T' => 'World of Warcraft', 
'LINK7' => 'http://www.warhammeronline.com', 
'LINK7T' => 'Warhammer', 
'LINK8' => 'http://www.vanguardmmorpg.com', 
'LINK8T' => 'Vanguard', 

//Recruitment
'RECRUITMENT_BLOCK' => 'Rekrutierungs Status', 
'RECRUIT_CLOSED' => 'Geslossen !', 
'TANK'	=>    'Schutz',
'DPS'	=>    'Schaden',
'HEAL'	=>    'Heilung',
'RECRUIT_MESSAGE' => 'Wir sind zurzeit auf der Suche nach neue Mitglieder für den folgenden Klassen:',

//ROSTER

'GUILDROSTER' => 'Mitgliedsbuch',
'RANK' 		  => 'Rang',
'CLASS' 	  => 'Klasse',
'LVL' 		  => 'Level',
'ACHIEV'	  => 'Erfolge', 

//listmembers
'ADJUSTMENT' => 'Anpassung',
'ALL' => 'Alle', 
'CURRENT' => 'Jetzt',
'EARNED' => 'Bekommen',
'FILTER' => 'Filter',
'LASTRAID' => 'Letzter Raid',
'LEVEL' => 'Level',
'LISTMEMBERS_TITLE' => 'Mitglieder Statistik',
'MNOTFOUND' => 'Mitglied nicht gefunden', 
'EMPTYRAIDNAME' => 'Raidname nicht gefunden', 
'NAME' => 'Name',
'POOL' => 'DKP Pool',
'RAID_ATTENDANCE_HISTORY' => 'Raidbeteiligungs Historie',
'RAIDS_LIFETIME' => 'Lebensdauer (%s - %s)',
'RAIDS_X_DAYS' => 'Letzte %d Tage',
'SPENT' => 'Ausgegeben',
'COMPARE_MEMBERS' => 'Vergleiche Mitglieder',
'LISTMEMBERS_FOOTCOUNT' => '... %d Mitglieder gefunden',

'LISTADJ_TITLE' => 'Punkteanpassungsliste',
'LISTEVENTS_TITLE' => 'Ereigniswerten',
'LISTIADJ_TITLE' => 'Punkteanpassungsliste',
'LISTITEMS_TITLE' => 'Item Werte',
'LISTPURCHASED_TITLE' => 'Item Historie',
'LISTRAIDS_TITLE' => 'Raidliste',
'LOGIN_TITLE' => 'Login',
'STATS_TITLE' => '%s Statistiken',
'TITLE_PREFIX' => '%s %s',
'VIEWEVENT_TITLE' => 'Gepeicherte Raid Historie für %s',
'VIEWITEM_TITLE' => 'Kauf Historie für %s',
'VIEWMEMBER_TITLE' => 'Raid Historie für %s',
'VIEWRAID_TITLE' => 'Raid Zusammenfassung',

// Various
'ACCOUNT' => 'Konto',
'ACTION' => 'Aktion',
'ACTIVE' => 'Activ',
'ADD' => 'Zufügen',
'ADDED_BY' => 'Zugefügt von',

'ADMINISTRATION' => 'Administration',
'ADMINISTRATIVE_OPTIONS' => 'Administrative Einstellungen',
'ADMIN_INDEX' => 'Admin Index',
'ATTENDANCE_BY_EVENT' => 'Beteiligung bei Ereignis',
'ATTENDED' => 'Teilgenommen',
'ATTENDEES' => 'Teilnehmer',
'AVERAGE' => 'Durchschnitt',
'BOSS' => 'Boss',
'BUYER' => 'Käufer',
'BUYERS' => 'Käufer',

'ARMOR' => 'Rüstung',
'TYPE' => 'Rüstung',
// TYPES of armor are static across games, no need to put it in DB
'CLOTH' => 'Stoff', 
'LEATHER' => 'Leder', 
'MAIL' =>  'Schwere Rüstung', 
'PLATE' => 'Platte', 

'CLASSID' => 'Klasse ID',
'CLASSARMOR' => 'Klasserüstung',
'CLASSIMAGE' => 'Bild',
'CLASSMIN' => 'Minimum level',
'CLASSMAX' => 'Maximum level',
'CLASS_DISTRIBUTION' => 'Klassendistribution',
'CLASS_SUMMARY' => 'Klassen Übersicht: %s to %s',
'CONFIGURATION' => 'Konfiguration',

'DATE' => 'Datum',
'DELETE' => 'Löschen',
'DELETE_CONFIRMATION' => 'Bestätige Löschung',
'DKP_VALUE' => '%s Wert',
'STATUS' => 'Status Y/N',
'DROPS' => 'Drops',
'EVENT' => 'Ereignis',
'EVENTNAME' => 'Ereignisname',
'EVENTS' => 'Ereignisse',
'FACTION' => 'Faktion',
'FACTIONID' => 'Faktions ID',
'FIRST' => 'Erster',
'GROUP_ADJ' => 'Anpassung.',
'GROUP_ADJUSTMENTS' => 'Anpassungen',
'INDIVIDUAL_ADJUSTMENTS' => 'Anpassungen',
'INDIVIDUAL_ADJUSTMENT_HISTORY' => 'Anpassungs Historie',
'INDIV_ADJ' => 'Anp.',
'ITEM' => 'Item',
'ITEMS' => 'Items',
'ITEM_PURCHASE_HISTORY' => 'Item Ankauf Historie',
'JOINDATE' => 'Gilden Eintrittsdatum',
'LAST' => 'Letzter',
'LASTLOOT' => 'Letztes Loot',

'LAST_VISIT' => 'Letzte Visite',

'LOG_DATE_TIME' => 'Datum/Zeit dieses Logs',
'LOOT_FACTOR' => 'Loot Faktor',
'LOOTS' => 'Loots',
'MANAGE' => 'Verwalten',
'MEMBER' => 'Mitglied',
'MEMBERS' => 'Mitglieder',
'MEMBERS_PRESENT_AT' => 'Mitglieder anwesend am %s um %s',
'MISCELLANEOUS' => 'Verschiedenes',


'NOTE' => 'Notiz',
'ONLINE' => 'Online',
'OPTIONS' => 'Einstellungen',
'OUTDATE' => 'Gilden Austrittsdatum',
'PERCENT' => 'Prozent',
'PERMISSIONS' => 'Berechtigungen',
'PER_DAY' => 'Pro Tag',
'PER_RAID' => 'Pro Raid',
'PCT_EARNED_LOST_TO' => '% Verdientes verringert durch',
'PREFERENCES' => 'Voreinstellungen',
'PURCHASE_HISTORY_FOR' => 'Kauf-Historie für %s',
'QUOTE' => 'Zitat',
'RACE' => 'Rasse',
'RAID' => 'Raid',
'RAIDS' => 'Raids',
'RAID_ID' => 'Raid ID',

'RANK_DISTRIBUTION' => 'Rang-Aufteilung',
'RECORDED_RAID_HISTORY' => 'Gespeicherte Raid-Historie für %s',
'RECORDED_DROP_HISTORY' => 'Gespeicherte Ankauf-Historie für %s',
'REASON' => 'Grund',
'REGISTRATION_INFORMATION' => 'Registrations Information',
'RESULT' => 'Ergebnis',
'SESSION_ID' => 'Session ID',
'SETTINGS' => 'Einstellungen',

'STATUS' => 'Status',
'SUMMARY_DATES' => 'Raid Zusammenfassung: %s bis %s',
'TIME' => 'Zeit',
'TOTAL' => 'Gesamt',
'TOTAL_EARNED' => 'Gesamt verdient',
'TOTAL_ITEMS' => 'Gesamt Items',
'TOTAL_RAIDS' => 'Gesamt Raids',
'TOTAL_SPENT' => 'Gesamt ausgegeben',
'TRANSFER_MEMBER_HISTORY' => 'Mitglieder Historie',
'TURN_INS' => 'Turn-ins',
'TYPE' => 'Type',
'UPDATE' => 'Aktualisieren',
'UPDATED_BY' => 'Aktualisiert By',
'USER' => 'Benutzer',
'USERNAME' => 'Benutzername',
'VALUE' => 'Wert',
'VIEW' => 'Ansehen',
'VIEW_ACTION' => 'Aktion ansehen',
'VIEW_LOGS' => 'Logs ansehen',

// Page Foot Counts

'LISTEVENTS_FOOTCOUNT' => '... %d Ereignisse gefunden / %d pro Seite',
'LISTIADJ_FOOTCOUNT' => '... %d Punkteanpassungen gefunden / %d pro Seite',
'LISTITEMS_FOOTCOUNT' => '... %d einmalige Items / %d pro Seite',
'LISTNEWS_FOOTCOUNT' => '... %d Nachrichten gefunden / %d pro Seite',
'LISTMEMBERS_ACTIVE_FOOTCOUNT' => '... %d aktive(s) Mitglied(er) gefunden / %s Zeige alle</a>',
'LISTMEMBERS_COMPARE_FOOTCOUNT' => '... vergleiche %d Mitglieder',
'LISTPURCHASED_FOOTCOUNT' => '... %d Item(s) gefunden / %d pro Seite',
'LISTPURCHASED_FOOTCOUNT_SHORT' => '... %d Item(s) gefunden',
'LISTRAIDS_FOOTCOUNT' => '... %d raid(s) gefunden / %d pro Seite',
'STATS_ACTIVE_FOOTCOUNT' => '... %d Active(s) Mitglied(er) gefunden / %sshow all</a>',
'STATS_FOOTCOUNT' => '... %d Mitglieder(er) gefunden',
'VIEWEVENT_FOOTCOUNT' => '... %d Raid(s) gefunden',
'VIEWITEM_FOOTCOUNT' => '... %d Item(s) gefunden',
'VIEWMEMBER_ADJUSTMENT_FOOTCOUNT' => '... %d Punkteanpassung(en) gefunden',
'VIEWMEMBER_ITEM_FOOTCOUNT' => '... %d gekaufte Item(s) gefunden / %d pro Seite',
'VIEWMEMBER_RAID_FOOTCOUNT' => '... %d teilgenommene(n) Raid(s) gefunden / %d pro Seite',
'VIEWMEMBER_EVENT_FOOTCOUNT' => '... %d teilgenommen(es) Ereignis(se)',
'VIEWRAID_ATTENDEES_FOOTCOUNT' => '... %d Teilnehmer gefunden',
'VIEWRAID_DROPS_FOOTCOUNT' => '... %d Drop(s) gefunden',

// Submit Buttons
'CLOSE_WINDOW' => 'Fenster Schließen',

'LOG_ADD_DATA' => 'Daten zufügen',
'NO' => 'Nein',
'PROCEED' => 'Fortfahren',
'RESET' => 'Zurücksetzen',
'SUBMIT' => 'Abschicken',
'UPGRADE' => 'Upgrade',
'YES' => 'Ja',

// Form Element Descriptions
'ENDING_DATE' => 'Enddatum',
'FROM' => 'Von',
'GUILD_TAG' => 'Gildenbezeichnung',
'LANGUAGE' => 'Sprache',
'STARTING_DATE' => 'Startdatum',
'TO' => 'Zu',
'USERNAME' => 'Benutzername',
'USERS' => 'Benutzer',

// Pagination
'NEXT_PAGE' => 'Nächste Seite',
'PAGE' => 'Seite',
'PREVIOUS_PAGE' => 'Vorherige Seite',

// Permission Messages
'NOAUTH_DEFAULT_TITLE' => 'Zugriff verweigert',
'NOAUTH_U_EVENT_LIST' => 'Du hast keine Berechtigung Ereignisse aufzulisten.',
'NOAUTH_U_EVENT_VIEW' => 'Du hast keine Berechtigung Ereignisse zu sehen.',
'NOAUTH_U_ITEM_LIST' => 'Du hast keine Berechtigung Items aufzulisten.',
'NOAUTH_U_ITEM_VIEW' => 'Du hast keine Berechtigung Items zu sehen.',
'NOAUTH_U_MEMBER_LIST' => 'Du hast keine Berechtigung Mitgliederstände zu sehen.',
'NOAUTH_U_MEMBER_VIEW' => 'Du hast keine Berechtigung Historien der Mitglieder zu sehen.',
'NOAUTH_U_RAID_LIST' => 'Du hast keine Berechtigung Raids aufzulisten.',
'NOAUTH_U_RAID_VIEW' => 'Du hast keine Berechtigung Raids zu sehen.',

// Miscellaneous
'ADDED' => 'Zugefügt',
'DELETED' => 'Gelöscht',
'ENTER_NEW' => 'Neu eingeben',
'ENTER_NEW_GAMEITEMID' => 'Item ID',
'FEMALE'	=> 'Weiblich',
'GENDER'	=> 'Geschlecht',
'GUILD'	=> 'Gilde', 
'LIST' => 'Liste',
'LIST_EVENTS' => 'Ereignisse zeigen',
'LIST_INDIVADJ' => 'Punkteanpassungen zeigen',
'LIST_ITEMS' => 'Items zeigen',
'LIST_MEMBERS' => 'Mitglieder zeigen',
'LIST_RAIDS' => 'Raids zeigen',
'MALE'	=> 'Männlich',
'MAY_BE_NEGATIVE_NOTE' => 'darf negativ sein',
'NOT_AVAILABLE' => 'Nicht vorhanden',
'OF_RAIDS' => '%d',
'OF_RAIDS_CHAR' => '%s', 
'OR' => 'Oder',
'POWERED_BY' => 'Powered by',
'PREVIEW' => 'Vorschau',
'REQUIRED_FIELD_NOTE' => 'Mit * gekennzeichnete Felder sind Pflichtfelder.',
'SELECT_EXISTING' => 'Wähle vorhandene',
'UPDATED' => 'Aktualisiert',

// Error messages
'NOT_ADMIN' => 'Du bist kein Administrator',

//---- About --- do not change anything here 
//tabs
'ABOUT' => 'Über',
'MAINIMG' => 'bbdkp.png', 
'IMAGE_ALT' => 'Logo', 
'REPOSITORY_IMAGE' => 'Google.jpg', 
'TCOPYRIGHT' => 'Copyright',  
'TCREDITS' => 'Credits', 
'TEAM' => 'Entwickler Team', 
'TSPONSORS' => 'Donateure', 
'TPLUGINS' => 'Plugins', 
'CREATED' => 'Erschaffer',
'DEVELOPEDBY' => 'weiter entwickelt durch',
'DEVTEAM'=> 'bbDKP Development Team',
'AUTHNAME' => 'Ippeh', 
'WEBNAME' =>'Website', 
'SVNNAME' => 'Repository',
'SVNURL' => 'http://code.google.com/p/bbdkp/',
'WEBURL' => 'http://www.bbdkp.com',
'AUTHWEB' => 'http://www.explodinglabrats.com/',
'DONATIONCOMMENT' => 'bbDKP wird als Freeware vertrieben. Falls Sie aber den Autor des Programms als Gegenleistung für seinen Aufwand an Zeit und Ressourcen für die Entwicklung und den Support sowie bei den Kosten des Webhostings unterstützen möchten, nimmt er gerne eine finanzielle Zuwendung an.',
'PAYPALLINK' => '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt=""><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>',
'LICENSE1' => 'bbDKP ist freie Software. Sie können es unter den Bedingungen der GNU General Public License, wie von der Free Software Foundation veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß Version 2 der Lizenz oder (nach Ihrer Option) jeder späteren Version. Die Veröffentlichung bbDKP erfolgt in der Hoffnung, daß es Ihnen von Nutzen sein wird, aber OHNE IRGENDEINE GARANTIE, sogar ohne die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FÜR EINEN BESTIMMTEN ZWECK. Details finden Sie in der GNU General Public License. Sie sollten ein Exemplar der GNU General Public License zusammen mit diesem Programm erhalten haben. Falls nicht, schreiben Sie an die Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA. ', 
'LICENSE2' => 'Powered by bbDkp (c) 2009 The bbDkp Project Team. If you use this software and find it to be useful, we ask that you retain the copyright notice below. While not required for free use, it will help build interest in the bbDkp project and is <b>required for obtaining support</b>.',
'COPYRIGHT3' => 'bbDKP (c) 2010 Sajaki, Malfate, Blazeflack <br />
bbDKP (c) 2008, 2009 Sajaki, Malfate, Kapli, Hroar',
'COPYRIGHT2' => 'bbDKP (c) 2007 Ippeh, Teksonic, Monkeytech, DWKN',
'COPYRIGHT1' => 'EQDkp (c) 2003 The EqDkp Project Team ',

'PRODNAME' => 'Produkt',
'VERSION' => 'Version',
'DEVELOPER' => 'Entwickler',
'JOB' => 'Job', 
'DEVLINK' => 'Link',
'PROD' => 'bbDKP',
'DEVELOPERS' => '<a href=mailto:sajaki9@gmail.com>Sajaki</a>',   
'PHPBB' => 'phpBB', 
'PHPBBGR' => 'phpBB Group', 
'PHPBBLINK' => 'http://www.phpbb.com', 
'EQDKP' => 'Original EQDKP',
'EQDKPVERS' => '1.3.2',
'EQDKPDEV' => 'Tsigo', 
'EQDKPLINK' => 'http://www.eqdkp.com/', 

'PLUGINS' => 	'Plugins',
'PLUGINVERS'=> 	'Version',
'AUTHOR'=> 		'Author',
'MAINT'=> 		'Maintainer', 

'DONATORS' => 'Unexpectedgreg, Bisa, Sniperpaladin, McTurk, Mizpah, Kapli, Hroar', 

'DONATION' => 'Donation',
'DONA_NAME' => 'Name',
'ADDITIONS' => 'Code Additions',
'CONTRIB' => 'Contributions',

));

?>
