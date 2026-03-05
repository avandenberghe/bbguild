<?php
/**
 * @package bbGuild Portal
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Portal language keys (French)
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
	'BBGUILD_PORTAL_MOTD'            => 'Message du jour',
	'BBGUILD_PORTAL_GUILD_NEWS'      => 'Nouvelles de la guilde',
	'BBGUILD_PORTAL_RECRUITMENT'     => 'Recrutement',
	'BBGUILD_PORTAL_ACTIVITY_FEED'   => 'Flux d\'activité',
	'BBGUILD_PORTAL_CUSTOM'          => 'Bloc personnalisé',

	// Module content
	'NO_RECRUITS'                    => 'Aucune position de recrutement ouverte.',
	'NO_ACTIVITY'                    => 'Aucune activité récente.',
	'BBGUILD_NO_PORTAL_MODULES'      => 'Aucun module de portail configuré pour cette guilde.',

	// Activity feed
	'LOOTED'                         => 'a obtenu',
	'FORNPOINTS'                     => ' pour %s points.',

	// Custom module ACP
	'BBGUILD_PORTAL_CUSTOM_SETTINGS' => 'Paramètres du bloc personnalisé',
	'BBGUILD_PORTAL_CUSTOM_TITLE'    => 'Titre du bloc',
	'BBGUILD_PORTAL_CUSTOM_CODE'     => 'Contenu du bloc',

	// ACP portal management
	'ACP_PORTAL_EXPLAIN'             => 'Gérer les modules du portail pour chaque guilde. Ajouter, supprimer, réorganiser et activer/désactiver les blocs.',
	'ACP_PORTAL_SELECT_GUILD'        => 'Sélectionner la guilde',
	'ACP_PORTAL_MODULES'             => 'Modules du portail',
	'ACP_PORTAL_MODULE_NAME'         => 'Module',
	'ACP_PORTAL_COLUMN'              => 'Colonne',
	'ACP_PORTAL_ORDER'               => 'Ordre',
	'ACP_PORTAL_COLUMN_TOP'          => 'Haut',
	'ACP_PORTAL_COLUMN_CENTER'       => 'Centre',
	'ACP_PORTAL_COLUMN_RIGHT'        => 'Droite',
	'ACP_PORTAL_ADD_MODULE'          => 'Ajouter un module',
	'ACP_PORTAL_NO_MODULES'          => 'Aucun module de portail configuré pour cette guilde. Ajoutez-en un ci-dessous.',
	'ACP_PORTAL_MODULE_ADDED'        => 'Le module du portail a été ajouté.',
	'ACP_PORTAL_MODULE_ADD_FAILED'   => 'Impossible d\'ajouter le module. Le module n\'est peut-être pas autorisé dans la colonne sélectionnée.',
	'ACP_PORTAL_MODULE_DELETED'      => 'Le module du portail a été supprimé.',
	'ACP_PORTAL_MOVE_LEFT'           => 'Déplacer vers la colonne précédente',
	'ACP_PORTAL_MOVE_RIGHT'          => 'Déplacer vers la colonne suivante',
]);
