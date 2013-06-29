<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * @since 1.2.9
 */
namespace bbdkp;
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!interface_exists('\bbdkp\iGame'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/iGame.$phpEx");
}

/**
 * Games
 *
 * Manages creation of Game
 *
 * @package 	bbDKP
 * @todo write implementation for creating a new game based on current installer
*/
class Game implements iGame {
	
	/**
	 * list of allowed games
	 * @var array
	 */
	private $allowed_games;

	/**
	 * 
	 * Boolean to indicate if a game is installed
	 * @var bool
	 */
	private $is_installed;
	
	function __construct() 
	{
		
	}
	
	/**
	 * @todo gets Game from database
	 * 
	 */
	function Get()
	{
		
	}
	
	/**
	 * @todo adds a Game to database
	*/
	function Make()
	{
		
	}
	
	/**
	 * @todo deletes a Game from database
	*/
	function Delete()
	{
		
	}
	
	/**
	 * (method not in interface)
	 * @todo  updates a Game to database
	*/
	function GameUpdate(Game $old_game)
	{
		
	}
}

?>