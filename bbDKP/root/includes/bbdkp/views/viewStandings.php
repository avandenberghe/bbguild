<?php
/**
 * standings module
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0.3
 *
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
if (!class_exists('\bbdkp\controller\raids\Raids'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/raids/Raids.$phpEx");
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
    private $Raids;

    function __construct(viewNavigation $Navigation)
    {
        global $phpbb_root_path, $phpEx;

        $this->Raids = new \bbdkp\controller\raids\Raids();
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

        $this->u_listmemberdkp = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=standings' .
            '&amp;guild_id=' . $Navigation->getGuildId() .
            '&amp;' . URI_DKPSYS . '=' . $this->PointsController->dkpsys_id .
            '&amp;member_name=' . urlencode($this->PointsController->member_filter)
         );

        $this->buildpage($Navigation);

    }

    public function buildpage(viewNavigation $Navigation)
    {
        global $user;
        $this->leaderboard($Navigation);
        $this->dkplisting($Navigation);
        page_header ( $user->lang ['LISTMEMBERS_TITLE'] );
    }

    private function leaderboard(viewNavigation $Navigation)
    {
        global $template, $config, $phpbb_root_path, $phpEx;

        if ($config ['bbdkp_epgp'] == '1')
        {
            $this->memberlist = $this->PointsController->listEPGPaccounts(0, false, true);
        }
        else
        {
            $this->memberlist = $this->PointsController->listdkpaccounts(0, false, true);
        }
        // loop sorted member array and dump to template
        $classes = array();
        if(count($this->memberlist[0]) == 0)
        {
            return false;
        }

        foreach ( $this->memberlist[0] as $member_id => $member )
        {
            $classes [] = $member['CLASS_ID'];
        }

        $classes = array_unique($classes);
        sort($classes);


        foreach ($Navigation->getClassarray() as $k => $class)
        {
            if(in_array( $class['class_id'], $classes))
            {
                $template->assign_block_vars ( 'class',
                    array (
                        'CLASSNAME' 	=> $class ['class_name'],
                        'CLASSIMGPATH'	=> (strlen($class['imagename']) > 1) ? $class['imagename'] . ".png" : '',
                        'COLORCODE' 	=> $class['colorcode']
                    )
                );

                $leaderboard = 0;
                foreach ($this->memberlist[0] as $member)
                {
                    if($member['CLASS_ID'] == $class['class_id'] && $member['GAME_ID'] == $class['game_id'] && $leaderboard <= 5 )
                    {
                        //dkp data per class
                        $dkprowarray= array (
                            'NAME' => ($member ['STATUS'] == '0') ? '<em>' . $member ['NAME'] . '</em>' : $member ['NAME'] ,
                            'DKPCOLOUR' => $member ['DKPCOLOUR1'],
                            'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=member&amp;'.
                                URI_NAMEID . '=' . $member ['ID'] . '&amp;' .
                                URI_DKPSYS . '=' . $Navigation->getDkpsysId() ) );

                        if($config['bbdkp_epgp'] == 1)
                        {
                            $dkprowarray[ 'PR'] = number_format($member ['PR'],2) ;
                        }
                        else
                        {
                            $dkprowarray[ 'CURRENT'] = number_format ($member ['CURRENT'],2) ;
                        }

                        $template->assign_block_vars ( 'class.dkp_row', $dkprowarray );

                        $leaderboard +=1;
                    }
                }

            }
        }


        $template->assign_vars(array(
            'S_SHOWLEAD' 		=> true ,
        ));
    }

    /**
     * @return bool
     */
    private function dkplisting(viewNavigation $Navigation)
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

        $output = array ();


        if(count($this->memberlist[0]) == 0)
        {
            $output = array (
                'S_DISPLAY_STANDINGS' => true,
            );

            $template->assign_vars ( $output );
            return;

        }

        //all time guild raidcount
        $Guild_raidcount = $this->Raids->raidcount($this->PointsController->dkpsys_id, 0, 0, 1, true,  $this->PointsController->guild_id );

        $current_order = $this->memberlist[1];
        $lines = $this->memberlist[2]; // all accounts
        $membersids = array();
        if($lines >0)
        {
            foreach ($this->memberlist[0]  as $member_id => $dkp)
            {
                $dkp['ATTENDANCE'] = $Guild_raidcount > 0 ? number_format($dkp['RAIDCOUNT'] / $Guild_raidcount, 4 ) * 100 : 0 ;
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
            'BUTTON_NAME' => $user->lang['DELETE'],
            'BUTTON_VALUE' => $user->lang ['DELETE_SELECTED_MEMBERS'],
            'O_NAME' => $this->u_listmemberdkp . "&amp;o=" . $current_order ['uri'] [1],
            'O_RANK' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [2],
            'O_LEVEL' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [3],
            'O_CLASS' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [4],
            'O_RAIDVALUE' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [5],
            'O_ADJUSTMENT' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [10],
            'O_SPENT' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [12],
            'O_LASTRAID' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [17],
            'O_RAIDCOUNT' => $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [18],
            'S_SHOWZS' => ($config ['bbdkp_zerosum'] == '1') ? true : false,
            'S_SHOWDECAY' => ($config ['bbdkp_decay'] == '1') ? true : false,
            'S_SHOWEPGP' => ($config ['bbdkp_epgp'] == '1') ? true : false,
            'S_SHOWTIME' => ($config ['bbdkp_timebased'] == '1') ? true : false,
            'U_LIST_MEMBERDKP' => $this->u_listmemberdkp,
            'S_NOTMM' => false,
            'S_DISPLAY_STANDINGS' => true,
            'LISTMEMBERS_FOOTCOUNT' => sprintf ( $user->lang ['LISTMEMBERS_FOOTCOUNT'], $lines ),
            'DKPSYS' => $Navigation->getDkpsysId(),
            'DKPSYSNAME' => $Navigation->getDkpsysName(),
            'DKPPAGINATION' => $pagination,
            'MEMBER_NAME' =>  $this->PointsController->member_filter,


        );

        if ($config ['bbdkp_timebased'] == 1)
        {
            $output ['O_TIMEBONUS'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [6];

        }

        if ($config ['bbdkp_zerosum'] == 1)
        {
            $output ['O_ZSBONUS'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [7];

        }

        if ($config ['bbdkp_decay'] == 1)
        {
            $output ['O_RDECAY'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [9];
            $output ['O_IDECAY'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [13];
        }

        if ($config ['bbdkp_epgp'] == 1)
        {
            $output ['O_EP'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [11];
            $output ['O_GP'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [14];
            $output ['O_PR'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [15];
        }
        else
        {
            $output ['O_EARNED'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [8];
            $output ['O_CURRENT'] = $this->u_listmemberdkp . "&amp;o=". $current_order ['uri'] [16];
        }

        $template->assign_vars ( $output );

    }




}
