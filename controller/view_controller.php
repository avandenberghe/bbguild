<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\bbguild\controller;

use avathar\bbguild\portal\guild_context;
use avathar\bbguild\portal\portal_renderer;

/**
 * Front-end controller for the guild portal page.
 * Handles the /guild/{page}/{guild_id} route.
 */
class view_controller
{
	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var guild_context */
	protected $guild_context;

	/** @var portal_renderer */
	protected $portal_renderer;

	/**
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbb\template\template $template
	 * @param guild_context            $guild_context
	 * @param portal_renderer          $portal_renderer
	 */
	public function __construct(
		\phpbb\controller\helper $helper,
		\phpbb\template\template $template,
		guild_context $guild_context,
		portal_renderer $portal_renderer
	)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->guild_context = $guild_context;
		$this->portal_renderer = $portal_renderer;
	}

	/**
	 * Main view handler — builds guild context and renders portal.
	 *
	 * @param  int    $guild_id
	 * @param  string $page  Kept for route compatibility (unused)
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handleview($guild_id, $page = 'welcome')
	{
		// Build guild context (header, guild dropdown)
		$this->guild_context->init((int) $guild_id);

		// Render all portal modules (MOTD, Roster, News, etc.)
		$this->portal_renderer->render($this->guild_context->guild_id);

		$this->template->assign_vars(['S_DISPLAY_WELCOME' => true]);

		return $this->helper->render('main.html', $this->guild_context->guild->getName());
	}
}
