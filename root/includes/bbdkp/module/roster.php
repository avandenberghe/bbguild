<?php
 /**
 * Roster2 - generic version
 * 
 * @package bbDKP
 * @copyright 2011 bbdkp http://www.bbdkp.com
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 *
 * more data can be presented in the roster with the armory plugin 
 * 
 * 
 */

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}


/*
 * list installed games
 */
$games = array(
    'wow'        => $user->lang['WOW'], 
    'lotro'      => $user->lang['LOTRO'], 
    'eq'         => $user->lang['EQ'], 
    'daoc'       => $user->lang['DAOC'], 
    'vanguard' 	 => $user->lang['VANGUARD'],
    'eq2'        => $user->lang['EQ2'],
    'warhammer'  => $user->lang['WARHAMMER'],
    'aion'       => $user->lang['AION'],
    'FFXI'       => $user->lang['FFXI'],
	'rift'       => $user->lang['RIFT'],
	'swtor'      => $user->lang['SWTOR']
);

$installed_games = array();
foreach($games as $id => $gamename)
{
	if ($config['bbdkp_games_' . $id] == 1)
	{
		$installed_games[$id] = $gamename; 
	} 
	
}

$game_id = request_var('displaygame', '');

// dump gamelist to template
foreach ($installed_games as $id => $gamename)
{
	$template->assign_block_vars ( 'game_row', array (
		'VALUE' => $id, 
		'SELECTED' => ($id == $game_id) ? ' selected="selected"' : '',
		'OPTION' => $gamename));
}

$template->assign_vars(array(
    'GUILDNAME'			=>  $config['bbdkp_guildtag'],
	'S_MULTIGAME'		=> (sizeof($installed_games) > 1) ? true:false, 
	'S_DISPLAY_ROSTER' => true,
	'F_ROSTER'			=> append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=roster' ), 
));

if (array_key_exists ( $game_id , $installed_games))
{
	//show chosen game
	displayroster($game_id);
}
else 
{
	//show first game
	foreach($installed_games as $id => $gamename)
	{
		displayroster($id);
		break 1;	
	}
}

/*
 * Displays the roster
 */
