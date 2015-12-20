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
					'modes'           => array('mainpage', 'dkp_config', 'dkp_logs', 'dkp_indexpageconfig') ,
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
			array('acp', 'ACP_DKP_MAINPAGE', array(
				'module_basename' => '\sajaki\bbdkp\acp\dkp_game_module',
				'modes'           => array('listgames', 'addfaction', 'addrace', 'addclass'),
			)),

			/**
			* Guild and Member management Category
			*/
			array('module.add', array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MEMBER')),


			// add GuildList
			array('module.add', array(
				'acp', 'ACP_DKP_MEMBER', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_guild_module',
					'modes'           => array('addguild', 'editguild', 'listguilds') ,
				)
			)),

			// add Roster
			array('module.add', array(
				'acp', 'ACP_DKP_MEMBER', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_mm_module',
					'modes'           => array('mm_addmember', 'mm_listmembers') ,
				)
			)),

			// add Recruitment
			array('module.add', array(
				'acp', 'ACP_DKP_MEMBER', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_recruit_module',
					'modes'           => array('addrecruit', 'editrecruit', 'listrecruit') ,
				)
			)),

			/**
			 * add dkp management Category
			 *
			 */
			array('acp', 'ACP_CAT_DKP', 'ACP_DKP_MDKP'),

			// add dkp pool module
			array('module.add', array(
				'acp', 'ACP_DKP_MDKP', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_sys_module',
					'modes'           => array('adddkpsys', 'editdkpsys', 'listdkpsys', 'addevent' ) ,
				)
			)),

			// add dkp - edit dkp - transfer dkp module
			array('module.add', array(
				'acp', 'ACP_DKP_MDKP', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_mdkp_module',
					'modes'           => array('mm_editmemberdkp', 'mm_listmemberdkp', 'mm_transfer') ,
				)
			)),

			// add dkp adjustments module
			array('module.add', array(
				'acp', 'ACP_DKP_MDKP', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_adj_module',
					'modes'           => array('addiadj', 'listiadj') ,
				)
			)),

			/**
			 * add Raid management Category
			 */
			array('module.add', array('acp', 'ACP_CAT_DKP', 'ACP_DKP_RAIDS')),

			//  add manual raid modules
			array('module.add', array(
				'acp', 'ACP_DKP_RAIDS', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_raid_module',
					'modes'           => array('addraid', 'editraid', 'listraids') ,
				)
			)),

			// add loot module
			array('module.add', array(
				'acp', 'ACP_DKP_RAIDS', array(
					'module_basename' => '\sajaki\bbdkp\acp\dkp_loot_module',
					'modes'           => array('listloot', 'addloot', 'search', 'viewloot') ,
				)
			)),

			/**
			 * Add the UCP Category
			 * link bbDKP memberids to phpbb accounts
			 *
			 */
			array('module.add', array('ucp', '0', 'UCP_DKP')),

			// Add one UCP module to the new category
			array('module.add', array(
				'acp', 'UCP_DKP', array(
					'module_basename' => '\sajaki\bbdkp\ucp\dkp_ucp_module',
					'modes'           => array('characters', 'characteradd') ,
				)
			)),


		);
	}
}
