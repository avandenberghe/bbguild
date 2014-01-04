<?php
/**
 * roster module
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
$mode = request_var('mode', ($config['bbdkp_roster_layout'] == '0') ? 'listing': 'class' );

// Include the member class
if (!class_exists('\bbdkp\controller\members\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/members/Members.$phpEx");
}
$members = new \bbdkp\controller\members\Members;

$start = request_var('start' ,0);
$url = append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=roster&amp;mode=' . $mode .'&amp;guild_id=' . $this->guild_id);

$characters = $members->getmemberlist($start, $mode, $this->query_by_armor, $this->query_by_class, $this->filter,
		$this->game_id, $this->guild_id, $this->class_id, $this->race_id, $level1, $level2, false);

if ($mode =='listing')
{
	/*
	 * Displays the listing
	*/
	// use pagination
	foreach ($characters[0] as $char)
	{
		$template->assign_block_vars('members_row', array(
				'MEMBER_ID'		=> $char['member_id'],
				'GAME'			=> $char['game_id'],
				'COLORCODE'		=> $char['colorcode'],
				'CLASS'			=> $char['class_name'],
				'NAME'			=> $char['member_name'],
				'RACE'			=> $char['race_name'],
				'RANK'			=> $char['member_rank'],
				'LVL'			=> $char['member_level'],
				'ARMORY'		=> $char['member_armory_url'],
				'PHPBBUID'		=> $char['username'],
				'PORTRAIT'		=> $char['member_portrait_url'],
				'ACHIEVPTS'		=> $char['member_achiev'],
				'CLASS_IMAGE' 	=> $char['class_image'],
				'RACE_IMAGE' 	=> $char['race_image'],
		));
	}

	$rosterpagination = $this->generate_pagination2($url . '&amp;o=' . $characters[1] ['uri'] ['current'] ,
			count($characters[0]),
			$config ['bbdkp_user_llimit'],
			$start, true, 'start' );

	// add navigationlinks
	$navlinks_array = array(
			array(
					'DKPPAGE' => $user->lang['MENU_ROSTER'],
					'U_DKPPAGE' => $url,
	));

	foreach($navlinks_array as $name )
	{
		$template->assign_block_vars('dkpnavlinks', array(
				'DKPPAGE' => $name['DKPPAGE'],
				'U_DKPPAGE' => $name['U_DKPPAGE'],
		));
	}

	$template->assign_vars(array(
			'ROSTERPAGINATION' 		=> $rosterpagination ,
			'O_NAME'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][0],
			'O_CLASS'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][2],
			'O_RANK'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][3],
			'O_LEVEL'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][4],
			'O_PHPBB'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][5],
			'O_ACHI'	=> $url .'&amp;'. URI_ORDER. '='. $characters[1]['uri'][6]
	));


	// add template constants
	$template->assign_vars(array(
			'S_RSTYLE'		    => '0',
			'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
			'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . count($characters[0]),
			'S_DISPLAY_ROSTERLISTING' => true
	));


}
elseif($mode == 'class')
{
	//display grid
	$classgroup = $members->get_classes($this->filter, $this->query_by_armor,
		$this->class_id, $this->guild_id,  $this->race_id, $level1, $level2);

	if(count($classgroup) > 0)
	{
		foreach($classgroup as $row1 )
		{
			$classes[$row1['class_id']]['name'] = $row1['class_name'];
			$classes[$row1['class_id']]['imagename'] = $row1['imagename'];
			$classes[$row1['class_id']]['colorcode'] = $row1['colorcode'];
		}

		foreach ($classes as  $classid => $class)
		{
			$classimgurl =  $phpbb_root_path . "images/bbdkp/roster_classes/" . $class['imagename'] .'.png';
			$classcolor = $class['colorcode'];

			$template->assign_block_vars('class', array(
					'CLASSNAME'     => $class['name'],
					'CLASSIMG'		=> $classimgurl,
					'COLORCODE'		=> $classcolor,
			));

			$classmembers=1;
			foreach ($characters[0] as $row2)
			{
				if($row2['member_class_id'] == $classid)
				{
					$template->assign_block_vars('class.members_row', array(
							'MEMBER_ID'		=> $row2['member_id'],
							'GAME'			=> $row2['game_id'],
							'COLORCODE'		=> $row2['colorcode'],
							'CLASS'			=> $row2['class_name'],
							'NAME'			=> $row2['member_name'],
							'RACE'			=> $row2['race_name'],
							'RANK'			=> $row2['member_rank'],
							'LVL'			=> $row2['member_level'],
							'ARMORY'		=> $row2['member_armory_url'],
							'PHPBBUID'		=> $row2['username'],
							'PORTRAIT'		=> $row2['member_portrait_url'],
							'ACHIEVPTS'		=> $row2['member_achiev'],
							'CLASS_IMAGE' 	=> $row2['class_image'],
							'RACE_IMAGE' 	=> $row2['race_image'],
					));
					$classmembers++;
				}
			}
		}

		$rosterpagination = $this->generate_pagination2($url . '&amp;o=' . $characters[1] ['uri'] ['current'] ,
				count($characters[0]), $config ['bbdkp_user_llimit'], $start, true, 'start' );

		if (isset($characters[1]) && sizeof ($characters[1]) > 0)
		{
			$template->assign_vars(array(
					'ROSTERPAGINATION' 		=> $rosterpagination ,
					'U_LIST_MEMBERS0'	=> $url . '&amp;'. URI_ORDER. '='. $characters[1]['uri'][0],
					'U_LIST_MEMBERS1'	=> $url . '&amp;'. URI_ORDER. '='. $characters[1]['uri'][1],
					'U_LIST_MEMBERS2'	=> $url . '&amp;'. URI_ORDER. '='. $characters[1]['uri'][2],
					'U_LIST_MEMBERS3'	=> $url . '&amp;'. URI_ORDER. '='. $characters[1]['uri'][3],
					'U_LIST_MEMBERS4'	=> $url . '&amp;'. URI_ORDER. '='. $characters[1]['uri'][4],
			));

		}

		// add template constants
		$template->assign_vars(array(
				'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
				'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . count($characters[0]),
				'S_DISPLAY_ROSTERGRID' => true
		));
	}

	// add menu navigationlinks
	$navlinks_array = array(
			array(
					'DKPPAGE' => $user->lang['MENU_ROSTER'],
					'U_DKPPAGE' => $url,
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

}

$template->assign_vars(array(
		'S_MULTIGAME'		=> (sizeof($this->games) > 1) ? true:false,
		'S_DISPLAY_ROSTER'  => true,
		'F_ROSTER'			=> $url,
		'S_GAME'		    => $members->game_id,
));

$header = $user->lang['GUILDROSTER'];
page_header($header);

?>
