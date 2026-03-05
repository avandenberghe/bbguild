<?php
/**
 *
 * @package bbGuild Extension
 * @copyright (c) 2018 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Data migration
 */

namespace avathar\bbguild\migrations\basics;

class data extends \phpbb\db\migration\container_aware_migration
{
	public static function depends_on()
	{
		return ['\avathar\bbguild\migrations\basics\schema'];
	}

	public function effectively_installed()
	{
		$guild_table = $this->table_prefix . 'bb_guild';

		if (!$this->db_tools->sql_table_exists($guild_table))
		{
			return false;
		}

		$sql = 'SELECT COUNT(*) AS cnt FROM ' . $guild_table . ' WHERE id = 0';
		$result = $this->db->sql_query($sql);
		$count = (int) $this->db->sql_fetchfield('cnt');
		$this->db->sql_freeresult($result);

		return $count > 0;
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'insert_sample_data']]],
		];
	}

	public function revert_data()
	{
		return [
			['custom', [[$this, 'remove_sample_data']]],
		];
	}

	public function insert_sample_data()
	{
		$user = $this->container->get('user');
		$user->add_lang_ext('avathar/bbguild', 'admin');
		$welcome_message = $this->encode_message($user->lang['MOTD']);

		$games_table    = $this->table_prefix . 'bb_games';
		$faction_table  = $this->table_prefix . 'bb_factions';
		$class_table    = $this->table_prefix . 'bb_classes';
		$race_table     = $this->table_prefix . 'bb_races';
		$role_table     = $this->table_prefix . 'bb_gameroles';
		$lang_table     = $this->table_prefix . 'bb_language';
		$guild_table    = $this->table_prefix . 'bb_guild';
		$rank_table     = $this->table_prefix . 'bb_ranks';
		$player_table   = $this->table_prefix . 'bb_players';
		$motd_table     = $this->table_prefix . 'bb_motd';
		$news_table     = $this->table_prefix . 'bb_news';
		$recruit_table  = $this->table_prefix . 'bb_recruit';
		$logs_table     = $this->table_prefix . 'bb_logs';

		$now = (int) time();

		// -- bb_games: Custom game --
		if ($this->db_tools->sql_table_exists($games_table))
		{
			$this->db->sql_multi_insert($games_table, [
				[
					'game_id'        => 'custom',
					'game_name'      => 'Custom Game',
					'region'         => '',
					'status'         => 'active',
					'imagename'      => 'custom',
					'armory_enabled' => 0,
					'bossbaseurl'    => '',
					'zonebaseurl'    => '',
					'apikey'         => '',
					'apilocale'      => '',
					'privkey'        => '',
				],
			]);
		}

		// -- bb_factions: 2 factions --
		if ($this->db_tools->sql_table_exists($faction_table))
		{
			$this->db->sql_multi_insert($faction_table, [
				['game_id' => 'custom', 'faction_id' => 1, 'faction_name' => 'Alliance', 'faction_hide' => 0],
				['game_id' => 'custom', 'faction_id' => 2, 'faction_name' => 'Horde',    'faction_hide' => 0],
			]);
		}

		// -- bb_classes: 4 classes (0=Unknown + 3 real) --
		if ($this->db_tools->sql_table_exists($class_table))
		{
			$this->db->sql_multi_insert($class_table, [
				['game_id' => 'custom', 'class_id' => 0, 'class_faction_id' => 0, 'class_min_level' => 1, 'class_max_level' => 60, 'class_armor_type' => '',        'class_hide' => 1, 'imagename' => '', 'colorcode' => '#999999'],
				['game_id' => 'custom', 'class_id' => 1, 'class_faction_id' => 0, 'class_min_level' => 1, 'class_max_level' => 60, 'class_armor_type' => 'PLATE',   'class_hide' => 0, 'imagename' => 'custom_warrior', 'colorcode' => '#c69b6d'],
				['game_id' => 'custom', 'class_id' => 2, 'class_faction_id' => 0, 'class_min_level' => 1, 'class_max_level' => 60, 'class_armor_type' => 'CLOTH',   'class_hide' => 0, 'imagename' => 'custom_mage',    'colorcode' => '#3fc7eb'],
				['game_id' => 'custom', 'class_id' => 3, 'class_faction_id' => 0, 'class_min_level' => 1, 'class_max_level' => 60, 'class_armor_type' => 'CLOTH',   'class_hide' => 0, 'imagename' => 'custom_priest',  'colorcode' => '#ffffff'],
			]);
		}

		// -- bb_races: 4 races (0=Unknown + 3 real) --
		if ($this->db_tools->sql_table_exists($race_table))
		{
			$this->db->sql_multi_insert($race_table, [
				['game_id' => 'custom', 'race_id' => 0, 'race_faction_id' => 0, 'race_hide' => 1, 'image_female' => '', 'image_male' => ''],
				['game_id' => 'custom', 'race_id' => 1, 'race_faction_id' => 1, 'race_hide' => 0, 'image_female' => 'custom_human_female', 'image_male' => 'custom_human_male'],
				['game_id' => 'custom', 'race_id' => 2, 'race_faction_id' => 1, 'race_hide' => 0, 'image_female' => 'custom_elf_female',   'image_male' => 'custom_elf_male'],
				['game_id' => 'custom', 'race_id' => 3, 'race_faction_id' => 2, 'race_hide' => 0, 'image_female' => 'custom_orc_female',   'image_male' => 'custom_orc_male'],
			]);
		}

		// -- bb_gameroles: 3 default roles --
		if ($this->db_tools->sql_table_exists($role_table))
		{
			$this->db->sql_multi_insert($role_table, [
				['game_id' => 'custom', 'role_id' => 0, 'role_color' => '#FF4455', 'role_icon' => 'dps_icon',    'role_cat_icon' => ''],
				['game_id' => 'custom', 'role_id' => 1, 'role_color' => '#11FF77', 'role_icon' => 'healer_icon', 'role_cat_icon' => ''],
				['game_id' => 'custom', 'role_id' => 2, 'role_color' => '#c3834c', 'role_icon' => 'tank_icon',   'role_cat_icon' => ''],
			]);
		}

		// -- bb_language: English names for classes, races, roles --
		if ($this->db_tools->sql_table_exists($lang_table))
		{
			$this->db->sql_multi_insert($lang_table, [
				// Classes
				['game_id' => 'custom', 'attribute_id' => 0, 'language' => 'en', 'attribute' => 'class', 'name' => 'Unknown', 'name_short' => '??'],
				['game_id' => 'custom', 'attribute_id' => 1, 'language' => 'en', 'attribute' => 'class', 'name' => 'Warrior', 'name_short' => 'War'],
				['game_id' => 'custom', 'attribute_id' => 2, 'language' => 'en', 'attribute' => 'class', 'name' => 'Mage',    'name_short' => 'Mag'],
				['game_id' => 'custom', 'attribute_id' => 3, 'language' => 'en', 'attribute' => 'class', 'name' => 'Priest',  'name_short' => 'Pri'],
				// Races
				['game_id' => 'custom', 'attribute_id' => 0, 'language' => 'en', 'attribute' => 'race', 'name' => 'Unknown', 'name_short' => '??'],
				['game_id' => 'custom', 'attribute_id' => 1, 'language' => 'en', 'attribute' => 'race', 'name' => 'Human',   'name_short' => 'Hum'],
				['game_id' => 'custom', 'attribute_id' => 2, 'language' => 'en', 'attribute' => 'race', 'name' => 'Elf',     'name_short' => 'Elf'],
				['game_id' => 'custom', 'attribute_id' => 3, 'language' => 'en', 'attribute' => 'race', 'name' => 'Orc',     'name_short' => 'Orc'],
				// Roles
				['game_id' => 'custom', 'attribute_id' => 0, 'language' => 'en', 'attribute' => 'role', 'name' => 'Damage',  'name_short' => 'DPS'],
				['game_id' => 'custom', 'attribute_id' => 1, 'language' => 'en', 'attribute' => 'role', 'name' => 'Healer',  'name_short' => 'HPS'],
				['game_id' => 'custom', 'attribute_id' => 2, 'language' => 'en', 'attribute' => 'role', 'name' => 'Defense', 'name_short' => 'DEF'],
			]);
		}

		// -- bb_guild: Guildless placeholder + test guild --
		if ($this->db_tools->sql_table_exists($guild_table))
		{
			$this->db->sql_multi_insert($guild_table, [
				[
					'id'             => 0,
					'name'           => 'Guildless',
					'realm'          => 'default',
					'region'         => '',
					'roster'         => 0,
					'players'        => 0,
					'emblemurl'      => '',
					'game_id'        => '',
					'min_armory'     => 0,
					'rec_status'     => 0,
					'guilddefault'   => 0,
					'armory_enabled' => 0,
					'armoryresult'   => '',
					'recruitforum'   => 0,
					'faction'        => 0,
				],
				[
					'id'             => 1,
					'name'           => 'Test Guild',
					'realm'          => 'Test Realm',
					'region'         => 'us',
					'roster'         => 1,
					'players'        => 3,
					'emblemurl'      => '',
					'game_id'        => 'custom',
					'min_armory'     => 1,
					'rec_status'     => 1,
					'guilddefault'   => 1,
					'armory_enabled' => 0,
					'armoryresult'   => '',
					'recruitforum'   => 0,
					'faction'        => 1,
				],
			]);
		}

		// -- bb_ranks: Out rank + test guild ranks --
		if ($this->db_tools->sql_table_exists($rank_table))
		{
			$this->db->sql_multi_insert($rank_table, [
				['guild_id' => 0, 'rank_id' => 99, 'rank_name' => 'Out',          'rank_hide' => 1, 'rank_prefix' => '', 'rank_suffix' => ''],
				['guild_id' => 1, 'rank_id' => 0,  'rank_name' => 'Guild Master', 'rank_hide' => 0, 'rank_prefix' => '', 'rank_suffix' => ''],
				['guild_id' => 1, 'rank_id' => 1,  'rank_name' => 'Officer',      'rank_hide' => 0, 'rank_prefix' => '', 'rank_suffix' => ''],
				['guild_id' => 1, 'rank_id' => 2,  'rank_name' => 'Member',       'rank_hide' => 0, 'rank_prefix' => '', 'rank_suffix' => ''],
			]);
		}

		// -- bb_players: 3 test players --
		if ($this->db_tools->sql_table_exists($player_table))
		{
			$this->db->sql_multi_insert($player_table, [
				[
					'game_id'             => 'custom',
					'player_name'         => 'Testwarrior',
					'player_region'       => 'us',
					'player_realm'        => 'Test Realm',
					'player_title'        => '',
					'player_level'        => 60,
					'player_race_id'      => 1,
					'player_class_id'     => 1,
					'player_rank_id'      => 0,
					'player_role'         => 'DPS',
					'player_comment'      => '',
					'player_joindate'     => $now,
					'player_outdate'      => 0,
					'player_guild_id'     => 1,
					'player_gender_id'    => 0,
					'player_achiev'       => 0,
					'player_armory_url'   => '',
					'player_portrait_url' => '',
					'phpbb_user_id'       => 0,
					'player_status'       => 1,
					'deactivate_reason'   => '',
					'last_update'         => $now,
				],
				[
					'game_id'             => 'custom',
					'player_name'         => 'Testmage',
					'player_region'       => 'us',
					'player_realm'        => 'Test Realm',
					'player_title'        => '',
					'player_level'        => 55,
					'player_race_id'      => 2,
					'player_class_id'     => 2,
					'player_rank_id'      => 1,
					'player_role'         => 'DPS',
					'player_comment'      => '',
					'player_joindate'     => $now,
					'player_outdate'      => 0,
					'player_guild_id'     => 1,
					'player_gender_id'    => 1,
					'player_achiev'       => 0,
					'player_armory_url'   => '',
					'player_portrait_url' => '',
					'phpbb_user_id'       => 0,
					'player_status'       => 1,
					'deactivate_reason'   => '',
					'last_update'         => $now,
				],
				[
					'game_id'             => 'custom',
					'player_name'         => 'Testhealer',
					'player_region'       => 'us',
					'player_realm'        => 'Test Realm',
					'player_title'        => '',
					'player_level'        => 45,
					'player_race_id'      => 3,
					'player_class_id'     => 3,
					'player_rank_id'      => 2,
					'player_role'         => 'Healer',
					'player_comment'      => '',
					'player_joindate'     => $now,
					'player_outdate'      => 0,
					'player_guild_id'     => 1,
					'player_gender_id'    => 0,
					'player_achiev'       => 0,
					'player_armory_url'   => '',
					'player_portrait_url' => '',
					'phpbb_user_id'       => 0,
					'player_status'       => 1,
					'deactivate_reason'   => '',
					'last_update'         => $now,
				],
			]);
		}

		// -- bb_motd: Welcome message --
		if ($this->db_tools->sql_table_exists($motd_table))
		{
			$this->db->sql_multi_insert($motd_table, [
				[
					'guild_id'        => 1,
					'motd_title'      => $user->lang['MOTDGREETING'],
					'motd_timestamp'  => $now,
					'motd_msg'        => $welcome_message['text'],
					'bbcode_uid'      => $welcome_message['uid'],
					'bbcode_bitfield' => $welcome_message['bitfield'],
					'user_id'         => $user->data['user_id'],
				],
			]);
		}

		// -- bb_news: Sample news entry --
		if ($this->db_tools->sql_table_exists($news_table))
		{
			$news_message = $this->encode_message('Welcome to bbGuild! This is a sample news entry.');
			$this->db->sql_multi_insert($news_table, [
				[
					'guild_id'        => 1,
					'news_headline'   => 'bbGuild Installed',
					'news_message'    => $news_message['text'],
					'news_date'       => $now,
					'user_id'         => $user->data['user_id'],
					'bbcode_bitfield' => $news_message['bitfield'],
					'bbcode_uid'      => $news_message['uid'],
					'bbcode_options'  => '',
				],
			]);
		}

		// -- bb_recruit: Sample recruitment entry --
		if ($this->db_tools->sql_table_exists($recruit_table))
		{
			$this->db->sql_multi_insert($recruit_table, [
				[
					'guild_id'         => 1,
					'role_id'          => 1,
					'class_id'         => 3,
					'level'            => 40,
					'positions'        => 2,
					'applicants'       => 0,
					'status'           => 1,
					'applytemplate_id' => 0,
					'last_update'      => $now,
					'note'             => 'Looking for healers!',
				],
			]);
		}

		// -- bb_logs: Installation log entry --
		if ($this->db_tools->sql_table_exists($logs_table))
		{
			$this->db->sql_multi_insert($logs_table, [
				[
					'log_date'      => $now,
					'log_type'      => 'install',
					'log_action'    => 'bbGuild installed with sample data',
					'log_ipaddress' => '127.0.0.1',
					'log_sid'       => '',
					'log_result'    => 'success',
					'log_userid'    => $user->data['user_id'],
				],
			]);
		}
	}

	public function remove_sample_data()
	{
		$tables = [
			'bb_logs'      => "log_type = 'install'",
			'bb_recruit'   => 'guild_id = 1',
			'bb_news'      => "news_headline = 'bbGuild Installed'",
			'bb_motd'      => null,
			'bb_players'   => 'player_guild_id IN (0, 1)',
			'bb_ranks'     => 'guild_id IN (0, 1)',
			'bb_guild'     => 'id IN (0, 1)',
			'bb_language'  => "game_id = 'custom'",
			'bb_gameroles' => "game_id = 'custom'",
			'bb_races'     => "game_id = 'custom'",
			'bb_classes'   => "game_id = 'custom'",
			'bb_factions'  => "game_id = 'custom'",
			'bb_games'     => "game_id = 'custom'",
		];

		foreach ($tables as $table => $where)
		{
			$full_table = $this->table_prefix . $table;
			if ($this->db_tools->sql_table_exists($full_table))
			{
				$sql = 'DELETE FROM ' . $full_table;
				if ($where !== null)
				{
					$sql .= ' WHERE ' . $where;
				}
				$this->db->sql_query($sql);
			}
		}
	}

	private function encode_message($text)
	{
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

		return [
			'text'     => $text,
			'uid'      => $uid,
			'bitfield' => $bitfield,
		];
	}
}
