<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Guild ACP module — thin dispatcher (delegates to admin_guild controller)
 *
 */

namespace avathar\bbguild\acp;

class guild_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * ACP guild function
	 *
	 * @param int $id   the id of the node who parent has to be returned by function
	 * @param int $mode id of the submenu
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		$admin_guild = $phpbb_container->get('avathar.bbguild.admin.guild');

		$form_key = 'avathar/bbguild';
		add_form_key($form_key);
		$this->tpl_name = 'acp_' . $mode;

		if (!$admin_guild->auth->acl_get('a_bbguild'))
		{
			trigger_error($admin_guild->user->lang['NOAUTH_A_GUILD_MAN'], E_USER_WARNING);
		}

		switch ($mode)
		{
			case 'listguilds':
				$admin_guild->BuildTemplateListGuilds();
				break;

			case 'addguild':
				$admin_guild->show_addguild();
				break;

			case 'editguild':
				$admin_guild->show_editguild_dispatch();
				break;

			default:
				$this->page_title = 'ACP_BBGUILD_MAINPAGE';
				trigger_error('Error' . '<br /><a href="' . append_sid("index.php", 'i=-avathar-bbguild-acp-guild_module&amp;mode=listguilds') . '"><h3>' . $admin_guild->user->lang['RETURN_GUILDLIST'] . '</h3></a>', E_USER_WARNING);
		}

		// Propagate tpl_name/page_title back if controller changed them
		if (isset($admin_guild->tpl_name))
		{
			$this->tpl_name = $admin_guild->tpl_name;
		}
		if (isset($admin_guild->page_title))
		{
			$this->page_title = $admin_guild->page_title;
		}
	}
}
