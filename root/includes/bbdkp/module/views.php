<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 *
 */
namespace bbdkp;
/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}

/**
 * dispatch request to right viewpage
 * @package bbDKP
 *
 */
class views extends \bbdkp\admin
{
	public function load($page)
	{
		global $user, $template, $config, $phpbb_root_path, $phpEx ;
		global $db, $auth;

		if ($this->bbtips == true)
		{
			if (! class_exists ( 'bbtips' ))
			{
				require ($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx);
			}
			$bbtips = new bbtips ( );
		}

		// load modules
		switch ($page)
		{
			case 'news':
				include($phpbb_root_path . 'includes/bbdkp/module/news.' . $phpEx);
				break;
			case 'standings':
				include($phpbb_root_path . 'includes/bbdkp/module/standings.' . $phpEx);
				break;
			case 'listitems':
				include($phpbb_root_path . 'includes/bbdkp/module/listitems.' . $phpEx);
				break;
			case 'listevents':
				include($phpbb_root_path . 'includes/bbdkp/module/listevents.' . $phpEx);
				break;
			case 'stats':
				include($phpbb_root_path . 'includes/bbdkp/module/stats.' . $phpEx);
				break;
			case 'listraids':
				include($phpbb_root_path . 'includes/bbdkp/module/listraids.' . $phpEx);
				break;
			case 'viewevent':
				include($phpbb_root_path . 'includes/bbdkp/module/viewevent.' . $phpEx);
				break;
			case 'viewitem':
				include($phpbb_root_path . 'includes/bbdkp/module/viewitem.' . $phpEx);
				break;
			case 'viewraid':
				include($phpbb_root_path . 'includes/bbdkp/module/viewraid.' . $phpEx);
				break;
			case 'viewmember':
				include($phpbb_root_path . 'includes/bbdkp/module/viewmember.' . $phpEx);
				break;
			case 'bossprogress':
				include($phpbb_root_path . 'includes/bbdkp/module/bossprogress.' . $phpEx);
				break;
			case 'roster':
				include($phpbb_root_path . 'includes/bbdkp/module/roster.' . $phpEx);
				break;
			case 'planner':
				include($phpbb_root_path . 'includes/bbdkp/raidplanner/planner.' . $phpEx);
				break;
			case 'portal':
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
				if (isset($config['bbdkp_bp_version']))
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
				break;


		}
	}


	/**
	 * this function builds a grid with PR or earned (after decay)
	 *
	 * @param int $dkpsys_id
	 * @param bool $query_by_pool
	 * @param bool $show_all
	 */
	private function leaderboard($memberarray, $classarray)
	{
		// get all classes that have dkp members
		global $db, $template, $config;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$classes = array ();
		foreach ($classarray as $k => $class)
		{
			$template->assign_block_vars ( 'class',
					array (
							'CLASSNAME' 	=> $class ['class_name'],
							'CLASSIMGPATH'	=> (strlen($class['imagename']) > 1) ? $class['imagename'] . ".png" : '',
							'COLORCODE' 	=> $class['colorcode']
					)
			);


			foreach ($memberarray as  $member)
			{
				if($member['class_id'] == $class['class_id'] && $member['game_id'] == $class['game_id'])
				{
					//dkp data per class
					$dkprowarray= array (
							'NAME' => ($member ['member_status'] == '0') ? '<em>' . $member ['member_name'] . '</em>' : $member ['member_name'] ,
							'CURRENT' => $member ['member_current'],
							'DKPCOLOUR' => ($member ['member_current'] >= 0) ? 'positive' : 'negative',
							'U_VIEW_MEMBER' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=viewmember&amp;'.
							URI_NAMEID . '=' . $member ['member_id'] . '&amp;' .
							URI_DKPSYS . '=' . $member['member_dkpid'] ) );

					if($config['bbdkp_epgp'] == 1)
					{
						$dkprowarray[ 'PR'] = $member ['pr'] ;
					}

					$template->assign_block_vars ( 'class.dkp_row', $dkprowarray );
				}

			}

			$template->assign_vars ( array (
					'S_SHOWLEAD' => true,
			));
		}

		if(count($classarray)==0)
		{
			$template->assign_vars ( array (
					'S_SHOWLEAD' => false,
			));
		}

