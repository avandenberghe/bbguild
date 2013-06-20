<?php
namespace bbdkp;

interface iRealms
{

    /**
     * gets online status of this realm
     */
    function getAllRealmStatus();

    /**
     * Get status results for specified realm(s).
     *
	 * @param mixed $realms String or array of realm(s)
	 * @return mixed
     */
    function getRealmStatus($realms = array());

}

?>