<?php
/**
 * returns rank xml based on ajax call
 *
 * @package acp\ajax
 * @copyright (c) 2009 bbDkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link http://www.bbdkp.com
 *
 */

define('IN_PHPBB', true);
define('ADMIN_START', true);
$game_id = $request->variable('game_id', '');

$sql = "SELECT faction_id, faction_name FROM " . FACTION_TABLE . " where game_id = '" . $game_id . "' order by faction_id";
$result = $db->sql_query($sql);

$data =array();
while ( $row = $db->sql_fetchrow($result))
{
	$data =array(
		'faction_id' => $member['faction_id'],
		'faction_name' =>  $member['faction_name'],
	);
}
$db->sql_freeresult($result);
$data= json_encode($data);
