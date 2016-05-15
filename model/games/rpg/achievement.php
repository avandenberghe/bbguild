<?php
/**
 * This file holds the Achievement API class
 *
 * @package   bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * Date: 18.04.16
 * Time: 12:10
 *
 */

namespace bbdkp\bbguild\model\games\rpg;

use bbdkp\bbguild\model\api\battlenet;
use bbdkp\bbguild\model\games\game;
use bbdkp\bbguild\model\admin\admin;

/**
 * Class achievement
 *
 * @package bbdkp\bbguild\model\games\rpg
 */
class achievement extends admin
{
	/**
	 * achievement id
	 * bb_achievement
	 * @var int
	 */
	public $id;

	/**
	 * game id
	 * bb_achievement
	 * @var string
	 */
	public $game_id;

	/**
	 * title of achievement
	 * bb_achievement
	 * @var string
	 */
	protected $title;

	/**
	 * points
	 * bb_achievement
	 * @var int
	 */
	protected $points;

	/**
	 * long description
	 * bb_achievement
	 * @var string
	 */
	protected $description;

	/**
	 * icon
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * faction ID
	 * bb_achievement
	 * @var int
	 */
	protected $factionId;

	/**
	 * guild if its a guild achievement
	 * bb_achievement
	 * @var string
	 */
	protected $guild_id;

	/**
	 * player_id if its an individual achievement
	 * bb_achievement
	 * @var string
	 */
	protected $player_id;

	/***************************************/

	/**
	 * criteria
	 * bb_achievement_criteria
	 * @var array
	 */
	protected $criteria;

	/**
	 * reward
	 * bb_achievement_rewards
	 * @var array
	 */
	protected $rewardItems;

	/**
	 * date of achievement completion.
	 * bb_achievement_track
	 * @type double
	 */
	protected $achievements_completed;

	/***************************************/

	/**
	 * @type game
	 */
	private $game;

	/***************************************/

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return achievement
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGameId()
	{
		return $this->game_id;
	}

