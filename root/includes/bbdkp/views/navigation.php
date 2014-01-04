<?php
/**
 * left front navigation block
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
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

// get inputs
$this->guild_id = request_var(URI_GUILD, request_var('hidden_guild_id', 0) );


$this->show_all = ( request_var ( 'show', request_var ( 'hidden_show', '' )) == $user->lang['ALL']) ? true : false;

//include the guilds class
if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}
$guilds = new \bbdkp\controller\guilds\Guilds();

$guildlist = $guilds->guildlist(1);
if(count($guildlist) > 0)
{
	foreach ($guildlist as $g)
	{
		//assign guild_id property
		if($this->guild_id==0)
		{
			//if there is a default guild
			if($g['guilddefault'] == 1)
			{
				$this->guild_id = $g['id'];
			}
			elseif($g['membercount'] > 1)
			{
				$this->guild_id = $g['id'];
			}

			//if guild id field still 0
			if($this->guild_id == 0 && $g['id'] > 0)
			{
				$this->guild_id = $g['id'];
			}
		}

		//populate guild popup
		if($g['id'] > 0) // exclude guildless
		{
			$template->assign_block_vars('guild_row', array(
					'VALUE' => $g['id'] ,
					'SELECTED' => ($g['id'] == $this->guild_id ) ? ' selected="selected"' : '' ,
					'OPTION' =>  $g['name']));
		}
	}

}
else
{
	trigger_error('ERROR_NOGUILD', E_USER_WARNING );
}


$guilds->guildid = $this->guild_id;
$guilds->Getguild();
$this->game_id = $guilds->game_id;

$this->query_by_pool = true;
$this->dkppulldown();

$race_id =  request_var('race_id',0);
$level1 =  request_var('$level1',0);
$level2 =  request_var('classid', 200);

$this->filter= request_var('filter', $user->lang['ALL']);
$this->query_by_armor = false;
$this->query_by_class = false;
$this->armor($page);

if ($this->filter!= $user->lang['ALL'])
{
	if (array_key_exists ( $this->filter, $this->armor_type ))
	{
		// looking for an armor type
		$this->filter= preg_replace ( '/ Armor/', '', $this->filter);
		$this->query_by_armor = true;
		$this->query_by_class = false;
	}
	elseif (array_key_exists ( $this->filter, $this->classname ))
	{
		// looking for a class
		$this->query_by_class = true;
		$t = explode("_", $this->filter);
		$this->class_id = count($t) > 1 ? $t[2]: 0;
		$this->query_by_armor = false;
	}
}

$template->assign_vars(array(
		// Form values
		'S_GUILDDROPDOWN'	=> count($guildlist) > 1 ? true : false,
 		'U_NEWS'  			=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=news&amp;guild_id=' . $this->guild_id),
		'U_LISTMEMBERS'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=standings&amp;guild_id=' . $this->guild_id),
		'U_LOOTDB'     		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=lootdb&amp;guild_id=' . $this->guild_id),
		'U_LOOTHIST'  		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=loothistory&amp;guild_id=' . $this->guild_id),
		'U_LISTEVENTS'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listevents&amp;guild_id=' . $this->guild_id),
		'U_LISTRAIDS'   	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listraids&amp;guild_id=' . $this->guild_id),
		'U_VIEWITEM'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewitem&amp;guild_id=' . $this->guild_id),
		'U_VIEWMEMBER'   	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewmember&amp;guild_id=' . $this->guild_id),
		'U_VIEWRAID'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewraid&amp;guild_id=' . $this->guild_id),
		'U_BP'   			=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=bossprogress&ampg;uild_id=' . $this->guild_id),
		'U_ROSTER'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;guild_id=' . $this->guild_id),
		'U_STATS'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=stats&amp;guild_id=' . $this->guild_id),
		'U_ABOUT'         	=> append_sid("{$phpbb_root_path}aboutbbdkp.$phpEx"),
		'GAME_ID'			=> $guilds->game_id,
		'GUILD_ID' 			=> $this->guild_id,
		'GUILD_NAME' 		=> $guilds->name,
		'REALM' 			=> $guilds->realm,
		'REGION' 			=> $guilds->region,
		'MEMBERCOUNT' 		=> $guilds->membercount ,
		'ARMORY_URL' 		=> $guilds->guildarmoryurl ,
		'MIN_ARMORYLEVEL' 	=> $guilds->min_armory ,
		'SHOW_ROSTER' 		=> $guilds->showroster,
		'EMBLEM'			=> $guilds->emblempath,
		'EMBLEMFILE' 		=> basename($guilds->emblempath),
		'ARMORY'			=> $guilds->guildarmoryurl,
		'ACHIEV'			=> $guilds->achievementpoints,
		'SHOWALL'			=> ($this->show_all) ? $user->lang['ALL']: '',
		'F_NAVURL' 			=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;guild_id=' . $this->guild_id),
));



?>