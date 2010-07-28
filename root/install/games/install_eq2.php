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

function install_eq2($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;

    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
   
    $sql_ary = array();
    // Everquest classes

	$sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Heavy' , 'class_min_level' => 1 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '1', 'class_name' => 'Assassin', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '2', 'class_name' => 'Berserker', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '3', 'class_name' => 'Bruiser', 'class_armor_type' => 'Light' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '4', 'class_name' => 'Brigand', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '5', 'class_name' => 'Coercer', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '6', 'class_name' => 'Conjuror', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '7', 'class_name' => 'Defiler', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '8', 'class_name' => 'Dirge', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '9', 'class_name' => 'Fury', 'class_armor_type' => 'Light' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '10', 'class_name' => 'Guardian', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '11', 'class_name' => 'Illusionist', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '12', 'class_name' => 'Inquisitor', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '13',  'class_name' => 'Monk', 'class_armor_type' => 'Light' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '14', 'class_name' => 'Mystic', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '15', 'class_name' => 'Necromancer', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '16', 'class_name' => 'Paladin', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '17', 'class_name' => 'Ranger', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '18', 'class_name' => 'Shadowknight', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '19', 'class_name' => 'Swashbuckler', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '20', 'class_name' => 'Templar', 'class_armor_type' => 'Heavy' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '21', 'class_name' => 'Troubador', 'class_armor_type' => 'Medium' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '22', 'class_name' => 'Warlock', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '23', 'class_name' => 'Warden', 'class_armor_type' => 'Light' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
	$sql_ary[] = array('class_id' => '24', 'class_name' => 'Wizard', 'class_armor_type' => 'VeryLight' , 'class_min_level' => 20 , 'class_max_level'  => 99 ); 
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
    
    // Everquest 2 races
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 0, 'race_name' => 'Unknown' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Gnome' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Human' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Barbarian' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Dwarf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 5, 'race_name' => 'High Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 6, 'race_name' => 'Dark Elf' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 7, 'race_name' => 'Wood Elf' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 8, 'race_name' => 'Half Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 9, 'race_name' => 'Kerra' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 10, 'race_name' => 'Troll' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 11, 'race_name' => 'Ogre' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 12, 'race_name' => 'Frog' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 13, 'race_name' => 'Iksar' , 'race_faction_id' => 2 );
    $sql_ary[] = array('race_id' => 14, 'race_name' => 'Erudite' , 'race_faction_id' => 3 );
    $sql_ary[] = array('race_id' => 15, 'race_name' => 'Halfling' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 16, 'race_name' => 'Ratonga' , 'race_faction_id' => 3 );
    
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);
    
    unset ($sql_ary);

    // bossprogress for Everquest2

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
	
    // boss list     
    $sql_ary = array();
    $sql_ary[] = array('config_name' => 'pb_dummy', 'config_value' => 'Dummy boss'  );

    // Trakanon's Lair (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_trakanon', 'config_value' => 'Trakanon' );

    // Veeshan's Peak (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_kluzen', 'config_value' => 'Kluzen the Protector' );
    $sql_ary[] = array('config_name' => 'pb_ekron', 'config_value' => 'Elder Ekron' );
    $sql_ary[] = array('config_name' => 'pb_silverwing', 'config_value' => 'Silverwing' );
    $sql_ary[] = array('config_name' => 'pb_travenro', 'config_value' => 'Travenro the Skygazer' );
    $sql_ary[] = array('config_name' => 'pb_phara_dar', 'config_value' => 'Phara Dar' );
    $sql_ary[] = array('config_name' => 'pb_taskmaster_nichok', 'config_value' => 'Taskmaster Nichok' );
    $sql_ary[] = array('config_name' => 'pb_qunard_ashenclaw', 'config_value' => 'Qunard Ashenclaw' );
    $sql_ary[] = array('config_name' => 'pb_hoshkar', 'config_value' => 'Hoshkar' );
    $sql_ary[] = array('config_name' => 'pb_druushk', 'config_value' => 'Druushk' );
    $sql_ary[] = array('config_name' => 'pb_milyex_vioren', 'config_value' => 'Milyex Vioren' );
    $sql_ary[] = array('config_name' => 'pb_xygoz', 'config_value' => 'Xygoz' );
    $sql_ary[] = array('config_name' => 'pb_nexona', 'config_value' => 'Nexona' );
    
	//The Chamber of Destiny (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_leviathan', 'config_value' => 'Leviathan' );
    
	// Venril Sathir's Lair (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_venril', 'config_value' => 'Venril Sathir' );
    
    // The Temple of Kor-Sha (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_aktar_the_dark', 'config_value' => 'Aktar the Dark' );
    $sql_ary[] = array('config_name' => 'pb_atrebe', 'config_value' => 'Atrebe\'s Statue' );
    $sql_ary[] = array('config_name' => 'pb_ilyan', 'config_value' => 'Ilyan' );
    $sql_ary[] = array('config_name' => 'pb_kodyx', 'config_value' => 'Kodux' );
    $sql_ary[] = array('config_name' => 'pb_selrach', 'config_value' => 'Selrach Di\'Zok' );
    $sql_ary[] = array('config_name' => 'pb_uthtak_the_cruel', 'config_value' => 'Uthtak the Cruel' );
	$sql_ary[] = array('config_name' => 'pb_uzdrak_the_invincible', 'config_value' => 'Uzdrak the Invincible' );
    $sql_ary[] = array('config_name' => 'pb_zarda', 'config_value' => 'Zarda' );
    
    //	 The Protector's Realm (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_adkar_vyx', 'config_value' => 'Adkar Vyx' );
    $sql_ary[] = array('config_name' => 'pb_iztapa_vyx', 'config_value' => 'Iztapa Vyx' );
    $sql_ary[] = array('config_name' => 'pb_wymbulu_vyx', 'config_value' => 'Wymbulu Vyx' );
    $sql_ary[] = array('config_name' => 'pb_zykluk_vyx', 'config_value' => 'Zykluk Vyx' );
    $sql_ary[] = array('config_name' => 'pb_blorgok_the_brutal', 'config_value' => 'Blorgok the Brutal' );
    $sql_ary[] = array('config_name' => 'pb_doomcoil', 'config_value' => 'Doomcoil' );
    $sql_ary[] = array('config_name' => 'pb_imzok', 'config_value' => 'Imzok\'s Revenge' );
    $sql_ary[] = array('config_name' => 'pb_jracol_binari', 'config_value' => 'Jracol Binari' );
    $sql_ary[] = array('config_name' => 'pb_ludmila_kystov', 'config_value' => 'Ludmila Kystov' );
    
    //  The Execution Throne Room (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_pawbuster', 'config_value' => 'Pawbuster' ); 

    //The Tomb of Thuuga (Rise of Kunark) 
    $sql_ary[] = array('config_name' => 'pb_tairiza', 'config_value' => 'Tairiza the Widow Mistress' );    
    
   	 /* Throne of New Tunaria (Echoes of Faydwer)  */
    $sql_ary[] = array('config_name' => 'pb_harbinger_of_absolution', 'config_value' => 'The Harbinger of Absolution' );
    $sql_ary[] = array('config_name' => 'pb_vampire_lord_mayong_mistmoore', 'config_value' => 'Vampire Lord Mayong Mistmoore' );  
    
    $sql_ary[] = array('config_name' => 'pb_mayong_mistmoore', 'config_value' => 'Mayong Mistmoore' );
    $sql_ary[] = array('config_name' => 'pb_cheroon', 'config_value' => 'D\'Lizta Cheroon' );   
    $sql_ary[] = array('config_name' => 'pb_kzalk', 'config_value' => 'V\'Tekla K\'Zalk' ); 
    $sql_ary[] = array('config_name' => 'pb_viswin', 'config_value' => 'D\'Lizta Viswin' ); 
    $sql_ary[] = array('config_name' => 'pb_enynti', 'config_value' => 'Enynti' ); 
            
    $sql_ary[] = array('config_name' => 'pb_malkonis', 'config_value' => 'Malkonis D\'Morte' );
    $sql_ary[] = array('config_name' => 'pb_treyloth', 'config_value' => 'Treyloth D\'Kulvith' );
    $sql_ary[] = array('config_name' => 'pb_othysis', 'config_value' => 'Othysis Muravian' );
    $sql_ary[] = array('config_name' => 'pb_zylphax', 'config_value' => 'Zylphax_the_Shredder' );
    
    $sql_ary[] = array('config_name' => 'pb_wuoshi', 'config_value' => 'Wuoshi' );
    $sql_ary[] = array('config_name' => 'pb_galiel_spirithoof', 'config_value' => 'Galiel Spirithoof' );
    $sql_ary[] = array('config_name' => 'pb_treah_greenroot', 'config_value' => 'Treah Greenroot' );
    $sql_ary[] = array('config_name' => 'pb_sariah_the_bloomseeker', 'config_value' => 'Sariah the Bloomseeker' );
    $sql_ary[] = array('config_name' => 'pb_mistress_of_the_veil', 'config_value' => 'Mistress of the Veil' );
    $sql_ary[] = array('config_name' => 'pb_sarik_the_fang', 'config_value' => 'Sarik the Fang' );
    $sql_ary[] = array('config_name' => 'pb_segmented_rumbler', 'config_value' => 'The Segmented Rumbler' );
    $sql_ary[] = array('config_name' => 'pb_elaani_the_collector', 'config_value' => 'Elaani the collector' );
    $sql_ary[] = array('config_name' => 'pb_gardener_thirgen', 'config_value' => 'Gardener Thirgen' );
    $sql_ary[] = array('config_name' => 'pb_the_farstride_unicorn', 'config_value' => 'The Farstride Unicorn' );
    $sql_ary[] = array('config_name' => 'pb_tender_of_the_seedlings', 'config_value' => 'Tender of the Seedlings' );
    
	$sql_ary[] = array('config_name' => 'pb_round_1', 'config_value' => 'Round 1' );
	$sql_ary[] = array('config_name' => 'pb_round_2', 'config_value' => 'Round 2' );
	$sql_ary[] = array('config_name' => 'pb_round_3', 'config_value' => 'Round 3' );
    
	$sql_ary[] = array('config_name' => 'pb_tatr', 'config_value' => 'The Ancient Twisted Root' );
	$sql_ary[] = array('config_name' => 'pb_tatf', 'config_value' => 'The Ancient Twisted Fungus' );
	$sql_ary[] = array('config_name' => 'pb_crusher', 'config_value' => 'The Crusher, Crusher' );
	$sql_ary[] = array('config_name' => 'pb_xuxlaiom', 'config_value' => 'Xuxlaio Master of the Fluttering Wing, Xuxlaio' );
	
	$sql_ary[] = array('config_name' => 'pb_bonesplitter', 'config_value' => 'Bonesplitter' );
	
	$sql_ary[] = array('config_name' => 'pb_gurgul', 'config_value' => 'Gurgul the Warden, Gurgul' );
	$sql_ary[] = array('config_name' => 'pb_kogurgul', 'config_value' => 'Keeper of Gurgul' );
	$sql_ary[] = array('config_name' => 'pb_kog', 'config_value' => 'Keeper of the Gate' );
	$sql_ary[] = array('config_name' => 'pb_final_warden', 'config_value' => 'The Final Warden' );
	$sql_ary[] = array('config_name' => 'pb_gol', 'config_value' => 'The Guardian of Leadership' );
	
	$sql_ary[] = array('config_name' => 'pb_essence_of_fear', 'config_value' => 'Essence of Fear' );
	$sql_ary[] = array('config_name' => 'pb_gnillaw', 'config_value' => 'Gnillaw the Demented' );
	$sql_ary[] = array('config_name' => 'pb_gnorbl', 'config_value' => 'Gnorbl the Playful' );
	$sql_ary[] = array('config_name' => 'pb_vilucidae', 'config_value' => 'Vilucidae the Priest of Thule, Vilucidae' );
	
	$sql_ary[] = array('config_name' => 'pb_alzid', 'config_value' => 'The Slaving Alzid, Alzid' );
	$sql_ary[] = array('config_name' => 'pb_doomright', 'config_value' => 'Doomright Vakrizt, Vakrizt' );
	$sql_ary[] = array('config_name' => 'pb_pardas', 'config_value' => 'Pardas Predd, Predd' );
	$sql_ary[] = array('config_name' => 'pb_kinvah', 'config_value' => 'Doom Prophet Kinvah, Kinvah' );
	$sql_ary[] = array('config_name' => 'pb_ruystad', 'config_value' => 'Doom Ravager Ruystad, Ruystad' );
	$sql_ary[] = array('config_name' => 'pb_cheyak', 'config_value' => 'Doom Reaver Cheyak, Cheyak' );
	$sql_ary[] = array('config_name' => 'pb_uncaged_alzid', 'config_value' => 'The Uncaged Alzid, Alzid' );
	$sql_ary[] = array('config_name' => 'pb_uustalastus', 'config_value' => 'Uustalastus Xiterrax, Xiterrax' );
	$sql_ary[] = array('config_name' => 'pb_doomsworn', 'config_value' => 'Doomsworn Zatrakh, Zatrakh' );
	$sql_ary[] = array('config_name' => 'pb_corsolander', 'config_value' => 'The Corsolander, Corsolander' );
	$sql_ary[] = array('config_name' => 'pb_amdaatk', 'config_value' => 'Euktrzkai Amdaatk, Amdaatk' );
	$sql_ary[] = array('config_name' => 'pb_vyemm', 'config_value' => 'Lord Vyemm, Vilucidae' );
	$sql_ary[] = array('config_name' => 'pb_alzid_prime', 'config_value' => 'Alzid Prime, Vilucidae' );
	
	$sql_ary[] = array('config_name' => 'pb_shadowy_presence', 'config_value' => 'A Shadowy Presence' );
	$sql_ary[] = array('config_name' => 'pb_charged_presence', 'config_value' => 'A Charged Presence' );
	$sql_ary[] = array('config_name' => 'pb_elemental_warder', 'config_value' => 'The Elemental Warder' );
	$sql_ary[] = array('config_name' => 'pb_pain', 'config_value' => 'Pain' );
	$sql_ary[] = array('config_name' => 'pb_suffering', 'config_value' => 'Suffering' );
	$sql_ary[] = array('config_name' => 'pb_bloodbeast', 'config_value' => 'BloodBeast' );
	$sql_ary[] = array('config_name' => 'pb_venekor', 'config_value' => 'Venekor' );
	
	$sql_ary[] = array('config_name' => 'pb_yitzik', 'config_value' => 'Yitzik the Hurler, Yitzik' );
	$sql_ary[] = array('config_name' => 'pb_fitzpitzle', 'config_value' => 'Fitzpitzle' );
	$sql_ary[] = array('config_name' => 'pb_amorphous', 'config_value' => 'Amorphous Drake, Amorphous' );
	$sql_ary[] = array('config_name' => 'pb_tarinax', 'config_value' => 'Tarinax the Destroyer, Tarinax' );
	$sql_ary[] = array('config_name' => 'pb_cruor', 'config_value' => 'Cruor Alluvium, Cruor' );
	
	
	$sql_ary[] = array('config_name' => 'pb_slashing_talon', 'config_value' => 'Prophet of The Slashing Talon, Prophet' );
	$sql_ary[] = array('config_name' => 'pb_flapping_wing', 'config_value' => 'Ancient of the Flapping Wings, Ancient' );
	$sql_ary[] = array('config_name' => 'pb_ireth', 'config_value' => 'Ireth the Cold, Ireth' );
	$sql_ary[] = array('config_name' => 'pb_sharti', 'config_value' => 'Sharti of the Flame, Sharti' );
	$sql_ary[] = array('config_name' => 'pb_gorenaire', 'config_value' => 'Gorenaire' );
	$sql_ary[] = array('config_name' => 'pb_talendor', 'config_value' => 'Talendor' );
	
	
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
	unset ($sql_ary);	
	
     /* RoK */
    /* Tomb of Thuuga */
	
	// Zone names     
	$sql_ary[] = array('config_name' => 'pz_dummyzone', 'config_value' => 'Dummy Zone'  );
	
	// 8.4
	$sql_ary[] = array('config_name' => 'pz_trakanon_lair', 'config_value' => 'Trakanon\'s Lair');
	$sql_ary[] = array('config_name' => 'pz_veeshan', 'config_value' => 'Veeshan\'s Peak'  );
	
	// 8.3
	$sql_ary[] = array('config_name' => 'pz_destiny', 'config_value' => 'Chamber of Destiny'  );
	
	// 8.2
	$sql_ary[] = array('config_name' => 'pz_venril_sathir_lair', 'config_value' => 'Venril Sathir\'s Lair'  );
	$sql_ary[] = array('config_name' => 'pz_korsha', 'config_value' => 'Temple of Kor-Sha'  );
	
	// 8.1	
	$sql_ary[] = array('config_name' => 'pz_protector', 'config_value' => 'Protector\'s realm');
	$sql_ary[] = array('config_name' => 'pz_execution', 'config_value' => 'Execution Throne Room'  );
	$sql_ary[] = array('config_name' => 'pz_thuuga', 'config_value' => 'Tomb of Thuuga'  );
	//$sql_ary[] = array('config_name' => 'pz_shard', 'config_value' => 'Shard of Hate'  );
	
	// EoF	
	$sql_ary[] = array('config_name' => 'pz_tunaria', 'config_value' => 'Throne of New Tunaria' );
	$sql_ary[] = array('config_name' => 'pz_mistmoore', 'config_value' => 'Mistmoore\'s Inner Sanctum' );
	$sql_ary[] = array('config_name' => 'pz_freethinkers_hideout', 'config_value' => 'Freethinkers Hideout' );
	$sql_ary[] = array('config_name' => 'pz_emerald_halls', 'config_value' => 'Emerald Halls' );
	$sql_ary[] = array('config_name' => 'pz_clockwork_menace', 'config_value' => 'The Clockwork Menace Factory' );

	//Fallen Dynasty
	$sql_ary[] = array('config_name' => 'pz_xlar', 'config_value' => 'XuxLaios Roost' );
	$sql_ary[] = array('config_name' => 'pz_crustaceans', 'config_value' => 'Cavern of the Crustaceans' );
	
	//Kingdom of Sky
	$sql_ary[] = array('config_name' => 'pz_tol', 'config_value' => 'Trials of Awakened: Trial of Leadership' );
	$sql_ary[] = array('config_name' => 'pz_lyceum', 'config_value' => 'Lyceum of Abhorrence' );
	$sql_ary[] = array('config_name' => 'pz_lab_of_vyemm', 'config_value' => 'The Laboratory of Lord Vyemm' );
	$sql_ary[] = array('config_name' => 'pz_halls_of_the_seeing', 'config_value' => 'Halls of the Seeing' );	
	$sql_ary[] = array('config_name' => 'pz_deathtoll', 'config_value' => 'Deathtoll' );
	$sql_ary[] = array('config_name' => 'pz_awakening', 'config_value' => 'Ascent of the Awakening, Awakening' );
	
	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
	unset ($sql_ary);

	// Zone show     
	$sql_ary[] = array('config_name' => 'sz_dummyzone', 'config_value' => '0'  );
	
	// RoK
	$sql_ary[] = array('config_name' => 'sz_trakanon_lair', 'config_value' => '1'  );
	$sql_ary[] = array('config_name' => 'sz_veeshan', 'config_value' => '1'  );
	
	$sql_ary[] = array('config_name' => 'sz_destiny', 'config_value' => '1'  );
	
	$sql_ary[] = array('config_name' => 'sz_venril_sathir_lair', 'config_value' => '1'  );
	$sql_ary[] = array('config_name' => 'sz_korsha', 'config_value' => '1'  );
	
	$sql_ary[] = array('config_name' => 'sz_protector', 'config_value' => '1');
	$sql_ary[] = array('config_name' => 'sz_execution', 'config_value' => '1'  );
	$sql_ary[] = array('config_name' => 'sz_thuuga', 'config_value' => '1'  );
	//$sql_ary[] = array('config_name' => 'sz_shard', 'config_value' => '1'  );
	
	// EoF	
	$sql_ary[] = array('config_name' => 'sz_tunaria', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_mistmoore', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_freethinkers_hideout', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_emerald_halls', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_clockwork_menace', 'config_value' => '1' );
    
	//Fallen Dynasty
	$sql_ary[] = array('config_name' => 'sz_crustaceans', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_xlar', 'config_value' => '1' );
	
	//Kingdom of Sky	
	$sql_ary[] = array('config_name' => 'sz_tol', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_lyceum', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_lab_of_vyemm', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_halls_of_the_seeing', 'config_value' => '1' );
	$sql_ary[] = array('config_name' => 'sz_deathtoll', 'config_value' => '1' );
	
	$sql_ary[] = array('config_name' => 'sz_awakening', 'config_value' => '1' );
	
	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config', $sql_ary);
	
	unset ($sql_ary);	

    // Zone and Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();

	/* Dummy */ 
	$sql_ary[] = array('name' => 'dummy' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dummyzone' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/********** TIER 8 ************/

	/* RISE OF KUNARK */ 
	
	/* 8,4 trakanon's Lair */
	$sql_ary[] = array('name' => 'trakanon_lair' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'trakanon' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8,4 Veeshan's Peak */ 
	$sql_ary[] = array('name' => 'veeshan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kluzen' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ekron' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );	
	$sql_ary[] = array('name' => 'silverwing' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );	
	$sql_ary[] = array('name' => 'travenro' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'phara_dar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'taskmaster_nichok' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'qunard_ashenclaw' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'hoshkar' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'druushk' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'milyex_vioren' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'xygoz' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'nexona' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8,3 Chamber of Destiny */ 
	$sql_ary[] = array('name' => 'destiny' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'leviathan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8,2 Venril Sathir's Lair */ 
	$sql_ary[] = array('name' => 'venril_sathir_lair' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'venril' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8,2 Temple of Korsha */ 
	$sql_ary[] = array('name' => 'korsha' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'aktar_the_dark' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'atrebe' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ilyan' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kodyx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'selrach' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'uthtak_the_cruel' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'uzdrak_the_invincible' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zarda' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	/* 8,1 Protector's Realm */ 
	$sql_ary[] = array('name' => 'protector' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'adkar_vyx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zykluk_vyx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'iztapa_vyx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'wymbulu_vyx' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'blorgok_the_brutal' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'doomcoil' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'imzok' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'jracol_binari' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ludmila_kystov' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );	

	/* 8,1 Executioner's Throne */ 
	$sql_ary[] = array('name' => 'execution' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'pawbuster' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8,1 Tomb of Thuuga */ 
	$sql_ary[] = array('name' => 'thuuga' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'tairiza' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* 8 Shard of Hate */ 
	//$sql_ary[] = array('name' => 'shard' , 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	

	/********** TIER 7 ************/

	/* Echoes of Faydwer */
	/* Throne of New Tunaria */
	$sql_ary[] = array('name' => 'tunaria','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'harbinger_of_absolution','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'vampire_lord_mayong_mistmoore','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );		
    
	 /* mistmoore */
	$sql_ary[] = array('name' => 'mistmoore','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'mayong_mistmoore','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'cheroon', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'kzalk', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'viswin', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );  
    $sql_ary[] = array('name' => 'enynti', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );    
	
     /* freethinkers_hideout */
    $sql_ary[] = array('name' => 'freethinkers_hideout', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'malkonis', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'treyloth', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'othysis', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'zylphax', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
     /* emerald_halls */
	$sql_ary[] = array('name' => 'emerald_halls', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'wuoshi', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'galiel_spirithoof', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'treah_greenroot', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'sariah_the_bloomseeker', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'mistress_of_the_veil', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'sarik_the_fang', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'segmented_rumbler', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'elaani_the_collector', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'gardener_thirgen','fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'the_farstride_unicorn', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'tender_of_the_seedlings', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

    /* clockwork_menace */
    $sql_ary[] = array('name' => 'clockwork_menace', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $sql_ary[] = array('name' => 'round_1', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'round_2', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'round_3', 'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* FD */
	/* xuxlaio */
	$sql_ary[] = array('name' => 'xlar' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'tatr' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'tatf' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'crusher' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'xuxlaiom' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* crustaceans */
	$sql_ary[] = array('name' => 'crustaceans' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'bonesplitter' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* tol */
	$sql_ary[] = array('name' => 'tol' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gurgul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kogurgul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kog' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'final_warden' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gol' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
    /* lyceum */
	$sql_ary[] = array('name' => 'lyceum' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'essence_of_fear' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gnillaw' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gnorbl' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vilucidae' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* lab_of_vyemm   */
	$sql_ary[] = array('name' => 'lab_of_vyemm' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'alzid' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'doomright' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'pardas' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kinvah' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ruystad' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'cheyak' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'uncaged_alzid' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'uustalastus' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'doomsworn' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'corsolander' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'amdaatk' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vyemm' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'alzid_prime' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	
	/* halls_of_the_seeing */
	$sql_ary[] = array('name' => 'halls_of_the_seeing' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'shadowy_presence' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'charged_presence' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'elemental_warder' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'pain' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'suffering' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'bloodbeast' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'venekor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* deathtoll */
	$sql_ary[] = array('name' => 'deathtoll' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'yitzik' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'fitzpitzle' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'amorphous' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'tarinax' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'cruor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	/* awakening */
	$sql_ary[] = array('name' => 'awakening' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	$sql_ary[] = array('name' => 'slashing_talon' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'flapping_wing' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ireth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'sharti' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gorenaire' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'talendor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    unset ($sql_ary);
    
    // dkp system bbeqdkp_dkpsystem 
    // set to default
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
    

    
}
    
?>