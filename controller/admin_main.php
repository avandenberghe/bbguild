<?php
/**
 * bbGuild Mainpage ACP
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\controller;

use phpbb\config\config;
use phpbb\language\language;
use phpbb\log\log;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use avathar\bbguild\model\admin\constants;

/**
 * Class admin_controller
 */
class admin_main
{
	/**
	 * @var \phpbb\auth\auth
	 */
	public $auth;
	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\language\language */
	protected $language;
	/** @var \phpbb\log\log */
	protected $log;
	protected $pagination;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	/**
	 * @var \phpbb\extension\manager
	 */
	protected $phpbb_extension_manager;
	/**
	 * @var string
	 */
	protected $ext_path;
	/**
	 * @var string
	 */
	protected $ext_path_web;
	/** @var string phpBB root path */
	protected $root_path;
	/** @var string phpBB admin path */
	protected $adm_relative_path;
	/** @var string PHP extension */
	protected $php_ext;
	/** @var string Form key used for form validation */
	protected $form_key;
	/** @var string Custom form action */
	protected $u_action;

	/* @var \avathar\bbguild\model\admin\curl curl class */
	public $curl;

	/* @var \avathar\bbguild\model\admin\log logging class */
	public $bbguildlog;

	/**
	 * supported languages. The game related texts (class names etc) are not stored in language files but in the database.
	 * supported languages are en, fr, de : to add a new language you need to a) make language files b) make db installers in new language c) adapt this array
	 *
	 * @var array
	 */
	public $languagecodes;

	public $bb_games_table;
	public $bb_logs_table;
	public $bb_ranks_table;
	public $bb_guild_table;
	public $bb_players_table;
	public $bb_classes_table;
	public $bb_races_table;
	public $bb_gameroles_table;
	public $bb_factions_table;
	public $bb_language_table;
	public $bb_motd_table;
	public $bb_recruit_table;
	public $bb_achievement_track_table;
	public $bb_achievement_table;
	public $bb_achievement_rewards_table;
	public $bb_criteria_track_table;
	public $bb_achievement_criteria_table;
	public $bb_relations_table;
	public $bb_bosstable;
	public $bb_zonetable;
	public $bb_news;
	public $bb_plugins;

