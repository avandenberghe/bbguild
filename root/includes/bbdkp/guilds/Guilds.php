<?php
namespace bbdkp;
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
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
require_once ("{$phpbb_root_path}includes/bbdkp/guilds/iGuilds.$phpEx");

// Include the base class

if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/Admin.$phpEx");
}


/**
 * Guild
 * 
 * Manages Guild creation
 * 
 * @package 	bbDKP
 */
 class Guild extends \bbdkp\Admin implements iGuilds
{
	public $guildid = 0;
	public $name = '';
	public $realm = '';
	public $region = '';
	public $achievements = 0;
	public $membercount = 0;
	public $startdate = 0;
	public $showroster = 0;
	public $aionlegionid = 0;
	public $aionserverid = 0;

	/**
	 */
	function __construct($guild_id)
	{
		if($guild_id !=0)
		{
			$this->guildid = $guild_id;
			$this->Get();
			$this->countmembers();
		}
	}

	/**
	 * gets a guild from database
	 * @see \bbdkp\iGuilds::Get()
	 */
	public function Get()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$sql = 'SELECT id, name, realm, region, roster
				FROM ' . GUILD_TABLE . '
				WHERE id = ' . $this->guildid;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if (! $row)
		{
			$this->guildid = 0;
			$this->name = '';
			$this->realm = '';
			$this->region = '';
			$this->showroster = 0;
		}
		else
		{
			// load guild object
			$this->guildid = $row['id'];
			$this->name = $row['name'];
			$this->realm = $row['realm'];
			$this->region = $row['region'];
			$this->showroster = $row['roster'];
		}


	}

	/**
	 * inserts a new guild to database
	 * we always add guilds with an id greater then zero. this way, the guild with id=zero is the "guildless" guild
	 * the zero guild is added by default in a new install.
	 * do not delete the zero record in the guild table or you will see that guildless members
	 * become invisible in the roster and in the memberlist or in any list member selection that makes
	 * an inner join with the guild table.
	 * @see \bbdkp\iGuilds::Make()
	 */
	public function Make()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$error = array ();

		if ($this->name == null || $this->realm == null)
		{
			trigger_error($user->lang['ERROR_GUILDEMPTY'] . $this->link, E_USER_WARNING);
		}

		// check existing guild-realmname
		$result = $db->sql_query("SELECT count(*) as evcount from " . GUILD_TABLE . "
			WHERE id !=0 AND UPPER(name) = '" . strtoupper($db->sql_escape($this->name)) . "'
			AND UPPER(realm) = '" . strtoupper($db->sql_escape($this->realm)) . "'");
		$grow = $db->sql_fetchrow($result);

		if ($grow['evcount'] != 0)
		{
			trigger_error($user->lang['ERROR_GUILDTAKEN'] . $this->link, E_USER_WARNING);
		}

		$result = $db->sql_query("SELECT MAX(id) as id FROM " . GUILD_TABLE . ";");
		$row = $db->sql_fetchrow($result);
		$this->guildid = (int) $row['id'] + 1;

		//@todo complete this
		$this->aionlegionid = 0;
		$this->aionserverid = 0;

		$query = $db->sql_build_array('INSERT', array(
				'id' => $this->guildid,
				'name' => $this->name ,
				'realm' => $this->realm,
				'region' => $this->region ,
				'roster' => $this->showroster ,
				'aion_legion_id' => $this->aionlegionid ,
				'aion_server_id' => $this->aionserverid
			));

		$db->sql_query('INSERT INTO ' . GUILD_TABLE . $query);

		//add a default rank
		if (!class_exists('Ranks'))
		{
			require("{$phpbb_root_path}includes/bbdkp/ranks/Ranks.$phpEx");
		}
		$newrank = new Ranks();
		$newrank->RankName = "Member";
		$newrank->RankId = 0;
		$newrank->GuildId = $this->guildid;
		$newrank->RankHide = 0;
		$newrank->RankPrefix = '';
		$newrank->RankSuffix = '';
		$newrank->Make();
		
		$log_action = array(
				'header' => 'L_ACTION_GUILD_ADDED' ,
				'id' =>  $this->guildid ,
				'L_USER' => $user->data['user_id'] ,
				'L_USERCOLOUR' => $user->data['user_colour'] ,
				'L_NAME' => $this->name ,
				'L_REALM' => $this->realm ,
				'L_ADDED_BY' => $user->data['username']);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));
		return  $this->guildid;
		

	}


	/**
	 * updates a guild to database
	 * @see \bbdkp\iMembers::Update()
	 */
	public function Update($old_guild)
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		// check if already exists
		if($this->name != $old_guild->name || $this->realm != $old_guild->realm)
		{
				// check existing guild-realmname
			$result = $db->sql_query("SELECT count(*) as evcount from " . GUILD_TABLE . "
				WHERE UPPER(name) = '" . strtoupper($db->sql_escape($this->name)) . "'
				AND UPPER(realm) = '" . strtoupper($db->sql_escape($this->realm)) . "'");
			$grow = $db->sql_fetchrow($result);
			if ($grow['evcount'] != 0)
			{
				trigger_error($user->lang['ERROR_GUILDTAKEN'] . $this->link, E_USER_WARNING);
			}
		}

		$query = $db->sql_build_array('UPDATE', array(
				'id' => $this->guildid,
				'name' => $this->name ,
				'realm' => $this->realm,
				'region' => $this->region ,
				'roster' => $this->showroster ,
				'aion_legion_id' => $this->aionlegionid ,
				'aion_server_id' => $this->aionserverid
		));

		$db->sql_query('UPDATE ' . GUILD_TABLE . ' SET ' . $query . ' WHERE id= ' . $this->guildid);

		$log_action = array(
				'header' => 'L_ACTION_GUILD_UPDATED' ,
				'L_NAME_BEFORE' => $old_guild->name ,
				'L_REALM_BEFORE' => $old_guild->realm ,
				'L_NAME_AFTER' => $this->name,
				'L_REALM_AFTER' => $this->realm,
				'L_UPDATED_BY' => $user->data['username']);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));


	}

	/**
	 * deletes a guild from database
	 * @see \bbdkp\iMembers::Delete()
	 */
	public function Delete()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		if($this->guildid == 0)
		{
			trigger_error($user->lang['ERROR_INVALID_GUILD_PROVIDED'], E_USER_WARNING);
		}

		if ($this->guildid < 2)
		{
			trigger_error($user->lang['ERROR_GUILDIDRESERVED'], E_USER_WARNING);
		}

		// check if guild has members
		$sql = 'SELECT COUNT(*) as mcount FROM ' . MEMBER_LIST_TABLE . '
           WHERE member_guild_id = ' . $this->guildid;
		$result = $db->sql_query($sql);
		if ((int) $db->sql_fetchfield('mcount') >= 1)
		{
			trigger_error($user->lang['ERROR_GUILDHASMEMBERS'], E_USER_WARNING);
		}

		$sql = 'DELETE FROM ' . MEMBER_RANKS_TABLE . ' WHERE guild_id = ' .  $this->guildid;
		$db->sql_query($sql);

		$sql = 'DELETE FROM ' . GUILD_TABLE . ' WHERE id = ' .  $this->guildid;
		$db->sql_query($sql);

		$log_action = array(
				'header' => sprintf($user->lang['ACTION_GUILD_DELETED'], $this->name) ,
				'L_NAME' => $this->name ,
				'L_UPDATED_BY' => $user->data['username'],
				);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));


	}

	/**
	 * returns a member listing for this guild
	 * @param string $order
	 * @param int $start
	 * @return array
	 */
	public function listmembers($order, $start)
	{

		global $user, $db, $config, $phpEx, $phpbb_root_path;
		$sql_array = array(
				'SELECT' => 'm.* , u.username, u.user_id, u.user_colour, g.name, l.name as member_class, r.rank_id,
			    				r.rank_name, r.rank_prefix, r.rank_suffix,
								 c.colorcode , c.imagename, m.member_gender_id, a.image_female, a.image_male' ,
				'FROM' => array(
						MEMBER_LIST_TABLE => 'm' ,
						MEMBER_RANKS_TABLE => 'r' ,
						CLASS_TABLE => 'c' ,
						RACE_TABLE => 'a' ,
						BB_LANGUAGE => 'l' ,
						GUILD_TABLE => 'g') ,
				'LEFT_JOIN' => array(
						array(
								'FROM' => array(
										USERS_TABLE => 'u') ,
								'ON' => 'u.user_id = m.phpbb_user_id ')) ,
				'WHERE' => " (m.member_rank_id = r.rank_id)
			    				and m.game_id = l.game_id
			    				AND l.attribute_id = c.class_id and l.game_id = c.game_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
								AND (m.member_guild_id = g.id)
								AND (m.member_guild_id = r.guild_id)
								AND (m.member_guild_id = " . $this->guildid . ')
								AND m.game_id =  a.game_id
								AND m.game_id =  c.game_id
								AND m.member_race_id =  a.race_id
								AND (m.member_class_id = c.class_id)' ,
				'ORDER_BY' => $order);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$members_result = $db->sql_query_limit($sql, $config['bbdkp_user_llimit'], $start);
		return $members_result;

	}

	/**
	 * counts all guild members
	 * @see \bbdkp\iGuilds::countmembers()
	 */
	public function countmembers()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;
		//get total members
		$sql_array = array(
				'SELECT' => 'count(*) as membercount ' ,
				'FROM' => array(
						MEMBER_LIST_TABLE => 'm' ,
						MEMBER_RANKS_TABLE => 'r' ,
						CLASS_TABLE => 'c' ,
						RACE_TABLE => 'a' ,
						BB_LANGUAGE => 'l' ,
						GUILD_TABLE => 'g') ,
				'LEFT_JOIN' => array(
						array(
								'FROM' => array(
										USERS_TABLE => 'u') ,
								'ON' => 'u.user_id = m.phpbb_user_id ')) ,
				'WHERE' => " (m.member_rank_id = r.rank_id)
				    				and m.game_id = l.game_id
				    				AND l.attribute_id = c.class_id and l.game_id = c.game_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
									AND (m.member_guild_id = g.id)
									AND (m.member_guild_id = r.guild_id)
									AND (m.member_guild_id = " . $this->guildid . ')
									AND m.game_id =  a.game_id
									AND m.game_id =  c.game_id
									AND m.member_race_id =  a.race_id
									AND (m.member_class_id = c.class_id)');
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$total_members = (int) $db->sql_fetchfield('membercount');
		$db->sql_freeresult($result);
		$this->membercount = $total_members;

	}

}

?>