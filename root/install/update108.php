<?php
/**
 * bbdkp updater
 * 
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

/****************************** 
 * wrapperfunction for uninstalling bbdkp 1.0.8
 * 
 * makes backup of old data 
 */
function bbdkp_old_uninstall($current_version, $game)
{
    global $db, $user, $table_prefix, $umil, $bbdkp_table_prefix, $backup, $version, $config;
          
    if(!defined('OLD_CONFIG_TABLE'))
    {
        define('OLD_CONFIG_TABLE',  'bbeqdkp_config');
    }
    
    $sql = array();
    			
    if($umil->permission_exists('a_dkp'))
    {
	    $umil->permission_remove(array(
	            array('a_dkp', true),
	            array('a_dkp_no', true),         
	      ));
    }
      	
    //removing all 1.0.8 tables if they exist !!
    if ($umil->table_exists($bbdkp_table_prefix . 'item_cache'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'item_cache');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'logs'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'logs');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'config'))
    {
    	//saving old config table
        if ($umil->table_exists('temp_config'))
    	{
    		$umil->table_remove('temp_config');
    	}  
    	
    	$sql = 'CREATE TABLE temp_config AS SELECT * FROM ' . $bbdkp_table_prefix . 'config';
	    $result = $db->sql_query($sql);
		$umil->table_remove($bbdkp_table_prefix . 'temp_config');
    }
    
   if ($umil->table_exists($bbdkp_table_prefix . 'events'))
    {
    	//make backup
        if ($umil->table_exists('temp_events'))
    	{
    		$umil->table_remove('temp_events');
    	}
    	$sql = 'CREATE TABLE temp_events AS SELECT * FROM ' . $bbdkp_table_prefix . 'events';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'events');
    }
        
    if ($umil->table_exists($bbdkp_table_prefix . 'raids'))
    {
    	//make backup
        if ($umil->table_exists('temp_raids'))
    	{
    		$umil->table_remove('temp_raids');
    	}   
    	$sql = 'CREATE TABLE temp_raids AS SELECT * FROM ' . $bbdkp_table_prefix . 'raids';
	    $result = $db->sql_query($sql);
		$umil->table_remove($bbdkp_table_prefix . 'raids');
    }
        
    
    if ($umil->table_exists($bbdkp_table_prefix . 'member_ranks'))
    {
        //make backup
    	if ($umil->table_exists('temp_member_ranks'))
    	{
    		$umil->table_remove('temp_member_ranks');
    	}
   	    $sql = 'CREATE TABLE temp_member_ranks AS SELECT * FROM ' . $bbdkp_table_prefix . 'member_ranks';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'member_ranks');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'members'))
    {
    	//make backup
        if ($umil->table_exists('temp_members'))
    	{
    		$umil->table_remove('temp_members');
    	}
   	    $sql = 'CREATE TABLE temp_members AS SELECT * FROM ' . $bbdkp_table_prefix . 'members';
	    $result = $db->sql_query($sql);
	    
    	$umil->table_remove($bbdkp_table_prefix . 'members');
    }
        
    if ($umil->table_exists($bbdkp_table_prefix . 'adjustments'))
    {
    	//make backup
        if ($umil->table_exists('temp_adjustments'))
    	{
    		$umil->table_remove('temp_adjustments');
    	}
    	$sql = 'CREATE TABLE temp_adjustments AS SELECT * FROM ' . $bbdkp_table_prefix . 'adjustments';
	    $result = $db->sql_query($sql);
		$umil->table_remove($bbdkp_table_prefix . 'adjustments');
    }

    
    if ($umil->table_exists($bbdkp_table_prefix . 'items'))
    {
    	//make backup
    	if ($umil->table_exists('temp_items'))
    	{
    		$umil->table_remove('temp_items');
    	}
   	    $sql = 'CREATE TABLE temp_items AS SELECT * FROM ' . $bbdkp_table_prefix . 'items';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'items');
    }


    if ($umil->table_exists($bbdkp_table_prefix . 'raid_attendees'))
    {
		//make backup
        if ($umil->table_exists('temp_raid_attendees'))
    	{
    		$umil->table_remove('temp_raid_attendees');
    	}     	   
    	$sql = 'CREATE TABLE temp_raid_attendees AS SELECT * FROM ' . $bbdkp_table_prefix . 'raid_attendees';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'raid_attendees');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'news'))
    {
    	//make backup
        if ($umil->table_exists('temp_news'))
    	{
    		$umil->table_remove('temp_news');
    	}  
    	$sql = 'CREATE TABLE temp_news AS SELECT * FROM ' . $bbdkp_table_prefix . 'news';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'news');
    }
       
    if ($umil->table_exists($bbdkp_table_prefix . 'classes'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'classes');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'races'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'races');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'factions'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'factions');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'indexpage'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'indexpage');
    }
                        
    if ($umil->table_exists($bbdkp_table_prefix . 'plugins'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'plugins');
    }

    // old bossprogress plugin    
    if ($umil->table_exists($bbdkp_table_prefix . 'bb_config'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'bb_config');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'bb_offsets'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'bb_offsets');
    }

    // old ctrt plugin    
    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_config'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_config');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_aliases'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_aliases');
    }    
    
    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_event_triggers'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_event_triggers');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_raid_note_triggers'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_raid_note_triggers');
    }

    
    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_own_raids'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_own_raids');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_add_items'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_add_items');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'ctrt_ignore_items'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'ctrt_ignore_items');
    }
    
    // old roster plugin
    if ($umil->table_exists($bbdkp_table_prefix . 'armory'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'armory');
    }

    if ($umil->table_exists($bbdkp_table_prefix . 'armory_settings'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'armory_settings');
    }

    // old apply plugin
    if ($umil->table_exists($bbdkp_table_prefix . 'appconfig'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'appconfig');
    }    

    if ($umil->table_exists($bbdkp_table_prefix . 'apptemplate'))
    {
		$umil->table_remove($bbdkp_table_prefix . 'apptemplate');
    }    
    
    /*** removing old modules ****/
	// this will be module id 190 normally
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'DKP'";
    $result = $db->sql_query($sql);
    $dkp0 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    /*** removing 1.0.8 main modules ****/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Menu'";
    $result = $db->sql_query($sql);
    $dkp1 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);

    if( $umil->module_exists('acp', $dkp1, 'EQdkp Index'))
    {
	    $umil->module_remove('acp', $dkp1, 'EQdkp Index'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'View Logs'))
    {
	    $umil->module_remove('acp', $dkp1, 'View Logs'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'Config'))
    {
	    $umil->module_remove('acp', $dkp1, 'Config'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'indexpage config'))
    {
	    $umil->module_remove('acp', $dkp1, 'indexpage config'); 
    }

    if( $umil->module_exists('acp', $dkp0, 'Menu'))
    {
	    $umil->module_remove('acp', $dkp0, 'Menu'); 
    }
    
	/*** removing 1.0.8 news modules ****/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'News management'";
    $result = $db->sql_query($sql);
    $dkp2 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);

    if( $umil->module_exists('acp', $dkp2, 'Add News'))
    {
	    $umil->module_remove('acp', $dkp2, 'Add News'); 
    }
    
    if( $umil->module_exists('acp', $dkp2, 'List News'))
    {
	    $umil->module_remove('acp', $dkp2, 'List News'); 
    }
        
    if( $umil->module_exists('acp', $dkp0, 'News management'))
    {
	    $umil->module_remove('acp', $dkp0, 'News management'); 
    }
    
    /*** removing 1.0.8 raid modules ****/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Raid management'";
    $result = $db->sql_query($sql);
    $dkp3 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp3, 'Add Raid'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Raid'); 
    }    
    
    if( $umil->module_exists('acp', $dkp3, 'List Raid'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Raid'); 
    }    

    if( $umil->module_exists('acp', $dkp3, 'Add Event'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Event'); 
    }

    if( $umil->module_exists('acp', $dkp3, 'List Event'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Event'); 
    }
    
    if( $umil->module_exists('acp', $dkp3, 'Add Item'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Item'); 
    }    

    if( $umil->module_exists('acp', $dkp3, 'List Items'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Items'); 
    }    
    
    if( $umil->module_exists('acp', $dkp3, 'Parse Wow (hide)'))
    {
	    $umil->module_remove('acp', $dkp3, 'Parse Wow (hide)'); 
    }    
    
    if( $umil->module_exists('acp', $dkp3, 'View Item'))
    {
	    $umil->module_remove('acp', $dkp3, 'View Item'); 
    }    

    if( $umil->module_exists('acp', $dkp3, 'Search Item'))
    {
	    $umil->module_remove('acp', $dkp3, 'Search Item'); 
    }    
    
    if( $umil->module_exists('acp', $dkp0, 'Raid management'))
    {
	    $umil->module_remove('acp', $dkp0, 'Raid management'); 
    }    
    
    /** removing 1.0.8 member module *****/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Member management'";
    $result = $db->sql_query($sql);
    $dkp4 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp4, 'Add member'))
    {
	    $umil->module_remove('acp', $dkp4, 'Add member'); 
    }    
    
    if( $umil->module_exists('acp', $dkp4, 'List members'))
    {
	    $umil->module_remove('acp', $dkp4, 'List members'); 
    }    
    
    if( $umil->module_exists('acp', $dkp4, 'Ranks'))
    {
	    $umil->module_remove('acp', $dkp4, 'Ranks'); 
    }    
    
    if( $umil->module_exists('acp', $dkp4, 'Transfer'))
    {
	    $umil->module_remove('acp', $dkp4, 'Transfer'); 
    }    
    
    if( $umil->module_exists('acp', $dkp0, 'Member management'))
    {
	    $umil->module_remove('acp', $dkp0, 'Member management'); 
    }    
    
    /***** removing 1.0.8 adjustments  *****/      
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Adjustments management'";
    $result = $db->sql_query($sql);
    $dkp5 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp5, 'Add Group Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'Add Group Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp5, 'Add Individual Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'Add Individual Adjustments'); 
    }    

    if( $umil->module_exists('acp', $dkp5, 'List Group Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'List Group Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp5, 'List Individual Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'List Individual Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp0, 'Adjustments management'))
    {
	    $umil->module_remove('acp', $dkp0, 'Adjustments management'); 
    }    
          
    /*** removing 1.0.8 bossprogress  ****/      
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Bossprogress'";
    $result = $db->sql_query($sql);
    $dkp6 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp6, 'Bossbase config'))
    {
	    $umil->module_remove('acp', $dkp6, 'Bossbase config'); 
    }    
    
    if( $umil->module_exists('acp', $dkp6, 'Bossbase offset'))
    {
	    $umil->module_remove('acp', $dkp6, 'Bossbase offset'); 
    }    
    
    if( $umil->module_exists('acp', $dkp6, 'Bossprogress config'))
    {
	    $umil->module_remove('acp', $dkp6, 'Bossprogress config'); 
    }    
    
    if( $umil->module_exists('acp', $dkp0, 'Bossprogress'))
    {
	    $umil->module_remove('acp', $dkp0, 'Bossprogress'); 
    }    
 	
	/***removing 1.0.8 roster ****/     
    
 	$sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Roster'";
    $result = $db->sql_query($sql);
    $dkp8 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    if(isset($dkp8))
    {
   		if( $umil->module_exists('acp', $dkp8, 'Armory config'))
	    {
		    $umil->module_remove('acp', $dkp8, 'Armory config'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp0, 'Roster'))
	    {
		    $umil->module_remove('acp', $dkp0, 'Roster'); 
	    }    
    }
    
    /*** removing 1.0.8 ctrt  ****/     
    
 	$sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'CTRT'";
    $result = $db->sql_query($sql);
    $dkp7 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if(isset($dkp7))
    {
    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Manage Settings'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Manage Settings'); 
	    }    
  	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Alias (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Alias (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Alias (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Alias (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Alias (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Alias (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Alias (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Alias (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Event Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Event Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Event Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Event Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Event Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Event Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Event Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Event Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Ignore Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Ignore Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Ignore Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Ignore Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Ignore Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Ignore Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Ignore Items (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Ignore Items (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Own Raids(hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Own Raids(hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Own Raids (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Own Raids (hide)'); 
	    }    

	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Own Raids (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Own Raids (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Own Raids (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Own Raids (hide)'); 
	    }  
	    	     	
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Add Raid Note Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Add Raid Note Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT List Raid Note Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT List Raid Note Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Export Raid Note Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Export Raid Note Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import Raid Note Triggers (hide)'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import Raid Note Triggers (hide)'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp7, 'CTRT Import'))
	    {
		    $umil->module_remove('acp', $dkp7, 'CTRT Import'); 
	    }    
	    
	    if( $umil->module_exists('acp', $dkp0, 'CTRT'))
	    {
		    $umil->module_remove('acp', $dkp0, 'CTRT'); 
	    }    
	    
    }
    
    if( $umil->module_exists('acp', 0, 'DKP'))
	{
	   $umil->module_remove('acp', 0, 'DKP'); 
	}    
    
	$backup = true;
    return true; 
    
}


?>
