<?php
/**
 * View individual raid
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
$user->setup('viewforum');
$user->add_lang(array('mods/dkp_common'));
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}


if ( isset($_GET[URI_RAID])  )
{
	
	$raidid = request_var(URI_RAID,0); 
	
	// Check for valid raid
	$sql = 'SELECT raid_id, raid_dkpid, raid_name, raid_date, raid_note, raid_value, raid_added_by, raid_updated_by
            FROM ' . RAIDS_TABLE . ' WHERE raid_id= '. $raidid;
	
    if ( !($raid_result = $db->sql_query($sql)) )
    {
        trigger_error ($user->lang['MNOTFOUND']);
    }
    
    if ( !$raid = $db->sql_fetchrow($raid_result) )
    {
       	trigger_error ($user->lang['MNOTFOUND']);
    }
    $db->sql_freeresult($raid_result);

    $dkpid = (int) $raid['raid_dkpid'];
    $raidid = (int) $raid['raid_id']; 
    
    // Attendees
    $attendees = array();
    $sql = 'SELECT member_name FROM ' . RAID_ATTENDEES_TABLE . " WHERE raid_id = " . $raidid . " ORDER BY member_name";
    $result = $db->sql_query($sql);
    while ( $arow = $db->sql_fetchrow($result) )
    {
        $attendees[] = $arow['member_name'];
    }
    $db->sql_freeresult($result);

    // Get class info
    $class_count = null;   
    $eq_classes = array();
    $total_attendees = count($attendees);

	$sql = 'SELECT class_id, class_name from ' . CLASS_TABLE . ' where class_id != 0';
    $result = $db->sql_query($sql);
	while ( $row = $db->sql_fetchrow($result) )
    {
    	$class_name[$row['class_id']] = $row['class_name'];
    	$class_id[$row['class_name']] = $row['class_id'];
    	$class_count[$row['class_name']]=null;
        $eq_classes[$row['class_name']]=null;
    }
    $db->sql_freeresult($result);

    // Get attendee ranks
    $ranks = array();
    $sql = 'SELECT m.member_name, m.member_class_id, c.class_name AS member_classname, r.rank_prefix, r.rank_suffix
            FROM ( ' . CLASS_TABLE . ' c, ' . MEMBER_LIST_TABLE . ' m
            LEFT JOIN ' . MEMBER_RANKS_TABLE . ' r
            ON m.member_rank_id = r.rank_id )
            WHERE m.member_class_id = c.class_id 
            AND ' . $db->sql_in_set('m.member_name', $attendees, false, true); 
    
    $result = $db->sql_query($sql);
    while ( $row = $db->sql_fetchrow($result) )
    {
        $ranks[ $row['member_name'] ] = array(
            'prefix'             => (( !empty($row['rank_prefix']) ) ? $row['rank_prefix'] : ''),
            'suffix'             => (( !empty($row['rank_suffix']) ) ? $row['rank_suffix'] : ''),
            'member_classname'   => (( !empty($row['member_classname']) ) ? $row['member_classname'] : '')
        );
    }
    $db->sql_freeresult($result);

    
    /*********************************
	*	attendee block
	**********************************/
    if ( @sizeof($attendees) > 0 )
    {
        // First get rid of duplicates and resort them just in case,
        // so we're sure they're displayed correctly
        $attendees = array_unique($attendees);
        sort($attendees);
        reset($attendees);
        $rows = ceil(sizeof($attendees) / 8);

        for ( $i = 0; $i < $rows; $i++ )
        {
        	// First loop: iterate through the rows
            $block_vars = array();
            for ( $j = 0; $j < 8; $j++ )
            {
				// Second loop: iterate through the columns as defined in template_config,
                $offset = ($i + ($rows * $j));
                $attendee = ( isset($attendees[$offset]) ) ? $attendees[$offset] : '';
                $html_prefix = ( isset($ranks[$attendee]) ) ? $ranks[$attendee]['prefix'] : '';
                $html_suffix = ( isset($ranks[$attendee]) ) ? $ranks[$attendee]['suffix'] : '';
                $cssclass = ( isset($ranks[$attendee]) ) ?  $config['bbdkp_default_game'] . 'class'. $class_id[ $ranks[$attendee]['member_classname']  ]       : '';
				
                // then "add" an array to $block_vars that contains the column definitions,
      			// then assign the block vars.
     			// Prevents one column from being assigned and the rest of the columns for
     			// that row being blank
                if ( $attendee != '' )
                {
                    $block_vars += array(
                      'COLUMN'.$j.'_NAME' => '<a href="' . append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . $attendee . '&' . URI_DKPSYS . '=' . $dkpid) . '" class="' . $cssclass . '">' . $html_prefix . $attendee . $html_suffix . '</a>'
                    );
                }
                else
                {
                    $block_vars += array(
                        'COLUMN'.$j.'_NAME' => ''
                    );
                }

                // Are we showing this column?
                $s_column = 's_column'.$j;
                ${$s_column} = true;
                
          
            }
            $template->assign_block_vars('attendees_row', $block_vars);
        }
        $column_width = floor(100 / 8);
    }
    
    
    /*********************************
	*	Drops block
	**********************************/

    $startdrops = request_var('start',0);

    $sql = 'SELECT count(*) as itemscount    
            FROM  ' . ITEMS_TABLE . '  
            WHERE raid_id='. $raidid ;
    
	$total_result = $db->sql_query($sql);
	$total_items = $db->sql_fetchfield('itemscount');
	
	$db->sql_freeresult($total_result);

	$droppagination = generate_pagination( append_sid("{$phpbb_root_path}viewraid.$phpEx" , URI_RAID . '=' . request_var(URI_RAID, 0)) , $total_items, $config['bbdkp_user_ilimit'], $startdrops);
	
    $sql = 'SELECT i.item_id, i.item_buyer, i.item_name, i.item_value, m.member_class_id, c.class_id, m.member_name
            FROM ' . CLASS_TABLE . ' c, ' . MEMBER_LIST_TABLE . ' m, ' . ITEMS_TABLE . " i 
            WHERE i.item_buyer = m.member_name
            AND m.member_class_id = c.class_id
            AND raid_id= ".  $raidid  . '
            ORDER BY m.member_name, i.item_name ASC ';
    $items_result = $db->sql_query_limit($sql, $config['bbdkp_user_ilimit']  , $startdrops);
   
    if ( !$items_result )
    {
       	trigger_error ($user->lang['MNOTFOUND']);
    }

    $items_re = 0;
    
	$bbDkp_Admin = new bbDkp_Admin;
	if ($bbDkp_Admin->bbtips == true)
	{
		if ( !class_exists('bbtips')) 
		{
			require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
		}
		$bbtips = new bbtips;
	}
		
    while ( $item = $db->sql_fetchrow($items_result) )
    {
		if ($bbDkp_Admin->bbtips == true)
		{
			$item_name = '<b>' . $bbtips->parse('[itemdkp]' . $item['item_name']  . '[/itemdkp]') . '</b>'; 
		}
		else
		{
			$item_name = '<b>' . $item['item_name'] . '</b>';
		}
		
        $items_re++;
        $cssclass = $config['bbdkp_default_game'] . 'class'. $item['class_id'];
        
        $template->assign_block_vars('items_row', array(
            'BUYER'        => $item['item_buyer'],
            'U_VIEW_BUYER' => append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . $item['item_buyer'] . '&' . URI_DKPSYS . '=' . $dkpid . '" class="' . $cssclass) ,
			'NAME'         => $item_name, 
            'U_VIEW_ITEM'  => append_sid("{$phpbb_root_path}viewitem.$phpEx",  URI_ITEM . '='.$item['item_id']),
            'VALUE'        => $item['item_value'])
        );
    }
    
    if ($items_re == 0)
    {
    	$s_comp = false;
    }
    /*****************************
    * 
	*	class block
	* 
	******************************/
    
	// Get each attendee's class
    $sql = 'SELECT m.member_name, m.member_class_id, c.class_name AS member_classmm
            FROM ' . MEMBER_LIST_TABLE . ' m, ' . CLASS_TABLE . ' c
	    	WHERE (m.member_class_id = c.class_id)  
			AND ' . $db->sql_in_set('m.member_name', $attendees, false, true);
    
    $result = $db->sql_query($sql);
    
    while ( $row = $db->sql_fetchrow($result) )
    {
      $member_name = $row['member_name'];
	  $member_class = $row['member_classmm'];
		
        if ( $member_name != '' )
        {
            $html_prefix = ( isset($ranks[$member_name]) ) ? $ranks[$member_name]['prefix'] : '';
            $html_suffix = ( isset($ranks[$member_name]) ) ? $ranks[$member_name]['suffix'] : '';
            $eq_classes[ $row['member_classmm'] ] .= " " . $html_prefix . $member_name . $html_suffix .",";
	        $class_count[ $row['member_classmm'] ]++;          
        }
    }
    $db->sql_freeresult($result);
    unset($ranks);
    
    // Now find out how many of each class there are
    foreach ( $eq_classes as $class => $members )
    {
	    $percentage =  ( $total_attendees > 0 ) ? round(($class_count[$class] / $total_attendees) * 100) : 0;
	    $cssclass = $config['bbdkp_default_game'] . 'class'. $class_id[$class];
	    
        $template->assign_block_vars('class_row', array(
            'CLASS'     => '<font class="' . $cssclass . '">' . $class . '</font>' ,
            'BAR'       => create_bar(($class_count[ $class ] * 10), '<font class="' . $class . '">' . $class_count[ $class ] . ' (' . $percentage . '%)'),
            'ATTENDEES' => '<font class="' . $cssclass . '">' . $members . '</font>' )        );
    }
    unset($eq_classes);

    $title = sprintf($user->lang['MEMBERS_PRESENT_AT'], $raid['raid_name'],
                                  date('F j, Y', $raid['raid_date']));
                                  
    $navlinks_array = array(
    array(
     'DKPPAGE' => $user->lang['MENU_RAIDS'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}listraids.$phpEx"),
    ),

    array(
     'DKPPAGE' => $user->lang['MENU_VIEWRAID'],
     'U_DKPPAGE' => append_sid("{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '=' . $raidid),
    ),
    );

    foreach( $navlinks_array as $name )
    {
	    $template->assign_block_vars('dkpnavlinks', array(
	    'DKPPAGE' => $name['DKPPAGE'],
	    'U_DKPPAGE' => $name['U_DKPPAGE'],
	    ));
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
    
        'L_MEMBERS_PRESENT_AT' => sprintf($user->lang['MEMBERS_PRESENT_AT'], $raid['raid_name'], date('F j, Y', $raid['raid_date'])),

        'S_COMP' => ( isset($s_comp) ) ? false : true,
		'S_COLUMN0' => ( isset($s_column0) ) ? true : false,
        'S_COLUMN1' => ( isset($s_column1) ) ? true : false,
        'S_COLUMN2' => ( isset($s_column2) ) ? true : false,
        'S_COLUMN3' => ( isset($s_column3) ) ? true : false,
        'S_COLUMN4' => ( isset($s_column4) ) ? true : false,
        'S_COLUMN5' => ( isset($s_column5) ) ? true : false,
        'S_COLUMN6' => ( isset($s_column6) ) ? true : false,
        'S_COLUMN7' => ( isset($s_column7) ) ? true : false,
        'S_COLUMN8' => ( isset($s_column8) ) ? true : false,
        'S_COLUMN9' => ( isset($s_column9) ) ? true : false,

                                  
        'COLUMN_WIDTH' => ( isset($column_width) ) ? $column_width : 0,
        'COLSPAN'      => 8,

        'RAID_ADDED_BY'       => ( !empty($raid['raid_added_by']) ) ? $raid['raid_added_by'] : 'N/A',
        'RAID_UPDATED_BY'     => ( !empty($raid['raid_updated_by']) ) ? $raid['raid_updated_by'] : 'N/A',
        'RAID_NOTE'           => ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
        'RAID_VALUE'          => $raid['raid_value'],
        'ATTENDEES_FOOTCOUNT' => sprintf($user->lang['VIEWRAID_ATTENDEES_FOOTCOUNT'], sizeof($attendees)),
        'ITEM_FOOTCOUNT'      => sprintf($user->lang['VIEWRAID_DROPS_FOOTCOUNT'], $items_re),
                                  
         'START' 		=> $startdrops,                   
        'ITEM_PAGINATION' => $droppagination
        )
    );

// Output page
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/viewraid.html')
);

page_footer();

}
else
{
   	trigger_error ($user->lang['MNOTFOUND']);
}
?>
