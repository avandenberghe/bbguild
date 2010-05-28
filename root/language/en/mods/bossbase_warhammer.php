<?php
/**
 * bossprogress language file ported from BossSuite
 * updated for Wow, lotro, eq2
 * 
 * @author sz3
 * @author sajaki

 * @package bbDkp
 * @copyright 2006-2008 sz3
 * @copyright 2009 bbdkp <http://code.google.com/p/bbdkp/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id$
 * 
 */
 
 
/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(


/*
begin Warhammer bosses
*/

/****** altdorf ****/
'altdorf' => array('id' => 'altdorf', 'long' => 'Altdorf Sewers', 'short' => 'Altdor' ),
'kokrit' => array('id' => '19409', 'long' => 'Kokrit Man-Eater', 'short' => 'Kokrit' ),
'bulbous' => array('id' => '3650', 'long' => 'Bulbous One', 'short' => 'Bulbous'),
'fangchitter' => array('id' => '3651', 'long' => 'Prot and Vermer Fangchitter', 'short' => 'Fangchitter'),
'vitchek' => array('id' => '18762', 'long' => 'Master Moulder Vitchek', 'short' =>'Vitchek' ),
'goradian' => array('id' => '33401', 'long' => 'Goradian the Creator', 'short' => 'Goradian'),

/****** sacellum ****/
'sacellum' => array('id' => 'sacellum', 'long' => 'The Sacellum Dungeon', 'short' => 'sacellum' ),
'ghalmar' => array('id' => '25721', 'long' => 'Ghalmar Ragehorn', 'short' => 'Ghalmar' ),
'guzhak' => array('id' => '22044', 'long' => 'Guzhak the Betrayer', 'short' => 'Guzhak'),
'vul' => array('id' => '33173', 'long' => 'Vul The Bloodchosen', 'short' => 'Vul'),
'hoarfrost' => array('id' => '10256', 'long' => 'Hoarfrost', 'short' =>'Hoarfrost' ),
'sebcraw' => array('id' => '26812', 'long' => 'Sebcraw the Discarded', 'short' => 'Sebcraw'),
'thunder' => array('id' => '93573', 'long' => 'Slorth and Lorth Thunderbelly', 'short' => 'Thunderbelly'),
'breeder' => array('id' => '33172', 'long' => 'Snaptail the Breeder', 'short' => 'Snaptail'),
'goremane' => array('id' => '33182', 'long' => 'Goremane', 'short' => 'Goremane'),
'viraxil' => array('id' => '33181', 'long' => 'Viraxil the Broken', 'short' => 'Viraxil'),
 
/****** sacellum ****/
'gunbad' => array('id' => 'gunbad', 'long' => 'Mount Gunbad', 'short' => 'gundbad' ),
'griblik' => array('id' => '36549', 'long' => 'Griblik da Stinka', 'short' => 'Griblik' ),
'bilebane' => array('id' => '36547', 'long' => 'Bilebane the Rager', 'short' => 'Bilebane'),
'garrolath' => array('id' => '38234', 'long' => 'Garrolath the Poxbearer', 'short' => 'Garrolath'),
'foulm' => array('id' => '38623', 'long' => 'Foul Mouf da \'ungry', 'short' =>'Foul' ),
'kurga' => array('id' => '38624', 'long' => 'Kurga da Squig-Maker', 'short' => 'Kurga'),
'glomp' => array('id' => '38829', 'long' => 'Glomp the Squig Masta', 'short' => 'Glomp'),
'solithex' => array('id' => '37964', 'long' => 'Solithex', 'short' => 'Solithex'),


/****** The Bastion Stair ****/
'stair' => array('id' => 'stair', 'long' => 'The Bastion Stair', 'short' => 'stair' ),
'borzar' => array('id' => '9227', 'long' => 'Borzar Rageborn', 'short' => 'Rageborn' ),
'gahlvoth' => array('id' => '45224', 'long' => 'Gahlvoth Darkrage', 'short' => 'Gahlvoth' ),
'azuk' => array('id' => '47390', 'long' => 'Azuk\'Thul', 'short' => 'Azuk\'Thul' ),
'thar' => array('id' => '45084', 'long' => 'Thar\'lgnan', 'short' => 'Thar\'lgnan' ),
'urlf' => array('id' => '7622', 'long' => 'Urlf Daemonblessed', 'short' => 'Urlf' ),
'garithex' => array('id' => '7597', 'long' => 'Garithex the Mountain', 'short' => 'Garithex' ),
'chorek' => array('id' => '49164', 'long' => 'Chorek the Unstoppable', 'short' => 'Chorek' ),
'slarith' => array('id' => '48112', 'long' => 'Lord Slaurith', 'short' => 'Slaurith' ),
'wrackspite' => array('id' => '16078', 'long' => 'Wrackspite', 'short' => 'Wrackspite' ),
'clawfang' => array('id' => '46327', 'long' => 'Clawfang and Doomspike', 'short' => 'Clawfang' ),
'zekaraz' => array('id' => '46325', 'long' => 'Zekaraz the Bloodcaller', 'short' => 'Zekaraz' ),
'kaarn' => array('id' => '46330', 'long' => 'Kaarn the Vanquisher', 'short' => 'Kaarn' ),
'skullord' => array('id' => '64106', 'long' => 'Skull Lord Var\'Ithrok', 'short' => 'Var\'Ithrok' ),

/*
End Warhammer bosses
*/


));

?>
