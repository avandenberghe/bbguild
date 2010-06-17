<?php
/**
 * @package bbDkp-installer
 * @author sajaki9@gmail.com
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

define('UMIL_AUTO', true);
define('IN_PHPBB', true);
define('IN_INSTALL', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

// We only allow a founder install this MOD
if ($user->data['user_type'] != USER_FOUNDER)
{
    if ($user->data['user_id'] == ANONYMOUS)
    {
        login_box('', 'LOGIN');
    }

    trigger_error('NOT_AUTHORISED', E_USER_WARNING);
}

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
    trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

if (!file_exists($phpbb_root_path . 'install/index.' . $phpEx))
{
    trigger_error('Warning! Install directory has wrong name. it must be \'install\'. Please rename it and launch again.', E_USER_WARNING);
}

 //trigger_error('Warning! please do these DIY instructions before. : <........>', E_USER_WARNING);
 
 
/*
if (isset($config['MOD1']))
{
   if($config['MOD1'] <= '1.0.3')
   
     trigger_error('Warning! please do these DIY instructions before. : <........>', E_USER_WARNING);
}
*/
    




// The name of the mod to be displayed during installation.
$mod_name = 'bbDKP 1.1.0';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'bbdkp_version';

/*
* The language file which will be included when installing
*/
$language_file = 'mods/dkp_admin';

/*
* Run Options 
*/
$options = array(
		'guildtag'	=> array('lang' => 'UMIL_GUILD', 'type' => 'text:40:255', 'explain' => false, 'select_user' => false),
        'realm'	    => array('lang' => 'REALM_NAME', 'type' => 'text:40:255', 'explain' => false, 'select_user' => false),
		'region'   => array('lang' => 'REGION', 'type' => 'select', 'function' => 'regionoptions', 'explain' => true),
	    'game'     => array('lang' => 'UMIL_CHOOSE', 'type' => 'select', 'function' => 'gameoptions', 'explain' => true),
);

/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
//$logo_img = 'images/bbdkp.png';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/

/*
 * bbDKP TABLE PREFIX
 */
$bbdkp_table_prefix = "bbeqdkp_";

/*
 * backup of 1.0.9 data ? 
 */
$backup = false;


// include required sub installers
$game = request_var('game', '');
switch ($game)
	{
		case 'aion':
			include($phpbb_root_path .'install/install_aion.' . $phpEx);
			break;
    	case 'daoc':
			include($phpbb_root_path .'install/install_daoc.' . $phpEx);
			break; 
		case 'eq':
			include($phpbb_root_path .'install/install_eq.' . $phpEx);
			break; 
		case 'eq2':
			include($phpbb_root_path .'install/install_eq2.' . $phpEx);
			break; 
		case 'FFXI':
			include($phpbb_root_path .'install/install_ffxi.' . $phpEx);
			break; 
		case 'lotro':
			include($phpbb_root_path .'install/install_lotro.' . $phpEx);
			break;
		case 'vanguard':
			include($phpbb_root_path .'install/install_vanguard.' . $phpEx);
			break; 
		case 'warhammer':
			include($phpbb_root_path .'install/install_warhammer.' . $phpEx);
			break; 
		case 'wow':				    
			include($phpbb_root_path .'install/install_wow.' . $phpEx);
			break;
		default :
			break; 
}

