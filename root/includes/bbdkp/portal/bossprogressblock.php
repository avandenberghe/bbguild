<?php
 
/* bossprogress block
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 

 MOD Title: Raid Progress Block (uses bossprogress) a bbdkp addon.
 MOD Author: Teksonic (admin@pinnaclewow.com)
 MOD Description: A raid progress block for the bbdkp system, 
 utilizing the bossprogress addon to automatically
 track raid progress and display it in a quick convient block 
 on your main site page.    
 MOD Version: 0.3
 Author Notes: 
 Live demo at http://www.pinnaclewow.com/test.php
 Much respect to the BBDKP group and all the loyal community.
 Original idea for this mod by: Ordon
 MOD History:
* 2010-01-03 ver 0.4
  - moved to block plugin 
* 2009-01-09 ver 0.3 Sajaki
  - rewritten to phpbb codingstyle, moved bp_block.php to menu.php
  - now works independently of bosspogress style (2/3 columns)
*  2008-04-17 ver 0.2 Teksonic
  - Code clean up in bp_block.php
*  2008-03-20 ver 0.1 Teksonic 
  - first revision
*/

if (!defined('IN_PHPBB'))
{
   exit;
}
	
	$user->add_lang(array('mods/bossbase_general'));
    $user->add_lang(array('mods/bossbase_' . $config['bbdkp_default_game']));
	require( $phpbb_root_path . 'includes/bbdkp/bossprogress/extfunc.' . $phpEx);
	
	$bb_config = bb_get_bossprogress_config();
	$bb_pzone = bb_get_parse_zones();
	$bb_pboss = bb_get_parse_bosses();
	$bzone = bb_get_zonebossarray();
	
	require ($phpbb_root_path . 'includes/bbdkp/bossprogress/bp_functions.' . $phpEx);
	$bp_all_config = bp_get_config();
	
	// get visible zones only
	$sbzone = array ();
	if ($bzone != null) 
	{
	    $bpshow = true;
    	    
    	foreach ( $bzone as $zone => $bosses ) 
    	{
    		if ($bp_all_config ['sz_' . $zone] == '1') 
    		{
    			$sbzone [$zone] = $bosses;
    		}
    	}
    	
    	//Get killdata from database
    	switch ($bb_config['source']) 
    	{
    	    case 'database':
    			$data = bp_init_data_array($bzone);
    			$data = bp_fetch_raidinfo($sbzone, $data, $bb_config, $bb_pzone, $bb_pboss);
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
    	    	$data = bp_fetch_raidinfo($sbzone, $data, $bb_config, $bb_pzone, $bb_pboss);	
    	    break;
    	}
    	
    	$progressCount=0;
    	foreach ( $sbzone as $zone => $bosses ) 
    	{  
    		// loop visible zones
    		$loc_killed = 0;
    		foreach ( $data [$zone] ['bosses'] as $boss ) 
    		{  // loop bosses
    			if ($boss ['bosscount'] > 0)
    			{	
    			    $loc_killed ++;
    			}
    			
    		}
    		
    		if ((! $bp_all_config ['dynZone']) or ($loc_killed > 0)) 
    		{
    			$loc_completed = round ( $loc_killed / count ( $bosses ) * 100 );
    			$totalbosscount = count ($bosses);
    			
    			if ($loc_completed == 0) 
    			{
    				$cssclass = 'bpprogress00';
    			} 
    			elseif ($loc_completed <= 25) 
    			{
    				$cssclass = 'bpprogress25';
    			} 
    			elseif ($loc_completed <= 50)
    			{
    				$cssclass = 'bpprogress50';
    			} 
    			elseif ($loc_completed <= 75) 
    			{
    				$cssclass = 'bpprogress75';
    			} 
    			elseif ($loc_completed <= 99) 
    			{
    				$cssclass = 'bpprogress99';
    			} 
    			elseif ($loc_completed == 100) 
    			{
    				$cssclass = 'bpprogress100';
    			} 
    			$template->assign_block_vars('boss', 
    			array(
    			'CSSCLASS'  => $cssclass,
    			'ZONE'  => '<b><a class="' . $cssclass . '" href="' . append_sid("{$phpbb_root_path}bossprogress.$phpEx#$zone") . '">' . $user->lang [$zone] ['short'] . '</a></b>',
    			'LOC_KILLED'	=> $loc_killed, 
    			'TOTAL_BCOUNT'	=> $totalbosscount,
    			'PERCOMP' 		=> $loc_completed,
    			'PROGRESSCOUNT' => $progressCount,
    			'LOCCOMPLETED' 	=> $loc_completed,
									
    			));
    		}
			$progressCount++;
    	}
	
	}
	else
    {
	     $bpshow = false;
    }


/** global template vars **/
$template->assign_vars(array(
		
	  'GAME' => $config['bbdkp_default_game'],
	  'S_BPSHOW' => $bpshow,
));

/**  end bossprogress block ***/


?>