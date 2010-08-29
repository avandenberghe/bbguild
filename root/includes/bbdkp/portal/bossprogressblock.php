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

$sql_array = array (
	'SELECT' => 'z.id as zoneid, z.zonename, zonename_short, z.completed   ', 
	'FROM' => array (
		ZONEBASE => 'z' , 
		), 
	'WHERE' => 'z.showzoneportal = 0 and  length(z.zonename) >0  ', 
	'ORDER_BY' => 'z.sequence desc ' 
);
$sql = $db->sql_build_query ( 'SELECT', $sql_array );
$result = $db->sql_query ( $sql );
$i = 0;
$row = $db->sql_fetchrow ( $result );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$bpshow = true;
	$zone [$i] = array (
		'zoneid' => $row ['zoneid'], 
		'zonename' => $row ['zonename'], 
		'zonename_short' => $row ['zonename_short'], 
		'completed' => $row ['completed'] );
	
	$sql_array = array (
		'SELECT' => 'b.bossname, b.id, b.bossname_short, b.killed ', 
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
			'bossname_short' => $row2 ['bossname_short'], 
			'killed' => $row2 ['killed']
		 ); 
		 if ($row2 ['killed'] == 1)
		 {
			$bosskill++;	 
		 }
		 $j++;
	}
	$zone[$i]['bosses'] = $boss; 
	$zone[$i]['bosscount'] = $j; 
	$zone[$i]['completed'] = ($j>0) ? round($bosskill/$j)*100 : 0;
	unset ($boss);
	$i++;
	$db->sql_freeresult ($result2);
}
$db->sql_freeresult ($result);	

/*
$template->assign_block_vars ( 'zone.boss', array (
	'BOSS_NAME' => $row2 ['bossname'], 
	'BOSS_NAME_SHORT' => $row2 ['bossname_short'], 
	'BOSS_WEBID' => $row2 ['webid'], 
	'BOSS_KILLED' => ($row2 ['killed'] == 1) ? ' checked="checked"' : '', 
	'BOSS_DD' => ($row2 ['killdate'] == 0) ? ' ' : date ( 'd', $row2 ['killdate'] ), 
	'BOSS_MM' => ($row2 ['killdate'] == 0) ? ' ' : date ( 'm', $row2 ['killdate'] ), 
	'BOSS_YY' => ($row2 ['killdate'] == 0) ? ' ' : date ( 'y', $row2 ['killdate'] ), 
	'BOSS_SHOW' => ($row2 ['bosszone'] == 1) ? ' checked="checked"' : '' ) );
*/

/** global template vars **/
$template->assign_vars ( array (
'GAME' => $config ['bbdkp_default_game'], 
	'S_BPSHOW' => $bpshow ));

/**  end bossprogress block ***/

?>