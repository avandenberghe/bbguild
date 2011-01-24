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
define('URI_NAMEID',     'member_id');	 
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

// BBTIPS

// ARMORY

// RAIDPLANNER

// APPLY


?>