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
	/** @var string phpBB root path */
	protected $root_path;
	/** @var string PHP extension */
	protected $php_ext;
	/** @var string Form key used for form validation */
	protected $form_key;
	/** @var string Custom form action */
	protected $u_action;

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
		\phpbb\cache\driver\driver_interface $cache, config $config, \phpbb\db\driver\driver_interface $db, language $language, log $log, pagination $pagination, request $request, template $template, user $user, $phpbb_root_path, $phpEx,
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
		$this->log = $log;
		$this->log->set_log_table($this->bb_logs_table);
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
	}

	/**
	 * Main handler, called by the ACP module
	 *
	 * @return void
	 */
	public function main()
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
		$sql = 'SELECT count(*) as guild_count  FROM ' . $this->bb_guild_table;
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
			if (phpbb_version_compare($latest_version_info, BBGUILD_VERSION, '='))
			{
				$this->template->assign_vars(
					array(
						'S_VERSION_UP_TO_DATE'    => true,
					)
				);
			}
			else if (phpbb_version_compare($latest_version_info, BBGUILD_VERSION, '>'))
			{
				// you have an old version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->user->lang['NOT_UP_TO_DATE_TITLE'], 'bbGuild'),
						'S_PRERELEASE'    => false,
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'BBGUILDVERSION' => $this->user->lang['BBGUILD_YOURVERSION'] . BBGUILD_VERSION ,
						'UPDATEINSTR' => $this->user->lang['BBGUILD_LATESTVERSION'] . $latest_version_info . ', <a href="' . $this->user->lang['WEBURL'] . '">' . $this->user->lang['DOWNLOAD'] . '</a>')
				);

			}
			else
			{
				// you have a prerelease or development version
				$this->template->assign_vars(
					array(
						'BBGUILD_NOT_UP_TO_DATE_TITLE' => sprintf($this->user->lang['PRELELEASE_TITLE'], 'bbGuild'),
						'BBGUILD_LATESTVERSION' => $latest_version_info,
						'S_PRERELEASE'    => true,
						'BBGUILDVERSION' => $this->user->lang['BBGUILD_YOURVERSION'] . BBGUILD_VERSION ,
						'UPDATEINSTR' => $this->user->lang['BBGUILD_LATESTVERSION'] . $latest_version_info . ', <a href="' . $this->user->lang['WEBURL'] . '">' . $this->user->lang['DOWNLOAD'] . '</a>')
				);
			}
		}

		// read verbose log
		$logs = log::Instance();
		$listlogs = $logs->read_log('', false, true, '', '');
		if (isset($listlogs))
		{
			foreach ($listlogs as $key => $log)
			{
				$this->template->assign_block_vars(
					'actions_row', array(
						'U_VIEW_LOG'     => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-avathar-bbguild-acp-main_module&amp;mode=logs&amp;' . URI_LOG . '=' . $log['log_id']) ,
						'LOGDATE'         => $log['datestamp'],
						'ACTION'         => $log['log_line'],
					)
				);
			}
		}

		//LOOP PLUGINS TABLE
		$plugins_installed = 0;
		$plugin_versioninfo = (array) parent::get_plugin_info($this->request->variable('versioncheck_force', false));
		foreach ($plugin_versioninfo as $pname => $pdetails)
		{
			$a = phpbb_version_compare(trim($pdetails['latest']), $pdetails['version'], '<=');
			$this->template->assign_block_vars(
				'plugin_row', array(
					'PLUGINNAME'     => ucwords($pdetails['name']) ,
					'VERSION'         => $pdetails['version'] ,
					'ISUPTODATE'    => phpbb_version_compare(trim($pdetails['latest']), $pdetails['version'], '<=') ,
					'LATESTVERSION' => $pdetails['latest'] ,
					'UPDATEINSTR'     => '<a href="' . BBDKP_PLUGINURL . '">' . $this->user->lang['DOWNLOAD_LATEST_PLUGINS'] . $pdetails['latest'] . '</a>',
					'INSTALLDATE'     => $pdetails['installdate'],
				)
			);
			$plugins_installed +=1;
		}

		$this->template->assign_vars(
			array(
				'GLYPH' => $this->ext_path . 'adm/images/glyphs/view.gif',
				'NUMBER_OF_PLAYERS' => $total_players ,
				'NUMBER_OF_GUILDS' => $total_guildcount ,
				'BBGUILD_STARTED' => $bbguild_started,
				'BBGUILD_VERSION'    => BBGUILD_VERSION,
				'U_VERSIONCHECK_FORCE' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-avathar-bbguild-acp-main_module&amp;mode=panel&amp;versioncheck_force=1'),
				'GAMES_INSTALLED' => count($this->games) > 0 ? implode(', ', $this->games) : $this->user->lang['NA'],
				'PLUGINS_INSTALLED' => $plugins_installed,
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
		global $user, $cache;

		//get latest productversion from cache
		$latest_version_a = $cache->get('latest_bbguild');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest_version_a === false || $force_update)
		{
			$data = $this->curl(constants::BBGUILD_VERSIONURL . 'bbguild.json', false, false, false);
			if (0 === count($data) )
			{
				$cache->destroy('latest_bbguild');
				if ($warn_fail)
				{
					trigger_error($user->lang['VERSION_NOTONLINE'], E_USER_WARNING);
				}
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$latest_version_a = $latest_version['stable']['2.0']['current'];

			//put this info in the cache
			$cache->put('latest_bbguild', $latest_version_a, $ttl);
		}

		return $latest_version_a;
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
		global  $phpbb_extension_manager, $path_helper;

		$pemfile = '';
		$versionurl = ($meta_data['extra']['version-check']['ssl'] == '1' ? 'https://': 'http://') .
			$meta_data['extra']['version-check']['host'].$meta_data['extra']['version-check']['directory'].'/'.$meta_data['extra']['version-check']['filename'];
		$ssl = $meta_data['extra']['version-check']['ssl'] == '1' ? true: false;
		if ($ssl)
		{
			//https://davidwalsh.name/php-ssl-curl-error
			$pemfile = $phpbb_extension_manager->get_extension_path('avathar/bbguild', true) . 'controller/mozilla.pem';
			if (!(file_exists($pemfile) && is_readable($pemfile)))
			{
				$ssl = false;
			}
		}

		//get latest productversion from cache
		$latest_version = $this->cache->get('bbg_versioncheck');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest_version === false || $force_update)
		{
			$data = parent::curl($versionurl, $pemfile, $ssl, false, false, false);
			if (0 === count($data) )
			{
				$cache->destroy('recenttopics_versioncheck');
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$latest_version = $latest_version['stable']['3.2']['current'];

			//put this info in the cache
			$cache->put('recenttopics_versioncheck', $latest_version, $ttl);

		}

		return $latest_version;
	}


	/**
	 * Add a prefix
	 *
	 * @return void
	 */
	public function add_prefix()
	{
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($this->form_key))
			{
				$this->trigger_message('FORM_INVALID', E_USER_WARNING);
			}

			$tag = $this->request->variable('prefix_tag', '', true);
			$prefix = $this->manager->add_prefix($tag, $this->forum_id);

			$this->log($prefix['prefix_tag'], 'ACP_LOG_PREFIX_ADDED');
		}
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
	 */
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

}
