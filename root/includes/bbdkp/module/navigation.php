<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * navigation block
 * @version 1.3.0
 */

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}

//include the guilds class
if (!class_exists('\bbdkp\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
}
$guilds = new \bbdkp\Guilds();
$guildlist = $guilds->guildlist();
$default = 0;
foreach ($guildlist as $g)
{
	if($default == 0)
	{
		if($g['guilddefault'] == '1') 
		{
			$default = $g['id']; 
		}
		
		if($default == 0 && $g['membercount'] > 1)
		{
			$default = $g['id'];
		}
	}
	
	//populate guild popup
	$template->assign_block_vars('guild_row', array(
			'VALUE' => $g['id'] ,
			'SELECTED' => ($g['guilddefault'] == '1') ? ' selected="selected"' : '' ,
			'OPTION' => (! empty($g['name'])) ? $g['name'] : '(None)'));
	
}

$mode = request_var('mode', ($config['bbdkp_roster_layout'] == '0') ? 'listing': 'class' );
$navurl = append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=roster&amp;mode=' . $mode);

$guilds->guildid = request_var(URI_GUILD, $default);
$guilds->Getguild(); 
$template->assign_vars(array(
		// Form values
		'GAME_ID'	=> $guilds->game_id,
		'GUILD_ID' => $guilds->guildid,
		'GUILD_NAME' => $guilds->name,
		'REALM' => $guilds->realm,
		'REGION' => $guilds->region,
		'MEMBERCOUNT' => $guilds->membercount ,
		'ARMORY_URL' => $guilds->guildarmoryurl ,
		'MIN_ARMORYLEVEL' => $guilds->min_armory ,
		'SHOW_ROSTER' => $guilds->showroster,
		'EMBLEM'	=> $guilds->emblempath,
		'EMBLEMFILE' => basename($guilds->emblempath),
		'ARMORY'	=> $guilds->guildarmoryurl,
		'ACHIEV'	=> $guilds->achievementpoints,
		'F_NAVURL' => $navurl,
));



?>