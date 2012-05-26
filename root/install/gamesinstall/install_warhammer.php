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

function install_warhammer()
{
    global  $db, $table_prefix, $umil, $user;

    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'warhammer'" );    
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'warhammer', 'class_id' => 0, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Unknown_small' );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 1, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Witch_Elf_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 2, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Sorcerer_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 3, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Disciple_of_Khaine_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 4, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Chosen_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 5, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Marauder_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 6, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Zealot_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 7, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Magus_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 8, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Squig_Herder_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 9, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Black_Orc_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 10,'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Shaman_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 11, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Rune_Priest_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 12, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Iron_Breaker_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 13, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Engineer_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 14, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Witch_Hunter_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 15, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Bright_Wizard_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 16, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Warrior_Priest_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 17, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Archmage_small'  );    
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 18, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Swordmaster_small'  );    
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 19, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_Shadow_Warrior_small'  );
    $sql_ary[] = array('game_id' => 'warhammer','class_id' => 20, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 40, 'imagename' => 'warhammer_White_Lion_small'  );    
    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);

    // factions
   	unset ($sql_ary); 
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'warhammer'" );	
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'warhammer','faction_id' => 1, 'faction_name' => 'Order' );
    $sql_ary[] = array('game_id' => 'warhammer','faction_id' => 2, 'faction_name' => 'Destruction' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);

    unset ($sql_ary);
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'warhammer'");  
    $sql_ary = array();
   	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 0, 'race_faction_id' => 1,  'image_female_small' => ' ',  'image_male_small' => ' '  ); //Unknown
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 1, 'race_faction_id' => 1,  'image_female_small' => 'warhammer_dwarf',  'image_male_small' => 'warhammer_dwarf'  ); //Dwarf
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 2, 'race_faction_id' => 1 , 'image_female_small' => 'warhammer_empire',  'image_male_small' => 'warhammer_empire' ); ///The Empire'
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 3, 'race_faction_id' => 1 , 'image_female_small' => 'warhammer_highelves',  'image_male_small' => 'warhammer_highelves' ); ///High Elves
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 4, 'race_faction_id' => 2 , 'image_female_small' => 'warhammer_delves',  'image_male_small' => 'warhammer_delves' ) ; //Dark Elf
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 5, 'race_faction_id' => 2 , 'image_female_small' => 'warhammer_chaos',  'image_male_small' => 'warhammer_chaos' ); //chaos
	$sql_ary [] = array ('game_id' => 'warhammer','race_id' => 6, 'race_faction_id' => 2 , 'image_female_small' => 'warhammer_greenskin',  'image_male_small' => 'warhammer_greenskin' ); //Greenskins
     
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);

	// dkp system 
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'warhammer' and (attribute='class' or attribute = 'race')");
	unset ( $sql_ary );
	$sql_ary = array();	
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Witch Elf' ,  'name_short' =>  'Witch Elf' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Disciple of Khaine' ,  'name_short' =>  'Disciple of Khaine' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Chosen' ,  'name_short' =>  'Chosen' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Marauder' ,  'name_short' =>  'Marauder' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Zealot' ,  'name_short' =>  'Zealot' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Magus' ,  'name_short' =>  'Magus' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Squig Herder' ,  'name_short' =>  'Squig Herder' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Black Orc' ,  'name_short' =>  'Black Orc' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Rune Priest' ,  'name_short' =>  'Rune Priest' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Iron Breaker' ,  'name_short' =>  'Iron Breaker' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Engineer' ,  'name_short' =>  'Engineer' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Witch Hunter' ,  'name_short' =>  'Witch Hunter' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bright Wizard' ,  'name_short' =>  'Bright Wizard' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior Priest' ,  'name_short' =>  'Warrior Priest' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Archmage' ,  'name_short' =>  'Archmage' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Swordmaster' ,  'name_short' =>  'Swordmaster' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shadow Warrior' ,  'name_short' =>  'Shadow Warrior' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'White Lion' ,  'name_short' =>  'White Lion' );
	
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Witch Elf' ,  'name_short' =>  'Witch Elf' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Disciple of Khaine' ,  'name_short' =>  'Disciple of Khaine' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chosen' ,  'name_short' =>  'Chosen' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Marauder' ,  'name_short' =>  'Marauder' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Zealot' ,  'name_short' =>  'Zealot' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Magus' ,  'name_short' =>  'Magus' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Squig Herder' ,  'name_short' =>  'Squig Herder' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Black Orc' ,  'name_short' =>  'Black Orc' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Rune Priest' ,  'name_short' =>  'Rune Priest' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 12, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Iron Breaker' ,  'name_short' =>  'Iron Breaker' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 13, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Engineer' ,  'name_short' =>  'Engineer' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 14, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Witch Hunter' ,  'name_short' =>  'Witch Hunter' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 15, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bright Wizard' ,  'name_short' =>  'Bright Wizard' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 16, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior Priest' ,  'name_short' =>  'Warrior Priest' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 17, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Archmage' ,  'name_short' =>  'Archmage' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 18, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Swordmaster' ,  'name_short' =>  'Swordmaster' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 19, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Shadow Warrior' ,  'name_short' =>  'Shadow Warrior' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 20, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'White Lion' ,  'name_short' =>  'White Lion' );

	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'The Empire' ,  'name_short' =>  'The Empire' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'High Elves' ,  'name_short' =>  'High Elves' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dark Elves' ,  'name_short' =>  'Dark Elves' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Chaos' ,  'name_short' =>  'Chaos' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Greenskins' ,  'name_short' =>  'Greenskins' );
					
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'The Empire' ,  'name_short' =>  'The Empire' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'High Elves' ,  'name_short' =>  'High Elves' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Dark Elves' ,  'name_short' =>  'Dark Elves' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Chaos' ,  'name_short' =>  'Chaos' );
	$sql_ary[] = array( 'game_id' => 'warhammer','attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Greenskins' ,  'name_short' =>  'Greenskins' );
			
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
		
}




?>