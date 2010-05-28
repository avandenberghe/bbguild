<?php
/**
 * Dkp overview
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
if(!defined("EMED_BBDKP"))
{
    trigger_error('bbDkp is currently disabled.', E_USER_WARNING); 
}

include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

global $config;
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');
$user->add_lang(array('mods/dkp_common'));

/******************/
/* configuration  */
/******************/

$pool1 = 1; 
$pool2 = 2; 
$pool3 = 3; 

/******************/


$sql = 'SELECT class_id, class_name FROM ' . CLASS_TABLE . ' where class_id != 0 order by class_name';
$result = $db->sql_query($sql);
$classes = array();
while ( $row = $db->sql_fetchrow($result) )
{
    $class[$row['class_id']]    = $row['class_name'];
}

$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE ;
$result = $db->sql_query($sql);
while ( $row = $db->sql_fetchrow($result) )
{
  	$dkpsysidarray[$row['dkpsys_name']] = $row['dkpsys_id'];
    $dkpsysnamearray[$row['dkpsys_id']] = $row['dkpsys_name'];
}

$raid_count = 0;
$list_p1 = (isset($config['bbdkp_list_p1'])==true) ? $config['bbdkp_list_p1'] : 30;
$start_date = mktime(0, 0, 0, date('m'), date('d')- $list_p1, date('Y'));
$end_date = time();
$sql = 'SELECT count(*) as raidcount FROM ' . RAIDS_TABLE . ' WHERE (raid_date BETWEEN ' . $start_date . ' AND ' . $end_date . ') ';
$sql .= ' AND raid_dkpid = ' . $pool3; 
$result = $db->sql_query($sql);
$raid_count3 = (int) $db->sql_fetchfield('raidcount');
$db->sql_freeresult($result);

