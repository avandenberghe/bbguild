<?php
/**
 * bbDKP database installer
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\migrations;

use \phpbb\db\migration\container_aware_migration;

/**
* Migration stage 2: Initial data
*/
class release_2_0_0_m02_data extends container_aware_migration
{

	static public function depends_on()
	{
		return array('\sajaki\bbdkp\migrations\release_2_0_0_m01_schema');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'insert_sample_data'))),
		);
	}

    /**
     * populate with staring data.
     */
    public function insert_sample_data()
    {
        $user = $this->container->get('user');
        $user->add_lang_ext('sajaki/bbdkp', 'dkp_admin');

        $welcome_message = $this->encode_message($user->lang['WELCOME_DEFAULT']);

        $guildless = array(
            array(
            'id'	        => 0,
            'name'          => 'Guildless',
            'realm'         => 'default',
            'region'        => 'us',
            'battlegroup'   => ' ',
            'roster'        =>  0,
            'aion_legion_id'    => 0,
            'aion_server_id'    => 0,
            'level'             => 0,
            'members'           => 0,
            'achievementpoints' =>  0,
            'guildarmoryurl'    => '',
            'emblemurl'         => '',
            'game_id'           => 'wow',
            'min_armory'        => 0,
            'rec_status'        => 0,
            'guilddefault'      => 0,
            'armory_enabled'    => 0,
            'armoryresult'      => '',
            'recruitforum'      => 0,
            )
        );

        $this->db->sql_multi_insert($this->table_prefix . 'bbdkp_memberguild', $guildless);

        $outrank = array(
            array(
                'guild_id'	    => 0,
                'rank_id'	    => 99,
                'rank_name'	    => 'Out',
                'rank_hide'	    => 1,
                'rank_prefix'	=> '',
                'rank_suffix'	=> '',
            ));

        $this->db->sql_multi_insert($this->table_prefix . 'bbdkp_member_ranks', $outrank);

        $welcome = array(
            array(
            'welcome_title' => 'Welcome to our guild',
            'welcome_timestamp' => (int) time(),
            'welcome_msg' => $welcome_message['text'],
            'bbcode_uid' => $welcome_message['uid'],
            'bbcode_bitfield' => $welcome_message['bitfield'],
            'user_id' => $user->data['user_id']
            )
        );

        $this->db->sql_multi_insert($this->table_prefix . 'bbdkp_welcomemsg', $welcome);

    }

    /**
     * encode welcome text
     *
     * @param string $text
     * @return array
     * @package bbdkp
     */
    private function encode_message($text)
    {
        $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
        $allow_bbcode = $allow_urls = $allow_smilies = true;
        generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
        $welcome_message['text']=$text;
        $welcome_message['uid']=$uid;
        $welcome_message['bitfield']=$bitfield;
        return $welcome_message;
    }


}
