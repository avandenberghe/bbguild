<?php
/**
 * returns race & class xml based on ajax call
 * @package acp\ajax
 * @copyright (c) 2011 https://github.com/bbDKP
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
define('IN_PHPBB', true);
$game_id = $request->variable('game_id', '');

$sql_array = array(
    'SELECT'	=>	'  r.race_id, l.name as race_name ',
    'FROM'		=> array(
        RACE_TABLE		=> 'r',
        BB_LANGUAGE		=> 'l',
    ),
    'WHERE'		=> " r.race_id = l.attribute_id
					AND r.game_id = '" . $game_id . "' 
					AND l.attribute='race' 
					AND l.game_id = r.game_id 
					AND l.language= '" . $config['bbdkp_lang'] ."'",
    'ORDER_BY'	=> 'l.name',
);
$sql = $db->sql_build_query('SELECT', $sql_array);
$result = $db->sql_query($sql);


$races =array();
while ( $row = $db->sql_fetchrow($result))
{
    $races =array(
        'race_id' => $row['race_id'],
        'race_name' => $row['race_name']
    );;
}

//now get classes
$sql_array = array(
    'SELECT'	=>	' c.class_id, l.name as class_name ',
    'FROM'		=> array(
        CLASS_TABLE		=> 'c',
        BB_LANGUAGE		=> 'l',
    ),
    'WHERE'		=> " l.game_id = c.game_id AND c.game_id = '" . $game_id . "'
	AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",
);
$sql = $db->sql_build_query('SELECT', $sql_array);
$result1 = $db->sql_query($sql);
$classes = array();
while ( $row1 = $db->sql_fetchrow($result1))
{
    $classes = array(
        'class_id' => $row1['class_id'],
        'class_name' => $row1['class_name']
    );
}
$db->sql_freeresult($result);
$db->sql_freeresult($result1);

$data = json_encode(array(
    'races'=> $races,
    'classes'=> $classes,
));
