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
$user->add_lang(array('mods/dkp_common'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

$user->add_lang(array('mods/bossbase_general'));
$user->add_lang(array('mods/bossbase_' . $config['bbdkp_default_game']));
include($phpbb_root_path . 'includes/bbdkp/bossprogress/extfunc.' . $phpEx);
$bb_config = bb_get_bossprogress_config();
$bb_pzone = bb_get_parse_zones();
$bb_pboss = bb_get_parse_bosses();
$bzone = bb_get_zonebossarray();

require ($phpbb_root_path . 'includes/bbdkp/bossprogress/bp_functions.' . $phpEx);
$bp_all_config = bp_get_config();

$visiblezone = bp_get_visible_bzone($bzone, $bp_all_config);

switch ($bb_config['source']) 
{
    case 'database':
      	$data = bp_init_data_array($bzone);
	    $data = bp_fetch_raidinfo($visiblezone, $data, $bb_config, $bb_pzone, $bb_pboss);
	break;
    case 'offsets':
        $bb_boffs = bb_get_boss_offsets();
	    $bb_zoffs = bb_get_zone_offsets();
	    foreach($bzone as $zone => $bosses)
	    {
		    $data[$zone]['firstzd'] = $bb_zoffs[$zone]['fd'];
		    $data[$zone]['lastzd'] = $bb_zoffs[$zone]['ld'];
		    $data[$zone]['zonecount'] = $bb_zoffs[$zone]['co'];		
		    foreach($bosses as $boss)
		    {
			    $data[$zone]['bosses'][$boss]['firstkd'] = $bb_boffs[$boss]['fd'];
			    $data[$zone]['bosses'][$boss]['lastkd']  = $bb_boffs[$boss]['ld'];
			    $data[$zone]['bosses'][$boss]['bosscount'] = $bb_boffs[$boss]['co'];		
		    }
	    }
        break;
    case 'both':
    	$bb_boffs = bb_get_boss_offsets();
    	$bb_zoffs = bb_get_zone_offsets();
    	foreach($bzone as $zone => $bosses)
    	{
    		$data[$zone]['firstzd'] = $bb_zoffs[$zone]['fd'];
    		$data[$zone]['lastzd'] = $bb_zoffs[$zone]['ld'];
    		$data[$zone]['zonecount'] = $bb_zoffs[$zone]['co'];		
    		foreach($bosses as $boss)
    		{
    			$data[$zone]['bosses'][$boss]['firstkd'] = $bb_boffs[$boss]['fd'];
    			$data[$zone]['bosses'][$boss]['lastkd'] = $bb_boffs[$boss]['ld'];
    			$data[$zone]['bosses'][$boss]['bosscount'] = $bb_boffs[$boss]['co'];		
    		}
    	}
    	$data = bp_fetch_raidinfo($visiblezone, $data, $bb_config, $bb_pzone, $bb_pboss);	
    break;
}

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
    'U_LISTITEMS'         => append_sid("{$phpbb_root_path}listitems.$phpEx"),  
   	'U_LISTITEMHIST'      => append_sid("{$phpbb_root_path}listitems.$phpEx?&amp;page=history"),
    'U_LISTMEMBERS'       => append_sid("{$phpbb_root_path}listmembers.$phpEx"),
   	'U_LISTEVENTS'        => append_sid("{$phpbb_root_path}listevents.$phpEx"),
   	'U_LISTRAIDS'         => append_sid("{$phpbb_root_path}listraids.$phpEx"),
   	'U_VIEWITEM'          => append_sid("{$phpbb_root_path}viewitem.$phpEx"),
   	'U_BP'                => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
   	'U_ROSTER'            => append_sid("{$phpbb_root_path}roster.$phpEx"),
   	'U_ABOUT'             => append_sid("{$phpbb_root_path}about.$phpEx"),
 	'U_STATS'             => append_sid("{$phpbb_root_path}stats.$phpEx"),
    'U_VIEWNEWS'          => append_sid("{$phpbb_root_path}viewnews.$phpEx"),
));


// Output page
page_header($user->lang['MENU_BOSS']);

$template->set_filenames(array('body' => 'dkp/bossprogress.html'));

page_footer();
?>
