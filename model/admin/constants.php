<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/*
* connect to phpBB
*/
if (!defined('IN_PHPBB') )
{
	exit;
}
global $table_prefix;

define('BBGUILD_VERSION', '2.0.0-a6');
define('BBGUILD_VERSIONURL', 'http://www.avathar.be/versioncheck/');
define('URI_LOG', 'log');
define('URI_NAME', 'name');
define('URI_NAMEID', 'player_id');
define('URI_NEWS', 'news');
define('URI_ORDER', 'o');
define('URI_PAGE', 'pag');
define('URI_GUILD', 'guild_id');
define('URI_GAME', 'game_id');
define('USER_LLIMIT',  20);

define('BBGAMES_TABLE',             $table_prefix . 'bb_games');
define('BBLOGS_TABLE',              $table_prefix . 'bb_logs');
define('PLAYER_RANKS_TABLE',        $table_prefix . 'bb_ranks');
define('GUILD_TABLE',               $table_prefix . 'bb_guild');
define('PLAYER_TABLE',              $table_prefix . 'bb_players');
define('CLASS_TABLE',               $table_prefix . 'bb_classes');
define('BB_GAMEROLE_TABLE',         $table_prefix . 'bb_gameroles');
define('RACE_TABLE',                $table_prefix . 'bb_races');
define('FACTION_TABLE',             $table_prefix . 'bb_factions');
define('BB_LANGUAGE',               $table_prefix . 'bb_language');
define('MOTD_TABLE',                $table_prefix . 'bb_motd');
define('BBRECRUIT_TABLE',           $table_prefix . 'bb_recruit');

define('ACHIEVEMENT_TRACK_TABLE',   $table_prefix . 'bb_achievement_track');
define('ACHIEVEMENT_TABLE',         $table_prefix . 'bb_achievement');
define('ACHIEVEMENT_REWARDS_TABLE', $table_prefix . 'bb_achievement_rewards');
define('CRITERIA_TRACK_TABLE',      $table_prefix . 'bb_criteria_track');
define('ACHIEVEMENT_CRITERIA_TABLE', $table_prefix . 'bb_achievement_criteria');
define('BB_RELATIONS_TABLE',        $table_prefix . 'bb_relations_table');

define('BOSSBASE',                  $table_prefix . 'bb_bosstable');
define('ZONEBASE',                  $table_prefix . 'bb_zonetable');

define('NEWS_TABLE',                $table_prefix . 'bb_news');
define('BBDKPPLUGINS_TABLE',        $table_prefix . 'bb_plugins');
