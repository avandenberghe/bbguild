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
class views extends \bbdkp\admin\Admin
{
	/**
	 * guild id
	 * @var int
	 */
	private $guild_id;
	/**
	 * game id
	 * @var string
	 */
	private $game_id;
	/**
	 * filter by pool ?
	 * @var boolean
	 */
	private $query_by_pool = true;
	/**
	 * filter by armor ?
	 * @var boolean
	 */
	private $query_by_armor = false;
	/**
	 * filter by class ?
	 * @var unknown
	 */
	private $query_by_class = false;
	/**
	 * the filter string
	 * @var string
	 */
	private $filter = '';
	/**
	 * show all members even not active ?
	 * @var boolean
	 */
	private $show_all = false;

	/**
	 * pool id
	 * @var integer
	 */
	private $dkpsys_id = 0;
	private $defaultpool = 0;

	/**
	 * name of pool
	 * @var string
	 */
	private $dkpsys_name = '';


	/**
	 * values of armor types
	 * @var array
	 */
	private $armor_type = array();
	private $classname = array();
	private $classarray = array();
	/**
	 * race id from pulldown
	 */
	private $race_id = 0;

	/**
	 * class id from pulldown
	 */
	private $class_id = 0;

	/**
	 * load a page asked for by user
	 *
	 * @param string $page
	 */
	public function load($page)
	{
		global $user, $template, $config, $phpbb_root_path, $phpEx ;
		global $db, $auth;

		$this->filter = $user->lang['ALL'];

		if ($this->bbtips == true)
		{
			if (! class_exists ( 'bbtips' ))
			{
				require ($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx);
			}
		}

		//load navigation
		include($phpbb_root_path . 'includes/bbdkp/views/navigation.' . $phpEx);

		// load viewss
		switch ($page)
		{
			case 'roster':
				include($phpbb_root_path . 'includes/bbdkp/views/roster.' . $phpEx);
				break;
			case 'news':
				include($phpbb_root_path . 'includes/bbdkp/views/news.' . $phpEx);
				break;
			case 'standings':
				include($phpbb_root_path . 'includes/bbdkp/views/standings.' . $phpEx);
				break;
			case 'loothistory':
				include($phpbb_root_path . 'includes/bbdkp/views/loothistory.' . $phpEx);
				break;
			case 'lootdb':
				include($phpbb_root_path . 'includes/bbdkp/views/lootdb.' . $phpEx);
				break;
			case 'listevents':
				include($phpbb_root_path . 'includes/bbdkp/views/listevents.' . $phpEx);
				break;
			case 'stats':
				include($phpbb_root_path . 'includes/bbdkp/views/stats.' . $phpEx);
				break;
			case 'listraids':
				include($phpbb_root_path . 'includes/bbdkp/views/listraids.' . $phpEx);
				break;
			case 'viewevent':
				include($phpbb_root_path . 'includes/bbdkp/views/viewevent.' . $phpEx);
				break;
			case 'viewitem':
				include($phpbb_root_path . 'includes/bbdkp/views/viewitem.' . $phpEx);
				break;
			case 'viewraid':
				include($phpbb_root_path . 'includes/bbdkp/views/viewraid.' . $phpEx);
				break;
			case 'viewmember':
				include($phpbb_root_path . 'includes/bbdkp/views/viewmember.' . $phpEx);
				break;
			case 'bossprogress':
				include($phpbb_root_path . 'includes/bbdkp/views/bossprogress.' . $phpEx);
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

				if (isset($config['bbdkp_raidplanner']))
				{
				    if ($config['rp_show_portal'] == 1)
				    {
				        $user->add_lang(array('mods/raidplanner'));
                        if (!class_exists('\rpblocks', false))
                        {
                            //display the blocks
                            include($phpbb_root_path . 'includes/bbdkp/raidplanner/rpblocks.' . $phpEx);
                        }
                        $blocks = new \rpblocks();
                        $blocks->display();
                    }
                }
				break;
		}
	}


	/**
	 * build dkp dropdown, for standings/stats, called by navigation
	 * @return int $dkpsys_id
	 */
	private function dkppulldown()
	{
		global $user, $db, $template, $query_by_pool;


		$defaultpool = 99;
		$dkpvalues = array();

		$dkpvalues[0] = $user->lang['ALL'];
		$dkpvalues[1] = '--------';

		// find only pools with dkp records that are active

		$sql_array = array(
				'SELECT'    => 'a.dkpsys_id, a.dkpsys_name, a.dkpsys_default',
				'FROM'		=> array(
						DKPSYS_TABLE => 'a',
						MEMBER_DKP_TABLE => 'd',
						MEMBER_LIST_TABLE => 'l'
				),

				'WHERE'  => " a.dkpsys_id = d.member_dkpid
							AND a.dkpsys_status != 'N'
							AND d.member_id = l.member_id
							AND l.member_guild_id = " . $this->guild_id ,
				'GROUP_BY'  => 'a.dkpsys_id, a.dkpsys_name, a.dkpsys_default',
				'ORDER_BY'  => 'a.dkpsys_id '
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
				$this->defaultpool = $row ['dkpsys_id'];
			}
			$index +=1;
		}
		$db->sql_freeresult ( $result );

