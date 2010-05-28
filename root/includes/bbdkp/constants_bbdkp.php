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
define('BOSSBASE_CONFIG',          $bbdkp_table_prefixx . 'bb_config'); 	 
define('BOSSBASE_OFFSETS',         $bbdkp_table_prefixx . 'bb_offsets'); 

// RAIDTRACKER

// BBTIPS

// ARMORY

// RAIDPLANNER

// APPLY


?>