function displayroster($game_id)
{

	global $phpbb_root_path, $phpEx, $config, $template, $db, $user;
	$totalmembers = 0; 
	
	$mode = $config['bbdkp_roster_layout'];
	$current_order = array();

	//by class
	if($mode == '1')
	{
		 //class
	 	 $result = get_classes($game_id);
		 $classes = array();
         while ( $row = $db->sql_fetchrow($result) )
         {
			$classes[$row['class_id']]['name'] 		= $row['class_name'];
			$classes[$row['class_id']]['imagename'] = $row['imagename'];
			$classes[$row['class_id']]['colorcode'] = $row['colorcode'];
         }
         $db->sql_freeresult($result);
        
         foreach ($classes as  $classid => $class )
         {
            $classimgurl =  $phpbb_root_path . "images/roster_classes/" . removeFromEnd($class['imagename'], '_small') .'.png'; 
			$classcolor = $class['colorcode']; 
            
			$template->assign_block_vars('class', array(	
          		'CLASSNAME'     => $class['name'], 
          		'CLASSIMG'		=> $classimgurl,
          		'COLORCODE'		=> $classcolor,
            ));
            $classmembers=1;

            $result = get_listingresult($game_id, 'class', $current_order, $classid);
            
            while ( $row = $db->sql_fetchrow($result))
            {
				$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male_small'] : $row['image_female_small']);

            	$template->assign_block_vars('class.members_row', array(
        			'COLORCODE'		=> $row['colorcode'],
        			'CLASS'			=> $row['class_name'],
        			'NAME'			=> $row['member_name'],
        			'RACE'			=> $row['race_name'],
        			'GNOTE'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
             		'LVL'			=> $row['member_level'],
        		    'ARMORY'		=> $row['member_armory_url'],  
            		'PHPBBUID'		=> get_username_string('full', $row['phpbb_user_id'], $row['username'], $row['user_colour']),
        			'PORTRAIT'		=> getportrait($game_id, $row), 	
        		    'ACHIEVPTS'		=> $row['member_achiev'], 
					'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',  
					'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false, 
					'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '',  
					'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false, 
            	
        		));
        		$totalmembers++;
        		$classmembers++;
            	
            }
            
            
         }
         
         
	}
	else
	{
		//listing format
		
		$result = get_listingresult($game_id, 'listing', $current_order);
		 while ( $row = $db->sql_fetchrow($result))
            {
				$race_image = (string) (($row['member_gender_id']==0) ? $row['image_male_small'] : $row['image_female_small']);

            	$template->assign_block_vars('members_row', array(
        			'COLORCODE'		=> $row['colorcode'],
        			'CLASS'			=> $row['class_name'],
        			'NAME'			=> $row['member_name'],
        			'RACE'			=> $row['race_name'],
        			'GNOTE'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
             		'LVL'			=> $row['member_level'],
        		    'ARMORY'		=> $row['member_armory_url'],  
            		'PHPBBUID'		=> get_username_string('full', $row['phpbb_user_id'], $row['username'], $row['user_colour']),  
        			'PORTRAIT'		=> getportrait($game_id, $row), 		
        		    'ACHIEVPTS'		=> $row['member_achiev'], 
					'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',  
					'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false, 
					'RACE_IMAGE' 	=> (strlen($race_image) > 1) ? $phpbb_root_path . "images/race_images/" . $race_image . ".png" : '',  
					'S_RACE_IMAGE_EXISTS' => (strlen($race_image) > 1) ? true : false, 
            	));
            }
            	
	
	}
	
	$db->sql_freeresult($result);
	
	// add navigationlinks
	$navlinks_array = array(
	array(
		 'DKPPAGE' => $user->lang['MENU_ROSTER'],
		 'U_DKPPAGE' => append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster'),
	));
	
	foreach( $navlinks_array as $name )
	{
		$template->assign_block_vars('dkpnavlinks', array(
		'DKPPAGE' => $name['DKPPAGE'],
		'U_DKPPAGE' => $name['U_DKPPAGE'],
		));
	}
	$show_achiev = $config['bbdkp_show_achiev'];
	
	if (isset($current_order) && sizeof ($current_order) > 0)
	{
		$template->assign_vars(array(
			'U_LIST_MEMBERS0'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][0]),
		    'U_LIST_MEMBERS1'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][1]),
		    'U_LIST_MEMBERS2'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][2]),
		    'U_LIST_MEMBERS3'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][3]),
		    'U_LIST_MEMBERS4'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][4]),
		));
		
	}
	
	// add template constants
	$template->assign_vars(array(
	    'S_RSTYLE'		    => $mode,
		'S_GAME'		    => $game_id, 
	    'S_SHOWACH'			=> $show_achiev, 
	    'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $totalmembers,
	));
	
	$header = $user->lang['GUILDROSTER'];
	page_header($header);
}


function getportrait($game_id, $row)
{
	global $phpbb_root_path;

	// setting up the links
	switch ($game_id)
    {
    	case 'wow':
    	 if ( $row['member_portrait_url'] != '')
    	 {
    	 	$memberportraiturl =  $row['member_portrait_url'];		 
    	 }
    	 else 
    	 {
		   if($row['member_level'] <= "59")
		   {
				$maxlvlid ="wow-default";
		   }
		   elseif($row['member_level'] <= 69)
		   {
				$maxlvlid ="wow";
		   }
		   elseif($row['member_level'] <= 79)
		   {
				$maxlvlid ="wow-70";
		   }
		   else
		   {
				// level 85 is not yet iconified
				$maxlvlid ="wow-80";
		   }
       	   $memberportraiturl =  $phpbb_root_path .'images/roster_portraits/'. $maxlvlid .'/' . $row['member_gender_id'] . '-' . 
       	    $row['member_race_id'] . '-' . $row['member_class_id'] . '.gif';
    	 }
               break;
      	 case 'aion': 
	       $memberportraiturl =  $phpbb_root_path . 'images/roster_portraits/aion/' . $row['member_race_id'] . '_' . $row['member_gender_id'] . '.jpg';
               break;     		        
          default:
           $memberportraiturl='';
	           break;
        }
        return $memberportraiturl;
	
}


