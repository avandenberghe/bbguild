<?php
/**
* This class manages Game, Race and Class 
* 
* Powered by bbdkp © 2010 The bbDKP Project Team
* If you use this software and find it to be useful, we ask that you
* retain the copyright notice below.  While not required for free use,
* it will help build interest in the bbDKP project.
*
* @package bbDKP.acp
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

class acp_dkp_game extends bbDKP_Admin
{
    var $u_action;
	/** 
	* main ACP game function
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
        $link = '<br /><a href="'.append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames") . '"><h3>'. $user->lang['RETURN_DKPINDEX'] . '</h3></a>';

         /***  DKPSYS drop-down ***/
        $dkpsys_id = 1;
        $sql = 'SELECT dkpsys_id, dkpsys_name, dkpsys_default 
                FROM ' . DKPSYS_TABLE . '
                ORDER BY dkpsys_name';
        $resultdkpsys = $db->sql_query($sql);
        
        $form_key = 'acp_dkp_game';
		add_form_key($form_key);
				
        switch ($mode)
        {
            case 'addfaction':
				$addnew = (isset($_POST['factionadd'])) ? true : false;

				if ($addnew)
				{
                   	if (!check_form_key('acp_dkp_game'))
					{
						trigger_error('FORM_INVALID');
					}
					
					$sql = 'select max(faction_id) as max from ' . FACTION_TABLE; 
					$result = $db->sql_query($sql);	
					$factionid = (int) $db->sql_fetchfield('max', 0 ,$result );	
					$db->sql_freeresult($result);
					
					
					$factionname = utf8_normalize_nfc(request_var('factionname', '', true));
					$data = array( 
						'faction_name'		=> (string) $factionname,
						'faction_id'		=> (int) $factionid + 1,
						'faction_hide'		=> 0,
					);
					
					$db->sql_transaction('begin');
					
					$sql = 'INSERT INTO ' . FACTION_TABLE . ' ' . $db->sql_build_array('INSERT', $data);
					$db->sql_query($sql);			
									
					$db->sql_transaction('commit');
					
					trigger_error( sprintf( $user->lang['ADMIN_ADD_FACTION_SUCCESS'], $factionname) . $link, E_USER_NOTICE);
						
				}

				// send parameters to template
                   $template->assign_vars( array(
                   	 'U_ACTION'		=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addfaction'),  
                ));		
				
                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
            	break;
            
           case 'addrace':
				$raceadd = (isset($_POST['add'])) ? true : false;
				$raceupdate = (isset($_POST['update'])) ? true : false;

        		if ( $raceadd || $raceupdate )
                {
                   	if (!check_form_key('acp_dkp_game'))
					{
						trigger_error('FORM_INVALID');
					}
       			}
       			
				$id = request_var('race_id', 0);
				$racename = utf8_normalize_nfc(request_var('racename', '', true));
				$factionid = request_var('faction', 0 );
				$race_imagename_m = utf8_normalize_nfc(request_var('image_male_small', '', true));
				$race_imagename_f = utf8_normalize_nfc(request_var('image_female_small', '', true));
				
				if($raceadd)
				{
					// add the race	 to db
					$sql = 'select count(*) as count from ' . RACE_TABLE . ' where race_id  = ' . $id; 
					$result = $db->sql_query($sql);	
					if( (int) $db->sql_fetchfield('count', 0 ,$result ) > 0 )
					{
						 trigger_error( sprintf( $user->lang['ADMIN_ADD_RACE_FAILED'], $id) . $link, E_USER_WARNING);	
					}
					$db->sql_freeresult($result);
					$data = array( 
						'race_id'				=> (int) $id,
						'race_faction_id'		=> (int) $factionid,
						'image_male_small'		=> (string) $race_imagename_m,
						'image_female_small'	=> (string) $race_imagename_f,
						'race_hide'				=> 0,
					);
					
					$db->sql_transaction('begin');
					$sql = 'INSERT INTO ' . RACE_TABLE . ' ' . $db->sql_build_array('INSERT', $data);
					$db->sql_query($sql);
						
					$names = array(
						'attribute_id'	=>  $id,
						'language'		=>  $config['bbdkp_lang'],
						'attribute'		=>  'race', 
						'name'			=> (string) $racename,
						'name_short'	=> (string) $racename,	
					);
					
					$sql = 'INSERT INTO ' . BB_LANGUAGE . ' ' . $db->sql_build_array('INSERT', $names);
					$db->sql_query($sql);		
									
					$db->sql_transaction('commit');
					
					trigger_error( sprintf( $user->lang['ADMIN_ADD_RACE_SUCCESS'], $racename) . $link, E_USER_NOTICE);
				}
				
				if ($raceupdate)
				{
					// update the race to db
					$data = array( 
						'race_faction_id'		=> (int) $factionid,
						'image_male_small'		=> (string) $race_imagename_m,
						'image_female_small'	=> (string) $race_imagename_f,					
					);
					
					$db->sql_transaction('begin');
					
					$sql = 'UPDATE ' . RACE_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) .  '  
						    WHERE race_id = ' . $id ;
					$db->sql_query($sql);	

					$names = array(
						'name'		=> (string) $racename,
						'name_short'=> (string) $racename,
					);
					
					$sql = 'UPDATE ' . BB_LANGUAGE . ' set ' . $db->sql_build_array('UPDATE', $names) . ' WHERE attribute_id = ' . $id . 
						" AND attribute='race'  AND language= '" . $config['bbdkp_lang'] ."'";
					$db->sql_query($sql);	
					
					$db->sql_transaction('commit');
						
					trigger_error( sprintf( $user->lang['ADMIN_UPDATE_RACE_SUCCESS'], $racename) . $link, E_USER_NOTICE);
					
				}
                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
            	break;
                        
            case 'addclass':
            	
        		$classadd 	 = (isset($_POST['add'])) ? true : false;
        		$classupdate = (isset($_POST['update'])) ? true : false;
        	    
        		if ( $classadd || $classupdate )
                {
                   	if (!check_form_key('acp_dkp_game'))
					{
						trigger_error('FORM_INVALID');
					}
       			}
       			
				//user pressed add or update in list
				$classname = utf8_normalize_nfc(request_var('class_name', '', true));
				$class_id = request_var('class_id', 0);
				$min = request_var('class_level_min', 0); 
				$max = request_var('class_level_max', 0); 
				$armorytype = request_var('armory', '' );
				$image = request_var('image', '' );
				$colorcode = request_var('classcolor', '' );

				if($classadd)
				{
					// add the class to db
					$sql = 'select count(*) as count from ' . CLASS_TABLE . ' where class_id  = ' . $class_id; 
					$result = $db->sql_query($sql);	
					if( (int) $db->sql_fetchfield('count', 0 ,$result ) > 0 )
					{
						 trigger_error( sprintf( $user->lang['ADMIN_ADD_CLASS_FAILED'], $class_id) . $link, E_USER_WARNING);	
					}
					$db->sql_freeresult($result);
					
					$data = array( 
						'class_id'				=> (int) $class_id,
						'class_min_level'		=> (int) $min,
						'class_max_level'		=> (int) $max,
						'class_armor_type'		=> (string) $armorytype,
						'imagename'				=> $image,
						'class_hide'			=> 0,
						'colorcode'				=> $colorcode,
					);
					
					$db->sql_transaction('begin');
					
					$sql = 'INSERT INTO ' . CLASS_TABLE . ' ' . $db->sql_build_array('INSERT', $data);
					$db->sql_query($sql);							

					$id = $db->sql_nextid();
					 
					$names = array(
						'attribute_id'	=>  $class_id,
						'language'		=>  $config['bbdkp_lang'],
						'attribute'		=>  'class', 
						'name'			=> (string) $classname,
						'name_short'	=> (string) $classname,	
					);
					
					$sql = 'INSERT INTO ' . BB_LANGUAGE . ' ' . $db->sql_build_array('INSERT', $names);
					$db->sql_query($sql);			

					$db->sql_transaction('commit');
					
					trigger_error( sprintf( $user->lang['ADMIN_ADD_CLASS_SUCCESS'], $classname) . $link, E_USER_NOTICE);
					
				}
				
				if ($classupdate)
				{
					// update class in db

					// get unique key !!
					$class_id0 = request_var('class_id0', 0); 
					//get pk
					$c_index = request_var('c_index', 0);
					//new classid
					$class_id = request_var('class_id', 0); 
					
					// check for unique classid exception : if this class id exists already 
					$sql = 'select count(*) as count from ' . CLASS_TABLE . ' where c_index != ' . $c_index . " and class_id = '" . $db->sql_escape($class_id0)  . "'"; 
					$result = $db->sql_query($sql);	
					if( (int) $db->sql_fetchfield('count', 0 ,$result ) > 0 )
					{	// nubcake earned !
						 trigger_error( sprintf( $user->lang['ADMIN_ADD_CLASS_FAILED'], $class_id0) . $link, E_USER_WARNING);	
					}
					$db->sql_freeresult($result);
					
					// ok proceed
					$data = array( 
						'class_id'				=> (int) $class_id,
						'class_min_level'		=> (int) $min,
						'class_max_level'		=> (int) $max,
						'class_armor_type'		=> (string) $armorytype,
						'imagename'				=> $image,
						'class_hide'			=> 0,
						'colorcode'				=> $colorcode,
					);
					$db->sql_transaction('begin');
					$sql = 'UPDATE ' . CLASS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) .  '  
						    WHERE c_index = ' . $c_index ;
					$db->sql_query($sql);		

					// now update the language table!
					$names = array(
						'attribute_id'		=> (string) $class_id, //new classid
						'name'				=> (string) $classname, 
						'name_short'		=> (string) $classname,	
					);
					
					$sql = 'UPDATE ' . BB_LANGUAGE . ' set ' . $db->sql_build_array('UPDATE', $names) . ' WHERE attribute_id = ' . $class_id0 . 
						" AND attribute='class'  AND language= '" . $config['bbdkp_lang'] ."'";
					$db->sql_query($sql);	
					
					$db->sql_transaction('commit');
						
					trigger_error( sprintf( $user->lang['ADMIN_UPDATE_CLASS_SUCCESS'], $classname) . $link, E_USER_NOTICE);
					
				}
            	$this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
                break;
                        
            case 'listgames':

            	$addrace = (isset($_POST['showraceadd'])) ? true : false;
            	$addclass = (isset($_POST['showclassadd'])) ? true : false;
            	$addfaction = (isset($_POST['showfactionadd'])) ? true : false;
            	
            	$deletefaction = (isset($_GET['factiondelete'])) ? true : false;
            	$racedelete = (isset($_GET['racedelete'])) ? true : false;
            	$classdelete = (isset($_GET['classdelete'])) ? true : false;

            	$raceedit = (isset($_GET['raceedit'])) ? true : false;
            	$classedit = (isset($_GET['classedit'])) ? true : false;
            	
            	if($addfaction)
            	{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addfaction"));            		
            		break;
            	}
            	
                // user pressed delete faction
            	if ($deletefaction)
            	{
            		$id = request_var('id', 0); 
                	$sql_array = array(
					    'SELECT'    => 	' count(*) as factioncount  ', 
					    'FROM'      => array(
					        RACE_TABLE 		=> 'r',
					        FACTION_TABLE	=> 'f',
					    	),
					    'WHERE' => 'r.race_faction_id = f.faction_id and f.f_index =  ' . $id
				    );        
				    $sql = $db->sql_build_query('SELECT', $sql_array);    		
            		$result = $db->sql_query($sql);	
					$factioncount = (int) $db->sql_fetchfield('factioncount', 0 ,$result );	
					$db->sql_freeresult($result);
					if ($factioncount == 0)
					{
						// ask for permission
						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . FACTION_TABLE . ' WHERE f_index =' . $id;  
							$db->sql_query($sql);
							
							trigger_error(sprintf($user->lang['ADMIN_DELETE_FACTION_SUCCESS'], $id) . $link, E_USER_WARNING);
						}
						else
						{
							// get field content
							$s_hidden_fields = build_hidden_fields(array(
								'delete'	=> true,
								'id'		=> $id,
								)
							);
							confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_FACTION'], $id), $s_hidden_fields);
						}
					
					}
					else 
					{
						//no really ?
						trigger_error(sprintf($user->lang['ADMIN_DELETE_FACTION_FAILED'], $id) . $link, E_USER_WARNING);
					}
            		
            	}

            	// user pressed race add / edit, load acp_addrace	
            	if($raceedit || $addrace)
            	{
            		if(isset ($_GET['id']))
            		{
	            		$id = request_var('id', 0); 
	            		//edit					
						$sql_array = array(
					    'SELECT'    => 	'  r.race_id, l.name as race_name, r.race_faction_id,  r.image_female_small, r.image_male_small ', 
					    'FROM'      => array(
								RACE_TABLE 		=> 'r',
								BB_LANGUAGE 	=> 'l',
									),
						'WHERE'		=> " r.race_id = l.attribute_id 
										AND l.attribute='race' 
										AND l.language= '" . $config['bbdkp_lang'] ."'
										AND r.race_id = " . $id ,
					    );
						
					    $sql = $db->sql_build_query('SELECT', $sql_array);
					    $result = $db->sql_query($sql);	
						$factionid = $db->sql_fetchfield('race_faction_id', 0 ,$result );	
						$race_name = $db->sql_fetchfield('race_name', 0 ,$result );	
						$race_imagename_m = $db->sql_fetchfield('image_male_small', 0 ,$result );
						$race_imagename_f = $db->sql_fetchfield('image_female_small', 0 ,$result );
						
						$db->sql_freeresult($result);
	            		
	            		// faction dropdown
						$sql_array = array(
					    'SELECT'    => 	' f.faction_name, f.faction_id ', 
					    'FROM'      => array(
								FACTION_TABLE 	=> 'f',
									),
						'ORDER_BY'	=> 'faction_id asc ',
					    );
					    
						$sql = $db->sql_build_query('SELECT', $sql_array);
	                    $result = $db->sql_query($sql);
	                    $s_faction_options= '';
						while ( $row = $db->sql_fetchrow($result) )
	                    {
	                    	$selected = ($row['faction_id'] == $factionid) ? ' selected="selected"' : '';
							$s_faction_options .= '<option value="' . $row['faction_id'] . '" '.$selected.'> ' . $row['faction_name'] . '</option>';                    
	                    }
	                    $db->sql_freeresult($result);
	                    
	                    // send parameters to template
	                    $template->assign_vars( array(
			                    
			                    'RACE_ID' 				=> $id  ,
			                    'RACE_NAME' 		    => $race_name  ,
								'S_FACTIONLIST_OPTIONS'	=> $s_faction_options, 
	                    		'S_RACE_IMAGE_M_EXISTS'	=> (strlen($race_imagename_m) > 1) ? true : false,
	                    		'RACE_IMAGENAME_M' 	 	=> $race_imagename_m, 
	                    		'RACE_IMAGE_M' 		 	=> (strlen($race_imagename_m) > 1) ? $phpbb_root_path . "images/race_images/" . $race_imagename_m . ".png" : '',
	                    		'S_RACE_IMAGE_F_EXISTS'	=> (strlen($race_imagename_f) > 1) ? true : false, 
	                    		'RACE_IMAGENAME_F' 	 	=> $race_imagename_f, 
	                    		'RACE_IMAGE_F' 		 	=> (strlen($race_imagename_f) > 1) ? $phpbb_root_path . "images/race_images/" . $race_imagename_f . ".png" : '',  	                    
								'S_ADD'   				=> FALSE,
	                    		'U_ACTION'				=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addrace'),  
	                    		'MSG_NAME_EMPTY'   		=> $user->lang['FV_REQUIRED_NAME'],
	                    		
		                ));
            		}
            		else 
            		{	// build add form
						$sql_array = array(
						    'SELECT'    => 	' f.faction_name, f.faction_id ', 
						    'FROM'      => array(
									FACTION_TABLE 	=> 'f',
										),
							'ORDER_BY'	=> 'faction_id asc ',
						    );
						$sql = $db->sql_build_query('SELECT', $sql_array);
		                $result = $db->sql_query($sql);
		                $s_faction_options= '';
						while ( $row = $db->sql_fetchrow($result) )
		                {
							$s_faction_options .= '<option value="' . $row['faction_id'] . '" > ' . $row['faction_name'] . '</option>';                    
		                }
			            $db->sql_freeresult($result);
			            $template->assign_vars( array(
							'S_FACTIONLIST_OPTIONS'	=> $s_faction_options, 
							'S_ADD'   				=> TRUE,
			            	'U_ACTION'				=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addrace'),
			            	'MSG_NAME_EMPTY'   	=> $user->lang['FV_REQUIRED_NAME'],
		                ));
            		}
            		
					$this->page_title = 'ACP_LISTGAME';
                	$this->tpl_name = 'dkp/acp_addrace';
            		break;
            	}
            	
            	// user pressed delete race
            	if ($racedelete)
            	{
            		$id = request_var('id', 0); 
                	$sql_array = array(
					    'SELECT'    => 	' count(*) as racecount  ', 
					    'FROM'      => array(
					        MEMBER_LIST_TABLE 	=> 'm',
					        RACE_TABLE			=> 'r',
					    	),
					    'WHERE' => 'm.member_race_id = r.race_id and r.race_id =  ' . $id
				    );        
				    $sql = $db->sql_build_query('SELECT', $sql_array);    		
            		$result = $db->sql_query($sql);	
					$racecount = (int) $db->sql_fetchfield('racecount', 0 ,$result );	
					$db->sql_freeresult($result);
					if ($racecount == 0)
					{
						// ask for permission
						if (confirm_box(true))
						{
							$db->sql_transaction('begin');
							
							$sql = 'DELETE FROM ' . RACE_TABLE . ' WHERE race_id =' . $id;  
							$db->sql_query($sql);
							
							$sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE language= '" . $config['bbdkp_lang'] . "' and attribute = 'race' and attribute_id= " . $id;  
							$db->sql_query($sql);
							
							$db->sql_transaction('commit');
													
							trigger_error(sprintf($user->lang['ADMIN_DELETE_RACE_SUCCESS'], $id) . $link, E_USER_WARNING);
								
						}
						else
						{
							// get field content
							$s_hidden_fields = build_hidden_fields(array(
								'delete'	=> true,
								'id'		=> $id,
								)
							);
							confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_RACE'], $id), $s_hidden_fields);
						}
					
					}
					else 
					{
						//no really ?
						trigger_error(sprintf($user->lang['ADMIN_DELETE_RACE_FAILED'], $id) . $link, E_USER_WARNING);
					}
            		
            	}
            	            	
            	if($classedit || $addclass)
            	{
            		// Load template for adding/editing
					$armortype = array(
						'CLOTH' 	=> $user->lang['CLOTH'], 
						'LEATHER'   => $user->lang['LEATHER'], 
						'MAIL' 		=> $user->lang['MAIL'], 
						'PLATE'		=> $user->lang['PLATE'], 
					);
					
					if( isset($_GET['id']))
            		{
            			//edit this class_id
	            		$id = request_var('id', 0); 
						
						$sql_array = array(
					    'SELECT'    => 	' c.c_index, c.class_id, l.name as class_name, c.class_min_level, c.class_max_level, c.class_armor_type, c.imagename, c.colorcode ', 
					    'FROM'      => array(
								CLASS_TABLE 	=> 'c',
								BB_LANGUAGE 	=> 'l',
									),
						'WHERE'		=> " c.class_id = l.attribute_id 
										AND l.attribute='class' 
										AND l.language= '" . $config['bbdkp_lang'] ."'
										AND c.class_id = " . $id ,
					    );						
					    $sql = $db->sql_build_query('SELECT', $sql_array);    		
						$result = $db->sql_query($sql);	
						$c_index  =  $db->sql_fetchfield('c_index', 0 ,$result );
						$class_id = (int)  $db->sql_fetchfield('class_id', 0 ,$result );
						$class_name = (string)  $db->sql_fetchfield('class_name', 0 ,$result );	
						$class_min_level = (int) $db->sql_fetchfield('class_min_level', 0 ,$result );	
						$class_max_level = (int) $db->sql_fetchfield('class_max_level', 0 ,$result );	
						$class_armor_type = (string) $db->sql_fetchfield('class_armor_type', 0 ,$result );	
						$class_imagename = (string) $db->sql_fetchfield('imagename', 0 ,$result );	
						$class_colorcode = (string) $db->sql_fetchfield('colorcode', 0 ,$result );
						
						$db->sql_freeresult($result);

						$s_armor_options = ''; 
						foreach ( $armortype as $armor => $armorname )
						{
							//
				        	$selected = ($armor == $class_armor_type) ? ' selected="selected"' : '';
							$s_armor_options .= '<option value="' . $armor . '" '.$selected.'> ' . $armorname . '</option>';                    
						}
						
						// send parameters to template
	                    $template->assign_vars( array(
	                    		'C_INDEX' 				 => $c_index, 
			                    'CLASS_ID' 			 => $class_id  ,
			                    'CLASS_NAME' 		 => $class_name  ,
								'CLASS_MIN' 		 => $class_min_level  ,
	                    		'CLASS_MAX' 		 => $class_max_level  ,
	                    		'S_ARMOR_OPTIONS' 	 => $s_armor_options ,
	                    		'CLASS_IMAGENAME' 	 => $class_imagename,
	                    		'COLORCODE' 		 => ($class_colorcode == '') ? '#123456' : $class_colorcode,
	                    		'CLASS_IMAGE' 		 => (strlen($class_imagename) > 1) ? $phpbb_root_path . "images/class_images/" . $class_imagename . ".png" : '',  
								'S_CLASS_IMAGE_EXISTS' => (strlen($class_imagename) > 1) ? true : false, 
								'S_ADD'   			 => FALSE,
	                    		'U_ACTION'			 => append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addclass'),
	                    		'MSG_NAME_EMPTY'   	 => $user->lang['FV_REQUIRED_NAME'],
		                ));
            			
						

            		}
            		else 
            		{
            			// new class
            			$s_armor_options = '';
           		        foreach ( $armortype as $armor => $armorname )
						{
							$s_armor_options .= '<option value="' . $armor . '" > ' . $armorname . '</option>';                    
						}
            			// send parameters to template
	                    $template->assign_vars( array(
	                    		'S_ARMOR_OPTIONS' 		=> $s_armor_options  ,
								'S_ADD'   				=> TRUE,
	                    		'U_ACTION'				=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=dkp_game&amp;mode=addclass'),
	                    		'MSG_NAME_EMPTY'   		=> $user->lang['FV_REQUIRED_NAME'],
		                ));
		                
            		}
					
					$this->page_title = 'ACP_LISTGAME';
                	$this->tpl_name = 'dkp/acp_addclass';
            		break;
            	}
            	
            	
                // user pressed delete class in the listing
            	if ($classdelete)
            	{
            		//unique key
            		$class_id = request_var('id', 0); 
            		
            		// see if there are mambers in this class
                	$sql_array = array(
					    'SELECT'    => 	' c.class_id, count(*) as classcount  ', 
					    'FROM'      => array(
					        MEMBER_LIST_TABLE 	=> 'm',
					        CLASS_TABLE			=> 'c',
					    	),
					    'WHERE' => 'm.member_class_id = c.class_id and c.class_id =  ' . $class_id
				    );
				    
				    $sql = $db->sql_build_query('SELECT', $sql_array);    		
            		$result = $db->sql_query($sql);	
					$classcount = (int) $db->sql_fetchfield('classcount', 0 ,$result );
					$db->sql_freeresult($result);
					if ($classcount == 0)
					{
						
						// ask for permission
						if (confirm_box(true))
						{
							$db->sql_transaction('begin');
							
							$sql = 'DELETE FROM ' . CLASS_TABLE . ' WHERE class_id  = ' . $class_id;  
							$db->sql_query($sql);
							
							$sql = 'DELETE FROM ' . BB_LANGUAGE . " WHERE language= '" . $config['bbdkp_lang'] . "' and attribute = 'class' and attribute_id= " . $class_id;  
							$db->sql_query($sql);
							
							$db->sql_transaction('commit');
							
							trigger_error(sprintf($user->lang['ADMIN_DELETE_CLASS_SUCCESS'], $class_id) . $link, E_USER_WARNING);
						}
						else
						{
							// get field content
							$s_hidden_fields = build_hidden_fields(array(
								'delete'	=> true,
								'id'		=> $class_id,
								)
							);
							confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_CLASS'], $class_id), $s_hidden_fields);
						}
					
					}
					else 
					{
						//no really ?
						trigger_error(sprintf($user->lang['ADMIN_DELETE_CLASS_FAILED'], $class_id) . $link, E_USER_WARNING);
					}
            		
            	}            	

            	/********************
            	 * template filling
            	 *******************/
                $sort_order = array(
                    0 => array('race_id', 'race_id desc'),
                    1 => array('race_name', 'race_name desc'),
                    2 => array('faction_name desc', 'faction_name, race_name desc')
                );
                
  
                $current_order = switch_order($sort_order);
                
                // list the factions
				$total_factions = 0;
                $sql_array = array(
				    'SELECT'    => 	'f_index, f.faction_id, f.faction_name  ', 
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
                        'ID' 			=> $row['f_index'],
                        'FACTIONID' 	=> $row['faction_id'],
                        'FACTIONNAME' 	=> $row['faction_name'], 
                    	'U_DELETE' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;factiondelete=1&amp;id={$row['f_index']}"))  
                    );
                }
                $db->sql_freeresult($result);

                // list the races
				$total_races = 0;
                $sql_array = array(
				    'SELECT'    => 	' r.race_id, l.name as race_name, r.race_faction_id, r.race_hide, f.faction_name , r.image_female_small, r.image_male_small ', 
				    'FROM'      => array(
				        RACE_TABLE 		=> 'r',
				        FACTION_TABLE	=> 'f',
				        BB_LANGUAGE		=> 'l', 
				    	),
				    'WHERE'		=> "r.race_faction_id = f.faction_id
				    				AND l.attribute_id = r.race_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'race' ",   
					'ORDER_BY'	=> $current_order['sql'],
				    );
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);			   
                while ( $row = $db->sql_fetchrow($result) )
                {
                	$total_races++;
                    $template->assign_block_vars('race_row', array(
                        'U_VIEW_RACE' =>  append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addrace&amp;r=". $row['race_id']),
                        'RACEID' 	=> $row['race_id'],
                        'RACENAME' 		=> $row['race_name'],
                        'FACTIONNAME' 	=> $row['faction_name'], 
                    	'RACE_IMAGE_M' 	=> (strlen($row['image_male_small']) > 1) ? $phpbb_root_path . "images/race_images/" . $row['image_male_small'] . ".png" : '',
                    	'RACE_IMAGE_F' 	=> (strlen($row['image_female_small']) > 1) ? $phpbb_root_path . "images/race_images/" . $row['image_female_small'] . ".png" : '',
                    	'S_RACE_IMAGE_M_EXISTS' => (strlen($row['image_male_small']) > 1) ? true : false, 
                    	'S_RACE_IMAGE_F_EXISTS' => (strlen($row['image_female_small']) > 1) ? true : false, 
                    	'U_DELETE' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;racedelete=1&amp;id={$row['race_id']}"), 
                    	'U_EDIT' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;raceedit=1&amp;id={$row['race_id']}"), 
                    )  
                    );
                }
                $db->sql_freeresult($result);
                
                // list the classes
                $sort_order2 = array(
                    0 => array('class_id', 'class_id desc'),
                    1 => array('class_name', 'class_name desc'),
                    2 => array('class_armor_type', 'class_armor_type, class_id desc'),
                    3 => array('class_min_level', 'class_min_level, class_id desc'),
                    4 => array('class_max_level', 'class_max_level, class_id desc'),
                );
                $current_order2 = switch_order($sort_order2, "o1");
                
                $total_classes = 0;
                $sql_array = array(
				    'SELECT'    => 	'c.c_index, c.class_id, l.name as class_name, c.class_hide, c.class_min_level, class_max_level, c.class_armor_type , c.imagename, c.colorcode ', 
				    'FROM'      => array(
				        CLASS_TABLE 	=> 'c',
				        BB_LANGUAGE		=> 'l', 
				    	),
				    'WHERE'		=> " l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",   				    	
					'ORDER_BY'	=> $current_order2['sql'],
				    );
				    
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query($sql);			   
                while ( $row = $db->sql_fetchrow($result) )
                {
                	 $total_classes++;
                    $template->assign_block_vars('class_row', array(
                        'U_VIEW_CLASS' =>  append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=addclass&amp;r=". $row['class_id']),
						'C_INDEX' 		=> $row['c_index'],
                    	'CLASSID' 		=> $row['class_id'],
                        'CLASSNAME' 	=> $row['class_name'],
                    	'COLORCODE' 	=> $row['colorcode'],
                    	'CLASSARMOR' 	=> $user->lang[$row['class_armor_type']], 	
                    	'CLASSMIN' 		=> $row['class_min_level'], 	
                    	'CLASSMAX' 		=> $row['class_max_level'], 	
                        'CLASSHIDE' 	=> $row['class_hide'], 	
                    	'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false, 
                    	'CLASSIMAGE'	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',
                        'U_DELETE' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;classdelete=1&amp;id={$row['class_id']}"), 
                    	'U_EDIT' 		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;classedit=1&amp;id={$row['class_id']}"), 
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
	                'O_CLASSID'   	  => $current_order2['uri'][0], 
	                'O_CLASSNAME'     => $current_order2['uri'][1], 
	                'O_CLASSARMOR'    => $current_order2['uri'][2], 
	                'O_CLASSMIN'      => $current_order2['uri'][3], 
	                'O_CLASSMAX'      => $current_order2['uri'][4], 
                    'U_LIST_GAMES' 	  => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_game&amp;mode=listgames&amp;"),  
                   	'LISTFACTION_FOOTCOUNT' => sprintf($user->lang['LISTFACTION_FOOTCOUNT'], $total_factions),
                    'LISTRACE_FOOTCOUNT' => sprintf($user->lang['LISTRACE_FOOTCOUNT'], $total_races),
                	'LISTCLASS_FOOTCOUNT' => sprintf($user->lang['LISTCLASS_FOOTCOUNT'], $total_classes),
                    'U_ACTION'			=> $this->u_action,
                )
                );

                $this->page_title = 'ACP_LISTGAME';
                $this->tpl_name = 'dkp/acp_'. $mode;
               
            break;

       
        }
    }
}

?>