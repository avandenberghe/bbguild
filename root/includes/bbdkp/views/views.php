<?php
/**
 * View class
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 *
 */
namespace bbdkp\views;
use \bbdkp\admin;
/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\admin\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin/admin.$phpEx");
}

/**
 * dispatch request to right viewpage
 *   @package bbdkp
 *
 */

class viewFactory
{

    private $valid_views = ['news', 'roster', 'standings', 'loothistory', 'lootdb',
    'listevents', 'stats', 'listraids', 'event',
    'viewitem', 'viewraid', 'member', 'bossprogress', 'planner'];

	/**
	 * load a page asked for by user
	 *
	 * @param string $page
	 */
	public function get_view($page)
	{
		global $phpbb_root_path, $phpEx ;
        global $user, $config, $template;

        if (in_array($page, $this->valid_views))
        {
            include($phpbb_root_path . 'includes/bbdkp/views/iViews.' . $phpEx);

            include($phpbb_root_path . 'includes/bbdkp/views/viewNavigation.'. $phpEx);
            $Navigation = new viewNavigation($page);

            include($phpbb_root_path . 'includes/bbdkp/views/view'. ucfirst($page) . '.' . $phpEx);
            $viewtype = "\\bbdkp\\views\\view". ucfirst($page);
            return new $viewtype($Navigation);

        }
        elseif ($page=='portal')
        {
            /***** load blocks ************/

            // fixed bocks -- always displayed
            include($phpbb_root_path . 'includes/bbdkp/block/newsblock.' . $phpEx);
            if ($config['bbdkp_portal_rtshow'] == 1 )
            {
                include($phpbb_root_path . 'includes/bbdkp/block/recentblock.' . $phpEx);
            }
            /* show loginbox or usermenu */
            if ($user->data['is_registered'])
            {
                include($phpbb_root_path .'includes/bbdkp/block/userblock.' . $phpEx);
            }
            else
            {
                include($phpbb_root_path . 'includes/bbdkp/block/loginblock.' . $phpEx);
            }
            include($phpbb_root_path . 'includes/bbdkp/block/whoisonline.' . $phpEx);

            // variable blocks - these depend on acp
            if ($config['bbdkp_portal_newmembers'] == 1)
            {
                include($phpbb_root_path . 'includes/bbdkp/block/newmembers.' . $phpEx);
            }

            if ($config['bbdkp_portal_welcomemsg'] == 1)
            {
                include($phpbb_root_path . 'includes/bbdkp/block/welcomeblock.' . $phpEx);
            }

            if ($config['bbdkp_portal_menu'] == 1)
            {
                include($phpbb_root_path . 'includes/bbdkp/block/mainmenublock.' . $phpEx);
            }

            if ($config['bbdkp_portal_loot'] == 1 )
            {
                include($phpbb_root_path . 'includes/bbdkp/block/lootblock.' . $phpEx);
            }

            if ($config['bbdkp_portal_recruitment'] == 1)
            {
                include($phpbb_root_path . 'includes/bbdkp/block/recruitmentblock.' . $phpEx);
            }

            $template->assign_var('S_BPSHOW', false);
            if (isset($config['bbdkp_gameworld_version']))
            {
                if ($config['bbdkp_portal_bossprogress'] == 1)
                {
                    include($phpbb_root_path . 'includes/bbdkp/block/bossprogressblock.' . $phpEx);
                    $template->assign_var('S_BPSHOW', true);
                }
            }

            if ($config['bbdkp_portal_links'] == 1)
            {
                include($phpbb_root_path . 'includes/bbdkp/block/linksblock.' . $phpEx);
            }

            if (isset($config['bbdkp_raidplanner']))
            {
                if ($config['rp_show_portal'] == 1)
                {
                    $user->add_lang(array('mods/raidplanner'));
                    if (!class_exists('\bbdkp\raidplanner\rpblocks', false))
                    {
                        //display the blocks
                        include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpblocks.' . $phpEx);
                    }
                    $blocks = new \bbdkp\raidplanner\rpblocks();
                    $blocks->display();
                }
            }

        }
        else
        {
            trigger_error('Wrong viewname : ' . $page);
        }
    }



}

