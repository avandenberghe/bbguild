<?php
/**
 * bossprogress language file ported from BossSuite
 * updated for Wow, lotro, eq2
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

/*
Start WOW Bosses
*/

/****** WoW Miscellaneous bosses*****/
'misc' => array('long' => 'Miscellaneous bosses', 'short' => 'Misc'),
'azuregos' => array('id' =>'6109', 'long' => 'Azuregos', 'short' => 'Azuregos'),
'kazzak' => array('id' =>'12397', 'long' => 'Lord Kazzak', 'short' => 'Kazzak'),

/******Blackwing Lair*****/
'bwl' => array('long' => 'Blackwing Lair', 'short' => 'BWL'),
'razorgore' => array('id' =>'12435', 'long' => 'Razorgore the Untamed', 'short' => 'Razorgore'),
'vaelastrasz' => array('id' =>'13020', 'long' => 'Vaelastrasz the Corrupt', 'short' => 'Vaelastrasz'),
'lashlayer' => array('id' =>'12017', 'long' => 'Broodlord Lashlayer', 'short' => 'Lashlayer'),
'firemaw' => array('id' =>'11981', 'long' => 'Firemaw', 'short' => 'Firemaw'),
'ebonroc' => array('id' =>'14601', 'long' => 'Ebonroc', 'short' => 'Ebonroc'),
'flamegor' => array('id' =>'11983', 'long' => 'Flamegor', 'short' => 'Flamegor'),
'chromaggus' => array('id' =>'14020', 'long' => 'Chromaggus', 'short' => 'Chromaggus'),
'nefarian' => array('id' =>'11583', 'long' => 'Nefarian', 'short' => 'Nefarian'),

/******Onyxia*****/
'onylair' => array('long' => 'Onyxia\'s Lair', 'short' => 'Onyxia'),
'onyxia' => array('id' =>'10184', 'long' => 'Onyxia', 'short' => 'Onyxia'),

/******The Emerald Dream*****/
'dream' => array('long' => 'The Emerald Dream', 'short' => 'Dream'),
'ysondre' => array('id' =>'14887', 'long' => 'Ysondre', 'short' => 'Ysondre'),
'taerar' => array('id' =>'14890', 'long' => 'Taerar', 'short' => 'Taerar'),
'emeriss' => array('id' =>'14889', 'long' => 'Emeriss', 'short' => 'Emeriss'),
'lethon' => array('id' =>'14888', 'long' => 'Lethon', 'short' => 'Lethon'),

/******Molten Core*****/
'mc' => array('long' => 'Molten Core', 'short' => 'MC'),
'lucifron' => array('id' =>'12118', 'long' => 'Lucifron', 'short' => 'Lucifron'),
'magmadar' => array('id' =>'11982', 'long' => 'Magmadar', 'short' => 'Magmadar'),
'gehennas' => array('id' =>'12259', 'long' => 'Gehennas', 'short' => 'Gehennas'),
'garr' => array('id' =>'12057', 'long' => 'Garr', 'short' => 'Garr'),
'geddon' => array('id' =>'12056', 'long' => 'Baron Geddon', 'short' => 'Geddon'),
'shazzrah' => array('id' =>'12264', 'long' => 'Shazzrah', 'short' => 'Shazzrah'),
'sulfuron' => array('id' =>'12098', 'long' => 'Sulfuron Harbringer', 'short' => 'Sulfuron'),
'golemagg' => array('id' =>'11988', 'long' => 'Golemagg the Incinerator', 'short' => 'Golemagg'),
'majordomo' => array('id' =>'12018', 'long' => 'Majordomo Executus', 'short' => 'Majordomo'),
'ragnaros' => array('id' =>'11502', 'long' => 'Ragnaros', 'short' => 'Ragnaros'),

/******Zul'Gurub*****/
'zg' => array('long' => 'Zul\'Gurub', 'short' => 'ZG'),
'mandokir' => array('id' =>'11382', 'long' => 'Bloodlord Mandokir', 'short' => 'Mandokir'),
'jindo' => array('id' =>'11380', 'long' => 'Jin\'do the Hexxer', 'short' => 'Jin\'do'),
'gahzranka' => array('id' =>'15114', 'long' => 'Gahz\'ranka', 'short' => 'Gahz\'ranka'),
'grilek' => array('id' =>'15082', 'long' => 'Gri\'lek', 'short' => 'Gri\'lek'),
'hazzarah' => array('id' =>'15083', 'long' => 'Hazza\'rah', 'short' => 'Hazza\'rah'),
'renataki' => array('id' =>'15084', 'long' => 'Renataki', 'short' => 'Renataki'),
'wushoolay' => array('id' =>'15085', 'long' => 'Wushoolay', 'short' => 'Wushoolay'),
'thekal' => array('id' =>'14509', 'long' => 'High Priest Thekal', 'short' => 'Thekal'),
'arlokk' => array('id' =>'14515', 'long' => 'High Priestess Arlokk', 'short' => 'Arlokk'),
'jeklik' => array('id' =>'14517', 'long' => 'High Priestess Jeklik', 'short' => 'Jeklik'),
'marli' => array('id' =>'14510', 'long' => 'High Priestess Mar\'li', 'short' => 'Mar\'li'),
'venoxis' => array('id' =>'14507', 'long' => 'High Priest Venoxis', 'short' => 'Venoxis'),
'hakkar' => array('id' =>'14834', 'long' => 'Hakkar', 'short' => 'Hakkar'),

