<?php

/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */
namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}
require_once("{$phpbb_root_path}includes/bbdkp/iAdmin.$phpEx");

/**
 * Guild
 *
 * Manages Guild creation
 *
 * @package 	bbDKP
 */
interface iGuilds extends \bbdkp\iAdmin
{

	/**
	 * gets guild from database
	 */
	function Getguild();

	/**
	 * adds a guild to database
	 */
	function MakeGuild();

	/**
	 * deletes a guild from database
	 */
	function Guildelete();

	/**
	 * 
	 * updates a guild to database. If $params array is filled then use it to get additional info from public API
	 * @param Guilds $old_guild
	 * @param array $params
	 */
	function Guildupdate($old_guild, $params);

	/**
	 * lists all members for this guild
	 * @param unknown_type $order
	 * @param unknown_type $start
	 */
	function listmembers($order, $start);

	/**
	 * counts all guild members
	 */
	function countmembers();



}
