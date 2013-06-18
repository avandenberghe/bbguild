<?php
namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**
 * Ranks interface
 *
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */
interface iRanks
{

	/**
	 * gets rank from database
	 */
	function Get();

	/**
	 * adds a rank to database
	*/
	function Make();

	/**
	 * deletes a rank from database
	*/
	function Delete($override);

	/**
	 * updates a rank to database
	*/
	function Update(Ranks $old_rank);

}
