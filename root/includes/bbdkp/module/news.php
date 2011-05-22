<?php
/**
 * View/edit news
 * 
 * @package bbDKP
 * @author Sajaki 
 * @copyright 2011 bbdkp <http://www.bbdkp.com/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}
$user->add_lang(array('posting'));
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

$mode = request_var('mode', '');

$update = false;
if (isset ( $_GET [URI_NEWS]) && mode == 'editpost')
{
	// editing mode 
	$sql = 'SELECT news_headline, news_message, news_id, bbcode_uid, bbcode_bitfield, 
			bbcode_options FROM ' . NEWS_TABLE . ' WHERE news_id= ' . ( int ) request_var ( URI_NEWS, 0 );
	$result = $db->sql_query ( $sql );
	if (! $row = $db->sql_fetchrow ( $result ))
	{
		trigger_error ( $user->lang ['ERROR_INVALID_NEWS'], E_USER_WARNING );
	}
	$db->sql_freeresult ( $result );
	$time = time ();
	$news = array (
		'news_headline' => $row ['news_headline'], 
		'news_message'  => decode_message($row['news_message'], $row['bbcode_uid']), 
		);
	$template->assign_vars(array(
		'S_POST_ACTION'				=> true,
	));
} 
elseif ($mode=='newpost')
{
	//show a new posting editor
	$template->assign_vars(array(
		'S_POST_ACTION'				=> true,
	));
}
else
{
	//just show all posts
	$time = time ();
	$news ['news_headline'] = null;
	$news ['news_message'] = null;
	$news ['news_id'] = null;
	$template->assign_vars(array(
		'S_POST_ACTION'				=> false,
	));
}

// edit, newpost and delete handlers
$submit = (isset ( $_POST ['post'] )) ? true : false;
$delete = (isset ( $_POST ['delete'] )) ? true : false;
if ($submit || $delete)
{
	if (! check_form_key ( 'addnews' ))
	{
		trigger_error ( 'FORM_INVALID' );
	}
}

if ($submit)
{
	if ($update == false)
	{
		//new message
		$text = utf8_normalize_nfc ( request_var ( 'message', '', true ) );
		$uid = $bitfield = $options = 1; // will be modified by generate_text_for_storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage ( $text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies );
		$sql_ary = array (
			'news_headline' => utf8_normalize_nfc ( request_var ( 'news_headline', '', true ) ), 
			'news_message' => $text, 
			'bbcode_uid' => $uid, 
			'bbcode_bitfield' => $bitfield, 
			'bbcode_options' => $options, 
			'news_date' => $time, 
			'user_id' => $user->data ['user_id'] );
		
		$sql = 'INSERT INTO ' . NEWS_TABLE . ' ' . $db->sql_build_array ( 'INSERT', $sql_ary );
		$db->sql_query ( $sql );
		
		$log_action = array (
			'header' => 'L_ACTION_NEWS_ADDED', 
			'L_HEADLINE' => utf8_normalize_nfc ( request_var ( 'news_headline', '', true ) ), 
			'L_MESSAGE_BODY' => nl2br ( utf8_normalize_nfc ( request_var ( 'news_message', '', true ) ) ), 
			'L_ADDED_BY' => $user->data ['username'] );
		
		log_insert ( array (
			'log_type' => $log_action ['header'], 
			'log_action' => $log_action ) );
		
		$success_message = $user->lang ['ADMIN_ADD_NEWS_SUCCESS'];
		trigger_error ( $success_message . $link );
	} 
	else
	{
		// update
		if (isset ( $_POST ['update_date'] ))
		{
			$query = $db->sql_build_array ( 
				'UPDATE', array (
					'news_headline' => utf8_normalize_nfc ( request_var ( 'news_headline', '', true ) ), 
					'news_message' => nl2br ( utf8_normalize_nfc ( request_var ( 'news_message', '', true ) ) ), 
					'news_date' => $time, 
					'user_id' => $user->data ['user_id'] ) );
		} 
		else
		{
			$query = $db->sql_build_array ( 'UPDATE', array (
				'news_headline' => utf8_normalize_nfc (request_var( 'news_headline', '', true )), 
				'news_message' => nl2br ( utf8_normalize_nfc ( request_var ( 'news_message', '', true )))));
		}
		$db->sql_query ( 'UPDATE ' . NEWS_TABLE . ' SET ' . $query . " WHERE news_id='" . ( int ) $this->news ['news_id'] . "'" );
		
		$log_action = array (
			'header' => 'L_ACTION_NEWS_UPDATED', 
			'id' => $news ['news_id'], 
			'L_HEADLINE_BEFORE' => $news ['news_headline'], 
			'L_MESSAGE_BEFORE' => nl2br ( $news ['news_message'] ), 
			'L_HEADLINE_AFTER' => utf8_normalize_nfc ( request_var ( 'news_headline', '', true ) ), 
			'L_MESSAGE_AFTER' => nl2br ( utf8_normalize_nfc ( request_var ( 'news_message', '', true ) ) ), 
			'L_UPDATED_BY' => $user->data ['username'] );
		
		log_insert ( array (
			'log_type' => $log_action ['header'], 
			'log_action' => $log_action ) );
		
		$success_message = $user->lang ['ADMIN_UPDATE_NEWS_SUCCESS'];
		trigger_error ( $success_message . $link );
	}
}

if ($delete)
{
	if (isset ( $_GET [URI_NEWS] ))
	{
		if (confirm_box ( true ))
		{
			$sql = 'DELETE FROM ' . NEWS_TABLE . ' WHERE news_id=' . ( int ) request_var ( URI_NEWS, 0 );
			$db->sql_query ( $sql );
			$success_message = $user->lang ['ADMIN_DELETE_NEWS_SUCCESS'];
			trigger_error ( $success_message . $link );
		} 
		else
		{
			$s_hidden_fields = build_hidden_fields (
			 array ('delete' => true, 
			 'news_id' => (int) request_var ( URI_NEWS, 0 )));
			confirm_box ( false, $user->lang ['CONFIRM_DELETE_NEWS'], $s_hidden_fields );
		}
	}
}
$form_key = 'addnews';
add_form_key ( $form_key );

/* viewnews */

