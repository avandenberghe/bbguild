<?php
/**
 * bbdkp Rift install data
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

function install_rift()
{
    global $db, $table_prefix, $umil, $user;
    
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'rift'" );   
    $sql_ary = array();
    
    // class general
    $sql_ary[] = array('game_id' => 'rift','class_id' => 0, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_Unknown_small' );
    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 1, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_champion_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 2, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_reaver_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 3, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_paladin_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 4, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_warlord_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 5, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_paragon_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 6, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_riftblade_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 7, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_voidknight_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 8, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_beastmaster_small' );

    $sql_ary[] = array('game_id' => 'rift','class_id' => 9, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_purifier_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 10, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_inquisitor_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 11, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_sentinel_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 12, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_justicar_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 13, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_shaman_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 14, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_warden_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 15, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_druid_small' );    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 16, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_cabalist_small' );    
    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 17, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_nightblade_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 18, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_ranger_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 19, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_bladedancer_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 20, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_assassin_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 21, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_riftstalker_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 22, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_marksman_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 23, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_saboteur_small' );    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 24, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_bard_small' );
    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 25, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_elementalist_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 26, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_warlock_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 27, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_pyromancer_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 28, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_stormcaller_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 29, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_archon_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 30, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_necromancer_small' );
    $sql_ary[] = array('game_id' => 'rift','class_id' => 31, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_dominator_small' );    
    $sql_ary[] = array('game_id' => 'rift','class_id' => 32, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'rift_chloromancer_small' );

    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
   	
   	// factions
   	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'rift'" );	    
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'rift','faction_id' => 1, 'faction_name' => 'Guardians' );
    $sql_ary[] = array('game_id' => 'rift','faction_id' => 2, 'faction_name' => 'Defiant' );
    $sql_ary[] = array('game_id' => 'rift','faction_id' => 3, 'faction_name' => 'NPC' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    unset ($sql_ary); 
    
    // races (No races, only factions, dummy value)
    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'rift'");  
    $sql_ary = array();
    $sql_ary[] = array('game_id' => 'rift','race_id' => 1, 'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'rift','race_id' => 2, 'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'rift','race_id' => 3, 'race_faction_id' => 1 );
    $sql_ary[] = array('game_id' => 'rift','race_id' => 4, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'rift','race_id' => 5, 'race_faction_id' => 2 );
    $sql_ary[] = array('game_id' => 'rift','race_id' => 6, 'race_faction_id' => 2 );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);
	unset ($sql_ary);	

    $db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'rift' and (attribute_id='class' or attribute_id = 'race')");
    $sql_ary = array();
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Champion' ,  'name_short' =>  'Champion' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Reaver' ,  'name_short' =>  'Reaver' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warlord' ,  'name_short' =>  'Warlord' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paragon' ,  'name_short' =>  'Paragon' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Riftblade' ,  'name_short' =>  'Riftblade' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Voidknight' ,  'name_short' =>  'Voidknight' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
	
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Purifier' ,  'name_short' =>  'Purifier' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Inquisitor' ,  'name_short' =>  'Inquisitor' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sentinel' ,  'name_short' =>  'Sentinel' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Justicar' ,  'name_short' =>  'Justicar' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warden' ,  'name_short' =>  'Warden' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cabalist' ,  'name_short' =>  'Cabalist' );
	
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Nightblade' ,  'name_short' =>  'Nightblade' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bladedancer' ,  'name_short' =>  'Bladedancer' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Assassin' ,  'name_short' =>  'Assassin' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 21, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Riftstalker' ,  'name_short' =>  'Riftstalker' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 22, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Marksman' ,  'name_short' =>  'Marksman' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 23, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Saboteur' ,  'name_short' =>  'Saboteur' );
	$sql_ary[] = array( 'game_id' => 'rift', 'attribute_id' => 24, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 25, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Elementalist' ,  'name_short' =>  'Elementalist' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 26, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warlock' ,  'name_short' =>  'Warlock' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 27, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Pyromancer' ,  'name_short' =>  'Pyromancer' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 28, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Stormcaller' ,  'name_short' =>  'Stormcaller' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 29, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Archon' ,  'name_short' =>  'Archon' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 30, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Necromancer' ,  'name_short' =>  'Necromancer' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 31, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dominator' ,  'name_short' =>  'Dominator' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 32, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Chloromancer' ,  'name_short' =>  'Chloromancer' );

	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Dwarves' ,  'name_short' =>  'Dwarves' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'High Elves' , 'name_short' =>  'High Elves' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Mathosian' ,  'name_short' =>  'Mathosian' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Bahmi' ,  'name_short' =>  'Bahmi' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Eth' , 'name_short' =>  'Eth' );
	$sql_ary[] = array( 'game_id' => 'rift','attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Kelari' ,  'name_short' =>  'Kelari' );
	
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
	
}



?>