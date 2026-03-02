<?php
/**
 * Game Registry
 *
 * Collects all game providers registered via tagged services.
 * Game plugins register by tagging their service with 'bbguild.game_provider'.
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\model\games;

/**
 * Class game_registry
 *
 * Central registry that collects all game_provider_interface implementations
 * injected via Symfony's tagged service iterator.
 *
 * @package avathar\bbguild\model\games
 */
class game_registry
{
	/** @var game_provider_interface[] Indexed by game_id */
	private $providers = [];

	/**
	 * Constructor.
	 *
	 * @param iterable $providers Tagged game provider services
	 */
	public function __construct(iterable $providers)
	{
		foreach ($providers as $provider)
		{
			$this->providers[$provider->get_game_id()] = $provider;
		}
	}

	/**
	 * Get a game provider by its game_id.
	 *
	 * @param string $game_id The unique game identifier
	 * @return game_provider_interface|null The provider, or null if not registered
	 */
	public function get(string $game_id): ?game_provider_interface
	{
		return $this->providers[$game_id] ?? null;
	}

	/**
	 * Get all registered game providers.
	 *
	 * @return game_provider_interface[] Indexed by game_id
	 */
	public function get_all(): array
	{
		return $this->providers;
	}

	/**
	 * Get all games that can be installed (for the ACP game install list).
	 *
	 * @return array Associative array of game_id => game_name
	 */
	public function get_installable_games(): array
	{
		$games = [];
		foreach ($this->providers as $game_id => $provider)
		{
			$games[$game_id] = $provider->get_game_name();
		}
		return $games;
	}

	/**
	 * Check if a game provider is registered.
	 *
	 * @param string $game_id The unique game identifier
	 * @return bool
	 */
	public function has(string $game_id): bool
	{
		return isset($this->providers[$game_id]);
	}
}
