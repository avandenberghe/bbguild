<?php

namespace includes\bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

interface iRaces 
{
	/**
	 * gets race from database
	 */
	function Get();
	
	/**
	 * adds a race to database
	*/
	function Make();
	
	/**
	 * deletes a race from database
	*/
	function Delete();
	
	/**
	 * updates a race to database
	*/
	function Update(Races $old_race);

}

?>