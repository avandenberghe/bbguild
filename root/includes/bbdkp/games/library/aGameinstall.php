<?php
namespace bbdkp;

/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}
/**
 * Game interface
 * 
 *
 * @package 	bbDKP
 * 
 */
abstract class aGameinstall 
{
	
	public function install()
	{
		$this->Installfactions();
		$this->InstallClasses();
		$this->InstallRaces();
		$this->InstallEventGroup();
	}
	
	/**
	 * Installs factions
	 */
	abstract function Installfactions();
	
	/**
	 * Installs game classes
	*/
	abstract function InstallClasses();
	
	/**
	 * Installs races
	*/
	abstract function InstallRaces();
	
	/**
	 * Install sample Event Groups
	 * an Event answers the 'what' question
	 */
	public function InstallEventGroup()
	{
		
	}

	private function InstallEvents()
	{
		
	}
	
	/**
	 * install worldprogress (bossprogress successor) 
	 * installs Lands, Dungeons, bosses 
	 */
	public function InstallWorld()
	{
		// @todo 
	}
}

?>