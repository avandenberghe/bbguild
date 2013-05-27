<?php

namespace includes\bbdkp;

interface iMembers {

	/**
	 * gets member from database
	 */
	function Get();

	/**
	 * adds a member to database
	 */
	function Make();

	/**
	 * deletes a member from database
	 */
	function Delete();

	/**
	 * updates a member to database
	 */
	function Update($old_member);
}

?>