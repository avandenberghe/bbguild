<?php
/**
* This class manages Bossprogress 
*  
* Powered by bbdkp © 2009 The bbDkp Project Team
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDkp project.
* 
* Thanks for sz3 for Bossprogress.
* Integrated by: ippeh
* 
* @package bbDkp.acp
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* $Id$
* 
*  
**/


/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

class acp_dkp_bossprogress extends bbDkp_Admin
{
	var $u_action;
	
	function bossprogress_update_config($fieldname,$insertvalue) 
	{
    	global $db;
		$sql = 'UPDATE ' . BOSSBASE_CONFIG . ' SET config_value="'. $db->sql_escape($insertvalue).'" WHERE config_name= "'. $db->sql_escape($fieldname) .'"';
		$db->sql_query($sql);
	}
	
	function bb_update_offsets($name, $firstdate, $lastdate, $counter)
	{
	    global $db;
	    $firstdate = intval($firstdate);
        $lastdate = intval($lastdate);
        $counter = intval($counter);
		$sql = "UPDATE  " . BOSSBASE_OFFSETS . " SET fdate='". $firstdate ."', ldate='". $lastdate ."', counter='". $counter ."' WHERE name='". $name ."';";
		$db->sql_query($sql);

	}	
	
	// Get configuration from database
	function bossprogress_get_config() 
	{
    	global $db;
    	$sql = 'SELECT * FROM ' . BOSSBASE_CONFIG . ' ORDER BY config_name';
    	if (!($settings_result = $db->sql_query($sql))) 
    	{
            trigger_error('Could not obtain bossprogress configuration data ' , E_USER_WARNING);       
    	}
    	
    	while($row = $db->sql_fetchrow($settings_result))
    	{
    		$conf[$row['config_name']] = $row['config_value'];
	    }    	
    	return $conf;
	}

