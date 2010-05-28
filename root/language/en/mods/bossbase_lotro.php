<?php
/**
 * bossprogress language file lotro
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
Start LOTRO Bosses - note that a number of Alakhazam 'id numbers' are still needed, waiting for Alakhazam to update. 
*/

/****** Lotro Miscellaneous bosses *****/
'lotro_misc' => array('id' => '', 'long' => 'Miscellaneous bosses', 'short' => 'Misc'),
'ferndur' => array('id' => '3814','long' => 'Ferndur', 'short' => 'Ferndur'),
'bogbereth' => array('id' =>'2062', 'long' => 'Bogbereth', 'short' => 'Bogbereth'),

/********Annuminas************/
'annuminas' => array('id' => '', 'long' => 'Annuminas - Glinghant', 'short' => 'Annu - Garden'),
'glinghant' => array('id' => '', 'long' => 'Annuminas - Glinghant', 'short' => 'Annu - Garden'),
'nengon' => array('id' => '3255','long' => 'Nengon', 'short' => 'Nengon'),
'ost_elendil' => array('id' => '1572','long' => 'Annuminas - Ost Elendil', 'short' => 'Annu - Palace'),
'guloth' => array('id' => '','long' => 'Guloth', 'short' => 'Guloth'),
'balhest' => array('id' => '1572','long' => 'Balhest', 'short' => 'Balhest'),
'haudh_valandil' => array('id' => '','long' => 'Annuminas - Haudh Valandil', 'short' => 'Annu - Tomb'),
'shingrinder' => array('id' => '','long' => 'Shingrinder', 'short' => 'Shingrinder'),
'dolvaethor' => array('id' => '','long' => 'Dolvaethor', 'short' => 'Dolvaethor'),
'valandil' => array('id' => '','long' => 'Valandil of Arnor', 'short' => 'Valandil'),

/***** Helegrod *****/
'helegrod' => array('long' => 'Helegrod', 'short' => 'Helegrod'),
'coldbear' => array('id' =>'2287', 'long' => 'Coldbear', 'short' => 'Coldbear'),
'storvagun' => array('id' =>'2265', 'long' => 'Storvagun', 'short' => 'Storvagun'),
'ansach' => array('id' =>'2251', 'long' => 'Ansach', 'short' => 'Ansach'),
'breosal' => array('id' =>'2254', 'long' => 'Breosal', 'short' => 'Breosal'),
'grisgart' => array('id' =>'2252', 'long' => 'Grisgart', 'short' => 'Grisgart'),
'adhargul' => array('id' =>'2253', 'long' => 'Adhargul', 'short' => 'Adhargul'),
'zaudru' => array('id' =>'2281', 'long' => 'Zaudru', 'short' => 'Zaudru'),
'drugoth' => array('id' =>'2282', 'long' => 'Drugoth The Death-Monger', 'short' => 'Drugoth'),
'thorog' => array ('id' =>'2257', 'long' => 'Thorog', 'short' => 'Thorog'),

/******Fornost*****/
'fornost' => array('long' => 'Fornost', 'short' => 'Fornost'),
'brogadan' => array('id' => '','long' => 'Brogadan', 'short' => 'Bragadan'),
'megoriath' => array('id' => '','long' => 'Megoriath', 'short' => 'Megoriath'),
'rhavameldir' => array('id' => '','long' => 'Rhavameldir', 'short' => 'Rhavameldir'),
'warchief_burzghash' => array('id' => '3367','long' => 'Warchief Burzgash', 'short' => 'Burzgash'),
'zhurmat' => array('id' => '','long' => 'Zhurmat', 'short' => 'Zhurmat'),
'riamul' => array('id' => '','long' => 'Riamul', 'short' => 'Riamul'),
'zanthrug' => array('id' => '','long' => 'Zanthrug', 'short' => 'Zanthrug'),
'krithmog' => array('id' => '2081','long' => 'Krithmog', 'short' => 'Krithmog'),
'einiora' => array('id' => '','long' => 'Einiora', 'short' => 'Einiora'),
'remmenaeg' => array('id' => '3812','long' => 'Remmenaeg', 'short' => 'Remmenaeg'),

/******The Rift*****/
'rift' => array('long' => 'The Rift of Nrz Ghshu', 'short' => 'The Rift'),
'zurm' => array('id' => '2754', 'long' => 'Zurm', 'short' => 'Zurm'),
'barz' => array('id' => '1338', 'long' => 'Barz', 'short' => 'Barz'),
'fruz' => array('id' => '2755', 'long' => 'Fruz', 'short' => 'Fruz'),
'zogtark' => array('id' => '2748', 'long' => 'Zogtark', 'short' => 'Zogtark'),
'narnulubat' => array('id' => '2756', 'long' => 'Narnulubat', 'short' => 'Narn˚lubat'),
'shadow_eater' => array('id' => '2757', 'long' => 'Shadow Eater', 'short' => 'Eater'),
'thrang' => array('id' => '2759', 'long' => 'Thrang', 'short' => 'Thrang'),
'thaurlach' => array('id' => '2760', 'long' => 'Thaurlach', 'short' => 'Thaurlach'),

