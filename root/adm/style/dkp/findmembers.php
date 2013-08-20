<?php
/**
 * returns memberlist xml based on ajax call 
 *  used by acp_addraid.html
 * @copyright (c) 2013 bbDkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License

 */
define('IN_PHPBB', true);
define('ADMIN_START', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$guild_id = request_var('guild', 0);

$sql = 'SELECT member_id, member_name  
		FROM ' . MEMBER_LIST_TABLE . ' WHERE member_status = 1 and 
		member_guild_id =  '. $guild_id . ' ORDER BY member_rank_id asc, member_level desc, member_name asc';

$result = $db->sql_query($sql);
header('Content-type: text/xml');
// preparing xml
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<memberlist>';
while ( $row = $db->sql_fetchrow($result)) 
{
	 $xml .= '<member>'; 
	 $xml .= "<member_id>" . $row['member_id'] . "</member_id>";
	 $xml .= "<member_name>" . $row['member_name'] . "</member_name>";
	 $xml .= '</member>'; 	 
}
$xml .= '</memberlist>';
$db->sql_freeresult($result);
//return xml to ajax
echo($xml); 
?>
