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
	public final function install($game_id, $gamename, $bossbaseurl, $zonebaseurl, $region)
	{
		global $cache, $db;
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

		$sql = 'INSERT INTO ' . BBGAMES_TABLE . ' ' . $db->sql_build_array('INSERT', $data);
		$db->sql_query($sql);

		$db->sql_transaction('commit');
		$cache->destroy('sql', BBGAMES_TABLE);
		$cache->destroy('sql', CLASS_TABLE);
		$cache->destroy('sql', BB_LANGUAGE);
		$cache->destroy('sql', RACE_TABLE);
		$cache->destroy('sql', PLAYER_TABLE);
		$cache->destroy('sql', BB_GAMEROLE_TABLE);

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

		$sql = 'DELETE FROM ' . BBGAMES_TABLE . " WHERE game_id = '" .   $this->game_id . "'";
		$db->sql_query($sql);

		$db->sql_transaction('commit');

		$cache->destroy('sql', BBGAMES_TABLE);
		$cache->destroy('sql', CLASS_TABLE);
		$cache->destroy('sql', BB_LANGUAGE);
		$cache->destroy('sql', RACE_TABLE);
		$cache->destroy('sql', PLAYER_TABLE);
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

		$db->sql_query('DELETE FROM ' .  BB_GAMEROLE_TABLE . " WHERE role_id < 3 and game_id = '" . $this->game_id . "'");
		$db->sql_query('DELETE FROM ' .  BB_LANGUAGE . " WHERE attribute_id < 3 and  attribute = 'role' and game_id = '" . $this->game_id  . "'");

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
		$db->sql_multi_insert(BB_GAMEROLE_TABLE, $sql_ary);

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
		$db->sql_multi_insert(BB_LANGUAGE, $sql_ary);
	}
}
