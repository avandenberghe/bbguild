<?php
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

/**
 * iClasses
 *
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */
interface iClasses 
{

	/**
	 * gets class from database
	 */
	function Get();
	
	/**
	 * adds a class to database
	*/
	function Make();
	
	/**
	 * deletes a class from database
	*/
	function Delete();
	
	/**
	 * updates a class to database
	*/
	function Update(Classes $old_class);
	
}

?>