	/**
	 * admin_main constructor.
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\cache\driver\driver_interface  $cache
	 * @param config $config
	 * @param \phpbb\db\driver\driver_interface     $db
	 * @param language $language
	 * @param log $log
	 * @param pagination $pagination
	 * @param request $request
	 * @param template $template
	 * @param user $user
	 * @param \phpbb\path_helper                $path_helper
	 * @param \phpbb\extension\manager          $phpbb_extension_manager
	 * @param \avathar\bbguild\model\admin\curl $curl
	 * @param \avathar\bbguild\model\admin\log $log
	 * @param string $phpbb_root_path
	 * @param string $phpEx
	 * @param string $bb_games_table
	 * @param string $bb_logs_table
	 * @param string $bb_ranks_table
	 * @param string $bb_guild_table
	 * @param string $bb_players_table
	 * @param string $bb_classes_table
	 * @param string $bb_races_table
	 * @param string $bb_gameroles_table
	 * @param string $bb_factions_table
	 * @param string $bb_language_table
	 * @param string $bb_motd_table
	 * @param string $bb_recruit_table
	 * @param string $bb_achievement_track_table
	 * @param string $bb_achievement_table
	 * @param string $bb_achievement_rewards_table
	 * @param string $bb_criteria_track_table
	 * @param string $bb_achievement_criteria_table
	 * @param string $bb_relations_table
	 * @param string $bb_bosstable
	 * @param string $bb_zonetable
	 * @param string $bb_news
	 * @param string $bb_plugins
	 */
	public function __construct(\phpbb\auth\auth $auth,
		\phpbb\cache\driver\driver_interface $cache,
		config $config,
		\phpbb\db\driver\driver_interface $db,
		language $language,
		log $log,
		pagination $pagination,
		request $request, template $template, user $user,
		\phpbb\path_helper $path_helper,
		\phpbb\extension\manager $phpbb_extension_manager,
		$phpbb_root_path,
		$adm_relative_path,
		$phpEx,
		\avathar\bbguild\model\admin\curl $curl,
		\avathar\bbguild\model\admin\log $bbguildlog,
		$bb_games_table,
		$bb_logs_table,
		$bb_ranks_table,
		$bb_guild_table,
		$bb_players_table,
		$bb_classes_table,
		$bb_races_table,
		$bb_gameroles_table,
		$bb_factions_table,
		$bb_language_table,
		$bb_motd_table,
		$bb_recruit_table,
		$bb_achievement_track_table,
		$bb_achievement_table,
		$bb_achievement_rewards_table,
		$bb_criteria_track_table,
		$bb_achievement_criteria_table,
		$bb_relations_table,
		$bb_bosstable,
		$bb_zonetable,
		$bb_news,
		$bb_plugins)
	{

		$this->bb_games_table = $bb_games_table;
		$this->bb_logs_table = $bb_logs_table;
		$this->bb_ranks_table = $bb_ranks_table;
		$this->bb_guild_table = $bb_guild_table;
		$this->bb_players_table = $bb_players_table;
		$this->bb_classes_table = $bb_classes_table;
		$this->bb_races_table = $bb_races_table;
		$this->bb_gameroles_table = $bb_gameroles_table;
		$this->bb_factions_table = $bb_factions_table;
		$this->bb_language_table = $bb_language_table;
		$this->bb_motd_table = $bb_motd_table;
		$this->bb_recruit_table = $bb_recruit_table;
		$this->bb_achievement_track_table = $bb_achievement_track_table;
		$this->bb_achievement_table = $bb_achievement_table;
		$this->bb_achievement_rewards_table = $bb_achievement_rewards_table;
		$this->bb_criteria_track_table = $bb_criteria_track_table;
		$this->bb_achievement_criteria_table = $bb_achievement_criteria_table;
		$this->bb_relations_table = $bb_relations_table;
		$this->bb_bosstable = $bb_bosstable;
		$this->bb_zonetable =  $bb_zonetable;
		$this->bb_news = $bb_news;
		$this->bb_plugins = $bb_plugins;

		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->language = $language;
		$this->log = $log;
		$this->log->set_log_table($this->bb_logs_table);
		$this->bbguildlog = $bbguildlog;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper  = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->ext_path     = $this->phpbb_extension_manager->get_extension_path('avathar/bbguild', true);
		$this->ext_path_web = $this->path_helper->get_web_root_path($this->ext_path);
		$this->root_path = $phpbb_root_path;
		$this->adm_relative_path = $adm_relative_path;
		$this->php_ext = $phpEx;
		$this->curl = $curl;

		$this->languagecodes = array(
			'de' => $this->language->lang('LANG_DE'),
			'en' => $this->language->lang('LANG_EN'),
			'fr' => $this->language->lang('LANG_FR'),
			'it' => $this->language->lang('LANG_IT'),
		);
	}

	/**
	 * request handler, called by ACP
	 *
	 * @return void
	 */
	public function handle()
	{

		if (! $this->auth->acl_get('a_bbguild'))
		{
			trigger_error($this->language->lang('NOAUTH_A_CONFIG_MAN'));
		}
		$this->u_action = $this->request->variable('action', '');

		switch ($this->request->variable('mode', ''))
		{
			case 'config':
				if ($this->request->is_set_post('submit'))
				{
					$this->update_config();
				}
				break;
			case 'logs':
				$log_id = $this->request->variable(constants::URI_LOG, 0);
				if ($log_id)
				{
					$this->u_action = 'view';
					$this->viewlog();
				}
		}

		//switch css trigger
		$this->template->assign_vars(
			array (
				'S_BBGUILD' => true,
			)
		);
	}

