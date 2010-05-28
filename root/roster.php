<?php
 /**
 * Roster2 - generic version
 * 
 * @package bbDkp
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
            'SELECT'    => 'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e.race_name, 
            				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev, m.member_armory_url,
            				 r.rank_prefix , r.rank_name, r.rank_suffix,  
            				 g.name, g.realm, g.region, 
            				 c.class_name' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                GUILD_TABLE          =>  'g',
                MEMBER_RANKS_TABLE   =>  'r',
                RACE_TABLE           =>  'e',
                ),
            'WHERE'     => 'g.id = m.member_guild_id AND
            				 c.class_id = m.member_class_id AND
            				 e.race_id = m.member_race_id AND
            				 r.guild_id = m.member_guild_id AND 
            				 r.rank_id = m.member_rank_id AND r.rank_hide = 0',
            'ORDER_BY'  => $current_order['sql']
        );
        
        $sql = $db->sql_build_query('SELECT', $sql_array);
        $result = $db->sql_query($sql);
        
        while ( $row = $db->sql_fetchrow($result) )
        {
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
            		$memberportraiturl = $phpbb_root_path . 'images/roster_portraits/'. $maxlvlid .'/' . $row['member_gender_id'] . '-' . $row['member_race_id'] . '-' . $row['member_class_id'] . '.gif';
            		switch ($row['region'])
                		{
                		    case 'EU': 
                		        $site='http://eu.wowarmory.com';
                		        break;
                		    case 'US': 
                		        $site='http://www.wowarmory.com';
                		        break;
                		}
                	$memberarmoryurl = $site . '/character-sheet.xml?r=' . $row['realm'] . '&amp;n=' .  $row['member_name'];
                   break;
    		    case 'aion': 
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
        			'CSSCLASS'		=> $cssclass,
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
            'SELECT'    => 'c.class_id, c.class_name' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                MEMBER_RANKS_TABLE   =>  'r',
                ),
            'WHERE'     => 'c.class_id = m.member_class_id AND
            				 r.guild_id = m.member_guild_id AND 
            				 r.rank_id = m.member_rank_id AND r.rank_hide = 0', 
            'ORDER_BY'  =>  'c.class_name'
         );
        $sql2 = $db->sql_build_query('SELECT', $sql_array);
        $result2 = $db->sql_query($sql2);
        $class = array();
        while ( $row = $db->sql_fetchrow($result2) )
        {
            $class[$row['class_id']] = $row['class_name'];
        }
        $db->sql_freeresult($result2);
        
        foreach ($class as  $classid => $classname )
        {
            
            $classimgurl =  $phpbb_root_path . 'images/roster_classes/' . $config['bbdkp_default_game'] . '_' . $classname . '.png'; 
            
            $template->assign_block_vars('class', array(	
            		'CLASSNAME'     => $classname, 
            		'CLASSIMG'		=> $classimgurl,
            ));
            $classmembers=1;
            
            
            
            $sql_array = array(
            'SELECT'    => 'm.member_guild_id,  m.member_name, m.member_level, m.member_race_id, e.race_name, 
            				 m.member_class_id, m.member_gender_id, m.member_rank_id, m.member_achiev,  m.member_armory_url,
            				 r.rank_prefix , r.rank_name, r.rank_suffix,  
            				 g.name, g.realm, g.region, 
            				 c.class_name' , 
            'FROM'      => array(
                MEMBER_LIST_TABLE    =>  'm',
                CLASS_TABLE          =>  'c',
                GUILD_TABLE          =>  'g',
                MEMBER_RANKS_TABLE   =>  'r',
                RACE_TABLE           =>  'e',
                ),
            'WHERE'     => 'g.id = m.member_guild_id AND
            				 c.class_id = m.member_class_id AND
            				 r.rank_id = m.member_rank_id AND r.rank_hide = 0 AND
            				 r.guild_id = m.member_guild_id AND
            				 e.race_id = m.member_race_id AND
                            m.member_class_id = ' . (int) $classid,
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
                		        $site='http://eu.wowarmory.com';
                		        break;
                		     case 'US': 
                		        $site='http://www.wowarmory.com';
                		        break;
                		}
                		$memberarmoryurl = $site . '/character-sheet.xml?r=' . $row['realm'] . '&amp;n=' .  $row['member_name'];
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
        			'CSSCLASS'		=> $cssclass,
        		
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
    'U_LISTITEMS'         => append_sid("{$phpbb_root_path}listitems.$phpEx"),  
	'U_LISTITEMHIST'      => append_sid("{$phpbb_root_path}listitems.$phpEx?&amp;page=history"),
	'U_LISTMEMBERS'       => append_sid("{$phpbb_root_path}listmembers.$phpEx"),
	'U_LISTEVENTS'        => append_sid("{$phpbb_root_path}listevents.$phpEx"),
	'U_LISTRAIDS'         => append_sid("{$phpbb_root_path}listraids.$phpEx"),
	'U_VIEWITEM'          => append_sid("{$phpbb_root_path}viewitem.$phpEx"),
	'U_BP'                => append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
	'U_ROSTER'             => append_sid("{$phpbb_root_path}roster.$phpEx"),
	'U_ABOUT'             => append_sid("{$phpbb_root_path}about.$phpEx"),
	'U_STATS'             => append_sid("{$phpbb_root_path}stats.$phpEx"),
	'U_VIEWNEWS'          => append_sid("{$phpbb_root_path}viewnews.$phpEx"),
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
?>
