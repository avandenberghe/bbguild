<?php
/**
 * bbguild acp language file for recruitments (FR)
 *
 * @package   phpBB Extension - bbguild
 * @copyright 2009 bbguild
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    sajaki
 * @link      http://www.avathar.be/bbdkp
 * @version   2.0
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
$lang = array_merge(
	$lang, array(
	'ACP_BBGUILD_RECRUIT'        => 'Recrutements',
	 'ACP_BBGUILD_RECRUIT_ADD'    => 'Ajouter recrutement',
	'ACP_BBGUILD_RECRUIT_EDIT'    => 'Modifier recrutement',
	'ACP_BBGUILD_RECRUIT_LIST'    => 'Recrutements',
	)
);