	/**
	 * @param string $game_id
	 * @return achievement
	 */
	public function setGameId($game_id)
	{
		$this->game_id = $game_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return achievement
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPoints()
	{
		return $this->points;
	}

	/**
	 * @param int $points
	 * @return achievement
	 */
	public function setPoints($points)
	{
		$this->points = $points;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return achievement
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 * @return achievement
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFactionId()
	{
		return $this->factionId;
	}

	/**
	 * @param int $factionId
	 * @return achievement
	 */
	public function setFactionId($factionId)
	{
		$this->factionId = $factionId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGuildId()
	{
		return $this->guild_id;
	}

	/**
	 * @param string $guild_id
	 */
	public function setGuildId($guild_id)
	{
		$this->guild_id = $guild_id;
	}

	/**
	 * @return string
	 */
	public function getPlayerId()
	{
		return $this->player_id;
	}

	/**
	 * @param string $player_id
	 */
	public function setPlayerId($player_id)
	{
		$this->player_id = $player_id;
	}


	/*****************************************************/

	/**
	 * @return array
	 */
	public function getRewardItems()
	{
		return $this->rewardItems;
	}

	/**
	 * @param array $rewardItems
	 * @return achievement
	 */
	public function setRewardItems($rewardItems)
	{
		$this->rewardItems = $rewardItems;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getCriteria()
	{
		return $this->criteria;
	}

	/**
	 * @param array $criteria
	 * @return achievement
	 */
	public function setCriteria($criteria)
	{
		$this->criteria = $criteria;
		return $this;
	}

	/*************************************/
	/**
	 * achievement constructor.
	 *
	 * @param \bbdkp\bbguild\model\games\game $game
	 * @param int                             $id
	 */
	public function __construct(game $game, $id)
	{
		parent::__construct();
		$this->game_id = $game->game_id;
		$this->game = $game;
		$this->id = $id;

		if ($id > 0)
		{
			if ($this->get_achievement() == 0)
			{
				$data = $this->Call_Achievement_API();
				$this->insert_achievement($data);
			}
		}
	}

	/**
	 * call achievement endpoint
	 * @return array|bool
	 */
	public function Call_Achievement_API()
	{
		global $cache;
		$data = array();
		if (! $this->game->getArmoryEnabled())
		{
			return false;
		}

		$api  = new battlenet('achievement',$this->game->getRegion(), $this->game->getApikey(),
			$this->game->get_apilocale(), $this->game->get_privkey(), $this->ext_path, $cache);
		$data = $api->achievement->getAchievementDetail($this->id);
		$data = $data['response'];
		unset($api);
		if (!isset($data))
		{
			return false;
		}

		//if we get error code
		if (isset($data['code']))
		{
			return false;
		}

		if (isset($data['status']))
		{
			return false;
		}

		return $data;
	}

	/**
	 * get achievements from db
	 * @return int
	 */
	private function get_achievements()
	{
		global $db;
		$i=0;
		$sql = 'SELECT guild_id, player_id, achievement_id, achievements_completed
    			FROM ' . ACHIEVEMENT_TRACK_TABLE. '
    			WHERE guild_id = ' . (int) $this->guildid ;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$i=1;
			$this->guildachievements[] = array (
				'guild_id' => $row['guild_id'] ,
				'player_id' => $row['player_id'] ,
				'achievement_id' => $row['achievement_id'],
				'achievements_completed' => $row['achievements_completed'],
			);
		}
		$db->sql_freeresult($result);

		// @todo add check if achievements are old
		return $i;
	}


	/**
	 * get achievement (no track info)
	 * @return int
	 */
	public function get_achievement()
	{
		global $db;
		$i=0;

		$sql_array = array (
			'SELECT' => ' a.id, a.game_id, a.title, a.points, a.description, a.icon, a.factionid,
				r1.rel_value as criteria_id,
				r2.rel_value as rewards_item_id,
				c.description as criteria, c.orderindex as criteriaorder, c.max as criteriamax,
				w.description as rewards, w.orderindex as rewardorder, w.max as rewardmax',
			'FROM' => array (
				ACHIEVEMENT_TABLE => 'a',
				BB_RELATIONS_TABLE => 'r1',
				ACHIEVEMENT_CRITERIA_TABLE => 'c',
				ACHIEVEMENT_REWARDS_TABLE => 'w',
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'  => array(BB_RELATIONS_TABLE => 'r2'),
					'ON'    => " r2.attribute_id = 'ACH' AND r2.rel_attr_id = 'REW' AND r2.att_value = a.id" ,
				)),
			'WHERE' =>  'a.id = ' . (int) $this->id .
				" AND a.game_id = '". $this->game_id .
				"' AND r1.attribute_id = 'ACH' AND r1.rel_attr_id = 'CRI' AND r1.att_value = a.id
				   AND c.criteria_id = r1.rel_value AND c.rewards_item_id = r2.rel_value "
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$i=1;
			$this->title = $row['title'];
			$this->points    = $row['points'];
			$this->description    = $row['description'];
			$this->icon    = $row['icon'];
			$this->factionId    = $row['factionid'];
			$this->criteria       = array( $row['criteria_id'], $row['criteria'], $row['criteriaorder'], $row['criteriamax'] );
			$this->rewardItems    = array( $row['rewards_item_id'], $row['rewards'], $row['rewardorder'], $row['rewardmax'] );
		}
		$db->sql_freeresult($result);
		return $i;
	}

	/**
	 * get tracked achievements from local database
	 * guild_id'              => array('UINT', 0),
	 * player_id'             => array('UINT', 0),
	 * achievement_id'        => array('UINT', 0),
	 * achievements_completed' => array('TIMESTAMP', 0),
	 * criteria'               => array('VCHAR:3000', ''),
	 * criteria_quantity'      => array('VCHAR_UNI:255', ''),
	 * criteria_timestamp'     => array('TIMESTAMP', 0),
	 *
	 * @param $guild_id
	 * @param $player_id
	 * @return array
	 */
	public function get_tracked_achievements($start, $guild_id, $player_id = 0)
	{

		global $db;

		// for understanding the entity schema see achievements.png
		$sql_array = array (
			'SELECT' => ' a.id as achievement_id, a.game_id, a.title, a.points, a.description, a.icon, a.factionid,
				r1.rel_value as criteria_id,
				r2.rel_value as rewards_item_id,
				c.description as criteriadescription, c.orderindex as criteriaorder, c.max as criteriamax, ct.criteria_quantity, ct.criteria_timestamp, ct.criteria_created,
				w.description as rewardsdescription, w.orderindex as rewardorder, w.max as rewardmax,
				ac.achievements_completed ',
			'FROM' => array (
				ACHIEVEMENT_TABLE => 'a',
				ACHIEVEMENT_TRACK_TABLE => 'ac',
				BB_RELATIONS_TABLE => 'r1',
				ACHIEVEMENT_REWARDS_TABLE => 'w',
				ACHIEVEMENT_CRITERIA_TABLE => 'c',
				CRITERIA_TRACK_TABLE => 'ct'
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'  => array(BB_RELATIONS_TABLE => 'r2'),
					'ON'    => " a.id = r2.att_value AND r2.attribute_id = 'ACH' AND r2.rel_attr_id = 'REW' " ,
				)),
			'WHERE' =>  '1=1
				    AND (ac.guild_id = ' . $guild_id .' OR ac.player_id= ' . $player_id . ") AND ac.achievement_id = a.id
				    AND a.game_id = '". $this->game_id . "' AND a.id = r1.att_value AND r1.attribute_id = 'ACH' AND r1.rel_attr_id = 'CRI' " .
				' AND c.criteria_id = r1.rel_value AND w.rewards_item_id = r2.rel_value
					AND (ct.guild_id = ' . $guild_id .' OR ct.player_id= ' . $player_id . ') AND ct.criteria_id = c.criteria_id ',
		);

		$sort_order = array(
			0 => array('achievement_id', 'achievement_id desc'),
			1 => array('title', 'title desc'),
			2 => array('description', 'description desc'),
			3 => array('points', 'points desc'),
			4 => array('achievements_completed', 'achievements_completed desc'),
		);

		$current_order   = $this->switch_order($sort_order);
		$sql_array['ORDER_BY']  = $current_order['sql'];

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$dataset = $db->sql_fetchrowset($result);
		$achievcount = count($dataset);

		if($start> 0)
		{
			$result = $db->sql_query_limit($sql, 15, $start);
			$dataset = $db->sql_fetchrowset($result);
		}
		$db->sql_freeresult($result);

		$achievements = array();

		foreach ($dataset as $row)
		{
			$achievements[] = array(
				'guild_id'                 => $guild_id,
				'player_id'                => $player_id,
				'game_id'                  => $this->game_id,
				'title'                    => $row['title'],
				'points'                   => $row['points'],
				'description'              => $row['description'],
				'icon'                     => $row['icon'],
				'factionId'                => $row['factionid'],
				'criteria'                 => array(
						$row['criteria_id'],
						$row['criteriadescription'],
						$row['criteriaorder'],
						$row['criteriamax'],
						$row['criteria_quantity'],
						$row['criteria_timestamp'],
						$row['criteria_created']),
				'rewardItems'              => array(
						$row['rewards_item_id'],
						$row['rewardsdescription'],
						$row['rewardorder'],
						$row['rewardmax']),
				'achievements_completed'   => $row['achievements_completed']
			);
		}
		$a = 1;
		return array($achievements, $current_order, $achievcount);

	}

	/**
	 * Insert an achievement into local database
	 *
	 * @param array $data
	 */
	private function insert_achievement(array $data)
	{
		global $db;
		$this->id = isset($data['id']) ? $data['id'] : 0;
		$this->game_id = 'wow';
		$this->title = isset($data['title']) ? $data['title']: '';
		$this->points = isset($data['points']) ? $data['points']: 0;
		$this->description = isset($data['description']) ? $data['description']: '';
		$this->reward = isset($data['reward']) ? $data['reward']: '';
		$this->rewardItems = isset($data['rewardItems']) ? json_encode($data['rewardItems']): '';
		$this->icon = isset($data['icon']) ? $data['icon']: '';
		$this->criteria = isset($data['criteria']) ? json_encode($data['criteria']): '';
		$this->factionId = isset($data['factionId']) ? $data['factionId']: '';

		$sql_ary = array(
			'id'            => $this->id,
			'game_id'          => $this->game_id,
			'title'         => $this->title,
			'points'        => $this->points,
			'description'   => $this->description,
			'reward'        => $this->reward,
			'rewarditems'   => $this->rewardItems,
			'criteria'      => $this->criteria,
			'factionid'     => $this->factionId,
		);

		$db->sql_query('INSERT INTO ' . ACHIEVEMENT_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));
	}




	/**
	 * call API and set set achievment tracked
	 * delete from achievement track
	 * insert achievement track
	 * delete from criteria track
	 * insert criteria track
	 * insert achievements if not already exists
	 * insert criteria if not already exists
	 * insert relations if not already exists
	 */
	private function set_achievements()
	{
		global $db;
		$i=0;

		$data = $this->Call_Guild_API(array('achievements'));

		$achievements = array();

		$game          = new game;
		$game->game_id = $this->game_id;
		$game->get_game();

		foreach ($data['achievements']['achievementsCompleted'] as $id => $achi)
		{
			$achievement[$id]['id'] = $achi;
		}
		foreach ($data['achievements']['achievementsCompletedTimestamp'] as $id => $achiTimeStamp)
		{
			$achievement[$id]['timestamp'] = $achiTimeStamp;
		}

		//delete from achievement track

		$sql = "DELETE FROM " . ACHIEVEMENT_TRACK_TABLE . " WHERE guild_id = " . $this->guildid;
		$db->sql_query($sql);

		//insert achievement track
		$sql_ary = array();

		foreach ($achievement as $id => $achi)
		{
			$sql_ary[] = array(
				'guild_id' => $this->guildid,
				'player_id' => 0,
				'achievement_id' => $achi['id'],
				'achievements_completed' => $achi['timestamp'],
			);
		}
		$db->sql_multi_insert(ACHIEVEMENT_TRACK_TABLE, $sql_ary);

		// delete from criteria track
		$sql = "DELETE FROM " . CRITERIA_TRACK_TABLE . " WHERE guild_id = " . $this->guildid;
		$db->sql_query($sql);

		$criteria = array();
		foreach ($data['achievements']['criteria'] as $id => $criteria_id)
		{
			$criteria[$id]['criteria_id'] = $criteria_id;
			$criteria[$id]['criteriaQuantity'] = $data['achievements']['criteriaQuantity'][$id];
			$criteria[$id]['criteriaTimestamp'] = $data['achievements']['criteriaTimestamp'][$id];
			$criteria[$id]['criteriaCreated'] = $data['achievements']['criteriaCreated'][$id];
		}

		$sql_ary = array();
		foreach ($criteria as $id => $crit)
		{
			$sql_ary[] = array(
				'guild_id' => $this->guildid,
				'player_id' => 0,
				'criteria_id' => $crit['criteria_id'],
				'criteria_quantity' => $crit['criteriaQuantity'],
				'criteria_timestamp' => $crit['criteriaTimestamp'],
				'criteria_created' => $crit['criteriaCreated'],
			);
		}

		$db->sql_multi_insert(CRITERIA_TRACK_TABLE, $sql_ary);

		//phpbb_bb_achievement
		$sql = 'SELECT achievement_id from ' . ACHIEVEMENT_TRACK_TABLE . ' where guild_id = ' . $this->guildid;
		$sql .= ' AND achievement_id not in (SELECT achievement_id from ' . ACHIEVEMENT_TABLE . ')';
		$result = $db->sql_query($sql);

		$achievement_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$achievement_ids[] = $row['achievement_id'];
		}
		$db->sql_freeresult($result);

		foreach ($achievement_ids as $id => $achievement_id)
		{
			$this->achievement = new achievement($game, $achievement_id);
			unset($this->achievement);
		}


	}

}
