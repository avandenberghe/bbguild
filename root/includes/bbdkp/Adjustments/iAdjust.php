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
if (! defined('EMED_BBDKP'))
{
	$user->add_lang(array(
			'mods/dkp_admin'));
	trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}

interface iAdjust 
{
	function add();
	
	/**
	 * 
	 * @param string $order
	 * @param int $member_id
	 */
	function listadj($order, $member_id, $start=0);
	
	/**
	 * 
	 * @param int $member_id
	 */
	function countadjust($member_id);
	
	/**
	 * lists all pools where there is an adjustment
	 */
	function listAdjPools();
}

?>