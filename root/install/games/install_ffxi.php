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

function install_ffxi($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
    $sql_ary = array();
    // class : 
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Warrior', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Monk', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Thief', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'White Mage', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Black Mage', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Blue Mage', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Red Mage', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Paladin', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Dark Knight', 'class_armor_type' => 'Plate' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '10', 'class_name' => 'Dragoon', 'class_armor_type' => 'Chain' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '11', 'class_name' => 'Ninja', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '12', 'class_name' => 'Samurai', 'class_armor_type' => 'Chain' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '13', 'class_name' => 'Summoner', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '14', 'class_name' => 'Ranger', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '15', 'class_name' => 'Dancer', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '16', 'class_name' => 'Scholar', 'class_armor_type' => 'Cloth' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '17', 'class_name' => 'Corsair', 'class_armor_type' => 'Chain' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '18', 'class_name' => 'Bard', 'class_armor_type' => 'Chain' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '19', 'class_name' => 'Beastmaster', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $sql_ary[] = array('class_id' => '20', 'class_name' => 'Puppetmaster', 'class_armor_type' => 'Leather' , 'class_min_level' => 1 , 'class_max_level'  => 75 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
   	
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Bastok' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'San d\'Oria' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'Windurst' );
    $sql_ary[] = array('faction_id' => 4, 'faction_name' => 'Jueno' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
    // races
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Unknown' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Galka' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Hume' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Elvaan' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'Tarutaru' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Mithra' , 'race_faction_id' => 3 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);
    unset ($sql_ary); 

	$db->sql_multi_insert($bbdkp_table_prefix . 'indexpage', $sql_ary);
    
	
    // bossprogress for FFXI
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
    // boss list     
    $sql_ary = array();

    // Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();
	$sql_ary[] = array('name' => 'dummyboss' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dummyzone' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    unset ($sql_ary);
    
    // dkp system  set to default
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
	
}

?>