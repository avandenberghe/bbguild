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

// Include the abstract base
if (!interface_exists('\bbdkp\iGame'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/iGame.$phpEx");
}

/**
 * Factions interface
 *
 * @package 	bbDKP
 * 
 */
interface iFaction extends \bbdkp\iGame
{
	/**
	 * gets faction from database
	 */
	function Get();
	
	/**
	 * adds a faction to database
	*/
	function Make();
	
	/**
	 * deletes a faction from database
	*/
	function Delete();

}

?>