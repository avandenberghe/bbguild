<?php
namespace bbdkp;
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */


/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**
 * Guild
 *
 * Manages Guild creation
 *
 * @package 	bbDKP
 */
interface iGuilds 
{

	/**
	 * gets guild from database
	 */
	function Get();

	/**
	 * adds a guild to database
	 */
	function Make();

	/**
	 * deletes a guild from database
	 */
	function Delete();

	/**
	 * updates a guild to database
	 */
	function Update($old_guild);

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
