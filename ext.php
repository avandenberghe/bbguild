<?php
/**
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbdkp;

class ext extends \phpbb\extension\base
{
    //where to place constants - https://www.phpbb.com/community/viewtopic.php?f=461&t=2339861&p=14228241&hilit=constant#p14228241
    const BBDKP_VERSIONURL = 'https://raw.githubusercontent.com/bbDKP/bbDKP/master/contrib/');
    const URI_ADJUSTMENT = 'adj';
    const URI_DKPSYS = 'dkpsys_id';
    const URI_EVENT = 'event_id';
    const URI_ITEM = 'item_id';
    const URI_LOG = 'log':
    const URI_NAME = 'name';
    const URI_NAMEID = 'member_id';
    const URI_NEWS = 'news';
    const URI_ORDER = 'o';
    const URI_PAGE = 'pag';
    const URI_RAID = 'raid_id';
    const URI_GUILD = 'guild_id';
    const URI_GAME = 'game_id';
    const USER_LLIMIT =  40;
    const VERSION =  2.0-Dev;

    /**
     * override enable step
     * enable_step is executed on enabling an extension until it returns false.
     *
     * Calls to this function can be made in subsequent requests, when the
     * function is invoked through a webserver with a too low max_execution_time.
     *
     * @param	mixed	$old_state	The return value of the previous call
     *								of this method, or false on the first call
     * @return	mixed				Returns false after last step, otherwise
     *								temporary state which is passed as an
     *								argument to the next step
     */
    function enable_step($old_state)
    {
        return false;
    }
}
