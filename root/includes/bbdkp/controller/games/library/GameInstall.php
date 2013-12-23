<?php
/**
 * abstract class aGameInstall
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
namespace bbdkp\controller\games;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}
/**
 * Game interface
 * this abstract class is the framework for all game installers
 *
 */
abstract class GameInstall
{

	private $game_id;
	private $gamename;

	/**
	 * Install a game
	 */
	public function install($game_id, $gamename)
	{
		$this->game_id = $game_id;
		$this->gamename = $gamename;
		global $db, $user, $config;
		$db->sql_transaction ( 'begin' );
		$this->Installfactions();
		$this->InstallClasses();
		$this->InstallRaces();
		$this->InstallEventGroup();

		//insert a new entry in the game table
		$data = array (
				'game_id' => $this->game_id,
				'game_name' => $this->gamename,
				'imagename' => '',
				'armory_enabled' => ($this->game_id == 'wow' ? 1 : 0),
				'status' => 1
		);

		$sql = 'INSERT INTO ' . GAMES_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $data );
		$db->sql_query ( $sql );

		$db->sql_transaction ( 'commit' );

	}

	/**
	 * Installs factions
	 */
	abstract function Installfactions();

	/**
	 * Installs game classes
	*/
	abstract function InstallClasses();

	/**
	 * Installs races
	*/
	abstract function InstallRaces();

	/**
	 * Install sample Event Groups
	 * an Event answers the 'what' question
	 */
	public function InstallEventGroup()
	{

	}

	/**
	 * install events
	 * leave implementation to daughter class
	 */
	private function InstallEvents()
	{

	}

	/**
	 * install worldprogress (bossprogress successor)
	 * installs Lands, Dungeons, bosses
	 */
	public function InstallWorld()
	{
		// @todo
	}
}

?>