<?php
/**
 * Custom Game Installer file
 * does in fact nothing...
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 *
 */
namespace bbdkp\controller\games;
/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\controller\games\GameInstall'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/games/library/GameInstall.$phpEx");
}


/**
 * Custom Installer Class
 *
 * @author Sajaki
 *
 */
class install_custom extends \bbdkp\controller\games\GameInstall
{

	public $game_id;

	/**
	 * Installs factions
	 */
	public function Installfactions()
	{


	}

	/**
	 * Installs game classes
	 */
	public function InstallClasses()
	{

	}

	/**
	 * Installs races
	 */
	public function InstallRaces()
	{

	}

	/**
	 * Install sample Event Groups
	 * an Event answers the 'what' question
	*/
	public function InstallEventGroup()
	{

	}


	/**
	 * Install sample Events and Events
	 * an Event answers the 'what' question
	*/
	private function InstallEvents()
	{

	}
}

?>