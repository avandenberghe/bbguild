<?php
/**
 * Recruitment block
 *
 * @package bbdkp
 * @link http://www.bbdkp.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4.0
 * @author Sajaki
 *
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**  begin recruitment block ***/

$template->assign_block_vars('status', array('MESSAGE' => $user->lang['RECRUIT_MESSAGE']));

// Include the abstract base
if (!class_exists('\bbdkp\controller\guilds\Recruitment'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Recruitment.$phpEx");
}

$color = array(
		array(0 , $user->lang['CLOSED'] , "#AAAAAA" , "rec_closed.png") ,
		array(1 , $user->lang['LOW'] , "#FFBB44" , "rec_low.png") ,
		array(2 , $user->lang['MEDIUM'] ,"#FF3300" ,"rec_med.png") ,
		array(3 , $user->lang['HIGH'] ,"#AA00AA" ,"rec_high.png")
);
$recruit = new \bbdkp\controller\guilds\Recruitment;

$guildrecruitingresult = $recruit->get_recruiting_guilds();

$this->apply_installed = false;
$plugin_versioninfo = (array) parent::get_plugin_info(request_var('versioncheck_force', false));

if(isset($plugin_versioninfo['apply']))
{
    $this->apply_installed = true;
}

while ($row = $db->sql_fetchrow($guildrecruitingresult))
{

	$guild_id = $row['id'];

	$template->assign_block_vars('guild', array(
			'EMBLEM' 	=> $row['emblemurl'],
			'NAME' 		=> $row['name'],
			'S_CLOSED'  => $row['rec_status'] ==0 ? true : false,
	));

    $recruit->setGuildId($guild_id);

	$Guild = new \bbdkp\controller\guilds\Guilds();
	$Guild->guildid= $guild_id;
	$Guild->Getguild();

	$blockresult = $recruit->ListRecruitments(1);
	while ($row = $db->sql_fetchrow($blockresult))
	{
		switch ($row['positions'])
		{
			case 0:
				$pos_icon= 'rec_closed.png';
				break;
			case 1:
				$pos_icon= 'rec_low.png';
				break;
			case 2:
				$pos_icon= 'rec_med.png';
				break;
			default:
				$pos_icon= 'rec_high.png';
				break;
		}

        $forumlink = append_sid ("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $Guild->recruitforum);

        if($this->apply_installed)
        {
            $forumlink = append_sid ("{$phpbb_root_path}apply.$phpEx", 'template_id=' . $row['applytemplate_id']);
        }

		$template->assign_block_vars('guild.rec', array(
				'CLASS_IMAGE' =>  (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/bbdkp/class_images/" . $row['imagename'] . ".png" : '' ,
				'CLASSID' => $row['class_id'] ,
				'CLASS' => $row['class_name'] ,
				'IMAGENAME' => $row['imagename'] ,
				'CLASSCOLOR' => $row['colorcode'] ,
                'ROLENAME' => $row['role_name'] ,
                'ROLEICON' => $phpbb_root_path . "images/bbdkp/role_icons/" .$row['role_icon'] . ".png",
                'POSITIONS' => $row['positions'] ,
				'FORUMLINK' => $forumlink,
				'POSITIONSICON' => $phpbb_root_path . "images/bbdkp/recruitblock/" .$pos_icon,
                'NOTE' => $row['note'] ,
				'COLOR' => $color[$row['positions'] > 3 ? 3 : $row['positions']][2],
		));

	}
	$db->sql_freeresult($blockresult);

}
$db->sql_freeresult($guildrecruitingresult);
$template->assign_vars(array(
		'S_DISPLAY_RECRUIT' => true,
		));
