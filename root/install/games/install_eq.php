<?php
/**
 * bbdkp everquest install data
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

function install_eq($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
   
    $sql_ary = array();
    // Everquest classes
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Tank' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Warrior', 'class_armor_type' => 'Tank' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Rogue', 'class_armor_type' => 'Melee' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Monk', 'class_armor_type' => 'Melee' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Ranger', 'class_armor_type' => 'Melee' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Paladin', 'class_armor_type' => 'Tank' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Shadow Knight', 'class_armor_type' => 'Tank' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Bard', 'class_armor_type' => 'Crowd' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Beastlord', 'class_armor_type' => 'Melee' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Cleric', 'class_armor_type' => 'Healer' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '10', 'class_name' => 'Druid', 'class_armor_type' => 'Healer' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '11', 'class_name' => 'Shaman', 'class_armor_type' => 'Healer' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '12', 'class_name' => 'Enchanter', 'class_armor_type' => 'Crowd' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '13', 'class_name' => 'Wizard', 'class_armor_type' => 'Caster' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '14', 'class_name' => 'Necromancer', 'class_armor_type' => 'Caster' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '15', 'class_name' => 'Magician', 'class_armor_type' => 'Caster' , 'class_min_level' => 1 , 'class_max_level'  => 70 );
    $sql_ary[] = array('class_id' => '16', 'class_name' => 'Berserker', 'class_armor_type' => 'Melee' , 'class_min_level' => 1 , 'class_max_level'  => 70 );

    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
  
    
    
    $db->sql_multi_insert($bbdkp_table_prefix . 'indexpage', $sql_ary);
    
    unset ($sql_ary);   	
    // class roles
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'roles');
    $sql_ary = array();
    $sql_ary[] = array('role_id' => 1, 'role_name' => 'Tank' );
    $sql_ary[] = array('role_id' => 2, 'role_name' => 'Melee' );
    $sql_ary[] = array('role_id' => 3, 'role_name' => 'Caster' );
    $sql_ary[] = array('role_id' => 4, 'role_name' => 'Healer' );
    $sql_ary[] = array('role_id' => 5, 'role_name' => 'Crowd' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'roles', $sql_ary);
    unset ($sql_ary); 
    
    
   	// Everquest factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Good' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Evil' );
    $sql_ary[] = array('faction_id' => 3, 'faction_name' => 'Neutral' );

    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
    // Everquest races
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 0, 'race_name' => 'Unknown' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Gnome' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Human' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Barbarian' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Dwarf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'High Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Half Elf' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 7, 'race_name' => 'Dark Elf' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 8, 'race_name' => 'Wood Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 9, 'race_name' => 'Vah Shir' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 10, 'race_name' => 'Troll' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 11, 'race_name' => 'Ogre' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 12, 'race_name' => 'Froglok' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 13, 'race_name' => 'Iksar' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 14, 'race_name' => 'Erudite' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 15, 'race_name' => 'Halfling' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 16, 'race_name' => 'Drakkin' , 'race_faction_id' => 3 );
   
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);

    // bossprogress for Everquest
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
    
    // dkp system bbeqdkp_dkpsystem 
    // set to default
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
		
	
    
}


/*
 * new boss progress data for eq
 * generated with the spreadsheet
 * 
 */
function install_eq_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		$sql_ary = array ();
		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Dummy Zone', 'zonename_short' =>  'Dummy Zone' , 'imagename' =>  'dummyzone' , 'game' =>  'eq' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );

		$sql_ary[] = array('id' => 1 , 'bossname' => 'Dummy Boss' , 'bossname_short' => 'Dummy Boss', 'imagename' =>  'dummyboss' , 'game' =>  'eq' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );	
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );
		
		
	}
}


    
?>