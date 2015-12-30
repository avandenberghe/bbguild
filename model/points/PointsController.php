<?php
/**
 * Pointscontroller class file
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\model\points;

/*
if (!class_exists('\bbdkp\controller\points\Points'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/points/Points.$phpEx");
}
if (!class_exists('\bbdkp\controller\points\Pool'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/points/Pool.$phpEx");
}
// Include the member class
if (!class_exists('\bbdkp\controller\members\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/members/Members.$phpEx");
}
// Include the Adjustments class
if (!class_exists('\bbdkp\controller\adjustments\Adjust'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/adjustments/Adjust.$phpEx");
}
*/

/**
 * Points controller
 *
 *   @package bbdkp
 */
class PointsController extends \sajaki\bbdkp\model\admin\Admin
{

	/**
	 * instance of points object
	 * @var unknown_type
	 */
	private $Points;

    /**
	 * instance of pools object
     * @var Pool
     */
	private $Pools;

	/**
	 * object
	 * @var object
	 */
	public $dkpsys;

	/**
	 * point pool
	 * @var int
	 */
	public $dkpsys_id;


    /**
     * limit to one dkp pool?
     * @var
     */
    public $query_by_pool;

	/**
	 * guild id
	 * @var int
	 */
	public $guild_id;

    /**
     * show inactive members
     * @var bool
     */
    public $show_inactive;

    /**
     * query by name string ?
     * @var
     */
    public $query_by_name;

    /**
     * holds the active name filter
     * @var
     */
    public $member_filter;

    /**
     * is the armor string
     * @var string
     */
    public $armor_filter;

    /**
     * query by armor string ?
     * @var
     */
    public $query_by_armor;


    /**
     * is the class id
     * @var int
     */
    public $class_id;

    /**
     * query by class
     * @var bool
     */
    public $query_by_class;


    /**
     * name of rank
     * @var string
     */
    public $rankfilter;

    /**
     * query by name of rank
     * @var
     */
    public $query_by_rank;


    /**
     * pointscontroller constructor
     * @param int $dkpsys_id
     */
    function __construct($dkpsys_id = 0)
	{
		//load model
		parent::__construct();
		$this->dkpsys_id = $dkpsys_id;
		$this->Points = new \sajaki\bbdkp\model\points\Points(0, $dkpsys_id);
		$this->Pools = new \sajaki\bbdkp\model\points\Pool($dkpsys_id);
		$this->dkpsys = $this->Pools->dkpsys;
        $this->show_inactive= false;
    }

