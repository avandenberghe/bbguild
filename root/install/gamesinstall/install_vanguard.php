<?php
/**
 * bbdkp wow install data
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

function install_vanguard()
{
    global  $db, $table_prefix, $umil, $user;
    
    // class : 
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'vanguard'" ); 
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 0, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#999', 'imagename' => 'vanguard_Unknown');
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 1, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#FF0044', 'imagename' => 'vanguard_Bard' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 3, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#33FF00',  'imagename' => 'vanguard_Bloodmage' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 4, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#33FF00', 'imagename' => 'vanguard_Cleric' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 5, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#33FF00', 'imagename' => 'vanguard_Disciple' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 6, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC9933', 'imagename' => 'vanguard_Dreadknight' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 7, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC00BB', 'imagename' => 'vanguard_Druid' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 8, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#FF0044', 'imagename' => 'vanguard_Monk' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 9, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC00BB',  'imagename' => 'vanguard_Necromancer' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 10, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC9933', 'imagename' => 'vanguard_Paladin' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 11, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC00BB', 'imagename' => 'vanguard_Psionicist' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 12, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#FF0044', 'imagename' => 'vanguard_Ranger' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 13, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#FF0044', 'imagename' => 'vanguard_Rogue' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 14, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC00BB', 'imagename' => 'vanguard_Sorcerer' );
    $sql_ary[] = array('game_id' => 'vanguard','class_id' => 15, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#CC9933', 'imagename' => 'vanguard_Warrior' );
	$sql_ary[] = array('game_id' => 'vanguard','class_id' => 16, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50,  'colorcode' =>  '#33FF00',  'imagename' => 'vanguard_Shaman' );    
    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
   	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'vanguard'" );	
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'vanguard','faction_id' => 1, 'faction_name' => 'Thestra' );
    $sql_ary[] = array('game_id' => 'vanguard','faction_id' => 2, 'faction_name' => 'Kojan' );
    $sql_ary[] = array('game_id' => 'vanguard','faction_id' => 3, 'faction_name' => 'Qalia' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    unset ($sql_ary); 
    
    // races
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'vanguard'");  
    $sql_ary = array();
   	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 0, 'race_faction_id' => 1,  'image_female_small' => ' ',  'image_male_small' => ' '  ); //Unknown
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 1, 'race_faction_id' => 1,  'image_female_small' => 'vanguard_thestran',  'image_male_small' => 'vanguard_thestran'  ); //Thestran Human
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 2, 'race_faction_id' => 1 , 'image_female_small' => 'vanguard_dwarf',  'image_male_small' => 'vanguard_dwarf' ); ///Dwarf
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 3, 'race_faction_id' => 1 , 'image_female_small' => 'vanguard_halfling',  'image_male_small' => 'vanguard_halfling' ); ///Halfling
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 4, 'race_faction_id' => 1 , 'image_female_small' => 'vanguard_helf',  'image_male_small' => 'vanguard_helf' ) ; //High Elf
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 5, 'race_faction_id' => 1  , 'image_female_small' => 'vanguard_vulmane',  'image_male_small' => 'vanguard_vulmane' ); //Vulmane
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 6, 'race_faction_id' => 1 , 'image_female_small' => 'vanguard_varanjer',  'image_male_small' => 'vanguard_varanjer' ); //Varanjar
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 7, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_lgiant',  'image_male_small' => 'vanguard_lgiant' ); //Lesser Giant
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 8, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_kojani',  'image_male_small' => 'vanguard_kojani' ); //Kojani Human
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 9, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_welf',  'image_male_small' => 'vanguard_welf' ); //Wood Elf
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 10, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_aelf',  'image_male_small' => 'vanguard_aelf' ); //Half Elf
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 11, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_orc',  'image_male_small' => 'vanguard_orc' ); //Orc
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 12, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_goblin',  'image_male_small' => 'vanguard_goblin' ); //Goblin
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 13, 'race_faction_id' => 2 , 'image_female_small' => 'vanguard_raki',  'image_male_small' => 'vanguard_raki' ); //Raki
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 14, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_qual',  'image_male_small' => 'vanguard_qual' ); //Qaliathar
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 15, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_gnome',  'image_male_small' => 'vanguard_gnome' ); //Gnome
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 16, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_delf',  'image_male_small' => 'vanguard_delf' ); //Dark Elf
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 17, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_kurashasa',  'image_male_small' => 'vanguard_kurashasa' ); //Kurashasa
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 18, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_mordebi',  'image_male_small' => 'vanguard_mordebi' ); //Mordebi Human
	$sql_ary [] = array ('game_id' => 'vanguard','race_id' => 19, 'race_faction_id' => 3 , 'image_female_small' => 'vanguard_varan',  'image_male_small' => 'vanguard_varan' ); //Varanthari
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);
    unset ( $sql_ary );

    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'vanguard' and (attribute='class' or attribute = 'race')");
	$sql_ary = array();
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Blood Mage' ,  'name_short' =>  'Blood Mage' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cleric' ,  'name_short' =>  'Cleric' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Disciple' ,  'name_short' =>  'Disciple' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dread Knight' ,  'name_short' =>  'Dread Knight' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Necromancer' ,  'name_short' =>  'Necromancer' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Psionicist' ,  'name_short' =>  'Psionicist' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Rogue' ,  'name_short' =>  'Rogue' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );		
	
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Thestran Human' ,  'name_short' =>  'Thestran Human' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Halfling' ,  'name_short' =>  'Halfling' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'High Elf' ,  'name_short' =>  'High Elf' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Vulmane' ,  'name_short' =>  'Vulmane' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Varanjar' ,  'name_short' =>  'Varanjar' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Lesser Giant' ,  'name_short' =>  'Lesser Giant' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Kojani Human' ,  'name_short' =>  'Kojani Human' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Wood Elf' ,  'name_short' =>  'Wood Elf' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Half Elf' ,  'name_short' =>  'Half Elf' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Raki' ,  'name_short' =>  'Raki' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Qaliathar' ,  'name_short' =>  'Qaliathar' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dark Elf' ,  'name_short' =>  'Dark Elf' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Kurashasa' ,  'name_short' =>  'Kurashasa' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Mordebi Human' ,  'name_short' =>  'Mordebi Human' );
	$sql_ary[] = array( 'game_id' => 'vanguard','attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Varanthari' ,  'name_short' =>  'Varanthari' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary);
	unset ( $sql_ary );
		
}

?>