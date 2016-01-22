<?php
/**
 * Returns rank xml based on ajax call 
 * 
 * @package acp\ajax
 * @copyright (c) 2009 bbDkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
define('IN_PHPBB', true);
define('ADMIN_START', true);

$guild_id = $request->variable('guild', 0);

$sql = 'SELECT a.rank_id, a.rank_name, b.game_id
        FROM ' . MEMBER_RANKS_TABLE . ' a, ' . GUILD_TABLE. ' b WHERE a.rank_hide = 0 and
        a.guild_id =  '. $guild_id . ' AND a.guild_id = b.id ORDER BY rank_id desc';

$result = $db->sql_query($sql);
// preparing json
$data =array();
while ( $row = $db->sql_fetchrow($result))
{
	 $data =array(
     'rank_game_id' => $row['game_id'],
     'rank_id' => $row['rank_id'],
	 'rank_name' => $row['rank_name']
	 );
}
$db->sql_freeresult($result);
$data= json_encode($data);

