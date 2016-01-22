<?php
/**
 * returns memberlist xml based on ajax call used by acp_addraid.html
 * 
 * @package acp\ajax
 * @copyright (c) 2013 bbDkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
define('IN_PHPBB', true);
define('ADMIN_START', true);

$guild_id = $request->variable('guild', 0);

$members = new \bbdkp\bbguild\model\player\Members();
$members->listallmembers($guild_id);

$data =array();
foreach ( (array) $members->guildmemberlist as $member )
while ( $row = $db->sql_fetchrow($result))
{
	$data =array(
		'member_id' => $member['member_id'],
		'member_name' =>  $member['rank_name'] . ' '.  $member['member_name'],
	);
}
$db->sql_freeresult($result);
$data= json_encode($data);