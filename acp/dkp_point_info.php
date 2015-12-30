<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\acp;

/**
 * info class for acp module dkp_point
 *   @package bbdkp
 */
class dkp_point_info
{
    /**
     * phpbb module function
     */
    function module()
    {
        return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_point_module',
            'title'		=> 'ACP_DKP_POINT_CONFIG',
            'version'	=> '2.0.0',
            'modes'		=> array(
                'pointconfig'			=> array(
                    'title' => 'ACP_DKP_POINT_CONFIG',
                    'auth' => 'acl_a_dkp',
                    'cat' => array('ACP_DKP_MAINPAGE')),
            ),
        );
    }

}

?>
