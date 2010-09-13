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

function install_daoc($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
   
    $sql_ary = array();
    // class general
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );

    // class Albion   
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Armsman', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Cabalist', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Cleric', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Friar', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Heretic', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Infiltrator', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Mercenary', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Minstrel', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '10', 'class_name' => 'Necromancer', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '11', 'class_name' => 'Paladin', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '12', 'class_name' => 'Reaver', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '13', 'class_name' => 'Scout', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '14', 'class_name' => 'Sorcerer', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '15', 'class_name' => 'Theurgist', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '16', 'class_name' => 'Wizard', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    
   // class Hibernia
    $sql_ary[] = array('class_id' => '17', 'class_name' => 'Animist', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '18', 'class_name' => 'Bainshee', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '19', 'class_name' => 'Bard', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '20', 'class_name' => 'Blademaster', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '21', 'class_name' => 'Champion', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '22', 'class_name' => 'Druid', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '23', 'class_name' => 'Eldritch', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '24', 'class_name' => 'Enchanter', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '25', 'class_name' => 'Hero', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '26', 'class_name' => 'Mentalist', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '27', 'class_name' => 'Nightshade', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '28', 'class_name' => 'Ranger', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '29', 'class_name' => 'Valewalker', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '30', 'class_name' => 'Vampiir', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '31', 'class_name' => 'Warden', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
   
    // class Midgard
    $sql_ary[] = array('class_id' => '32',  'class_name' => 'Berserker', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '33',  'class_name' => 'Bonedancer', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '34', 'class_name' => 'Healer', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '35', 'class_name' => 'Hunter', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '36', 'class_name' => 'Runemaster', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );    
    $sql_ary[] = array('class_id' => '37', 'class_name' => 'Savage', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '38', 'class_name' => 'Shadowblade', 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '39', 'class_name' => 'Shaman', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '40', 'class_name' => 'Skald', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '41', 'class_name' => 'Spiritmaster', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '42', 'class_name' => 'Thane', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );                                                                                        
    $sql_ary[] = array('class_id' => '43', 'class_name' => 'Valkyrie', 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '44', 'class_name' => 'Warlock', 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '45', 'class_name' => 'Warrior', 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 50 );

    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Albion' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Hibernian' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'Midgard' );
    $sql_ary[] = array('faction_id' => 4, 'faction_name' => 'DaoC' );
    
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
   	// roles
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'roles');
    $sql_ary = array();
    $sql_ary[] = array('role_id' => 1, 'role_name' => 'Light tank' );
    $sql_ary[] = array('role_id' => 2, 'role_name' => 'Heavy tank' );
    $sql_ary[] = array('role_id' => 3, 'role_name' => 'Caster' );
    $sql_ary[] = array('role_id' => 4, 'role_name' => 'Healer' );
    $sql_ary[] = array('role_id' => 5, 'role_name' => 'Hybrid' );        
    $db->sql_multi_insert( $bbdkp_table_prefix . 'roles', $sql_ary);
    unset ($sql_ary); 
    
    // races
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Briton' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Saracen' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Avalonian' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Highlander' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'Inconnu' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Half Ogre' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 7, 'race_name' => 'Minotaur' , 'race_faction_id' => 4 );
    $sql_ary[] = array('race_id' => 8, 'race_name' => 'Celt' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 9, 'race_name' => 'Lurikeen' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 10, 'race_name' => 'Firbolg' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 11, 'race_name' => 'Elf' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 12, 'race_name' => 'Sylvan' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 13, 'race_name' => 'Shar' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 14, 'race_name' => 'Kobold' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 15, 'race_name' => 'Dwarf' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 16, 'race_name' => 'Norseman' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 17, 'race_name' => 'Troll' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 18, 'race_name' => 'Valkyn' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 19, 'race_name' => 'Frostalf' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 20, 'race_name' => 'Deifrang' , 'race_faction_id' => 3 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);
    

    // bossprogress for DaoC
    // no info exists on this so we have entered dummy values

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
		$sql_ary[] = array('config_name' =>  'pb_dummyboss', 'config_value' => 'Dummy boss'  );
	    $sql_ary[] = array('config_name' =>  'pz_dummyzone', 'config_value' => 'Dummy Zone'  );	 
	    $sql_ary[] = array('config_name' =>  'sz_dummyzone', 'config_value' => '1'  );
		
		$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config' , $sql_ary);
	}

	unset ($sql_ary);	

    // Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();
	$sql_ary[] = array('name' => 'dummyboss' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dummyzone' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    
    // dkp system bbeqdkp_dkpsystem 
    // set to default
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);

}

/*
 * new boss progress data for daoc
 * generated with the spreadsheet
 * 
 */
function install_daoc_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_zonetable' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_bosstable' )))
	{
		$sql_ary = array ();
		$sql_ary[] = array( 'id' => 1 , 'imagename' =>  'dummyzone' , 'game' =>  'daoc' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '' ,  'showzone' =>  1);
		
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );

		$sql_ary[] = array('id' => 1 ,  'imagename' =>  'dummyboss' , 'game' =>  'daoc' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0' , 'showboss' =>  1     );
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );

		$sql_ary[] = array( 'id' => 1 , 'attribute_id' => '1', 'language' =>  'en' , 'attribute' =>  'zone' , 'name' =>  'Dummy Zone' ,  'name_short' =>  'Dummy Zone' );
		$sql_ary[] = array( 'id' => 2 , 'attribute_id' => '1', 'language' =>  'en' , 'attribute' =>  'boss' , 'name' =>  'Dummy Boss' ,  'name_short' =>  'Dummy Boss' );
		
		$sql_ary[] = array( 'id' => 3 , 'attribute_id' => '1', 'language' =>  'fr' , 'attribute' =>  'zone' , 'name' =>  'Zone par défaut' ,  'name_short' =>  'Zone par défaut' );
		$sql_ary[] = array( 'id' => 4 , 'attribute_id' => '1', 'language' =>  'fr' , 'attribute' =>  'boss' , 'name' =>  'Boss par défaut' ,  'name_short' =>  'Boss par défaut' );
		

		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_language', $sql_ary );
		unset ( $sql_ary );
		
		
	}
}




?>