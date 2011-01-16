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

// TABLE DEFINITIONS
define('ADJUSTMENTS_TABLE',        $table_prefix . 'bbdkp_adjustments'); 	 
define('EVENTS_TABLE',             $table_prefix . 'bbdkp_events'); 	 
define('RAID_ITEMS_TABLE',         $table_prefix . 'bbdkp_raid_items'); 	 
define('LOGS_TABLE',               $table_prefix . 'bbdkp_logs'); 	 
define('MEMBER_RANKS_TABLE',       $table_prefix . 'bbdkp_member_ranks');
define('MEMBER_LIST_TABLE',        $table_prefix . 'bbdkp_memberlist'); 	
define('MEMBER_DKP_TABLE',         $table_prefix . 'bbdkp_memberdkp');  
define('NEWS_TABLE',               $table_prefix . 'bbdkp_news'); 	 
define('RAID_DETAIL_TABLE',        $table_prefix . 'bbdkp_raid_detail'); 	 
define('RAIDS_TABLE',              $table_prefix . 'bbdkp_raids'); 	 
define('CLASS_TABLE',              $table_prefix . 'bbdkp_classes'); 	 
define('RACE_TABLE',               $table_prefix . 'bbdkp_races'); 	 
define('FACTION_TABLE',            $table_prefix . 'bbdkp_factions'); 
define('DKPSYS_TABLE',     		   $table_prefix . 'bbdkp_dkpsystem');
define('PLUGINS_TABLE',	           $table_prefix . 'bbdkp_plugins');
define('GUILD_TABLE',	           $table_prefix . 'bbdkp_memberguild');
define('LOOTSYS_TABLE',	           $table_prefix . 'bbdkp_lootsystem');
define('BB_LANGUAGE',	           $table_prefix . 'bbdkp_language');
define('BOSSBASE',          	   $table_prefix . 'bbdkp_bosstable'); 	 
define('ZONEBASE',         		   $table_prefix . 'bbdkp_zonetable'); 

// plugin anchors

// RAIDTRACKER
define('RT_TEMP_RAIDINFO', $table_prefix . 'rt_temp_raidinfo');
define('RT_TEMP_PLAYERINFO', $table_prefix . 'rt_temp_playerinfo');
define('RT_TEMP_JOININFO', $table_prefix . 'rt_temp_joininfo');
define('RT_TEMP_BOSSKILLS', $table_prefix . 'rt_temp_bosskills');
define('RT_TEMP_ATTENDEES', $table_prefix . 'rt_temp_attendees');
define('RT_TEMP_LOOT', $table_prefix . 'rt_temp_loot');
define('RT_ALIASES_TABLE', $table_prefix . 'rt_aliases'); 	 
define('RT_EVENT_TRIGGERS_TABLE', $table_prefix . 'rt_eventtriggers'); 	 
define('RT_RAID_NOTE_TRIGGERS_TABLE', $table_prefix . 'rt_raidnote_triggers'); 	 
define('RT_OWN_RAIDS_TABLE', $table_prefix . 'rt_ownraids'); 	 
define('RT_ADD_ITEMS_TABLE', $table_prefix . 'rt_additems'); 	 
define('RT_IGNORE_ITEMS_TABLE', $table_prefix . 'rt_ignoreitems'); 
define('RT_IQ_POOR',          0); 	 
define('RT_IQ_COMMON',        1); 	 
define('RT_IQ_UNCOMMON',      2); 	 
define('RT_IQ_RARE',          3); 	 
define('RT_IQ_EPIC',          4); 	 
define('RT_IQ_LEGENDARY',     5); 	 
define('RT_AF_NONE',      0);         // 0 = None 	 
define('RT_AF_BOSS_KILL', 1);         // 1 = Boss Kill Time


// BBTIPS


// ARMORY


// RAIDPLANNER


// APPLY


?>