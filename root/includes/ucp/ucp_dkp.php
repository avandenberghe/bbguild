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
			// Handle the form processing by storing digest settings
			
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
			// newpage
			// GET processing logic
			add_form_key('digests');
			switch ($mode)
			{
			
				case 'characters':
					// build popup for adding new chars to user account, get only those that are not assigned yet.
					// if someone picks a guildmember that does not belong to them then the guild admin wen override it in acp
					$sql = 'select member_id, member_name from ' . MEMBER_LIST_TABLE .' where phpbb_user_id = 0 order by member_name asc';
					$result = $db->sql_query($sql, 0);
					$s_guildmembers = ' '; 
					while ( $row = $db->sql_fetchrow($result) )
                    {
						$show_buttons = true;
						$s_guildmembers .= '<option value="' . $row['member_id'] .'">' . $row['member_name'] . '</option>';
                    }
					
					// These template variables are used on all the pages
					$template->assign_vars(array(
						'S_DKPMEMBER_OPTIONS'	=> $s_guildmembers,
						'S_SHOW_BUTTONS'		=> $show_buttons,
						'U_ACTION'  			=> $this->u_action,
						'DKPCHAR_TITLE'			=> $user->lang['UCP_DKP_CHARACTERS'],
						)
					);
					
					// this mode is shown to users in order to select the character with which they will raid
					$this->page_title 	= $user->lang['UCP_DKP_CHARACTERS'];
					
				break;
					
			}
			
		}
	}
}

?>