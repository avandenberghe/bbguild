<?php
/**
 *
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

// Include the abstract base
if (!class_exists('\bbdkp\Game'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/Game.$phpEx");
}
/**
 * Classes
 * 
 * Manages all Game Classes
 * 
 * @package 	bbDKP
 */
 class Classes extends \bbdkp\Game
 {

	public $game_id; 
	public $class_id;
	public $c_index;
	public $faction_id;
	public $min_level;
	public $max_level;
	public $armor_type;
	public $classname;
	public $hide;
	public $dps;
	public $tank;
	public $heal;
	public $imagename;
	public $colorcode;

	/**
	 */
	public function __construct() 
	{
		$this->game_id = '';
		$this->c_index = 0;
		$this->class_id = 0;
		$this->classname = '';
		$this->min_level = 0;
		$this->max_level = 0;
		$this->armor_type = '';
		$this->imagename = '';
		$this->colorcode = '';
		$this->faction_id = 0;
	
	}
	
	/**
	 * gets class from database
	 * @see \bbdkp\iClasses::Get()
	 */
	public function Get()
	{
		global $user, $db, $config, $phpEx, $cache, $phpbb_root_path;
		
		$sql_array = array (
				'SELECT' => ' c.c_index, c.class_id, l.name AS class_name, c.class_min_level, c.class_max_level, c.class_faction_id, 
							c.class_armor_type, c.imagename, c.colorcode ',
				'FROM' => array (
						CLASS_TABLE => 'c', BB_LANGUAGE => 'l' ),
				'WHERE' => " c.class_id = l.attribute_id
							AND l.attribute='class'
							AND l.game_id = '" . $this->game_id . "'
							AND c.game_id = l.game_id
							AND l.language= '" . $config ['bbdkp_lang'] . "'
							AND c.class_id = " . $this->class_id);
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		while ( $row = $db->sql_fetchrow ($result))
		{
			$this->c_index = $row['c_index'];
			$this->class_id = (int) $row['class_id'];
			$this->classname = (string) $row['class_name'];
			$this->min_level = (int) $row['class_min_level'];
			$this->max_level = (int) $row['class_max_level'];
			$this->armor_type = (string) $row['class_armor_type'];
			$this->imagename = (string) $row['imagename'];
			$this->colorcode = (string) $row['colorcode'];
			$this->faction_id = (string) $row['class_faction_id']; 
		}
		$db->sql_freeresult ( $result );
		
	}

	/**
	 * adds a class to database
	 */
	public function Make()
	{
		global $user, $db, $config, $phpEx, $cache, $phpbb_root_path;
		
		$sql = 'SELECT count(*) AS countclass FROM ' . CLASS_TABLE . ' WHERE class_id  = ' . 
			$this->class_id . " AND game_id = '" . $this->game_id . "'";
		$resultc = $db->sql_query ($sql);
			
		if (( int ) $db->sql_fetchfield ( 'countclass', false, $resultc ) > 0)
		{
			trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_CLASS_FAILED'], $this->classname ), E_USER_WARNING );
		}
		$db->sql_freeresult ( $resultc );
		unset ( $resultc );
			
		$data = array (
				'class_id' => ( int ) $this->class_id,
				'game_id' => ( string ) $this->game_id,
				'class_min_level' => ( int ) $this->min_level,
				'class_max_level' => ( int ) $this->max_level,
				'class_armor_type' => ( string ) $this->armor_type,
				'imagename' => $this->imagename,
				'class_hide' => 0,
				'colorcode' => $this->colorcode );
			
		$db->sql_transaction ( 'begin' );
			
		$sql = 'INSERT INTO ' . CLASS_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $data );
		$db->sql_query ( $sql );
			
		$id = $db->sql_nextid ();
			
		$names = array (
				'game_id' => ( string ) $this->game_id,
				'attribute_id' => $this->class_id,
				'language' => $config ['bbdkp_lang'],
				'attribute' => 'class',
				'name' => ( string ) $this->classname,
				'name_short' => ( string ) $this->classname );
			
		$sql = 'INSERT INTO ' . BB_LANGUAGE . ' ' . $db->sql_build_array ( 'INSERT', $names );
		$db->sql_query ( $sql );
			
		$db->sql_transaction ( 'commit' );
		$cache->destroy ( 'sql', BB_LANGUAGE );
		$cache->destroy ( 'sql', CLASS_TABLE );
	}
	
	/**
	 * deletes a class from database
	 */
	public function Delete()
	{
		global $user, $db, $config, $phpEx, $cache, $phpbb_root_path;
		
		// see if there are members in this class
		$sql_array = array (
				'SELECT' => ' c.class_id, COUNT(*) AS classcount  ',
				'FROM' => array (
						MEMBER_LIST_TABLE => 'm',
						CLASS_TABLE => 'c' ),
				'WHERE' => 	"m.game_id = c.game_id AND m.game_id = '" . $this->game_id . "'
    					and m.member_class_id = c.class_id AND c.class_id =  " . $this->class_id ,
				'GROUP_BY' => 'c.class_id'
		);
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		$classcount = ( int ) $db->sql_fetchfield ( 'classcount', false, $result );
		$db->sql_freeresult ( $result );
		
		
		if ($classcount == 0)
		{
			$db->sql_transaction ( 'begin' );
			
			$sql = 'DELETE FROM ' . CLASS_TABLE . ' WHERE class_id  = ' . $this->class_id . " and game_id = '" . $this->game_id . "'";
			$db->sql_query ( $sql );
			
			$sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE language= '" . $config ['bbdkp_lang'] . "' AND attribute = 'class'
					and attribute_id= " . $this->class_id . " and game_id = '" . $this->game_id . "'";
			$db->sql_query ( $sql );
			
			$db->sql_transaction ( 'commit' );
			$cache->destroy ( 'sql', CLASS_TABLE );
			$cache->destroy ( 'sql', BB_LANGUAGE );
		}
		else
		{
			trigger_error ( sprintf ( $user->lang ['ADMIN_DELETE_CLASS_FAILED'], $this->class_id ) , E_USER_WARNING );
		}
		
		
		
	}
	
	/**
	 * deletes all classes from a game
	 */
	public function Delete_all_classes()
	{
		global $db, $user, $cache;
	
		$sql = 'DELETE FROM ' . CLASS_TABLE . " WHERE game_id = '" .   $this->game_id . "'"  ;
		$db->sql_query ( $sql );
	
		$sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE attribute = 'class'
							AND game_id = '" . $this->game_id . "'";
		$db->sql_query ($sql);
	
		$cache->destroy ( 'sql', CLASS_TABLE );
		$cache->destroy ( 'sql', BB_LANGUAGE );
	}
	
	
	
	/**
	 * updates a class to database
	 */
	public function Update(Classes $oldclass)
	{
		global $user, $db, $config, $phpEx, $cache, $phpbb_root_path;
		
		// check for unique classid exception : if the new class id exists already
		$sql = 'SELECT count(*) AS countclass FROM ' . CLASS_TABLE . ' 
				WHERE c_index != ' . $this->c_index . " 
				AND class_id = '" . $db->sql_escape ( $oldclass->class_id ) . "' 
				AND game_id = '" . $this->game_id. "'";
		
		$result = $db->sql_query ( $sql );
		if (( int ) $db->sql_fetchfield ( 'countclass', false, $result ) > 0)
		{
			trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_CLASS_FAILED'], $this->classname ), E_USER_WARNING );
		}
		$db->sql_freeresult ( $result );
			
		$data = array (
				'class_id' => ( int ) $this->class_id,
				'class_min_level' => ( int ) $this->min_level,
				'class_max_level' => ( int ) $this->max_level,
				'class_armor_type' => ( string ) $this->armor_type,
				'imagename' => $this->imagename,
				'class_hide' => 0,
				'colorcode' => $this->colorcode );
		$db->sql_transaction ( 'begin' );
		
		$sql = 'UPDATE ' . CLASS_TABLE . ' SET ' . $db->sql_build_array ( 'UPDATE', $data ) . '
			    WHERE c_index = ' . $this->c_index;
		
		$db->sql_query($sql);
			
		// now update the language table!
		$names = array (
				'attribute_id' => ( string ) $this->class_id, //new classid
				'name' => ( string ) $this->classname,
				'name_short' => ( string ) $this->classname);
		
		$sql = 'UPDATE ' . BB_LANGUAGE . ' SET ' . $db->sql_build_array ( 'UPDATE', $names ) . '
		 WHERE attribute_id = ' . $oldclass->class_id . " AND attribute='class'
		 AND language= '" . $config ['bbdkp_lang'] . "' AND game_id = '" . $this->game_id . "'";
		$db->sql_query ( $sql );
			
		$db->sql_transaction ( 'commit' );
		$cache->destroy ( 'sql', BB_LANGUAGE );
		$cache->destroy ( 'sql', CLASS_TABLE );
		
	}
	
	
	/**
	 * lists all classes
	 * @return array
	 */
	public function listclasses()
	{
		global $user, $db, $config, $phpEx, $cache, $phpbb_root_path;
		
		$sql_array = array (
				'SELECT' => ' c.c_index, c.class_id, l.name AS class_name, c.class_min_level, c.class_max_level, c.class_armor_type, c.imagename, c.colorcode ',
				'FROM' => array (
						CLASS_TABLE => 'c', BB_LANGUAGE => 'l' ),
				'WHERE' => " c.class_id = l.attribute_id
							AND l.attribute='class'
							AND l.game_id = '" . $db->sql_escape ( $this->game_id ) . "'
							AND c.game_id = l.game_id
							AND l.language= '" . $config ['bbdkp_lang'] . "'
							AND c.class_id = " . $this->class_id );
		
		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		return $result; 
	}
	
}

?>