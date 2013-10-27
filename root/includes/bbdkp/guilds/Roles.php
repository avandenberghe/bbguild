<?php
namespace bbdkp;
/**
 * @package 	bbDKP\Guilds
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
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

// Include the base class

if (!class_exists('\bbdkp\Classes'))
{
	require("{$phpbb_root_path}includes/bbdkp/games/classes/Classes.$phpEx");
}

if (!class_exists('\bbdkp\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
}


/**
 * holds vacancies per guild, game, role and class
 * @package 	bbDKP\Guilds
 *
 */
class Roles 
{
	
	/**
	 * primary key 
	 */
	public $id; 
	
	/**
	 * guild for which we want to recruit
	 * @var int
	 */
	public $guild_id;
	
	/**
	 * game for which we want to recruit, derived from guild
	 * @var string
	 */
	private $game_id;
	
	/**
	 * role needed. derived from Class
	 * @var string
	 */
	public $role;
	
	/**
	 * Class id needed
	 * @var int
	 */
	public $class_id; 
	
	/**
	 * how many
	 * @var int 
	 */
	public $needed; 

	/**
	 * holds possible roles
	 * @var unknown_type
	 */
	public $roles = array();
	
	protected $classrecstatus = array();
	
	protected $classreccolor= array();
	
	/**
	 * class constructor
	 */
	public function __construct($guild_id = 0, $role = '', $class_id = 0, $needed = 0) 
	{
		global $user; 
		
		$this->guild_id = $guild_id;
		$this->role = $role;
		$this->class_id = $class_id;
		$this->needed = $needed;
		

		/**
		 * possible roles that a class can fullfill, as exist in Wow API
		 * note: roleID max 20 chars !!
		 * the role types are *not* stored in database but only in this array. so if you need something else edit this array
		 * the concrete roles are stored in the bbdkp_roles table
		 */
		$this->roles =  array (
				'DPS' 	=> $user->lang ['DAMAGE'],
				'HEAL' 	=> $user->lang ['HEAL'],
				'TANK' 	=> $user->lang ['TANK'],
				'NA' 	=> $user->lang ['NA'],
		);
		
		$this->classrecstatus = array(
				0 => $user->lang['NA'] ,
				1 => $user->lang['CLOSED'] ,
				2 => $user->lang['LOW'] ,
				3 => $user->lang['MEDIUM'] ,
				4 => $user->lang['HIGH']);
		
		$this->classreccolor = array(
				0 => "bullet_white.png" ,
				1 => "bullet_white.png" ,
				2 => "bullet_yellow.png" ,
				3 => "bullet_red.png" ,
				4 => "bullet_purple.png");
	}
	
	/**
	 * inserts one role
	 */
	public function make()
	{
		global $db;
		
		if($this->guild_id == null)
		{
			trigger_error($user->lang['ERROR_GUILDEMPTY'], E_USER_WARNING);
		}
		
		if($this->get($this->guild_id, $this->role, $this->class_id) == 1)
		{
			return 0;  			
		} 
		
		$recruitingguild = new \bbdkp\Guilds($this->guild_id );
		$this->game_id = $recruitingguild->game_id; 
		$query = $db->sql_build_array('INSERT', array(
				'game_id' => $this->game_id, 
				'guild_id' => $this->guild_id,
				'role' => $this->role ,
				'class_id' => $this->class_id,
				'needed' => $this->needed,
		));
		
		$db->sql_query('INSERT INTO ' . BBDKP_ROLES_TABLE . $query);
		return 1;
	}
	
	public function update()
	{
		global $db; 
		$query = $db->sql_build_array('UPDATE', array(
				'needed' => $this->needed,
		));
		$db->sql_query('UPDATE ' . BBDKP_ROLES_TABLE . ' SET ' . $query . ' WHERE id = ' . $this->id);
		
	}
	
	
	/**
	 * for all classes, initialises class roles
	 * 
	 */
	public function init_guildroles()
	{
		global $db;
		
	}
	
	/**
	 * gets how many needed for this role
	 */
	public function get($guild_id, $role, $class_id)
	{
		global $user, $db;
		$sql = 'SELECT id, game_id, guild_id, role, class_id, needed
				FROM ' . BBDKP_ROLES_TABLE . '
				WHERE guild_id = ' . $this->guild_id . "
				AND role = '" . $this->role . "' 
				AND class_id = " . $this->class_id; 
		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if (! $row)
		{
			return 0;
		}
		else
		{
			$this->guild_id = $row['guild_id'];
			$this->game_id = $row['game_id'];
			$this->role = (isset($user->lang[$row['role']])) ? $user->lang[$row['role']] : $row['role'];
			$this->class_id = $row['class_id'];
			$this->needed = $row['needed'];
			
			return 1;
		}
		
	}

	/**
	 * gets all roles needed for one guild
	 */
	public function listroles()
	{
		global $config, $db;
		
		/*$sql = 'SELECT game_id, guild_id, role, class_id, needed
				FROM ' . BBDKP_ROLES_TABLE . '
				WHERE guild_id = ' . $this->guild_id; 
		*/
		
		$sql =  'SELECT a.id as roleid, c.class_id, l.name as class_name, c.colorcode, c.imagename, ';  
		$sql .= ' a.game_id, g.id as guild_id,  a.role, a.needed '; 
		$sql .= ' FROM ' . CLASS_TABLE . ' c ';
		$sql .= ' INNER JOIN ' . BB_LANGUAGE. ' l ON l.attribute_id = c.class_id AND c.game_id = l.game_id ' ;
		$sql .= ' INNER JOIN ' . GUILD_TABLE . ' g ON c.game_id = g.game_id '; 
		$sql .= ' LEFT OUTER JOIN ' . BBDKP_ROLES_TABLE  . ' a ON c.class_id = a.class_id AND a.game_id = c.game_id  ' ; 
		$sql .= " WHERE c.class_id > 0 AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ";
		$sql .= " AND g.id =  " . $this->guild_id  ;  
		$sql .= " ORDER BY c.game_id, c.class_id ";
		
		$result = $db->sql_query($sql);
		return $result;
		
		
	}
	
	/**
	 * deletes a role
	 */
	public function delete()
	{
		global $db;
		$sql = 'DELETE FROM ' . BBDKP_ROLES_TABLE . '
				WHERE guild_id = ' . $this->guild_id . "
				AND role = '" . $this->role . "'
				AND class_id = " . $this->class_id;
		$db->sql_query($sql);
		
	}
	
	
	
}

?>