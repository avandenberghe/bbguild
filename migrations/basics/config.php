<?php
/**
 * bbGuild - Configuration migration
 *
 * @package   avathar\bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\migrations\basics;

class config extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\data'];
	}

	public function effectively_installed()
	{
		return isset($this->config['bbguild_date_format']);
	}

	public function update_data()
	{
		return [
			['config.add', ['bbguild_date_format', 'd.m.y']],
			['config.add', ['bbguild_default_game', '']],
			['config.add', ['bbguild_eqdkp_start', time()]],
			['config.add', ['bbguild_guild_faction', 1]],
			['config.add', ['bbguild_hide_inactive', 1]],
			['config.add', ['bbguild_lang', 'en']],
			['config.add', ['bbguild_maxchars', 5]],
			['config.add', ['bbguild_minrosterlvl', 50]],
			['config.add', ['bbguild_n_news', 5]],
			['config.add', ['bbguild_news_forumid', 2]],
			['config.add', ['bbguild_regid', 0]],
			['config.add', ['bbguild_roster_layout', 0]],
			['config.add', ['bbguild_show_achiev', 0]],
			['config.add', ['bbguild_user_llimit', 15]],
			['config.add', ['bbguild_user_nlimit', 15]],
			['config.add', ['bbguild_portal_links', 1]],
			['config.add', ['bbguild_portal_loot', 1]],
			['config.add', ['bbguild_portal_maxnewplayers', 5]],
			['config.add', ['bbguild_portal_menu', 1]],
			['config.add', ['bbguild_portal_newplayers', 1]],
			['config.add', ['bbguild_portal_onlineblockposition', 1]],
			['config.add', ['bbguild_portal_recent', 1]],
			['config.add', ['bbguild_portal_recruitments', 1]],
			['config.add', ['bbguild_portal_rtlen', 15]],
			['config.add', ['bbguild_portal_rtno', 5]],
			['config.add', ['bbguild_portal_showedits', 1]],
			['config.add', ['bbguild_motd', 1]],
			['config.add', ['bbguild_portal_whoisonline', 1]],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['bbguild_date_format']],
			['config.remove', ['bbguild_default_game']],
			['config.remove', ['bbguild_eqdkp_start']],
			['config.remove', ['bbguild_guild_faction']],
			['config.remove', ['bbguild_hide_inactive']],
			['config.remove', ['bbguild_lang']],
			['config.remove', ['bbguild_maxchars']],
			['config.remove', ['bbguild_minrosterlvl']],
			['config.remove', ['bbguild_n_news']],
			['config.remove', ['bbguild_news_forumid']],
			['config.remove', ['bbguild_regid']],
			['config.remove', ['bbguild_roster_layout']],
			['config.remove', ['bbguild_show_achiev']],
			['config.remove', ['bbguild_user_llimit']],
			['config.remove', ['bbguild_user_nlimit']],
			['config.remove', ['bbguild_portal_links']],
			['config.remove', ['bbguild_portal_loot']],
			['config.remove', ['bbguild_portal_maxnewplayers']],
			['config.remove', ['bbguild_portal_menu']],
			['config.remove', ['bbguild_portal_newplayers']],
			['config.remove', ['bbguild_portal_onlineblockposition']],
			['config.remove', ['bbguild_portal_recent']],
			['config.remove', ['bbguild_portal_recruitments']],
			['config.remove', ['bbguild_portal_rtlen']],
			['config.remove', ['bbguild_portal_rtno']],
			['config.remove', ['bbguild_portal_showedits']],
			['config.remove', ['bbguild_motd']],
			['config.remove', ['bbguild_portal_whoisonline']],
		];
	}
}
