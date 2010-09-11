<?php
/**
* This class manages Game, Race and Class 
* 
* Powered by bbdkp © 2010 The bbDkp Project Team
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDkp project.
*
* @package bbDkp.acp
* @version $Id$
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
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
if (! defined('EMED_BBDKP')) 
{
	$user->add_lang ( array ('mods/dkp_admin' ));
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}
class acp_dkp_game extends bbDkp_Admin
{
    var $u_action;
    
   	/** 
	* validationfunction for event : requiredvalues
	* @access public 
	*/ 
    function error_check()
    {
        global $user;
        $this->fv->is_number(request_var('event_value',0.00), $user->lang['FV_NUMBER_VALUE']);
       
        $this->fv->is_filled(array(
           request_var('event_dkpsys_name',' ')  => $user->lang['FV_REQUIRED_DKPSYS_NAME'],
           request_var('event_name',' ')  => $user->lang['FV_REQUIRED_NAME'],
           request_var('event_value', 0.00)    => $user->lang['FV_REQUIRED_VALUE'])
        );
        return $this->fv->is_error();
    }
    
	/** 
	* main ACP dkp event function
	* @param int $id the id of the node who parent has to be returned by function 
	* @param int $mode id of the submenu
	* @access public 
	*/
    function main($id, $mode)
    {
        global $db, $user, $template, $cache;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
        $user->add_lang(array('mods/dkp_admin'));   
        $user->add_lang(array('mods/dkp_common'));   
        $link = '<br /><a href="'.append_sid("index.$phpEx", "i=dkp_event&amp;mode=listevents") . '"><h3>'. $user->lang['RETURN_DKPINDEX'] . '</h3></a>';

         /***  DKPSYS drop-down ***/
        $dkpsys_id = 1;
        $sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default 
                FROM ' . DKPSYS_TABLE . '
                ORDER BY dkpsys_name';
        $resultdkpsys = $db->sql_query($sql);
       
        switch ($mode)
        {
            case 'addfaction':
				$factionadd = (isset($_POST['showfactionadd'])) ? true : false;
				$addnew = (isset($_POST['factionadd'])) ? true : false;
				//user pressed add in list
				if($factionadd)
				{
					
				}
				
                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
            	break;
            
           case 'addrace':
				$raceadd = (isset($_POST['showraceadd'])) ? true : false;
				$addnew = (isset($_POST['addnewrace'])) ? true : false;
				//user pressed add in list
				if($raceadd)
				{
					
				}
                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
            	break;
                        
            case 'addclass':
        		$classadd = (isset($_POST['showclassadd'])) ? true : false;
				$addnew = (isset($_POST['addnewclass'])) ? true : false;
				//user pressed add in list
				if($classadd)
				{
					
				}
            	$this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
                break;
                        
            case 'listgames':

            	$showrace = (isset($_POST['raceadd'])) ? true : false;
            	$showclass = (isset($_POST['classadd'])) ? true : false;
            	
            	if($showrace)
            	{
					redirect(append_sid("index.$phpEx", "i=dkp_game&amp;mode=addrace"));            		
            		break;
            	}
            	
            	if($showclass)
            	{
					redirect(append_sid("index.$phpEx", "i=dkp_game&amp;mode=addclass"));            		
            		break;
            	}
            	
                $sort_order = array(
                    0 => array('race_id', 'race_id desc'),
                    1 => array('race_name', 'race_name, event_name desc'),
                    2 => array('faction_name desc', 'faction_name, race_name desc')
                );
                
                $sort_order2 = array(
                    0 => array('class_id', 'class_id desc'),
                    1 => array('class_name', 'class_name desc'),
                    2 => array('class_armor_type', 'class_armor_type, class_id desc'),
                    3 => array('class_min_level', 'class_min_level, class_id desc'),
                    4 => array('class_max_level', 'class_max_level, class_id desc'),
                );
                
                $current_order = switch_order($sort_order);
                $current_order2 = switch_order($sort_order2);
                
                // list the factions
				$total_factions = 0;
                $sql_array = array(
				    'SELECT'    => 	' f.faction_id, f.faction_name  ', 
				    'FROM'      => array(
				        FACTION_TABLE	=> 'f',
				    	),
				    );
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);			   
                while ( $row = $db->sql_fetchrow($result) )
                {
                	$total_factions++;
                    $template->assign_block_vars('faction_row', array(
                        'FACTIONID' 	=> $row['faction_id'],
                        'FACTIONNAME' 	=> $row['faction_name'])
                    );
                }
                $db->sql_freeresult($result);

                // list the races
				$total_races = 0;
                $sql_array = array(
				    'SELECT'    => 	' r.race_id, r.race_name, r.race_faction_id, r.race_hide, f.faction_name  ', 
				    'FROM'      => array(
				        RACE_TABLE 		=> 'r',
				        FACTION_TABLE	=> 'f',
				    	),
				    'WHERE'		=> 'r.race_faction_id = f.faction_id' ,
					'ORDER_BY'	=> $current_order['sql'],
				    );
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);			   
                while ( $row = $db->sql_fetchrow($result) )
                {
                	$total_races++;
                    $template->assign_block_vars('race_row', array(
                        'U_VIEW_RACE' =>  append_sid("index.$phpEx", "i=dkp_game&amp;mode=addrace&amp;r=". $row['race_id']),
                        'RACEID' 	=> $row['race_id'],
                        'RACENAME' 		=> $row['race_name'],
                        'FACTIONNAME' 	=> $row['faction_name'])
                    );
                }
                $db->sql_freeresult($result);
                
                // list the classes
                $total_classes = 0;
                $sql_array = array(
				    'SELECT'    => 	' c.class_id, c.class_name, c.class_hide, c.class_min_level, class_max_level, c.class_armor_type , c.imagename ', 
				    'FROM'      => array(
				        CLASS_TABLE 	=> 'c',
				    	),
					'ORDER_BY'	=> $current_order2['sql'],
				    );
				    
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);			   
                while ( $row = $db->sql_fetchrow($result) )
                {
                	 $total_classes++;
                    $template->assign_block_vars('class_row', array(
                        'U_VIEW_CLASS' =>  append_sid("index.$phpEx", "i=dkp_game&amp;mode=addclass&amp;r=". $row['race_id']),
                        'CLASSID' 		=> $row['class_id'],
                        'CLASSNAME' 	=> $row['class_name'],
                    	'CLASSARMOR' 	=> $row['class_armor_type'], 	
                    	'CLASSMIN' 		=> $row['class_min_level'], 	
                    	'CLASSMAX' 		=> $row['class_max_level'], 	
                        'CLASSHIDE' 	=> $row['class_hide'], 	
                    	'CLASSIMAGE'	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',
                    )
                    );
                }
                $db->sql_freeresult($result);
               
                $template->assign_vars(array(
                    'L_TITLE'         => $user->lang['ACP_LISTGAME'],
                    'L_EXPLAIN'       => $user->lang['ACP_LISTGAME_EXPLAIN'],
                    'O_RACEID' 		  => $current_order['uri'][0],
                    'O_RACENAME' 	  => $current_order['uri'][1],
                    'O_FACTIONNAME'   => $current_order['uri'][2],   
                    'U_LIST_GAMES' 	  => append_sid("index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;"),    
                   	'LISTFACTION_FOOTCOUNT' => sprintf($user->lang['LISTFACTION_FOOTCOUNT'], $total_factions),
                    'LISTRACE_FOOTCOUNT' => sprintf($user->lang['LISTRACE_FOOTCOUNT'], $total_races),
                	'LISTCLASS_FOOTCOUNT' => sprintf($user->lang['LISTCLASS_FOOTCOUNT'], $total_classes),
                    
                )
                );

                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
               
            break;

       
        }
    }
}

?>