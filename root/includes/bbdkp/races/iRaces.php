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

/**
 * Races interface
 *
 * @package 	bbDKP
 * 
 */
interface iRaces 
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