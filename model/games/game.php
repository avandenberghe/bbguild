<?PHP
/**
 * Game clas file
 *
 * @package   bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace bbdkp\bbguild\model\games;

use bbdkp\bbguild\model\games\library\install_custom;

/**
 * Games
 *
 * Manages creation of Game
 *
 * @package bbguild
 */
class game
{

	/**
	 * primary key in games table
	 *
	 * @var int
	 */
	private $id;

	/**
	 * the game_id (unique key)
	 *
	 * @var string
	 */
	public $game_id;

	/**
	 * name of game
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * game status (not used atm)
	 *
	 * @var boolean
	 */
	protected $status;

	/**
	 * game region
	 *
	 * @var string
	 */
	protected $region;

	/**
	 * date at which this game was installed
	 *
	 * @var int
	 */
	protected $install_date;

	/**
	 * name of game logo png
	 * in /images/bbguild/games/<game_id>
	 *
	 * @var string
	 */
	protected $imagename;

	/**
	 * true if armory is on
	 *
	 * @var boolean
	 */
	protected $armory_enabled;

	/**
	 * base boss database url
	 *
	 * @var boolean
	 */
	protected $bossbaseurl;

	/**
	 * base zone database url
	 *
	 * @var string
	 */
	protected $zonebaseurl;

	/**
	 * api key for game armory
	 *
	 * @var string
	 */
	protected $apikey;

	/**
	 * private api key for game armory
	 *
	 * @var string
	 */
	protected $privkey;

	/**
	 * locale string for the language in which api data are returned. en_GB, en_US, de_DE, es_ES, fr_FR, it_IT, pt_PT, pt_BR, or ru_RU
	 *
	 * @var string
	 */
	protected $apilocale;

	/**
	 * pre-installable games
	 *
	 * @var array
	 */
	protected $preinstalled_games;

	/**
	 * installed games
	 *
	 * @var array
	 */
	public $games;

	/**
	 * extension path
	 *
	 * @var
	 */
	private $ext_path;

	/**
	 * possible regions
	 *
	 * @var array
	 */
	protected $regions;

	/**
	 * list of possible locale strings
	 *
	 * @var array
	 */
	protected $apilocales;

	/**
	 * list of possible realms
	 *
	 * @var string
	 */
	protected $realmlist;

	/**
	 * @param boolean $basebossurl
	 */
	public  function setBossbaseurl($basebossurl)
	{
		$this->bossbaseurl = $basebossurl;
	}

	/**
	 * @return boolean
	 */
	public  function getBossbaseurl()
	{
		return $this->bossbaseurl;
	}

	/**
	 * @param string $zonebaseurl
	 */
	public function setZonebaseurl($zonebaseurl)
	{
		$this->zonebaseurl = $zonebaseurl;
	}

	/**
	 * @return string
	 */
	public function getZonebaseurl()
	{
		return $this->zonebaseurl;
	}


	/**
	 * @param string $imagename
	 */
	public function setImagename($imagename)
	{
		$this->imagename = $imagename;
	}