/******Ruins of Ahn'Qiraj*****/
'aq20' => array('long' => 'Ruins of Ahn\'Qiraj', 'short' => 'AQ20'),
'kurinnaxx' => array('id' =>'15348', 'long' => 'Kurinnaxx', 'short' => 'Kurinnaxx'),
'rajaxx' => array('id' =>'15341', 'long' => 'General Rajaxx', 'short' => 'Rajaxx'),
'ayamiss' => array('id' =>'15369', 'long' => 'Ayamiss the Hunter', 'short' => 'Ayamiss'),
'buru' => array('id' =>'15370', 'long' => 'Buru the Gorger', 'short' => 'Buru'),
'moam' => array('id' =>'15340', 'long' => 'Moam', 'short' => 'Moam'),
'ossirian' => array('id' =>'15339', 'long' => 'Ossirian the Unscarred', 'short' => 'Ossirian'),

/******Gates of Ahn'Qiraj*****/
'aq40' => array('long' => 'Gates of Ahn\'Qiraj', 'short' => 'AQ40'),
'skeram' => array('id' =>'15263', 'long' => 'The Prophet Skeram', 'short' => 'Skeram'),
'kri' => array('id' =>'15511', 'long' => 'Lord Kri', 'short' => 'Lord Kri'),
'yauj' => array('id' =>'15543', 'long' => 'Princess Yauj', 'short' => 'Princess Yauj'),
'vem' => array('id' =>'15544', 'long' => 'Vem', 'short' => 'Vem'),
'sartura' => array('id' =>'15516', 'long' => 'Battleguard Sartura', 'short' => 'Sartura'),
'fankriss' => array('id' =>'15510', 'long' => 'Fankriss the Unyielding', 'short' => 'Fankriss'),
'huhuran' => array('id' =>'15509', 'long' => 'Princess Huhuran', 'short' => 'Huhuran'),
'viscidus' => array('id' =>'15299', 'long' => 'Viscidus', 'short' => 'Viscidus'),
'veknilash' => array('id' =>'15275', 'long' => 'Emperor Vek\'nilash', 'short' => 'Vek\'nilash'),
'veklor' => array('id' =>'15276', 'long' => 'Emperor Vek\'lor', 'short' => 'Vek\'lor'),
'ouro' => array('id' =>'15517', 'long' => 'Ouro', 'short' => 'Ouro'),
'cthun' => array('id' =>'15727', 'long' => 'C\'Thun', 'short' => 'C\'Thun'),

/******Naxxramas*****/
'naxx' => array('long' => 'Naxxramas', 'short' => 'Naxx'),
'anubrekhan' => array('id' =>'15956', 'long' => 'Anub\'Rekhan', 'short' => 'Anub\'Rekhan'),
'faerlina' => array('id' =>'15953', 'long' => 'Grand Widow Faerlina', 'short' => 'Faerlina'),
'maexxna' => array('id' =>'15952', 'long' => 'Maexxna', 'short' => 'Maexxna'),
'noth' => array('id' =>'15954', 'long' => 'Noth the Plaguebringer', 'short' => 'Noth'),
'heigan' => array('id' =>'15936', 'long' => 'Heigan the Unclean', 'short' => 'Heigan'),
'loatheb' => array('id' =>'16011', 'long' => 'Loatheb', 'short' => 'Loatheb'),
'patchwerk' => array('id' =>'16028', 'long' => 'Patchwerk' , 'short' => 'Patchwerk'),
'grobbulus' => array('id' =>'15931', 'long' => 'Grobbulus', 'short' => 'Grobbulus'),
'gluth' => array('id' =>'15932', 'long' => 'Gluth', 'short' => 'Gluth'),
'thaddius' => array('id' =>'15928', 'long' => 'Thaddius', 'short' => 'Thaddius'),
'razuvious' => array('id' =>'16061', 'long' => 'Instructor Razuvious', 'short' => 'Razuvious'),
'gothik' => array('id' =>'16060', 'long' => 'Gothik the Harvester', 'short' => 'Gothik'),
'korthazz' => array('id' =>'16064', 'long' => 'Thane Korth\'azz', 'short' => 'Korth\'azz'),
'blaumeux' => array('id' =>'16065', 'long' => 'Lady Blaumeux', 'short' => 'Blaumeux'),
'mograine' => array('id' =>'16062', 'long' => 'Highlord Mograine', 'short' => 'Mograine'),
'zeliek' => array('id' =>'16063', 'long' => 'Sir Zeliek', 'short' => 'Sir Zeliek'),
'sapphiron' => array('id' =>'15989', 'long' => 'Sapphiron', 'short' => 'Sapphiron'),
'kelthuzad' => array('id' =>'15990', 'long' => 'Kel\'Thuzad', 'short' => 'Kel\'Thuzad'),

/******Outland Outdoor Bosses*****/
'outdoor2' => array('long' => 'Outland Outdoor Bosses', 'short' => 'Outdoor'),
'doomkazzak' => array('id' =>'18728', 'long' => 'Doom Lord Kazzak', 'short' => 'Doom Kazzak'),
'doomwalker' => array('id' =>'17711', 'long' => 'Doomwalker', 'short' => 'Doomwalker'),

