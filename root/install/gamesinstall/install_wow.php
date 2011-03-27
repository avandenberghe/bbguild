<?php
/**
 * bbdkp wow install data
 * @author Sajaki@betenoire
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 * 
 * 
 * 
 */

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}

function install_wow()
{
	global $db, $table_prefix, $umil, $user;
	// classes
	// note class 10 does not exist
	$db->sql_query ( 'TRUNCATE TABLE ' . $table_prefix . 'bbdkp_classes' );
	$sql_ary = array ();
	$sql_ary [] = array ('class_id' => 0, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#999', 'imagename' => 'wow_Unknown_small');   
	$sql_ary [] = array ('class_id' => 1, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#C79C6E', 'imagename' => 'wow_Warrior_small');   
	$sql_ary [] = array ('class_id' => 4, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 85, 'colorcode' =>  '#FFF569',  'imagename' => 'wow_Rogue_small');    
	$sql_ary [] = array ('class_id' => 3, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#ABD473',  'imagename' => 'wow_Hunter_small');    
	$sql_ary [] = array ('class_id' => 2, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 85 ,  'colorcode' =>  '#F58CBA',  'imagename' => 'wow_Paladin_small'); 
	$sql_ary [] = array ('class_id' => 7, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#0070DE',  'imagename' => 'wow_Shaman_small'); 
	$sql_ary [] = array ('class_id' => 11, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#FF7D0A',  'imagename' => 'wow_Druid_small');  
	$sql_ary [] = array ('class_id' => 9, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#9482C9',  'imagename' => 'wow_Warlock_small'); 
	$sql_ary [] = array ('class_id' => 8, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 85 , 'colorcode' =>  '#69CCF0',  'imagename' => 'wow_Mage_small');   
	$sql_ary [] = array ('class_id' => 5, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 85 ,  'colorcode' =>  '#FFFFFF', 'imagename' => 'wow_Priest_small');  
	$sql_ary [] = array ('class_id' => 6, 'class_armor_type' => 'PLATE', 'class_min_level' => 55, 'class_max_level' => 85 , 'colorcode' =>  '#C41F3B',  'imagename' => 'wow_Death_Knight_small'); 
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_classes', $sql_ary );
	unset ( $sql_ary ); 

	// factions
	$db->sql_query ( 'TRUNCATE TABLE ' . $table_prefix . 'bbdkp_factions' );
	$sql_ary = array();
	$sql_ary [] = array ('faction_id' => 1, 'faction_name' => 'Alliance' );
	$sql_ary [] = array ('faction_id' => 2, 'faction_name' => 'Horde' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_factions', $sql_ary );
	unset ( $sql_ary );
	
	// races
	$db->sql_query ('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_races');
	$sql_ary = array ();
	$sql_ary [] = array ('race_id' => 0, 'race_faction_id' => 0, 'image_female_small' => ' ',  'image_male_small' => ' '  ); //Unknown
	$sql_ary [] = array ('race_id' => 1, 'race_faction_id' => 1, 'image_female_small' => 'wow_human_female_small',  'image_male_small' => 'wow_human_male_small'  ); //Human
	$sql_ary [] = array ('race_id' => 2, 'race_faction_id' => 2 , 'image_female_small' => 'wow_orc_female_small',  'image_male_small' => 'wow_orc_male_small' ); //Orc
	$sql_ary [] = array ('race_id' => 3, 'race_faction_id' => 1 , 'image_female_small' => 'wow_dwarf_female_small',  'image_male_small' => 'wow_dwarf_male_small' ); //Dwarf
	$sql_ary [] = array ('race_id' => 4, 'race_faction_id' => 1 , 'image_female_small' => 'wow_nightelf_female_small',  'image_male_small' => 'wow_nightelf_male_small' ) ; //Night Elf
	$sql_ary [] = array ('race_id' => 5, 'race_faction_id' => 2 , 'image_female_small' => 'wow_scourge_female_small',  'image_male_small' => 'wow_scourge_male_small' ); //Undead
	$sql_ary [] = array ('race_id' => 6, 'race_faction_id' => 2 , 'image_female_small' => 'wow_tauren_female_small',  'image_male_small' => 'wow_tauren_male_small' ); //Tauren
	$sql_ary [] = array ('race_id' => 7, 'race_faction_id' => 1 , 'image_female_small' => 'wow_gnome_female_small',  'image_male_small' => 'wow_gnome_male_small' ); //Gnome
	$sql_ary [] = array ('race_id' => 8, 'race_faction_id' => 2 , 'image_female_small' => 'wow_troll_female_small',  'image_male_small' => 'wow_troll_male_small' ); //Troll
	$sql_ary [] = array ('race_id' => 9, 'race_faction_id' => 2 , 'image_female_small' => 'wow_goblin_female_small',  'image_male_small' => 'wow_goblin_male_small' ); //Goblin
	$sql_ary [] = array ('race_id' => 10, 'race_faction_id' => 2 , 'image_female_small' => 'wow_bloodelf_female_small',  'image_male_small' => 'wow_bloodelf_male_small' ); //Blood Elf
	$sql_ary [] = array ('race_id' => 11, 'race_faction_id' => 1 , 'image_female_small' => 'wow_draenei_female_small',  'image_male_small' => 'wow_draenei_male_small' ); //Draenei
	$sql_ary [] = array ('race_id' => 22, 'race_faction_id' => 1 , 'image_female_small' => 'wow_worgen_female_small',  'image_male_small' => 'wow_worgen_male_small' ); //Worgen
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_races', $sql_ary );
	unset ( $sql_ary );
	

	// dkp system
	$db->sql_query ( 'TRUNCATE TABLE ' . $table_prefix . 'bbdkp_dkpsystem' );
	$sql_ary = array ();
	$sql_ary [] = array ('dkpsys_id' => 1, 'dkpsys_name' => 'Classic', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => 2, 'dkpsys_name' => 'TBC', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => 3, 'dkpsys_name' => 'WLK10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => 4, 'dkpsys_name' => 'WLK25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$sql_ary [] = array ('dkpsys_id' => 5, 'dkpsys_name' => 'CATA10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'Y' );
	$sql_ary [] = array ('dkpsys_id' => 6, 'dkpsys_name' => 'CATA25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_dkpsystem', $sql_ary );
	unset ( $sql_ary );

	// classes in en
	$sql_ary = array ();
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Rogue' ,  'name_short' =>  'Rogue' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Hunter' ,  'name_short' =>  'Hunter' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warlock' ,  'name_short' =>  'Warlock' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mage' ,  'name_short' =>  'Mage' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Priest' ,  'name_short' =>  'Priest' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Death Knight' ,  'name_short' =>  'Death Knight' );
	
	//classes in fr
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Voleur' ,  'name_short' =>  'Voleur' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chasseur' ,  'name_short' =>  'Chasseur' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chaman' ,  'name_short' =>  'Chaman' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Druide' ,  'name_short' =>  'Druide' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Démoniste' ,  'name_short' =>  'Démoniste' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Mage' ,  'name_short' =>  'Mage' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Prêtre' ,  'name_short' =>  'Prêtre' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chevalier de la Mort' ,  'name_short' =>  'Chevalier de la Mort' );
	
	//classes in de	
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unbekannt' ,  'name_short' =>  'Unbekannt' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Krieger' ,  'name_short' =>  'Krieger' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Schurke' ,  'name_short' =>  'Schurke' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Jäger' ,  'name_short' =>  'Jäger' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Schamane' ,  'name_short' =>  'Schamane' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Druide' ,  'name_short' =>  'Druide' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Hexenmeister' ,  'name_short' =>  'Hexenmeister' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Magier' ,  'name_short' =>  'Magier' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Priester' ,  'name_short' =>  'Priester' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Todesritter' ,  'name_short' =>  'Todesritter' );
				
	//races in en
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Human' ,  'name_short' =>  'Human' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Night Elf' ,  'name_short' =>  'Night Elf' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Undead' ,  'name_short' =>  'Undead' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array( 'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Blood Elf' ,  'name_short' =>  'Blood Elf' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Draenei' ,  'name_short' =>  'Draenei' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array( 'attribute_id' => 22, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );
		
	// races in fr
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Humain' ,  'name_short' =>  'Humain' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Nain' ,  'name_short' =>  'Nain' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elfe de la Nuit' ,  'name_short' =>  'Elfe de la Nuit' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Mort-Vivant' ,  'name_short' =>  'Mort-Vivant' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array( 'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elfe de Sang' ,  'name_short' =>  'Elfe de Sang' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Draeneï' ,  'name_short' =>  'Draeneï' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array( 'attribute_id' => 22, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );
			
	//races in de
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Mensch' ,  'name_short' =>  'Mensch' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Zwerg' ,  'name_short' =>  'Zwerg' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Nachtelf' ,  'name_short' =>  'Nachtelf' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Untoter' ,  'name_short' =>  'Untoter' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array( 'attribute_id' => 10, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Blutelf' ,  'name_short' =>  'Blutelf' );
	$sql_ary[] = array( 'attribute_id' => 11, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Draenei' ,  'name_short' =>  'Draenei' );
	$sql_ary[] = array( 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array( 'attribute_id' => 22, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );

	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
}
?>