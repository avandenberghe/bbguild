<?php

/* bossprogress block
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 

 MOD Title: Raid Progress Block (uses bossprogress) a bbdkp addon.
 MOD Version: 0.5
 MOD Author: Sajaki, Teksonic (admin@pinnaclewow.com)
 MOD Description: A raid progress block for the bbdkp system, 
 utilizing the bossprogress addon to automatically
 track raid progress and display it in a quick convient block 
 on your main site page.    

 Author Notes: 
 Live demo at http://www.pinnaclewow.com/test.php
 Much respect to the BBDKP group and all the loyal community.
 Original idea for this mod by: Ordon

 MOD History:
* 2010-08-30 ver 0.5 
   - completely recoded for bbdkp 1.1.2
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

if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
$bpshow = false;
$user->add_lang ( array ('mods/dkp_admin' ) );

$sql_array = array(
   'SELECT'    => 	' z.id as zoneid, 
   					  l.name as zonename, 
   					  l.name_short as zonename_short, 
   					  z.completed ',
   'FROM'      => array(
		ZONEBASE 		=> 'z',
		BB_LANGUAGE 	=> 'l',
			),
'WHERE'		=> " z.id = l.attribute_id 
AND l.attribute='zone' AND l.language= '" . $config['bbdkp_lang'] ."'
AND z.showzoneportal = 1  
AND game= '" . $config['bbdkp_default_game'] . "'",
'ORDER_BY'	=> 'z.sequence desc, z.id desc ',
   );
				    
$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query ( $sql );
$i = 0;
$zones = array();
$row = $db->sql_fetchrow ( $result );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$bpshow = true;
	$zones [$i] = array (
		'zoneid' => $row ['zoneid'], 
		'zonename' => $row ['zonename'], 
		'zonename_short' => $row ['zonename_short'], 
		'completed' => $row ['completed'] );
	
	$sql_array = array(
	    'SELECT'    => 	' b.id, l.name as bossname, l.name_short as bossname_short, b.imagename, 
	    b.webid, b.killed, b.killdate, b.counter, b.showboss, b.zoneid  ', 
	    'FROM'      => array(
	        BOSSBASE 		=> 'b',
            BB_LANGUAGE 	=> 'l',
	    	),
	    'WHERE'		=> 'b.zoneid = ' . $row ['zoneid'] . " AND b.id = l.attribute_id 
	    AND b.showboss=1 
	    AND l.attribute='boss' AND l.language= '" . $config['bbdkp_lang'] ."'",
		'ORDER_BY'	=> 'b.zoneid, b.id ASC ',
	    );	
	    
	
	// skip new bosses?
	if ($config['bbdkp_bp_hidenonkilled'] == 1 )
	{
		$sql_array['WHERE'] .= ' AND b.killed = 1 '; 
	}
	
	$bosskill=0;
	$boss = array();
	$j = 0;
	$sql2 = $db->sql_build_query ( 'SELECT', $sql_array );
	$result2 = $db->sql_query ( $sql2 );
	while ( $row2 = $db->sql_fetchrow ( $result2 ) )
	{
		$boss[$j] = array( 
			'bossid' 		 => $row2 ['id'], 
			'bossname' 		 => $row2 ['bossname'], 
			'bossname_short' => $row2 ['bossname_short'], 
			'killed'  		 => $row2 ['killed'], 
			'url' 			 => $user->lang[strtoupper($config['bbdkp_default_game']).'_BASEURL'] . $row2 ['webid']
		 ); 
		 if ($row2 ['killed'] == 1)
		 {
			$bosskill++;	 
		 }
		 $j++;
	}
	
	$zones[$i]['bosses'] = (array) $boss; 
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

foreach($zones as $key => $zone)
{
	$template->assign_block_vars('zone', array(
			'ZONEID'  		=> $zone['zoneid'],
			'CSSCLASS'  	=> $zone['cssclass'],
			'ZONENAME' 		=> $zone['zonename'],
			'BOSSKILLS'		=> $zone['bosskills'], 
			'BOSSCOUNT'		=> $zone['bosscount'],
			'COMPLETED' 	=> $zone['completed'],
	));

	foreach($zone['bosses'] as $key => $boss)
	{
		$a = 1;
		$template->assign_block_vars('zone.boss', array(
				'BOSSNAME'  	=> $boss['bossname'],
				'KILLED'  		=> $boss['killed'],
				'BOSSURL'  		=> $boss['url'],
		));
	}
}

$template->assign_vars ( array (
		'GAME' => $config ['bbdkp_default_game'], 
		'S_BPSHOW' => $bpshow ));


?>