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
if (!defined('IN_PHPBB'))
{
	exit;
}
/************************
 * bbdkp main wow Installer 
 * 
 *
 * @param string $bbdkp_table_prefix
 */
function install_wow($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
    $sql_ary = array();

    // class : note these eqdkp class_ids are changed in release 1.1 to follow blizz
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Warrior', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Rogue', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Hunter', 'class_armor_type' => 'Mail' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Paladin', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Shaman', 'class_armor_type' => 'Mail' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Druid', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Warlock', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Mage', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Priest', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 80 );
    $sql_ary[] = array('class_id' => '10', 'class_name' => 'Death Knight', 'class_armor_type' => 'Plate' , 'class_min_level' => 55 , 'class_max_level'  => 80 );
        
    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Alliance' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Horde' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
    // races : note we use blizz id
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 0, 'race_name' => 'Unknown' , 'race_faction_id' => 0 );
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Human' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Orc' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Dwarf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Night Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'Undead' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Tauren' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 7, 'race_name' => 'Gnome' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 8, 'race_name' => 'Troll' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 10, 'race_name' => 'Blood Elf' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 11, 'race_name' => 'Draenei' , 'race_faction_id' => 1 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);

   	unset ($sql_ary); 
    // index page recruitment status
    $sql_ary = array();
    $sql_ary[] = array('setting' => 'Death Knight (Blood)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Death Knight (Frost)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Death Knight (Unholy)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Druid (Resto)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Druid (Feral)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Druid (Balance)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Hunter', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Priest (Shadow)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Priest (Holy)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Priest (Disc)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Rogue', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Shaman (Enh)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Shaman (Resto)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Shaman (Ele)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Warlock', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Mage', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Paladin (Holy)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Paladin (Ret)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Paladin (Prot)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Warrior (Tank)', 'value' => 'Closed'  );
    $sql_ary[] = array('setting' => 'Warrior (DPS)', 'value' => 'Closed'  );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'indexpage', $sql_ary);
    
	unset ($sql_ary); 
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config'))
	{
	    $sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_config';
		$db->sql_query($sql);
		
		$sql_ary[] = array('config_name'	=> 'bossInfo', 'config_value'	=> 'rname' );
		$sql_ary[] = array('config_name'	=> 'dynBoss', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'dynZone', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'nameDelim', 'config_value'	=> '-' );
		$sql_ary[] = array('config_name'	=> 'noteDelim', 'config_value'	=> ',' );
		$sql_ary[] = array('config_name'	=> 'showSB', 'config_value'	=> '1' );
		$sql_ary[] = array('config_name'	=> 'source', 'config_value'	=> 'database' );
		$sql_ary[] = array('config_name'	=> 'style', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'tables', 'config_value'	=> 'bbeqdkp' );
		$sql_ary[] = array('config_name'	=> 'zhiType', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'zoneInfo', 'config_value'	=> 'rname' );
		$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config' , $sql_ary);
			
    	// Boss names     
        $sql_ary = array();
        $sql_ary[] = array('config_name' =>  'pb_akama', 'config_value' => 'Akama'  );
    	$sql_ary[] = array('config_name' =>  'pb_alar', 'config_value' => 'Al\'Ar the Phoenix God, Al\'Ar'  );
    	$sql_ary[] = array('config_name' =>  'pb_anetheron', 'config_value' => 'Anetheron'  );
    	$sql_ary[] = array('config_name' =>  'pb_anubrekhan', 'config_value' => 'Anub\'Rekhan'  );
    	$sql_ary[] = array('config_name' =>  'pb_aran', 'config_value' => 'Shade of Aran, Aran'  );
    	$sql_ary[] = array('config_name' =>  'pb_archimonde', 'config_value' => 'Archimonde'  );
    	$sql_ary[] = array('config_name' =>  'pb_arlokk', 'config_value' => 'High Priestess Arlokk, Arlokk'  );
    	$sql_ary[] = array('config_name' =>  'pb_attumen', 'config_value' => 'Attumen the Huntsman, Attumen'  );
    	$sql_ary[] = array('config_name' =>  'pb_ayamiss', 'config_value' => 'Ayamiss the Hunter, Ayamiss'  );
    	$sql_ary[] = array('config_name' =>  'pb_azgalor', 'config_value' => 'Azgalor'  );
    	$sql_ary[] = array('config_name' =>  'pb_azuregos', 'config_value' => 'Azuregos'  );
    	$sql_ary[] = array('config_name' =>  'pb_blaumeux', 'config_value' => 'Lady Blaumeux, Blaumeux'  );
    	$sql_ary[] = array('config_name' =>  'pb_bloodboil', 'config_value' => 'Gurtogg Bloodboil, Bloodboil'  );
    	$sql_ary[] = array('config_name' =>  'pb_buru', 'config_value' => 'Buru the Gorger, Buru'  );
    	$sql_ary[] = array('config_name' =>  'pb_chess', 'config_value' => 'Chess Event, Chess'  );
    	$sql_ary[] = array('config_name' =>  'pb_chromaggus', 'config_value' => 'Chromaggus'  );
    	$sql_ary[] = array('config_name' =>  'pb_council', 'config_value' => 'Illidari Council, Council'  );
    	$sql_ary[] = array('config_name' =>  'pb_cthun', 'config_value' => 'C\'Thun'  );
    	$sql_ary[] = array('config_name' =>  'pb_curator', 'config_value' => 'The Curator, Curator'  );
    	$sql_ary[] = array('config_name' =>  'pb_doomkazzak', 'config_value' => 'Doom Lord Kazzak, Doom Kazzak'  );
    	$sql_ary[] = array('config_name' =>  'pb_doomwalker', 'config_value' => 'Doomwalker'  );
    	$sql_ary[] = array('config_name' =>  'pb_ebonroc', 'config_value' => 'Ebonroc'  );
    	$sql_ary[] = array('config_name' =>  'pb_emeriss', 'config_value' => 'Emeriss'  );
    	$sql_ary[] = array('config_name' =>  'pb_essence', 'config_value' => 'Essence of Souls, Essence'  );
    	$sql_ary[] = array('config_name' =>  'pb_faerlina', 'config_value' => 'Grand Widow Faerlina, Faerlina'  );
    	$sql_ary[] = array('config_name' =>  'pb_fankriss', 'config_value' => 'Fankriss the Unyielding, Fankriss'  );
    	$sql_ary[] = array('config_name' =>  'pb_firemaw', 'config_value' => 'Firemaw'  );
    	$sql_ary[] = array('config_name' =>  'pb_flamegor', 'config_value' => 'Flamegor'  );
    	$sql_ary[] = array('config_name' =>  'pb_gahzranka', 'config_value' => 'Gahz\'ranka'  );
    	$sql_ary[] = array('config_name' =>  'pb_garr', 'config_value' => 'Garr'  );
    	$sql_ary[] = array('config_name' =>  'pb_geddon', 'config_value' => 'Baron Geddon, Geddon'  );
    	$sql_ary[] = array('config_name' =>  'pb_gehennas', 'config_value' => 'Gehennas'  );
    	$sql_ary[] = array('config_name' =>  'pb_gluth', 'config_value' => 'Gluth'  );
    	$sql_ary[] = array('config_name' =>  'pb_golemagg', 'config_value' => 'Golemagg the Incinerator, Golemagg'  );
    	$sql_ary[] = array('config_name' =>  'pb_gorefiend', 'config_value' => 'Teron Gorefiend, Gorefiend'  );
    	$sql_ary[] = array('config_name' =>  'pb_gothik', 'config_value' => 'Gothik the Harvester, Gothik'  );
    	$sql_ary[] = array('config_name' =>  'pb_grilek', 'config_value' => 'Gri\'lek'  );
    	$sql_ary[] = array('config_name' =>  'pb_grobbulus', 'config_value' => 'Grobbulus'  );
    	$sql_ary[] = array('config_name' =>  'pb_gruul', 'config_value' => 'Gruul the Dragonkiller, Gruul'  );
    	$sql_ary[] = array('config_name' =>  'pb_hakkar', 'config_value' => 'Hakkar'  );
    	$sql_ary[] = array('config_name' =>  'pb_hazzarah', 'config_value' => 'Hazza\'rah'  );
    	$sql_ary[] = array('config_name' =>  'pb_heigan', 'config_value' => 'Heigan the Unclean, Heigan'  );
    	$sql_ary[] = array('config_name' =>  'pb_huhuran', 'config_value' => 'Princess Huhuran, Huhuran'  );
    	$sql_ary[] = array('config_name' =>  'pb_hydross', 'config_value' => 'Hydross the Unstable, Hydross'  );
    	$sql_ary[] = array('config_name' =>  'pb_illhoof', 'config_value' => 'Terestian Illhoof'  );
    	$sql_ary[] = array('config_name' =>  'pb_illidan', 'config_value' => 'Illidan Stormrage, Illidan'  );
    	$sql_ary[] = array('config_name' =>  'pb_jeklik', 'config_value' => 'High Priestess Jeklik, Jeklik'  );
    	$sql_ary[] = array('config_name' =>  'pb_jindo', 'config_value' => 'Jin\'do the Hexxer, Jin\'do'  );
    	$sql_ary[] = array('config_name' =>  'pb_kaelthas', 'config_value' => 'Kael\'thas Sunstrider, Kaelthas'  );
    	$sql_ary[] = array('config_name' =>  'pb_karathress', 'config_value' => 'Fathom-Lord Karathress, Karathress'  );
    	$sql_ary[] = array('config_name' =>  'pb_kazrogal', 'config_value' => 'Kaz\'rogal'  );
    	$sql_ary[] = array('config_name' =>  'pb_kazzak', 'config_value' => 'Lord Kazzak, Kazzak'  );
    	$sql_ary[] = array('config_name' =>  'pb_kelthuzad', 'config_value' => 'Kel\'Thuzad'  );
    	$sql_ary[] = array('config_name' =>  'pb_korthazz', 'config_value' => 'Thane Korth\'azz, Korth\'azz'  );
    	$sql_ary[] = array('config_name' =>  'pb_kri', 'config_value' => 'Lord Kri'  );
    	$sql_ary[] = array('config_name' =>  'pb_kurinnaxx', 'config_value' => 'Kurinnaxx'  );
    	$sql_ary[] = array('config_name' =>  'pb_lashlayer', 'config_value' => 'Broodlord Lashlayer, Lashlayer'  );
    	$sql_ary[] = array('config_name' =>  'pb_leotheras', 'config_value' => 'Leotheras the Blind, Leotheras'  );
    	$sql_ary[] = array('config_name' =>  'pb_lethon', 'config_value' => 'Lethon'  );
    	$sql_ary[] = array('config_name' =>  'pb_loatheb', 'config_value' => 'Loatheb'  );
    	$sql_ary[] = array('config_name' =>  'pb_lucifron', 'config_value' => 'Lucifron'  );
    	$sql_ary[] = array('config_name' =>  'pb_lurker', 'config_value' => 'The Lurker Below, Lurker'  );
    	$sql_ary[] = array('config_name' =>  'pb_maexxna', 'config_value' => 'Maexxna'  );
    	$sql_ary[] = array('config_name' =>  'pb_magmadar', 'config_value' => 'Magmadar'  );
    	$sql_ary[] = array('config_name' =>  'pb_magtheridon', 'config_value' => 'Magtheridon'  );
    	$sql_ary[] = array('config_name' =>  'pb_maiden', 'config_value' => 'Maiden of Virtue, Maiden'  );
    	$sql_ary[] = array('config_name' =>  'pb_majordomo', 'config_value' => 'Majordomo Executus, Majordomo'  );
    	$sql_ary[] = array('config_name' =>  'pb_malchezaar', 'config_value' => 'Prince Malchezaar, Malchezaar'  );
    	$sql_ary[] = array('config_name' =>  'pb_mandokir', 'config_value' => 'Bloodlord Mandokir, Mandokir'  );
    	$sql_ary[] = array('config_name' =>  'pb_marli', 'config_value' => 'High Priestess Mar\'li, Mar\'li'  );
    	$sql_ary[] = array('config_name' =>  'pb_maulgar', 'config_value' => 'High King Maulgar, Maulgar'  );
    	$sql_ary[] = array('config_name' =>  'pb_nalorakk', 'config_value' => 'Nalorakk, Bear Avatar'  );
    	$sql_ary[] = array('config_name' =>  'pb_akilzon', 'config_value' => 'Akil\'Zon, Eagle Avatar'  );
    	$sql_ary[] = array('config_name' =>  'pb_halazzi', 'config_value' => 'Halazzi, Lynx Avatar'  );
    	$sql_ary[] = array('config_name' =>  'pb_janalai', 'config_value' => 'Jan\'Alai, Dragonhawk Avatar'  );
    	$sql_ary[] = array('config_name' =>  'pb_malacrass', 'config_value' => 'Hex Lord Malacrass, Malacrass'  );
    	$sql_ary[] = array('config_name' =>  'pb_zuljin', 'config_value' => 'Zul\'Jin, Zul\'Jin'  );
    	$sql_ary[] = array('config_name' =>  'pb_kalecgos', 'config_value' => 'Kalecgos, Kalecgos'  );
    	$sql_ary[] = array('config_name' =>  'pb_brutallus', 'config_value' => 'Brutallus, Brutallus'  );
    	$sql_ary[] = array('config_name' =>  'pb_felmyst', 'config_value' => 'Felmyst, Felmyst'  );
    	$sql_ary[] = array('config_name' =>  'pb_fetwins', 'config_value' => 'Alythess & Sacrolash, Alythess & Sacrolash'  );
    	$sql_ary[] = array('config_name' =>  'pb_muru', 'config_value' => 'M\'uru, M\'uru'  );
    	$sql_ary[] = array('config_name' =>  'pb_kiljaeden', 'config_value' => 'Kil\'jaeden, Kil\'jaeden'  );
    	$sql_ary[] = array('config_name' =>  'pb_moam', 'config_value' => 'Moam'  );
    	$sql_ary[] = array('config_name' =>  'pb_mograine', 'config_value' => 'Highlord Mograine, Mograine'  );
    	$sql_ary[] = array('config_name' =>  'pb_moroes', 'config_value' => 'Moroes'  );
    	$sql_ary[] = array('config_name' =>  'pb_morogrim', 'config_value' => 'Morogrim Tidewalker, Morogrim'  );
    	$sql_ary[] = array('config_name' =>  'pb_najentus', 'config_value' => 'High Warlord Naj\'entus, Naj\'entus'  );
    	$sql_ary[] = array('config_name' =>  'pb_nefarian', 'config_value' => 'Nefarian'  );
    	$sql_ary[] = array('config_name' =>  'pb_netherspite', 'config_value' => 'Netherspite'  );
    	$sql_ary[] = array('config_name' =>  'pb_nightbane', 'config_value' => 'Nightbane'  );
    	$sql_ary[] = array('config_name' =>  'pb_noth', 'config_value' => 'Noth the Plaguebringer, Noth'  );
    	$sql_ary[] = array('config_name' =>  'pb_onyxia', 'config_value' => 'Onyxia'  );
    	$sql_ary[] = array('config_name' =>  'pb_opera', 'config_value' => 'Opera Event, Opera'  );
    	$sql_ary[] = array('config_name' =>  'pb_ossirian', 'config_value' => 'Ossirian the Unscarred, Ossirian'  );
    	$sql_ary[] = array('config_name' =>  'pb_ouro', 'config_value' => 'Ouro'  );
    	$sql_ary[] = array('config_name' =>  'pb_patchwerk', 'config_value' => 'Patchwerk'  );
    	$sql_ary[] = array('config_name' =>  'pb_ragnaros', 'config_value' => 'Ragnaros'  );
    	$sql_ary[] = array('config_name' =>  'pb_rajaxx', 'config_value' => 'General Rajaxx, Rajaxx'  );
    	$sql_ary[] = array('config_name' =>  'pb_razorgore', 'config_value' => 'Razorgore the Untamed, Razorgore'  );
    	$sql_ary[] = array('config_name' =>  'pb_razuvious', 'config_value' => 'Instructor Razuvious, Razuvious'  );
    	$sql_ary[] = array('config_name' =>  'pb_renataki', 'config_value' => 'Renataki'  );
    	$sql_ary[] = array('config_name' =>  'pb_sapphiron', 'config_value' => 'Sapphiron'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartura', 'config_value' => 'Battleguard Sartura, Sartura'  );
    	$sql_ary[] = array('config_name' =>  'pb_shahraz', 'config_value' => 'Mother Shahraz, Shahraz'  );
    	$sql_ary[] = array('config_name' =>  'pb_shazzrah', 'config_value' => 'Shazzrah'  );
    	$sql_ary[] = array('config_name' =>  'pb_skeram', 'config_value' => 'The Prophet Skeram, Skeram'  );
    	$sql_ary[] = array('config_name' =>  'pb_solarian', 'config_value' => 'High Astromancer Solarian, Solarian'  );
    	$sql_ary[] = array('config_name' =>  'pb_sulfuron', 'config_value' => 'Sulfuron Harbringer, Sulfuron'  );
    	$sql_ary[] = array('config_name' =>  'pb_supremus', 'config_value' => 'Supremus'  );
    	$sql_ary[] = array('config_name' =>  'pb_taerar', 'config_value' => 'Taerar'  );
    	$sql_ary[] = array('config_name' =>  'pb_thaddius', 'config_value' => 'Thaddius'  );
    	$sql_ary[] = array('config_name' =>  'pb_thekal', 'config_value' => 'High Priest Thekal, Thekal'  );
    	$sql_ary[] = array('config_name' =>  'pb_vaelastrasz', 'config_value' => 'Vaelastrasz the Corrupt, Vaelastrasz'  );
    	$sql_ary[] = array('config_name' =>  'pb_vashj', 'config_value' => 'Lady Vashj, Vashj'  );
    	$sql_ary[] = array('config_name' =>  'pb_veklor', 'config_value' => 'Emperor Vek\'lor, Vek\'lor'  );
    	$sql_ary[] = array('config_name' =>  'pb_veknilash', 'config_value' => 'Emperor Vek\'nilash, Vek\'nilash'  );
    	$sql_ary[] = array('config_name' =>  'pb_vem', 'config_value' => 'Vem'  );
    	$sql_ary[] = array('config_name' =>  'pb_venoxis', 'config_value' => 'High Priest Venoxis, Venoxis'  );
    	$sql_ary[] = array('config_name' =>  'pb_viscidus', 'config_value' => 'Viscidus'  );
    	$sql_ary[] = array('config_name' =>  'pb_vreaver', 'config_value' => 'Void Reaver, Reaver'  );
    	$sql_ary[] = array('config_name' =>  'pb_winterchill', 'config_value' => 'Rage Winterchill, Winterchill'  );
    	$sql_ary[] = array('config_name' =>  'pb_wushoolay', 'config_value' => 'Wushoolay'  );
    	$sql_ary[] = array('config_name' =>  'pb_yauj', 'config_value' => 'Princess Yauj'  );
    	$sql_ary[] = array('config_name' =>  'pb_ysondre', 'config_value' => 'Ysondre'  );
    	$sql_ary[] = array('config_name' =>  'pb_zeliek', 'config_value' => 'Sir Zeliek'  );
    	$sql_ary[] = array('config_name' =>  'pb_anubrekhan_10', 'config_value' => 'Anub\'Rekhan (10), AnubRekhan (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_faerlina_10', 'config_value' => 'Grand Widow Faerlina (10), Faerlina (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_maexxna_10', 'config_value' => 'Maexxna (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_noth_10', 'config_value' => 'Noth the Plaguebringer (10), Noth (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_heigan_10', 'config_value' => 'Heigan the Unclean (10), Heigan (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_loatheb_10', 'config_value' => 'Loatheb (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_patchwerk_10', 'config_value' => 'Patchwerk (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_grobbulus_10', 'config_value' => 'Grobbulus (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_gluth_10', 'config_value' => 'Gluth (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_thaddius_10', 'config_value' => 'Thaddius (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_razuvious_10', 'config_value' => 'Instructor Razuvious (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_gothik_10', 'config_value' => 'Gothik the Harvester (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_horsemen_10', 'config_value' => 'Four Horsemen (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sapphiron_10', 'config_value' => 'Sapphiron (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_kelthuzad_10', 'config_value' => 'Kel\'Thuzad (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_anubrekhan_25', 'config_value' => 'Anub\'Rekhan (25), AnubRekhan (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_faerlina_25', 'config_value' => 'Grand Widow Faerlina (25), Faerlina (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_maexxna_25', 'config_value' => 'Maexxna (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_noth_25', 'config_value' => 'Noth the Plaguebringer (25), Noth (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_heigan_25', 'config_value' => 'Heigan the Unclean (25), Heigan (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_loatheb_25', 'config_value' => 'Loatheb (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_patchwerk_25', 'config_value' => 'Patchwerk (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_grobbulus_25', 'config_value' => 'Grobbulus (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_gluth_25', 'config_value' => 'Gluth (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_thaddius_25', 'config_value' => 'Thaddius (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_razuvious_25', 'config_value' => 'Instructor Razuvious (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_gothik_25', 'config_value' => 'Gothik the Harvester (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_horsemen_25', 'config_value' => 'Four Horsemen (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sapphiron_25', 'config_value' => 'Sapphiron (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_kelthuzad_25', 'config_value' => 'Kel\'Thuzad (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_archavon_10', 'config_value' => 'Archavon the Stone Watcher (10), Archavon (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_archavon_25', 'config_value' => 'Archavon the Stone Watcher (25), Archavon (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_malygos_10', 'config_value' => 'Malygos (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_malygos_25', 'config_value' => 'Malygos (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_0d_10', 'config_value' => 'Sartharion the Onyx Guardian No dragons (10), Sartharion 0d (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_1d_10', 'config_value' => 'Sartharion the Onyx Guardian One dragon (10), Sartharion 1d (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_2d_10', 'config_value' => 'Sartharion the Onyx Guardian Two dragons (10), Sartharion 2d (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_3d_10', 'config_value' => 'Sartharion the Onyx Guardian Three dragons (10), Sartharion 3d (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_0d_25', 'config_value' => 'Sartharion the Onyx Guardian No dragon (25), Sartharion 0d (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_1d_25', 'config_value' => 'Sartharion the Onyx Guardian One dragons (25), Sartharion 1d (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_2d_25', 'config_value' => 'Sartharion the Onyx Guardian Two dragons (25), Sartharion 2d (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_sartharion_3d_25', 'config_value' => 'Sartharion the Onyx Guardian Three dragons (25), Sartharion 3d (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_algalon_10', 'config_value' => 'Algalon (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_algalon_25', 'config_value' => 'Algalon (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_assembly_10', 'config_value' => 'Assembly of Iron (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_assembly_25', 'config_value' => 'Assembly of Iron (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_auriaya_10', 'config_value' => 'Auriaya (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_auriaya_25', 'config_value' => 'Auriaya (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_deconstructor_10', 'config_value' => 'XT-002 Deconstructor (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_deconstructor_25', 'config_value' => 'XT-002 Deconstructor (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_emalon_10', 'config_value' => 'Emalon the Storm Watcher (10), Emalon (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_emalon_25', 'config_value' => 'Emalon the Storm Watcher (25), Emalon (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_freya_10', 'config_value' => 'Freya (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_freya_25', 'config_value' => 'Freya (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_hodir_10', 'config_value' => 'Hodir (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_hodir_25', 'config_value' => 'Hodir (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_ignis_10', 'config_value' => 'Ignis the Furnace Master (10), Ignis (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_ignis_25', 'config_value' => 'Ignis the Furnace Master (25), Ignis (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_kologarn_10', 'config_value' => 'Kologarn (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_kologarn_25', 'config_value' => 'Kologarn (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_leviathan_10', 'config_value' => 'Flame Leviathan (10), Leviathan (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_leviathan_25', 'config_value' => 'Flame Leviathan (25), Leviathan (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_mimiron_10', 'config_value' => 'Mimiron (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_mimiron_25', 'config_value' => 'Mimiron (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_razorscale_10', 'config_value' => 'Razorscale (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_razorscale_25', 'config_value' => 'Razorscale (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_thorim_10', 'config_value' => 'Thorim (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_thorim_25', 'config_value' => 'Thorim (25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_vezax_10', 'config_value' => 'General Vezax (10), Vezax(10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_vezax_25', 'config_value' => 'General Vezax (25), Vezax(25)'  );
    	$sql_ary[] = array('config_name' =>  'pb_yoggsaron_10', 'config_value' => 'Yogg-Saron (10)'  );
    	$sql_ary[] = array('config_name' =>  'pb_yoggsaron_25', 'config_value' => 'Yogg-Saron (25)'  );
    	$sql_ary[] = array('config_name' =>  'pz_aq20', 'config_value' => 'Ruins of Ahn\'Qiraj, AQ20'  );
    	$sql_ary[] = array('config_name' =>  'pz_aq40', 'config_value' => 'Gates of Ahn\'Qiraj, AQ40'  );
    	$sql_ary[] = array('config_name' =>  'pz_bwl', 'config_value' => 'Blackwing Lair, BWL'  );
    	$sql_ary[] = array('config_name' =>  'pz_zg', 'config_value' => 'Zul\'Gurub, ZG'  );
    	$sql_ary[] = array('config_name' =>  'pz_dream', 'config_value' => 'The Emerald Dream, Dream'  );
    	$sql_ary[] = array('config_name' =>  'pz_naxx', 'config_value' => 'Naxxramas, Naxx'  );
    	$sql_ary[] = array('config_name' =>  'pz_onylair', 'config_value' => 'Onyxia\'s Lair, Onyxia'  );
    	$sql_ary[] = array('config_name' =>  'pz_mc', 'config_value' => 'Molten Core, MC'  );
    	$sql_ary[] = array('config_name' =>  'pz_misc', 'config_value' => 'Miscellaneous bosses, Misc'  );
    	$sql_ary[] = array('config_name' =>  'pz_kara', 'config_value' => 'Karazhan, Kara'  );
    	$sql_ary[] = array('config_name' =>  'pz_gruuls', 'config_value' => 'Gruul\'s Lair, GL'  );
    	$sql_ary[] = array('config_name' =>  'pz_za', 'config_value' => 'Zul\'Aman, ZA'  );
    	$sql_ary[] = array('config_name' =>  'pz_maglair', 'config_value' => 'Magtheridon\'s Lair, Magtheridon'  );
    	$sql_ary[] = array('config_name' =>  'pz_eye', 'config_value' => 'The Eye, Eye'  );
    	$sql_ary[] = array('config_name' =>  'pz_outdoor2', 'config_value' => 'Outland Outdoor Bosses, Outdoor'  );
    	$sql_ary[] = array('config_name' =>  'pz_hyjal', 'config_value' => 'Battle of Mount Hyjal, Hyjal'  );
    	$sql_ary[] = array('config_name' =>  'pz_serpent', 'config_value' => 'Serpentshrine Cavern, SC'  );
    	$sql_ary[] = array('config_name' =>  'pz_temple', 'config_value' => 'The Black Temple, Temple'  );
    	$sql_ary[] = array('config_name' =>  'pz_sunwell', 'config_value' => 'The Sunwell Plateau, Sunwell'  );
    	$sql_ary[] = array('config_name' =>  'pz_naxx_10', 'config_value' => 'Naxxramas (10), Naxx (10)'  );
    	$sql_ary[] = array('config_name' =>  'pz_naxx_25', 'config_value' => 'Naxxramas (25), Naxx (25)'  );
    	$sql_ary[] = array('config_name' =>  'pz_vault_of_archavon_10', 'config_value' => 'Vault of Archavon (10), VoA (10)'  );
    	$sql_ary[] = array('config_name' =>  'pz_vault_of_archavon_25', 'config_value' => 'Vault of Archavon (25), VoA (25)'  );
    	$sql_ary[] = array('config_name' =>  'pz_eye_of_eternity_10', 'config_value' => 'Eye of Eternity (10), EoE (10)'  );
    	$sql_ary[] = array('config_name' =>  'pz_eye_of_eternity_25', 'config_value' => 'Eye of Eternity (25), EoE (25)'  );
    	$sql_ary[] = array('config_name' =>  'pz_obsidian_sanctum_10', 'config_value' => 'The Obsidian Sanctum (10), OS (10)'  );
    	$sql_ary[] = array('config_name' =>  'pz_obsidian_sanctum_25', 'config_value' => 'The Obsidian Sanctum (25), OS (25)'  );
    	$sql_ary[] = array('config_name' =>  'pz_ulduar_10', 'config_value' => 'Ulduar (10)'  );
    	$sql_ary[] = array('config_name' =>  'pz_ulduar_25', 'config_value' => 'Ulduar (25)'  );
    	$sql_ary[] = array('config_name' =>  'sz_aq20', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_aq40', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_bwl', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_zg', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_dream', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_naxx', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_onylair', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_mc', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_misc', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_kara', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_gruuls', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_za', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_maglair', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_eye', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_outdoor2', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_hyjal', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_serpent', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_temple', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_sunwell', 'config_value' => '0'  );
    	$sql_ary[] = array('config_name' =>  'sz_naxx_10', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_naxx_25', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_vault_of_archavon_10', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_vault_of_archavon_25', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_eye_of_eternity_10', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_eye_of_eternity_25', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_obsidian_sanctum_10', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_obsidian_sanctum_25', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_ulduar_10', 'config_value' => '1'  );
    	$sql_ary[] = array('config_name' =>  'sz_ulduar_25', 'config_value' => '1'  );
    	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
    
	}
	
	if	 ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets'))
	{
    	// Boss offsets
	    unset ($sql_ary); 
    	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
    	$sql_ary = array();
    	$sql_ary[] = array('name' => 'akama' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'alar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'anetheron' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'anubrekhan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'aq20' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'aq40' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'aran' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'archimonde' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'arlokk' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'attumen' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ayamiss' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'azgalor' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'azuregos' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'blaumeux' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'bloodboil' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'buru' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'bwl' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'chess' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'chromaggus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'council' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'cthun' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'curator' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'doomkazzak' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'doomwalker' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'dream' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ebonroc' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'emeriss' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'essence' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'eye' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'faerlina' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'fankriss' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'firemaw' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'flamegor' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gahzranka' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'garr' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'geddon' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gehennas' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gluth' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'golemagg' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gorefiend' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gothik' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'grilek' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'grobbulus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gruul' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gruuls' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'za' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hakkar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hazzarah' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'heigan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'huhuran' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hydross' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hyjal' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'illhoof' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'illidan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'jeklik' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'jindo' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kaelthas' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kara' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'karathress' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kazrogal' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kazzak' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kelthuzad' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'korthazz' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kri' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kurinnaxx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'lashlayer' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'leotheras' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'lethon' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'loatheb' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'lucifron' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'lurker' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maexxna' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maglair' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'magmadar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'magtheridon' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maiden' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'majordomo' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'malchezaar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'mandokir' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'marli' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maulgar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'mc' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'misc' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'moam' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'mograine' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'moroes' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'morogrim' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'najentus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'naxx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'nefarian' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'netherspite' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'nightbane' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'noth' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'onylair' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'onyxia' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'opera' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ossirian' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ouro' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'outdoor2' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'patchwerk' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ragnaros' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'rajaxx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razorgore' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razuvious' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'renataki' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sapphiron' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartura' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'serpent' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'shahraz' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'shazzrah' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'skeram' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'solarian' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sulfuron' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'supremus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'taerar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'temple' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thaddius' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thekal' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vaelastrasz' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vashj' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'veklor' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'veknilash' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vem' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'venoxis' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'viscidus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vreaver' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'winterchill' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'wushoolay' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'yauj' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ysondre' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'zeliek' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'zg' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'nalorakk' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'akilzon' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'halazzi' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'janalai' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'malacrass' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'zuljin' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sunwell' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kalecgos' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'brutallus' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'felmyst' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'fetwins' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'muru' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kiljaeden' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'anubrekhan_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'faerlina_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maexxna_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'noth_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'heigan_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'loatheb_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'patchwerk_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'grobbulus_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gluth_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thaddius_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razuvious_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gothik_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'horsemen_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sapphiron_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kelthuzad_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'anubrekhan_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'faerlina_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'maexxna_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'noth_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'heigan_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'loatheb_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'patchwerk_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'grobbulus_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gluth_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thaddius_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razuvious_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'gothik_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'horsemen_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sapphiron_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kelthuzad_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'archavon_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'archavon_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'malygos_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'malygos_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'sartharion_0d_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_1d_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_2d_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_3d_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_0d_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_1d_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_2d_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'sartharion_3d_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'naxx_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'naxx_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vault_of_archavon_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vault_of_archavon_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'obsidian_sanctum_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'obsidian_sanctum_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'eye_of_eternity_10' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'eye_of_eternity_25' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    	
    	$sql_ary[] = array('name' => 'algalon_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'algalon_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'assembly_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'assembly_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'auriaya_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'auriaya_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'deconstructor_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'deconstructor_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'emalon_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'emalon_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'freya_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'freya_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hodir_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'hodir_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ignis_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ignis_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kologarn_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'kologarn_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'leviathan_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'leviathan_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'mimiron_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'mimiron_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razorscale_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'razorscale_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thorim_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'thorim_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ulduar_10' , 'fdate' => '1239832800' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'ulduar_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vezax_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'vezax_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'yoggsaron_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$sql_ary[] = array('name' => 'yoggsaron_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
    	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);
	    
	}
	
    // dkp system bbeqdkp_dkpsystem 
    // set to classic tbc wlk 
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Classic' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'N' ) ;
	$sql_ary[] = array('dkpsys_id' => '2' , 'dkpsys_name' => 'TBC' , 'dkpsys_status' => 'Y',   'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'N' ) ;
    $sql_ary[] = array('dkpsys_id' => '3' , 'dkpsys_name' => 'WLK10' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin'  , 'dkpsys_default' =>  'Y' );
	$sql_ary[] = array('dkpsys_id' => '4' , 'dkpsys_name' => 'WLK25' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin'  , 'dkpsys_default' =>  'N'    );
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
	
}

/*
 * installation of new zone and boss names for wow patch 3.2, 
 */
function install_wow2($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config') and ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets')) )
	{
	     // check if TOTC was installed manually, otherwise install
	     $sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_trial_of_the_grand_crusader_25'";
         $result = $db->sql_query($sql);
         $countx = (int) $db->sql_fetchfield('countx'); 
         if ($countx == 0)
         {
                $sql_ary = array();
                $sql_ary[] = array('config_name' =>  'pz_trial_of_the_grand_crusader_25', 'config_value' => 'Trial of the Grand Crusader (25), ToGC (25)'  );
                $sql_ary[] = array('config_name' =>  'pz_trial_of_the_grand_crusader_10', 'config_value' => 'Trial of the Grand Crusader (10), ToGC (10)'  );
                $sql_ary[] = array('config_name' =>  'pz_trial_of_the_crusader_25', 'config_value' => 'Trial of the Crusader (25), ToC (25)' );
                $sql_ary[] = array('config_name' =>  'pz_trial_of_the_crusader_10', 'config_value' => 'Trial of the Crusader (10), ToC (10)' );
                
                $sql_ary[] = array('config_name' =>  'pb_northrend_beasts_25_hc', 'config_value' => 'Northrend Beasts (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_northrend_beasts_25', 'config_value' => 'Northrend Beasts (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_northrend_beasts_10_hc', 'config_value' => 'Northrend Beasts (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_northrend_beasts_10', 'config_value' => 'Northrend Beasts (10)'  );
                
                $sql_ary[] = array('config_name' =>  'pb_faction_champions_25_hc', 'config_value' => 'Faction Champions (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_faction_champions_25', 'config_value' => 'Faction Champions (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_faction_champions_10_hc', 'config_value' => 'Faction Champions (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_faction_champions_10', 'config_value' => 'Faction Champions (10)'  );
        
                $sql_ary[] = array('config_name' =>  'pb_lord_jaraxxus_25_hc', 'config_value' => 'Lord Jaraxxus (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_jaraxxus_25', 'config_value' => 'Lord Jaraxxus (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_jaraxxus_10_hc', 'config_value' => 'Lord Jaraxxus (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_jaraxxus_10', 'config_value' => 'Lord Jaraxxus (10)'  );
                
                $sql_ary[] = array('config_name' =>  'pb_twin_valkyrs_25_hc', 'config_value' => 'Twin Valkyrs (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_twin_valkyrs_25', 'config_value' => 'Twin Valkyrs (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_twin_valkyrs_10_hc', 'config_value' => 'Twin Valkyrs (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_twin_valkyrs_10', 'config_value' => 'Twin Valkyrs (10)'  );
        
                $sql_ary[] = array('config_name' =>  'pb_anub_arak_25_hc', 'config_value' => 'Anub Arak (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_anub_arak_25', 'config_value' => 'Anub Arak (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_anub_arak_10_hc', 'config_value' => 'Anub Arak (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_anub_arak_10', 'config_value' => 'Anub Arak (10)'  );
                
                
            	$sql_ary[] = array('config_name' =>  'sz_trial_of_the_grand_crusader_25', 'config_value' => '1'  );
            	$sql_ary[] = array('config_name' =>  'sz_trial_of_the_grand_crusader_10', 'config_value' => '1'  );
            	$sql_ary[] = array('config_name' =>  'sz_trial_of_the_crusader_25', 'config_value' => '1'  );
            	$sql_ary[] = array('config_name' =>  'sz_trial_of_the_crusader_10', 'config_value' => '1'  );

            	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
               unset ($sql_ary); 
            	
        	    // new boss offsets
               $sql_ary = array();
            	$sql_ary[] = array('name' => 'trial_of_the_grand_crusader_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'trial_of_the_grand_crusader_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'trial_of_the_crusader_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'trial_of_the_crusader_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'northrend_beasts_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'northrend_beasts_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'northrend_beasts_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'northrend_beasts_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'faction_champions_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'faction_champions_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'faction_champions_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'faction_champions_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_jaraxxus_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_jaraxxus_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_jaraxxus_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_jaraxxus_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'twin_valkyrs_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'twin_valkyrs_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'twin_valkyrs_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'twin_valkyrs_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'anub_arak_25_hc' , 'fdate' => '1239832800' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'anub_arak_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'anub_arak_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'anub_arak_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	
            	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);
        	    unset ($sql_ary);
	        }
	    
	}
   
}

/*
 * installation of new zone and boss names for wow patch 3.2.2, Onyxia 
 */
function install_wow3($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config') and ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets')) )
	{
	     // check if Onyxia was installed manually, otherwise install
	     $sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_onylair_25'";
         $result = $db->sql_query($sql);
         $countx = (int) $db->sql_fetchfield('countx'); 
         if ($countx == 0)
         {
                $sql_ary = array();
                $sql_ary[] = array('config_name' =>  'pz_onylair_25', 'config_value' => 'Onyxia\'s Lair (25), Onyxia (25)'  );
                $sql_ary[] = array('config_name' =>  'pz_onylair_10', 'config_value' => 'Onyxia\'s Lair (10), Onyxia (10)'  );
                $sql_ary[] = array('config_name' =>  'sz_onylair_25', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'sz_onylair_10', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'pb_onyxia_25', 'config_value' => 'Onyxia (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_onyxia_10', 'config_value' => 'Onyxia (10)'  );
                    	    	
                $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
               unset ($sql_ary); 
            	
        	    // new boss offsets
               $sql_ary = array();
            	$sql_ary[] = array('name' => 'onylair_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'onylair_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'onyxia_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'onyxia_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	
            	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);
        	    unset ($sql_ary);
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
    global  $db, $table_prefix, $umil, $user;
    
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config') and ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets')) )
	{
	     // check if installed manually, otherwise install
	     $sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_icecrown_citadel_10'";
         $result = $db->sql_query($sql);
         $countx = (int) $db->sql_fetchfield('countx'); 
         if ($countx == 0)
         {
                $sql_ary = array();
                $sql_ary[] = array('config_name' =>  'sz_icecrown_citadel_10', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'sz_icecrown_citadel_25', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'pz_icecrown_citadel_10', 'config_value' => 'Icecrown Citadel (10), ICC (10)'  );
                $sql_ary[] = array('config_name' =>  'pz_icecrown_citadel_25', 'config_value' => 'Icecrown Citadel (25), ICC (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_marrowgar_10', 'config_value' => 'Lord Marrowgar (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_marrowgar_10_hc', 'config_value' => 'Lord Marrowgar (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_marrowgar_25', 'config_value' => 'Lord Marrowgar (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_lord_marrowgar_25_hc', 'config_value' => 'Lord Marrowgar (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_lady_deathwhisper_10', 'config_value' => 'Lady Deathwhisper (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_lady_deathwhisper_10_hc', 'config_value' => 'Lady Deathwhisper (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_lady_deathwhisper_25', 'config_value' => 'Lady Deathwhisper (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_lady_deathwhisper_25_hc', 'config_value' => 'Lady Deathwhisper (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_icecrown_gunship_battle_10', 'config_value' => 'Icecrown Gunship Battle (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_icecrown_gunship_battle_10_hc', 'config_value' => 'Icecrown Gunship Battle (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_icecrown_gunship_battle_25', 'config_value' => 'Icecrown Gunship Battle (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_icecrown_gunship_battle_25_hc', 'config_value' => 'Icecrown Gunship Battle (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_deathbringer_saurfang_10', 'config_value' => 'Deathbringer Saurfang (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_deathbringer_saurfang_10_hc', 'config_value' => 'Deathbringer Saurfang (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_deathbringer_saurfang_25', 'config_value' => 'Deathbringer Saurfang (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_deathbringer_saurfang_25_hc', 'config_value' => 'Deathbringer Saurfang (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_festergut_10', 'config_value' => 'Festergut (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_festergut_10_hc', 'config_value' => 'Festergut (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_festergut_25', 'config_value' => 'Festergut (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_festergut_25_hc', 'config_value' => 'Festergut (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_rotface_10', 'config_value' => 'Rotface (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_rotface_10_hc', 'config_value' => 'Rotface (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_rotface_25', 'config_value' => 'Rotface (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_rotface_25_hc', 'config_value' => 'Rotface (25HM)'  );                
                $sql_ary[] = array('config_name' =>  'pb_professor_putricide_10', 'config_value' => 'Professor Putricide (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_professor_putricide_10_hc', 'config_value' => 'Professor Putricide (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_professor_putricide_25', 'config_value' => 'Professor Putricide (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_professor_putricide_25_hc', 'config_value' => 'Professor Putricide (25HM)'  );                 
                $sql_ary[] = array('config_name' =>  'pb_blood_princes_10', 'config_value' => 'Blood Princes (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_princes_10_hc', 'config_value' => 'Blood Princes (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_princes_25', 'config_value' => 'Blood Princes (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_princes_25_hc', 'config_value' => 'Blood Princes (25HM)'  );                 
                $sql_ary[] = array('config_name' =>  'pb_blood_queen_lana_thel_10', 'config_value' => 'Blood-Queen Lana thel (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_queen_lana_thel_10_hc', 'config_value' => 'Blood-Queen Lana thel (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_queen_lana_thel_25', 'config_value' => 'Blood-Queen Lana thel (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_blood_queen_lana_thel_25_hc', 'config_value' => 'Blood-Queen Lana thel (25HM)'  ); 
                $sql_ary[] = array('config_name' =>  'pb_valithria_dreamwalker_10', 'config_value' => 'Valithria Dreamwalker (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_valithria_dreamwalker_10_hc', 'config_value' => 'Valithria Dreamwalker (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_valithria_dreamwalker_25', 'config_value' => 'Valithria Dreamwalker (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_valithria_dreamwalker_25_hc', 'config_value' => 'Valithria Dreamwalker (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_sindragosa_10', 'config_value' => 'Sindragosa (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_sindragosa_10_hc', 'config_value' => 'Sindragosa (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_sindragosa_25', 'config_value' => 'Sindragosa (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_sindragosa_25_hc', 'config_value' => 'Sindragosa (25HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_the_lich_king_10', 'config_value' => 'The Lich King (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_the_lich_king_10_hc', 'config_value' => 'The Lich King (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_the_lich_king_25', 'config_value' => 'The Lich King (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_the_lich_king_25_hc', 'config_value' => 'The Lich King (25HM)'  );                                $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);

                
                unset ($sql_ary); 
            	
        	    // new boss offsets
               $sql_ary = array();
            	$sql_ary[] = array('name' => 'icecrown_citadel_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'icecrown_citadel_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_marrowgar_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_marrowgar_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_marrowgar_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lord_marrowgar_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lady_deathwhisper_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'lady_deathwhisper_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	            	            	            	
            	$sql_ary[] = array('name' => 'lady_deathwhisper_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'lady_deathwhisper_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'icecrown_gunship_battle_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'icecrown_gunship_battle_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'icecrown_gunship_battle_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'icecrown_gunship_battle_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'deathbringer_saurfang_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'deathbringer_saurfang_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'deathbringer_saurfang_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'deathbringer_saurfang_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'festergut_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'festergut_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'festergut_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'festergut_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	
            	$sql_ary[] = array('name' => 'rotface_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'rotface_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'rotface_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'rotface_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );       
            	$sql_ary[] = array('name' => 'professor_putricide_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'professor_putricide_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'professor_putricide_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'professor_putricide_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_princes_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_princes_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_princes_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_princes_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_queen_lana_thel_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_queen_lana_thel_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_queen_lana_thel_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'blood_queen_lana_thel_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
				$sql_ary[] = array('name' => 'valithria_dreamwalker_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'valithria_dreamwalker_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'valithria_dreamwalker_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'valithria_dreamwalker_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
				$sql_ary[] = array('name' => 'sindragosa_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'sindragosa_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'sindragosa_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'sindragosa_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
				$sql_ary[] = array('name' => 'the_lich_king_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'the_lich_king_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'the_lich_king_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'the_lich_king_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );            	            	            	
            	
            	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);
        	    unset ($sql_ary);
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

     	  $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 99 where class_id = 4"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 4 where class_id = 2"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 2 where class_id = 99"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 99 where class_id = 5"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 5 where class_id = 9"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 11 where class_id = 6"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 6 where class_id = 10");
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 9 where class_id = 7");
          $db->sql_query("update " .  $bbdkp_table_prefix . "classes set class_id = 7 where class_id = 99"); 

          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 99 where member_class_id = 4"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 4 where member_class_id = 2"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 2 where member_class_id = 99"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 99 where member_class_id = 5"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 5 where member_class_id = 9"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 11 where member_class_id = 6"); 
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 6 where member_class_id = 10");
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 9 where member_class_id = 7");
          $db->sql_query("update " .  $bbdkp_table_prefix . "memberlist set member_class_id = 7 where member_class_id = 99");
                    
}

/*
 * installation of zone and boss names for wow patch 3.3.5 
 * The Ruby Sanctum
 * 
  * 
 */
function install_wow5($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config') and ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets')) )
	{
	     // check if installed manually, otherwise install
	     $sql = 'SELECT count(*) as countx FROM ' . $bbdkp_table_prefix . "bb_config WHERE config_name = 'pz_rs_10'";
         $result = $db->sql_query($sql);
         $countx = (int) $db->sql_fetchfield('countx'); 
         if ($countx == 0)
         {
                $sql_ary = array();
                $sql_ary[] = array('config_name' =>  'sz_rs_10', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'sz_rs_25', 'config_value' => '1'  );
                $sql_ary[] = array('config_name' =>  'pz_rs_10', 'config_value' => 'The Ruby Sanctum (10), RS (10)'  );
                $sql_ary[] = array('config_name' =>  'pz_rs_25', 'config_value' => 'The Ruby Sanctum (25), RS (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_halion_10', 'config_value' => 'Halion (10)'  );
                $sql_ary[] = array('config_name' =>  'pb_halion_10_hc', 'config_value' => 'Halion (10HM)'  );
                $sql_ary[] = array('config_name' =>  'pb_halion_25', 'config_value' => 'Halion (25)'  );
                $sql_ary[] = array('config_name' =>  'pb_halion_25_hc', 'config_value' => 'Halion (25HM)'  );
				$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);

                
                unset ($sql_ary); 
            	
        	    // new boss offsets
               $sql_ary = array();
            	$sql_ary[] = array('name' => 'rs_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'rs_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'halion_10' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'halion_10_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'halion_25' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$sql_ary[] = array('name' => 'halion_25_hc' , 'fdate' => '1419980400' , 'ldate' => '946594800', 'counter' =>  '0' );
            	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);
        	    unset ($sql_ary);
	        }
	    
	}
   
}