$sql2 = 'SELECT * FROM ' . NEWS_TABLE;
$total_news = 0;
$result2 = $db->sql_query ( $sql2 );
while ( $row = $db->sql_fetchrow ( $result2 ) )
{
	$total_news ++;
}

$start = request_var ( 'start', 0 );
$previous_date = null;

$sql = 'SELECT n.news_id, n.news_date, n.news_headline, n.news_message, n.bbcode_uid, n.bbcode_bitfield, n.bbcode_options, u.username
        FROM ' . NEWS_TABLE . ' n, ' . USERS_TABLE . ' u
        WHERE (n.user_id = u.user_id)
        ORDER BY news_date DESC';
$result = $db->sql_query_limit ( $sql, $config ['bbdkp_user_nlimit'], $start );

while ( $news = $db->sql_fetchrow ( $result ) )
{
	
	/* Show a new date row if it's not the same as the last */
	if (date ( 'd.m.y', $news ['news_date'] ) != date ( 'd.m.y', $previous_date ))
	{
		$template->assign_block_vars ( 'date_row', array (
			'DATE' => date ( 'F j, Y', $news ['news_date'] ) ) );
		$previous_date = $news ['news_date'];
	}
	
	$message = $news ['news_message'];
	$bbcode_options = (OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS);
	$message = generate_text_for_display ( $message, $news ['bbcode_uid'], $news ['bbcode_bitfield'], $news ['bbcode_options'] = true );
	$message = smiley_text ( $message );
	
	$template->assign_block_vars ( 
		'date_row.news_row', array (
			'HEADLINE' => $news ['news_headline'], 
			'AUTHOR' => $news ['username'], 
			'TIME' => date ( 'h:ia T', $news ['news_date'] ), 
			'MESSAGE' => $message ));
}
$db->sql_freeresult ( $result );

$template->assign_vars ( array (
	'S_ADD' => ! $update, 
	'S_UPDATE' => $update, 
	'HEADLINE' => $news ['news_headline'], 
	'MESSAGE' => $news ['news_message'], 
	'UPDATE_DATE_TO' => sprintf ( $user->lang ['UPDATE_DATE_TO'], date ( 'm/d/y h:ia T', time () ) ), 
	'S_DISPLAY_NEWS' => true ));

/********************************
 * page info
 ********************************/
$navlinks_array = array (array (
	'DKPPAGE' => $user->lang ['MENU_NEWS'], 
	'U_DKPPAGE' => append_sid ( "{$phpbb_root_path}dkp.$phpEx", '&amp;page=news' ) ) );

