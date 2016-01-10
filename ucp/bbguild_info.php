<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * info class for ucp module dkp
 *   @package bbguild
 */
class ucp_info
{	/**
 * phpbb module function
 */
    function module()
    {
        return array(
            'filename'	=> '\sajaki\bbguild\acp\bbguild_module',
            'title'		=> 'UCP_BBGUILD',
            'version'	=> '2.0.0',
            'modes'		=> array(
                'char'	=> array(
                    'title' => 'CHARACTERS',
                    'auth' => 'ext_sajaki/bbguild && u_charclaim',
                    'cat' => array('UCP_BBGUILD')),
                'add'	=> array(
                    'title' => 'CHARACTER_ADD',
                    'auth' => 'ext_sajaki/bbguild && u_charadd',
                    'cat' => array('UCP_BBGUILD')),
            ),
        );
    }
}
