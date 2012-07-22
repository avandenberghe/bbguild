<?php
/**
 * bbdkp wow install data
 * @author Sajaki@betenoire
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

function install_wow()
{
	global $db, $table_prefix, $umil, $user;
	// classes
	// note class 10 does not exist
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'wow'" ); 
	$sql_ary = array ();
	$sql_ary [] = array ('game_id' => 'wow', 'class_id' => 0, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#999', 'imagename' => 'wow_unknown');   
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 1, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#C79C6E', 'imagename' => 'wow_warrior');   
	$sql_ary [] = array ('game_id' => 'wow', 'class_id' => 4, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 90, 'colorcode' =>  '#FFF569',  'imagename' => 'wow_rogue');    
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 3, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#ABD473',  'imagename' => 'wow_hunter');    
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 2, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 90 ,  'colorcode' =>  '#F58CBA',  'imagename' => 'wow_paladin'); 
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 7, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#0070DE',  'imagename' => 'wow_shaman'); 
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 11, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#FF7D0A',  'imagename' => 'wow_druid');  
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 9, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#9482C9',  'imagename' => 'wow_warlock'); 
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 8, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 90 , 'colorcode' =>  '#69CCF0',  'imagename' => 'wow_mage');   
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 5, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 90 ,  'colorcode' =>  '#FFFFFF', 'imagename' => 'wow_priest');  
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 6, 'class_armor_type' => 'PLATE', 'class_min_level' => 55, 'class_max_level' => 90 , 'colorcode' =>  '#C41F3B',  'imagename' => 'wow_death_knight');
	$sql_ary [] = array ('game_id' => 'wow','class_id' => 10, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 90 ,  'colorcode' =>  '#008467', 'imagename' => 'wow_monk'); 
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_classes', $sql_ary );
	unset ( $sql_ary ); 

	// factions
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'wow'" );
	$sql_ary = array();
	$sql_ary [] = array ('game_id' => 'wow','faction_id' => 1, 'faction_name' => 'Alliance' );
	$sql_ary [] = array ('game_id' => 'wow','faction_id' => 2, 'faction_name' => 'Horde' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_factions', $sql_ary );
	unset ( $sql_ary );
	
	// races
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'wow'");
	$sql_ary = array ();
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 0, 'race_faction_id' => 0, 'image_female' => ' ',  'image_male' => ' '  ); //Unknown
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 1, 'race_faction_id' => 1, 'image_female' => 'wow_human_female',  'image_male' => 'wow_human_male'  ); //Human
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 2, 'race_faction_id' => 2 , 'image_female' => 'wow_orc_female',  'image_male' => 'wow_orc_male' ); //Orc
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 3, 'race_faction_id' => 1 , 'image_female' => 'wow_dwarf_female',  'image_male' => 'wow_dwarf_male' ); //Dwarf
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 4, 'race_faction_id' => 1 , 'image_female' => 'wow_nightelf_female',  'image_male' => 'wow_nightelf_male' ) ; //Night Elf
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 5, 'race_faction_id' => 2 , 'image_female' => 'wow_scourge_female',  'image_male' => 'wow_scourge_male' ); //Undead
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 6, 'race_faction_id' => 2 , 'image_female' => 'wow_tauren_female',  'image_male' => 'wow_tauren_male' ); //Tauren
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 7, 'race_faction_id' => 1 , 'image_female' => 'wow_gnome_female',  'image_male' => 'wow_gnome_male' ); //Gnome
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 8, 'race_faction_id' => 2 , 'image_female' => 'wow_troll_female',  'image_male' => 'wow_troll_male' ); //Troll
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 9, 'race_faction_id' => 2 , 'image_female' => 'wow_goblin_female',  'image_male' => 'wow_goblin_male' ); //Goblin
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 10, 'race_faction_id' => 2 , 'image_female' => 'wow_bloodelf_female',  'image_male' => 'wow_bloodelf_male' ); //Blood Elf
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 11, 'race_faction_id' => 1 , 'image_female' => 'wow_draenei_female',  'image_male' => 'wow_draenei_male' ); //Draenei
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 22, 'race_faction_id' => 1 , 'image_female' => 'wow_worgen_female',  'image_male' => 'wow_worgen_male' ); //Worgen
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 24, 'race_faction_id' => 1 , 'image_female' => 'wow_pandaren_female',  'image_male' => 'wow_pandaren_male' ); //Pandaren alliance
	$sql_ary [] = array ('game_id' => 'wow','race_id' => 25, 'race_faction_id' => 2 , 'image_female' => 'wow_pandaren_female',  'image_male' => 'wow_pandaren_male' ); //Pandaren Horde
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_races', $sql_ary );
	unset ( $sql_ary );
	

	// classes
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'wow' and (attribute='class' or attribute = 'race')");
	
	$sql_ary = array ();
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Rogue' ,  'name_short' =>  'Rogue' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Hunter' ,  'name_short' =>  'Hunter' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Shaman' ,  'name_short' =>  'Shaman' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Druid' ,  'name_short' =>  'Druid' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warlock' ,  'name_short' =>  'Warlock' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mage' ,  'name_short' =>  'Mage' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Priest' ,  'name_short' =>  'Priest' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Death Knight' ,  'name_short' =>  'Death Knight' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
	
	//classes in fr
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Voleur' ,  'name_short' =>  'Voleur' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chasseur' ,  'name_short' =>  'Chasseur' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chaman' ,  'name_short' =>  'Chaman' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Druide' ,  'name_short' =>  'Druide' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Démoniste' ,  'name_short' =>  'Démoniste' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Mage' ,  'name_short' =>  'Mage' );
	$sql_ary[] = array('game_id' => 'wow','attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Prêtre' ,  'name_short' =>  'Prêtre' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Chevalier de la Mort' ,  'name_short' =>  'Chevalier de la Mort' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Moine' ,  'name_short' =>  'Moine' );
	
	//classes in de	
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unbekannt' ,  'name_short' =>  'Unbekannt' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Krieger' ,  'name_short' =>  'Krieger' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Schurke' ,  'name_short' =>  'Schurke' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Jäger' ,  'name_short' =>  'Jäger' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Schamane' ,  'name_short' =>  'Schamane' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Druide' ,  'name_short' =>  'Druide' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Hexenmeister' ,  'name_short' =>  'Hexenmeister' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Magier' ,  'name_short' =>  'Magier' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Priester' ,  'name_short' =>  'Priester' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Todesritter' ,  'name_short' =>  'Todesritter' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Mönch' ,  'name_short' =>  'Mönch' );
				
	//races in en
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Human' ,  'name_short' =>  'Human' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Dwarf' ,  'name_short' =>  'Dwarf' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Night Elf' ,  'name_short' =>  'Night Elf' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 5, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Undead' ,  'name_short' =>  'Undead' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Blood Elf' ,  'name_short' =>  'Blood Elf' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Draenei' ,  'name_short' =>  'Draenei' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 22, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 24, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Pandaren Alliance' ,  'name_short' =>  'Pandaren Alliance' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 25, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Pandaren Horde' ,  'name_short' =>  'Pandaren Horde' );
		
	// races in fr
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Humain' ,  'name_short' =>  'Humain' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Nain' ,  'name_short' =>  'Nain' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Elfe de la Nuit' ,  'name_short' =>  'Elfe de la Nuit' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 5, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Mort-Vivant' ,  'name_short' =>  'Mort-Vivant' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Elfe de Sang' ,  'name_short' =>  'Elfe de Sang' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Draeneï' ,  'name_short' =>  'Draeneï' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 22, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 24, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Pandaren Alliance' ,  'name_short' =>  'Pandaren Alliance' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 25, 'language' => 'fr' , 'attribute' =>  'race' , 'name' =>  'Pandaren Horde' ,  'name_short' =>  'Pandaren Horde' );
	
	//races in de
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 0, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 1, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Mensch' ,  'name_short' =>  'Mensch' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 2, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Orc' ,  'name_short' =>  'Orc' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 3, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Zwerg' ,  'name_short' =>  'Zwerg' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 4, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Nachtelf' ,  'name_short' =>  'Nachtelf' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 5, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Untoter' ,  'name_short' =>  'Untoter' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 6, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Tauren' ,  'name_short' =>  'Tauren' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 7, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Gnome' ,  'name_short' =>  'Gnome' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 8, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Troll' ,  'name_short' =>  'Troll' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 10, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Blutelf' ,  'name_short' =>  'Blutelf' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 11, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Draenei' ,  'name_short' =>  'Draenei' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Goblin' ,  'name_short' =>  'Goblin' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 22, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Worgen' ,  'name_short' =>  'Worgen' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 24, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Pandaren Alliance' ,  'name_short' =>  'Pandaren Alliance' );
	$sql_ary[] = array('game_id' => 'wow', 'attribute_id' => 25, 'language' => 'de' , 'attribute' =>  'race' , 'name' =>  'Pandaren Horde' ,  'name_short' =>  'Pandaren Horde' );
	
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );

	
	// insert events if table is empty
	
	//@todo
	
	
	// set imagenames for wow
	global $db, $table_prefix;
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_naxx' where event_name like 'Naxx%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_os' where event_name like 'Obsidian%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_voa' where event_name like '%Archavon%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_eoe' where event_name like '%Eternity%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_uld' where event_name like 'Ulduar%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_toc' where event_name like 'Trial%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_icc' where event_name like 'Icecrown%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_rub' where event_name like 'Ruby%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_ony' where event_name like 'Onyxia%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_bot' where event_name like 'Bastion%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_bwd' where event_name like 'Black%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_bar' where event_name like '%Barad%' ";  
	$db->sql_query($sql);
	$sql="update " . $table_prefix . "bbdkp_events set event_imagename='wow_tfr' where event_name like 'Throne%' ";  
	$db->sql_query($sql);
	
		// dkp system
	// if we only have the default dkp system installed then add some more 
	$result = $db->sql_query('select count(*) as num_dkp from ' . $table_prefix . 'bbdkp_dkpsystem');
	$total_dkps = (int) $db->sql_fetchfield('num_dkp');
	$db->sql_freeresult($result);
	if($total_dkps == 1)
	{
		$sql_ary = array ();
		$sql_ary [] = array ('dkpsys_name' => 'WLK10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$sql_ary [] = array ('dkpsys_name' => 'WLK25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$sql_ary [] = array ('dkpsys_name' => 'CATA10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$sql_ary [] = array ('dkpsys_name' => 'CATA25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$sql_ary [] = array ('dkpsys_name' => 'PAN10', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$sql_ary [] = array ('dkpsys_name' => 'PAN25', 'dkpsys_status' => 'Y', 'dkpsys_addedby' => 'admin', 'dkpsys_default' => 'N' );
		$db->sql_multi_insert ( $table_prefix . 'bbdkp_dkpsystem', $sql_ary );
		unset ( $sql_ary );
	}
	
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