		unset($memberarray);
		unset($classarray);
	}


	/**
	 * prepares armor dropdown
	 *
	 */
	private function armor()
	{
		global $config, $user, $db, $template, $query_by_pool;

		global $query_by_armor, $query_by_class, $filter;

		/***** begin armor-class pulldown ****/
		$classarray = array();
		$filtervalues = array();
		$armor_type = array();
		$classname = array();

		$filtervalues ['all'] = $user->lang['ALL'];
		$filtervalues ['separator1'] = '--------';

		// generic armor list
		$sql = 'SELECT class_armor_type FROM ' . CLASS_TABLE . ' GROUP BY class_armor_type';
		$result = $db->sql_query ( $sql, 604000 );
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$filtervalues [strtoupper($row ['class_armor_type'])] = $user->lang[strtoupper($row ['class_armor_type'])];
			$armor_type [strtoupper($row ['class_armor_type'])] = $user->lang[strtoupper($row ['class_armor_type'])];
		}
		$db->sql_freeresult ( $result );
		$filtervalues ['separator2'] = '--------';

		// get classlist
		$sql_array = array(
			'SELECT'    => 	'  c.game_id, c.class_id, l.name as class_name, c.class_min_level,
		c.class_max_level, c.imagename, c.colorcode ',
			'FROM'      => array(
					CLASS_TABLE 	=> 'c',
					BB_LANGUAGE		=> 'l',
					MEMBER_LIST_TABLE	=> 'i',
					MEMBER_DKP_TABLE	=> 'd',
			),
			'WHERE'		=> " c.class_id > 0 and l.attribute_id = c.class_id and c.game_id = l.game_id
		 AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
		 AND i.member_class_id = c.class_id and i.game_id = c.game_id
		 AND d.member_id = i.member_id ",
			'GROUP_BY'	=> 'c.game_id, c.class_id, l.name, c.class_min_level, c.class_max_level, c.imagename, c.colorcode',
			'ORDER_BY'	=> 'c.game_id, c.class_id ',
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ( $sql, 604000);
		$classarray = array();
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$classarray[] = $row;
			$filtervalues [$row['game_id'] . '_class_' . $row ['class_id']] = $row ['class_name'];
			$classname [$row['game_id'] . '_class_' . $row ['class_id']] = $row ['class_name'];
		}
		$db->sql_freeresult ( $result );

		$query_by_armor = 0;
		$query_by_class = 0;
		$submitfilter = (isset ( $_GET ['filter'] ) or isset ( $_POST ['filter'] )) ? true : false;
		if ($submitfilter)
		{
			$filter = request_var ( 'filter', '' );

			if ($filter == "all")
			{
				// select all
				$query_by_armor = 0;
				$query_by_class = 0;
			}
			elseif (array_key_exists ( $filter, $armor_type ))
			{
				// looking for an armor type
				$filter = preg_replace ( '/ Armor/', '', $filter );
				$query_by_armor = 1;
				$query_by_class = 0;
			}
			elseif (array_key_exists ( $filter, $classname ))
			{
				// looking for a class
				$query_by_class = 1;
				$query_by_armor = 0;
			}
		}
		else
		{
			// select all
			$query_by_armor = 0;
			$query_by_class = 0;
			$filter = 'all';
		}

		// dump filtervalues to dropdown template
		foreach ( $filtervalues as $fid => $fname )
		{
			$template->assign_block_vars ( 'filter_row', array (
					'VALUE' => $fid,
					'SELECTED' => ($fid == $filter && $fname !=  '--------' ) ? ' selected="selected"' : '',
					'DISABLED' => ($fname == '--------' ) ? ' disabled="disabled"' : '',
					'OPTION' => (! empty ( $fname )) ? $fname : $user->lang['ALL'] ) );
		}

		/***** end armor - class pulldown ****/
		return $classarray;
	}



	/**
	 * gets array with members to display
	 *
	 * @param int $dkpsys_id
	 * @param array $installed_games
	 * @param int $startd
	 * @return array $memberarray
	 */
	private function get_standings($dkpsys_id, $installed_games, $startd, $show_all)
	{

		global $config, $user, $db, $template, $query_by_pool, $phpbb_root_path;
		global $query_by_armor, $query_by_class, $filter;

		$sql_array = array(
				'SELECT'    => 	'l.game_id, m.member_dkpid, d.dkpsys_name, m.member_id, m.member_status, m.member_lastraid,
							sum(m.member_raid_value) as member_raid_value,
							sum(m.member_earned) as member_earned,
							sum(m.member_adjustment - m.adj_decay) as member_adjustment,
							sum(m.member_spent) as member_spent,
						sum(m.member_earned + m.member_adjustment - m.member_spent - m.adj_decay ) AS member_current,
							 l.member_name, l.member_level, l.member_race_id ,l.member_class_id, l.member_rank_id ,
								 r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix,
								 l1.name AS member_class, c.class_id,
								 c.colorcode, c.class_armor_type AS armor_type, c.imagename,
								 l.member_gender_id, a.image_female, a.image_male,
						c.class_min_level AS min_level,
						c.class_max_level AS max_level',

				'FROM'      => array(
						MEMBER_DKP_TABLE 	=> 'm',
						DKPSYS_TABLE 		=> 'd',
						MEMBER_LIST_TABLE 	=> 'l',
						MEMBER_RANKS_TABLE  => 'r',
						RACE_TABLE  		=> 'a',
						CLASS_TABLE    		=> 'c',
						BB_LANGUAGE			=> 'l1',
				),

				'WHERE'     =>  "(m.member_id = l.member_id)
					AND l1.attribute_id =  c.class_id AND l1.language= '" . $config['bbdkp_lang'] . "' AND l1.attribute = 'class' and c.game_id = l1.game_id
				AND (c.class_id = l.member_class_id and c.game_id=l.game_id)
				AND (l.member_race_id =  a.race_id and a.game_id=l.game_id)
				AND (r.rank_id = l.member_rank_id)
				AND (m.member_dkpid = d.dkpsys_id)
				AND (l.member_guild_id = r.guild_id)
				AND r.rank_hide = 0 " ,
				'GROUP_BY' => 'l.game_id, m.member_dkpid, d.dkpsys_name, m.member_id, m.member_status, m.member_lastraid,
						 l.member_name, l.member_level, l.member_race_id ,l.member_class_id, l.member_rank_id ,
							 r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix,
							 l1.name, c.class_id,
							 c.colorcode, c.class_armor_type , c.imagename,
							 l.member_gender_id, a.image_female, a.image_male,
					c.class_min_level ,
					c.class_max_level ',
		);


		if($config['bbdkp_timebased'] == 1)
		{
			$sql_array[ 'SELECT'] .= ', sum(m.member_time_bonus) as member_time_bonus ';
		}

		if($config['bbdkp_zerosum'] == 1)
		{
			$sql_array[ 'SELECT'] .= ', sum(m.member_zerosum_bonus) as member_zerosum_bonus';
		}

		if($config['bbdkp_decay'] == 1)
		{
			$sql_array[ 'SELECT'] .= ',
			sum(m.member_raid_decay) as member_raid_decay,
			sum(m.member_item_decay) as member_item_decay ';
		}

		if($config['bbdkp_epgp'] == 1)
		{
			$sql_array[ 'SELECT'] .= ",
			sum(m.member_earned + m.member_adjustment - m.adj_decay) AS ep,
			sum(m.member_spent - m.member_item_decay  + ". floatval($config['bbdkp_basegp']) . " ) AS gp,
		CASE  WHEN SUM(m.member_spent - m.member_item_decay  + " . max(0, $config['bbdkp_basegp']) . " ) = 0
		THEN  1
		ELSE  ROUND(SUM(m.member_earned + m.member_adjustment - m.adj_decay) /
				SUM(" . max(0, $config['bbdkp_basegp']) . " + m.member_spent - m.member_item_decay),2) END AS pr " ;
		}

		//check if inactive members will be shown
		if ($config ['bbdkp_hide_inactive'] == '1' && !$show_all )
		{
			// don't show inactive members
			$sql_array[ 'WHERE'] .= ' AND m.member_status = 1 ';
		}

		if  (isset($_POST['compare']) && isset($_POST['compare_ids']))
		{
			$compare =  request_var('compare_ids', array('' => 0)) ;
			$sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('m.member_id', $compare, false, true);
		}

		if ($query_by_pool)
		{
			$sql_array['WHERE'] .= ' AND m.member_dkpid = ' . $dkpsys_id . ' ';
		}


		if (isset ( $_GET ['rank'] ))
		{
			$sql_array['WHERE'] .= " AND r.rank_name='" . request_var ( 'rank', '' ) . "'";
		}

		if ($query_by_class == 1)
		{
			//wow_class_8 = Mage
			//lotro_class_5=Hunter
			foreach($installed_games as $k=>$gamename)
			{
				//x is for avoiding output zero which may be outcome of false
				if (strpos('x'.$filter,$k) > 0)
				{
					$class_id = substr($filter, strlen($k)+7);
					$sql_array['WHERE'] .= " AND c.class_id =  '" . $db->sql_escape ( $class_id ) . "' ";
					$sql_array['WHERE'] .= " AND c.game_id =  '" . $db->sql_escape ( $k ) . "' ";
					break 1;
				}
			}

		}

		if ($query_by_armor == 1)
		{
			$sql_array['WHERE'] .= " AND c.class_armor_type =  '" . $db->sql_escape ( $filter ) . "'";
		}

		// default sorting
		if($config['bbdkp_epgp'] == 1)
		{
			$sql_array[ 'ORDER_BY'] = "CASE WHEN SUM(m.member_spent - m.member_item_decay  + ". floatval($config['bbdkp_basegp']) . "  ) = 0
		THEN 1
		ELSE ROUND(SUM(m.member_earned + m.member_adjustment - m.adj_decay) /
		SUM(" . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay),2) END DESC ' ;
		}
		else
		{
			$sql_array[ 'ORDER_BY'] = 'sum(m.member_earned + m.member_adjustment - m.member_spent - m.adj_decay) desc, l.member_name asc ' ;
		}


		$sql = $db->sql_build_query('SELECT_DISTINCT', $sql_array);
		if (! ($members_result = $db->sql_query ( $sql )))
		{
			trigger_error ($user->lang['MNOTFOUND']);
		}

		global $allmember_count;
		$allmember_count = 0;
		while ( $row = $db->sql_fetchrow ( $members_result ) )
		{
			++$allmember_count;
		}

		$members_result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_llimit'], $startd );
		$memberarray = array ();
		$member_count =0;
		while ( $row = $db->sql_fetchrow ( $members_result ) )
		{
			$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);

			++$member_count;
			$memberarray [$member_count] ['game_id'] = $row ['game_id'];
			$memberarray [$member_count] ['class_id'] = $row ['class_id'];
			$memberarray [$member_count] ['dkpsys_name'] = $row ['dkpsys_name'];
			$memberarray [$member_count] ['member_id'] = $row ['member_id'];
			$memberarray [$member_count] ['count'] = $member_count;
			$memberarray [$member_count] ['member_name'] = $row ['member_name'];
			$memberarray [$member_count] ['member_status'] = $row ['member_status'];
			$memberarray [$member_count] ['rank_prefix'] = $row ['rank_prefix'];
			$memberarray [$member_count] ['rank_suffix'] = $row ['rank_suffix'];
			$memberarray [$member_count] ['rank_name'] = $row ['rank_name'];
			$memberarray [$member_count] ['rank_hide'] = $row ['rank_hide'];
			$memberarray [$member_count] ['member_level'] = $row ['member_level'];
			$memberarray [$member_count] ['member_class'] = $row ['member_class'];
			$memberarray [$member_count] ['colorcode'] = $row ['colorcode'];
			$memberarray [$member_count] ['class_image'] = (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '';
			$memberarray [$member_count] ['class_image_exists'] = (strlen($row['imagename']) > 1) ? true : false;
			$memberarray [$member_count] ['race_image'] = (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '';
			$memberarray [$member_count] ['race_image_exists'] = (strlen($race_image) > 1) ? true : false;

			$memberarray [$member_count] ['armor_type'] = $row ['armor_type'];
			$memberarray [$member_count] ['member_raid_value'] = $row ['member_raid_value'];
			if($config['bbdkp_timebased'] == 1)
			{
				$memberarray [$member_count] ['member_time_bonus'] = $row ['member_time_bonus'];

			}
			if($config['bbdkp_zerosum'] == 1)
			{
				$memberarray [$member_count] ['member_zerosum_bonus'] = $row ['member_zerosum_bonus'];
			}
			$memberarray [$member_count] ['member_earned'] = $row ['member_earned'];

			$memberarray [$member_count] ['member_adjustment'] = $row ['member_adjustment'];

			if($config['bbdkp_decay'] == 1)
			{
				$memberarray [$member_count] ['member_raid_decay'] = $row ['member_raid_decay'];
				$memberarray [$member_count] ['member_item_decay'] = $row ['member_item_decay'];
			}

			$memberarray [$member_count] ['member_spent'] = $row ['member_spent'];
			$memberarray [$member_count] ['member_current'] = $row ['member_current'];

			if($config['bbdkp_epgp'] == 1)
			{
				$memberarray [$member_count] ['ep'] = $row ['ep'];
				$memberarray [$member_count] ['gp'] = $row ['gp'];
				$memberarray [$member_count] ['pr'] = $row ['pr'];
			}

			$memberarray [$member_count] ['member_lastraid'] = $row ['member_lastraid'];
			$memberarray [$member_count] ['attendanceP1'] = $row ['member_raidcount'];
			//raidcount ( true, $row ['member_dkpid'], $config ['bbdkp_list_p1'], $row ['member_id'],2,false );
			$memberarray [$member_count] ['member_dkpid'] = $row ['member_dkpid'];

		}
		$db->sql_freeresult ( $members_result );

		return $memberarray;
	}



	/**
	 * prepares dkp dropdown,
	 *
	 * @return int $dkpsys_id
	 */
	private function dkppulldown()
	{
		global $user, $db, $template, $query_by_pool;

		$query_by_pool = false;
		$defaultpool = 99;
		$dkpvalues = array();

		$dkpvalues[0] = $user->lang['ALL'];
		$dkpvalues[1] = '--------';
		// find only pools with dkp records
		$sql_array = array(
				'SELECT'    => 'a.dkpsys_id, a.dkpsys_name, a.dkpsys_default',
				'FROM'		=> array(
						DKPSYS_TABLE => 'a',
						MEMBER_DKP_TABLE => 'd',
				),
				'WHERE'  => ' a.dkpsys_id = d.member_dkpid',
				'GROUP_BY'  => 'a.dkpsys_id, a.dkpsys_name, a.dkpsys_default'
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query ($sql);
		$index = 3;
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$dkpvalues[$index]['id'] = $row ['dkpsys_id'];
			$dkpvalues[$index]['text'] = $row ['dkpsys_name'];
			if (strtoupper ( $row ['dkpsys_default'] ) == 'Y')
			{
				$defaultpool = $row ['dkpsys_id'];
			}
			$index +=1;
		}
		$db->sql_freeresult ( $result );

		$dkpsys_id = 0;
		if(isset( $_POST ['pool']) or isset ( $_GET [URI_DKPSYS] ) )
		{
			if (isset( $_POST ['pool']) )
			{
				$pulldownval = request_var('pool',  $user->lang['ALL']);
				if(is_numeric($pulldownval))
				{
					$query_by_pool = true;
					$dkpsys_id = intval($pulldownval);
				}
			}
			elseif (isset ( $_GET [URI_DKPSYS] ))
			{

				$pulldownval = request_var(URI_DKPSYS,  $user->lang['ALL']);
				if(is_numeric($pulldownval))
				{
					$query_by_pool = true;
					$dkpsys_id = request_var(URI_DKPSYS, 0);
				}
				else
				{
					$query_by_pool = false;
					$dkpsys_id = $defaultpool;
				}
			}
		}
		else
		{
			// if no parameters passed to this page then show default pool
			$query_by_pool = true;
			$dkpsys_id = $defaultpool;
		}

		foreach ($dkpvalues as $key => $value)
		{
			if(!is_array($value))
			{
				$template->assign_block_vars ( 'pool_row', array (
						'VALUE' => $value,
						'SELECTED' => (!$query_by_pool && $value != '--------') ? ' selected="selected"' : '',
						'DISABLED' => ($value == '--------' ) ? ' disabled="disabled"' : '',
						'OPTION' => $value,
				));
			}
			else
			{
				$template->assign_block_vars ( 'pool_row', array (
						'VALUE' => $value['id'],
						'SELECTED' => ($dkpsys_id == $value['id']  && $query_by_pool ) ? ' selected="selected"' : '',
						'OPTION' => $value['text'],
				));

			}
		}

		return $dkpsys_id;
	}

	public $selfurl;
	public $mode;
	public $game_id;
	public $start;

	private $dataset;
	private $current_order;
	private $member_count;


	/*
	 * Displays the class grid
	*/
	public function displaygrid()
	{
	    global $phpbb_root_path, $phpEx, $config, $template, $user;
	    //class

	    $this->get_classes();
	    if(count($this->classes) > 0)
	    {
	        foreach($this->classes as $row )
	        {
	            $classes[$row['class_id']]['name'] 		= $row['class_name'];
	            $classes[$row['class_id']]['imagename'] = $row['imagename'];
	            $classes[$row['class_id']]['colorcode'] = $row['colorcode'];
	        }

	        foreach ($classes as  $classid => $class )
	        {
	            $classimgurl =  $phpbb_root_path . "images/roster_classes/" . $this->removeFromEnd($class['imagename'], '') .'.png';
	            $classcolor = $class['colorcode'];

	            $template->assign_block_vars('class', array(
	                    'CLASSNAME'     => $class['name'],
	                    'CLASSIMG'		=> $classimgurl,
	                    'COLORCODE'		=> $classcolor,
	            ));
	            $classmembers=1;
	            foreach ( $this->dataset as $row)
	            {
	                if($row['member_class_id'] == $classid)
	                {
	                    $race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
	                    $template->assign_block_vars('class.members_row', array(
	                            'COLORCODE'		=> $row['colorcode'],
	                            'CLASS'			=> $row['class_name'],
	                            'NAME'			=> $row['member_name'],
	                            'RACE'			=> $row['race_name'],
	                            'RANK'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
	                            'LVL'			=> $row['member_level'],
	                            'ARMORY'		=> $row['member_armory_url'],
	                            'PHPBBUID'		=> get_username_string('full', $row['phpbb_user_id'], $row['username'], $row['user_colour']),
	                            'PORTRAIT'		=> $this->getportrait($row),
	                            'ACHIEVPTS'		=> $row['member_achiev'],
	                            'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',
	                            'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
	                            'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '',
	                            'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false,
	                    ));
	                    $classmembers++;
	                }
	            }
	        }

	        $rosterpagination = $this->generate_pagination2($this->selfurl . '&amp;o=' . $this->current_order ['uri'] ['current'] , $this->member_count, $config ['bbdkp_user_llimit'], $this->start, true, 'start'  );

	        if (isset($this->current_order) && sizeof ($this->current_order) > 0)
	        {
	            $template->assign_vars(array(
	                    'ROSTERPAGINATION' 		=> $rosterpagination ,
	                    'U_LIST_MEMBERS0'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $this->current_order['uri'][0]),
	                    'U_LIST_MEMBERS1'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $this->current_order['uri'][1]),
	                    'U_LIST_MEMBERS2'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $this->current_order['uri'][2]),
	                    'U_LIST_MEMBERS3'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $this->current_order['uri'][3]),
	                    'U_LIST_MEMBERS4'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $this->current_order['uri'][4]),
	            ));

	        }

	        // add template constants
	        $template->assign_vars(array(
	                'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
	                'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $this->member_count,
	        ));
	    }

	    // add navigationlinks
	    $navlinks_array = array(
	            array(
	                    'DKPPAGE' => $user->lang['MENU_ROSTER'],
	                    'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster'),
	    ));

	    foreach( $navlinks_array as $name )
	    {
	        $template->assign_block_vars('dkpnavlinks', array(
	                'DKPPAGE' => $name['DKPPAGE'],
	                'U_DKPPAGE' => $name['U_DKPPAGE'],
	        ));
	    }

	    $template->assign_vars(array(
	            'S_RSTYLE'		    => '1',
	    ));

	    $header = $user->lang['GUILDROSTER'];
	    page_header($header);
	}

	/*
	 * Displays the listing
	*/
	public function displaylisting()
	{
	    global $phpbb_root_path, $phpEx, $config, $template, $user;

	    $a=0;
	    // use pagination
	    foreach ($this->dataset as $row)
	    {
	        $a++;
	        $race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
	        $template->assign_block_vars('members_row', array(
	                'GAME'			=>  $this->games[$row['game_id']],
	                'COLORCODE'		=> $row['colorcode'],
	                'CLASS'			=> $row['class_name'],
	                'NAME'			=> $row['member_name'],
	                'RACE'			=> $row['race_name'],
	                'RANK'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
	                'LVL'			=> $row['member_level'],
	                'ARMORY'		=> $row['member_armory_url'],
	                'PHPBBUID'		=> get_username_string('full', $row['phpbb_user_id'], $row['username'], $row['user_colour']),
	                'PORTRAIT'		=> $this->getportrait($this->game_id, $row),
	                'ACHIEVPTS'		=> $row['member_achiev'],
	                'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',
	                'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
	                'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '',
	                'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false,
	        ));
	    }

	    $rosterpagination = $this->generate_pagination2($this->selfurl . '&amp;o=' .
	            $this->current_order ['uri'] ['current'] ,
	            $this->member_count,
	            $config ['bbdkp_user_llimit'],
	            $this->start, true, 'start'  );


	    // add navigationlinks
	    $navlinks_array = array(
	            array(
	                    'DKPPAGE' => $user->lang['MENU_ROSTER'],
	                    'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster'),
	    ));

	    foreach( $navlinks_array as $name )
	    {
	        $template->assign_block_vars('dkpnavlinks', array(
	                'DKPPAGE' => $name['DKPPAGE'],
	                'U_DKPPAGE' => $name['U_DKPPAGE'],
	        ));
	    }

	    $template->assign_vars(array(
	            'ROSTERPAGINATION' 		=> $rosterpagination ,
	            'O_NAME'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][0],
	            'O_GAME'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][1],
	            'O_CLASS'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][2],
	            'O_RANK'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][3],
	            'O_LEVEL'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][4],
	            'O_PHPBB'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][5],
	            'O_ACHI'	=> $this->selfurl .'&amp;'. URI_ORDER. '='. $this->current_order['uri'][6]
	    ));


	    // add template constants
	    $template->assign_vars(array(
	            'S_RSTYLE'		    => '0',
	            'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
	            'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $this->member_count,
	    ));

	    $header = $user->lang['GUILDROSTER'];
	    page_header($header);
	}

	protected function getportrait($row)
	{
	    global $phpbb_root_path;

	    // setting up the links
	    switch ($this->game_id)
	    {
	        case 'wow':
	            if ( $row['member_portrait_url'] != '')
	            {
	                //get battle.NET icon
	                $memberportraiturl =  $row['member_portrait_url'];
	            }
	            else
	            {
	                if($row['member_level'] <= "59")
	                {
	                    $maxlvlid ="wow-default";
	                }
	                elseif($row['member_level'] <= 69)
	                {
	                    $maxlvlid ="wow";
	                }
	                elseif($row['member_level'] <= 79)
	                {
	                    $maxlvlid ="wow-70";
	                }
	                else
	                {
	                    // level 85 is not yet iconified
	                    $maxlvlid ="wow-80";
	                }
	                $memberportraiturl =  $phpbb_root_path .'images/roster_portraits/'. $maxlvlid .'/' . $row['member_gender_id'] . '-' .
	                        $row['member_race_id'] . '-' . $row['member_class_id'] . '.gif';
	            }
	            break;
	        case 'aion':
	            $memberportraiturl =  $phpbb_root_path . 'images/roster_portraits/aion/' . $row['member_race_id'] . '_' . $row['member_gender_id'] . '.jpg';
	            break;
	        default:
	            $memberportraiturl='';
	            break;
	    }
	    return $memberportraiturl;

	}


	protected function removeFromEnd($string, $stringToRemove)
	{
	    $stringToRemoveLen = strlen($stringToRemove);
	    $stringLen = strlen($string);
	    $pos = $stringLen - $stringToRemoveLen;
	    $out = substr($string, 0, $pos);
	    return $out;
	}


	public function get_listingresult($classid=0)
	{
	    global $db, $config;
	    $sql_array = array();
	    $sql_array['SELECT'] =  'm.game_id, m.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e1.name as race_name,
    		m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev, m.member_armory_url, m.member_portrait_url,
    		r.rank_prefix , r.rank_name, r.rank_suffix, e.image_female, e.image_male,
    		g.name, g.realm, g.region, c1.name as class_name, c.colorcode, c.imagename, m.phpbb_user_id, u.username, u.user_colour  ';

	    $sql_array['FROM'] = array(
	            MEMBER_LIST_TABLE    =>  'm',
	            CLASS_TABLE          =>  'c',
	            GUILD_TABLE          =>  'g',
	            MEMBER_RANKS_TABLE   =>  'r',
	            RACE_TABLE           =>  'e',
	            BB_LANGUAGE			 =>  'e1');

	    $sql_array['LEFT_JOIN'] = array(
	            array(
	                    'FROM'  => array(USERS_TABLE => 'u'),
	                    'ON'    => 'u.user_id = m.phpbb_user_id '),
	            array(
	                    'FROM'  => array(BB_LANGUAGE => 'c1'),
	                    'ON'    => "c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'  and c1.game_id = c.game_id "
	            ));

	    $sql_array['WHERE'] = " c.class_id = m.member_class_id
			AND c.game_id = m.game_id
			AND e.race_id = m.member_race_id
			AND e.game_id = m.game_id
			AND g.id = m.member_guild_id
			AND r.guild_id = m.member_guild_id
			AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
			AND m.member_status = 1
			AND m.member_level >= ".  intval($config['bbdkp_minrosterlvl']) . "
			AND m.member_rank_id != 99
			AND m.game_id = '" . $db->sql_escape($this->game_id) . "'
			AND e1.attribute_id = e.race_id AND e1.language= '" . $config['bbdkp_lang'] . "'
			AND e1.attribute = 'race' and e1.game_id = e.game_id";

	    $sort_order = array(
	            0 => array('m.member_name', 'm.member_name desc'),
	            1 => array('m.game_id', 'm.member_name desc'),
	            2 => array('m.member_class_id', 'm.member_class_id desc'),
	            3 => array('m.member_rank_id', 'm.member_rank_id desc'),
	            4 => array('m.member_level', 'm.member_level  desc'),
	            5 => array('u.username', 'u.username desc'),
	            6 => array('m.member_achiev', 'm.member_achiev  desc')
	    );

	    $this->current_order = $this->switch_order($sort_order);

	    if($this->mode=='class')
	    {
	        $sql_array['ORDER_BY']  = "m.member_class_id, " . $this->current_order['sql'];
	    }
	    else
	    {
	        $sql_array['ORDER_BY']  = $this->current_order['sql'];
	    }

	    $sql = $db->sql_build_query('SELECT', $sql_array);

	    $result = $db->sql_query($sql);

	    if ($this->mode=='listing' && $this->start > 0)
	    {
	        $this->member_count=0;
	        while ($row = $db->sql_fetchrow($result))
	        {
	            $this->member_count++;
	        }

	        //now get wanted window
	        $result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_llimit'], $this->start );
	        $this->dataset = $db->sql_fetchrowset($result);
	    }
	    else
	    {
	        $this->dataset = $db->sql_fetchrowset($result);
	        $this->member_count = count($this->dataset);
	    }

	    $db->sql_freeresult($result);
	}

	/**
	 * gets class array
	 *
	 * @param unknown_type $game_id
	 * @return unknown
	 */
	protected function get_classes()
	{
	    global $db, $config;
	    $sql_array = array(
	            'SELECT'    => 'c.class_id, c1.name as class_name, c.imagename, c.colorcode' ,
	            'FROM'      => array(
	                    MEMBER_LIST_TABLE    =>  'm',
	                    CLASS_TABLE          =>  'c',
	                    BB_LANGUAGE			=>  'c1',
	                    MEMBER_RANKS_TABLE   =>  'r',
	            ),
	            'WHERE'     => " c.class_id = m.member_class_id
    							AND c.game_id = m.game_id
    							AND r.guild_id = m.member_guild_id
    							AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
    							AND c1.attribute_id =  c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'
    							AND (c.game_id = '" . $db->sql_escape($this->game_id) . "')
    							AND c1.game_id=c.game_id

    							",
	            'ORDER_BY'  =>  'c1.name asc'
	    );
	    $sql = $db->sql_build_query('SELECT', $sql_array);
	    $result = $db->sql_query($sql);
	    $this->classes = $db->sql_fetchrowset($result);
	    $db->sql_freeresult($result);
	}







}

?>