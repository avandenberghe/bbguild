<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author	  Andy Vandenberghe <sajaki9@gmail.com>
 * @author	  Chris Saylor
 * @author	  Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright 2013 bbdkp
 * @copyright 2011, Chris Saylor, Daniel Cannon, Andy Vandenberghe
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 * @since 1.2.9
 */

namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
    exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;
//include the abstract base
if (!interface_exists('\bbdkp\iRealms'))
{
	require ("{$phpbb_root_path}includes/bbdkp/realms/iRealms.$phpEx");
}
/**
 * @package 	bbDKP
 *
 */
class Realm implements iRealms
{

    function __construct ()
    {}

    /**
     *
     * @see \bbdkp\iRealms::getAllRealmStatus()
     */
    public function  getAllRealmStatus();

    /**
     *
     * @see \bbdkp\iRealms::getRealmStatus()
     */
    public function getRealmStatus($realms = array())
    {
        global $user, $phpbb_root_path, $phpEx;
        $user->add_lang ( array ('mods/wowapi' ));

        if (empty($realms))
        {
            trigger_error($user->lang['WOWAPI_NO_REALMS']);
        }

        elseif (!is_array($realms))
        {
            $data = $this->consume('status', array(
                    'data' => 'realm='.$realms
            ));
        }
        else
        {
            $realm_str = 'realms=';
            foreach($realms as $key => $realm)
            {
                $realm_str .= ($key == 0 ? '' : ',') . rawurlencode($realm);
            }
            $data = $this->consume('status', array(
                    'data' => $realm_str
            ));
        }
        return $data;
    }
}

?>