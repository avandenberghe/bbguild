<?php
/** 
*
* @package ucp
* @copyright (c) 2010 bbDKP 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
* @author Sajaki
* This is the user interface for the character dkp integration
*/
			
/**
* @package ucp
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class ucp_dkp
{
	var $u_action;
					
	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;
		$s_hidden_fields = '';
		$submit = (isset($_POST['submit'])) ? true : false;
		
		// Attach the language file
		$user->add_lang('mods/dkp_common');
		if ($submit)
		{
			// user pressed submit
			// Verify the form key is unchanged
			if (!check_form_key('digests'))
			{
				trigger_error('FORM_INVALID');
			}
			
			switch ($mode)
			{
				case 'characters':
					$sql_ary = array(
						'phpbb_user_id'	=> $user->data['user_id'], 
					);
					$sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE member_id = ' . (int) request_var('dkpmember', 0);
					$db->sql_query($sql);
					
				break;
			}
			
			// Generate confirmation page. It will redirect back to the calling page
			meta_refresh(3, $this->u_action);
			$message = $user->lang['CHARACTERS_UPDATED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
		}
		
		else
		{

			// Set up the page
			$this->tpl_name 	= 'dkp/ucp_dkp';
			
			// GET processing logic
			add_form_key('digests');
			switch ($mode)
			{
				// this mode is shown to users in order to select the character with which they will raid
				case 'characters':
					
					$show_buttons = false;
					$show = false;
					
					//if there are no chars at all, show a message and do not show add button
					$sql = 'select count(*) as mcount from ' . MEMBER_LIST_TABLE .' ';
					$result = $db->sql_query($sql, 0);
					$mcount = (int) $db->sql_fetchfield('mcount');
					if ( $mcount > 0)
					{
						$show = true;
					}
					$db->sql_freeresult ($dkp_result);
					
					// build popup for adding new chars to user account, get only those that are not assigned yet.
					// do not show if all accounts are assigned
					// if someone picks a guildmember that does not belong to them then the guild admin can override it in acp
					$sql = 'select member_id, member_name from ' . MEMBER_LIST_TABLE .' where phpbb_user_id = 0 order by member_name asc';
					$result = $db->sql_query($sql, 0);
					$s_guildmembers = ' '; 
					while ( $row = $db->sql_fetchrow($result) )
                    {
						$s_guildmembers .= '<option value="' . $row['member_id'] .'">' . $row['member_name'] . '</option>';
                    	$show_buttons = true;
                    }
					
					// These template variables are used on all the pages
					$template->assign_vars(array(
						'S_DKPMEMBER_OPTIONS'	=> $s_guildmembers,
						'S_SHOW'				=> $show,
						'S_SHOW_BUTTONS'		=> $show_buttons,
						'U_ACTION'  			=> $this->u_action,
						'DKPCHAR_TITLE'			=> $user->lang['UCP_DKP_CHARACTERS'],
						)
					);
					
					
					// make a listing of my own characters with dkp for each pool
					$sql_array = array(
					    'SELECT'    => 	'm.member_id, m.member_name, m.member_level, u.username, g.name as guildname, l.name as member_class , c.imagename, c.colorcode  ', 
					    'FROM'      => array(
					        MEMBER_LIST_TABLE 	=> 'm',
					        CLASS_TABLE  		=> 'c',
					        BB_LANGUAGE			=> 'l', 
					        GUILD_TABLE  		=> 'g',
					        USERS_TABLE 		=> 'u', 
					    	),
					 
					    'WHERE'     =>  "  l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
										  AND (m.member_guild_id = g.id) AND (m.member_class_id = c.class_id) 
										  AND u.user_id = m.phpbb_user_id and u.user_id = " . $user->data['user_id']  ,
						'ORDER_BY'	=> " m.member_name ",
					    );

				    $sql = $db->sql_build_query('SELECT', $sql_array);
					if (!($members_result = $db->sql_query($sql)) )
					{
						trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
					}
					$lines = 0;
					$member_count = 0;
					while ( $row = $db->sql_fetchrow($members_result) )
					{
						++$member_count;
						$template->assign_block_vars('members_row', array(
							'COUNT'         => $member_count,
							'NAME'          => $row['member_name'],
							'LEVEL'         => $row['member_level'],
							'CLASS'         => $row['member_class'],
							'GUILD'         => $row['guildname'],
							'CLASSIMGPATH'	=> (strlen($row['imagename']) > 1) ? $row['imagename'] . ".png" : '',
							'COLORCODE' 	=> $row['colorcode']
							)
						); 
						
						$sql_array2 = array(
						    'SELECT'    => ' d.dkpsys_id, d.dkpsys_name, sum(b.member_earned) as earned, 
						    				 sum(b.member_spent) as spent, sum(b.member_adjustment) as adjustment, 
	    									 sum(b.member_earned-b.member_spent+b.member_adjustment) AS current ', 
						    'FROM'      => array(
							        MEMBER_DKP_TABLE 	=> 'b', 
							        DKPSYS_TABLE 		=> 'd',
						    	),
						    'WHERE'     => " b.member_dkpid = d.dkpsys_id and b.member_id = " . $row['member_id'],
							'GROUP_BY'  => " d.dkpsys_name " , 
							'ORDER_BY'	=> " d.dkpsys_name ",
						    );
	
					    $sql2 = $db->sql_build_query('SELECT', $sql_array2);
					    $dkp_result = $db->sql_query($sql2); 
						while ($row2 = $db->sql_fetchrow($dkp_result))
						{
							$template->assign_block_vars('members_row.dkp_row', array(
								'DKPSYS'        => $row2['dkpsys_name'],
								'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}viewmember.$phpEx", URI_NAME . '=' . $row['member_name'] . '&amp;' . URI_DKPSYS . '= ' . $row2['dkpsys_id'] ), 
								'EARNED'       => $row2['earned'],
								'SPENT'        => $row2['spent'],
								'ADJUSTMENT'   => $row2['adjustment'],
								'CURRENT'      => $row2['current'],
								)
							); 
						}
    					$db->sql_freeresult ($dkp_result);	
							
					}
					
					$db->sql_freeresult ($members_result);

					$this->page_title 	= $user->lang['UCP_DKP_CHARACTERS'];
					
				break;		
			}	
		}
	}
}
?>