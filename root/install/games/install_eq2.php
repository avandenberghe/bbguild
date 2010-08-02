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

/*
 * new boss progress data for eq
 * generated with the spreadsheet
 * 
 */
function install_eq2_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_zonetable' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_bosstable' )))
	{
		$sql_ary = array ();
		
		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Trakanon\'s Lair', 'zonename_short' =>  'Trakanon\'s Lair' , 'imagename' =>  'trakanon_lair' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 2 , 'zonename' => 'Veeshan\'s Peak', 'zonename_short' =>  'Veeshan\'s Peak' , 'imagename' =>  'veeshan' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 3 , 'zonename' => 'Chamber of Destiny', 'zonename_short' =>  'Chamber of Destiny' , 'imagename' =>  'destiny' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 4 , 'zonename' => 'Venril Sathir\'s Lair', 'zonename_short' =>  'Venril Sathir\'s Lair' , 'imagename' =>  'venril_sathir_lair' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 5 , 'zonename' => 'Temple of Kor-Sha', 'zonename_short' =>  'Temple of Kor-Sha' , 'imagename' =>  'korsha' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 6 , 'zonename' => 'Protector\'s realm', 'zonename_short' =>  'Protector\'s realm' , 'imagename' =>  'protector' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 7 , 'zonename' => 'Execution Throne Room', 'zonename_short' =>  'Execution Throne Room' , 'imagename' =>  'execution' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 8 , 'zonename' => 'Tomb of Thuuga', 'zonename_short' =>  'Tomb of Thuuga' , 'imagename' =>  'thuuga' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 9 , 'zonename' => 'Throne of New Tunaria', 'zonename_short' =>  'Throne of New Tunaria' , 'imagename' =>  'tunaria' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 10 , 'zonename' => 'Mistmoore\'s Inner Sanctum', 'zonename_short' =>  'Mistmoore\'s Inner Sanctum' , 'imagename' =>  'mistmoore' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 11 , 'zonename' => 'Freethinkers Hideout', 'zonename_short' =>  'Freethinkers Hideout' , 'imagename' =>  'freethinkers_hideout' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 12 , 'zonename' => 'Emerald Halls', 'zonename_short' =>  'Emerald Halls' , 'imagename' =>  'emerald_halls' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 13 , 'zonename' => 'The Clockwork Menace Factory', 'zonename_short' =>  'The Clockwork Menace Factory' , 'imagename' =>  'clockwork_menace' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 14 , 'zonename' => 'XuxLaios Roost', 'zonename_short' =>  'XuxLaios Roost' , 'imagename' =>  'xlar' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 15 , 'zonename' => 'Cavern of the Crustaceans', 'zonename_short' =>  'Cavern of the Crustaceans' , 'imagename' =>  'crustaceans' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 16 , 'zonename' => 'Trials of Awakened: Trial of Leadership', 'zonename_short' =>  'Trials of Awakened: Trial of Leadership' , 'imagename' =>  'tol' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 17 , 'zonename' => 'Lyceum of Abhorrence', 'zonename_short' =>  'Lyceum of Abhorrence' , 'imagename' =>  'lyceum' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 18 , 'zonename' => 'The Laboratory of Lord Vyemm', 'zonename_short' =>  'The Laboratory of Lord Vyemm' , 'imagename' =>  'lab_of_vyemm' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 19 , 'zonename' => 'Halls of the Seeing', 'zonename_short' =>  'Halls of the Seeing' , 'imagename' =>  'halls_of_the_seeing' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 20 , 'zonename' => 'Deathtoll', 'zonename_short' =>  'Deathtoll' , 'imagename' =>  'deathtoll' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 21 , 'zonename' => 'Ascent of the Awakening', 'zonename_short' =>  'Ascent of the Awakening' , 'imagename' =>  'awakening' , 'game' =>  eq2 ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
				
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );
		
		$sql_ary[] = array('id' => 1 , 'bossname' => 'Trakanon' , 'bossname_short' => 'Trakanon', 'imagename' =>  'trakanon' , 'game' =>  'eq2' , 'zoneid' =>  1 , 'type' =>  'npc'  , 'webid' =>  'Trakanon' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 2 , 'bossname' => 'Kluzen the Protector' , 'bossname_short' => 'Kluzen', 'imagename' =>  'kluzen' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Kluzen_the_Protector' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 3 , 'bossname' => 'Elder Ekron' , 'bossname_short' => 'Ekron', 'imagename' =>  'ekron' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Elder_Ekron' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 4 , 'bossname' => 'Silverwing' , 'bossname_short' => 'Silverwing', 'imagename' =>  'silverwing' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Silverwing' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 5 , 'bossname' => 'Travenro the Skygazer' , 'bossname_short' => 'Travenro', 'imagename' =>  'travenro' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Travenro_the_Skygazer' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 6 , 'bossname' => 'Phara Dar' , 'bossname_short' => 'Phara Dar', 'imagename' =>  'phara_dar' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Phara_Dar' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 7 , 'bossname' => 'Taskmaster Nichok' , 'bossname_short' => 'Nichok', 'imagename' =>  'taskmaster_nichok' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Taskmaster_Nichok' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 8 , 'bossname' => 'Qunard Ashenclaw' , 'bossname_short' => 'qunard', 'imagename' =>  'qunard_ashenclaw' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Qunard_Ashenclaw' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 9 , 'bossname' => 'Hoshkar' , 'bossname_short' => 'Hoshkar', 'imagename' =>  'hoshkar' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Hoshkar' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 10 , 'bossname' => 'Druushk' , 'bossname_short' => 'Druushk', 'imagename' =>  'druushk' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'druushk' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 11 , 'bossname' => 'Milyex Vioren' , 'bossname_short' => 'Milyex', 'imagename' =>  'milyex_vioren' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Milyex_Vioren' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 12 , 'bossname' => 'Xygoz' , 'bossname_short' => 'Xygoz', 'imagename' =>  'xygoz' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Xygoz' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 13 , 'bossname' => 'Nexona' , 'bossname_short' => 'Nexona', 'imagename' =>  'nexona' , 'game' =>  'eq2' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  'Nexona' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 14 , 'bossname' => 'Leviathan' , 'bossname_short' => 'Leviathan', 'imagename' =>  'leviathan' , 'game' =>  'eq2' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  'The_Leviathan' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 15 , 'bossname' => 'Venril Sathir' , 'bossname_short' => 'Venril', 'imagename' =>  'venril' , 'game' =>  'eq2' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  'Venril_Sathir' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 16 , 'bossname' => 'Aktar the Dark' , 'bossname_short' => 'Aktar', 'imagename' =>  'aktar_the_dark' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Aktar_the_Dark' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 17 , 'bossname' => 'Atrebe\'s Statue' , 'bossname_short' => 'Atrebe', 'imagename' =>  'atrebe' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Atrebe\'s_Statue' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 18 , 'bossname' => 'Ilyan' , 'bossname_short' => 'Ilyan', 'imagename' =>  'ilyan' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Ilyan' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 19 , 'bossname' => 'Kodux' , 'bossname_short' => 'Kodux', 'imagename' =>  'kodyx' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Kodyx' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 20 , 'bossname' => 'Selrach Di\'Zok' , 'bossname_short' => 'Selrach', 'imagename' =>  'selrach' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Selrach_Di\'Zok' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 21 , 'bossname' => 'Uthtak the Cruel' , 'bossname_short' => 'Uthtak', 'imagename' =>  'uthtak_the_cruel' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Uthtak_the_Cruel' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 22 , 'bossname' => 'Uzdrak the Invincible' , 'bossname_short' => 'Uzdrak', 'imagename' =>  'uzdrak_the_invincible' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Uzdrak_the_Invincible' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 23 , 'bossname' => 'Zarda' , 'bossname_short' => 'Zarda', 'imagename' =>  'zarda' , 'game' =>  'eq2' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  'Zarda' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 24 , 'bossname' => 'Adkar Vyx' , 'bossname_short' => 'Adkar', 'imagename' =>  'adkar_vyx' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Adkar_Vyx' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 25 , 'bossname' => 'Iztapa Vyx' , 'bossname_short' => 'Iztapa', 'imagename' =>  'iztapa_vyx' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Iztapa_Vyx' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 26 , 'bossname' => 'Wymbulu Vyx' , 'bossname_short' => 'Wymbulu', 'imagename' =>  'wymbulu_vyx' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Wymbulu_Vyx' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 27 , 'bossname' => 'Zykluk Vyx' , 'bossname_short' => 'Zykluk', 'imagename' =>  'zykluk_vyx' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Zykluk_Vyx' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 28 , 'bossname' => 'Blorgok the Brutal' , 'bossname_short' => 'Blorgok', 'imagename' =>  'blorgok_the_brutal' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Blorgok_the_Brutal' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 29 , 'bossname' => 'Doomcoil' , 'bossname_short' => 'Doomcoil', 'imagename' =>  'doomcoil' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Doomcoil' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 30 , 'bossname' => 'Imzok\'s Revenge' , 'bossname_short' => 'Imzok\'s Revenge', 'imagename' =>  'imzok' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Imzok\'s_Revenge' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 31 , 'bossname' => 'Jracol Binari' , 'bossname_short' => 'Binari', 'imagename' =>  'jracol_binari' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Jracol_Binari' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 32 , 'bossname' => 'Ludmila Kystov' , 'bossname_short' => 'Kystov', 'imagename' =>  'ludmila_kystov' , 'game' =>  'eq2' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  'Ludmila_Kystov' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 33 , 'bossname' => 'Pawbuster' , 'bossname_short' => 'Pawbuster', 'imagename' =>  'pawbuster' , 'game' =>  'eq2' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  'Pawbuster' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 34 , 'bossname' => 'Tairiza the Widow Mistress' , 'bossname_short' => 'Tairiza', 'imagename' =>  'tairiza' , 'game' =>  'eq2' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  'Tairiza_the_Widow_Mistress' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 35 , 'bossname' => 'The Harbinger of Absolution' , 'bossname_short' => 'The Harbinger of Absolution', 'imagename' =>  'harbinger_of_absolution' , 'game' =>  'eq2' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  'The_Harbinger_of_Absolution' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 36 , 'bossname' => 'Vampire Lord Mayong Mistmoore' , 'bossname_short' => 'Vampire Lord Mayong Mistmoore', 'imagename' =>  'vampire_lord_mayong_mistmoore' , 'game' =>  'eq2' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  'Vampire_Lord_Mayong_Mistmoore' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 37 , 'bossname' => 'Mayong Mistmoore' , 'bossname_short' => 'Mayong Mistmoore', 'imagename' =>  'mayong_mistmoore' , 'game' =>  'eq2' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  'Mayong_Mistmoore_(Instanced)' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 38 , 'bossname' => 'Vikomt D\'Raethe' , 'bossname_short' => 'D\'Raethe', 'imagename' =>  'cheroon' , 'game' =>  'eq2' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  'D\'Lizta Cheroon' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 39 , 'bossname' => 'V\'Tekla K\'Zalk' , 'bossname_short' => 'K\'Zalk', 'imagename' =>  'kzalk' , 'game' =>  'eq2' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  'V\'Tekla K\'Zalk' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 40 , 'bossname' => 'D\'Lizta Viswin' , 'bossname_short' => 'Viswin', 'imagename' =>  'viswin' , 'game' =>  'eq2' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  'D\'Lizta Viswin' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 41 , 'bossname' => 'Enynti' , 'bossname_short' => 'Enynti', 'imagename' =>  'enynti' , 'game' =>  'eq2' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  'Enynti' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 42 , 'bossname' => 'Malkonis D\'Morte' , 'bossname_short' => 'Malkonis', 'imagename' =>  'malkonis' , 'game' =>  'eq2' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  'Malkonis_D\'Morte' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 43 , 'bossname' => 'Treyloth D\'Kulvith' , 'bossname_short' => 'Treyloth D\'Kulvith', 'imagename' =>  'treyloth' , 'game' =>  'eq2' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  'Treyloth_D\'Kulvith' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 44 , 'bossname' => 'Othysis Muravian' , 'bossname_short' => 'Othysis Muravian', 'imagename' =>  'othysis' , 'game' =>  'eq2' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  'Othysis_Muravian' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 45 , 'bossname' => 'Zylphax the Shredder' , 'bossname_short' => 'Zylphax the Shredder', 'imagename' =>  'zylphax' , 'game' =>  'eq2' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  'Zylphax_the_Shredder' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 46 , 'bossname' => 'Wuoshi' , 'bossname_short' => 'Wuoshi', 'imagename' =>  'wuoshi' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Wuoshi' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 47 , 'bossname' => 'Galiel Spirithoof' , 'bossname_short' => 'Galiel Spirithoof', 'imagename' =>  'galiel_spirithoof' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Galiel_Spirithoof' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 48 , 'bossname' => 'Treah Greenroot' , 'bossname_short' => 'Treah Greenroot', 'imagename' =>  'treah_greenroot' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Treah_Greenroot' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 49 , 'bossname' => 'Sariah the Bloomseeker' , 'bossname_short' => 'Sariah the Bloomseeker', 'imagename' =>  'sariah_the_bloomseeker' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Sariah_the_Bloomseeker' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 50 , 'bossname' => 'Mistress of the Veil' , 'bossname_short' => 'Mistress of the Veil', 'imagename' =>  'mistress_of_the_veil' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Mistress_of_the_Veil' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 51 , 'bossname' => 'Sarik the Fang' , 'bossname_short' => 'Sarik the Fang', 'imagename' =>  'sarik_the_fang' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Sarik_the_Fang' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 52 , 'bossname' => 'The Segmented Rumbler' , 'bossname_short' => 'The Segmented Rumbler', 'imagename' =>  'segmented_rumbler' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'the_segmented_rumbler' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 53 , 'bossname' => 'Elaani the collector' , 'bossname_short' => 'Elaani the collector', 'imagename' =>  'elaani_the_collector' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Elaani_the_collector' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 54 , 'bossname' => 'Gardener Thirgen' , 'bossname_short' => 'Gardener Thirgen', 'imagename' =>  'gardener_thirgen' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Gardener_Thirgen' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 55 , 'bossname' => 'The Farstride Unicorn' , 'bossname_short' => 'The Farstride Unicorn', 'imagename' =>  'the_farstride_unicorn' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'The_Farstride_Unicorn' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 56 , 'bossname' => 'The Farstride Unicorn' , 'bossname_short' => 'The Farstride Unicorn', 'imagename' =>  'the_farstride_unicorn' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'The_Farstride_Unicorn' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 57 , 'bossname' => 'Tender of the Seedlings' , 'bossname_short' => 'Tender of the Seedlings', 'imagename' =>  'tender_of_the_seedlings' , 'game' =>  'eq2' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  'Tender_of_the_Seedlings' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 58 , 'bossname' => 'Round 1' , 'bossname_short' => 'Round 1', 'imagename' =>  'round_1' , 'game' =>  'eq2' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  'The_Clockwork_Menace_Factory' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 59 , 'bossname' => 'Round 2' , 'bossname_short' => 'Round 2', 'imagename' =>  'round_2' , 'game' =>  'eq2' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  'The_Clockwork_Menace_Factory' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 60 , 'bossname' => 'Round 3' , 'bossname_short' => 'Round 3', 'imagename' =>  'round_3' , 'game' =>  'eq2' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  'The_Clockwork_Menace_Factory' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 61 , 'bossname' => 'The Ancient Twisted Root' , 'bossname_short' => 'The Ancient Twisted Root', 'imagename' =>  'tatr' , 'game' =>  'eq2' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  'Xux%27Laio%27s_Roost' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 62 , 'bossname' => 'The Ancient Twisted Fungus' , 'bossname_short' => 'The Ancient Twisted Fungus', 'imagename' =>  'tatf' , 'game' =>  'eq2' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  'Xux%27Laio%27s_Roost' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 63 , 'bossname' => 'The Crusher' , 'bossname_short' => 'The Crusher', 'imagename' =>  'crusher' , 'game' =>  'eq2' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  'Xux%27Laio%27s_Roost' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 64 , 'bossname' => 'Xux\'laio Master of the Fluttering Wing' , 'bossname_short' => 'Xux\'laio Master of the Fluttering Wing', 'imagename' =>  'xuxlaiom' , 'game' =>  'eq2' , 'zoneid' =>  14 , 'type' =>  'npc'  , 'webid' =>  'Xux%27Laio%27s_Roost' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 65 , 'bossname' => 'Bonesplitter' , 'bossname_short' => 'Bonesplitter', 'imagename' =>  'bonesplitter' , 'game' =>  'eq2' , 'zoneid' =>  15 , 'type' =>  'npc'  , 'webid' =>  'Cavern_of_the_Crustaceans' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 66 , 'bossname' => 'Gur\'gul the Warden' , 'bossname_short' => 'Gur\'gul the Warden', 'imagename' =>  'gurgul' , 'game' =>  'eq2' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  'Trials_of_Awakened:_Trial_of_Leadership#Gur.27gul_the_Warden' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 67 , 'bossname' => 'Keeper of Gur\'gul' , 'bossname_short' => 'Keeper of Gur\'gul', 'imagename' =>  'kogurgul' , 'game' =>  'eq2' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  'Trials_of_Awakened:_Trial_of_Leadership#Keeper_of_Gur.27gul' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 68 , 'bossname' => 'Keeper of the Gate' , 'bossname_short' => 'Keeper of the Gate', 'imagename' =>  'kog' , 'game' =>  'eq2' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  'Trials_of_Awakened:_Trial_of_Leadership#Keeper_of_the_Gate' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 69 , 'bossname' => 'The Final Warden' , 'bossname_short' => 'The Final Warden', 'imagename' =>  'final_warden' , 'game' =>  'eq2' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  'Trials_of_Awakened:_Trial_of_Leadership#The_Final_Warden' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 70 , 'bossname' => 'The Guardian of Leadership' , 'bossname_short' => 'The Guardian of Leadership', 'imagename' =>  'gol' , 'game' =>  'eq2' , 'zoneid' =>  16 , 'type' =>  'npc'  , 'webid' =>  'Trials_of_Awakened:_Trial_of_Leadership#The_Guardian_of_Leadership' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 71 , 'bossname' => 'Essence of Fear' , 'bossname_short' => 'Essence of Fear', 'imagename' =>  'essence_of_fear' , 'game' =>  'eq2' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  'Lyceum_of_Abhorrence#Essence_of_Fear' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 72 , 'bossname' => 'Gnillaw the Demented' , 'bossname_short' => 'Gnillaw the Demented', 'imagename' =>  'gnillaw' , 'game' =>  'eq2' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  'Lyceum_of_Abhorrence#Gnillaw_the_Demented' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 73 , 'bossname' => 'Gnorbl the Playful' , 'bossname_short' => 'Gnorbl the Playful', 'imagename' =>  'gnorbl' , 'game' =>  'eq2' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  'Lyceum_of_Abhorrence#Gnorbl_the_Playful' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 74 , 'bossname' => 'Vilucidae the Priest of Thule' , 'bossname_short' => 'Vilucidae the Priest of Thule', 'imagename' =>  'vilucidae' , 'game' =>  'eq2' , 'zoneid' =>  17 , 'type' =>  'npc'  , 'webid' =>  'Lyceum_of_Abhorrence#Vilucidae_the_Priest_of_Thule' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 75 , 'bossname' => 'The Slaving Alzid' , 'bossname_short' => 'The Slaving Alzid', 'imagename' =>  'alzid' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#The_Slavering_Alzid' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 76 , 'bossname' => 'Doomright Vakrizt' , 'bossname_short' => 'Doomright Vakrizt', 'imagename' =>  'doomright' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Doomright_Vakrizt' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 77 , 'bossname' => 'Pardas Predd' , 'bossname_short' => 'Pardas Predd', 'imagename' =>  'pardas' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Pardas_Predd' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 78 , 'bossname' => 'Doom Prophet Kinvah' , 'bossname_short' => 'Doom Prophet Kinvah', 'imagename' =>  'kinvah' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Kinvah' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 79 , 'bossname' => 'Doom Ravager Ru\'ystad' , 'bossname_short' => 'Doom Ravager Ru\'ystad', 'imagename' =>  'ruystad' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Ruystad' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 80 , 'bossname' => 'Doom Reaver Cheyak' , 'bossname_short' => 'Doom Reaver Cheyak', 'imagename' =>  'cheyak' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Cheyak' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 81 , 'bossname' => 'The Uncaged Alzid' , 'bossname_short' => 'The Uncaged Alzid', 'imagename' =>  'uncaged_alzid' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#The_Uncaged_Alzid' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 82 , 'bossname' => 'Uustalastus Xiterrax' , 'bossname_short' => 'Uustalastus Xiterrax', 'imagename' =>  'uustalastus' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Uustalastus_Xiterrax' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 83 , 'bossname' => 'Doomsworn Zatrakh' , 'bossname_short' => 'Doomsworn Zatrakh', 'imagename' =>  'doomsworn' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Doomsworn_Zatrakh' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 84 , 'bossname' => 'The Corsolander' , 'bossname_short' => 'The Corsolander', 'imagename' =>  'corsolander' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#The_Corsolander' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 85 , 'bossname' => 'Euktrzkai Amdaatk' , 'bossname_short' => 'Euktrzkai Amdaatk', 'imagename' =>  'amdaatk' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Euktrzkai_Amdaatk' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 86 , 'bossname' => 'Lord Vyemm' , 'bossname_short' => 'Lord Vyemm', 'imagename' =>  'vyemm' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Lord_Vyemm' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 87 , 'bossname' => 'Alzid Prime' , 'bossname_short' => 'Alzid Prime', 'imagename' =>  'alzid_prime' , 'game' =>  'eq2' , 'zoneid' =>  18 , 'type' =>  'npc'  , 'webid' =>  'The_Laboratory_of_Lord_Vyemm#Alzid_Prime' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 88 , 'bossname' => 'A Shadowy Presence' , 'bossname_short' => 'A Shadowy Presence', 'imagename' =>  'shadowy_presence' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#A_Shadowy_Presence' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 89 , 'bossname' => 'A Charged Presence' , 'bossname_short' => 'A Charged Presence', 'imagename' =>  'charged_presence' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#A_Charged_Presence' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 90 , 'bossname' => 'The Elemental Warder' , 'bossname_short' => 'The Elemental Warder', 'imagename' =>  'elemental_warder' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#The_Elemental_Warder' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 91 , 'bossname' => 'Pain' , 'bossname_short' => 'Pain', 'imagename' =>  'pain' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#Pain' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 92 , 'bossname' => 'Suffering' , 'bossname_short' => 'Suffering', 'imagename' =>  'suffering' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#Suffering' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 93 , 'bossname' => 'BloodBeast' , 'bossname_short' => 'BloodBeast', 'imagename' =>  'bloodbeast' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#BloodBeast' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 94 , 'bossname' => 'Venekor' , 'bossname_short' => 'Venekor', 'imagename' =>  'venekor' , 'game' =>  'eq2' , 'zoneid' =>  19 , 'type' =>  'npc'  , 'webid' =>  'Halls_of_the_Seeing#Venekor' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 95 , 'bossname' => 'Yitzik the Hurler' , 'bossname_short' => 'Yitzik the Hurler', 'imagename' =>  'yitzik' , 'game' =>  'eq2' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  'Deathtoll#Yitzik_the_Hurler' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 96 , 'bossname' => 'Fitzpitzle' , 'bossname_short' => 'Fitzpitzle', 'imagename' =>  'fitzpitzle' , 'game' =>  'eq2' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  'Deathtoll#Fitzpitzle' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 97 , 'bossname' => 'Amorphous Drake' , 'bossname_short' => 'Amorphous Drake', 'imagename' =>  'amorphous' , 'game' =>  'eq2' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  'Deathtoll#Amorphous_Drake' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 98 , 'bossname' => 'Tarinax the Destroyer' , 'bossname_short' => 'Tarinax the Destroyer', 'imagename' =>  'tarinax' , 'game' =>  'eq2' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  'Deathtoll#Tarinax_the_Destroyer' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 99 , 'bossname' => 'Cruor Alluvium' , 'bossname_short' => 'Cruor Alluvium', 'imagename' =>  'cruor' , 'game' =>  'eq2' , 'zoneid' =>  20 , 'type' =>  'npc'  , 'webid' =>  'Deathtoll#Cruor_Alluvium' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 100 , 'bossname' => 'Prophet of The Slashing Talon' , 'bossname_short' => 'Prophet of The Slashing Talon', 'imagename' =>  'slashing_talon' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2#Prophet_of_The_Slashing_Talon_.5B67_Epic_x2.5D' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 101 , 'bossname' => 'Ancient of the Flapping Wingz' , 'bossname_short' => 'Barz', 'imagename' =>  'flapping_wing' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2#Ancient_of_the_Flapping_Wing_.5B67_Epic_x2.5D' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 102 , 'bossname' => 'Ireth the Cold' , 'bossname_short' => 'Ireth the Cold', 'imagename' =>  'ireth' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2#Ireth_The_Cold_.5B70_epicx2.5D' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 103 , 'bossname' => 'Sharti of the Flame' , 'bossname_short' => 'Sharti of the Flame', 'imagename' =>  'sharti' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2#Sharti_of_The_Flame_.5B70_Epic_x2.5D' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 104 , 'bossname' => 'Gorenaire' , 'bossname_short' => 'Gorenaire', 'imagename' =>  'gorenaire' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 105 , 'bossname' => 'Talendor' , 'bossname_short' => 'Talendor', 'imagename' =>  'talendor' , 'game' =>  'eq2' , 'zoneid' =>  21 , 'type' =>  'npc'  , 'webid' =>  'Ascent_of_the_Awakeningx2' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
				
		
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );
		
		
	}
}







?>