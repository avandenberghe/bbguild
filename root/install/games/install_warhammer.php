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

function install_warhammer($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
    $sql_ary = array();

    // class : 
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Light Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Witch Elf', 'class_armor_type' => 'Light Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Sorcerer', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Disciple of Khaine', 'class_armor_type' => 'Medium Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Chosen', 'class_armor_type' => 'Heavy Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Marauder', 'class_armor_type' => 'Medium Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Zealot', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Magus', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Squig Herder', 'class_armor_type' => 'Light Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Black Orc', 'class_armor_type' => 'Heavy Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '10', 'class_name' => 'Shaman', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '11', 'class_name' => 'Rune Priest', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '12', 'class_name' => 'Iron Breaker', 'class_armor_type' => 'Heavy Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '13', 'class_name' => 'Engineer', 'class_armor_type' => 'Medium Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '14', 'class_name' => 'Witch Hunter', 'class_armor_type' => 'Light Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '15', 'class_name' => 'Bright Wizard', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '16', 'class_name' => 'Warrior Priest', 'class_armor_type' => 'Medium Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '17', 'class_name' => 'Archmage', 'class_armor_type' => 'Robe' , 'class_min_level' => 1 , 'class_max_level'  => 40 );    
    $sql_ary[] = array('class_id' => '18', 'class_name' => 'Swordmaster', 'class_armor_type' => 'Heavy Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );    
    $sql_ary[] = array('class_id' => '19', 'class_name' => 'Shadow Warrior', 'class_armor_type' => 'Light Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );
    $sql_ary[] = array('class_id' => '20', 'class_name' => 'White Lion', 'class_armor_type' => 'Medium Armor' , 'class_min_level' => 1 , 'class_max_level'  => 40 );    
    
    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
   	unset ($sql_ary); 
    
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Order' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'Destruction' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 
    
    // races : note we use blizz id
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 0, 'race_name' => 'Unknown' , 'race_faction_id' => 0 );
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Dwarf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'The Empire' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'High Elves' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Dark Elves' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'Chaos' , 'race_factio<n_id' => 2 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Greenskins' , 'race_faction_id' => 2 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);

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
	$sql_ary[] = array('config_name' =>  'pb_dummy', 'config_value' => 'Dummy boss'  );
	$sql_ary[] = array('config_name' =>  'pz_dummyzone', 'config_value' => 'Dummy Zone'  );
	$sql_ary[] = array('config_name' =>  'sz_dummyzone', 'config_value' => '1'  );

	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
	unset ($sql_ary);	

    // Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();
	$sql_ary[] = array('name' => 'dummy' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dummyzone' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    
    // dkp system bbeqdkp_dkpsystem 
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
		
}  


function install_warhammer_rc2($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
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
        
        $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config' , $sql_ary);
        
        unset ($sql_ary); 
        // altdorf     
        $sql_ary = array();
        $sql_ary[] = array('config_name' =>  'pb_kokrit', 'config_value' => 'Kokrit Man-Eater'  );
        $sql_ary[] = array('config_name' =>  'pb_bulbous', 'config_value' => 'Bulbous One'  );
        $sql_ary[] = array('config_name' =>  'pb_fangchitter', 'config_value' => 'Prot and Vermer Fangchitter'  );
        $sql_ary[] = array('config_name' =>  'pb_vitchek', 'config_value' => 'Master Moulder Vitchek'  );
        $sql_ary[] = array('config_name' =>  'pb_goradian', 'config_value' => 'Goradian the Creator'  );
        
        // sacellum
        $sql_ary[] = array('config_name' =>  'pb_ghalmar', 'config_value' => 'Ghalmar Ragehorn'  );
        $sql_ary[] = array('config_name' =>  'pb_guzhak', 'config_value' => 'Guzhak the Betrayer'  );
        $sql_ary[] = array('config_name' =>  'pb_vul', 'config_value' => 'Vul The Bloodchosen'  );
        $sql_ary[] = array('config_name' =>  'pb_hoarfrost', 'config_value' => 'Hoarfrost'  );
        $sql_ary[] = array('config_name' =>  'pb_sebcraw', 'config_value' => 'Sebcraw the Discarded'  );
        $sql_ary[] = array('config_name' =>  'pb_thunder', 'config_value' => 'Slorth and Lorth Thunderbelly'  );
        $sql_ary[] = array('config_name' =>  'pb_breeder', 'config_value' => 'Snaptail the Breeder'  );
        $sql_ary[] = array('config_name' =>  'pb_goremane', 'config_value' => 'Goremane'  );	
        $sql_ary[] = array('config_name' =>  'pb_viraxil', 'config_value' => 'Viraxil the Broken'  );
        
        // mount gunbad
        $sql_ary[] = array('config_name' =>  'pb_griblik', 'config_value' => 'Griblik da Stinka'  );
        $sql_ary[] = array('config_name' =>  'pb_bilebane', 'config_value' => 'Bilebane the Rager'  );
        $sql_ary[] = array('config_name' =>  'pb_garrolath', 'config_value' => 'Garrolath the Poxbearer'  );
        $sql_ary[] = array('config_name' =>  'pb_foulm', 'config_value' => 'Foul Mouf da \'ungry'  );
        $sql_ary[] = array('config_name' =>  'pb_kurga', 'config_value' => 'Kurga da Squig-Maka'  );
        $sql_ary[] = array('config_name' =>  'pb_glomp', 'config_value' => 'Glomp the Squig Masta'  );                        
        $sql_ary[] = array('config_name' =>  'pb_solithex', 'config_value' => 'Solithex'  );
                            
        // stairs
        $sql_ary[] = array('config_name' =>  'pb_borzar', 'config_value' => 'Borzar Rageborn'  );
        $sql_ary[] = array('config_name' =>  'pb_gahlvoth', 'config_value' => 'Gahlvoth Darkrage'  );
        $sql_ary[] = array('config_name' =>  'pb_azuk', 	'config_value' => 'Azuk\'Thul'  );
        $sql_ary[] = array('config_name' =>  'pb_thar', 	'config_value' => 'Thar\'lgnan'  );
        $sql_ary[] = array('config_name' =>  'pb_urlf', 	'config_value' => 'Urlf Daemonblessed'  );                        
        $sql_ary[] = array('config_name' =>  'pb_garithex', 'config_value' => 'Garithex the Mountain'  );
        $sql_ary[] = array('config_name' =>  'pb_chorek', 'config_value' => 'Chorek the Unstoppable'  );
        $sql_ary[] = array('config_name' =>  'pb_slarith', 'config_value' => 'Lord Slaurith'  );            
        $sql_ary[] = array('config_name' =>  'pb_wrackspite', 'config_value' => 'Wrackspite'  );
        $sql_ary[] = array('config_name' =>  'pb_clawfang', 'config_value' => 'Clawfang and Doomspike'  );
        $sql_ary[] = array('config_name' =>  'pb_zekaraz', 'config_value' => 'Zekaraz the Bloodcaller'  );
        $sql_ary[] = array('config_name' =>  'pb_kaarn', 'config_value' => 'Kaarn the Vanquisher'  );                
        $sql_ary[] = array('config_name' =>  'pb_skullord', 'config_value' => 'Skull Lord Var\'Ithrok'  );             
        $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
        unset ($sql_ary);	
        
        // Zone names     
        $sql_ary[] = array('config_name' =>  'pz_altdorf', 'config_value' => 'Altdorf Sewers'  );
        $sql_ary[] = array('config_name' =>  'pz_sacellum', 'config_value' => 'The Sacellum Dungeon'  );
        $sql_ary[] = array('config_name' =>  'pz_gunbad', 'config_value' => 'Mount Gunbad'  );
        $sql_ary[] = array('config_name' =>  'pz_stair', 'config_value' => 'The Bastion Stair'  );
        $sql_ary[] = array('config_name' =>  'pz_warpblade', 'config_value' => 'Warpblade Tunnels'  );
        $sql_ary[] = array('config_name' =>  'pz_sigmar', 'config_value' => 'Sigmar Crypts'  );
        $sql_ary[] = array('config_name' =>  'pz_broodwrought', 'config_value' => 'The Bloodwrought Enclave'  );
        $sql_ary[] = array('config_name' =>  'pz_bilerot', 'config_value' => 'The Bilerot Burrow'  );
        $sql_ary[] = array('config_name' =>  'pz_lostvale', 'config_value' => 'The Lost Vale'  );		
        $sql_ary[] = array('config_name' =>  'pz_vulture', 'config_value' => 'Tomb of the Vulture Lord'  );	
        $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
        unset ($sql_ary);
        
        // Zone show     
        $sql_ary[] = array('config_name' =>  'sz_altdorf', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_sacellum', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_gunbad', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_stair', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_warpblade', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_sigmar', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_broodwrought', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_bilerot', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_lostvale', 'config_value' => '1'  );
        $sql_ary[] = array('config_name' =>  'sz_vulture', 'config_value' => '1'  );	
        $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
        unset ($sql_ary);	
    	
	}
    


    // Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();
	$sql_ary[] = array('name' => 'altdorf' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'sacellum' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gunbad' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'stair' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'warpblade' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'sigmar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'broodwrought' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'bilerot' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'lostvale' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vulture' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );				
	
	$sql_ary[] = array('name' => 'kokrit' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'bulbous' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'fangchitter' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vitchek' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'goradian' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	$sql_ary[] = array('name' => 'ghalmar' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'guzhak' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'hoarfrost' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'sebcraw' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thunder' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'breeder' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'goremane' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'viraxil' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	$sql_ary[] = array('name' => 'griblik' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'bilebane' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'garrolath' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'foulm' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kurga' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'glomp' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'solithex' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	$sql_ary[] = array('name' => 'borzar' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gahlvoth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'azuk' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'urlf' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'garithex' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'chorek' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'garrolath' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'slarith' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'wrackspite' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'clawfang' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zekaraz' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kaarn' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'skullord' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    
		
}  


/*
 * new boss progress data for warhammer
 * generated with the spreadsheet
 * 
 */
function install_war_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		$sql_ary = array ();
		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Altdorf Sewers', 'zonename_short' =>  'Altdorf Sewers' , 'imagename' =>  'altdorf' , 'game' =>  'warhammer' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '0');
		$sql_ary[] = array( 'id' => 2 , 'zonename' => 'The Sacellum Dungeon', 'zonename_short' =>  'The Sacellum Dungeon' , 'imagename' =>  'sacellum' , 'game' =>  'warhammer' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '0');
		$sql_ary[] = array( 'id' => 3 , 'zonename' => 'Mount Gunbad', 'zonename_short' =>  'Mount Gunbad' , 'imagename' =>  'gunbad' , 'game' =>  'warhammer' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '0');
		$sql_ary[] = array( 'id' => 4 , 'zonename' => 'The Bastion Stair', 'zonename_short' =>  'The Bastion Stair' , 'imagename' =>  'stair' , 'game' =>  'warhammer' ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '0');
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );

		$sql_ary[] = array('id' => 1 , 'bossname' => 'Kokrit Man-Eater' , 'bossname_short' => 'Kokrit', 'imagename' =>  'kokrit' , 'game' =>  'warhammer' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '19409' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 2 , 'bossname' => 'Bulbous One' , 'bossname_short' => 'Bulbous', 'imagename' =>  'bulbous' , 'game' =>  'warhammer' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '3650' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 3 , 'bossname' => 'Prot and Vermer Fangchitter' , 'bossname_short' => 'Fangchitter', 'imagename' =>  'fangchitter' , 'game' =>  'warhammer' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '3651' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 4 , 'bossname' => 'Master Moulder Vitchek' , 'bossname_short' => 'Vitchek', 'imagename' =>  'vitchek' , 'game' =>  'warhammer' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '18762' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 5 , 'bossname' => 'Goradian the Creator' , 'bossname_short' => 'Goradian', 'imagename' =>  'goradian' , 'game' =>  'warhammer' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '33401' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 6 , 'bossname' => 'Ghalmar Ragehorn' , 'bossname_short' => 'Ghalmar', 'imagename' =>  'ghalmar' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '25721' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 7 , 'bossname' => 'Guzhak the Betrayer' , 'bossname_short' => 'Guzhak', 'imagename' =>  'guzhak' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '22044' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 8 , 'bossname' => 'Vul The Bloodchosen' , 'bossname_short' => 'Vul', 'imagename' =>  'vul' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '33173' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 9 , 'bossname' => 'Hoarfrost' , 'bossname_short' => 'Hoarfrost', 'imagename' =>  'hoarfrost' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '10256' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 10 , 'bossname' => 'Sebcraw the Discarded' , 'bossname_short' => 'Sebcraw', 'imagename' =>  'sebcraw' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '26812' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 11 , 'bossname' => 'Slorth and Lorth Thunderbelly' , 'bossname_short' => 'Thunderbelly', 'imagename' =>  'thunder' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '93573' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 12 , 'bossname' => 'Snaptail the Breeder' , 'bossname_short' => 'Snaptail', 'imagename' =>  'breeder' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '33172' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 13 , 'bossname' => 'Goremane' , 'bossname_short' => 'Goremane', 'imagename' =>  'goremane' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '33182' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 14 , 'bossname' => 'Viraxil the Broken' , 'bossname_short' => 'Viraxil', 'imagename' =>  'viraxil' , 'game' =>  'warhammer' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '33181' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 15 , 'bossname' => 'Griblik da Stinka' , 'bossname_short' => 'Griblik', 'imagename' =>  'griblik' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '36549' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 16 , 'bossname' => 'Bilebane the Rager' , 'bossname_short' => 'Bilebane', 'imagename' =>  'bilebane' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '36547' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 17 , 'bossname' => 'Garrolath the Poxbearer' , 'bossname_short' => 'Garrolath', 'imagename' =>  'garrolath' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '38234' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 18 , 'bossname' => 'Foul Mouf da \'ungry' , 'bossname_short' => 'Foul', 'imagename' =>  'foulm' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '38623' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 19 , 'bossname' => 'Kurga da Squig-Maker' , 'bossname_short' => 'Kurga', 'imagename' =>  'kurga' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '38624' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 20 , 'bossname' => 'Glomp the Squig Masta' , 'bossname_short' => 'Glomp', 'imagename' =>  'glomp' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '38829' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 21 , 'bossname' => 'Solithex' , 'bossname_short' => 'Solithex', 'imagename' =>  'solithex' , 'game' =>  'warhammer' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '37964' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 22 , 'bossname' => 'Borzar Rageborn' , 'bossname_short' => 'Rageborn', 'imagename' =>  'borzar' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '9227' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 23 , 'bossname' => 'Gahlvoth Darkrage' , 'bossname_short' => 'Gahlvoth', 'imagename' =>  'gahlvoth' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '45224' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 24 , 'bossname' => 'Azuk\'Thul' , 'bossname_short' => 'Azuk\'Thul', 'imagename' =>  'azuk' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '47390' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 25 , 'bossname' => 'Thar\'lgnan' , 'bossname_short' => 'Thar\'lgnan', 'imagename' =>  'thar' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '45084' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 26 , 'bossname' => 'Urlf Daemonblessed' , 'bossname_short' => 'Urlf', 'imagename' =>  'urlf' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '7622' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 27 , 'bossname' => 'Garithex the Mountain' , 'bossname_short' => 'Garithex', 'imagename' =>  'garithex' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '7597' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 28 , 'bossname' => 'Chorek the Unstoppable' , 'bossname_short' => 'Chorek', 'imagename' =>  'chorek' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '49164' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 29 , 'bossname' => 'Lord Slaurith' , 'bossname_short' => 'Slaurith', 'imagename' =>  'slarith' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '48112' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 30 , 'bossname' => 'Wrackspite' , 'bossname_short' => 'Wrackspite', 'imagename' =>  'wrackspite' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '16078' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 31 , 'bossname' => 'Clawfang and Doomspike' , 'bossname_short' => 'Clawfang', 'imagename' =>  'clawfang' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '46327' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 32 , 'bossname' => 'Zekaraz the Bloodcaller' , 'bossname_short' => 'Zekaraz', 'imagename' =>  'zekaraz' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '46325' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 33 , 'bossname' => 'Kaarn the Vanquisher' , 'bossname_short' => 'Kaarn', 'imagename' =>  'kaarn' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '46330' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 34 , 'bossname' => 'Skull Lord Var\'Ithrok' , 'bossname_short' => 'Var\'Ithrok', 'imagename' =>  'skullord' , 'game' =>  'warhammer' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '64106' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
				
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );
		
		
	}
}




?>