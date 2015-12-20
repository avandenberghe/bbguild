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
* Migration stage 3: config data
*/
class release_2_0_0_m03_config extends \phpbb\db\migration\migration
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
		return array('\sajaki\bbdkp\migrations\release_2_0_0_m02_data');
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
			array('config.add', array('bbdkp_active_point_adj', 10.00)),
			array('config.add', array('bbdkp_adjdecaypct', 5)),
			array('config.add', array('bbdkp_bankerid', 0)),
			array('config.add', array('bbdkp_basegp', 0)),
			array('config.add', array('bbdkp_crontime', 23)),
			array('config.add', array('bbdkp_date_format', 'd.m.y')),
			array('config.add', array('bbdkp_decay', 0)),
			array('config.add', array('bbdkp_decaycron', 0)),
			array('config.add', array('bbdkp_decayfreqtype', 1)),
			array('config.add', array('bbdkp_decayfrequency', 1)),
			array('config.add', array('bbdkp_default_game', 'wow')),
			array('config.add', array('bbdkp_default_realm', 0)),
			array('config.add', array('bbdkp_default_region', 0)),
			array('config.add', array('bbdkp_dkp_name', 'DKP')),
			array('config.add', array('bbdkp_dkptimeunit', 5)),
			array('config.add', array('bbdkp_epgp', 0)),
			array('config.add', array('bbdkp_eqdkp_start', 1447196400)),
			array('config.add', array('bbdkp_event_viewall', 1)),
			array('config.add', array('bbdkp_guild_faction', 1)),
			array('config.add', array('bbdkp_hide_inactive', 1)),
			array('config.add', array('bbdkp_inactive_period', 150)),
			array('config.add', array('bbdkp_inactive_point_adj', -10.00)),
			array('config.add', array('bbdkp_itemdecaypct', 5)),
			array('config.add', array('bbdkp_lang', 'EN')),
			array('config.add', array('bbdkp_lastcron', 0)),
			array('config.add', array('bbdkp_list_p1', 30)),
			array('config.add', array('bbdkp_list_p2', 60)),
			array('config.add', array('bbdkp_list_p3', 90)),
			array('config.add', array('bbdkp_maxchars', 5)),
			array('config.add', array('bbdkp_minep', 100)),
			array('config.add', array('bbdkp_minrosterlvl', 50)),
			array('config.add', array('bbdkp_n_items', 5)),
			array('config.add', array('bbdkp_n_news', 5)),
			array('config.add', array('bbdkp_news_forumid', 2)),
			array('config.add', array('bbdkp_portal_links', 1)),
			array('config.add', array('bbdkp_portal_loot', 1)),
			array('config.add', array('bbdkp_portal_maxnewmembers', 5)),
			array('config.add', array('bbdkp_portal_menu', 1)),
			array('config.add', array('bbdkp_portal_newmembers', 1)),
			array('config.add', array('bbdkp_portal_onlineblockposition', 1)),
			array('config.add', array('bbdkp_portal_recent', 1)),
			array('config.add', array('bbdkp_portal_recruitments', 1)),
			array('config.add', array('bbdkp_portal_rtlen', 15)),
			array('config.add', array('bbdkp_portal_rtno', 5)),
			array('config.add', array('bbdkp_portal_showedits', 1)),
			array('config.add', array('bbdkp_portal_welcomemsg', 1)),
			array('config.add', array('bbdkp_portal_whoisonline', 1)),
			array('config.add', array('bbdkp_regid', 0)),
			array('config.add', array('bbdkp_roster_layout', 0)),
			array('config.add', array('bbdkp_show_achiev', 0)),
			array('config.add', array('bbdkp_standardduration', 1)),
			array('config.add', array('bbdkp_starting_dkp', 15.00)),
			array('config.add', array('bbdkp_timebased', 0)),
			array('config.add', array('bbdkp_timeunit', 0)),
			array('config.add', array('bbdkp_user_alimit', 30)),
			array('config.add', array('bbdkp_user_elimit', 30)),
			array('config.add', array('bbdkp_user_llimit', 30)),
			array('config.add', array('bbdkp_user_nlimit', 20)),
			array('config.add', array('bbdkp_user_rlimit', 20)),
			array('config.add', array('bbdkp_version','2.0.0')),
			array('config.add', array('bbdkp_zerosum', 0)),
			array('config.add', array('bbdkp_zerosumdistother', 0)),
		);
	}
}
