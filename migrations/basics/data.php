<?php
/**
 * bbGuild - Initial seed data migration
 *
 * @package   avathar\bbguild
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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

		$guild_table = $this->table_prefix . 'bb_guild';
		$rank_table  = $this->table_prefix . 'bb_ranks';
		$motd_table  = $this->table_prefix . 'bb_motd';

		// Guildless placeholder guild — game_id is empty (game plugins set their own)
		$guildless = [
			[
				'id'                => 0,
				'name'              => 'Guildless',
				'realm'             => 'default',
				'region'            => '',
				'roster'            => 0,
				'players'           => 0,
				'emblemurl'         => '',
				'game_id'           => '',
				'min_armory'        => 0,
				'rec_status'        => 0,
				'guilddefault'      => 0,
				'armory_enabled'    => 0,
				'armoryresult'      => '',
				'recruitforum'      => 0,
			'faction'           => 0,
			],
		];

		if ($this->db_tools->sql_table_exists($guild_table))
		{
			$this->db->sql_multi_insert($guild_table, $guildless);
		}

		// Out rank for guildless players
		$outrank = [
			[
				'guild_id'    => 0,
				'rank_id'     => 99,
				'rank_name'   => 'Out',
				'rank_hide'   => 1,
				'rank_prefix' => '',
				'rank_suffix' => '',
			],
		];

		if ($this->db_tools->sql_table_exists($rank_table))
		{
			$this->db->sql_multi_insert($rank_table, $outrank);
		}

		// Welcome MOTD message
		$welcome = [
			[
				'motd_title'      => $user->lang['MOTDGREETING'],
				'motd_timestamp'  => (int) time(),
				'motd_msg'        => $welcome_message['text'],
				'bbcode_uid'      => $welcome_message['uid'],
				'bbcode_bitfield' => $welcome_message['bitfield'],
				'user_id'         => $user->data['user_id'],
			],
		];

		if ($this->db_tools->sql_table_exists($motd_table))
		{
			$this->db->sql_multi_insert($motd_table, $welcome);
		}
	}

	public function remove_sample_data()
	{
		$guild_table = $this->table_prefix . 'bb_guild';
		$rank_table  = $this->table_prefix . 'bb_ranks';
		$motd_table  = $this->table_prefix . 'bb_motd';

		if ($this->db_tools->sql_table_exists($guild_table))
		{
			$this->db->sql_query('DELETE FROM ' . $guild_table . ' WHERE id = 0');
		}

		if ($this->db_tools->sql_table_exists($rank_table))
		{
			$this->db->sql_query('DELETE FROM ' . $rank_table . ' WHERE guild_id = 0 AND rank_id = 99');
		}

		if ($this->db_tools->sql_table_exists($motd_table))
		{
			$this->db->sql_query('DELETE FROM ' . $motd_table . ' WHERE motd_id = 1');
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
