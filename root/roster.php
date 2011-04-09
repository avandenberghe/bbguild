<?php
 /**
 * Roster2 - generic version
 * 
 * @package bbDKP
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
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
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx); 

// Start session management
$user->session_begin();
$auth->acl($user->data); 
$user->setup('viewforum');
$user->add_lang ( array ('mods/dkp_common' ));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined('EMED_BBDKP')) 
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}
global $config; 
$layout = $config['bbdkp_roster_layout'];
$show_achiev = $config['bbdkp_show_achiev'];

$sort_order = array(
    0 => array('m.member_name', 'm.member_name desc'),
    1 => array('m.member_class_id', 'm.member_class_id desc'),
    2 => array('m.member_rank_id', 'm.member_rank_id desc'),
    3 => array('m.member_level', 'm.member_level  desc'),
    4 => array('m.member_achiev', 'm.member_achiev  desc')
);

$current_order = switch_order($sort_order);
$totalmembers = 0;

$genderid = array(
0   => $user->lang['MALE'], 
1   => $user->lang['FEMALE'],
);

switch ($layout)
{
        //default layout 
         case 0:
        $sql_array = array(
            'SELECT'    => 'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e1.name as race_name, 
            				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev, m.member_armory_url,
            				 r.rank_prefix , r.rank_name, r.rank_suffix,  
            				 g.name, g.realm, g.region, 
            				 c1.name as class_name, c.colorcode ' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                GUILD_TABLE          =>  'g',
                MEMBER_RANKS_TABLE   =>  'r',
                RACE_TABLE           =>  'e',
                BB_LANGUAGE			 =>  'e1', 
                ),
             						    
		     'LEFT_JOIN' => array(
		        array(
		            'FROM'  => array(BB_LANGUAGE => 'c1'),
		            'ON'    => "c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'"  
		      )),
		                      
            'WHERE'     => " g.id = m.member_guild_id
            				 AND c.class_id = m.member_class_id 
            				 AND e.race_id = m.member_race_id 
            				 AND r.guild_id = m.member_guild_id  
            				 AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
            				 AND e1.attribute_id = e.race_id AND e1.language= '" . $config['bbdkp_lang'] . "' AND e1.attribute = 'race'
            				 ",
            'ORDER_BY'  => $current_order['sql']
        );
        
        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        
        while ( $row = $db->sql_fetchrow($result) )
        {
            switch ($config['bbdkp_default_game'])
            {
                case 'wow':
                    // individual portraits
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
                        $maxlvlid ="wow-80";
                   }
                   
            		$cssclass = 'wowclass'. $row['member_class_id'];
            		$memberportraiturl = $phpbb_root_path . 'images/roster_portraits/'. $maxlvlid .'/' . $row['member_gender_id'] . '-' . $row['member_race_id'] . '-' . $row['member_class_id'] . '.gif';
            		switch ($row['region'])
                		{
                		    case 'EU': 
                		        $site='http://eu.battle.net/wow/en/character';
                		        break;
                		    case 'US': 
                		        $site='http://us.battle.net/wow/en/character/';
                		        break;
                		}
                	$memberarmoryurl = $site . $row['realm'] . '/' .  $row['member_name'];
                   break;
    		    case 'aion': 
                    // individual portraits
                    $memberportraiturl =  $phpbb_root_path .'images/roster_portraits/aion/' . $row['member_race_id'] . '_' . $row['member_gender_id'] . '.jpg';
                    $memberarmoryurl = $row['member_armory_url']; 
                    $cssclass = ''; 
                     break;     		        
                default:
                   $cssclass = '';
                   $memberportraiturl='';
                   $memberarmoryurl= '';
                   break;
            }
    		
    		$template->assign_block_vars('members_row', array(
        			'CLASS'			=> $row['class_name'],
        			'COLORCODE'		=> $row['colorcode'],
        			'NAME'			=> $row['member_name'],
        			'RACE'			=> $row['race_name'],
        			'GNOTE'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
             		'LVL'			=> $row['member_level'],
        		    'ARMORY'		=> $memberarmoryurl,  
        			'PORTRAIT'		=> $memberportraiturl,		
        		    'ACHIEVPTS'		=> $row['member_achiev'], 
    		));
    		$totalmembers++;
        	
        }
        

        break;
        
    case 1:
        // class layout
        $sql_array = array(
            'SELECT'    => 'c.class_id, c1.name as class_name, c.imagename, c.colorcode' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                BB_LANGUAGE			 =>  'c1',
                MEMBER_RANKS_TABLE   =>  'r',
                ),
            'WHERE'     => " c.class_id = m.member_class_id 
            				 AND r.guild_id = m.member_guild_id 
            				 AND r.rank_id = m.member_rank_id AND r.rank_hide = 0
            				 AND c1.attribute_id =  c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class' ", 
            'ORDER_BY'  =>  'c1.name asc'
         );
        $sql2 = $db->sql_build_query('SELECT', $sql_array);
        $result2 = $db->sql_query($sql2);
        $classes = array();
        while ( $row = $db->sql_fetchrow($result2) )
        {
            $classes[$row['class_id']]['name'] = $row['class_name'];
            $classes[$row['class_id']]['imagename'] = $row['imagename'];
            $classes[$row['class_id']]['colorcode'] = $row['colorcode'];
        }
        $db->sql_freeresult($result2);
        
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
            
            $sql_array = array(
            'SELECT'    => 'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e1.name as race_name, 
            				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev,  m.member_armory_url,
            				 r.rank_prefix , r.rank_name, r.rank_suffix,  
            				 g.name, g.realm, g.region, 
            				 c1.name as class_name' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                GUILD_TABLE          =>  'g',
                MEMBER_RANKS_TABLE   =>  'r',
                RACE_TABLE           =>  'e',
                BB_LANGUAGE			 =>  'e1',
                ),
             						    
		     'LEFT_JOIN' => array(
		        array(
		            'FROM'  => array(BB_LANGUAGE => 'c1'),
		            'ON'    => "c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'"  
		      )),
             
            'WHERE'     => "g.id = m.member_guild_id 
            				AND c.class_id = m.member_class_id 
            				AND r.rank_id = m.member_rank_id AND r.rank_hide = 0 
            				AND r.guild_id = m.member_guild_id 
            				AND e.race_id = m.member_race_id 
            				AND c1.attribute_id = c.class_id AND c1.language= '" . $config['bbdkp_lang'] . "' AND c1.attribute = 'class'
            				AND e1.attribute_id = r.rank_id AND e1.language= '" . $config['bbdkp_lang'] . "' AND e1.attribute = 'race'  
                            AND m.member_class_id = " . (int) $classid,
            'ORDER_BY'  => $current_order['sql']
            );
            $sql = $db->sql_build_query('SELECT', $sql_array);
            $result = $db->sql_query($sql);
            while ( $row = $db->sql_fetchrow($result) )
            {
                
                // setting up the links
        		switch ($config['bbdkp_default_game'])
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
                            $maxlvlid ="wow-80";
                       }
                		$cssclass = 'wowclass'. $row['member_class_id'];
                		$memberportraiturl =  $phpbb_root_path .'images/roster_portraits/'. $maxlvlid .'/' . $row['member_gender_id'] . '-' . $row['member_race_id'] . '-' . $row['member_class_id'] . '.gif';
                		
                		switch ($row['region'])
                		{
 							case 'EU': 
                		        $site='http://eu.battle.net/wow/en/character';
                		        break;
                		    case 'US': 
                		        $site='http://us.battle.net/wow/en/character/';
                		        break;
                		}
                		$memberarmoryurl = $site . $row['realm'] . '/' .  $row['member_name'];
                       break;
    		        case 'aion': 
		                $cssclass = '';
    		            $memberportraiturl =  $phpbb_root_path . 'images/roster_portraits/aion/' . $row['member_race_id'] . '_' . $row['member_gender_id'] . '.jpg';
    		            $memberarmoryurl = $row['member_armory_url']; 
                        break;     		        
                       
                    default:
                       $cssclass = '';
                       $memberportraiturl='';
                       $memberarmoryurl= '';
                       break;
                }
                
                
        		$template->assign_block_vars('class.members_row', array(
        			'CLASS'			=> $row['class_name'],
        			'NAME'			=> $row['member_name'],
        			'RACE'			=> $row['race_name'],
        			'GNOTE'			=> $row['rank_prefix'] . $row['rank_name'] . $row['rank_suffix'] ,
             		'LVL'			=> $row['member_level'],
        		    'ARMORY'		=> $memberarmoryurl,  
        			'PORTRAIT'		=> $memberportraiturl,		
        		    'ACHIEVPTS'		=> $row['member_achiev'], 
        		));
        		$totalmembers++;
        		$classmembers++;
        		
            	
            }
            $db->sql_freeresult($result);
        }
}

$navlinks_array = array(
array(
 'DKPPAGE' => $user->lang['MENU_ROSTER'],
 'U_DKPPAGE' => append_sid("{$phpbb_root_path}roster.$phpEx"),
));

foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
	'DKPPAGE' => $name['DKPPAGE'],
	'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

// constants
$template->assign_vars(array(
    'GUILDNAME'			=>  $config['bbdkp_guildtag'],
    'U_LIST_MEMBERS0'	=> append_sid("{$phpbb_root_path}roster.$phpEx", '&amp;'. URI_ORDER. '='. $current_order['uri'][0]),
    'U_LIST_MEMBERS1'	=> append_sid("{$phpbb_root_path}roster.$phpEx", '&amp;'. URI_ORDER. '='. $current_order['uri'][1]),
    'U_LIST_MEMBERS2'	=> append_sid("{$phpbb_root_path}roster.$phpEx", '&amp;'. URI_ORDER. '='. $current_order['uri'][2]),
    'U_LIST_MEMBERS3'	=> append_sid("{$phpbb_root_path}roster.$phpEx", '&amp;'. URI_ORDER. '='. $current_order['uri'][3]),
    'U_LIST_MEMBERS4'	=> append_sid("{$phpbb_root_path}roster.$phpEx", '&amp;'. URI_ORDER. '='. $current_order['uri'][4]),
    'S_RSTYLE'		    => $layout , 
    'S_SHOWACH'			=> $show_achiev, 
    'LISTMEMBERS_FOOTCOUNT' => 'Total members : ' . $totalmembers,
));

$header = $user->lang['GUILDROSTER'];
page_header($header);

switch ($config['bbdkp_default_game'])
{
	      case 'wow':
	          $template->set_filenames(array('body' => 'dkp/roster.html'));
	          break;
	      default:
	          $template->set_filenames(array('body' => 'dkp/roster.html'));
	          break;
}
page_footer();

function removeFromEnd($string, $stringToRemove) 
{
    $stringToRemoveLen = strlen($stringToRemove);
    $stringLen = strlen($string);
   
    $pos = $stringLen - $stringToRemoveLen;

    $out = substr($string, 0, $pos);

    return $out;
}
?>
