<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Custom HTML portal module.
 * Allows admins to place arbitrary BBCode/HTML content in any column.
 * Supports multiple instances.
 */

namespace avathar\bbguild\portal\modules;

use avathar\bbguild\portal\portal_config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;

class custom extends module_base
{
	protected int $columns = 13; // top + center + right
	protected string $name = 'BBGUILD_PORTAL_CUSTOM';
	protected string $image_src = '';
	protected bool $multiple_includes = true;

	protected driver_interface $db;
	protected template $template;
	protected portal_config $portal_config;

	public function __construct(driver_interface $db, template $template, portal_config $portal_config)
	{
		$this->db = $db;
		$this->template = $template;
		$this->portal_config = $portal_config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		return $this->render_custom($module_id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_side(int $module_id)
	{
		return $this->render_custom($module_id);
	}

	/**
	 * Render custom content block.
	 *
	 * @return array Template data with custom code
	 */
	protected function render_custom(int $module_id): array
	{
		$content = $this->portal_config->get('custom_code_' . $module_id, $this->guild_id);
		$title = $this->portal_config->get('custom_title_' . $module_id, $this->guild_id, $this->name);

		// Process BBCode if stored
		$uid = $this->portal_config->get('custom_bbcode_uid_' . $module_id, $this->guild_id);
		$bitfield = $this->portal_config->get('custom_bbcode_bitfield_' . $module_id, $this->guild_id);

		if ($uid || $bitfield)
		{
			$content = generate_text_for_display($content, $uid, $bitfield, OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS);
		}

		return [
			'template' => 'custom_center.html',
			'title'    => $title,
			'code'     => $content,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function install(int $module_id): bool
	{
		$this->portal_config->set('custom_code_' . $module_id, '', $this->guild_id);
		$this->portal_config->set('custom_title_' . $module_id, 'Custom Block', $this->guild_id);
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function uninstall(int $module_id): bool
	{
		$this->portal_config->delete_by_prefix('custom_', $this->guild_id);
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_acp(int $module_id): array
	{
		return [
			'title' => 'BBGUILD_PORTAL_CUSTOM_SETTINGS',
			'vars'  => [
				'legend1' => 'BBGUILD_PORTAL_CUSTOM_SETTINGS',
				'custom_title_' . $module_id => [
					'lang'     => 'BBGUILD_PORTAL_CUSTOM_TITLE',
					'validate' => 'string',
					'type'     => 'text:40:255',
				],
				'custom_code_' . $module_id => [
					'lang'     => 'BBGUILD_PORTAL_CUSTOM_CODE',
					'validate' => 'string',
					'type'     => 'textarea:10:60',
				],
			],
		];
	}
}
