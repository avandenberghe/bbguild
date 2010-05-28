<?php
/******************************
* EQdkp Bossprogress2
* @package bbDkp.includes
* @author sz3
*
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

define ( 'MAXDATE', mktime ( 0, 0, 0, 12, 31, 2014 ) );
define ( 'MINDATE', mktime ( 0, 0, 0, 12, 31, 1999 ) );

function bp_date2text($date) 
{
	global $user;
	if (($date == MAXDATE) or ($date ==  MINDATE )) 
	{
		return $user->lang ['never'];
	} 
	else 
	{
		return strftime ( $user->lang ['dateFormat'], $date );
	}
}

// Save or add values to the database.
function bp_update_config($fieldname, $insertvalue) 
{
    
	global $user, $sid, $db;
	$sql = "UPDATE " . BOSSBASE_CONFIG . " SET config_value='" . $db->sql_escape($insertvalue) . "' WHERE 
			config_name='" . $db->sql_escape($fieldname) . "';";
	$db->sql_query ( $sql );

}

// Get configuration from database
function bp_get_config() 
{
	global $db;
	$sql = 'SELECT * FROM ' . BOSSBASE_CONFIG . ' ORDER BY config_name';
	if (! ($settings_result = $db->sql_query ( $sql ))) 
	{
        trigger_error('Could not obtain bossprogress configuration data');
	}
	
	while ( $roww = $db->sql_fetchrow ( $settings_result ) ) 
	{
		$conf [$roww ['config_name']] = $roww ['config_value'];
	}
	
	return $conf;
}

function bp_get_visible_bzone($zones, $conf) 
{
	$szones = array ();
	foreach ( $zones as $zone => $bosses ) 
	{
		if ($conf ['sz_' . $zone] == '1') 
		{
			$szones [$zone] = $bosses;
		}
	}
	return $szones;
}

function bp_init_data_array($zones) 
{
    // fase1
	$zo_vc = null;
	foreach ( $zones as $thiszone => $bosses ) 
	{
		$data [$thiszone] ['zonecount'] = 0 + $zo_vc [$thiszone];
		$data [$thiszone] ['firstzd'] = MAXDATE;
		$data [$thiszone] ['lastzd'] = MINDATE;
		foreach ( $bosses as $boss ) 
		{
			$data [$thiszone] ['bosses'] [$boss] ['bosscount'] = 0;
			$data [$thiszone] ['bosses'] [$boss] ['firstkd'] = MAXDATE;
			$data [$thiszone] ['bosses'] [$boss] ['lastkd'] = MINDATE;
		}
	}
	
	return $data;
}

function bp_fetch_raidinfo($bzone, $data, $bb_conf, $bb_pzone, $bb_pboss) 
{
    // fase2
    // fetch data into initial array
	global $db;
	$delim = array ('rnote' => '/' . $bb_conf ['noteDelim'] . '/', 
					'rname' => '/' . $bb_conf ['nameDelim'] . '/' );
	
	$bossInfo = $bb_conf ['bossInfo']; // where to get boss from
	$zoneInfo = $bb_conf ['zoneInfo'];// where to get zone from
	
	$sql = "SELECT raid_name AS rname, raid_date AS rdate, raid_note AS rnote FROM " . RAIDS_TABLE ;
	$result = $db->sql_query ($sql);
	
	while ( $row=$db->sql_fetchrow ($result) )
	{
		foreach ( $bzone as $zone => $bosses ) 
		{
			// Get zoneinfo from current raid, standard delimiter either , or - 
			if ($delim [$zoneInfo] != "//") 
			{
				$zone_element = preg_split($delim [$zoneInfo], $row [$zoneInfo], - 1, PREG_SPLIT_NO_EMPTY );
			} 
			else 
			{
				$zone_element = array ($row [$zoneInfo] );
			}
			foreach ( $zone_element as $raid ) 
			{
			    /* this matches ', '   */
				$zparseList = preg_split ( "/\', \'/", trim ( $bb_pzone ['pz_' . $zone], "\' " ) );
				
								
				if (in_array ( trim ( $raid ), $zparseList )) 
				{
				    
				    if ( !isset($data [$zone] ['zonecount'])  )
				    {
				       $data [$zone] ['zonecount']=0;
				    }
				    $data [$zone] ['zonecount'] ++;

				    if ($data [$zone] ['firstzd'] > $row ["rdate"]) 
					{
						$data [$zone] ['firstzd'] = $row ["rdate"];
					}
					
					if ($data [$zone] ['lastzd'] < $row ["rdate"]) 
					{
						$data [$zone] ['lastzd'] = $row ["rdate"];
					}
				
				}
			}
			
			
			// Get bossinfo from current row
			if ($delim [$bossInfo] != "//") 
			{
				$boss_element = preg_split ( $delim [$bossInfo], $row [$bossInfo], - 1, PREG_SPLIT_NO_EMPTY );
			} 
			else 
			{
				$boss_element = array ($row [$bossInfo] );
			}
			
			foreach ( $boss_element as $raid ) 
			{
				foreach ( $bosses as $boss ) 
				{
					$bparseList = preg_split ( "/\', \'/", trim ( $bb_pboss ['pb_' . $boss], "\' " ) );
					if (in_array ( trim ( $raid ), $bparseList )) 
					{
					    if ( !isset($data [$zone] ['bosses'] [$boss] ['bosscount'])  )
				        {
				           $data [$zone] ['bosses'] [$boss] ['bosscount']=0;
				        }
				        $data [$zone] ['bosses'] [$boss] ['bosscount'] ++;
				        
						if ($data [$zone] ['bosses'] [$boss] ['firstkd'] > $row ["rdate"]) 
						{
							$data [$zone] ['bosses'] [$boss] ['firstkd'] = $row ["rdate"];
						}
						if ($data [$zone] ['bosses'] [$boss] ['lastkd'] < $row ["rdate"]) 
						{
							$data [$zone] ['bosses'] [$boss] ['lastkd'] = $row ["rdate"];
						}
					}
				}
			}
			
			
		}
	}
	
	$db->sql_freeresult ( $result );
	return $data;
}
?>
