<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * @since 1.2.9 
 */
namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;
require_once ("{$phpbb_root_path}includes/bbdkp/Loot/iLoot.$phpEx");

/**
 * this class manages the loot transaction table (phpbb_bbdkp_raid_items)
 * 
 * item_id pk
 * raid_id key
 * item_name 
 * member_id 
 * item_date 
 * item_added_by 
 * item_updated_by 
 * item_group_key 
 * item_gameid 
 * item_value 
 * item_decay 
 * item_zs 
 * decay_time 
 *
 */
class Loot implements iLoot 
{
	function __construct() 
	{
	
	}
}

?>