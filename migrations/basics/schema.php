<?php
/**
 * bbGuild - Initial schema migration
 *
 * @package   avathar\bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\migrations\basics;

class schema extends \phpbb\db\migration\migration
{
	protected $bbgames_table;
	protected $news_table;
	protected $bblogs_table;
	protected $player_ranks_table;
	protected $player_list_table;
	protected $class_table;
	protected $race_table;
	protected $faction_table;
	protected $guild_table;
	protected $bb_language;
	protected $motd_table;
	protected $bbrecruit_table;
	protected $bb_gamerole_table;

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'bb_games');
	}

	public function update_schema()
	{
		$this->GetTablenames();

		return [
			'add_tables' => [
				/* 1 - news */
				$this->news_table => [
					'COLUMNS' => [
						'news_id'           => ['UINT', null, 'auto_increment'],
						'news_headline'     => ['VCHAR_UNI', ''],
						'news_message'      => ['TEXT_UNI', ''],
						'news_date'         => ['TIMESTAMP', 0],
						'user_id'           => ['UINT', 0],
						'bbcode_bitfield'   => ['VCHAR:20', ''],
						'bbcode_uid'        => ['VCHAR:8', ''],
						'bbcode_options'    => ['VCHAR:8', ''],
					],
					'PRIMARY_KEY' => 'news_id',
				],
				/* 2 - language */
				$this->bb_language => [
					'COLUMNS' => [
						'id'                => ['UINT', null, 'auto_increment'],
						'game_id'           => ['VCHAR:10', ''],
						'attribute_id'      => ['UINT', 0],
						'language'          => ['CHAR:2', ''],
						'attribute'         => ['VCHAR:30', ''],
						'name'              => ['VCHAR_UNI:255', ''],
						'name_short'        => ['VCHAR_UNI:255', ''],
					],
					'PRIMARY_KEY' => ['id'],
					'KEYS'        => ['UQ01' => ['UNIQUE', ['game_id', 'attribute_id', 'language', 'attribute']]],
				],
				/* 3 - factions */
				$this->faction_table => [
					'COLUMNS' => [
						'game_id'           => ['VCHAR:10', ''],
						'f_index'           => ['USINT', null, 'auto_increment'],
						'faction_id'        => ['USINT', 0],
						'faction_name'      => ['VCHAR_UNI', ''],
						'faction_hide'      => ['BOOL', 0],
					],
					'PRIMARY_KEY' => 'f_index',
					'KEYS'        => ['UQ02' => ['UNIQUE', ['game_id', 'faction_id']]],
				],
				/* 4 - classes */
				$this->class_table => [
					'COLUMNS' => [
						'c_index'           => ['USINT', null, 'auto_increment'],
						'game_id'           => ['VCHAR:10', ''],
						'class_id'          => ['USINT', 0],
						'class_faction_id'  => ['USINT', 0],
						'class_min_level'   => ['USINT', 0],
						'class_max_level'   => ['USINT', 0],
						'class_armor_type'  => ['VCHAR_UNI', ''],
						'class_hide'        => ['BOOL', 0],
						'imagename'         => ['VCHAR:255', ''],
						'colorcode'         => ['VCHAR:10', ''],
					],
					'PRIMARY_KEY' => 'c_index',
					'KEYS'        => ['UQ01' => ['UNIQUE', ['game_id', 'class_id']]],
				],
				/* 5 - races */
				$this->race_table => [
					'COLUMNS' => [
						'game_id'           => ['VCHAR:10', ''],
						'race_id'           => ['USINT', 0],
						'race_faction_id'   => ['USINT', 0],
						'race_hide'         => ['BOOL', 0],
						'image_female'      => ['VCHAR:255', ''],
						'image_male'        => ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => ['game_id', 'race_id'],
				],
				/* 6 - guilds */
				$this->guild_table => [
					'COLUMNS' => [
						'id'                => ['USINT', 0],
						'name'              => ['VCHAR_UNI:255', ''],
						'realm'             => ['VCHAR_UNI:255', ''],
						'region'            => ['VCHAR:3', ''],
						'roster'            => ['BOOL', 0],
						'players'           => ['UINT', 0],
						'emblemurl'         => ['VCHAR:255', ''],
						'game_id'           => ['VCHAR:10', ''],
						'min_armory'        => ['UINT', 90],
						'rec_status'        => ['BOOL', 0],
						'guilddefault'      => ['BOOL', 0],
						'armory_enabled'    => ['BOOL', 0],
						'armoryresult'      => ['VCHAR_UNI:255', ''],
						'recruitforum'      => ['UINT', 0],
						'faction'           => ['UINT', 0],
					],
					'PRIMARY_KEY' => ['id'],
				],
				/* 7 - ranks */
				$this->player_ranks_table => [
					'COLUMNS' => [
						'guild_id'          => ['USINT', 0],
						'rank_id'           => ['USINT', 0],
						'rank_name'         => ['VCHAR_UNI:50', ''],
						'rank_hide'         => ['BOOL', 0],
						'rank_prefix'       => ['VCHAR:75', ''],
						'rank_suffix'       => ['VCHAR:75', ''],
					],
					'PRIMARY_KEY' => ['rank_id', 'guild_id'],
				],
				/* 8 - players */
				$this->player_list_table => [
					'COLUMNS' => [
						'player_id'           => ['UINT', null, 'auto_increment'],
						'game_id'             => ['VCHAR:10', ''],
						'player_name'         => ['VCHAR_UNI:100', ''],
						'player_region'       => ['VCHAR', ''],
						'player_realm'        => ['VCHAR:30', ''],
						'player_title'        => ['VCHAR_UNI:100', ''],
						'player_level'        => ['USINT', 0],
						'player_race_id'      => ['USINT', 0],
						'player_class_id'     => ['USINT', 0],
						'player_rank_id'      => ['USINT', 0],
						'player_role'         => ['VCHAR:20', ''],
						'player_comment'      => ['TEXT_UNI', ''],
						'player_joindate'     => ['TIMESTAMP', 0],
						'player_outdate'      => ['TIMESTAMP', 0],
						'player_guild_id'     => ['USINT', 0],
						'player_gender_id'    => ['USINT', 0],
						'player_achiev'       => ['UINT', 0],
						'player_armory_url'   => ['VCHAR:255', ''],
						'player_portrait_url' => ['VCHAR', ''],
						'phpbb_user_id'       => ['UINT', 0],
						'player_status'       => ['BOOL', 0],
						'deactivate_reason'   => ['VCHAR_UNI:255', ''],
						'last_update'         => ['TIMESTAMP', 0],
					],
					'PRIMARY_KEY' => 'player_id',
					'KEYS'        => ['UQ01' => ['UNIQUE', ['player_guild_id', 'player_name', 'player_realm']]],
				],
				/* 9 - motd */
				$this->motd_table => [
					'COLUMNS' => [
						'motd_id'         => ['UINT', null, 'auto_increment'],
						'motd_title'      => ['VCHAR_UNI', ''],
						'motd_msg'        => ['TEXT_UNI', ''],
						'motd_timestamp'  => ['TIMESTAMP', 0],
						'bbcode_bitfield' => ['VCHAR:255', ''],
						'bbcode_uid'      => ['VCHAR:8', ''],
						'user_id'         => ['UINT', 0],
						'bbcode_options'  => ['UINT', 7],
					],
					'PRIMARY_KEY' => 'motd_id',
				],
				/* 10 - games */
				$this->bbgames_table => [
					'COLUMNS' => [
						'id'             => ['UINT', null, 'auto_increment'],
						'game_id'        => ['VCHAR:10', ''],
						'game_name'      => ['VCHAR_UNI:255', ''],
						'region'         => ['VCHAR:3', ''],
						'status'         => ['VCHAR:30', ''],
						'imagename'      => ['VCHAR:20', ''],
						'armory_enabled' => ['UINT', 0],
						'bossbaseurl'    => ['VCHAR:255', ''],
						'zonebaseurl'    => ['VCHAR:255', ''],
						'apikey'         => ['VCHAR:255', ''],
						'apilocale'      => ['VCHAR:5', ''],
						'privkey'        => ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => ['id'],
					'KEYS'        => ['UQ01' => ['UNIQUE', ['game_id']]],
				],
				/* 11 - game roles */
				$this->bb_gamerole_table => [
					'COLUMNS' => [
						'role_pkid'     => ['UINT', null, 'auto_increment'],
						'game_id'       => ['VCHAR:10', ''],
						'role_id'       => ['INT', 0],
						'role_color'    => ['VCHAR', ''],
						'role_icon'     => ['VCHAR', ''],
						'role_cat_icon' => ['VCHAR', ''],
					],
					'PRIMARY_KEY' => 'role_pkid',
					'KEYS'        => ['UQ01' => ['UNIQUE', ['game_id', 'role_id']]],
				],
				/* 12 - recruitment */
				$this->bbrecruit_table => [
					'COLUMNS' => [
						'id'               => ['UINT', null, 'auto_increment'],
						'guild_id'         => ['USINT', 0],
						'role_id'          => ['INT', 0],
						'class_id'         => ['UINT', 0],
						'level'            => ['UINT', 0],
						'positions'        => ['USINT', 0],
						'applicants'       => ['USINT', 0],
						'status'           => ['USINT', 0],
						'applytemplate_id' => ['UINT', 0],
						'last_update'      => ['TIMESTAMP', 0],
						'note'             => ['TEXT_UNI', ''],
					],
					'PRIMARY_KEY' => 'id',
				],
				/* 13 - logs */
				$this->bblogs_table => [
					'COLUMNS' => [
						'log_id'        => ['UINT', null, 'auto_increment'],
						'log_date'      => ['TIMESTAMP', 0],
						'log_type'      => ['VCHAR_UNI:255', ''],
						'log_action'    => ['TEXT_UNI', ''],
						'log_ipaddress' => ['VCHAR:45', ''],
						'log_sid'       => ['VCHAR:32', ''],
						'log_result'    => ['VCHAR', ''],
						'log_userid'    => ['UINT', 0],
					],
					'PRIMARY_KEY' => 'log_id',
					'KEYS'        => [
						'I01' => ['INDEX', 'log_userid'],
						'I02' => ['INDEX', 'log_type'],
						'I03' => ['INDEX', 'log_ipaddress'],
					],
				],
			],
		];
	}

	public function revert_schema()
	{
		$this->GetTablenames();

		return [
			'drop_tables' => [
				$this->news_table,
				$this->bb_language,
				$this->faction_table,
				$this->class_table,
				$this->race_table,
				$this->guild_table,
				$this->player_ranks_table,
				$this->player_list_table,
				$this->motd_table,
				$this->bbgames_table,
				$this->bb_gamerole_table,
				$this->bbrecruit_table,
				$this->bblogs_table,
			],
		];
	}

	private function GetTablenames()
	{
		$this->bbgames_table              = $this->table_prefix . 'bb_games';
		$this->news_table                 = $this->table_prefix . 'bb_news';
		$this->bblogs_table               = $this->table_prefix . 'bb_logs';
		$this->player_ranks_table         = $this->table_prefix . 'bb_ranks';
		$this->player_list_table          = $this->table_prefix . 'bb_players';
		$this->class_table                = $this->table_prefix . 'bb_classes';
		$this->race_table                 = $this->table_prefix . 'bb_races';
		$this->faction_table              = $this->table_prefix . 'bb_factions';
		$this->guild_table                = $this->table_prefix . 'bb_guild';
		$this->bb_language                = $this->table_prefix . 'bb_language';
		$this->motd_table                 = $this->table_prefix . 'bb_motd';
		$this->bbrecruit_table            = $this->table_prefix . 'bb_recruit';
		$this->bb_gamerole_table          = $this->table_prefix . 'bb_gameroles';
	}
}
