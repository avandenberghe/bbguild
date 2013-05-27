<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */


/*
namespace includes\bbdkp\members;
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
require_once ("{$phpbb_root_path}includes/bbdkp/members/iMembers.$phpEx");

use includes\bbdkp\iMembers;

class Members implements iMembers {
	public $game_id;
	public $member_id;
	public $member_name;
	public $member_status;
	public $member_level;
	public $member_race_id;
	public $member_race;
	public $member_class_id;
	public $member_class;
	public $member_rank_id;
	public $member_comment;
	public $member_joindate;
	public $member_joindate_d;
	public $member_joindate_mo;
	public $member_joindate_y;
	public $member_outdate;
	public $member_outdate_d;
	public $member_outdate_mo;
	public $member_outdate_y;
	public $member_guild_id;
	public $member_guild_name;
	public $member_guild_realm;
	public $member_guild_region;
	public $member_gender_id;
	public $member_achiev;
	public $member_armory_url;
	public $member_portrait_url;
	public $phpbb_user_id;
	public $colorcode;
	public $race_image;
	public $class_image;

	/**
	 */
	function __construct()
	{

	}

	/**
	 * gets a member from database
	 * @see \includes\bbdkp\iMembers::Get()
	 */
	public function Get()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$sql_array = array(
				'SELECT' => 'm.*, c.colorcode , c.imagename,  c1.name AS member_class, l1.name AS member_race,
							r.image_female, r.image_male,
							g.id as guild_id, g.name as guild_name, g.realm , g.region' ,
				'FROM' => array(
						MEMBER_LIST_TABLE => 'm' ,
						CLASS_TABLE => 'c' ,
						BB_LANGUAGE => 'l1' ,
						RACE_TABLE => 'r' ,
						GUILD_TABLE => 'g') ,
				'LEFT_JOIN' => array(
						array(
							'FROM' => array(
								BB_LANGUAGE => 'c1') ,
							'ON' => "c1.attribute_id = c.class_id
							AND c1.game_id = c.game_id
							AND c1.language= '" . $config['bbdkp_lang'] . "'
							AND c1.attribute = 'class'")) ,
				'WHERE' => "
						 l1.attribute_id = r.race_id AND l1.game_id = r.game_id AND l1.language= '" . $config['bbdkp_lang'] . "' AND l1.attribute = 'race'
						AND m.game_id = c.game_id
						AND m.member_class_id = c.class_id
						AND m.game_id = r.game_id
						AND m.member_race_id = r.race_id
						AND m.member_guild_id = g.id
						AND member_id = " . (int) $this->member_id);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row)
		{
			$this->member_id = $row['member_id'] ;
			$this->member_name = $row['member_name'] ;
			$this->member_race_id = $row['member_race_id'] ;
			$this->member_race = $row['member_race'] ;
			$this->member_class_id = $row['member_class_id'] ;
			$this->member_class = $row['member_class'] ;
			$this->member_level = $row['member_level'] ;
			$this->member_rank_id = $row['member_rank_id'] ;
			$this->member_comment = $row['member_comment'] ;
			$this->member_gender_id = $row['member_gender_id'] ;
			$this->member_joindate = $row['member_outdate'] ;
			$this->member_joindate_d = date('j', $row['member_joindate']) ;
			$this->member_joindate_mo = date('n', $row['member_joindate']);
			$this->member_joindate_y = date('Y', $row['member_joindate']) ;
			$this->member_outdate = $row['member_outdate'];
			$this->member_outdate_d = date('j', $row['member_outdate']);
			$this->member_outdate_mo = date('n', $row['member_outdate']);
			$this->member_outdate_y = date('Y', $row['member_outdate']);
			$this->member_guild_name = $row['guild_name'];
			$this->member_guild_id = $row['guild_id'];
			$this->member_guild_realm = $row['realm'];
			$this->member_guild_region = $row['region'];
			$this->member_armory_url = $row['member_armory_url'];
			$this->member_portrait_url = $phpbb_root_path . $row['member_portrait_url'];
			$this->phpbb_user_id = $row['phpbb_user_id'];
			$this->member_status = $row['member_status'];
			$this->game_id = $row['game_id'];
			$this->colorcode = $row['colorcode'];
			$race_image = (string) (($row['member_gender_id'] == 0) ? $row['image_male'] : $row['image_female']);
			$this->race_image = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
			$this->class_image = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';

			return true;
		}
		else
		{
			return false;
		}


	}

	/**
	 * inserts a new member to database
	 * @see \includes\bbdkp\iMembers::Make()
	 */
	public function Make()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$error = array ();

		$this->member_status = 1;

		$sql = 'SELECT count(*) as memberexists
				FROM ' . MEMBER_LIST_TABLE . "
				WHERE UPPER(member_name)= UPPER('" . $db->sql_escape($this->member_name) . "')
				AND member_guild_id = " . $this->member_guild_id;
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('memberexists');
		$db->sql_freeresult($result);
		if ($countm != 0)
		{
			$error[]= $user->lang['ERROR_MEMBEREXIST'];
		}

		$sql = 'SELECT count(*) as rankccount
				FROM ' . MEMBER_RANKS_TABLE . '
				WHERE rank_id=' . (int) $this->member_rank_id . ' and guild_id = ' . (int) $this->member_guild_id;
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('rankccount');
		$db->sql_freeresult($result);
		if ($countm == 0)
		{
			$error[]= $user->lang['ERROR_INCORRECTRANK'];
		}

		$sql = 'SELECT max(class_max_level) as maxlevel FROM ' . CLASS_TABLE;
		$result = $db->sql_query($sql);
		$maxlevel = $db->sql_fetchfield('maxlevel');
		$db->sql_freeresult($result);
		if ($this->member_lvl > $maxlevel)
		{
			$this->member_lvl = $maxlevel;
		}


		$sql = 'SELECT realm, region FROM ' . GUILD_TABLE . ' WHERE id = ' . (int) $this->member_guild_id;
		$result = $db->sql_query($sql);
		$this->member_guild_realm  = $config['bbdkp_default_realm'];
		$this->member_guild_region = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$this->member_guild_realm = $row['realm'];
			$this->member_guild_region = $row['region'];
		}

		if (($this->game_id == 'wow' || $this->game_id == 'aion'))
		{
			$this->member_portrait_url = $this->generate_portraitlink();
		}

		if ($this->game_id == 'wow' & $this->memberarmoryurl == ' ')
		{
			if ($config['bbdkp_default_region'] == '')
			{
				// if region is not set then put EU...
				set_config('bbdkp_default_region', 'EU', true);
			}
			$this->memberarmoryurl = $this->generate_armorylink();
		}

		$query = $db->sql_build_array('INSERT', array(
				'member_name' => ucwords($this->member_name) ,
				'member_status' => $this->member_status ,
				'member_level' => $this->member_level,
				'member_race_id' => $this->member_race_id ,
				'member_class_id' => $this->member_class_id ,
				'member_rank_id' => $this->member_rank_id ,
				'member_comment' => (string) $this->member_comment ,
				'member_joindate' => (int) $this->member_joindate ,
				'member_outdate' => (int) $this->member_outdate ,
				'member_guild_id' => $this->member_guild_id ,
				'member_gender_id' => $this->member_gender_id ,
				'member_achiev' => $this->member_achiev ,
				'member_armory_url' => (string) $this->member_armory_url ,
				'phpbb_user_id' => (int) $this->phpbb_user_id ,
				'game_id' => (string) $this->game_id ,
				'member_portrait_url' => (string) $this->member_portrait_url));

		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

		$log_action = array(
				'header' 	 => 'L_ACTION_MEMBER_ADDED' ,
				'L_NAME' 	 => ucwords($this->member_name)  ,
				'L_LEVEL' 	 => $this->member_level,
				'L_RACE' 	 => $this->member_race_id,
				'L_CLASS' 	 => $this->member_class_id,
				'L_ADDED_BY' => $user->data['username']);

		$db->sql_query('INSERT INTO ' . MEMBER_LIST_TABLE . $query);

		$this->member_id = $db->sql_nextid();

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		return $this->member_id;

	}


	/**
	 * updates a member to database
	 * @see \includes\bbdkp\iMembers::Update()
	 */
	public function Update($old_member)
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		// if user chooses other name then check if it already exists. if so refuse update
		// namechange to existing membername is not allowed
		if($this->member_name != $old_member->member_name)
		{
			$sql = 'SELECT count(*) as memberexists
								FROM ' . MEMBER_LIST_TABLE . '
								WHERE member_id <> ' . $updatemember->member_id . "
								AND UPPER(member_name)= UPPER('" . $db->sql_escape($this->member_name) . "')";
			$result = $db->sql_query($sql);
			$countm = $db->sql_fetchfield('memberexists');
			$db->sql_freeresult($result);
			if ($countm != 0)
			{
				trigger_error(sprintf($user->lang['ADMIN_UPDATE_MEMBER_FAIL'], ucwords($this->member_name)) . $this->link, E_USER_WARNING);
			}
		}

		// check if rank exists
		$sql = 'SELECT count(*) as rankccount
				FROM ' . MEMBER_RANKS_TABLE . '
				WHERE rank_id=' . (int) $this->member_rank_id . ' and guild_id = ' . request_var('member_guild_id', 0);
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('rankccount');
		$db->sql_freeresult($result);
		if ($countm == 0)
		{
			trigger_error($user->lang['ERROR_INCORRECTRANK'] . $this->link, E_USER_WARNING);
		}

		// check level
		$sql = 'SELECT max(class_max_level) as maxlevel FROM ' . CLASS_TABLE;
		$result = $db->sql_query($sql);
		$maxlevel = $db->sql_fetchfield('maxlevel');
		$db->sql_freeresult($result);
		if ($this->member_level > $maxlevel)
		{
			$this->member_level = $maxlevel;
		}


		// update the data including the phpbb userid
		$query = $db->sql_build_array('UPDATE', array(
				'member_name' => $this->member_name ,
				'member_status' => $this->member_status ,
				'member_level' => $this->member_level ,
				'member_race_id' => $this->member_race_id ,
				'member_class_id' => $this->member_class_id,
				'member_rank_id' => $this->member_rank_id ,
				'member_gender_id' => $this->member_gender_id ,
				'member_comment' => $this->member_comment ,
				'member_guild_id' => $this->member_guild_id,
				'member_outdate' => $this->member_outdate,
				'member_joindate' => $this->member_joindate,
				'phpbb_user_id' => $this->phpbb_user_id,
				'game_id' => $this->game_id));

		$db->sql_query('UPDATE ' . MEMBER_LIST_TABLE . ' SET ' . $query . ' WHERE member_id= ' . $this->member_id);

		// log it
		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

		$log_action = array(
				'header' => 'L_ACTION_MEMBER_UPDATED' ,
				'L_NAME_BEFORE' => $old_member->member_name ,
				'L_LEVEL_BEFORE' => $old_member->member_level ,
				'L_RACE_BEFORE' => $old_member->member_race_id ,
				'L_CLASS_BEFORE' => $old_member->member_class_id ,
				'L_NAME_AFTER' => $this->member_name,
				'L_LEVEL_AFTER' => $this->member_level,
				'L_RACE_AFTER' => $this->member_race_id ,
				'L_CLASS_AFTER' => $this->member_class_id ,
				'L_UPDATED_BY' => $user->data['username']);

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		unset($bbdkp);



	}

	/**
	 * deletes a member from database
	 * @see \includes\bbdkp\iMembers::Delete()
	 */
	public function Delete()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$sql = 'DELETE FROM ' . RAID_DETAIL_TABLE . ' where member_id = ' . (int) $this->member_id;
		$db->sql_query($sql);
		$sql = 'DELETE FROM ' . RAID_ITEMS_TABLE . ' where member_id = ' . (int) $this->member_id;
		$db->sql_query($sql);
		$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . ' where member_id = ' . (int) $this->member_id;
		$db->sql_query($sql);
		$sql = 'DELETE FROM ' . ADJUSTMENTS_TABLE . ' where member_id = ' . (int) $this->member_id;
		$db->sql_query($sql);
		$sql = 'DELETE FROM ' . MEMBER_LIST_TABLE . ' where member_id = ' . (int) $this->member_id;
		$db->sql_query($sql);

		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

		$log_action = array(
				'header' => sprintf($user->lang['ACTION_MEMBER_DELETED'], $this->member_name) ,
				'L_NAME' => $this->member_name ,
				'L_LEVEL' => $this->member_level ,
				'L_RACE' => $this->member_race_id ,
				'L_CLASS' => $this->member_class_id);

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

	}

	/**
	 * generates armory link (only wow)
	*/
	private function generate_armorylink ()
	{
		global $config;
		$site = '';
		switch ($config['bbdkp_default_region'])
		{
			case 'EU':
				$site = 'http://eu.battle.net/wow/en/character/';
				break;
			case 'US':
				$site = 'http://us.battle.net/wow/en/character/';
				break;
			default:
				$site = 'http://eu.battle.net/wow/en/character/';
		}
		return $site . urlencode(str_replace(' ', '-', $this->member_guild_realm)) . '/' . urlencode($this->member_name) . '/simple';
	}


	/*
	 * generates a standard portrait image url for wow /aion based on characterdata
	*/
	private function generate_portraitlink ()
	{
		$memberportraiturl = '';
		if ($this->game_id == 'aion')
		{
			$this->memberportraiturl = 'images/roster_portraits/aion/' . $this->member_race_id . '_' . $this->member_gender_id . '.jpg';
		}

		elseif ($this->$game_id == 'wow')
		{
			if ($this->member_level <= "59")
			{
				$maxlvlid = "wow-default";
			}
			elseif ($this->member_level <= 69)
			{
				$maxlvlid = "wow";
			}
			elseif ($this->member_level <= 79)
			{
				$maxlvlid = "wow-70";
			}
			else
			{
				// level 85 is not yet iconified
				$maxlvlid = "wow-80";
			}
			$this->memberportraiturl = 'images/roster_portraits/' . $maxlvlid . '/' . $this->member_gender_id . '-' . $this->member_race_id . '-' . $this->member_class_id . '.gif';
		}

	}


}

?>