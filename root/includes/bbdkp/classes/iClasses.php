<?php

namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

interface iClasses {

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