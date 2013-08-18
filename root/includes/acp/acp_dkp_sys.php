<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */

// don't add this file to namespace bbdkp
/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
if (! defined ( 'EMED_BBDKP' ))
{
	$user->add_lang ( array ('mods/dkp_admin' ) );
	trigger_error ( $user->lang ['BBDKPDISABLED'], E_USER_WARNING );
}
if (!class_exists('\bbdkp\Admin'))
{
	require ("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}
if (!class_exists('\bbdkp\Pool'))
{
	require("{$phpbb_root_path}includes/bbdkp/Points/Pool.$phpEx");
}

/**
 * This class manages admin settings
 * 
 * @package bbDKP
 */  
 class acp_dkp_sys extends \bbdkp\Admin
{
	var $u_action;
	var $link;
	var $dkpsys; 
	
	function main($id, $mode)
	{
		global $user, $template, $config, $phpbb_admin_path, $phpEx;
		
		$this->link = '<br /><a href="' . append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=listdkpsys" ) .'"><h3>'. $user->lang['RETURN_DKPPOOLINDEX'].'</h3></a>';
		$this->tpl_name = 'dkp/acp_' . $mode;
		
		switch ($mode)
		{
			case 'adddkpsys' :
				$update = false;
				$add = (isset ( $_POST ['add'] )) ? true : false;
				$submit = (isset ( $_POST ['update'] )) ? true : false;
				
                if ( $add || $submit)
                {
                  	if (!check_form_key('adddkpsys'))
					{
						trigger_error('FORM_INVALID');
					}
      			}
      			
				if ($add)
				{
					$this->dkpsys = new \bbdkp\Pool();
					$this->dkpsys->dkpsys_name =utf8_normalize_nfc (request_var ( 'dkpsys_name', '', true ));  
					$this->dkpsys->dkpsys_status = request_var ( 'dkpsys_status', 'Y' );
					$this->dkpsys->dkpsys_default = request_var ( 'dkpsys_default', 'Y' );
					$this->dkpsys->add(); 
					$success_message = sprintf ( $user->lang ['ADMIN_ADD_DKPSYS_SUCCESS'], $this->dkpsys->dkpsys_name );
					trigger_error ( $success_message . $this->link );
				}
				
				if ($submit)
				{
					$this->dkpsys = new \bbdkp\Pool(request_var('hidden_id', 0));
					$olddkpsys = new \bbdkp\Pool(request_var('hidden_id', 0));			
					
					$this->dkpsys->dkpsys_name =utf8_normalize_nfc (request_var ( 'dkpsys_name', '', true ));
					$this->dkpsys->dkpsys_status = request_var ( 'dkpsys_status', 'Y' );
					$this->dkpsys->dkpsys_default = request_var ( 'dkpsys_default', 'Y'); 
					
					$this->dkpsys->update($olddkpsys); 
					unset($olddkpsys); 
					$success_message = sprintf ( $user->lang ['ADMIN_UPDATE_DKPSYS_SUCCESS'], $this->dkpsys->dkpsys_id, 
							$this->dkpsys->dkpsys_name, $this->dkpsys->dkpsys_status  );
					
					trigger_error ( $success_message . $this->link );
				}
				
				$form_key = 'adddkpsys';
				add_form_key($form_key);
				
				$id = request_var ( URI_DKPSYS, 0 );
				$this->dkpsys = new \bbdkp\Pool($id);
				
				$template->assign_vars ( array (
					'DKPSYS_ID' => $this->dkpsys->dkpsys_id, 
					'L_TITLE' 	=> $user->lang ['ACP_ADDDKPSYS'], 
					'L_EXPLAIN' => $user->lang ['ACP_ADDDKPSYS_EXPLAIN'], 				
					'DKPSYS_NAME' => $this->dkpsys->dkpsys_name, 
					'DKPSYS_STATUS' => $this->dkpsys->dkpsys_status,  
					'MSG_NAME_EMPTY' => $user->lang ['FV_REQUIRED_NAME'], 
					'MSG_STATUS_EMPTY' => $user->lang ['FV_REQUIRED_STATUS'], 
					'S_ADD' => ($this->dkpsys->dkpsys_id > 0 ) ? false : true) );

				$this->page_title = 'ACP_ADDDKPSYS';
				
				break;
			
			case 'listdkpsys' :
				
				// list of pools
				$showadd = (isset ( $_POST ['dkpsysadd'] )) ? true : false;
				$delete = (isset ( $_GET ['delete'] ) && isset ( $_GET [URI_DKPSYS] )) ? true : false;
				$submit = (isset ( $_POST ['upddkpsysdef'] )) ? true : false;
				
				//add new pool
				if ($showadd)
				{
					redirect ( append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=adddkpsys" ) );
					break;
				}
				
				//user clicked on red button
				if ($delete)
				{
					if (confirm_box ( true ))
					{
						$this->dkpsys = new \bbdkp\Pool( request_var ( 'hidden_dkpsys_id' , 0 ) );
						$this->dkpsys->delete(); 
					
						$success_message = sprintf ($user->lang ['ADMIN_DELETE_DKPSYS_SUCCESS'], $this->dkpsys->dkpsys_name);
						trigger_error ($success_message . $this->link );
					} 
					else
					{
						$s_hidden_fields = build_hidden_fields ( array (
							'delete' => true, 
							'hidden_dkpsys_id' => request_var ( URI_DKPSYS, 0 ) ) );
						$template->assign_vars ( array ('S_HIDDEN_FIELDS' => $s_hidden_fields ) );
						confirm_box ( false, $user->lang ['CONFIRM_DELETE_DKPSYS'], $s_hidden_fields );
					}
				}
				
				
				// DEFAULT DKPSYS submit buttonhandler
				if ($submit)
				{
					$this->dkpsys = new \bbdkp\Pool(request_var ( 'defaultsys', 0 ));
					$olddkpsys = new \bbdkp\Pool(request_var ( 'defaultsys', 0 ));  
					$this->dkpsys->dkpsys_default = true;
					$this->dkpsys->update($olddkpsys); 
					unset($olddkpsys); 
					$success_message = sprintf ( $user->lang ['ADMIN_DEFAULTPOOL_SUCCESS'],  $this->dkpsys->dkpsys_name );
					trigger_error ( $success_message . $this->link) ;
				}
				
				// template
				
				$this->dkpsys = new \bbdkp\Pool();
				$listpools = $this->dkpsys->listpools();
				foreach($listpools as $dkpsys_id => $pool)
				{
					$template->assign_block_vars ( 'dkpsysdef_row', 
						array (
							'VALUE' => $dkpsys_id, 
							'SELECTED' => ('Y' == $pool ['dkpsys_default']) ? ' selected="selected"' : '', 
							'OPTION' => $pool['dkpsys_name'] ));
				}
				
				$sort_order = array (
					0 => array ('dkpsys_name', 'dkpsys_name desc' ), 
					1 => array ('dkpsys_id desc', 'dkpsys_id' ) );
				$current_order = $this->switch_order ( $sort_order );
				$start = request_var ( 'start', 0 );
				$listpools = $this->dkpsys->listpools($current_order['sql'], $start, 1); 
				
				foreach($listpools as $dkpsys_id => $pool)
				{
					$template->assign_block_vars ( 'dkpsys_row', 
						array (
							'U_VIEW_DKPSYS' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=adddkpsys&amp;" . URI_DKPSYS . "={$dkpsys_id}" ), 
							'U_DELETE_DKPSYS' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=listdkpsys&amp;delete=1&amp;" . URI_DKPSYS . "={$dkpsys_id}" ), 
							'NAME' => $pool['dkpsys_name'], 
							'STATUS' => $pool['dkpsys_status'], 
							'DEFAULT' => $pool['dkpsys_default'] ));
				}
	
				$template->assign_vars ( array (
					'L_TITLE' 		=> $user->lang ['ACP_LISTDKPSYS'], 
					'L_EXPLAIN' 	=> $user->lang ['ACP_LISTDKPSYS_EXPLAIN'], 
					'O_NAME' 		=> $current_order ['uri'] [0], 
					'O_STATUS' 		=> $current_order ['uri'] [1], 
					'U_LIST_DKPSYS' => append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=listdkpsys&amp;" ), 
					'START' 		=> $start, 
					'LISTDKPSYS_FOOTCOUNT' => sprintf ( $user->lang ['LISTDKPSYS_FOOTCOUNT'], $this->dkpsys->poolcount, $config ['bbdkp_user_elimit'] ), 
					'DKPSYS_PAGINATION' => generate_pagination ( append_sid ( "{$phpbb_admin_path}index.$phpEx", "i=dkp_sys&amp;mode=listdkpsys&amp;" ) . "&amp;o=" . 
						$current_order ['uri'] ['current'], $this->dkpsys->poolcount, $config ['bbdkp_user_elimit'], $start )), true );
				
				$this->page_title = 'ACP_LISTDKPSYS';
				break;
		}
	}
	
	


}

?>