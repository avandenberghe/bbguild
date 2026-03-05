<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal ACP module — thin dispatcher (delegates to admin_portal controller)
 */

namespace avathar\bbguild\acp;

class portal_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \avathar\bbguild\controller\admin_portal $admin_portal */
		$admin_portal = $phpbb_container->get('avathar.bbguild.admin.portal');

		/** @var \phpbb\language\language $lang */
		$lang = $phpbb_container->get('language');

		$admin_portal->set_u_action($this->u_action);

		switch ($mode)
		{
			case 'portal':
			default:
				$this->page_title = $lang->lang('ACP_BBGUILD_PORTAL');
				$this->tpl_name = 'acp_portal';
				$admin_portal->display();
				break;
		}
	}
}
