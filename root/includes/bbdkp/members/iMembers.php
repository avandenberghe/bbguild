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
 * Member interface
 *
 * @package 	bbDKP
 * 
 */
interface iMembers extends \bbdkp\iAdmin
{

	/**
	 * gets member from database
	 */
	function Get();

	/**
	 * adds a member to database
	 * @return int member_id in case of success, 0 if false
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