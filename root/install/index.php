<?php
/**
 * @package bbDKP.install
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.7
 */
 
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
define('IN_INSTALL', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_install.' . $phpEx);
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$user->add_lang ( array ('mods/dkp_admin'));

$error= array();
// anything lower than php 5.1 not supported (we use simplexml xpath)
if (version_compare(PHP_VERSION, '5.1.0') < 0)
{
	$error[] = 'You are running an unsupported PHP version ('. PHP_VERSION . '). Please upgrade to PHP 5.1.2 or higher before trying to install bbDKP. ';
}

// check for mysql 4. use of subqueries only after 4.1
$alldbms = get_available_dbms($dbms);
foreach($alldbms as $thisdmbs)
{
	switch($thisdmbs['DRIVER'])
	{
		case 'mysql':
			$dbversion = mysql_get_server_info($db->db_connect_id);
			if (version_compare($dbversion, '4.1.0', '<'))
			{
				$error[] = "You are running an unsupported Mysql version ($dbversion) . Please upgrade to Mysql 4.1 or higher before trying to install bbDKP. ";
			}
			break;
	}
}
unset ($alldbms);
unset ($thisdmbs);

if(count($error) > 0)
{
	trigger_error(implode($error,"<br /> "), E_USER_WARNING);
}

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

//check old version. if lower then 126 then trigger error
check_oldbbdkp();

// The name of the mod to be displayed during installation.
$mod_name = 'bbDKP';

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
* Setting the checkboxes at runtime 
*/
$gameinstall['aion']=false;
$gameinstall['daoc']=false;
$gameinstall['eq']=false;
$gameinstall['eq2']=false;
$gameinstall['FFXI']=false;
$gameinstall['lotro']=false;
$gameinstall['rift']=false;
$gameinstall['vanguard']=false;
$gameinstall['wow']=false;
$gameinstall['warhammer']=false;
$gameinstall['swtor']=false;
$gameinstall['lineage2']=false;

$choice=false;
if (isset($config['bbdkp_default_game'])) 
{
	$gameinstall[$config['bbdkp_default_game']] = $choice = true;
}

if (isset($config['bbdkp_games_aion']))
{
	$gameinstall['aion'] = $config['bbdkp_games_aion'];
}
if (isset($config['bbdkp_games_daoc']))
{
	$gameinstall['daoc'] =  $config['bbdkp_games_daoc'];
}
if (isset($config['bbdkp_games_eq']))
{
	$gameinstall['eq'] =  $config['bbdkp_games_eq'];
}
if (isset($config['bbdkp_games_eq2']))
{
	$gameinstall['eq2'] = $config['bbdkp_games_eq2'];
}
if (isset($config['bbdkp_games_FFXI']))
{
	$gameinstall['FFXI'] = $config['bbdkp_games_FFXI'];
}
if (isset($config['bbdkp_games_lotro']))
{
	$gameinstall['lotro'] = $config['bbdkp_games_lotro'];
}
if (isset($config['bbdkp_games_rift']))
{
	$gameinstall['rift'] = $config['bbdkp_games_rift'];
}
if (isset($config['bbdkp_games_vanguard']))
{
	$gameinstall['vanguard'] = $config['bbdkp_games_vanguard'];
}
if (isset($config['bbdkp_games_wow']))
{
	$gameinstall['wow'] = $config['bbdkp_games_wow'];
}
if (isset($config['bbdkp_games_warhammer']))
{
	$gameinstall['warhammer'] = $config['bbdkp_games_warhammer'];
}
if (isset($config['bbdkp_games_swtor']))
{
	$gameinstall['swtor'] = $config['bbdkp_games_swtor'];
}
if (isset($config['bbdkp_games_lineage2']))
{
	$gameinstall['lineage2'] = $config['bbdkp_games_lineage2'];
}

$options = array(
		'guildtag'	=> array('lang' => 'UMIL_GUILD', 'type' => 'text:40:255', 'explain' => false, 'select_user' => false),
        'realm'	    => array('lang' => 'REALM_NAME', 'type' => 'text:40:255', 'explain' => false, 'select_user' => false),
		'region'   => array('lang' => 'REGION', 'type' => 'select', 'function' => 'regionoptions', 'explain' => true),
		
		'aion'   => array('lang' => 'AION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['aion']) ? true:false) ),
		'daoc'   => array('lang' => 'DAOC', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['daoc']) ? true:false)),
		'eq'   => array('lang' => 'EQ', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['eq']) ? true:false)),
		'eq2'   => array('lang' => 'EQ2', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['eq2']) ? true:false)),
		'FFXI'   => array('lang' => 'FFXI', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['FFXI']) ? true:false)),
		'lotro'   => array('lang' => 'LOTRO', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['lotro']) ? true:false)),
		'rift'   => array('lang' => 'RIFT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['rift']) ? true:false)),
		'vanguard'   => array('lang' => 'VANGUARD', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['vanguard'] ) ? true:false)),
		'warhammer'   => array('lang' => 'WARHAMMER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['warhammer'] ) ? true:false)),
		'wow'     => array('lang' => 'WOW', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['wow'] ) ? true:false)),
		'swtor'     => array('lang' => 'SWTOR', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['swtor'] ) ? true:false)),
		'lineage2'     => array('lang' => 'LINEAGE2', 'validate' => 'bool', 'type' => 'radio:yes_no', 'default' => (($gameinstall['lineage2'] ) ? true:false)),
);

