<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 */

namespace avathar\bbguild\portal;

use phpbb\cache\driver\driver_interface as cache_interface;
use phpbb\db\driver\driver_interface;

/**
 * OOP replacement for Board3's procedural obtain_portal_config()/set_portal_config().
 * Config is scoped per guild_id.
 */
class portal_config
{
	protected driver_interface $db;
	protected cache_interface $cache;
	protected string $table;

	/** @var array<int, array<string, string>> In-memory config keyed by guild_id */
	protected array $configs = [];

	public function __construct(driver_interface $db, cache_interface $cache, string $portal_config_table)
	{
		$this->db = $db;
		$this->cache = $cache;
		$this->table = $portal_config_table;
	}

	/**
	 * Get all portal config values for a guild.
	 *
	 * @return array<string, string>
	 */
	public function obtain(int $guild_id): array
	{
		if (isset($this->configs[$guild_id]))
		{
			return $this->configs[$guild_id];
		}

		$cache_key = 'bbguild_portal_config_' . $guild_id;
		$cached = $this->cache->get($cache_key);

		if ($cached !== false)
		{
			$this->configs[$guild_id] = $cached;
			return $cached;
		}

		$config = [];
		$sql = 'SELECT config_name, config_value
			FROM ' . $this->table . '
			WHERE guild_id = ' . (int) $guild_id;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$config[$row['config_name']] = $row['config_value'];
		}
		$this->db->sql_freeresult($result);

		$this->cache->put($cache_key, $config);
		$this->configs[$guild_id] = $config;

		return $config;
	}

	/**
	 * Set a portal config value for a guild. Creates the entry if missing.
	 */
	public function set(string $name, string $value, int $guild_id): void
	{
		$sql = 'UPDATE ' . $this->table . "
			SET config_value = '" . $this->db->sql_escape($value) . "'
			WHERE config_name = '" . $this->db->sql_escape($name) . "'
				AND guild_id = " . (int) $guild_id;
		$this->db->sql_query($sql);

		if (!$this->db->sql_affectedrows())
		{
			$sql = 'INSERT INTO ' . $this->table . ' ' . $this->db->sql_build_array('INSERT', [
				'config_name'  => $name,
				'config_value' => $value,
				'guild_id'     => (int) $guild_id,
			]);
			$this->db->sql_query($sql);
		}

		// Invalidate cache
		$this->cache->destroy('bbguild_portal_config_' . $guild_id);
		unset($this->configs[$guild_id]);
	}

	/**
	 * Get a single config value with default fallback.
	 */
	public function get(string $name, int $guild_id, string $default = ''): string
	{
		$config = $this->obtain($guild_id);
		return $config[$name] ?? $default;
	}

	/**
	 * Delete all config entries for a module.
	 */
	public function delete_by_prefix(string $prefix, int $guild_id): void
	{
		$sql = 'DELETE FROM ' . $this->table . "
			WHERE config_name " . $this->db->sql_like_expression($prefix . $this->db->get_any_char()) . "
				AND guild_id = " . (int) $guild_id;
		$this->db->sql_query($sql);

		$this->cache->destroy('bbguild_portal_config_' . $guild_id);
		unset($this->configs[$guild_id]);
	}
}
