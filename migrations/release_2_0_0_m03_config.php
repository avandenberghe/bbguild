<?php
/**
 * bbDKP database installer
 *
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbguild\migrations;
use phpbb\db\migration\migration;

/**
 * Migration stage 2: config data
 */
class release_2_0_0_m03_config extends migration
{

    protected $bbguild_version = '2.0.0-a1-dev';

    /**
     * Assign migration file dependencies for this migration
     *
     * @return array Array of migration files
     * @static
     * @access public
     */
    static public function depends_on()
    {
        return array('\sajaki\bbguild\migrations\release_2_0_0_m02_data');
    }

    /**
     * Add or update data in the database
     *
     * @return array Array of table data
     * @access public
     */
    public function update_data()
    {
        $data_sets = array(
            array('config.add', array('bbguild_version', $this->bbguild_version )),
            array('config.add', array('bbguild_date_format', 'd.m.y')),
            array('config.add', array('bbguild_default_game', 'wow')),
            array('config.add', array('bbguild_default_realm', 0)),
            array('config.add', array('bbguild_default_region', 0)),
            array('config.add', array('bbguild_eqdkp_start', 1447196400)),
            // guildfaction : limit the possible races to be available to users to those available in the guild's chosen faction
            array('config.add', array('bbguild_guild_faction', 1)),
            array('config.add', array('bbguild_hide_inactive', 1)),
            array('config.add', array('bbguild_lang', 'en')),
            array('config.add', array('bbguild_maxchars', 5)),
            array('config.add', array('bbguild_minrosterlvl', 50)),
            array('config.add', array('bbguild_n_news', 5)),
            array('config.add', array('bbguild_news_forumid', 2)),
            array('config.add', array('bbguild_regid', 0)),
            // roster layout: main parameter for steering roster layout
            array('config.add', array('bbguild_roster_layout', 0)),
            // showachiev : show the achievement points
            array('config.add', array('bbguild_show_achiev', 0)),
            array('config.add', array('bbguild_user_llimit', 30)),
            array('config.add', array('bbguild_user_nlimit', 20)),
            // portal settings
            array('config.add', array('bbguild_portal_links', 1)),
            array('config.add', array('bbguild_portal_loot', 1)),
            array('config.add', array('bbguild_portal_maxnewmembers', 5)),
            array('config.add', array('bbguild_portal_menu', 1)),
            array('config.add', array('bbguild_portal_newmembers', 1)),
            array('config.add', array('bbguild_portal_onlineblockposition', 1)),
            array('config.add', array('bbguild_portal_recent', 1)),
            array('config.add', array('bbguild_portal_recruitments', 1)),
            array('config.add', array('bbguild_portal_rtlen', 15)),
            array('config.add', array('bbguild_portal_rtno', 5)),
            array('config.add', array('bbguild_portal_showedits', 1)),
            array('config.add', array('bbguild_portal_welcomemsg', 1)),
            array('config.add', array('bbguild_portal_whoisonline', 1)),

            // Add permission
            array('permission.add', array('a_bbguild', true)),
            array('permission.add', array('f_bbguild', true)),
            array('permission.add', array('u_charclaim', true)),
            array('permission.add', array('u_charadd', true)),
        );

        //admin role can access bbguild acp
        if($this->role_exists('ROLE_ADMIN_FULL'))
        {
            $data_sets[] =  array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_bbguild'));
        }

        if($this->role_exists('ROLE_ADMIN_STANDARD'))
        {
            $data_sets[] =  array('permission.permission_set', array('ROLE_ADMIN_STANDARD', 'a_bbguild'));
        }

        //normal user can ccess pages
        if($this->role_exists('ROLE_USER_STANDARD'))
        {
            $data_sets[] = array('permission.permission_set', array('ROLE_USER_STANDARD', array('f_bbguild', 'u_charclaim', 'u_charadd')));
        }

        if($this->role_exists('ROLE_USER_FULL'))
        {
            $data_sets[] = array('permission.permission_set', array('ROLE_USER_STANDARD', array('f_bbguild', 'u_charclaim', 'u_charadd')));
        }

        return $data_sets;
    }

    /**
     * check if role exists
     *
     * @param $role
     * @return bool
     */
    protected function role_exists($role)
    {
        $sql = 'SELECT COUNT(role_id) AS role_count
	        FROM ' . ACL_ROLES_TABLE . "
	        WHERE role_name = '" . $this->db->sql_escape($role) . "'";
        $result = $this->db->sql_query_limit($sql, 1);
        $role_count = $this->db->sql_fetchfield('role_count');
        $this->db->sql_freeresult($result);
        return $role_count > 0;
    }
}
