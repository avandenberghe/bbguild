<?php
/**
 * main menu block
 * 
 * @package bbDkp
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */

if (!defined('IN_PHPBB'))
{
   exit;
}


$template->assign_vars(array(
	'S_DISPLAY_MAINMENU' 	=> true,
    'U_VIEWNEWS'			=> append_sid("{$phpbb_root_path}viewnews.$phpEx"),
   	'U_ROSTER'				=> append_sid("{$phpbb_root_path}roster.$phpEx"),
    'U_LISTMEMBERS'			=> append_sid("{$phpbb_root_path}listmembers.$phpEx"),
   	'U_LISTEVENTS'			=> append_sid("{$phpbb_root_path}listevents.$phpEx"),
   	'U_LISTRAIDS'			=> append_sid("{$phpbb_root_path}listraids.$phpEx"),
    'U_LISTITEMS'			=> append_sid("{$phpbb_root_path}listitems.$phpEx"),  
   	'U_LISTITEMHIST'		=> append_sid("{$phpbb_root_path}listitems.$phpEx?&amp;page=history"),
 	'U_STATS'				=> append_sid("{$phpbb_root_path}stats.$phpEx"),
   	'U_BP'					=> append_sid("{$phpbb_root_path}bossprogress.$phpEx"),
	'U_BBFAQ'   			=> append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode'),
	'U_TERMS'      			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=terms'),
	'U_PRIV'      			=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'mode=privacy'),
));


?>