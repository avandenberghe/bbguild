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
 class Guilds extends \bbdkp\Admin implements iGuilds
{
	public $game_id = '';
	public $guildid = 0;
	public $name = '';
	public $realm = '';
	public $region = '';
	public $achievements = 0;
	public $membercount = 0;
	public $startdate = 0;
	public $showroster = 0;
	//aion
	public $aionlegionid = 0;
	public $aionserverid = 0;
	//wow
	public $achievementpoints = 0;
	public $level = 0;
	public $emblempath = '';
	public $emblem = array(); 
	public $battlegroup = '';
	public $guilarmorydurl = '';
	public $memberdata = array();
	public $side = 0;

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

		$sql = 'SELECT id, name, realm, region, roster, game_id, members, 
				achievementpoints, level, battlegroup, guildarmoryurl, emblemurl
				FROM ' . GUILD_TABLE . '
				WHERE id = ' . $this->guildid;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if (! $row)
		{
			$this->game_id = '';
			$this->guildid = 0;
			$this->name = '';
			$this->realm = '';
			$this->region = '';
			$this->showroster = 0;
		}
		else
		{
			// load guild object
			$this->game_id = $row['game_id'];
			$this->guildid = $row['id'];
			$this->name = $row['name'];
			$this->realm = $row['realm'];
			$this->region = $row['region'];
			$this->showroster = $row['roster'];
			$this->achievementpoints = $row['achievementpoints'];
			$this->level = $row['level'];
			$this->battlegroup = $row['battlegroup'];
			$this->guildarmoryurl = $row['guildarmoryurl'];
			$this->emblempath = $phpbb_root_path . $row['emblemurl'];
			
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
				'game_id' => $this->game_id,
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
		if (!class_exists('\bbdkp\Ranks'))
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
				'game_id' => $this->game_id,  
				'id' => $this->guildid,
				'name' => $this->name ,
				'realm' => $this->realm,
				'region' => $this->region ,
				'roster' => $this->showroster ,
				'aion_legion_id' => $this->aionlegionid ,
				'aion_server_id' => $this->aionserverid
		));

		$db->sql_query('UPDATE ' . GUILD_TABLE . ' SET ' . $query . ' WHERE id= ' . $this->guildid);

		switch ($this->game_id)
		{
			case 'wow':
				//$params = array('members', 'achievements','news');
				$params = array('members' );
				$this->Armory_get($params);
				
				$query = $db->sql_build_array('UPDATE', array(
						'achievementpoints' => $this->achievementpoints,
						'level' => $this->level,
						'guildarmoryurl' => $this->guilarmorydurl,
						'emblemurl' => $this->emblempath,
						'battlegroup' => $this->battlegroup,
				));
				
				$db->sql_query('UPDATE ' . GUILD_TABLE . ' SET ' . $query . ' WHERE id= ' . $this->guildid);
				break;
		}
		
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
	 * Calls api to pull more information
	 *
	 * Currently only the WoW API is available
	 *
	 * @return void
	 */
	public function Armory_get($params)
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
				
				 //available extra fields : 'members', 'achievements','news'
				$api = new WowAPI('guild', $this->region);
				
				$data = $api->Guild->getGuild($this->name, $this->realm, $params);  
				
				$this->achievementpoints = $data['achievementPoints'];
				$this->level = $data['level'];
				$this->battlegroup = $data['battlegroup'];
				$this->side = $data['side'];
				$this->guilarmorydurl = sprintf('http://%s.battle.net/wow/en/', $this->region) . 'guild/' . $data['realm']. '/' . $data['name'] . '/';
				//$this->emblemurl = sprintf('http://%s.battle.net/static-render/%s/', $this->member_region, $this->member_region) . $data['thumbnail'];
				//@todo update guild membership
				$this->emblem = $data['emblem'];
				$this->emblempath = $this->createEmblem(true);
				$this->memberdata = $data['members'];
		}
	
	}
	
	/**
	 * function to create a Wow Guild emblem, adapted for phpBB from http://us.battle.net/wow/en/forum/topic/3082248497#8
	 *  	
 	 * @author Thomas Andersen <acoon@acoon.dk>
	 * @copyright Copyright (c) 2011, Thomas Andersen, http://sourceforge.net/projects/wowarmoryapi
	 * @param boolean $showlevel
	 * @param int $width
	 * @return resource
	 */
	private function createEmblem($showlevel=TRUE, $width=215)
	{
		global $phpEx, $phpbb_root_path;
		
		//location to create the file
		$imgfile = $phpbb_root_path . "images/wowapi/guildemblem/".$this->region.'_'.$this->realm.'_'.$this->name.".png";
		$outputpath = "images/wowapi/guildemblem/".$this->region.'_'.$this->realm.'_'.$this->name.".png";
		if (file_exists($imgfile) AND $width==(imagesx(imagecreatefrompng($imgfile))) AND (filemtime($imgfile)+86000) > time()) 
		{
			$finalimg = imagecreatefrompng($imgfile);
			imagesavealpha($finalimg,true);
			imagealphablending($finalimg, true);
		} 
		else 
		{
			if ($width > 1 AND $width < 215)
			{
				$height = ($width/215)*230;
				$finalimg = imagecreatetruecolor($width, $height);
				$trans_colour = imagecolorallocatealpha($finalimg, 0, 0, 0, 127);
				imagefill($finalimg, 0, 0, $trans_colour);
				imagesavealpha($finalimg,true);
				imagealphablending($finalimg, true);
			}
				
			if ($this->side == 0)
			{
				$ring = 'alliance';
			} 
			else 
			{
				$ring = 'horde';
			}
	
			$imgOut = imagecreatetruecolor(215, 230);
					
			$emblemURL = $phpbb_root_path ."images/wowapi/emblems/emblem_".sprintf("%02s",$this->emblem['icon']).".png";
			$borderURL = $phpbb_root_path ."images/wowapi/borders/border_".sprintf("%02s",$this->emblem['border']).".png";
			$ringURL = $phpbb_root_path ."images/wowapi/static/ring-".$ring.".png";
			$shadowURL = $phpbb_root_path ."images/wowapi/static/shadow_00.png";
			$bgURL = $phpbb_root_path ."images/wowapi/static/bg_00.png";
			$overlayURL = $phpbb_root_path ."images/wowapi//static/overlay_00.png";
			$hooksURL = $phpbb_root_path ."images/wowapi/static/hooks.png";
			$levelURL = $phpbb_root_path ."images/wowapi/static/";
					
			imagesavealpha($imgOut,true);
			imagealphablending($imgOut, true);
			$trans_colour = imagecolorallocatealpha($imgOut, 0, 0, 0, 127);
			imagefill($imgOut, 0, 0, $trans_colour);
					
			$ring = imagecreatefrompng($ringURL);
			$ring_size = getimagesize($ringURL);
				
			$emblem = imagecreatefrompng($emblemURL);
			$emblem_size = getimagesize($emblemURL);
			imagelayereffect($emblem, IMG_EFFECT_OVERLAY);
			$emblemcolor = preg_replace('/^ff/i','',$this->emblem['iconColor']);
			$color_r = hexdec(substr($emblemcolor,0,2));
			$color_g = hexdec(substr($emblemcolor,2,2));
			$color_b = hexdec(substr($emblemcolor,4,2));
			imagefilledrectangle($emblem,0,0,$emblem_size[0],$emblem_size[1],imagecolorallocatealpha($emblem, $color_r, $color_g, $color_b,0));
					
					
			$border = imagecreatefrompng($borderURL);
			$border_size = getimagesize($borderURL);
			imagelayereffect($border, IMG_EFFECT_OVERLAY);
			$bordercolor = preg_replace('/^ff/i','',$this->emblem['borderColor']);
			$color_r = hexdec(substr($bordercolor,0,2));
			$color_g = hexdec(substr($bordercolor,2,2));
			$color_b = hexdec(substr($bordercolor,4,2));
			imagefilledrectangle($border,0,0,$border_size[0]+100,$border_size[0]+100,imagecolorallocatealpha($border, $color_r, $color_g, $color_b,0));
					
			$shadow = imagecreatefrompng($shadowURL);
							
			$bg = imagecreatefrompng($bgURL);
			$bg_size = getimagesize($bgURL);
			imagelayereffect($bg, IMG_EFFECT_OVERLAY);
			$bgcolor = preg_replace('/^ff/i','',$this->emblem['backgroundColor']);
			$color_r = hexdec(substr($bgcolor,0,2));
			$color_g = hexdec(substr($bgcolor,2,2));
			$color_b = hexdec(substr($bgcolor,4,2));
			imagefilledrectangle($bg,0,0,$bg_size[0]+100,$bg_size[0]+100,imagecolorallocatealpha($bg, $color_r, $color_g, $color_b,0));
											
											
			$overlay = imagecreatefrompng($overlayURL);
			$hooks = imagecreatefrompng($hooksURL);
											
			$x = 20;
			$y = 23;
											
			if (!$this->emblemHideRing)
			{
				imagecopy($imgOut,$ring,0,0,0,0, $ring_size[0],$ring_size[1]);
			}
			
			$size = getimagesize($shadowURL);
			imagecopy($imgOut,$shadow,$x,$y,0,0, $size[0],$size[1]);
			imagecopy($imgOut,$bg,$x,$y,0,0, $bg_size[0],$bg_size[1]);
			imagecopy($imgOut,$emblem,$x+17,$y+30,0,0, $emblem_size[0],$emblem_size[1]);
			imagecopy($imgOut,$border,$x+13,$y+15,0,0, $border_size[0],$border_size[1]);
			$size = getimagesize($overlayURL);
			imagecopy($imgOut,$overlay,$x,$y+2,0,0, $size[0],$size[1]);
			$size = getimagesize($hooksURL);
			imagecopy($imgOut,$hooks,$x-2,$y,0,0, $size[0],$size[1]);
			
			if ($showlevel)
			{
				$level = $this->level;
				if ($level < 10)
				{
					$levelIMG = imagecreatefrompng($levelURL.$level.".png");
				} 
				else 
				{
					$digit[1] = substr($level,0,1);
					$digit[2] = substr($level,1,1);
					$digit1 = imagecreatefrompng($levelURL.$digit[1].".png");
					$digit2 = imagecreatefrompng($levelURL.$digit[2].".png");
					$digitwidth = imagesx($digit1);
					$digitheight = imagesy($digit1);
					$levelIMG = imagecreatetruecolor($digitwidth*2,$digitheight);
					$trans_colour = imagecolorallocatealpha($levelIMG, 0, 0, 0, 127);
					imagefill($levelIMG, 0, 0, $trans_colour);
					imagesavealpha($levelIMG,true);
					imagealphablending($levelIMG, true);
					// Last image added first because of the shadow need to be behind first digit
					imagecopy($levelIMG,$digit2,$digitwidth-12,0,0,0, $digitwidth, $digitheight);
					imagecopy($levelIMG,$digit1,12,0,0,0, $digitwidth, $digitheight);
				}
				$size[0] = imagesx($levelIMG);
				$size[1] = imagesy($levelIMG);
				$levelemblem = imagecreatefrompng($ringURL);
				imagesavealpha($levelemblem,true);
				imagealphablending($levelemblem, true);
				imagecopy($levelemblem,$levelIMG,(215/2)-($size[0]/2),(215/2)-($size[1]/2),0,0,$size[0],$size[1]);
				imagecopyresampled($imgOut, $levelemblem, 143, 150,0,0, 215/3, 215/3, 215, 215);
			}
			
			if ($width > 1 AND $width < 215)
			{
				imagecopyresampled($finalimg, $imgOut, 0, 0, 0, 0, $width, $height, 215, 230);
			} 
			else 
			{
				$finalimg = $imgOut;
			}
			
			imagepng($finalimg,$imgfile);
		
		}
		return $outputpath;
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