	/**
	 * returns an array to list all dkp accounts
	 *
	 */
	public function listdkpaccounts($start = 0, $pages=false, $leader=false)
	{
		global $db, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

        $sort_order = array (
            0 => array ('member_id', 'member_id desc' ),
            1 => array ('member_name', 'member_name desc' ),
            2 => array ('rank_name', 'rank_name desc' ),
            3 => array ('member_level desc', 'member_level' ),
            4 => array ('member_class', 'member_class desc' ),
            5 => array ('member_raid_value desc', 'member_raid_value' ),
            6 => array ('member_time_bonus desc', 'member_time_bonus' ),
            7 => array ('member_zerosum_bonus desc', 'member_zerosum_bonus' ),
            8 => array ('member_earned', 'member_earned desc' ),
            9 => array ('member_raid_decay desc', 'member_raid_decay' ),
            10 => array ('member_adjustment desc', 'member_adjustment' ),
            12 => array ('member_spent desc', 'member_spent' ),
            13 => array ('member_item_decay desc', 'member_item_decay' ),
            16 => array ('member_current desc', 'member_current' ),
            17 => array ('member_lastraid desc', 'member_lastraid' ),
            18 => array ('member_raidcount desc, member_current desc, member_name asc', 'member_raidcount asc, member_current asc, member_name asc' ),
        );


        $current_order = $this->switch_order ( $sort_order, 'o', '18.0' );
        $sql_array = array (
				'SELECT' => 'm.member_id,  a.member_name, a.member_level, m.member_dkpid,
						m.member_raid_value, m.member_raid_decay,  m.member_time_bonus,
						 m.member_zerosum_bonus, m.member_earned - m.member_raid_decay as member_earned,
						m.member_spent, m.member_item_decay, m.member_spent- m.member_item_decay as net_spent,
						m.member_adjustment,  m.adj_decay,  m.member_raidcount,
						(m.member_earned - m.member_raid_decay - (m.member_spent - m.member_item_decay) + m.member_adjustment - m.adj_decay) AS member_current,
                        m.member_lastraid,
						s.dkpsys_name, l.name AS member_class, r.rank_hide, r.rank_name, r.rank_prefix, r.rank_suffix, c.colorcode , c.imagename,
                        c.class_id, a.member_status, a.game_id',
				'FROM' => array (
						MEMBER_LIST_TABLE 	=> 'a',
						MEMBER_DKP_TABLE 	=> 'm',
						MEMBER_RANKS_TABLE 	=> 'r',
						CLASS_TABLE 		=> 'c',
						BB_LANGUAGE 		=> 'l',
						DKPSYS_TABLE 		=> 's' ),
				'WHERE' => "(a.member_rank_id = r.rank_id)
		    			AND (a.member_guild_id = r.guild_id)
						AND (a.member_id = m.member_id)
						AND (a.member_class_id = c.class_id and a.game_id = c.game_id)
						AND (m.member_dkpid = s.dkpsys_id)
						AND l.attribute_id = c.class_id
						AND l.game_id = c.game_id AND l.language= '" . $config ['bbdkp_lang'] . "' AND l.attribute = 'class'
						AND a.member_guild_id = " . $this->guild_id
        );

        if ($this->query_by_name && $this->member_filter != '')
        {
            $sql_array['WHERE'] .= ' AND lcase(a.member_name) ' . $db->sql_like_expression($db->any_char . $db->sql_escape(mb_strtolower($this->member_filter)) . $db->any_char);
        }

        //check if inactive members will be shown
        if ($config ['bbdkp_hide_inactive'] == '1' && ! $this->show_inactive )
        {
            // don't show inactive members
            $sql_array[ 'WHERE'] .= ' AND a.member_status = 1 ';
        }

        if ($this->query_by_armor && $this->armor_filter != '')
        {
            $sql_array['WHERE'] .= " AND c.class_armor_type =  '" . $db->sql_escape ( $this->armor_filter ) . "'";
        }

        if ($this->query_by_pool)
        {
            $sql_array['WHERE'] .= ' AND m.member_dkpid = ' . (int) $this->dkpsys_id;
        }

        if ($this->query_by_rank && $this->rankfilter != '')
        {
            $sql_array['WHERE'] .= " AND r.rank_name='" . $db->sql_escape($this->rankfilter) . "'";
        }

        if ($this->query_by_class)
        {
            $sql_array['WHERE'] .= " AND c.class_id =  " .  (int) $this->class_id . " ";
        }

        $sql = $db->sql_build_query('SELECT', $sql_array);

        if($leader)
        {
            $sql = ' SELECT * FROM ( ' . $sql . ')  x ORDER BY x.class_id asc, x.member_current desc ';
        }
        else
        {
            $sql = ' SELECT * FROM ( ' . $sql . ') x ORDER BY ' . $current_order ['sql'];
        }

        $count = 0;
        $members_result = $db->sql_query($sql);
        while ( $row = $db->sql_fetchrow ( $members_result ) )
        {
            $count+=1;
        }

        if($pages)
        {
            $members_result = $db->sql_query_limit($sql, $config['bbdkp_user_llimit'], $start);
        }
        else
        {
            $members_result = $db->sql_query($sql);
        }

        $members_row = array();

		while ( $row = $db->sql_fetchrow ( $members_result ) )
		{

            $u_rank_search = append_sid ( "{$phpbb_root_path}dkp.$phpEx" , 'page=standings&amp;rank=' . urlencode ( $row ['rank_name'] ) .'&amp;guild_id=' . $this->guild_id) ;
            $u_rank_search .= (($config ['bbdkp_hide_inactive'] == 1) && ! $this->show_inactive  ) ? '' : '&amp;show=all';

            $members_row [$row ['member_id']] = array (
					'ID' => $row ['member_id'],
                    'STATUS' => $row ['member_status'],
                    'GAME_ID' => $row ['game_id'],
					'DKPID' => $row ['member_dkpid'],
					'DKPSYS_S' => $this->dkpsys_id,
					'DKPSYS_NAME' => $row ['dkpsys_name'],
					'CLASS' => ($row ['member_class'] != 'NULL') ? $row ['member_class'] : '&nbsp;',
                    'CLASS_ID' => $row ['class_id'],
					'COLORCODE' => ($row ['colorcode'] == '') ? '#254689' : $row ['colorcode'],
					'CLASS_IMAGE' => (strlen ( $row ['imagename'] ) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row ['imagename'] . ".png" : '',
					'NAME' => $row ['rank_prefix'] . $row ['member_name'] . $row ['rank_suffix'],
					'S_CLASS_IMAGE_EXISTS' => (strlen ( $row ['imagename'] ) > 1) ? true : false,
                    'RANK_NAME' => $row ['rank_name'],
                    'RANK_HIDE' => $row ['rank_hide'],
                    'RANK_SEARCH' => $u_rank_search,
					'LEVEL' => ($row ['member_level'] > 0) ? $row ['member_level'] : '&nbsp;',
					'RAIDVAL' => number_format ( $row ['member_raid_value'], 2 ),
					'RAIDDECAY' => number_format( - $row ['member_raid_decay'], 2),
					'TIMEBONUS' => number_format ($row ['member_time_bonus'], 2),
					'ZEROSUM' => number_format ($row ['member_zerosum_bonus'], 2),
					'EARNED' => number_format ( $row ['member_earned'], 2 ),
					'ADJUSTMENT' =>  number_format ( ($row ['member_adjustment'] - $row ['adj_decay']) != 0 ? ($row ['member_adjustment'] - $row ['adj_decay']) : 0, 2),
					'SPENT' => number_format (- $row ['member_spent'] != 0 ?  - $row ['member_spent'] : 0, 2),
					'ITEMDECAY' => number_format( $row ['member_item_decay'] != 0 ?  $row ['member_item_decay'] : 0, 2) ,
					'NETSPENT' =>  number_format ( ($row ['member_spent'] - $row ['member_item_decay']) != 0 ? - ($row ['member_spent'] -$row ['member_item_decay']) : 0 , 2),
					'CURRENT' => number_format ($row ['member_current'] , 2),
                    'DKPCOLOUR1' 	=> ($row ['member_adjustment'] >= 0) ? 'positive' : 'negative',
                    'DKPCOLOUR2' 	=> ($row ['member_current'] >= 0) ? 'positive' : 'negative',
                    'RAIDCOUNT' => number_format($row ['member_raidcount'] , 0, '', ''),
					'LASTRAID' => (! empty ( $row ['member_lastraid'] )) ? date ( $config ['bbdkp_date_format'], $row ['member_lastraid'] ) : '&nbsp;',
					'U_VIEW_MEMBER_ACP' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp" ) . '&amp;member_id=' . $row ['member_id'] . '&amp;' . URI_DKPSYS . '=' . $row ['member_dkpid'],
                    'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx",'page=member&amp;' . URI_NAMEID . '=' . $row ['member_id'] .'&amp;' . URI_DKPSYS . '=' .$row ['member_dkpid']) ,


            );
		}

		$db->sql_freeresult ($members_result);
		return array($members_row, $current_order,$count );
	}


	/**
	 * returns an array to list all EPGP accounts
	 */
	public function listEPGPaccounts($start = 0, $pages=false,  $leader=false)
	{
		global $db, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

        $sort_order = array (
            0 => array ('member_id asc', 'member_id desc' ),
            1 => array ('member_name asc', 'member_name desc' ),
            2 => array ('rank_name', 'rank_name desc' ),
            3 => array ('member_level desc', 'member_level' ),
            4 => array ('member_class', 'member_class desc' ),
            5 => array ('member_raid_value desc', 'member_raid_value' ),
            6 => array ('member_time_bonus desc', 'member_time_bonus' ),
            9 => array ('member_raid_decay desc', 'member_raid_decay' ),
            10 => array ('member_adjustment desc', 'member_adjustment' ),
            11 => array ('ep desc', 'ep ' ),
            12 => array ('member_spent desc', 'member_spent' ),
            13 => array ('member_item_decay desc', 'member_item_decay' ),
            14 => array ('gp desc', 'gp ' ),
            15 => array ('pr desc', 'pr ' ),
            17 => array ('member_lastraid desc', 'member_lastraid asc' ),
            18 => array ('member_raidcount desc, pr desc, member_name asc', 'member_raidcount asc, pr asc, member_name asc' ),
        );

        $current_order = $this->switch_order ( $sort_order );

		$sql_array = array (
				'SELECT' => 's.dkpsys_name, l.name AS member_class, r.rank_name, r.rank_prefix, r.rank_suffix, c.colorcode , c.imagename,
						m.member_id,  a.member_name, a.member_level, m.member_dkpid, m.member_lastraid, m.member_raidcount,
						m.member_raid_value, m.member_time_bonus, m.member_raid_decay, m.member_spent, m.member_item_decay,  m.member_adjustment,  m.adj_decay,
						(m.member_raid_value + m.member_time_bonus - m.member_raid_decay + m.member_adjustment - m.adj_decay) AS ep,
						(m.member_spent - m.member_item_decay  + ' . max ( 0, $config ['bbdkp_basegp'] ) . ' ) AS gp,
						CASE when (m.member_spent - m.member_item_decay + ' . max ( 0, $config ['bbdkp_basegp'] ) . ' ) = 0 then 1
						ELSE round( (m.member_raid_value + m.member_time_bonus - m.member_raid_decay + m.member_adjustment - m.adj_decay) /
						(' . max ( 0, $config ['bbdkp_basegp'] ) . ' + m.member_spent - m.member_item_decay),2) end as pr,
						 c.class_id, a.member_status, a.game_id',
				'FROM' => array (
						MEMBER_LIST_TABLE 	=> 'a',
						MEMBER_DKP_TABLE 	=> 'm',
						MEMBER_RANKS_TABLE 	=> 'r',
						CLASS_TABLE 		=> 'c',
						BB_LANGUAGE 		=> 'l',
						DKPSYS_TABLE 		=> 's' ),
				'WHERE' => "(a.member_rank_id = r.rank_id)
		    			AND (a.member_guild_id = r.guild_id)
						AND (a.member_id = m.member_id)
						AND (a.member_class_id = c.class_id and a.game_id = c.game_id)
						AND (m.member_dkpid = s.dkpsys_id)
						AND l.attribute_id = c.class_id
						AND a.member_guild_id = " . $this->guild_id . "
						AND l.game_id = c.game_id AND l.language= '" . $config ['bbdkp_lang'] . "' AND l.attribute = 'class' ",

        );

        if ($this->query_by_name && $this->member_filter != '')
        {
            $sql_array['WHERE'] .= ' AND lcase(a.member_name) ' . $db->sql_like_expression($db->any_char . $db->sql_escape(mb_strtolower($this->member_filter)) . $db->any_char);
        }

        //check if inactive members will be shown
        if ($config ['bbdkp_hide_inactive'] == '1' && ! $this->show_inactive )
        {
            // don't show inactive members
            $sql_array[ 'WHERE'] .= ' AND a.member_status = 1 ';
        }

        if ($this->query_by_armor && $this->armor_filter != '')
        {
            $sql_array['WHERE'] .= " AND c.class_armor_type =  '" . $db->sql_escape ( $this->armor_filter ) . "'";
        }

        if ($this->query_by_pool)
        {
            $sql_array['WHERE'] .= ' AND m.member_dkpid = ' . (int) $this->dkpsys_id;
        }

        if ($this->query_by_rank && $this->rankfilter != '')
        {
            $sql_array['WHERE'] .= " AND r.rank_name='" . $db->sql_escape($this->rankfilter) . "'";
        }

        if ($this->query_by_class)
        {
            $sql_array['WHERE'] .= " AND c.class_id =  " .  (int) $this->class_id . " ";
        }

        $sql = $db->sql_build_query('SELECT', $sql_array);

        if($leader)
        {
            $sql = ' SELECT * FROM ( ' . $sql . ')  x ORDER BY x.class_id asc, x.PR desc ';
        }
        else
        {
            $sql = ' SELECT * FROM ( ' . $sql . ') x ORDER BY ' . $current_order ['sql'];
        }

        $count = 0;
        $members_result = $db->sql_query($sql);
        while ( $row = $db->sql_fetchrow ( $members_result ) )
        {
            $count+=1;
        }

        if($pages)
        {
            $members_result = $db->sql_query_limit($sql, $config['bbdkp_user_llimit'], $start);
        }
        else
        {
            $members_result = $db->sql_query($sql);
        }

        while ( $row = $db->sql_fetchrow ( $members_result ) )
		{
            $u_rank_search = append_sid ( "{$phpbb_root_path}dkp.$phpEx" , 'page=standings&amp;rank=' . urlencode ( $row ['rank_name'] ) .'&amp;guild_id=' . $this->guild_id) ;
            $u_rank_search .= (($config ['bbdkp_hide_inactive'] == 1) && ! $this->show_inactive  ) ? '' : '&amp;show=all';

			$members_row [$row ['member_id']] = array (
					'ID' => $row ['member_id'],
                    'STATUS' => $row ['member_status'],
                    'GAME_ID' => $row ['game_id'],
					'DKPID' => $row ['member_dkpid'],
					'DKPSYS_S' => $this->dkpsys_id,
					'DKPSYS_NAME' => $row ['dkpsys_name'],
					'CLASS' => ($row ['member_class'] != 'NULL') ? $row ['member_class'] : '&nbsp;',
                    'CLASS_ID' => $row ['class_id'],
					'COLORCODE' => ($row ['colorcode'] == '') ? '#254689' : $row ['colorcode'],
					'CLASS_IMAGE' => (strlen ( $row ['imagename'] ) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row ['imagename'] . ".png" : '',
					'NAME' => $row ['rank_prefix'] . $row ['member_name'] . $row ['rank_suffix'],
					'S_CLASS_IMAGE_EXISTS' => (strlen ( $row ['imagename'] ) > 1) ? true : false,
                    'RANK_NAME' => $row ['rank_name'],
                    'RANK_HIDE' => $row ['rank_hide'],
                    'RANK_SEARCH' => $u_rank_search,
                    'LEVEL' => ($row ['member_level'] > 0) ? $row ['member_level'] : '&nbsp;',
					'RAIDVAL' => number_format($row ['member_raid_value'], 2),
					'RAIDDECAY' => number_format ( $row ['member_raid_decay'] != 0 ?  $row ['member_raid_decay'] : 0 ) ,
					'ADJUSTMENT' => number_format (($row ['member_adjustment'] - $row ['adj_decay']) != 0 ? ($row ['member_adjustment'] - $row ['adj_decay']) : 0 ),
					'EP' => number_format ( $row ['ep'] , 0 ),
					'SPENT' => number_format ( $row ['member_spent'] != 0 ? $row ['member_spent'] : 0 ),
					'ITEMDECAY' =>  number_format ( $row ['member_item_decay'] != 0 ?  $row ['member_item_decay'] : 0) ,
					'GP' => number_format ( $row ['gp'] , 2 ),
					'PR' => number_format ($row ['pr'] , 2 ),
                    'DKPCOLOUR1' 	=> ($row ['member_adjustment'] >= 0) ? 'positive' : 'negative',
                    'DKPCOLOUR2' 	=> ($row ['pr'] >= 0) ? 'positive' : 'negative',
					'LASTRAID' => (! empty ( $row ['member_lastraid'] )) ? date ( $config ['bbdkp_date_format'], $row ['member_lastraid'] ) : '&nbsp;',
                    'RAIDCOUNT' => number_format($row ['member_raidcount'] , 0, '', ''),
					'U_VIEW_MEMBER_ACP' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&amp;mode=mm_editmemberdkp" ) . '&amp;member_id=' . $row ['member_id'] . '&amp;' . URI_DKPSYS . '=' . $row ['member_dkpid'],
                    'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx",'page=member&amp;' . URI_NAMEID . '=' . $row ['member_id'] .'&amp;' . URI_DKPSYS . '=' .$row ['member_dkpid']) ,
            );

			if ($config ['bbdkp_timebased'] == 1)
			{
				$members_row [$row ['member_id']] ['TIMEBONUS'] = $row ['member_time_bonus'];
			}

		}

		$db->sql_freeresult ($members_result);
		return array($members_row, $current_order, $count);


	}

	/**
	 * updates a dkp account
 	 * @param int $member_id
	 * @uses called by acp_dkp_mkdkp
	 */
	public function update_dkpaccount($member_id)
	{
		global $phpbb_admin_path, $user, $phpEx;
		$member= new \bbdkp\controller\members\Members($member_id);
		$oldpoints = new \bbdkp\controller\points\Points($member_id, $this->dkpsys_id);
		$oldpoints->dkpid = $this->dkpsys_id;
		$oldpoints->member_id = $member_id;

		$newpoints = new \bbdkp\controller\points\Points($member_id, $this->dkpsys_id);
		$newpoints->dkpid = $this->dkpsys_id;
		$newpoints->member_id = $member_id;

		$newpoints->raid_value = request_var ( 'raid_value', 0.00 );
		$newpoints->time_bonus = request_var ( 'time_value', 0.00 );
		$newpoints->zerosum_bonus = request_var ( 'zerosum', 0.00 );
		$newpoints->earned_decay = request_var ( 'rdecay', 0.00 );
		$newpoints->spent = request_var ( 'spent', 0.00 );
		$newpoints->item_decay = request_var ( 'idecay', 0.00 );

		$newpoints->update_account();

		$log_action = array (
				'header' => 'L_ACTION_MEMBERDKP_UPDATED',
				'L_USER' => $user->data ['user_id'],
				'L_USERCOLOUR' => $user->data ['user_colour'],
				'L_NAME' => $member->member_name,
				'L_EARNED_BEFORE' => $oldpoints->raid_value + $oldpoints->time_bonus + $oldpoints->zerosum_bonus,
				'L_SPENT_BEFORE' =>  $newpoints->spent,
				'L_EARNED_AFTER' => $newpoints->raid_value + $newpoints->time_bonus + $newpoints->zerosum_bonus,
				'L_SPENT_AFTER' => $oldpoints->spent);

		$this->log_insert ( array (
			'log_type' => $log_action ['header'],
			'log_action' => $log_action ));

		$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_MEMBERDKP_SUCCESS'], $member->member_name );

		unset($member);
		unset($oldpoints);
		unset($newpoints);

		$link =  '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&mode=mm_listmemberdkp" ) . '"><h3>' . $user->lang ['RETURN_DKPINDEX'] . '</h3></a>';
		trigger_error ($success_message . ' ' . $link , E_USER_NOTICE );


	}

	/**
	 * deletes a dkp account
	 * @param int $member_id
	 * @uses called by acp_dkp_mkdkp
	 */
	public function delete_dkpaccount($member_id)
	{
		global $user, $phpbb_admin_path, $phpbb_root_path, $phpEx;
		$member= new \sajaki\bbdkp\model\player\Members($member_id);

		//delete player from raiddetail table
		if (!class_exists('\bbdkp\controller\raids\Raiddetail'))
		{
			require("{$phpbb_root_path}includes/bbdkp/controller/raids/Raiddetail.$phpEx");
		}

		$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail();
		$raiddetail->deleteaccount($member_id, $this->dkpsys_id);


		//delete player from adjustments table
		if (!class_exists('\bbdkp\controller\adjustments\Adjust'))
		{
			require("{$phpbb_root_path}includes/bbdkp/controller/adjustments/Adjust.$phpEx");
		}
		$Adjust = new \sajaki\bbdkp\model\points\Adjust($this->dkpsys_id);
        $Adjust->setMemberId($member_id);
        $Adjust->setAdjustmentDkpid($this->dkpsys_id);
		$Adjust->delete_memberadjustments();


		//delete player dkp points
		$oldpoints = new \sajaki\bbdkp\model\points\Points($member_id, $this->dkpsys_id);
		$oldpoints->dkpid = $this->dkpsys_id;
		$oldpoints->member_id = $member_id;
		$oldpoints->delete_account();

		$log_action = array (
				'header' => 'ACTION_MEMBERDKP_DELETED',
				'L_NAME' => $member->member_name,
				'L_EARNED' => $oldpoints->raid_value + $oldpoints->time_bonus + $oldpoints->zerosum_bonus,
				'L_SPENT' => $oldpoints->spent,
				'L_ADJUSTMENT' => $oldpoints->adjustment );

		$this->log_insert ( array (
				'log_type' => $log_action ['header'],
				'log_action' => $log_action ) );

		$success_message = sprintf ( $user->lang ['ADMIN_DELETE_MEMBERDKP_SUCCESS'], $member->member_name, $this->dkpsys_id );

		unset($member);
		unset($raiddetail);
		unset($Adjust);
		unset($oldpoints);

		$link =  '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_mdkp&mode=mm_listmemberdkp" ) . '"><h3>' . $user->lang ['RETURN_DKPINDEX'] . '</h3></a>';
		trigger_error ($success_message . ' ' . $link , E_USER_NOTICE );

	}

    /**
     * add dkp points.
     * this must be called after the raid and raid detail tables are filled.
     *
     * @param int $raid_id
     * @param int|number $member_id
     */
	public function add_points($raid_id, $member_id = 0)
	{
		global $config;

		$new_raid = new \sajaki\bbdkp\model\raids\Raids($raid_id);
		$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail($raid_id);
		if ($member_id > 0)
		{
		    $raiddetail->Get($raid_id, $member_id);
		}
		else
		{
    		$raiddetail->Get($raid_id);
		}


		foreach ((array) $raiddetail->raid_details as $member_id => $raiddetail)
		{
            $this->addpoint($new_raid, $raiddetail, $member_id);
		}

		if ($config ['bbdkp_hide_inactive'] == 1)
		{
			$this->update_player_status ($new_raid->event_dkpid);
		}
	}

    /**
	 * add points to record
     * @param \sajaki\bbdkp\model\raids\Raids $new_raid
     * @param $raiddetail
     * @param $member_id
     */
	private function addpoint(\sajaki\bbdkp\model\raids\Raids $new_raid, $raiddetail, $member_id)
	{

	    $this->Points = new \sajaki\bbdkp\model\points\Points($member_id, $new_raid->event_dkpid);
	    $this->Points->dkpid = $new_raid->event_dkpid;
	    $this->Points->member_id = $member_id;

	    if($this->Points->has_account($member_id, $new_raid->event_dkpid))
	    {
	        $this->Points->read_account();
	        $this->Points->raid_value += $raiddetail['raid_value'];
	        $this->Points->time_bonus += $raiddetail['time_bonus'];
	        $this->Points->zerosum_bonus += $raiddetail['zerosum_bonus'];
	        $this->Points->raidcount += 1;

	        // update firstraid if it's later than this raid's starting time
	        if ( $this->Points->firstraid >  $new_raid->raid_start )
	        {
	            $this->Points->firstraid =  $new_raid->raid_start;
	        }

	        // update their lastraid if it's earlier than this raid's starting time
	        if ( $this->Points->lastraid <  $new_raid->raid_start )
	        {
	            $this->Points->lastraid =  $new_raid->raid_start;
	        }
	        $this->Points->update_account();
	    }
	    else
	    {

	        $this->Points->raid_value = $raiddetail['raid_value'];
	        $this->Points->time_bonus = $raiddetail['time_bonus'];
	        $this->Points->zerosum_bonus = $raiddetail['zerosum_bonus'];
	        $this->Points->earned_decay = 0.0;
	        $this->Points->spent = 0.0;
	        $this->Points->item_decay = 0.0;
	        $this->Points->adjustment = 0.0;
	        $this->Points->adj_decay = 0.0;
	        $this->Points->firstraid = $new_raid->raid_start ;
	        $this->Points->lastraid = $new_raid->raid_start;
	        $this->Points->raidcount = 1;
	        $this->Points->status = 1;
	        $this->Points->open_account();
	        $this->update_raiddate($member_id, $new_raid->event_dkpid);
	    }

	    unset($this->Points);

	}


	/**
	 * remove a raid from existing dkprecord
	 * @param int $raid_id
	 * @param int $member_id
	 */
	public function removeraid_delete_dkprecord($raid_id, $member_id = 0)
	{
		global $config;

		if($member_id == 0)
		{
		    // remove whole raid
			$old_raid = new \sajaki\bbdkp\model\raids\Raids($raid_id);
			$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail($raid_id);
			$raiddetail->Get($raid_id);
			foreach ((array) $raiddetail->raid_details as $member_id => $attendee)
			{
				$this->Points->dkpid = $old_raid->event_dkpid;
				$this->Points->member_id = $member_id;
				$this->Points->read_account();

				$this->Points->raid_value -= $attendee['raid_value'];
				$this->Points->time_bonus -= $attendee['time_bonus'];
				$this->Points->zerosum_bonus -= $attendee['zerosum_bonus'];
				$this->Points->earned_decay -= $raiddetail->raid_decay;
                if ($this->Points->raidcount > 0)
                {
                    $this->Points->raidcount -= 1;
                }
				$this->Points->update_account();
				$this->update_raiddate($member_id, $old_raid->event_dkpid);
			}

		}
		else
		{
		    // remove 1 member
			$old_raid = new \sajaki\bbdkp\model\raids\Raids($raid_id);
			$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail($raid_id);
			$raiddetail->Get($raid_id, $member_id);

			$this->Points->dkpid = $old_raid->event_dkpid;
			$this->Points->member_id = $member_id;
			$this->Points->read_account();

			$this->Points->raid_value -= $raiddetail->raid_value;
			$this->Points->time_bonus -= $raiddetail->time_bonus;
			$this->Points->zerosum_bonus -= $raiddetail->zerosum_bonus;
			$this->Points->earned_decay -= $raiddetail->raid_decay;
            if ($this->Points->raidcount > 0)
            {
                $this->Points->raidcount -= 1;
            }
            $this->Points->update_account();

			$this->update_raiddate($member_id, $old_raid->event_dkpid);

		}

		if ($config ['bbdkp_hide_inactive'] == 1)
		{
			$this->update_player_status ( $old_raid->event_dkpid);
		}

	}

	/**
	 * add loot to account
	 * @param float $item_value
	 * @param integer $member_id
	 */
	public function addloot_update_dkprecord($item_value, $member_id)
	{

		$this->Points->dkpid = $this->dkpsys_id;
		$this->Points->member_id = $member_id;
		$this->Points->read_account();

		$this->Points->spent += $item_value;
		$this->Points->update_account();
	}

    /**
	 * update a dkp account to remove loot
     * @param \sajaki\bbdkp\model\loot\Loot $loot
     */
	public function removeloot_update_dkprecord(\sajaki\bbdkp\model\loot\Loot $loot)
	{
		$this->Points->dkpid = $this->dkpsys_id;
		$this->Points->member_id = $loot->member_id;
		$this->Points->read_account();

		$this->Points->spent -= $loot->item_value;
		$this->Points->decay -= $loot->item_decay;

		$this->Points->update_account();
	}


	/**
	 *
	 * update_raiddate : update dkp record first and lastraids
	 * @param int $member_id
	 * @param int $dkpid
	 */
	private function update_raiddate($member_id, $dkpid)
	{
		global $db;

		// get first & last raids
		$sql_array = array (
				'SELECT' => 'MIN(r.raid_start) AS member_firstraid, MAX(r.raid_start) AS member_lastraid, ra.member_id ',
				'FROM' => array (
						RAIDS_TABLE => 'r',
						RAID_DETAIL_TABLE => 'ra' ,
						BBEVENTS_TABLE => 'e'
				),
				'WHERE' => ' ra.raid_id = r.raid_id
					AND r.event_id = e.event_id
					AND e.event_dkpid = ' . $dkpid . '
					AND ra.member_id  = ' . $member_id,
				'GROUP_BY' => 'member_id',
		);

		$sql = $db->sql_build_query ( 'SELECT', $sql_array );
		$result = $db->sql_query ( $sql );
		$member_firstraid = 0;
		$member_lastraid = 0;
		while ( $row = $db->sql_fetchrow ($result))
		{
			$member_firstraid = $row['member_firstraid'];
			$member_lastraid = $row['member_lastraid'];
		}
		$db->sql_freeresult ($result);

		$first_raid = ( isset($member_firstraid) ? $member_firstraid : 0 );
		$last_raid = ( isset($member_lastraid) ? $member_lastraid : 0 );

		$query = $db->sql_build_array ( 'UPDATE', array (
				'member_firstraid' 		=> $first_raid,
				'member_lastraid' 		=> $last_raid,
		));

		$db->sql_query ( 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $query . '
	            WHERE member_id = ' . $member_id . '
	            AND  member_dkpid = ' . $dkpid);
	}



	/**
	 * Set active or inactive based on last raid. only for current raids dkp pool
	 * Update active inactive player status column member_status
	 * active = 1 inactive = 0
	 *
	 * @param int $dkpid
	 * @return bool
	 */
	private function update_player_status($dkpid)
	{
		global $db, $user, $config;

		$inactive_time = mktime (0, 0, 0, date ( 'm' ), date ( 'd' ) - $config ['bbdkp_inactive_period'], date ( 'Y' ) );

		$active_members = array ();
		$inactive_members = array ();

		// Don't do active/inactive adjustments if we don't need to.
		if (($config ['bbdkp_active_point_adj'] != 0) || ($config ['bbdkp_inactive_point_adj'] != 0))
		{
			// adapt status and set adjustment points
			$sql_array = array (
					'SELECT' => 'a.member_id, b.member_name, b.member_status, a.member_lastraid',
					'FROM' => array (
							MEMBER_DKP_TABLE => 'a',
							MEMBER_LIST_TABLE => 'b'
					),
					'WHERE' => ' a.member_id = b.member_id AND a.member_dkpid =' . $dkpid
			);
			$adj_value = 0.00;
			$adj_reason = '';
			$sql = $db->sql_build_query ( 'SELECT', $sql_array );
			$result = $db->sql_query ( $sql );
			while ( $row = $db->sql_fetchrow ( $result ) )
			{
				unset ( $adj_value ); // destroy local
				unset ( $adj_reason );

				// Active -> Inactive
				if (((float) $config ['bbdkp_inactive_point_adj'] != 0.00) && ($row['member_status'] == 1) && ($row['member_lastraid'] < $inactive_time))
				{
					$adj_value = $config ['bbdkp_inactive_point_adj'];
					$adj_reason = 'Inactive adjustment';
					$inactive_members [] = $row['member_id'];
					$inactive_membernames [] = $row['member_name'];
				}
				// Inactive -> Active
				elseif (( (float) $config ['bbdkp_active_point_adj'] != 0.00) && ($row['member_status'] == 0) && ($row['member_lastraid'] >= $inactive_time))
				{
					$adj_value = $config ['bbdkp_active_point_adj'];
					$adj_reason = 'Active adjustment';
					$active_members [] = $row['member_id'];
					$active_membernames [] = $row['member_name'];
				}

				//
				// Insert individual adjustment
				if ((isset ( $adj_value )) && (isset ( $adj_reason )))
				{
                    $group_key = $this->gen_group_key ( $this->time, $adj_reason, $adj_value );
                    $query = $db->sql_build_array ( 'INSERT',
                    array (
                        'adjustment_dkpid' 		=> $dkpid,
                        'adjustment_value' 		=> $adj_value,
                        'adjustment_date' 		=> $this->time,
                        'member_id' 			=> $row['member_id'],
                        'adjustment_reason' 	=> $adj_reason,
                        'adjustment_group_key' 	=> $group_key,
                        'adjustment_added_by' 	=> $user->data ['username'] ));

                    $db->sql_query ( 'INSERT INTO ' . ADJUSTMENTS_TABLE . $query );
                }
            }
            $db->sql_freeresult( $result );

            // Update members to inactive and put dkp adjustment
            if (sizeof ( $inactive_members ) > 0)
            {
                $adj_value = (float) $config ['bbdkp_inactive_point_adj'];

                $sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
                    SET member_adjustment = member_adjustment + ' . (string) $adj_value . '
                    WHERE member_dkpid = ' . $dkpid . '  AND ' . $db->sql_in_set ( 'member_id', $inactive_members ) ;
                $db->sql_query($sql);

                $sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
                    SET member_status = 0
                    WHERE ' . $db->sql_in_set ( 'member_id', $inactive_members) ;
                $db->sql_query($sql);

                $log_action = array (
                        'header' 		=> 'L_ACTION_INDIVADJ_ADDED',
                        'L_ADJUSTMENT' 	=> $config ['bbdkp_inactive_point_adj'],
                        'L_MEMBERS' 	=> implode ( ', ', $inactive_membernames ),
                        'L_REASON' 		=> $user->lang['INACTIVE_POINT_ADJ'],
                        'L_ADDED_BY'	=> $user->data ['username'] );

                $this->log_insert ( array (
                        'log_type' 		=> $log_action ['header'],
                        'log_action' 	=> $log_action ));
            }

            // Update active members' adjustment
            if (sizeof ( $active_members ) > 0)
            {
                $adj_value = (float) $config ['bbdkp_active_point_adj'];
                $sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
                SET member_adjustment = member_adjustment + ' . (string) $adj_value . '
                WHERE member_dkpid = ' . $dkpid . '  AND ' . $db->sql_in_set ( 'member_id', $active_members );
                $db->sql_query($sql);

                $sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
                    SET member_status = 1
                    WHERE ' . $db->sql_in_set ( 'member_id', $active_members) ;
                $db->sql_query($sql);

                $log_action = array (
                        'header' 		=> 'L_ACTION_INDIVADJ_ADDED',
                        'L_ADJUSTMENT' 	=> $config ['bbdkp_active_point_adj'],
                        'L_MEMBERS' 	=> implode ( ', ', $active_membernames ),
                        'L_REASON' 		=> $user->lang['ACTIVE_POINT_ADJ'],
                        'L_ADDED_BY' 	=> $user->data ['username'] );

                $this->log_insert ( array ('log_type' => $log_action ['header'], 'log_action' => $log_action ) );
            }
        }

       return true;
	}

	/**
	 * Zero-sum DKP function
	 *
	 * will increase earned points for members present at loot time (== bosskill time) or present in Raid, depending on Settings
	 * ex. player A pays 100dkp for item A
	 * there are 15 players in raid
	 * so each raider gets 100/15 = earned bonus 6.67
	 *
	 * @param int $looter_id
	 * @param int $raid_id
	 * @param float $itemvalue
	 * @return boolean
	 */
	public function zero_balance($looter_id, $raid_id, $itemvalue)
	{
		global $db;
		$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail($raid_id);

		$zerosumdkp = round( $itemvalue / max(1, count($raiddetail->raid_details)) , 2);

		// increase raid detail table
		$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET zerosum_bonus = zerosum_bonus + ' . (float) $zerosumdkp . '
						WHERE raid_id = ' . (int) $raid_id . ' AND ' .  $db->sql_in_set('member_id',  array_keys($raiddetail->raid_details))   ;
		$db->sql_query ( $sql );

		// @note : this zero sum should be decay-synchronised

		// allocate dkp itemvalue bought to all raiders
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
						SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $zerosumdkp  .  ',
						member_earned = member_earned + ' . (float) $zerosumdkp  .  '
						WHERE member_dkpid = ' . (int) $this->dkpsys_id  . '
					  	AND ' . $db->sql_in_set('member_id', array_keys($raiddetail->raid_details) ) ;
		$db->sql_query ( $sql );

		// give rest value to buyer or guildbank
		$restvalue = $itemvalue - ($zerosumdkp * count($raiddetail->raid_details));
		if($restvalue != 0 )
		{

			$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
							SET zerosum_bonus = zerosum_bonus + ' . (float) $restvalue  .  '
							WHERE raid_id = ' . (int) $raid_id . '
						  	AND member_id = ' . $looter_id;
			$db->sql_query ( $sql );

			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
							SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $restvalue  .  ',
							member_earned = member_earned + ' . (float) $restvalue  .  '
							WHERE member_dkpid = ' . (int) $this->dkpsys_id  . '
						  	AND member_id = ' . $looter_id;
			$db->sql_query ( $sql );
		}
		return true;
	}

    /**
     * delete zero sum points for this item
     *
     * @param \sajaki\bbdkp\model\loot\Loot $Loot
     * @return bool
     */
	public function zero_balance_delete(\sajaki\bbdkp\model\loot\Loot $Loot)
	{
		global $db;
		if ($Loot->item_zs == 0)
		{
			return false;
		}

		//get raid detail of this loot
		$raiddetail = new \sajaki\bbdkp\model\raids\Raiddetail($Loot->raid_id);
		$zerosumdkp = round( $Loot->item_value / max(1, count($raiddetail->raid_details)) , 2);

		// decrease values raid detail table
		$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET zerosum_bonus = zerosum_bonus - ' . (float) $zerosumdkp . '
						WHERE raid_id = ' . (int) $Loot->raid_id . ' AND ' .  $db->sql_in_set('member_id',  array_keys($raiddetail->raid_details))   ;
		$db->sql_query ( $sql );

		// @note : this should be decay-synchronised again

		// deallocate dkp zero sum value bought to all raiders
		$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
						SET member_zerosum_bonus = member_zerosum_bonus - ' . (float) $zerosumdkp  .  ',
						member_earned = member_earned - ' . (float) $zerosumdkp  .  '
						WHERE member_dkpid = ' . (int) $Loot->dkpid . '
					  	AND ' . $db->sql_in_set('member_id', array_keys($raiddetail->raid_details) ) ;
		$db->sql_query ( $sql );


		// give rest value to buyer or guildbank
		$restvalue = $Loot->item_value - ($zerosumdkp *  max(1, count($raiddetail->raid_details)) );
		if($restvalue != 0 )
		{
			$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
							SET zerosum_bonus = zerosum_bonus - ' . (float) $restvalue  .  '
							WHERE raid_id = ' . (int) $Loot->raid_id. '
						  	AND member_id = ' . $Loot->member_id ;
			$db->sql_query ( $sql );

			//remove the rest value from dkp table
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
							SET member_zerosum_bonus = member_zerosum_bonus - ' . (float) $restvalue  .  ',
							member_earned = member_earned - ' . (float) $restvalue  .  '
							WHERE member_dkpid = ' . (int) $this->dkpsys_id  . '
						  	AND member_id = ' . $Loot->member_id ;
			$db->sql_query ( $sql );
		}

		return true;
	}


	/**
	 * syncchronise zero sum
	 * @param int $mode 0 or 1 (0= set to 0, 1="resynchronise")
	 * @return boolean|number
	 */
	public function sync_zerosum($mode)
	{
		global $user, $db, $config;

		switch ($mode)
		{
			case 0:
				// set all to 0
				//  update raid detail table to 0
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET zerosum_bonus = 0 ' ;
				$db->sql_query ( $sql );

				// update dkp account
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_zerosum_bonus = 0, member_earned = member_raid_value + member_time_bonus';
				$db->sql_query ( $sql );

				$log_action = array (
						'header' 		=> 'L_ACTION_ZSYNC',
						'L_USER' 		=>  $user->data['user_id'],
						'L_USERCOLOUR' 	=>  $user->data['user_colour'],

				);
				$this->log_insert ( array (
						'log_type' 		=> $log_action ['header'],
						'log_action' 	=> $log_action ) );

				\trigger_error ( sprintf($user->lang ['RESYNC_ZEROSUM_DELETED']) , E_USER_NOTICE );

				return true;
				break;

			case 1:
				// redistribute

				//  update raid detail table to 0
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET zerosum_bonus = 0 ' ;
				$db->sql_query ( $sql );

				// update dkp account
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_zerosum_bonus = 0, member_earned = member_raid_value + member_time_bonus';
				$db->sql_query ( $sql );


				// loop raids having items
				$sql = 'SELECT e.event_dkpid, r.raid_id FROM '.
						RAIDS_TABLE. ' r, ' .
						BBEVENTS_TABLE . ' e, ' .
						RAID_ITEMS_TABLE . ' i
					WHERE e.event_id = r.event_id
					AND r.raid_id = i.raid_id
					GROUP BY e.event_dkpid, r.raid_id' ;
				$result = $db->sql_query ($sql);
				$countraids=0;
				$raids = array();
				while ( ($row = $db->sql_fetchrow ( $result )) )
				{
					$raids[$row['raid_id']]['dkpid']=$row['event_dkpid'];
					$countraids++;
				}
				$db->sql_freeresult ( $result);

				//build an array
				foreach($raids as $raid_id => $raid)
				{
					$numraiders = 0;
					$sql = 'SELECT member_id FROM ' . RAID_DETAIL_TABLE . ' WHERE raid_id = ' . $raid_id;
					$result = $db->sql_query($sql);
					while ( $row = $db->sql_fetchrow ($result))
					{
						if ($row['member_id'] != $config['bbdkp_bankerid'])
						{
							$raids[$raid_id]['raiders'][]= $row['member_id'];
						}
						$numraiders++;
					}
					$raids[$raid_id]['numraiders'] = $numraiders;

					$db->sql_freeresult ( $result);

					$sql = 'SELECT member_id, item_value, item_id FROM ' . RAID_ITEMS_TABLE . ' WHERE raid_id = ' . $raid_id;
					$result = $db->sql_query($sql);
					$numbuyers=0;
					while ( $row = $db->sql_fetchrow ($result))
					{
						$raids[$raid_id]['item'][$row['item_id']]['buyer'] = $row['member_id'];
						$raids[$raid_id]['item'][$row['item_id']]['item_value'] = $row['item_value'];

						$distributed = round($row['item_value'] / max(1, $numraiders), 2);
						$raids[$raid_id]['item'][$row['item_id']]['distributed_value']= $distributed;

						// rest of division
						$restvalue = $row['item_value'] - ($numraiders * $distributed);
						$raids[$raid_id]['item'][$row['item_id']]['rest_value'] = $restvalue;

						$numbuyers++;

					}

					$db->sql_freeresult ( $result);
					$raids[$raid_id]['numbuyers'] = $numbuyers;
				}

				/*
				 * now process the raid array with following structure
				 "$raids[1]"	Array [5]
				dkpid	(string:1) 1
				raiders	Array [4]
					0	(string:1) 2
					1	(string:1) 3
					2	(string:1) 4
					3	(string:1) 5
				numraiders	(int) 4
				item	Array [2]
					1	Array [4]
				buyer	(string:1) 5
				item_value	(string:5) 15.00
				distributed_value	(double) 3.75
				rest_value	(double) 0
					2	Array [4]
				buyer	(string:1) 4
				item_value	(string:5) 15.00
				distributed_value	(double) 3.75
				rest_value	(double) 0
				numbuyers	(int) 2
				*/

				$itemcount = 0;
				$accountupdates=0;
				foreach($raids as $raid_id => $raid)
				{
					$accountupdates += $raid['numraiders'];
					$items = $raid['item'];
					foreach($items as $item_id => $item)
					{
						// distribute this item value as income to all raiders
						$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
						SET 	zerosum_bonus = zerosum_bonus + ' . (float) $item['distributed_value'] . '
								WHERE raid_id = ' . (int) $raid_id . ' AND ' . $db->sql_in_set('member_id', $raid['raiders']);
						$db->sql_query ( $sql );
						$itemcount ++;

						// update their dkp account aswell
						$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
							SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $item['distributed_value']  .  ',
								member_earned = member_earned + ' . (float) $item['distributed_value']  .  '
										WHERE member_dkpid = ' . (int) $raid['dkpid']  . '
							  	AND ' . $db->sql_in_set('member_id', $raid['raiders']   ) ;
					  	$db->sql_query ( $sql );

					  	// give rest value to the buyer in raiddetail or to the guild bank
					  	if($item['rest_value']!=0 )
					  	{
						  	$sql = 'UPDATE ' . RAID_DETAIL_TABLE . '
					  			SET zerosum_bonus = zerosum_bonus + ' . (float) $item['rest_value']  .  '
					  			WHERE raid_id = ' . (int) $raid_id . '
					  			AND member_id = ' . ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $item['buyer'])  ;
		  					$db->sql_query ( $sql );

		  					$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
			  					SET member_zerosum_bonus = member_zerosum_bonus + ' . (float) $item['rest_value']  .  ',
								member_earned = member_earned + ' . (float) $item['rest_value']  .  '
								WHERE member_dkpid = ' . (int) $raid['dkpid']  . '
								AND member_id = ' .  ($config['bbdkp_zerosumdistother'] == 1 ? $config['bbdkp_bankerid'] : $item['buyer']);
						  	$db->sql_query ( $sql );
					  	}
					}
				}

				$log_action = array (
						'header' 		=> 'L_ACTION_ZSYNC',
						'L_USER' 		=>  $user->data['user_id'],
						'L_USERCOLOUR' 	=>  $user->data['user_colour'],
				);
				$this->log_insert ( array (
						'log_type' 		=> $log_action ['header'],
						'log_action' 	=> $log_action ) );

				\trigger_error ( sprintf($user->lang ['RESYNC_ZEROSUM_SUCCESS'], $itemcount, $accountupdates ) , E_USER_NOTICE );
				return $countraids;
				break;
		}

	}


	/**
	 * resynchronises the DKP points table with the adjustments, raids, items.
	 *
	 */
	public function syncdkpsys()
	{
		global $user, $db;

		/* start transaction */

		/* reinintialise the dkp points table */
		$sql = "DELETE from " . MEMBER_DKP_TABLE;
		$db->sql_query ($sql);

		$db->sql_transaction('begin');
		/* select adjustments */
		$sql = "SELECT adjustment_dkpid, member_id, SUM(adjustment_value) AS adjustment_value
			FROM " . ADJUSTMENTS_TABLE . '
			GROUP BY adjustment_dkpid, member_id
			ORDER BY adjustment_dkpid, member_id';
		$result = $db->sql_query ($sql);
		while ($row = $db->sql_fetchrow ( $result))
		{

			$query = $db->sql_build_array('INSERT', array(
					'member_dkpid'     	   => $row['adjustment_dkpid'],
					'member_id'           =>  $row['member_id'],
					'member_earned'       =>  0.00,
					'member_spent'        =>  0.00,
					'member_adjustment'   =>  $row['adjustment_value'],
					'member_firstraid'    =>  0,
					'member_lastraid'     =>  0,
					'member_raidcount'    =>  0 )
			);
			$db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
		}
		$db->sql_freeresult ( $result);

		/* select raids */
		$sql = 'SELECT e.event_dkpid, d.member_id FROM '.
				BBEVENTS_TABLE . ' e
			INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
			INNER JOIN ' . RAID_DETAIL_TABLE . ' d ON r.raid_id = d.raid_id
			GROUP BY e.event_dkpid, d.member_id';

		$dkpcorr = 0;
		$dkpadded = 0;
		$dkpspentcorr = 0;

		$result0 = $db->sql_query ($sql);
		while ($row = $db->sql_fetchrow ( $result0 ))
		{
			$member_id = $row['member_id'];
			$event_dkpid = $row['event_dkpid'];

			/* select raid values */
			$sql = 'SELECT
				MIN(r.raid_start) as first_raid,
				MAX(r.raid_start) as last_raid,
				COUNT(d.raid_id) as raidcount,
				SUM(d.raid_value) as raid_value,
				SUM(d.time_bonus) as time_bonus,
				SUM(d.raid_decay) as raid_decay,
				SUM(d.zerosum_bonus) as zerosum_bonus
				FROM '. BBEVENTS_TABLE . ' e
				INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
				INNER JOIN ' . RAID_DETAIL_TABLE . ' d ON r.raid_id = d.raid_id
				WHERE d.member_id = ' . $member_id . '
				AND	e.event_dkpid = ' . $event_dkpid;
			$result = $db->sql_query ($sql);
			while ( ($rowd = $db->sql_fetchrow ( $result )) )
			{
				$first_raid = $rowd['first_raid'];
				$last_raid = $rowd['last_raid'];
				$raidcount= $rowd['raidcount'];
				$raid_value = $rowd['raid_value'];
				$time_bonus = $rowd['time_bonus'];
				$raid_decay= $rowd['raid_decay'];
				$zerosum_bonus = $rowd['zerosum_bonus'];
			}
			$db->sql_freeresult ( $result);

			$sql =  'SELECT count(*) as count FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . '
			AND	member_dkpid = ' . $event_dkpid;
			$result = $db->sql_query ($sql);
			$count = $db->sql_fetchfield('count', false, $result);
			$db->sql_freeresult ( $result);

			//this will be zero at first loop
			if($count ==1)
			{
				$sql =  'SELECT * FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . '
				AND	member_dkpid = ' . $event_dkpid;
				$result = $db->sql_query ($sql);
				while ( ($rowe = $db->sql_fetchrow ( $result )) )
				{
					$first_raid_accounted = $rowe['member_firstraid'];
					$last_raid_accounted = $rowe['member_lastraid'];
					$raidcount_accounted= $rowe['member_raidcount'];
					$raid_value_accounted = $rowe['member_raid_value'];
					$time_bonus_accounted = $rowe['member_time_bonus'];
					$raid_decay_accounted= $rowe['member_raid_decay'];
					$zerosum_bonus_accounted = $rowe['member_zerosum_bonus'];
					$earned_accounted = $rowe['member_earned'];
				}
				$db->sql_freeresult ( $result);

				if(( $first_raid != $first_raid_accounted) ||
						($last_raid != $last_raid_accounted) ||
						($raidcount != $raidcount_accounted) ||
						($raid_value != $raid_value_accounted) ||
						($time_bonus != $time_bonus_accounted) ||
						($raid_decay != $raid_decay_accounted) ||
						($zerosum_bonus != $zerosum_bonus_accounted))
				{
					$dkpcorr +=1;

					$data = array(
							'member_firstraid'      => $first_raid,
							'member_lastraid'       => $last_raid,
							'member_raidcount'      => $raidcount,
							'member_raid_value'     => $raid_value,
							'member_time_bonus'     => $time_bonus,
							'member_raid_decay'     => $raid_decay,
							'member_zerosum_bonus'	=> $zerosum_bonus,
							'member_earned'     	=> $raid_value+$time_bonus+$zerosum_bonus-$raid_decay,
					);

					$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
					SET ' . $db->sql_build_array('UPDATE', $data) .
						' WHERE member_id = ' . $member_id . '
					AND	member_dkpid = ' . $event_dkpid;
					$db->sql_query ($sql);

				}
			}
			else
			{
				//delete and reinsert
				$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . '
				AND	member_dkpid = ' . $event_dkpid;
				$db->sql_query ($sql);

				$data = array(
						'member_dkpid'      	=> $event_dkpid,
						'member_id'      		=> $member_id,
						'member_firstraid'      => $first_raid,
						'member_lastraid'       => $last_raid,
						'member_raidcount'      => $raidcount,
						'member_raid_value'     => $raid_value,
						'member_time_bonus'     => $time_bonus,
						'member_raid_decay'     => $raid_decay,
						'member_zerosum_bonus'	=> $zerosum_bonus,
						'member_earned'     	=> $raid_value+$time_bonus+$zerosum_bonus-$raid_decay,
				);
				$dkpadded +=1;

				$sql = 'INSERT INTO ' . MEMBER_DKP_TABLE . $db->sql_build_array('INSERT', $data);
				$db->sql_query ($sql);

			}
		}
		$db->sql_freeresult ( $result0);

		/* select loot */
		$sql = 'SELECT e.event_dkpid, i.member_id FROM '.
				BBEVENTS_TABLE . ' e
				INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
				INNER JOIN ' . RAID_ITEMS_TABLE . ' i ON r.raid_id = i.raid_id
				GROUP BY e.event_dkpid, i.member_id' ;

		$result0 = $db->sql_query ($sql);

		while ($row = $db->sql_fetchrow ( $result0 ))
		{
			$member_id = $row['member_id'];
			$event_dkpid = $row['event_dkpid'];
			$item_value = 0;
			$item_decay =0;
			/* select lootvalues */
			$sql = 'SELECT
				SUM(i.item_value) as item_value,
				SUM(i.item_decay) as item_decay
				FROM '. BBEVENTS_TABLE . ' e
				INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
				INNER JOIN ' . RAID_ITEMS_TABLE . ' i ON i.raid_id = r.raid_id
				WHERE i.member_id = ' . $member_id . '
				AND	e.event_dkpid = ' . $event_dkpid;
			$result = $db->sql_query ($sql);
			while ( ($rowd = $db->sql_fetchrow ( $result )) )
			{
				$item_value = $rowd['item_value'];
				$item_decay= $rowd['item_decay'];
			}
			$db->sql_freeresult ( $result);

			$sql =  'SELECT count(*) as count FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . '
			AND	member_dkpid = ' . $event_dkpid;
			$result = $db->sql_query ($sql);
			$count = $db->sql_fetchfield('count', false, $result);
			$db->sql_freeresult ( $result);
			if($count == 1 )
			{
				$sql =  'SELECT * FROM ' . MEMBER_DKP_TABLE . ' WHERE member_id = ' . $member_id . '
				AND	member_dkpid = ' . $event_dkpid;
				$result = $db->sql_query ($sql);
				while ( ($rowe = $db->sql_fetchrow ( $result )) )
				{
					$item_value_accounted = $rowe['member_spent'];
					$item_decay_accounted = $rowe['member_item_decay'];
				}
				$db->sql_freeresult ( $result);
				if(( $item_value  != $item_value_accounted) ||
						($item_decay  != $item_decay_accounted))
				{
					$dkpspentcorr += 1;
					/* account exists */
					$data = array(
							'member_spent'     		=> $item_value,
							'member_item_decay'     => $item_decay,
					);

					$sql = 'UPDATE ' . MEMBER_DKP_TABLE . '
					SET ' . $db->sql_build_array('UPDATE', $data) .
						' WHERE member_id = ' . $member_id . '
					AND	member_dkpid = ' . $event_dkpid;
					$db->sql_query ($sql);


				}
			}
			// case count=0 is not possible
		}
		$db->sql_freeresult ( $result0);

		$db->sql_transaction('commit');

		$log_action = array (
				'header' 	=> 'L_ACTION_DKPSYNC',
				'L_USER' 		=>  $user->data['user_id'],
				'L_USERCOLOUR' 	=>  $user->data['user_colour'],
				'L_LOG_1'		=>  $dkpcorr,
				'L_LOG_2'		=>  $dkpspentcorr,
		);
		$this->log_insert ( array (
				'log_type' 		=> $log_action ['header'],
				'log_action' 	=> $log_action ) );

		return ($dkpcorr  + $dkpspentcorr + $dkpadded);

	}

    /**
     * Recalculates and updates adjustment decay
     * @param $mode 1 for recalculating, 0 for setting decay to zero.
     * @param string $origin
     * @return bool|int
     */
	public function sync_adjdecay ($mode, $origin = '')
	{
		global $user, $db;
		switch ($mode)
		{
			case 0:
				//  Decay = OFF : set all decay to 0
				//  update item detail to new decay value

				$db->sql_transaction('begin');

				$sql = 'UPDATE ' . ADJUSTMENTS_TABLE . ' SET adj_decay = 0 ';
				$db->sql_query($sql);
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET adj_decay = 0 ';
				$db->sql_query($sql);

				$db->sql_transaction('commit');

				if ($origin == 'cron')
				{
					$origin = $user->lang['DECAYCRON'];
				}
				return true;
				break;
			case 1:
				// Decay is ON : synchronise
				// loop all ajustments
				$adj = new \sajaki\bbdkp\model\points\Adjust();
				$sql = 'SELECT adjustment_dkpid, adjustment_id, member_id , adjustment_date, adjustment_value, adj_decay FROM ' . ADJUSTMENTS_TABLE . ' WHERE can_decay = 1';
				$result = $db->sql_query($sql);
				$countadj = 0;
				while (($row = $db->sql_fetchrow($result)))
				{
					$adj->decayadj($row['adjustment_id']);
					$countadj ++;
				}
				$db->sql_freeresult($result);
				return $countadj;
				break;
		}
	}

	/**
	 * calculates decay on epoch timedifference (seconds) and earned
	 * we decay the sum of earned ( = raid value + time bonus + zerosumpoints)
	 *
	 * @param float $value = the value to decay
	 * @param int $timediff   = diff in seconds since raidstart
	 * @param int $mode  1 for raid, 2 for items
	 * @return array (decay, decay time)
	 */
	public function decay($value, $timediff, $mode)
	{
		global $user, $config;
		$i=0;
		switch ($mode)
		{
			case 1:
				// get raid decay rate in pct
				$i = (float) $config['bbdkp_raiddecaypct']/100;
				break;
			case 2:
				// get item decay rate in pct
				$i = (float) $config['bbdkp_itemdecaypct']/100;
				break;
		}

		// get decay frequency
		$freq = $config['bbdkp_decayfrequency'];
		if ($freq==0)
		{
			//frequency can't be 0. throw error
			trigger_error($user->lang['FV_FREQUENCY_NOTZERO'],E_USER_WARNING );
		}

		//pick decay frequency type (0=days, 1=weeks, 2=months) and convert timediff to that
		$t=0;
		switch ($config['bbdkp_decayfreqtype'])
		{
			case 0:
				//days
				$t = (float) $timediff / 86400;
				break;
			case 1:
				//weeks
				$t = (float) $timediff / (86400*7);
				break;
			case 2:
				//months
				$t = (float) $timediff / (86400*30.44);
				break;
		}

		// take the integer part of time and interval division base 10,
		// since we only decay after a set interval
		$n = intval($t/$freq, 10);

		//calculate rounded raid decay, defaults to rounds half up PHP_ROUND_HALF_UP, so 9.495 becomes 9.50
		$decay = round($value * (1 - pow(1-$i, $n)), 2);

		return array($decay, $n) ;

	}

	/**
	 * Recalculates and updates decay
	 * loops all raids - caution this may run a long time
	 *
	 * @param int $mode 1 for recalculating, 0 for setting decay to zero.
	 * @param string $origin
	 * @return boolean|number
	 */
	public function sync_decay($mode, $origin= '')
	{
		global $user, $db;
		switch ($mode)
		{
			case 0:
				//  Decay = OFF : set all decay to 0
				//  update item detail to new decay value
				$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET raid_decay = 0 ' ;
				$db->sql_query ( $sql );

				// update dkp account, deduct old, add new decay
				$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_raid_decay = 0, member_item_decay = 0';
				$db->sql_query ( $sql );

				$sql = 'UPDATE ' . RAID_ITEMS_TABLE . ' SET item_decay = 0';
				$db->sql_query ( $sql);

				if ($origin != 'cron')
				{
					//no logging for cronjobs
					$log_action = array (
							'header' 		=> 'L_ACTION_DECAYOFF',
							'L_USER' 		=>  $user->data['user_id'],
							'L_USERCOLOUR' 	=>  $user->data['user_colour'],
							'L_ORIGIN' 		=>  $origin
					);

					$this->log_insert ( array (
							'log_type' 		=> $log_action ['header'],
							'log_action' 	=> $log_action ) );
				}

				return true;
				break;

			case 1:
				// Decay is ON : synchronise
				// loop all raids
				$sql = 'SELECT e.event_dkpid, r.raid_id FROM '. RAIDS_TABLE. ' r, ' . BBEVENTS_TABLE . ' e WHERE e.event_id = r.event_id ' ;
				$result = $db->sql_query ($sql);
				$countraids=0;
				while ( ($row = $db->sql_fetchrow ( $result )) )
				{
					$this->decayraid($row['raid_id'], $row['event_dkpid']);
					$countraids++;
				}
				$db->sql_freeresult ($result);

				if ($countraids > 0 && $origin != 'cron' )
				{
					//no logging for cronjobs due to users just not getting it.
					$log_action = array (
							'header' 		=> 'L_ACTION_DECAYSYNC',
							'L_USER' 		=>  $user->data['user_id'],
							'L_USERCOLOUR' 	=>  $user->data['user_colour'],
							'L_RAIDS' 		=>  $countraids,
							'L_ORIGIN' 		=>  $origin
					);

					$this->log_insert ( array (
							'log_type' 		=> $log_action ['header'],
							'log_action' 	=> $log_action ) );
				}

				return $countraids;

				break;

		}


	}


    /**
     * function to decay one specific raid
     * calling this function multiple time will not lead to cumulative decays, just the delta is applied.
     *
     * @param int $raid_id the raid id to decay
     * @param int $dkpid dkpid for adapting accounts
     * @return bool
     */
	public function decayraid($raid_id, $dkpid)
	{
		global  $db;
		//loop raid detail, pass earned and timediff to decay function, update raid detail

		//get old raidinfo
		$sql_array = array (
				'SELECT' => ' r.raid_start, ra.member_id, (ra.raid_value + ra.time_bonus + ra.zerosum_bonus) as earned, ra.raid_decay ',
				'FROM' => array (
						RAIDS_TABLE 		=> 'r' ,
						RAID_DETAIL_TABLE	=> 'ra' ,
				),
				'WHERE' => " r.raid_id = ra.raid_id and r.raid_id=" . ( int ) $raid_id,
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		$raidstart = 0;
		$raid = array();
		while ( ($row = $db->sql_fetchrow ( $result )) )
		{
			$raidstart =  $row['raid_start'];
			$raid[$row['member_id']] = array (
					'member_id' 	=> $row['member_id'],
					'earned' 		=> $row['earned'],
					'raid_decay' 	=> $row['raid_decay'],
			);
		}
		$db->sql_freeresult ($result);

		//get timediff
		$now = getdate();
		$timediff = mktime($now['hours'], $now['minutes'], $now['seconds'], $now['mon'], $now['mday'], $now['year']) - $raidstart  ;

		// loop raid detail
		foreach($raid as $member_id => $raiddetail)
		{
			// get new decay : may be different per player due to it being calculated on earned
			$decay = $this->decay($raiddetail['earned'], $timediff, 1);

			// update raid detail to new decay value
			$sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET raid_decay = ' . $decay[0] . ', decay_time = ' . $decay[1] . ' WHERE raid_id = ' . ( int ) $raid_id . '
			AND member_id = ' . $raiddetail['member_id'] ;
			$db->sql_query ( $sql );

			// update dkp account, deduct old, add new decay
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_raid_decay = member_raid_decay - ' . $raiddetail['raid_decay'] . ' + ' . $decay[0] . "
			WHERE member_id = " . ( int ) $member_id . '
			and member_dkpid = ' . $dkpid ;
			$db->sql_query ( $sql );
		}

		//now loop raid items detail
		$sql = 'SELECT i.item_id, i.member_id, i.item_value, i.item_decay FROM ' . RAID_ITEMS_TABLE . ' i where i.raid_id = ' .  $raid_id;
		$result = $db->sql_query ($sql);
		$items= array();
		while ( ($row = $db->sql_fetchrow ( $result )) )
		{
			$items[$row['item_id']] = array (
					'member_id'		=> $row['member_id'],
					'item_value' 	=> $row['item_value'],
					'item_decay' 	=> $row['item_decay'],
			);
		}
		$db->sql_freeresult ($result);


		// loot decay
		foreach($items as $item_id => $item)
		{
			// get new itemdecay
			$itemdecay = $this->decay($item['item_value'], $timediff, 2);

			//  update item detail to new decay value
			$sql = 'UPDATE ' . RAID_ITEMS_TABLE . ' SET item_decay = ' . $itemdecay[0] . ', decay_time = ' . $itemdecay[1] . ' WHERE item_id = ' . $item_id;
			$db->sql_query ( $sql);

			// update dkp account, deduct old, add new decay
			$sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET member_item_decay = member_item_decay - ' . $item['item_decay'] . ' + ' . $itemdecay[0] . "
			WHERE member_id = " . ( int ) $item['member_id'] . ' and member_dkpid = ' . $dkpid ;
			$db->sql_query ( $sql );
		}

		return true;


	}

	/**
	 * Transfer points from member a to b
	 * @param int $member_from
	 * @param int $member_to
	 * @param int $dkpsys_id
	 * @param string $link
	 */
	public function transfer_points( $member_from, $member_to, $dkpsys_id, $link)
	{
		global $user, $db;

		//declare transfer array
		$transfer = array ();
		$member1= new \sajaki\bbdkp\model\player\Members($member_from);
		$member2= new \sajaki\bbdkp\model\player\Members($member_to);

        // get all raids where old member participated
        /* 1) transfer old attendee name to new member */
        $sql_array = array (
            'SELECT' => 'r.raid_id',
            'FROM' => array (
                RAID_DETAIL_TABLE => 'rd',
                RAIDS_TABLE => 'r',
                BBEVENTS_TABLE => 'e' ),
            'WHERE' => 'e.event_dkpid = ' . $dkpsys_id . '
					AND e.event_id = r.event_id
					AND r.raid_id = rd.raid_id
					AND rd.member_id = ' . $member_from, 'ORDER_BY' => 'raid_id' );

        $sql = $db->sql_build_query ( 'SELECT', $sql_array );
        $result = $db->sql_query ( $sql, 0 );
        $raid_ids = array ();
        while ( $row = $db->sql_fetchrow ( $result ) )
        {
            $raid_ids [] = $row ['raid_id'];
        }

        if (count ( $raid_ids ) > 0)
        {
            // 2) delete from these raids all member b if they also participated (otherwise you get unique key violation)
            $sql = 'DELETE FROM ' . RAID_DETAIL_TABLE . ' WHERE member_id =' . $member_to . '
					AND ' . $db->sql_in_set ( 'raid_id', $raid_ids, false, true );
            $db->sql_query ( $sql );

            // 3) now update the memberid to b
            $sql = 'UPDATE ' . RAID_DETAIL_TABLE . ' SET member_id =' . $member_to . ' WHERE member_id=' . $member_from . '
					AND ' . $db->sql_in_set ( 'raid_id', $raid_ids, false, true );
            $db->sql_query ( $sql );

            /* 4) transfer loot to new owner */
            $sql = 'UPDATE ' . RAID_ITEMS_TABLE . ' SET member_id =' . $member_to . ' WHERE member_id=' . $member_from . '
					AND ' . $db->sql_in_set ( 'raid_id', $raid_ids, false, true );
            $db->sql_query ( $sql );
        }


        // 5) update the adjustments table for this dkpid
        $sql = 'UPDATE ' . ADJUSTMENTS_TABLE . ' SET member_id=' . $member_to . ' WHERE member_id= ' . $member_from . '
					AND adjustment_dkpid = ' . $dkpsys_id;
        $db->sql_query ( $sql );

        /* 6) update points for b to 0 */
        $data = array(
            'member_earned'       =>  0.00,
            'member_spent'        =>  0.00,
            'member_adjustment'  =>  0.00,

            'member_firstraid'      => 0,
            'member_lastraid'       => 0,
            'member_raidcount'      => 0,

            'member_raid_value'     => 0,
            'member_time_bonus'     => 0,
            'member_raid_decay'     => 0,
            'member_zerosum_bonus'	=> 0,
        );

        $sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' .$db->sql_build_array('UPDATE', $data) . ' WHERE  member_id  = ' . $member_from . ' AND member_dkpid = ' . $dkpsys_id;
        $db->sql_query ($sql);

        /* 7) delete b account */
        $sql = "DELETE from " . MEMBER_DKP_TABLE . ' WHERE  member_id  = ' . $member_to . ' AND member_dkpid = ' . $dkpsys_id;
        $db->sql_query ($sql);

        /* 8)  ...and re-insert b with adjustments */
        $sql = "SELECT member_id, SUM(adjustment_value) AS adjustment_value
			FROM " . ADJUSTMENTS_TABLE . ' WHERE member_id = ' . $member_to . '
			AND adjustment_dkpid = ' . $dkpsys_id . '
			GROUP BY adjustment_dkpid, member_id
			ORDER BY adjustment_dkpid, member_id';
        $result = $db->sql_query ($sql);
        while ($row = $db->sql_fetchrow ( $result))
        {
            $query = $db->sql_build_array('INSERT', array(
                    'member_id'            =>  $member_to,
                    'member_dkpid'     	   => $dkpsys_id,
                    'member_raid_value'    => 0,
                    'member_time_bonus'    => 0,
                    'member_zerosum_bonus' => 0,
                    'member_earned'        => 0.00,
                    'member_raid_decay'    => 0,
                    'member_spent'         => 0.00,
                    'member_item_decay'    => 0.00,
                    'member_adjustment'    => isset($row['adjustment_value']) ? $row['adjustment_value'] : 0,
                    'member_firstraid'     => 0,
                    'member_lastraid'      => 0,
                    'member_raidcount'     => 0,
                )
            );
            $db->sql_query('INSERT INTO ' . MEMBER_DKP_TABLE . $query);
        }
        $db->sql_freeresult ( $result);

        /* 9) update b with raid points  */
        $sql = 'SELECT
            MIN(r.raid_start) as first_raid,
            MAX(r.raid_start) as last_raid,
            COUNT(d.raid_id) as raidcount,
            SUM(d.raid_value) as raid_value,
            SUM(d.time_bonus) as time_bonus,
            SUM(d.raid_decay) as raid_decay,
            SUM(d.zerosum_bonus) as zerosum_bonus
            FROM '. BBEVENTS_TABLE . ' e
            INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
            INNER JOIN ' . RAID_DETAIL_TABLE . ' d ON r.raid_id = d.raid_id
            WHERE d.member_id = ' . $member_to . '
            AND	e.event_dkpid = ' . $dkpsys_id;

        $result = $db->sql_query ($sql);
        while ( ($rowd = $db->sql_fetchrow ( $result )) )
        {
            $first_raid = $rowd['first_raid'];
            $last_raid = $rowd['last_raid'];
            $raidcount = $rowd['raidcount'];
            $raid_value = $rowd['raid_value'];
            $time_bonus = $rowd['time_bonus'];
            $raid_decay= $rowd['raid_decay'];
            $zerosum_bonus = $rowd['zerosum_bonus'];

            /* 9) insert in points table */
            $data = array(
                'member_firstraid'      => isset($first_raid) ? $first_raid:0,
                'member_lastraid'       => isset($last_raid) ? $last_raid:0,
                'member_raidcount'      => isset($raidcount) ? $raidcount:0,
                'member_raid_value'     => isset($raid_value) ? $raid_value:0,
                'member_time_bonus'     => isset($time_bonus) ? $time_bonus:0,
                'member_raid_decay'     => isset($raid_decay) ? $raid_decay:0,
                'member_zerosum_bonus'	=> isset($zerosum_bonus) ? $zerosum_bonus:0,
                'member_earned'     	=> (isset($raid_value) ? $raid_value:0) +
                                           (isset($time_bonus) ? $time_bonus:0) +
                                           (isset($zerosum_bonus) ? $zerosum_bonus:0) -
                                           (isset($raid_decay) ? $raid_decay:0),
            );

            $sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' .$db->sql_build_array('UPDATE', $data) . ' WHERE  member_id  = ' . $member_to . ' AND member_dkpid = ' . $dkpsys_id;
            $db->sql_query ($sql);

        }
        $db->sql_freeresult ( $result);

        // 10) now process loot
        $item_value = 0;
        $item_decay =0;

        $sql = 'SELECT
            SUM(i.item_value) as item_value,
            SUM(i.item_decay) as item_decay
            FROM '. BBEVENTS_TABLE . ' e
            INNER JOIN ' . RAIDS_TABLE. ' r ON e.event_id = r.event_id
            INNER JOIN ' . RAID_ITEMS_TABLE . ' i ON i.raid_id = r.raid_id
            WHERE i.member_id = ' . $member_to . '
            AND	e.event_dkpid = ' . $dkpsys_id;
        $result = $db->sql_query ($sql);
        while ( ($rowd = $db->sql_fetchrow ( $result )) )
        {
            $item_value = $rowd['item_value'];
            $item_decay = $rowd['item_decay'];

            $data = array(
                'member_spent'     		=> isset($item_value) ? $item_value: 0,
                'member_item_decay'     => isset($item_decay) ? $item_decay: 0,
            );

            $sql = 'UPDATE ' . MEMBER_DKP_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE member_id = ' . $member_to . ' AND	member_dkpid = ' . $dkpsys_id;
            $db->sql_query ($sql);


        }
        $db->sql_freeresult ( $result);

  		//log the action
		$log_action = array (
				'header' => 'L_ACTION_HISTORY_TRANSFER',
				'L_FROM' => $member1->member_name,
				'L_TO' => $member2->member_name );

		$this->log_insert ( array (
				'log_type' => $log_action ['header'],
				'log_action' => $log_action ) );

		$success_message = sprintf ( $user->lang ['ADMIN_TRANSFER_HISTORY_SUCCESS'],
				$member1->member_name, $member2->member_name, $member1->member_name, $dkpsys_id );
		trigger_error ( $success_message . $link );
	}

}
