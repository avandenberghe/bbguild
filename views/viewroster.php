<?php
/**
 * Guild roster
 *
 * @package   bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */
namespace avathar\bbguild\views;

use avathar\bbguild\model\player\player;
use avathar\bbguild\model\admin\constants;

/**
 * Class viewroster
 *
* @package avathar\bbguild\views
 */
class viewroster implements iviews
{
	private $navigation;
	public  $response;
	private $tpl;

	/**
	 * viewRoster constructor.
	 *
	 * @param \avathar\bbguild\views\viewnavigation $navigation
	 */
	public function __construct(viewnavigation $navigation)
	{
		$this->navigation = $navigation;
		$this->buildpage();
	}

	/**
	 *prepare the rendering
	 */
	public function buildpage()
	{
		$this->tpl = 'main.html';
		$classes = array();

		$players = new player($this->navigation->view_controller->bb_players_table,
			$this->navigation->view_controller->bb_ranks_table,
			$this->navigation->view_controller->bb_classes_table,
			$this->navigation->view_controller->bb_races_table,
			$this->navigation->view_controller->bb_language_table,
			$this->navigation->view_controller->bb_guild_table,
			$this->navigation->view_controller->bb_factions_table
		);
		$players->game_id = $this->navigation->getGameId();

		$start = $this->navigation->request->variable('start', 0);

		$mode = $this->navigation->request->variable('rosterlayout', 0);
		$player_filter = $this->navigation->request->variable('player_name', '', true);

		$characters = $players->getplayerlist(
			$start, $mode, $this->navigation->getQueryByArmor(), $this->navigation->getQueryByClass(), $this->navigation->getFilter(),
			$this->navigation->getGameId(), $this->navigation->getGuildId(), $this->navigation->getClassId(), $this->navigation->getRaceId(), $this->navigation->getLevel1(), $this->navigation->getLevel2(), false, $player_filter, 0
		);

		$rosterlayoutlist = array(
			0 => $this->navigation->user->lang['ARM_STAND'] ,
			1 => $this->navigation->user->lang['ARM_CLASS']);

		foreach ($rosterlayoutlist as $lid => $lname)
		{
			$this->navigation->template->assign_block_vars(
				'rosterlayout_row', array(
					'VALUE' => $lid ,
					'SELECTED' => ($lid == $mode) ? ' selected="selected"' : '' ,
					'OPTION' => $lname)
			);
		}
		//pagination url
		$base_url = $this->navigation->helper->route(
			'avathar_bbguild_00',
			array(
				'guild_id' => $this->navigation->getGuildId(),
				'page' => 'roster'
			)
		);

		if ($mode ==0)
		{
			$this->display_listing($characters, $base_url, $start);
		}
		else if ($mode == 1)
		{
			$this->display_grid($players, $classes, $characters, $base_url, $start);
		}

		if ((count($this->navigation->games) > 1))
		{
			$this->navigation->template->assign_vars(
				array(
					'PLAYER_NAME'      => $player_filter,
					'S_MULTIGAME'      => true,
					'S_DISPLAY_ROSTER' => true,
					'F_ROSTER'         => $base_url,
					'S_GAME'           => $players->game_id,
				)
			);

		}
		else
		{
			$this->navigation->template->assign_vars(
				array(
					'PLAYER_NAME'      => $player_filter,
					'S_MULTIGAME'      => false,
					'S_DISPLAY_ROSTER' => true,
					'F_ROSTER'         => $base_url,
					'S_GAME'           => $players->game_id,
				)
			);
		}

		$title = $this->navigation->user->lang['GUILDROSTER'];

		// full rendered page source that will put output on the screen.
		$this->response = $this->navigation->helper->render($this->tpl, $title);

	}

