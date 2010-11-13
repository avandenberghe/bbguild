<?php
/**
 * bbdkp FFXI install data
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 * 
 */


/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function install_ffxi()
{
    global  $db, $table_prefix, $umil, $user;
    
    // class : 
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_classes');
    $sql_ary = array();
    $sql_ary[] = array('class_id' => 0, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Unknown_small' );
    $sql_ary[] = array('class_id' => 1, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Warrior_small' );
    $sql_ary[] = array('class_id' => 2, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Monk_small' );
    $sql_ary[] = array('class_id' => 3, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Thief_small' );
    $sql_ary[] = array('class_id' => 4, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_White_Mage_small' ); 
    $sql_ary[] = array('class_id' => 5, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Black_Mage_small' ); 
    $sql_ary[] = array('class_id' => 6, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Blue_Mage_small' );
    $sql_ary[] = array('class_id' => 7, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_Red_Mage_small' );  
    $sql_ary[] = array('class_id' => 8, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Paladin_small' );
    $sql_ary[] = array('class_id' => 9, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Dark_Knight_small' );
    $sql_ary[] = array('class_id' => 10, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Dragoon_small' );
    $sql_ary[] = array('class_id' => 11, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Ninja_small' );
    $sql_ary[] = array('class_id' => 12, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Samurai_small' );
    $sql_ary[] = array('class_id' => 13, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Summoner_small' );
    $sql_ary[] = array('class_id' => 14, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Ranger_small' );
    $sql_ary[] = array('class_id' => 15, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Dancer_small' );
    $sql_ary[] = array('class_id' => 16, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Scholar_small' );
    $sql_ary[] = array('class_id' => 17, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Corsair_small' );
    $sql_ary[] = array('class_id' => 18, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Bard_small' );
    $sql_ary[] = array('class_id' => 19, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Beastmaster_small' );
    $sql_ary[] = array('class_id' => 20, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_Puppetmaster_small' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_classes', $sql_ary);
   	unset ($sql_ary); 
   	
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Bastok' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'San d\'Oria' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'Windurst' );
    $sql_ary[] = array('faction_id' => 4, 'faction_name' => 'Jueno' );
    $db->sql_multi_insert( $table_prefix . 'bbdkp_factions', $sql_ary);
    unset ($sql_ary); 
    
    // races
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_faction_id' => 3 ); //Unknown
    $sql_ary[] = array('race_id' => 2, 'race_faction_id' => 1 ); //Galka
    $sql_ary[] = array('race_id' => 3, 'race_faction_id' => 1 ); //Hume
    $sql_ary[] = array('race_id' => 4, 'race_faction_id' => 2 ); ///Elvaan
    $sql_ary[] = array('race_id' => 5, 'race_faction_id' => 3 ); //Tarutaru
    $sql_ary[] = array('race_id' => 6, 'race_faction_id' => 3 ); //Mithra
    $db->sql_multi_insert( $table_prefix . 'bbdkp_races', $sql_ary);

    // dkp system  set to default
    unset ($sql_ary); 
    $db->sql_query('TRUNCATE TABLE ' . $table_prefix . 'bbdkp_dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' );
	$db->sql_multi_insert( $table_prefix . 'bbdkp_dkpsystem', $sql_ary);
	
	unset ( $sql_ary );
	$sql_ary = array ();
	$sql_ary[] = array( 'id' => 1 , 'imagename' =>  'dummyzone' , 'game' =>  'ffxi' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '' ,  'showzone' =>  1);
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_zonetable', $sql_ary );

	unset ( $sql_ary );
	$sql_ary[] = array('id' => 1 ,  'imagename' =>  'dummyboss' , 'game' =>  'ffxi' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0' , 'showboss' =>  1     );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_bosstable', $sql_ary );

	unset ( $sql_ary );
	$sql_ary[] = array( 'id' => 1 , 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'zone' , 'name' =>  'Dummy Zone' ,  'name_short' =>  'Dummy Zone' );
	$sql_ary[] = array( 'id' => 2 , 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'boss' , 'name' =>  'Dummy Boss' ,  'name_short' =>  'Dummy Boss' );
	
	$sql_ary[] = array( 'id' => 3 , 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'zone' , 'name' =>  'Zone par défaut' ,  'name_short' =>  'Zone par défaut' );
	$sql_ary[] = array( 'id' => 4 , 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'boss' , 'name' =>  'Boss par défaut' ,  'name_short' =>  'Boss par défaut' );
	
	$sql_ary[] = array( 'id' => 5 , 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 6 , 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'id' => 7 , 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
	$sql_ary[] = array( 'id' => 8 , 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
	$sql_ary[] = array( 'id' => 9 , 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
	$sql_ary[] = array( 'id' => 10 , 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
	$sql_ary[] = array( 'id' => 11 , 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
	$sql_ary[] = array( 'id' => 12 , 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
	$sql_ary[] = array( 'id' => 13 , 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'id' => 14 , 'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
	$sql_ary[] = array( 'id' => 15 , 'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
	$sql_ary[] = array( 'id' => 16 , 'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
	$sql_ary[] = array( 'id' => 17 , 'attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
	$sql_ary[] = array( 'id' => 18 , 'attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
	$sql_ary[] = array( 'id' => 19 , 'attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'id' => 20 , 'attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
	$sql_ary[] = array( 'id' => 21 , 'attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
	$sql_ary[] = array( 'id' => 22 , 'attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
	$sql_ary[] = array( 'id' => 23 , 'attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array( 'id' => 24 , 'attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
	$sql_ary[] = array( 'id' => 25 , 'attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );

	$sql_ary[] = array( 'id' => 26 , 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 27 , 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'id' => 28 , 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
	$sql_ary[] = array( 'id' => 29 , 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
	$sql_ary[] = array( 'id' => 30 , 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
	$sql_ary[] = array( 'id' => 31 , 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
	$sql_ary[] = array( 'id' => 32 , 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
	$sql_ary[] = array( 'id' => 33 , 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
	$sql_ary[] = array( 'id' => 34 , 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'id' => 35 , 'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
	$sql_ary[] = array( 'id' => 36 , 'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
	$sql_ary[] = array( 'id' => 37 , 'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
	$sql_ary[] = array( 'id' => 38 , 'attribute_id' => 12, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
	$sql_ary[] = array( 'id' => 39 , 'attribute_id' => 13, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
	$sql_ary[] = array( 'id' => 40 , 'attribute_id' => 14, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'id' => 41 , 'attribute_id' => 15, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
	$sql_ary[] = array( 'id' => 42 , 'attribute_id' => 16, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
	$sql_ary[] = array( 'id' => 43 , 'attribute_id' => 17, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
	$sql_ary[] = array( 'id' => 44 , 'attribute_id' => 18, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array( 'id' => 45 , 'attribute_id' => 19, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
	$sql_ary[] = array( 'id' => 46 , 'attribute_id' => 20, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );

	$sql_ary[] = array( 'id' => 65 , 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 66 , 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array( 'id' => 67 , 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
	$sql_ary[] = array( 'id' => 68 , 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
	$sql_ary[] = array( 'id' => 69 , 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
	$sql_ary[] = array( 'id' => 70 , 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
	$sql_ary[] = array( 'id' => 71 , 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
	$sql_ary[] = array( 'id' => 72 , 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
	$sql_ary[] = array( 'id' => 73 , 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array( 'id' => 74 , 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
	$sql_ary[] = array( 'id' => 75 , 'attribute_id' => 10, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
	$sql_ary[] = array( 'id' => 76 , 'attribute_id' => 11, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
	$sql_ary[] = array( 'id' => 77 , 'attribute_id' => 12, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
	$sql_ary[] = array( 'id' => 78 , 'attribute_id' => 13, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
	$sql_ary[] = array( 'id' => 79 , 'attribute_id' => 14, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array( 'id' => 80 , 'attribute_id' => 15, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
	$sql_ary[] = array( 'id' => 81 , 'attribute_id' => 16, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
	$sql_ary[] = array( 'id' => 82 , 'attribute_id' => 17, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
	$sql_ary[] = array( 'id' => 83 , 'attribute_id' => 18, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
	$sql_ary[] = array( 'id' => 84 , 'attribute_id' => 19, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
	$sql_ary[] = array( 'id' => 85 , 'attribute_id' => 20, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );
		
	$sql_ary[] = array( 'id' => 47 , 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 48 , 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
	$sql_ary[] = array( 'id' => 49 , 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
	$sql_ary[] = array( 'id' => 50 , 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
	$sql_ary[] = array( 'id' => 51 , 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
	$sql_ary[] = array( 'id' => 52 , 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
	
	$sql_ary[] = array( 'id' => 53 , 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 54 , 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
	$sql_ary[] = array( 'id' => 55 , 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
	$sql_ary[] = array( 'id' => 56 , 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
	$sql_ary[] = array( 'id' => 57 , 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
	$sql_ary[] = array( 'id' => 58 , 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
	
	$sql_ary[] = array( 'id' => 59 , 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array( 'id' => 60 , 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
	$sql_ary[] = array( 'id' => 61 , 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
	$sql_ary[] = array( 'id' => 62 , 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
	$sql_ary[] = array( 'id' => 63 , 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
	$sql_ary[] = array( 'id' => 64 , 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
	

		
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
}




?>