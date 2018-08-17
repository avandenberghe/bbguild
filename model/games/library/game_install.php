<?php
/**
 * abstract class aGameInstall
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */
namespace avathar\bbguild\model\games\library;

/**
 * @ignore
 */
use avathar\bbguild\model\games\rpg\classes;
use avathar\bbguild\model\games\rpg\faction;
use avathar\bbguild\model\games\rpg\races;
use avathar\bbguild\model\games\rpg\roles;

/**
 * Game interface
 * this abstract class is the framework for all game installers
 *
* @package avathar\bbguild\model\games\library
 */
abstract class game_install
{

	protected $game_id;
	protected $bossbaseurl;
	protected $zonebaseurl;

	private $gamename;

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
	 * @return string
	 */
	public final function getBossbaseurl()
	{
		return $this->bossbaseurl;
	}

	/**
	 * @return string
	 */
	public final function getZonebaseurl()
	{
		return $this->zonebaseurl;
	}

	/**
	 * Install a game
	 * cannot be overridden, this is the default install
	 *
	 * @param $game_id
	 * @param $gamename
	 * @param $bossbaseurl
	 * @param $zonebaseurl
	 * @param $region
	 */
	public final function install($bb_games_table,
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
								  $bb_plugins,
								  $game_id, $gamename, $bossbaseurl, $zonebaseurl, $region)
	{
		global $cache, $db;

		$this->bb_games_table = $bb_games_table;
		$this->bb_classes_table = $bb_classes_table;
		$this->bb_language_table = $bb_language_table;
		$this->bb_races_table = $bb_races_table;
		$this->bb_players_table = $bb_players_table;

		$this->bb_factions_table = $bb_factions_table;
		$this->bb_gameroles_table = $bb_gameroles_table;

		$this->bb_logs_table = $bb_logs_table;
		$this->bb_ranks_table = $bb_ranks_table;
		$this->bb_guild_table = $bb_guild_table;
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

		$this->game_id = $game_id;
		$this->gamename = $gamename;
		$this->bossbaseurl = $bossbaseurl;
		$this->zonebaseurl = $zonebaseurl;

		$db->sql_transaction('begin');
		$this->install_factions();
		$this->install_classes();
		$this->install_races();
		$this->install_roles();

		//insert a new entry in the game table
		$data = array (
		'game_id' => $this->game_id,
		'game_name' => $this->gamename,
		'imagename' => $this->game_id,
		'armory_enabled' => ($this->game_id == 'wow' ? 1 : 0),
				'bossbaseurl' => $this->bossbaseurl,
				'zonebaseurl' => $this->zonebaseurl ,
		'status' => 1,
		'region' => $region
		);

		$sql = 'INSERT INTO ' . $this->bb_games_table . ' ' . $db->sql_build_array('INSERT', $data);
		$db->sql_query($sql);

		$db->sql_transaction('commit');
		$cache->destroy('sql', $this->bb_games_table);
		$cache->destroy('sql', $this->bb_classes_table);
		$cache->destroy('sql', $this->bb_language_table);
		$cache->destroy('sql', $this->bb_races_table);
		$cache->destroy('sql', $this->bb_players_table);
		$cache->destroy('sql', $this->bb_gameroles_table );

	}

	/**
	 * Uninstall a game
	 *
	 * @param $game_id
	 * @param $gamename
	 */
	public final function uninstall($game_id, $gamename)
	{
		global $cache, $db;
		$this->game_id = $game_id;
		$this->gamename = $gamename;

		$db->sql_transaction('begin');

		$factions = new faction($this->game_id);
		$factions->delete_all_factions();

		$races = new races;
		$races->game_id = $this->game_id;
		$races->delete_all_races();

		$classes = new classes;
		$classes->game_id = $this->game_id;
		$classes->delete_all_classes();

		$roles = new roles;
		$roles->game_id = $this->game_id;
		$roles->delete_all_roles();

		$sql = 'DELETE FROM ' . $this->bb_games_table . " WHERE game_id = '" .   $this->game_id . "'";
		$db->sql_query($sql);

		$db->sql_transaction('commit');

		$cache->destroy('sql', $this->bb_games_table);
		$cache->destroy('sql', $this->bb_classes_table);
		$cache->destroy('sql', $this->bb_language_table);
		$cache->destroy('sql', $this->bb_races_table);
		$cache->destroy('sql', $this->bb_players_table);
	}

