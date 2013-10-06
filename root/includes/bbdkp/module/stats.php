<?php
/**
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */

/**
 * @ignore
 */
if ( !defined('IN_PHPBB') OR !defined('IN_BBDKP') )
{
	exit;
}
if (!class_exists('\bbdkp\LootController'))
{
	require("{$phpbb_root_path}includes/bbdkp/Loot/LootController.$phpEx");
}
$LootStats = new \bbdkp\LootController;

if (!class_exists('\bbdkp\Raids'))
{
	require("{$phpbb_root_path}includes/bbdkp/Raids/Raids.$phpEx");
}
$RaidStats = new \bbdkp\Raids;

$u_stats = append_sid ( "{$phpbb_root_path}dkp.$phpEx", 'page=stats' . '&amp;guild_id=' . $this->guild_id );
$title = $user->lang['MENU_STATS'];

$navlinks_array = array(
		array(
				'DKPPAGE' => $user->lang['MENU_STATS'],
				'U_DKPPAGE' => $u_stats,
		));
foreach( $navlinks_array as $name )
{
	$template->assign_block_vars('dkpnavlinks', array(
			'DKPPAGE' => $name['DKPPAGE'],
			'U_DKPPAGE' => $name['U_DKPPAGE'],
	));
}

$this->show_all = ( (isset($_GET['show'])) && (request_var('show', '') == "all") ) ? true : false;

$time = time() + $user->timezone + $user->dst;

$LootStats->MemberLootStats($time, $this->guild_id, $this->query_by_pool, $this->dkpsys_id, $this->show_all);
$LootStats->ClassLootStats(NULL, $this->guild_id, $this->query_by_pool, $this->dkpsys_id, $this->show_all);
$RaidStats->attendance_statistics($time, $u_stats, $this->guild_id, $this->query_by_pool, $this->dkpsys_id, $this->show_all);

// Output page
page_header($title);

?>