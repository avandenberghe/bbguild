<?php
namespace bbdkp;
/**
 * this class manages the loot transaction table (phpbb_bbdkp_raid_items)
 *
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
 *
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

//include the abstract base interface
if (!interface_exists('\bbdkp\iLoot'))
{
	require ("{$phpbb_root_path}includes/bbdkp/Loot/iLoot.$phpEx");
}

/**
 * this class manages the loot transaction table (phpbb_bbdkp_raid_items)
 * 
 * @package 	bbDKP
 */
class Loot implements iLoot 
{
	function __construct() 
	{
	
	}
}

?>