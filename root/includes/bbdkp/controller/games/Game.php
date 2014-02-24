<?PHP
/**
 * Game clas file
 *   @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 */
namespace bbdkp\controller\games;
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\admin\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin/admin.$phpEx");
}

/**
 * Games
 *
 * Manages creation of Game
 *
 *   @package bbdkp
 */
class Game extends \bbdkp\admin\Admin
{
	/**
	 * primary key in games table
	 * @var unknown_type
	 */
	private $id;

	/**
	 * name of game
	 * @var string
	 */
	public $name;

	/**
	 * the game_id (unique key)
	 * @var string
	 */
	public $game_id;

	/**
	 * game status (not used atm)
	 * @var boolean
	 */
	public $status;

	/**
	 * date at which this game was installed
	 * @var int
	 */
	public $install_date;


	/**
	 * name of game logo png
	 * in /images/bbdkp/games/<game_id>
	 * @var string
	 */
	public $imagename;

	/**
	 * true if armory is on
	 * @var boolean
	 */
	public $armory_enabled;

	/**
	 * Game class constructor
	 */
	function __construct()
	{
		parent::__construct();
		global $db, $user;
	}

	/**
	 * adds a Game to database
	*/
	public function install()
	{
		//insert into phpbb_bbdkp_games table
		global $user, $phpEx, $phpbb_root_path, $config;

		if ($this->game_id == '')
		{
			\trigger_error ( sprintf ( $user->lang ['ADMIN_INSTALL_GAME_FAILURE'], $this->name ) . E_USER_WARNING );
		}

		if(array_key_exists($this->game_id, $this->preinstalled_games))
		{
			//game id is one of the preinstallable games
			$this->name= $this->preinstalled_games[$this->game_id];
		}
		else
		{
			//custom game, this is dispatched to dummy game installer
			$this->game_id = 'custom';
			if ($this->name == '')
			{
				$this->name='Custom';
			}
		}

		//fetch installer
		if (!class_exists('\bbdkp\controller\games\install_' . $this->game_id))
		{
			include($phpbb_root_path .'includes/bbdkp/controller/games/library/install_' . $this->game_id . '.' . $phpEx);
		}

		//build name of the namespaced game installer class
		$classname = '\bbdkp\controller\games\install_' . $this->game_id;
		$installgame = new $classname;
        //call the game installer
        $installgame->Install($this->game_id, $this->name );

        //is bossprogress installed ?
        if(isset($config['bbdkp_bp_version']))
        {
            if ($config['bbdkp_bp_version'] >= '1.0.10')
            {
                if (!class_exists('\bbdkp\controller\games\world_' . $this->game_id))
                {
                    include($phpbb_root_path .'includes/bbdkp/controller/games/library/world_' . $this->game_id . '.' . $phpEx);
                }
                $classname = '\bbdkp\controller\games\world_' . $this->game_id;
                $installworld = new $classname;
                $installworld->Install($this->game_id);

            }
        }

		//
		// Logging
		//
		$log_action = array(
			'header' => 'L_ACTION_GAME_ADDED' ,
			'L_GAME' => $this->game_id  ,
		);

		$this->log_insert(array(
			'log_type' =>  'L_ACTION_GAME_ADDED',
			'log_action' => $log_action));
	}

	/**
	 * deletes a Game from database, including all factions, classes and races.
    *  @todo delete ranks...
	*/
	public function Delete()
	{

        global $user, $phpEx, $phpbb_root_path, $config;
        if ($this->game_id == '')
        {
            \trigger_error ( sprintf ( $user->lang ['ADMIN_INSTALL_GAME_FAILURE'], $this->name ) . E_USER_WARNING );
        }

        //fetch installer
        if (!class_exists('\bbdkp\controller\games\install_' . $this->game_id))
        {
            include($phpbb_root_path .'includes/bbdkp/controller/games/library/install_' . $this->game_id . '.' . $phpEx);
        }

        //build name of the namespaced game installer class
        $classname = '\bbdkp\controller\games\install_' . $this->game_id;
        $installgame = new $classname;
        //call the game installer
        $installgame->Uninstall($this->game_id, $this->name );

        //is bossprogress installed ?
        if(isset($config['bbdkp_bp_version']))
        {
            if ($config['bbdkp_bp_version'] >= '1.0.10')
            {
                if (!class_exists('\bbdkp\controller\games\world_' . $this->game_id))
                {
                    include($phpbb_root_path .'includes/bbdkp/controller/games/library/world_' . $this->game_id . '.' . $phpEx);
                }
                $classname = '\bbdkp\controller\games\world_' . $this->game_id;
                $installworld = new $classname;
                $installworld->Uninstall($this->game_id);
            }
        }

        //
        // Logging
        //
        $log_action = array(
            'header' => 'L_ACTION_GAME_DELETED' ,
            'L_GAME' => $this->game_id  ,
        );

        $this->log_insert(array(
        'log_type' =>  'L_ACTION_GAME_DELETED',
        'log_action' => $log_action));

	}

	/**
	 * gets Game info from database
	 *
	 */
	public function Get()
	{
		//read phpbb_bbdkp_games table
		global $db;
		$sql = 'SELECT id, game_id, game_name, status, imagename, armory_enabled
    			FROM ' . GAMES_TABLE . "
    			WHERE game_id = '" . $this->game_id . "'";

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->id = $row['id'];
			$this->name = $row['game_name'];
			$this->status= ($row['status'] == 1) ? true : false;
			$this->imagename = $row['imagename'];
			$this->armory_enabled = $row['armory_enabled'];
		}
		$db->sql_freeresult($result);

	}

	/**
	 * update this game
	 */
	public function update()
	{

		//delete from phpbb_bbdkp_games table
		global $db;

		$db->sql_transaction ( 'begin' );

		$query = $db->sql_build_array('UPDATE', array(
				'imagename' => substr($this->imagename, 0, 20) ,
				'armory_enabled' => $this->armory_enabled,
		));

		$sql = 'UPDATE ' . GAMES_TABLE . ' SET ' . $query . " WHERE game_id = '" . $this->game_id . "'";
		$db->sql_query($sql);

		$db->sql_transaction ('commit');
	}

	/**
	 * lists all games
	 *
	 * @param string $order
	 * @return array
	 */
	public function listgames($order)
	{
		global $db;
		$gamelist = array();
		$sql = 'SELECT id, game_id, game_name, status, imagename FROM ' . GAMES_TABLE . ' ORDER BY ' . $order;
		$result = $db->sql_query ( $sql );
		while ($row = $db->sql_fetchrow($result))
		{
			$gamelist[$row['game_id']] = array(
					'id' => $row['id'] ,
					'name' => $row['game_name'] ,
					'game_id' => $row['game_id'] ,
					'status' => $row['status'],
					'imagename' => $row['imagename'],
			);
		}
		$db->sql_freeresult($result);

		return $gamelist;

	}



}

?>