/******Urugarth*****/
'urugarth' => array('long' => 'Urugarth', 'short' => 'Urugarth'),
'sorkrank' => array('id' => '4036', 'long' => 'Sorkrank', 'short' => 'Sorkrank'),
'burzfil' => array('id' => '2821', 'long' => 'Burzfil', 'short' => 'Burzfil'),
'dushkal' => array('id' => '3809', 'long' => 'Dushkal', 'short' => 'Dushkal'),
'akrur' => array('id' => '4037', 'long' => 'Akrur', 'short' => 'Akrur'),
'kughurz' => array('id' => '', 'long' => 'Kughurz', 'short' => 'Kughurz'),
'lamkarn' => array('id' => '', 'long' => 'Lamkarn', 'short' => 'Lamkarn'),
'athpukh' => array('id' => '2068', 'long' => 'Athpukh', 'short' => 'Athpukh'),
'lhugrien' => array('id' => '2067', 'long' => 'Lhugrien', 'short' => 'Lhugrien'),
'morthrang' => array('id' => '2485', 'long' => 'Morthrang', 'short' => 'Morthrang'),
'gruglok' => array('id' => '2136', 'long' => 'Gruglok', 'short' => 'Gruglok'),
'lagmas' => array('id' => '2480', 'long' => 'Lagmas', 'short' => 'Lagmas'),

/*****Barad Gularan*******/
'barad_gularan' => array('long' => 'Barad Gularan', 'short' => 'BG'),
'forvengwath' => array('id' => '2329', 'long' => 'Forvengwath', 'short' => 'Forvengwath'),
'wisdan' => array('id' => '2352', 'long' => 'Castellan Wisdan', 'short' => 'Wisdan'),
'udunion' => array('id' => '2331', 'long' => 'Udunion', 'short' => 'Udunion'),

/******Carn Dum*****/
'carn_dum' => array('long' => 'Carn Dum', 'short' => 'CD'),
'urro' => array('id' => '', 'long' => 'Urro', 'short' => 'Urro'),
'barashal' => array('id' => '1930', 'long' => 'Barashal', 'short' => 'Barashal'),
'helchgam' => array('id' => '2425', 'long' => 'Helchgam', 'short' => 'Helchgam'),
'salvakh' => array('id' => '3827', 'long' => 'Salvakh', 'short' => 'Salvakh'),
'azgoth' => array('id' => '2111', 'long' => 'Azgoth', 'short' => 'Azgoth'),
'tarlug' => array('id' => '2303', 'long' => 'Tarlug', 'short' => 'Tarlug'),
'mormoz' => array('id' => '2304', 'long' => 'Mormoz', 'short' => 'Mormoz'),
'rodakhan' => array('id' => '2306', 'long' => 'Rodakhan', 'short' => 'Rodakhan'),
'mura' => array('id' => '2308', 'long' => 'Mura', 'short' => 'Mura'),
'gurthul' => array('id' => '2309', 'long' => 'Gurthul', 'short' => 'Gurthul'),
'mordirith' => array('id' => '2315', 'long' => 'Mordirith', 'short' => 'Mordirith'),

/******The Great Barrow*****/
'great_barrow' => array('long' => 'The Great Barrow', 'short' => 'GB'),
'gaerthel_gaerdring' => array('id' => '1767', 'long' => 'Gaerthel & Gaerdring', 'short' => 'G & G'),
'thadur' => array('id' => '1760', 'long' => 'Thadur the Ravager', 'short' => 'Thad˙r'),
'sambrog' => array('id' => '1255', 'long' => 'Sambrog', 'short' => 'Sambrog'),

/******Garth Agarwen*****/
'garth_agarwen' => array( 'long' => 'Garth Agarwen', 'short' => 'GA'),
'temair' => array('id' => '4156', 'long' => 'Temair the Devoted', 'short' => 'Temair'),
'grimbark' => array('id' => '2519', 'long' => 'Grimbark', 'short' => 'Grimbark'),
'edan_esyld' => array('id' => '4176', 'long' => 'Edan & Esyld', 'short' => 'E & E'),
'ivar' => array('id' => '3489', 'long' => 'Ivar the Bloodhand', 'short' => 'Ivar'),
'vatar' => array('id' => '', 'long' => 'Vatar', 'short' => 'Vatar'),
'naruhel' => array('id' => '3813', 'long' => 'Naruhel - The Red Maid', 'short' => 'Naruhel'),

/***** Ettenmoors - Creeps *****/
'ettenmoors_creeps' => array ('long' => 'Ettenmoors', 'short' => 'Ettenmoors'),
'tharbil' => array('id' => '1331', 'long' => 'Tyrant Tharbil', 'short' => 'Tharbil'),
'burzgoth' => array('id' => '1337', 'long' => 'Tyrant Burzgoth', 'short' => 'Burzgoth'),
'gundzor' => array('id' => '', 'long' => 'Tyrant Gundzor', 'short' => 'Gundzor'),
'durgrat' => array('id' => '', 'long' => 'Tyrant Durgrat', 'short' => 'Durgrat'),
'trintru' => array('id' => '', 'long' => 'Tyrant Trintru', 'short' => 'Trintru'),
'barashish' => array('id' => '', 'long' => 'Tyrant Barashish', 'short' => 'Barashish'),

/***** Ettenmoors - Freeps *****/
'ettenmoors_freeps' => array ('long' => 'Ettenmoors', 'short' => 'Ettenmoors'),
'bordagor' => array('id' => '2430', 'long' => 'Captain-General Bordagor', 'short' => 'Bordagor'),
'lainedhel' => array('id' => '1716', 'long' => 'Captain-General Lainedhel', 'short' => 'Lainedhel'),
'verdantine' => array('id' => '', 'long' => 'Captain-General Verdantine', 'short' => 'Verdantine'),
'makan' => array('id' => '', 'long' => 'Captain-General Makan', 'short' => 'Makan'),
'meldun' => array('id' => '', 'long' => 'Captain-General Meldun', 'short' => 'Meldun'),
'harvestgain' => array('id' => '', 'long' => 'Captain-General Harvestgain', 'short' => 'Harvestgain'),

/*
End LOTRO Bosses
*/



));

?>