$versions = array(

	//not released version
	'1.1.0-B1'    => array(
       'custom' => array( 
            // removing old data (if bbdkp 1.09 was installed before) 
			// this makes a backup of all essential legacy tables
       		'bbdkp_cleanupold',
       ) 	 
	),

	
    '1.1.0-RC1'    => array(
    
    	// bbdkp tables (this uses the layout from develop/create_schema_files.php and from phpbb_db_tools)
        'table_add' => array(
        
        array($bbdkp_table_prefix . 'adjustments', array(
              'COLUMNS'        => array(
                  'adjustment_dkpid'     => array('USINT', 0),
                  'adjustment_id'        => array('UINT', NULL, 'auto_increment'),
                  'adjustment_value'     => array('DECIMAL:11', 0),
        		  'adjustment_date'      => array('TIMESTAMP', 0),
				  'member_id'        	 => array('UINT', 0),
				  'adjustment_reason'    => array('VCHAR_UNI', ''),
				  'adjustment_added_by'  => array('VCHAR_UNI:255', ''),
				  'adjustment_updated_by'=> array('VCHAR_UNI:255', ''),
				  'adjustment_group_key' => array('VCHAR', ''),
                ),
                'PRIMARY_KEY'    => 'adjustment_id',
            ),
          ),
            
          // bossprogress config table
          array($bbdkp_table_prefix . 'bb_config', array(
                    'COLUMNS'        	=> array(
                        'config_name'	=> array('VCHAR:255', ''),
                        'config_value'	=> array('VCHAR:255', ''),
                   )
                ),
            ),
            
           
          // bossprogress offset config table
          array($bbdkp_table_prefix . 'bb_offsets', array(
                    'COLUMNS'        	=> array(
                        'name'	    	=> array('VCHAR:255', ''),
                        'fdate'			=> array('VCHAR:255', ''),
                        'ldate'			=> array('VCHAR:255', ''),
                        'counter'		=> array('VCHAR:255', ''),
                   )
                ),
            ),
            

            array($bbdkp_table_prefix . 'classes', array(
                    'COLUMNS'        => array(
                        'c_index'    		=> array('USINT', NULL, 'auto_increment'),
                        'class_id'   		=> array('USINT', 0),
                        'class_name'        => array('VCHAR_UNI:255', ''),
                        'class_min_level'	=> array('USINT', 0),
                        'class_max_level'	=> array('USINT', 0),
                        'class_armor_type'	=> array('VCHAR_UNI', ''),
                        'class_hide'		=> array('BOOL', 0),
            			'dps'				=> array('USINT', 0),
            			'tank'				=> array('USINT', 0),
            			'heal'				=> array('USINT', 0),
            
                    ),
                    'PRIMARY_KEY'    => 'c_index',
                ),
            ),
            
            array($bbdkp_table_prefix . 'dkpsystem', array(
                    'COLUMNS'        => array(
                        'dkpsys_id'    		=> array('USINT', NULL, 'auto_increment'),
                        'dkpsys_name'   	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_status'     => array('VCHAR:2', 'Y'),
                        'dkpsys_addedby'	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_updatedby'	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_default'	=> array('VCHAR:2', 'N'),
                    ),
                    'PRIMARY_KEY'    => 'dkpsys_id',
                ),
            ),
            
            
            array($bbdkp_table_prefix . 'events', array(
                    'COLUMNS'        => array(
                        'event_id'    		=> array('UINT', NULL, 'auto_increment'),
                        'event_dkpid'   	=> array('USINT', 0),
                        'event_name'     	=> array('VCHAR_UNI:255', ''),
                        'event_value'		=> array('DECIMAL:11', 0),
                        'event_added_by'	=> array('VCHAR_UNI:255', ''),
                        'event_updated_by'	=> array('VCHAR_UNI:255', ''),
                    ),
                    'PRIMARY_KEY'    => 'event_id',
                    'KEYS'            => array('event_dkpid'    => array('INDEX', 'event_dkpid')),
                    
                ),
            ),
            
            array($bbdkp_table_prefix . 'roles', array(
                    'COLUMNS'        => array(
                        'r_index'    		=> array('USINT', NULL, 'auto_increment'),
                        'role_id'   		=> array('USINT', 0),
                        'role_name'     	=> array('VCHAR_UNI', ''),
                    ),
                    'PRIMARY_KEY'    => 'r_index',                   
                    
                ),
            ),
            
            array($bbdkp_table_prefix . 'factions', array(
                    'COLUMNS'        => array(
                        'f_index'    		=> array('USINT', NULL, 'auto_increment'),
                        'faction_id'   		=> array('USINT', 0),
                        'faction_name'     	=> array('VCHAR_UNI', ''),
                        'faction_hide'		=> array('BOOL', 0),
                    ),
                    'PRIMARY_KEY'    => 'f_index',                   
                    
                ),
            ),
            
            array($bbdkp_table_prefix . 'items', array(
                    'COLUMNS'        => array(
                       'item_dkpid'       => array('USINT', 0),
                       'item_id'          => array('UINT', NULL, 'auto_increment'),
                       'item_name'        => array('VCHAR_UNI:255', ''),
						'item_buyer'      => array('VCHAR_UNI:255', ''),
						'raid_id'         => array('UINT', 0),
						'item_value'      => array('DECIMAL:11', 0.00),
						'item_date'       => array('TIMESTAMP', 0),
						'item_added_by'   => array('VCHAR_UNI:255', ''),
						'item_updated_by' => array('VCHAR_UNI:255', ''),
            			'item_group_key'  => array('VCHAR', ''),
            
                    ),
                    'PRIMARY_KEY'     => 'item_id',
                    'KEYS'         => array('item_dkpid'    => array('INDEX', 'item_dkpid')),					
                 ),
            ),
            
            // new Guild table to prepare multiguild feature, also needed for Roster
            // realm, region is for wow
            // last two columns are for aion
            array($bbdkp_table_prefix . 'memberguild', array(
                    'COLUMNS'       => array(
                       'id'				=> array('USINT', 0), 
                       'name'			=> array('VCHAR_UNI:255', ''),
		  			   'realm'			=> array('VCHAR_UNI:255', ''),
					   'region'  		=> array('VCHAR:2', ''),
					   'roster'  		=> array('BOOL', 0), 
					   'aion_legion_id' => array('USINT', 0), 
            		   'aion_server_id' => array('USINT', 0),
            			 
                      ),
                    'PRIMARY_KEY'  	=> array('id', 'name'),
              ),
            ),

           array($bbdkp_table_prefix . 'member_ranks', array(
                    'COLUMNS'        => array(
            			//rank_id is not auto-increment 
                       'guild_id'		=> array('USINT', 0),
                        'rank_id'		=> array('USINT', 0),
                        'rank_name'		=> array('VCHAR_UNI:50', ''),
                        'rank_hide'		=> array('BOOL', 0),
                        'rank_prefix'	=> array('VCHAR:75', ''),
                        'rank_suffix'	=> array('VCHAR:75', ''),                      
                    ),
                    'PRIMARY_KEY'    => array('rank_id', 'guild_id'),
                ),
            ),

            array($bbdkp_table_prefix . 'memberlist', array(
                    'COLUMNS'        	   => array(
                        'member_id'        => array('UINT', NULL, 'auto_increment'),
                        'member_name'      => array('VCHAR_UNI:255', ''),
                        'member_status'    => array('BOOL', 0) ,
						'member_level'     => array('USINT', 0),
						'member_race_id'   => array('USINT', 0),
						'member_class_id'  => array('USINT', 0),
						'member_rank_id'   => array('USINT', 0),
						'member_comment'   => array('VCHAR_UNI:255', ''),
						'member_joindate'  => array('TIMESTAMP', 0),
            			'member_outdate'   => array('TIMESTAMP', 0),
            			'member_guild_id'  => array('USINT', 0),
            			'member_gender_id' => array('USINT', 0),
            			'member_achiev'    => array('UINT', 0),
            			'member_armory_url' => array('VCHAR:500', 0),
            
                    ),
                    'PRIMARY_KEY'  => 'member_id',
                    'KEYS'         => array('member_name'    => array('UNIQUE', 'member_name')),
                ),
            ),

		  array($bbdkp_table_prefix . 'memberdkp', array(
                    'COLUMNS'        	 => array(
                        'member_dkpid'		=> array('USINT', 0),
                        'member_id'			=> array('UINT', 0),
                        'member_earned'		=> array('DECIMAL:11', 0),
						'member_spent'		=> array('DECIMAL:11', 0),
						'member_adjustment' => array('DECIMAL:11', 0),
						'member_status' 	=> array('BOOL', 0) ,
						'member_firstraid'  => array('TIMESTAMP', 0),
						'member_lastraid'	=> array('TIMESTAMP', 0),
						'member_raidcount'	=> array('UINT', 0),
            
                    ),
                    'PRIMARY_KEY'  => array('member_dkpid', 'member_id'),
                ),
            ),
            
		  array($bbdkp_table_prefix . 'news', array(
                    'COLUMNS'				=> array(
                        'news_id'			=> array('UINT', NULL, 'auto_increment'),
                        'news_headline'		=> array('VCHAR_UNI', ''),
                        'news_message'		=> array('TEXT_UNI', ''),
                        'news_date'			=> array('TIMESTAMP', 0),
                        'user_id'			=> array('UINT', 0),
                        'bbcode_bitfield'	=> array('VCHAR:20', ''),
                        'bbcode_uid'		=> array('VCHAR:8', ''),
                        'bbcode_options'	=> array('VCHAR:8', ''),	  		  		  
                    ),
                    'PRIMARY_KEY'    => 'news_id',
                ),
            ),
            
            
		  array($bbdkp_table_prefix . 'races', array(
                    'COLUMNS'				=> array(
                        'race_id'			=> array('USINT', 0),
                        'race_name'			=> array('VCHAR_UNI:50', ''),
                        'race_faction_id'	=> array('USINT', 0),
                        'race_hide'			=> array('BOOL', 0),
                    ),
                    'PRIMARY_KEY'    => 'race_id',
                ),
            ),
                        
		  array($bbdkp_table_prefix . 'raid_attendees', array(
                    'COLUMNS'		=> array(
                        'raid_id'		=> array('UINT', 0),
                        'member_id'		=> array('UINT', 0),
                        'member_name'	=> array('VCHAR_UNI:255', ''),
                    ),
                    'PRIMARY_KEY'  => array('raid_id', 'member_id')
                ),
            ),
            
		  array($bbdkp_table_prefix . 'raids', array(
				'COLUMNS'        	=> array(
					'raid_dkpid'		=> array('USINT', 0),
					'raid_id'			=> array('UINT', NULL, 'auto_increment'),
					'raid_name'  		=> array('VCHAR_UNI:255', ''),
					'raid_note'   		=> array('VCHAR_UNI:255', ''),
					'raid_date'  		=> array('TIMESTAMP', 0) ,
					'raid_value' 		=> array('DECIMAL:11', 0),
					'raid_added_by' 	=> array('VCHAR_UNI:255', ''),
					'raid_updated_by' 	=> array('VCHAR_UNI:255', ''),
					),
				'PRIMARY_KEY'  => array('raid_id'),
				'KEYS'         => array('raid_dkpid'    => array('INDEX', 'raid_dkpid'),
                ),
            ),
          ),
           
           array($bbdkp_table_prefix . 'logs', array(
                    'COLUMNS'           => array(
                       'log_id'        => array('UINT', NULL, 'auto_increment'),
                       'log_date'      => array('TIMESTAMP', 0),
                       'log_type'      => array('VCHAR_UNI:255', ''),
					   'log_action'    => array('TEXT_UNI', ''),
					   'log_ipaddress' => array('VCHAR:15', ''),
					   'log_sid'       => array('VCHAR:32', ''),
					   'log_result'    => array('VCHAR', ''),
					   'log_userid'    => array('UINT', 0),
            
                    ),
                    'PRIMARY_KEY'  => 'log_id',
                    'KEYS'         => array(
		               'log_userid'	=> array('INDEX', 'log_userid'),
		               'log_type'		=> array('INDEX', 'log_type'),
		               'log_ipaddress'	=> array('INDEX', 'log_ipaddress'),
                    )
                ),
            ),
            
		  array($bbdkp_table_prefix . 'plugins', array(
                    'COLUMNS'        	=> array(
                        'name'			=> array('VCHAR_UNI:255', ''),
                        'value'			=> array('BOOL', 0),
                        'version'  		=> array('VCHAR:50', ''),
                        'orginal_copyright' => array('VCHAR_UNI:150', ''),
                        'bbdkp_copyright'  	=> array('VCHAR_UNI:150', ''),
                    )
                ),
            ),


       ),
       
       'table_row_insert'	=> array(

       // we insert a dummy guild (None) for guildless people and also the default guild
         array($bbdkp_table_prefix .'memberguild',
           array(
           
           		  // guildless -> do show on rester
                  array('id'  => 0,
                      'name' => '(None)',
                      'realm' => utf8_normalize_nfc(request_var('realm', '', true)),
                      'region' => request_var('region', ''), 
                      'roster' => 1
                  		),
                  
           		  // default guild -> do show on rester                  
                  array('id'  => 1,
                      'name' => ( request_var('guildtag', ' ')== ' ' ? utf8_normalize_nfc(request_var('guildtag', ' ', true)) : 'default'), 
                      'realm' => ( request_var('realm', ' ', true) == ' ' ? utf8_normalize_nfc(request_var('realm', ' ', true)) : 'default'),  
                      'region' => (isset($_POST['region']) ? request_var('region', ' ') : 'EU'), 
                  	  'roster' => 1 ),
                  )
              
           ),
			
		 array($bbdkp_table_prefix . 'member_ranks', 
			 array(
	       		array(
	       			'guild_id'	=> 1,	
					'rank_id'	=> 0,
					'rank_name'	=> 'Member',
					'rank_hide'	=> 0,
					'rank_prefix'	=> '',
					'rank_suffix'	=> '',
				 ),
				 
				// dont hide the Out rank by default
	       		array(
	       			'guild_id'	=> 0,	
					'rank_id'	=> 99,
					'rank_name'	=> 'Out',
					'rank_hide'	=> 1,
					'rank_prefix'	=> '',
					'rank_suffix'	=> '',
				 ),
				)
			)
		),
       
    	// two basic permissions
	   'permission_add' => array(
            array('a_dkp', true),
            array('a_dkp_no', true),         
      	),
      
        // Assign default permissions to Full admin
        'permission_set' => array(
            // Global Role permissions give to the role "Full admin"
            array('ROLE_ADMIN_FULL', 'a_dkp'),
            array('ROLE_ADMIN_FULL', 'a_dkp_no'),
        ),
        
        // add new parameters
        'config_add' => array(

        	//global config
	        array('bbdkp_active_point_adj', '0.00', true),
			array('bbdkp_date_format', 'd.m.y', true),
			array('bbdkp_default_game', request_var('game', ''), true),
			array('bbdkp_dkp_name', 'DKP', true),
			array('bbdkp_eqdkp_start', mktime(0, 0, 0, date("m")  , date("d"), date("Y")) , true),
			array('bbdkp_guildtag', utf8_normalize_nfc(request_var('guildtag', '', true)), true),
			array('bbdkp_hide_inactive', '0', true),
			array('bbdkp_inactive_period', '14', true),
			array('bbdkp_inactive_point_adj', '0.00', true),
			array('bbdkp_list_p1', '30', true),
			array('bbdkp_list_p2', '90', true),
			array('bbdkp_user_alimit', '30', true),
			array('bbdkp_user_elimit', '30', true),
			array('bbdkp_user_ilimit', '20', true),
			array('bbdkp_user_llimit', '20', true),
			array('bbdkp_user_nlimit', '5', true),
			array('bbdkp_user_rlimit', '20', true),
        
	        // guildfaction : limit the possible races to be available to users to those available in the guild's chosen faction
	         array('bbdkp_guild_faction', '1', true),
	        // roster layout: main parameter for steering roster layout 
	         array('bbdkp_roster_layout', '1', true),
	        // showachiev : show the achievement points
	         array('bbdkp_show_achiev', '0', true),
	        // list_p3 : third standings option
	         array('bbdkp_list_p3', '0', true),    
	        // default realm & region
	         array('bbdkp_default_realm', ( request_var('realm', ' ', true) == ' ' ? utf8_normalize_nfc(request_var('realm', ' ', true)) : 'default') , true),  
	         array('bbdkp_default_region', request_var('region', ''), true),  
	
	        // new portal configuragion
	        // number of news
	         array('bbdkp_n_news', 5, true),   
	        // news forum id
	         array('bbdkp_news_forumid', 2 , true),   
	        // number of items
	         array('bbdkp_n_items',5 , true),   
	        // recruitment forum id
	         array('bbdkp_recruit_forumid', 3, true),
			// 1 if open,  if closed             
	         array('bbdkp_recruitment', 0, true ), 
			// show loot block          
	         array('bbdkp_portal_loot', 1, true ), 
			// show bossprogress block
	         array('bbdkp_portal_bossprogress', 1, true ), 
			// show recruitment block          
	         array('bbdkp_portal_recruitment', 1, true ), 
			// show link block          
	         array('bbdkp_portal_links', 1, true ), 
		    // show post edits in portal          
	         array('bbdkp_portal_showedits', 1, true ),
	         
          ),

          
        // add the bbdkp modules to ACP using the info files, 
        // old 1.09 modules must already be removed !
		'module_add' => array(
      		 /*
             * First, lets add maincategory named ACP_DKP to the root.
             * note to validation team : phpbb MOD policy wants this in 
             * ACP_CAT_DOT_MODS but due to the size of our tree we place it on top (0)
             * 
             */ 
            array('acp', 0, 'ACP_CAT_DKP'),
            
             /*
             * add main menu
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MAINPAGE'),
            array('acp', 'ACP_DKP_MAINPAGE', array(
           		 'module_basename' => 'dkp',
            	 'modes'           => array('mainpage', 'dkp_config', 'dkp_logs', 'dkp_indexpageconfig') ,
        		),

            ),

            /*
             * add news
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_NEWS'),
            array('acp', 'ACP_DKP_NEWS', array(
           		 'module_basename' => 'dkp_news',
            	 'modes'           => array('addnews', 'listnews'),
        		),

            ),
            
             /*
             * add member management menu
             * note added the roster here
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MEMBER'),
            
            // add memberlist-add-ranks-roster
            array('acp', 'ACP_DKP_MEMBER', array(
           		 'module_basename' => 'dkp_mm',
            	 'modes'           => array('mm_addguild', 'mm_listguilds', 'mm_addmember', 'mm_listmembers', 'mm_ranks'),
        		),
            ),          
            
            /*
             * add raid management menu
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_RAIDS'),
            
            // add raid pools
            array('acp', 'ACP_DKP_RAIDS', array(
           		 'module_basename' => 'dkp_sys',
            	 'modes'           => array('adddkpsys', 'listdkpsys'),
        		),
            ),
            
            // add events modules
            array('acp', 'ACP_DKP_RAIDS', array(
           		 'module_basename' => 'dkp_event',
            	 'modes'           => array('addevent', 'listevents'),
        		),
            ),            
            
            // add manual raid modules
            array('acp', 'ACP_DKP_RAIDS', array(
           		 'module_basename' => 'dkp_raid',
            	 'modes'           => array('addraid', 'listraids'),
        		),
            ),            
            
            /*
             * add item management menu
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_ITEM'),
            
            // add raid pools
            array('acp', 'ACP_DKP_ITEM', array(
           		 'module_basename' => 'dkp_item',
            	 'modes'           => array('additem', 'listitems', 'search', 'viewitem'),
        		),
            ),
            
            /*
             * add member dkp menu
             * note the transfer moved to member dkp
             */  
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MDKP'),
                        
            // add dkp - edit dkp - transfer dkp
            array('acp', 'ACP_DKP_MDKP', array(
           		 'module_basename' => 'dkp_mdkp',
            	 'modes'           => array('mm_listmemberdkp', 'mm_editmemberdkp', 'mm_transfer'),
        		),
            ),        

            // add dkp adjustments
            array('acp', 'ACP_DKP_MDKP', array(
           		 'module_basename' => 'dkp_adj',
            	 'modes'           => array('addiadj', 'listiadj'),
        		),
            ),        
            
            /*
             * bossprogress menu
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_BOSS'),
                        
            // add memberlist-add-ranks-transfers
            array('acp', 'ACP_DKP_BOSS', array(
           		 'module_basename' => 'dkp_bossprogress',
            	 'modes'           => array('bossprogress', 'bossbase', 'bossbase_offset'),
        		),
            ),        
            
        ),

        'custom' => array( 
            'bbdkp_game_data',
            'upd110dkplink', 
            'bbdkp_caches',
            'bbdkp_restore'
       ), 
    ),
    
     '1.1.0-RC2'    => array(
		// db change consolidated in RC1 script
		),

     '1.1.0-RC3'    => array(
        
        // guildname fieldsize increased to max 255 chars - see ticket 3
		'table_column_update' => array(
            array($bbdkp_table_prefix . 'memberguild' , 'name', array('VCHAR_UNI', '')), 
            ), 
            
        'custom' => array( 
            'custom110RC3', 
       	), 
       		
        ),        
     
       '1.1.0'    => array(
        'table_column_update' => array(
            array($bbdkp_table_prefix . 'adjustments' , 'adjustment_id', array('UINT', NULL, 'auto_increment')), 
			array($bbdkp_table_prefix . 'dkpsystem' , 'dkpsys_updatedby', array('VCHAR_UNI:255', '')),
            array($bbdkp_table_prefix . 'items' , 'item_id',array('UINT', NULL, 'auto_increment')),
            array($bbdkp_table_prefix . 'items' , 'raid_id', array('UINT', 0)), 
            array($bbdkp_table_prefix . 'memberguild' , 'id', array('USINT', 0)), 
            array($bbdkp_table_prefix . 'memberguild' , 'realm', array('VCHAR_UNI:255', '')), 
            array($bbdkp_table_prefix . 'member_ranks' , 'rank_hide', array('BOOL', 0)),
            array($bbdkp_table_prefix . 'memberlist' , 'member_id', array('UINT', NULL, 'auto_increment')), 
            array($bbdkp_table_prefix . 'memberdkp' , 'member_id', array('UINT', 0)),
            array($bbdkp_table_prefix . 'memberdkp' , 'member_raidcount', array('UINT', 0)),            
            array($bbdkp_table_prefix . 'raid_attendees' , 'raid_id', array('UINT', 0)),            
            array($bbdkp_table_prefix . 'raid_attendees' , 'member_id', array('UINT', 0)),
            array($bbdkp_table_prefix . 'raid_attendees' , 'member_name',  array('VCHAR_UNI:255', '')),            
            array($bbdkp_table_prefix . 'raids' , 'raid_id', array('UINT', NULL, 'auto_increment'),
            ), 
		
		), 
		
		
		'table_column_add' => array(
			array($bbdkp_table_prefix . 'items', 'item_gameid' , array('UINT', 0)) 
		),
		
		
		
		
		),
		    
        
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

function bbdkp_game_data($action, $version)
{
	global $db, $table_prefix, $umil, $bbdkp_table_prefix, $phpbb_root_path, $phpEx, $backup;
	switch ($action)
	{
		case 'install' :
		case 'update' :
			// Run this when installing/updating
			// Run this when updating
     	    
			$game = request_var('game', '');
			switch ($game)
			{
				case 'wow':
       				install_wow($bbdkp_table_prefix);
       				// update bossprogress for trial of champion
       				install_wow2($bbdkp_table_prefix);
       				// update bossprogress onyxia
       				install_wow3($bbdkp_table_prefix);
       				// update 3.3
       				install_wow4($bbdkp_table_prefix);
       				// update the class id of members
       				upd110_classid($bbdkp_table_prefix);
       				
       				return array('command' => 'UMIL_INSERT_WOWDATA', 'result' => 'SUCCESS');
					break;
				case 'aion':
       				install_aion($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_AIONDATA', 'result' => 'SUCCESS');
					break;
		    	case 'daoc':
       				install_daoc($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_DAOCDATA', 'result' => 'SUCCESS');
					break; 
				case 'FFXI':
       				install_ffxi($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_FFXIDATA', 'result' => 'SUCCESS');
					break; 
				case 'vanguard':
       				install_vanguard($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_VANGUARDDATA', 'result' => 'SUCCESS');
					break; 
				case 'warhammer':
       				install_warhammer($bbdkp_table_prefix);
       				install_warhammer_rc2($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_WARDATA', 'result' => 'SUCCESS');
					break; 
				case 'eq':
       				install_eq($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_EQDATA', 'result' => 'SUCCESS');
					break; 
				case 'eq2':
       				install_eq2($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_EQ2DATA', 'result' => 'SUCCESS');
					break; 
				case 'lotro':
       				install_lotro($bbdkp_table_prefix);
       				return array('command' => 'UMIL_INSERT_LOTRODATA', 'result' => 'SUCCESS');
					break; 
				default :
				    break; 
			}
			$db->sql_query("update " .  $bbdkp_table_prefix . "classes set dps = 0 "); 
		    $db->sql_query("update " .  $bbdkp_table_prefix . "classes set tank = 0 "); 
     	    $db->sql_query("update " .  $bbdkp_table_prefix . "classes set heal = 0 ");   
			
			
			break; 
			
			
		case 'uninstall' :
			// Run this when uninstalling
			if ($umil->table_exists($bbdkp_table_prefix . 'classes'))
			{
				$sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes ';
				$db->sql_query($sql);
			}
			
			if ($umil->table_exists($bbdkp_table_prefix . 'races'))
			{
				$sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races ';
				$db->sql_query($sql);
			}
			
			if ($umil->table_exists($bbdkp_table_prefix . 'factions'))
			{
				$sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions ';
				$db->sql_query($sql);
			}
			
			if ($umil->table_exists($bbdkp_table_prefix . 'classes'))
			{
				$sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes ';
				$db->sql_query($sql);
			}
			return array(
					'command' => 'UMIL_REMOVE_GAME_ROW', 
					'result' => 'SUCCESS');
			break;
	
	}
}

/***************************************
 *
 * adds config value with 1.1 dkp module Id
 */
function upd110dkplink($action, $version)
{
    global $db, $table_prefix, $umil, $phpbb_root_path, $phpEx;
	switch ($action)
	{
		// lookup first node module id 
		case 'install' :
		case 'update' :
		  $sql = 'SELECT module_id FROM ' . $table_prefix . "modules WHERE module_langname = 'ACP_CAT_DKP'";
          $result = $db->sql_query($sql);
          $modid = (int) $db->sql_fetchfield('module_id'); 
          if ($umil->config_exists('bbdkp_module_id'))
          {
              $umil->config_update('bbdkp_module_id', $modid, false);
          }
          else 
          {
              $umil->config_add('bbdkp_module_id', $modid, false);
          }
          $db->sql_freeresult($result);
          return array('command' => 'UMIL_INSERT_DKPLINK', 'result' => 'SUCCESS');
	      break;
		case 'uninstall' :
    	    if ($umil->config_exists('bbdkp_module_id'))
            {
                  $umil->config_remove('bbdkp_module_id');
            }
			return array('command' => 'UMIL_REMOVE_DKPLINK', 'result' => 'SUCCESS');
		  break; 		  
	}
}


function custom110RC3($action, $version)
{
    global $db, $umil, $bbdkp_table_prefix;
	
    switch ($action)
	{
	   case 'install' :
	   case 'update' :
		     // logging max 255 chars - see ticket 3
		     if  ($umil->table_exists($bbdkp_table_prefix . 'logs') == true)
		     {
		        $db->sql_query('update ' . $bbdkp_table_prefix . "logs set log_type = replace(log_type,'{','')");
		        $db->sql_query('update ' . $bbdkp_table_prefix . "logs set log_type = replace(log_type,'}','')");
		        $db->sql_query('update ' . $bbdkp_table_prefix . "logs set log_action = replace(log_action,'{','')");
		        $db->sql_query('update ' . $bbdkp_table_prefix . "logs set log_action = replace(log_action,'}','')"); 
		     }
		     
          return array(
          	'command' => 'UMIL_LOGCLEANED', 
          	'result' => 'SUCCESS');
	      break;
	   case 'uninstall' :
	      break;
	      // do nothing since were just deleting table in automatic mode
	}
}

/****************************
 *  
 * global function for rendering pulldown menu
 * 
 */
function gameoptions($selected_value, $key)
{
	global $user;

    /* game pulldown menu rendering */
    $gametypes = array(
        'aion'			=> "Aion: Tower of Eternity",
    	'daoc'     		=> "Dark Age of Camelot",
    	'eq'     		=> "EverQuest",
    	'eq2'     		=> "EverQuest II",
    	'FFXI'     		=> "Final Fantasy XI",
    	'lotro'     	=> "The Lord of the Rings Online",
    	'vanguard'		=> "Vanguard - Saga of Heroes",
    	'warhammer'     => "Warhammer Online", 
    	'wow'     		=> "World of Warcraft", 
    	 
    );
    $default = 'wow'; 
	$pass_char_options = '';
	foreach ($gametypes as $key => $game)
	{
		$selected = ($selected_value == $default) ? ' selected="selected"' : '';
		$pass_char_options .= '<option value="' . $key . '"' . $selected . '>' . $game . '</option>';
	}

	return $pass_char_options;
}



/**************************************
 *  
 * function for rendering region list
 * 
 */
function regionoptions($selected_value, $key)
{
	global $user;

    $regions = array(
    	'EU'     			=> "European region", 
    	'US'     			=> "US region",     	 
    );
    
    $default = 'US'; 
	$pass_char_options = '';
	foreach ($regions as $key => $region)
	{
		$selected = ($selected_value == $default) ? ' selected="selected"' : '';
		$pass_char_options .= '<option value="' . $key . '"' . $selected . '>' . $region . '</option>';
	}

	return $pass_char_options;
}


/**************************************
 *  
 * global function for clearing cache
 * 
 */
function bbdkp_caches($action, $version)
{
    global $db, $table_prefix, $umil, $bbdkp_table_prefix;
    
    $umil->cache_purge();
    $umil->cache_purge('imageset');
    $umil->cache_purge('template');
    $umil->cache_purge('theme');
    $umil->cache_purge('auth');
    
    return 'UMIL_CACHECLEARED';
}



/******************************
 * 
 *  uninstaller for 1.09rc1
 * 
 */
function bbdkp_cleanupold($action, $version)
{
	global $user, $config, $db, $table_prefix, $umil, $bbdkp_table_prefix;

	switch ($action)
	{
		case 'install' :
		case 'update' :
			
			// check for oldstyle acp
			if ($umil->module_exists('acp', false, 'DKP'))
    		{
    			  bbdkp_109_uninstall();
    			  return array('command' => sprintf($user->lang['UMIL_109_UNINSTALL_SUCCESS'], $config), 'result' => 'SUCCESS');
		    }
		    else
		    {
		        //don't bother to try to delete child modules if DKP category doesnt exist 
		        // this means user is at 1.1.0-RC
		        return array('command' => 'UMIL_109_RESTORE_NOT', 'result' => 'SUCCESS');
		    }
			break;
	}
    
}

/****************************** 
 * wrapperfunction for uninstalling bbdkp 1.0.9rc1
 * the strings are hardcoded because 1.09 had no language info files (ouch) 
 * 
 * makes backup of old data 
 * 
 */
function bbdkp_109_uninstall()
{
    global $db, $table_prefix, $umil, $bbdkp_table_prefix, $backup, $version, $config;
    
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
function bbdkp_restore($action, $version)
{
	global $db, $table_prefix, $umil, $bbdkp_table_prefix, $backup;

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
	   return array('command' => 'UMIL_109_RESTORE_SUCCESS', 'result' => 'SUCCESS');
	}
	else 
	{
		// no restore performed
		return array('command' => 'UMIL_109_RESTORE_NOT', 'result' => 'SUCCESS');
	}
    
}


?>