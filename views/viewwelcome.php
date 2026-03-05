<?php
/**
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Welcome module — renders the portal page for a guild.
 */

namespace avathar\bbguild\views;

class viewwelcome implements iviews
{
	private $navigation;
	public $response;
	private $tpl;

	/**
	 * @param viewnavigation $navigation
	 */
	public function __construct(viewnavigation $navigation)
	{
		$this->navigation = $navigation;
		$this->buildpage();
	}

	/**
	 * Build the welcome/portal page by delegating to the portal renderer.
	 */
	public function buildpage()
	{
		global $template;

		$this->tpl = 'main.html';
		$guild_id = (int) $this->navigation->guild_id;

		// Render all portal modules for this guild
		$this->navigation->view_controller->portal_renderer->render($guild_id);

		$template->assign_vars([
			'GUILD_FACTION'     => $this->navigation->guild->getFactionname(),
			'S_DISPLAY_WELCOME' => true,
		]);

		$title = $this->navigation->user->lang['WELCOME'];
		$this->response = $this->navigation->helper->render($this->tpl, $title);
	}
}