foreach ( $navlinks_array as $name )
{
	$template->assign_block_vars ( 
		'dkpnavlinks', array (
			'DKPPAGE' => $name ['DKPPAGE'], 
			'U_DKPPAGE' => $name ['U_DKPPAGE'] ) );
}

$template->assign_vars ( array (
	'NEWS_PAGINATION' => generate_pagination ( append_sid ( "{$phpbb_root_path}viewnews.$phpEx" ), $total_news, $config ['bbdkp_user_nlimit'], $start ) ) );


// HTML, BBCode, Smilies, Images and Flash status
$bbcode_status	= ($config['allow_bbcode']) ? true : false;
$img_status		= ($bbcode_status) ? true : false;
$flash_status	= ($bbcode_status && $config['allow_post_flash']) ? true : false;
$url_status		= ($config['allow_post_links']) ? true : false;
$smilies_status	= ($bbcode_status && $config['allow_smilies']) ? true : false;


if ($smilies_status)
{
	$display_link = false;
	$sql = 'SELECT smiley_id
		FROM ' . SMILIES_TABLE . '
		WHERE display_on_posting = 0';
	$result = $db->sql_query_limit($sql, 1, 0, 3600);
	if ($row = $db->sql_fetchrow($result))
	{
		$display_link = true;
	}
	$db->sql_freeresult($result);
	$last_url = '';

	$sql = 'SELECT *
		FROM ' . SMILIES_TABLE . '
		WHERE display_on_posting = 1  
		ORDER BY smiley_order';
	$result = $db->sql_query($sql, 3600);
	$smilies = array();
	while ($row = $db->sql_fetchrow($result))
	{
		if (empty($smilies[$row['smiley_url']]))
		{
			$smilies[$row['smiley_url']] = $row;
		}
	}
	$db->sql_freeresult($result);
		if (sizeof($smilies))
	{
		foreach ($smilies as $row)
		{
			$template->assign_block_vars('smiley', array(
				'SMILEY_CODE'	=> $row['code'],
				'A_SMILEY_CODE'	=> addslashes($row['code']),
				'SMILEY_IMG'	=> $phpbb_root_path . $config['smilies_path'] . '/' . $row['smiley_url'],
				'SMILEY_WIDTH'	=> $row['smiley_width'],
				'SMILEY_HEIGHT'	=> $row['smiley_height'],
				'SMILEY_DESC'	=> $row['emotion'])
			);
		}
	}
		if ($display_link)
	{
		$template->assign_vars(array(
			'S_SHOW_SMILEY_LINK' 	=> true,
			'U_MORE_SMILIES' 		=> append_sid("{$phpbb_root_path}planneradd.$phpEx", 'mode=smilies'))
		);
	}
}

$allow_delete= false;
$quote_status	= false;

$template->assign_vars(array(
	'BBCODE_STATUS'				=> ($bbcode_status) ? 
		sprintf($user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>') : 
		sprintf($user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>'),
	'IMG_STATUS'				=> ($img_status) ? $user->lang['IMAGES_ARE_ON'] : $user->lang['IMAGES_ARE_OFF'],
	'FLASH_STATUS'				=> ($flash_status) ? $user->lang['FLASH_IS_ON'] : $user->lang['FLASH_IS_OFF'],
	'SMILIES_STATUS'			=> ($smilies_status) ? $user->lang['SMILIES_ARE_ON'] : $user->lang['SMILIES_ARE_OFF'],
	'URL_STATUS'				=> ($bbcode_status && $url_status) ? $user->lang['URL_IS_ON'] : $user->lang['URL_IS_OFF'],

	'S_DELETE_ALLOWED'			=> $allow_delete,
	'S_BBCODE_ALLOWED'			=> $bbcode_status,
	'S_SMILIES_ALLOWED'			=> $smilies_status,
	'S_LINKS_ALLOWED'			=> $url_status,
	'S_BBCODE_IMG'				=> $img_status,
	'S_BBCODE_URL'				=> $url_status,
	'S_BBCODE_FLASH'			=> $flash_status,
	'S_BBCODE_QUOTE'			=> $quote_status,
	'U_POST_NEW_TOPIC'			=> append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=news&amp;mode=newpost'), 
)
);

// Build custom bbcodes array
display_custom_bbcodes();

page_header ( $user->lang ['MENU_NEWS'] );

?>