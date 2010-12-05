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
									
		// Set up the page
		$this->tpl_name 	= 'ucp_dkp';
		
		// There is at the moment only one mode for the DKP character interface. 
		switch ($mode)
		{
			case 'characters':
				// this mode is shown to users in order to select the character with which they will raid
				$this->page_title 	= $user->lang['UCP_DKP_CHARACTERS'];
				break;
			default:
				break;
		}
							 
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
					/*
					$sql_ary = array(
						'user_digest_sortby'			=> request_var('sort_by', $user->data['user_digest_sortby']),
					*/
					// Update the user's digest settings
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE user_id = ' . (int) $user->data['user_id'];
					$db->sql_query($sql);
					$result = $db->sql_query($sql);
				break;
					
				default:
					trigger_error(sprintf($user->lang['UCP_DIGESTS_MODE_ERROR'], $mode));
					
				break;
					
			}
			
			// Generate confirmation page. It will redirect back to the calling page
			meta_refresh(3, $this->u_action);
			$message = $user->lang['DIGEST_UPDATED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
		}
		
		else
		{
		
			// newpage
			// GET processing logic
			add_form_key('digests');

			// Don't show submit or reset buttons if there is no digest subscription


			switch ($mode)
			{
			
				case DIGEST_MODE_BASICS:
					
					// These template variables are used on all the pages
					$template->assign_vars(array(
						'DISABLED_MESSAGE' 		=> ($user->data['user_digest_type'] == DIGEST_NONE_VALUE) ? '<p><em>' . $user->lang['DIGEST_DISABLED_MESSAGE'] . '</em></p>' : '',
						'S_CONTROL_DISABLED' 	=> ($user->data['user_digest_type'] == DIGEST_NONE_VALUE),
						'S_DIGEST_HOME'			=> $config['digests_digests_title'],
						'S_DIGEST_PAGE_URL'		=> $config['digests_page_url'],
						'S_SHOW_BUTTONS'		=> $show_buttons,
						'U_ACTION'  			=> $this->u_action,
						'DIGEST_TITLE'						=> $user->lang['UCP_DIGESTS_BASICS'],
						'L_DIGEST_FREQUENCY_EXPLAIN'		=> sprintf($user->lang['DIGEST_FREQUENCY_EXPLAIN'], $user->lang['DIGEST_WEEKDAY'][$config['digests_weekly_digest_day']]),
						'L_DIGEST_HTML_CLASSIC_VALUE'		=> DIGEST_HTML_CLASSIC_VALUE,
						'L_DIGEST_HTML_VALUE'				=> DIGEST_HTML_VALUE,
						'L_DIGEST_PLAIN_CLASSIC_VALUE'		=> DIGEST_PLAIN_CLASSIC_VALUE,
						'L_DIGEST_PLAIN_VALUE'				=> DIGEST_PLAIN_VALUE,
						'L_DIGEST_TEXT_VALUE'				=> DIGEST_TEXT_VALUE,
						'S_BASICS'							=> true,
						'S_DIGEST_DAY_CHECKED' 				=> ($user->data['user_digest_type'] == DIGEST_DAILY_VALUE),
						'S_DIGEST_HTML_CHECKED' 			=> $styling_html,
						'S_DIGEST_HTML_CLASSIC_CHECKED' 	=> $styling_html_classic,
						'S_DIGEST_MONTH_CHECKED' 			=> ($user->data['user_digest_type'] == DIGEST_MONTHLY_VALUE),
						'S_DIGEST_NONE_CHECKED' 			=> ($user->data['user_digest_type'] == DIGEST_NONE_VALUE),
						'S_DIGEST_PLAIN_CHECKED' 			=> $styling_plain,
						'S_DIGEST_PLAIN_CLASSIC_CHECKED' 	=> $styling_plain_classic,
						'S_DIGEST_TEXT_CHECKED' 			=> $styling_text,
						'S_DIGEST_WEEK_CHECKED' 			=> ($user->data['user_digest_type'] == DIGEST_WEEKLY_VALUE),
						)
					);
				break;
					
				default:
					trigger_error(sprintf($user->lang['UCP_DIGESTS_MODE_ERROR'], $mode));
				break;
					
			}
			
		}
	}
}

?>