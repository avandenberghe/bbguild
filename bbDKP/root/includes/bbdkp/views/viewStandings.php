<?php
/**
 * standings module
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
namespace bbdkp\views;
/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

if (!class_exists('\bbdkp\controller\points\PointsController'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/points/PointsController.$phpEx");
}

class viewStandings implements iViews
{

    /**
     * instance of PointsController class
     * @var \bbdkp\controller\points\PointsController
     */
    private $PointsController;
    private $memberlist;
    private $start;
    private $u_listmemberdkp;

    function __construct(viewNavigation $Navigation)
    {
        global $phpbb_root_path, $phpEx;

        $this->PointsController = new \bbdkp\controller\points\PointsController();
        $this->PointsController->guild_id =  $Navigation->getGuildId();

        $this->PointsController->query_by_pool = $Navigation->getQueryByPool();
        $this->PointsController->dkpsys_id = $Navigation->getDkpsysId();

        $this->PointsController->show_inactive = $Navigation->getShowAll();

        $this->PointsController->query_by_armor = $Navigation->getQueryByArmor();
        $this->PointsController->armor_filter = '';
        if ($this->PointsController->query_by_armor)
        {
            $this->PointsController->armor_filter = $Navigation->getFilter();
        }

        $this->PointsController->query_by_class = $Navigation->getQueryByClass();
        $this->PointsController->class_id = 0;
        if($this->PointsController->query_by_class)
        {
            $this->PointsController->class_id = $Navigation->getClassId();
        }

        $this->PointsController->query_by_rank = false;
        $this->PointsController->rankfilter = '';
        if (request_var('rank', '') != '')
        {
            $this->PointsController->query_by_rank = true;
            $this->PointsController->rankfilter = request_var('rank', '');
        }

        $this->PointsController->member_filter = utf8_normalize_nfc(request_var('member_name', '', true)) ;
        $this->PointsController->query_by_name = false;
        if($this->PointsController->member_filter != '')
        {
            $this->PointsController->query_by_name= true;
        }

        $this->start = request_var('start', 0, false);

        $this->u_listmemberdkp = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=standings' . '&amp;guild_id=' . $Navigation->getGuildId() . '&amp;' . URI_DKPSYS . '=' . $this->PointsController->dkpsys_id );

        $this->buildpage($Navigation);

    }

    public function buildpage(viewNavigation $Navigation)
    {
        global $user;
        $this->leaderboard();
        $this->dkplisting();
        page_header ( $user->lang ['LISTMEMBERS_TITLE'] );
    }

    private function leaderboard()
    {
        global $config;

        if ($config ['bbdkp_epgp'] == '1')
        {
            $this->memberlist = $this->PointsController->listEPGPaccounts(0, false);
        }
        else
        {
            $this->memberlist = $this->PointsController->listdkpaccounts(0, false);
        }

    }

    private function dkplisting()
    {
        global $user, $config, $template;

        if ($config ['bbdkp_epgp'] == '1')
        {
            $this->memberlist = $this->PointsController->listEPGPaccounts($this->start, true);
        }
        else
        {
            $this->memberlist = $this->PointsController->listdkpaccounts($this->start, true);
        }

        $current_order = $this->memberlist[1];
        $lines = $this->memberlist[2]; // all accounts
        $membersids = array();
        if($lines >0)
        {
            foreach ($this->memberlist[0]  as $member_id => $dkp)
            {
                $template->assign_block_vars ('members_row', $dkp);
                $membersids[$member_id] = 1;
            }

            if( $this->PointsController->member_filter != '')
            {
                $pagination = generate_pagination(append_sid ( $this->u_listmemberdkp , "i=dkp_mdkp&mode=mm_listmemberdkp&amp;member_name=" .  $this->PointsController->member_filter . "&amp;o=" .
                    $current_order['uri']['current'] ) , $lines, $config['bbdkp_user_llimit'], $this->start, true, 'start' );
            }
            else
            {
                $pagination = generate_pagination(append_sid ( $this->u_listmemberdkp , "i=dkp_mdkp&mode=mm_listmemberdkp&amp;o=" .
                    $current_order['uri']['current'] ) , $lines, $config['bbdkp_user_llimit'], $this->start, true, 'start' );
            }

        }

        $output = array (
            'IDLIST'	=> implode(",", $membersids),
            'L_TITLE' => $user->lang ['ACP_DKP_LISTMEMBERDKP'],
            'L_EXPLAIN' => $user->lang ['ACP_MM_LISTMEMBERDKP_EXPLAIN'],
            'BUTTON_NAME' => $user->lang['DELETE'],
            'BUTTON_VALUE' => $user->lang ['DELETE_SELECTED_MEMBERS'],
            'O_NAME' => $current_order ['uri'] [1],
            'O_RANK' => $current_order ['uri'] [2],
            'O_LEVEL' => $current_order ['uri'] [3],
            'O_CLASS' => $current_order ['uri'] [4],
            'O_RAIDVALUE' => $current_order ['uri'] [5],
            'O_ADJUSTMENT' => $current_order ['uri'] [10],
            'O_SPENT' => $current_order ['uri'] [12],
            'O_LASTRAID' => $current_order ['uri'] [17],
            'S_SHOWZS' => ($config ['bbdkp_zerosum'] == '1') ? true : false,
            'S_SHOWDECAY' => ($config ['bbdkp_decay'] == '1') ? true : false,
            'S_SHOWEPGP' => ($config ['bbdkp_epgp'] == '1') ? true : false,
            'S_SHOWTIME' => ($config ['bbdkp_timebased'] == '1') ? true : false,
            'U_LIST_MEMBERDKP' => $this->u_listmemberdkp,
            'S_NOTMM' => false,
            'S_DISPLAY_STANDINGS' => true,
            'LISTMEMBERS_FOOTCOUNT' => sprintf ( $user->lang ['LISTMEMBERS_FOOTCOUNT'], $lines ),
            'DKPSYS' => $this->PointsController->dkpsys_id,
            'DKPSYSNAME' => $this->PointsController->dkpsys[$this->PointsController->dkpsys_id]['name'],
            'DKPPAGINATION' => $pagination,
            'MEMBER_NAME' =>  $this->PointsController->member_filter,

        );

        if ($config ['bbdkp_timebased'] == 1)
        {
            $output ['O_TIMEBONUS'] = $current_order ['uri'] [6];

        }

        if ($config ['bbdkp_zerosum'] == 1)
        {
            $output ['O_ZSBONUS'] = $current_order ['uri'] [7];

        }

        if ($config ['bbdkp_decay'] == 1)
        {
            $output ['O_RDECAY'] = $current_order ['uri'] [9];
            $output ['O_IDECAY'] = $current_order ['uri'] [13];
        }

        if ($config ['bbdkp_epgp'] == 1)
        {
            $output ['O_EP'] = $current_order ['uri'] [11];
            $output ['O_GP'] = $current_order ['uri'] [14];
            $output ['O_PR'] = $current_order ['uri'] [15];
        }
        else
        {
            $output ['O_EARNED'] = $current_order ['uri'] [8];
            $output ['O_CURRENT'] = $current_order ['uri'] [16];
        }

        $template->assign_vars ( $output );

    }




}
