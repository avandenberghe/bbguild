<?php
/**
 * bbDKP database installer
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\migrations;

/**
 * Migration stage 1: Initial schema
 */
class release_2_0_0_schema extends \phpbb\db\migration\migration
{

    private $bbdkp_version = '2.0.0';

    public function effectively_installed()
    {
        return isset($this->config['bbdkp_version']) && version_compare($this->config['bbdkp_version'], $this->bbdkp_version, '>=');
    }

    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v310\gold');
    }

    /**
     * Add the bbdkp table schema to the database:
     * @return array Array of table schema
     */
    public function update_schema()
    {
        return array(
            'add_tables'	=> array(
                /*1*/
                $this->table_prefix . 'bbdkp_news'	=> array(
                    'COLUMNS'	=> array(
                        'news_id'			=> array('UINT', NULL, 'auto_increment'),
                        'news_headline'		=> array('VCHAR_UNI', ''),
                        'news_message'		=> array('TEXT_UNI', ''),
                        'news_date'			=> array('TIMESTAMP', 0),
                        'user_id'			=> array('UINT', 0),
                        'bbcode_bitfield'	=> array('VCHAR:20', ''),
                        'bbcode_uid'		=> array('VCHAR:8', ''),
                        'bbcode_options'	=> array('VCHAR:8', '')
                    ),
                    'PRIMARY_KEY'	=> 'news_id',
                ),
                /*2*/
                $this->table_prefix . 'bbdkp_language'	=> array(
                    'COLUMNS'	=> array(
                        'id'     	       => array('UINT', NULL, 'auto_increment'),
                        'game_id' 	   => array('VCHAR:10', ''),
                        'attribute_id'   => array('UINT', 0),
                        'language'       => array('CHAR:2', ''),
                        'attribute'	   => array('VCHAR:30', ''),
                        'name'       	   => array('VCHAR_UNI:255', ''),
                        'name_short' 	   => array('VCHAR_UNI:255', ''),
                    ),
                    'PRIMARY_KEY'     => array('id'),
                    'KEYS'            => array('bbdkp_language' => array('UNIQUE', array('game_id', 'attribute_id', 'language', 'attribute')),
                    )),
                /*3*/
                $this->table_prefix . 'bbdkp_factions'	=> array(
                    'COLUMNS'	=> array(
                        'game_id' 			=> array('VCHAR:10', ''),
                        'f_index'    		=> array('USINT', NULL, 'auto_increment'),
                        'game_id' 			=> array('VCHAR', ''),

                        'faction_id'   		=> array('USINT', 0),
                        'faction_name'     	=> array('VCHAR_UNI', ''),
                        'faction_hide'		=> array('BOOL', 0),
                    ),
                    'PRIMARY_KEY'    => 'f_index',
                    'KEYS'         => array('bbdkp_factions'    => array('UNIQUE',  array('game_id', 'faction_id'))),
                ),
                /*4*/
                $this->table_prefix . 'bbdkp_classes'	=> array(
                    'COLUMNS'	=> array(
                        'c_index'    		=> array('USINT', NULL, 'auto_increment'),
                        'game_id' 			=> array('VCHAR:10', ''),
                        'class_id'   		=> array('USINT', 0),
                        'class_faction_id' 	=> array('UINT', 0),
                        'class_min_level'	=> array('USINT', 0),
                        'class_max_level'	=> array('USINT', 0),
                        'class_armor_type'	=> array('VCHAR_UNI', ''),
                        'class_hide'		=> array('BOOL', 0),
                        'imagename'			=> array('VCHAR:255', ''),
                        'colorcode'			=> array('VCHAR:10', ''),
                    ),
                    'PRIMARY_KEY'    => 'c_index',
                    'KEYS'         => array('bbclass'    => array('UNIQUE', array('game_id', 'class_id'))),
                ),
                /*5*/
                $this->table_prefix . 'bbdkp_races'	=> array(
                    'COLUMNS'	=> array(
                        'game_id' 			=> array('VCHAR:10', ''),
                        'race_id'			=> array('USINT', 0),
                        'race_faction_id'	=> array('USINT', 0),
                        'race_hide'			=> array('BOOL', 0),
                        'image_female'	=> array('VCHAR:255', ''),
                        'image_male'	=> array('VCHAR:255', ''),
                    ),
                    'PRIMARY_KEY'    => array('game_id', 'race_id') ,
                ),

                /*6*/
                $this->table_prefix . 'bbdkp_memberguild'	=> array(
                    'COLUMNS'	=> array(
                        'id'			    => array('USINT', 0),
                        'name'			    => array('VCHAR_UNI:255', ''),
                        'realm'			    => array('VCHAR_UNI:255', ''),
                        'region'  		    => array('VCHAR:2', ''),
                        'battlegroup'       => array('VCHAR:255', ''),
                        'roster'  		    => array('BOOL', 0),
                        'aion_legion_id'    => array('USINT', 0),
                        'aion_server_id'    => array('USINT', 0),
                        'level'             => array('UINT', 0),
                        'members'           => array('UINT', 0),
                        'achievementpoints' =>  array('UINT', 0),
                        'guildarmoryurl'    => array('VCHAR:255', ''),
                        'emblemurl'         => array('VCHAR:255', ''),
                        'game_id'           => array('VCHAR:10', ''),
                        'min_armory'        => array('UINT', 90),
                        'rec_status'        => array('BOOL', 0),
                        'guilddefault'      => array('BOOL', 0),
                        'armory_enabled'    => array('BOOL', 0),
                        'armoryresult'      => array('VCHAR_UNI:255', ''),
                        'recruitforum'      => array('UINT', 0),
                    ),
                    'PRIMARY_KEY'  	=> array('id'),
                    'KEYS'         => array('bbguild'    => array('UNIQUE', array('name', 'id') )),
                ),

                /*7*/
                $this->table_prefix . 'bbdkp_member_ranks'	=> array(
                    'COLUMNS'	=> array(
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

                /*8*/
                $this->table_prefix . 'bbdkp_memberlist'	=> array(
                    'COLUMNS'	=> array(
                        'member_id'        => array('UINT', NULL, 'auto_increment'),
                        'game_id'  		   => array('VCHAR:10', ''),
                        'member_name'      => array('VCHAR_UNI:255', ''),
                        'member_region'     => array('VCHAR', ''),
                        'member_realm'      => array('VCHAR', ''),
                        'member_title'      => array('VCHAR_UNI:255', ''),
                        'member_level'     => array('USINT', 0),
                        'member_race_id'   => array('USINT', 0),
                        'member_class_id'  => array('USINT', 0),
                        'member_rank_id'   => array('USINT', 0),
                        'member_role'       => array('VCHAR:20', ''),
                        'member_comment'   => array('VCHAR_UNI:255', ''),
                        'member_joindate'  => array('TIMESTAMP', 0),
                        'member_outdate'   => array('TIMESTAMP', 0),
                        'member_guild_id'  => array('USINT', 0),
                        'member_gender_id' => array('USINT', 0),
                        'member_achiev'    => array('UINT', 0),
                        'member_armory_url' => array('VCHAR:255', ''),
                        'member_portrait_url' => array('VCHAR', ''),
                        'phpbb_user_id' 	=> array('UINT', 0),
                        'member_status'     => array('BOOL', 0) ,
                        'deactivate_reason' => array('VCHAR_UNI:255', ''),
                        'last_update'       => array('TIMESTAMP', 0)

                    ),
                    'PRIMARY_KEY'  => 'member_id',
                    'KEYS'         => array('member_name'    => array('UNIQUE', 'member_name')),
                ),
                /*9*/
                $this->table_prefix . 'bbdkp_dkpsystem'	=> array(
                    'COLUMNS'	=> array(
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
                /*10*/
                $this->table_prefix . 'bbdkp_events'	=> array(
                    'COLUMNS'	=> array(
                        'event_id'    		=> array('UINT', NULL, 'auto_increment'),
                        'event_dkpid'   	=> array('USINT', 0),
                        'event_name'     	=> array('VCHAR_UNI:255', ''),
                        'event_color'     	=> array('VCHAR:8', ''),
                        'event_imagename'   => array('VCHAR:255', ''),
                        'event_value'		=> array('DECIMAL:11', 0),
                        'event_added_by'	=> array('VCHAR_UNI:255', ''),
                        'event_updated_by'	=> array('VCHAR_UNI:255', ''),
                        'event_status' 		=> array('BOOL', 1),
                    ),
                    'PRIMARY_KEY'    => 'event_id',
                    'KEYS'            => array('event_dkpid'    => array('INDEX', 'event_dkpid')),
                ),
                /*11*/
                $this->table_prefix . 'bbdkp_memberdkp'	=> array(
                    'COLUMNS'	=> array(
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
                        'member_firstraid'  => array('TIMESTAMP', 0),
                        'member_lastraid'	=> array('TIMESTAMP', 0),
                        'member_raidcount'	=> array('UINT', 0),
                        'adj_decay' 		=> array('DECIMAL:11', 0.00),
                    ),
                    'PRIMARY_KEY'  => array('member_dkpid', 'member_id'),
                ),
                /*12*/
                $this->table_prefix . 'bbdkp_adjustments'	=> array(
                    'COLUMNS'	=> array(
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
                ),

                /*13*/
                $this->table_prefix . 'bbdkp_raids'	=> array(
                    'COLUMNS'	=> array(
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
                ),

                /*14*/
                $this->table_prefix . 'bbdkp_raid_detail'	=> array(
                    'COLUMNS'	=> array(
                        'raid_id'		=> array('UINT', 0),
                        'member_id'		=> array('UINT', 0),
                        'raid_value' 	=> array('DECIMAL:11', 0),
                        'time_bonus' 	=> array('DECIMAL:11', 0),
                        'zerosum_bonus' => array('DECIMAL:11', 0),
                        'raid_decay' 	=> array('DECIMAL:11', 0),
                        'decay_time' 	=> array('DECIMAL:11', 0.00),
                    ),
                    'PRIMARY_KEY'  => array('raid_id', 'member_id'),
                ),


                /*15*/
                $this->table_prefix . 'bbdkp_raid_items'	=> array(
                    'COLUMNS'	=> array(
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
                        'wowhead_id'      => array('UINT', 0)
                    ),
                    'PRIMARY_KEY'     => 'item_id',
                    'KEYS'         => array('raid_id'    => array('INDEX', 'raid_id')),
                ),

                /*16*/
                $this->table_prefix . 'bbdkp_logs'	=> array(
                    'COLUMNS'	=> array(
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
                        'log_ipaddress'	=> array('INDEX', 'log_ipaddress')),
                ),

                /*17*/
                $this->table_prefix . 'bbdkp_plugins'	=> array(
                    'COLUMNS'	=> array(
                        'name'			=> array('VCHAR_UNI:255', ''),
                        'value'			=> array('BOOL', 0),
                        'version'  		=> array('VCHAR:50', ''),
                        'installdate'   => array('TIMESTAMP', 0),
                        'orginal_copyright' => array('VCHAR_UNI:150', ''),
                        'bbdkp_copyright'  	=> array('VCHAR_UNI:150', ''),
                    ),
                ),

                /*18*/
                $this->table_prefix . 'bbdkp_welcomemsg'	=> array(
                    'COLUMNS'	=> array(
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
                ),
                /*19*/
                $this->table_prefix . 'bbdkp_games'	=> array(
                    'COLUMNS'	=> array(
                        'id'     	     => array('UINT', NULL, 'auto_increment'),
                        'game_id' 	     => array('VCHAR:10', ''),
                        'game_name'      => array('VCHAR_UNI:255', ''),
                        'status'	   	 => array('VCHAR:30', ''),
                        'imagename'	     => array('VCHAR:20', ''),
                        'armory_enabled' => array('UINT', 0),
                        'bossbaseurl'    => array('VCHAR:255', ''),
                        'zonebaseurl'    => array('VCHAR:255', ''),
                        'apikey'         => array('VCHAR:255', ''),
                        'apilocale'      => array('VCHAR:5', ''),
                        'privkey'        => array('VCHAR:255', '')
                    ),
                    'PRIMARY_KEY'     => array('id'),
                    'KEYS'            => array('bbdkp_games' => array('UNIQUE', array('game_id')))
                ),
                /*20*/
                $this->table_prefix . 'bbdkp_gameroles'	=> array(
                    'COLUMNS'	=> array(
                        'role_pkid'        => array('INT:8', NULL, 'auto_increment'),
                        'game_id'          => array('VCHAR:10', ''),
                        'role_id'          => array('INT:8', 0),
                        'role_color'       => array('VCHAR', ''),
                        'role_icon'        => array('VCHAR', ''),
                        'role_cat_icon'    => array('VCHAR', ''),
                    ),
                    'PRIMARY_KEY'    => 'role_pkid',
                    'KEYS'         => array('bbroles'    => array('UNIQUE', array('game_id', 'role_id')))
                ),
                /*22*/
                $this->table_prefix . 'bbdkp_recruit'	=> array(
                    'COLUMNS'	=> array(
                        'id'               => array('INT:8', NULL, 'auto_increment'),
                        'guild_id'         => array('USINT', 0),
                        'role_id'          => array('INT:8', 0),
                        'class_id'         => array('UINT',  0),
                        'level'            => array('UINT',  0),
                        'positions'        => array('USINT', 0),
                        'applicants'       => array('USINT', 0),
                        'status'           => array('USINT', 0),
                        'applytemplate_id' => array('UINT',  0),
                        'last_update'      => array('TIMESTAMP', 0),
                        'note'             => array('TEXT_UNI', ''),
                    ),
                    'PRIMARY_KEY'          => 'id',
                ),


            ),
        );
    }

    /**
     * Drop the pages table schema from the database
     *
     * @return array Array of table schema
     * @access public
     */
    public function revert_schema()
    {
        return array(
            'drop_tables'	=> array(
                $this->table_prefix . 'bbdkp_news',
                $this->table_prefix . 'bbdkp_language',
                $this->table_prefix . 'bbdkp_factions',
                $this->table_prefix . 'bbdkp_classes',
                $this->table_prefix . 'bbdkp_races',
                $this->table_prefix . 'bbdkp_memberguild',
                $this->table_prefix . 'bbdkp_member_ranks',
                $this->table_prefix . 'bbdkp_memberlist',
                $this->table_prefix . 'bbdkp_dkpsystem',
                $this->table_prefix . 'bbdkp_events',
                $this->table_prefix . 'bbdkp_memberdkp',
                $this->table_prefix . 'bbdkp_adjustments',
                $this->table_prefix . 'bbdkp_raids',
                $this->table_prefix . 'bbdkp_raid_detail',
                $this->table_prefix . 'bbdkp_raid_items',
                $this->table_prefix . 'bbdkp_logs',
                $this->table_prefix . 'bbdkp_plugins',
                $this->table_prefix . 'bbdkp_welcomemsg',
                $this->table_prefix . 'bbdkp_games',
                $this->table_prefix . 'bbdkp_gameroles',
                $this->table_prefix . 'bbdkp_recruit',
            ),
        );
    }
}