	/**
	 * display the acp frontpage
	 */
	public function DisplayPanel()
	{
		// get inactive players
		$sql = 'SELECT count(*) as player_count FROM ' . $this->bb_players_table . " WHERE player_status='0'";
		$result = $this->db->sql_query($sql);
		$total_players_inactive = (int) $this->db->sql_fetchfield('player_count');

		//get the active players
		$sql = 'SELECT count(*) as player_count FROM ' . $this->bb_players_table . " WHERE player_status='1'";
		$result = $this->db->sql_query($sql);
		$total_players_active = (int) $this->db->sql_fetchfield('player_count');

		// active player kpi
		$total_players = $total_players_active . ' / ' . $total_players_inactive;

		//number of guilds
		$sql = 'SELECT count(*) as guild_count FROM ' . $this->bb_guild_table;
		$result = $this->db->sql_query($sql);
		$total_guildcount = (int) $this->db->sql_fetchfield('guild_count');

		//start date
		$bbguild_started = '';
		if ($this->config['bbguild_eqdkp_start'] != 0)
		{
			$bbguild_started = date($this->config['bbguild_date_format'], $this->config['bbguild_eqdkp_start']);
		}

		//version check
		$ext_meta_manager = $this->phpbb_extension_manager->create_extension_metadata_manager('avathar/bbguild', $this->template);
		$meta_data  = $ext_meta_manager->get_metadata();
		$ext_version  = $meta_data['version'];

		$latest_version_info = $this->version_check($meta_data, $this->request->variable('versioncheck_force', false));
		if ($latest_version_info == false)
		{
			$this->template->assign_var('S_VERSIONCHECK_FAIL', true);
		}
		else
		{
			if (phpbb_version_compare($latest_version_info, $this->config['bbguild_version'], '='))
			{
				$this->template->assign_vars(
					array(
						'S_VERSION_UP_TO_DATE'    => true,
					)
				);
			}
			else if (phpbb_version_compare($latest_version_info, $this->config['bbguild_version'] , '>'))
			{
				// you have an old version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->language->lang('NOT_UP_TO_DATE_TITLE'), 'bbGuild'),
						'S_PRERELEASE'    => false,
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'BBGUILDVERSION' => $this->language->lang('BBGUILD_YOURVERSION') . $this->config['bbguild_version']  ,
						'UPDATEINSTR' => $this->language->lang('BBGUILD_LATESTVERSION') . $latest_version_info . ', <a href="' .
							$this->language->lang('WEBURL') . '">' . $this->language->lang('DOWNLOAD') . '</a>')
				);

			}
			else if (phpbb_version_compare($latest_version_info, $this->config['bbguild_version'] , '<'))
			{
				// you have a prerelease or development version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->language->lang('PRELELEASE_TITLE'), 'bbGuild'),
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'S_PRERELEASE'    => true,
						'BBGUILDVERSION' => $this->language->lang('BBGUILD_YOURVERSION') . $this->config['bbguild_version']  ,
						'UPDATEINSTR' => $this->language->lang('BBGUILD_LATESTVERSION') . $latest_version_info . ', <a href="' . $this->language->lang('WEBURL') . '">' . $this->language->lang('DOWNLOAD') . '</a>')
				);
			}
		}

		// read verbose log
		$listlogs = $this->bbguildlog->read_log('', false, true, '', '');
		if (isset($listlogs))
		{
			foreach ($listlogs as $key => $log)
			{
				$this->template->assign_block_vars(
					'actions_row', array(
						'U_VIEW_LOG'     => append_sid("{$this->adm_relative_path}index.$this->php_ext", 'i=-avathar-bbguild-acp-main_module&amp;mode=logs&amp;' . constants::URI_LOG . '=' . $log['log_id']) ,
						'LOGDATE'         => $log['datestamp'],
						'ACTION'         => $log['log_line'],
					)
				);
			}
		}

		$listgames = new \avathar\bbguild\model\games\game($this->bb_classes_table, $this->bb_races_table, $this->bb_language_table, $this->bb_factions_table, $this->bb_games_table );
		$games = $listgames->games;
		unset($listgames);


		$this->template->assign_vars(
			array(
				'U_ACTION' =>  'updateconfig',
				'GLYPH' => $this->ext_path . 'adm/images/glyphs/view.gif',
				'NUMBER_OF_PLAYERS' => $total_players ,
				'NUMBER_OF_GUILDS' => $total_guildcount ,
				'BBGUILD_STARTED' => $bbguild_started,
				'BBGUILD_VERSION'    => $this->config['bbguild_version'] ,
				'U_VERSIONCHECK_FORCE' => append_sid($this->u_action . '&amp;versioncheck_force=1'),
				'GAMES_INSTALLED' => count($games) > 0 ? implode(', ', $games) : $this->user->lang['NA'],
			)
		);


	}


	/**
	 * display current configuration
	 */
	public function display_config()
	{

		$s_lang_options = '';
		foreach ($this->languagecodes as $lang => $langname)
		{
			$selected = ($this->config['bbguild_lang'] == $lang) ? ' selected="selected"' : '';
			$s_lang_options .= '<option value="' . $lang . '" ' . $selected . '> ' . $langname . '</option>';
		}

		$this->template->assign_block_vars(
			'hide_row', array(
				'VALUE' => 'YES',
				'SELECTED' => ($this->config['bbguild_hide_inactive'] == 1) ? ' selected="selected"' : '' ,
				'OPTION' => 'YES')
		);

		$this->template->assign_block_vars(
			'hide_row', array(
				'VALUE' => 'NO',
				'SELECTED' => ($this->config['bbguild_hide_inactive'] == 0) ? ' selected="selected"' : '' ,
				'OPTION' => 'NO')
		);

		//roster layout switch hetween grid and class list
		$rosterlayoutlist = array(
			0 =>  $this->language->lang('ARM_STAND') ,
			1 =>  $this->language->lang('ARM_CLASS')
		);

		foreach ($rosterlayoutlist as $lid => $lname)
		{
			$this->template->assign_block_vars(
				'rosterlayout_row', array(
					'VALUE' => $lid ,
					'SELECTED' => ($lid == $this->config['bbguild_roster_layout']) ? ' selected="selected"' : '' ,
					'OPTION' => $lname)
			);
		}

		// get welcome msg
		$welcometext = $uid = $bitfield = '';
		$sql = 'SELECT motd_msg, bbcode_bitfield, bbcode_uid FROM ' . $this->bb_motd_table;
		$this->db->sql_query($sql);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$welcometext = $row['motd_msg'];
			$bitfield = $row['bbcode_bitfield'];
			$uid = $row['bbcode_uid'];
		}

		$textarr = generate_text_for_edit($welcometext, $uid, $bitfield);
		// number of news and items to show on front page
		$n_news  = $this->config['bbguild_n_news'];
		$n_items = $this->config['bbguild_n_items'];

		$this->template->assign_vars(
			array(
				'EQDKP_START_DD' => date('d', $this->config['bbguild_eqdkp_start']) ,
				'EQDKP_START_MM' => date('m', $this->config['bbguild_eqdkp_start']) ,
				'EQDKP_START_YY' => date('Y', $this->config['bbguild_eqdkp_start']) ,
				'DATE_FORMAT'   => $this->config['bbguild_date_format'] ,
				'S_LANG_OPTIONS' => $s_lang_options,
				'USER_LLIMIT' => $this->config['bbguild_user_llimit'] ,
				'MAXCHARS' => $this->config['bbguild_maxchars'] ,
				'MINLEVEL' => $this->config['bbguild_minrosterlvl'],
				'F_SHOWACHIEV' => $this->config['bbguild_show_achiev'] ,
				'HIDE_INACTIVE_YES_CHECKED' => ($this->config['bbguild_hide_inactive'] == '1') ? ' checked="checked"' : '' ,
				'HIDE_INACTIVE_NO_CHECKED' => ($this->config['bbguild_hide_inactive'] == '0') ? ' checked="checked"' : '' ,
				'SHOW_WELCOME_YES_CHECKED' => ($this->config['bbguild_motd'] == '1') ? 'checked="checked"' : '' ,
				'SHOW_WELCOME_NO_CHECKED' => ($this->config['bbguild_motd'] == '0') ? 'checked="checked"' : '' ,
				'WELCOME_MESSAGE' => $textarr['text'] ,
				'USER_NLIMIT' => $this->config['bbguild_user_nlimit'] ,
				'U_ACTION' => $this->u_action,
			)
		);

		// is gameworld extension installed ?
		if (isset($config['bbguild_gameworld_version']))
		{
			$this->template->assign_vars(
				array(
					'S_BP_SHOW' => true ,
					'SHOW_BOSS_YES_CHECKED' => ($this->config['bbguild_portal_bossprogress'] == '1') ? ' checked="checked"' : '' ,
					'SHOW_BOSS_NO_CHECKED' => ($this->config['bbguild_portal_bossprogress'] == '0') ? ' checked="checked"' : '')
			);
		}
		else
		{
			$this->template->assign_var('S_BP_SHOW', false);
		}

		add_form_key('acp_bbguild');
		$this->page_title = 'ACP_BBGUILD_CONFIG';
	}

	/**
	 *
	 * save bbguild config
	 */
	public function update_config()
	{
		if (! check_form_key('acp_bbguild'))
		{
			trigger_error($this->lang->lang('FV_FORMVALIDATION'), E_USER_WARNING);
		}

		$day = $this->request->variable('bbguild_start_dd', 0);
		$month = $this->request->variable('bbguild_start_mm', 0);
		$year = $this->request->variable('bbguild_start_yy', 0);
		$bbguild_start = mktime(0, 0, 0, $month, $day, $year);
		$this->config->set('bbguild_eqdkp_start', $bbguild_start, true);
		$this->config->set('bbguild_date_format', $this->request->variable('date_format', ''), true);
		$this->config->set('bbguild_lang', $this->request->variable('language', 'en'), true);
		$this->config->set('bbguild_user_nlimit', $this->request->variable('bbguild_user_nlimit', 0), true);
		$this->config->set('bbguild_user_llimit', $this->request->variable('bbguild_user_llimit', 0), true);
		$this->config->set('bbguild_maxchars', $this->request->variable('bbguild_maxchars', 2), true);
		$this->config->set('bbguild_minrosterlvl', $this->request->variable('bbguild_minrosterlvl', 0), true);
		$this->config->set('bbguild_roster_layout', $this->request->variable('bbguild_roster_layout', 0), true);
		$this->config->set('bbguild_show_achiev', $this->request->variable('bbguild_show_achiev', 0), true);
		$this->config->set('bbguild_hide_inactive', $this->request->variable('bbguild_hide_inactive', 0), true);
		$this->config->set('bbguild_motd', $this->request->variable('show_motd_block', 0), true);
		$welcometext = $this->request->variable('message_of_the_day', '', true);
		$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($welcometext, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

		$sql = 'UPDATE ' . $this->bb_motd_table . " SET
						motd_msg = '" . (string) $this->db->sql_escape($welcometext) . "' ,
						motd_timestamp = " . (int) time() . " ,
						bbcode_bitfield = 	'" . (string) $bitfield . "' ,
						bbcode_uid = 		'" . (string) $uid . "'
						WHERE motd_id = 1";
		$this->db->sql_query($sql);

		//if the gameworld extension is installed
		if (isset($this->config['bbguild_gameworld_version']))
		{
			$this->config->set('bbguild_portal_bossprogress', $this->request->variable('show_bosspblock', 0), true);
		}

		// Purge config cache
		$this->cache->destroy('config');

		//
		// Logging
		//
		$log_action = array(
			'header' => 'L_ACTION_SETTINGS_CHANGED'     ,
			'L_SETTINGS' => $this->language->lang('ACTION_SETTINGS_CHANGED'),
		);

		$this->bbguildlog->log_insert(
			array(
				'log_type' =>  'L_ACTION_SETTINGS_CHANGED',
				'log_action' => $log_action)
		);
		trigger_error($this->language->lang('ACTION_SETTINGS_CHANGED') , E_USER_NOTICE);

	}


	/**
	 * retrieve latest version
	 *
	 * @param  bool $force_update Ignores cached data. Defaults to false.
	 * @param  int  $ttl          Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
	 * @return bool
	 */
	public final function version_check($meta_data, $force_update = false, $ttl = 86400)
	{
		$pemfile = '';
		$versionurl = ($meta_data['extra']['version-check']['ssl'] == '1' ? 'https://': 'http://') .
			$meta_data['extra']['version-check']['host'].$meta_data['extra']['version-check']['directory'].'/'.$meta_data['extra']['version-check']['filename'];
		$ssl = $meta_data['extra']['version-check']['ssl'] == '1' ? true: false;
		if ($ssl)
		{
			//https://davidwalsh.name/php-ssl-curl-error
			$pemfile = $this->phpbb_extension_manager->get_extension_path('avathar/bbguild', true) . 'controller/mozilla.pem';
			if (!(file_exists($pemfile) && is_readable($pemfile)))
			{
				$ssl = false;
			}
		}

		//get latest productversion from cache
		$latest_version = $this->cache->get('bbguild_version_latest');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest_version === false || $force_update)
		{
			$data = $this->curl->curl($versionurl, $pemfile, $ssl, false, false, false);
			if (0 === count($data) )
			{
				$this->cache->destroy('bbguild_version_latest');
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$latest_version = $latest_version['stable']['3.2']['current'];

			//put this info in the cache
			$this->cache->put('bbguild_version_latest', $latest_version, $ttl);

		}

		return $latest_version;
	}

	/**
	 * Set u_action
	 *
	 * @param string $u_action Custom form action
	 * @return main_controller
	 */
	public function set_u_action($u_action)
	{
		$this->u_action = $u_action;
		return $this;
	}


}