/******Magtheridon's Lais******/
'maglair' => array('long' => 'Magtheridon\'s Lair', 'short' => 'Magtheridon'),
'magtheridon' => array('id' =>'21174', 'long' => 'Magtheridon', 'short' => 'Magtheridon'),

/******Karazhan*****/
'kara' => array('long' => 'Karazhan', 'short' => 'Kara'),
'attumen' => array('id' =>'16152', 'long' => 'Attumen the Huntsman', 'short' => 'Attumen'),
'moroes' => array('id' =>'15687', 'long' => 'Moroes', 'short' => 'Moroes'),
'maiden' => array('id' =>'16457', 'long' => 'Maiden of Virtue', 'short' => 'Maiden'),
'curator' => array('id' =>'15691', 'long' => 'The Curator', 'short' => 'Curator'),
'illhoof' => array('id' =>'15688', 'long' => 'Terestian Illhoof', 'short' => 'Terestian Illhoof'),
'aran' => array('id' =>'16524', 'long' => 'Shade of Aran', 'short' => 'Aran'),
'netherspite' => array('id' =>'15689', 'long' => 'Netherspite', 'short' => 'Netherspite'),
'malchezaar' => array('id' =>'15690', 'long' => 'Prince Malchezaar', 'short' => 'Malchezaar'),
'nightbane' => array('id' =>'17225', 'long' => 'Nightbane', 'short' => 'Nightbane'),
'chess' => array('id' =>'99999', 'long' => 'Chess Event', 'short' => 'Chess'),
'opera' => array('id' =>'99999', 'long' => 'Opera Event', 'short' => 'Opera'),

/******Zul'Aman*****/
'za' => array('long' => 'Zul\'Aman', 'short' => 'ZA'),
'nalorakk' => array('id' =>'23576', 'long' => 'Nalorakk, Bear Avatar' , 'short' => 'Nalorakk'),
'akilzon' => array('id' =>'23574', 'long' => 'Akil\'Zon, Eagle Avatar', 'short' => 'Akil\'Zon'),
'janalai' => array('id' =>'23578', 'long' => 'Jan\'Alai, Dragonhawk Avatar', 'short' => 'Jan\'Alai'),
'halazzi' => array('id' =>'23577', 'long' => 'Halazzi, Lynx Avatar', 'short' => 'Halazzi'),
'malacrass' => array('id' =>'24364', 'long' => 'Hex Lord Malacrass', 'short' => 'Malacrass'),
'zuljin' => array('id' =>'99999', 'long' => 'Zul\'Jin', 'short' => 'Zul\'Jin'),

/******Gruul's Lair*****/
'gruuls' => array('long' => 'Gruul\'s Lair', 'short' => 'GL'),
'maulgar' => array('id' =>'18831', 'long' => 'High King Maulgar', 'short' => 'Maulgar'),
'gruul' => array('id' =>'19044', 'long' => 'Gruul the Dragonkiller', 'short' => 'Gruul'),

/******Serpentshrine Cavern*****/
'serpent' => array('long' => 'Serpentshrine Cavern', 'short' => 'SSC'),
'hydross' => array('id' =>'21932', 'long' => 'Hydross the Unstable', 'short' => 'Hydross'),
'leotheras' => array('id' =>'21215', 'long' => 'Leotheras the Blind', 'short' => 'Leotheras'),
'karathress' => array('id' =>'21214', 'long' => 'Fathom-Lord Karathress', 'short' => 'Karathress'),
'morogrim' => array('id' =>'21213', 'long' => 'Morogrim Tidewalker', 'short' => 'Morogrim'),
'lurker' => array('id' => '21217', 'long' => 'The Lurker Below', 'short' => 'Lurker'),
'vashj' => array('id' =>'21212', 'long' => 'Lady Vashj', 'short' => 'Vashj'),

/******The Eye*****/
'eye' => array('long' => 'The Eye', 'short' => 'TK'),
'alar' => array('id' =>'19514', 'long' => 'Al\'Ar the Phoenix God', 'short' => 'Al\'Ar'),
'vreaver' => array('id' =>'19516', 'long' => 'Void Reaver', 'short' => 'Reaver'),
'solarian' => array('id' =>'18805', 'long' => 'High Astromancer Solarian', 'short' => 'Solarian'),
'kaelthas' => array('id' =>'19622', 'long' => 'Kael\'thas Sunstrider', 'short' => 'Kaelthas'),

/******Battle of Mount Hyjal*****/
'hyjal' => array('long' => 'Battle of Mount Hyjal', 'short' => 'MH'),
'winterchill' => array('id' =>'17767', 'long' => 'Rage Winterchill', 'short' => 'Winterchill'),
'anetheron' => array('id' =>'17808', 'long' => 'Anetheron', 'short' => 'Anetheron'),
'kazrogal' => array('id' =>'17888', 'long' => 'Kaz\'rogal', 'short' => 'Kaz\'rogal'),
'azgalor' => array('id' =>'17842', 'long' => 'Azgalor', 'short' => 'Azgalor'),
'archimonde' => array('id' =>'17968', 'long' => 'Archimonde', 'short' => 'Archimonde'),

