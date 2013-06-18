<?php
/**
 * bbdkp Guildwars2 install data
 * @author Sajaki9@gmail.com
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

function install_gw2()
{
	global $db, $table_prefix;
	
	// factions
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_factions where game_id = 'gw2'" );
	$sql_ary = array();
	$sql_ary [] = array ('game_id' => 'gw2','faction_id' => 1, 'faction_name' => 'Alliance' );
	$sql_ary [] = array ('game_id' => 'gw2','faction_id' => 2, 'faction_name' => 'Zaithan' );
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_factions', $sql_ary );
	unset ( $sql_ary );
   
	// professions
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_classes where game_id = 'gw2'" ); 
	$sql_ary = array ();
	//**Soldiers**
	$sql_ary [] = array ('game_id' => 'gw2', 'class_id' => 0, 'class_faction_id' => 1, 'class_armor_type' => 'CLOTH', 'class_min_level' => 1, 'class_max_level' => 80 , 'colorcode' =>  '#222222', 'imagename' => 'gw2_unknown');   
	//warrior
	$sql_ary [] = array ('game_id' => 'gw2', 'class_id' => 1, 'class_faction_id' => 1, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 80, 'colorcode' =>  '#FFAA44',  'imagename' => 'gw2_warrior');
	//Guardian    
	$sql_ary [] = array ('game_id' => 'gw2','class_id' => 2, 'class_faction_id' => 1, 'class_armor_type' => 'PLATE', 'class_min_level' => 1, 'class_max_level' => 80 , 'colorcode' =>  '#006699',  'imagename' => 'gw2_guardian');
	//**Adventurers**
	//engineer
	$sql_ary [] = array ('game_id' => 'gw2', 'class_id' => 3, 'class_faction_id' => 1, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 80, 'colorcode' =>  '#B67721',  'imagename' => 'gw2_engineer');
	//ranger
	$sql_ary [] = array ('game_id' => 'gw2','class_id' => 4, 'class_faction_id' => 1, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 80 , 'colorcode' =>  '#00CC66', 'imagename' => 'gw2_ranger');   
	//Thief
	$sql_ary [] = array ('game_id' => 'gw2', 'class_id' => 5, 'class_faction_id' => 1, 'class_armor_type' => 'LEATHER', 'class_min_level' => 1, 'class_max_level' => 80, 'colorcode' =>  '#777777',  'imagename' => 'gw2_thief');	 	  
	//**Scholars**
	$sql_ary [] = array ('game_id' => 'gw2','class_id' => 6, 'class_faction_id' => 1, 'class_armor_type' => 'MAIL', 'class_min_level' => 1, 'class_max_level' => 80 ,  'colorcode' =>  '#BB0044',  'imagename' => 'gw2_elementalist'); 
	//Mesmer
	$sql_ary [] = array ('game_id' => 'gw2','class_id' => 7, 'class_faction_id' => 1, 'class_armor_type' => 'ROBE', 'class_min_level' => 1, 'class_max_level' => 80 , 'colorcode' =>  '#FF33DD',  'imagename' => 'gw2_mesmer');
	//Necromancer
	$sql_ary [] = array ('game_id' => 'gw2','class_id' => 8, 'class_faction_id' => 1, 'class_armor_type' => 'ROBE', 'class_min_level' => 1, 'class_max_level' => 80 ,  'colorcode' =>  '#00FF88',  'imagename' => 'gw2_necromancer');
	//Commando 
	//$sql_ary [] = array ('game_id' => 'gw2','class_id' => 9, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY', 'class_min_level' => 1, 'class_max_level' => 80 ,  'colorcode' =>  '#0077BB',  'imagename' => 'gw2_comm');
	
	$db->sql_multi_insert ( $table_prefix . 'bbdkp_classes', $sql_ary );
	unset ( $sql_ary ); 

	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_races  where game_id = 'gw2'");
	$sql_ary = array ();
	//Unknown
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 0, 'race_faction_id' => 1, 'image_female' => 'gw2_unknown', 'image_male' => 'gw2_unknown'  ); 
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 1, 'race_faction_id' => 1, 'image_female' => 'gw2_sylvari',  'image_male' => 'gw2_sylvari' );  
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 2, 'race_faction_id' => 1, 'image_female' => 'gw2_norn',  'image_male' => 'gw2_norn' ); 
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 3, 'race_faction_id' => 1, 'image_female' => 'gw2_charr',  'image_male' => 'gw2_charr' ); 
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 4, 'race_faction_id' => 1, 'image_female' => 'gw2_asura',  'image_male' => 'gw2_asura' ) ; 
	$sql_ary [] = array ('game_id' => 'gw2','race_id' => 5, 'race_faction_id' => 1, 'image_female' => 'gw2_human',  'image_male' => 'gw2_human'  ); 
	$db->sql_multi_insert ($table_prefix . 'bbdkp_races', $sql_ary);
	unset ( $sql_ary );
	
	// Dictionary
	$db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_language  where game_id = 'gw2' and attribute in('class','race') ");
	$sql_ary = array ();
	// 
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Guardian' ,  'name_short' =>  'Guardian' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Engineer' ,  'name_short' =>  'Engineer' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Elementalist' ,  'name_short' =>  'Elementalist' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Mesmer' ,  'name_short' =>  'Mesmer' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Necromancer' ,  'name_short' =>  'Necromancer' );
	
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 0, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'T7-01' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 1, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Sylvari' ,  'name_short' =>  'Sylvari' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 2, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Norn' ,  'name_short' =>  'Norn' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 3, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Charr' ,  'name_short' =>  'Charr' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 4, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Asura' ,  'name_short' =>  'Asura' );
	$sql_ary[] = array('game_id' => 'gw2', 'attribute_id' => 5, 'language' => 'en' , 'attribute' =>  'race' , 'name' =>  'Human' ,  'name_short' =>  'Human' );

	$db->sql_multi_insert ( $table_prefix . 'bbdkp_language', $sql_ary );
	unset ( $sql_ary );
	
	$result = $db->sql_query('SELECT dkpsys_id FROM ' . $table_prefix . "bbdkp_dkpsystem  where dkpsys_name = 'GuildWars2 Dungeons' ");
	$row = $db->sql_fetchrow ($result); 
	if($row)
    {
		$gw2dkpid = $row['dkpsys_id'];    	
    }
    else
    {
    	// dkp pool
		$sql_ary = array (
			'dkpsys_name' => 'GuildWars2 Dungeons', 
			'dkpsys_status' => 'Y', 
			'dkpsys_addedby' => 'admin', 
			'dkpsys_default' => 'N' );
		$sql = 'INSERT INTO ' . $table_prefix . 'bbdkp_dkpsystem ' . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
		$gw2dkpid = $db->sql_nextid();
    }
    $db->sql_freeresult ( $result );

    $sql_ary = array();
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Ascalonian Catacombs (30)', 'event_color' => '#888888', 'event_value' => 5, 'event_imagename' => ''  ) ;
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Caudecus’s Manor  (45)', 'event_color' => '#888888', 'event_value' => 5 , 'event_imagename' => '') ;
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Twilight Arbor (50)', 'event_color' => '#00CC66', 'event_value' => 5, 'event_imagename' => '' );
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Sorrow’s Embrace (65)', 'event_color' => '#00CC66', 'event_value' => 5, 'event_imagename' => '' );
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Citadel of Flame (70)', 'event_color' => '#AA0099', 'event_value' => 5, 'event_imagename' => '' );
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Crucible of Eternity (75)', 'event_color' => '#AA0099', 'event_value' => 5, 'event_imagename' => '' );
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Honor of the Waves (80)', 'event_color' => '#AA0099', 'event_value' => 5, 'event_imagename' => '' );
	$sql_ary [] = array('event_dkpid' => $gw2dkpid , 'event_name' => 'Arah (80)', 'event_color' => '#AA0099', 'event_value' => 5, 'event_imagename' => 'gw2_ara' );	

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
?>