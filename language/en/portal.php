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

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Custom Block Settings',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Block Title',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Block Content',

]);
