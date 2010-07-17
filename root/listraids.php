<?php
/**
 * list of Raids
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

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->add_lang(array('mods/dkp_common'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}
$user->setup();

/**** begin dkpsys pulldown  ****/	
$query_by_pool = false;
$defaultpool = 99; 

$dkpvalues[0] = $user->lang['ALL']; 
$dkpvalues[1] = '--------'; 
$sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default FROM ' . DKPSYS_TABLE;
$result = $db->sql_query ( $sql );
$index = 3;
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$dkpvalues[$index]['id'] = $row ['dkpsys_id']; 
	$dkpvalues[$index]['text'] = $row ['dkpsys_name']; 
	if (strtoupper ( $row ['dkpsys_default'] ) == 'Y')
	{
		$defaultpool = $row ['dkpsys_id'];
	}
	$index +=1;
}
$db->sql_freeresult ( $result );

$dkpsys_id = 0; 
if(isset( $_POST ['pool']) or isset( $_POST ['getdksysid']) or isset ( $_GET [URI_DKPSYS] ) )
{
	if (isset( $_POST ['pool']) )
	{
		$pulldownval = request_var('pool',  $user->lang['ALL']);
		if(is_numeric($pulldownval))
		{
			$query_by_pool = true;
			$dkpsys_id = intval($pulldownval); 	
		}
	}
	if (isset( $_POST ['getdksysid']) )
	{
		$query_by_pool = true;
		$dkpsys_id = request_var('getdksysid', 0); 
		
	}
	if (isset ( $_GET [URI_DKPSYS] ))
	{
		$query_by_pool = true;
		$dkpsys_id = request_var(URI_DKPSYS, 0); 
	}
}
else 
{
	$query_by_pool = true;
	$dkpsys_id = $defaultpool; 
}


foreach ( $dkpvalues as $key => $value )
{
	if(!is_array($value))
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value, 
			'SELECTED' => ($value == $dkpsys_id && $value != '--------') ? ' selected="selected"' : '',
			'DISABLED' => ($value == '--------' ) ? ' disabled="disabled"' : '',  
			'OPTION' => $value, 
		));
	}
	else 
	{
		$template->assign_block_vars ( 'pool_row', array (
			'VALUE' => $value['id'], 
			'SELECTED' => ($dkpsys_id == $value['id']) ? ' selected="selected"' : '', 
			'OPTION' => $value['text'], 
		));
		
	}
}


$query_by_pool = ($dkpsys_id != 0) ? true : false;
/**** end dkpsys pulldown  ****/	 
	 
$start = request_var('start', 0);   
$u_list_raids =  append_sid("{$phpbb_root_path}listraids.$phpEx");

$total_raids=0;

// get sort order 
$sort_order = array
(
    0 => array('raid_date desc', 'raid_date'),
    1 => array('dkpsys_name', 'dkpsys_name desc'),
    2 => array('raid_name', 'raid_name desc'),
    3 => array('raid_note', 'raid_note desc'),
    4 => array('raid_value desc', 'raid_value')
);
 
$current_order = switch_order($sort_order);
//get total nr of raids 
$sql = 'SELECT count(*) as numraids FROM ' . RAIDS_TABLE;
if ($query_by_pool)
{
    $sql .= ' where raid_dkpid = '. $dkpsys_id . ' ';
}
$result = $db->sql_query($sql);
$total_raids = (int) $db->sql_fetchfield('numraids');
$db->sql_freeresult ( $result );

// how many raids per page
$raidlines = $config['bbdkp_user_rlimit'] ;

if ($query_by_pool)
{
    $pagination = generate_pagination( append_sid("{$phpbb_root_path}listraids.$phpEx" , URI_DKPSYS . '=' . 
    $dkpsys_id . '&amp;o='.  $current_order['uri']['current'] ), $total_raids, $config['bbdkp_user_rlimit'], $start, true);
     
}
else 
{
    $pagination = generate_pagination( append_sid("{$phpbb_root_path}listraids.$phpEx" , URI_DKPSYS .  '=All&amp;o='.  
    $current_order['uri']['current'] ), $total_raids, $config['bbdkp_user_rlimit'], $start, true);
}

$sql_array = array(
    'SELECT'    => 	'b.dkpsys_name, b.dkpsys_id, a.raid_id, a.raid_name, a.raid_date, a.raid_note, a.raid_value ', 
 
    'FROM'      => array(
        RAIDS_TABLE 	=> 'a',
        DKPSYS_TABLE 		=> 'b',
    	),
 
    'WHERE'     =>  'a.raid_dkpid = b.dkpsys_id ' ,
    'ORDER_BY'	=>  $current_order['sql'], 
);

if ($query_by_pool)
{
    $sql_array['WHERE'] .= ' AND a.raid_dkpid = '. $dkpsys_id . ' ';
}
$sql .= ' ORDER BY ' . $current_order['sql']; 

$sql = $db->sql_build_query('SELECT', $sql_array);

$raids_result = $db->sql_query_limit($sql, $raidlines , $start);
	 
if ( !$raids_result)
{
   trigger_error ( $user->lang['ERROR_INVALID_RAID'] , E_USER_WARNING );
    
}

while ( $row = $db->sql_fetchrow($raids_result) )
{
    $template->assign_block_vars('raids_row', array(
        'DATE' => ( !empty($row['raid_date']) ) ? date($config['bbdkp_date_format'], $row['raid_date']) : '&nbsp;',
        'U_VIEW_RAID' => append_sid("{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '='.$row['raid_id']),
		'POOL' => ( !empty($row['dkpsys_name']) ) ? $row['dkpsys_name'] : '&lt;<i>Not Found</i>&gt;',
    	'NAME' => ( !empty($row['raid_name']) ) ? $row['raid_name'] : '&lt;<i>Not Found</i>&gt;',
    	'NOTE' => ( !empty($row['raid_note']) ) ? $row['raid_note'] : '&nbsp;',
        'VALUE' => $row['raid_value'])
    );
}

$sortlink = array();
for ($i=0; $i<=4; $i++)
{
    if ($query_by_pool)
    {
        $sortlink[$i] = append_sid($phpbb_root_path . 'listraids.'.$phpEx, 'o=' . $current_order['uri'][$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=' . $dkpsys_id ); 
    }
    else 
    {
        $sortlink[$i] = append_sid($phpbb_root_path . 'listraids.'.$phpEx, 'o=' . $current_order['uri'][$i] . '&amp;start=' . $start . '&amp;' . URI_DKPSYS . '=All'  ); 
    }
}

$template->assign_block_vars('dkpnavlinks', array(
'DKPPAGE' 		=> $user->lang['MENU_RAIDS'],
'U_DKPPAGE' 	=> $u_list_raids,
));


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

    'O_DATE'  => $sortlink[0],
    'O_POOL'  => $sortlink[1],
    'O_NAME'  => $sortlink[2],
    'O_NOTE'  => $sortlink[3],
    'O_VALUE' => $sortlink[4],
    
    'U_LIST_RAIDS' => $u_list_raids , 
    'LISTRAIDS_FOOTCOUNT' => sprintf($user->lang['LISTRAIDS_FOOTCOUNT'], $total_raids, $config['bbdkp_user_rlimit']),

    'START' => $start,
    'RAID_PAGINATION' => $pagination
    )
);

// Output page
page_header($user->lang['RAIDS']);

$template->set_filenames(array(
	'body' => 'dkp/listraids.html')
);

page_footer();

?>