foreach ($class as $classid => $classname)
{
		$cssclass = $config['bbdkp_default_game'] . 'class'. $classid;
		
		switch ($classid)
		{
		    case 0:
		        // unknown
		        break;
		    case 1: //warrior
		        $imgpath = 'wow_Warrior (DPS)_small.gif';
		        break;
		    case 2: //rogue
		        $imgpath = 'wow_Rogue_small.gif';
		        break;
		    case 3://hunter
		        $imgpath = 'wow_Hunter_small.gif';
		        break;
		    case 4://paladin
		        $imgpath = 'wow_Hunter_small.gif';
		        break;
		    case 5://shaman
		        $imgpath = 'wow_Shaman (Ele)_small.gif';
		        break;
		    case 6://druid
		        $imgpath = 'wow_Druid (Resto)_small.gif';
		        break;
		    case 7://warlock
		        $imgpath = 'wow_Warlock_small.gif';
		        break;
		    case 8://mage
		        $imgpath = 'wow_Mage_small.gif';
		        break;
		    case 9://priest
		        $imgpath = 'wow_Priest (Holy)_small.gif';
		        break;
		    case 10://dk
		        $imgpath = 'wow_Death Knight (Blood)_small.gif';
		        break;
		}
		
		$template->assign_block_vars('class', array(
			'CLASSNAME' 	=> $classname,
			'CLASSIMGPATH' 	=> $imgpath,   
			'CSSCLASS' 		=> $config['bbdkp_default_game'] . 'class'. $classid, 
			)
		 );
		
		$sql = 'SELECT  a.member_name,  sum(a.member_current1) as P1,  sum(a.member_current2) as P2, sum(a.raidcount3) as R3 
		       from ( ' ; 
		$sql .= 'SELECT  l.member_name, m.member_status, 
			(if (m.member_dkpid = ' . $pool1 . ', m.member_earned-m.member_spent+m.member_adjustment , 0)) AS member_current1, 
			(if (m.member_dkpid = ' . $pool2 . ', m.member_earned-m.member_spent+m.member_adjustment , 0)) AS member_current2, 
		    (if (m.member_dkpid = ' . $pool3 . ', ifnull(rc1.raidcount,0)/1 * 100, 0 )) as raidcount3  ';
        $sql .= '  FROM 
            ' . MEMBER_DKP_TABLE 	. ' m, 
            ' . MEMBER_LIST_TABLE 	. ' l, 
            ' . MEMBER_RANKS_TABLE . ' r,  ';  
        
        $sql .= "( SELECT n.member_dkpid, n.member_id, rc.raidcount  ";
        $sql .= " FROM " . MEMBER_DKP_TABLE . " as n ";
        $sql .= " left join "; 
        $sql .= "(SELECT rd.raid_dkpid, ra.member_id, count(*) as raidcount";    
        $sql .= " FROM " . RAIDS_TABLE . " rd, " . RAID_ATTENDEES_TABLE . " ra ";
        $sql .= " WHERE  (ra.raid_id = rd.raid_id)  ";
        $sql .= " AND (rd.raid_date BETWEEN " . $start_date . " AND " . $end_date . ")";
        $sql .= " group by rd.raid_dkpid, ra.member_id) rc on n.member_id = rc.member_id and n.member_dkpid = rc.raid_dkpid ) rc1   ";
        
        $sql .= " WHERE m.member_id = rc1.member_id and m.member_dkpid = rc1.member_dkpid  ";
        
        $sql .= ' AND (m.member_id = l.member_id) 
                  AND ( l.member_class_id = ' . $classid . " )  
                  AND (r.rank_id = l.member_rank_id) 
                  AND (l.member_guild_id = r.guild_id) 
                  AND r.rank_hide = '0'" ;
        if ( $config['bbdkp_hide_inactive'] == '1' )
	    {
			$sql .= " AND m.member_status <> '0'";			
		}

		$sql .= " ) a  group by a.member_name ";
		
		$result2 = $db->sql_query($sql);

		while ( $dkprow = $db->sql_fetchrow($result2) )
		{
			//dkp data per class

		    $template->assign_block_vars( 'class.dkp_row' , 
			array(
			
			  
				
				'NAME'          => $dkprow['member_name'],
								   
				'U_VIEW_MEMBER1' => append_sid("{$phpbb_root_path}viewmember.$phpEx", '&amp;' . URI_NAME . '='.$dkprow['member_name'] . '&amp;' . URI_DKPSYS . '=' . $pool1) ,
				
				'CURRENT1'       => $dkprow['P1'],
								   
				'DKPCOLOUR1'		=> ($dkprow['P1'] >= 0) ? 
									'style="font-size :8pt; color: green; text-align: right;"' : 
									'style="font-size :8pt; color: red; text-align: right;"',	

				'U_VIEW_MEMBER2' => append_sid("{$phpbb_root_path}viewmember.$phpEx", '&amp;' . URI_NAME . '='.$dkprow['member_name'] . '&amp;' . URI_DKPSYS . '=' . $pool2) , 
				
				'CURRENT2'       => $dkprow['P2'],	
								   						   
				'DKPCOLOUR2'		=> ($dkprow['P2'] >= 0) ? 
									'style="font-size :8pt; color: green; text-align: right;"' : 
									'style="font-size :8pt; color: red; text-align: right;"',	
								   
				'U_VIEW_MEMBER3' => append_sid("{$phpbb_root_path}viewmember.$phpEx", '&amp;' . URI_NAME . '='.$dkprow['member_name'] . '&amp;' . URI_DKPSYS . '=' . $pool3) , 
				
				'ATTENDANCE3'    => round($dkprow['R3'],2),
							   
			)
		
			);
		}
		$db->sql_freeresult($result2);
}

 $template->assign_vars(array(
    'U_LISTITEMS'         => append_sid("{$phpbb_root_path}listitems.$phpEx"),  
   	'U_LISTITEMHIST'      => append_sid("{$phpbb_root_path}listitems.$phpEx?&amp;page=history"),
    'U_LISTMEMBERS'       => append_sid("{$phpbb_root_path}listmembers.$phpEx"),
   	'U_LISTEVENTS'       => append_sid("{$phpbb_root_path}listevents.$phpEx"),
   	'U_LISTRAIDS'         => append_sid("{$phpbb_root_path}listraids.$phpEx"),
   	'U_VIEWITEM'          => append_sid("{$phpbb_root_path}viewitem.$phpEx"),
   	'U_BP'                => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
   	'U_ROSTER'             => append_sid("{$phpbb_root_path}roster.$phpEx"),
   	'U_ABOUT'             => append_sid("{$phpbb_root_path}about.$phpEx"),
 	'U_STATS'             => append_sid("{$phpbb_root_path}stats.$phpEx"),
    'U_VIEWNEWS'          => append_sid("{$phpbb_root_path}viewnews.$phpEx"),

 	'F_MEMBERS' =>  append_sid("{$phpbb_root_path}listmembers.$phpEx"),
    'L_NAME'		=> $user->lang ['name'],
	'L_P1'		    => $dkpsysnamearray[$pool1], 
	'L_P2'		    => $dkpsysnamearray[$pool2],
	'L_R3'		    => $dkpsysnamearray[$pool3],   
 
 	'L_POOL'          => $user->lang['dkp_sys'],
    'L_NAME'          => $user->lang['name'],
    'L_CLASS'         => $user->lang['class'],
    'L_CURRENT'       => $user->lang['current'],
    'L_RAIDS_P1_DAYS' => sprintf($user->lang['raids_x_days'], $list_p1) ,
 )

);

$test = 'Dkp Overview';


// Output page
page_header($test);

$template->set_filenames(array(
	'body' => 'dkp/dkpoverview.html')
);

page_footer();

/*
 * 
 */
function percentage_raidcount($query_by_pool, $dkpsys_id, $start_date, $end_date, $member_name)
{
    global $db;
    
    
 	$sql2 = 'SELECT count(*) as raidcount FROM ' . RAIDS_TABLE . ' WHERE (raid_date BETWEEN ' . $start_date . ' AND ' . $end_date . ')';
	if ($query_by_pool == true)
	{
		$sql2 .= ' AND raid_dkpid = ' . $dkpsys_id; 
	}
 	 
    $raid_count = 0;
	$result = $db->sql_query($sql2);
	$raid_count = (int) $db->sql_fetchfield('raidcount');
	$db->sql_freeresult($result);
	
	$sql = 'SELECT COUNT(*) as raidcount FROM ' . RAIDS_TABLE . ' r, ' . RAID_ATTENDEES_TABLE . ' ra ';
	$sql .= " WHERE  (ra.raid_id = r.raid_id)
            AND (ra.member_name='" . $db->sql_escape($member_name) . "')
            AND (r.raid_date BETWEEN " . $start_date . ' AND ' . $end_date . ')';
	if ($query_by_pool == true)
	{
		$sql .= ' AND r.raid_dkpid = ' . $dkpsys_id; 
	}
	
	$individual_raid_count = 0;
    $result = $db->sql_query($sql);
    $individual_raid_count = (int) $db->sql_fetchfield('raidcount');
	$db->sql_freeresult($result);
    
    $percent_of_raids = ( $raid_count > 0 ) ? round(($individual_raid_count / $raid_count) * 100,2) : 0;

    return $percent_of_raids ; 
}


?>
