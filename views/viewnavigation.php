<?php
/**
 * left front navigation block
 *
 * @package   bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\views;

use avathar\bbguild\model\admin\admin;
use avathar\bbguild\model\admin\constants;
use avathar\bbguild\model\player\guilds;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\pagination;
use phpbb\request\request;

/**
 * Class viewNavigation
 *
* @package avathar\bbguild\views
 */
class viewnavigation extends admin implements iviews
{

	/**
	* @var request
	*/
	public $request;

	/**
	 * @var \avathar\bbguild\controller\view_controller
	 */
	public $view_controller;

	/**
	* @var \phpbb\user
	*/
	public $user;

	/**
	* @var \phpbb\template\template
	*/
	public $template;

	/**
	* @var driver_interface
	*/
	public $db;

	/**
	* @var config
	*/
	public $config;

	/**
	* @var helper
	*/
	public $helper;

	/**
	* @var pagination $pagination
	*/
	public $pagination;

	/**
	 * extension path
	 * @type string
	 */
	public $ext_path;

	/**
	 * webroot extension path
	 * @type string
	 */
	public $ext_path_web;

	/**
	 * image extension path
	* @var string
	*/
	public $ext_path_images;

	/**
	* @var string
	*/
	public $root_path;

	/**
	 * guild id
	 *
	 * @var int
	 */
	public $guild_id;

	/**
	 * game id
	 *
	 * @var string
	 */
	private $game_id;

	/**
	 * filter by armor ?
	 *
	 * @var boolean
	 */
	private $query_by_armor = false;
	/**
	 * values of armor types
	 *
	 * @var array
	 */
	private $armor_type = array();

	/**
	 * filter by class ?
	 *
	 * @var boolean
	 */
	private $query_by_class = false;

	/**
	 * @type array
	 */
	private $classname = array();

	/**
	 * @type array
	 */
	private $classarray = array();

	/**
	 * class id from pulldown
	 */
	private $class_id = 0;

	/**
	 * race id from pulldown
	 */
	private $race_id = 0;

	/**
	 * the filter string
	 *
	 * @var string
	 */
	private $filter = '';

	/**
	 * show all players even not active ?
	 *
	 * @var boolean
	 */
	private $show_all = false;

	private $level1;
	private $level2;

	private $page;

	/**
	 * @type guilds
	 */
	public $guild;

	private $guildlist;

	/**
	 * @return array
	 */
	public function getArmorType()
	{
		return $this->armor_type;
	}

	/**
	 * @return mixed
	 */
	public function getClassId()
	{
		return $this->class_id;
	}

	/**
	 * @return array
	 */
	public function getClassarray()
	{
		return $this->classarray;
	}

	/**
	 * @return array
	 */
	public function getClassname()
	{
		return $this->classname;
	}

	/**
	 * @return string
	 */
	public function getFilter()
	{
		return $this->filter;
	}

	/**
	 * @return string
	 */
	public function getGameId()
	{
		return $this->game_id;
	}

	/**
	 * @return int
	 */
	public function getGuildId()
	{
		return $this->guild_id;
	}

	/**
	 * @return string
	 */
	public function getQueryByClass()
	{
		return $this->query_by_class;
	}

	/**
	 * @return boolean
	 */
	public function getQueryByArmor()
	{
		return $this->query_by_armor;
	}

	/**
	 * @return mixed
	 */
	public function getRaceId()
	{
		return $this->race_id;
	}

	/**
	 * @return boolean
	 */
	public function getShowAll()
	{
		return $this->show_all;
	}

	/**
	 * @return mixed
	 */
	public function getLevel1()
	{
		return $this->level1;
	}

	/**
	 * @return mixed
	 */
	public function getLevel2()
	{
		return $this->level2;
	}

	/**
	 * viewNavigation constructor.
	 *
	 * @param string $page
	 * @param \avathar\bbguild\controller\view_controller $view_controller
	 * @param string $guild_id
	 */
	public function __construct(
		$page,
		$view_controller,
		$guild_id
	)
	{

		parent::__construct();
		$this->view_controller = $view_controller;
		$this->request = $this->view_controller->request;
		$this->config = $this->view_controller->config;
		$this->user = $this->view_controller->user;
		$this->page = $this->view_controller->page;
		$this->template = $this->view_controller->template;
		$this->db = $this->view_controller->db;
		$this->helper = $this->view_controller->helper;
		$this->pagination = $this->view_controller->pagination;
		$this->ext_path = $this->view_controller->ext_path;
		$this->ext_path_web = $this->view_controller->ext_path_web;
		$this->ext_path_images = $this->view_controller->ext_path_images;
		$this->root_path  = $this->view_controller->root_path;
		$this->guild_id = $guild_id;

		$this->build_navigation();
		$this->buildpage();
	}

