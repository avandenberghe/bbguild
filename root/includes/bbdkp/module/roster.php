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
if (!defined('IN_PHPBB'))
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


$mode = $config['bbdkp_roster_layout'];


$genderid = array(
0   => $user->lang['MALE'], 
1   => $user->lang['FEMALE'],
);

$game_id = request_var('displaygame', '');


$installed_games = array();
foreach($games as $id => $gamename)
{
	if ($config['bbdkp_games_' . $id] == 1)
	{
		$installed_games[$id] = $gamename; 
	} 
	
}

if (array_key_exists ( $game_id , $installed_games))
{
	//show chosen game
	displayroster($game_id, $mode);
}
else 
{
	//show first game
	foreach($installed_games as $id => $gamename)
	{
		displayroster($id, $mode);
		break 1;	
	}
}


function displayroster($game_id, $mode)
{
	global $phpbb_root_path, $phpEx, $config, $template, $db, $user;
	$totalmembers = 0; 
	
	$current_order = array();

	//by class or by listing
	// class mode only for wow or aion...
	if($mode == '1' && ($game_id=='wow' || $game_id =='aion') )
	{
		 //class
	 	 $result = get_classes();
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
    
                
        		$template->assign_block_vars('class.members_row', array(
        			'CLASS'			=> $row['class_name'],
        			'NAME'			=> $row['member_name'],
        			'RACE'			=> $row['race_name'],
        			'GNOTE'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
             		'LVL'			=> $row['member_level'],
        		    'ARMORY'		=> $row['member_armory_url'],  
        			'PORTRAIT'		=> $memberportraiturl,		
        		    'ACHIEVPTS'		=> $row['member_achiev'], 
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
	
	// add template constants
	$template->assign_vars(array(
	    'GUILDNAME'			=>  $config['bbdkp_guildtag'],
	    'U_LIST_MEMBERS0'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][0]),
	    'U_LIST_MEMBERS1'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][1]),
	    'U_LIST_MEMBERS2'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][2]),
	    'U_LIST_MEMBERS3'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][3]),
	    'U_LIST_MEMBERS4'	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster&amp;'. URI_ORDER. '='. $current_order['uri'][4]),
	    'S_RSTYLE'		    => $mode , 
	    'S_SHOWACH'			=> $show_achiev, 
		'S_DISPLAY_ROSTER' => true,
	    'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $totalmembers,
	));
	
	$header = $user->lang['GUILDROSTER'];
	page_header($header);
}


function getlinks($gameid, $row)
{

	// setting up the links
	switch ($game_id)
    {
    	case 'wow':
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
               break;
      	 case 'aion': 
	       $memberportraiturl =  $phpbb_root_path . 'images/roster_portraits/aion/' . $row['member_race_id'] . '_' . $row['member_gender_id'] . '.jpg';
               break;     		        
          default:
           $memberportraiturl='';
	           break;
        }
                	
	
	
}


function removeFromEnd($string, $stringToRemove) 
{
    $stringToRemoveLen = strlen($stringToRemove);
    $stringLen = strlen($string);
    $pos = $stringLen - $stringToRemoveLen;
    $out = substr($string, 0, $pos);
    return $out;
}

function get_listingresult($game_id, $mode, &$current_order, $classid=0)
{
	global $db, $config; 
	
	$totalmembers = 0;

	$sql_array = array();
	$sql_array['SELECT'] =  'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e1.name as race_name, 
           				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev, m.member_armory_url,
           				 r.rank_prefix , r.rank_name, r.rank_suffix,  
           				 g.name, g.realm, g.region, 
           				 c1.name as class_name, c.colorcode '; 
	
	 $sql_array['FROM'] = array(
               MEMBER_LIST_TABLE    =>  'm',
               CLASS_TABLE          =>  'c',
               GUILD_TABLE          =>  'g',
               MEMBER_RANKS_TABLE   =>  'r',
               RACE_TABLE           =>  'e',
               BB_LANGUAGE			 =>  'e1');

        $sql_array['LEFT_JOIN'] = array(       
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
           				 AND m.game_id = " . (int) $game_id . "
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
function get_classes()
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
       				 AND c1.game_id=c.game_id
       				 
       				  ", 
       'ORDER_BY'  =>  'c1.name asc'
    );
    $sql = $db->sql_build_query('SELECT', $sql_array);
    $result = $db->sql_query($sql);
    return $result;
    
    
	
}



?>