function install_wow_bb2($bbdkp_table_prefix)
{
global  $db, $table_prefix, $umil, $user;
    
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config') and ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets')) )
	{
		$sql_ary = array();
		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Miscellaneous bosses', 'zonename_short' =>  'Misc' , 'imagename' =>  'misc' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 2 , 'zonename' => 'Onyxia\s Lair', 'zonename_short' =>  'Onyxia' , 'imagename' =>  'onylair' , 'game' =>  wow ,  'tier' =>  'T2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 3 , 'zonename' => 'The Emerald Dream', 'zonename_short' =>  'Dream' , 'imagename' =>  'dream' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 4 , 'zonename' => 'Zul\Gurub', 'zonename_short' =>  'ZG' , 'imagename' =>  'zg' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 5 , 'zonename' => 'Blackwing Lair', 'zonename_short' =>  'BWL' , 'imagename' =>  'bwl' , 'game' =>  wow ,  'tier' =>  'T3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 6 , 'zonename' => 'Molten Core', 'zonename_short' =>  'MC' , 'imagename' =>  'mc' , 'game' =>  wow ,  'tier' =>  'T1' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 7 , 'zonename' => 'Ruins of Ahn\Qiraj', 'zonename_short' =>  'AQ20' , 'imagename' =>  'aq20' , 'game' =>  wow ,  'tier' =>  'T2.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 8 , 'zonename' => 'Gates of Ahn\Qiraj', 'zonename_short' =>  'AQ40' , 'imagename' =>  'aq40' , 'game' =>  wow ,  'tier' =>  'T2.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 9 , 'zonename' => 'Naxxramas', 'zonename_short' =>  'Naxx' , 'imagename' =>  'naxx' , 'game' =>  wow ,  'tier' =>  'T3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 10 , 'zonename' => 'Karazhan', 'zonename_short' =>  'Kara' , 'imagename' =>  'kara' , 'game' =>  wow ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 11 , 'zonename' => 'Zul\Aman', 'zonename_short' =>  'ZA' , 'imagename' =>  'za' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 12 , 'zonename' => 'Gruul\s Lair', 'zonename_short' =>  'GL' , 'imagename' =>  'gruuls' , 'game' =>  wow ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 13 , 'zonename' => 'Magtheridon\s Lair', 'zonename_short' =>  'Magtheridon' , 'imagename' =>  'maglair' , 'game' =>  wow ,  'tier' =>  'T4' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 14 , 'zonename' => 'Outland Outdoor Bosses', 'zonename_short' =>  'Outdoor' , 'imagename' =>  'outdoor2' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 15 , 'zonename' => 'Serpentshrine Cavern', 'zonename_short' =>  'SC' , 'imagename' =>  'serpent' , 'game' =>  wow ,  'tier' =>  'T5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 16 , 'zonename' => 'The Eye', 'zonename_short' =>  'Eye' , 'imagename' =>  'eye' , 'game' =>  wow ,  'tier' =>  'T5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 17 , 'zonename' => 'Battle of Mount Hyjal', 'zonename_short' =>  'Hyjal' , 'imagename' =>  'hyjal' , 'game' =>  wow ,  'tier' =>  'T6' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 18 , 'zonename' => 'The Black Temple', 'zonename_short' =>  'Temple' , 'imagename' =>  'temple' , 'game' =>  wow ,  'tier' =>  'T6' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 19 , 'zonename' => 'The Sunwell Plateau', 'zonename_short' =>  'Sunwell' , 'imagename' =>  'sunwell' , 'game' =>  wow ,  'tier' =>  'T6.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 20 , 'zonename' => 'Naxxramas (10)', 'zonename_short' =>  'Naxx (10)' , 'imagename' =>  'naxx_10' , 'game' =>  wow ,  'tier' =>  'T7' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 21 , 'zonename' => 'Vault of Archavon (10)', 'zonename_short' =>  'VoA (10)' , 'imagename' =>  'vault_of_archavon_10' , 'game' =>  wow ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 22 , 'zonename' => 'The Obsidian Sanctum (10)', 'zonename_short' =>  'OS (10)' , 'imagename' =>  'obsidian_sanctum_10' , 'game' =>  wow ,  'tier' =>  'T7' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 23 , 'zonename' => 'Eye of Eternity (10)', 'zonename_short' =>  'EoE (10)' , 'imagename' =>  'eye_of_eternity_10' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 24 , 'zonename' => 'Naxxramas (25)', 'zonename_short' =>  'Naxx (25)' , 'imagename' =>  'naxx_25' , 'game' =>  wow ,  'tier' =>  'T7.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 25 , 'zonename' => 'Vault of Archavon (25)', 'zonename_short' =>  'VoA (25)' , 'imagename' =>  'vault_of_archavon_25' , 'game' =>  wow ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 26 , 'zonename' => 'The Obsidian Sanctum (25)', 'zonename_short' =>  'OS (25)' , 'imagename' =>  'obsidian_sanctum_25' , 'game' =>  wow ,  'tier' =>  'T7.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 27 , 'zonename' => 'Eye of Eternity (25)', 'zonename_short' =>  'EoE (25)' , 'imagename' =>  'eye_of_eternity_25' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 28 , 'zonename' => 'Ulduar (10)', 'zonename_short' =>  'EoE (25)' , 'imagename' =>  'ulduar_10' , 'game' =>  wow ,  'tier' =>  'T8' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 29 , 'zonename' => 'Ulduar (25)', 'zonename_short' =>  '' , 'imagename' =>  'ulduar_25' , 'game' =>  wow ,  'tier' =>  'T8.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 30 , 'zonename' => 'Trial of the Crusader (10)', 'zonename_short' =>  'ToC (10)' , 'imagename' =>  'trial_of_the_crusader_10' , 'game' =>  wow ,  'tier' =>  'T9.1' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 31 , 'zonename' => 'Trial of the Crusader (25)', 'zonename_short' =>  'ToC (25)' , 'imagename' =>  'trial_of_the_crusader_25' , 'game' =>  wow ,  'tier' =>  'T9.2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 32 , 'zonename' => 'Trial of the Grand Crusader (10)', 'zonename_short' =>  'ToGC (10)' , 'imagename' =>  'trial_of_the_grand_crusader_10' , 'game' =>  wow ,  'tier' =>  'T9.2' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 33 , 'zonename' => 'Trial of the Grand Crusader (25)', 'zonename_short' =>  'ToGC (25)' , 'imagename' =>  'trial_of_the_grand_crusader_25' , 'game' =>  wow ,  'tier' =>  'T9.3' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 34 , 'zonename' => 'Onyxia\s Lair (10)', 'zonename_short' =>  'Onyxia (10)' , 'imagename' =>  'onylair_10' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 35 , 'zonename' => 'Onyxia\s Lair (25)', 'zonename_short' =>  'Onyxia (25)' , 'imagename' =>  'onylair_25' , 'game' =>  wow ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 36 , 'zonename' => 'Icecrown Citadel (25)', 'zonename_short' =>  'ICC (25)' , 'imagename' =>  'icecrown_citadel_25' , 'game' =>  wow ,  'tier' =>  'T10.5' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 37 , 'zonename' => 'Icecrown Citadel (10)', 'zonename_short' =>  'ICC (10)' , 'imagename' =>  'icecrown_citadel_10' , 'game' =>  wow ,  'tier' =>  'T10' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');

		$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary);
		unset ($sql_ary);
	    
	}
}




?>