/******The Black Temple*****/
'temple' => array('long' => 'The Black Temple', 'short' => 'BT'),
'najentus' => array('id' =>'22887', 'long' => 'High Warlord Naj\'entus', 'short' => 'Naj\'entus'),
'supremus' => array('id' =>'22898', 'long' => 'Supremus', 'short' => 'Supremus'),
'akama' => array('id' =>'22841', 'long' => 'Akama', 'short' => 'Akama'),
'gorefiend' => array('id' =>'22871', 'long' => 'Teron Gorefiend', 'short' => 'Gorefiend'),
'essence' => array('id' =>'23418', 'long' => 'Essence of Souls', 'short' => 'Essence'),
'bloodboil' => array('id' =>'22948', 'long' => 'Gurtogg Bloodboil', 'short' => 'Bloodboil'),
'shahraz' => array('id' =>'22947', 'long' => 'Mother Shahraz', 'short' => 'Shahraz'),
'council' => array('id' =>'23426', 'long' => 'Illidari Council', 'short' => 'Council'),
'illidan' => array('id' =>'22917', 'long' => 'Illidan Stormrage', 'short' => 'Illidan'),

/******The Sunwell Plateau*****/
'sunwell' => array('long' => 'The Sunwell Plateau', 'short' => 'Sunwell'),
'kalecgos' => array('id' =>'24850', 'long' => 'Kalecgos', 'short' => 'Kalecgos'),
'brutallus' => array('id' =>'24882', 'long' => 'Brutallus', 'short' => 'Brutallus'),
'felmyst' => array('id' =>'25038', 'long' => 'Felmyst', 'short' => 'Felmyst'),
'fetwins' => array('id' =>'25166', 'long' => 'Alythess & Sacrolash', 'short' => 'Twins'),
'muru' => array('id' =>'25741', 'long' => 'M\'uru', 'short' => 'M\'uru'),
'kiljaeden' => array('id' =>'25315', 'long' => 'Kil\'jaeden', 'short' => 'Kil\'jaeden'),

/******Naxxramas Wotlk (10) *****/
'naxx_10' => array('long' => 'Naxxramas (10)', 'short' => 'Naxx (10)'),
'anubrekhan_10' => array('id' =>'15956', 'long' => 'Anub\'Rekhan (10)', 'short' => 'Anub\'Rekhan (10)'),
'faerlina_10' => array('id' =>'15953', 'long' => 'Grand Widow Faerlina (10)', 'short' => 'Faerlina (10)'),
'maexxna_10' => array('id' =>'15952', 'long' => 'Maexxna (10)', 'short' => 'Maexxna (10)'),
'noth_10' => array('id' =>'15954', 'long' => 'Noth the Plaguebringer (10)', 'short' => 'Noth (10)'),
'heigan_10' => array('id' =>'15936', 'long' => 'Heigan the Unclean (10)', 'short' => 'Heigan (10)'),
'loatheb_10' => array('id' =>'16011', 'long' => 'Loatheb (10)', 'short' => 'Loatheb (10)'),
'patchwerk_10' => array('id' =>'16028', 'long' => 'Patchwerk (10)' , 'short' => 'Patchwerk (10)'),
'grobbulus_10' => array('id' =>'15931', 'long' => 'Grobbulus (10)', 'short' => 'Grobbulus (10)'),
'gluth_10' => array('id' =>'15932', 'long' => 'Gluth (10)', 'short' => 'Gluth (10)'),
'thaddius_10' => array('id' =>'15928', 'long' => 'Thaddius (10)', 'short' => 'Thaddius (10)'),
'razuvious_10' => array('id' =>'16061', 'long' => 'Instructor Razuvious (10)', 'short' => 'Razuvious (10)'),
'gothik_10' => array('id' =>'16060', 'long' => 'Gothik the Harvester (10)', 'short' => 'Gothik (10)'),
'horsemen_10' => array('id' =>'16064', 'long' => 'Four Horsemen (10)', 'short' => 'Korth\'azz (10)'),
'sapphiron_10' => array('id' =>'15989', 'long' => 'Sapphiron (10)', 'short' => 'Sapphiron (10)'),
'kelthuzad_10' => array('id' =>'15990', 'long' => 'Kel\'Thuzad (10)', 'short' => 'Kel\'Thuzad (10)'),

