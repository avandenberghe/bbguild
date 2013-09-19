<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

$game_id = request_var(URI_GAME, '');
// push common data to template
foreach ($this->games as $id => $gamename)
{
	$template->assign_block_vars ( 'game_row', array (
			'VALUE' => $id,
			'SELECTED' => ($id == $game_id) ? ' selected="selected"' : '',
			'OPTION' => $gamename));
}
$game_id = ($game_id == '') ? $id : $game_id;  

// Include the member class
if (!class_exists('\bbdkp\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}
$members = new \bbdkp\Members;
$members->game_id = $game_id; 

$start = request_var('start' ,0);
$mode = request_var('mode', ($config['bbdkp_roster_layout'] == '0') ? 'listing': 'class' ); 
$url = append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=roster&amp;mode=' . $mode); 
$guild_id =  request_var('guild_id',0);
$race_id =  request_var('race_id',0);
$class_id =  request_var('class_id',0);
$level1 =  request_var('$level1',0);
$level2 =  request_var('classid', 200);

$data = $members->get_listingresult($start, $mode, $guild_id, $class_id, $race_id, $level1, $level2); 

if ($mode =='listing')
{
	/*
	 * Displays the listing
	*/
	// use pagination
	foreach ($data[1] as $row)
	{
		$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male'] : $row['image_female']);
		$template->assign_block_vars('members_row', array(
				'GAME'			=>  $this->games[$row['game_id']],
				'COLORCODE'		=> $row['colorcode'],
				'CLASS'			=> $row['class_name'],
				'NAME'			=> $row['member_name'],
				'RACE'			=> $row['race_name'],
				'RANK'			=>  $row['rank_prefix'] . ' ' . $row['rank_name'] . ' ' . $row['rank_suffix'] ,
				'LVL'			=> $row['member_level'],
				'ARMORY'		=> $row['member_armory_url'],
				'PHPBBUID'		=> get_username_string('full', $row['phpbb_user_id'], $row['username'], $row['user_colour']),
				'PORTRAIT'		=> $row['member_portrait_url'],
				'ACHIEVPTS'		=> $row['member_achiev'],
				'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row['imagename'] . ".png" : '',
				'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
				'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/bbdkp/race_images/" . $race_image . ".png" : '',
				'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false,
		));
	}
	
	$rosterpagination = $this->generate_pagination2($url . '&amp;o=' . $data[2] ['uri'] ['current'] , $data[0],
			$config ['bbdkp_user_llimit'],
			$start, true, 'start'  );
	
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
			'O_NAME'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][0],
			'O_CLASS'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][2],
			'O_RANK'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][3],
			'O_LEVEL'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][4],
			'O_PHPBB'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][5],
			'O_ACHI'	=> $url .'&amp;'. URI_ORDER. '='. $data[2]['uri'][6]
	));
	
	
	// add template constants
	$template->assign_vars(array(
			'S_RSTYLE'		    => '0',
			'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
			'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $data[0],
			'S_DISPLAY_ROSTERLISTING' => true
	));
	

}
elseif($mode == 'class')
{
	//display grid 
	$classgroup = $members->get_classes($guild_id, $class_id, $race_id, $level1, $level2); 
	
	if(count($classgroup) > 0)
	{
		foreach($classgroup as $row1 )
		{
			$classes[$row1['class_id']]['name'] = $row1['class_name'];
			$classes[$row1['class_id']]['imagename'] = $row1['imagename'];
			$classes[$row1['class_id']]['colorcode'] = $row1['colorcode'];
		}

		foreach ($classes as  $classid => $class )
		{
			$classimgurl =  $phpbb_root_path . "images/bbdkp/roster_classes/" . $class['imagename'] .'.png';
			$classcolor = $class['colorcode'];

			$template->assign_block_vars('class', array(
					'CLASSNAME'     => $class['name'],
					'CLASSIMG'		=> $classimgurl,
					'COLORCODE'		=> $classcolor,
			));
			
			$classmembers=1;
			foreach ($data[1] as $row2)
			{
				if($row2['member_class_id'] == $classid)
				{
					$race_image = (string) (($row2['member_gender_id']==0) ? $row2['image_male'] : $row2['image_female']);
					$template->assign_block_vars('class.members_row', array(
							'COLORCODE'		=> $row2['colorcode'],
							'CLASS'			=> $row2['class_name'],
							'NAME'			=> $row2['member_name'],
							'RACE'			=> $row2['race_name'],
							'RANK'			=> $row2['rank_prefix'] . ' ' . $row2['rank_name'] . ' ' . $row2['rank_suffix'] ,
							'LVL'			=> $row2['member_level'],
							'ARMORY'		=> $row2['member_armory_url'],
							'PORTRAIT'		=> $row2['member_portrait_url'],
							'PHPBBUID'		=> get_username_string('full', $row2['phpbb_user_id'], $row2['username'], $row2['user_colour']),
							'ACHIEVPTS'		=> $row2['member_achiev'],
							'CLASS_IMAGE' 	=> (strlen($row2['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row2['imagename'] . ".png" : '',
							'S_CLASS_IMAGE_EXISTS' => (strlen($row2['imagename']) > 1) ? true : false,
							'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/bbdkp/race_images/" . $race_image . ".png" : '',
							'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false,
					));
					$classmembers++;
				}
			}
		}

		$rosterpagination = $this->generate_pagination2($url . '&amp;o=' . $data[2] ['uri'] ['current'] ,
			$data[0], $config ['bbdkp_user_llimit'], $start, true, 'start'  );

		if (isset($data[2]) && sizeof ($data[2]) > 0)
		{
			$template->assign_vars(array(
					'ROSTERPAGINATION' 		=> $rosterpagination ,
					'U_LIST_MEMBERS0'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $data[2]['uri'][0]),
					'U_LIST_MEMBERS1'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $data[2]['uri'][1]),
					'U_LIST_MEMBERS2'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $data[2]['uri'][2]),
					'U_LIST_MEMBERS3'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $data[2]['uri'][3]),
					'U_LIST_MEMBERS4'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $data[2]['uri'][4]),
			));

		}

		// add template constants
		$template->assign_vars(array(
				'S_SHOWACH'			=> $config['bbdkp_show_achiev'],
				'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $data[0],
				'S_DISPLAY_ROSTERGRID' => true
		));
	}

	// add navigationlinks
	/*
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
	*/
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