	/**
	 * @return string
	 */
	public function getImagename()
	{
		return $this->imagename;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param boolean $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return boolean
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param boolean $armory_enabled
	 */
	public function setArmoryEnabled($armory_enabled)
	{
		$this->armory_enabled = $armory_enabled;
	}

	/**
	 * @return boolean
	 */
	public function getArmoryEnabled()
	{
		return $this->armory_enabled;
	}


	/**
	 * @param string $apikey
	 */
	public function setApikey($apikey)
	{
		$this->apikey = $apikey;
	}

	/**
	 * @return string
	 */
	public function getApikey()
	{
		return $this->apikey;
	}

	/**
	 * @return string
	 */
	public function get_apilocale()
	{
		return $this->apilocale;
	}

	/**
	 * @param string $apilocale
	 */
	public function set_apilocale($apilocale)
	{
		$this->apilocale = $apilocale;
	}

	/**
	 * @return string
	 */
	public function get_privkey()
	{
		return $this->privkey;
	}

	/**
	 * @param string $privkey
	 */
	public function set_privkey($privkey)
	{
		$this->privkey = $privkey;
	}

	/**
	 * @return string
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @param string $region
	 */
	public function setRegion($region)
	{
		$this->region = $region;
	}

	/**
	 * @return array
	 */
	public function getRegions()
	{
		return $this->regions;
	}

	/**
	 * @return int
	 */
	public function getInstallDate()
	{
		return $this->install_date;
	}

	/**
	 * @param int $install_date
	 */
	public function setInstallDate($install_date)
	{
		$this->install_date = $install_date;
	}

	/**
	 * @return array with locales for this region
	 */
	public function getApilocales($region)
	{
		return $this->apilocales[$region];
	}


	/**
	 * @return array
	 */
	public function getPreinstalledGames()
	{
		return $this->preinstalled_games;
	}

	/**
	 * @param array $preinstalled_games
	 */
	public function setPreinstalledGames($preinstalled_games)
	{
		$this->preinstalled_games = $preinstalled_games;
	}

	/**
	 * @return array
	 */
	public function getRealmlist()
	{
		return $this->realmlist;
	}


	/**
	 * Game class constructor
	 */
	public function __construct()
	{
		global $user, $phpbb_extension_manager;
		$this->ext_path = $phpbb_extension_manager->get_extension_path('bbdkp/bbguild', true);

		$this->preinstalled_games = array (
			'aion'     => $user->lang['AION'],
			'daoc'     => $user->lang['DAOC'],
			'eq'     => $user->lang['EQ'],
			'eq2'     => $user->lang['EQ2'],
			'FFXI'     => $user->lang['FFXI'],
			'gw2'     => $user->lang['GW2'],
			'lineage2' => $user->lang['LINEAGE2'],
			'lotro' => $user->lang['LOTRO'],
			'rift'     => $user->lang['RIFT'],
			'swtor' => $user->lang['SWTOR'],
			'tera'     => $user->lang['TERA'],
			'vanguard' => $user->lang['VANGUARD'],
			'warhammer' => $user->lang['WARHAMMER'],
			'wow'     => $user->lang['WOW'],
			'ffxiv'    => $user->lang['FFXIV'],
		);

		$this->regions = array(
			'eu' => $user->lang['REGIONEU'],
			'kr' => $user->lang['REGIONKR'],
			'sea' => $user->lang['REGIONSEA'],
			'tw' => $user->lang['REGIONTW'],
			'us' => $user->lang['REGIONUS'],
		);

		//available locale strings - used for wow only
		$this->apilocales = array(
			'eu' => array('en_GB', 'de_DE', 'es_ES', 'fr_FR', 'it_IT', 'pl_PL', 'pt_PT', 'ru_RU'),
			'kr' => array('ko_KR') ,
			'sea' => array('en_US'),
			'tw' => array('zh_TW'),
			'us' => array('en_US', 'pt_BR', 'es_MX')
		);

		//fill the games array
		$this->games = $this->gamesarray();
	}

	/**
	 * adds a Game to database
	 */
	public function install_game()
	{
		//insert into phpbb_bbguild_games table
		global $user, $phpEx, $config;

		if ($this->game_id == '')
		{
			trigger_error(sprintf($user->lang['ADMIN_INSTALL_GAME_FAILURE'], $this->name) . E_USER_WARNING);
		}

		if (array_key_exists($this->game_id, $this->preinstalled_games))
		{
			//game id is one of the preinstallable games
			$this->name= $this->preinstalled_games[$this->game_id];
			//build name of the namespaced game installer class
			$classname = '\bbdkp\bbguild\model\games\library\install_' . $this->game_id;
			$installgame = new $classname;

			//call the game installer
			$installgame->install(
				$this->game_id, $this->name,
				$installgame->getBossbaseurl(),
				$installgame->getZonebaseurl(),
				$this->getRegion()
			);

			//is gameworld installed ?
			if (isset($config['bbguild_gameworld_version']))
			{
				if ($config['bbguild_gameworld_version'] >= '2.0')
				{
					$classname = '\bbdkp\bbworld\model\games\library\world_' . $this->game_id;
					$installworld = new $classname;
					$installworld->install($this->game_id);
				}
			}

		}
		else
		{
			//custom game, this is dispatched to dummy game installer
			if ($this->name == '')
			{
				$this->name='Custom';
			}
			$installgame = new install_custom;
			//call the game installer
			$installgame->install($this->game_id, $this->name, $installgame->getBossbaseurl(), $installgame->getZonebaseurl(), $this->getRegion() );

			//is gameworld installed ?
			if (isset($config['bbguild_gameworld_version']))
			{
				if ($config['bbguild_gameworld_version'] >= '2.0')
				{
					if (!class_exists('\bbdkp\bbworld\model\games\library\world_custom'))
					{
						include $this->ext_path .'model/games/library/world_custom.' . $phpEx;
					}
					$installworld = new \bbdkp\bbworld\model\games\library\world_custom;
					$installworld->install($this->game_id);
				}
			}
		}
	}

	/**
	 * deletes a Game from database, including all factions, classes and races.
	 */
	public function delete_game()
	{
		global $user, $phpEx, $config;
		if ($this->game_id == '')
		{
			\trigger_error(sprintf($user->lang['ADMIN_INSTALL_GAME_FAILURE'], $this->name) . E_USER_WARNING);
		}

		if (array_key_exists($this->game_id, $this->preinstalled_games))
		{
			//fetch installer
			if (!class_exists('\bbdkp\bbguild\model\games\library\install_' . $this->game_id))
			{
				include $this->ext_path .'model/games/library/install_' . $this->game_id . '.' . $phpEx;
			}
			$gameclassname = '\bbdkp\bbguild\model\games\library\install_' . $this->game_id;
		}
		else
		{
			if (!class_exists('\bbdkp\bbguild\model\games\library\install_custom'))
			{
				include $this->ext_path .'model/games/library/install_custom.' . $phpEx;
			}
			$gameclassname = '\bbdkp\bbguild\model\games\library\install_custom';
		}

		//is bossprogress installed ?
		if (isset($config['bbguild_gameworld_version']))
		{
			if ($config['bbguild_gameworld_version'] >= '2.0')
			{
				if (array_key_exists($this->game_id, $this->preinstalled_games))
				{
					$gameworld_classname = '\bbdkp\bbworld\model\games\library\world_' . $this->game_id;
					$installworld = new $gameworld_classname;
				}
				else
				{
					$installworld = new \bbdkp\bbworld\model\games\library\world_custom;
				}
				$installworld->uninstall($this->game_id, $this->getName());
			}
		}

		//build name of the namespaced game installer class. do it after uninstalling the gameworld.
		$installgame = new $gameclassname;
		//call the game installer
		$installgame->uninstall($this->game_id, $this->name);
	}

	/**
	 * gets Game info from database
	 * read phpbb_bbguild_games table
	 */
	public function get_game()
	{
		global $db;
		$sql = 'SELECT id, game_id, game_name, status, imagename, armory_enabled,
 				bossbaseurl, zonebaseurl, apikey, apilocale, privkey, region
    			FROM ' . BBGAMES_TABLE . "
    			WHERE game_id = '" . $this->game_id . "'";

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->id = $row['id'];
			$this->name = $row['game_name'];
			$this->status= ($row['status'] == 1) ? true : false;
			$this->imagename = $row['imagename'];
			$this->armory_enabled = $row['armory_enabled'];
			$this->bossbaseurl = $row['bossbaseurl'];
			$this->zonebaseurl = $row['zonebaseurl'];
			$this->apikey = $row['apikey'];
			$this->apilocale = $row['apilocale'];
			$this->privkey = $row['privkey'];
			$this->region = $row['region'];
		}
		$db->sql_freeresult($result);
	}

