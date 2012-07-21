<?php
/**
 * bbdkp tera install data
 * @author Sajaki9@gmail.com
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}

function install_tera()
{
	global $db, $table_prefix;
	
	// factions
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'tera'" );
	$sql_ary = array();
	$sql_ary [] = array ('game_id' => 'tera','faction_id' => 1, 'faction_name' => 'Tera' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_factions', $sql_ary );
	unset ( $sql_ary );
	
	// classes
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'tera'" ); 
	$sql_ary = array ();
	$sql_ary [] = array ('game_id' => 'tera', 'class_id' => 0, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY', 'class_min_level' => 1, 'class_max_level' => 50 , 'colorcode' =>  '#999', 'imagename' => 'tera_unknown');   
	$sql_ary [] = array ('game_id' => 'tera','class_id' => 1, 'class_faction_id' => 1, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 50 , 'colorcode' =>  '#66CCFF', 'imagename' => 'tera_archer');   
	$sql_ary [] = array ('game_id' => 'tera', 'class_id' => 2, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY', 'class_min_level' => 1, 'class_max_level' => 50, 'colorcode' =>  '#AFDCEC',  'imagename' => 'tera_berserker');    
	$sql_ary [] = array ('game_id' => 'tera','class_id' => 3, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY', 'class_min_level' => 1, 'class_max_level' => 50 , 'colorcode' =>  '#437C17',  'imagename' => 'tera_lancer');    
	$sql_ary [] = array ('game_id' => 'tera','class_id' => 4, 'class_faction_id' => 1, 'class_armor_type' => 'ROBE', 'class_min_level' => 1, 'class_max_level' => 50 ,  'colorcode' =>  '#663333',  'imagename' => 'tera_mystic'); 
	$sql_ary [] = array ('game_id' => 'tera','class_id' => 5, 'class_faction_id' => 1, 'class_armor_type' => 'ROBE', 'class_min_level' => 1, 'class_max_level' => 50 , 'colorcode' =>  '#CC0033',  'imagename' => 'tera_priest');
	$sql_ary [] = array ('game_id' => 'tera', 'class_id' => 6, 'class_faction_id' => 1, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 50, 'colorcode' =>  '#AFDCEC',  'imagename' => 'tera_slayer');
	$sql_ary [] = array ('game_id' => 'tera','class_id' => 7, 'class_faction_id' => 1, 'class_armor_type' => 'ROBE', 'class_min_level' => 1, 'class_max_level' => 50 ,  'colorcode' =>  '#663333',  'imagename' => 'tera_sorcerer');
	$sql_ary [] = array ('game_id' => 'tera', 'class_id' => 8, 'class_faction_id' => 1, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 50, 'colorcode' =>  '#AFDCEC',  'imagename' => 'tera_warrior');	 	  
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_classes', $sql_ary );
	unset ( $sql_ary ); 

	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'tera'");
	$sql_ary = array ();
	//Unknown
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 0, 'race_faction_id' => 1, 'image_female' => ' ', 'image_male' => ' '  ); 
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 1, 'race_faction_id' => 1, 'image_female' => 'tera_aman_female',  'image_male' => 'tera_aman_male' );  
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 2, 'race_faction_id' => 1, 'image_female' => 'tera_baraka_female',  'image_male' => 'tera_baraka_male' ); 
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 3, 'race_faction_id' => 1, 'image_female' => 'tera_castanic_female',  'image_male' => 'tera_castanic_male' ); 
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 4, 'race_faction_id' => 1, 'image_female' => 'tera_elin_female',  'image_male' => 'tera_elin_male' ) ; 
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 5, 'race_faction_id' => 1, 'image_female' => 'tera_helf_female',  'image_male' => 'tera_helf_male' );  
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 6, 'race_faction_id' => 1, 'image_female' => 'tera_human_female',  'image_male' => 'tera_human_male'  ); 
	$sql_ary [] = array ('game_id' => 'tera','race_id' => 7, 'race_faction_id' => 1, 'image_female' => 'tera_popori_female',  'image_male' => 'tera_popori_male' ); 
	$db->sql_multi_insert ($table_prefix . 'bbdkp_races', $sql_ary);
	unset ( $sql_ary );
	
	// Dictionary
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'tera' and attribute in('class','race') ");
	$sql_ary = array ();
	// 
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Archer' ,  'name_short' =>  'Archer' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Berserker' ,  'name_short' =>  'Berserker' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Lancer' ,  'name_short' =>  'Lancer' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mystic' ,  'name_short' =>  'Mystic' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Priest' ,  'name_short' =>  'Priest' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Slayer' ,  'name_short' =>  'Slayer' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 0, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'T7-01' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 1, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Aman' ,  'name_short' =>  'Aman' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 2, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Baraka' ,  'name_short' =>  'Baraka' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 3, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Castanic' ,  'name_short' =>  'Castanic' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 4, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Elin' ,  'name_short' =>  'Elin' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 5, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'High Elf' ,  'name_short' =>  'High Elf' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 6, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Human' ,  'name_short' =>  'Human' );
	$sql_ary[] = array('game_id' => 'tera', 'attribute_id' => 7, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Popori' ,  'name_short' =>  'Popori' );

	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
	
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_dkpsystem  where dkpsys_name = 'Tera Dungeons' ");
	// dkp pool
	$sql_ary = array (
		'dkpsys_name' => 'Tera Dungeons', 
		'dkpsys_status' => 'Y', 
		'dkpsys_addedby' => 'admin', 
		'dkpsys_default' => 'N' );
	$sql = 'INSERT INTO ' . $table_prefix . 'bbdkp_dkpsystem ' . $db->sql_build_array('INSERT', $sql_ary);
	$db->sql_query($sql);
	$teradkpid = $db->sql_nextid();
	
    $sql_ary = array();
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Bastion of Lok', 'event_color' => '#C6DEFF', 'event_value' => 5, 'event_imagename' => 'tera_lok'  ) ;
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Sinestral Manor', 'event_color' => '#C6DEFF', 'event_value' => 5 , 'event_imagename' => 'tera_sines') ;
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Cultists’ Refuge', 'event_color' => '#6D7B8D', 'event_value' => 5, 'event_imagename' => 'tera_cult' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Necromancer Tomb', 'event_color' => '#6D7B8D', 'event_value' => 5, 'event_imagename' => 'tera_necro' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Sigil Adstringo', 'event_color' => '#6D7B8D', 'event_value' => 5, 'event_imagename' => 'tera_sigil' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Golden Labyrinth', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_gold' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Akasha’s Hideout', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_aka' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Akasha’s Hideout (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_aka2' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Ascent of Saravash', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_sar' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Ebon Tower', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_ebo' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Ebon Tower (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_ebo2' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Kelsaik’s Nest', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_kel' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Kelsaik’s Nest (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_kel2' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Labyrinth of Terror', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_lab' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Labyrinth of Terror (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_lab2' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Balder’s Temple', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_bald' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Balder’s Temple (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_bald2' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Fane of Kaprima', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_kap' );
	$sql_ary [] = array('event_dkpid' => $teradkpid , 'event_name' => 'Fane of Kaprima (Hard)', 'event_color' => '#842DCE', 'event_value' => 5, 'event_imagename' => 'tera_kap2' );
	
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_events', $sql_ary );
	
	
}
?>