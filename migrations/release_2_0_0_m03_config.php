<?php
/**
 * bbDKP database installer
 *
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbguild\migrations;
use phpbb\db\migration\migration;

/**
* Migration stage 2: config data
*/
class release_2_0_0_m03_config extends migration
{

    protected $bbguild_version = '2.0.0-a1-dev';

	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('\sajaki\bbguild\migrations\release_2_0_0_m02_data');
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
            array('config.add', array('bbguild_version', $this->bbguild_version )),
			array('config.add', array('bbguild_date_format', 'd.m.y')),
			array('config.add', array('bbguild_default_game', 'wow')),
			array('config.add', array('bbguild_default_realm', 0)),
			array('config.add', array('bbguild_default_region', 0)),
			array('config.add', array('bbguild_eqdkp_start', 1447196400)),
			// guildfaction : limit the possible races to be available to users to those available in the guild's chosen faction
			array('config.add', array('bbguild_guild_faction', 1)),
			array('config.add', array('bbguild_hide_inactive', 1)),
			array('config.add', array('bbguild_lang', 'en')),
			array('config.add', array('bbguild_maxchars', 5)),
			array('config.add', array('bbguild_minrosterlvl', 50)),
			array('config.add', array('bbguild_n_news', 5)),
			array('config.add', array('bbguild_news_forumid', 2)),
			array('config.add', array('bbguild_regid', 0)),
			// roster layout: main parameter for steering roster layout
			array('config.add', array('bbguild_roster_layout', 0)),
			// showachiev : show the achievement points
			array('config.add', array('bbguild_show_achiev', 0)),
			array('config.add', array('bbguild_user_llimit', 30)),
			array('config.add', array('bbguild_user_nlimit', 20)),
            // portal settings
			array('config.add', array('bbguild_portal_links', 1)),
			array('config.add', array('bbguild_portal_loot', 1)),
			array('config.add', array('bbguild_portal_maxnewmembers', 5)),
			array('config.add', array('bbguild_portal_menu', 1)),
			array('config.add', array('bbguild_portal_newmembers', 1)),
			array('config.add', array('bbguild_portal_onlineblockposition', 1)),
			array('config.add', array('bbguild_portal_recent', 1)),
			array('config.add', array('bbguild_portal_recruitments', 1)),
			array('config.add', array('bbguild_portal_rtlen', 15)),
			array('config.add', array('bbguild_portal_rtno', 5)),
			array('config.add', array('bbguild_portal_showedits', 1)),
			array('config.add', array('bbguild_portal_welcomemsg', 1)),
			array('config.add', array('bbguild_portal_whoisonline', 1)),


            // Add permission
            array('permission.add', array('a_bbguild', true)),
            array('permission.add', array('f_bbguild', true)),
            array('permission.add', array('u_charclaim', true)),
            array('permission.add', array('u_charadd', true)),
            array('permission.add', array('u_chardelete', true)),
            array('permission.add', array('u_charupdate', true)),

            // Set permissions

            //admin can access bbguild acp
            array('permission.permission_set', array('ADMINISTRATORS', 'a_bbguild', 'group')),

            //can see bbguild pages
            array('permission.permission_set', array('GUESTS', 'f_bbguild', 'group')),
            array('permission.permission_set', array('REGISTERED_COPPA', 'f_bbguild', 'group')),
            array('permission.permission_set', array('GLOBAL_MODERATORS', 'f_bbguild', 'group')),
            array('permission.permission_set', array('ADMINISTRATORS', 'f_bbguild', 'group')),
            array('permission.permission_set', array('BOTS', 'f_bbguild', 'group')),
            array('permission.permission_set', array('NEWLY_REGISTERED', 'f_bbguild', 'group')),

            //can claim a character
            array('permission.permission_set', array('ADMINISTRATORS', 'u_charclaim', 'group')),
            array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_charclaim', 'group')),
            array('permission.permission_set', array('REGISTERED', 'u_charclaim', 'group')),
            array('permission.permission_set', array('REGISTERED_COPPA', 'u_charclaim', 'group')),

            // can delete their own character in ucp
            array('permission.permission_set', array('ADMINISTRATORS', 'u_chardelete', 'group')),
            array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_chardelete', 'group')),
            array('permission.permission_set', array('REGISTERED', 'u_chardelete', 'group')),
            array('permission.permission_set', array('REGISTERED_COPPA', 'u_chardelete', 'group')),

            // can add own character in ucp
            array('permission.permission_set', array('ADMINISTRATORS', 'u_charadd', 'group')),
            array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_charadd', 'group')),
            array('permission.permission_set', array('REGISTERED', 'u_charadd', 'group')),
            array('permission.permission_set', array('REGISTERED_COPPA', 'u_charadd', 'group')),

            // can update own character in ucp
            array('permission.permission_set', array('ADMINISTRATORS', 'u_charupdate', 'group')),
            array('permission.permission_set', array('GLOBAL_MODERATORS', 'u_charupdate', 'group')),
            array('permission.permission_set', array('REGISTERED', 'u_charupdate', 'group')),
            array('permission.permission_set', array('REGISTERED_COPPA', 'u_charupdate', 'group')),

        );
	}
}
