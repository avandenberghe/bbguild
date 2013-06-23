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
require_once("{$phpbb_root_path}includes/bbdkp/guilds/iGuilds.$phpEx");

/**
 * Ranks interface
 *
 * @package 	bbDKP
 * 
 */
interface iRanks extends \bbdkp\iGuilds
{

	/**
	 * gets rank from database
	 */
	function Getrank();

	/**
	 * adds a rank to database
	*/
	function Makerank();

	/**
	 * deletes a rank from database
	*/
	function Rankdelete($override);

	/**
	 * updates a rank to database
	*/
	function Rankupdate(Ranks $old_rank);

}
