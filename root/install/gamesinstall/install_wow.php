<?php
/**
 * bbdkp wow install data
 * @author Sajaki@betenoire
 * -installer
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

	$result = $db->sql_query('SELECT dkpsys_id FROM ' . $table_prefix . "bbdkp_dkpsystem  where dkpsys_name = 'WoW Cataclysm' ");
	$row = $db->sql_fetchrow ($result);
	if($row)
    {
    	//get existing
		$wowpdkpid = $row['dkpsys_id'];
    }
    else
    {
    	// add new dkp pool
		$sql_ary = array (
			'dkpsys_name' => 'WoW Cataclysm',
			'dkpsys_status' => 'Y',
			'dkpsys_addedby' => 'admin',
			'dkpsys_default' => 'N' );
		$sql = 'INSERT INTO ' . $table_prefix . 'bbdkp_dkpsystem ' . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		$wowpdkpid = $db->sql_nextid();
    }
    $db->sql_freeresult ( $result );

    $sql_ary = array();
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T11 Blackwing Descent 10', 'event_color' => '#77FF11', 'event_value' => 5, 'event_imagename' => 'wow_bd10'  ) ;
    $sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T11 Bastion of Twilight 10', 'event_color' => '#99FF11', 'event_value' => 5, 'event_imagename' => 'wow_bot10'  ) ;
    $sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T11 Throne of the Four Winds 10', 'event_color' => '#BBFF11', 'event_value' => 5, 'event_imagename' => 'wow_tfw10');
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T12 Firelands 10', 'event_color' => '#CCFF11', 'event_value' => 5 , 'event_imagename' => 'wow_fl10');
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T12 Firelands 10hm', 'event_color' => '#FF2266', 'event_value' => 5 , 'event_imagename' => 'wow_fl10hm');
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T12 Firelands 25', 'event_color' => '#FFBB22', 'event_value' => 5 , 'event_imagename' => 'wow_fl25');
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T12 Firelands 25hm', 'event_color' => '#FF2266', 'event_value' => 5 , 'event_imagename' => 'wow_fl25hm');
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T13 Dragon Soul 10', 'event_color' => '#CCFF22', 'event_value' => 5, 'event_imagename' => 'wow_ds10' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T13 Dragon Soul 10hm', 'event_color' => '#FF2266', 'event_value' => 5, 'event_imagename' => 'wow_ds10hm' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T13 Dragon Soul 25', 'event_color' => '#FFBB33', 'event_value' => 5, 'event_imagename' => 'wow_ds25' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T13 Dragon Soul 25hm', 'event_color' => '#FF2266', 'event_value' => 5, 'event_imagename' => 'wow_ds25hm' );

	$sql_ary2 = array();
	foreach($sql_ary as $evt => $event)
	{
		$sql = 'SELECT event_id FROM ' . $table_prefix . 'bbdkp_events where event_name ' . $db->sql_like_expression($db->any_char . $event['event_name'] . $db->any_char);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow ($result);
		if(!$row)
		{
			$sql_ary2[] = $event;
		}
		$db->sql_freeresult ($result);
	}

	if (count($sql_ary2) > 0)
	{
		$db->sql_multi_insert ( $table_prefix . 'bbdkp_events', $sql_ary2 );
	}

	$result = $db->sql_query('SELECT dkpsys_id FROM ' . $table_prefix . "bbdkp_dkpsystem  where dkpsys_name = 'WoW Pandaria' ");
	$row = $db->sql_fetchrow ($result);
	if($row)
    {
    	//get existing
		$wowpdkpid = $row['dkpsys_id'];
    }
    else
    {
    	// add new dkp pool
		$sql_ary = array (
			'dkpsys_name' => 'WoW Pandaria',
			'dkpsys_status' => 'Y',
			'dkpsys_addedby' => 'admin',
			'dkpsys_default' => 'N' );
		$sql = 'INSERT INTO ' . $table_prefix . 'bbdkp_dkpsystem ' . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		$wowpdkpid = $db->sql_nextid();
    }
    $db->sql_freeresult ( $result );

    $sql_ary = array();
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => utf8_encode('T14 MoguShan Vaults (10)'), 'event_color' => '#FF9999', 'event_value' => 5, 'event_imagename' => 'mogushanvaults10'  ) ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => utf8_encode('T14 MoguShan Vaults (10HM)'), 'event_color' => '#FF9999', 'event_value' => 5, 'event_imagename' => 'mogushanvaultsh10'  ) ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => utf8_encode('T14 MoguShan Vaults (25)'), 'event_color' => '#FF9999', 'event_value' => 5, 'event_imagename' => 'mogushanvaults25'  ) ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => utf8_encode('T14 MoguShan Vaults (25HM)'), 'event_color' => '#FF9999', 'event_value' => 5, 'event_imagename' => 'mogushanvaultsh25'  ) ;

	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Terrace of Endless Spring  (10)', 'event_color' => '#FF88AA', 'event_value' => 5 , 'event_imagename' => 'terraceofendlessspring10') ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Terrace of Endless Spring  (10HM)', 'event_color' => '#FF88AA', 'event_value' => 5 , 'event_imagename' => 'terraceofendlessspringh10') ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Terrace of Endless Spring  (25)', 'event_color' => '#FF88AA', 'event_value' => 5 , 'event_imagename' => 'terraceofendlessspring25') ;
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Terrace of Endless Spring  (25HM)', 'event_color' => '#FF88AA', 'event_value' => 5 , 'event_imagename' => 'terraceofendlessspringh25') ;

	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Heart of Fear (10)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'heartoffear10' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Heart of Fear (10HM)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'heartoffearh10' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Heart of Fear (25)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'heartoffear25' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T14 Heart of Fear (25HM)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'heartoffearh25' );

	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T15 Throne of Thunder (10)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'throneofthunder10' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T15 Throne of Thunder (10HM)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'throneofthunderh10' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T15 Throne of Thunder (25)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'throneofthunder25' );
	$sql_ary [] = array('event_dkpid' => $wowpdkpid , 'event_name' => 'T15 Throne of Thunder (25HM)', 'event_color' => '#DD00AA', 'event_value' => 10, 'event_imagename' => 'throneofthunderh25' );

	$sql_ary2 = array();
	foreach($sql_ary as $evt => $event)
	{
		$sql = 'SELECT event_id FROM ' . $table_prefix . 'bbdkp_events where event_name ' . $db->sql_like_expression($db->any_char . $event['event_name'] . $db->any_char);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow ($result);
		if(!$row)
		{
			$sql_ary2[] = $event;
		}
		$db->sql_freeresult ($result);
	}

	if (count($sql_ary2) > 0)
	{
		$db->sql_multi_insert ( $table_prefix . 'bbdkp_events', $sql_ary2 );
	}



}


/*
 * demo data
 * inserts 3 dummy members, 3 dkp accounts, 3 dummy items, and 1 dummy raid
 */
