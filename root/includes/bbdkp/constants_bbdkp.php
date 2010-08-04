<?php
/**
* Constants defined for bbdkp
* 
* @package bbDkp.includes
* @version $Id$
* @copyright (c) 2009 bbDKP 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
global $phpbb_root_path; 
    
//BBDKP
define('URI_ADJUSTMENT', 'adj');
define('URI_DKPSYS',     'dkp'); 	
define('URI_EVENT',      'eve'); 	 
define('URI_ITEM',       'item'); 	 
define('URI_LOG',        'log'); 	 
define('URI_NAME',       'name'); 	 
define('URI_NEWS',       'news'); 	 
define('URI_ORDER',      'o'); 	 
define('URI_PAGE',       'pag'); 	 
define('URI_RAID',       'raid');
define('URI_GUILD',       'guild');  	 
define('USER_LLIMIT', 40);  // LOG LIMIT

$bbdkp_table_prefixx = 'bbeqdkp_';

// TABLE DEFINITIONS
define('ITEMCACHE_TABLE',	        $bbdkp_table_prefixx . 'item_cache');
define('ADJUSTMENTS_TABLE',        $bbdkp_table_prefixx . 'adjustments'); 	 
define('EVENTS_TABLE',             $bbdkp_table_prefixx . 'events'); 	 
define('ITEMS_TABLE',              $bbdkp_table_prefixx . 'items'); 	 
define('LOGS_TABLE',               $bbdkp_table_prefixx . 'logs'); 	 
define('MEMBER_RANKS_TABLE',       $bbdkp_table_prefixx . 'member_ranks');
define('OLD_CONFIG_TABLE',         $bbdkp_table_prefixx . 'config');
define('MEMBER_LIST_TABLE',        $bbdkp_table_prefixx . 'memberlist'); 	
define('MEMBER_DKP_TABLE',         $bbdkp_table_prefixx . 'memberdkp');  
define('NEWS_TABLE',               $bbdkp_table_prefixx . 'news'); 	 
define('RAID_ATTENDEES_TABLE',     $bbdkp_table_prefixx . 'raid_attendees'); 	 
define('RAIDS_TABLE',              $bbdkp_table_prefixx . 'raids'); 	 
define('CLASS_TABLE',              $bbdkp_table_prefixx . 'classes'); 	 
define('RACE_TABLE',               $bbdkp_table_prefixx . 'races'); 	 
define('FACTION_TABLE',            $bbdkp_table_prefixx . 'factions'); 
define('DKPSYS_TABLE',     			$bbdkp_table_prefixx . 'dkpsystem');
define('PLUGINS_TABLE',	        	$bbdkp_table_prefixx . 'plugins');
define('GUILD_TABLE',	        	$bbdkp_table_prefixx . 'memberguild');

// plugin anchors

// BOSSPROGRESS
define('BOSSBASE_CONFIG',       $bbdkp_table_prefixx . 'bb_config'); 	 
define('BOSSBASE_OFFSETS',      $bbdkp_table_prefixx . 'bb_offsets'); 
define('BOSSBASE',          	$bbdkp_table_prefixx . 'bb_bosstable'); 	 
define('ZONEBASE',         		$bbdkp_table_prefixx . 'bb_zonetable'); 

// RAIDTRACKER
define('RT_TEMP_RAIDINFO', $bbdkp_table_prefixx . 'rt_temp_raidinfo');
define('RT_TEMP_PLAYERINFO', $bbdkp_table_prefixx . 'rt_temp_playerinfo');
define('RT_TEMP_JOININFO', $bbdkp_table_prefixx . 'rt_temp_joininfo');
define('RT_TEMP_BOSSKILLS', $bbdkp_table_prefixx . 'rt_temp_bosskills');
define('RT_TEMP_ATTENDEES', $bbdkp_table_prefixx . 'rt_temp_attendees');
define('RT_TEMP_LOOT', $bbdkp_table_prefixx . 'rt_temp_loot');
define('RT_ALIASES_TABLE', $bbdkp_table_prefixx . 'rt_aliases'); 	 
define('RT_EVENT_TRIGGERS_TABLE', $bbdkp_table_prefixx . 'rt_eventtriggers'); 	 
define('RT_RAID_NOTE_TRIGGERS_TABLE', $bbdkp_table_prefixx . 'rt_raidnote_triggers'); 	 
define('RT_OWN_RAIDS_TABLE', $bbdkp_table_prefixx . 'rt_ownraids'); 	 
define('RT_ADD_ITEMS_TABLE', $bbdkp_table_prefixx . 'rt_additems'); 	 
define('RT_IGNORE_ITEMS_TABLE', $bbdkp_table_prefixx . 'rt_ignoreitems'); 
define('RT_IQ_POOR',          0); 	 
define('RT_IQ_COMMON',        1); 	 
define('RT_IQ_UNCOMMON',      2); 	 
define('RT_IQ_RARE',          3); 	 
define('RT_IQ_EPIC',          4); 	 
define('RT_IQ_LEGENDARY',     5); 	 
define('RT_AF_NONE',      0);         // 0 = None 	 
define('RT_AF_BOSS_KILL', 1);         // 1 = Boss Kill Time

// BBTIPS
define('BBTIPS_CACHE_TBL', $bbdkp_table_prefixx . 'wowhead_cache');							// main cache table
define('BBTIPS_CRAFT_TBL', $bbdkp_table_prefixx . 'wowhead_craftable');						// craftable table
define('BBTIPS_CRAFT_REAGENT_TBL', $bbdkp_table_prefixx . 'wowhead_craftable_reagent');		// table for reagents of craftable
define('BBTIPS_CRAFT_SPELL_TBL', $bbdkp_table_prefixx . 'wowhead_craftable_spell');			// craftable spells
define('BBTIPS_ITEMSET_TBL', $bbdkp_table_prefixx . 'wowhead_itemset');						// itemset table
define('BBTIPS_ITEMSET_REAGENT_TBL', $bbdkp_table_prefixx . 'wowhead_itemset_reagent');		// itemset reagent table
define('BBTIPS_GEM_TBL', $bbdkp_table_prefixx . 'wowhead_gems');							// gem table
define('BBTIPS_ENCHANT_TBL', $bbdkp_table_prefixx . 'wowhead_enchants');					// enchant table
define('BBTIPS_NPC_TBL', $bbdkp_table_prefixx . 'wowhead_npc');								// npc table

// ARMORY

// RAIDPLANNER

// APPLY
	define('APPTEMPLATE_TABLE', $bbdkp_table_prefixx . 'apptemplate');

?>