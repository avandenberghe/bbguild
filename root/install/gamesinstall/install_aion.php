<?php
/**
 * bbdkp wow install data
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: install_aion.php 661 2009-09-08 22:16:06Z Sajaki9 &Malfate
 * 
 */


/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function install_aion()
{
    global $db, $table_prefix, $umil, $user;
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_classes');
   
    $sql_ary = array();
    // class general
    // sub classes, excluding the original 4 classes, which are irrelevant endgame 
    $sql_ary[] = array('class_id' => 0, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Unknown_small' );
    $sql_ary[] = array('class_id' => 1, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Spiritmaster_small' );
    $sql_ary[] = array('class_id' => 2, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Sorcerer_small' );
    $sql_ary[] = array('class_id' => 3, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Assassin_small' );
    $sql_ary[] = array('class_id' => 4, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Ranger_small' );
    $sql_ary[] = array('class_id' => 5, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Chanter_small' );
    $sql_ary[] = array('class_id' => 6, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Cleric_small' );
    $sql_ary[] = array('class_id' => 7, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Gladiator_small' );
    $sql_ary[] = array('class_id' => 8, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 55, 'imagename' => 'aion_Templar_small' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Light' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Darkness' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'NPC' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    unset ($sql_ary); 
    
   	// roles
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_roles');
    $sql_ary = array();
    $sql_ary[] = array('role_id' => 1, 'role_name' => 'Healer' );
    $sql_ary[] = array('role_id' => 2, 'role_name' => 'Hybrid Support' );
    $sql_ary[] = array('role_id' => 3, 'role_name' => 'Caster DPS' );
    $sql_ary[] = array('role_id' => 4, 'role_name' => 'Melee DPS' );
	$sql_ary[] = array('role_id' => 5, 'role_name' => 'Off Tank' );      
    $sql_ary[] = array('role_id' => 6, 'role_name' => 'Tank' );        
    $db->sql_multi_insert( $table_prefix . 'bbdkp_roles', $sql_ary);
    unset ($sql_ary); 
    
    // races (No races, only factions, dummy value)
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_faction_id' => 2 );
    // balaur race is non playable but we add them anyway
    $sql_ary[] = array('race_id' => 3, 'race_faction_id' => 3 );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);
	unset ($sql_ary);	

    // dkp system
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $table_prefix . 'bbdkp_dkpsystem', $sql_ary);
	unset ( $sql_ary );
	
	$sql_ary = array ();
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Spiritmaster' ,  'name_short' =>  'Spiritmaster' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Assassin' ,  'name_short' =>  'Assassin' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Chanter' ,  'name_short' =>  'Chanter' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cleric' ,  'name_short' =>  'Cleric' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Gladiator' ,  'name_short' =>  'Gladiator' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Templar' ,  'name_short' =>  'Templar' );
	
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Inconnu' ,  'name_short' =>  'Inconnu' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Spiritualiste' ,  'name_short' =>  'Spiritualiste' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Sorcier' ,  'name_short' =>  'Sorcier' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Assassin' ,  'name_short' =>  'Assassin' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Rôdeur' ,  'name_short' =>  'Rôdeur' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Aède' ,  'name_short' =>  'Aède' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Clerc' ,  'name_short' =>  'Clerc' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Gladiateur' ,  'name_short' =>  'Gladiateur' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Templier' ,  'name_short' =>  'Templier' );
	
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elyos' ,  'name_short' =>  'Elyos' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Asmodian' ,  'name_short' =>  'Asmodian' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Balaur' ,  'name_short' =>  'Balaur' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elyséens' ,  'name_short' =>  'Elyséens' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Asmodiens' ,  'name_short' =>  'Asmodiens' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Balaur' ,  'name_short' =>  'Balaur' );
	
	$sql_ary[] = array( 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unbekannt' ,  'name_short' =>  'Unbekannt' );
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Beschwörer' ,  'name_short' =>  'Beschwörer' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Zauberer' ,  'name_short' =>  'Zauberer' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Assassinen' ,  'name_short' =>  'Assassinen' );
	$sql_ary[] = array( 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Jäger' ,  'name_short' =>  'Jäger' );
	$sql_ary[] = array( 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Kantor' ,  'name_short' =>  'Kantor' );
	$sql_ary[] = array( 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Kleriker' ,  'name_short' =>  'Kleriker' );
	$sql_ary[] = array( 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Gladiator' ,  'name_short' =>  'Gladiator' );
	$sql_ary[] = array( 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Templer' ,  'name_short' =>  'Templer' );
	
	$sql_ary[] = array( 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Elyos' ,  'name_short' =>  'Elyos' );
	$sql_ary[] = array( 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Asmodier' , 'name_short' =>  'Asmodian' );
	$sql_ary[] = array( 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Balaur' ,  'name_short' =>  'Balaur' );

	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
	
}



?>