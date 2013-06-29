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

// Include the abstract base
if (!interface_exists('\bbdkp\iGame'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/iGame.$phpEx");
}

/**
 * Races interface
 *
 * @package 	bbDKP
 * 
 */
interface iRaces extends \bbdkp\iGame
{
	/**
	 * gets race from database
	 */
	function Get();
	
	/**
	 * adds a race to database
	*/
	function Make();
	
	/**
	 * deletes a race from database
	*/
	function Delete();
	
	/**
	 * updates a race to database
	*/
	function Update(Races $old_race);

}

?>