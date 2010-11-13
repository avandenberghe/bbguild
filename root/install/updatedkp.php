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

if (!file_exists($phpbb_root_path . 'install/updatedkp.' . $phpEx))
{
    trigger_error('Warning! Install directory has wrong name. it must be \'install\'. Please rename it and launch again.', E_USER_WARNING);
}

// The name of the mod to be displayed during installation.
$mod_name = 'bbDKP 1.1.3';

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
$logo_img = 'install/logo.jpg';

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
$bbdkpold = '';

// include required sub installers
$game = request_var('game', '');
switch ($game)
{
		case 'aion':
			include($phpbb_root_path .'install/games/install_aion.' . $phpEx);
			break;
    	case 'daoc':
			include($phpbb_root_path .'install/games/install_daoc.' . $phpEx);
			break; 
		case 'eq':
			include($phpbb_root_path .'install/games/install_eq.' . $phpEx);
			break; 
		case 'eq2':
			include($phpbb_root_path .'install/games/install_eq2.' . $phpEx);
			break; 
		case 'FFXI':
			include($phpbb_root_path .'install/games/install_ffxi.' . $phpEx);
			break; 
		case 'lotro':
			include($phpbb_root_path .'install/games/install_lotro.' . $phpEx);
			break;
		case 'vanguard':
			include($phpbb_root_path .'install/games/install_vanguard.' . $phpEx);
			break; 
		case 'warhammer':
			include($phpbb_root_path .'install/games/install_warhammer.' . $phpEx);
			break; 
		case 'wow':				    
			include($phpbb_root_path .'install/games/install_wow.' . $phpEx);
			break;
		default :
			break; 
}

