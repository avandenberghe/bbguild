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
 * Member interface
 *
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */
interface iMembers {

	/**
	 * gets member from database
	 */
	function Get();

	/**
	 * adds a member to database
	 */
	function Make();

	/**
	 * deletes a member from database
	 */
	function Delete();

	/**
	 * updates a member to database
	 */
	function Update(Members $old_member);


	function activate(array $mlist, array $mwindow);

}

?>