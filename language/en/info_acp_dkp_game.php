<?php
/**
 * bbdkp acp language file for Game, Race and Class  (FR)
 *
 * @package phpBB Extension - bbdkp
 * @copyright 2009 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author sajaki <sajaki@gmail.com>
 * @link http://www.bbdkp.com
 * @version 2.0
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
    exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(
    'ACP_DKP_GAME'			=> 'Game Settings',
    'ACP_DKP_FACTION_ADD'		=> 'Add Faction',
    'ACP_DKP_RACE_ADD'		=> 'Add Race',
    'ACP_DKP_ROLE_ADD'		=> 'Add Role',
    'ACP_DKP_CLASS_ADD'		=> 'Add Class',
    'ACP_DKP_GAME_LIST'		=> 'Game List',
    'ACP_DKP_GAME_EDIT'		=> 'Edit Game',
));
