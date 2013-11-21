<?php
/**
 * Recruitment block
 *
 *   @package bbdkp
 * @link http://www.bbdkp.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @author Sajaki, Blazeflack, Malfate
 *
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**  begin recruitment block ***/
$color = array(
	array(0 , $user->lang['CLOSED'] , "#AAAAAA" , "rec_closed.png") ,
	array(1 , $user->lang['LOW'] , "#FFBB44" , "rec_low.png") ,
	array(2 , $user->lang['MEDIUM'] ,"#FF3300" ,"rec_med.png") ,
	array(3 , $user->lang['HIGH'] ,"#AA00AA" ,"rec_high.png")
	);

$template->assign_block_vars('status', array(
		'MESSAGE' => $user->lang['RECRUIT_MESSAGE']));

$rec_forum_id = $config['bbdkp_recruit_forumid'];

// Include the abstract base
if (!class_exists('\bbdkp\controller\guilds\Roles'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Roles.$phpEx");

}

$roles = new \bbdkp\controller\guilds\Roles;
$result = $roles->recruitblock();
while ($row = $db->sql_fetchrow($result))
{
	$class[$row['class_id']] = $row['class_name'];

	$template->assign_block_vars('rec', array(
		'CLASSID' => $row['class_id'] ,
		'CLASS' => $row['class_name'] ,
		'IMAGENAME' => $row['imagename'] ,
		'CLASS_IMAGE' =>  (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row['imagename'] . ".png" : '' ,
		'CLASSCOLOR' => $row['colorcode'] ,

		'TANKCOLOR' => $color[$row['tank']][2] ,
		'TANKFORUM' => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id) ,
		'TANKTEXT' => $color[$row['tank']][1] ,
		'TANK' => $color[$row['tank']][3] ,
		'S_TANK' => ((int) $row['tank'] == 0) ? false: true,

		'DPSCOLOR' => $color[$row['dps']][2] ,
		'DPSFORUM' => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id) ,
		'DPSTEXT' => $color[$row['dps']][1] ,
		'DPS' => $color[$row['dps']][3] ,
		'S_DPS' => ((int) $row['dps'] == 0) ? false: true,

		'HEALCOLOR' => $color[$row['heal']][2] ,
		'HEALFORUM' => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id) ,
		'HEALTEXT' => $color[$row['heal']][1] ,
		'HEAL' => $color[$row['heal']][3],
		'S_HEAL' => ((int)  $row['heal'] == 0) ? false: true,

	));
}
$db->sql_freeresult($result);

/*
 * $template->assign_block_vars('status', array(
		'S_DISPLAY_RECRUIT' => true ,
		'MESSAGE' => $user->lang['RECRUIT_CLOSED']));
 */



$template->assign_vars(array( 'S_DISPLAY_RECRUIT' => true));


?>