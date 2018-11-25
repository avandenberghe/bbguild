<?php
/**
 * Guild ACP file
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\acp;

use avathar\bbguild\model\admin\admin;
use avathar\bbguild\model\games\game;
use avathar\bbguild\model\games\rpg\faction;
use avathar\bbguild\model\player\guilds;
use avathar\bbguild\model\player\ranks;

/**
 * This class manages guilds
 *
 * @package bbguild
 */
class guild_module
{

	/* @var string */
	public $u_action;
	/* @var string  */
	public $url_id;
	/* @var \phpbb\request\request **/
	protected $request;
	/* @var \phpbb\template\template **/
	protected $template;
	/* @var \phpbb\user  **/
	protected $user;
	/* @var \phpbb\db\driver\driver_interface */
	protected $db;
	/* @var \phpbb\config\config */
	protected $config;
	public $id;
	public $mode;

	protected $factions;
	/* @var \avathar\bbguild\controller\admin_controller */
	protected $admin_controller;
	/* @var \phpbb\controller\helper */
	protected  $helper;
	protected $phpbb_container;
	/* @var string */
	protected $factionroute;
	/* @type game */
	private $game;

	/**
	 * ACP guild function
	 *
	 * @param int $id   the id of the node who parent has to be returned by function
	 * @param int $mode id of the submenu
	 */
	public function main($id, $mode)
	{
		global  $db, $phpbb_admin_path, $phpEx;
		global $request, $auth;

		global $phpbb_container;


		$this->id       = $id;
		$this->mode     = $mode;
		$this->request  = $request;


		$this->db       = $db;
		$this->auth     = $auth;
		$this->phpbb_container = $phpbb_container;
		$this->admin_controller = $this->phpbb_container->get('avathar.bbguild.admin.controller');
		$this->helper = $phpbb_container->get('controller.helper');
		$this->factionroute =  $this->helper->route('avathar_bbguild_01', array());


		$form_key = 'avathar/bbguild';
		add_form_key($form_key);
		$this->tpl_name   = 'acp_'.$mode;
		$this->link       = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx",
				'i=-aavathar-bbguild-acp-guild_module&amp;mode=listguilds').'"><h3>'.$this->user->lang['RETURN_GUILDLIST'].'</h3></a>';
		$this->page_title = 'ACP_LISTGUILDS';

		switch ($mode)
		{
			case 'listguilds':
				$this->BuildTemplateListGuilds();
				break;

			case 'addguild':
				$this->show_addguild($config);

				break;
			case 'editguild':
				$this->url_id = $this->request->variable(URI_GUILD, 0);
				$updateguild  = new guilds($this->url_id);

				$this->game          = new game;
				$this->game->game_id = $updateguild->getGameId();
				$this->game->get_game();

				if ($this->request->is_set_post('playeradd'))
				{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", 'i=-avathar-bbguild-acp-mm_module&amp;mode=addplayer&amp;'.URI_GUILD. '=' .$this->url_id));
				}

				$action = $this->request->variable('action', '');
				switch ($action)
				{
					case 'guildranks':
						$this->show_editguildranks($updateguild);
						break;

					case 'editguild':
					default:
						$this->show_editguild($updateguild);
						break;
				}//end switch
				break;

			default:
				$this->page_title = 'ACP_BBGUILD_MAINPAGE';
				$success_message  = 'Error';
				trigger_error($success_message.$this->link, E_USER_WARNING);
		}//end switch

	}//end main()


}//end class
