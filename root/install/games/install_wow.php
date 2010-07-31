<?php
/**
 * bbdkp wow install data
 * @author Sajaki@betenoire
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
/************************
 * bbdkp main wow Installer 
 * 
 *
 * @param string $bbdkp_table_prefix
 */
function install_wow($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	$db->sql_query ( 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes' );
	$sql_ary = array ();
	
	// class : note these eqdkp class_ids are changed in release 1.1 to follow blizz
	$sql_ary [] = array ('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Plate', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '1', 'class_name' => 'Warrior', 'class_armor_type' => 'Plate', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '2', 'class_name' => 'Rogue', 'class_armor_type' => 'Leather', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '3', 'class_name' => 'Hunter', 'class_armor_type' => 'Mail', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '4', 'class_name' => 'Paladin', 'class_armor_type' => 'Plate', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '5', 'class_name' => 'Shaman', 'class_armor_type' => 'Mail', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '6', 'class_name' => 'Druid', 'class_armor_type' => 'Leather', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '7', 'class_name' => 'Warlock', 'class_armor_type' => 'Cloth', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '8', 'class_name' => 'Mage', 'class_armor_type' => 'Cloth', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '9', 'class_name' => 'Priest', 'class_armor_type' => 'Cloth', 'class_min_level' => 1, 'class_max_level' => 80 );
	$sql_ary [] = array ('class_id' => '10', 'class_name' => 'Death Knight', 'class_armor_type' => 'Plate', 'class_min_level' => 55, 'class_max_level' => 80 );
	
	$db->sql_multi_insert ( $bbdkp_table_prefix . 'classes', $sql_ary );
	unset ( $sql_ary );
	
	// factions
	$db->sql_query ( 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions' );
	$sql_ary = array ();
	$sql_ary [] = array ('faction_id' => 1, 'faction_name' => 'Alliance' );
	$sql_ary [] = array ('faction_id' => 2, 'faction_name' => 'Horde' );
	$db->sql_multi_insert ( $bbdkp_table_prefix . 'factions', $sql_ary );
	unset ( $sql_ary );
	
	// races : note we use blizz id
	$db->sql_query ( 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races' );
	$sql_ary = array ();
	$sql_ary [] = array ('race_id' => 0, 'race_name' => 'Unknown', 'race_faction_id' => 0 );
	$sql_ary [] = array ('race_id' => 1, 'race_name' => 'Human', 'race_faction_id' => 1 );
	$sql_ary [] = array ('race_id' => 2, 'race_name' => 'Orc', 'race_faction_id' => 2 );
	$sql_ary [] = array ('race_id' => 3, 'race_name' => 'Dwarf', 'race_faction_id' => 1 );
	$sql_ary [] = array ('race_id' => 4, 'race_name' => 'Night Elf', 'race_faction_id' => 1 );
	$sql_ary [] = array ('race_id' => 5, 'race_name' => 'Undead', 'race_faction_id' => 2 );
	$sql_ary [] = array ('race_id' => 6, 'race_name' => 'Tauren', 'race_faction_id' => 2 );
	$sql_ary [] = array ('race_id' => 7, 'race_name' => 'Gnome', 'race_faction_id' => 1 );
	$sql_ary [] = array ('race_id' => 8, 'race_name' => 'Troll', 'race_faction_id' => 2 );
	$sql_ary [] = array ('race_id' => 10, 'race_name' => 'Blood Elf', 'race_faction_id' => 2 );
	$sql_ary [] = array ('race_id' => 11, 'race_name' => 'Draenei', 'race_faction_id' => 1 );
	$db->sql_multi_insert ( $bbdkp_table_prefix . 'races', $sql_ary );
	
	unset ( $sql_ary );
	// index page recruitment status
	$sql_ary = array ();
	$sql_ary [] = array ('setting' => 'Death Knight (Blood)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Death Knight (Frost)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Death Knight (Unholy)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Druid (Resto)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Druid (Feral)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Druid (Balance)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Hunter', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Priest (Shadow)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Priest (Holy)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Priest (Disc)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Rogue', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Shaman (Enh)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Shaman (Resto)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Shaman (Ele)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Warlock', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Mage', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Paladin (Holy)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Paladin (Ret)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Paladin (Prot)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Warrior (Tank)', 'value' => 'Closed' );
	$sql_ary [] = array ('setting' => 'Warrior (DPS)', 'value' => 'Closed' );
	$db->sql_multi_insert ( $bbdkp_table_prefix . 'indexpage', $sql_ary );
	
	unset ( $sql_ary );
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ))
	{
		$sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_config';
		$db->sql_query ( $sql );
		
		$sql_ary [] = array ('config_name' => 'bossInfo', 'config_value' => 'rname' );
		$sql_ary [] = array ('config_name' => 'dynBoss', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'dynZone', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'nameDelim', 'config_value' => '-' );
		$sql_ary [] = array ('config_name' => 'noteDelim', 'config_value' => ',' );
		$sql_ary [] = array ('config_name' => 'showSB', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'source', 'config_value' => 'database' );
		$sql_ary [] = array ('config_name' => 'style', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'tables', 'config_value' => 'bbeqdkp' );
		$sql_ary [] = array ('config_name' => 'zhiType', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'zoneInfo', 'config_value' => 'rname' );
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
		
		// Boss names     
		$sql_ary = array ();
		$sql_ary [] = array ('config_name' => 'pb_akama', 'config_value' => 'Akama' );
		$sql_ary [] = array ('config_name' => 'pb_alar', 'config_value' => 'Al\'Ar the Phoenix God, Al\'Ar' );
		$sql_ary [] = array ('config_name' => 'pb_anetheron', 'config_value' => 'Anetheron' );
		$sql_ary [] = array ('config_name' => 'pb_anubrekhan', 'config_value' => 'Anub\'Rekhan' );
		$sql_ary [] = array ('config_name' => 'pb_aran', 'config_value' => 'Shade of Aran, Aran' );
		$sql_ary [] = array ('config_name' => 'pb_archimonde', 'config_value' => 'Archimonde' );
		$sql_ary [] = array ('config_name' => 'pb_arlokk', 'config_value' => 'High Priestess Arlokk, Arlokk' );
		$sql_ary [] = array ('config_name' => 'pb_attumen', 'config_value' => 'Attumen the Huntsman, Attumen' );
		$sql_ary [] = array ('config_name' => 'pb_ayamiss', 'config_value' => 'Ayamiss the Hunter, Ayamiss' );
		$sql_ary [] = array ('config_name' => 'pb_azgalor', 'config_value' => 'Azgalor' );
		$sql_ary [] = array ('config_name' => 'pb_azuregos', 'config_value' => 'Azuregos' );
		$sql_ary [] = array ('config_name' => 'pb_blaumeux', 'config_value' => 'Lady Blaumeux, Blaumeux' );
		$sql_ary [] = array ('config_name' => 'pb_bloodboil', 'config_value' => 'Gurtogg Bloodboil, Bloodboil' );
		$sql_ary [] = array ('config_name' => 'pb_buru', 'config_value' => 'Buru the Gorger, Buru' );
		$sql_ary [] = array ('config_name' => 'pb_chess', 'config_value' => 'Chess Event, Chess' );
		$sql_ary [] = array ('config_name' => 'pb_chromaggus', 'config_value' => 'Chromaggus' );
		$sql_ary [] = array ('config_name' => 'pb_council', 'config_value' => 'Illidari Council, Council' );
		$sql_ary [] = array ('config_name' => 'pb_cthun', 'config_value' => 'C\'Thun' );
		$sql_ary [] = array ('config_name' => 'pb_curator', 'config_value' => 'The Curator, Curator' );
		$sql_ary [] = array ('config_name' => 'pb_doomkazzak', 'config_value' => 'Doom Lord Kazzak, Doom Kazzak' );
		$sql_ary [] = array ('config_name' => 'pb_doomwalker', 'config_value' => 'Doomwalker' );
		$sql_ary [] = array ('config_name' => 'pb_ebonroc', 'config_value' => 'Ebonroc' );
		$sql_ary [] = array ('config_name' => 'pb_emeriss', 'config_value' => 'Emeriss' );
		$sql_ary [] = array ('config_name' => 'pb_essence', 'config_value' => 'Essence of Souls, Essence' );
		$sql_ary [] = array ('config_name' => 'pb_faerlina', 'config_value' => 'Grand Widow Faerlina, Faerlina' );
		$sql_ary [] = array ('config_name' => 'pb_fankriss', 'config_value' => 'Fankriss the Unyielding, Fankriss' );
		$sql_ary [] = array ('config_name' => 'pb_firemaw', 'config_value' => 'Firemaw' );
		$sql_ary [] = array ('config_name' => 'pb_flamegor', 'config_value' => 'Flamegor' );
		$sql_ary [] = array ('config_name' => 'pb_gahzranka', 'config_value' => 'Gahz\'ranka' );
		$sql_ary [] = array ('config_name' => 'pb_garr', 'config_value' => 'Garr' );
		$sql_ary [] = array ('config_name' => 'pb_geddon', 'config_value' => 'Baron Geddon, Geddon' );
		$sql_ary [] = array ('config_name' => 'pb_gehennas', 'config_value' => 'Gehennas' );
		$sql_ary [] = array ('config_name' => 'pb_gluth', 'config_value' => 'Gluth' );
		$sql_ary [] = array ('config_name' => 'pb_golemagg', 'config_value' => 'Golemagg the Incinerator, Golemagg' );
		$sql_ary [] = array ('config_name' => 'pb_gorefiend', 'config_value' => 'Teron Gorefiend, Gorefiend' );
		$sql_ary [] = array ('config_name' => 'pb_gothik', 'config_value' => 'Gothik the Harvester, Gothik' );
		$sql_ary [] = array ('config_name' => 'pb_grilek', 'config_value' => 'Gri\'lek' );
		$sql_ary [] = array ('config_name' => 'pb_grobbulus', 'config_value' => 'Grobbulus' );
		$sql_ary [] = array ('config_name' => 'pb_gruul', 'config_value' => 'Gruul the Dragonkiller, Gruul' );
		$sql_ary [] = array ('config_name' => 'pb_hakkar', 'config_value' => 'Hakkar' );
		$sql_ary [] = array ('config_name' => 'pb_hazzarah', 'config_value' => 'Hazza\'rah' );
		$sql_ary [] = array ('config_name' => 'pb_heigan', 'config_value' => 'Heigan the Unclean, Heigan' );
		$sql_ary [] = array ('config_name' => 'pb_huhuran', 'config_value' => 'Princess Huhuran, Huhuran' );
		$sql_ary [] = array ('config_name' => 'pb_hydross', 'config_value' => 'Hydross the Unstable, Hydross' );
		$sql_ary [] = array ('config_name' => 'pb_illhoof', 'config_value' => 'Terestian Illhoof' );
		$sql_ary [] = array ('config_name' => 'pb_illidan', 'config_value' => 'Illidan Stormrage, Illidan' );
		$sql_ary [] = array ('config_name' => 'pb_jeklik', 'config_value' => 'High Priestess Jeklik, Jeklik' );
		$sql_ary [] = array ('config_name' => 'pb_jindo', 'config_value' => 'Jin\'do the Hexxer, Jin\'do' );
		$sql_ary [] = array ('config_name' => 'pb_kaelthas', 'config_value' => 'Kael\'thas Sunstrider, Kaelthas' );
		$sql_ary [] = array ('config_name' => 'pb_karathress', 'config_value' => 'Fathom-Lord Karathress, Karathress' );
		$sql_ary [] = array ('config_name' => 'pb_kazrogal', 'config_value' => 'Kaz\'rogal' );
		$sql_ary [] = array ('config_name' => 'pb_kazzak', 'config_value' => 'Lord Kazzak, Kazzak' );
		$sql_ary [] = array ('config_name' => 'pb_kelthuzad', 'config_value' => 'Kel\'Thuzad' );
		$sql_ary [] = array ('config_name' => 'pb_korthazz', 'config_value' => 'Thane Korth\'azz, Korth\'azz' );
		$sql_ary [] = array ('config_name' => 'pb_kri', 'config_value' => 'Lord Kri' );
		$sql_ary [] = array ('config_name' => 'pb_kurinnaxx', 'config_value' => 'Kurinnaxx' );
		$sql_ary [] = array ('config_name' => 'pb_lashlayer', 'config_value' => 'Broodlord Lashlayer, Lashlayer' );
		$sql_ary [] = array ('config_name' => 'pb_leotheras', 'config_value' => 'Leotheras the Blind, Leotheras' );
		$sql_ary [] = array ('config_name' => 'pb_lethon', 'config_value' => 'Lethon' );
		$sql_ary [] = array ('config_name' => 'pb_loatheb', 'config_value' => 'Loatheb' );
		$sql_ary [] = array ('config_name' => 'pb_lucifron', 'config_value' => 'Lucifron' );
		$sql_ary [] = array ('config_name' => 'pb_lurker', 'config_value' => 'The Lurker Below, Lurker' );
		$sql_ary [] = array ('config_name' => 'pb_maexxna', 'config_value' => 'Maexxna' );
		$sql_ary [] = array ('config_name' => 'pb_magmadar', 'config_value' => 'Magmadar' );
		$sql_ary [] = array ('config_name' => 'pb_magtheridon', 'config_value' => 'Magtheridon' );
		$sql_ary [] = array ('config_name' => 'pb_maiden', 'config_value' => 'Maiden of Virtue, Maiden' );
		$sql_ary [] = array ('config_name' => 'pb_majordomo', 'config_value' => 'Majordomo Executus, Majordomo' );
		$sql_ary [] = array ('config_name' => 'pb_malchezaar', 'config_value' => 'Prince Malchezaar, Malchezaar' );
		$sql_ary [] = array ('config_name' => 'pb_mandokir', 'config_value' => 'Bloodlord Mandokir, Mandokir' );
		$sql_ary [] = array ('config_name' => 'pb_marli', 'config_value' => 'High Priestess Mar\'li, Mar\'li' );
		$sql_ary [] = array ('config_name' => 'pb_maulgar', 'config_value' => 'High King Maulgar, Maulgar' );
		$sql_ary [] = array ('config_name' => 'pb_nalorakk', 'config_value' => 'Nalorakk, Bear Avatar' );
		$sql_ary [] = array ('config_name' => 'pb_akilzon', 'config_value' => 'Akil\'Zon, Eagle Avatar' );
		$sql_ary [] = array ('config_name' => 'pb_halazzi', 'config_value' => 'Halazzi, Lynx Avatar' );
		$sql_ary [] = array ('config_name' => 'pb_janalai', 'config_value' => 'Jan\'Alai, Dragonhawk Avatar' );
		$sql_ary [] = array ('config_name' => 'pb_malacrass', 'config_value' => 'Hex Lord Malacrass, Malacrass' );
		$sql_ary [] = array ('config_name' => 'pb_zuljin', 'config_value' => 'Zul\'Jin, Zul\'Jin' );
		$sql_ary [] = array ('config_name' => 'pb_kalecgos', 'config_value' => 'Kalecgos, Kalecgos' );
		$sql_ary [] = array ('config_name' => 'pb_brutallus', 'config_value' => 'Brutallus, Brutallus' );
		$sql_ary [] = array ('config_name' => 'pb_felmyst', 'config_value' => 'Felmyst, Felmyst' );
		$sql_ary [] = array ('config_name' => 'pb_fetwins', 'config_value' => 'Alythess & Sacrolash, Alythess & Sacrolash' );
		$sql_ary [] = array ('config_name' => 'pb_muru', 'config_value' => 'M\'uru, M\'uru' );
		$sql_ary [] = array ('config_name' => 'pb_kiljaeden', 'config_value' => 'Kil\'jaeden, Kil\'jaeden' );
		$sql_ary [] = array ('config_name' => 'pb_moam', 'config_value' => 'Moam' );
		$sql_ary [] = array ('config_name' => 'pb_mograine', 'config_value' => 'Highlord Mograine, Mograine' );
		$sql_ary [] = array ('config_name' => 'pb_moroes', 'config_value' => 'Moroes' );
		$sql_ary [] = array ('config_name' => 'pb_morogrim', 'config_value' => 'Morogrim Tidewalker, Morogrim' );
		$sql_ary [] = array ('config_name' => 'pb_najentus', 'config_value' => 'High Warlord Naj\'entus, Naj\'entus' );
		$sql_ary [] = array ('config_name' => 'pb_nefarian', 'config_value' => 'Nefarian' );
		$sql_ary [] = array ('config_name' => 'pb_netherspite', 'config_value' => 'Netherspite' );
		$sql_ary [] = array ('config_name' => 'pb_nightbane', 'config_value' => 'Nightbane' );
		$sql_ary [] = array ('config_name' => 'pb_noth', 'config_value' => 'Noth the Plaguebringer, Noth' );
		$sql_ary [] = array ('config_name' => 'pb_onyxia', 'config_value' => 'Onyxia' );
		$sql_ary [] = array ('config_name' => 'pb_opera', 'config_value' => 'Opera Event, Opera' );
		$sql_ary [] = array ('config_name' => 'pb_ossirian', 'config_value' => 'Ossirian the Unscarred, Ossirian' );
		$sql_ary [] = array ('config_name' => 'pb_ouro', 'config_value' => 'Ouro' );
		$sql_ary [] = array ('config_name' => 'pb_patchwerk', 'config_value' => 'Patchwerk' );
		$sql_ary [] = array ('config_name' => 'pb_ragnaros', 'config_value' => 'Ragnaros' );
		$sql_ary [] = array ('config_name' => 'pb_rajaxx', 'config_value' => 'General Rajaxx, Rajaxx' );
		$sql_ary [] = array ('config_name' => 'pb_razorgore', 'config_value' => 'Razorgore the Untamed, Razorgore' );
		$sql_ary [] = array ('config_name' => 'pb_razuvious', 'config_value' => 'Instructor Razuvious, Razuvious' );
		$sql_ary [] = array ('config_name' => 'pb_renataki', 'config_value' => 'Renataki' );
		$sql_ary [] = array ('config_name' => 'pb_sapphiron', 'config_value' => 'Sapphiron' );
		$sql_ary [] = array ('config_name' => 'pb_sartura', 'config_value' => 'Battleguard Sartura, Sartura' );
		$sql_ary [] = array ('config_name' => 'pb_shahraz', 'config_value' => 'Mother Shahraz, Shahraz' );
		$sql_ary [] = array ('config_name' => 'pb_shazzrah', 'config_value' => 'Shazzrah' );
		$sql_ary [] = array ('config_name' => 'pb_skeram', 'config_value' => 'The Prophet Skeram, Skeram' );
		$sql_ary [] = array ('config_name' => 'pb_solarian', 'config_value' => 'High Astromancer Solarian, Solarian' );
		$sql_ary [] = array ('config_name' => 'pb_sulfuron', 'config_value' => 'Sulfuron Harbringer, Sulfuron' );
		$sql_ary [] = array ('config_name' => 'pb_supremus', 'config_value' => 'Supremus' );
		$sql_ary [] = array ('config_name' => 'pb_taerar', 'config_value' => 'Taerar' );
		$sql_ary [] = array ('config_name' => 'pb_thaddius', 'config_value' => 'Thaddius' );
		$sql_ary [] = array ('config_name' => 'pb_thekal', 'config_value' => 'High Priest Thekal, Thekal' );
		$sql_ary [] = array ('config_name' => 'pb_vaelastrasz', 'config_value' => 'Vaelastrasz the Corrupt, Vaelastrasz' );
		$sql_ary [] = array ('config_name' => 'pb_vashj', 'config_value' => 'Lady Vashj, Vashj' );
		$sql_ary [] = array ('config_name' => 'pb_veklor', 'config_value' => 'Emperor Vek\'lor, Vek\'lor' );
		$sql_ary [] = array ('config_name' => 'pb_veknilash', 'config_value' => 'Emperor Vek\'nilash, Vek\'nilash' );
		$sql_ary [] = array ('config_name' => 'pb_vem', 'config_value' => 'Vem' );
		$sql_ary [] = array ('config_name' => 'pb_venoxis', 'config_value' => 'High Priest Venoxis, Venoxis' );
		$sql_ary [] = array ('config_name' => 'pb_viscidus', 'config_value' => 'Viscidus' );
		$sql_ary [] = array ('config_name' => 'pb_vreaver', 'config_value' => 'Void Reaver, Reaver' );
		$sql_ary [] = array ('config_name' => 'pb_winterchill', 'config_value' => 'Rage Winterchill, Winterchill' );
		$sql_ary [] = array ('config_name' => 'pb_wushoolay', 'config_value' => 'Wushoolay' );
		$sql_ary [] = array ('config_name' => 'pb_yauj', 'config_value' => 'Princess Yauj' );
		$sql_ary [] = array ('config_name' => 'pb_ysondre', 'config_value' => 'Ysondre' );
		$sql_ary [] = array ('config_name' => 'pb_zeliek', 'config_value' => 'Sir Zeliek' );
		$sql_ary [] = array ('config_name' => 'pb_anubrekhan_10', 'config_value' => 'Anub\'Rekhan (10), AnubRekhan (10)' );
		$sql_ary [] = array ('config_name' => 'pb_faerlina_10', 'config_value' => 'Grand Widow Faerlina (10), Faerlina (10)' );
		$sql_ary [] = array ('config_name' => 'pb_maexxna_10', 'config_value' => 'Maexxna (10)' );
		$sql_ary [] = array ('config_name' => 'pb_noth_10', 'config_value' => 'Noth the Plaguebringer (10), Noth (10)' );
		$sql_ary [] = array ('config_name' => 'pb_heigan_10', 'config_value' => 'Heigan the Unclean (10), Heigan (10)' );
		$sql_ary [] = array ('config_name' => 'pb_loatheb_10', 'config_value' => 'Loatheb (10)' );
		$sql_ary [] = array ('config_name' => 'pb_patchwerk_10', 'config_value' => 'Patchwerk (10)' );
		$sql_ary [] = array ('config_name' => 'pb_grobbulus_10', 'config_value' => 'Grobbulus (10)' );
		$sql_ary [] = array ('config_name' => 'pb_gluth_10', 'config_value' => 'Gluth (10)' );
		$sql_ary [] = array ('config_name' => 'pb_thaddius_10', 'config_value' => 'Thaddius (10)' );
		$sql_ary [] = array ('config_name' => 'pb_razuvious_10', 'config_value' => 'Instructor Razuvious (10)' );
		$sql_ary [] = array ('config_name' => 'pb_gothik_10', 'config_value' => 'Gothik the Harvester (10)' );
		$sql_ary [] = array ('config_name' => 'pb_horsemen_10', 'config_value' => 'Four Horsemen (10)' );
		$sql_ary [] = array ('config_name' => 'pb_sapphiron_10', 'config_value' => 'Sapphiron (10)' );
		$sql_ary [] = array ('config_name' => 'pb_kelthuzad_10', 'config_value' => 'Kel\'Thuzad (10)' );
		$sql_ary [] = array ('config_name' => 'pb_anubrekhan_25', 'config_value' => 'Anub\'Rekhan (25), AnubRekhan (25)' );
		$sql_ary [] = array ('config_name' => 'pb_faerlina_25', 'config_value' => 'Grand Widow Faerlina (25), Faerlina (25)' );
		$sql_ary [] = array ('config_name' => 'pb_maexxna_25', 'config_value' => 'Maexxna (25)' );
		$sql_ary [] = array ('config_name' => 'pb_noth_25', 'config_value' => 'Noth the Plaguebringer (25), Noth (25)' );
		$sql_ary [] = array ('config_name' => 'pb_heigan_25', 'config_value' => 'Heigan the Unclean (25), Heigan (25)' );
		$sql_ary [] = array ('config_name' => 'pb_loatheb_25', 'config_value' => 'Loatheb (25)' );
		$sql_ary [] = array ('config_name' => 'pb_patchwerk_25', 'config_value' => 'Patchwerk (25)' );
		$sql_ary [] = array ('config_name' => 'pb_grobbulus_25', 'config_value' => 'Grobbulus (25)' );
		$sql_ary [] = array ('config_name' => 'pb_gluth_25', 'config_value' => 'Gluth (25)' );
		$sql_ary [] = array ('config_name' => 'pb_thaddius_25', 'config_value' => 'Thaddius (25)' );
		$sql_ary [] = array ('config_name' => 'pb_razuvious_25', 'config_value' => 'Instructor Razuvious (25)' );
		$sql_ary [] = array ('config_name' => 'pb_gothik_25', 'config_value' => 'Gothik the Harvester (25)' );
		$sql_ary [] = array ('config_name' => 'pb_horsemen_25', 'config_value' => 'Four Horsemen (25)' );
		$sql_ary [] = array ('config_name' => 'pb_sapphiron_25', 'config_value' => 'Sapphiron (25)' );
		$sql_ary [] = array ('config_name' => 'pb_kelthuzad_25', 'config_value' => 'Kel\'Thuzad (25)' );
		$sql_ary [] = array ('config_name' => 'pb_archavon_10', 'config_value' => 'Archavon the Stone Watcher (10), Archavon (10)' );
		$sql_ary [] = array ('config_name' => 'pb_archavon_25', 'config_value' => 'Archavon the Stone Watcher (25), Archavon (25)' );
		$sql_ary [] = array ('config_name' => 'pb_malygos_10', 'config_value' => 'Malygos (10)' );
		$sql_ary [] = array ('config_name' => 'pb_malygos_25', 'config_value' => 'Malygos (25)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_0d_10', 'config_value' => 'Sartharion the Onyx Guardian No dragons (10), Sartharion 0d (10)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_1d_10', 'config_value' => 'Sartharion the Onyx Guardian One dragon (10), Sartharion 1d (10)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_2d_10', 'config_value' => 'Sartharion the Onyx Guardian Two dragons (10), Sartharion 2d (10)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_3d_10', 'config_value' => 'Sartharion the Onyx Guardian Three dragons (10), Sartharion 3d (10)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_0d_25', 'config_value' => 'Sartharion the Onyx Guardian No dragon (25), Sartharion 0d (25)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_1d_25', 'config_value' => 'Sartharion the Onyx Guardian One dragons (25), Sartharion 1d (25)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_2d_25', 'config_value' => 'Sartharion the Onyx Guardian Two dragons (25), Sartharion 2d (25)' );
		$sql_ary [] = array ('config_name' => 'pb_sartharion_3d_25', 'config_value' => 'Sartharion the Onyx Guardian Three dragons (25), Sartharion 3d (25)' );
		$sql_ary [] = array ('config_name' => 'pb_algalon_10', 'config_value' => 'Algalon (10)' );
		$sql_ary [] = array ('config_name' => 'pb_algalon_25', 'config_value' => 'Algalon (25)' );
		$sql_ary [] = array ('config_name' => 'pb_assembly_10', 'config_value' => 'Assembly of Iron (10)' );
		$sql_ary [] = array ('config_name' => 'pb_assembly_25', 'config_value' => 'Assembly of Iron (25)' );
		$sql_ary [] = array ('config_name' => 'pb_auriaya_10', 'config_value' => 'Auriaya (10)' );
		$sql_ary [] = array ('config_name' => 'pb_auriaya_25', 'config_value' => 'Auriaya (25)' );
		$sql_ary [] = array ('config_name' => 'pb_deconstructor_10', 'config_value' => 'XT-002 Deconstructor (10)' );
		$sql_ary [] = array ('config_name' => 'pb_deconstructor_25', 'config_value' => 'XT-002 Deconstructor (25)' );
		$sql_ary [] = array ('config_name' => 'pb_emalon_10', 'config_value' => 'Emalon the Storm Watcher (10), Emalon (10)' );
		$sql_ary [] = array ('config_name' => 'pb_emalon_25', 'config_value' => 'Emalon the Storm Watcher (25), Emalon (25)' );
		$sql_ary [] = array ('config_name' => 'pb_freya_10', 'config_value' => 'Freya (10)' );
		$sql_ary [] = array ('config_name' => 'pb_freya_25', 'config_value' => 'Freya (25)' );
		$sql_ary [] = array ('config_name' => 'pb_hodir_10', 'config_value' => 'Hodir (10)' );
		$sql_ary [] = array ('config_name' => 'pb_hodir_25', 'config_value' => 'Hodir (25)' );
		$sql_ary [] = array ('config_name' => 'pb_ignis_10', 'config_value' => 'Ignis the Furnace Master (10), Ignis (10)' );
		$sql_ary [] = array ('config_name' => 'pb_ignis_25', 'config_value' => 'Ignis the Furnace Master (25), Ignis (25)' );
		$sql_ary [] = array ('config_name' => 'pb_kologarn_10', 'config_value' => 'Kologarn (10)' );
		$sql_ary [] = array ('config_name' => 'pb_kologarn_25', 'config_value' => 'Kologarn (25)' );
		$sql_ary [] = array ('config_name' => 'pb_leviathan_10', 'config_value' => 'Flame Leviathan (10), Leviathan (10)' );
		$sql_ary [] = array ('config_name' => 'pb_leviathan_25', 'config_value' => 'Flame Leviathan (25), Leviathan (25)' );
		$sql_ary [] = array ('config_name' => 'pb_mimiron_10', 'config_value' => 'Mimiron (10)' );
		$sql_ary [] = array ('config_name' => 'pb_mimiron_25', 'config_value' => 'Mimiron (25)' );
		$sql_ary [] = array ('config_name' => 'pb_razorscale_10', 'config_value' => 'Razorscale (10)' );
		$sql_ary [] = array ('config_name' => 'pb_razorscale_25', 'config_value' => 'Razorscale (25)' );
		$sql_ary [] = array ('config_name' => 'pb_thorim_10', 'config_value' => 'Thorim (10)' );
		$sql_ary [] = array ('config_name' => 'pb_thorim_25', 'config_value' => 'Thorim (25)' );
		$sql_ary [] = array ('config_name' => 'pb_vezax_10', 'config_value' => 'General Vezax (10), Vezax(10)' );
		$sql_ary [] = array ('config_name' => 'pb_vezax_25', 'config_value' => 'General Vezax (25), Vezax(25)' );
		$sql_ary [] = array ('config_name' => 'pb_yoggsaron_10', 'config_value' => 'Yogg-Saron (10)' );
		$sql_ary [] = array ('config_name' => 'pb_yoggsaron_25', 'config_value' => 'Yogg-Saron (25)' );
		$sql_ary [] = array ('config_name' => 'pz_aq20', 'config_value' => 'Ruins of Ahn\'Qiraj, AQ20' );
		$sql_ary [] = array ('config_name' => 'pz_aq40', 'config_value' => 'Gates of Ahn\'Qiraj, AQ40' );
		$sql_ary [] = array ('config_name' => 'pz_bwl', 'config_value' => 'Blackwing Lair, BWL' );
		$sql_ary [] = array ('config_name' => 'pz_zg', 'config_value' => 'Zul\'Gurub, ZG' );
		$sql_ary [] = array ('config_name' => 'pz_dream', 'config_value' => 'The Emerald Dream, Dream' );
		$sql_ary [] = array ('config_name' => 'pz_naxx', 'config_value' => 'Naxxramas, Naxx' );
		$sql_ary [] = array ('config_name' => 'pz_onylair', 'config_value' => 'Onyxia\'s Lair, Onyxia' );
		$sql_ary [] = array ('config_name' => 'pz_mc', 'config_value' => 'Molten Core, MC' );
		$sql_ary [] = array ('config_name' => 'pz_misc', 'config_value' => 'Miscellaneous bosses, Misc' );
		$sql_ary [] = array ('config_name' => 'pz_kara', 'config_value' => 'Karazhan, Kara' );
		$sql_ary [] = array ('config_name' => 'pz_gruuls', 'config_value' => 'Gruul\'s Lair, GL' );
		$sql_ary [] = array ('config_name' => 'pz_za', 'config_value' => 'Zul\'Aman, ZA' );
		$sql_ary [] = array ('config_name' => 'pz_maglair', 'config_value' => 'Magtheridon\'s Lair, Magtheridon' );
		$sql_ary [] = array ('config_name' => 'pz_eye', 'config_value' => 'The Eye, Eye' );
		$sql_ary [] = array ('config_name' => 'pz_outdoor2', 'config_value' => 'Outland Outdoor Bosses, Outdoor' );
		$sql_ary [] = array ('config_name' => 'pz_hyjal', 'config_value' => 'Battle of Mount Hyjal, Hyjal' );
		$sql_ary [] = array ('config_name' => 'pz_serpent', 'config_value' => 'Serpentshrine Cavern, SC' );
		$sql_ary [] = array ('config_name' => 'pz_temple', 'config_value' => 'The Black Temple, Temple' );
		$sql_ary [] = array ('config_name' => 'pz_sunwell', 'config_value' => 'The Sunwell Plateau, Sunwell' );
		$sql_ary [] = array ('config_name' => 'pz_naxx_10', 'config_value' => 'Naxxramas (10), Naxx (10)' );
		$sql_ary [] = array ('config_name' => 'pz_naxx_25', 'config_value' => 'Naxxramas (25), Naxx (25)' );
		$sql_ary [] = array ('config_name' => 'pz_vault_of_archavon_10', 'config_value' => 'Vault of Archavon (10), VoA (10)' );
		$sql_ary [] = array ('config_name' => 'pz_vault_of_archavon_25', 'config_value' => 'Vault of Archavon (25), VoA (25)' );
		$sql_ary [] = array ('config_name' => 'pz_eye_of_eternity_10', 'config_value' => 'Eye of Eternity (10), EoE (10)' );
		$sql_ary [] = array ('config_name' => 'pz_eye_of_eternity_25', 'config_value' => 'Eye of Eternity (25), EoE (25)' );
		$sql_ary [] = array ('config_name' => 'pz_obsidian_sanctum_10', 'config_value' => 'The Obsidian Sanctum (10), OS (10)' );
		$sql_ary [] = array ('config_name' => 'pz_obsidian_sanctum_25', 'config_value' => 'The Obsidian Sanctum (25), OS (25)' );
		$sql_ary [] = array ('config_name' => 'pz_ulduar_10', 'config_value' => 'Ulduar (10)' );
		$sql_ary [] = array ('config_name' => 'pz_ulduar_25', 'config_value' => 'Ulduar (25)' );
		$sql_ary [] = array ('config_name' => 'sz_aq20', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_aq40', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_bwl', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_zg', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_dream', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_naxx', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_onylair', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_mc', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_misc', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_kara', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_gruuls', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_za', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_maglair', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_eye', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_outdoor2', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_hyjal', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_serpent', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_temple', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_sunwell', 'config_value' => '0' );
		$sql_ary [] = array ('config_name' => 'sz_naxx_10', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_naxx_25', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_vault_of_archavon_10', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_vault_of_archavon_25', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_eye_of_eternity_10', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_eye_of_eternity_25', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_obsidian_sanctum_10', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_obsidian_sanctum_25', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_ulduar_10', 'config_value' => '1' );
		$sql_ary [] = array ('config_name' => 'sz_ulduar_25', 'config_value' => '1' );
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
	
	}
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' ))
	{
		// Boss offsets
		unset ( $sql_ary );
		$db->sql_query ( 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets' );
		$sql_ary = array ();
		$sql_ary [] = array ('name' => 'akama', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'alar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'anetheron', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'anubrekhan', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'aq20', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'aq40', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'aran', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'archimonde', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'arlokk', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'attumen', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ayamiss', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'azgalor', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'azuregos', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'blaumeux', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'bloodboil', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'buru', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'bwl', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'chess', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'chromaggus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'council', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'cthun', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'curator', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'doomkazzak', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'doomwalker', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'dream', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ebonroc', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'emeriss', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'essence', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'eye', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'faerlina', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'fankriss', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'firemaw', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'flamegor', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gahzranka', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'garr', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'geddon', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gehennas', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gluth', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'golemagg', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gorefiend', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gothik', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'grilek', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'grobbulus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gruul', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gruuls', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'za', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hakkar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hazzarah', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'heigan', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'huhuran', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hydross', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hyjal', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'illhoof', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'illidan', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'jeklik', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'jindo', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kaelthas', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kara', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'karathress', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kazrogal', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kazzak', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kelthuzad', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'korthazz', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kri', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kurinnaxx', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'lashlayer', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'leotheras', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'lethon', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'loatheb', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'lucifron', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'lurker', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maexxna', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maglair', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'magmadar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'magtheridon', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maiden', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'majordomo', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'malchezaar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'mandokir', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'marli', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maulgar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'mc', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'misc', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'moam', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'mograine', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'moroes', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'morogrim', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'najentus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'naxx', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'nefarian', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'netherspite', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'nightbane', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'noth', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'onylair', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'onyxia', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'opera', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ossirian', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ouro', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'outdoor2', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'patchwerk', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ragnaros', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'rajaxx', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razorgore', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razuvious', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'renataki', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sapphiron', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartura', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'serpent', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'shahraz', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'shazzrah', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'skeram', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'solarian', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sulfuron', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'supremus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'taerar', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'temple', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thaddius', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thekal', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vaelastrasz', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vashj', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'veklor', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'veknilash', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vem', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'venoxis', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'viscidus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vreaver', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'winterchill', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'wushoolay', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'yauj', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ysondre', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'zeliek', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'zg', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'nalorakk', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'akilzon', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'halazzi', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'janalai', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'malacrass', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'zuljin', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sunwell', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kalecgos', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'brutallus', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'felmyst', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'fetwins', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'muru', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kiljaeden', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'anubrekhan_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'faerlina_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maexxna_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'noth_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'heigan_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'loatheb_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'patchwerk_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'grobbulus_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gluth_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thaddius_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razuvious_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gothik_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'horsemen_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sapphiron_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kelthuzad_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'anubrekhan_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'faerlina_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'maexxna_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'noth_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'heigan_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'loatheb_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'patchwerk_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'grobbulus_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gluth_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thaddius_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razuvious_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'gothik_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'horsemen_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sapphiron_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kelthuzad_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'archavon_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'archavon_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'malygos_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'malygos_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'sartharion_0d_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_1d_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_2d_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_3d_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_0d_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_1d_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_2d_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'sartharion_3d_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'naxx_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'naxx_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vault_of_archavon_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vault_of_archavon_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'obsidian_sanctum_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'obsidian_sanctum_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'eye_of_eternity_10', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'eye_of_eternity_25', 'fdate' => '1420063200', 'ldate' => '946677600', 'counter' => '0' );
		
		$sql_ary [] = array ('name' => 'algalon_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'algalon_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'assembly_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'assembly_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'auriaya_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'auriaya_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'deconstructor_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'deconstructor_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'emalon_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'emalon_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'freya_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'freya_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hodir_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'hodir_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ignis_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ignis_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kologarn_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'kologarn_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'leviathan_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'leviathan_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'mimiron_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'mimiron_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razorscale_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'razorscale_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thorim_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'thorim_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ulduar_10', 'fdate' => '1239832800', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'ulduar_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vezax_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'vezax_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'yoggsaron_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$sql_ary [] = array ('name' => 'yoggsaron_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_offsets', $sql_ary );
	
	}
	
	// dkp system bbeqdkp_dkpsystem 
	// set to classic tbc wlk 
	$db->sql_query ( 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem' );
	$sql_ary = array ();
	$sql_ary [] = array ('dkpsys_id' => '1', 'dkpsys_name' => 'Classic', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => '2', 'dkpsys_name' => 'TBC', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => '3', 'dkpsys_name' => 'WLK10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'Y' );
	$sql_ary [] = array ('dkpsys_id' => '4', 'dkpsys_name' => 'WLK25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$db->sql_multi_insert ( $bbdkp_table_prefix . 'dkpsystem', $sql_ary );

}

/*
 * installation of new zone and boss names for wow patch 3.2, 
 */
function install_wow2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		// check if TOTC was installed manually, otherwise install
		$sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_trial_of_the_grand_crusader_25'";
		$result = $db->sql_query ( $sql );
		$countx = ( int ) $db->sql_fetchfield ( 'countx' );
		if ($countx == 0)
		{
			$sql_ary = array ();
			$sql_ary [] = array ('config_name' => 'pz_trial_of_the_grand_crusader_25', 'config_value' => 'Trial of the Grand Crusader (25), ToGC (25)' );
			$sql_ary [] = array ('config_name' => 'pz_trial_of_the_grand_crusader_10', 'config_value' => 'Trial of the Grand Crusader (10), ToGC (10)' );
			$sql_ary [] = array ('config_name' => 'pz_trial_of_the_crusader_25', 'config_value' => 'Trial of the Crusader (25), ToC (25)' );
			$sql_ary [] = array ('config_name' => 'pz_trial_of_the_crusader_10', 'config_value' => 'Trial of the Crusader (10), ToC (10)' );
			
			$sql_ary [] = array ('config_name' => 'pb_northrend_beasts_25_hc', 'config_value' => 'Northrend Beasts (25)' );
			$sql_ary [] = array ('config_name' => 'pb_northrend_beasts_25', 'config_value' => 'Northrend Beasts (25)' );
			$sql_ary [] = array ('config_name' => 'pb_northrend_beasts_10_hc', 'config_value' => 'Northrend Beasts (10)' );
			$sql_ary [] = array ('config_name' => 'pb_northrend_beasts_10', 'config_value' => 'Northrend Beasts (10)' );
			
			$sql_ary [] = array ('config_name' => 'pb_faction_champions_25_hc', 'config_value' => 'Faction Champions (25)' );
			$sql_ary [] = array ('config_name' => 'pb_faction_champions_25', 'config_value' => 'Faction Champions (25)' );
			$sql_ary [] = array ('config_name' => 'pb_faction_champions_10_hc', 'config_value' => 'Faction Champions (10)' );
			$sql_ary [] = array ('config_name' => 'pb_faction_champions_10', 'config_value' => 'Faction Champions (10)' );
			
			$sql_ary [] = array ('config_name' => 'pb_lord_jaraxxus_25_hc', 'config_value' => 'Lord Jaraxxus (25)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_jaraxxus_25', 'config_value' => 'Lord Jaraxxus (25)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_jaraxxus_10_hc', 'config_value' => 'Lord Jaraxxus (10)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_jaraxxus_10', 'config_value' => 'Lord Jaraxxus (10)' );
			
			$sql_ary [] = array ('config_name' => 'pb_twin_valkyrs_25_hc', 'config_value' => 'Twin Valkyrs (25)' );
			$sql_ary [] = array ('config_name' => 'pb_twin_valkyrs_25', 'config_value' => 'Twin Valkyrs (25)' );
			$sql_ary [] = array ('config_name' => 'pb_twin_valkyrs_10_hc', 'config_value' => 'Twin Valkyrs (10)' );
			$sql_ary [] = array ('config_name' => 'pb_twin_valkyrs_10', 'config_value' => 'Twin Valkyrs (10)' );
			
			$sql_ary [] = array ('config_name' => 'pb_anub_arak_25_hc', 'config_value' => 'Anub Arak (25)' );
			$sql_ary [] = array ('config_name' => 'pb_anub_arak_25', 'config_value' => 'Anub Arak (25)' );
			$sql_ary [] = array ('config_name' => 'pb_anub_arak_10_hc', 'config_value' => 'Anub Arak (10)' );
			$sql_ary [] = array ('config_name' => 'pb_anub_arak_10', 'config_value' => 'Anub Arak (10)' );
			
			$sql_ary [] = array ('config_name' => 'sz_trial_of_the_grand_crusader_25', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_trial_of_the_grand_crusader_10', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_trial_of_the_crusader_25', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_trial_of_the_crusader_10', 'config_value' => '1' );
			
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
			unset ( $sql_ary );
			
			// new boss offsets
			$sql_ary = array ();
			$sql_ary [] = array ('name' => 'trial_of_the_grand_crusader_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'trial_of_the_grand_crusader_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'trial_of_the_crusader_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'trial_of_the_crusader_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'northrend_beasts_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'northrend_beasts_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'northrend_beasts_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'northrend_beasts_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'faction_champions_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'faction_champions_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'faction_champions_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'faction_champions_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_jaraxxus_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_jaraxxus_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_jaraxxus_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_jaraxxus_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'twin_valkyrs_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'twin_valkyrs_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'twin_valkyrs_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'twin_valkyrs_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'anub_arak_25_hc', 'fdate' => '1239832800', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'anub_arak_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'anub_arak_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'anub_arak_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_offsets', $sql_ary );
			unset ( $sql_ary );
		}
	
	}

}

/*
 * installation of new zone and boss names for wow patch 3.2.2, Onyxia 
 */
function install_wow3($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		// check if Onyxia was installed manually, otherwise install
		$sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_onylair_25'";
		$result = $db->sql_query ( $sql );
		$countx = ( int ) $db->sql_fetchfield ( 'countx' );
		if ($countx == 0)
		{
			$sql_ary = array ();
			$sql_ary [] = array ('config_name' => 'pz_onylair_25', 'config_value' => 'Onyxia\'s Lair (25), Onyxia (25)' );
			$sql_ary [] = array ('config_name' => 'pz_onylair_10', 'config_value' => 'Onyxia\'s Lair (10), Onyxia (10)' );
			$sql_ary [] = array ('config_name' => 'sz_onylair_25', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_onylair_10', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'pb_onyxia_25', 'config_value' => 'Onyxia (25)' );
			$sql_ary [] = array ('config_name' => 'pb_onyxia_10', 'config_value' => 'Onyxia (10)' );
			
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
			unset ( $sql_ary );
			
			// new boss offsets
			$sql_ary = array ();
			$sql_ary [] = array ('name' => 'onylair_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'onylair_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'onyxia_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'onyxia_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_offsets', $sql_ary );
			unset ( $sql_ary );
		}
	
	}

}

/*
 * installation of zone and boss names for wow patch 3.3
 * 
 * thanks to Bmagic
 * 
 */
function install_wow4($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		// check if installed manually, otherwise install
		$sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_icecrown_citadel_10'";
		$result = $db->sql_query ( $sql );
		$countx = ( int ) $db->sql_fetchfield ( 'countx' );
		if ($countx == 0)
		{
			$sql_ary = array ();
			$sql_ary [] = array ('config_name' => 'sz_icecrown_citadel_10', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_icecrown_citadel_25', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'pz_icecrown_citadel_10', 'config_value' => 'Icecrown Citadel (10), ICC (10)' );
			$sql_ary [] = array ('config_name' => 'pz_icecrown_citadel_25', 'config_value' => 'Icecrown Citadel (25), ICC (25)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_marrowgar_10', 'config_value' => 'Lord Marrowgar (10)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_marrowgar_10_hc', 'config_value' => 'Lord Marrowgar (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_marrowgar_25', 'config_value' => 'Lord Marrowgar (25)' );
			$sql_ary [] = array ('config_name' => 'pb_lord_marrowgar_25_hc', 'config_value' => 'Lord Marrowgar (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_lady_deathwhisper_10', 'config_value' => 'Lady Deathwhisper (10)' );
			$sql_ary [] = array ('config_name' => 'pb_lady_deathwhisper_10_hc', 'config_value' => 'Lady Deathwhisper (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_lady_deathwhisper_25', 'config_value' => 'Lady Deathwhisper (25)' );
			$sql_ary [] = array ('config_name' => 'pb_lady_deathwhisper_25_hc', 'config_value' => 'Lady Deathwhisper (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_icecrown_gunship_battle_10', 'config_value' => 'Icecrown Gunship Battle (10)' );
			$sql_ary [] = array ('config_name' => 'pb_icecrown_gunship_battle_10_hc', 'config_value' => 'Icecrown Gunship Battle (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_icecrown_gunship_battle_25', 'config_value' => 'Icecrown Gunship Battle (25)' );
			$sql_ary [] = array ('config_name' => 'pb_icecrown_gunship_battle_25_hc', 'config_value' => 'Icecrown Gunship Battle (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_deathbringer_saurfang_10', 'config_value' => 'Deathbringer Saurfang (10)' );
			$sql_ary [] = array ('config_name' => 'pb_deathbringer_saurfang_10_hc', 'config_value' => 'Deathbringer Saurfang (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_deathbringer_saurfang_25', 'config_value' => 'Deathbringer Saurfang (25)' );
			$sql_ary [] = array ('config_name' => 'pb_deathbringer_saurfang_25_hc', 'config_value' => 'Deathbringer Saurfang (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_festergut_10', 'config_value' => 'Festergut (10)' );
			$sql_ary [] = array ('config_name' => 'pb_festergut_10_hc', 'config_value' => 'Festergut (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_festergut_25', 'config_value' => 'Festergut (25)' );
			$sql_ary [] = array ('config_name' => 'pb_festergut_25_hc', 'config_value' => 'Festergut (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_rotface_10', 'config_value' => 'Rotface (10)' );
			$sql_ary [] = array ('config_name' => 'pb_rotface_10_hc', 'config_value' => 'Rotface (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_rotface_25', 'config_value' => 'Rotface (25)' );
			$sql_ary [] = array ('config_name' => 'pb_rotface_25_hc', 'config_value' => 'Rotface (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_professor_putricide_10', 'config_value' => 'Professor Putricide (10)' );
			$sql_ary [] = array ('config_name' => 'pb_professor_putricide_10_hc', 'config_value' => 'Professor Putricide (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_professor_putricide_25', 'config_value' => 'Professor Putricide (25)' );
			$sql_ary [] = array ('config_name' => 'pb_professor_putricide_25_hc', 'config_value' => 'Professor Putricide (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_princes_10', 'config_value' => 'Blood Princes (10)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_princes_10_hc', 'config_value' => 'Blood Princes (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_princes_25', 'config_value' => 'Blood Princes (25)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_princes_25_hc', 'config_value' => 'Blood Princes (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_queen_lana_thel_10', 'config_value' => 'Blood-Queen Lana thel (10)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_queen_lana_thel_10_hc', 'config_value' => 'Blood-Queen Lana thel (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_queen_lana_thel_25', 'config_value' => 'Blood-Queen Lana thel (25)' );
			$sql_ary [] = array ('config_name' => 'pb_blood_queen_lana_thel_25_hc', 'config_value' => 'Blood-Queen Lana thel (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_valithria_dreamwalker_10', 'config_value' => 'Valithria Dreamwalker (10)' );
			$sql_ary [] = array ('config_name' => 'pb_valithria_dreamwalker_10_hc', 'config_value' => 'Valithria Dreamwalker (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_valithria_dreamwalker_25', 'config_value' => 'Valithria Dreamwalker (25)' );
			$sql_ary [] = array ('config_name' => 'pb_valithria_dreamwalker_25_hc', 'config_value' => 'Valithria Dreamwalker (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_sindragosa_10', 'config_value' => 'Sindragosa (10)' );
			$sql_ary [] = array ('config_name' => 'pb_sindragosa_10_hc', 'config_value' => 'Sindragosa (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_sindragosa_25', 'config_value' => 'Sindragosa (25)' );
			$sql_ary [] = array ('config_name' => 'pb_sindragosa_25_hc', 'config_value' => 'Sindragosa (25HM)' );
			$sql_ary [] = array ('config_name' => 'pb_the_lich_king_10', 'config_value' => 'The Lich King (10)' );
			$sql_ary [] = array ('config_name' => 'pb_the_lich_king_10_hc', 'config_value' => 'The Lich King (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_the_lich_king_25', 'config_value' => 'The Lich King (25)' );
			$sql_ary [] = array ('config_name' => 'pb_the_lich_king_25_hc', 'config_value' => 'The Lich King (25HM)' );
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
			
			unset ( $sql_ary );
			
			// new boss offsets
			$sql_ary = array ();
			$sql_ary [] = array ('name' => 'icecrown_citadel_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'icecrown_citadel_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_marrowgar_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_marrowgar_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_marrowgar_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lord_marrowgar_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lady_deathwhisper_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lady_deathwhisper_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lady_deathwhisper_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'lady_deathwhisper_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'icecrown_gunship_battle_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'icecrown_gunship_battle_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'icecrown_gunship_battle_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'icecrown_gunship_battle_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'deathbringer_saurfang_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'deathbringer_saurfang_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'deathbringer_saurfang_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'deathbringer_saurfang_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'festergut_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'festergut_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'festergut_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'festergut_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'rotface_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'rotface_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'rotface_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'rotface_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'professor_putricide_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'professor_putricide_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'professor_putricide_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'professor_putricide_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_princes_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_princes_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_princes_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_princes_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_queen_lana_thel_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_queen_lana_thel_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_queen_lana_thel_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'blood_queen_lana_thel_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'valithria_dreamwalker_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'valithria_dreamwalker_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'valithria_dreamwalker_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'valithria_dreamwalker_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'sindragosa_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'sindragosa_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'sindragosa_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'sindragosa_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'the_lich_king_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'the_lich_king_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'the_lich_king_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'the_lich_king_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_offsets', $sql_ary );
			unset ( $sql_ary );
		}
	
	}

}

/*
 * switch from old eqdkp class id to blizzard armory class id 
 * 
 * old
 * 1 warrior
 * 2 rogue 
 * 3 hunter
 * 4 paladin
 * 5 shaman
 * 6 druid
 * 7 warlock
 * 8 mage
 * 9 priest
 * 10 death knight
 * 
 * new 
 * 1 warrior
 * 2 paladin
 * 3 hunter
 * 4 Rogue
 * 5 Priest
 * 6 Death knight
 * 7 Shaman
 * 8 Mage
 * 9 Warlock
 * 10 
 * 11 Druid
 * 
 * switch sequence : switch 2 with 4, 5 & 6 & 7 move to 7 & 11 & 9, 9 & 10 move to 5 & 6
 * 4 -> 99 
 * 2 > 4
 * 99 -> 2
 * 5 -> 99
 * 9 -> 5
 * 6 -> 11
 * 10 -> 6
 * 7 -> 9
 * 99 -> 7
 * 
 */
function upd110_classid($bbdkp_table_prefix)
{
	global $db;
	
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 99 where class_id = 4" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 4 where class_id = 2" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 2 where class_id = 99" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 99 where class_id = 5" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 5 where class_id = 9" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 11 where class_id = 6" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 6 where class_id = 10" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 9 where class_id = 7" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "classes set class_id = 7 where class_id = 99" );
	
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 99 where member_class_id = 4" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 4 where member_class_id = 2" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 2 where member_class_id = 99" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 99 where member_class_id = 5" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 5 where member_class_id = 9" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 11 where member_class_id = 6" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 6 where member_class_id = 10" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 9 where member_class_id = 7" );
	$db->sql_query ( "update " . $bbdkp_table_prefix . "memberlist set member_class_id = 7 where member_class_id = 99" );

}

/*
 * installation of zone and boss names for wow patch 3.3.5 
 * The Ruby Sanctum
 * 
  * 
 */
function install_wow5($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		// check if installed manually, otherwise install
		$sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_rs_10'";
		$result = $db->sql_query ( $sql );
		$countx = ( int ) $db->sql_fetchfield ( 'countx' );
		if ($countx == 0)
		{
			$sql_ary = array ();
			$sql_ary [] = array ('config_name' => 'sz_rs_10', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'sz_rs_25', 'config_value' => '1' );
			$sql_ary [] = array ('config_name' => 'pz_rs_10', 'config_value' => 'The Ruby Sanctum (10), RS (10)' );
			$sql_ary [] = array ('config_name' => 'pz_rs_25', 'config_value' => 'The Ruby Sanctum (25), RS (25)' );
			$sql_ary [] = array ('config_name' => 'pb_halion_10', 'config_value' => 'Halion (10)' );
			$sql_ary [] = array ('config_name' => 'pb_halion_10_hc', 'config_value' => 'Halion (10HM)' );
			$sql_ary [] = array ('config_name' => 'pb_halion_25', 'config_value' => 'Halion (25)' );
			$sql_ary [] = array ('config_name' => 'pb_halion_25_hc', 'config_value' => 'Halion (25HM)' );
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_config', $sql_ary );
			
			unset ( $sql_ary );
			
			// new boss offsets
			$sql_ary = array ();
			$sql_ary [] = array ('name' => 'rs_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'rs_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'halion_10', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'halion_10_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'halion_25', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$sql_ary [] = array ('name' => 'halion_25_hc', 'fdate' => '1419980400', 'ldate' => '946594800', 'counter' => '0' );
			$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_offsets', $sql_ary );
			unset ( $sql_ary );
		}
	
	}

}


/*
 * new boss progress data for WoW
 * generated with the spreadsheet
 * 
 */
function install_wow_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		$sql_ary = array ();

		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Miscellaneous bosses', 'zonename_short' =>  'Misc' , 'imagename' =>  'misc' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 2 , 'zonename' => 'Onyxia\'s Lair', 'zonename_short' =>  'Onyxia' , 'imagename' =>  'onylair' , 'game' =>  'wow' ,  'tier' =>  'T2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 3 , 'zonename' => 'The Emerald Dream', 'zonename_short' =>  'Dream' , 'imagename' =>  'dream' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 4 , 'zonename' => 'Zul\'Gurub', 'zonename_short' =>  'ZG' , 'imagename' =>  'zg' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 5 , 'zonename' => 'Blackwing Lair', 'zonename_short' =>  'BWL' , 'imagename' =>  'bwl' , 'game' =>  'wow' ,  'tier' =>  'T3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 6 , 'zonename' => 'Molten Core', 'zonename_short' =>  'MC' , 'imagename' =>  'mc' , 'game' =>  'wow' ,  'tier' =>  'T1' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 7 , 'zonename' => 'Ruins of Ahn\'Qiraj', 'zonename_short' =>  'AQ20' , 'imagename' =>  'aq20' , 'game' =>  'wow' ,  'tier' =>  'T2.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 8 , 'zonename' => 'Gates of Ahn\'Qiraj', 'zonename_short' =>  'AQ40' , 'imagename' =>  'aq40' , 'game' =>  'wow' ,  'tier' =>  'T2.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 9 , 'zonename' => 'Naxxramas', 'zonename_short' =>  'Naxx' , 'imagename' =>  'naxx' , 'game' =>  'wow' ,  'tier' =>  'T3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 10 , 'zonename' => 'Karazhan', 'zonename_short' =>  'Kara' , 'imagename' =>  'kara' , 'game' =>  'wow' ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 11 , 'zonename' => 'Zul\'Aman', 'zonename_short' =>  'ZA' , 'imagename' =>  'za' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 12 , 'zonename' => 'Gruul\'s Lair', 'zonename_short' =>  'GL' , 'imagename' =>  'gruuls' , 'game' =>  'wow' ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 13 , 'zonename' => 'Magtheridon\'s Lair', 'zonename_short' =>  'Magtheridon' , 'imagename' =>  'maglair' , 'game' =>  'wow' ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 14 , 'zonename' => 'Outland Outdoor Bosses', 'zonename_short' =>  'Outdoor' , 'imagename' =>  'outdoor2' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 15 , 'zonename' => 'Serpentshrine Cavern', 'zonename_short' =>  'SC' , 'imagename' =>  'serpent' , 'game' =>  'wow' ,  'tier' =>  'T5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 16 , 'zonename' => 'The Eye', 'zonename_short' =>  'Eye' , 'imagename' =>  'eye' , 'game' =>  'wow' ,  'tier' =>  'T5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 17 , 'zonename' => 'Battle of Mount Hyjal', 'zonename_short' =>  'Hyjal' , 'imagename' =>  'hyjal' , 'game' =>  'wow' ,  'tier' =>  'T6' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 18 , 'zonename' => 'The Black Temple', 'zonename_short' =>  'Temple' , 'imagename' =>  'temple' , 'game' =>  'wow' ,  'tier' =>  'T6' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 19 , 'zonename' => 'The Sunwell Plateau', 'zonename_short' =>  'Sunwell' , 'imagename' =>  'sunwell' , 'game' =>  'wow' ,  'tier' =>  'T6.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 20 , 'zonename' => 'Naxxramas (10)', 'zonename_short' =>  'Naxx (10)' , 'imagename' =>  'naxx_10' , 'game' =>  'wow' ,  'tier' =>  'T7' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 21 , 'zonename' => 'Naxxramas (25)', 'zonename_short' =>  'Naxx (25)' , 'imagename' =>  'naxx_25' , 'game' =>  'wow' ,  'tier' =>  'T7.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 22 , 'zonename' => 'Vault of Archavon (10)', 'zonename_short' =>  'VoA (10)' , 'imagename' =>  'vault_of_archavon_10' , 'game' =>  'wow' ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 23 , 'zonename' => 'Vault of Archavon (25)', 'zonename_short' =>  'VoA (25)' , 'imagename' =>  'vault_of_archavon_25' , 'game' =>  'wow' ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 24 , 'zonename' => 'The Obsidian Sanctum (10)', 'zonename_short' =>  'OS (10)' , 'imagename' =>  'obsidian_sanctum_10' , 'game' =>  'wow' ,  'tier' =>  'T7' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 25 , 'zonename' => 'The Obsidian Sanctum (25)', 'zonename_short' =>  'OS (25)' , 'imagename' =>  'obsidian_sanctum_25' , 'game' =>  'wow' ,  'tier' =>  'T7.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 26 , 'zonename' => 'Eye of Eternity (10)', 'zonename_short' =>  'EoE (10)' , 'imagename' =>  'eye_of_eternity_10' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 27 , 'zonename' => 'Eye of Eternity (25)', 'zonename_short' =>  'EoE (25)' , 'imagename' =>  'eye_of_eternity_25' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 28 , 'zonename' => 'Ulduar (10)', 'zonename_short' =>  'EoE (25)' , 'imagename' =>  'ulduar_10' , 'game' =>  'wow' ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 29 , 'zonename' => 'Ulduar (25)', 'zonename_short' =>  '' , 'imagename' =>  'ulduar_25' , 'game' =>  'wow' ,  'tier' =>  'T8.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 30 , 'zonename' => 'Trial of the Crusader (10)', 'zonename_short' =>  'ToC (10)' , 'imagename' =>  'trial_of_the_crusader_10' , 'game' =>  'wow' ,  'tier' =>  'T9.1' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 31 , 'zonename' => 'Trial of the Crusader (25)', 'zonename_short' =>  'ToC (25)' , 'imagename' =>  'trial_of_the_crusader_25' , 'game' =>  'wow' ,  'tier' =>  'T9.2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 32 , 'zonename' => 'Trial of the Grand Crusader (10)', 'zonename_short' =>  'ToGC (10)' , 'imagename' =>  'trial_of_the_grand_crusader_10' , 'game' =>  'wow' ,  'tier' =>  'T9.2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 33 , 'zonename' => 'Trial of the Grand Crusader (25)', 'zonename_short' =>  'ToGC (25)' , 'imagename' =>  'trial_of_the_grand_crusader_25' , 'game' =>  'wow' ,  'tier' =>  'T9.3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 34 , 'zonename' => 'Onyxia\'s Lair (10)', 'zonename_short' =>  'Onyxia (10)' , 'imagename' =>  'onylair_10' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 35 , 'zonename' => 'Onyxia\'s Lair (25)', 'zonename_short' =>  'Onyxia (25)' , 'imagename' =>  'onylair_25' , 'game' =>  'wow' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 37 , 'zonename' => 'Icecrown Citadel (25)', 'zonename_short' =>  'ICC (25)' , 'imagename' =>  'icecrown_citadel_25' , 'game' =>  'wow' ,  'tier' =>  'T10.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 36 , 'zonename' => 'Icecrown Citadel (10)', 'zonename_short' =>  'ICC (10)' , 'imagename' =>  'icecrown_citadel_10' , 'game' =>  'wow' ,  'tier' =>  'T10' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 38 , 'zonename' => 'The Ruby Sanctum (10)', 'zonename_short' =>  'RS (10)' , 'imagename' =>  'rs_10' , 'game' =>  'wow' ,  'tier' =>  'T10' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 39 , 'zonename' => 'The Ruby Sanctum (25)', 'zonename_short' =>  'RS (25)' , 'imagename' =>  'rs_25' , 'game' =>  'wow' ,  'tier' =>  'T10' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
				
		
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );
		
		$sql_ary[] = array('id' => 1 , 'bossname' => 'Azuregos' , 'bossname_short' => 'Azuregos', 'imagename' =>  'azuregos' , 'game' =>  'wow' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  '6109' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 2 , 'bossname' => 'Lord Kazzak' , 'bossname_short' => 'Kazzak', 'imagename' =>  'kazzak' , 'game' =>  'wow' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  '12397' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 3 , 'bossname' => 'Onyxia' , 'bossname_short' => 'Onyxia', 'imagename' =>  'onyxia' , 'game' =>  'wow' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '10184' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 4 , 'bossname' => 'Ysondre' , 'bossname_short' => 'Ysondre', 'imagename' =>  'ysondre' , 'game' =>  'wow' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '14887' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 5 , 'bossname' => 'Taerar' , 'bossname_short' => 'Taerar', 'imagename' =>  'taerar' , 'game' =>  'wow' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '14890' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 6 , 'bossname' => 'Emeriss' , 'bossname_short' => 'Emeriss', 'imagename' =>  'emeriss' , 'game' =>  'wow' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '14889' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 7 , 'bossname' => 'Lethon' , 'bossname_short' => 'Lethon', 'imagename' =>  'lethon' , 'game' =>  'wow' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '14888' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 8 , 'bossname' => 'Bloodlord Mandokir' , 'bossname_short' => 'Mandokir', 'imagename' =>  'mandokir' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '11382' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 9 , 'bossname' => 'Jin\'do the Hexxer' , 'bossname_short' => 'Jin\'do', 'imagename' =>  'jindo' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '11380' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 10 , 'bossname' => 'Gahz\'ranka' , 'bossname_short' => 'Gahz\'ranka', 'imagename' =>  'gahzranka' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '15114' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 11 , 'bossname' => 'Gri\'lek' , 'bossname_short' => 'Gri\'lek', 'imagename' =>  'grilek' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '15082' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 12 , 'bossname' => 'Hazza\'rah' , 'bossname_short' => 'Hazza\'rah', 'imagename' =>  'hazzarah' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '15083' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 13 , 'bossname' => 'Renataki' , 'bossname_short' => 'Renataki', 'imagename' =>  'renataki' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '15084' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 14 , 'bossname' => 'Wushoolay' , 'bossname_short' => 'Wushoolay', 'imagename' =>  'wushoolay' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '15085' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 15 , 'bossname' => 'High Priest Thekal' , 'bossname_short' => 'Thekal', 'imagename' =>  'thekal' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14509' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 16 , 'bossname' => 'High Priestess Arlokk' , 'bossname_short' => 'Arlokk', 'imagename' =>  'arlokk' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14515' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 17 , 'bossname' => 'High Priestess Jeklik' , 'bossname_short' => 'Jeklik', 'imagename' =>  'jeklik' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14517' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 18 , 'bossname' => 'High Priestess Mar\'li' , 'bossname_short' => 'Mar\'li', 'imagename' =>  'marli' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14510' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 19 , 'bossname' => 'High Priest Venoxis' , 'bossname_short' => 'Venoxis', 'imagename' =>  'venoxis' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14507' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 20 , 'bossname' => 'Hakkar' , 'bossname_short' => 'Hakkar', 'imagename' =>  'hakkar' , 'game' =>  'wow' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '14834' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 21 , 'bossname' => 'Razorgore the Untamed' , 'bossname_short' => 'Razorgore', 'imagename' =>  'razorgore' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '12435' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 22 , 'bossname' => 'Vaelastrasz the Corrupt' , 'bossname_short' => 'Vaelastrasz', 'imagename' =>  'vaelastrasz' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '13020' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 23 , 'bossname' => 'Broodlord Lashlayer' , 'bossname_short' => 'Lashlayer', 'imagename' =>  'lashlayer' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '12017' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 24 , 'bossname' => 'Firemaw' , 'bossname_short' => 'Firemaw', 'imagename' =>  'firemaw' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '11981' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 25 , 'bossname' => 'Ebonroc' , 'bossname_short' => 'Ebonroc', 'imagename' =>  'ebonroc' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '14601' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 26 , 'bossname' => 'Flamegor' , 'bossname_short' => 'Flamegor', 'imagename' =>  'flamegor' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '11983' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 27 , 'bossname' => 'Chromaggus' , 'bossname_short' => 'Chromaggus', 'imagename' =>  'chromaggus' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '14020' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 28 , 'bossname' => 'Nefarian' , 'bossname_short' => 'Nefarian', 'imagename' =>  'nefarian' , 'game' =>  'wow' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '11583' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 29 , 'bossname' => 'Lucifron' , 'bossname_short' => 'Lucifron', 'imagename' =>  'lucifron' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12118' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 30 , 'bossname' => 'Magmadar' , 'bossname_short' => 'Magmadar', 'imagename' =>  'magmadar' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '11982' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 31 , 'bossname' => 'Gehennas' , 'bossname_short' => 'Gehennas', 'imagename' =>  'gehennas' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12259' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 32 , 'bossname' => 'Garr' , 'bossname_short' => 'Garr', 'imagename' =>  'garr' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12057' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 33 , 'bossname' => 'Baron Geddon' , 'bossname_short' => 'Geddon', 'imagename' =>  'geddon' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12056' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 34 , 'bossname' => 'Shazzrah' , 'bossname_short' => 'Shazzrah', 'imagename' =>  'shazzrah' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12264' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 35 , 'bossname' => 'Sulfuron Harbringer' , 'bossname_short' => 'Sulfuron', 'imagename' =>  'sulfuron' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12098' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 36 , 'bossname' => 'Golemagg the Incinerator' , 'bossname_short' => 'Golemagg', 'imagename' =>  'golemagg' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '11988' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 37 , 'bossname' => 'Majordomo Executus' , 'bossname_short' => 'Majordomo', 'imagename' =>  'majordomo' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '12018' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 38 , 'bossname' => 'Ragnaros' , 'bossname_short' => 'Ragnaros', 'imagename' =>  'ragnaros' , 'game' =>  'wow' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '11502' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 39 , 'bossname' => 'Kurinnaxx' , 'bossname_short' => 'Kurinnaxx', 'imagename' =>  'kurinnaxx' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15348' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 40 , 'bossname' => 'General Rajaxx' , 'bossname_short' => 'Rajaxx', 'imagename' =>  'rajaxx' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15341' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 41 , 'bossname' => 'Ayamiss the Hunter' , 'bossname_short' => 'Ayamiss', 'imagename' =>  'ayamiss' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15369' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 42 , 'bossname' => 'Buru the Gorger' , 'bossname_short' => 'Buru', 'imagename' =>  'buru' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15370' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 43 , 'bossname' => 'Moam' , 'bossname_short' => 'Moam', 'imagename' =>  'moam' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15340' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 44 , 'bossname' => 'Ossirian the Unscarred' , 'bossname_short' => 'Ossirian', 'imagename' =>  'ossirian' , 'game' =>  'wow' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '15339' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 45 , 'bossname' => 'The Prophet Skeram' , 'bossname_short' => 'Skeram', 'imagename' =>  'skeram' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15263' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 46 , 'bossname' => 'Lord Kri' , 'bossname_short' => 'Lord Kri', 'imagename' =>  'kri' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15511' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 47 , 'bossname' => 'Princess Yauj' , 'bossname_short' => 'Princess Yauj', 'imagename' =>  'yauj' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15543' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 48 , 'bossname' => 'Vem' , 'bossname_short' => 'Vem', 'imagename' =>  'vem' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15544' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 49 , 'bossname' => 'Battleguard Sartura' , 'bossname_short' => 'Sartura', 'imagename' =>  'sartura' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15516' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 50 , 'bossname' => 'Fankriss the Unyielding' , 'bossname_short' => 'Fankriss', 'imagename' =>  'fankriss' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15510' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 51 , 'bossname' => 'Princess Huhuran' , 'bossname_short' => 'Huhuran', 'imagename' =>  'huhuran' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15509' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 52 , 'bossname' => 'Viscidus' , 'bossname_short' => 'Viscidus', 'imagename' =>  'viscidus' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15299' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 53 , 'bossname' => 'Emperor Vek\'nilash' , 'bossname_short' => 'Vek\'nilash', 'imagename' =>  'veknilash' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15275' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 54 , 'bossname' => 'Emperor Vek\'lor' , 'bossname_short' => 'Vek\'lor', 'imagename' =>  'veklor' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15276' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 55 , 'bossname' => 'Ouro' , 'bossname_short' => 'Ouro', 'imagename' =>  'ouro' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15517' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 56 , 'bossname' => 'C\'Thun' , 'bossname_short' => 'C\'Thun', 'imagename' =>  'cthun' , 'game' =>  'wow' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '15727' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 57 , 'bossname' => 'Anub\'Rekhan' , 'bossname_short' => 'Anub\'Rekhan', 'imagename' =>  'anubrekhan' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15956' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 58 , 'bossname' => 'Grand Widow Faerlina' , 'bossname_short' => 'Faerlina', 'imagename' =>  'faerlina' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15953' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 59 , 'bossname' => 'Maexxna' , 'bossname_short' => 'Maexxna', 'imagename' =>  'maexxna' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15952' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 60 , 'bossname' => 'Noth the Plaguebringer' , 'bossname_short' => 'Noth', 'imagename' =>  'noth' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15954' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 61 , 'bossname' => 'Heigan the Unclean' , 'bossname_short' => 'Heigan', 'imagename' =>  'heigan' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15936' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 62 , 'bossname' => 'Loatheb' , 'bossname_short' => 'Loatheb', 'imagename' =>  'loatheb' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16011' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 63 , 'bossname' => 'Patchwerk' , 'bossname_short' => 'Patchwerk', 'imagename' =>  'patchwerk' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16028' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 64 , 'bossname' => 'Grobbulus' , 'bossname_short' => 'Grobbulus', 'imagename' =>  'grobbulus' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15931' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 65 , 'bossname' => 'Gluth' , 'bossname_short' => 'Gluth', 'imagename' =>  'gluth' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15932' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 66 , 'bossname' => 'Thaddius' , 'bossname_short' => 'Thaddius', 'imagename' =>  'thaddius' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15928' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 67 , 'bossname' => 'Instructor Razuvious' , 'bossname_short' => 'Razuvious', 'imagename' =>  'razuvious' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16061' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 68 , 'bossname' => 'Gothik the Harvester' , 'bossname_short' => 'Gothik', 'imagename' =>  'gothik' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16060' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 69 , 'bossname' => 'Thane Korth\'azz' , 'bossname_short' => 'Korth\'azz', 'imagename' =>  'korthazz' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16064' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 70 , 'bossname' => 'Lady Blaumeux' , 'bossname_short' => 'Blaumeux', 'imagename' =>  'blaumeux' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16065' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 71 , 'bossname' => 'Highlord Mograine' , 'bossname_short' => 'Mograine', 'imagename' =>  'mograine' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16062' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 72 , 'bossname' => 'Sir Zeliek' , 'bossname_short' => 'Sir Zeliek', 'imagename' =>  'zeliek' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '16063' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 73 , 'bossname' => 'Sapphiron' , 'bossname_short' => 'Sapphiron', 'imagename' =>  'sapphiron' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15989' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 74 , 'bossname' => 'Kel\'Thuzad' , 'bossname_short' => 'Kel\'Thuzad', 'imagename' =>  'kelthuzad' , 'game' =>  'wow' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '15990' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 75 , 'bossname' => 'Attumen the Huntsman' , 'bossname_short' => 'Attumen', 'imagename' =>  'attumen' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '16152' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 76 , 'bossname' => 'Moroes' , 'bossname_short' => 'Moroes', 'imagename' =>  'moroes' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '15687' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 77 , 'bossname' => 'Maiden of Virtue' , 'bossname_short' => 'Maiden', 'imagename' =>  'maiden' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '16457' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 78 , 'bossname' => 'Opera Event' , 'bossname_short' => 'Opera', 'imagename' =>  'opera' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '16812' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 79 , 'bossname' => 'The Curator' , 'bossname_short' => 'Curator', 'imagename' =>  'curator' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '15691' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 80 , 'bossname' => 'Terestian Illhoof' , 'bossname_short' => 'Terestian Illhoof', 'imagename' =>  'illhoof' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '15688' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 81 , 'bossname' => 'Shade of Aran' , 'bossname_short' => 'Aran', 'imagename' =>  'aran' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '16524' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 82 , 'bossname' => 'Chess Event' , 'bossname_short' => 'Chess', 'imagename' =>  'chess' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '17651' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 83 , 'bossname' => 'Netherspite' , 'bossname_short' => 'Netherspite', 'imagename' =>  'netherspite' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '15689' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 84 , 'bossname' => 'Prince Malchezaar' , 'bossname_short' => 'Malchezaar', 'imagename' =>  'malchezaar' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '15690' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 85 , 'bossname' => 'Nightbane' , 'bossname_short' => 'Nightbane', 'imagename' =>  'nightbane' , 'game' =>  'wow' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '17225' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 86 , 'bossname' => 'Nalorakk, Bear Avatar' , 'bossname_short' => 'Nalorakk', 'imagename' =>  'nalorakk' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '23576' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 87 , 'bossname' => 'Akil\'Zon, Eagle Avatar' , 'bossname_short' => 'Akil\'Zon', 'imagename' =>  'akilzon' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '23574' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 88 , 'bossname' => 'Halazzi, Lynx Avatar' , 'bossname_short' => 'Halazzi', 'imagename' =>  'halazzi' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '23577' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 89 , 'bossname' => 'Jan\'Alai, Dragonhawk Avatar' , 'bossname_short' => 'Jan\'Alai', 'imagename' =>  'janalai' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '23578' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 90 , 'bossname' => 'Hex Lord Malacrass' , 'bossname_short' => 'Malacrass', 'imagename' =>  'malacrass' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '24364' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 91 , 'bossname' => 'Zul\'Jin' , 'bossname_short' => 'Zul\'Jin', 'imagename' =>  'zuljin' , 'game' =>  'wow' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '23863' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 92 , 'bossname' => 'High King Maulgar' , 'bossname_short' => 'Maulgar', 'imagename' =>  'maulgar' , 'game' =>  'wow' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '18831' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 93 , 'bossname' => 'Gruul the Dragonkiller' , 'bossname_short' => 'Gruul', 'imagename' =>  'gruul' , 'game' =>  'wow' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '19044' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 94 , 'bossname' => 'Magtheridon' , 'bossname_short' => 'Magtheridon', 'imagename' =>  'magtheridon' , 'game' =>  'wow' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '21174' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 95 , 'bossname' => 'Doom Lord Kazzak' , 'bossname_short' => 'Doom Kazzak', 'imagename' =>  'doomkazzak' , 'game' =>  'wow' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  '18728' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 96 , 'bossname' => 'Doomwalker' , 'bossname_short' => 'Doomwalker', 'imagename' =>  'doomwalker' , 'game' =>  'wow' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  '17711' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 97 , 'bossname' => 'Hydross the Unstable' , 'bossname_short' => 'Hydross', 'imagename' =>  'hydross' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21932' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 98 , 'bossname' => 'Fathom-Lord Karathress' , 'bossname_short' => 'Karathress', 'imagename' =>  'karathress' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21214' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 99 , 'bossname' => 'Morogrim Tidewalker' , 'bossname_short' => 'Morogrim', 'imagename' =>  'morogrim' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21213' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 100 , 'bossname' => 'Leotheras the Blind' , 'bossname_short' => 'Leotheras', 'imagename' =>  'leotheras' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21215' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 101 , 'bossname' => 'The Lurker Below' , 'bossname_short' => 'Lurker', 'imagename' =>  'lurker' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21217' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 102 , 'bossname' => 'Lady Vashj' , 'bossname_short' => 'Vashj', 'imagename' =>  'vashj' , 'game' =>  'wow' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  '21212' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 103 , 'bossname' => 'Al\'Ar the Phoenix God' , 'bossname_short' => 'Al\'Ar', 'imagename' =>  'alar' , 'game' =>  'wow' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  '19514' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 104 , 'bossname' => 'Void Reaver' , 'bossname_short' => 'Reaver', 'imagename' =>  'vreaver' , 'game' =>  'wow' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  '19516' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 105 , 'bossname' => 'High Astromancer Solarian' , 'bossname_short' => 'Solarian', 'imagename' =>  'solarian' , 'game' =>  'wow' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  '18805' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 106 , 'bossname' => 'Kael\'thas Sunstrider' , 'bossname_short' => 'Kaelthas', 'imagename' =>  'kaelthas' , 'game' =>  'wow' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  '19622' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 107 , 'bossname' => 'Rage Winterchill' , 'bossname_short' => 'Winterchill', 'imagename' =>  'winterchill' , 'game' =>  'wow' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  '17767' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 108 , 'bossname' => 'Anetheron' , 'bossname_short' => 'Anetheron', 'imagename' =>  'anetheron' , 'game' =>  'wow' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  '17808' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 109 , 'bossname' => 'Kaz\'rogal' , 'bossname_short' => 'Kaz\'rogal', 'imagename' =>  'kazrogal' , 'game' =>  'wow' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  '17888' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 110 , 'bossname' => 'Azgalor' , 'bossname_short' => 'Azgalor', 'imagename' =>  'azgalor' , 'game' =>  'wow' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  '17842' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 111 , 'bossname' => 'Archimonde' , 'bossname_short' => 'Archimonde', 'imagename' =>  'archimonde' , 'game' =>  'wow' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  '17968' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 112 , 'bossname' => 'High Warlord Naj\'entus' , 'bossname_short' => 'Naj\'entus', 'imagename' =>  'najentus' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22887' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 113 , 'bossname' => 'Supremus' , 'bossname_short' => 'Supremus', 'imagename' =>  'supremus' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22898' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 114 , 'bossname' => 'Akama' , 'bossname_short' => 'Akama', 'imagename' =>  'akama' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22841' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 115 , 'bossname' => 'Teron Gorefiend' , 'bossname_short' => 'Gorefiend', 'imagename' =>  'gorefiend' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22871' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 116 , 'bossname' => 'Essence of Souls' , 'bossname_short' => 'Essence', 'imagename' =>  'essence' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '23418' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 117 , 'bossname' => 'Gurtogg Bloodboil' , 'bossname_short' => 'Bloodboil', 'imagename' =>  'bloodboil' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22948' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 118 , 'bossname' => 'Mother Shahraz' , 'bossname_short' => 'Shahraz', 'imagename' =>  'shahraz' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22947' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 119 , 'bossname' => 'Illidari Council' , 'bossname_short' => 'Council', 'imagename' =>  'council' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '23426' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 120 , 'bossname' => 'Illidan Stormrage' , 'bossname_short' => 'Illidan', 'imagename' =>  'illidan' , 'game' =>  'wow' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  '22917' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 121 , 'bossname' => 'Kalecgos' , 'bossname_short' => 'Kalecgos', 'imagename' =>  'kalecgos' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '24850' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 122 , 'bossname' => 'Brutallus' , 'bossname_short' => 'Brutallus', 'imagename' =>  'brutallus' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '24882' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 123 , 'bossname' => 'Felmyst' , 'bossname_short' => 'Felmyst', 'imagename' =>  'felmyst' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '25038' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 124 , 'bossname' => 'Alythess & Sacrolash' , 'bossname_short' => 'Twins', 'imagename' =>  'fetwins' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '25166' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 125 , 'bossname' => 'M\'uru' , 'bossname_short' => 'M\'uru', 'imagename' =>  'muru' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '25741' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 126 , 'bossname' => 'Kil\'jaeden' , 'bossname_short' => 'Kil\'jaeden', 'imagename' =>  'kiljaeden' , 'game' =>  'wow' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  '25315' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 127 , 'bossname' => 'Anub\'Rekhan (10)' , 'bossname_short' => 'Anub\'Rekhan (10)', 'imagename' =>  'anubrekhan_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15956' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 128 , 'bossname' => 'Grand Widow Faerlina (10)' , 'bossname_short' => 'Faerlina (10)', 'imagename' =>  'faerlina_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15953' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 129 , 'bossname' => 'Maexxna (10)' , 'bossname_short' => 'Maexxna (10)', 'imagename' =>  'maexxna_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15952' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 130 , 'bossname' => 'Noth the Plaguebringer (10)' , 'bossname_short' => 'Noth (10)', 'imagename' =>  'noth_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15954' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 131 , 'bossname' => 'Heigan the Unclean (10)' , 'bossname_short' => 'Heigan (10)', 'imagename' =>  'heigan_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15936' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 132 , 'bossname' => 'Loatheb (10)' , 'bossname_short' => 'Loatheb (10)', 'imagename' =>  'loatheb_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '16011' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 133 , 'bossname' => 'Patchwerk (10)' , 'bossname_short' => 'Patchwerk (10)', 'imagename' =>  'patchwerk_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '16028' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 134 , 'bossname' => 'Grobbulus (10)' , 'bossname_short' => 'Grobbulus (10)', 'imagename' =>  'grobbulus_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15931' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 135 , 'bossname' => 'Gluth (10)' , 'bossname_short' => 'Gluth (10)', 'imagename' =>  'gluth_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15932' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 136 , 'bossname' => 'Thaddius (10)' , 'bossname_short' => 'Thaddius (10)', 'imagename' =>  'thaddius_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15928' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 137 , 'bossname' => 'Instructor Razuvious (10)' , 'bossname_short' => 'Razuvious (10)', 'imagename' =>  'razuvious_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '16061' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 138 , 'bossname' => 'Gothik the Harvester (10)' , 'bossname_short' => 'Gothik (10)', 'imagename' =>  'gothik_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '16060' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 139 , 'bossname' => 'Four Horsemen (10)' , 'bossname_short' => 'Korth\'azz (10)', 'imagename' =>  'horsemen_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '16064' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 140 , 'bossname' => 'Sapphiron (10)' , 'bossname_short' => 'Sapphiron (10)', 'imagename' =>  'sapphiron_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15989' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 141 , 'bossname' => 'Kel\'Thuzad (10)' , 'bossname_short' => 'Kel\'Thuzad (10)', 'imagename' =>  'kelthuzad_10' , 'game' =>  'wow' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  '15990' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 142 , 'bossname' => 'Anub\'Rekhan (25)' , 'bossname_short' => 'Anub\'Rekhan (25)', 'imagename' =>  'anubrekhan_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15956' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 143 , 'bossname' => 'Grand Widow Faerlina (25)' , 'bossname_short' => 'Faerlina (25)', 'imagename' =>  'faerlina_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15953' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 144 , 'bossname' => 'Maexxna (25)' , 'bossname_short' => 'Maexxna (25)', 'imagename' =>  'maexxna_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15952' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 145 , 'bossname' => 'Noth the Plaguebringer (25)' , 'bossname_short' => 'Noth (25)', 'imagename' =>  'noth_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15954' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 146 , 'bossname' => 'Heigan the Unclean (25)' , 'bossname_short' => 'Heigan (25)', 'imagename' =>  'heigan_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15936' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 147 , 'bossname' => 'Loatheb (25)' , 'bossname_short' => 'Loatheb (25)', 'imagename' =>  'loatheb_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '16011' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 148 , 'bossname' => 'Patchwerk (25)' , 'bossname_short' => 'Patchwerk (25)', 'imagename' =>  'patchwerk_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '16028' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 149 , 'bossname' => 'Grobbulus (25)' , 'bossname_short' => 'Grobbulus (25)', 'imagename' =>  'grobbulus_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15931' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 150 , 'bossname' => 'Gluth (25)' , 'bossname_short' => 'Gluth (25)', 'imagename' =>  'gluth_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15932' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 151 , 'bossname' => 'Thaddius (25)' , 'bossname_short' => 'Thaddius (25)', 'imagename' =>  'thaddius_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15928' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 152 , 'bossname' => 'Instructor Razuvious (25)' , 'bossname_short' => 'Razuvious (25)', 'imagename' =>  'razuvious_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '16061' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 153 , 'bossname' => 'Gothik the Harvester (25)' , 'bossname_short' => 'Gothik (25)', 'imagename' =>  'gothik_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '16060' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 154 , 'bossname' => 'Four Horsemen (25)' , 'bossname_short' => 'Korth\'azz (25)', 'imagename' =>  'horsemen_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '16064' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 155 , 'bossname' => 'Sapphiron (25)' , 'bossname_short' => 'Sapphiron (25)', 'imagename' =>  'sapphiron_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15989' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 156 , 'bossname' => 'Kel\'Thuzad (25)' , 'bossname_short' => 'Kel\'Thuzad (25)', 'imagename' =>  'kelthuzad_25' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '15990' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 157 , 'bossname' => 'Malygos (10)' , 'bossname_short' => 'Malygos (10)', 'imagename' =>  'malygos_10' , 'game' =>  'wow' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  '28859' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 158 , 'bossname' => 'Archavon the Stone Watcher (10)' , 'bossname_short' => 'Archavon (10)', 'imagename' =>  'archavon_10' , 'game' =>  'wow' , 'zoneid' =>  22 , 'type' =>  'npc'  , 'webid' =>  '31125' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 159 , 'bossname' => 'Emalon the Storm Watcher (10)' , 'bossname_short' => 'Emalon (10)', 'imagename' =>  'emalon_10' , 'game' =>  'wow' , 'zoneid' =>  22 , 'type' =>  'npc'  , 'webid' =>  '33993' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 160 , 'bossname' => 'Archavon the Stone Watcher (25)' , 'bossname_short' => 'Archavon (25)', 'imagename' =>  'archavon_25' , 'game' =>  'wow' , 'zoneid' =>  23 , 'type' =>  'npc'  , 'webid' =>  '31125' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 161 , 'bossname' => 'Emalon the Storm Watcher (25)' , 'bossname_short' => 'Emalon (25)', 'imagename' =>  'emalon_25' , 'game' =>  'wow' , 'zoneid' =>  23 , 'type' =>  'npc'  , 'webid' =>  '33993' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 162 , 'bossname' => 'Sartharion the Onyx Guardian No dragons (10)' , 'bossname_short' => 'Sartarion 0d (10)', 'imagename' =>  'sartharion_0d_10' , 'game' =>  'wow' , 'zoneid' =>  24 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 163 , 'bossname' => 'Sartharion the Onyx Guardian One dragon (10)' , 'bossname_short' => 'Sartarion 1d (10)', 'imagename' =>  'sartharion_1d_10' , 'game' =>  'wow' , 'zoneid' =>  24 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 164 , 'bossname' => 'Sartharion the Onyx Guardian Two dragons (10)' , 'bossname_short' => 'Sartarion 2d (10)', 'imagename' =>  'sartharion_2d_10' , 'game' =>  'wow' , 'zoneid' =>  24 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 165 , 'bossname' => 'Sartharion the Onyx Guardian Three dragons (10)' , 'bossname_short' => 'Sartarion 3d (10)', 'imagename' =>  'sartharion_3d_10' , 'game' =>  'wow' , 'zoneid' =>  24 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 166 , 'bossname' => 'Sartharion the Onyx Guardian No dragons (25)' , 'bossname_short' => 'Sartarion 0d (25)', 'imagename' =>  'sartharion_0d_25' , 'game' =>  'wow' , 'zoneid' =>  25 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 167 , 'bossname' => 'Sartharion the Onyx Guardian One dragon (25)' , 'bossname_short' => 'Sartarion 1d (25)', 'imagename' =>  'sartharion_1d_25' , 'game' =>  'wow' , 'zoneid' =>  25 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 168 , 'bossname' => 'Sartharion the Onyx Guardian Two dragons (25)' , 'bossname_short' => 'Sartarion 2d (25)', 'imagename' =>  'sartharion_2d_25' , 'game' =>  'wow' , 'zoneid' =>  25 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 169 , 'bossname' => 'Sartharion the Onyx Guardian Three dragons (25)' , 'bossname_short' => 'Sartarion 3d (25)', 'imagename' =>  'sartharion_3d_25' , 'game' =>  'wow' , 'zoneid' =>  25 , 'type' =>  'npc'  , 'webid' =>  '28860' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 170 , 'bossname' => 'Malygos (25)' , 'bossname_short' => 'Malygos (25)', 'imagename' =>  'malygos_25' , 'game' =>  'wow' , 'zoneid' =>  27 , 'type' =>  'npc'  , 'webid' =>  '28859' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 171 , 'bossname' => 'Flame Leviathan (10)' , 'bossname_short' => 'Leviathan (10)', 'imagename' =>  'leviathan_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33113' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 172 , 'bossname' => 'Razorscale (10)' , 'bossname_short' => 'Razorscale (10)', 'imagename' =>  'razorscale_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33186' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 173 , 'bossname' => 'XT-002 Deconstructor (10)' , 'bossname_short' => 'Deconstructor (10)', 'imagename' =>  'deconstructor_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33293' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 174 , 'bossname' => 'Ignis the Furnace Master (10)' , 'bossname_short' => 'Ignis (10)', 'imagename' =>  'ignis_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33118' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 175 , 'bossname' => 'Assembly of Iron (10)' , 'bossname_short' => 'Assembly (10)', 'imagename' =>  'assembly_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 176 , 'bossname' => 'Kologarn (10)' , 'bossname_short' => 'Kologarn (10)', 'imagename' =>  'kologarn_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '32930' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 177 , 'bossname' => 'Auriaya (10)' , 'bossname_short' => 'Auriaya (10)', 'imagename' =>  'auriaya_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33515' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 178 , 'bossname' => 'Mimiron (10)' , 'bossname_short' => 'Mimiron (10)', 'imagename' =>  'mimiron_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 179 , 'bossname' => 'Hodir (10)' , 'bossname_short' => 'Hodir (10)', 'imagename' =>  'hodir_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33213' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 180 , 'bossname' => 'Thorim (10)' , 'bossname_short' => 'Thorim (10)', 'imagename' =>  'thorim_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33413' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 181 , 'bossname' => 'Freya (10)' , 'bossname_short' => 'Freya (10)', 'imagename' =>  'freya_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33410' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 182 , 'bossname' => 'General Vezax (10)' , 'bossname_short' => 'Vezax (10)', 'imagename' =>  'vezax_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33271' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 183 , 'bossname' => 'Yogg-Saron (10)' , 'bossname_short' => 'Yogg-Saron (10)', 'imagename' =>  'yoggsaron_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '33288' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 184 , 'bossname' => 'Algalon (10)' , 'bossname_short' => 'Algalon (10)', 'imagename' =>  'algalon_10' , 'game' =>  'wow' , 'zoneid' =>  28 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 185 , 'bossname' => 'Flame Leviathan (25)' , 'bossname_short' => 'Leviathan (25)', 'imagename' =>  'leviathan_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33113' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 186 , 'bossname' => 'Razorscale (25)' , 'bossname_short' => 'Razorscale (25)', 'imagename' =>  'razorscale_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33186' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 187 , 'bossname' => 'XT-002 Deconstructor (25)' , 'bossname_short' => 'Deconstructor (25)', 'imagename' =>  'deconstructor_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33293' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 188 , 'bossname' => 'Ignis the Furnace Master (25)' , 'bossname_short' => 'Ignis (25)', 'imagename' =>  'ignis_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33118' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 189 , 'bossname' => 'Assembly of Iron (25)' , 'bossname_short' => 'Assembly (25)', 'imagename' =>  'assembly_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 190 , 'bossname' => 'Kologarn (25)' , 'bossname_short' => 'Kologarn (25)', 'imagename' =>  'kologarn_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '32930' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 191 , 'bossname' => 'Auriaya (25)' , 'bossname_short' => 'Auriaya (25)', 'imagename' =>  'auriaya_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33515' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 192 , 'bossname' => 'Mimiron (25)' , 'bossname_short' => 'Mimiron (25)', 'imagename' =>  'mimiron_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 193 , 'bossname' => 'Hodir (25)' , 'bossname_short' => 'Hodir (25)', 'imagename' =>  'hodir_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33213' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 194 , 'bossname' => 'Thorim (25)' , 'bossname_short' => 'Thorim (25)', 'imagename' =>  'thorim_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33413' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 195 , 'bossname' => 'Freya (25)' , 'bossname_short' => 'Freya (25)', 'imagename' =>  'freya_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33410' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 196 , 'bossname' => 'General Vezax (25)' , 'bossname_short' => 'Vezax (25)', 'imagename' =>  'vezax_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33271' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 197 , 'bossname' => 'Yogg-Saron (25)' , 'bossname_short' => 'Yogg-Saron (25)', 'imagename' =>  'yoggsaron_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '33288' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 198 , 'bossname' => 'Algalon (25)' , 'bossname_short' => 'Algalon (25)', 'imagename' =>  'algalon_25' , 'game' =>  'wow' , 'zoneid' =>  29 , 'type' =>  'npc'  , 'webid' =>  '32867' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 199 , 'bossname' => 'Northrend Beasts (10)' , 'bossname_short' => 'Beasts (10)', 'imagename' =>  'northrend_beasts_10' , 'game' =>  'wow' , 'zoneid' =>  30 , 'type' =>  'npc'  , 'webid' =>  '35470' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 200 , 'bossname' => 'Faction Champions (10)' , 'bossname_short' => 'Champions (10)', 'imagename' =>  'faction_champions_10' , 'game' =>  'wow' , 'zoneid' =>  30 , 'type' =>  'object'  , 'webid' =>  '195631' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 201 , 'bossname' => 'Lord Jaraxxus (10)' , 'bossname_short' => 'Jaraxxus (10)', 'imagename' =>  'lord_jaraxxus_10' , 'game' =>  'wow' , 'zoneid' =>  30 , 'type' =>  'npc'  , 'webid' =>  '34780' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 202 , 'bossname' => 'Twin Valkyrs (10)' , 'bossname_short' => 'Valkyrs (10)', 'imagename' =>  'twin_valkyrs_10' , 'game' =>  'wow' , 'zoneid' =>  30 , 'type' =>  'npc'  , 'webid' =>  '34497' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 203 , 'bossname' => 'Anub Arak (10)' , 'bossname_short' => 'Anub (10)', 'imagename' =>  'anub_arak_10' , 'game' =>  'wow' , 'zoneid' =>  30 , 'type' =>  'npc'  , 'webid' =>  '34564' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 204 , 'bossname' => 'Northrend Beasts (25)' , 'bossname_short' => 'Beasts (25)', 'imagename' =>  'northrend_beasts_25' , 'game' =>  'wow' , 'zoneid' =>  31 , 'type' =>  'npc'  , 'webid' =>  '35470' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 205 , 'bossname' => 'Faction Champions (25)' , 'bossname_short' => 'Champions (25)', 'imagename' =>  'faction_champions_25' , 'game' =>  'wow' , 'zoneid' =>  31 , 'type' =>  'object'  , 'webid' =>  '195631' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 206 , 'bossname' => 'Lord Jaraxxus (25)' , 'bossname_short' => 'Jaraxxus (25)', 'imagename' =>  'lord_jaraxxus_25' , 'game' =>  'wow' , 'zoneid' =>  31 , 'type' =>  'npc'  , 'webid' =>  '34780' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 207 , 'bossname' => 'Twin Valkyrs (25)' , 'bossname_short' => 'Valkyrs (25)', 'imagename' =>  'twin_valkyrs_25' , 'game' =>  'wow' , 'zoneid' =>  31 , 'type' =>  'npc'  , 'webid' =>  '34497' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 208 , 'bossname' => 'Anub Arak (25)' , 'bossname_short' => 'Anub (25)', 'imagename' =>  'anub_arak_25' , 'game' =>  'wow' , 'zoneid' =>  31 , 'type' =>  'npc'  , 'webid' =>  '34564' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 209 , 'bossname' => 'Northrend Beasts (10)' , 'bossname_short' => 'Beasts (10)', 'imagename' =>  'northrend_beasts_10_hc' , 'game' =>  'wow' , 'zoneid' =>  32 , 'type' =>  'npc'  , 'webid' =>  '35470' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 210 , 'bossname' => 'Faction Champions (10)' , 'bossname_short' => 'Champions (10)', 'imagename' =>  'faction_champions_10_hc' , 'game' =>  'wow' , 'zoneid' =>  32 , 'type' =>  'object'  , 'webid' =>  '195631' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 211 , 'bossname' => 'Lord Jaraxxus (10)' , 'bossname_short' => 'Jaraxxus (10)', 'imagename' =>  'lord_jaraxxus_10_hc' , 'game' =>  'wow' , 'zoneid' =>  32 , 'type' =>  'npc'  , 'webid' =>  '34780' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 212 , 'bossname' => 'Twin Valkyrs (10)' , 'bossname_short' => 'Valkyrs (10)', 'imagename' =>  'twin_valkyrs_10_hc' , 'game' =>  'wow' , 'zoneid' =>  32 , 'type' =>  'npc'  , 'webid' =>  '34497' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 213 , 'bossname' => 'Anub Arak (10)' , 'bossname_short' => 'Anub (10)', 'imagename' =>  'anub_arak_10_hc' , 'game' =>  'wow' , 'zoneid' =>  32 , 'type' =>  'npc'  , 'webid' =>  '34564' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 214 , 'bossname' => 'Northrend Beasts (25)' , 'bossname_short' => 'Beasts (25)', 'imagename' =>  'northrend_beasts_25_hc' , 'game' =>  'wow' , 'zoneid' =>  33 , 'type' =>  'npc'  , 'webid' =>  '35470' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 215 , 'bossname' => 'Faction Champions (25)' , 'bossname_short' => 'Champions (25)', 'imagename' =>  'faction_champions_25_hc' , 'game' =>  'wow' , 'zoneid' =>  33 , 'type' =>  'object'  , 'webid' =>  '195631' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 216 , 'bossname' => 'Lord Jaraxxus (25)' , 'bossname_short' => 'Jaraxxus (25)', 'imagename' =>  'lord_jaraxxus_25_hc' , 'game' =>  'wow' , 'zoneid' =>  33 , 'type' =>  'npc'  , 'webid' =>  '34780' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 217 , 'bossname' => 'Twin Valkyrs (25)' , 'bossname_short' => 'Valkyrs (25)', 'imagename' =>  'twin_valkyrs_25_hc' , 'game' =>  'wow' , 'zoneid' =>  33 , 'type' =>  'npc'  , 'webid' =>  '34497' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 218 , 'bossname' => 'Anub Arak (25)' , 'bossname_short' => 'Anub (25)', 'imagename' =>  'anub_arak_25_hc' , 'game' =>  'wow' , 'zoneid' =>  33 , 'type' =>  'npc'  , 'webid' =>  '34564' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 219 , 'bossname' => 'Onyxia (10)' , 'bossname_short' => 'Onyxia (10)', 'imagename' =>  'onyxia_10' , 'game' =>  'wow' , 'zoneid' =>  34 , 'type' =>  'npc'  , 'webid' =>  '10184' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 220 , 'bossname' => 'Onyxia (10)' , 'bossname_short' => 'Onyxia (10)', 'imagename' =>  'onyxia_10' , 'game' =>  'wow' , 'zoneid' =>  34 , 'type' =>  'npc'  , 'webid' =>  '10184' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 221 , 'bossname' => 'Onyxia (25)' , 'bossname_short' => 'Onyxia (25)', 'imagename' =>  'onyxia_25' , 'game' =>  'wow' , 'zoneid' =>  35 , 'type' =>  'npc'  , 'webid' =>  '10184' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 222 , 'bossname' => 'Onyxia (25)' , 'bossname_short' => 'Onyxia (25)', 'imagename' =>  'onyxia_25' , 'game' =>  'wow' , 'zoneid' =>  35 , 'type' =>  'npc'  , 'webid' =>  '10184' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 223 , 'bossname' => 'Lord Marrowgar (10)' , 'bossname_short' => 'Marrowgar (10)', 'imagename' =>  'lord_marrowgar_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36612' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 224 , 'bossname' => 'Lord Marrowgar (10HM)' , 'bossname_short' => 'Marrowgar (10HM)', 'imagename' =>  'lord_marrowgar_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36612' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 225 , 'bossname' => 'Lady Deathwhisper (10)' , 'bossname_short' => 'Deathwisper (10)', 'imagename' =>  'lady_deathwhisper_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36855' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 226 , 'bossname' => 'Lady Deathwhisper (10HM)' , 'bossname_short' => 'Deathwisper (10HM)', 'imagename' =>  'lady_deathwhisper_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36855' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 227 , 'bossname' => 'Icecrown Gunship Battle (10)' , 'bossname_short' => 'Gunship (10)', 'imagename' =>  'icecrown_gunship_battle_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '201873' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 228 , 'bossname' => 'Icecrown Gunship Battle (10HM)' , 'bossname_short' => 'Gunship (10HM)', 'imagename' =>  'icecrown_gunship_battle_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '201873' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 229 , 'bossname' => 'Deathbringer Saurfang (10)' , 'bossname_short' => 'Saurfang (10)', 'imagename' =>  'deathbringer_saurfang_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37813' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 230 , 'bossname' => 'Deathbringer Saurfang (10HM)' , 'bossname_short' => 'Saurfang (10HM)', 'imagename' =>  'deathbringer_saurfang_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37813' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 231 , 'bossname' => 'Festergut (10)' , 'bossname_short' => 'Festergut (10)', 'imagename' =>  'festergut_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36626' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 232 , 'bossname' => 'Festergut (10HM)' , 'bossname_short' => 'Festergut (10HM)', 'imagename' =>  'festergut_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36626' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 233 , 'bossname' => 'Rotface (10)' , 'bossname_short' => 'Rotface (10)', 'imagename' =>  'rotface_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36627' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 234 , 'bossname' => 'Rotface (10HM)' , 'bossname_short' => 'Rotface (10HM)', 'imagename' =>  'rotface_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36627' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 235 , 'bossname' => 'Professor Putricide (10)' , 'bossname_short' => 'Putricide (10)', 'imagename' =>  'professor_putricide_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36678' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 236 , 'bossname' => 'Professor Putricide (10HM)' , 'bossname_short' => 'Putricide (10HM)', 'imagename' =>  'professor_putricide_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36678' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 237 , 'bossname' => 'Blood Princes (10)' , 'bossname_short' => 'Princes (10)', 'imagename' =>  'blood_princes_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37970' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 238 , 'bossname' => 'Blood Princes (10HM)' , 'bossname_short' => 'Princes (10HM)', 'imagename' =>  'blood_princes_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37970' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 239 , 'bossname' => 'Blood-Queen Lana thel (10)' , 'bossname_short' => 'Lana thel (10)', 'imagename' =>  'blood_queen_lana_thel_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '38004' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 240 , 'bossname' => 'Blood-Queen Lana thel (10HM)' , 'bossname_short' => 'Lana thel (10HM)', 'imagename' =>  'blood_queen_lana_thel_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '38004' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 241 , 'bossname' => 'Valithria Dreamwalker (10)' , 'bossname_short' => 'Valithria (10)', 'imagename' =>  'valithria_dreamwalker_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36789' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 242 , 'bossname' => 'Valithria Dreamwalker (10HM)' , 'bossname_short' => 'Valithria (10HM)', 'imagename' =>  'valithria_dreamwalker_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '36789' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 243 , 'bossname' => 'Sindragosa (10)' , 'bossname_short' => 'Sindragosa (10)', 'imagename' =>  'sindragosa_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37755' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 244 , 'bossname' => 'Sindragosa (10HM)' , 'bossname_short' => 'Sindragosa (10HM)', 'imagename' =>  'sindragosa_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '37755' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 245 , 'bossname' => 'The Lich King (10)' , 'bossname_short' => 'Lich King (10)', 'imagename' =>  'the_lich_king_10' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '29983' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 246 , 'bossname' => 'The Lich King (10HM)' , 'bossname_short' => 'Lich King (10HM)', 'imagename' =>  'the_lich_king_10_hc' , 'game' =>  'wow' , 'zoneid' =>  36 , 'type' =>  'npc'  , 'webid' =>  '29983' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 247 , 'bossname' => 'Lord Marrowgar (25)' , 'bossname_short' => 'Marrowgar (25)', 'imagename' =>  'lord_marrowgar_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36612' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 248 , 'bossname' => 'Lord Marrowgar (25HM)' , 'bossname_short' => 'Marrowgar (25HM)', 'imagename' =>  'lord_marrowgar_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36612' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 249 , 'bossname' => 'Lady Deathwhisper (25)' , 'bossname_short' => 'Deathwisper (25)', 'imagename' =>  'lady_deathwhisper_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36855' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 250 , 'bossname' => 'Lady Deathwhisper (25HM)' , 'bossname_short' => 'Deathwisper (25HM)', 'imagename' =>  'lady_deathwhisper_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36855' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 251 , 'bossname' => 'Icecrown Gunship Battle (25)' , 'bossname_short' => 'Gunship (25)', 'imagename' =>  'icecrown_gunship_battle_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '201873' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 252 , 'bossname' => 'Icecrown Gunship Battle (25HM)' , 'bossname_short' => 'Gunship (25HM)', 'imagename' =>  'icecrown_gunship_battle_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '201873' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 253 , 'bossname' => 'Deathbringer Saurfang (25)' , 'bossname_short' => 'Saurfang (25)', 'imagename' =>  'deathbringer_saurfang_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37813' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 254 , 'bossname' => 'Deathbringer Saurfang (25HM)' , 'bossname_short' => 'Saurfang (25HM)', 'imagename' =>  'deathbringer_saurfang_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37813' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 255 , 'bossname' => 'Festergut (25)' , 'bossname_short' => 'Festergut (25)', 'imagename' =>  'festergut_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36626' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 256 , 'bossname' => 'Festergut (25HM)' , 'bossname_short' => 'Festergut (25HM)', 'imagename' =>  'festergut_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36626' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 257 , 'bossname' => 'Rotface (25)' , 'bossname_short' => 'Rotface (25)', 'imagename' =>  'rotface_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36627' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 258 , 'bossname' => 'Rotface (25HM)' , 'bossname_short' => 'Rotface (25HM)', 'imagename' =>  'rotface_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36627' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 259 , 'bossname' => 'Professor Putricide (25)' , 'bossname_short' => 'Putricide (25)', 'imagename' =>  'professor_putricide_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36678' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 260 , 'bossname' => 'Professor Putricide (25HM)' , 'bossname_short' => 'Putricide (25HM)', 'imagename' =>  'professor_putricide_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36678' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 261 , 'bossname' => 'Blood Princes (25)' , 'bossname_short' => 'Princes (25)', 'imagename' =>  'blood_princes_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37970' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 262 , 'bossname' => 'Blood Princes (25HM)' , 'bossname_short' => 'Princes (25HM)', 'imagename' =>  'blood_princes_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37970' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 263 , 'bossname' => 'Blood-Queen Lana thel (25)' , 'bossname_short' => 'Lana thel (25)', 'imagename' =>  'blood_queen_lana_thel_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '38004' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 264 , 'bossname' => 'Blood-Queen Lana thel (25HM)' , 'bossname_short' => 'Lana thel (25HM)', 'imagename' =>  'blood_queen_lana_thel_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '38004' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 265 , 'bossname' => 'Valithria Dreamwalker (25)' , 'bossname_short' => 'Valithria (25)', 'imagename' =>  'valithria_dreamwalker_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36789' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 266 , 'bossname' => 'Valithria Dreamwalker (25HM)' , 'bossname_short' => 'Valithria (25HM)', 'imagename' =>  'valithria_dreamwalker_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '36789' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 267 , 'bossname' => 'Sindragosa (25)' , 'bossname_short' => 'Sindragosa (25)', 'imagename' =>  'sindragosa_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37755' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 268 , 'bossname' => 'Sindragosa (25HM)' , 'bossname_short' => 'Sindragosa (25HM)', 'imagename' =>  'sindragosa_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '37755' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 269 , 'bossname' => 'The Lich King (25)' , 'bossname_short' => 'Lich King (25)', 'imagename' =>  'the_lich_king_25' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '29983' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 270 , 'bossname' => 'The Lich King (25HM)' , 'bossname_short' => 'Lich King (25HM)', 'imagename' =>  'the_lich_king_25_hc' , 'game' =>  'wow' , 'zoneid' =>  37 , 'type' =>  'npc'  , 'webid' =>  '29983' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 271 , 'bossname' => 'Halion (10)' , 'bossname_short' => 'Halion (10)', 'imagename' =>  'halion_10' , 'game' =>  'wow' , 'zoneid' =>  38 , 'type' =>  'npc'  , 'webid' =>  '39863' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 272 , 'bossname' => 'Halion (10HM)' , 'bossname_short' => 'Halion (10HM)', 'imagename' =>  'halion_10_hc' , 'game' =>  'wow' , 'zoneid' =>  38 , 'type' =>  'npc'  , 'webid' =>  '39863' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 273 , 'bossname' => 'Halion (25)' , 'bossname_short' => 'Halion (25)', 'imagename' =>  'Halion_25' , 'game' =>  'wow' , 'zoneid' =>  39 , 'type' =>  'npc'  , 'webid' =>  '39863' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 274 , 'bossname' => 'Halion (25HM)' , 'bossname_short' => 'Halion (25HM)', 'imagename' =>  'Halion_25_hc' , 'game' =>  'wow' , 'zoneid' =>  39 , 'type' =>  'npc'  , 'webid' =>  '39863' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
								
	
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );

		
		
		
		
	}
}

?>