function removeFromEnd($string, $stringToRemove) 
{
    $stringToRemoveLen = strlen($stringToRemove);
    $stringLen = strlen($string);
    $pos = $stringLen - $stringToRemoveLen;
    $out = substr($string, 0, $pos);
    return $out;
}

function get_phpbbuid($userid)
{

	
}


function get_listingresult($game_id, $mode, &$current_order, $classid=0)
{
	global $db, $config; 
	
	$totalmembers = 0;

	$sql_array = array();
	$sql_array['SELECT'] =  'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e1.name as race_name, 
           				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev, m.member_armory_url, m.member_portrait_url, 
           				 r.rank_prefix , r.rank_name, r.rank_suffix, e.image_female_small, e.image_male_small,
           				 g.name, g.realm, g.region, c1.name as class_name, c.colorcode, c.imagename, m.phpbb_user_id, u.username, u.user_colour  '; 
	
	 $sql_array['FROM'] = array(
               MEMBER_LIST_TABLE    =>  'm',
               CLASS_TABLE          =>  'c',
               GUILD_TABLE          =>  'g',
               MEMBER_RANKS_TABLE   =>  'r',
               RACE_TABLE           =>  'e',
               BB_LANGUAGE			 =>  'e1');

        $sql_array['LEFT_JOIN'] = array(    
        	 array(
				 'FROM'  => array(USERS_TABLE => 'u'),
				   'ON'    => 'u.user_id = m.phpbb_user_id '), 
	        array(
	            'FROM'  => array(BB_LANGUAGE => 'c1'),
	            'ON'    => "c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'  and c1.game_id = c.game_id "  
	      )); 
	                      
	$sql_array['WHERE'] = " c.class_id = m.member_class_id
           				 AND c.game_id = m.game_id 
           				 AND e.race_id = m.member_race_id
           				 AND e.game_id = m.game_id 
           				 AND g.id = m.member_guild_id
           				 AND r.guild_id = m.member_guild_id  
           				 AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
           				 AND m.member_status = 1
           				 AND m.member_rank_id != 99
           				 AND m.game_id = '" . $db->sql_escape($game_id) . "'
           				 AND e1.attribute_id = e.race_id AND e1.language= '" . $config['bbdkp_lang'] . "' AND e1.attribute = 'race' and e1.game_id = e.game_id";
	
	$sort_order = array(
	    0 => array('m.member_name', 'm.member_name desc'),
	    1 => array('m.member_class_id', 'm.member_class_id desc'),
	    2 => array('m.member_rank_id', 'm.member_rank_id desc'),
	    3 => array('m.member_level', 'm.member_level  desc'),
	    4 => array('m.member_achiev', 'm.member_achiev  desc')
	);
	
	$current_order = switch_order($sort_order);
    $sql_array['ORDER_BY']  = $current_order['sql'];
	
	if($mode=='class')
	{
		$sql_array['WHERE'] .= " AND m.member_class_id = " . (int) $classid;
	}
	
    $sql = $db->sql_build_query('SELECT', $sql_array);
    $result = $db->sql_query($sql);
       
     return $result;
}


/*
 * gets class array
 */
function get_classes($game_id)
{
	global $db, $config; 
	$sql_array = array(
       'SELECT'    => 'c.class_id, c1.name as class_name, c.imagename, c.colorcode' , 
       'FROM'      => array(
           MEMBER_LIST_TABLE    =>  'm',
           CLASS_TABLE          =>  'c',
           BB_LANGUAGE			=>  'c1',
           MEMBER_RANKS_TABLE   =>  'r',
           ),
       'WHERE'     => " c.class_id = m.member_class_id 
       				 AND c.game_id = m.game_id
       				 AND r.guild_id = m.member_guild_id 
       				 AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
       				 AND c1.attribute_id =  c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class' 
       				 AND (c.game_id = '" . $db->sql_escape($game_id) . "')  
       				 AND c1.game_id=c.game_id
       				 
       				  ", 
       'ORDER_BY'  =>  'c1.name asc'
    );
    $sql = $db->sql_build_query('SELECT', $sql_array);
    $result = $db->sql_query($sql);
    return $result;
    
    
	
}



?>
