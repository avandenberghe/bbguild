<?php

 /* recruitment block
  @package bbDkp
  @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
  @license http://opensource.org/licenses/gpl-license.php GNU Public License
  @version $Id$
 */


if (!defined('IN_PHPBB'))
{
   exit;
}

/**  begin recruitment block ***/

$color =array(
     array(0, "  ",   "#000000", "bullet_white.png" ),
     array(1, "Clos", "#AAAAAA", "bullet_white.png" ),
     array(2, "Low",  "#FFBB44", "bullet_yellow.png" ),
     array(3, "Med",  "#FF3300", "bullet_red.png" ),
     array(4, "High", "#AA00AA", "bullet_purple.png" ),
    );

if ($config['bbdkp_recruitment'] == 1)
{
	$template->assign_block_vars('status', array(
		'MESSAGE' 	=> $user->lang['RECRUIT_MESSAGE'],					
	));
	
	$rec_forum_id  = $config['bbdkp_recruit_forumid'];
	
	// get recruitment statuses from class table
    $sql_array = array(
	    'SELECT'    => 	' c.class_id, l.name as class_name, c.colorcode, 
	    				  c.imagename, c.dps, c.tank, c.heal ', 
	    'FROM'      => array(
	        CLASS_TABLE 	=> 'c',
	        BB_LANGUAGE		=> 'l', 
	    	),
	    'WHERE'		=> " c.class_id > 0 and l.attribute_id = c.c_index AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",   				    	
		'ORDER_BY'	=> ' c.class_id ',
	    );
	    
	$sql = $db->sql_build_query('SELECT', $sql_array);    
    
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) 
    {

        $class[$row['class_id']] =$row['class_name'] ; 
        
        $template->assign_block_vars('rec', array(
    	    'CLASSID' => $row['class_id'],
            'CLASS'   => $row['class_name'],  
        	'IMAGENAME' => $row['imagename'],
			'CLASSCOLOR' => $row['colorcode'],        
        	'TANKCOLOR'  => $color[$row['tank']][2],
			'TANKFORUM'  => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id), 
			'TANKTEXT'  => $color[$row['tank']][1], 
			'DPSCOLOR' => $color[$row['dps']][2],
        	'DPSFORUM' => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id),   
		    'DPSTEXT'  => $color[$row['dps']][1], 
			'HEALCOLOR' => $color[$row['heal']][2],
        	'HEALFORUM' => append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $rec_forum_id),   
		    'HEALTEXT' => $color[$row['heal']][1], 
        ));
                  
	}
	$db->sql_freeresult($result);
}
else 
{
	$template->assign_block_vars('status', array(
       	'S_DISPLAY_RECRUIT' => true, 
		'MESSAGE' 	=> $user->lang['RECRUIT_CLOSED'],					
	));
}

$template->assign_vars(array(
	'S_DISPLAY_RECRUIT' => true, 
));


/**  end recruitment block ***/



?>