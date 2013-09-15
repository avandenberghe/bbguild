<?php
/**
 * frontpage for bbdkp
 *
 * @package bbDKP
 * @copyright 2011 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author ippehe <ippe.he@gmail.com>
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.bbdkp.com
 *
 */

/**
 * @ignore
 */
define('IN_PHPBB', true);
define('IN_BBDKP', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/dkp_common', 'mods/dkp_admin'));

if(!defined("EMED_BBDKP"))
{
	trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}
if (!isset($config['bbdkp_version']))
{
	// THE CONFIGS AND DATABASE TABLES AREN'T INSTALLED, EXIT
	trigger_error('GENERAL_ERROR', E_USER_WARNING);
}
if (!$auth->acl_get('u_dkp'))
{
	trigger_error('NOT_AUTHORISED');
}
if (!class_exists('\bbdkp\views'))
{
	require("{$phpbb_root_path}includes/bbdkp/module/views.$phpEx");
}

$template->assign_vars(array(
	'U_NEWS'  			=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=news'),
	'U_LISTMEMBERS'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=standings'),
	'U_LISTITEMS'     	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listitems'),
	'U_LISTITEMHIST'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listitems&amp;mode=history'),
	'U_LISTEVENTS'  	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listevents'),
	'U_LISTRAIDS'   	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=listraids'),
	'U_VIEWITEM'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewitem'),
	'U_VIEWMEMBER'   	=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewmember'),
	'U_VIEWRAID'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=viewraid'),
	'U_BP'   			=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=bossprogress'),
	'U_ROSTER'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=roster'),
	'U_STATS'   		=> append_sid("{$phpbb_root_path}dkp.$phpEx", 'page=stats'),
	'U_ABOUT'         	=> append_sid("{$phpbb_root_path}aboutbbdkp.$phpEx"),
	'U_DKP_ACP'			=> ($auth->acl_get('a_') && !empty($user->data['is_registered'])) ? append_sid("{$phpbb_root_path}adm/index.$phpEx", 'i=' .
	 (isset($config['bbdkp_module_id']) ? $config['bbdkp_module_id'] : 194) ,true,$user->session_id ) :'',
));

$template->set_filenames(array(
	'body' => 'dkp/dkpmain.html')
);

$views = new \bbdkp\views();
$page =  request_var('page', 'standings');
$views->load($page);

page_footer();

?>