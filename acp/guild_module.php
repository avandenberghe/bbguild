<?php
/**
 * Guild ACP file
 *
 * @package   bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace bbdkp\bbguild\acp;

use bbdkp\bbguild\model\admin\admin;
use bbdkp\bbguild\model\games\game;
use bbdkp\bbguild\model\games\rpg\faction;
use bbdkp\bbguild\model\player\guilds;
use bbdkp\bbguild\model\player\Ranks;

/**
 * This class manages guilds
 *
 * @package bbguild
 */
class guild_module extends admin
{
	/**
	 * url action
	 *
	 * @var string
	 */
	public $u_action;
	/**
	 * trigger url
	 *
	 * @var string
	 */
	public $link = ' ';
	/**
	 * current url
	 *
	 * @var string
	 */
	public $url_id;

	/**
	 * @var \phpbb\request\request
	 **/
	protected $request;
	/**
	 * @var \phpbb\template\template
	 **/
	protected $template;
	/**
	 * @var \phpbb\user
	 **/
	protected $user;
	/**
	 * @var \phpbb\db\driver\driver_interface
	 */
	protected $db;
	/**
	 * @var \phpbb\config\config
	 */
	protected $config;

	public $id;
	public $mode;
	public $auth;
	protected $factions;

	/**
	 * ACP guild function
	 *
	 * @param int $id   the id of the node who parent has to be returned by function
	 * @param int $mode id of the submenu
	 */
	public function main($id, $mode)
	{
		global $config, $user, $template, $db, $phpbb_admin_path, $phpEx;
		global $request, $auth;

		$this->config   = $config;
		$this->id       = $id;
		$this->mode     = $mode;
		$this->request  = $request;
		$this->template = $template;
		$this->user     = $user;
		$this->db       = $db;
		$this->auth     = $auth;

		parent::__construct();
		$form_key = 'bbdkp/bbguild';
		add_form_key($form_key);

		$this->tpl_name   = 'acp_'.$mode;
		$this->link       = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=listguilds').'"><h3>'.$this->user->lang['RETURN_GUILDLIST'].'</h3></a>';
		$this->page_title = 'ACP_LISTGUILDS';

		if (! $this->auth->acl_get('a_bbguild'))
		{
			trigger_error($user->lang['NOAUTH_A_GUILD_MAN']);
		}

		switch ($mode)
		{
			case 'listguilds':
				$this->BuildTemplateListGuilds();
				break;
			case 'addguild':
				$addguild = new guilds();
				$addguild->setGameId($config['bbguild_default_game']);
				$addguild->setRegion($config['bbguild_default_region']);

				if ($this->request->is_set_post('newguild'))
				{
					$this->AddGuild($addguild);
				}

				foreach ($this->regions as $key => $regionname)
				{
					$this->template->assign_block_vars(
						'region_row',
						array(
							'VALUE'    => $key,
							'SELECTED' => ($addguild->getRegion() == $key) ? ' selected="selected"' : '',
							'OPTION'   => (! empty($regionname)) ? $regionname : '(None)',
						)
					);
				}

				$thisgame          = new game;
				$thisgame->game_id = $addguild->getGameId();
				$thisgame->get_game();

				$this->factions = new faction($thisgame->game_id);

				// reset armory_enabled to false if no API key
				if ($thisgame->game_id == 'wow' && $thisgame->getApikey() == '')
				{
					$addguild->setArmoryEnabled(false);
				}

				if (isset($this->games))
				{
					foreach ($this->games as $key => $gamename)
					{
						$this->template->assign_block_vars(
							'game_row',
							array(
								'VALUE'    => $key,
								'SELECTED' => ($addguild->getGameId() == $key) ? ' selected="selected"' : '',
								'OPTION'   => (! empty($gamename)) ? $gamename : '(None)',
							)
						);
					}
				}
				else
				{
					trigger_error('ERROR_NOGAMES', E_USER_WARNING);
				}

				$listfactions = $this->factions->get_factions();
				if (isset($listfactions))
				{
					foreach ($listfactions as $key => $faction)
					{
						$this->template->assign_block_vars(
							'faction_row',
							array(
								'VALUE'    => $key,
								'SELECTED' => ($addguild->getFaction() == $key) ? ' selected="selected"' : '',
								'OPTION'   => (! empty($faction['faction_name'])) ? $faction['faction_name'] : '(None)',
							)
						);
					}
				}

				switch ($thisgame->game_id)
				{
					case 'gw2':
						$addguild->setName('Twisted');
						$addguild->setRealm('Gunnar\'s Hold');
				}

				$this->template->assign_vars(
					array(
						'GUILD_NAME'      => $addguild->getName(),
						'REALM_NAME'      => $addguild->getRealm(),
						'F_ENABLEARMORY'  => $addguild->isArmoryEnabled(),
						'DEFAULTREALM'    => ($config['bbguild_default_realm'] =='' ) ? $addguild->getRealm() : $config['bbguild_default_realm'],
						'RECSTATUS'       => true,
						'MIN_ARMORYLEVEL' => $config['bbguild_minrosterlvl'],
					)
				);
				$this->page_title = $this->user->lang['ACP_ADDGUILD'];

				break;
			case 'editguild':
				$this->url_id = $this->request->variable(URI_GUILD, 0);
				$updateguild  = new guilds($this->url_id);
				if ($this->request->is_set_post('playeradd'))
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\mm_module&amp;mode=addplayer&amp;'.URI_GUILD. '=' .$this->url_id));
				}

				$action = $this->request->variable('action', '');
				switch ($action)
				{
					case 'guildranks':
						$updaterank = $this->request->is_set_post('updaterank');
						$deleterank = $this->request->variable('deleterank', '') != '' ? true : false;
						$addrank    = $this->request->is_set_post('addrank');
						$this->link = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildranks&amp;'.URI_GUILD.'='.
								$updateguild->getGuildid()).'"><h3>'.$this->user->lang['RETURN_GUILDLIST'].'</h3></a>';

						if (($updaterank || $addrank) && (!check_form_key('bbdkp/bbguild')))
						{
							trigger_error('FORM_INVALID');
						}

						if ($addrank)
						{
							$this->AddRank($updateguild);
						}

						if ($addrank)
						{
							$this->AddRank($updateguild);
						}

						if ($updaterank)
						{
							$this->UpdateRank($updateguild);
						}

						if ($deleterank)
						{
							$this->DeleteRank();
						}

						$this->tpl_name = 'acp_editguild_ranks';
						$this->BuildTemplateEditGuildRanks($updateguild);
						break;

					case 'editguild':
					default:
						$submit        = $this->request->is_set_post('updateguild');
						$delete        = $this->request->is_set_post('deleteguild');
						$armoryenabled = $this->request->is_set_post('armory_enabled');
						$updatearmory  = $this->request->is_set_post('armory');
						$this->link    = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildedit&amp;'.URI_GUILD.'='.
								$updateguild->getGuildid()).'"><h3>'.$this->user->lang['RETURN_GUILDLIST'].'</h3></a>';
						// POST check
						if ($submit)
						{
							if (! check_form_key('bbdkp/bbguild'))
							{
								trigger_error('FORM_INVALID', E_USER_NOTICE);
							}

							if ($armoryenabled)
							{
								$this->UpdateGuild($updateguild, true);
							}
							else
							{
								$this->UpdateGuild($updateguild, false);
							}
						}

