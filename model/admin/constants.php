<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/*
* connect to phpBB
*/
if ( !defined('IN_PHPBB') )
{
    exit;
}
global $table_prefix;

define('BBGUILD_VERSION' , '2.0.0-a1-dev');
define('BBGUILD_VERSIONURL' , 'http://www.avathar.be/versioncheck/');
define('URI_LOG' , 'log');
define('URI_NAME' , 'name');
define('URI_NAMEID' , 'member_id');
define('URI_NEWS' , 'news');
define('URI_ORDER' , 'o');
define('URI_PAGE' , 'pag');
define('URI_GUILD' , 'guild_id');
define('URI_GAME' , 'game_id');
define('USER_LLIMIT' ,  40);
define('BBGAMES_TABLE',        	   $table_prefix . 'bbguild_games');
define('NEWS_TABLE',        	   $table_prefix . 'bbguild_news');
define('BBLOGS_TABLE',             $table_prefix . 'bbguild_logs');
define('MEMBER_RANKS_TABLE',       $table_prefix . 'bbguild_ranks');
define('MEMBER_LIST_TABLE',        $table_prefix . 'bbguild_members');
define('CLASS_TABLE',              $table_prefix . 'bbguild_classes');
define('RACE_TABLE',               $table_prefix . 'bbguild_races');
define('FACTION_TABLE',            $table_prefix . 'bbguild_factions');
define('GUILD_TABLE',	           $table_prefix . 'bbguild_guild');
define('BB_LANGUAGE',	           $table_prefix . 'bbguild_language');
define('BOSSBASE',          	   $table_prefix . 'bbguild_bosstable');
define('ZONEBASE',         		   $table_prefix . 'bbguild_zonetable');
define('WELCOME_MSG_TABLE',        $table_prefix . 'bbguild_welcomemsg');
define('BBRECRUIT_TABLE',          $table_prefix . 'bbguild_recruit');
define('BB_GAMEROLE_TABLE',        $table_prefix . 'bbguild_gameroles');
define('BBDKPPLUGINS_TABLE',	   $table_prefix . 'bbdkp_plugins');