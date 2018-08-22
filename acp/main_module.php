<?php
/**
 *
 * bbGuild Mainpage ACP
 *
 * @package   bbguild v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild\acp;

use avathar\bbguild\model\admin\constants;

class main_module
{
    public $page_title;
    public $tpl_name;
    public $u_action;

    public function main($id, $mode)
    {
        global $phpbb_container;

        /** @var \phpbb\language\language $lang */
        $lang = $phpbb_container->get('language');

        /** @var \phpbb\request\request $request */
        $request = $phpbb_container->get('request');

        // Get an instance of the admin controller
        $admin_main = $phpbb_container->get('avathar.bbguild.admin.main');

        // Requests
        $action = $request->variable('action', '');
        $admin_main->handle();

        // Make the $u_action url available in the admin controller
        $admin_main->set_page_url($this->u_action);
        $this->tpl_name = 'acp_' . $mode;

        // Load the "settings" or "manage" module modes
        switch ($mode)
        {
            case 'panel':
                // Set the page title for our ACP page
                $this->page_title = $lang->lang('ACP_BBGUILD_MAINPAGE');

                // Load the display options handle in the admin controller
                $admin_main->DisplayPanel();
                break;

            case 'config':
                // Set the page title for our ACP page
                $this->page_title = $lang->lang('ACP_BBGUILD_CONFIG');
                // Perform any actions submitted by the user
                switch ($action)
                {
                    case 'updateconfig':
                        // Set the page title for our ACP page
                        $this->page_title = $lang->lang('ACP_KNOWLEDGEBASE_CREATE_CATEGORY');

                        // Load the add_category handle in the admin controller
                        $admin_main->add_category();

                        // Return to stop execution of this script
                        return;
                        break;
                }
                $admin_main->display_config();
                break;

            case 'logs':
                $log_id = $this->request->variable(constants::URI_LOG, 0);
                $action = 'list';
                if ($log_id)
                {
                    $action = 'view';
                }

                switch ($action)
                {
                    case 'list':
                        $admin_main->listlogs();
                        break;
                    case 'view':
                        $admin_main->viewlog();
                        break;
                }



        }
    }
}
