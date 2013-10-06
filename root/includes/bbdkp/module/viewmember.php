<?php
/**
 *
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

// Include the member class
if (!class_exists('\bbdkp\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/members/Members.$phpEx");
}
if (!class_exists('\bbdkp\Raids'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Raids.$phpEx");
}
if (!class_exists('\bbdkp\Points'))
{
	require("{$phpbb_root_path}includes/bbdkp/Points/Points.$phpEx");
}
if (!class_exists('\bbdkp\Loot'))
{
	require("{$phpbb_root_path}includes/bbdkp/loot/Loot.$phpEx");
}

if ( !isset($_GET[URI_NAMEID]) )
{
	trigger_error ($user->lang['MNOTFOUND']);
}
$member_id = request_var(URI_NAMEID, 0);
$member = new \bbdkp\Members($member_id);
$points = new \bbdkp\Points($member_id, $this->dkpsys_id);
$Raids = new \bbdkp\Raids();

/* Get attendance */
$range1 = $config['bbdkp_list_p1'];
$range2 = $config['bbdkp_list_p2'];
$range3 = $config['bbdkp_list_p3'];

$mc1 = $Raids->raidcount($this->dkpsys_id, $range1, $member_id, 0, false, $member->member_guild_id);
$mc2 = $Raids->raidcount($this->dkpsys_id, $range2, $member_id, 0, false, $member->member_guild_id);
$mc3 = $Raids->raidcount($this->dkpsys_id, $range3, $member_id, 0, false, $member->member_guild_id);
$mclife = $Raids->raidcount($this->dkpsys_id, 0, $member_id, 0, true, $member->member_guild_id);

$pc1	= $Raids->raidcount($this->dkpsys_id, $range1, $member_id, 1, false, $member->member_guild_id);
$pc2	= $Raids->raidcount($this->dkpsys_id, $range2, $member_id, 1, false, $member->member_guild_id);
$pc3	= $Raids->raidcount($this->dkpsys_id, $range3, $member_id, 1, false, $member->member_guild_id);
$pclife = $Raids->raidcount($this->dkpsys_id, 0, $member_id, 1, true, $member->member_guild_id);

$pct1 =	 ( $pc1 > 0 ) ? round(($mc1 / $pc1) * 100, 1) : 0;
$pct2 =	 ( $pc2 > 0 ) ? round(($mc2 / $pc2) * 100, 1) : 0;
$pct3 =	 ( $pc3 > 0 ) ? round(($mc3 / $pc3) * 100, 1) : 0;
$pctlife = ( $pclife > 0 ) ? round(($mclife / $pclife) * 100, 1) : 0;

/*get raids*/
$rstart = request_var('rstart',0) ;
$current_earned = $points->earned_net; 
$raids_result = $Raids->getRaids('r.raid_start DESC', $this->dkpsys_id, 0, $rstart, $member_id);
while ( $raid = $db->sql_fetchrow($raids_result))
{
	$template->assign_block_vars('raids_row', array(
			'DATE'			 => ( !empty($raid['raid_start']) ) ? date($config['bbdkp_date_format'], $raid['raid_start']) : '&nbsp;',
			'U_VIEW_RAID'	 => append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=viewraid&amp;' . URI_RAID . '='.$raid['raid_id']),
			'NAME'			 => $raid['event_name'],
			'NOTE'			 => ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
			'RAIDVAL'		 => $raid['raid_value'],
			'TIMEBONUS'		 => $raid['time_value'],
			'ZSBONUS'		 => $raid['zs_value'],
			'RAIDDECAY'		 => $raid['raiddecay'],
			'EARNED'		 => $raid['net_earned'],
			'CURRENT_EARNED' => sprintf("%.2f", $current_earned)
	));
	$current_earned = $current_earned - $raid['net_earned'];
}

