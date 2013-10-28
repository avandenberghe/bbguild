<?PHP
/**
 * Game clas file
 * @package bbDKP\Game
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 */
namespace bbdkp;
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

/**
 * Games
 *
 * Manages creation of Game
 *
 * @package bbDKP\Game
 */
class Game extends \bbdkp\Admin
{
	/**
	 * primary key in games table
	 * @var unknown_type
	 */
	private $id;
	
	/**
	 * name of game
	 * @var unknown_type
	 */
	public $name;
	
	/**
	 * the game_id (unique key)
	 * @var unknown_type
	 */
	public $game_id;
	
	/**
	 * game status (not used atm)
	 * @var boolean
	 */
	public $status;
	
	/**
	 * date at which this game was installed
	 * @var unknown_type
	 */
	public $install_date;
	
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
		global $user, $db, $phpEx, $phpbb_root_path;
		
		if ($this->game_id == '')
		{
			\trigger_error ( sprintf ( $user->lang ['ADMIN_INSTALL_GAME_FAILURE'], $this->name ) . E_USER_WARNING );
		}
		
		$db->sql_transaction ( 'begin' );
			
		if(! array_key_exists($this->game_id, $this->preinstalled_games))
		{
			if ($this->name == '')
			{
				$this->name='Custom';
			}
		}
		else
		{
			$this->name= $this->preinstalled_games[$this->game_id];
			
			if (!class_exists('\bbdkp\install_' . $this->game_id))
			{
				include($phpbb_root_path .'includes/bbdkp/games/library/install_' . $this->game_id . '.' . $phpEx);
			}
		
			$classname = '\bbdkp\install_' . $this->game_id;
			$installgame = new $classname; 
			
			
			$installgame->install();
		}
		
		$data = array (
				'game_id' => ( string ) $this->game_id,
				'game_name' => ( string ) $this->name,
				'status' => 1
				);
		
		$sql = 'INSERT INTO ' . GAMES_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $data );
		$db->sql_query ( $sql );
		
		$this->id = $db->sql_nextid ();
		
		$db->sql_transaction ( 'commit' );
		
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
	*/
	public function Delete()
	{
		//delete from phpbb_bbdkp_games table
		global $db, $user, $cache;
			
			$db->sql_transaction ( 'begin' );
		
			$sql = 'DELETE FROM ' . GAMES_TABLE . " WHERE game_id = '" .   $this->game_id . "'";
			$db->sql_query ($sql);
			
			$factions = new \bbdkp\Faction(); 
			$factions->game_id = $this->game_id; 
			$factions->Delete_all_factions(); 

			$races = new \bbdkp\Races();
			$races->game_id = $this->game_id;
			$races->Delete_all_races(); 

			$classes = new \bbdkp\Classes();
			$classes->game_id = $this->game_id;
			$classes->Delete_all_classes();
			
			$db->sql_transaction ( 'commit' );
			
			$cache->destroy ( 'sql', GAMES_TABLE );
			
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
			
		//@todo delete ranks...
	}
	
	/**
	 * gets Game info from database
	 * 
	 */
	function Get()
	{
		//read phpbb_bbdkp_games table
		global $db;
		$sql = 'SELECT id, game_id, game_name, status
    			FROM ' . GAMES_TABLE . "
    			WHERE game_id = '" . $this->game_id . "'"; 
		
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->id = $row['id'];
			$this->name = $row['game_name'];
			$this->status= ($row['status'] == 1) ? true : false;
		}
		$db->sql_freeresult($result);
		
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
		$sql = 'SELECT id, game_id, game_name, status FROM ' . GAMES_TABLE . ' ORDER BY ' . $order; 
		$result = $db->sql_query ( $sql );
		while ($row = $db->sql_fetchrow($result))
		{
			$gamelist[$row['game_id']] = array(
					'id' => $row['id'] ,
					'name' => $row['game_name'] ,
					'game_id' => $row['game_id'] ,
					'status' => $row['status'],
			);
		}
		$db->sql_freeresult($result);
		 
		return $gamelist;
		
	}
	
	

}

?>