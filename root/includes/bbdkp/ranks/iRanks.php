<?php

namespace includes\bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

interface iRanks
{

	/**
	 * gets rank from database
	 */
	function Get();

	/**
	 * adds a rank to database
	*/
	function Make();

	/**
	 * deletes a rank from database
	*/
	function Delete($override);

	/**
	 * updates a rank to database
	*/
	function Update(Ranks $old_rank);

}
