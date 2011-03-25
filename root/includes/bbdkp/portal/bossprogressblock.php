<?php

/* bossprogress block
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
*/
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
$bpshow = false;
$user->add_lang ( array ('mods/dkp_admin' ) );
if ($config['bbdkp_bp_hidenewzone'] == 1)
{
	// inner join to hide zones with no bosses killed
	$sql_array = array(
	   'SELECT'    => 	' z.id as zoneid, 
	   					  l.name as zonename, 
	   					  l.name_short as zonename_short, 
	   					  z.completed ',
	   'FROM'      => array(
			ZONEBASE 		=> 'z',
			BB_LANGUAGE 	=> 'l',
			BOSSBASE		=> 'b', 
				),
		'WHERE'		=> " z.id = l.attribute_id 
			AND l.attribute='zone' AND l.language= '" . $config['bbdkp_lang'] ."'
			AND z.showzoneportal = 1  
			AND z.game= '" . $config['bbdkp_default_game'] . "'
			AND b.zoneid = z.id and b.killed = 1",
		'GROUP_BY'	=> 'z.id, l.name, l.name_short, z.completed',
		'ORDER_BY'	=> 'z.sequence desc, z.id desc ',
	   );
}
else 
{
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
}

$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query ( $sql );
$i = 0;
$zones = array();
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
			'url' 			 => sprintf($user->lang[strtoupper($config['bbdkp_default_game']).'_BASEURL'], $row2 ['webid'])
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
	$zones[$i]['completed'] = ($j>0) ? round($bosskill/$j,2)*100 : 0;
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
		'S_SHOWPROGRESSBAR' => ($config['bbdkp_bp_blockshowprogressbar']==1 ? true:false) , 
		'GAME' => $config ['bbdkp_default_game'], 
		'S_BPSHOW' => $bpshow ));


?>