/******Naxxramas Wotlk (25) *****/
'naxx_25' => array('long' => 'Naxxramas (25)', 'short' => 'Naxx (25)'),
'anubrekhan_25' => array('id' =>'15956', 'long' => 'Anub\'Rekhan (25)', 'short' => 'Anub\'Rekhan (25)'),
'faerlina_25' => array('id' =>'15953', 'long' => 'Grand Widow Faerlina (25)', 'short' => 'Faerlina (25)'),
'maexxna_25' => array('id' =>'15952', 'long' => 'Maexxna (25)', 'short' => 'Maexxna (25)'),
'noth_25' => array('id' =>'15954', 'long' => 'Noth the Plaguebringer (25)', 'short' => 'Noth (25)'),
'heigan_25' => array('id' =>'15936', 'long' => 'Heigan the Unclean (25)', 'short' => 'Heigan (25)'),
'loatheb_25' => array('id' =>'16011', 'long' => 'Loatheb (25)', 'short' => 'Loatheb (25)'),
'patchwerk_25' => array('id' =>'16028', 'long' => 'Patchwerk (25)' , 'short' => 'Patchwerk (25)'),
'grobbulus_25' => array('id' =>'15931', 'long' => 'Grobbulus (25)', 'short' => 'Grobbulus (25)'),
'gluth_25' => array('id' =>'15932', 'long' => 'Gluth (25)', 'short' => 'Gluth (25)'),
'thaddius_25' => array('id' =>'15928', 'long' => 'Thaddius (25)', 'short' => 'Thaddius (25)'),
'razuvious_25' => array('id' =>'16061', 'long' => 'Instructor Razuvious (25)', 'short' => 'Razuvious (25)'),
'gothik_25' => array('id' =>'16060', 'long' => 'Gothik the Harvester (25)', 'short' => 'Gothik (25)'),
'horsemen_25' => array('id' =>'16064', 'long' => 'Four Horsemen (25)', 'short' => 'Korth\'azz (25)'),
'sapphiron_25' => array('id' =>'15989', 'long' => 'Sapphiron (25)', 'short' => 'Sapphiron (25)'),
'kelthuzad_25' => array('id' =>'15990', 'long' => 'Kel\'Thuzad (25)', 'short' => 'Kel\'Thuzad (25)'),


/******Vault of Archavon (10)*****/
'vault_of_archavon_10' => array('id' =>'31125', 'long' => 'Vault of Archavon (10)', 'short' => 'VoA (10)'),
'archavon_10' => array('id' =>'31125', 'long' => 'Archavon the Stone Watcher (10)', 'short' => 'Archavon (10)'),
'emalon_10' => array('id' =>'33993', 'long' => 'Emalon the Storm Watcher (10)', 'short' => 'Emalon (10)'),

/******Vault of Archavon (25)*****/
'vault_of_archavon_25' => array('id' =>'31125', 'long' => 'Vault of Archavon (25)', 'short' => 'VoA (25)'),
'archavon_25' => array('id' =>'31125', 'long' => 'Archavon the Stone Watcher (25)', 'short' => 'Archavon (25)'),
'emalon_25' => array('id' =>'33993', 'long' => 'Emalon the Storm Watcher (25)', 'short' => 'Emalon (25)'),

/******Eye of Eternity (10)*****/  
'eye_of_eternity_10' => array('long' => 'Eye of Eternity (10)', 'short' => 'EoE (10)'),
'malygos_10' => array('id' =>'28859', 'long' => 'Malygos (10)', 'short' => 'Malygos (10)'),

/******Eye of Eternity (25)*****/  
'eye_of_eternity_25' => array('long' => 'Eye of Eternity (25)', 'short' => 'EoE (25)'),
'malygos_25' => array('id' =>'28859', 'long' => 'Malygos (25)', 'short' => 'Malygos (25)'),

/******The Obsidian Sanctum (10)*****/
'obsidian_sanctum_10' => array('long' => 'The Obsidian Sanctum (10)', 'short' => 'OS (10)'),
'sartharion_0d_10' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian No dragons (10)', 'short' => 'Sartarion 0d (10)'),
'sartharion_1d_10' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian One dragon (10)', 'short' => 'Sartarion 1d (10)'),
'sartharion_2d_10' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian Two dragons (10)', 'short' => 'Sartarion 2d (10)'),
'sartharion_3d_10' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian Three dragons (10)', 'short' => 'Sartarion 3d (10)'),

/******The Obsidian Sanctum (25)*****/
'obsidian_sanctum_25' => array('long' => 'The Obsidian Sanctum (25)', 'short' => 'OS (25)'),
'sartharion_0d_25' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian No dragons (25)', 'short' => 'Sartarion 0d (25)'),
'sartharion_1d_25' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian One dragon (25)', 'short' => 'Sartarion 1d (25)'),
'sartharion_2d_25' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian Two dragons (25)', 'short' => 'Sartarion 2d (25)'),
'sartharion_3d_25' => array('id' =>'28860', 'long' => 'Sartharion the Onyx Guardian Three dragons (25)', 'short' => 'Sartarion 3d (25)'),

/******WOTLK - Ulduar (10) *****/
'ulduar_10' => array('long' => 'Ulduar (10)', 'short' => 'UD (10)'),
'leviathan_10' => array('id' =>'33113', 'long' => 'Flame Leviathan (10)', 'short' => 'Leviathan (10)'),
'razorscale_10' => array('id' =>'33186', 'long' => 'Razorscale (10)', 'short' => 'Razorscale (10)'),
'deconstructor_10' => array('id' =>'33293', 'long' => 'XT-002 Deconstructor (10)', 'short' => 'Deconstructor (10)'),
'ignis_10' => array('id' =>'33118', 'long' => 'Ignis the Furnace Master (10)', 'short' => 'Ignis (10)'),
'assembly_10' => array('id' =>'32867', 'long' => 'Assembly of Iron (10)', 'short' => 'Assembly (10)'),
'kologarn_10' => array('id' =>'32930', 'long' => 'Kologarn (10)', 'short' => 'Kologarn (10)'),
'auriaya_10' => array('id' =>'33515', 'long' => 'Auriaya (10)', 'short' => 'Auriaya (10)'),
'mimiron_10' => array('id' =>'32867', 'long' => 'Mimiron (10)', 'short' => 'Mimiron (10)'),
'hodir_10' => array('id' =>'33213', 'long' => 'Hodir (10)', 'short' => 'Hodir (10)'),
'thorim_10' => array('id' =>'33413', 'long' => 'Thorim (10)', 'short' => 'Thorim (10)'),
'freya_10' => array('id' =>'33410', 'long' => 'Freya (10)', 'short' => 'Freya (10)'),
'vezax_10' => array('id' =>'33271', 'long' => 'General Vezax (10)', 'short' => 'Vezax (10)'),
'yoggsaron_10' => array('id' =>'33288', 'long' => 'Yogg-Saron (10)', 'short' => 'Yogg-Saron (10)'),
'algalon_10' => array('id' =>'32867', 'long' => 'Algalon (10)', 'short' => 'Algalon (10)'),