	/**
	 * update this game
	 */
	public function update_game()
	{
		//update phpbb_bbguild_games table
		global $cache, $db;

		$db->sql_transaction('begin');

		$query = $db->sql_build_array(
			'UPDATE', array(
			'imagename'      => substr($this->imagename, 0, 20) ,
			'armory_enabled' => $this->armory_enabled,
			'game_name'      => $this->name,
			'bossbaseurl'    => $this->bossbaseurl,
			'zonebaseurl'    => $this->zonebaseurl,
			'apikey'         => $this->apikey,
			'apilocale'      => $this->apilocale,
			'privkey'        => $this->privkey,
			'region'         => $this->region,
			)
		);

		$sql = 'UPDATE ' . BBGAMES_TABLE . ' SET ' . $query . " WHERE game_id = '" . $this->game_id . "'";
		$db->sql_query($sql);

		$db->sql_transaction('commit');
		$cache->destroy('sql', BBGAMES_TABLE);
	}

	/**
	 * exposed games array
	 *
	 * @return array|void
	 */
	private function gamesarray()
	{
		global $db;

		$sql = ' SELECT g.id, g.game_id, g.game_name, g.status, g.imagename, g.bossbaseurl, g.zonebaseurl, g.region ';
		$sql .= ' FROM ' . BBGAMES_TABLE . '  g';
		$sql .= ' INNER JOIN '. RACE_TABLE . ' r ON r.game_id = g.game_id';
		$sql .= ' INNER JOIN  ' . CLASS_TABLE . ' c ON c.game_id= g.game_id';
		$sql .= ' GROUP BY g.id, g.game_id, g.game_name, g.status, g.imagename, g.bossbaseurl, g.zonebaseurl, g.region ';
		$sql .= ' ORDER BY g.game_id';
		// cache for 1 days
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->games[$row['game_id']] = $row['game_name'];
		}
		$db->sql_freeresult($result);
		return $this->games;
	}

	/**
	 * lists all games (used in game acp)
	 *
	 * @param  string $order
	 * @return array
	 */
	public function list_games($order = 'game_id ASC')
	{
		global $db;
		$gamelist = array();
		$sql = 'SELECT id, game_id, game_name, status, imagename, bossbaseurl, zonebaseurl, region FROM ' . BBGAMES_TABLE . ' ORDER BY ' . $order;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$gamelist[$row['game_id']] = array(
				'id' => $row['id'] ,
				'name' => $row['game_name'] ,
				'game_id' => $row['game_id'] ,
				'status' => $row['status'],
				'imagename' => $row['imagename'],
				'bossbaseurl'   => $row['bossbaseurl'],
				'zonebaseurl'   => $row['zonebaseurl'],
				'region'        => $row['region'],
			);
		}
		$db->sql_freeresult($result);

		return $gamelist;
	}

	/**
	 * updates the default game flag
	 *
	 * @param int $id
	 */
	public function update_gamedefault($id)
	{
		global $config;
		$config->set('bbguild_default_game', $id, true);

	}


}
