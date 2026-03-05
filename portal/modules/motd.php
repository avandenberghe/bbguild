<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * MOTD (Message of the Day) portal module.
 * Reads the guild's welcome message from bb_motd and renders it with BBCode.
 */

namespace avathar\bbguild\portal\modules;

use phpbb\db\driver\driver_interface;
use phpbb\template\template;

class motd extends module_base
{
	protected int $columns = 5; // top + center
	protected string $name = 'BBGUILD_PORTAL_MOTD';
	protected string $image_src = '';

	protected driver_interface $db;
	protected template $template;
	protected string $motd_table;

	public function __construct(driver_interface $db, template $template, string $motd_table)
	{
		$this->db = $db;
		$this->template = $template;
		$this->motd_table = $motd_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		$sql = 'SELECT motd_msg, bbcode_uid, bbcode_bitfield, bbcode_options
			FROM ' . $this->motd_table . '
			WHERE guild_id = ' . (int) $this->guild_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$message = '';
		if ($row)
		{
			$message = generate_text_for_display(
				$row['motd_msg'],
				$row['bbcode_uid'],
				$row['bbcode_bitfield'],
				(int) $row['bbcode_options']
			);
			$message = smiley_text($message);
		}

		$this->template->assign_var('PORTAL_MOTD_MESSAGE', $message);

		return 'motd_center.html';
	}
}
