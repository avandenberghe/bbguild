<?php
/**
 * View individual raid
 * 
 * @package bbDKP
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$user->add_lang(array('mods/dkp_common'));
if (!$auth->acl_get('u_dkp'))
{
	redirect(append_sid("{$phpbb_root_path}portal.$phpEx"));
}
if (! defined ( "EMED_BBDKP" ))
{
	trigger_error ( $user->lang['BBDKPDISABLED'] , E_USER_WARNING );
}

$sql2 = 'SELECT * FROM ' . NEWS_TABLE;
$total_news = 0;
$result2 = $db->sql_query($sql2);
while ( $row = $db->sql_fetchrow($result2) )
{
	$total_news++;
}    

$start = request_var('start', 0);

$previous_date = null;

$sql = 'SELECT n.news_id, n.news_date, n.news_headline, n.news_message, n.bbcode_uid, n.bbcode_bitfield, n.bbcode_options, u.username
        FROM ' . NEWS_TABLE . ' n, ' . USERS_TABLE . ' u
        WHERE (n.user_id = u.user_id)
        ORDER BY news_date DESC'; 
$result = $db->sql_query_limit($sql, $config['bbdkp_user_nlimit'], $start);

while ( $news = $db->sql_fetchrow($result) )
{

    /* Show a new date row if it's not the same as the last */
    if ( date('d.m.y', $news['news_date']) != date('d.m.y', $previous_date) )
    {
        $template->assign_block_vars('date_row', 
         array(
            'DATE' => date('F j, Y', $news['news_date']))
           );
        
        $previous_date = $news['news_date'];
    }
    
    $message = $news['news_message'];
   
    $bbcode_options   = (OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS);
    $message      = generate_text_for_display($message, $news['bbcode_uid'], $news['bbcode_bitfield'], $news['bbcode_options'] = true);
    //$message      = smiley_text($message);
    
    $template->assign_block_vars('date_row.news_row', 
    array(
        'HEADLINE' => $news['news_headline'],
        'AUTHOR' => $news['username'],
        'TIME' => date('h:ia T', $news['news_date']),
        'MESSAGE' => $message)
    );
}
$db->sql_freeresult($result);

$navlinks_array = array(
array(
 'DKPPAGE' => $user->lang['MENU_NEWS'],
 'U_DKPPAGE' => append_sid("{$phpbb_root_path}viewnews.$phpEx"),
));

foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
	'DKPPAGE' => $name['DKPPAGE'],
	'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

$template->assign_vars(array(
      'NEWS_PAGINATION' => generate_pagination( append_sid("{$phpbb_root_path}viewnews.$phpEx"), $total_news, $config['bbdkp_user_nlimit'], $start))
);

$title = 'DKP News';

/* Output page */
page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/viewnews.html')
);
page_footer();
?>
