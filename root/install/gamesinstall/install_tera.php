<?php
/**
 * @author sh1ny https://github.com/sh1ny/
 * bbdkp tera install data
 * @package bbDkp-installer
 * @copyright (c) 2011 bbDKP <https://github.com/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function install_tera()
{
    global $db, $table_prefix, $umil, $user;
    
    	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'tera'" );   
    	$sql_ary = array();
    
    	// class general
	// Unknown
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 0, 'class_armor_type' => 'HEAVY' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_Unknown_small' );
	//ARCHER
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 1, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_archer_small' );
	//BERSERKER
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 2, 'class_armor_type' => 'HEAVY' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_berserker_small' );
	//LANCER
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 3, 'class_armor_type' => 'HEAVY' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_lancer_small' );
	//MYSTIC
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 4, 'class_armor_type' => 'ROBE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_mystic_small' );
	//PRIEST
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 5, 'class_armor_type' => 'HEAVY' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_priest_small' );
	//SLAYER
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 6, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_slayer_small' );
	//SORCERER
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 7, 'class_armor_type' => 'ROBE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_sorcerer_small' );
	//WARRIOR
    	$sql_ary[] = array('game_id' => 'tera','class_id' => 8, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'tera_warrior_small' );

    	$db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
   	
   	// factions
   	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'tera'" );	    
    	$sql_ary = array();
    	$sql_ary[] = array('game_id' => 'tera','faction_id' => 1, 'faction_name' => 'Default' );
    	$db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    	unset ($sql_ary); 
    
        // races
    	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'tera'");  
    	$sql_ary = array();
	// Unknown
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 0, 'race_faction_id' => 1 );
	// AMAN
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 1, 'race_faction_id' => 1 );
	//Baraka
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 2, 'race_faction_id' => 1 );
	//Castanic
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 3, 'race_faction_id' => 1 );
	//Elin
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 4, 'race_faction_id' => 1 );
	//High Elf
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 5, 'race_faction_id' => 1 );
	//Human
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 6, 'race_faction_id' => 1 );
	//Popori
    	$sql_ary[] = array('game_id' => 'tera','race_id' => 7, 'race_faction_id' => 1 );

    	$db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);
	unset ($sql_ary);	

	//LANGUAGE
    	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'tera' and (attribute_id='class' or attribute_id = 'race')");
    	$sql_ary = array();
	//Classes
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Archer' ,  'name_short' =>  'Archer' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Berserker' ,  'name_short' =>  'Berserker' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Lancer' ,  'name_short' =>  'Lancer' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mystic' ,  'name_short' =>  'Mystic' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Priest' ,  'name_short' =>  'Priest' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Slayer' ,  'name_short' =>  'Slayer' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );

	//Races
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Aman' ,  'name_short' =>  'Aman' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Baraka' ,  'name_short' =>  'Baraka' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Castanic' ,  'name_short' =>  'Castanic' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elin' ,  'name_short' =>  'Elin' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'High Elf' ,  'name_short' =>  'High Elf' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Human' ,  'name_short' =>  'Human' );
	$sql_ary[] = array( 'game_id' => 'tera','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Popori' ,  'name_short' =>  'Popori' );

	
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
	
}



?>
