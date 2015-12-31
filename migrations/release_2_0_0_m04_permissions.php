<?php
/**
 * bbDKP database installer
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\migrations;

/**
* Migration stage 5: Initial permission
*/
class release_2_0_0_m04_permissions extends \phpbb\db\migration\migration
{
	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('\sajaki\bbdkp\migrations\release_2_0_0_m03_config');
	}

	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			// Add permission
			array('permission.add', array('a_dkp', true)),
			array('permission.add', array('f_dkp', true)),
			array('permission.add', array('u_dkpucp', true)),
			array('permission.add', array('u_dkp_charadd', true)),
			array('permission.add', array('u_dkp_chardelete', true)),
			array('permission.add', array('u_dkp_charupdate', true)),

			// Set permissions

			//admin can access dkp acp
			array('permission.permission_set', array('ADMINISTRATORS', 'a_dkp', 'group')),

            //can see dkp pages
			array('permission.permission_set', array('GUESTS', 'f_dkp', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'f_dkp', 'group')),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'f_dkp', 'group')),
			array('permission.permission_set', array('ADMINISTRATORS', 'f_dkp', 'group')),
			array('permission.permission_set', array('BOTS', 'f_dkp', 'group')),
			array('permission.permission_set', array('NEWLY_REGISTERED', 'f_dkp', 'group')),

            //can claim a character
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dkpucp', 'group')),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_dkpucp', 'group')),
			array('permission.permission_set', array('REGISTERED', 'u_dkpucp', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_dkpucp', 'group')),

            // can delete their own character in ucp
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dkp_chardelete', 'group')),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_dkp_chardelete', 'group')),
			array('permission.permission_set', array('REGISTERED', 'u_dkp_chardelete', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_dkp_chardelete', 'group')),

			// can add own character in ucp
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dkp_charadd', 'group')),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_dkp_charadd', 'group')),
			array('permission.permission_set', array('REGISTERED', 'u_dkp_charadd', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_dkp_charadd', 'group')),

			// can update own character in ucp
			array('permission.permission_set', array('ADMINISTRATORS', 'u_dkp_charupdate', 'group')),
			array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_dkp_charupdate', 'group')),
			array('permission.permission_set', array('REGISTERED', 'u_dkp_charupdate', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_dkp_charupdate', 'group')),
		);


	}
}