	function main($id, $mode) 
	{
		global $db, $user, $auth, $template, $sid, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang(array('mods/dkp_admin'));
		$user->add_lang(array('mods/dkp_common'));
		$user->add_lang(array('mods/bossbase_general'));
        $user->add_lang(array('mods/bossbase_' . $config['bbdkp_default_game']));
		$link = '<br /><a href="'.append_sid("index.$phpEx", "i=dkp&amp;mode=mainpage") . '"><h3>'.$user->lang['RETURN_DKPINDEX'].'</h3></a>'; 
		switch ($mode)
		{
			case 'bossbase':
		
    			require ($phpbb_root_path . 'includes/bbdkp/bossprogress/extfunc.'. $phpEx);
    			$bzone = bb_get_zonebossarray(); //extfunc
    			$submit = (isset($_POST['bpsaveconfig'])) ? true : false;
    			
    			if ($submit)
    			{
    				// global config
    				$this->bossprogress_update_config('source', request_var('source', '')); 
    				$this->bossprogress_update_config('zoneInfo', request_var('zoneInfo', '')); 
    				$this->bossprogress_update_config('bossInfo', request_var('bossInfo', ''));
    				$this->bossprogress_update_config('noteDelim', request_var('notedelim', ''));
    				$this->bossprogress_update_config('nameDelim', request_var('namedelim', ''));
    				foreach ($bzone as $zoneid => $bosslist)
    				{
    					$this->bossprogress_update_config('pz_'.$zoneid, request_var('pz_'.$zoneid, ''));
    					
    					foreach ($bosslist as $bossid)
    					{
    						$this->bossprogress_update_config('pb_'.$bossid, request_var('pb_'.$bossid, ''));
    					}
    				}
    				trigger_error('Settings saved' . $link, E_USER_NOTICE);
    			}
    			
    			$row = bb_get_bossprogress_config();
    			$pzrow = bb_get_parse_zones();
    			$pbrow = bb_get_parse_bosses();
    			
    			$arrvals = array (
    				'CREDITS' => 'Integrated in bbDKP by Ippeh.<br />Original mod made by <a href="http://forums.eqdkp.com/index.php?showtopic=9214&pid=50176">sz3 for EqDkp</a>',
    				'F_CONFIG' => append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=bossbase"),
    				'BP_NOTEDELIM' => $row['noteDelim'],
    				'BP_NAMEDELIM' => $row['nameDelim'],
    				
    				'L_TITLE' => $user->lang['bossbase'],
    				'L_EXPLAIN' => $user->lang['bossbase_explain'],
    				'L_GENERAL' => $user->lang['bb_al_general'],
    				'L_NOTEDELIM' => $user->lang['bb_al_delimRNO'],
    				'L_NAMEDELIM' => $user->lang['bb_al_delimRNA'],
    				'L_PINFO' => $user->lang['bb_al_parseInfo'],
    				'L_SUBMIT' => $user->lang['bb_al_submit'],
    				'L_ZONEINFO' => $user->lang['bb_al_zoneInfo'],
    				'L_BOSSINFO' => $user->lang['bb_al_bossInfo'],
    				'L_RNAME' => $user->lang['bb_ao_rname'],
    				'L_RNOTE' => $user->lang['bb_ao_rnote'],
    				'L_SOURCE' => $user->lang['bb_al_source'],
    
    				'ZONEINFO_SEL_RNAME'    => ( $row['zoneInfo'] == "rname" ) ? ' selected="selected"' : '',
    				'ZONEINFO_SEL_RNOTE'    => ( $row['zoneInfo'] == "rnote" ) ? ' selected="selected"' : '',
    				'BOSSINFO_SEL_RNAME'    => ( $row['bossInfo'] == "rname" ) ? ' selected="selected"' : '',
    				'BOSSINFO_SEL_RNOTE'    => ( $row['bossInfo'] == "rnote" ) ? ' selected="selected"' : '',	
    			);
    			$template->assign_vars($arrvals);
    			
    			$bb_source['database'] = $user->lang['bb_source_db'];
    			$bb_source['offsets'] = $user->lang['bb_source_offs'];
    			$bb_source['both'] = $user->lang['bb_source_both'];
    			
    			foreach ($bb_source as $value => $option) 
    			{
    				$template->assign_block_vars('source_row', array (
    					'VALUE' => $value,
    					'SELECTED' => ($row['source'] == $value) ? ' selected="selected"' : '',
    					'OPTION' => $option
    					)
    				);
    			}
    			$zbcode = null;
        		foreach ($bzone as $zoneid => $bosslist)
    			{
        			$template->assign_block_vars('gamezone', array(
        			    'ZONE_NAME'	=> $user->lang[$zoneid]['long'],
        			    'PARSE_ZONE' => $user->lang['bb_al_parse'].$user->lang[$zoneid]['long'], 
        			    'PARSE_ZONE_TXTNAME' => 'pz_' . $zoneid, 
        			    'PARSE_ZONE_TXTVAL' => $pzrow['pz_'.$zoneid],
        			));
        			foreach ($bosslist as $bossid)
					{
            			$template->assign_block_vars('gamezone.boss', array(
            			    'PARSE_BOSS' => $user->lang['bb_al_parse'].$user->lang[$bossid]['long'], 
            			    'PARSE_BOSS_TXTNAME' => 'pb_' . $bossid, 
            			    'PARSE_BOSS_TXTVAL' => $pbrow['pb_'.$bossid],
            			));
					}
    			}
    			$this->page_title =  $user->lang['bossbase'] . ' - '. $user->lang['bb_am_conf'];
    			$this->tpl_name = 'dkp/acp_'. $mode;

			break;	
			
			
			case 'bossbase_offset':
			    //
			    // manual scoring table
    			//
			    require ($phpbb_root_path .'includes/bbdkp/bossprogress/extfunc.'. $phpEx);
    			$bzone = bb_get_zonebossarray();
    			
    			$submit = (isset($_POST['bpsavebu'])) ? true : false;
    			
    			if ($submit)
    			{
    				foreach ($bzone as $zoneid => $bosslist)
    				{
    					$fdate = mktime(0,0,0, request_var('fdm_'.$zoneid, 0) , request_var('fdd_'.$zoneid, 0), request_var('fdY_'.$zoneid, 0));
    					$ldate = mktime(0,0,0, request_var('ldm_'.$zoneid, 0), request_var('ldd_'.$zoneid, 0), request_var('ldY_'.$zoneid, 0));
    					$this->bb_update_offsets( $zoneid, $fdate, $ldate, request_var('co_'.$zoneid, 0));
    			
    					foreach ($bosslist as $bossid)
    					{
    						$fdate = mktime(0,0,0, request_var('fdm_'.$bossid, 0), request_var('fdd_'.$bossid, 0), request_var('fdY_'.$bossid, 0));
    						$ldate = mktime(0,0,0, request_var('ldm_'.$bossid, 0), request_var('ldd_'.$bossid, 0), request_var('ldY_'.$bossid, 0));
    						$this->bb_update_offsets($bossid, $fdate, $ldate, request_var('co_'.$bossid, 0));
    					}
    				}
    				trigger_error('Offset dates saved' . $link, E_USER_NOTICE);
    			}
    			
    			$offsets = bb_get_all_offsets();
    			
    			$arrvals = array (
        			'L_MODVERSION' => ' ',
        			'L_TITLE' => $user->lang['bossbase_offsets'],
    				'L_EXPLAIN' => $user->lang['bossbase_offsets_explain'], 
    				'CREDITS' => 'Integrated in bbDKP by Ippeh.<br />Original mod made by <a href="http://forums.eqdkp.com/index.php?showtopic=9214&pid=50176">sz3 for EqDkp</a>',
    				'F_CONFIG'       => append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=bossbase_offset"),
    				'L_OFFSET_INFO'  => $user->lang['bb_ol_dateFormat'],
    				'L_SUBMIT'       => $user->lang['bb_ol_submit']
    			);
    			
    			//Output
    			$template->assign_vars($arrvals);
    			
    			$zbcode = null;
    			foreach ($bzone as $zoneid => $bosslist)
    			{
    				
    			    $template->assign_block_vars('gamezone', array(
        			    'L_ZNAME'	=> $user->lang['bb_ol_in'],
    			        'L_FD'	=> $user->lang['bb_ol_fd'],
    			        'L_LD'	=> $user->lang['bb_ol_ld'],
    			    	'L_CO'	=> $user->lang['bb_ol_co'],
        			    'ZONE_NAME'	=> $user->lang[$zoneid]['long'],
    			        'TFDD' => 'fdd_' . $zoneid, 
    			        'TFDM' => 'fdm_' . $zoneid,
    			    	'TFDY' => 'fdY_' . $zoneid,
    			        'TLDD' => 'ldd_' . $zoneid, 
    			        'TLDM' => 'ldm_' . $zoneid,
    			    	'TLDY' => 'ldY_' . $zoneid,
    			    	'FDD' => strftime('%d',$offsets[$zoneid]['fd']), 
    			        'FDM' => strftime('%m',$offsets[$zoneid]['fd']), 
    			    	'FDY' => strftime('%Y',$offsets[$zoneid]['fd']),
    			        'LDD' => strftime('%d',$offsets[$zoneid]['ld']), 
    			        'LDM' => strftime('%m',$offsets[$zoneid]['ld']),
    			    	'LDY' => strftime('%Y',$offsets[$zoneid]['ld']),
    			        'TCO' => 'co_' . $zoneid,
    			        'CO' => $offsets[$zoneid]['counter'], 
        			));
        			
        			foreach ($bosslist as $bossid)
    				{
        			    $template->assign_block_vars('gamezone.boss', array(
            			    'BOSS_NAME'	=> $user->lang[$bossid]['long'],
        			        'TFDD' => 'fdd_' . $bossid, 
        			        'TFDM' => 'fdm_' . $bossid,
        			    	'TFDY' => 'fdY_' . $bossid,
        			        'TLDD' => 'ldd_' . $bossid, 
        			        'TLDM' => 'ldm_' . $bossid,
        			    	'TLDY' => 'ldY_' . $bossid,
        			    	'FDD' => strftime('%d',$offsets[$bossid]['fd']), 
        			        'FDM' => strftime('%m',$offsets[$bossid]['fd']), 
        			    	'FDY' => strftime('%Y',$offsets[$bossid]['fd']),
        			        'LDD' => strftime('%d',$offsets[$bossid]['ld']), 
        			        'LDM' => strftime('%m',$offsets[$bossid]['ld']),
        			    	'LDY' => strftime('%Y',$offsets[$bossid]['ld']),
        			        'TCO' => 'co_' . $bossid,
        			        'CO' => $offsets[$bossid]['counter'], 
            			));
    				}
    			}
    			
    			$this->page_title =  $user->lang['bossbase'] . ' - '. $user->lang['bb_am_offs'];
    			$this->tpl_name = 'dkp/acp_'. $mode;
			break;	
		
			case 'bossprogress':	
			    //
			    // Layout of progress page
    			//
    			require ($phpbb_root_path .'includes/bbdkp/bossprogress/extfunc.' . $phpEx);
				$bzone = bb_get_zonebossarray();		
				$submit = (isset($_POST['bpsavebu'])) ? true : false;
				
				// Saving
				if ($submit)
				{
					// global config
				  	$this->bossprogress_update_config('zhiType', request_var('Headertype', 0) );
				  	$this->bossprogress_update_config('style', request_var('style', 0));
                   $this->bossprogress_update_config('showSB', ( isset($_POST['showSB']) ) ? 1 : 0);
					$this->bossprogress_update_config('dynZone', ( isset($_POST['dynloc']) ) ? 1 : 0);
					$this->bossprogress_update_config('dynBoss', ( isset($_POST['dynboss']) ) ? 1 : 0);
				
					foreach ($bzone as $zoneid => $bosslist)
					{
						$this->bossprogress_update_config('sz_'.$zoneid, ( isset($_POST['sz_'.$zoneid]) ) ? '1' : '0');
					}
					trigger_error('Bossprogress Settings saved' . $link);
				}
				
				$row = $this->bossprogress_get_config();
				$bp_styles['0'] = $user->lang['bp_style_bp'];
				$bp_styles['1'] = $user->lang['bp_style_bps'];
				$bp_styles['2'] = $user->lang['bp_style_rp3r'];
				
				foreach ($bp_styles as $value => $option) 
				{
					$template->assign_block_vars('style_row', array (
							'VALUE' => $value,
							'SELECTED' => ($row['style'] == $value) ? ' selected="selected"' : '',
							'OPTION' => $option
						)
					);
				}
				
				$arrvals = array (
					'F_CONFIG' => append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=bossprogress"),
					'BP_DYNLOC' => ($row['dynZone'] == 1) ? ' checked="checked"' : '',
					'BP_DYNBOSS' => ($row['dynBoss'] == 1) ? ' checked="checked"' : '',
					'BP_SHOWSB' => ($row['showSB'] == 1) ? ' checked="checked"' : '',
					
					// Language
					'L_TITLE' => $user->lang['bossprogress'],
    				'L_EXPLAIN' => $user->lang['bossprogress_explain'], 
				
				    'L_SHOWZONES' => $user->lang['showzones'], 
					'L_GENERAL' => $user->lang['opt_general'],
					'L_DYNLOC'      => $user->lang['opt_dynloc'],
					'L_DYNBOSS'    => $user->lang['opt_dynboss'],
					'L_HEADERTYPE' => $user->lang['opt_zhiType'],
					'L_SUBMIT' => "Save",
					'L_SHOWSB' => $user->lang['opt_showSB'],
					'L_STYLE' => $user->lang['opt_style'],	
				
					'L_OLDPHOTO' => $user->lang['zhi_jitter'],
					'L_BW' => $user->lang['zhi_bw'],
					'L_NONE' => $user->lang['zhi_none'],
					'HEADER_SEL_OLDPHOTO'    => ( $row['zhiType'] == "0" ) ? ' selected="selected"' : '',
					'HEADER_SEL_BW'    => ( $row['zhiType'] == "1" ) ? ' selected="selected"' : '',
					'HEADER_SEL_NONE'    => ( $row['zhiType'] == "2" ) ? ' selected="selected"' : '',
				);
				$template->assign_vars($arrvals);
				
				foreach ($bzone as $zoneid => $bosslist)
    			{
        			$template->assign_block_vars('gamezone', array(
        			    'ZONE_NAME'	=> $user->lang['opt_showzone'] . $user->lang[$zoneid]['long'],        			    
        			    'BOSS_SHOWN' => 'sz_' . $zoneid, 
        				'BOSS_CHK' => ($row['sz_'.$zoneid] == 1) ? ' checked="checked"' : '',
        			));
    			}
    			
				$this->page_title =  $user->lang['bb_am_bp_conf'] . ' - '. $user->lang['bb_am_conf'];
				$this->tpl_name = 'dkp/acp_'. $mode;

			break;		
		}
	}
}
?>
