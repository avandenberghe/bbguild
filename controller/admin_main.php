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
	}

	/**
	 * Main handler, called by the ACP module
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->form_key = 'avathar/bbguild';
		add_form_key($this->form_key);

		if (! $this->auth->acl_get('a_bbguild'))
		{
			trigger_error($this->language->lang('NOAUTH_A_CONFIG_MAN'));
		}

		//css trigger
		$this->template->assign_vars(
			array (
				'S_BBGUILD' => true,
			)
		);
	}

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

		$latest_version_info = $this->productversion($this->request->variable('versioncheck_force', false));
		if ($latest_version_info === false)
		{
			$this->template->assign_var('S_VERSIONCHECK_FAIL', true);
		}
		else
		{
			if (phpbb_version_compare($latest_version_info, constants::BBGUILD_VERSION, '='))
			{
				$this->template->assign_vars(
					array(
						'S_VERSION_UP_TO_DATE'    => true,
					)
				);
			}
			else if (phpbb_version_compare($latest_version_info, constants::BBGUILD_VERSION, '>'))
			{
				// you have an old version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->language->lang('NOT_UP_TO_DATE_TITLE'), 'bbGuild'),
						'S_PRERELEASE'    => false,
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'BBGUILDVERSION' => $this->language->lang('BBGUILD_YOURVERSION') . constants::BBGUILD_VERSION ,
						'UPDATEINSTR' => $this->language->lang('BBGUILD_LATESTVERSION') . $latest_version_info . ', <a href="' .
							$this->language->lang('WEBURL') . '">' . $this->language->lang('DOWNLOAD') . '</a>')
				);

			}
			else
			{
				// you have a prerelease or development version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->language->lang('PRELELEASE_TITLE'), 'bbGuild'),
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'S_PRERELEASE'    => true,
						'BBGUILDVERSION' => $this->language->lang('BBGUILD_YOURVERSION') . constants::BBGUILD_VERSION ,
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
				'GLYPH' => $this->ext_path . 'adm/images/glyphs/view.gif',
				'NUMBER_OF_PLAYERS' => $total_players ,
				'NUMBER_OF_GUILDS' => $total_guildcount ,
				'BBGUILD_STARTED' => $bbguild_started,
				'BBGUILD_VERSION'    => constants::BBGUILD_VERSION,
				'U_VERSIONCHECK_FORCE' => append_sid("{$this->adm_relative_path}index.$this->php_ext", 'i=-avathar-bbguild-acp-main_module&amp;mode=panel&amp;versioncheck_force=1'),
				'GAMES_INSTALLED' => count($games) > 0 ? implode(', ', $games) : $this->user->lang['NA'],
			)
		);


	}

	/**
	 *
	 * @param  bool $force_update Ignores cached data. Defaults to false.
	 * @param  bool $warn_fail    Trigger a warning if obtaining the latest version information fails. Defaults to false.
	 * @param  int  $ttl          Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
	 * @return bool
	 */
	public function productversion($force_update = false, $warn_fail = false, $ttl = 86400)
	{
		global $cache;

		//get latest productversion from cache
		$version = $cache->get('latest_bbguild');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($version === false || $force_update)
		{
			$data = $this->curl->curl(constants::BBGUILD_VERSIONURL . 'bbguild.json', false, false, false);
			if (0 === count($data) )
			{
				$cache->destroy('latest_bbguild');
				if ($warn_fail)
				{
					trigger_error($this->language->lang('VERSION_NOTONLINE'), E_USER_WARNING);
				}
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$version = $latest_version['stable']['2.0']['current'];

			//put this info in the cache
			$cache->put('latest_bbguild', $version, $ttl);
		}

		return $version;
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
		$latest_version = $this->cache->get('bbguild_versioncheck');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest_version === false || $force_update)
		{
			$data = $this->curl->curl($versionurl, $pemfile, $ssl, false, false, false);
			if (0 === count($data) )
			{
				$this->cache->destroy('bbguild_versioncheck');
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$latest_version = $latest_version['stable']['3.2']['current'];

			//put this info in the cache
			$this->cache->put('bbguild_versioncheck', $latest_version, $ttl);

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

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return void
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	 * Logging helper
	 *
	 * @param string $tag     The topic prefix tag
	 * @param string $message The log action language key
	 * @return void
	protected function log($tag, $message)
	{

		$this->log->add(
			'admin',
			$this->user->data['user_id'],
			$this->user->ip,
			$message,
			time(),
			[$tag, $forum_data['forum_name']]
		);
	}
	 */

}
