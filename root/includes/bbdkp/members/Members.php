<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * @since 1.2.9
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
require ("{$phpbb_root_path}includes/bbdkp/members/iMembers.$phpEx");

// Include the abstract base
if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

/**
 * manages member creation
 * 
 * @package 	bbDKP
 * 
 */
 class Members extends \bbdkp\Admin implements \bbdkp\iMembers 
{
	
	/**
	 * game id
	 */
    public $game_id;

    /**
     * primary key in the bbDKP membertable
     * @var integer
     */
	public $member_id;

    /**
     * utF-8 member name
     * @var string
     */
	public $member_name;

    /**
     * status (0 or 1)
     * @var unknown_type
     */
	public $member_status;

	/**
	 * level
	 * @var integer
	 */
	public $member_level;

	public $member_race_id;
	public $member_race;
	public $member_class_id;
	public $member_class;

	/**
	 * guild rankid
	 * @var unknown_type
	 */
	public $member_rank_id;

	/**
	 * administrator comment
	 * @var unknown_type
	 */
	public $member_comment;

	public $member_joindate;
	public $member_joindate_d;
	public $member_joindate_mo;
	public $member_joindate_y;
	public $member_outdate;
	public $member_outdate_d;
	public $member_outdate_mo;
	public $member_outdate_y;

	/**
	 * the id of my guild
	 * @var unknown_type
	 */
	public $member_guild_id;

	/**
	 * my guildname
	 * @var unknown_type
	 */
	public $member_guild_name;

	/**
	 * character realm
	 * @var unknown_type
	 */
	public $member_realm;

	/**
	 * region to which the char is on
	 * @var unknown_type
	 */
	public $member_region;

	/**
	 * Allowed regions
	 * @var array
	 */
	protected $regionlist = array( 'eu', 'us' , 'kr', 'tw', 'cn', 'sea');
	
	/**
	 *gender ID 0=male, 1=female
	 * @var unknown_type
	 */
	public $member_gender_id;

	/**
	 * Achievement points
	 * @var unknown_type
	 */
	public $member_achiev;

	/**
	 * url to armory
	 * @var string
	 */
	public $member_armory_url;


	public $member_portrait_url;

	/**
	 * The phpBB member id linked to this member
	 * @var unknown_type
	 */
	public $phpbb_user_id;

	/**
	 * Class color
	 * @var unknown_type
	 */
	public $colorcode;

	/**
	 * Race icon
	 * @var unknown_type
	 */
	public $race_image;

	/**
	 * Class icon
	 * @var unknown_type
	 */
	public $class_image;

	/**
	 * current talent builds
	 * @var string
	 */
	public $talents;
	
	/**
	 * current title
	 */
	public $member_title;

	/**
	 */
	function __construct()
	{
		global $user;
		$this->games = array (
				'wow' 	=> $user->lang ['WOW'],
				'lotro' => $user->lang ['LOTRO'],
				'eq' 	=> $user->lang ['EQ'],
				'daoc' 	=> $user->lang ['DAOC'],
				'vanguard' => $user->lang ['VANGUARD'],
				'eq2' 	=> $user->lang ['EQ2'],
				'warhammer' => $user->lang ['WARHAMMER'],
				'aion' 	=> $user->lang ['AION'],
				'FFXI' 	=> $user->lang ['FFXI'],
				'rift' 	=> $user->lang ['RIFT'],
				'swtor' => $user->lang ['SWTOR'],
				'lineage2' => $user->lang ['LINEAGE2'],
				'tera' 	=> $user->lang ['TERA'],
				'gw2' 	=> $user->lang ['GW2'],
		);
		
	}

	/**
	 * gets a member from database
	 * @see \bbdkp\iMembers::Get()
	 */
	public function Getmember()
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
			$this->member_realm = $row['realm'];
			$this->member_region = $row['region'];
			$this->member_armory_url = $row['member_armory_url'];
			$this->member_portrait_url = $row['member_portrait_url'];
			$this->phpbb_user_id = $row['phpbb_user_id'];
			$this->member_status = $row['member_status'];
			$this->member_achiev = $row['member_achiev'];
			$this->game_id = $row['game_id'];
			$this->colorcode = $row['colorcode'];
			$race_image = (string) (($row['member_gender_id'] == 0) ? $row['image_male'] : $row['image_female']);
			$this->race_image = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
			$this->class_image = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';
			//$this->member_title  = $row['member_title'];
			
