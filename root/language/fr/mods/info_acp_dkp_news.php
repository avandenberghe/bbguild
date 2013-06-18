<?php
/**
 * bbdkp acp language file for News (FR)
 * 
 * 
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
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
    'ACP_DKP_NEWS'			=> 'Gestion des Actualités',
	'ACP_ADD_NEWS_EXPLAIN' =>  'Ici vous pouvez ajouter et modifier diverses actualités de la Guilde.',
	'ACP_DKP_NEWS_ADD'		=> 'Ajouter une Actualité',  
	'ACP_DKP_NEWS_LIST'		=> 'Liste des Actualités',
	'ACP_DKP_NEWS_LIST_EXPLAIN'	=> 'Ceci montre les Informations de la Guilde. ',
));

?>