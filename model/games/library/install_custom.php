<?php
/**
 * Custom Game Installer file
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace sajaki\bbdkp\model\games\library;

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}

/**
 * Custom Installer Class
 * @package sajaki\bbdkp\model\games\library
 */
class install_custom extends \sajaki\bbdkp\model\games\library\GameInstall
{
    protected $bossbaseurl = '';
    protected $zonebaseurl = '';

	/**
	 * Installs factions
	 */
    protected function Installfactions()
	{


	}

	/**
	 * Installs game classes
	 */
    protected function InstallClasses()
	{

	}

	/**
	 * Installs races
	 */
    protected function InstallRaces()
	{

	}

	/**
	 * Install sample Event Groups
	 * an Event answers the 'what' question
	*/
    protected function InstallEventGroup()
	{

	}

	/**
	 * Install sample Events and Events
	 * an Event answers the 'what' question
	*/
    protected function InstallEvents()
	{

	}



}
