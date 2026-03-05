<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Guild News portal module.
 * Displays news items from bb_news with BBCode support.
 */

namespace avathar\bbguild\portal\modules;

use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;

class guild_news extends module_base
{
	protected int $columns = 5; // top + center
	protected string $name = 'BBGUILD_PORTAL_GUILD_NEWS';
	protected string $image_src = '';

	protected driver_interface $db;
	protected template $template;
	protected user $user;
	protected string $news_table;

	/** @var int Max news items to display */
	protected int $max_items = 5;

	public function __construct(driver_interface $db, template $template, user $user, string $news_table)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->news_table = $news_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		$sql = 'SELECT n.news_id, n.news_headline, n.news_message, n.news_date,
				n.user_id, n.bbcode_uid, n.bbcode_bitfield, n.bbcode_options,
				u.username, u.user_colour
			FROM ' . $this->news_table . ' n
			LEFT JOIN ' . USERS_TABLE . ' u ON n.user_id = u.user_id
			ORDER BY n.news_date DESC';
		$result = $this->db->sql_query_limit($sql, $this->max_items);

		$has_news = false;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$has_news = true;
			$message = generate_text_for_display(
				$row['news_message'],
				$row['bbcode_uid'],
				$row['bbcode_bitfield'],
				(int) $row['bbcode_options']
			);

			$this->template->assign_block_vars('portal_news', [
				'HEADLINE'  => $row['news_headline'],
				'MESSAGE'   => $message,
				'DATE'      => $this->user->format_date($row['news_date']),
				'AUTHOR'    => get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'NEWS_ID'   => $row['news_id'],
			]);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_var('S_PORTAL_HAS_NEWS', $has_news);

		return 'guild_news_center.html';
	}
}