	/**
	 * Installs factions
	 * must be implemented
	 */
	abstract protected function install_factions();

	/**
	 * Installs game classes
	 * must be implemented
	*/
	abstract protected function install_classes();

	/**
	 * Installs races
	 * must be implemented
	*/
	abstract protected function install_races();

	/**
	 * Install sample roles.
	 * if a game needs a special role, then implement that role in the game installer class.
	 * the only game needing special roles is GW2 due to it not following the holy trinity
	 * http://www.mmo-champion.com/threads/1125142-GW2-Roles-An-explanation
	 *
	 * can be implemented
	 */
	protected function install_roles()
	{

		global $db;

		$db->sql_query('DELETE FROM ' .  $this->bb_gameroles_table  . " WHERE role_id < 3 and game_id = '" . $this->game_id . "'");
		$db->sql_query('DELETE FROM ' .  $this->bb_language_table . " WHERE attribute_id < 3 and  attribute = 'role' and game_id = '" . $this->game_id  . "'");

		$sql_ary = array(
			array(
				// dps
				'game_id'             => $this->game_id ,
				'role_id'           => 0,
				'role_color'       => '#FF4455',
				'role_icon'           => 'dps_icon',
			),
			array(
				// healer
				'game_id'             => $this->game_id ,
				'role_id'           => 1,
				'role_color'       => '#11FF77',
				'role_icon'           => 'healer_icon',
			),
			array(
				// tank
				'game_id'             => $this->game_id ,
				'role_id'           => 2,
				'role_color'       => '#c3834c',
				'role_icon'           => 'tank_icon',
			),
		);
		$db->sql_multi_insert($this->bb_gameroles_table , $sql_ary);

		//english
		$sql_ary = array(
			array(
				// dps
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  0,
				'language'          => 'en',
				'attribute'            => 'role',
				'name'                => 'Damage',
				'name_short'        => 'DPS',
			),
			array(
				// healer
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  1,
				'language'          => 'en',
				'attribute'            => 'role',
				'name'                => 'Healer',
				'name_short'        => 'HPS',
			),
			array(
				// defense
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  2,
				'language'          => 'en',
				'attribute'            => 'role',
				'name'                => 'Defense',
				'name_short'        => 'DEF',
			),
			array(
				// dps
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  0,
				'language'          => 'fr',
				'attribute'            => 'role',
				'name'                => 'Dégats',
				'name_short'        => 'DPS',
			),
			array(
				// healer
				'game_id'              => $this->game_id ,
				'attribute_id'        => 1,
				'language'          => 'fr',
				'attribute'            => 'role',
				'name'                => 'Soigneur',
				'name_short'        => 'HPS',
			),
			array(
				// tank
				'game_id'              => $this->game_id ,
				'attribute_id'        => 2,
				'language'          => 'fr',
				'attribute'            => 'role',
				'name'                => 'Défense',
				'name_short'        => 'DEF',
			),
			array(
				// dps
				'game_id'              => $this->game_id ,
				'attribute_id'        => 0,
				'language'          => 'de',
				'attribute'            => 'role',
				'name'                => 'Kämpfer',
				'name_short'        => 'Schaden',
			),
			array(
				// healer
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  1,
				'language'          => 'de',
				'attribute'            => 'role',
				'name'                => 'Heiler',
				'name_short'        => 'Heil',
			),
			array(
				// tank
				'game_id'              => $this->game_id ,
				'attribute_id'        =>  2,
				'language'          => 'de',
				'attribute'            => 'role',
				'name'                => 'Verteidigung',
				'name_short'        => 'Schutz',
			),
			array(
				// dps
				'game_id'              => $this->game_id ,
				'attribute_id'        => 0,
				'language'          => 'it',
				'attribute'            => 'role',
				'name'                => 'Danni',
				'name_short'        => 'Danni',
			),
			array(
				// healer
				'game_id'              =>  $this->game_id ,
				'attribute_id'        => 1,
				'language'          => 'it',
				'attribute'            => 'role',
				'name'                => 'Cura',
				'name_short'        => 'Cura',
			),
			array(
				// tank
				'game_id'              =>  $this->game_id ,
				'attribute_id'        =>  2,
				'language'          => 'it',
				'attribute'            => 'role',
				'name'                => 'Difeza',
				'name_short'        => 'Tank',
			),
		);
		$db->sql_multi_insert($this->bb_language_table, $sql_ary);
	}
}
