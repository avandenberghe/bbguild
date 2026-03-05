<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (English)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	// Module names
	'BBGUILD_PORTAL_MOTD'            => 'Message of the Day',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Guild News',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Recruitment',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Activity Feed',
	'BBGUILD_PORTAL_CUSTOM'          => 'Custom Block',

	// Module content
	'NO_RECRUITS'                    => 'No open recruitment positions.',
	'NO_ACTIVITY'                    => 'No recent activity.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'No portal modules configured for this guild.',

	// Activity feed
	'LOOTED'                         => 'obtained',
	'FORNPOINTS'                     => ' for %s points.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Custom Block Settings',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Block Title',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Block Content',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Manage portal modules for each guild. Add, remove, reorder, and enable/disable blocks.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Select Guild',
	'ACP_PORTAL_MODULES'             => 'Portal Modules',
	'ACP_PORTAL_MODULE_NAME'         => 'Module',
	'ACP_PORTAL_COLUMN'              => 'Column',
	'ACP_PORTAL_ORDER'               => 'Order',
	'ACP_PORTAL_COLUMN_TOP'          => 'Top',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Center',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Right',
	'ACP_PORTAL_ADD_MODULE'          => 'Add Module',
	'ACP_PORTAL_NO_MODULES'          => 'No portal modules configured for this guild. Add one below.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Portal module has been added.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Could not add portal module. The module may not be allowed in the selected column.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Portal module has been removed.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Move to previous column',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Move to next column',
]);
