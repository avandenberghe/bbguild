<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Game API Interface
 * Optional contract for games that have an external API integration
 * (e.g. Battle.net for WoW).
 *
 */

namespace avathar\bbguild\model\games;

/**
 * Interface game_api_interface
 *
 * @package avathar\bbguild\model\games
 */
interface game_api_interface
{
	/**
	 * Fetch guild data from the external API.
	 *
	 * @param string $guild_name The guild name
	 * @param string $realm      The realm/server name
	 * @param string $region     The region code (e.g. 'eu', 'us')
	 * @param array  $params     Additional parameters
	 * @return mixed Raw API response data
	 */
	public function fetch_guild_data(string $guild_name, string $realm, string $region, array $params);

	/**
	 * Process raw guild data from the API into a normalized format.
	 *
	 * @param array $raw_data Raw data from fetch_guild_data()
	 * @param array $params   Additional parameters
	 * @return array Processed guild data
	 */
	public function process_guild_data(array $raw_data, array $params): array;

	/**
	 * Fetch individual character data from the external API.
	 *
	 * @param string $name   The character name
	 * @param string $realm  The realm/server name
	 * @param string $region The region code
	 * @return mixed Raw API response data
	 */
	public function fetch_character_data(string $name, string $realm, string $region);

	/**
	 * Get the armory/profile URL for a player.
	 *
	 * @param string $name   The character name
	 * @param string $realm  The realm/server name
	 * @param string $region The region code
	 * @return string The URL to the player's armory/profile page
	 */
	public function get_player_armory_url(string $name, string $realm, string $region): string;

	/**
	 * Get the portrait/avatar URL for a player.
	 *
	 * @param array $player_data Player data array
	 * @return string The URL to the player's portrait image
	 */
	public function get_player_portrait_url(array $player_data): string;

	/**
	 * Sync guild members from API data into the database.
	 *
	 * @param array  $member_data The raw member data from the API
	 * @param int    $guild_id    The internal guild ID
	 * @param string $region      The region code
	 * @param int    $min_level   Minimum level filter
	 * @return void
	 */
	public function sync_guild_members(array $member_data, int $guild_id, string $region, int $min_level): void;

	/**
	 * Whether this API requires an API key to function.
	 *
	 * @return bool
	 */
	public function requires_api_key(): bool;

	/**
	 * Persist game-specific guild extension data to the plugin's own table.
	 *
	 * Called by guilds::update_guild_battleNet() after saving generic guild
	 * fields to bb_guild. Each game plugin stores its own fields (e.g. WoW
	 * stores battlegroup, level, achievementpoints, guildarmoryurl).
	 *
	 * @param int   $guild_id  The internal guild ID
	 * @param array $processed Processed data from process_guild_data()
	 * @return void
	 */
	public function save_guild_extension(int $guild_id, array $processed): void;
}