/******WOTLK - Ulduar (25) *****/
'ulduar_25' => array('long' => 'Ulduar (25)', 'short' => 'UD (25)'),
'leviathan_25' => array('id' =>'33113', 'long' => 'Flame Leviathan (25)', 'short' => 'Leviathan (25)'),
'razorscale_25' => array('id' =>'33186', 'long' => 'Razorscale (25)', 'short' => 'Razorscale (25)'),
'deconstructor_25' => array('id' =>'33293', 'long' => 'XT-002 Deconstructor (25)', 'short' => 'Deconstructor (25)'),
'ignis_25' => array('id' =>'33118', 'long' => 'Ignis the Furnace Master (25)', 'short' => 'Ignis (25)'),
'assembly_25' => array('id' =>'32867', 'long' => 'Assembly of Iron (25)', 'short' => 'Assembly (25)'),
'kologarn_25' => array('id' =>'32930', 'long' => 'Kologarn (25)', 'short' => 'Kologarn (25)'),
'auriaya_25' => array('id' =>'33515', 'long' => 'Auriaya (25)', 'short' => 'Auriaya (25)'),
'mimiron_25' => array('id' =>'32867', 'long' => 'Mimiron (25)', 'short' => 'Mimiron (25)'),
'hodir_25' => array('id' =>'33213', 'long' => 'Hodir (25)', 'short' => 'Hodir (25)'),
'thorim_25' => array('id' =>'33413', 'long' => 'Thorim (25)', 'short' => 'Thorim (25)'),
'freya_25' => array('id' =>'33410', 'long' => 'Freya (25)', 'short' => 'Freya (25)'),
'vezax_25' => array('id' =>'33271', 'long' => 'General Vezax (25)', 'short' => 'Vezax (25)'),
'yoggsaron_25' => array('id' =>'33288', 'long' => 'Yogg-Saron (25)', 'short' => 'Yogg-Saron (25)'),
'algalon_25' => array('id' =>'32867', 'long' => 'Algalon (25)', 'short' => 'Algalon (25)'),


/******WOTLK - Trial of the Grand Crusader (25) *****/
'trial_of_the_grand_crusader_25' => array('long' => 'Trial of the Grand Crusader (25)', 'short' => 'ToGC (25)'),
'northrend_beasts_25_hc' => array('id' =>'35470', 'long' => 'Northrend Beasts (25)', 'short' => 'Beasts (25)'),
'faction_champions_25_hc' => array('id' =>'', 'long' => 'Faction Champions (25)', 'short' => 'Champions (25)'),
'lord_jaraxxus_25_hc' => array('id' =>'', 'long' => 'Lord Jaraxxus (25)', 'short' => 'Jaraxxus (25)'),
'twin_valkyrs_25_hc' => array('id' =>'', 'long' => 'Twin Valkyrs (25)', 'short' => 'Valkyrs (25)'),
'anub_arak_25_hc' => array('id' =>'', 'long' => 'Anub Arak (25)', 'short' => 'Anub (25)'),

/******WOTLK - Trial of the Grand Crusader (10) *****/
'trial_of_the_grand_crusader_10' => array('long' => 'Trial of the Grand Crusader (10)', 'short' => 'ToGC (10)'),
'northrend_beasts_10_hc' => array('id' =>'35470', 'long' => 'Northrend Beasts (10)', 'short' => 'Beasts (10)'),
'faction_champions_10_hc' => array('id' =>'', 'long' => 'Faction Champions (10)', 'short' => 'Champions (10)'),
'lord_jaraxxus_10_hc' => array('id' =>'', 'long' => 'Lord Jaraxxus (10)', 'short' => 'Jaraxxus (10)'),
'twin_valkyrs_10_hc' => array('id' =>'', 'long' => 'Twin Valkyrs (10)', 'short' => 'Valkyrs (10)'),
'anub_arak_10_hc' => array('id' =>'', 'long' => 'Anub Arak (10)', 'short' => 'Anub (10)'),

/******WOTLK - Trial of the Crusader (25) *****/
'trial_of_the_crusader_25' => array('long' => 'Trial of the Crusader (25)', 'short' => 'ToC (25)'),
'northrend_beasts_25' => array('id' =>'35470', 'long' => 'Northrend Beasts (25)', 'short' => 'Beasts (25)'),
'faction_champions_25' => array('id' =>'', 'long' => 'Faction Champions (25)', 'short' => 'Champions (25)'),
'lord_jaraxxus_25' => array('id' =>'', 'long' => 'Lord Jaraxxus (25)', 'short' => 'Jaraxxus (25)'),
'twin_valkyrs_25' => array('id' =>'', 'long' => 'Twin Valkyrs (25)', 'short' => 'Valkyrs (25)'),
'anub_arak_25' => array('id' =>'', 'long' => 'Anub Arak (25)', 'short' => 'Anub (25)'),