			return true;
		}
		else
		{
			return false;
		}


	}

	/**
	 * get member id given a membername and guild
	 *
	 * @param string $membername
	 * @param int $guild_id optional
	 * @return int
	 */
	public function get_member_id ($membername, $guild_id = 0)
	{
		global $db;
		if($guild_id !=0)
		{
			$sql = 'SELECT member_id
	                FROM ' . MEMBER_LIST_TABLE . "
	                WHERE member_name ='" . $db->sql_escape($membername) . "'
	                AND member_guild_id = " . (int) $db->sql_escape($guild_id);

		}
		else
		{
			$sql = 'SELECT member_id
	                FROM ' . MEMBER_LIST_TABLE . "
	                WHERE member_name ='" . $db->sql_escape($membername) . "'";
		}

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$membid = $row['member_id'];
			break;
		}
		$db->sql_freeresult($result);
		if (isset($membid))
		{
			return $membid;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * 
	 * @see \bbdkp\iMembers::Make()
	 */
	public function Makemember()
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
		if ($this->member_level > $maxlevel)
		{
			$this->member_level = $maxlevel;
		}


		$sql = 'SELECT realm, region FROM ' . GUILD_TABLE . ' WHERE id = ' . (int) $this->member_guild_id;
		$result = $db->sql_query($sql);
		$this->member_realm  = $config['bbdkp_default_realm'];
		$this->member_region = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$this->member_realm = $row['realm'];
			$this->member_region = $row['region'];
		}

		switch ($this->game_id)
		{
			case 'wow':
				$this->Armory_getmember();
			case 'aion':
				$this->member_portrait_url = $this->generate_portraitlink();
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
				'member_portrait_url' => (string) $this->member_portrait_url, 
				'member_title' => $this->member_title
				));

		$db->sql_query('INSERT INTO ' . MEMBER_LIST_TABLE . $query);

		$this->member_id = $db->sql_nextid();

		$log_action = array(
				'header' 	 => 'L_ACTION_MEMBER_ADDED' ,
				'L_NAME' 	 => ucwords($this->member_name)  ,
				'L_LEVEL' 	 => $this->member_level,
				'L_RACE' 	 => $this->member_race_id,
				'L_CLASS' 	 => $this->member_class_id,
				'L_ADDED_BY' => $user->data['username']);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		return $this->member_id;

	}


	/**
	 * 
	 * @see \bbdkp\iMembers::Update()
	 */
	public function Updatemember(Members $old_member)
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		if ($this->member_id == 0)
		{
		    return false;
		}
		
		// if user chooses other name then check if the new name already exists. if so refuse update
		// namechange to existing membername is not allowed
		if($this->member_name != $old_member->member_name)
		{
			$sql = 'SELECT count(*) as memberexists
								FROM ' . MEMBER_LIST_TABLE . '
								WHERE member_id <> ' . $this->member_id . "
								AND UPPER(member_name)= UPPER('" . $db->sql_escape($this->member_name) . "')";
			$result = $db->sql_query($sql);
			$countm = $db->sql_fetchfield('memberexists');
			$db->sql_freeresult($result);
			if ($countm != 0)
			{
				trigger_error(sprintf($user->lang['ADMIN_UPDATE_MEMBER_FAIL'], ucwords($this->member_name)) , E_USER_WARNING);
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
			trigger_error($user->lang['ERROR_INCORRECTRANK'], E_USER_WARNING);
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
		
		switch ($this->game_id)
		{
			case 'wow':
				$this->Armory_getmember();
				break;
			case 'aion':
				if(trim($this->member_portrait_url) == '')
				{
					$this->member_portrait_url = $this->generate_portraitlink();
				}
				break;
		}
		
		// Get first and last raiding dates from raid table.
		$sql = "SELECT b.member_id,
		        MIN(a.raid_start) AS startdate,
		        MAX(a.raid_start) AS enddate
			FROM " . RAIDS_TABLE . " a
			INNER JOIN " . RAID_DETAIL_TABLE . " b on a.raid_id = b.raid_id
			WHERE  b.member_id = " . $this->member_id . "
			GROUP BY b.member_id ";
		$result = $db->sql_query($sql);
		$startraiddate = (int) $db->sql_fetchfield('startdate', false, $result);
		$endraiddate = (int) $db->sql_fetchfield('enddate', false, $result);
		$db->sql_freeresult($result);

		// if first recorded raiddate is before joindate then update joindate
		if ($startraiddate != 0 && ($this->member_joindate == 0 || $this->member_joindate > $startraiddate))
		{
		    $this->member_joindate = $startraiddate;
		}

		// if last raiddate is after outdate or outdate is in future then reset it
		if ($endraiddate !=0 && ($this->member_outdate < $endraiddate || $this->member_outdate > time()))
		{
		    $this->member_outdate = mktime(0, 0, 0, 12, 31, 2030);
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
		        'member_armory_url' => $this->member_armory_url,
		        'member_portrait_url' => $this->member_portrait_url,
		        'member_achiev' => $this->member_achiev,
				'game_id' => $this->game_id, 
				'member_title' => $this->member_title
				));

		$db->sql_query('UPDATE ' . MEMBER_LIST_TABLE . ' SET ' . $query . '
		        WHERE member_id= ' . $this->member_id);

		$log_action = array(
				'header' => 'L_ACTION_MEMBER_UPDATED' ,
                'L_NAME' => $this->member_name ,
				'L_NAME_BEFORE' => $old_member->member_name,
				'L_LEVEL_BEFORE' => $old_member->member_level,
				'L_RACE_BEFORE' => $old_member->member_race_id,
                'L_RANK_BEFORE' => $old_member->member_rank_id,
				'L_CLASS_BEFORE' => $old_member->member_class_id,
                'L_GENDER_BEFORE' => $old_member->member_gender_id,
                'L_ACHIEV_BEFORE' => $old_member->member_achiev,
				'L_NAME_AFTER' => $this->member_name,
				'L_LEVEL_AFTER' => $this->member_level,
				'L_RACE_AFTER' => $this->member_race_id ,
                'L_RANK_AFTER' => $this->member_rank_id,
				'L_CLASS_AFTER' => $this->member_class_id ,
                'L_GENDER_AFTER' => $this->member_gender_id,
                'L_ACHIEV_AFTER' => $this->member_achiev,
				'L_UPDATED_BY' => $user->data['username']);
	
		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

	}

	/**
	 * 
	 * 
	 * @see \bbdkp\iMembers::Delete()
	 */
	public function Deletemember()
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

		$log_action = array(
				'header' => sprintf($user->lang['ACTION_MEMBER_DELETED'], $this->member_name) ,
				'L_NAME' => $this->member_name ,
				'L_LEVEL' => $this->member_level ,
				'L_RACE' => $this->member_race_id ,
				'L_CLASS' => $this->member_class_id);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

	}
	

	/**
	 * 
	 * @see \bbdkp\iMembers::Armory_get()
	 */
	public function Armory_getmember()
	{
		global $phpEx, $phpbb_root_path;
		
		switch ($this->game_id)
		{
			case 'wow':
				//Initialising the class
				if (!class_exists('WowAPI'))
				{
					require($phpbb_root_path . 'includes/bbdkp/wowapi/WowAPI.' . $phpEx);
				}
				
				 /** 
				  * available extra fields :
				 * 'guild','stats','talents','items','reputation','titles','professions','appearance',
				 * 'companions','mounts','pets','achievements','progression','pvp','quests'
				 */
				$api = new WowAPI('character', $this->member_region);
				$params = array('guild', 'titles');
				$data = $api->Character->getCharacter($this->member_name, $this->member_realm, $params);
				
				$this->member_level = $data['level'];
				$this->member_race_id = $data['race'];
				$this->member_class_id = $data['class'];
				$this->member_gender_id = $data['gender'];
				$this->member_achiev = $data['achievementPoints'];
				$this->member_armory_url = sprintf('http://%s.battle.net/wow/en/', $this->member_region) . 'character/' . $this->member_realm . '/' . $data ['name'] . '/simple';
				$this->member_portrait_url = sprintf('http://%s.battle.net/static-render/%s/', $this->member_region, $this->member_region) . $data['thumbnail'];
				$this->member_title = '';
				if (isset($data['titles']))
				{
					foreach($data['titles'] as $key => $title)
					{
						if (isset($title['selected']))
						{
							$this->member_title = $title['name'];
						}
					}
				}
				
				
				
		}
		
	}
	
	/**
	 * activates all checked members
	 * @see \bbdkp\iMembers::Activatemembers()
	 */	public function Activatemembers(array $mlist, array $mwindow)
	{

		global $user, $db, $config, $phpEx, $phpbb_root_path;

		$db->sql_transaction('begin');
		//if checkbox set then activate
		$sql1 = 'UPDATE ' . MEMBER_LIST_TABLE . "
                        SET member_status = '1'
                        WHERE " . $db->sql_in_set('member_id', $mlist, false, true);
		$db->sql_query($sql1);
		//if checkbox not set and in window then deactivate
		$sql2 = 'UPDATE ' . MEMBER_LIST_TABLE . "
                        SET member_status = '0'
                        WHERE  " . $db->sql_in_set('member_id', $mlist, true, true) . "
						AND  " . $db->sql_in_set('member_id', $mwindow, false, true);
		$db->sql_query($sql2);
		$db->sql_transaction('commit');


	}

	/*
	 * generates a standard portrait image url for aion based on characterdata
	*/
	private function generate_portraitlink()
	{
		global $phpbb_root_path;
		if ($this->game_id == 'aion')
		{
			$this->member_portrait_url = $phpbb_root_path . 'images/roster_portraits/aion/' . $this->member_race_id . '_' . $this->member_gender_id . '.jpg';
		}
	}
	
	/**
	 * function for removing member from guild but leave him in the member table.;
	 * @param unknown_type $member_name
	 * @param unknown_type $guild_id
	 * @return boolean
	 * @todo fix this
	 */
	public function GuildKick($member_name, $guild_id)
	{
		global $db, $user, $config;
		// find id for existing member name
		$sql = "SELECT *
				FROM " . MEMBER_LIST_TABLE . "
				WHERE member_name = '" . $db->sql_escape($member_name) . "' and member_guild_id = " . (int) $guild_id;
		$result = $db->sql_query($sql);
		// get old data
		while ($row = $db->sql_fetchrow($result))
		{
			$this->old_member = array(
					'member_id' => $row['member_id'] ,
					'member_rank_id' => $row['member_rank_id'] ,
					'member_guild_id' => $row['member_guild_id'] ,
					'member_comment' => $row['member_comment']);
		}
		$db->sql_freeresult($result);
		$sql_arr = array(
				'member_rank_id' => 99 ,
				'member_comment' => "Member left " . date("F j, Y, g:i a") . ' by Armory plugin' ,
				'member_outdate' => $this->time ,
				'member_guild_id' => 0);
		$sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
	        SET ' . $db->sql_build_array('UPDATE', $sql_arr) . '
	        WHERE member_id = ' . (int) $this->old_member['member_id'] . ' and member_guild_id = ' . (int) $this->old_member['member_guild_id'];

		$db->sql_query($sql);
		
		$log_action = array(
				'header' => 'L_ACTION_MEMBER_UPDATED' ,
				'L_NAME' => $member_name ,
				'L_RANK_BEFORE' => $this->old_member['member_rank_id'] ,
				'L_COMMENT_BEFORE' => $this->old_member['member_comment'] ,
				'L_RANK_AFTER' => 99 ,
				'L_COMMENT_AFTER' => "Member left " . date("F j, Y, g:i a") . ' by Armory plugin' ,
				'L_UPDATED_BY' => $user->data['username']);
		
		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));
		
		return true;
	}
	
	/**
	 * 
	 * @see \bbdkp\iMembers::WoWArmoryUpdate()
	 */
	public function WoWArmoryUpdate($memberdata, $guild_id, $region, $min_armory)
	{
		global $user, $db, $phpEx, $phpbb_root_path;
		$member_id = 0; 
		$member_ids = array();
		$oldmembers = array();
		$newmembers = array();
		
		/* GET OLD RANKS */
		$sql = ' select member_name, member_id FROM ' . MEMBER_LIST_TABLE . ' 
				 WHERE member_guild_id =  ' . (int) $guild_id . " 
				 AND game_id='wow' order by member_name ASC";
		$result = $db->sql_query ($sql);
		while ($row = $db->sql_fetchrow ($result))
		{
			$oldmembers[] = $row['member_name'];
			
			//this is to find the memberindex when updating
			$member_ids[ bin2hex($row['member_name'])] = $row['member_id']; 
			
		}
		$db->sql_freeresult ( $result );

		foreach($memberdata as $mb)
		{
			$newmembers[] = $mb['character']['name']; 
		}
		
		// get the new members to insert
		$to_add = array_diff($newmembers, $oldmembers);
		
		// start transaction
		$db->sql_transaction('begin');
		
		foreach($memberdata as $mb)
		{
			if (in_array($mb['character']['name'], $to_add) && $mb['character']['level'] >= $min_armory )
			{
				$this->game_id ='wow';
				$this->member_guild_id = $guild_id;
				$this->member_rank_id = $mb['rank'];
				$this->member_name = $mb['character']['name'];
				$this->member_level = (int) $mb['character']['level'];
				$this->member_gender_id = (int) $mb['character']['gender'];
				$this->member_race_id = (int) $mb['character']['race'];
				$this->member_class_id = (int) $mb['character']['class'];
				$this->member_achiev = (int) $mb['character']['achievementPoints'];
				$this->member_armory_url = sprintf('http://%s.battle.net/wow/en/', $region) . 'character/' . $realm . '/' . $this->member_name . '/simple';
				$this->member_status = 1;
				$this->member_comment = sprintf($user->lang['ACP_SUCCESSMEMBERADD'],  date("F j, Y, g:i a") );
				$this->member_joindate = $this->time;
				$this->member_outdate = mktime ( 0, 0, 0, 12, 31, 2030 );
				$this->member_portrait_url = sprintf('http://%s.battle.net/static-render/%s/', $region, $region) . $mb['character']['thumbnail'];
				$this->member_title = '';
				if (isset($mb['titles']))
				{
					foreach($mb['titles'] as $key => $title)
					{
						if (isset($title['selected']))
						{
							$this->member_title = $title['name'];
						}
					}
				}
				
								
				$query [] = array (
						'member_name' => ucwords($this->member_name) ,
						'member_status' => $this->member_status ,
						'member_level' => $this->member_level,
						'member_race_id' => $this->member_race_id ,
						'member_guild_id' => $this->member_guild_id ,
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
						'member_portrait_url' => (string) $this->member_portrait_url,
						
					);
			}
		}
		$db->sql_multi_insert(MEMBER_LIST_TABLE, $query);
		$db->sql_transaction('commit');		

		// get the members to update
		$to_update = array_intersect($newmembers, $oldmembers);
		foreach($memberdata as $mb)
		{
			if (in_array($mb['character']['name'], $to_update))
			{
				$member_id =  (int) $member_ids[bin2hex($this->member_name)]; 
				$this->game_id ='wow';
				$this->member_rank_id = $mb['rank'];
				$this->member_name = $mb['character']['name'];
				$this->member_guild_id = $guild_id;
				$this->member_level = (int) $mb['character']['level'];
				$this->member_gender_id = (int) $mb['character']['gender'];
				$this->member_race_id = (int) $mb['character']['race'];
				$this->member_class_id = (int) $mb['character']['class'];
				$this->member_achiev = (int) $mb['character']['achievementPoints'];
				$this->member_armory_url = sprintf('http://%s.battle.net/wow/en/', $region) . 'character/' . $realm. '/' . $this->member_name . '/simple';
				$this->member_portrait_url = sprintf('http://%s.battle.net/static-render/%s/', $this->member_region, $this->member_region) . $mb['character']['thumbnail'];
		
				$sql_ary = array (
						'member_name' => ucwords($this->member_name) ,
						'member_level' => $this->member_level,
						'member_race_id' => $this->member_race_id ,
						'member_class_id' => $this->member_class_id ,
						'member_rank_id' => $this->member_rank_id ,
						'member_guild_id' => $this->member_guild_id ,
						'member_gender_id' => $this->member_gender_id ,
						'member_achiev' => $this->member_achiev ,
						'member_armory_url' => (string) $this->member_armory_url ,
						'phpbb_user_id' => (int) $this->phpbb_user_id ,
						'member_portrait_url' => (string) $this->member_portrait_url,
				); 
				
				
				$sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
				    SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
				    WHERE member_id = ' . $member_id;
				$db->sql_query($sql);
				
				
			}
		}
		
	}
	
	/******
	 * returns raid attendance percentage for a member/pool
	*  @param $query_by_pool = boolean
	*  @param $dkpsys_id = int
	*  @param $days = int
	*  @param $member_id = int
	*  @param $mode= 0 -> indiv raidcount, 1 -> total rc, 2 -> attendancepct
	*  @param $all : if true then get count forever, otherwise since x days
	* used by listmembers.php and viewmember.php
	*
	*/
	function raidcount($query_by_pool, $dkpsys_id, $days, $member_id=0, $mode=1, $all = false)
	{
		$start_date = mktime(0, 0, 0, date('m'), date('d')-$days, date('Y'));
		// member joined in the last $days ?
	
		$joindate = $this->get_joindate($member_id);
		if ($all==true || $joindate > $start_date)
		{
			// then count from join date
			$start_date = $joindate;
		}
	
		$end_date = time();
	
		switch ($mode)
		{
			case 0:
				// get member raidcount
				return $this->memberraidcount($member_id, $start_date, $end_date, $query_by_pool, $dkpsys_id, $all);
				break;
				 
			case 1:
				// get total pool raidcount
				return $this->totalraidcount($start_date, $end_date, $query_by_pool, $dkpsys_id, $all);
				break;
	
			case 2:
				$memberraidcount = $this->memberraidcount($member_id, $start_date, $end_date, $query_by_pool, $dkpsys_id, $all);
				$raid_count = $this->totalraidcount($start_date, $end_date, $query_by_pool, $dkpsys_id, $all);
				$percent_of_raids = ($raid_count > 0 ) ?  round(($memberraidcount / $raid_count) * 100,2) : 0;
				return (float) $percent_of_raids;
				break;
		}
	
	}
	
	/**
	 * calculate raid count for the whole pool starting from joindate or startdate
	 * no caching
	 *
	 * @param int $member_id
	 * @param int $start_date
	 * @param int $end_date
	 * @param bool $query_by_pool
	 * @param bool $all
	 * @return int
	 */
	function memberraidcount($member_id, $start_date, $end_date, $query_by_pool, $dkpsys_id, $all)
	{
		global $db;
		$sql_array = array(
				'SELECT'    => 	' COUNT(*) as raidcount  ',
				'FROM'      => array(
						EVENTS_TABLE			=> 'e',
						RAIDS_TABLE 			=> 'r',
						RAID_DETAIL_TABLE	=> 'ra',
				),
				'WHERE'		=> '
	    	r.event_id = e.event_id
	    	AND ra.raid_id = r.raid_id
            AND ra.member_id =' . (int) $member_id
		);
	
		if ($all==true)
		{
			$sql_array['WHERE'] .= ' AND r.raid_start >= ' . $start_date;
		}
		else
		{
			$sql_array['WHERE'] .= ' AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date;
		}
	
		if ($query_by_pool == true)
		{
			$sql_array['WHERE'] .= ' AND e.event_dkpid = ' . $dkpsys_id;
		}
		$sql = $db->sql_build_query('SELECT', $sql_array);
	
		$result = $db->sql_query($sql);
		$individual_raid_count = (int) $db->sql_fetchfield('raidcount');
	
		$db->sql_freeresult($result);
	
		return $individual_raid_count;
	}
	
	/**
	 * calculate total raidcount for pool starting from joindate
	 * no caching
	 *
	 * @param int $start_date
	 * @param int $end_date
	 * @param boolean $query_by_pool
	 * @param boolean $all
	 * @return int
	 */
	public function totalraidcount($start_date, $end_date, $query_by_pool, $dkpsys_id, $all)
	{
		global $db;
	
		$sql_array = array(
				'SELECT'    => 	' COUNT(*) as raidcount  ',
				'FROM'      => array(
						EVENTS_TABLE			=> 'e',
						RAIDS_TABLE 			=> 'r'
				),
				'WHERE'		=> 'r.event_id = e.event_id ',
		);
	
		if ($all == true)
		{
			$sql_array['WHERE'] .= ' AND r.raid_start >= ' . $start_date;
		}
		else
		{
			$sql_array['WHERE'] .= ' AND r.raid_start BETWEEN ' . $start_date . ' AND ' . $end_date;
		}
	
	
		if ($query_by_pool == true)
		{
			$sql_array['WHERE'] .= ' AND e.event_dkpid = ' . $dkpsys_id;
		}
	
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$raid_count = (int) $db->sql_fetchfield('raidcount');
	
		$db->sql_freeresult($result);
		return $raid_count;
	
	}
	/**
	 * Enter joindate for guildmember (query is cached for 1 week !)
	 *
	 * @param unknown_type $member_id
	 * @return unknown
	 *
	 */
	public function get_joindate($member_id)
	{
		// get member joindate
		global $db;
		$sql = 'SELECT member_joindate  FROM ' . MEMBER_LIST_TABLE . ' WHERE member_id = ' . $member_id;
		$result = $db->sql_query($sql,3600);
		$joindate = $db->sql_fetchfield('member_joindate');
	
		$db->sql_freeresult($result);
		return $joindate;
	
	}


}

?>