	public function buildpage()
	{
		$this->template->assign_vars(
			array(
				// Form values
				'S_GUILDDROPDOWN'    => count($this->guildlist) > 1 ? true : false,
				'U_WELCOME'           => $this->helper->route(
					'avathar_bbguild_00',
					array(
						'guild_id' => $this->guild_id,
						'page' => 'welcome'
					)
				),
				'U_ROSTER'           => $this->helper->route(
					'avathar_bbguild_00',
					array(
						'guild_id' => $this->guild_id,
						'page' => 'roster'
					)
				),
				'U_ACHIEVEMENTS'           => $this->helper->route(
					'avathar_bbguild_00',
					array(
						'guild_id' => $this->guild_id,
						'page' => 'achievements'
					)
				),
				'FACTION'            => $this->guild->getFaction(),
				'FACTION_NAME'       => $this->guild->getFactionname(),
				'GAME_ID'            => $this->guild->getGameId(),
				'GUILD_ID'           => $this->guild_id,
				'S_GUILD_ALLIANCE'   => ($this->guild->getGameId() == 'wow' &&  $this->guild->getFaction() == 1) ? true: false,
				'S_GUILD_HORDE'      => ($this->guild->getGameId() == 'wow' &&  $this->guild->getFaction() == 2) ? true: false,
				'S_GUILD_OTHER'      => ($this->guild->getGameId() != 'wow') ? true: false,
				'GUILD_NAME'         => $this->guild->getName(),
				'REALM'              => $this->guild->getRealm(),
				'REGION'             => $this->guild->getRegion(),
				'PLAYERCOUNT'        => $this->guild->getPlayercount() ,
				'ARMORY_URL'         => $this->guild->getGuildarmoryurl() ,
				'MIN_ARMORYLEVEL'    => $this->guild->getMinArmory() ,
				'SHOW_ROSTER'        => $this->guild->getShowroster(),
				'EMBLEM'             => $this->ext_path_images . 'guildemblem/' . basename($this->guild->getEmblempath()),
				'EMBLEMFILE'         => basename($this->guild->getEmblempath()),
				'ARMORY'             => $this->guild->getGuildarmoryurl(),
				'ACHIEV'             => $this->guild->getAchievementpoints(),
				'SHOWALL'            => ($this->show_all) ? $this->user->lang['ALL']: '',
			)
		);

	}

	private function build_navigation()
	{
		$this->guild_id = $this->request->variable(constants::URI_GUILD, $this->request->variable('hidden_guild_id', $this->guild_id));
		$this->guildlist = $this->get_guildinfo();
		$this->build_roster_navigation();
	}

	/**
	 * Build Guild Sidebar
	 *
	 * @return array
	 */
	private function get_guildinfo()
	{
		$this->guild = new guilds( $this->view_controller->bb_players_table,
			$this->view_controller->bb_ranks_table,
			$this->view_controller->bb_classes_table,
			$this->view_controller->bb_races_table,
			$this->view_controller->bb_language_table,
			$this->view_controller->bb_guild_table,
			$this->view_controller->bb_factions_table,
			$this->guild_id );

		$guildlist = $this->guild->guildlist(1);

		if (count($guildlist) > 0)
		{
			//loop the guilds
			foreach ($guildlist as $g)
			{
				if ($this->guild_id == 0)
				{
					//if there is a default guild
					if ($g['guilddefault'] == 1)
					{
						$this->guild_id = $g['id'];
					}
					else if ($g['playercount'] > 1)
					{
						$this->guild_id = $g['id'];
					}

					//if guild id field still 0
					if ($this->guild_id == 0 && $g['id'] > 0)
					{
						$this->guild_id = $g['id'];
					}
				}

				//populate guild popup
				if ($g['id'] > 0) // exclude guildless
				{
					$this->template->assign_block_vars(
						'guild_row', array(
							'VALUE' => $g['id'] ,
							'SELECTED' => ($g['id'] == $this->guild_id ) ? ' selected="selected"' : '' ,
							'OPTION' =>  $g['name'])
					);
				}
			}

		}
		else
		{
			trigger_error('ERROR_NOGUILD', E_USER_WARNING);
		}

		$this->guild->setGuildid($this->guild_id);
		$this->guild->get_guild();
		$this->game_id = $this->guild->getGameId();

		return $guildlist;
	}