/******WOTLK - Trial of the Crusader (10) *****/
'trial_of_the_crusader_10' => array('long' => 'Trial of the Crusader (10)', 'short' => 'ToC (10)'),
'northrend_beasts_10' => array('id' =>'35470', 'long' => 'Northrend Beasts (10)', 'short' => 'Beasts (10)'),
'faction_champions_10' => array('id' =>'', 'long' => 'Faction Champions (10)', 'short' => 'Champions (10)'),
'lord_jaraxxus_10' => array('id' =>'', 'long' => 'Lord Jaraxxus (10)', 'short' => 'Jaraxxus (10)'),
'twin_valkyrs_10' => array('id' =>'', 'long' => 'Twin Valkyrs (10)', 'short' => 'Valkyrs (10)'),
'anub_arak_10' => array('id' =>'', 'long' => 'Anub Arak (10)', 'short' => 'Anub (10)'),

/******Onyxia (25) *****/
'onylair_25' => array('long' => 'Onyxia\'s Lair (25)', 'short' => 'Onyxia (25)'),
'onyxia_25' => array('id' =>'10184', 'long' => 'Onyxia', 'short' => 'Onyxia (25)'),

/******Onyxia (10) *****/
'onylair_10' => array('long' => 'Onyxia\'s Lair (10)', 'short' => 'Onyxia (10)'),
'onyxia_10' => array('id' =>'10184', 'long' => 'Onyxia', 'short' => 'Onyxia (10)'),


/******WOTLK - Icecrown Citadel (10) *****/
'icecrown_citadel_10' => array('long' => 'Icecrown Citadel (10)', 'short' => 'ICC (10)'),
'lord_marrowgar_10' => array('id' =>'36612', 'long' => 'Lord Marrowgar (10)', 'short' => 'Marrowgar (10)'),
'lady_deathwhisper_10' => array('id' =>'36855', 'long' => 'Lady Deathwhisper (10)', 'short' => 'Deathwisper (10)'),
'icecrown_gunship_battle_10' => array('id' =>'201873', 'long' => 'Icecrown Gunship Battle (10)', 'short' => 'Gunship (10)'),
'deathbringer_saurfang_10' => array('id' =>'37813', 'long' => 'Deathbringer Saurfang (10)', 'short' => 'Saurfang (10)'),
'festergut_10' => array('id' =>'36626', 'long' => 'Festergut (10)', 'short' => 'Festergut (10)'),
'rotface_10' => array('id' =>'36627', 'long' => 'Rotface (10)', 'short' => 'Rotface (10)'),
'professor_putricide_10' => array('id' =>'36678', 'long' => 'Professor Putricide (10)', 'short' => 'Putricide (10)'),
'blood_princes_10' => array('id' =>'37970', 'long' => 'Blood Princes (10)', 'short' => 'Princes (10)'),
'blood_queen_lana_thel_10' => array('id' =>'38004', 'long' => 'Blood-Queen Lana thel (10)', 'short' => 'Lana thel (10)'),
'valithria_dreamwalker_10' => array('id' =>'36789', 'long' => 'Valithria Dreamwalker (10)', 'short' => 'Valithria (10)'),
'sindragosa_10' => array('id' =>'37755', 'long' => 'Sindragosa (10)', 'short' => 'Sindragosa (10)'),
'the_lich_king_10' => array('id' =>'29983', 'long' => 'The Lich King (10)', 'short' => 'Lich King (10)'),
'lord_marrowgar_10_hc' => array('id' =>'36612', 'long' => 'Lord Marrowgar (10HM)', 'short' => 'Marrowgar (10HM)'),
'lady_deathwhisper_10_hc' => array('id' =>'36855', 'long' => 'Lady Deathwhisper (10HM)', 'short' => 'Deathwisper (10HM)'),
'icecrown_gunship_battle_10_hc' => array('id' =>'201873', 'long' => 'Icecrown Gunship Battle (10HM)', 'short' => 'Gunship (10HM)'),
'deathbringer_saurfang_10_hc' => array('id' =>'37813', 'long' => 'Deathbringer Saurfang (10HM)', 'short' => 'Saurfang (10HM)'),
'festergut_10_hc' => array('id' =>'36626', 'long' => 'Festergut (10HM)', 'short' => 'Festergut (10HM)'),
'rotface_10_hc' => array('id' =>'36627', 'long' => 'Rotface (10HM)', 'short' => 'Rotface (10HM)'),
'professor_putricide_10_hc' => array('id' =>'36678', 'long' => 'Professor Putricide (10HM)', 'short' => 'Putricide (10HM)'),
'blood_princes_10_hc' => array('id' =>'37970', 'long' => 'Blood Princes (10HM)', 'short' => 'Princes (10HM)'),
'blood_queen_lana_thel_10_hc' => array('id' =>'38004', 'long' => 'Blood-Queen Lana thel (10HM)', 'short' => 'Lana thel (10HM)'),
'valithria_dreamwalker_10_hc' => array('id' =>'36789', 'long' => 'Valithria Dreamwalker (10HM)', 'short' => 'Valithria (10HM)'),
'sindragosa_10_hc' => array('id' =>'37755', 'long' => 'Sindragosa (10HM)', 'short' => 'Sindragosa (10HM)'),
'the_lich_king_10_hc' => array('id' =>'29983', 'long' => 'The Lich King (10HM)', 'short' => 'Lich King (10HM)'),

