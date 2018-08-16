<?php
/**
 * @package bbguild
 * @copyright 2018 avathar.be
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\controller;

use avathar\bbguild\views\viewnavigation;

/**
 * Class view_controller
 *
* @package avathar\bbguild\controller
 */
class view_controller
{
	/**
	 * @var \phpbb\auth\auth
	 */
	protected $auth;
	/**
	 * @var \phpbb\config\config
	 */
	protected $config;
	/**
	 * @var \phpbb\controller\helper
	 */
	protected $helper;
	/**
	 * @var \phpbb\template\template
	 */
	protected $template;
	/**
	 * @var \phpbb\db\driver\driver_interface
	 */
	protected $db;
	/**
	 * @var \phpbb\request\request
	 */
	protected $request;
	/**
	 * @var \phpbb\user
	 */
	protected $user;
	/**
	 * @var string
	 */
	protected $phpEx;
	/**
	 * @var \phpbb\pagination
	 */
	protected $pagination;
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
	/**
	 * @var string
	 */
	protected $ext_path_images;
	/**
	 * @var string
	 */
	protected $root_path;

	/**
	 * view_controller constructor.
	 *
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\controller\helper          $helper
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\request\request            $request
	 * @param \phpbb\user                       $user
	 * @param \phpbb\pagination                 $pagination
	 * @param $php_ext
	 * @param \phpbb\path_helper                $path_helper
	 * @param \phpbb\extension\manager          $phpbb_extension_manager
	 * @param $root_path
	 * @param  string           $bb_games_table	name of game table
	 * @param  string           $bb_logs_table	name of logging table
	 * @param  string           $bb_ranks_table	name of ranks table
	 * @param  string           $bb_guild_table	name of guild table
	 * @param  string           $bb_players_table	name of players table
	 * @param  string           $bb_classes_table	name of classes table
	 * @param  string           $bb_gameroles_table	name of roles table
	 * @param  string           $bb_factions_table	name of factions table
	 * @param  string           $bb_language_table	name of language table
	 * @param  string           $bb_motd_table	name of motd table
	 * @param  string           $recruit_table	name of recruit table
	 * @param  string           $bb_achievement_track_table	name of achievement track table
	 * @param  string           $bb_achievement_table	name of achievement table
	 * @param  string           $bb_achievement_rewards_table	name of achievement rewards table
	 * @param  string           $bb_criteria_track_table	name of achievement criteria track table
	 * @param  string           $bb_achievement_criteria_table	name of achievement criteria table
	 * @param  string           $bb_relations_table 	name of relations table
	 * @param  string           $bb_bosstable	name of boss table
	 * @param  string           $bb_zonetable	name of zone table
	 * @param  string           $bb_news	name of news table
	 * @param  string           $bb_plugins	name of plugin table
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\pagination $pagination,
		$php_ext,
		\phpbb\path_helper $path_helper,
		\phpbb\extension\manager $phpbb_extension_manager,
		$root_path,
		$bb_games_table,
		$bb_logs_table,
		$bb_ranks_table,
		$bb_guild_table,
		$bb_players_table,
		$bb_classes_table,
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
		$bb_plugins
	)
	{

		$this->auth         = $auth;
		$this->config         = $config;
		$this->helper         = $helper;
		$this->template     = $template;
		$this->db           = $db;
		$this->request      = $request;
		$this->user         = $user;
		$this->pagination     = $pagination;
		$this->php_ext         = $php_ext;
		$this->path_helper    = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->ext_path            = $this->phpbb_extension_manager->get_extension_path('avathar/bbguild', true);
		$this->ext_path_web        = $this->path_helper->get_web_root_path($this->ext_path);
		$this->ext_path_images    = $this->ext_path_web . 'ext/avathar/bbguild/images/';
		$this->root_path  = $root_path;
		$this->bb_games_table = $bb_games_table;
		$this->bb_logs_table = $bb_logs_table;
		$this->bb_ranks_table = $bb_ranks_table;
		$this->bb_guild_table = $bb_guild_table;
		$this->bb_players_table = $bb_players_table;
		$this->bb_classes_table = $bb_classes_table;
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
	}

	private $valid_views = array('roster', 'welcome', 'achievements');
	//private $valid_views = array('news', 'roster', 'standings', 'welcome', 'stats', 'player', 'raids');

	/**
	 * View factory
	 *
	 * @param  $guild_id
	 * @param  $page
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handleguild($guild_id, $page)
	{
		if (in_array($page, $this->valid_views))
		{
			$Navigation = new viewnavigation(
				$page, $this->request, $this->user,
				$this->template, $this->db, $this->config, $this->helper, $this->pagination, $this->ext_path,
				$this->ext_path_web, $this->ext_path_images, $this->root_path, $guild_id
			);
			$viewtype = "\\bbdkp\\bbguild\\views\\view". $page;
			$view = new $viewtype($Navigation);
			$response = $view->response;
			return $response;
		}
		else
		{
			if (isset($this->user->lang['NOVIEW']))
			{
				trigger_error(sprintf($this->user->lang['NOVIEW'], $page));
			}
		}

	}

}
