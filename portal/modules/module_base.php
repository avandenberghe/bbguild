<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @copyright (c) 2023 Board3 Group (www.board3.de) — original design
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal\modules;

/**
 * Base class for portal modules. Subclasses override properties to define
 * their column constraints, name, image, etc.
 */
class module_base implements module_interface
{
	/** @var int Bitmask of allowed columns (top=1, center=4, right=8) */
	protected int $columns = 0;

	/** @var string Language key for module name */
	protected string $name = '';

	/** @var string Image filename */
	protected string $image_src = '';

	/** @var string|array|false Language file */
	protected $language = false;

	/** @var bool Allow multiple instances */
	protected bool $multiple_includes = false;

	/** @var int Current guild context */
	protected int $guild_id = 0;

	/**
	 * {@inheritdoc}
	 */
	public function get_allowed_columns(): int
	{
		return $this->columns;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_image(): string
	{
		return $this->image_src;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_language()
	{
		return $this->language;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_side(int $module_id)
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_acp(int $module_id): array
	{
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function install(int $module_id): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function uninstall(int $module_id): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function can_multi_include(): bool
	{
		return $this->multiple_includes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_guild_context(int $guild_id): void
	{
		$this->guild_id = $guild_id;
	}
}
