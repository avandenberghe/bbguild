<?php
/**
 *
 * bbGuild games ACP
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
				$admin_games->editgames();
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

	}
}