	/**
	 *
	 */
	private function build_roster_navigation()
	{

		$this->show_all = ( $this->request->variable('show', $this->request->variable('hidden_show', '')) == $this->user->lang['ALL']) ? true : false;
		$this->race_id = $this->request->variable('race_id', 0);
		$this->level1 = $this->request->variable('level1', 0);
		$this->level2 = $this->request->variable('level2', 200);
		$this->filter = $this->request->variable('filter', $this->user->lang['ALL']);

		$this->query_by_armor = false;
		$this->query_by_class = false;
		$this->armor();

		if ($this->filter != $this->user->lang['ALL'])
		{
			if (array_key_exists($this->filter, $this->armor_type))
			{
				// looking for an armor type
				$this->filter= preg_replace('/ Armor/', '', $this->filter);
				$this->query_by_armor = true;
				$this->query_by_class = false;
			}
			else if (array_key_exists($this->filter, $this->classname))
			{
				// looking for a class
				$this->query_by_class = true;
				$t = explode('_', $this->filter);
				$this->class_id = count($t) > 1 ? $t[2]: 0;
				$this->query_by_armor = false;
			}
		}

	}



	/**
	 * generate Armor listing
	 */
	private function armor()
	{
		$filtervalues = array();
		$filtervalues['all'] = $this->user->lang['ALL'];
		$filtervalues['separator1'] = '--------';

		// generic armor list
		$sql = 'SELECT class_armor_type FROM ' . $this->view_controller->bb_classes_table . ' GROUP BY class_armor_type';
		$result = $this->db->sql_query($sql);
		while ( $row = $this->db->sql_fetchrow($result) )
		{
			$filtervalues[strtoupper($row['class_armor_type'])] = $this->user->lang[strtoupper($row['class_armor_type'])];
			$this->armor_type[strtoupper($row['class_armor_type'])] = $this->user->lang[strtoupper($row['class_armor_type'])];
		}
		$this->db->sql_freeresult($result);
		$filtervalues['separator2'] = '--------';

		// get classlist, depending on page
		$sql_array = array(
			'SELECT'    =>     '  c.game_id, c.class_id, l.name as class_name, c.class_min_level, c.class_max_level, c.imagename, c.colorcode ',
			'FROM'      => array(
				$this->view_controller->bb_classes_table	=> 'c',
				$this->view_controller->bb_language_table	=> 'l',
				$this->view_controller->bb_players_table    => 'i',
			),
			'WHERE'        => " c.class_id > 0 and l.attribute_id = c.class_id and c.game_id = l.game_id
				 		AND l.language= '" . $this->config['bbguild_lang'] . "' AND l.attribute = 'class'
				 		AND i.player_class_id = c.class_id and i.game_id = c.game_id AND i.game_id = '" .  $this->game_id . "'" ,

			'GROUP_BY'    => 'c.game_id, c.class_id, l.name, c.class_min_level, c.class_max_level, c.imagename, c.colorcode',
			'ORDER_BY'    => 'c.game_id, c.class_id ',
		);

		$sql_array['WHERE'] .= ' AND i.player_guild_id = ' . $this->guild_id . ' ';

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$this->classarray = array();
		while ( $row = $this->db->sql_fetchrow($result) )
		{
			$this->classarray[] = $row;
			$filtervalues[$row['game_id'] . '_class_' . $row['class_id']] = $row['class_name'];
			$this->classname[$row['game_id'] . '_class_' . $row['class_id']] = $row['class_name'];
		}
		$this->db->sql_freeresult($result);

		// dump filtervalues to dropdown template
		foreach ($filtervalues as $fid => $fname)
		{
			$this->template->assign_block_vars(
				'filter_row', array (
				'VALUE' => $fid,
				'SELECTED' => ($fid == $this->filter && $fname !=  '--------' ) ? ' selected="selected"' : '',
				'DISABLED' => ($fname == '--------' ) ? ' disabled="disabled"' : '',
				'OPTION' => (! $fname == '' ) ? $fname : $this->user->lang['ALL'] )
			);
		}

	}
}