function populate_wow()
{

	global $db, $table_prefix, $umil, $user;

	$sql_ary = array();
	$sql_ary[] = array(
			'game_id'  		   => 'wow',
			'member_name'      => 'wow1',
			'member_status'    => 1 ,
			'member_level'     => 90,
			'member_race_id'   => 1,
			'member_class_id'  => 1,
			'member_rank_id'   => 0,
			'member_comment'   => 'Inserted by installer',
			'member_joindate'  => time(),
			'member_outdate'   => '1893456000',
			'member_guild_id'  => 1,
			'member_gender_id' => 0,
			'member_achiev'    => 0,
			'phpbb_user_id' 	=> $user->data['user_id'],
			'characterinfo' 	=> '',
	) ;

	$sql_ary[] = array(
			'game_id'  		   => 'wow',
			'member_name'      => 'wow2',
			'member_status'    => 1 ,
			'member_level'     => 90,
			'member_race_id'   => 2,
			'member_class_id'  => 2,
			'member_rank_id'   => 0,
			'member_comment'   => 'Inserted by installer',
			'member_joindate'  => time(),
			'member_outdate'   => '1893456000',
			'member_guild_id'  => 1,
			'member_gender_id' => 0,
			'member_achiev'    => 0,
			'phpbb_user_id' 	=> $user->data['user_id'],
			'characterinfo' 	=> '',
	) ;

	$sql_ary[] = array(
			'game_id'  		   => 'wow',
			'member_name'      => 'wow3',
			'member_status'    => 1 ,
			'member_level'     => 90,
			'member_race_id'   => 3,
			'member_class_id'  => 3,
			'member_rank_id'   => 0,
			'member_comment'   => 'Inserted by installer',
			'member_joindate'  => time(),
			'member_outdate'   => '1893456000',
			'member_guild_id'  => 1,
			'member_gender_id' => 0,
			'member_achiev'    => 0,
			'phpbb_user_id' 	=> $user->data['user_id'],
			'characterinfo' 	=> '',
	) ;
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_memberlist', $sql_ary );


}






?>