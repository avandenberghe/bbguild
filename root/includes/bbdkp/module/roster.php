<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
namespace bbdkp;
/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}


$this->selfurl = append_sid("{$phpbb_root_path}dkp.$phpEx" , 'page=roster');
$this->start = request_var('start' ,0);
$newroster->game_id = request_var('displaygame', '');

$newroster->get_listingresult();
//show chosen game

// push common data to template
foreach ($this->games as $id => $gamename)
{
	$template->assign_block_vars ( 'game_row', array (
			'VALUE' => $id,
			'SELECTED' => ($id == $this->game_id) ? ' selected="selected"' : '',
			'OPTION' => $gamename));
}

$template->assign_vars(array(
		'GUILDNAME'			=>  $config['bbdkp_guildtag'],
		'S_MULTIGAME'		=> (sizeof($this->games) > 1) ? true:false,
		'S_DISPLAY_ROSTER' => true,
		'F_ROSTER'			=> $newroster->selfurl,
		'S_GAME'		    => $newroster->game_id,
));


$newroster->mode = ($config['bbdkp_roster_layout'] == '0') ? 'listing' : 'class';
if($newroster->mode == 'class')
{
	$newroster->displaygrid();
}
else
{
	$newroster->displaylisting();
}


?>
