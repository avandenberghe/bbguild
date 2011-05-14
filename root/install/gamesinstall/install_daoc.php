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
function install_daoc()
{
    global $db, $table_prefix, $umil, $user;

    $sql_ary = array();
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'daoc'" );    
    // class general
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 1, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Unknown_small' );
    // class Albion   
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 2,  'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Armsman_small'  );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 3,  'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Cabalist_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 4,  'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Cleric_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 5,  'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Friar_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 6,  'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Heretic_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 7,  'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Infiltrator_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 8,  'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Mercenary_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 9,  'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Minstrel_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 10, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Necromancer_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 11, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Paladin_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 12, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Reaver_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 13, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Scout_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 14, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Sorcerer_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 15, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Theurgist_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 16, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Wizard_small' );
    
   // class Hibernia
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 17, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Animist_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 18, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Bainshee_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 19, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Bard_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 20, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Blademaster_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 21, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Champion_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 22, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Druid_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 23, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Eldritch_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 24, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Enchanter_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 25, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Hero_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 26, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Mentalist_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 27, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Nightshade_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 28, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Ranger_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 29, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Valewalker_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 30, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Vampiir_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 31, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Warden_small' );
   
    // class Midgard
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 32, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Berserker_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 33, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Bonedancer_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 34, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Healer_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 35, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Hunter_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 36, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Runemaster_small' );    
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 37, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Savage_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 38, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Shadowblade_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 39, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Shaman_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 40, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Skald_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 41, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Spiritmaster_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 42, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Thane_small' );                                                                                        
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 43, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Valkyrie_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 44, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Warlock_small' );
    $sql_ary[] = array('game_id' => 'daoc', 'class_id' => 45, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50, 'imagename' => 'daoc_Warrior_small' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'daoc'" );	
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'daoc', 'faction_id' => 1, 'faction_name' => 'Albion' );
    $sql_ary[] = array('game_id' => 'daoc', 'faction_id' => 2, 'faction_name' => 'Hibernian' );
    $sql_ary[] = array('game_id' => 'daoc', 'faction_id' => 3, 'faction_name' => 'Midgard' );
    $sql_ary[] = array('game_id' => 'daoc', 'faction_id' => 4, 'faction_name' => 'DaoC' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    unset ($sql_ary); 

    // races
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'daoc'");  
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'daoc',  'race_id' => 1,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 2,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 3,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 4,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 5,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 6,  'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 7,  'race_faction_id' => 4 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 8,  'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 9,  'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 10, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 11, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 12, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 13, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 14, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 15, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 16, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 17, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 18, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 19, 'race_faction_id' => 3 );
    $sql_ary[] = array('game_id' => 'daoc', 'race_id' => 20, 'race_faction_id' => 3 );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);
    unset ($sql_ary); 

    //language strings 
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'daoc' and (attribute_id='class' or attribute_id = 'race')");	   
	$sql_ary = array();	
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Armsman' ,  'name_short' =>  'Armsman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cabalist' ,  'name_short' =>  'Cabalist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cleric' ,  'name_short' =>  'Cleric' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Friar' ,  'name_short' =>  'Friar' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Heretic' ,  'name_short' =>  'Heretic' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Infiltrator' ,  'name_short' =>  'Infiltrator' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mercenary' ,  'name_short' =>  'Mercenary' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Minstrel' ,  'name_short' =>  'Minstrel' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Necromancer' ,  'name_short' =>  'Necromancer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Reaver' ,  'name_short' =>  'Reaver' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Scout' ,  'name_short' =>  'Scout' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Theurgist' ,  'name_short' =>  'Theurgist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Wizard' ,  'name_short' =>  'Wizard' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Animist' ,  'name_short' =>  'Animist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bainshee' ,  'name_short' =>  'Bainshee' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Blademaster' ,  'name_short' =>  'Blademaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 21, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Champion' ,  'name_short' =>  'Champion' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 22, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 23, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Eldritch' ,  'name_short' =>  'Eldritch' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 24, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Enchanter' ,  'name_short' =>  'Enchanter' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 25, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Hero' ,  'name_short' =>  'Hero' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 26, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mentalist' ,  'name_short' =>  'Mentalist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 27, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Nightshade' ,  'name_short' =>  'Nightshade' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 28, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 29, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Valewalker' ,  'name_short' =>  'Valewalker' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 30, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Vampiir' ,  'name_short' =>  'Vampiir' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 31, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warden' ,  'name_short' =>  'Warden' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 32, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Berserker' ,  'name_short' =>  'Berserker' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 33, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bonedancer' ,  'name_short' =>  'Bonedancer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 34, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Healer' ,  'name_short' =>  'Healer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 35, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Hunter' ,  'name_short' =>  'Hunter' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 36, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Runemaster' ,  'name_short' =>  'Runemaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 37, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Savage' ,  'name_short' =>  'Savage' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 38, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shadowblade' ,  'name_short' =>  'Shadowblade' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 39, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 40, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Skald' ,  'name_short' =>  'Skald' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 41, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Spiritmaster' ,  'name_short' =>  'Spiritmaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 42, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Thane' ,  'name_short' =>  'Thane' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 43, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Valkyrie' ,  'name_short' =>  'Valkyrie' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 44, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warlock' ,  'name_short' =>  'Warlock' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 45, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
		
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Armsman' ,  'name_short' =>  'Armsman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Cabalist' ,  'name_short' =>  'Cabalist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Cleric' ,  'name_short' =>  'Cleric' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Friar' ,  'name_short' =>  'Friar' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Heretic' ,  'name_short' =>  'Heretic' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Infiltrator' ,  'name_short' =>  'Infiltrator' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Mercenary' ,  'name_short' =>  'Mercenary' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Minstrel' ,  'name_short' =>  'Minstrel' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Necromancer' ,  'name_short' =>  'Necromancer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 12, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Reaver' ,  'name_short' =>  'Reaver' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 13, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Scout' ,  'name_short' =>  'Scout' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 14, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 15, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Theurgist' ,  'name_short' =>  'Theurgist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 16, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Wizard' ,  'name_short' =>  'Wizard' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 17, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Animist' ,  'name_short' =>  'Animist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 18, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bainshee' ,  'name_short' =>  'Bainshee' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 19, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 20, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Blademaster' ,  'name_short' =>  'Blademaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 21, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Champion' ,  'name_short' =>  'Champion' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 22, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 23, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Eldritch' ,  'name_short' =>  'Eldritch' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 24, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Enchanter' ,  'name_short' =>  'Enchanter' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 25, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Hero' ,  'name_short' =>  'Hero' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 26, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Mentalist' ,  'name_short' =>  'Mentalist' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 27, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Nightshade' ,  'name_short' =>  'Nightshade' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 28, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 29, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Valewalker' ,  'name_short' =>  'Valewalker' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 30, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Vampiir' ,  'name_short' =>  'Vampiir' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 31, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warden' ,  'name_short' =>  'Warden' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 32, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Berserker' ,  'name_short' =>  'Berserker' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 33, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bonedancer' ,  'name_short' =>  'Bonedancer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 34, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Healer' ,  'name_short' =>  'Healer' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 35, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Hunter' ,  'name_short' =>  'Hunter' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 36, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Runemaster' ,  'name_short' =>  'Runemaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 37, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Savage' ,  'name_short' =>  'Savage' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 38, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Shadowblade' ,  'name_short' =>  'Shadowblade' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 39, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 40, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Skald' ,  'name_short' =>  'Skald' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 41, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Spiritmaster' ,  'name_short' =>  'Spiritmaster' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 42, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Thane' ,  'name_short' =>  'Thane' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 43, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Valkyrie' ,  'name_short' =>  'Valkyrie' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 44, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warlock' ,  'name_short' =>  'Warlock' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 45, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
		
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Briton' ,  'name_short' =>  'Briton' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Saracen' ,  'name_short' =>  'Saracen' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Avalonian' ,  'name_short' =>  'Avalonian' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Highlander' ,  'name_short' =>  'Highlander' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Inconnu' ,  'name_short' =>  'Inconnu' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Half Ogre' ,  'name_short' =>  'Half Ogre' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Minotaur' ,  'name_short' =>  'Minotaur' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Celt' ,  'name_short' =>  'Celt' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Lurikeen' ,  'name_short' =>  'Lurikeen' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Firbolg' ,  'name_short' =>  'Firbolg' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elf' ,  'name_short' =>  'Elf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Sylvan' ,  'name_short' =>  'Sylvan' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Shar' ,  'name_short' =>  'Shar' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Kobold' ,  'name_short' =>  'Kobold' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Norseman' ,  'name_short' =>  'Norseman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Valkyn' ,  'name_short' =>  'Valkyn' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Frostalf' ,  'name_short' =>  'Frostalf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Deifrang' ,  'name_short' =>  'Deifrang' );
			
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Briton' ,  'name_short' =>  'Briton' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Saracen' ,  'name_short' =>  'Saracen' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Avalonian' ,  'name_short' =>  'Avalonian' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Highlander' ,  'name_short' =>  'Highlander' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Inconnu' ,  'name_short' =>  'Inconnu' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Half Ogre' ,  'name_short' =>  'Half Ogre' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Minotaur' ,  'name_short' =>  'Minotaur' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Celt' ,  'name_short' =>  'Celt' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Lurikeen' ,  'name_short' =>  'Lurikeen' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Firbolg' ,  'name_short' =>  'Firbolg' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elf' ,  'name_short' =>  'Elf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 12, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Sylvan' ,  'name_short' =>  'Sylvan' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 13, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Shar' ,  'name_short' =>  'Shar' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 14, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Kobold' ,  'name_short' =>  'Kobold' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 15, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 16, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Norseman' ,  'name_short' =>  'Norseman' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 17, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 18, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Valkyn' ,  'name_short' =>  'Valkyn' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 19, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Frostalf' ,  'name_short' =>  'Frostalf' );
	$sql_ary[] = array('game_id' => 'daoc',  'attribute_id' => 20, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Deifrang' ,  'name_short' =>  'Deifrang' );
		
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
		
}

?>