$versions = array(

	//not released version
	'1.1.0-B1'    => array(
       'custom' => array( 
            // removing old data (if pre 1.1.0 bbdkp was installed before) 
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
            			'member_armory_url' => array('VCHAR:255', 0),
            
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
            'gameupdate',
            'upd110dkplink', 
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
	            'gameupdate', 
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
		    
		
	'1.1.1'  => array(
	
       	//new config for hiding the portal menublock
		 'config_add' => array(
		      array('bbdkp_portal_menu', 1, true),
	        ), 
		
		
	    //  remove a_dkp_no permission
	   'permission_remove' => array(array('a_dkp_no'),         
      	),
      	
       // basic user permissions
	   'permission_add' => array( array('u_dkp', true),         
      	),
      	
      
        // Assign default permissions to Full admin
        'permission_set' => array(
            // Global Role permissions give to the role "Full admin"
            array('ROLE_ADMIN_FULL', 'u_dkp'),
            array('ROLE_USER_STANDARD', 'u_dkp'),
        ),
        
		'custom' => array( 
	        'gameupdate', 
		     // restore old data
            'bbdkp_restoreold', 
       		),
       	
		), 
	/// end 1.1.1
		
	/// begin 1.1.2
	'1.1.2'  => array(
		
  		'config_add' => array(
        	//global config
        	
        	//Hide zones with no boss kills
        	array('bbdkp_bp_hidenewzone', 0, true),
        	
        	//Hide never killed bosses?
        	array('bbdkp_bp_hidenonkilled', 0, true),
        	
        	//header image style : sepia, photo, blue
	        array('bbdkp_bp_zonephoto', 0, true),
	        
	        //show zoneprogressionbar
	        array('bbdkp_bp_zoneprogress', 1, true),
	        
	        //bp style : 2 row, 3 row, simple, photo
	        array('bbdkp_bp_zonestyle', 0, true),
	        
	        //default dkp language
	        array('bbdkp_lang', 'en', true),
	        
	        //show 3darmory in viewmember
	        array('bbdkp_show_3darmory', 1, true),
	     ),
					
		'table_add' => array(
		        array($bbdkp_table_prefix . 'bb_zonetable', array(
		              'COLUMNS'            => array(
		                  'id'     	       => array('UINT', NULL, 'auto_increment'), 
		        		  'imagename'      => array('VCHAR_UNI:255', ''),
						  'game'           => array('VCHAR:10', ''),
						  'tier'           => array('VCHAR:30', ''),
						  'completed'      => array('BOOL', 0),
						  'completedate'   => array('TIMESTAMP', 0), 
						  'webid'          => array('VCHAR:255', ''),
		        		  'showzone'	   => array('BOOL', 0),
		        		  'showzoneportal' => array('BOOL', 0), 
		        		  'sequence'	   => array('UINT', 0),
		                ),
		                'PRIMARY_KEY'      => 'id',
		            ),
		          ),
		          
		          array($bbdkp_table_prefix . 'bb_bosstable', array(
		              'COLUMNS'            => array(
		                  'id'     	       => array('UINT', NULL, 'auto_increment'), 
		        		  'imagename'      => array('VCHAR_UNI:255', ''),
		                  'game'           => array('VCHAR:10', ''),
						  'zoneid'         => array('UINT', 0), 
						  'type'           => array('VCHAR:10', ''),
						  'webid'          => array('VCHAR:255', ''),
						  'killed'         => array('BOOL', 0),
						  'killdate'   	   => array('TIMESTAMP', 0), 
						  'counter'        => array('UINT', 0),
		            	  'showboss'	   => array('BOOL', 0), 
		          	),
		                'PRIMARY_KEY'      => 'id',
		          		'KEYS'            => array('zoneid'    => array('INDEX', 'zoneid')),
		            ),
		          ),        
		          
		          array($bbdkp_table_prefix . 'bb_language', array(
		              'COLUMNS'            => array(
		          		  'id'     	       => array('UINT', NULL, 'auto_increment'), 
		                  'attribute_id'   => array('UINT', 0), 
		                  'language'       => array('CHAR:2', ''),
		          		  'attribute'	   => array('VCHAR:30', ''), 
		                  'name'       	   => array('VCHAR_UNI:255', ''), 
		                  'name_short' 	   => array('VCHAR_UNI:255', ''),
		          	),
		                'PRIMARY_KEY'     => array('id'),
		          		'KEYS'            => array('unq_mtch' => array('UNIQUE', array('attribute_id', 'language', 'attribute')),
					),
		            ),
		          ),   
		        
	            array($bbdkp_table_prefix . 'lootsystem', array(
	                    'COLUMNS'        => array(
	                        'lootsys_id'    		=> array('USINT', NULL, 'auto_increment'),
	                        'lootsys_name'   		=> array('VCHAR_UNI:255', ''),
	            			'lootsys_description'  	=> array('MTEXT_UNI', ''),
	                        'lootsys_status'    	=> array('VCHAR:2', 'Y'),
	                        'lootsys_addedby'		=> array('VCHAR_UNI:255', ''),
	                        'lootsys_updatedby'		=> array('VCHAR_UNI:255', ''),
	                        'lootsys_default'		=> array('VCHAR:2', 'N'),
	                    ),
	                    'PRIMARY_KEY'    => 'lootsys_id',
	                ),
	            ),
	            
	            array($bbdkp_table_prefix . 'transactions', array(
	                    'COLUMNS'        => array(
	                        'id'    		=> array('USINT', NULL, 'auto_increment'),
	                        'account'   		=> array('VCHAR:11', ''),
	            			'member_id'			=> array('UINT', 0),
	            			'raid_id'			=> array('UINT', 0),
	            			'dkp_id'			=> array('UINT', 0),
	            			'game'  			=> array('VCHAR:10', ''),
	                        'user'    			=> array('VCHAR:255', 'Y'),
	                        'value'				=> array('DECIMAL:11', 0),
	            			'time'				=> array('TIMESTAMP', 0),
	                    ),
	                    'PRIMARY_KEY'    => 'id',
	                ),
	            ),
	            
          	),
          	
            

          // adding image, class color code column to class table
          // the class css will dissapear since all is now dynamic
         'table_column_add' => array(
            	array($bbdkp_table_prefix .'classes', 'imagename',  array('VCHAR:255', 0)),
            	array($bbdkp_table_prefix .'classes', 'colorcode',  array('VCHAR:10', '')),
            ),
            
        // removing old table
        'table_remove' => array( $bbdkp_table_prefix . 'bb_config', $bbdkp_table_prefix . 'bb_offsets'), 

		'custom' => array( 
			'gameupdate', 
			'bbdkp_caches'
			),
			
		 // removing class_name, race_name column
         'table_column_remove' => array(
            	array($bbdkp_table_prefix .'races', 'race_name' ), 
            	array($bbdkp_table_prefix .'classes', 'class_name'),
            ),

          // module adding - do this last
         'module_add' => array(
		 array('acp', 'ACP_DKP_BOSS', array(
          		 'module_basename' => 'dkp_bossprogress',
            	 'modes'           => array('zoneprogress' ),
         		)),
          array('acp', 'ACP_DKP_MEMBER', array(
           		 'module_basename' => 'dkp_game',
            	 'modes'           => array('listgames', 'addfaction', 'addrace', 'addclass'),
        		)),        
         ),
                     
            
		),
		
		'1.1.2.1'    => array(
			'custom' => array( 
				'gameupdate', 
				'bbdkp_caches'
			),
		),
		
		'1.1.2.2'    => array(

  		'config_add' => array(
        	//show progressbar on block
        	array('bbdkp_bp_blockshowprogressbar', 1, true),
	     ),
	     
		),

		'1.1.3'    => array(

			'custom' => array( 
				'tablerename', 
				'bbdkp_caches'
			),
		
		),
		
		
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

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
    global $umil;
    
    $umil->cache_purge();
    $umil->cache_purge('imageset');
    $umil->cache_purge('template');
    $umil->cache_purge('theme');
    $umil->cache_purge('auth');
    
    return 'UMIL_CACHECLEARED';
}

/******************************
 * 
 *  pre-1.1.0 uninstaller and backup
 * 
 */
function bbdkp_cleanupold($action, $version)
{
	global $user, $config, $db, $table_prefix, $umil, $phpbb_root_path, $bbdkp_table_prefix, $phpEx, $bbdkpold;

	switch ($action)
	{
		case 'install' :
		case 'update' :
			
			// check for oldstyle acp
			if ($umil->module_exists('acp', false, 'DKP'))
    		{
    		
    			//we have found an old dkp tab, now check for version
    			if($umil->config_exists('bbdkp_version', true))
				{
					// insure against cleared config array 
					$sql = 'select config_value from ' . CONFIG_TABLE . " WHERE config_name = 'bbdkp_version' ";
					$result = $db->sql_query($sql);
					$bbdkpold = $db->sql_fetchfield('config_value', 0, $result); 
					$db->sql_freeresult($result);
					
					switch ($bbdkpold)
					{
						case '1.0.9b4' :
							trigger_error('UMIL_109_ILLEGALVERSION', E_USER_WARNING); 
							break;
						case '1.0.9rc1':
							include($phpbb_root_path .'install/update109rc1.' . $phpEx);
			    			bbdkp_109rc1_uninstall();
			    			return array('command' => sprintf($user->lang['UMIL_OLD_UNINSTALL_SUCCESS'], $bbdkpold), 'result' => 'SUCCESS');
							break;
						default:
							//1.0.9 betas not upgradable to 1.1, you have to update to 1.09rc1
							trigger_error('UMIL_109_ILLEGALVERSION', E_USER_WARNING); 
							break;
					}
				}
				else 
				{
					// legacy versions updater
					// no config entry before 1.0.9rc1, so look in old config table
					if(!defined('OLD_CONFIG_TABLE'))
					{
					    define('OLD_CONFIG_TABLE',  'bbeqdkp_config');
					}

					//get game
					$sql = 'SELECT config_value FROM ' . OLD_CONFIG_TABLE . " where config_name = 'bbdkp_default_game' " ;
					$result = @$db->sql_query($sql);
					$game = @$db->sql_fetchrow( $result );
					$game = strtolower($game['config_value']);
					
					//get version
					$sql = 'SELECT config_value FROM ' . OLD_CONFIG_TABLE . " where config_name = 'bbdkp_version' " ;
					$result = @$db->sql_query($sql);
					$row = @$db->sql_fetchrow( $result );
					
					//very early versions had no version number
					$bbdkpold = isset($row['config_value']) ? strtolower($row['config_value']) : '1.0.8';
	
					//include updater
					include($phpbb_root_path .'install/update108.' . $phpEx);	
					bbdkp_old_uninstall($bbdkpold); 
					return array('command' => sprintf($user->lang['UMIL_OLD_UNINSTALL_SUCCESS'], $bbdkpold), 'result' => 'SUCCESS');
				}
    			
		    }
		    else
		    {
		        //don't bother to try to delete child modules if DKP category doesnt exist 
		        // this means user is at 1.1.0-RC or new install
		        // we will just follow the umil procedure
			    return array('command' => sprintf($user->lang['UMIL_OLD_RESTORE_NOT'], $bbdkpold), 'result' => 'SUCCESS');
		    }
			break;
	}
    
}


/******************************
 * 
 *  pre-1.1.0 restore 
 * 
 */
function bbdkp_restoreold($action, $version)
{
	global $user, $config, $db, $table_prefix, $umil, $bbdkp_table_prefix, $bbdkpold;

	switch ($action)
	{
		case 'install' :
		case 'update' :
			switch ($bbdkpold)
			{
	            case '1.0.8': 
		        case '1.0.8b3':  
		        case '1.0.8b4':  
		        case '1.0.8b5':
		        	bbdkp_restore108($bbdkpold);
		        	return array('command' => sprintf($user->lang['UMIL_OLD_RESTORE_SUCCESS'], $bbdkpold), 'result' => 'SUCCESS');
					break;
				case '1.0.9rc1':
	    			bbdkp_restore109rc1($bbdkpold);
	    			return array('command' => sprintf($user->lang['UMIL_OLD_RESTORE_SUCCESS'], $bbdkpold), 'result' => 'SUCCESS');
					break;
				default:
					break;
			}
	}
    
}

/******************************
 * 
 *  gametable update calls 
 * 
 */
function gameupdate($action, $version)
{
	global $user, $config, $db, $table_prefix, $umil, $bbdkp_table_prefix; 
	switch ($action)
	{
		case 'install' :
		case 'update' :
		
			switch ($version)
			{
				case '1.1.0-RC1': 
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
					
				case '1.1.0-RC3':
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
				
				case '1.1.1' :
					$db->sql_query( " update " . MODULES_TABLE . " set module_auth = 'acl_a_dkp' where module_auth = 'acl_a_dkp_no' " );
					// Run this when installing/updating
					$game = request_var('game', '');
					switch ($game)
					{
						case 'lotro':
							 $db->sql_query('update ' . $bbdkp_table_prefix . "classes set class_max_level = 60	");
		       				return array('command' => 'UMIL_INSERT_LOTRODATA', 'result' => 'SUCCESS');
							break; 
						case 'wow': 
							install_wow5($bbdkp_table_prefix); 
							return array('command' => 'UMIL_INSERT_WOWDATA', 'result' => 'SUCCESS');
						default :
						    break; 
					}
					break;
	
				case '1.1.2' : 
					$game = request_var('game', '');
					
					
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_NEWS_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_GUILD_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_MEMBER_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_NEWS_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_POOL_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_EVENT_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_RAID_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_ITEM_ADD' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_ITEM_SEARCH' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_EDITMEMBERDKP' " );
					$db->sql_query( " update " . MODULES_TABLE . " set module_display = '0' where module_langname = 'ACP_DKP_ADDADJ' " );
					
					// updating class imagenames
					//would use concat() but it’s not cross db compatible (thnks Bill) so we need to loop the class table...
					$sql = 'select c_index, class_name, class_armor_type from ' . $bbdkp_table_prefix . 'classes'; 
					$result = $db->sql_query($sql);	
					while ( $row = $db->sql_fetchrow($result) )
					{	
						$sql = 'UPDATE ' . $bbdkp_table_prefix . "classes 
								SET imagename = '" . $game . "_" . $db->sql_escape($row['class_name']) . "_small',
									class_armor_type = '" .  strtoupper($db->sql_escape( $row['class_armor_type'] )) . "'
								WHERE c_index = " . (int) $row['c_index']; 
						$db->sql_query($sql); 
					}
					$db->sql_freeresult($result);
					
					
					/* bossprogress refit */ 
					/* remove 3 modes */
					if($umil->module_exists('acp', 'ACP_DKP_BOSS','ACP_DKP_BOSS_BOSSBASE'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_BOSSBASE');
					}
					if($umil->module_exists('acp', 'ACP_DKP_BOSS','ACP_DKP_BOSS_OFFSET'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_OFFSET');
					}
					if($umil->module_exists('acp', 'ACP_DKP_BOSS', 'ACP_DKP_BOSS_CONFIG'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_CONFIG');
					}

					// game updaters
					
					switch ($game)
					{
						case 'wow':
							/* update class color code for Wow. other games will have to do this in the acp.
							 * 1 warrior
							 * 2 paladin
							 * 3 hunter
							 * 4 Rogue
							 * 5 Priest
							 * 6 Death knight
							 * 7 Shaman
							 * 8 Mage
							 * 9 Warlock
							 * 10 
							 * 11 Druid
							 */

							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#C69B6D' where class_id = '1'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#F48CBA' where class_id = '2'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#AAD372' where class_id = '3'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#F48CBA' where class_id = '4'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#999' where class_id = '5'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#F14' where class_id = '6'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#214FDE' where class_id = '7'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#68CCEF' where class_id = '8'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#B0B' where class_id = '9'" ;
							$db->sql_query ($sql);
							$sql = 'update ' . $bbdkp_table_prefix . "classes set colorcode = '#FF7C0A' where class_id = '11'" ;
		       				$db->sql_query ($sql);
							
							install_wow_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  ); 
		       				
		       				return array('command' => 'UMIL_INSERT_WOWDATA', 'result' => 'SUCCESS');
							break;
						case 'aion':
		       				install_aion_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_AIONDATA', 'result' => 'SUCCESS');
							break;
				    	case 'daoc':
		       				install_daoc_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_DAOCDATA', 'result' => 'SUCCESS');
							break; 
						case 'FFXI':
		       				install_ffxi_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_FFXIDATA', 'result' => 'SUCCESS');
							break; 
						case 'vanguard':
		       				install_vanguard_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_VANGUARDDATA', 'result' => 'SUCCESS');
							break; 
						case 'warhammer':
		       				install_warhammer_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_WARDATA', 'result' => 'SUCCESS');
							break; 
						case 'eq':
		       				install_eq_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_EQDATA', 'result' => 'SUCCESS');
							break; 
						case 'eq2':
		       				install_eq2_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_EQ2DATA', 'result' => 'SUCCESS');
							break; 
						case 'lotro':
		       				install_lotro_bb2($bbdkp_table_prefix);
		       				$db->sql_query ( 'update ' . $bbdkp_table_prefix . 'bb_zonetable  set sequence = id '  );
		       				return array('command' => 'UMIL_INSERT_LOTRODATA', 'result' => 'SUCCESS');
							break; 
						default :
						    break; 
					}
					
					break;	
					
				case '1.1.2.1':

					/* bossprogress refit */ 
					/* remove 3 modes */
					if($umil->module_exists('acp', 'ACP_DKP_BOSS','ACP_DKP_BOSS_BOSSBASE'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_BOSSBASE');
					}
					if($umil->module_exists('acp', 'ACP_DKP_BOSS','ACP_DKP_BOSS_OFFSET'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_OFFSET');
					}
					if($umil->module_exists('acp', 'ACP_DKP_BOSS', 'ACP_DKP_BOSS_CONFIG'))
					{
						$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_CONFIG');
					}

					break;
			}
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
			
			if($umil->module_exists('acp', 'ACP_DKP_BOSS', 'ACP_DKP_BOSS_CONFIG'))
			{
				$umil->module_remove('acp','ACP_DKP_BOSS','ACP_DKP_BOSS_CONFIG');
			}
					
			
			return array(
					'command' => 'UMIL_REMOVE_GAME_ROW', 
					'result' => 'SUCCESS');
			break;
	}
		
	 
}

/******************************
 * 
 *  table rename update calls 
 * 
 */
function tablerename($action, $version)
{
	global $user, $config, $db, $table_prefix, $umil, $bbdkp_table_prefix; 
	switch ($action)
	{
		case 'install' :
		case 'update' :
		
			switch ($version)
			{
				case '1.1.3':
					
					$sql = "UPDATE  bbeqdkp_bb_language SET attribute_id = '10' where attribute='race' and attribute_id='9'" ;
					$db->sql_query($sql);
					// renaming tables
					if ($umil->table_exists($bbdkp_table_prefix . 'adjustments'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'adjustments TO ' . $table_prefix . 'bbdkp_adjustments ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists($bbdkp_table_prefix . 'events'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'events TO ' . $table_prefix . 'bbdkp_events ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists($bbdkp_table_prefix . 'items'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'items TO ' . $table_prefix . 'bbdkp_items ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists($bbdkp_table_prefix . 'logs'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'logs TO ' . $table_prefix . 'bbdkp_logs ';
						$db->sql_query($sql);
					}										
					if ($umil->table_exists($bbdkp_table_prefix . 'logs'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'logs TO ' . $table_prefix . 'bbdkp_logs ';
						$db->sql_query($sql);
					}										
					if ($umil->table_exists($bbdkp_table_prefix . 'member_ranks'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'member_ranks TO ' . $table_prefix . 'bbdkp_member_ranks ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists($bbdkp_table_prefix . 'memberlist'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'memberlist TO ' . $table_prefix . 'bbdkp_memberlist ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists($bbdkp_table_prefix . 'memberdkp'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'memberdkp TO ' . $table_prefix . 'bbdkp_memberdkp ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists($bbdkp_table_prefix . 'news'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'news TO ' . $table_prefix . 'bbdkp_news ';
						$db->sql_query($sql);
					}			
					if ($umil->table_exists($bbdkp_table_prefix . 'raid_attendees'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'raid_attendees TO ' . $table_prefix . 'bbdkp_raid_attendees ';
						$db->sql_query($sql);
					}			
					if ($umil->table_exists($bbdkp_table_prefix . 'raids'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'raids TO ' . $table_prefix . 'bbdkp_raids ';
						$db->sql_query($sql);
					}																					
					if ($umil->table_exists($bbdkp_table_prefix . 'classes'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'classes TO ' . $table_prefix . 'bbdkp_classes ';
						$db->sql_query($sql);
					}																					
					if ($umil->table_exists($bbdkp_table_prefix . 'races'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'races TO ' . $table_prefix . 'bbdkp_races ';
						$db->sql_query($sql);
					}																					
					if ($umil->table_exists($bbdkp_table_prefix . 'factions'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'factions TO ' . $table_prefix . 'bbdkp_factions ';
						$db->sql_query($sql);
					}																					
					if ($umil->table_exists($bbdkp_table_prefix . 'dkpsystem'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'dkpsystem TO ' . $table_prefix . 'bbdkp_dkpsystem ';
						$db->sql_query($sql);
					}												
					if ($umil->table_exists($bbdkp_table_prefix . 'plugins'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'plugins TO ' . $table_prefix . 'bbdkp_plugins ';
						$db->sql_query($sql);
					}												
					if ($umil->table_exists($bbdkp_table_prefix . 'memberguild'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'memberguild TO ' . $table_prefix . 'bbdkp_memberguild ';
						$db->sql_query($sql);
					}												
					if ($umil->table_exists($bbdkp_table_prefix . 'lootsystem'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'lootsystem TO ' . $table_prefix . 'bbdkp_lootsystem ';
						$db->sql_query($sql);
					}												
					if ($umil->table_exists($bbdkp_table_prefix . 'bb_language'))
					{
						$sql = 'RENAME TABLE ' . $bbdkp_table_prefix . 'bb_language TO ' . $table_prefix . 'bbdkp_language ';
						$db->sql_query($sql);
					}						
					
					return array(
					'command' => 'UMIL_RENAMETABLESNEW', 
					'result' => 'SUCCESS');
					
					break;
			}
			break;
			
		case 'uninstall' :
				// Run this when uninstalling
				// renaming tables back to old
					if ($umil->table_exists($table_prefix . 'adjustments '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_adjustments TO ' . $bbdkp_table_prefix . 'adjustments ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists( $table_prefix . 'bbdkp_events '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_events TO ' . $bbdkp_table_prefix . 'events ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists( $table_prefix . 'bbdkp_items '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_items TO ' . $bbdkp_table_prefix . 'items ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists( $table_prefix . 'bbdkp_logs '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_logs TO ' . $bbdkp_table_prefix . 'logs ';
						$db->sql_query($sql);
					}
					if ($umil->table_exists( $table_prefix . 'bbdkp_member_ranks '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_member_ranks TO ' . $bbdkp_table_prefix . 'member_ranks ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_memberlist '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_memberlist TO ' . $bbdkp_table_prefix . 'memberlist ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_memberdkp '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_memberdkp TO ' . $bbdkp_table_prefix . 'memberdkp ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_news '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_news TO ' . $bbdkp_table_prefix . 'news ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_raid_attendees '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_raid_attendees TO ' . $bbdkp_table_prefix . 'raid_attendees ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_raids '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_raids TO ' . $bbdkp_table_prefix . 'raids ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_classes '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_classes TO ' . $bbdkp_table_prefix . 'classes ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_races '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_races TO ' . $bbdkp_table_prefix . 'races ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_factions '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_factions TO ' . $bbdkp_table_prefix . 'factions ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_dkpsystem '))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_dkpsystem TO ' . $bbdkp_table_prefix . 'dkpsystem ';
						$db->sql_query($sql);
					}					
					if ($umil->table_exists( $table_prefix . 'bbdkp_plugins'))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_plugins TO ' . $bbdkp_table_prefix . 'plugins ';
						$db->sql_query($sql);
					}		
					if ($umil->table_exists( $table_prefix . 'bbdkp_memberguild'))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_memberguild TO ' . $bbdkp_table_prefix . 'memberguild ';
						$db->sql_query($sql);
					}		
					if ($umil->table_exists( $table_prefix . 'bbdkp_lootsystem'))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_lootsystem TO ' . $bbdkp_table_prefix . 'lootsystem ';
						$db->sql_query($sql);
					}							
					if ($umil->table_exists( $table_prefix . 'bbdkp_language'))
					{
						$sql = 'RENAME TABLE ' . $table_prefix . 'bbdkp_language TO ' . $bbdkp_table_prefix . 'bb_language ';
						$db->sql_query($sql);
					}						
					
					return array(
					'command' => 'UMIL_RENAMETABLESOLD', 
					'result' => 'SUCCESS');
			break;
	}
		
	 
}





?>