/******WOTLK - Icecrown Citadel (25) *****/
'icecrown_citadel_25' => array('long' => 'Icecrown Citadel (25)', 'short' => 'ICC (25)'),
'lord_marrowgar_25' => array('id' =>'36612', 'long' => 'Lord Marrowgar (25)', 'short' => 'Marrowgar (25)'),
'lady_deathwhisper_25' => array('id' =>'36855', 'long' => 'Lady Deathwhisper (25)', 'short' => 'Deathwisper (25)'),
'icecrown_gunship_battle_25' => array('id' =>'201873', 'long' => 'Icecrown Gunship Battle (25)', 'short' => 'Gunship (25)'),
'deathbringer_saurfang_25' => array('id' =>'37813', 'long' => 'Deathbringer Saurfang (25)', 'short' => 'Saurfang (25)'),
'festergut_25' => array('id' =>'36626', 'long' => 'Festergut (25)', 'short' => 'Festergut (25)'),
'rotface_25' => array('id' =>'36627', 'long' => 'Rotface (25)', 'short' => 'Rotface (25)'),
'professor_putricide_25' => array('id' =>'36678', 'long' => 'Professor Putricide (25)', 'short' => 'Putricide (25)'),
'blood_princes_25' => array('id' =>'37970', 'long' => 'Blood Princes (25)', 'short' => 'Princes (25)'),
'blood_queen_lana_thel_25' => array('id' =>'38004', 'long' => 'Blood-Queen Lana thel (25)', 'short' => 'Lana thel (25)'),
'valithria_dreamwalker_25' => array('id' =>'36789', 'long' => 'Valithria Dreamwalker (25)', 'short' => 'Valithria (25)'),
'sindragosa_25' => array('id' =>'37755', 'long' => 'Sindragosa (25)', 'short' => 'Sindragosa (25)'),
'the_lich_king_25' => array('id' =>'29983', 'long' => 'The Lich King (25)', 'short' => 'Lich King (25)'),
'lord_marrowgar_25_hc' => array('id' =>'36612', 'long' => 'Lord Marrowgar (25HM)', 'short' => 'Marrowgar (25HM)'),
'lady_deathwhisper_25_hc' => array('id' =>'36855', 'long' => 'Lady Deathwhisper (25HM)', 'short' => 'Deathwisper (25HM)'),
'icecrown_gunship_battle_25_hc' => array('id' =>'201873', 'long' => 'Icecrown Gunship Battle (25HM)', 'short' => 'Gunship (25HM)'),
'deathbringer_saurfang_25_hc' => array('id' =>'37813', 'long' => 'Deathbringer Saurfang (25HM)', 'short' => 'Saurfang (25HM)'),
'festergut_25_hc' => array('id' =>'36626', 'long' => 'Festergut (25HM)', 'short' => 'Festergut (25HM)'),
'rotface_25_hc' => array('id' =>'36627', 'long' => 'Rotface (25HM)', 'short' => 'Rotface (25HM)'),
'professor_putricide_25_hc' => array('id' =>'36678', 'long' => 'Professor Putricide (25HM)', 'short' => 'Putricide (25HM)'),
'blood_princes_25_hc' => array('id' =>'37970', 'long' => 'Blood Princes (25HM)', 'short' => 'Princes (25HM)'),
'blood_queen_lana_thel_25_hc' => array('id' =>'38004', 'long' => 'Blood-Queen Lana thel (25HM)', 'short' => 'Lana thel (25HM)'),
'valithria_dreamwalker_25_hc' => array('id' =>'36789', 'long' => 'Valithria Dreamwalker (25HM)', 'short' => 'Valithria (25HM)'),
'sindragosa_25_hc' => array('id' =>'37755', 'long' => 'Sindragosa (25HM)', 'short' => 'Sindragosa (25HM)'),
'the_lich_king_25_hc' => array('id' =>'29983', 'long' => 'The Lich King (25HM)', 'short' => 'Lich King (25HM)'),

/******Ruby Sanctum (25) *****/
'rs_25' => array('long' => 'The Ruby Sanctum (25)', 'short' => 'Ruby Sanctum (25)'),
'halion_25'    => array('id' =>'39863', 'long' => 'Halion (25)', 'short' => 'Halion (25)'),
'halion_25_hc' => array('id' =>'39863', 'long' => 'Halion (25HM)', 'short' => 'Halion (25)'),

/******Ruby Sanctum (10) *****/
'rs_10' => array('long' => 'The Ruby Sanctum (10)', 'short' => 'Ruby Sanctum (10)'),
'halion_10'    => array('id' =>'39863', 'long' => 'Halion (10)', 'short' => 'Halion (10)'),
'halion_10_hc' => array('id' =>'39863', 'long' => 'Halion (10HM)', 'short' => 'Halion (10)'),




/* 
End WOW Bosses
*/



));

?>
