<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 * @since 1.3.0
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
 * This class manages the phpbb_bbdkp_raid_detail transaction table
 * 
 * @package 	bbDKP
 * 
 */
 class Raids implements iRaids
{
	function __construct() 
	{
		
	}
	
	
	
	
	
}

?>