/** loot history **/
$istart = request_var('istart', 0);
$current_spent = 0; 
$loot = new \bbdkp\Loot();
$lootdetails = $loot->GetAllLoot( ' i.item_date DESC ', $this->dkpsys_id,0, $istart, $member_id ); 
while ( $item = $db->sql_fetchrow($lootdetails))
{
	if ($this->bbtips == true)
	{
		if ($item['item_gameid'] == 'wow' )
		{
			$item_name = '<strong>' . $this->bbtips->parse('[itemdkp]' . $item['item_gameid']	 . '[/itemdkp]') . '</strong>' ;
		}
		else
		{
			$item_name = '<strong>' . $this->bbtips->parse ( '[itemdkp]' . $item ['item_name'] . '[/itemdkp]' . '</strong>'  );
		}
	}
	else
	{
		$item_name = '<strong>' . $item['item_name'] . '</strong>';
	}

	$template->assign_block_vars('items_row', array(
			'DATE'			=> ( !empty($item['item_date']) ) ? date($config['bbdkp_date_format'], $item['item_date']) : $item['item_date'] . '&nbsp;',
			'U_VIEW_ITEM'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewitem&amp;' . URI_ITEM . '=' . $item['item_id']),
			'U_VIEW_RAID'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewraid&amp;' . URI_RAID . '=' . $item['raid_id']),
			'NAME'			=> $item_name,
			'RAID'			=> ( !empty($item['event_name']) ) ? $item['event_name'] : '&lt;<i>Not Found</i>&gt;',
			'SPENT'			=> $item['item_value'],
			'CURRENT_SPENT' => sprintf("%.2f", $current_spent))
	);
	$current_spent -= $item['item_value'];
}
$db->sql_freeresult($lootdetails);

$sql_array = array(
		'SELECT'	=>	'count(*) as itemcount	',
		'FROM'		=> array(
				EVENTS_TABLE		=> 'e',
				RAIDS_TABLE			=> 'r',
				RAID_ITEMS_TABLE			=> 'i',
		),

		'WHERE'		=>	" e.event_id = r.event_id
		AND e.event_dkpid=" . (int) $this->dkpsys_id . '
		AND r.raid_id = i.raid_id
		AND i.member_id  = ' . $member_id,
);
$sql6 = $db->sql_build_query('SELECT', $sql_array);
$result6 = $db->sql_query($sql6);
$total_purchased_items = $db->sql_fetchfield('itemcount');
$db->sql_freeresult($result6);

$raidpag  = $this->generate_pagination2(append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewmember&amp;' . 
URI_DKPSYS.'='.$this->dkpsys_id. '&amp;' . URI_NAMEID. '='.$member_id. '&amp;istart=' .$istart),
$points->raidcount, $config ['bbdkp_user_rlimit'], $rstart, 1, 'rstart');

$itpag =   $this->generate_pagination2(append_sid("{$phpbb_root_path}dkp.$phpEx" ,'page=viewmember&amp;'.  URI_DKPSYS.'='.$this->dkpsys_id. '&amp;' . URI_NAMEID. '='.$member_id. '&amp;rstart='.$rstart ),
$total_purchased_items,	  $config ['bbdkp_user_ilimit'], $istart, 1 ,'istart');

$template->assign_vars(array(
		'RAID_PAGINATION'	  => $raidpag,
		'RSTART'			  => $rstart,
		'RAID_FOOTCOUNT'	  => sprintf($user->lang['VIEWMEMBER_RAID_FOOTCOUNT'], $points->raidcount, $config ['bbdkp_user_rlimit']),
		'ITEM_PAGINATION'	  => $itpag,
		'ISTART'			  => $istart,
		'ITEM_FOOTCOUNT'	  => sprintf($user->lang['VIEWMEMBER_ITEM_FOOTCOUNT'], $total_purchased_items, $config ['bbdkp_user_ilimit']),
		'ITEMS'				  => ( is_null($total_purchased_items) ) ? false : true,
));

//output
$url = append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewmember&amp;' . URI_NAMEID . '=' . $member_id .'&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id); 