/*
 * including the gamefiles
 */
include($phpbb_root_path .'install/gamesinstall/install_aion.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_daoc.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_eq.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_eq2.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_ffxi.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_lotro.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_vanguard.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_warhammer.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_wow.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_rift.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_swtor.' . $phpEx);
include($phpbb_root_path .'install/gamesinstall/install_lineage2.' . $phpEx);

/*
 * insert welcome message
 */	
$welcome_message = encode_message($user->lang['WELCOME_DEFAULT']);

/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
$logo_img = 'install/logo.png'; 
/*
  $user, $config, $db, $table_prefix, $umil, $bbdkp_table_prefix; 
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
    '1.2.6'    => array(
    	// install base bbdkp tables (this uses the layout from develop/create_schema_files.php and from phpbb_db_tools)
        'table_add' => array(

			array($table_prefix . 'bbdkp_news', array(
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
            
            array($table_prefix . 'bbdkp_language', array(
	              'COLUMNS'            => array(
	          		  'id'     	       => array('UINT', NULL, 'auto_increment'), 
                   	  'game_id' 	   => array('VCHAR', ''), 
	                  'attribute_id'   => array('UINT', 0), 
	                  'language'       => array('CHAR:2', ''),
	          		  'attribute'	   => array('VCHAR:30', ''), 
	                  'name'       	   => array('VCHAR_UNI:255', ''), 
	                  'name_short' 	   => array('VCHAR_UNI:255', ''),
	          	),
	                'PRIMARY_KEY'     => array('id'),
	          		'KEYS'            => array('bbdkp_language' => array('UNIQUE', array('game_id', 'attribute_id', 'language', 'attribute')),
				)
	            )),   

	      array($table_prefix . 'bbdkp_factions', array(
                    'COLUMNS'        => array(
	      				'game_id' 			=> array('VCHAR', ''), 
                        'f_index'    		=> array('USINT', NULL, 'auto_increment'),
	         			'game_id' 			=> array('VCHAR', ''), 

                        'faction_id'   		=> array('USINT', 0),
                        'faction_name'     	=> array('VCHAR_UNI', ''),
                        'faction_hide'		=> array('BOOL', 0),
                    ),
                    'PRIMARY_KEY'    => 'f_index',                   
                    'KEYS'         => array('bbdkp_factions'    => array('UNIQUE',  array('game_id', 'faction_id'))),    
                ),
            ),

          array($table_prefix . 'bbdkp_classes', array(
                    'COLUMNS'        => array(
                        'c_index'    		=> array('USINT', NULL, 'auto_increment'),
          				'game_id' 			=> array('VCHAR', ''), 
                        'class_id'   		=> array('USINT', 0),
          				'class_faction_id' 	=> array('UINT', 0), 
                        'class_min_level'	=> array('USINT', 0),
                        'class_max_level'	=> array('USINT', 0),
                        'class_armor_type'	=> array('VCHAR_UNI', ''),
                        'class_hide'		=> array('BOOL', 0),
            			'dps'				=> array('USINT', 0),
            			'tank'				=> array('USINT', 0),
            			'heal'				=> array('USINT', 0),
						'imagename'			=> array('VCHAR:255', ''),
                		'colorcode'			=> array('VCHAR:10', ''),
            
                    ),
                    'PRIMARY_KEY'    => 'c_index',
                    'KEYS'         => array('bbclass'    => array('UNIQUE', array('game_id', 'class_id'))),
                ),
            ),

		  array($table_prefix . 'bbdkp_races', array(
                    'COLUMNS'				=> array(
                        'game_id' 			=> array('VCHAR', ''),
                        'race_id'			=> array('USINT', 0),
                        'race_faction_id'	=> array('USINT', 0),
                        'race_hide'			=> array('BOOL', 0),
						'image_female'	=> array('VCHAR:255', ''),
						'image_male'	=> array('VCHAR:255', ''),
                    ),
                    'PRIMARY_KEY'    => array('game_id', 'race_id') ,
                    

                ),
            ),            
           
            // Guild table 
			//id is not auto-increment 
           array($table_prefix . 'bbdkp_memberguild', array(
                    'COLUMNS'       => array(
                       'id'				=> array('USINT', 0), 
                       'name'			=> array('VCHAR_UNI:255', ''), 
		  			   'realm'			=> array('VCHAR_UNI:255', ''),
					   'region'  		=> array('VCHAR:2', ''),
					   'roster'  		=> array('BOOL', 0), 
					   'aion_legion_id' => array('USINT', 0), 
            		   'aion_server_id' => array('USINT', 0),
            			 
                      ),
                    'PRIMARY_KEY'  	=> array('id'),
					'KEYS'         => array('bbguild'    => array('UNIQUE', array('name', 'id') )),                    
              ),
            ),  

           array($table_prefix . 'bbdkp_member_ranks', array(
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

		array($table_prefix . 'bbdkp_memberlist', array(
                    'COLUMNS'        	   => array(
						'game_id'  		   => array('VCHAR', ''), 
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
            			'member_armory_url' => array('VCHAR:255', ''),
						'member_portrait_url' => array('VCHAR', ''),
						'phpbb_user_id' 	=> array('UINT', 0)
            
                    ),
                    'PRIMARY_KEY'  => 'member_id',
                    'KEYS'         => array('member_name'    => array('UNIQUE', 'member_name')),
                ),
            ),

            array($table_prefix . 'bbdkp_dkpsystem', array(
                    'COLUMNS'        => array(
                        'dkpsys_id'    		=> array('USINT', NULL, 'auto_increment'),
                        'dkpsys_name'   	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_status'     => array('VCHAR:2', 'Y'),
                        'dkpsys_addedby'	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_updatedby'	=> array('VCHAR_UNI:255', ''),
                        'dkpsys_default'	=> array('VCHAR:2', 'N'),
            			'adj_decay' 		=> array('DECIMAL:11', 0.00),
                    ),
                    'PRIMARY_KEY'    => 'dkpsys_id',
                    'KEYS'         => array('dkpsys_name'    => array('UNIQUE', 'dkpsys_name')),
                ),
            ),

            array($table_prefix . 'bbdkp_events', array(
                    'COLUMNS'        => array(
                        'event_id'    		=> array('UINT', NULL, 'auto_increment'),
                        'event_dkpid'   	=> array('USINT', 0),
                        'event_name'     	=> array('VCHAR_UNI:255', ''),
            			'event_color'     	=> array('VCHAR:8', ''),
            			'event_imagename'   => array('VCHAR:255', ''),
                        'event_value'		=> array('DECIMAL:11', 0),
                        'event_added_by'	=> array('VCHAR_UNI:255', ''),
                        'event_updated_by'	=> array('VCHAR_UNI:255', ''),
                    ),
                    'PRIMARY_KEY'    => 'event_id',
                    'KEYS'            => array('event_dkpid'    => array('INDEX', 'event_dkpid')),
                    
                ),
            ),
            
		  array($table_prefix . 'bbdkp_memberdkp', array(
                    'COLUMNS'        	 => array(
                        'member_id'			=> array('UINT', 0),
                        'member_dkpid'		=> array('USINT', 0),
		  				'member_raid_value'	=> array('DECIMAL:11', 0),
		  				'member_time_bonus'	=> array('DECIMAL:11', 0),
		  				'member_zerosum_bonus'	=> array('DECIMAL:11', 0),
                        'member_earned'		=> array('DECIMAL:11', 0),		
		  				'member_raid_decay'	=> array('DECIMAL:11', 0),
						'member_spent'		=> array('DECIMAL:11', 0),
		  				'member_item_decay'	=> array('DECIMAL:11', 0),
						'member_adjustment' => array('DECIMAL:11', 0),
						'member_status' 	=> array('BOOL', 0) ,
						'member_firstraid'  => array('TIMESTAMP', 0),
						'member_lastraid'	=> array('TIMESTAMP', 0),
						'member_raidcount'	=> array('UINT', 0),
		  				'adj_decay' 		=> array('DECIMAL:11', 0.00), 
            
                    ),
                    'PRIMARY_KEY'  => array('member_dkpid', 'member_id'),
                ),
           ),

        array($table_prefix . 'bbdkp_adjustments', array(
              'COLUMNS'        => array(
                  'adjustment_id'        => array('UINT', NULL, 'auto_increment'),
				  'member_id'        	 => array('UINT', 0),
                  'adjustment_dkpid'     => array('USINT', 0),
                  'adjustment_value'     => array('DECIMAL:11', 0),
        		  'adjustment_date'      => array('TIMESTAMP', 0),
				  'adjustment_reason'    => array('VCHAR_UNI', ''),
				  'adjustment_added_by'  => array('VCHAR_UNI:255', ''),
				  'adjustment_updated_by'=> array('VCHAR_UNI:255', ''),
				  'adjustment_group_key' => array('VCHAR', ''),
        		  'adj_decay' 			 => array('DECIMAL:11', 0.00), 
       			  'can_decay' 			 => array('BOOL', 0), 
         		  'decay_time' 			 => array('DECIMAL:11', 0.00), 
                ),
                'PRIMARY_KEY'    => 'adjustment_id',
                'KEYS'         => array('member_id'    => array('INDEX', array('member_id', 'adjustment_dkpid'))),
          )),

          array($table_prefix . 'bbdkp_raids', array(
				'COLUMNS'        	=> array(
					'raid_id'			=> array('UINT', NULL, 'auto_increment'),
					'event_id'			=> array('UINT', 0),
					'raid_note'   		=> array('VCHAR_UNI:255', ''),
					'raid_start'  		=> array('TIMESTAMP', 0) ,
         			'raid_end'  		=> array('TIMESTAMP', 0) ,
					'raid_added_by' 	=> array('VCHAR_UNI:255', ''),
					'raid_updated_by' 	=> array('VCHAR_UNI:255', ''),
					),
				'PRIMARY_KEY'  => array('raid_id'),
				'KEYS'         => array('event_id'    => array('INDEX', 'event_id')),
            )),
 		
		  array($table_prefix . 'bbdkp_raid_detail', array(
				'COLUMNS'		=> array(
					'raid_id'		=> array('UINT', 0),
					'member_id'		=> array('UINT', 0),
					'raid_value' 	=> array('DECIMAL:11', 0),
					'time_bonus' 	=> array('DECIMAL:11', 0),
					'zerosum_bonus' => array('DECIMAL:11', 0),
		  			'raid_decay' 	=> array('DECIMAL:11', 0),
		            'decay_time' 	=> array('DECIMAL:11', 0.00), 
				),
				'PRIMARY_KEY'  => array('raid_id', 'member_id')
			)),

                
          array($table_prefix . 'bbdkp_raid_items', array(
                    'COLUMNS'     => array(
                    'item_id'         => array('UINT', NULL, 'auto_increment'),
					'raid_id'         => array('UINT', 0),
                    'item_name'       => array('VCHAR_UNI:255', ''),
          			'member_id'		  => array('UINT', 0),
					'item_date'       => array('TIMESTAMP', 0),
					'item_added_by'   => array('VCHAR_UNI:255', ''),
					'item_updated_by' => array('VCHAR_UNI:255', ''),
           			'item_group_key'  => array('VCHAR', ''),
           			'item_gameid' 	  => array('UINT', 0),
					'item_value'      => array('DECIMAL:11', 0.00),
          			'item_decay'      => array('DECIMAL:11', 0.00), // decay of itemvalue
          			'item_zs'      	  => array('BOOL', 0), 		// if this flag is set the itemvalue will be distributed over raid
         			'decay_time' 	  => array('DECIMAL:11', 0.00), 
                    ),
                    'PRIMARY_KEY'     => 'item_id',
                    'KEYS'         => array('raid_id'    => array('INDEX', 'raid_id')),					
             )),

          array($table_prefix . 'bbdkp_logs', array(
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
                )),
            
		  array($table_prefix . 'bbdkp_plugins', array(
                  'COLUMNS'        	=> array(
                      'name'			=> array('VCHAR_UNI:255', ''),
                      'value'			=> array('BOOL', 0),
                      'version'  		=> array('VCHAR:50', ''),
                      'orginal_copyright' => array('VCHAR_UNI:150', ''),
                      'bbdkp_copyright'  	=> array('VCHAR_UNI:150', ''),
                  )
               )),

          
          array($table_prefix . 'bbdkp_welcomemsg' , array(
                  	'COLUMNS'        => array(
                      'welcome_id'    		=> array('INT:8', NULL, 'auto_increment'),
                      'welcome_title' 		=> array('VCHAR_UNI', ''),
                      'welcome_msg'   		=> array('TEXT_UNI', ''),
            		  'welcome_timestamp' 	=> array('TIMESTAMP', 0),
				   	  'bbcode_bitfield' 	=> array('VCHAR:255', ''),
				   	  'bbcode_uid' 		=> array('VCHAR:8', ''),
            		  'user_id'     		=> array('INT:8', 0),
            		  'bbcode_options'		=> array('UINT', 7),
                  ),
                  'PRIMARY_KEY'    => 'welcome_id'
                )),
       ),
           
       'table_row_insert'	=> array(
       
		  // we insert a dummy pool
         array($table_prefix .'bbdkp_dkpsystem',
          array(
                  array(
                  		'dkpsys_name' 	 => 'Default DKP Pool' ,
                  		'dkpsys_status'  => 'Y',
                   		'dkpsys_addedby' =>  'admin' ,
                   		'dkpsys_default' =>  'Y'
                  		),
                  )
           ),
		
         // insert a dummy event
         array($table_prefix .'bbdkp_events',
          array(
                  array('event_dkpid' => 1,
                  		'event_name' => 'Default event',
                  		'event_color' => '#000000', 
                  		'event_value' => 10 
                  		),
                  )
           ),

          // we insert a dummy guild (None) for guildless people and also the default guild
         array($table_prefix .'bbdkp_memberguild',
          array(
           		  // guildless -> do show on roster
                  array('id'  => 0,
                      'name' => '(None)',
                      'realm' => utf8_normalize_nfc(request_var('realm', '', true)),
                      'region' => request_var('region', ''), 
                      'roster' => 1
                  		),
                  
           		  // default guild -> do show on roster                  
                  array('id'  => 1,
                      'name' => ( request_var('guildtag', ' ')== ' ' ? utf8_normalize_nfc(request_var('guildtag', ' ', true)) : 'default'), 
                      'realm' => ( request_var('realm', ' ', true) == ' ' ? utf8_normalize_nfc(request_var('realm', ' ', true)) : 'default'),  
                      'region' => (isset($_POST['region']) ? request_var('region', ' ') : 'EU'), 
                  	  'roster' => 1 ),
                  )
           ),
		
		 array($table_prefix . 'bbdkp_member_ranks', 
			 array(
			 
			 	// standard member rank
	       		array(
	       			'guild_id'	=> 1,	
					'rank_id'	=> 0,
					'rank_name'	=> 'Member',
					'rank_hide'	=> 0,
					'rank_prefix'	=> '',
					'rank_suffix'	=> '',
				 ),
				 
				 
				// operating rank, undeletable rank
	       		array(
	       			'guild_id'	=> 1,	
					'rank_id'	=> 90,
					'rank_name'	=> 'Operating',
					'rank_hide'	=> 1,
					'rank_prefix'	=> '',
					'rank_suffix'	=> '',
				 ),
				 
				// Out rank : for unguilded, undeletable rank
	       		array(
	       			'guild_id'	=> 0,	
					'rank_id'	=> 99,
					'rank_name'	=> 'Out',
					'rank_hide'	=> 1,
					'rank_prefix'	=> '',
					'rank_suffix'	=> '',
				 ),
				)
			), 
			
		 // create the guildbank character
		 array($table_prefix . 'bbdkp_memberlist', 
			 array(
	       		array(
	       			'member_name' 		=> 'Guildbank', 
					'member_status'		=> 1,
					'member_level'		=> 1,
					'member_race_id'	=> 0,
					'member_class_id'	=> 0,
					'member_rank_id'	=> 90,
	       			'member_comment'	=> 'The guildbank toon',
	       			'member_joindate'	=> time(),
	       			'member_outdate'	=> '1893456000',
	       			'member_guild_id'	=> 1,
	       			'member_gender_id'	=> 1,
	       			'phpbb_user_id'		=> $user->data['user_id'],
				 ),
			)),
			
		  array( $table_prefix . 'bbdkp_welcomemsg',
           		array(
                  array(
                  	'welcome_title' => 'Welcome to our guild', 
                  	'welcome_timestamp' => (int) time(),
                  	'welcome_msg' => $welcome_message['text'],
                  	'bbcode_uid' => $welcome_message['uid'],
                  	'bbcode_bitfield' => $welcome_message['bitfield'],
                  	'user_id' => $user->data['user_id'] ),          
          	 	)
            ),
		),

    	// create two basic permissions
	   'permission_add' => array(
            array('a_dkp', true),
            array('u_dkp', true), 
            array('u_dkpucp', true),
            array('u_dkp_charadd', true) ,
            array('u_dkp_chardelete', true), 
            array('u_dkp_charupdate', true),                       
      	),
      
        // Assign default permissions to Full admin
        'permission_set' => array(
            array('ROLE_ADMIN_FULL', 		'a_dkp'),
            array('ROLE_ADMIN_FULL', 		'u_dkp'),
            array('ROLE_USER_FULL', 		'u_dkp'),
            array('ROLE_ADMIN_FULL', 		'u_dkpucp'),
            array('ROLE_USER_STANDARD', 	'u_dkpucp'),
           	array('ROLE_USER_STANDARD', 'u_dkp_charadd'),
            array('ROLE_USER_STANDARD', 'u_dkp_chardelete'),
            array('ROLE_USER_STANDARD', 'u_dkp_charupdate'),
            array('ROLE_USER_STANDARD', 'u_dkpucp'),
            array('ROLE_USER_STANDARD', 'u_dkp'),
            array('ROLE_USER_FULL', 'u_dkp_charadd'),
            array('ROLE_USER_FULL', 'u_dkp_chardelete'),
            array('ROLE_USER_FULL', 'u_dkp_charupdate'),
            array('ROLE_USER_FULL', 'u_dkpucp'),
        ),
        
        // add new parameters
        'config_add' => array(
        
        	//bbdkp settings
        	
        	// guildname				
			array('bbdkp_guildtag', utf8_normalize_nfc(request_var('guildtag', '', true)), true),
	        // default realm & region
	        array('bbdkp_default_realm', ( request_var('realm', ' ', true) == ' ' ? utf8_normalize_nfc(request_var('realm', ' ', true)) : 'default') , true),  
	        array('bbdkp_default_region', request_var('region', ''), true),  
			array('bbdkp_eqdkp_start', mktime(0, 0, 0, date("m")  , date("d"), date("Y")) , true),
			array('bbdkp_date_format', 'd.m.y', true),
			array('bbdkp_dkp_name', 'DKP', true),
			array('bbdkp_default_game', request_var('game', ''), true),
	        //default dkp language
	        array('bbdkp_lang', 'en', true),
	        
	        // news limit
			array('bbdkp_user_nlimit', '5', true),
	        // roster layout: main parameter for steering roster layout 
			array('bbdkp_roster_layout', '1', true),
	        // showachiev : show the achievement points
	        array('bbdkp_show_achiev', '0', true),
			// guildfaction : limit the possible races to be available to users to those available in the guild's chosen faction
			array('bbdkp_guild_faction', '1', true),
	        //guildmemberlist paging
	        array('bbdkp_user_llimit', '20', true),
	        
	        //standings
			array('bbdkp_hide_inactive', '1', true),
			array('bbdkp_inactive_period', '150', true),
			array('bbdkp_list_p1', '30', true),
			array('bbdkp_list_p2', '60', true),
         	array('bbdkp_list_p3', '90', true),    
			
			//events
			array('bbdkp_user_elimit', '30', true),
			
			//adjustments
			array('bbdkp_user_alimit', '30', true),
			array('bbdkp_active_point_adj', '10.00', true),
			array('bbdkp_inactive_point_adj', '-10.00', true),
			array('bbdkp_starting_dkp', '15.00', true), 
			
			//items
			array('bbdkp_user_ilimit', '20', true),

			//raids
			array('bbdkp_user_rlimit', '20', true),
			
			//epgp
			array('bbdkp_epgp', 0, true),	
			array('bbdkp_basegp', 0, true),
			
			//decay			
			array('bbdkp_decay', 0, true),
			array('bbdkp_raiddecaypct', 5, true),	
			array('bbdkp_itemdecaypct', 5, true),			
			array('bbdkp_decayfrequency', 1, true),
			array('bbdkp_decayfreqtype', 1, true),

			//time
			array('bbdkp_timebased', 0, true),
			array('bbdkp_dkptimeunit', 5, true),
			array('bbdkp_timeunit', 30, true),
			array('bbdkp_standardduration', 1, true),
			
			//zerosum
			array('bbdkp_zerosum', 0, true),
			array('bbdkp_bankerid', 0, true),
			array('bbdkp_zerosumdistother', 0, true),

            // portal settings
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
			// show recruitment block          
	        array('bbdkp_portal_recruitment', 1, true ), 
			// show link block          
	        array('bbdkp_portal_links', 1, true ), 
		    // show post edits in portal          
	        array('bbdkp_portal_showedits', 1, true ),
	        // showing or hiding portal
	        array('bbdkp_portal_menu', 1, true),
	         
	        array('bbdkp_games_aion', 0, true),
			array('bbdkp_games_daoc', 0, true),
			array('bbdkp_games_eq', 0, true),
			array('bbdkp_games_eq2', 0, true),
			array('bbdkp_games_FFXI', 0, true),
			array('bbdkp_games_lotro', 0, true),
			array('bbdkp_games_rift', 0, true),
			array('bbdkp_games_vanguard', 0, true),
			array('bbdkp_games_wow', 0, true),
			array('bbdkp_games_warhammer', 0, true),
			array('bbdkp_games_swtor', 0, true),
	        
	        array('bbdkp_portal_welcomemsg', 1, true),
			array('bbdkp_maxchars', 2, true),
			array('bbdkp_games_lineage2', 0, true),

			array('bbdkp_minep', 100.0, true),
	        array('bbdkp_decaycron', 1, true),
	        array('bbdkp_lastcron', 0, true),
	        array('bbdkp_crontime', 23, true),
	        array('bbdkp_adjdecaypct', 5, true),
	        array('bbdkp_minrosterlvl', 50, true),
	        array('bbdkp_portal_rtno', 5, true),
	        array('bbdkp_portal_rtlen', 15, true),
	        array('bbdkp_portal_rtshow', 1, true),
        ),
          
        // add the bbdkp modules to ACP using the info files, 
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
            
            array('acp', 'ACP_DKP_MAINPAGE', array(
           		 'module_basename' => 'dkp_point',
            	 'modes'           => array('pointconfig') ,
        		)),
        		
            /*
             * add member management menu
             * note added the roster here
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MEMBER'),
            
            // add memberlist-add-ranks-roster
            array('acp', 'ACP_DKP_MEMBER', array(
           		 'module_basename' => 'dkp_mm',
            	 'modes'           => array('mm_listguilds', 'mm_addguild', 'mm_ranks', 'mm_listmembers', 'mm_addmember'),
        		),
            ),          
            
           array('acp', 'ACP_DKP_MEMBER', array(
           		 'module_basename' => 'dkp_game',
            	 'modes'           => array('listgames', 'addfaction', 'addrace', 'addclass'),
        		)), 
            
            /*
             * add raid management menu
             */
            array('acp', 'ACP_CAT_DKP', 'ACP_DKP_RAIDS'),
            
            // add raid pools 
            array('acp', 'ACP_DKP_RAIDS', array(
           		 'module_basename' => 'dkp_sys',
            	 'modes'           => array('adddkpsys', 'listdkpsys' ),
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
            	 'modes'           => array('addraid', 'editraid', 'listraids'),
        		),
            ),            
            
            // add item modules
            array('acp', 'ACP_DKP_RAIDS', array(
           		 'module_basename' => 'dkp_item',
            	 'modes'           => array('listitems', 'edititem', 'search', 'viewitem'),
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
            
			// Add the UCP module in which you link bbDKP memberids to your phpbb account
			array('ucp', 0, 'UCP_DKP'),
         	
			// Add one UCP module to the new category
			array('ucp', 'UCP_DKP', 
				array(
					'module_basename'   => 'dkp',
					'module_langname'   => 'UCP_DKP_CHARACTERS',
					'module_mode'       => 'characters',
					'module_auth'       => '',
				),
				
				array(
					'module_basename'   => 'dkp',
					'module_langname'   => 'UCP_DKP_CHARACTER_ADD',
					'module_mode'       => 'characteradd',
					'module_auth'       => '',
					),
			),
			

				
          ),
            
        'custom' => array( 
            'acplink', 
            'tableupdates', 
            'gameinstall', 
          	'bbdkp_caches'
       ), 
    ),
    
    '1.2.7' => array(

    // nothing to see here

    

    ), 

    


);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/**
 * encode welcome text
 *
 * @param string $text
 * @return array
 */
