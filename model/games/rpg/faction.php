<?php
/**
 * Faction Class
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\model\games\rpg;

/**
 * Faction Class
 *
 * Manages all Game Factions
 *
 *   @package bbguild
 */
class faction
{
	public $bb_factions_table;

	/**
	 * game id
	  *
	 * @var string
	 */
	public $game_id;

	/**
	 * pk
	*
	 * @var int
	 */
	protected $f_index; //readonly

	/**
	 * faction id
	*
	 * @var int
	 */
	protected $faction_id;
	/**
	 * name of faction
	*
	 * @var string
	 */
	protected $faction_name;
	/**
	 * flag to show or hide (0 or 1)
	*
	 * @var int
	 */
	protected $faction_hide;


	/**
	  * faction property getter
	  *
	  * @param  $fieldName
	  * @return null
	  */
	public function __get($fieldName)
	{
		global $user;

		if (property_exists($this, $fieldName))
		{
			return $this->$fieldName;
		}
		else
		{
			trigger_error($user->lang['ERROR'] . '  '. $fieldName, E_USER_WARNING);
		}
		return null;
	}

	/**
	  * faction property setter
	  *
	  * @param string $property
	  * @param string $value
	  */
	public function __set($property, $value)
	{
		global $user;

		switch ($property)
		{
		case 'f_index':
			break;
		default:
			if (property_exists($this, $property))
			{
				$this->$property = $value;
			}
			else
			{
				trigger_error($user->lang['ERROR'] . '  '. $property, E_USER_WARNING);
			}
		}
	}

	/**
	 * Faction class constructor
	 *
	 * @param $game_id
	 */
	public function __construct($game_id, $bb_factions_table)
	{
		$this->bb_factions_table = $bb_factions_table;

		$this->game_id=$game_id;
		$this->get();
	}

	/**
	 * build a full object
	 */
	public function get()
	{
		global $db;
		$sql = 'SELECT game_id, f_index, faction_id, faction_name, faction_hide
    			FROM ' . $this->bb_factions_table . '
    			WHERE f_index = ' . (int) $this->faction_id . " and game_id = '" . $this->game_id . "'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->faction_id = $row['faction_id'];
			$this->faction_name = $row['faction_name'];
			$this->faction_hide    = $row['faction_hide'];
		}
		$db->sql_freeresult($result);
	}


	/**
	 * adds a faction
	 */
	public function make_faction()
	{
		global $db, $cache;

		$sql = 'SELECT max(faction_id) as faction_id FROM ' . $this->bb_factions_table . "
				WHERE game_id = '" . $this->game_id . "' ";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$this->faction_id = (int) $row['faction_id'] + 1;
		$db->sql_freeresult($result);

		$data = array (
		'game_id' => $this->game_id,
		'faction_name' => ( string ) $this->faction_name,
		'faction_id' => (int ) $this->faction_id,
		'faction_hide' => 0 );

		$db->sql_transaction('begin');

		$sql = 'INSERT INTO ' . $this->bb_factions_table . ' ' . $db->sql_build_array('INSERT', $data);
		$db->sql_query($sql);

		$db->sql_transaction('commit');
		$cache->destroy('sql', $this->bb_factions_table);
	}


	/**
	  * Update a faction
	  */
	public function update_faction()
	{
		global $db, $cache;

		$data = array (
			'game_id' => $this->game_id,
			'faction_name' => ( string ) $this->faction_name,
			'faction_id' => (int ) $this->faction_id,
			'faction_hide' => 0 );

		$db->sql_transaction('begin');

		$sql = 'UPDATE ' . $this->bb_factions_table . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE faction_id = ' . $this->faction_id . " AND game_id = '" . $this->game_id . "'";
		$db->sql_query($sql);

		$db->sql_transaction('commit');
		$cache->destroy('sql', $this->bb_factions_table);
	}


	/**
	 * delete a faction
	 */
	public function delete_faction()
	{
		global $db, $user, $cache;

		/* check if there are races tied to this faction */
		$sql_array = array (
		'SELECT' => ' count(*) AS factioncount  ',
		'FROM' => array (
		 RACE_TABLE => 'r', $this->bb_factions_table => 'f' ),
		'WHERE' => "r.race_faction_id = f.faction_id 
				AND f.game_id = '" . $this->game_id . "'
				AND f.f_index =  " . $this->faction_id );

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$factioncount = (int) $db->sql_fetchfield('factioncount');

		if ($factioncount == 0)
		{
			$sql = 'DELETE FROM ' . $this->bb_factions_table . ' WHERE f_index =' . $this->faction_id . " AND game_id = '" .   $this->game_id . "'"  ;
			$db->sql_query($sql);
			$cache->destroy('sql', $this->bb_factions_table);
		}
		else
		{
			trigger_error(sprintf($user->lang['ADMIN_DELETE_FACTION_FAILED'], $this->game_id, $this->faction_name), E_USER_WARNING);
		}
	}

	/**
	 * deletes all factions from a game
	 */
	public function delete_all_factions()
	{
		global $db, $cache;
		$sql = 'DELETE FROM ' . $this->bb_factions_table . " WHERE game_id = '" .   $this->game_id . "'"  ;
		$db->sql_query($sql);
		$cache->destroy('sql', $this->bb_factions_table);
	}

	/**
	 * get factions for this game
	*
	 * @return array
	 */
	public function get_factions()
	{
		global $db;
		$sql_array = array (
		'SELECT' => ' f.game_id, f.f_index, f.faction_id, f.faction_name, f.faction_hide ',
		'FROM' => array ($this->bb_factions_table => 'f' ),
		'WHERE' => " f.game_id = '" . $this->game_id . "'",
		'ORDER_BY' => 'faction_id ASC ' );
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$fa = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$fa[$row['faction_id']] = array(
			'f_index' => $row['f_index'],
			'faction_name' => $row['faction_name'],
			'faction_id' => $row['faction_id']);
		}
		$db->sql_freeresult($result);
		return $fa;

	}
}
