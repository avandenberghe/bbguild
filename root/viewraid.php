<?php
/**
 * View individual raid
 * 
 * @package bbDKP
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
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}


if ( isset($_GET[URI_RAID])  )
{
	/********************************
	 * page info
	 ********************************/ 	
	$navlinks_array = array(
    array(
     'DKPPAGE' 		=> $user->lang['MENU_RAIDS'],
     'U_DKPPAGE' 	=> append_sid("{$phpbb_root_path}listraids.$phpEx"),
    ),

    array(
     'DKPPAGE' 		=> $user->lang['MENU_VIEWRAID'],
     'U_DKPPAGE' 	=> append_sid("{$phpbb_root_path}viewraid.$phpEx", URI_RAID . '=' . $raid_id),
    ),
    );

    foreach( $navlinks_array as $name )
    {
	    $template->assign_block_vars('dkpnavlinks', array(
	    'DKPPAGE' => $name['DKPPAGE'],
	    'U_DKPPAGE' => $name['U_DKPPAGE'],
	    ));
    }
    
	/********************************
	 * Raid information block
	 ********************************/ 
    
	$raid_id = request_var(URI_RAID,0); 

	$sql_array = array(
	    'SELECT'    => 	' r.raid_id, e.event_dkpid, e.event_name, r.raid_date, r.raid_note, r.raid_value, r.raid_added_by, r.raid_updated_by  ', 
	    'FROM'      => array(
			EVENTS_TABLE			=> 'e', 	        
			RAIDS_TABLE 			=> 'r'	         
	    	),
	    'WHERE'		=> ' r.event_id = e.event_id
            AND r.raid_id = ' . $raid_id,
    );
	$sql = $db->sql_build_query('SELECT', $sql_array);
	
    if ( !($raid_result = $db->sql_query($sql)) )
    {
        trigger_error ($user->lang['MNOTFOUND']);
    }
    
    if ( !$raid = $db->sql_fetchrow($raid_result) )
    {
       	trigger_error ($user->lang['MNOTFOUND']);
    }
    $db->sql_freeresult($raid_result);

    $dkpid = (int) $raid['event_dkpid'];
    $raid_id = (int) $raid['raid_id']; 
    $title = sprintf($user->lang['MEMBERS_PRESENT_AT'], $raid['event_name'], date('F j, Y', $raid['raid_date']));
    
    $template->assign_vars(array(
        'L_MEMBERS_PRESENT_AT' => sprintf($user->lang['MEMBERS_PRESENT_AT'], $raid['event_name'], date('F j, Y', $raid['raid_date'])),
        'RAID_ADDED_BY'       => ( !empty($raid['raid_added_by']) ) ? $raid['raid_added_by'] : 'N/A',
        'RAID_UPDATED_BY'     => ( !empty($raid['raid_updated_by']) ) ? $raid['raid_updated_by'] : 'N/A',
        'RAID_NOTE'           => ( !empty($raid['raid_note']) ) ? $raid['raid_note'] : '&nbsp;',
        'RAID_VALUE'          => $raid['raid_value'],
        )
    );
    
    /********************************
	 * Attendees block
	 ********************************/ 

    $raiders = array();
    $sql = 'SELECT a.member_id from ' . RAID_DETAIL_TABLE . ' a , ' . MEMBER_LIST_TABLE . ' l 
	where a.member_id = l.member_id and a.raid_id = ' .  $raid_id . ' ORDER BY l.member_name ';     
    $result = $db->sql_query($sql);
    while ( $arow = $db->sql_fetchrow($result) )
    {
        $raiders[] = $arow['member_id'];
    }
    $db->sql_freeresult($result);

    // Get class info
    $class_count = null;   
    $eq_classes = array();
    $total_attendees = count($raiders);

	$sql = 'SELECT a.class_id, c1.name, a.colorcode, a.imagename from ' . CLASS_TABLE . ' a, '  . BB_LANGUAGE . " c1 
	   WHERE a.class_id != 0 
	   AND c1.attribute_id = a.class_id 
	   AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'";
	
    $result = $db->sql_query($sql);
	while ( $row = $db->sql_fetchrow($result) )
    {
    	$class_name[$row['class_id']] = $row['name'];
    	$class_id[$row['name']] = $row['class_id'];
    	$colorcode[$row['class_id']] = $row['colorcode'];
    	$imagename[$row['class_id']] = $row['imagename'];
    	$class_count[$row['name']]=null;
        $eq_classes[$row['name']]=null;
    }
    $db->sql_freeresult($result);

    // Get attendee ranks
    $raidersinfo = array();
    $sql = 'SELECT m.member_id, m.member_name, m.member_class_id, c1.name as member_classname, r.rank_prefix, r.rank_suffix, c.colorcode  
            FROM ( ' . CLASS_TABLE . ' c, ' . BB_LANGUAGE . ' c1, ' . MEMBER_LIST_TABLE . ' m  
            LEFT JOIN ' . MEMBER_RANKS_TABLE . " r
            ON m.member_rank_id = r.rank_id )
            WHERE m.member_class_id = c.class_id 
            AND c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'  
            AND " . $db->sql_in_set('m.member_id', $raiders, false, true); 
    
    $result = $db->sql_query($sql);
    while ( $row = $db->sql_fetchrow($result) )
    {
        $raidersinfo[$row['member_id']] = array(
            'prefix'             => (( !empty($row['rank_prefix']) ) ? $row['rank_prefix'] : ''),
        	'member_name'        => $row['member_name'],
            'suffix'             => (( !empty($row['rank_suffix']) ) ? $row['rank_suffix'] : ''),
            'member_classname'   => $row['member_classname'], 
        	'member_class_id'   =>  $row['member_class_id']
        );
    }
    $db->sql_freeresult($result);

    if ( @sizeof($raiders) > 0 )
    {
        $rows = ceil(count($raiders) / 8);
        for ( $i = 0; $i < $rows; $i++ )
        {
        	// First loop: iterate through the rows
            $block_vars = array();
            for ( $j = 0; $j < 8; $j++ )
            {
				// Second loop: iterate through the columns
                $offset = ($i + ($rows * $j));
                $attendee = ( isset($raiders[$offset]) ) ? $raiders[$offset] : '';
                
                $prefix = ( isset($raidersinfo[$attendee]) ) ? $raidersinfo[$attendee]['prefix'] : '';
                $name = ( isset($raidersinfo[$attendee]) ) ? $raidersinfo[$attendee]['member_name'] : '';
                $suffix = ( isset($raidersinfo[$attendee]) ) ? $raidersinfo[$attendee]['suffix'] : '';
                $linkcolor = ( isset($raidersinfo[$attendee]) ) ?  $colorcode[ $raidersinfo[$attendee]['member_class_id']] : '';
				
                // then "add" an array to $block_vars that contains the column definitions,
      			// then assign the block vars.
     			// Prevents one column from being assigned and the rest of the columns for
     			// that row being blank
                if ( $attendee != '' )
                {
                    $block_vars += array(
                      'COLUMN'.$j.'_NAME' => '<strong><a style="color: '. $linkcolor.';" href="' . append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAMEID . '=' . $attendee . '&' . URI_DKPSYS . '=' . $dkpid) . '">' . $html_prefix . $name . $html_suffix . '</a></strong>'
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
    
    $template->assign_vars(array(
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
        'ATTENDEES_FOOTCOUNT' => sprintf($user->lang['VIEWRAID_ATTENDEES_FOOTCOUNT'], sizeof($raiders)),
        )
    );
    
    
    /*********************************
	*	Drops block
	**********************************/

    $startdrops = request_var('start',0);

    $sql = 'SELECT count(*) as itemscount    
            FROM  ' . RAID_ITEMS_TABLE . '  
            WHERE raid_id='. $raid_id ;
    
	$total_result = $db->sql_query($sql);
	$total_items = $db->sql_fetchfield('itemscount');
	
	$db->sql_freeresult($total_result);

	$droppagination = generate_pagination( append_sid("{$phpbb_root_path}viewraid.$phpEx" , 
	URI_RAID . '=' . request_var(URI_RAID, 0)) , $total_items, $config['bbdkp_user_ilimit'], $startdrops);

	$sql_array = array(
	    'SELECT'    => 	' i.item_id, i.item_name, i.item_value, item_gameid,
    		 m.member_class_id, c.class_id, m.member_name, m.member_id ', 
	    'FROM'      => array(
			MEMBER_LIST_TABLE	=> 'm', 
			RAID_ITEMS_TABLE			=> 'i', 	        
			CLASS_TABLE 		=> 'c'	         
	    	),
	    'WHERE'		=> ' i.member_id = m.member_id
            AND m.member_class_id = c.class_id
            AND i.raid_id = ' . $raid_id,
	    'ORDER_BY' => 'm.member_name, i.item_name ASC  '
    );
	$sql = $db->sql_build_query('SELECT', $sql_array);
    $items_result = $db->sql_query_limit($sql, $config['bbdkp_user_ilimit']  , $startdrops);
   
    if ( !$items_result )
    {
       	trigger_error ($user->lang['MNOTFOUND']);
    }

    $items_re = 0;
    
	$bbDKP_Admin = new bbDKP_Admin;
	if ($bbDKP_Admin->bbtips == true)
	{
		if ( !class_exists('bbtips')) 
		{
			require($phpbb_root_path . 'includes/bbdkp/bbtips/parse.' . $phpEx); 
		}
		$bbtips = new bbtips;		
	}
		
    while ( $item = $db->sql_fetchrow($items_result) )
    {
		if ($bbDKP_Admin->bbtips == true)
		{
			if ($item['item_gameid'] > 0 )
			{
				$item_name = '<strong>' . $bbtips->parse('[itemdkp]' . $item['item_gameid']  . '[/itemdkp]') . '</strong>' ; 
			}
			else 
			{
				$item_name = '<strong>' . $bbtips->parse ( '[itemdkp]' . $item ['item_name'] . '[/itemdkp]' . '</strong>'  );
			}
			
		}
		else
		{
			$item_name = '<strong>' . $item['item_name'] . '</strong>';
		}
		
        $items_re++;
               
        $template->assign_block_vars('items_row', array(
        	'CLASSCOLORCODE' 	=> $colorcode[$item['member_class_id']], 
            'BUYER'        		=> $item['member_name'],
            'U_VIEW_BUYER' 		=> append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAMEID . '=' . $item['member_id'] . '&' . URI_DKPSYS . '=' . $dkpid) ,
			'NAME'         		=> $item_name, 
            'U_VIEW_ITEM'  		=> append_sid("{$phpbb_root_path}viewitem.$phpEx",  URI_ITEM . '='.$item['item_id']),
            'VALUE'        		=> $item['item_value'])
        );
    }
    
    if ($items_re == 0)
    {
    	$s_comp = false;
    }
    
    $template->assign_vars(array(
        'S_COMP' => ( isset($s_comp) ) ? false : true,
        'ITEM_FOOTCOUNT'      => sprintf($user->lang['VIEWRAID_DROPS_FOOTCOUNT'], $items_re),
        'START' 		=> $startdrops,                   
        'ITEM_PAGINATION' => $droppagination
        )
    );
    
    /*****************************
	*	class block
	******************************/
    
	// Get each attendee's class
    $sql = 'SELECT m.member_name, m.member_class_id, c1.name as class_name 
            FROM ' . MEMBER_LIST_TABLE . ' m, ' . CLASS_TABLE . ' c, '.  BB_LANGUAGE . " c1 
	    	WHERE (m.member_class_id = c.class_id)  
	    	AND c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'  
			AND " . $db->sql_in_set('m.member_id', $raiders, false, true);
    $result = $db->sql_query($sql);
    
    while ( $row = $db->sql_fetchrow($result) )
    {
      $member_name = $row['member_name'];
	  $member_class = $row['class_name'];
		
        if ( $member_name != '' )
        {
            $html_prefix = ( isset($raidersinfo[$member_name]) ) ? $raidersinfo[$member_name]['prefix'] : '';
            $html_suffix = ( isset($raidersinfo[$member_name]) ) ? $raidersinfo[$member_name]['suffix'] : '';
            $eq_classes[ $row['class_name'] ] .= " " . $html_prefix . $member_name . $html_suffix .",";
			            
	        $class_count[ $row['class_name'] ]++;          
        }
    }
    $db->sql_freeresult($result);
    unset($raidersinfo);
    
    // Now find out how many of each class there are
    foreach ( $eq_classes as $class => $members )
    {
	    $percentage =  ( $total_attendees > 0 ) ? round(($class_count[$class] / $total_attendees) * 100) : 0;
        $template->assign_block_vars('class_row', array(
            'CLASS'     	=> $class,
            'CLASSIMAGE'	=> $imagename[$class_id[$class]], 
        	'CLASSCOLOR' 	=> $colorcode[$class_id[$class]], 
            'BAR'       	=> create_bar(($class_count[ $class ] * 10), $class_count[$class] . ' (' . $percentage . '%)'),
            'ATTENDEES' => '<span style="color: ' . $colorcode[$class_id[$class]] . '"><strong>' . $members . '</strong></span>' )        );
    }
    unset($eq_classes);


// Output page
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/viewraid.html')
);

page_footer();

}
else
{
	//raid not found
   	trigger_error ($user->lang['MNOTFOUND']);
}
?>
