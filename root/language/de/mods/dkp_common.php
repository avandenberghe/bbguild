<?php
/**
 * bbdkp common language file
 * 
 * @package bbDKP
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
  * @translation various unknown authors, killerpommes
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
	'BBDKPDISABLED'	=> 'bbDKP is momentan deaktiviert.',

//---- Portal blocks ----- 
	'PORTAL'	=> 'Portal',
	'RECENTLOOT'	=> 'Letzte Beute',
	'REMEMBERME'	=> 'Login merken',
	'INFORUM'	=> 'in',
	'DKP'	=> 'DKP',
	'NEWS'	=> 'Nachrichten',
	'COMMENT'	=> 'Kommentare',
	'LIST_NEWS'	=> 'Nachrichtenliste',
	'NO_NEWS'	=> 'Keine Nachrichten gefunden.',
	'NEWS_PER_PAGE'	=> 'Nachrichten pro Seite',
	'ERROR_INVALID_NEWS_PROVIDED'	=> 'Ungültige Nachricht.',
	'BOSSPROGRESS'	=> 'Instanz-Fortschritt',
	'WELCOME'	=> 'Willkommen', 
	'FOOTERBBDKP'	=> 'Powered by bbDKP', 
	'RECENT_LENGTH' 	=> 'Anzahl angezeigte Buchstaben',
	'NUMTOPICS'					=> 'Anzahl angezeigte Beiträge',
	'SHOW_RT_BLOCK'				=> 'neue Beiträge anzeigen',
	'RECENT_TOPICS_SETTING'		=> 'Einstellungen neue Beiträge',
	'RECENT_TOPICS'				=> 'Neue Beiträge',	
	'NO_RECENT_TOPICS'			=> 'Keine neue Beiträge',
	'POSTED_BY_ON'				=> '%2$s von %1$s',	
	
// Main Menu
	'MENU'	=> 'Menü',
	'MENU_ADMIN_PANEL'	=> 'Administration',
	'MENU_BOSS'	=> 'Instanz-Fortschritt',
	'MENU_EVENTS'	=> 'Ereignisse',
	'MENU_ITEMVAL'	=> 'Gegenstand-Werte',
	'MENU_ITEMHIST'	=> 'Gegenstand-Historie',
	'MENU_NEWS'	=> 'Nachrichten',
	'MENU_RAIDS'	=> 'Raids',
	'MENU_ROSTER'	=> 'Mitgliedsbuch',
	'MENU_STATS'	=> 'Statistiken',
	'MENU_SUMMARY'	=> 'Übersicht',
	'MENU_STANDINGS'	=> 'Punktestand',
	'MENU_VIEWMEMBER'	=> 'Mitglieder',
	'MENU_VIEWITEM'	=> 'Gegenstand',
	'MENU_VIEWRAID'	=> 'Raid ansehen',
	'MENU_VIEWEVENT'	=> 'Ereignis ansehen',
	'MENU_PLANNER'	=> 'Planer',


	//links
	'MENU_LINKS' => 'Weblinks',
	'LINK1' => 'http://www.bbdkp.com', 
	'LINK1T' => 'Powered By: bbDKP',
	'LINK2' => 'http://uk.aiononline.com', 
	'LINK2T' => 'Aion Online', 
	'LINK3' => 'http://darkageofcamelot.com', 
	'LINK3T' => 'Dark age of Camelot', 
	'LINK4' => 'http://everquest2.station.sony.com/', 
	'LINK4T' => 'Everquest 2', 
	'LINK5' => 'http://www.playonline.com/ff11us/index.shtml', 
	'LINK5T' => 'FFXI', 
	'LINK6' => 'http://www.guildwars2.com', 
	'LINK6T' => 'Guild Wars 2', 
	'LINK7' => 'http://www.lineage2.com', 
	'LINK7T' => 'Lineage 2', 
	'LINK8' => 'http://www.lotro.com', 
	'LINK8T' => 'Lord of the Rings', 
	'LINK9' => 'http://www.riftgame.com', 
	'LINK9T' => 'Rift', 
	'LINK10' => 'http://www.swtor.com', 
	'LINK10T' => 'Star Wars : ToR', 
	'LINK11' => 'http://tera.enmasse.com/', 
	'LINK11T' => 'Tera', 
	'LINK12' => 'http://www.vanguardmmorpg.com', 
	'LINK12T' => 'Vanguard', 
	'LINK13' => 'http://www.warhammeronline.com', 
	'LINK13T' => 'Warhammer', 
	'LINK14' => 'http://www.worldofwarcraft.com', 
	'LINK14T' => 'World of Warcraft', 


//games supported
	'WOW'			=> 'World of Warcraft',
	'LOTRO'			=> 'The Lord of the Rings Online',
	'EQ'			=> 'EverQuest',
	'DAOC'			=> 'Dark Age of Camelot',
	'VANGUARD'		=> 'Vanguard - Saga of Heroes',
	'EQ2'			=> 'EverQuest II',
	'WARHAMMER'		=> 'Warhammer Online',
	'AION'			=> 'Aion',
	'FFXI'			=> 'Final Fantasy XI',
	'RIFT'       	=> 'Rift',
	'SWTOR'       	=> 'Starwars : The old Republic', 
	'LINEAGE2'      => 'Lineage 2', 
	'GW2'       	=> 'Guildwars 2', 
	'TERA'       	=> 'Tera', 
	'NORAIDS'       => 'No Raids', 
//Recruitment
	'RECRUITMENT_BLOCK'	=> 'Rekrutierungs Status',
	'RECRUIT_CLOSED'	=> 'Geschlossen !',
	'TANK'	=> 'Schutz',
	'DPS'	=> 'Schaden',
	'HEAL'	=> 'Heilung',
	'RECRUIT_MESSAGE'	=> 'Wir sind zurzeit auf der Suche nach neuen Mitstreitern folgender Klassen und Spezialisierungen:',

//ROSTER

	'GUILDROSTER'	=> 'Mitgliedsbuch',
	'RANK'	=> 'Rang',
	'CLASS'	=> 'Klasse',
	'LVL'	=> 'Level',
	'ACHIEV'	=> 'Erfolge',

//listmembers
	'ADJUSTMENT'	=> 'Korrektur',
	'ALL'	=> 'Alle',
	'CURRENT'	=> 'Jetzt',
	'EARNED'	=> 'Bekommen',
	'FILTER'	=> 'Filter',
	'LASTRAID'	=> 'Letzter Raid',
	'LEVEL'	=> 'Level',
	'LISTMEMBERS_TITLE'	=> 'Mitglieder Statistik',
	'MNOTFOUND'	=> 'Mitglied nicht gefunden',
	'EMPTYRAIDNAME'	=> 'Raidname nicht gefunden',
	'NAME'	=> 'Name',
	'POOL'	=> 'DKP Pool',
	'RAID_ATTENDANCE_HISTORY'	=> 'Raidbeteiligungs Historie',
	'RAIDS_LIFETIME'	=> 'Lebensdauer (%s - %s)',
	'ATTENDANCE_LIFETIME'	=> 'Alle',
	'RAIDS_X_DAYS'	=> 'Letzte %d Tage',
	'SPENT'	=> 'Ausgegeben',
	'COMPARE_MEMBERS'	=> 'Vergleiche Mitglieder',
	'LISTMEMBERS_FOOTCOUNT'	=> '... %d Mitglieder gefunden',
	
	'LISTADJ_TITLE'	=> 'Korrekturen',
	'LISTEVENTS_TITLE'	=> 'Ereignisse',
	'LISTIADJ_TITLE'	=> 'Korrekturen',
	'LISTITEMS_TITLE'	=> 'Gegenstand Werte',
	'LISTPURCHASED_TITLE'	=> 'Gegenstand Historie',
	'LISTRAIDS_TITLE'	=> 'Raids',
	'LOGIN_TITLE'	=> 'Login',
	'STATS_TITLE'	=> '%s Statistiken',
	'TITLE_PREFIX'	=> '%s %s',
	'VIEWEVENT_TITLE'	=> 'Gepeicherte Raid Historie für %s',
	'VIEWITEM_TITLE'	=> 'Kauf Historie für %s',
	'VIEWMEMBER_TITLE'	=> 'Raid Historie für %s',
	'VIEWRAID_TITLE'	=> 'Raid Zusammenfassung',
	'NODKPACCOUNTS'	=> 'Keine DKP Konten gefunden', 
	'NOUCPACCESS' => 'Du kannst keine Charaktere einstellen.', 
	'NOUCPADDCHARS' => 'Du kannst keine Charaktere hinzufügen.', 
	'NOUCPUPDCHARS' => 'Du kannst deine Charaktere nicht bearbeiten.', 
	'NOUCPDELCHARS' => 'Du kannst deine Charaktere nicht löschen',

// Various
	'ACCOUNT'	=> 'Konto',
	'ACTION'	=> 'Aktion',
	'ADD'	=> 'Hinzufügen',
	'ADDED_BY'	=> 'Hinzugefügt von',

	'ADMINISTRATION'	=> 'Administration',
	'ADMINISTRATIVE_OPTIONS'	=> 'Administrative Einstellungen',
	'ADMIN_INDEX'	=> 'Admin Index',
	'ATTENDANCE_BY_EVENT'	=> 'Beteiligung bei Ereignis',
	'ATTENDED'	=> 'Teilgenommen',
	'ATTENDEES'	=> 'Teilnehmer',
	'ATTENDANCE_LIFETIME' => 'Allgemeine Teilnahme',
	'AVERAGE'	=> 'Durchschnitt',
	'BOSS'	=> 'Boss',
	'BUYER'	=> 'Käufer',
	'BUYERS'	=> 'Käufer',
	'ARMOR'	=> 'Rüstung',
	'TYPE'	=> 'Typ',

// TYPES of armor are static across games, no need to put it in DB
	'CLOTH'	=> 'Stoff',
	'ROBE' => 'Robe', 
	'LEATHER'	=> 'Leder',
	'AUGMENTED' => 'Erweiterter Anzug', 
	'MAIL' =>  'Mittel / Kettenhemd', 
	'HEAVY' => 'Schwere Rüstung', 
	'PLATE'	=> 'Schwer / Platte',

	'CLASSID'	=> 'Klassen ID',
	'CLASS_FACTOR'	=> 'Klassen Faktor',
	'CLASSARMOR'	=> 'Klasserüstung',
	'CLASSIMAGE'	=> 'Bild',
	'CLASSMIN'	=> 'Min Level',
	'CLASSMAX'	=> 'Max Level',
	'CLASS_DISTRIBUTION'	=> 'Klassenverteilung',
	'CLASS_SUMMARY'	=> 'Klassen Übersicht: %s zu %s',
	'CONFIGURATION'	=> 'Konfiguration',

	'DATE'	=> 'Datum',
	'DELETE'	=> 'Löschen',
	'DELETE_CONFIRMATION'	=> 'Bestätige Löschung',
	'DKP_VALUE'	=> '%s Wert',
	'STATUS'	=> 'Status Y/N',
	'CHARACTER'	=> 'Charaktername',
	'CHARACTER_EXPLAIN'	=> 'Wähle dein Charakternamen and bestätige.',
	'CHARACTERS_UPDATED'	=> 'Der Charaktername %s wurde an dein Forumkonto gekoppelt. ',
	'NO_CHARACTERS'	=> 'Keine Charaktere gefunden.',
	'RAIDDECAY'	=> 'Raid Entwertung',
	'ADJDECAY' => 'Ajustierungsentwertung', 
	'DECAY'	=> 'Entwert',
	'DROPS'	=> 'Beute',
	'EVENT'	=> 'Ereignis',
	'EVENTNAME'	=> 'Ereignisname',
	'EVENTS'	=> 'Ereignisse',
	'FACTION'	=> 'Fraktion',
	'FACTIONID'	=> 'Fraktions ID',
	'FIRST'	=> 'Erster',
	'GROUP_ADJ'	=> 'Gruppen Korrektur.',
	'GROUP_ADJUSTMENTS'	=> 'Gruppenkorrekturen',
	'INDIVIDUAL_ADJUSTMENTS'	=> 'Individuelle Korrekturen',
	'INDIVIDUAL_ADJUSTMENT_HISTORY'	=> 'Individuelle Korrekturen Verlauf',
	'INDIV_ADJ'	=> 'Korr.',
	'ITEM'	=> 'Gegenstand',
	'ITEMS'	=> 'Gegenstände',
	'ITEMVALUE'	=> 'Item Wert',
	'ITEMDECAY'	=> 'Item Entwert',
	'ITEMTOTAL'	=> 'Totalwert',
	'ITEM_PURCHASE_HISTORY'	=> 'Item Historie',
	'JOINDATE'	=> 'Gilden Eintrittsdatum',
	'LAST'	=> 'Letzter',
	'LASTLOOT'	=> 'Letztes Beute',
	'LAST_VISIT'	=> 'Letzter Besuch',
	'LATESTMEMBERS' => 'Neueste Benutzer', 
	'LOG_DATE_TIME'	=> 'Datum/Zeit dieses Logs',
	'LOOT_FACTOR'	=> 'Beute Faktor',
	'LOOTS'	=> 'Beute',
	'LOOTDIST_CLASS'	=> 'Beute-Klassenverteilung',
	'MANAGE'	=> 'Verwalten',
	'MEMBER'	=> 'Mitglied',
	'MEMBERS'	=> 'Mitglieder',
	'NETADJUSTMENT' => 'Netto Ajustierung', 	
	'NO_DATA' => 'Keine Daten',
	'RAID_ON'	=> 'Raid von %s in %s',
	'MAX_CHARS_EXCEEDED' => 'Du kannst höchstens %s Charaktere an dein Forumkonto koppeln.', 
	'MISCELLANEOUS'	=> 'Verschiedenes',
	'NEWEST'	=> 'Neuester Raid',
	'NOTE'	=> 'Notiz',
	'OLDEST'	=> 'Ältester Raid',
	'OPTIONS'	=> 'Einstellungen',
	'OUTDATE'	=> 'Gilden Austrittsdatum',
	'PERCENT'	=> 'Prozent',
	'PERMISSIONS'	=> 'Berechtigungen',
	'PER_DAY'	=> 'Pro Tag',
	'PER_RAID'	=> 'Pro Raid',
	'PCT_EARNED_LOST_TO'	=> '% Verdientes verringert durch',
	'PREFERENCES'	=> 'Voreinstellungen',
	'PURCHASE_HISTORY_FOR'	=> 'Kauf-Historie für %s',
	'QUOTE'	=> 'Zitat',
	'RACE'	=> 'Rasse',
	'RACEID'	=> 'Rassen ID',
	'RAIDSTART'	=> 'Raidanfang',
	'RAIDEND'	=> 'Raidende',
	'RAIDDURATION'	=> 'Dauer',
	'RAID'	=> 'Raid',
	'RAIDCOUNT'	=> 'Teilnehmerzahl',
	'RAIDS'	=> 'Raids',
	'RAID_ID'	=> 'Raid ID',
	'RAIDVALUE'	=> 'Raid Wert',
	'RAIDBONUS'	=> 'Raid Bonus',
	'RANK_DISTRIBUTION'	=> 'Rang-Verteilung',
	'RECORDED_RAID_HISTORY'	=> 'Gespeicherte Raid-Historie für %s',
	'RECORDED_DROP_HISTORY'	=> 'Gespeicherte Kauf-Historie für %s',
	'REASON'	=> 'Grund',
	'RESULT'	=> 'Ergebnis',
	'SESSION_ID'	=> 'Session ID',

	'SUMMARY_DATES'	=> 'Raid Zusammenfassung: %s bis %s',
	'TIME'	=> 'Zeit',
	'TIMEVALUE' => 'Zeitwert',
	'TIME_BONUS'	=> 'Zeitbonus',
	'TOTAL'	=> 'Gesamt',
	'TOTAL_EARNED'	=> 'Gesamt verdient',
	'TOTAL_ITEMS'	=> 'Gesamt Gegenstände',
	'TOTAL_RAIDS'	=> 'Gesamt Raids',
	'TOTAL_SPENT'	=> 'Gesamt ausgegeben',
	'TRANSFER_MEMBER_HISTORY'	=> 'Mitglieder Historie',
	'TYPE' => 'Typ',
	'UPDATE'	=> 'Aktualisieren',
	'UPDATED_BY'	=> 'Aktualisiert By',
	'USER'	=> 'Benutzer',
	'USERNAME'	=> 'Benutzername',
	'VALUE'	=> 'Wert',
	'VIEW'	=> 'Ansehen',
	'VIEW_ACTION'	=> 'Aktion ansehen',
	'VIEW_LOGS'	=> 'Logs ansehen',
	'ZSVALUE'	=> 'Nullsumme',
	'ZEROSUM'	=> 'Nullsummenbonus',

//lootsystems
	'EP'	=> 'EP',
	'EPNET'	=> 'EP net',
	'GP'	=> 'GP',
	'GPNET'	=> 'GP net',
	'PR'	=> 'Priorität',

// Page Foot Counts

	'LISTEVENTS_FOOTCOUNT'	=> '... %d Ereignisse gefunden / %d pro Seite',
	'LISTIADJ_FOOTCOUNT'	=> '... %d Korrektionen gefunden / %d pro Seite',
	'LISTITEMS_FOOTCOUNT'	=> '... %d einmalige Gegenstände / %d pro Seite',
	'LISTNEWS_FOOTCOUNT'	=> '... %d Nachrichten gefunden', 
	'LISTMEMBERS_ACTIVE_FOOTCOUNT'	=> '... %d aktive(s) Mitglied(er) gefunden / %s Zeige alle</a>',
	'LISTMEMBERS_COMPARE_FOOTCOUNT'	=> '... vergleiche %d Mitglieder',
	'LISTPURCHASED_FOOTCOUNT'	=> '... %d Gegenstände gefunden / %d pro Seite',
	'LISTPURCHASED_FOOTCOUNT_SHORT'	=> '... %d Gegenstände gefunden',
	'LISTRAIDS_FOOTCOUNT'	=> '... %d raid(s) gefunden / %d pro Seite',
	'STATS_ACTIVE_FOOTCOUNT'	=> '... %d Active(s) Mitglied(er) gefunden / %s zeige alle</a>',
	'STATS_FOOTCOUNT'	=> '... %d Mitglieder gefunden',
	'VIEWEVENT_FOOTCOUNT'	=> '... %d Raid(s) gefunden',
	'VIEWITEM_FOOTCOUNT'	=> '... %d Gegenstände gefunden',
	'VIEWMEMBER_ADJUSTMENT_FOOTCOUNT'	=> '... %d Punktekorrektur(en) gefunden',
	'VIEWMEMBER_ITEM_FOOTCOUNT'	=> '... %d gekaufte Gegenstände gefunden / %d pro Seite',
	'VIEWMEMBER_RAID_FOOTCOUNT'	=> '... %d teilgenommene(n) Raid(s) gefunden / %d pro Seite',
	'VIEWMEMBER_EVENT_FOOTCOUNT'	=> '... %d teilgenommen(es) Ereignis(se)',
	'VIEWRAID_ATTENDEES_FOOTCOUNT'	=> '... %d Teilnehmer gefunden',
	'VIEWRAID_DROPS_FOOTCOUNT'	=> '... %d Drop(s) gefunden',

// Submit Buttons
	'LOG_ADD_DATA'	=> 'Daten zufügen',
	'PROCEED'	=> 'Fortfahren',
	'UPGRADE'	=> 'Upgrade',

// Form Element Descriptions
	'ENDING_DATE'	=> 'Enddatum',
	'GUILD_TAG'	=> 'Gildenname',
	'LANGUAGE'	=> 'Sprache',
	'STARTING_DATE'	=> 'Startdatum',
	'TO'	=> 'Bis',

// Pagination
	'NEXT_PAGE'	=> 'Nächste Seite',
	'PAGE'	=> 'Seite',
	'PREVIOUS_PAGE'	=> 'Vorherige Seite',

// Permission Messages
	'NOAUTH_DEFAULT_TITLE'	=> 'Zugriff verweigert',
	'NOAUTH_U_EVENT_LIST'	=> 'Du hast keine Berechtigung Ereignisse aufzulisten.',
	'NOAUTH_U_EVENT_VIEW'	=> 'Du hast keine Berechtigung Ereignisse zu sehen.',
	'NOAUTH_U_ITEM_LIST'	=> 'Du hast keine Berechtigung Gegenstände aufzulisten.',
	'NOAUTH_U_ITEM_VIEW'	=> 'Du hast keine Berechtigung Gegenstände zu sehen.',
	'NOAUTH_U_MEMBER_LIST'	=> 'Du hast keine Berechtigung Mitgliederstände zu sehen.',
	'NOAUTH_U_MEMBER_VIEW'	=> 'Du hast keine Berechtigung Historien der Mitglieder zu sehen.',
	'NOAUTH_U_RAID_LIST'	=> 'Du hast keine Berechtigung Raids aufzulisten.',
	'NOAUTH_U_RAID_VIEW'	=> 'Du hast keine Berechtigung Raids zu sehen.',

// Miscellaneous
	'ADDED'	=> 'Zugefügt',
	'BOSSKILLCOUNT'	=> 'Anzahl Bosskills',
	'CLOSED'	=> 'Geschlossen',
	'DELETED'	=> 'Gelöscht',
	'ENTER_NEW'	=> 'Neu eingeben',
	'ENTER_NEW_GAMEITEMID'	=> 'Gegenstand ID',
	'FEMALE'	=> 'weiblich',
	'GENDER'	=> 'Geschlecht',
	'GUILD'	=> 'Gilde',
	'HIGH'	=> 'Hoch',		
	'LIST'	=> 'Liste',
	'LOW' => 'Niedrig',  
	'LIST_EVENTS'	=> 'Ereignisse zeigen',
	'LIST_INDIVADJ'	=> 'Individuelle Korrekturen anzeigen',
	'LIST_ITEMS'	=> 'Gegenstände zeigen',
	'LIST_MEMBERS'	=> 'Mitglieder zeigen',
	'LIST_RAIDS'	=> 'Raids zeigen',
	'ATTENDANCE'	=> 'Teilnahme', 
	'MALE'	=> 'männlich',
	'MEDIUM'	=> 'Mittel',	
	'MAY_BE_NEGATIVE_NOTE'	=> 'darf negativ sein',
	'NA'	=> 'N/V',		
	'NOT_AVAILABLE'	=> 'Nicht vorhanden',
	'OF_RAIDS'	=> '%d',
	'OF_RAIDS_CHAR'	=> '%s',
	'OPEN'	=> 'Offen',
	'OR'	=> 'oder',
	'POWERED_BY'	=> 'Unterstützt durch',
	'REQUIRED_FIELD_NOTE'	=> 'Mit * gekennzeichnete Felder sind Pflichtfelder.',
	'SELECT_EXISTING'	=> 'Wähle vorhandene',
	'UPDATED'	=> 'Aktualisiert',

// Error messages
	'NOT_ADMIN'	=> 'Du bist kein Administrator',

//---- About --- do not change anything here 
//tabs
	'ABOUT'	=> 'Über',
	'MAINIMG'	=> 'bbdkp.png',
	'IMAGE_ALT'	=> 'Logo',
	'REPOSITORY_IMAGE'	=> 'Google.jpg',
	'TCOPYRIGHT'	=> 'Copyright',
	'TCREDITS'	=> 'Credits',
	'TEAM'	=> 'Entwickler Team',
	'TSPONSORS'	=> 'Spender',
	'TPLUGINS'	=> 'Plugins',
	'CREATED'	=> 'Urheber',
	'DEVELOPEDBY'	=> 'weiter entwickelt durch',
	'DEVTEAM'	=> 'bbDKP Entwicklungs Team',
	'AUTHNAME'	=> 'Ippeh',
	'WEBNAME'	=> 'Website',
	'SVNNAME'	=> 'Repository',
	'SVNURL'	=> 'http://github.com/bbdkp/',
	'PLUGINURL' => 'http://www.bbdkp.com/downloads.php?cat=2" target="_blank',	
	'WEBURL'	=> 'http://www.bbdkp.com',
	'AUTHWEB'	=> 'http://www.explodinglabrats.com/',
	'DONATIONCOMMENT'	=> 'bbDKP wird als Freeware vertrieben. Falls Sie aber den Autor des Programms als Gegenleistung für seinen Aufwand an Zeit und Ressourcen für die Entwicklung und den Support sowie bei den Kosten des Webhostings unterstützen möchten, nimmt er gerne eine finanzielle Zuwendung an.',
	'PAYPALLINK'	=> '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt=""><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>',
	'LICENSE1'	=> 'bbDKP ist freie Software. Du kannst es unter den Bedingungen 
		der GNU General Public License, wie von der Free Software Foundation 
		veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß 
		Version 2 der Lizenz oder (nach Ihrer Wahl) jeder späteren Version. 
		
		Die Veröffentlichung bbDKP erfolgt in der Hoffnung, 
		daß es dir von Nutzen sein wird, aber OHNE IRGENDEINE 
		GARANTIE, sogar ohne die implizite Garantie der MARKTREIFE 
		oder der VERWENDBARKEIT FÜR EINEN BESTIMMTEN ZWECK. 
		Details finden Sie in der GNU General Public License. 
		
		Du solltest ein Exemplar der GNU General Public License 
		zusammen mit diesem Programm erhalten haben. 
		Falls nicht schau unter http://www.gnu.org/licenses',
	'LICENSE2'	=> 'Powered by bbDkp (c) 2009 The bbDkp Project Team. Wenn Sie diese Software benutzen und hilfreich finden, möchten wir Sie bitten den Copyright-Hinweis zu erhalten. Auch wenn er für die freie Verwendung nicht notwendig ist, so hilft er Interesse am Projekt bbDKP zu wecken und <strong>ist Voraussetzung um Support zu erhalten</strong>.',
	'COPYRIGHT3'	=> 'bbDKP (c) 2010 Sajaki, Malfate, Blazeflack <br /> bbDKP (c) 2008, 2009 Sajaki, Malfate, Kapli, Hroar',
	'COPYRIGHT2'	=> 'bbDKP (c) 2007 Ippeh, Teksonic, Monkeytech, DWKN',
	'COPYRIGHT1'	=> 'EQDkp (c) 2003 The EqDkp Project Team ',
	'PRODNAME'	=> 'Produkt',
	'VERSION'	=> 'Version',
	'DEVELOPER'	=> 'Entwickler',
	'JOB'	=> 'Job',
	'DEVLINK'	=> 'Link',
	'PROD'	=> 'bbDKP',
	'DEVELOPERS'	=> '<a href=mailto:sajaki9@gmail.com>Sajaki</a>',
	'PHPBB'	=> 'phpBB',
	'PHPBBGR'	=> 'phpBB Group',
	'PHPBBLINK'	=> 'http://www.phpbb.com',
	'EQDKP'	=> 'Original EQDKP',
	'EQDKPVERS'	=> '1.3.2',
	'EQDKPDEV'	=> 'Tsigo',
	'EQDKPLINK'	=> 'http://www.eqdkp.com/',
	'PLUGINS'	=> 'Plugins',
	'PLUGINVERS'	=> 'Version',
	'AUTHOR'	=> 'Author',
	'MAINT'	=> 'Maintainer',

'DONATORS' => 'Unexpectedgreg, Bisa, Sniperpaladin, McTurk, Mizpah, Kapli, Hroar, Chrome', 

	'DONATION'	=> 'Spenden',
	'DONA_NAME'	=> 'Name',
	'ADDITIONS'	=> 'Code Hilfen',
	'CONTRIB'	=> 'Mitwirkungen',
));

?>