$template->assign_vars(array(
		'S_DISPLAY_VIEWMEMBER' => true,
		'S_SHOWZS' 		   => ($config['bbdkp_zerosum'] == '1') ? true : false,
		'S_SHOWDECAY' 	   => ($config['bbdkp_decay'] == '1') ? true : false,
		'S_SHOWEPGP' 	   => ($config['bbdkp_epgp'] == '1') ? true : false,
		'S_SHOWTIME' 	   => ($config['bbdkp_timebased'] == '1') ? true : false,
		
		'NAME'	   		  => $member->member_name, 
		'GUILD'	   		  => $member->member_guild_name,
		'REGION'   		  => $member->member_region,
		'REALM'	   		  => $member->member_realm,
		'MEMBER_LEVEL'    => $member->member_level,
		'MEMBER_DKPID'    => $this->dkpsys_id,
		'MEMBER_DKPNAME'  => $this->dkpsys_name, 
		'MEMBER_RACE'     => $member->member_race,
		'MEMBER_CLASS'    => $member->member_class,
		'COLORCODE'       => $member->colorcode,
		'CLASS_IMAGE'       => $member->class_image,
		'S_CLASS_IMAGE_EXISTS' =>  (strlen($member->class_image) > 1) ? true : false,
		'RACE_IMAGE'       => $member->race_image,
		'S_RACE_IMAGE_EXISTS' =>  (strlen($member->race_image) > 1) ? true : false,
		'MEMBER_RANK'      => $member->member_rank_id,
		'U_VIEW_MEMBER'    => $url,
		'POINTNAME'		   => $config['bbdkp_dkp_name'],
));

if($config['bbdkp_epgp'] == '0')
{
	$template->assign_vars(array(
		'RAIDVAL'       => $points->raid_value,
		'TIMEBONUS'     => $points->time_bonus,
		'ZEROSUM'      	=> $points->zerosum_bonus,
		'RAIDDECAY'		=> $points->earned_decay,
		'EARNED'        => $points->total_earned ,
		'EARNED_NET'    => $points->earned_net,
			 
		'SPENT'         => $points->spent,
		'ITEMDECAY'     => $points->item_decay,
		'ITEMNET'		=> $points->item_net, 
		'CURRENT'       => $points->total,
		'C_CURRENT'       => ($points->total > 0) ? 'positive' : 'negative',
		'ADJUSTMENT'    => $points->adjustment,
		'C_ADJUSTMENT'  => ($points->adjustment > 0) ? 'positive' : 'negative',
		'ADJDECAY'		=> $points->adj_decay,
		'ADJNET'		=> $points->adj_net,
		'TOTAL_DECAY'	=> $points->total_decayed,
		'C_TOTAL_DECAY'	=> $points->total_decayed > 0 ? 'negative' : 'positive',
		'NETCURRENT'    => $points->total_net,
		'C_NETCURRENT'  => $points->total_net > 0 ? 'positive' : 'negative',
));	
}
elseif($config['bbdkp_epgp'] == '1')
{
	$template->assign_vars(array(
		'EP'    		=> $points->ep,
		'EPNET'			=> (float) $points->ep_net,
		'GP'     		=> $points->gp,
		'BGP'     		=> $config['bbdkp_basegp'],
		'GPNET'     	=> $points->gp_net,
		'PR'     		=> $points->pr,
		'PRNET'     	=> $points->pr_net,
	));
}

$template->assign_vars(array(
		'RAID_FOOTCOUNT'  => sprintf($user->lang['VIEWMEMBER_RAID_FOOTCOUNT'], $points->raidcount, $config ['bbdkp_user_rlimit']),
		'RAIDS_X1_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'], $range1),
		'RAIDS_X2_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'], $range2),
		'RAIDS_X3_DAYS'	  => sprintf($user->lang['RAIDS_X_DAYS'], $range3),
		'RAIDS_LIFETIME'  => sprintf($user->lang['RAIDS_LIFETIME'], date($config['bbdkp_date_format'], $points->firstraid), date($config['bbdkp_date_format'], $points->lastraid)),
		'C_RAIDS_X1_DAYS'  => $mc1 .'/'. $pc1 .' : '. $pct1,
		'C_RAIDS_X2_DAYS'  => $mc2 .'/'. $pc2 .' : '. $pct2,
		'C_RAIDS_X3_DAYS'  => $mc3 .'/'. $pc3 .' : '. $pct3,
		'C_RAIDS_LIFETIME' => $mclife .'/'. $pclife .' : '. $pctlife,
));

$navlinks_array = array(
	array(
		'DKPPAGE' 	=> $user->lang['MENU_STANDINGS'],
		'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=standings&amp;' . URI_DKPSYS . '=' . $this->dkpsys_id),
),

array(
		'DKPPAGE' => sprintf($user->lang['MENU_VIEWMEMBER'], $member->member_name) ,
		'U_DKPPAGE' => $url,
));

foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
			'DKPPAGE'	=> $name['DKPPAGE'],
			'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

$template->assign_vars(array(

));

// Output page
page_header($user->lang['MEMBER']);

?>
