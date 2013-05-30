<?php
namespace includes\bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

interface iGuilds {

	/**
	 * gets guild from database
	 */
	function Get();

	/**
	 * adds a guild to database
	 */
	function Make();

	/**
	 * deletes a guild from database
	 */
	function Delete();

	/**
	 * updates a guild to database
	 */
	function Update($old_guild);

	/**
	 * lists all members for this guild
	 * @param unknown_type $order
	 * @param unknown_type $start
	 */
	function listmembers($order, $start);

	/**
	 * counts all guild members
	 */
	function countmembers();



}
