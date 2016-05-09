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
	 * game id
	 *
	 * @var string
	 */
	public $game_id;

	/**
	 * guild if its a guild achievement
	 *
	 * @var string
	 */
	public $guild_id;

	/**
	 * player_id if its an individual achievement
	 *
	 * @var string
	 */
	public $player_id;

	/**
	 * achievement id
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * title of achievement
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * points
	 *
	 * @var int
	 */
	protected $points;

	/**
	 * long description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * reward (optional)
	 *
	 * @var string
	 */
	protected $reward;

	/**
	 * reward
	 *
	 * @var string
	 */
	protected $rewardItems;

	/**
	 * icon
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * criteria
	 *
	 * @var string
	 */
	protected $criteria;

	/**
	 * criteria
	 *
	 * @var int
	 */
	protected $factionId;

	/**
	 * @type game
	 */
	private $game;

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
	public function getReward()
	{
		return $this->reward;
	}

	/**
	 * @param string $reward
	 * @return achievement
	 */
	public function setReward($reward)
	{
		$this->reward = $reward;
		return $this;
	}

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
	 * achievement constructor.
	 *
	 * @param string $game_id
	 * @param int $id
	 */
	public function __construct(game $game, $id)
	{
		parent::__construct();
		$this->game_id = $game->game_id;
		$this->game = $game;
		$this->id = $id;

		if($id > 0)
		{
			if($this->get_achievement() == 0)
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
	private function Call_Achievement_API()
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
	 * get achievement from local database
	 *
	 * @return int
	 */
	public function get_achievement()
	{
		global $db;
		$i=0;
		$sql = 'SELECT id, game_id, title, points, description, reward, rewarditems, icon, factionid, criteria
    			FROM ' . ACHIEVEMENT_TABLE . '
    			WHERE id = ' . (int) $this->id . " and game_id = '" . $this->game_id . "'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$i=1;
			$this->title = $row['title'];
			$this->points    = $row['points'];
			$this->description    = $row['description'];
			$this->reward    = $row['reward'];
			$this->rewardItems    = $row['rewarditems'];
			$this->icon    = $row['icon'];
			$this->criteria    = $row['criteria'];
			$this->factionId    = $row['factionid'];
		}
		$db->sql_freeresult($result);
		return $i;
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
	public function get_tracked_achievements($guild_id, $player_id)
	{
		global $db;
		$sql = 'SELECT achievement_id, player_id, guild_id, achievements_completed, criteria,
					criteria_quantity, criteria_timestamp
    			FROM ' . ACHIEVEMENT_TRACK_TABLE . '
    			WHERE id = ' . (int) $this->id . " and game_id = '" . $this->game_id . "'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->title = $row['titletitle'];
			$this->points    = $row['points'];
			$this->description    = $row['description'];
			$this->reward    = $row['reward'];
			$this->rewardItems    = $row['rewarditems'];
			$this->icon    = $row['icon'];
			$this->criteria    = $row['criteria'];
			$this->factionId    = $row['factionid'];
			$achievementlist[] = json_decode($this, true);
		}
		$db->sql_freeresult($result);
		return $achievementlist;
	}



}
