<?php
/**
* This class manages the news page
* 
* @package bbDKP.acp
* @author Ippehe, Sajaki
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


class acp_dkp_news extends bbDKP_Admin
{
	var $u_action;
	function main($id, $mode) 
	{	
		global $db, $user, $auth, $template, $sid, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang(array('mods/dkp_admin'));
		$user->add_lang(array('mods/dkp_common'));

		$link = '<br /><a href="'. append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp&amp;mode=mainpage") . '"><h3>'.$user->lang['RETURN_DKPINDEX'].'</h3></a>'; 

		switch ($mode)
		{
			case 'addnews':
				if ( isset($_GET[URI_NEWS]) )
				{
					$update = true;
					$sql = 'SELECT news_headline, news_message, news_id, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . 
						NEWS_TABLE . ' WHERE news_id= ' . (int) request_var(URI_NEWS, 0);
            		$result = $db->sql_query($sql);
					if ( !$row = $db->sql_fetchrow($result) )
					{
						trigger_error($user->lang['ERROR_INVALID_NEWS'], E_USER_WARNING);
					}
					$db->sql_freeresult($result);
					$this->time = time();
					$this->news = array(
						'news_headline' => $row['news_headline'],
						//'news_message'  => decode_message($row['news_message'], $row['bbcode_uid']), 
						'news_message' 	=> $row['news_message'],
						'news_id' 		=> $row['news_id']);
				}
				else
				{
					$update = false;
					$this->time = time();
					$this->news['news_headline'] = null;
					$this->news['news_message'] = null;
					$this->news['news_id'] = null;
				}
					
				$submit	 = (isset($_POST['update'])) ? true : false;
				$delete	 = (isset($_POST['delete'])) ? true : false;	
		        if ( $submit || $delete )
                {
                   	if (!check_form_key('addnews'))
					{
						trigger_error('FORM_INVALID');
					}
        		}
        			
				if ($submit)
				{
						if ($update == false)
						{
							$text = utf8_normalize_nfc(request_var('news_message', '', true));
							$uid = $bitfield = $options = 1; // will be modified by generate_text_for_storage
							$allow_bbcode = $allow_urls = $allow_smilies = true;
							generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
 							$sql_ary = array(
								'news_headline' => utf8_normalize_nfc(request_var('news_headline', '' , true)),
							    'news_message'      => $text,
							    'bbcode_uid'        => $uid,
							    'bbcode_bitfield'   => $bitfield,
							    'bbcode_options'    => $options,
								'news_date'     	=> $this->time,
								'user_id'       	=> $user->data['user_id']
							);
								
							$sql = 'INSERT INTO ' . NEWS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
							$db->sql_query($sql);
							
							$log_action = array(
								'header'         => 'L_ACTION_NEWS_ADDED',
								'L_HEADLINE'     => utf8_normalize_nfc(request_var('news_headline', '' , true)),
								'L_MESSAGE_BODY' => nl2br(utf8_normalize_nfc(request_var('news_message', '', true))),
								'L_ADDED_BY'     => $user->data['username']);
							
							$this->log_insert(array(
								'log_type'   => $log_action['header'],
								'log_action' => $log_action)
							);						
							
							$success_message = $user->lang['ADMIN_ADD_NEWS_SUCCESS'];
							trigger_error($success_message . $link);
						}
						else
						{
							if ( isset($_POST['update_date']) )
							{
								$query = $db->sql_build_array('UPDATE', array(
									'news_headline' => utf8_normalize_nfc(request_var('news_headline', '' , true)),
									'news_message'  => nl2br(utf8_normalize_nfc(request_var('news_message', '', true))),
									'news_date'     => $this->time,
									'user_id'       => $user->data['user_id'])
								);
							}
							else
							{
								$query = $db->sql_build_array('UPDATE', array(
									'news_headline' => utf8_normalize_nfc(request_var('news_headline', '' , true)),
									'news_message'  => nl2br(utf8_normalize_nfc(request_var('news_message', '', true))),)
								);
							}
							$db->sql_query('UPDATE ' . NEWS_TABLE . ' SET ' . $query . " WHERE news_id='" . (int) $this->news['news_id'] . "'");
							
							$log_action = array(
								'header'              => 'L_ACTION_NEWS_UPDATED',
								'id'                  => $this->news['news_id'],
								'L_HEADLINE_BEFORE' => $this->news['news_headline'],
								'L_MESSAGE_BEFORE'  => nl2br($this->news['news_message']),
								'L_HEADLINE_AFTER'  => utf8_normalize_nfc(request_var('news_headline', '' , true)),
								'L_MESSAGE_AFTER'   => nl2br(utf8_normalize_nfc(request_var('news_message', '', true))),
								'L_UPDATED_BY'      => $user->data['username']);
							
							$this->log_insert(array(
								'log_type'   => $log_action['header'],
								'log_action' => $log_action)
							);

							$success_message = $user->lang['ADMIN_UPDATE_NEWS_SUCCESS'];
							trigger_error($success_message . $link);
						}
					}	

					if ($delete)
						{	
							if ( isset($_GET[URI_NEWS]  ))
							{ 
								if (confirm_box(true))
								{
	       							$sql = 'DELETE FROM ' . NEWS_TABLE . ' WHERE news_id=' . (int) request_var(URI_NEWS, 0);
        							$db->sql_query($sql);
									$success_message = $user->lang['ADMIN_DELETE_NEWS_SUCCESS'];
									trigger_error($success_message . $link);
								}
								else
								{
									$s_hidden_fields = build_hidden_fields(array(
										'delete'	=> true,
										'event_id'	=> (int) request_var(URI_NEWS, 0) ,
										)
									);
	
									confirm_box(false, $user->lang['CONFIRM_DELETE_NEWS'], $s_hidden_fields);
								}
							}
						}	
        
		        $form_key = 'addnews';
				add_form_key($form_key);
				
				$template->assign_vars(array(
					'S_ADD'         	=> !$update,
					'S_UPDATE' 			=> $update,
					'HEADLINE'     		=> $this->news['news_headline'],
					'MESSAGE'      		=> $this->news['news_message'],
					'UPDATE_DATE_TO' 	=> sprintf($user->lang['UPDATE_DATE_TO'], date('m/d/y h:ia T', time())),
				));
					
			
				$this->page_title = 'ACP_DKP_NEWS_ADD';
				$this->tpl_name = 'dkp/acp_'. $mode;
				
			break;

			case 'listnews':

   		     	$showadd = (isset($_POST['newsadd'])) ? true : false;
            	
            	if($showadd)
            	{
					redirect(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_news&amp;mode=addnews"));            		
            		break;
            	}
   	
				$sort_order = array(
					0 => array('news_date desc', 'news_date'),
					1 => array('news_headline', 'news_headline desc'),
					2 => array('username', 'username desc')
				);
				
				$current_order = switch_order($sort_order);
				
				$sql6 = 'SELECT * FROM ' . NEWS_TABLE;
				$result6 = $db->sql_query($sql6);	
				$rows = $db->sql_fetchrowset($result6);
				$db->sql_freeresult($result6);
				$total_news = count($rows);

				$start = request_var( 'start', 0) ;
				
				$sql = 'SELECT n.news_id, n.news_date, n.news_headline, n.news_message, u.username
						FROM ' . NEWS_TABLE . ' n, ' . USERS_TABLE . ' u
						WHERE (n.user_id = u.user_id)
						ORDER BY ' . $current_order['sql']; 
				$result = $db->sql_query_limit($sql, $config['bbdkp_user_nlimit'], $start);
				
				if (!$result)
				{
					trigger_error($user->lang['ERROR_INVALID_NEWS'], E_USER_WARNING);
				}
				while ( $news = $db->sql_fetchrow($result) )
				{
					$template->assign_block_vars('news_row', array(
						'DATE' => date($config['bbdkp_date_format'], $news['news_date']),
						'USERNAME' => $news['username'],             
						'U_VIEW_NEWS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_news&amp;mode=addnews&amp;". URI_NEWS . "={$news['news_id']}"),
						'HEADLINE' => $news['news_headline'])
					);
				}
				
				$template->assign_vars(array(
					'O_DATE'     => $current_order['uri'][0],
					'O_USERNAME' => $current_order['uri'][2],
					'O_HEADLINE' => $current_order['uri'][1],
					'U_LIST_NEWS' => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_news&amp;mode=listnews&amp;"),
					'START' => $start,
					'LISTNEWS_FOOTCOUNT' => sprintf($user->lang['NEWS_FOOTCOUNT'], $total_news, $config['bbdkp_user_nlimit']),
					'NEWS_PAGINATION' => generate_pagination(append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_news&amp;mode=listnews&amp;").'&amp;o='.$current_order['uri']['current'], $total_news, $config['bbdkp_user_nlimit'], $start))
				);

			
				$this->page_title = 'ACP_ADDNEWS';
				$this->tpl_name = 'dkp/acp_'. $mode;
				
			break;
			
		}
	}
}

?>
