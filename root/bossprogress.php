<?php
/**
 * Bossprogress bbDKP
 * 
 * @package bbDKP
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
	'SELECT' => 'z.id as zoneid, l.name as zonename, l.name_short as zonename_short, z.completed, z.completedate, z.imagename ', 
	'FROM' => array (
		ZONEBASE 		=> 'z' , 
		BB_LANGUAGE 	=> 'l',		
		), 
	'WHERE' => " z.showzone = 1 
				AND l.attribute_id = z.id AND l.attribute='zone' AND l.language= '" . $config['bbdkp_lang'] ."' 
				AND z.game= '" . $config['bbdkp_default_game'] . "'",
	'ORDER_BY' => 'z.sequence desc ' 
);

if ($config['bbdkp_bp_hidenewzone'] == '1' )
{
	$sql_array['WHERE'] .= ' AND z.completed = 1 '; 
}

$zones = array(); 
$boss = array();
$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query ( $sql );
$i = 0;
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$bpshow = true;
	$zones [$i] = array (
		'zoneid' 		 => $row ['zoneid'], 
		'zonename' 		 => $row ['zonename'], 
		'zonename_short' => $row ['zonename_short'], 
		'completed'      => $row ['completed'], 
		'completedate'   => $row ['completedate'], 
		'zoneimage'      => $row ['imagename'], 
	);
	
	$sql_array = array (
		'SELECT' => 'b1.name as bossname, b.id, b1.name_short as bossname_short, 
					 b.imagename, b.type, b.killed, b.webid, b.killdate, b.counter ', 
		'FROM' => array (
			ZONEBASE 	=> 'z' , 
			BOSSBASE 	=> 'b', 
			BB_LANGUAGE => 'b1'
			), 
		'WHERE' => ' b.zoneid = z.id and b.showboss = 1 and z.id = ' . $row ['zoneid'] . "
				AND b1.attribute_id = b.id AND b1.attribute='boss'
				AND b1.language= '" . $config['bbdkp_lang'] ."' 
				AND z.game= '" . $config['bbdkp_default_game'] . "'",
		'ORDER_BY' => 'z.sequence desc , b.id asc ' 
	);
	
	// skip new bosses?
	if ($config['bbdkp_bp_hidenonkilled'] == 1 )
	{
		$sql_array['WHERE'] .= ' AND b.killed = 1 '; 
	}

	$boss = array();
	
	$bosskill=0;
	$j = 0;
	$sql2 = $db->sql_build_query ( 'SELECT', $sql_array );
	$result2 = $db->sql_query ( $sql2 );
	while ( $row2 = $db->sql_fetchrow ( $result2 ) )
	{
		$boss[$j] = array( 
			'bossid' => $row2 ['id'], 
			'bossname' => $row2 ['bossname'], 
			'imagename' => $row2 ['imagename'],
			'bossname_short' => $row2 ['bossname_short'], 
			'killdate' => $row2 ['killdate'],
		    'counter' => $row2['counter'], 
			'killed' => $row2 ['killed'], 
			'url' => sprintf($user->lang[strtoupper($config['bbdkp_default_game']).'_BASEURL'], $row2 ['webid']),
		 ); 
		 
		 if ($row2 ['killed'] == 1)
		 {
			$bosskill++;	 
		 }
		 $j++;
	}
	// array with boss info
	$zones[$i]['bosses'] = (array) $boss;
	// total nr of bosses in zone 
	$zones[$i]['bosscount'] = $j;
	// number of bosskills
	$zones[$i]['bosskills'] = $bosskill; 
	// percentage done
	$zones[$i]['completed'] = ($j>0) ? round($bosskill/$j)*100 : 0;
 	unset ($boss);
	$i++;
	$db->sql_freeresult ($result2);
}
$db->sql_freeresult ($result);	

foreach($zones as $key => $zone)
{
	
	// set the background / progress zone image
	switch ($config['bbdkp_bp_zonephoto'])
	{
		case 0: //sepia
			$progrimg = $phpbb_root_path. 'images/bossprogress/' . $config['bbdkp_default_game'] . '/zones/normal/' . $zone['zoneimage'] . '.jpg';
			$background = $phpbb_root_path. 'images/bossprogress/' . $config['bbdkp_default_game'] . '/zones/photo/' . $zone['zoneimage'] . '.jpg';
			break;
		case 1: //blackwhite
			$progrimg = $phpbb_root_path. 'images/bossprogress/' . $config['bbdkp_default_game'] . '/zones/normal/' . $zone['zoneimage'] . '.jpg';
			$background = $phpbb_root_path. 'images/bossprogress/' . $config['bbdkp_default_game'] . '/zones/sw/' . $zone['zoneimage'] . '.jpg';
			break;
		case 2:
			$progrimg = '';
			$background = $phpbb_root_path. 'images/bossprogress/' . $config['bbdkp_default_game'] . '/zones/normal/' . $zone['zoneimage'] . '.jpg';
	}
	
	// show zone stats
	if($config['bbdkp_bp_zoneprogress'] == 1)
	{
	    $zonestats = (( !empty($row['completedate']) ) ? date($config['bbdkp_date_format'], $row['completedate']) : ' ') . ' - ' . 
        $user->lang ['STATUS'] . ' ' . $zone['bosskills'] . '/' . $zone['bosscount'] . ' (' . $zone['completed'] . '%)'; 
	}
	
	//dump to template
	$template->assign_block_vars('zone', array(
			'ZONEPROGRESSIMG'	=> $progrimg, 
			'ZONEBACKIMG'		=> $background, 
			'ZONECOMPLETE'		=> $zone['completed'],
		    'ZONESTATS'			=> $zonestats,
	));
		
	//process bossinfo
	if ( array_key_exists('bosses', $zone  ))
	{
		foreach($zone['bosses'] as $key => $boss)
		{

			if (file_exists ( "{$phpbb_root_path}images/bossprogress/" . $config['bbdkp_default_game'] . '/bosses/' . $boss['imagename'].'.gif' )) 
            {
				if ($boss['killed'] == 0)
				{
					$bossimg="{$phpbb_root_path}images/bossprogress/" . $config['bbdkp_default_game'] . '/bosses/' . $boss['imagename'] . '_b.gif';  
				}
                else
                {
                	$bossimg="{$phpbb_root_path}images/bossprogress/" . $config['bbdkp_default_game'] . '/bosses/' . $boss['imagename'] . '.gif';  
				}
            } 
			else 
			{
				$bossimg ="{$phpbb_root_path}images/bossprogress/" . $config['bbdkp_default_game'] . "/bosses/turkey.gif"; 
			}

			$template->assign_block_vars('zone.boss', array(
				'LASTKILL'	    => (!empty($boss['killdate']) ) ? $user->lang ['LASTKILL'] . date($config['bbdkp_date_format'], $boss['killdate']) : ' ',              
				'BOSSCOUNTSTR'	=> $boss['counter'] > 0 ? ($user->lang ['BOSSKILLCOUNT'] . ' : ' . $boss['counter']) : '' ,  
			    'BOSSCOUNT'		=> max( (int) $boss['killed'], (int) $boss['counter']), 
				'BOSSLINK'		=> $boss['url'],  
			    'BOSSNAME'		=> $boss['bossname'],
			    'BOSSIMG'		=> $bossimg,
			    'BOSSIMGALT'	=> $boss['bossname'], 
			));
		}
		
	}

}


 $template->assign_vars(array(
	'S_STYLE'  => $config['bbdkp_bp_zonestyle']
   ));

$template->assign_block_vars('dkpnavlinks', array(
		'DKPPAGE' => $user->lang['MENU_BOSS'],
		'U_DKPPAGE' => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
	));
	
// Output page
page_header($user->lang['MENU_BOSS']);
$template->set_filenames(array('body' => 'dkp/bossprogress.html'));
page_footer();
?>