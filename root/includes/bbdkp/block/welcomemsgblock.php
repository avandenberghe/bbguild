<?php
/**
 * welcome block
 *
 *   @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4.1
 */
use bbdkp\controller\guilds\Guilds;

if (!defined('IN_PHPBB'))
{
    exit;
}
$user->add_lang(array('posting'));

if (!function_exists('generate_text_for_display'))
{
    include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
}
$text='';
$sql = 'SELECT welcome_msg, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . WELCOME_MSG_TABLE;
$db->sql_query($sql);
$result = $db->sql_query($sql);
while ( $row = $db->sql_fetchrow($result) )
{
    $text = $row['welcome_msg'];
    $bbcode_uid = $row['bbcode_uid'];
    $bbcode_bitfield = $row['bbcode_bitfield'];
    $bbcode_options = $row['bbcode_options'];
}
$db->sql_freeresult($result);

$message = generate_text_for_display($text, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
$message = smiley_text($message);



// Include the abstract base
if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
    require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}

$guild = new Guilds(1);
$newsarr = $guild->GetGuildNews();

$i=0;
foreach ($newsarr['news'] as $id => $news)
{
    $i++;

    switch($news['type'])
    {
        case 'itemCraft' :
        case 'itemLoot' :
            $template->assign_block_vars('activityfeed', array(
                    'TYPE' => 'ITEM',
                    'ID' => $id,
                    'VERB' => $user->lang('LOOTED'),
                    'CHARACTER' => $news['character'],
                    'TIMESTAMP' => ( !empty($news['timestamp']) ) ? dateDiff($news['timestamp']) . '&nbsp;' : '&nbsp;',
                    'ITEM' =>  isset($news['itemId']) ? $news['itemId'] : '',
                    'CONTEXT' => $news['context'],
                    //trade-skill, quest-reward, raid-finder, vendor, dungeon-heroic, raid-normal , dungeon-normal
                )
            );
            break;
        case 'playerAchievement':
            $template->assign_block_vars('activityfeed', array(
                    'TYPE' => 'ACHI',
                    'ID' => $id,
                    'VERB' => $user->lang('ACHIEVED'),
                    'CHARACTER' => $news['character'],
                    'TIMESTAMP' => ( !empty($news['timestamp']) ) ? dateDiff($news['timestamp']) . '&nbsp;' : '&nbsp;',
                    'ACHIEVEMENT' =>  $news['achievement']['id'],
                    'TITLE' =>  $news['achievement']['title'],
                    'POINTS' =>  sprintf($user->lang['FORNPOINTS'], $news['achievement']['points'] ),
                )
            );
            break;
    }

    if($i > 10)
    {
        break;
    }

}

$template->assign_vars(array(
    'EMBLEM'                => $guild->emblempath,
    'GUILD_REALM'           => $guild->realm,
    'GUILD_REGION'          => $guild->region,
    'GUILD_NAME'            => $guild->name,
    'WELCOME_MESSAGE'		=> $message,
    'S_DISPLAY_WELCOME' 	=> true,
));

unset($newsarr);

/**
 * return relative time difference
 *
 * @param $epoch //in microtime
 * @return string
 */
function dateDiff($epoch)
{
    $dateDiff = '';
    $epoch = $epoch / 1000;
    $datetime1 = new DateTime("@$epoch");
    $datetime2 = new DateTime();
    $interval = date_diff($datetime1, $datetime2);

    $min=$interval->format('%i');
    $sec=$interval->format('%s');
    $hour=$interval->format('%h');
    $mon=$interval->format('%m');
    $day=$interval->format('%d');
    $year=$interval->format('%y');
    if($interval->format('%i%h%d%m%y')=="00000")
    {
        //echo $interval->format('%i%h%d%m%y')."<br>";
        $dateDiff= $sec." Seconds";

    }

    else if($interval->format('%h%d%m%y')=="0000")
    {
        $dateDiff= $min." Minutes";
    }


    else if($interval->format('%d%m%y')=="000")
    {
        $dateDiff= $hour." Hours";
    }


    else if($interval->format('%m%y')=="00")
    {
        $dateDiff= $day." Days";
    }

    else if($interval->format('%y')=="0")
    {
        $dateDiff= $mon." Months";
    }

    else
    {
        $dateDiff= $year." Years";
    }
    return $dateDiff;
}