						if ($updatearmory)
						{
							$this->UpdateGuild($updateguild, true);
						}

						if ($delete)
						{
							$this->DeleteGuild($updateguild);
						}

						// start template loading
						$this->BuildTemplateEditGuild($updateguild);
						break;
				}//end switch
				break;

			default:
				$this->page_title = 'ACP_BBGUILD_MAINPAGE';
				$success_message  = 'Error';
				trigger_error($success_message.$this->link, E_USER_WARNING);
		}//end switch

	}//end main()


	/**
	 * @param guilds $addguild
	 */
	private function AddGuild(guilds $addguild)
	{
		if (!check_form_key('bbdkp/bbguild'))
		{
			trigger_error('FORM_INVALID');
		}

		$addguild->setGameId($this->request->variable('game_id', ''));
		$addguild->setName($this->request->variable('guild_name', '', true));
		$addguild->setRealm($this->request->variable('realm', '', true));
		$addguild->setRegion($this->request->variable('region_id', ''));
		$addguild->setFaction($this->request->variable('faction_id', 0));
		$addguild->setShowroster($this->request->is_set_post('showroster'));
		$addguild->setMinArmory($this->request->variable('min_armorylevel', 0));
		$addguild->setArmoryEnabled($this->request->variable('armory_enabled', 0));
		$addguild->setRecstatus($this->request->variable('switchon_recruitment', 0));
		$addguild->setRecruitforum($this->request->variable('recruitforum', 0));
		$result = $addguild->make_guild();

		switch ($result)
		{
			case 0:
				$success_message = sprintf($this->user->lang['ADMIN_ADD_GUILD_SUCCESS'], $addguild->getName());
				trigger_error($success_message.$this->link, E_USER_NOTICE);
				break;
			case 1:
				$success_message = sprintf($this->user->lang['ADMIN_ADD_GUILD_SUCCESS'], $addguild->getName());
				trigger_error($success_message.$this->link, E_USER_NOTICE);
				break;
			case 2:
				$success_message = sprintf($this->user->lang['ERROR_ARMORY_NOTFOUND'], $addguild->getName());
				trigger_error($success_message.$this->link, E_USER_WARNING);
				break;
			default:
				$success_message = sprintf($this->user->lang['ADMIN_ADD_GUILD_FAIL'], $addguild->getName());
				trigger_error($success_message.$this->link, E_USER_WARNING);
		}
	}


	/**
	 * update the default flag
	 *
	 * @param $updateguild
	 */
	private function UpdateDefaultGuild(guilds $updateguild)
	{
		$id = $this->request->variable('defaultguild', 0);
		$updateguild->update_guilddefault($id);
		$success_message = sprintf($this->user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $id);
		trigger_error($success_message.$this->link, E_USER_NOTICE);

	}//end UpdateDefaultGuild()


	/**
	 * @param $updateguild
	 * @param $updateArmory
	 * @return void
	 */
	private function UpdateGuild(guilds $updateguild, $updateArmory)
	{
		$updateguild->setGuildid($this->url_id);
		$updateguild->get_guild();
		$old_guild = new guilds($this->url_id);
		$old_guild->get_guild();

		$updateguild->setGameId($this->request->variable('game_id', ''));
		$updateguild->setName($this->request->variable('guild_name', '', true));
		$updateguild->setRealm($this->request->variable('realm', '', true));
		$updateguild->setRegion($this->request->variable('region_id', ' '));
		$updateguild->setShowroster($this->request->variable('showroster', 0));
		$updateguild->setMinArmory($this->request->variable('min_armorylevel', 0));
		$updateguild->setRecstatus($this->request->variable('switchon_recruitment', 0));
		$updateguild->setArmoryEnabled($this->request->variable('armory_enabled', 0));
		$updateguild->setRecruitforum($this->request->variable('recruitforum', 0));

		// in the request we expect the file name here including extension, no path
		$updateguild->setEmblempath($this->ext_path. 'images/guildemblem/' .$this->request->variable('guild_emblem', '', true));

		$updateguild->setAionlegionid(0);
		$updateguild->setAionserverid(0);

		$GuildAPIParameters = array();

		if ($updateArmory)
		{
			$GuildAPIParameters = array('members');
		}

		if ($updateguild->update_guild($old_guild, $GuildAPIParameters))
		{
			$success_message = sprintf($this->user->lang['ADMIN_UPDATE_GUILD_SUCCESS'], $this->url_id);
			trigger_error($success_message.$this->link, E_USER_NOTICE);
		}
		else
		{
			$success_message = sprintf($this->user->lang['ADMIN_UPDATE_GUILD_FAILED'], $this->url_id);
			trigger_error($success_message.$this->link, E_USER_WARNING);
		}

	}

	/**
	 * delete a guild
	 *
	 * @param $updateguild
	 */
	private function DeleteGuild(guilds $updateguild)
	{
		if (confirm_box(true))
		{
			$deleteguild = new guilds($this->request->variable('guildid', 0));
			$deleteguild->get_guild();
			$deleteguild->delete_guild();
			$success_message = sprintf($this->user->lang['ADMIN_DELETE_GUILD_SUCCESS'], $deleteguild->getGuildid());
			trigger_error($success_message.$this->link, E_USER_NOTICE);
		}
		else
		{
			$s_hidden_fields = build_hidden_fields(
				array(
					'deleteguild' => true,
					'guildid'     => $updateguild->getGuildid(),
				)
			);

			$this->template->assign_vars(
				array('S_HIDDEN_FIELDS' => $s_hidden_fields)
			);

			confirm_box(false, $this->user->lang['CONFIRM_DELETE_GUILD'], $s_hidden_fields);
		}//end if

	}//end DeleteGuild()


	/**
	 * Add a guild rank
	 *
	 * @param $updateguild
	 */
	private function AddRank(guilds $updateguild)
	{
		$newrank            = new Ranks($updateguild->getGuildid());
		$newrank->RankName  = $this->request->variable('nrankname', '', true);
		$newrank->RankId    = $this->request->variable('nrankid', 0);
		$newrank->RankGuild = $updateguild->getGuildid();
		$newrank->RankHide  = $this->request->is_set_post('nhide');
		$newrank->RankPrefix = $this->request->variable('nprefix', '', true);
		$newrank->RankSuffix = $this->request->variable('nsuffix', '', true);
		$newrank->Makerank();
		$success_message = $this->user->lang['ADMIN_RANKS_ADDED_SUCCESS'];
		trigger_error($success_message.$this->link);

	}//end AddRank()


	/**
	 * Update a rank
	 *
	 * @param $updateguild
	 *
	 * @return int|string
	 */
	private function UpdateRank(guilds $updateguild)
	{
		$newrank = new Ranks($updateguild->getGuildid());
		$oldrank = new Ranks($updateguild->getGuildid());
		$rank_id=0;
		// template
		$modrank = $this->request->variable('ranks', array(0 => ''), true);
		foreach ($modrank as $rank_id => $rank_name)
		{
			$oldrank->RankId    = $rank_id;
			$oldrank->RankGuild = $updateguild->getGuildid();
			$oldrank->Getrank();

			$newrank->RankId     = $rank_id;
			$newrank->RankGuild  = $oldrank->RankGuild;
			$newrank->RankName   = $rank_name;
			$newrank->RankHide   = $this->request->is_set_post(['hide'][$rank_id]);
			$rank_prefix         = $this->request->variable('prefix', array((int) $rank_id => ''), true);
			$newrank->RankPrefix = $rank_prefix[$rank_id];

			$rank_suffix         = $this->request->variable('suffix', array((int) $rank_id => ''), true);
			$newrank->RankSuffix = $rank_suffix[$rank_id];

			// compare old with new,
			if ($oldrank != $newrank)
			{
				$newrank->Rankupdate($oldrank);
			}
		}

		$success_message = $this->user->lang['ADMIN_RANKS_UPDATE_SUCCESS'];
		trigger_error($success_message.$this->link);
		return $rank_id;

	}//end UpdateRank()


	/**
	 * delete a guild rank
	 */
	private function DeleteRank()
	{

		if (confirm_box(true))
		{
			$guildid    = $this->request->variable('hidden_guildid', 0);
			$rank_id    = $this->request->variable('hidden_rank_id', 999);
			$deleterank = new Ranks($guildid, $rank_id);
			$deleterank->Rankdelete(false);
		}
		else
		{
			// delete the rank only if there are no players left
			$rank_id    = $this->request->variable('ranktodelete', 999);
			$guildid    = $this->request->variable(URI_GUILD, 0);
			$old_guild  = new guilds($guildid);
			$deleterank = new Ranks($guildid, $rank_id);

			$s_hidden_fields = build_hidden_fields(
				array(
					'deleterank'     => true,
					'hidden_rank_id' => $rank_id,
					'hidden_guildid' => $guildid,
				)
			);

			confirm_box(false, sprintf($this->user->lang['CONFIRM_DELETE_RANKS'], $deleterank->RankName, $old_guild->getName()), $s_hidden_fields);
		}//end if

	}//end DeleteRank()


	/**
	 * @param $updateguild
	 */
	private function BuildTemplateEditGuild(guilds $updateguild)
	{
		global $phpEx,  $phpbb_admin_path;

		foreach ($this->regions as $key => $regionname)
		{
			$this->template->assign_block_vars(
				'region_row',
				array(
					'VALUE'    => $key,
					'SELECTED' => ($updateguild->getRegion() == $key) ? ' selected="selected"' : '',
					'OPTION'   => (!empty($regionname)) ? $regionname : '(None)',
				)
			);
		}

		if (isset($this->games))
		{
			foreach ($this->games as $key => $gamename)
			{
				$this->template->assign_block_vars(
					'game_row',
					array(
						'VALUE'    => $key,
						'SELECTED' => ($updateguild->getName() == $key) ? ' selected="selected"' : '',
						'OPTION'   => (!empty($gamename)) ? $gamename : '(None)',
					)
				);
			}
		}
		else
		{
			trigger_error('ERROR_NOGAMES', E_USER_WARNING);
		}

		$game          = new game;
		$game->game_id = $updateguild->getGameId();
		$game->get_game();

		$this->factions = new faction($game->game_id);
		$listfactions = $this->factions->get_factions();
		if (isset($listfactions))
		{
			foreach ($listfactions as $key => $faction)
			{
				$this->template->assign_block_vars(
					'faction_row',
					array(
						'VALUE'    => $key,
						'SELECTED' => ($updateguild->getFaction() == $key) ? ' selected="selected"' : '',
						'OPTION'   => (! empty($faction['faction_name'])) ? $faction['faction_name'] : '(None)',
					)
				);
			}
		}

		$this->template->assign_vars(
			array(
				'F_ENABLGAMEEARMORY'      => $game->getArmoryEnabled(),
				'F_ENABLEARMORY'          => $updateguild->isArmoryEnabled(),
				'RECRUITFORUM_OPTIONS'    => make_forum_select($updateguild->getRecruitforum(), false, false, true),
				'RECSTATUS'               => $updateguild->getRecstatus(),
				'GAME_ID'                 => $updateguild->getGameId(),
				'GUILDID'                 => $updateguild->getGuildid(),
				'GUILD_NAME'              => $updateguild->getName(),
				'REALM'                   => $updateguild->getRealm(),
				'REGION'                  => $updateguild->getRegion(),
				'FACTION'                 => $updateguild->getFaction(),
				'PLAYERCOUNT'             => $updateguild->getPlayercount(),
				'ARMORY_URL'              => $updateguild->getGuildarmoryurl(),
				'MIN_ARMORYLEVEL'         => $updateguild->getMinArmory(),
				'SHOW_ROSTER'             => ($updateguild->getShowroster() == 1) ? 'checked="checked"' : '',
				'ARMORYSTATUS'            => $updateguild->getArmoryresult(),
				// Language
				'L_TITLE'                 => $this->user->lang['ACP_EDITGUILD'],
				'L_EXPLAIN'               => $this->user->lang['ACP_EDITGUILD_EXPLAIN'],
				'L_EDIT_GUILD_TITLE'      => $this->user->lang['EDIT_GUILD'],
				// Javascript messages
				'MSG_NAME_EMPTY'          => $this->user->lang['FV_REQUIRED_NAME'],
				'EMBLEM'                  => $updateguild->getEmblempath(),
				'EMBLEMFILE'              => basename($updateguild->getEmblempath()),
				'U_EDIT_GUILD'            => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=editguild&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
				'U_EDIT_GUILDRANKS'       => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildranks&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
				'U_EDIT_GUILDRECRUITMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildrecruitment&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
			)
		);

		// extra
		if ($updateguild->game_id == 'wow')
		{
			$this->template->assign_vars(
				array(
					'S_WOW'  => true,
					'ARMORY' => $updateguild->getGuildarmoryurl(),
					'ACHIEV' => $updateguild->getAchievementpoints(),
				)
			);
		}

		$this->page_title = $this->user->lang['ACP_EDITGUILD'];

	}//end BuildTemplateEditGuild()


	/**
	 * list the ranks for this guild
	 *
	 * @param $updateguild
	 */
	private function BuildTemplateEditGuildRanks(guilds $updateguild)
	{
		global $phpEx,  $phpbb_admin_path;
		// everything from rank 90 is readonly
		$listranks          = new Ranks($updateguild->getGuildid());
		$listranks->game_id = $updateguild->getGameId();
		$result = $listranks->listranks();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$prefix = $row['rank_prefix'];
			$suffix = $row['rank_suffix'];
			$this->template->assign_block_vars(
				'ranks_row',
				array(
					'RANK_ID'       => $row['rank_id'],
					'RANK_NAME'     => $row['rank_name'],
					'RANK_PREFIX'   => $prefix,
					'RANK_SUFFIX'   => $suffix,
					'HIDE_CHECKED'  => ($row['rank_hide'] == 1) ? 'checked="checked"' : '',
					'S_READONLY'    => $row['rank_id'] >= 90,
					'U_DELETE_RANK' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;deleterank=1&amp;ranktodelete='.$row['rank_id']. '&amp;' .URI_GUILD. '=' .$updateguild->getGuildid()),
				)
			);
		}

		$this->db->sql_freeresult($result);

		$game          = new game;
		$game->game_id = $updateguild->getGameId();
		$game->get_game();

		$this->template->assign_vars(
			array(
				// Form values
				'S_GUILDLESS'             => $updateguild->getGuildid() == 0,
				'F_ENABLGAMEEARMORY'      => $game->getArmoryEnabled(),
				'F_ENABLEARMORY'          => $updateguild->isArmoryEnabled(),
				'GAME_ID'                 => $updateguild->getGameId(),
				'GUILDID'                 => $updateguild->getGuildid(),
				'GUILD_NAME'              => $updateguild->getName(),
				'U_ADD_RANK'              => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;addrank=1&amp;guild='.$updateguild->getGuildid()),
				// Language
				'L_TITLE'                 => ($this->url_id < 0) ? $this->user->lang['ACP_ADDGUILD'] : $this->user->lang['ACP_EDITGUILD'],
				'L_EXPLAIN'               => ($this->url_id < 0) ? $this->user->lang['ACP_ADDGUILD_EXPLAIN'] : $this->user->lang['ACP_EDITGUILD_EXPLAIN'],
				'L_ADD_GUILD_TITLE'       => ($this->url_id < 0) ? $this->user->lang['ADD_GUILD'] : $this->user->lang['EDIT_GUILD'],
				// Javascript messages
				'MSG_NAME_EMPTY'          => $this->user->lang['FV_REQUIRED_NAME'],
				'EMBLEM'                  => $updateguild->getEmblempath(),
				'EMBLEMFILE'              => basename($updateguild->getEmblempath()),
				// only filename
				'S_ADD'                   => $this->url_id < 0,
				'U_EDIT_GUILD'            => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=editguild&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
				'U_EDIT_GUILDRANKS'       => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildranks&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
				'U_EDIT_GUILDRECRUITMENT' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;action=guildrecruitment&amp;'.URI_GUILD.'='.$updateguild->getGuildid()),
			)
		);

		$this->page_title = $this->user->lang['ACP_EDITGUILD'];

	}//end BuildTemplateEditGuildRanks()


	/**
	 * list the guilds
	 */
	private function BuildTemplateListGuilds()
	{
		global   $phpbb_admin_path, $phpEx;
		if (count($this->games) == 0)
		{
			trigger_error($this->user->lang['ERROR_NOGAMES'], E_USER_WARNING);
		}

		$updateguild = new guilds();
		$guildlist   = $updateguild->guildlist(1);
		foreach ($guildlist as $g)
		{
			$this->template->assign_block_vars(
				'defaultguild_row',
				array(
					'VALUE'    => $g['id'],
					'SELECTED' => ($g['guilddefault'] == '1') ? ' selected="selected"' : '',
					'OPTION'   => (!empty($g['name'])) ? $g['name'] : '(None)',
				)
			);
		}

		$guilddefaultupdate = $this->request->is_set_post('upddefaultguild');
		if ($guilddefaultupdate)
		{
			$this->UpdateDefaultGuild($updateguild);
		}

		$guildadd = $this->request->is_set_post('addguild');
		if ($guildadd)
		{
			redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=addguild'));
		}

		$sort_order      = array(
			0 => array(
				'id',
				'id desc',
			),
			1 => array(
				'name',
				'name desc',
			),
			2 => array(
				'realm desc',
				'realm desc',
			),
			3 => array(
				'region',
				'region desc',
			),
			4 => array(
				'roster',
				'roster desc',
			),
		);
		$current_order   = $this->switch_order($sort_order);
		$guild_count     = 0;
		$sort_index      = explode('.', $current_order['uri']['current']);
		$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);

		$sql = 'SELECT id, name, realm, region, roster, game_id FROM '.GUILD_TABLE.' WHERE id > 0 ORDER BY '.$current_order['sql'];
		if (!($guild_result = $this->db->sql_query($sql)))
		{
			trigger_error($this->user->lang['ERROR_GUILDNOTFOUND'], E_USER_WARNING);
		}

		while ($row = $this->db->sql_fetchrow($guild_result))
		{
			$guild_count++;
			$listguild = new guilds($row['id']);
			$this->template->assign_block_vars(
				'guild_row',
				array(
					'ID'           => $listguild->getGuildid(),
					'NAME'         => $listguild->getName(),
					'REALM'        => $listguild->getRealm(),
					'REGION'       => $listguild->getRegion(),
					'GAME'         => $listguild->getGameId(),
					'PLAYERCOUNT'  => $listguild->getPlayercount(),
					'SHOW_ROSTER'  => $listguild->getShowroster() == 1 ? 'yes' : 'no',
					'U_VIEW_GUILD' => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=editguild&amp;'.URI_GUILD.'='.$listguild->getGuildid()),
				)
			);
		}

		$this->template->assign_vars(
			array(
				'U_GUILDLIST'            => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module').'&amp;mode=listguilds',
				'U_ADDGUILD'             => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module').'&amp;mode=addguild',
				'U_GUILD'                => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module').'&amp;mode=editguild',
				'L_TITLE'                => $this->user->lang['ACP_LISTGUILDS'],
				'L_EXPLAIN'              => $this->user->lang['ACP_LISTGUILDS_EXPLAIN'],
				'BUTTON_VALUE'           => $this->user->lang['DELETE_SELECTED_GUILDS'],
				'O_ID'                   => $current_order['uri'][0],
				'O_NAME'                 => $current_order['uri'][1],
				'O_REALM'                => $current_order['uri'][2],
				'O_REGION'               => $current_order['uri'][3],
				'O_ROSTER'               => $current_order['uri'][4],
				'U_LIST_GUILD'           => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\bbdkp\bbguild\acp\guild_module&amp;mode=listguilds'),
				'GUILDPLAYERS_FOOTCOUNT' => sprintf($this->user->lang['GUILD_FOOTCOUNT'], $guild_count),
			)
		);
		$this->page_title = 'ACP_LISTGUILDS';

	}//end BuildTemplateListGuilds()


}//end class
