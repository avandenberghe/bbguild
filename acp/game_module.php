<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * bbGuild Games ACP
 *
 */

namespace avathar\bbguild\acp;

class game_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 *
	 * @param  int $id   the id of the node who parent has to be returned by function
	 * @param  int $mode id of the submenu
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		// Get an instance of the games admin controller
		$admin_games = $phpbb_container->get('avathar.bbguild.admin.games');

		$form_key = 'avathar/bbguild';
		add_form_key($form_key);
		$this->tpl_name = 'acp_' . $mode;

		if (! $admin_games->auth->acl_get('a_bbguild'))
		{
			trigger_error($admin_games->language->lang('NOAUTH_A_GAME_MAN'), E_USER_WARNING);
		}

		switch ($mode)
		{
			case 'listgames' :
				$admin_games->listgames();
				break;

			case 'editgames' :
				$admin_games->gamelist();
				break;

			case 'addrole' :
				$admin_games->show_addrole();
				break;

			case 'addfaction' :
				$admin_games->show_addfaction();
				break;

			case 'addrace' :
				$admin_games->show_addrace();
				break;

			case 'addclass':
				$admin_games->show_addclass();
				break;
		}

		// Allow the controller to override the template name (for sub-forms)
		if (!empty($admin_games->tpl_name))
		{
			$this->tpl_name = $admin_games->tpl_name;
		}

		if (!empty($admin_games->page_title))
		{
			$this->page_title = $admin_games->page_title;
		}

	}
}
