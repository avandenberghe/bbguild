<?php
namespace bbdkp;
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**
 * Points interface
 *
 * @package 	bbDKP
 * 
 */
interface iPoints 
{

	/**
	 * Recalculates zero sum points
	 * -- loops all raids, may run a while
	 * @param $mode one for recalculating, 0 for setting zerosum to zero.
	 */
	function sync_zerosum($mode);
	
	
	
}

?>