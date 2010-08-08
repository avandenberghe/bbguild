<?php
/**
* This class manages Bossprogress 
*  
* @package bbDkp.acp
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* $Id$
* 
* Powered by bbdkp © 2009 The bbDkp Project Team
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDkp project.
* 
* Thanks for sz3 for Bossprogress.
* Integrated by: ippeh
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
	
	function main($id, $mode) 
	{
	    global $db, $user, $template, $config, $phpEx;   
        $user->add_lang(array('mods/dkp_admin'));   
		$link = '<br /><a href="'.append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=zoneprogress") . 
			'"><h3>'.$user->lang['RETURN_DKPINDEX'].'</h3></a>';
		
        switch ($mode)
		{
			case 'bossprogress':
				
				// list of bosses
				$this->page_title =  $user->lang['bossbase'] . ' - '. $user->lang['bb_am_conf'];
    			$this->tpl_name = 'dkp/acp_'. $mode;
				break;	
		
			case 'zoneprogress':	
				// page layout
				$submit = (isset($_POST['bpsave'])) ? true : false;
				$delete = (isset($_GET['step'])) ? true : false;
				
				// updating 
				if ($submit)
				{
					// global config
				  	set_config ('bbdkp_bp_hidenewzone',  ( isset($_POST['hidenewzone']) ) ? 1 : 0);
				  	set_config ('bbdkp_bp_hidenonkilled', ( isset($_POST['hidenewboss']) ) ? 1 : 0 );
				  	set_config ('bbdkp_bp_zonephoto',  request_var('headertype', 0), 0); 				  	
				  	set_config ('bbdkp_bp_zoneprogress', ( isset($_POST['showzone']) ) ? 1 : 0);
				  	set_config ('bbdkp_bp_zonestyle',  request_var('style', 0));

				  	$sql = "select imagename, showzone, completedate from " . ZONEBASE ;
					$result = $db->sql_query($sql);
                	$row = $db->sql_fetchrow($result); 
	                while ( $row = $db->sql_fetchrow($result) )
	                {
	                	$zonenames[] = $row['imagename']; 
	                	$completedate[$row['imagename']] = $row['completedate']; 
	                }
	                $db->sql_freeresult($result);

	                $newzonenames =  request_var('zonename', array( ''=> ''));
					$newzonenameshorts =  request_var('zonenameshort', array( ''=> ''));
					$newzoneimagenames =  request_var('zoneimagename', array( ''=> ''));
					$newzonewebids =  request_var('zonewebid', array( ''=> ''));
					$newzonedds =  request_var('zonedd', array( ''=> ''));
					$newzonemms =  request_var('zonemm', array( ''=> ''));
					$newzoneyys =  request_var('zoneyy', array( ''=> ''));
	                foreach ($zonenames as $key => $zonename) 
					{
						$month = (int) $newzonemms[$zonename];
						$month = min (12, max(1,$month) );
						$day = (int) $newzonedds[$zonename];
						$year = (int) $newzoneyys[$zonename]; 
						$year = min ( date("Y") ,  max(1999,$year) );
						if (checkdate($month, $day, $year))
						{
							// correct date found
							$data = array(
								'newzonename' => $newzonenames[$zonename],
								'newzonenameshort' => $newzonenameshorts[$zonename],
								'newzoneimagename' => $newzoneimagenames[$zonename], 
								'completed' => isset ( $_POST ['zonecompleted'][$zonename] ) ? 1 : 0,		
								'completedate' =>  mktime(0, 0, 0, $month, $day, $year), 
								'webid' => $newzonewebids[$zonename],
								'showzone' => isset ( $_POST ['zoneshow'][$zonename] ) ? 1 : 0,
							);
						}
						else 
						{
							// incorrect completedate entered so this completedate not be updated with user provided date
							$data= array(
								'zonename' => $newzonenames[$zonename],
								'zonename_short' => $newzonenameshorts[$zonename],
								'imagename' => $newzoneimagenames[$zonename], 
								'completed' => isset ( $_POST ['zonecompleted'][$zonename] ) ? 1 : 0,		
								'completedate' => $completedate[$zonename], 
								'webid' => $newzonewebids[$zonename],
								'showzone' => isset ( $_POST ['zoneshow'][$zonename] ) ? 1 : 0,
							);
							
							
						}
						
						// And doing an update query 
						$sql = 'UPDATE ' . ZONEBASE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE imagename = "'. $db->sql_escape($zonename) .'"';
						$db->sql_query($sql);
					}
					
					trigger_error($user->lang['BP_SAVED'] . $link, E_USER_NOTICE);
					
				}
				
				if ($delete)
				{
					//
					//delete a zone
					//
					$imagename = request_var('imagename', ''); 
					if (confirm_box(true))
					{
					$sql = ' SELECT id FROM ' . ZONEBASE . " where imagename = '" . $db->sql_escape($imagename) . "' ";	
			        $result = $db->sql_query($sql);
			        while ( $row = $db->sql_fetchrow($result) )
			        {
			        	$zoneid = $row['id']; 
			        }
			        $db->sql_freeresult ( $result);
        
					$sql = 'DELETE FROM ' . ZONEBASE . ' WHERE id = ' . $zoneid;  
					$db->sql_query($sql);	
					$sql = 'DELETE FROM ' . BOSSBASE . ' WHERE zoneid=' . $zoneid;  
					$db->sql_query($sql);
					trigger_error($user->lang['RP_ZONEDEL'] . $link, E_USER_NOTICE);
					
							
					}
					else
					{
						$s_hidden_fields = build_hidden_fields(array(
							'delete'		=> true,
							'imagename'		=> $imagename,
							)
						);
		
						confirm_box(false, sprintf($user->lang['RP_ZONEDELETCONFIRM'], $imagename), $s_hidden_fields);
					}
					
				}
				
				$bp_styles['0'] = $user->lang['BP_STYLE_BP'];
				$bp_styles['1'] = $user->lang['BP_STYLE_BPS'];
				$bp_styles['2'] = $user->lang['BP_STYLE_RP3R'];
				foreach ($bp_styles as $value => $option) 
				{
					$template->assign_block_vars('style_row', array (
							'VALUE' => $value,
							'SELECTED' => ($config['bbdkp_bp_zonestyle'] == $value) ? ' selected="selected"' : '',
							'OPTION' => $option
						)
					);
				}
				
				$arrvals = array (
					'F_CONFIG' 			 => append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=zoneprogress"),
					'BP_HIDENEWZONE'	 => ($config['bbdkp_bp_hidenewzone'] == 1) ? ' checked="checked"' : '',
					'BP_HIDENONKIBOSS' 	 => ($config['bbdkp_bp_hidenonkilled'] == 1) ? ' checked="checked"' : '',
					'BP_SHOWSB' 		 => ($config['bbdkp_bp_zoneprogress'] == 1) ? ' checked="checked"' : '',
					'HEADER_SEL_SEPIA'   => ($config['bbdkp_bp_zonephoto'] == 0 ) ? ' selected="selected"' : '',
					'HEADER_SEL_BLUE'    => ($config['bbdkp_bp_zonephoto'] == 1 ) ? ' selected="selected"' : '',
					'HEADER_SEL_NONE'    => ($config['bbdkp_bp_zonephoto'] == 2 ) ? ' selected="selected"' : '',
				);
				$template->assign_vars($arrvals);
				
				// list of zones
				$sql_array = array(
				    'SELECT'    => 	' zonename, zonename_short, imagename, completed, completedate, webid, showzone  ', 
				 
				    'FROM'      => array(
				        ZONEBASE 	=> 'z',
				    	),
					'ORDER_BY'	=> $current_order['sql'],
				    	
				    );
				    
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result); 
                while ( $row = $db->sql_fetchrow($result) )
                {
                    $template->assign_block_vars('gamezone', array(
	                    'ZONE_NAME' 		=> $row['zonename']  ,
	                    'ZONE_NAME_SHORT' 	=> $row['zonename_short']  ,
	                    'ZONE_IMAGENAME' 	=> $row['imagename']  ,
	                    'ZONE_WEBID' 		=> $row['webid']  ,
	                    'ZONE_COMPLETED' 	=> ($row['completed'] == 1) ? ' checked="checked"' : '',
	                  
	                    'ZONE_DD' => ($row['completedate'] == 0) ? ' ' : date('d', $row['completedate'])  ,
                    	'ZONE_MM' => ($row['completedate'] == 0) ? ' ' : date('m', $row['completedate'])  ,
                    	'ZONE_YY' => ($row['completedate'] == 0) ? ' ' : date('y', $row['completedate'])  ,
                                        
	                    'ZONE_SHOW'   	=> ($row['showzone'] == 1) ? ' checked="checked"' : '',
                    	'U_DELETE' 		=> append_sid("index.$phpEx", "i=dkp_bossprogress&amp;mode=zoneprogress&amp;step=delete&amp;imagename={$row['imagename']}")  ,  
                    ));
                }
                $db->sql_freeresult($result);

				$this->page_title =  $user->lang['RP_BP'] . ' - '. $user->lang['RP_BP_CONF'];
				$this->tpl_name = 'dkp/acp_zoneprogress';
				break;		
		}
				
								
	}

}
?>
