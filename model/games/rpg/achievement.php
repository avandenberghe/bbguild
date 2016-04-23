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

/**
 * Class achievement
 *
 * @package bbdkp\bbguild\model\games\rpg
 */
class achievement
{
	/**
	 * game id
	 *
	 * @var string
	 */
	public $game_id;

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
	 * @var array
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
	 * @var array
	 */
	protected $criteria;

	/**
	 * criteria
	 *
	 * @var int
	 */
	protected $factionId;

	/**
	 * achievement constructor.
	 *
	 * @param string $game_id
	 * @param int $id
	 */
	public function __construct($game_id, $id)
	{
		$this->game_id = $game_id;
		$this->id = $id;
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
	 * get achievement from local database
	 */
	public function get()
	{
		global $db;
		$sql = 'SELECT id, game_id, title, points, description, reward, rewarditems, icon, criteria, factionid
    			FROM ' . ACHIEVEMENT_TABLE . '
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
		}
		$db->sql_freeresult($result);
	}

	/**
	 * call achievement endpoint
	 *
	 * @param game $game
	 * @param $ext_path
	 * @param $cache
	 * @return bool
	 * @internal param $params
	 */
	public function Call_Achievement_API(game $game, $ext_path, $cache)
	{
		global $user;
		$data= 0;
		if (! $game->getArmoryEnabled())
		{
			return false;
		}

		$api  = new battlenet('achievement',$game->getRegion(), $game->getApikey(), $game->get_apilocale(), $game->get_privkey(), $ext_path, $cache);
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

	}

	/**
	 * fetch Battlenet achievement api endpoint and update the object
	 *
	 * @param array $data
	 * @param       $params
	 */
	public function update_achievement_battleNet(array $data, $params)
	{
		global $db;

		$this->points = isset($data['achievementPoints']) ? $data['achievementPoints'] : 0;
		$this->level = isset($data['level']) ? $data['level']: 0;
		$this->battlegroup = isset($data['battlegroup']) ? $data['battlegroup']: '';

		if ($data['side'] == 0)
		{
			$this->faction = isset($data['side']) ? (1) : '';
		} else
		{
			$this->faction = isset($data['side']) ? (2) : '';
		} // bbguild wants Alliance 1 and Horde 2

		$this->guildarmoryurl = '';
		if (isset($data['name']))
		{
			$this->guildarmoryurl = sprintf('http://%s.battle.net/wow/en/', $this->region) . 'guild/' . $this->realm. '/' . $data['name'] . '/';
		}

		$this->emblem = isset($data['emblem']) ? $data['emblem']: '';

		$this->emblempath = isset($data['emblem']) ?  $this->create_emblem()  : '';
		if(isset($data['members']))
		{
			$this->playercount = count($data['members']);
		}

		$query = $db->sql_build_array(
			'UPDATE', array(
				'achievementpoints' => $this->achievementpoints,
				'level'             => $this->level,
				'guildarmoryurl'    => $this->guildarmoryurl,
				'emblemurl'         => $this->emblempath,
				'battlegroup'       => $this->battlegroup,
				'armoryresult'      => $this->armoryresult,
				'players'           => $this->playercount,
				'faction'           => $this->faction,
			)
		);

		$db->sql_query('UPDATE ' . GUILD_TABLE . ' SET ' . $query . ' WHERE id= ' . $this->guildid);
		if (in_array('members', $params, true))
		{
			// update ranks table
			$rank = new ranks($this->guildid);
			$rank->WoWRankFix($this->playerdata, $this->guildid);
			//update player table
			$mb = new player();
			$mb->WoWArmoryUpdate($this->playerdata, $this->guildid, $this->region, $this->min_armory);
		}

	}


}