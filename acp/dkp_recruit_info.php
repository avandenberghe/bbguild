<?php
/**
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp\controller;

/**
 * info class for acp module dkp_guild
 *   @package bbdkp
 */
class dkp_recruit_info
{
    /**
     * phpbb module function
     */
    function module()
    {
        return array(
            'filename'	=> '\sajaki\bbdkp\acp\dkp_recruit_module',
            'title'		=> 'ACP_DKP_RECRUIT',
            'version'	=> '2.0.0',
            'modes'		=> array(
                'addrecruit'	    => array('title' => 'ACP_DKP_RECRUIT_ADD', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
                'editrecruit'	    => array('title' => 'ACP_DKP_RECRUIT_EDIT', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => false),
                'listrecruit'	    => array('title' => 'ACP_DKP_RECRUIT_LIST', 'auth' => 'acl_a_dkp', 'cat' => array('ACP_DKP_MEMBER'), 'display' => true),
            ));
    }
    /**
     * phpbb module function
     */
    function install()
    {
    }
    /**
     * phpbb module function
     */
    function uninstall()
    {
    }
}