function encode_message($text)
{
	$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	$allow_bbcode = $allow_urls = $allow_smilies = true;
	generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$welcome_message['text']=$text;
	$welcome_message['uid']=$uid;
	$welcome_message['bitfield']=$bitfield;
	return $welcome_message;
}


/******************************
 * 
 *  gametable installer
 *  at each bbdkp verionupdate this function is updated for latest specs. 
 * 
 */
function gameinstall($action, $version)
{
	global $db, $table_prefix, $umil, $user, $config, $phpbb_root_path, $phpEx; 
	$installed_games = array();
	
	switch ($action)
	{
		case 'install' :
		 case 'update' :
			switch ($version)
			{
				case '1.2.6':
				if(request_var('aion', 0) == 1)
				{
					install_aion($action, $version); 
					$umil->config_update('bbdkp_games_aion', 1, true);
					$installed_games[] = 'aion';
				}

				if(request_var('daoc', 0) == 1)
				{
					install_daoc($action, $version);
					$umil->config_update('bbdkp_games_daoc', 1, true);
					$installed_games[] = 'daoc';
				}
				
				if(request_var('eq', 0) == 1)
				{
					install_eq($action, $version); 
					$umil->config_update('bbdkp_games_eq', 1, true);
					$installed_games[] = 'eq';
				}
				
				if(request_var('eq2', 0) == 1)
				{
					install_eq2($action, $version); 
					$umil->config_update('bbdkp_games_eq2', 1, true);
					$installed_games[] = 'eq2';
				}

				if(request_var('FFXI', 0) == 1)
				{
					install_ffxi($action, $version); 
					$umil->config_update('bbdkp_games_FFXI', 1, true);
					$installed_games[] = 'FFXI';
				}
				
				if(request_var('lotro', 0) == 1)
				{
					install_lotro($action, $version); 
					$umil->config_update('bbdkp_games_lotro', 1, true);
					$installed_games[] = 'lotro';					
				}

				if(request_var('vanguard', 0) == 1)
				{
					install_vanguard($action, $version); 
					$umil->config_update('bbdkp_games_vanguard', 1, true);
					$installed_games[] = 'vanguard';
				}
				
				if(request_var('warhammer', 0) == 1)
				{
					install_warhammer($action, $version); 
					$umil->config_update('bbdkp_games_warhammer', 1, true);
					$installed_games[] = 'vanguard';
				}
				
				if(request_var('wow', 0) == 1)
				{
					install_wow($action, $version); 
					$umil->config_update('bbdkp_games_wow', 1, true);
					$installed_games[] = 'wow';
				}
				
				if(request_var('rift', 0) == 1)
				{
					// new game
					install_rift($action, $version); 
					$umil->config_update('bbdkp_games_rift', 1, true);
					$installed_games[] = 'rift';
				}
				
				if(request_var('swtor', 0) == 1)
				{
					// New game
					install_swtor($action, $version); 
					$umil->config_update('bbdkp_games_swtor', 1, true);
					$installed_games[] = 'swtor';
				}
				
				if(request_var('lineage2', 0) == 1)
		        {
	          			install_lineage2($action, $version); 
	         			$umil->config_update('bbdkp_games_lineage2', 1, true);
	         			$installed_games[] = 'lineage2';          			
	       		}
	       		
                foreach($installed_games as $gameid)
                {
                	// update the guildbank with the first installed gameid
					$sql = "UPDATE " . $table_prefix . 'bbdkp_memberlist' . " set game_id = '" . $gameid . "' where member_rank_id = '90' ";
					$db->sql_query($sql);
					// now break we dont need to run this more than once.
					break;
				}

			    // report what we did to umil
				return array('command' => sprintf($user->lang['UMIL_GAME126'], implode(", ", $installed_games)) , 'result' => 'SUCCESS');
				break;
			}
			break;
		case 'uninstall' :
			return array('command' => 'UMIL_GAMEUNINST126', 'result' => 'SUCCESS');
	}
					
}

