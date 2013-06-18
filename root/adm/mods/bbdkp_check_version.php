<?php

if (!defined('IN_PHPBB'))
{
	exit;
}
/**
 * bbdkp_check_version Versionchecker MOD
 * 
 * @link 		http://www.bbdkp.com
 * @author 		Sajaki@gmail.com
 * @copyright 	2013 bbdkp
 * @license 	http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 	1.2.9
 * @since 		1.2.9
 *
 */
class bbdkp_check_version
{
	function version()
	{
		global $config, $phpbb_root_path, $phpEx;
		
		return array(
			'author'	=> 'Sajaki',
			'title'		=> 'bbDKP',
			'tag'		=> 'bbDKP',
			'version'	=> (isset($config['bbdkp_version']) ? $config['bbdkp_version'] : 'not installed'), 
			'file'		=> array('bbdkp.com', 'versioncheck', 'bbdkp_versioncheck.xml'),
		);
	}
}

?>