<?php
/**
 * This file contains the Role class
 * @package bbdkp
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2014 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.1
 * @since 1.3.1
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

// Include the abstract base
if (!class_exists('\bbdkp\controller\games\Game'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/games/Game.$phpEx");
}
/**
 * Classes
 *
 * Manages all Game Classes
 *
 *   @package bbdkp
 */
class Roles extends \bbdkp\controller\games\Game
{
    /**
     * Primary key
     * @var int
     */
    public $role_pkid;

    /**
     * Class id
     * @var INT
     */
    public $role_id;

    /**
     * name of class
     * @var String
     */
    public $rolename;

    /**
     * name of image file
     * @var unknown
     */
    public $role_icon;
    /**
     * class color hex
     * @var string
     */
    public $role_color;

    /**
     * Role constructor
     */
    public function __construct()
    {
        $this->role_pkid = 0;
        $this->role_id = 0;
        $this->rolename = '';
        $this->role_icon = '';
        $this->role_color = '';
    }

    /**
     * gets 1 class from database
     *
     * CREATE TABLE `phpbb_bbdkp_gameroles` (
     * `role_pkid` int(8) NOT NULL AUTO_INCREMENT,
     * `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
     * `role_id` int(8) NOT NULL DEFAULT '0',
     * `role_color` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
     * `role_icon` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
     * PRIMARY KEY (`role_pkid`),
     * UNIQUE KEY `bbroles` (`game_id`,`role_id`)
     * ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
     *
     */
    public function Get()
    {
        global $db, $config;

        $sql_array = array (
            'SELECT' => ' r.role_pkid, r.game_id, l.name AS rolename, r.role_icon, r.role_color ',
            'FROM' => array (
                BB_GAMEROLE_TABLE => 'r', BB_LANGUAGE => 'l' ),
            'WHERE' => " c.role_id = l.attribute_id
							AND l.attribute='role'
							AND l.game_id = '" . $this->game_id . "'
							AND r.game_id = l.game_id
							AND l.language= '" . $config ['bbdkp_lang'] . "'
							AND r.class_id = " . $this->role_id);

        $sql = $db->sql_build_query ( 'SELECT', $sql_array );
        $result = $db->sql_query ( $sql );
        while ( $row = $db->sql_fetchrow ($result))
        {
            $this->role_pkid = $row['role_pkid'];
            $this->role_id = (int) $row['role_id'];
            $this->rolename = (string) $row['rolename'];
            $this->role_icon = (string) $row['role_icon'];
            $this->role_color = (string) $row['role_color'];
        }
        $db->sql_freeresult ( $result );

    }

    /**
     * adds a role to database
     */
    public function Make()
    {
        global $user, $db, $config, $cache;

        $sql = 'SELECT count(*) AS countrole FROM ' . BB_GAMEROLE_TABLE . ' WHERE role_id  = ' .
            $this->role_id . " AND game_id = '" . $this->game_id . "'";
        $resultc = $db->sql_query ($sql);

        if (( int ) $db->sql_fetchfield ( 'countrole', false, $resultc ) > 0)
        {
            trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_ROLE_FAILED'], $this->rolename ), E_USER_WARNING );
        }
        $db->sql_freeresult ( $resultc );
        unset ( $resultc );

        $data = array (
            'game_id' => ( string ) $this->game_id,
            'role_id' => ( int ) $this->role_id,
            'role_icon' => $this->role_icon,
            'role_color' => $this->role_color );

        $db->sql_transaction ( 'begin' );

        $sql = 'INSERT INTO ' . BB_GAMEROLE_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $data );
        $db->sql_query ( $sql );


        $names = array (
            'game_id' => ( string ) $this->game_id,
            'attribute_id' => $this->role_id,
            'language' => $config ['bbdkp_lang'],
            'attribute' => 'role',
            'name' => ( string ) $this->rolename,
            'name_short' => ( string ) $this->rolename );

        $sql = 'INSERT INTO ' . BB_LANGUAGE . ' ' . $db->sql_build_array ( 'INSERT', $names );
        $db->sql_query ( $sql );
        $db->sql_transaction ( 'commit' );
        $cache->destroy ( 'sql', BB_LANGUAGE );
        $cache->destroy ( 'sql', BB_GAMEROLE_TABLE );
    }