/*
 * 
 */
function tableupdates($action, $version)
{
	global $user, $umil, $config, $db, $table_prefix; 
	switch ($action)
	{
				
		case 'install' :
			switch ($version)
			{
					case '1.2.6':
						break;	
					case '1.2.7':
						break;										
			}
			break;
		case 'update':
			switch ($version)
			{
					case '1.2.6':
						break;
					case '1.2.7':
						break;
			}
			break;
		case 'uninstall' :
			switch ($version)
			{
				case '1.2.6':
					break;
				case '1.2.7':
					break;					
			}
			break;
	}
	return array('command' => sprintf($user->lang['UMIL_UPDTABLES'], $action, $version) , 'result' => 'SUCCESS');
	
}

/***************************************
 *
 * adds config value with dkp module Id
 */
function acplink($action, $version)
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

/***
 * checks if there is an older install then quit
 */
function check_oldbbdkp()
{
	global $db, $table_prefix, $umil, $config, $phpbb_root_path, $phpEx;
	
	include($phpbb_root_path . 'umil/umil.' . $phpEx);
	$umil=new umil;
	
	// check config		
	if($umil->config_exists('bbdkp_version'))
    {
		if(version_compare($config['bbdkp_version'], '1.2.6') == -1 )
		{
			//stop here, the version is less than 1.2.6
			//redirect(append_sid($phpbb_root_path . '/olddkpupdate/index.'. $phpEx));
			trigger_error( $user->lang['ERROR_MINIMUM126'], E_USER_WARNING);  
			
		}
		
    }   	

	
}


?>