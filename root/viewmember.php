<?php
/**
 * View individual member
 * 
 * @package bbDKP
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */
 
/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');
$user->add_lang(array('mods/dkp_common','mods/dkp_admin'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}

if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

if	(!isset($_GET[URI_NAMEID]) && isset($_GET[URI_DKPSYS])) 
{
	 trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
}

$member_id = request_var(URI_NAMEID, 0);
$dkp_id = request_var(URI_DKPSYS, 0);

/*****************************
/***   make member array
******************************/
$sql_array = array(
	'SELECT'    => '
    	a.*, 
    	g.name as guildname, g.realm, g.region, 
		m.member_id, 
		m.member_raid_value,
		m.member_time_bonus,
		m.member_zerosum_bonus, 
		m.member_earned,
		m.member_raid_decay, 
		m.member_adjustment, 
		(m.member_earned - m.member_raid_decay + m.member_adjustment) AS ep	,
		m.member_spent,
		m.member_item_decay,
		(m.member_spent - m.member_item_decay ) AS gp,
		(m.member_earned + m.member_adjustment - m.member_spent) AS member_current,
		case when (m.member_spent - m.member_item_decay) = 0 then 1 
		else round((m.member_earned - m.member_raid_decay + m.member_adjustment) / (m.member_spent - m.member_item_decay),2) end as er,
		m.member_lastraid,
		r1.name AS member_race,
		s.dkpsys_name, 
		l.name AS member_class, 
		r.rank_name, 
		r.rank_prefix, 
		r.rank_suffix, 
		c.class_armor_type AS armor_type ,
		c.colorcode, 
		c.imagename, 
		a.member_gender_id, race.image_female_small, race.image_male_small ', 
 
    'FROM'      => array(
        MEMBER_LIST_TABLE 	=> 'a',
        GUILD_TABLE			=> 'g', 
        MEMBER_DKP_TABLE    => 'm',
        MEMBER_RANKS_TABLE  => 'r',
		CLASS_TABLE 		=> 'c',
        RACE_TABLE  		=> 'race',
		BB_LANGUAGE			=> 'l', 
        DKPSYS_TABLE    	=> 's',
    ),
    
     'LEFT_JOIN' => array(
        array(
            'FROM'  => array(BB_LANGUAGE => 'r1'),
            'ON'    => "r1.attribute_id = a.member_race_id AND r1.language= '" . 
        		$config['bbdkp_lang'] . "' AND r1.attribute = 'race'" 
            )
        ),
 
    'WHERE'     =>  " a.member_rank_id = r.rank_id 
    				AND a.member_guild_id = r.guild_id  
					AND a.member_id = m.member_id 
					AND a.member_class_id = c.class_id
					AND a.member_race_id =  race.race_id  
					AND m.member_dkpid = s.dkpsys_id
					AND a.member_guild_id = g.id     
					AND l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'    
					AND s.dkpsys_id = " . $dkp_id . '   
				    AND a.member_id = ' . $member_id,
				);
			 
$sql = $db->sql_build_query('SELECT', $sql_array);
if ( !($result = $db->sql_query($sql)) )
{
	trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
}

// Make sure they provided a valid member
if ( !$row = $db->sql_fetchrow($result) )
{
	trigger_error($user->lang['ERROR_MEMBERNOTFOUND']);
}
$db->sql_freeresult($result);

// make object
$member = array(
	'guildname'         => $row['guildname'],
	'region'			=> $row['region'],  
	'realm'				=> $row['realm'],  
	'member_id'         => $row['member_id'],
	'member_dkpname'	=> $row['dkpsys_name'],  
	'member_name'       => $row['member_name'],
	'member_raid_value'    => $row['member_raid_value'],
	'member_time_bonus'    => $row['member_time_bonus'],
	'member_zerosum_bonus' => $row['member_zerosum_bonus'],
	'member_earned'        => $row['member_earned'],
	'member_raid_decay'	   => $row['member_raid_decay'], 
	'member_adjustment'    => $row['member_adjustment'],
	'ep'    			   => $row['ep'],
	'member_spent'         => $row['member_spent'],
	'member_item_decay'    => $row['member_item_decay'],
	'gp'     			   => $row['gp'],
	'er'     			   => $row['er'],
	'member_current'       => $row['member_current'],
	'member_race_id'    => $row['member_race_id'], 
	'member_race'       => $row['member_race'],
	'member_class_id'   => $row['member_class_id'],
	'member_class'      => $row['member_class'],
	'member_level'      => $row['member_level'], 
	'member_rank_id'    => $row['member_rank_id'],
	'member_rank'		=> $row['rank_name'],
	'classimage'		=> $row['imagename'],
	'raceimage'			=> (string) (($row['member_gender_id']==0) ? $row['image_male_small'] : $row['image_female_small']), 
	'colorcode'			=> $row['colorcode'], 
);	

//output
$template->assign_vars(array(
	'NAME'	   		=> $member['member_name'],
	'GUILD'	   		=> $member['guildname'], 
	'REGION'   		=> $member['region'], 
	'REALM'	   		=> $member['realm'],  

	'RAIDVAL'       => $member['member_raid_value'],
	'TIMEBONUS'     => $member['member_time_bonus'],
	'ZEROSUM'      	=> $member['member_zerosum_bonus'],
	'EARNED'        => $member['member_earned'],
	'RAIDDECAY'		=> $member['member_raid_decay'],
	'NETEARNED'		=> (float) $member['member_earned'] - $member['member_raid_decay'],

	'ADJUSTMENT'    => $member['member_adjustment'],
	'EP'    		=> $member['ep'],
	'SPENT'         => $member['member_spent'],
	'ITEMDECAY'     => $member['member_item_decay'],
	'NETITEM'     	=> $member['member_spent'] - $member['member_item_decay'],

	'CURRENT'       => $member['member_current'],
	'C_CURRENT'       => ($member['member_current'] > 0) ? 'positive' : 'negative',
	'NETCURRENT'    => $member['member_current'] - $member['member_raid_decay'] + $member['member_item_decay'],
	'C_NETCURRENT'      => (($member['member_current'] - $member['member_raid_decay'] + $member['member_item_decay']) > 0) ? 'positive' : 'negative',
	'GP'     		=> $member['gp'],
	'ER'     		=> $member['er'],

	
	'MEMBER_LEVEL'    => $member['member_level'],
	'MEMBER_DKPID'    => $dkp_id,
	'MEMBER_DKPNAME'  => $member['member_dkpname'],
	'MEMBER_RACE'     => $member['member_race'],
	'MEMBER_CLASS'    => $member['member_class'],
	'COLORCODE'       => $member['colorcode'],
	'CLASS_IMAGE'       => (strlen($member['classimage']) > 1) ? $phpbb_root_path . "images/class_images/" . $member['classimage'] . ".png" : '', 
	'S_CLASS_IMAGE_EXISTS' =>  (strlen($member['classimage']) > 1) ? true : false,
	'RACE_IMAGE'       => (strlen($member['raceimage']) > 1) ? $phpbb_root_path . "images/race_images/" . $member['raceimage'] . ".png" : '', 
	'S_RACE_IMAGE_EXISTS' =>  (strlen($member['raceimage']) > 1) ? true : false,

	'MEMBER_RANK'     => $member['member_rank'],

	'S_SHOWZS' 		=> ($config['bbdkp_zerosum'] == '1') ? true : false, 
	'S_SHOWDECAY' 	=> ($config['bbdkp_decay'] == '1') ? true : false,
	'S_SHOWEPGP' 	=> ($config['bbdkp_epgp'] == '1') ? true : false,
 	'S_SHOWTIME' 	=> ($config['bbdkp_timebased'] == '1') ? true : false,
	
	'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAMEID . '=' . $member_id .'&amp;' . URI_DKPSYS . '=' . $dkp_id)
));

// Output page
page_header($user->lang['MEMBER']);
$template->set_filenames(array(
	'body' => 'dkp/viewmember.html')
);
page_footer();

?>
