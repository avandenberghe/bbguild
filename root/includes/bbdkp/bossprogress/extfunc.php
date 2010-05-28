<?php
/**
* EQdkp BossMeta
*
* @package bbDkp.includes
* @author sz3
* @version $Id$
* @copyright (c) 2006 sz3
* @copyright (c) 2009 bbDKP 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// Get configuration from database
function bb_get_bossprogress_config() 
{
global $db;
/*
    bossInfo  	rname
	dynBoss 	0
	dynZone 	0
	nameDelim 	-
	noteDelim 	,
	showSB 		1
	source 		database
	style 		0
	tables 		bbeqdkp
	zhiType 	0
	zoneInfo 	rname
 */
$sql = "SELECT * FROM " . BOSSBASE_CONFIG . " WHERE 
	config_name not like 'pb_%' 
	and config_name not like 'pz_%' 
	and config_name not like 'sz_%' 
	ORDER BY config_name"; 
	if (!($settings_result = $db->sql_query($sql))) 
	{
	    trigger_error('Could not obtain bossbase configuration data');
	}
	while($roww = $db->sql_fetchrow($settings_result)) 
	{
		$conf[$roww['config_name']] = $roww['config_value'];
	}	
	return $conf;
}

// Get boss parse configuration from database    
function bb_get_parse_bosses() 
{
global $db;
	$sql = "SELECT * FROM " . BOSSBASE_CONFIG . " 
	WHERE config_name 
	like 'pb_%' ORDER BY config_name";
	if (!($settings_result = $db->sql_query($sql))) 
	{  
		trigger_error('Could not obtain bossbase boss parse data');
	}  

	while($roww = $db->sql_fetchrow($settings_result)) 
	{
	    $conf[$roww['config_name']] = $roww['config_value'];
	}	
	return $conf;
}


// Get zone parse configuration from database
function bb_get_parse_zones() 
{
global $db;
	$sql = "SELECT * FROM " . BOSSBASE_CONFIG . " 
	WHERE config_name 
	like 'pz_%' ORDER BY config_name";
	if (!($settings_result = $db->sql_query($sql))) 
	{
        trigger_error('Could not obtain bossbase zone parse data');
	}

	while($roww = $db->sql_fetchrow($settings_result)) 
	{
		$conf[$roww['config_name']] = $roww['config_value'];
	}	
	return $conf;
}

// Get all offsets
function bb_get_all_offsets() 
{
global $db;
	$sql = 'SELECT * FROM ' . BOSSBASE_OFFSETS;
	if (!($settings_result = $db->sql_query($sql))) 
	{
	     trigger_error('Could not obtain bossbase offset data');
	}

	while($row = $db->sql_fetchrow($settings_result)) 
	{
	    $offs[$row['name']] = array (
	    'fd' => $row['fdate'], 
	    'ld' => $row['ldate'], 
	    'counter' => $row['counter']);
	}	
	return $offs;
}

// Get zone offsets
function bb_get_zone_offsets() 
{
global $db;
	$sql = 'SELECT name,fdate,ldate,counter FROM ' . BOSSBASE_OFFSETS;
	if (!($settings_result = $db->sql_query($sql))) 
	{
	    trigger_error('Could not obtain bossbase zone offset data');  
	}

	$bzone = bb_get_zonebossarray();

	while($row = $db->sql_fetchrow($settings_result)) 
	{
    	if (array_key_exists($row['name'], $bzone)) 
		    $offs[$row['name']] = array (
		    'fd' => $row['fdate'],
		    'ld' => $row['ldate'], 
		    'co' => $row['counter']);

	}	
	return $offs;
}

// Get boss offsets
function bb_get_boss_offsets() 
{
global $db;
	$sql = 'SELECT * FROM ' . BOSSBASE_OFFSETS;
	if (!($settings_result = $db->sql_query($sql))) 
	{
		trigger_error('Could not obtain bossbase boss offset data');
	}
	$bzone = bb_get_zonebossarray();
	while($row = $db->sql_fetchrow($settings_result)) 
	{
    	if (!(array_key_exists($row['name'], $bzone))) 
			$offs[$row['name']] = array (
			'fd' => $row['fdate'], 
			'ld' => $row['ldate'], 
			'co' => $row['counter']);
	}	
	return $offs;
}

// Get zone-boss array
function bb_get_zonebossarray()
{
   global $config, $phpEx, $phpbb_root_path;
   require($phpbb_root_path . 'includes/bbdkp/bossprogress/' . $config['bbdkp_default_game'] .  '_data.' .$phpEx);
   return $bzone;
}
?>
