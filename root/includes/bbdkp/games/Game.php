<?PHP
/**
 * @package 	bbDKP
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

/**
 * Games
 *
 * Manages creation of Game
 *
 * @package 	bbDKP
 */
class Game
{
	
	/**
	 * list of allowed games
	 * @var array
	 */
	public $preinstalled_games;
	
	/**
	 * list of currently installed games
	 * @var unknown_type
	 */
	public $installed_games;
	
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
	
	
	function __construct() 
	{
		global $db, $user; 
		
		$this->preinstalled_games = array (
				'aion' 	=> $user->lang ['AION'],
				'daoc' 	=> $user->lang ['DAOC'],
				'eq' 	=> $user->lang ['EQ'],
				'eq2' 	=> $user->lang ['EQ2'],
				'FFXI' 	=> $user->lang ['FFXI'],
				'gw2' 	=> $user->lang ['GW2'],
				'lineage2' => $user->lang ['LINEAGE2'],
				'lotro' => $user->lang ['LOTRO'],
				'rift' 	=> $user->lang ['RIFT'],
				'swtor' => $user->lang ['SWTOR'],
				'tera' 	=> $user->lang ['TERA'],
				'vanguard' => $user->lang ['VANGUARD'],
				'warhammer' => $user->lang ['WARHAMMER'],
				'wow' 	=> $user->lang ['WOW'],
		);
		
		$result = $this->listgames('id'); 
		$this->installed_games = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$this->installed_games[$row['game_id']] = $row['game_name'];   
		}
		$db->sql_freeresult($result);
		
	}
	
	/**
	 * @todo adds a Game to database
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
			
		//@todo delete ranks...
	}
	
	/**
	 * @todo gets Game info from database
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
	 * @return array
	 */
	public function listgames($order)
	{
		global $db;
		$sql = 'SELECT id, game_id, game_name, status FROM ' . GAMES_TABLE . ' ORDER BY ' . $order; 
		$result = $db->sql_query ( $sql );
		return $result;
	}
	
	

}

?>