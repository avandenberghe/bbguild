<?php

namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

interface iFaction 
{
	/**
	 * gets faction from database
	 */
	function Get();
	
	/**
	 * adds a faction to database
	*/
	function Make();
	
	/**
	 * deletes a faction from database
	*/
	function Delete();
	
	/**
	 * updates a faction to database
	*/
	function Update(Faction $old_faction);
	
}

?>