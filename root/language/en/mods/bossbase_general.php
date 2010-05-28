<?php
/**
 * bossprogress language file lotro
 * 
 * @author sz3
 * @author sajaki

 * @package bbDkp
 * @copyright 2006-2008 sz3
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

//Labels
'bossbase' => 'BossBase',
'bossbase_explain' => 'BossBase is used to derive Bossprogress data. In this screen you can setup the data source (from raid table or offsets, or both).  
If you select \'Database\', then you can decide if your Boss info will come from the Raidname or the Note. The Raidnote delimiter can be specified. The Parse strings are what BossBase will look for in your Datasource.',
'bossbase_offsets' => 'BossBase Manual scoring table',
'bossbase_offsets_explain' => 'If you have previously raided but didnt keep score with a Database, then you can keep scores in this screen. Offsets can be combined with Database entries. Enter the first and the last date, and the number of times you scored.', 
'bossprogress' => 'Bossprogress',
'bossprogress_explain' => 'Configuration Boss Progress Userpage',

//acp menu
'bb_am_conf' => 'Settings',
'bb_am_offs' => 'Offsets',
'bb_am_bp_conf' => 'BossProgress',
'bb_am_bc_conf' => 'BossCounter',

// bossbase admin page
'bb_al_submit' => 'Save',
'bb_al_parse' => 'String(s) for: ',
'bb_al_general' => 'General settings',
'bb_al_delimRNO' => 'Raidnote delimiter (Opt.: Regular Expression):',
'bb_al_delimRNA' => 'Raidname delimiter (Opt.: Regular Expression):',
'bb_al_parseInfo' => 'Attention! Please keep in mind that the following strings are case-sensitive! Akil\'Zon won\'t match akil\'zon!',
'bb_al_zoneInfo' => 'Where (in the raid entry) to look for zone infos?',
'bb_al_bossInfo' => 'Where (in the raid entry) to look for boss infos?',
'bb_ao_rnote' => 'raidnote',
'bb_ao_rname' => 'raidname',
'bb_al_source' => 'Data source:',
'bb_source_db' => 'database',
'bb_source_offs' => 'offsets',
'bb_source_both' => 'both',

// offset page
'bb_ol_dateFormat' => 'The date format is: DD/MM/YYYY',
'bb_ol_in' => 'Name',
'bb_ol_fd' => 'First Date',
'bb_ol_ld' => 'Last Date',
'bb_ol_co' => 'Counter',
'bb_ol_submit' => 'Save',

//User Page config
'showzones' => 'Show Zones',
'FIRSTKILL' => 'First kill: ',
'LASTKILL' => 'Last kill: ',
'FIRSTVISIT' => 'First visit: ',
'LASTVISIT' => 'Last visit: ',
'ZONEVISITCOUNT' => 'Visit count: ',
'BOSSKILLCOUNT' => 'Kill count: ',
'status' => 'Status: ',
'never' => 'Never',
'opt_general' => 'General settings',
'opt_dynloc' => 'Hide zones with no boss kills?',
'opt_dynboss' => 'Hide never killed bosses?',
'opt_showzone' => 'Show: ',
'opt_showSB' => 'Show a zone progression bar?',
'opt_zhiType' => 'How to display the progress in the header image?',
'zhi_jitter' => 'old photo',
'zhi_bw' => 'black/white',
'zhi_none' => 'not at all',
'opt_style' => 'Style: ',
'bp_style_bp' => 'BossProgress default',
'bp_style_bps' => 'BossProgress simple',
'bp_style_rp2r' => 'Raidprogress 2/row',
'bp_style_rp3r' => 'Raidprogress 3/row',

//Settings
'LANG' => 'english',
'BASEURL' => 'http://www.wowhead.com/?npc=',
'AION_BASEURL' => 'http://db.aion.ign.com/npc/', 
'EQ_BASEURL' => 'http://eqbeastiary.allakhazam.com/search.shtml?zone=', 
'EQ2_BASEURL' => 'http://eq2.wikia.com/wiki/index.php/',
'DAOC_BASEURL' => 'http://camelot.allakhazam.com/db/search.html?cmob=',
'LOTRO_BASEURL' => 'http://lotro.allakhazam.com/db/bestiary.html?lotrmob=',
'VANGUARD_BASEURL' => 'http://vg.mmodb.com/bestiary/', 
'FFXI_BASEURL' => 'http://ffxi.allakhazam.com/db/bestiary.html?fmob=', 
'WARHAMMER_BASEURL' => 'http://www.wardb.com/npc.aspx?id=',
'WOW_BASEURL' => 'http://www.wowhead.com/?npc=',
'dateFormat' => '%m/%d/%Y',



));

?>
