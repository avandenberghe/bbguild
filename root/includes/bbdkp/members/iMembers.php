<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
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
	function Getmember();

	/**
	 *  inserts a new member to database
	 * @return int member_id in case of success, 0 if false
	 */
	function Makemember();

	/**
	 * deletes a member from database
	 */
	function Deletemember();

	/**
	 * updates a member to database
	 */
	function Updatemember(Members $old_member);
	
	/**
	 * Calls api to pull more information
	 * Currently only the WoW API is available
	 *
	 * @return object
	 */
	function Armory_getmember();
	
	/**
	 * 
	 * @param array $mlist
	 * @param array $mwindow
	 */
	function Activatemembers(array $mlist, array $mwindow);
	
	/**
	 * Updates the Member table from Armory
	 * @param array $memberdata
	 * @param int $guild_id
	 * @param char $region
	 * @param int $min_armory
	 */
	public function WoWArmoryUpdate($memberdata, $guild_id, $region, $min_armory); 
	

}

?>