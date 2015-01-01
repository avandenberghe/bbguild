<?php
/**
 * ACP info class acp_dkp_recruit_info
 * @version 1.4.0
 * @copyright (c) 2009 bbdkp https://github.com/bbDKP
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *   @package bbdkp
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
    exit;
}

/**
 * info class for acp module dkp_guild
 *   @package bbdkp
 */
class acp_dkp_recruit_info
{
    /**
     * phpbb module function
     */
    function module()
    {
        return array(
            'filename'	=> 'acp_dkp_recruit',
            'title'		=> 'ACP_DKP_RECRUIT',
            'version'	=> '1.4.0',
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

