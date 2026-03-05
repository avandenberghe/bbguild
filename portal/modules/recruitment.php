<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Recruitment portal module.
 * Shows open recruitment positions for the current guild.
 */

namespace avathar\bbguild\portal\modules;

use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;

class recruitment extends module_base
{
	protected int $columns = 12; // center + right
	protected string $name = 'BBGUILD_PORTAL_RECRUITMENT';
	protected string $image_src = '';

	protected driver_interface $db;
	protected template $template;
	protected user $user;
	protected config $config;
	protected string $recruit_table;
	protected string $classes_table;
	protected string $language_table;
	protected string $gameroles_table;

	public function __construct(
		driver_interface $db,
		template $template,
		user $user,
		config $config,
		string $recruit_table,
		string $classes_table,
		string $language_table,
		string $gameroles_table
	)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
		$this->recruit_table = $recruit_table;
		$this->classes_table = $classes_table;
		$this->language_table = $language_table;
		$this->gameroles_table = $gameroles_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		return $this->load_recruitment($module_id, 'recruitment_center.html');
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_side(int $module_id)
	{
		return $this->load_recruitment($module_id, 'recruitment_side.html');
	}

	/**
	 * Load recruitment data and assign template vars.
	 */
	protected function load_recruitment(int $module_id, string $template_file): string
	{
		$lang = $this->config['bbguild_lang'] ?? 'en';

		$sql = 'SELECT r.id, r.role_id, r.class_id, r.level, r.positions, r.applicants, r.status, r.note,
				l.name as class_name, c.colorcode, c.imagename,
				gr.role_id as gr_role_id
			FROM ' . $this->recruit_table . ' r
			LEFT JOIN ' . $this->classes_table . ' c ON r.class_id = c.class_id
			LEFT JOIN ' . $this->language_table . " l ON r.class_id = l.attribute_id
				AND l.attribute = 'class' AND l.language = '" . $this->db->sql_escape($lang) . "'
				AND l.game_id = c.game_id
			LEFT JOIN " . $this->gameroles_table . ' gr ON r.role_id = gr.role_id
			WHERE r.guild_id = ' . (int) $this->guild_id . '
				AND r.status = 1
			ORDER BY r.role_id, r.class_id';
		$result = $this->db->sql_query($sql);

		$has_recruits = false;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$has_recruits = true;
			$open = max(0, (int) $row['positions'] - (int) $row['applicants']);

			$this->template->assign_block_vars('portal_recruit', [
				'CLASS_NAME' => $row['class_name'] ?? $this->user->lang['UNKNOWN'],
				'COLORCODE'  => $row['colorcode'] ?? '',
				'IMAGENAME'  => $row['imagename'] ?? '',
				'POSITIONS'  => $row['positions'],
				'APPLICANTS' => $row['applicants'],
				'OPEN'       => $open,
				'LEVEL'      => $row['level'],
				'NOTE'       => $row['note'],
			]);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_var('S_PORTAL_HAS_RECRUITS', $has_recruits);

		return $template_file;
	}
}
