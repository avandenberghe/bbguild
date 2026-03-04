<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * bbGuild Mainpage ACP
 *
 */

namespace avathar\bbguild\controller;

use phpbb\config\config;
use phpbb\language\language;
use phpbb\log\log;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\event\dispatcher_interface;
use phpbb\user;

use avathar\bbguild\model\admin\constants;
use avathar\bbguild\model\games\game;
use avathar\bbguild\model\games\game_registry;
use avathar\bbguild\model\games\rpg\classes;
use avathar\bbguild\model\games\rpg\faction;
use avathar\bbguild\model\games\rpg\races;
use avathar\bbguild\model\games\rpg\roles;

use avathar\bbguild\model\admin;

/**
 * Class admin_controller
 */
class admin_games
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

	/* @var \avathar\bbguild\model\admin\util utility class */
	public $util;

	/** @var game_registry */
	protected $game_registry;

	/** @var dispatcher_interface */
	protected $dispatcher;

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
	public $bb_bosstable;
	public $bb_zonetable;
	public $bb_news;

	/**
	 * partly installed games
	 *
	 * @var string
	 */
	private $gamelist;

	/** @var \phpbb\path_helper */
	protected $path_helper;
	public $link;
	public $page_title;



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
	 * @param \avathar\bbguild\model\admin\util $util
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
	 * @param string $bb_bosstable
	 * @param string $bb_zonetable
	 * @param string $bb_news
	 */
	public function __construct(\phpbb\auth\auth $auth,
		\phpbb\cache\driver\driver_interface $cache,
		config $config,
		\phpbb\db\driver\driver_interface $db,
		language $language,
		log $log,
		pagination $pagination,
		request $request,
		template $template,
		user $user,
		\phpbb\path_helper $path_helper,
		\phpbb\extension\manager $phpbb_extension_manager,
		$phpbb_root_path,
		$adm_relative_path,
		$phpEx,
		\avathar\bbguild\model\admin\curl $curl,
		\avathar\bbguild\model\admin\log $bbguildlog,
		\avathar\bbguild\model\admin\util $util,
		game_registry $game_registry,
		dispatcher_interface $dispatcher,
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
		$bb_bosstable,
		$bb_zonetable,
		$bb_news)
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
		$this->bb_bosstable = $bb_bosstable;
		$this->bb_zonetable =  $bb_zonetable;
		$this->bb_news = $bb_news;

		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->language = $language;
		$this->log = $log;
		$this->log->set_log_table($this->bb_logs_table);
		$this->bbguildlog = $bbguildlog;
		$this->util = $util;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper  = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->ext_path     = $this->phpbb_extension_manager->get_extension_path('avathar/bbguild', true);
		$this->ext_path_web = $this->path_helper->get_web_root_path($this->ext_path);
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
		$this->curl = $curl;
		$this->game_registry = $game_registry;
		$this->dispatcher = $dispatcher;

		$this->languagecodes = array(
			'de' => $this->language->lang('LANG_DE'),
			'en' => $this->language->lang('LANG_EN'),
			'fr' => $this->language->lang('LANG_FR'),
			'it' => $this->language->lang('LANG_IT'),
		);



		//get number of games
		$listgames = new \avathar\bbguild\model\games\game($this->bb_classes_table, $this->bb_races_table, $this->bb_language_table, $this->bb_factions_table, $this->bb_games_table );
		//list installed games

		$sort_order = array(
			0 => array(    'id' , 'id desc') ,
			1 => array('game_id' , 'game_id desc') ,
			2 => array('game_name' , 'game_name desc'));

		$current_order = $util->switch_order($sort_order);

		$this->gamelist = $listgames->list_games($current_order['sql']);

		$installed = array();
		foreach ($this->gamelist as $game)
		{
			$installed[$game['game_id']] = $game['name'];
		}


	}

	/**
	 * request handler, called by ACP
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->u_action = $this->request->variable('action', '');

		switch ($this->request->variable('mode', ''))
		{
			case 'config':
				if ($this->request->is_set_post('submit'))
				{
					$this->update_config();
				}
				break;
		}

		//switch css trigger
		$this->template->assign_vars(
			array (
				'S_BBGUILD' => true,
			)
		);

	}

	/**
	 * list games
	 */
	public function listgames()
	{
		$link = '<br /><a href="' . append_sid("index.$this->php_ext", 'i=-avathar-bbguild-acp-game_module&amp;mode=listgames') . '"><h3>' . $this->language->lang('RETURN_GAMELIST') . '</h3></a>';
		//fetch installed games
		$games = new game($this->bb_classes_table, $this->bb_races_table, $this->bb_language_table, $this->bb_factions_table, $this->bb_games_table);

		$sort_order = array(
			0 => array(    'id' , 'id desc') ,
			1 => array('game_id' , 'game_id desc') ,
			2 => array('game_name' , 'game_name desc'));

		$current_order = $this->util->switch_order($sort_order);

		$sort_index = explode('.', $current_order['uri']['current']);
		$this->gamelist = $games->list_games($current_order['sql']);

		$installed = array();
		foreach ($this->gamelist as $game)
		{
			$installed[$game['game_id']] = $game['name'];
		}

	}


	/***
	 *
	 *
	 */
	public function gamelist()
	{
		$editgame = new game;
		$editgame->game_id = $this->request->variable(constants::URI_GAME, $this->request->variable('hidden_game_id', ''));
		$editgame->get_game();

		$this->link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-avathar-bbguild-acp-game_module&amp;mode=editgames&amp;' . constants::URI_GAME ."={$editgame->game_id}") . '"><h3>' . $this->user->lang['RETURN_GAMEVIEW'] . '</h3></a>';
		$gamereset = $this->request->is_set_post('gamereset');
		$gamedelete = $this->request->is_set_post('gamedelete');
		$gamesettings = $this->request->is_set_post('gamesettings');
		$addrole = $this->request->is_set_post('showrolesadd');
		$action = $this->request->variable('action', '');
		$addfaction = $this->request->is_set_post('showfactionadd');
		$addrace = $this->request->is_set_post('showraceadd');
		$raceedit = (isset($_GET['raceedit'])) ? true : false;
		$racedelete = (isset($_GET['racedelete'])) ? true : false;
		$addclass = $this->request->is_set_post('showclassadd');
		$classedit = (isset($_GET['classedit'])) ? true : false;
		$classdelete = (isset($_GET['classdelete'])) ? true : false;

		if ($gamereset)
		{
			$this->ResetGame($editgame);
		}
		else if ($gamesettings)
		{
			$editgame = $this->SaveGameSettings();
			$success_message = sprintf($this->user->lang['ADMIN_UPDATED_GAME_SUCCESS'], $editgame->game_id, $editgame->getName());
			meta_refresh(0.5, append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-avathar-bbguild-acp-game_module&amp;mode=editgames&amp;' . constants::URI_GAME . "={$editgame->game_id}"));
			trigger_error($success_message . $this->link, E_USER_NOTICE);
		}
		else if ($gamedelete)
		{
			$this->DeleteGame($editgame);
		}
		else if ($addrole)
		{
			$this->BuildTemplateRole($editgame);
		}
		else if ($action=='deleterole')
		{
			$this->DeleteRole($editgame);
		}
		else if ($action=='editrole')
		{
			$this->BuildTemplateRole($editgame);
		}
		else if ($addfaction)
		{
			$this->BuildTemplateFaction($editgame);
		}
		else if ($action=='deletefaction')
		{
			$this->DeleteFaction($editgame);
		}
		else if ($action=='editfaction')
		{
			$this->BuildTemplateFaction($editgame);
		}
		else if ($raceedit)
		{
			$this->BuildTemplateEditRace($editgame);
		}
		else if ($addrace)
		{
			$this->BuildTemplateAddRace($editgame);
		}
		else if ($racedelete)
		{
			$this->DeleteRace($editgame);
		}
		else if ($classedit)
		{
			$this->BuildTemplateEditClass($editgame);
		}
		else if ($addclass)
		{
			$this->BuildTemplateAddClass($editgame);
		}
		else if ($classdelete)
		{
			$this->DeleteClass($editgame);
		}

		$this->showgame($editgame);
		$this->page_title = 'ACP_ADDGAME';

	}

	/**
	 *
	 */
	public function gamerole()
	{

	}

	/**
	 *
	 */
	public function gamefaction()
	{

	}

	/**
	 *
	 */
	public function gamerace()
	{

	}

	/**
	 *
	 */
	public function gameclass()
	{

	}

	/**
	 * Save game settings from the edit game form.
	 *
	 * @return game The updated game object
	 */
	private function SaveGameSettings()
	{
		$editgame = new game(
			$this->bb_classes_table, $this->bb_races_table,
			$this->bb_language_table, $this->bb_factions_table,
			$this->bb_games_table, $this->game_registry
		);
		$editgame->game_id = $this->request->variable(constants::URI_GAME, $this->request->variable('hidden_game_id', ''));
		$editgame->get_game();

		$editgame->setName($this->request->variable('game_name', '', true));
		$editgame->setImagename($this->request->variable('imagename', '', true));
		$editgame->setBossbaseurl($this->request->variable('bossbaseurl', '', true));
		$editgame->setZonebaseurl($this->request->variable('zonebaseurl', '', true));

		// Check if this game has API support via the game registry
		$game_id = $editgame->game_id;
		$provider = $this->game_registry->get($game_id);
		$has_api = ($provider !== null && $provider->has_api());

		if ($has_api)
		{
			$editgame->setArmoryEnabled($this->request->variable('enable_armory', 0));
		}

		/**
		 * Event dispatched when game settings are submitted.
		 * Allows game plugins to read their own form values and set them on the game object.
		 *
		 * @event avathar.bbguild.acp_editgames_submit
		 * @var game   editgame  The game object being saved
		 * @var string game_id   The game identifier
		 * @var bool   has_api   Whether this game has API support
		 */
		$vars = array('editgame', 'game_id', 'has_api');
		extract($this->dispatcher->trigger_event('avathar.bbguild.acp_editgames_submit', compact($vars)));

		$editgame->update_game();

		return $editgame;
	}

	/**
	 * Populate template variables for the edit game page.
	 *
	 * @param game $editgame The game being displayed
	 */
	private function showgame(game $editgame)
	{
		$game_id = $editgame->game_id;
		$provider = $this->game_registry->get($game_id);
		$has_api = ($provider !== null && $provider->has_api());

		// Region dropdown
		$regions = $editgame->getRegions();
		if (is_array($regions))
		{
			foreach ($regions as $key => $regionname)
			{
				$this->template->assign_block_vars('region_row', array(
					'VALUE'    => $key,
					'SELECTED' => ($editgame->getRegion() == $key) ? ' selected="selected"' : '',
					'OPTION'   => (!empty($regionname)) ? $regionname : '(None)',
				));
			}
		}

		// Game image path
		$imagename = $editgame->getImagename();
		$gamepath = $this->ext_path_web . 'images/' . $game_id . '/' . $imagename . '.png';
		$gamepath_file = $this->ext_path . 'images/' . $game_id . '/' . $imagename . '.png';

		$this->template->assign_vars(array(
			'URI_GAME'           => constants::URI_GAME,
			'GAME_ID'            => $game_id,
			'GAME_NAME'          => $editgame->getName(),
			'GAMEIMAGE'          => $imagename,
			'GAMEIMAGEEXPLAIN'   => $this->language->lang('CLASS_IMAGE_EXPLAIN'),
			'GAMEPATH'           => $gamepath,
			'S_GAMEIMAGE_EXISTS' => @file_exists($gamepath_file),
			'F_ENABLEARMORY'     => $editgame->getArmoryEnabled(),
			'HAS_API'            => $has_api,
			'BOSSBASEURL'        => $editgame->getBossbaseurl(),
			'ZONEBASEURL'        => $editgame->getZonebaseurl(),
			'EDITGAME'           => sprintf($this->language->lang('ACP_EDITGAME'), $editgame->getName()),
		));

		/**
		 * Event dispatched when the edit game template is being built.
		 * Allows game plugins to inject their own template variables.
		 *
		 * @event avathar.bbguild.acp_editgames_display
		 * @var game   editgame  The game object being displayed
		 * @var string game_id   The game identifier
		 * @var bool   has_api   Whether this game has API support
		 */
		$vars = array('editgame', 'game_id', 'has_api');
		extract($this->dispatcher->trigger_event('avathar.bbguild.acp_editgames_display', compact($vars)));
	}
}