	/**
	 * display in grid
	 *
	 * @param $players
	 * @param $classes
	 * @param $characters
	 * @param $base_url
	 * @param $start
	 */
	private function display_grid(player $players, $classes, $characters, $base_url, $start)
	{
		$classgroup = $players->get_classes(
			$this->navigation->getFilter(), $this->navigation->getQueryByArmor(),
			$this->navigation->getClassId(), $this->navigation->getGameId(), $this->navigation->getGuildId(),
			$this->navigation->getRaceId(), $this->navigation->getLevel1(), $this->navigation->getLevel2()
		);

		if (count($classgroup) > 0)
		{
			foreach ($classgroup as $row1)
			{
				$classes[$row1['class_id']]['name']      = $row1['class_name'];
				$classes[$row1['class_id']]['imagename'] = $row1['imagename'];
				$classes[$row1['class_id']]['colorcode'] = $row1['colorcode'];
			}

			foreach ($classes as $classid => $class)
			{
				//$web_root_path 	= $this->navigation->path_helper->get_web_root_path();
				$classimgurl = $this->navigation->ext_path_images . 'roster_classes/' . $class['imagename'] . '.png';

				$classcolor  = $class['colorcode'];

				$this->navigation->template->assign_block_vars(
					'class', array(
						'CLASSNAME' => $class['name'],
						'CLASSIMG'  => $classimgurl,
						'COLORCODE' => $classcolor,
					)
				);

				$classplayers = 1;

				foreach ($characters[0] as $row2)
				{
					if ($row2['player_class_id'] == $classid)
					{
						$this->navigation->template->assign_block_vars(
							'class.players_row', array(
								'PLAYER_ID'   => $row2['player_id'],
								'GAME'        => $row2['game_id'],
								'COLORCODE'   => $row2['colorcode'],
								'CLASS'       => $row2['class_name'],
								'NAME'        => $row2['player_name'],
								'RACE'        => $row2['race_name'],
								'RANK'        => $row2['player_rank'],
								'LVL'         => $row2['player_level'],
								'ARMORY'      => $row2['player_armory_url'],
								'PHPBBUID'    => $row2['username'],
								'PORTRAIT'    => $row2['player_portrait_url'],
								'ACHIEVPTS'   => $row2['player_achiev'],
								'CLASS_IMAGE' => $this->navigation->ext_path_images . 'class_images/' . basename($row2['class_image']),
								'RACE_IMAGE'  => $this->navigation->ext_path_images . 'race_images/' . basename($row2['race_image']),
							)
						);
						$classplayers++;
					}
				}
			}

			if ($start >  count($characters[0]))
			{
				$start = 1;
			}

			$rosterpagination = $this->navigation->pagination->generate_template_pagination($base_url, 'pagination', 'start', count($characters[0]), $this->navigation->config['bbguild_user_llimit'], $start, true);

			if (isset($characters[1]) && count($characters[1]) > 0)
			{
				$this->navigation->template->assign_vars(
					array(
						'ROSTERPAGINATION' => $rosterpagination,
						'U_LIST_PLAYERS0'  => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][0],
						'U_LIST_PLAYERS1'  => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][1],
						'U_LIST_PLAYERS2'  => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][2],
						'U_LIST_PLAYERS3'  => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][3],
						'U_LIST_PLAYERS4'  => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][4],
					)
				);
			}

			// add template constants
			$this->navigation->template->assign_vars(
				array(
					'S_SHOWACH'             => $this->navigation->config['bbguild_show_achiev'],
					'LISTPLAYERS_FOOTCOUNT' => 'Total players : ' . count($characters[0]),
					'S_DISPLAY_ROSTERGRID'  => true
				)
			);
		}

		$this->navigation->template->assign_vars(
			array(
				'S_RSTYLE' => '1',
			)
		);
	}

	/**
	 * a simple list
	 *
	 * @param    $characters
	 * @param    $base_url
	 * @param    $start
	 * @internal param $url
	 */
	private function display_listing($characters, $base_url, $start)
	{
		/*
        * Displays the listing
        */

		foreach ($characters[0] as $char)
		{
			$this->navigation->template->assign_block_vars(
				'players_row', array(
					'PLAYER_ID'     => $char['player_id'],
					'U_VIEW_PLAYER' => '',
					'GAME'          => $char['game_id'],
					'COLORCODE'     => $char['colorcode'],
					'CLASS'         => $char['class_name'],
					'NAME'          => $char['player_name'],
					'RACE'          => $char['race_name'],
					'RANK'          => $char['player_rank'],
					'LVL'           => $char['player_level'],
					'ARMORY'        => $char['player_armory_url'],
					'PHPBBUID'      => $char['username'],
					'PORTRAIT'      => $char['player_portrait_url'],
					'ACHIEVPTS'     => $char['player_achiev'],
					'CLASS_IMAGE'   => $this->navigation->ext_path_images . 'class_images/' . basename($char['class_image']),
					'RACE_IMAGE'    => $this->navigation->ext_path_images . 'race_images/' . basename($char['race_image']),
				)
			);
		}

		if ($start > $characters[2])
		{
			$start = 1;
		}
		$rosterpagination = $this->navigation->pagination->generate_template_pagination($base_url, 'pagination', 'start', $characters[2], $this->navigation->config['bbguild_user_llimit'], $start, true);

		$this->navigation->template->assign_vars(
			array(
				'ROSTERPAGINATION' => $rosterpagination,
				'PAGE_NUMBER'      => $this->navigation->pagination->on_page($characters[2], $this->navigation->config['bbguild_user_llimit'], $start),
				'O_NAME'           => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][0],
				'O_CLASS'          => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][2],
				'O_RANK'           => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][3],
				'O_LEVEL'          => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][4],
				'O_PHPBB'          => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][5],
				'O_ACHI'           => $base_url . '?' . constants::URI_ORDER . '=' . $characters[1]['uri'][6]
			)
		);
		// add template constants
		$this->navigation->template->assign_vars(
			array(
				'S_RSTYLE'                => '0',
				'S_SHOWACH'               => $this->navigation->config['bbguild_show_achiev'],
				'LISTPLAYERS_FOOTCOUNT'   => 'Total players : ' . $characters[2],
				'S_DISPLAY_ROSTERLISTING' => true
			)
		);
	}

}
