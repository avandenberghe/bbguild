<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
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
require_once ("{$phpbb_root_path}includes/bbdkp/Raids/iRaids.$phpEx");

/**
 * This class manages the phpbb_bbdkp_raid_items transaction table
 * 
 * raid_id
 * member_id
 * raid_value
 * time_bonus 
 * zerosum_bonus 
 * raid_decay 
 * decay_time 
 * 
 */
class Raids implements iRaids
{
	function __construct() 
	{
		
	}
}

?>