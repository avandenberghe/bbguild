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

function install_aion($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
   
    $sql_ary = array();
    // class general
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 50 );

    // sub classes, excluding the original 4 classes, which are irrelavant end game 
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Spiritmaster', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Sorcerer', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Assassin', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Ranger', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Chanter', 'class_armor_type' => 'Chanter' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Cleric', 'class_armor_type' => 'Cleric' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Gladiator', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Templar', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 50 );
    

    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Light' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Darkness' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'NPC' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
   	// roles
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'roles');
    $sql_ary = array();
    $sql_ary[] = array('role_id' => 1, 'role_name' => 'Healer' );
    $sql_ary[] = array('role_id' => 2, 'role_name' => 'Hybrid Support' );
    $sql_ary[] = array('role_id' => 3, 'role_name' => 'Caster DPS' );
    $sql_ary[] = array('role_id' => 4, 'role_name' => 'Melee DPS' );
	$sql_ary[] = array('role_id' => 5, 'role_name' => 'Off Tank' );      
    $sql_ary[] = array('role_id' => 5, 'role_name' => 'Tank' );        
    $db->sql_multi_insert( $bbdkp_table_prefix . 'roles', $sql_ary);
    unset ($sql_ary); 
    
	
    // races (No races, only factions, dummy value)
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Elyos' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Asmodian' , 'race_faction_id' => 2 );
    // balaur race is non playable but we add them anyway
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Balaur' , 'race_faction_id' => 3 );
    
    
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);
	
    // bossprogress for aion
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

?>