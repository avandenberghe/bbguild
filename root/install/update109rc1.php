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
 * wrapperfunction for uninstalling bbdkp 1.0.9rc1
 * the strings are hardcoded because 1.09 had no language info files (ouch) 
 * 
 * makes backup of old data 
 * 
 */
function bbdkp_109rc1_uninstall()
{
    global $db, $table_prefix, $umil, $bbdkp_table_prefix, $backup, $version, $config;
    
    //just to make sure...
	if(!$umil->config_exists('bbdkp_version', true) == '1.0.9rc1')
	{
		trigger_error('UMIL_109_ILLEGALVERSION', E_USER_WARNING); 
	}
    			
    if($umil->permission_exists('a_dkp'))
    {
	    $umil->permission_remove(array(
	            array('a_dkp', true),
	            array('a_dkp_no', true),         
	      ));
    }
      	
    // removing old configs
       
    if($umil->config_exists('bbdkp_date_format'))
    {
    	$umil->config_remove('bbdkp_date_format');
    }
    
    if($umil->config_exists('bbdkp_user_alimit'))
    {
    	$umil->config_remove('bbdkp_user_alimit');
    }

    if($umil->config_exists('bbdkp_user_elimit'))
    {
    	$umil->config_remove('bbdkp_user_elimit');
    }
    
    if($umil->config_exists('bbdkp_user_ilimit'))
    {
    	$umil->config_remove('bbdkp_user_ilimit');
    }
    
    if($umil->config_exists('bbdkp_user_nlimit'))
    {
    	$umil->config_remove('bbdkp_user_nlimit');
    }

    if($umil->config_exists('bbdkp_user_rlimit'))
    {
    	$umil->config_remove('bbdkp_user_rlimit');
    }

    if($umil->config_exists('bbdkp_user_llimit'))
    {
    	$umil->config_remove('bbdkp_user_llimit');
    }

    if($umil->config_exists('bbdkp_dkp_name'))
    {
    	$umil->config_remove('bbdkp_dkp_name');
    }    
    
    if($umil->config_exists('bbdkp_parsetags'))
    {
    	$umil->config_remove('bbdkp_parsetags');
    }    

    if($umil->config_exists('bbdkp_default_game'))
    {
    	$umil->config_remove('bbdkp_default_game');
    }    

    if($umil->config_exists('bbdkp_eqdkp_start'))
    {
    	$umil->config_remove('bbdkp_eqdkp_start');
    }    

    if($umil->config_exists('bbdkp_guildtag'))
    {
    	$umil->config_remove('bbdkp_guildtag');
    }    

    if($umil->config_exists('bbdkp_hide_inactive'))
    {
    	$umil->config_remove('bbdkp_hide_inactive');
    }    

    if($umil->config_exists('bbdkp_inactive_period'))
    {
    	$umil->config_remove('bbdkp_inactive_period');
    }        
    
    if($umil->config_exists('bbdkp_inactive_point_adj'))
    {
    	$umil->config_remove('bbdkp_inactive_point_adj');
    }  
    
    if($umil->config_exists('bbdkp_active_point_adj'))
    {
    	$umil->config_remove('bbdkp_active_point_adj');
    }  

    if($umil->config_exists('bbdkp_it_site'))
    {
    	$umil->config_remove('bbdkp_it_site');
    }
    
    if($umil->config_exists('bbdkp_it_autodl'))
    {
    	$umil->config_remove('bbdkp_it_autodl');
    }

    if($umil->config_exists('bbdkp_it_autsrch'))
    {
    	$umil->config_remove('bbdkp_it_autsrch');
    }

    if($umil->config_exists('bbdkp_it_lang'))
    {
    	$umil->config_remove('bbdkp_it_lang');
    }

    if($umil->config_exists('bbdkp_it_ttshow'))
    {
    	$umil->config_remove('bbdkp_it_ttshow');
    }

    if($umil->config_exists('bbdkp_it_tttype'))
    {
    	$umil->config_remove('bbdkp_it_tttype');
    }

    if($umil->config_exists('bbdkp_it_ttlbl'))
    {
    	$umil->config_remove('bbdkp_it_ttlbl');
    }    

    if($umil->config_exists('bbdkp_list_p1'))
    {
    	$umil->config_remove('bbdkp_list_p1');
    }    

    if($umil->config_exists('bbdkp_list_p2'))
    {
    	$umil->config_remove('bbdkp_list_p2');
    }    
    
    if($umil->config_exists('bbdkp_module_id'))
    {
    	$umil->config_remove('bbdkp_module_id');
    }    
    
    //removing all 1.0.9 tables if they exist !!
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
		$umil->table_remove($bbdkp_table_prefix . 'config');
    }
    
    if ($umil->table_exists($bbdkp_table_prefix . 'dkpsystem'))
    {
        if ($umil->table_exists('temp_dkpsystem'))
    	{
    		$umil->table_remove('temp_dkpsystem');
    	}  
    	$sql = 'CREATE TABLE temp_dkpsystem AS SELECT * FROM ' . $bbdkp_table_prefix . 'dkpsystem';
	    $result = $db->sql_query($sql);
		$umil->table_remove($bbdkp_table_prefix . 'dkpsystem');
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
    
    if ($umil->table_exists($bbdkp_table_prefix . 'memberlist'))
    {
    	//make backup
        if ($umil->table_exists('temp_memberlist'))
    	{
    		$umil->table_remove('temp_memberlist');
    	}
   	    $sql = 'CREATE TABLE temp_memberlist AS SELECT * FROM ' . $bbdkp_table_prefix . 'memberlist';
	    $result = $db->sql_query($sql);
	    
	    $sql = 'UPDATE temp_memberlist SET member_outdate = 0 where member_outdate < 0';
	    $result = $db->sql_query($sql);
	    
    	$umil->table_remove($bbdkp_table_prefix . 'memberlist');
    }
        
    if ($umil->table_exists($bbdkp_table_prefix . 'memberdkp'))
    {
    	//make backup
        if ($umil->table_exists('temp_memberdkp'))
    	{
    		$umil->table_remove('temp_memberdkp');
    	}    	
   	    $sql = 'CREATE TABLE temp_memberdkp AS SELECT * FROM ' . $bbdkp_table_prefix . 'memberdkp';
	    $result = $db->sql_query($sql);
    	$umil->table_remove($bbdkp_table_prefix . 'memberdkp');
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

    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'DKP'";
    $result = $db->sql_query($sql);
    $dkp0 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    /*******/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'bbDkp Menu'";
    $result = $db->sql_query($sql);
    $dkp1 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if($umil->module_exists('acp', $dkp1, 'bbDkp Settings'))
    {
	    $umil->module_remove('acp', $dkp1, 'bbDkp Settings'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'View Logs'))
    {
	    $umil->module_remove('acp', $dkp1, 'View Logs'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'Config'))
    {
	    $umil->module_remove('acp', $dkp1, 'Config'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'Indexpage'))
    {
	    $umil->module_remove('acp', $dkp1, 'Indexpage'); 
    }
    
    if( $umil->module_exists('acp', $dkp1, 'Indexpage'))
    {
	    $umil->module_remove('acp', $dkp1, 'Indexpage'); 
    }

    // old apply module 
    if( $umil->module_exists('acp', $dkp1, 'Application config'))
    {
	    $umil->module_remove('acp', $dkp1, 'Application config'); 
    }
    
    if( $umil->module_exists('acp', $dkp0, 'bbDkp Menu'))
    {
	    $umil->module_remove('acp', $dkp0, 'bbDkp Menu'); 
    }
    
	/*******/    
    
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
    
    /*******/    
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Raid management'";
    $result = $db->sql_query($sql);
    $dkp3 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp3, 'Add DKP Pool'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add DKP Pool'); 
    }
    
    if( $umil->module_exists('acp', $dkp3, 'List DKP Pools'))
    {
	    $umil->module_remove('acp', $dkp3, 'List DKP Pools'); 
    }

    if( $umil->module_exists('acp', $dkp3, 'Add Event'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Event'); 
    }

    if( $umil->module_exists('acp', $dkp3, 'List Event'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Event'); 
    }
    
    if( $umil->module_exists('acp', $dkp3, 'Add Raid'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Raid'); 
    }    
    
    if( $umil->module_exists('acp', $dkp3, 'List Raid'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Raid'); 
    }    
        
    if( $umil->module_exists('acp', $dkp3, 'Add Item'))
    {
	    $umil->module_remove('acp', $dkp3, 'Add Item'); 
    }    

    if( $umil->module_exists('acp', $dkp3, 'List Items'))
    {
	    $umil->module_remove('acp', $dkp3, 'List Items'); 
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
    
    /*******/    
    
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
    
    /*******/      
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Member DKP management'";
    $result = $db->sql_query($sql);
    $dkp5 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    
    if( $umil->module_exists('acp', $dkp5, 'List member DKP'))
    {
	    $umil->module_remove('acp', $dkp5, 'List member DKP'); 
    }    

    
    if( $umil->module_exists('acp', $dkp5, 'Edit member DKP'))
    {
	    $umil->module_remove('acp', $dkp5, 'Edit member DKP'); 
    }    

    if( $umil->module_exists('acp', $dkp5, 'Add Group Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'Add Group Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp5, 'List Group Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'List Group Adjustments'); 
    }    

    if( $umil->module_exists('acp', $dkp5, 'Add Individual Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'Add Individual Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp5, 'List Individual Adjustments'))
    {
	    $umil->module_remove('acp', $dkp5, 'List Individual Adjustments'); 
    }    
    
    if( $umil->module_exists('acp', $dkp0, 'Member DKP management'))
    {
	    $umil->module_remove('acp', $dkp0, 'Member DKP management'); 
    }    
          
    /*******/      
    
    $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'Itemstats'";
    $result = $db->sql_query($sql);
    $dkp6 = (int) $db->sql_fetchfield('module_id'); 
    $db->sql_freeresult($result);
    
    if( $umil->module_exists('acp', $dkp6, 'Settings'))
    {
	    $umil->module_remove('acp', $dkp6, 'Settings'); 
    }    
        
    if( $umil->module_exists('acp', $dkp0, 'Itemstats'))
    {
	    $umil->module_remove('acp', $dkp0, 'Itemstats'); 
    }    

    /*******/      
    
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


    /*******/     
    
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
    
    /*******/     
    
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
	    
    if( $umil->module_exists('acp', 0, 'DKP'))
	{
	   $umil->module_remove('acp', 0, 'DKP'); 
	}    
    
	$backup = true;
    return true; 
    
}

/******************************
 * 
 *  restoring data from temp table
 * 
 */
function bbdkp_restore109rc1()
{
	global $db, $table_prefix, $umil, $bbdkp_table_prefix, $backup;

	//did we make a backup ?
	if ($backup)
	{
   	   // insert dkp dkpsystem
	   $sql='delete from ' . $bbdkp_table_prefix . 'dkpsystem';
	   $db->sql_query($sql); 

	   $sql='select * from temp_dkpsystem'; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
	   		 			'dkpsys_id' 		    => (int) $row['dkpsys_id'] , 
	   		 			'dkpsys_name' 			=> (string) $row['dkpsys_name'] ,
	   		 			'dkpsys_status' 		=> (string) $row['dkpsys_status'] ,
						'dkpsys_default' 		=> (string) $row['dkpsys_default'] ,	   		 	
	   		 			'dkpsys_addedby' 		=> (string) $row['dkpsys_addedby'] , 
						'dkpsys_updatedby' 		=> (string) isset($row['dkpsys_updatedby']) ? $row['dkpsys_updatedby'] : $row['dkpsys_addedby'] ,	   		 	
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'dkpsystem' , $sql_ary);
	   }
	   $db->sql_freeresult($result);

   	   // insert dkp events
	   $sql='select * from temp_events'; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'event_dkpid' 		    => (int) $row['event_dkpid'] ,
	   		 			'event_id' 		    	=> (int) $row['event_id'] , 
	   		 			'event_name' 			=> (string) $row['event_name'] , 
	   		 			'event_value' 			=> (float) $row['event_value'] , 
	   		 			'event_added_by' 		=> (string) $row['event_added_by'] , 
						'event_updated_by' 		=> (string) isset($row['event_updated_by']) ? $row['event_updated_by'] : $row['event_added_by'] ,	   		 	
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'events' , $sql_ary);
	   }
	   $db->sql_freeresult($result);
	   
	   // insert raids
	   $sql = "select * from temp_raids "; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'raid_dkpid' 		    => (int) $row['raid_dkpid'] ,
	   		 			'raid_id' 		    	=> (int) $row['raid_id'] , 
	   		 			'raid_name' 			=> (string) $row['raid_name'] , 
	   		 			'raid_date' 			=> (int) $row['raid_date'] , 
	   		 			'raid_note' 			=> (string) $row['raid_note'] , 
	   		 			'raid_value' 			=> (float) $row['raid_value'] , 
	   		 			'raid_added_by' 		=> (string) $row['raid_added_by'] , 
	   		 			'raid_updated_by' 		=> (string) isset($row['raid_updated_by']) ? $row['raid_updated_by'] : $row['raid_added_by'] , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'raids' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result);
	   unset ($sql_ary);
	   
	   
	   // ranks
	   //get 'out' rankid
	   $outrank = 99; 
	   $sql="select rank_id from temp_member_ranks where rank_name= 'Out'";
	   if($result = $db->sql_query($sql))
	   {
		   $outrank = (int) $db->sql_fetchfield('rank_id');
		   $db->sql_freeresult($result);
		   
		   //update memberlist
		   if (isset($outrank))
		   {
			   $sql = 'update temp_memberlist set member_rank_id = 99 where member_rank_id = ' . $outrank; 
			   $db->sql_query($sql); 
		   }
		   
	   }
	   
	   $sql = 'delete from ' . $bbdkp_table_prefix . 'member_ranks where guild_id = 1 '; 
	   $db->sql_query($sql); 
	   
	   //insert guildranks
	   $sql = "select * from temp_member_ranks where rank_name != 'Out' "; 
	   if($result1 = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result1))
	   		 {
	   		 	$sql_ary [] = array (
		   				'guild_id' 		    	=> 1 , 
		   				'rank_id' 		    	=> (int) $row['rank_id'] , 
	   		 			'rank_name' 		    => $row['rank_name'] , 
		   				'rank_hide'		    	=> (int) $row['rank_hide'] ,  
		   				'rank_prefix' 	        => $row['rank_prefix'] , 
		   				'rank_suffix' 			=> $row['rank_suffix'] , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'member_ranks' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result);
	   unset ($sql_ary); 
	   
	   
	   // insert guildmembers 
	   // gender and achiev will be wrong but you can fix it by doing an armory update
	   $sql = "select * from temp_memberlist "; 
	   if($result2 = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result2))
	   		 {
	   		 	$sql_ary [] = array (
		   				'member_id' 		    => (int) $row['member_id'] ,
	   		 			'member_name' 		    => (string) $row['member_name'] , 
	   		 			'member_status' 		=> 1, 
		   				'member_level'		    => (int) $row['member_level'] ,  
		   				'member_race_id' 	    => (int) $row['member_race_id'] , 
		   				'member_class_id' 		=> (int) $row['member_class_id'] , 
	   		 			'member_rank_id' 		=> (int) $row['member_rank_id'] , 
	   		 			'member_comment' 		=> (string) $row['member_comment'] ,
	   		 			'member_joindate' 		=> (int) $row['member_joindate'] , 
	   		 			'member_outdate' 		=> (int) $row['member_outdate'] ,
	   		 			'member_guild_id' 		=> ($row['member_rank_id'] == 99) ? 0 : 1 ,
	   		 			'member_gender_id' 		=> 1 ,  
		   				'member_achiev' 		=> 0 ,
	   		 			'member_armory_url' 	=> ' ' , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'memberlist' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result2);
	   unset ($sql_ary); 
	   
	   // insert dkp points
	   // member status set to 1 everywhere
	   $sql = "select * from temp_memberdkp "; 
	   if($result3 = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result3))
	   		 {
	   		 	$sql_ary [] = array (
		   				'member_dkpid' 		    => (int) $row['member_dkpid'] ,
	   		 			'member_id' 		    => (int) $row['member_id'] , 
	   		 			'member_earned' 		=> (float) $row['member_earned'] , 
	   		 			'member_spent' 			=> (float) $row['member_spent'] , 
	   		 			'member_adjustment' 	=> (float) $row['member_adjustment'] , 
	   		 			'member_status' 		=> 1 , 
	   		 			'member_firstraid' 		=> (int) $row['member_firstraid'] , 
	   		 			'member_lastraid' 		=> (int) $row['member_lastraid'] , 
	   		 			'member_raidcount' 		=> (int) $row['member_raidcount'] , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'memberdkp' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result3);
	   unset ($sql_ary);

	   // insert dkp adjustments
	   $sql = "select * from temp_adjustments "; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'adjustment_dkpid' 		    => (int) $row['adjustment_dkpid'] ,
	   		 			'adjustment_id' 		    => (int) $row['adjustment_id'] , 
	   		 			'adjustment_value' 			=> (float) $row['adjustment_value'] , 
	   		 			'adjustment_date' 			=> (int) $row['adjustment_date'] , 
	   		 			'member_id'	 				=> (int) $row['member_id'] , 
	   		 			'adjustment_reason' 		=> (string) $row['adjustment_reason'] , 
	   		 			'adjustment_added_by' 		=> (string) $row['adjustment_added_by'] , 
	   		 			'adjustment_updated_by' 	=> (string) isset($row['adjustment_updated_by']) ? $row['adjustment_updated_by'] : $row['adjustment_added_by'] ,
	   		 			'adjustment_group_key' 		=> (string) $row['adjustment_group_key'] , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'adjustments' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result3);
	   unset ($sql_ary);
	   	   
	   
	   // raidattendees
	   // the group by is done because raid attendees has pk now
	   
	   $sql_array = array(
		    'SELECT'    => 'a.raid_id, a.member_id, a.member_name',
		 
		    'FROM'      => array(
		        'temp_raid_attendees' 	=> 'a',
		        'temp_raids'    		=> 'r',
	   			'temp_memberdkp'    	=> 'd',
		    ),
		 
		    'WHERE'     =>  'a.raid_id = r.raid_id
		        			AND a.member_id = d.member_id' ,
		    
		    'GROUP_BY'  => 'a.raid_id, a.member_id, a.member_name'
		);
	   
	   $sql = $db->sql_build_query('SELECT', $sql_array);
	   
	   
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'raid_id' 		    	=> (int) $row['raid_id'] ,
	   		 			'member_id'	 			=> (int) $row['member_id'] ,
	   		 			'member_name' 			=> (string) $row['member_name'] ,
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'raid_attendees' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result);
	   unset ($sql_ary);

	   // insert items
	   $sql = "select * from temp_items "; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'item_dkpid' 		    => (int) $row['item_dkpid'] ,
	   		 			'item_id' 		    	=> (int) $row['item_id'] , 
	   		 			'item_name' 			=> (string) $row['item_name'] ,
						'item_buyer' 			=> (string) $row['item_buyer'] ,	   		 		 
	   		 			'raid_id'	 			=> (int) $row['raid_id'] ,
	   		 			'item_value' 			=> (float) $row['item_value'] , 
	   		 			'item_date'	 			=> (int) $row['item_date'] , 
	   		 			'item_added_by' 		=> (string) $row['item_added_by'] , 
	   		 			'item_updated_by' 		=> (string) isset($row['item_updated_by']) ? $row['item_updated_by'] : $row['item_added_by'] , 
	   		 			'item_group_key' 		=> (string) $row['item_group_key'] ,
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'items' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result);
	   unset ($sql_ary);
	   	   
	   // insert dkp news
	   $sql = "select * from temp_news "; 
	   if($result = $db->sql_query($sql))
	   {
	   		 $sql_ary = array();
	   		 while ($row = $db->sql_fetchrow($result))
	   		 {
	   		 	$sql_ary [] = array (
		   				'news_id' 		    	=> (int) $row['news_id'] ,
	   		 			'user_id' 		    	=> (int) $row['user_id'] , 
	   		 			'news_date' 			=> (int) $row['news_date'] , 
	   		 			'news_message' 			=> (string) $row['news_message'] , 
	   		 			'news_headline' 		=> (string) $row['news_headline'] , 
		  			);
	   		 }
	   		 $db->sql_multi_insert($bbdkp_table_prefix . 'news' , $sql_ary);
	   		 
	   }
	   $db->sql_freeresult($result);
	   unset ($sql_ary);
	   
	   
	   // restore successfull
	   return array('command' => sprintf($user->lang['UMIL_OLD_RESTORE_SUCCESS'], $bbdkpold), 'result' => 'SUCCESS');
	}
	else 
	{
		// no restore performed
		return array('command' => sprintf($user->lang['UMIL_OLD_RESTORE_NOT'], $bbdkpold), 'result' => 'SUCCESS');
	}
    
}


?>