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
* Migration stage 6: Initial module setup
*/
class release_2_0_0_m05_mod extends \phpbb\db\migration\migration
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
		return array('\sajaki\bbdkp\migrations\release_2_0_0_m04_permissions');
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

			/**
			 * First, lets add bbDKP category to the root.
			 */
			//array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_DKP')),
			array('module.add', array('acp', 0, 'ACP_CAT_DKP')),

			/**
			 * General Settings Category
			 */
			array('module.add', array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MAINPAGE')),


			//adminpanel, portal settings, Logs
			array('module.add', array(
				'acp', 'ACP_DKP_MAINPAGE', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_main_module',
					'modes'           => array('dkp_panel', 'dkp_config', 'dkp_logs', 'dkp_index') ,
				)
			)),


			//Point settings
			array('module.add', array(
				'acp', 'ACP_DKP_MAINPAGE', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_point_module',
					'modes'           => array('pointconfig') ,
				)
			)),

            //Gaming menu
            array('module.add', array(
                'acp', 'ACP_DKP_MAINPAGE', array(
                    'module_basename' => '\sajaki\bbdkp\acp\dkp_game_module',
                    'modes'           => array('listgames', 'editgames', 'addfaction', 'addrace', 'addclass', 'addrole'),
                )
            )),


        );
	}
}
