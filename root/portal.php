<?php
/**
 * Indexpage for bbdkp
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author ippehe <ippe.he@gmail.com>
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

if(!defined("EMED_BBDKP"))
{
    trigger_error('bbDkp is currently disabled.', E_USER_WARNING); 
}


// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');
$user->add_lang(array('mods/dkp_common'));

/***** blocks ************/

/* fixed bocks */
include($phpbb_root_path . 'includes/bbdkp/portal/mainmenublock.' . $phpEx);
include($phpbb_root_path . 'includes/bbdkp/portal/newsblock.' . $phpEx);

/* show loginbox or usermenu */
if ($user->data['is_registered'])
{
	include($phpbb_root_path .'includes/bbdkp/portal/userblock.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'includes/bbdkp/portal/loginblock.' . $phpEx);
}

include($phpbb_root_path . 'includes/bbdkp/portal/whoisonline.' . $phpEx);

// variable blocks
if ($config['bbdkp_portal_loot'])
{
	include($phpbb_root_path . 'includes/bbdkp/portal/lootblock.' . $phpEx);
}

if ($config['bbdkp_portal_bossprogress'])
{
	include($phpbb_root_path . 'includes/bbdkp/portal/bossprogressblock.' . $phpEx);
}

if ($config['bbdkp_portal_recruitment'])
{
	include($phpbb_root_path . 'includes/bbdkp/portal/recruitmentblock.' . $phpEx);
}

if ($config['bbdkp_portal_links'])
{
	include($phpbb_root_path . 'includes/bbdkp/portal/linksblock.' . $phpEx);
}

/***** end blocks ********/

$title = 'News';
$template->assign_var('S_PORTAL', true);

page_header($title);

$template->set_filenames(array(
	'body' => 'dkp/news_body.html')
);

make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));

// auto-refreshing portal is disabled
// uncomment this line if you want it back
//meta_refresh(60, append_sid("{$phpbb_root_path}portal.$phpEx")); 

page_footer();

?>