    /**
     * updates a class to database
     */
    public function Update(Roles $oldrole)
    {
        global $user, $db, $config, $cache;

        // check for unique role pk exception : if the new role id exists already
        $sql = 'SELECT count(*) AS countrole FROM ' . BB_GAMEROLE_TABLE . '
				WHERE role_pkid != ' . $this->role_pkid . "
				AND role_id = '" . $db->sql_escape ( $oldrole->role_id ) . "'
				AND game_id = '" . $this->game_id. "'";

        $result = $db->sql_query ( $sql );
        if (( int ) $db->sql_fetchfield ( 'countrole', false, $result ) > 0)
        {
            trigger_error ( sprintf ( $user->lang ['ADMIN_ADD_ROLE_FAILED'], $this->rolename ), E_USER_WARNING );
        }
        $db->sql_freeresult ( $result );

        $data = array (
            'game_id' => ( string ) $this->game_id,
            'role_id' => ( int ) $this->role_id,
            'role_icon' => $this->role_icon,
            'role_color' => $this->role_color );

        $db->sql_transaction ( 'begin' );

        $sql = 'UPDATE ' . BB_GAMEROLE_TABLE . ' SET ' . $db->sql_build_array ( 'UPDATE', $data ) . '
			    WHERE c_index = ' . $this->c_index;

        $db->sql_query($sql);

        // now update the language table!
        $names = array (
            'attribute_id' => ( string ) $this->role_id, //new classid
            'name' => ( string ) $this->rolename,
            'name_short' => ( string ) $this->rolename);

        $sql = 'UPDATE ' . BB_LANGUAGE . ' SET ' . $db->sql_build_array ( 'UPDATE', $names ) . '
             WHERE attribute_id = ' . $oldrole->role_id . " AND attribute='role'
             AND language= '" . $config ['bbdkp_lang'] . "' AND game_id = '" . $this->game_id . "'";
        $db->sql_query ( $sql );

        $db->sql_transaction ( 'commit' );
        $cache->destroy ( 'sql', BB_LANGUAGE );
        $cache->destroy ( 'sql', BB_GAMEROLE_TABLE );

    }


    /**
     * deletes a role from database
     */
    public function Delete()
    {
        global $db, $config, $cache;

        $db->sql_transaction ( 'begin' );

        $sql = 'DELETE FROM ' . BB_GAMEROLE_TABLE . ' WHERE role_id  = ' . $this->role_id . " and game_id = '" . $this->game_id . "'";
        $db->sql_query ( $sql );

        $sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE language= '" . $config ['bbdkp_lang'] . "' AND attribute = 'role'
                and attribute_id= " . $this->role_id . " and game_id = '" . $this->game_id . "'";
        $db->sql_query ( $sql );

        $db->sql_transaction ( 'commit' );
        $cache->destroy ( 'sql', CLASS_TABLE );
        $cache->destroy ( 'sql', BB_LANGUAGE );
    }

    /**
     * deletes all roles from a game
     */
    public function Delete_all_roles()
    {
        global $db, $cache;

        $sql = 'DELETE FROM ' . BB_GAMEROLE_TABLE . " WHERE game_id = '" .   $this->game_id . "'"  ;
        $db->sql_query ( $sql );

        $sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE attribute = 'role' AND game_id = '" . $this->game_id . "'";
        $db->sql_query ($sql);

        $cache->destroy ( 'sql', CLASS_TABLE );
        $cache->destroy ( 'sql', BB_LANGUAGE );
    }



    /**
     *
     * lists all roles
     * @param string $order
     * @param int|number $mode
     * @return array
     */
    public function listroles($order= 'role_id', $mode = 0)
    {
        global $db, $config;

        $sql_array = array (
            'SELECT' => ' r.game_id, r.role_pkid, r.role_id, l.name AS rolename, r.role_icon, r.role_color, g.game_name ',
            'FROM' => array (
                CLASS_TABLE => 'c',
                BB_LANGUAGE => 'l',
                BBGAMES_TABLE => 'g' ),
            'WHERE' => " r.class_id = l.attribute_id AND r.game_id = g.game_id
                            AND r.game_id = l.game_id AND l.game_id = '" . $db->sql_escape ( $this->game_id ) . "'
							AND l.attribute='role' AND l.language= '" . $config ['bbdkp_lang'] . "'",
            'ORDER_BY' => $order );

        if($mode == 0)
        {
            $sql_array['WHERE'] .=	'AND r.role_id = ' . $this->role_id;
        }

        $sql = $db->sql_build_query ( 'SELECT', $sql_array );
        $result = $db->sql_query ( $sql );
        $roles=array();
        while ( $row = $db->sql_fetchrow ($result))
        {
            $roles[$row['role_id']]  = array(
                'role_pkid'     => (int) $row['role_pkid'],
                'game_name'     => $row ['game_name'],
                'role_id'       => (int) $row['role_id'],
                'rolename'      => (string) $row['rolename'],
                'role_icon'     => (string) $row['role_icon'],
                'role_color'    => (string) $row['role_color'],
            );
        }
        $db->sql_freeresult ( $result );
        return $roles;
    }
}
