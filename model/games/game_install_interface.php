<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Game Install Interface
 * Contract for game installers that populate database tables with
 * game-specific factions, classes, races, and roles.
 *
 */

namespace avathar\bbguild\model\games;

/**
 * Interface game_install_interface
 *
 * @package avathar\bbguild\model\games
 */
interface game_install_interface
{
	/**
	 * Install the game data (factions, classes, races, roles) into the database.
	 *
	 * @param array  $table_names    Associative array of table names keyed by logical name
	 *                               (e.g. 'bb_games_table' => 'phpbb_bb_games')
	 * @param string $game_id        The unique game identifier
	 * @param string $game_name      The display name of the game
	 * @param string $boss_base_url  The base URL for boss database links
	 * @param string $zone_base_url  The base URL for zone database links
	 * @param string $region         The region code (e.g. 'eu', 'us')
	 * @return void
	 */
	public function install(array $table_names, string $game_id, string $game_name, string $boss_base_url, string $zone_base_url, string $region): void;

	/**
	 * Uninstall the game data from the database.
	 *
	 * @param array  $table_names Associative array of table names
	 * @param string $game_id     The unique game identifier
	 * @param string $game_name   The display name of the game
	 * @return void
	 */
	public function uninstall(array $table_names, string $game_id, string $game_name): void;
}
