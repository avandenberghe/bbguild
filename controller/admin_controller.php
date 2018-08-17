<?php
/**
 * @package bbguild
 * @copyright 2018 avathar.be
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\controller;

use avathar\bbguild\model\player\player;
use phpbb\cache\service;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\extension\manager;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Admin controller
 */
class admin_controller
{


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

	/*** @var service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/*** @var driver_interface */
	protected $db;

	/*** @var pagination */
	protected $pagination;

	/*** @var helper */
	protected $helper;

	/** @var \phpbb\request\request */
	protected $request;

	/*** @var template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/*** @var manager "Extension Manager" */
	protected $ext_manager;

	/*** @var string phpEx */
	protected $php_ext;

	/*** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param  service          $cache       Cache object
	 * @param  config           $config      Config object
	 * @param  driver_interface $db          Database object
	 * @param  pagination       $pagination  Pagination object
	 * @param  helper           $helper      Controller helper object
	 * @param  request          $request     Request object
	 * @param  template         $template    Template object
	 * @param  user             $user        User object
	 * @param  manager          $ext_manager Extension manager object
	 * @param  string           $root_path   phpBB root path
	 * @param  string           $php_ext     phpEx
	 * @param  string           $bb_games_table	name of game table
	 * @param  string           $bb_logs_table	name of logging table
	 * @param  string           $bb_ranks_table	name of ranks table
	 * @param  string           $bb_guild_table	name of guild table
	 * @param  string           $bb_players_table	name of players table
	 * @param  string           $bb_classes_table	name of classes table
	 * @param  string           $bb races_table	name of races table
	 * @param  string           $bb_gameroles_table	name of roles table
	 * @param  string           $bb_factions_table	name of factions table
	 * @param  string           $bb_language_table	name of language table
	 * @param  string           $bb_motd_table	name of motd table
	 * @param  string           $bb_recruit_table	name of recruit table
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
	 * @return \avathar\bbguild\controller\admin_controller
	 * @access public
	 */
	public function __construct(
		service $cache,
		config $config,
		driver_interface $db,
		pagination $pagination,
		helper $helper,
		request $request,
		template $template,
		user $user,
		manager $ext_manager,
		$root_path,
		$php_ext,
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
		$bb_plugins
	)
	{

		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->pagination = $pagination;
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->ext_manager     = $ext_manager;
		$this->php_ext = $php_ext;
		$this->ext_path = $this->ext_manager->get_extension_path('avathar/bbguild', true);
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
	}

	/**
	 * returns GameFaction json list based on ajax call given game_id
	 * used in guild acp & player acp
	 *
	 * @internal string $game_id must be string values from game table
	 * @return JsonResponse
	 */
	public function getfaction()
	{
		global $table_prefix;
		define('FACTION_TABLE',             $table_prefix . 'bb_factions');

		$game_id =  $this->request->variable('game_id', '', true);
		$sql = 'SELECT faction_id, faction_name FROM ' . FACTION_TABLE . " where game_id = '" . $game_id . "' order by faction_id";
		$result = $this->db->sql_query($sql);

		$data =array();
		while ( $row = $this->db->sql_fetchrow($result))
		{
			$data[] =array(
			'faction_id' => $row['faction_id'],
			'faction_name' =>  $row['faction_name'],
			);
		}
		$this->db->sql_freeresult($result);

		//transform array to json using phpbb class
		return new JsonResponse($data);
	}

	/**
	 * returns Guild rank json list based on ajax call given guild id
	 * used in acp
	 *
	 * @return JsonResponse
	 * @internal int     $guild_id
	 */
	public function getguildrank()
	{
		global $table_prefix;

		$guild_id =  $this->request->variable('guild_id', '', true);

		$sql = 'SELECT a.rank_id, a.rank_name, b.game_id
		FROM ' . PLAYER_RANKS_TABLE . ' a, ' . GUILD_TABLE. ' b WHERE a.rank_hide = 0 and
		a.guild_id =  '. $guild_id . ' AND a.guild_id = b.id ORDER BY rank_id desc';

		$result = $this->db->sql_query($sql);
		$data =array();
		while ( $row = $this->db->sql_fetchrow($result))
		{
			$data[] =array(
			'rank_game_id' => $row['game_id'],
			'rank_id' => $row['rank_id'],
			'rank_name' => $row['rank_name']
			);
		}
		$this->db->sql_freeresult($result);
		//transform array to json using phpbb class
		$jsonresponse = new JsonResponse($data);
		return $jsonresponse;
	}


	/**
	 * returns playerlist json based on ajax call
	 * used by acp_addraid.html
	 *
	 * @internal string      $guild_id
	 * @return JsonResponse  $JsonResponse
	 */
	public function getplayerList()
	{
		$players = new player();
		$guild_id =  $this->request->variable('guild_id', '', true);
		$players->listallplayers($guild_id);

		$data =array();
		foreach ((array) $players->getGuildplayerlist() as $player)
		{
			$data[] =array(
			'player_id' => $player['player_id'],
			'player_name' =>  $player['rank_name'] . ' '.  $player['player_name'],
			);
		}
		unset($players);
		//transform array to json using phpbb class
		$jsonresponse = new JsonResponse($data);
		return $jsonresponse;
	}


	/**
	* returns race & class json based on ajax call
	* @return JsonResponse  $JsonResponse
	* @internal string $game_id
	*/
	public function getclassrace()
	{
		global $table_prefix;
		$game_id  =  $this->request->variable('game_id', '', true);

		$sql_array = array(
		'SELECT'    =>    '  r.race_id, l.name as race_name ',
		'FROM'        => array(
		RACE_TABLE        => 'r',
		BB_LANGUAGE        => 'l',
		),
		'WHERE'        => " r.race_id = l.attribute_id
					AND r.game_id = '" . $game_id . "'
					AND l.attribute='race'
					AND l.game_id = r.game_id
					AND l.language= '" . $this->config['bbdkp_lang'] ."'",
		'ORDER_BY'    => 'l.name',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		$races =array();
		while ( $row = $this->db->sql_fetchrow($result))
		{
			$races[] =array(
			'race_id' => $row['race_id'],
			'race_name' => $row['race_name']
			);
		}

		//now get classes
		$sql_array = array(
		'SELECT'    =>    ' c.class_id, l.name as class_name ',
		'FROM'        => array(
		CLASS_TABLE        => 'c',
		BB_LANGUAGE        => 'l',
		),
		'WHERE'        => " l.game_id = c.game_id AND c.game_id = '" . $game_id . "'
			AND l.attribute_id = c.class_id  AND l.language= '" . $this->config['bbdkp_lang'] . "' AND l.attribute = 'class' ",
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result1 = $this->db->sql_query($sql);
		$classes = array();
		while ( $row1 = $this->db->sql_fetchrow($result1))
		{
			$classes[] = array(
			'class_id' => $row1['class_id'],
			'class_name' => $row1['class_name']
			);
		}
		$this->db->sql_freeresult($result);
		$this->db->sql_freeresult($result1);

		$data = json_encode(
			array(
			'races'=> $races,
			'classes'=> $classes,
			)
		);

		return new JsonResponse($data);

	}

}
