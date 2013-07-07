<?php

namespace bbdkp;
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

// Include the abstract base
if (!class_exists('\bbdkp\Game'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/Game.$phpEx");
}

/**
 * Faction
 * 
 * Manages all Game Factions
 * 
 * @package 	bbDKP
 */
 class Faction extends \bbdkp\Game 
{
	public $game_id;
	public $faction_id;
	public $faction_name;
	public $faction_hide;
	
	public function __construct() 
	{
		$this->game_id='';
		$this->faction_id=0;
		$this->faction_name='';
		$this->faction_hide=0;
	}

	/**
	 * get faction info
	 * (non-PHPdoc)
	 * @see \bbdkp\iFaction::Get()
	 */
	public function Get()
	{
		global $db;
		$sql = 'SELECT game_id, faction_id, faction_name, faction_hide
    			FROM ' . FACTION_TABLE . '
    			WHERE faction_id = ' . (int) $this->faction_id . ' and game_id = ' . (int) $this->game_id
		;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->faction_name = $row['faction_name'];
			$this->faction_hide	= $row['faction_hide'];
		}
		$db->sql_freeresult($result);
		
	}
	
	/**
	 * adds a faction
	 * (non-PHPdoc)
	 * @see \bbdkp\iFaction::Make()
	 */
	public function Make()
	{
		global $db, $user, $cache;
	
		$sql = 'SELECT max(faction_id) as faction_id FROM ' . FACTION_TABLE . "
				WHERE game_id = '" . $this->game_id . "' ";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$this->faction_id = (int) $row['faction_id'] + 1;
		$db->sql_freeresult ($result);
	
		$data = array (
				'game_id' => $this->game_id,
				'faction_name' => ( string ) $this->faction_name,
				'faction_id' => (int ) $this->faction_id,
				'faction_hide' => 0 );
	
		$db->sql_transaction ('begin');
	
		$sql = 'INSERT INTO ' . FACTION_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $data );
		$db->sql_query ( $sql );
	
		$db->sql_transaction ( 'commit' );
		$cache->destroy ( 'sql', FACTION_TABLE );
		
		trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_FACTION_SUCCESS'], $this->faction_name ), E_USER_NOTICE );
	
	}
	
	/**
	 * deletes a specific factions
	 */
	public function Delete()
	{
		global $db, $user, $cache; 
		
		/* check if there are races tied to this faction */
		$sql_array = array (
				'SELECT' => ' count(*) AS factioncount  ',
				'FROM' => array (
						RACE_TABLE => 'r', FACTION_TABLE => 'f' ),
				'WHERE' => "r.race_faction_id = f.faction_id 
				AND f.game_id = '" . $this->game_id . "'
				AND f.f_index =  " . $this->faction_id );
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query($sql);
		$factioncount = (int) $db->sql_fetchfield('factioncount');
		
		if ($factioncount == 0)
		{
			$sql = 'DELETE FROM ' . FACTION_TABLE . ' WHERE f_index =' . $this->faction_id . " AND game_id = '" .   $this->game_id . "'"  ;
			$db->sql_query ( $sql );
			$cache->destroy ( 'sql', FACTION_TABLE );
				
		}
		else
		{
			trigger_error (sprintf ( $user->lang ['ADMIN_DELETE_FACTION_FAILED'], $this->faction_name), E_USER_WARNING );
		}
	}
	
	/**
	 * deletes a specific factions
	 */
	public function Delete_all_factions()
	{
		global $db, $user, $cache;
		$sql = 'DELETE FROM ' . FACTION_TABLE . " WHERE game_id = '" .   $this->game_id . "'"  ;
		$db->sql_query ( $sql );
		$cache->destroy ( 'sql', FACTION_TABLE );
	}
	
	
	
}

?>