		foreach ($dkpvalues as $key => $value)
		{
			if(!is_array($value))
			{
				$template->assign_block_vars ( 'pool_row', array (
						'VALUE' => $value,
						'SELECTED' => (!$this->query_by_pool && $value != '--------') ? ' selected="selected"' : '',
						'DISABLED' => ($value == '--------' ) ? ' disabled="disabled"' : '',
						'OPTION' => $value,
				));
			}
			else
			{
				$template->assign_block_vars ( 'pool_row', array (
						'VALUE' => $value['id'],
						'SELECTED' => ($this->dkpsys_id == $value['id']  && $this->query_by_pool ) ? ' selected="selected"' : '',
						'OPTION' => $value['text'],
				));

				if($this->dkpsys_id == $value['id'] && $this->query_by_pool )
				{
					$this->dkpsys_name = $value['text'];
				}

			}
		}
	}

	/**
	 * build Class / armor dropdown
	 *
	 * @param string $page
	 * @return array
	 */
	private function armor($page)
	{
		global $config, $user, $db, $template, $query_by_pool;

		/***** begin armor-class pulldown ****/
		$filtervalues = array();

		$filtervalues ['all'] = $user->lang['ALL'];
		$filtervalues ['separator1'] = '--------';

		// generic armor list
		$sql = 'SELECT class_armor_type FROM ' . CLASS_TABLE . ' GROUP BY class_armor_type';
		$result = $db->sql_query ( $sql);
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$filtervalues [strtoupper($row ['class_armor_type'])] = $user->lang[strtoupper($row ['class_armor_type'])];
			$this->armor_type [strtoupper($row ['class_armor_type'])] = $user->lang[strtoupper($row ['class_armor_type'])];
		}
		$db->sql_freeresult ( $result );
		$filtervalues ['separator2'] = '--------';


		// get classlist, depending on page
		$sql_array = array(
				'SELECT'    => 	'  c.game_id, c.class_id, l.name as class_name, c.class_min_level, c.class_max_level, c.imagename, c.colorcode ',
				'FROM'      => array(
						CLASS_TABLE 	=> 'c',
						BB_LANGUAGE		=> 'l',
						MEMBER_LIST_TABLE	=> 'i',
				),
				'WHERE'		=> " c.class_id > 0 and l.attribute_id = c.class_id and c.game_id = l.game_id
				 		AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
				 		AND i.member_class_id = c.class_id and i.game_id = c.game_id AND i.game_id = '" .  $this->game_id . "'" ,

				'GROUP_BY'	=> 'c.game_id, c.class_id, l.name, c.class_min_level, c.class_max_level, c.imagename, c.colorcode',
				'ORDER_BY'	=> 'c.game_id, c.class_id ',
		);

		$sql_array[ 'WHERE'] .= ' AND i.member_guild_id = ' . $this->guild_id . ' ';

		if($page =='standings' or $page =='stats')
		{
			$sql_array['FROM'][MEMBER_DKP_TABLE] = 'd';
			$sql_array[ 'WHERE'] .= 'AND d.member_id = i.member_id';
			if ($config ['bbdkp_hide_inactive'] == '1' && ! $this->show_all )
			{
				// don't show inactive members
				$sql_array['WHERE'] .= ' AND d.member_status = 1 ';
			}
		}

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query ($sql);
		$this->classarray = array();
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$this->classarray[] = $row;
			$filtervalues [$row['game_id'] . '_class_' . $row ['class_id']] = $row ['class_name'];
			$this->classname [$row['game_id'] . '_class_' . $row ['class_id']] = $row ['class_name'];
		}
		$db->sql_freeresult ( $result );

		// dump filtervalues to dropdown template
		foreach ( $filtervalues as $fid => $fname )
		{
			$template->assign_block_vars ( 'filter_row', array (
					'VALUE' => $fid,
					'SELECTED' => ($fid == $this->filter && $fname !=  '--------' ) ? ' selected="selected"' : '',
					'DISABLED' => ($fname == '--------' ) ? ' disabled="disabled"' : '',
					'OPTION' => (! empty ( $fname )) ? $fname : $user->lang['ALL'] ) );
		}

		/***** end armor - class pulldown ****/

	}



}

?>