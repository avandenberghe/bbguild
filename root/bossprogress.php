<?php
/**
 * Bossprogress bbDkp
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author sz3
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
$user->setup();
$user->add_lang(array('mods/dkp_common', 'mods/dkp_admin'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

$sql_array = array (
	'SELECT' => 'z.id as zoneid, z.zonename, zonename_short, z.completed   ', 
	'FROM' => array (
		ZONEBASE => 'z' , 
		), 
	'WHERE' => 'z.showzone = 1 and  length(z.zonename) > 0 ', 
	'ORDER_BY' => 'z.sequence desc ' 
);

$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query ( $sql );
$i = 0;
$row = $db->sql_fetchrow ( $result );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$bpshow = true;
	$zones [$i] = array (
		'zoneid' => $row ['zoneid'], 
		'zonename' => $row ['zonename'], 
		'zonename_short' => $row ['zonename_short'], 
		'completed' => $row ['completed'], 
		'completedate' => $row ['completedate'], 
		'zoneimage' => $row ['zoneimage'], 
	);
	
	$sql_array = array (
		'SELECT' => 'b.bossname, b.id, b.bossname_short, b.imagename, b.type, b.killed, b.webid, b.killdate ', 
		'FROM' => array (
			ZONEBASE => 'z' , 
			BOSSBASE => 'b'), 
		'WHERE' => ' b.zoneid = z.id and b.showboss=1 and z.id = ' . $row ['zoneid'], 
		'ORDER_BY' => 'z.sequence desc , b.id asc ' 
	);
	
	$bosskill=0;
	$j = 0;
	$sql2 = $db->sql_build_query ( 'SELECT', $sql_array );
	$result2 = $db->sql_query ( $sql2 );
	$row = $db->sql_fetchrow ( $result2 );
	
	while ( $row2 = $db->sql_fetchrow ( $result2 ) )
	{
		$boss[$j] = array( 
			'bossid' => $row2 ['id'], 
			'bossname' => $row2 ['bossname'], 
			'imagename' => $row2 ['imagename'],
			'bossname_short' => $row2 ['bossname_short'], 
			'killdate' => $row2 ['killdate'],
			'killed' => $row2 ['killed'], 
			'url' => $user->lang[strtoupper($config['bbdkp_default_game']).'_BASEURL'] . $row2 ['webid']
		 ); 
		 if ($row2 ['killed'] == 1)
		 {
			$bosskill++;	 
		 }
		 $j++;
	}
	$zones[$i]['bosses'] = $boss; 
	$zones[$i]['bosscount'] = $j;
	$zones[$i]['bosskills'] = $bosskill; 
	$zones[$i]['completed'] = ($j>0) ? round($bosskill/$j)*100 : 0;
  	if ((int)$zones[$i]['completed']  <= 0) 
 	{
		$zones[$i]['cssclass'] = 'bpprogress00';
  	}
	elseif ((int)$zones[$i]['completed'] <= 25) 
	{
		$zones[$i]['cssclass'] = 'bpprogress25';
	}
	elseif ((int)$zones[$i]['completed'] <= 50) 
	{
		$zones[$i]['cssclass'] = 'bpprogress50';
	}
	elseif ((int)$zones[$i]['completed'] <= 75) 
	{	
		$zones[$i]['cssclass'] = 'bpprogress75';
	}
	elseif ((int)$zones[$i]['completed'] <= 99) 
	{
		$zones[$i]['cssclass'] = 'bpprogress99';
	}
	elseif ((int)$zones[$i]['completed'] >= 100) 
	{
		$zones[$i]['cssclass'] = 'bpprogress100';
	}
	unset ($boss);
	$i++;
	$db->sql_freeresult ($result2);
}
$db->sql_freeresult ($result);	


foreach ( $visiblezone as $zone => $bosses )
{
	$loc_killed = 0;
	
	$zonename = preg_split ( "/\', \'/", trim ( $bp_all_config ['pz_' . $zone], "\' " ) );
	
	foreach ( $data [$zone]['bosses'] as $boss ) 
	{
		if ($boss ['bosscount'] > 0)
		$loc_killed ++;
	}
	
	if ((! $bp_all_config ['dynZone']) or ($loc_killed > 0)) 
	{
		$loc_completed = round ( $loc_killed / count ($bosses) * 100 );
		$game_id = $config ['bbdkp_default_game'];

		switch ($bp_all_config['zhiType'])
		{
			// set the background / progress zone image
			case 0: //sepia
				$progrimg = $phpbb_root_path. 'images/bossprogress/' . $game_id . '/zones/normal/' . $zone . '.jpg';
				$background = $phpbb_root_path. 'images/bossprogress/' . $game_id . '/zones/photo/' . $zone . '.jpg';
				break;
			case 1: //blackwhite
				$progrimg = $phpbb_root_path. 'images/bossprogress/' . $game_id . '/zones/normal/' . $zone . '.jpg';
				$background = $phpbb_root_path. 'images/bossprogress/' . $game_id . '/zones/sw/' . $zone . '.jpg';
				break;
			case 2:
				$progrimg = '';
				$background = $phpbb_root_path. 'images/bossprogress/' . $game_id . '/zones/normal/' . $zone . '.jpg';
		}
		// set the  zone image	
		
		//set the zone progress text
		if ($bp_all_config ['showSB'])
		{
		    $zonestats= $user->lang['FIRSTVISIT'] . bp_date2text ($data[$zone]['firstzd']) . ' -- ' . 
        	$user->lang ['LASTVISIT'] . bp_date2text ( $data [$zone] ['lastzd'] ) . ' -- ' . 
	        $user->lang ['STATUS'] . ' ' . $loc_killed . '/' . count($bosses) . ' (' . $loc_completed . '%)'; 
		}
		
		
		  
		$template->assign_block_vars('zone', array(
			'ZONENAME' 			=> $zonename[0],
			'ZONEPROGRESSIMG'	=> $progrimg, 
			'ZONEBACKIMG'		=> $background, 
			'ZONECOMPLETE'		=> $loc_completed,
		    'ZONESTATS'			=> $zonestats,
		));
	
		foreach ( $bosses as $boss ) 
		{
			if ((! $bp_all_config ['dynBoss']) or ($data [$zone]['bosses'][$boss]['bosscount'] > 0)) 
			{
			     // if not hide never killed bosses or bosskill >0
                	$firstkill_date = bp_date2text ( $data [$zone] ['bosses'] [$boss] ['firstkd'] );
                	$lastkill_date = bp_date2text ( $data [$zone] ['bosses'] [$boss] ['lastkd'] );
                	
                	
                	$bossinfo = null;
                   $game_id = $config ['bbdkp_default_game'];
                   $bosscount = $data [$zone] ['bosses'] [$boss] ['bosscount']; 
                	if (file_exists ( "{$phpbb_root_path}images/bossprogress/$game_id/bosses/$boss.gif" )) 
                	{
                    	if ($bosscount == 0)
                    	{
                    	    $bossimg="{$phpbb_root_path}images/bossprogress/$game_id/bosses/$boss" . '_b.gif';  
                    	}
                    	else
                    	{
                    	    $bossimg="{$phpbb_root_path}images/bossprogress/$game_id/bosses/$boss.gif";  
                    	}
                	} 
                	else 
                	{
                		$bossimg="$phpbb_root_path/images/bossprogress/$game_id/bosses/turkey.gif"; 
                	}
                	
                	//note we need htmlspecialchars there are ampersands in the database.
                	
                	$template->assign_block_vars('zone.boss', array(
            			'FIRSTKILL' 		=> $user->lang ['FIRSTKILL'] . $firstkill_date,
            			'LASTKILL'	        => $user->lang ['LASTKILL'] . $lastkill_date, 
            			'BOSSCOUNTSTR'		=> $user->lang ['BOSSKILLCOUNT'] . $bosscount , 
                	    'BOSSCOUNT'			=> $bosscount , 
            			'BOSSLINK'		    => $user->lang [strtoupper($game_id. '_baseurl')] . $user->lang [$boss] ['id'], 
            		    'BOSSNAME'			=> htmlspecialchars($user->lang [$boss] ['long'], ENT_QUOTES),
                	    'BOSSIMG'			=> $bossimg,
                	    'BOSSIMGALT'		=> $boss
            		));
			}
		}
	}
}


$template->assign_block_vars('dkpnavlinks', array(
		'DKPPAGE' => $user->lang['MENU_BOSS'],
		'U_DKPPAGE' => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
	));
	
$template->assign_vars(array(
	'S_STYLE' 			   => $bp_all_config['style'], 
));


// Output page
page_header($user->lang['MENU_BOSS']);

$template->set_filenames(array('body' => 'dkp/bossprogress.html'));

page_footer();
?>
