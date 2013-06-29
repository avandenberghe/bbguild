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
 * Game interface
 *
 * @package 	bbDKP
 * 
 */
interface iGame 
{
	/**
	 * gets Game from database
	 */
	function Get();
	
	/**
	 * adds a Game to database
	*/
	function Make();
	
	/**
	 * deletes a Game from database
	*/
	function Delete();
	
	
}

?>