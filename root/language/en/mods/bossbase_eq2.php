<?php
/**
 * bossprogress language eq2
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
// â€™ Â» â€œ â€� â€¦
//

$lang = array_merge($lang, array(

/*
Start EQ2 Bosses
example id numbers see 
http://eq2.allakhazam.com/db/mob.html?eq2mob=7803
http://eq2.allakhazam.com/db/quest.html?eq2quest=3033
*/

/** Rise of Kunark **/
'TRAKANON_LAIR' => array('id' => 'Trakanon_Lair', 'long' => 'Trakanon\'s Lair', 'short' => 'Trakanon Lair'),
'TRAKANON' => array('id' => 'Trakanon', 'long' => 'Trakanon', 'short' => 'Trakanon'),

/*****  Veeshan's Peak (Rise of Kunark)  ******/
'VEESHAN' => array('id' => 'Veeshan', 'long' => 'Veeshan\'s Peak', 'short' => 'Veeshan'),
'KLUZEN' => array('id' => 'Kluzen_the_Protector', 'long' => 'Kluzen the Protector', 'short' => 'Kluzen'),
'EKRON' => array('id' => 'Elder_Ekron', 'long' => 'Elder Ekron', 'short' => 'Ekron'),
'SILVERWING' => array('id' => 'Silverwing', 'long' => 'Silverwing', 'short' => 'Silverwing'),
'TRAVENRO' => array('id' => 'Travenro_the_Skygazer', 'long' => 'Travenro the Skygazer', 'short' => 'Travenro'),
'PHARA_DAR' => array('id' => 'Phara_Dar', 'long' => 'Phara Dar', 'short' => 'Phara Dar'),
'TASKMASTER_NICHOK' => array('id' => 'Taskmaster_Nichok', 'long' => 'Taskmaster Nichok', 'short' => 'Nichok'),
'QUNARD_ASHENCLAW' => array('id' => 'Qunard_Ashenclaw', 'long' => 'Qunard Ashenclaw', 'short' => 'qunard'),
'HOSHKAR' => array('id' => 'Hoshkar', 'long' => 'Hoshkar', 'short' => 'Hoshkar'),
'DRUUSHK' => array('id' => 'druushk', 'long' => 'Druushk', 'short' => 'Druushk'),
'MILYEX_VIOREN' => array('id' => 'Milyex_Vioren', 'long' => 'Milyex Vioren', 'short' => 'Milyex'),
'XYGOZ' => array('id' => 'Xygoz', 'long' => 'Xygoz', 'short' => 'Xygoz'),
'NEXONA' => array('id' => 'Nexona', 'long' => 'Nexona', 'short' => 'Nexona'),

/****** 8.3 Chamber of Destiny *****/
'DESTINY' => array('id' => 'Destiny', 'long' => 'Chamber of Destiny', 'short' => 'Destiny'),
'LEVIATHAN' => array('id' => 'The_Leviathan', 'long' => 'Leviathan', 'short' => 'Leviathan'),

/****** 8.2 Venril Sathir's Lair *****/
'VENRIL_SATHIR_LAIR' => array('id' => 'venril_sathir\'s_lair', 'long' => 'Venril Sathir\'s Lair', 'short' => 'Venril Sathir\'s Lair'),
'VENRIL' => array('id' => 'Venril_Sathir', 'long' => 'Venril Sathir', 'short' => 'Venril'),

/****** 8.2 The Temple of Kor-Sha *****/
'KORSHA' => array('id' => 'The_Temple_of_Kor-Sha', 'long' => 'The Temple of Kor-Sha', 'short' => 'Kor-Sha'),
'AKTAR_THE_DARK' => array('id' => 'Aktar_the_Dark', 'long' => 'Aktar the Dark', 'short' => 'Aktar'),
'ATREBE' => array('id' => 'Atrebe\'s_Statue', 'long' => 'Atrebe\'s Statue', 'short' => 'Atrebe'),
'ILYAN' => array('id' => 'Ilyan', 'long' => 'Ilyan', 'short' => 'Ilyan'),
'KODYX' => array('id' => 'Kodyx', 'long' => 'Kodux', 'short' => 'Kodux'),
'SELRACH' => array('id' => 'Selrach_Di\'Zok', 'long' => 'Selrach Di\'Zok', 'short' => 'Selrach'),
'UTHTAK_THE_CRUEL' => array('id' => 'Uthtak_the_Cruel', 'long' => 'Uthtak the Cruel', 'short' => 'Uthtak'),
'UZDRAK_THE_INVINCIBLE' => array('id' => 'Uzdrak_the_Invincible', 'long' => 'Uzdrak the Invincible', 'short' => 'Uzdrak'),
'ZARDA' => array('id' => 'Zarda', 'long' => 'Zarda', 'short' => 'Zarda'),

/****** 8.1 The Protector's Realm *****/
'PROTECTOR' => array('id' => 'The_Protector\'s_Realm', 'long' => 'The Protector\'s Realm', 'short' => 'Protector'),
'ADKAR_VYX' => array('id' => 'Adkar_Vyx', 'long' => 'Adkar Vyx', 'short' => 'Adkar'),
'ZYKLUK_VYX' => array('id' => 'Zykluk_Vyx', 'long' => 'Zykluk Vyx', 'short' => 'Zykluk'),
'IZTAPA_VYX' => array('id' => 'Iztapa_Vyx', 'long' => 'Iztapa Vyx', 'short' => 'Iztapa'),
'WYMBULU_VYX' => array('id' => 'Wymbulu_Vyx', 'long' => 'Wymbulu Vyx', 'short' => 'Wymbulu'),
'BLORGOK_THE_BRUTAL' => array('id' => 'Blorgok_the_Brutal', 'long' => 'Blorgok the Brutal', 'short' => 'Blorgok'),
'DOOMCOIL' => array('id' => 'Doomcoil', 'long' => 'Doomcoil', 'short' => 'Doomcoil'),
'IMZOK' => array('id' => 'Imzok\'s_Revenge', 'long' => 'Imzok\'s Revenge', 'short' => 'Imzok\'s Revenge'),
'JRACOL_BINARI' => array('id' => 'Jracol_Binari', 'long' => 'Jracol Binari', 'short' => 'Binari'),
'LUDMILA_KYSTOV' => array('id' => 'Ludmila_Kystov', 'long' => 'Ludmila Kystov', 'short' => 'Kystov'),

/****** 8.1 The Execution Throne Room *****/
'EXECUTION' => array('id' => 'The_Execution_Throne_Room', 'long' => 'The Execution Throne Room', 'short' => 'Execution'),
'PAWBUSTER' => array('id' => 'Pawbuster', 'long' => 'Pawbuster', 'short' => 'Pawbuster'),

/****** 8.1 The Tomb of Thuuga *****/
'THUUGA' => array('id' => 'The_Tomb_of_Thuuga', 'long' => 'The Tomb of Thuuga', 'short' => 'Thuuga'),
'TAIRIZA' => array('id' => 'Tairiza_the_Widow_Mistress', 'long' => 'Tairiza the Widow Mistress', 'short' => 'Tairiza'),

//'shard' => array('id' => 'shard', 'long' => 'Shard of Hate', 'short' => 'Shard of Hate'),

/* Echoes of Faydwer 3rd expansion November of 2006  Tier 8 */

/****** Mistmoore's Inner Sanctum *****/
'TUNARIA' => array('id' => 'Throne_of_New_Tunaria', 'long' => 'Throne of New Tunaria', 'short' => 'Tunaria'),
'HARBINGER_OF_ABSOLUTION' => array('id' => 'The_Harbinger_of_Absolution', 'long' => 'The Harbinger of Absolution', 'short' => 'The Harbinger of Absolution'),
'VAMPIRE_LORD_MAYONG_MISTMOORE' => array('id' => 'Vampire_Lord_Mayong_Mistmoore', 'long' => 'Vampire Lord Mayong Mistmoore', 'short' => 'Vampire Lord Mayong Mistmoore'),

/****** Mistmoore's Inner Sanctum *****/
'MISTMOORE' => array('id' => 'Mistmoore\'s_Inner_Sanctum', 'long' => 'Mistmoore\'s Inner Sanctum', 'short' => 'Mistmoore\'s Inner Sanctum'),
'MAYONG_MISTMOORE'  => array('id' => 'Mayong_Mistmoore_(Instanced)', 'long' => 'Mayong Mistmoore', 'short' => 'Mayong Mistmoore'),
'CHEROON' => array('id' => 'D\'Lizta Cheroon', 'long' => 'Vikomt D\'Raethe', 'short' => 'D\'Raethe'),
'KZALK' => array('id' => 'V\'Tekla K\'Zalk', 'long' => 'V\'Tekla K\'Zalk', 'short' => 'K\'Zalk'),
'VISWIN' => array('id' => 'D\'Lizta Viswin', 'long' => 'D\'Lizta Viswin', 'short' => 'Viswin'),
'ENYNTI' => array('id' => 'Enynti', 'long' => 'Enynti', 'short' => 'Enynti'),

/****** Freethinker's hideout *****/
'FREETHINKERS_HIDEOUT' => array('id' => 'Freethinkers_Hideout', 'long' => 'Freethinkers Hideout', 'short' => 'Freethinkers Hideout'),
'MALKONIS' => array('id' => 'Malkonis_D\'Morte', 'long' => 'Malkonis D\'Morte', 'short' => 'Malkonis'),
'TREYLOTH'  => array('id' => 'Treyloth_D\'Kulvith', 'long' => 'Treyloth D\'Kulvith', 'short' => 'Treyloth D\'Kulvith'),
'OTHYSIS' => array('id' => 'Othysis_Muravian', 'long' => 'Othysis Muravian', 'short' => 'Othysis Muravian'),
'ZYLPHAX' => array('id' => 'Zylphax_the_Shredder', 'long' => 'Zylphax the Shredder', 'short' => 'Zylphax the Shredder'),


/****** Emerald Halls *****/
'EMERALD_HALLS' => array('id' => 'Emerald_Halls', 'long' => 'Emerald Halls', 'short' => 'Emerald Halls'),
'WUOSHI'        => array('id' => 'Wuoshi', 'long' => 'Wuoshi', 'short' => 'Wuoshi'),
'GALIEL_SPIRITHOOF' => array('id' => 'Galiel_Spirithoof', 'long' => 'Galiel Spirithoof', 'short' => 'Galiel Spirithoof'),
'TREAH_GREENROOT' => array('id' => 'Treah_Greenroot', 'long' => 'Treah Greenroot', 'short' => 'Treah Greenroot'),
'SARIAH_THE_BLOOMSEEKER' => array('id' => 'Sariah_the_Bloomseeker', 'long' => 'Sariah the Bloomseeker', 'short' => 'Sariah the Bloomseeker'),
'MISTRESS_OF_THE_VEIL' => array('id' => 'Mistress_of_the_Veil', 'long' => 'Mistress of the Veil', 'short' => 'Mistress of the Veil'),
'SARIK_THE_FANG' => array('id' => 'Sarik_the_Fang', 'long' => 'Sarik the Fang', 'short' => 'Sarik the Fang'),
'SEGMENTED_RUMBLER' => array('id' => 'the_segmented_rumbler', 'long' => 'The Segmented Rumbler', 'short' => 'The Segmented Rumbler'),
'ELAANI_THE_COLLECTOR' => array('id' => 'Elaani_the_collector', 'long' => 'Elaani the collector', 'short' => 'Elaani the collector'),
'GARDENER_THIRGEN' => array('id' => 'Gardener_Thirgen', 'long' => 'Gardener Thirgen', 'short' => 'Gardener Thirgen'),
'THE_FARSTRIDE_UNICORN' => array('id' => 'The_Farstride_Unicorn', 'long' => 'The Farstride Unicorn', 'short' => 'The Farstride Unicorn'),
'TENDER_OF_THE_SEEDLINGS' => array('id' => 'Tender_of_the_Seedlings', 'long' => 'Tender of the Seedlings', 'short' => 'Tender of the Seedlings'),


/******Clockwork Menace Factory*****/
'CLOCKWORK_MENACE' => array('id' => 'The_Clockwork_Menace_Factory', 'long' => 'The Clockwork Menace Factory', 'short' => 'The Clockwork Menace Factory'),
'ROUND_1' => array('id' => 'The_Clockwork_Menace_Factory', 'long' => 'Round 1', 'short' => 'Round 1'),
'ROUND_2' => array('id' => 'The_Clockwork_Menace_Factory', 'long' => 'Round 2', 'short' => 'Round 2'),
'ROUND_3' => array('id' => 'The_Clockwork_Menace_Factory', 'long' => 'Round 3', 'short' => 'Round 3'),


/* fallen Dynasty The Third adventure pack for EverQuest II, released June 14th, 2006.  */

/******Xux'Laio's Roost*****/
'XLAR' => array('id' => 'Xux%27Laio%27s_Roost', 'long' => 'Xux\'Laio\'s Roost', 'short' => 'Xux\'Laio\'s Roost'),
'TATR' => array('id' => 'Xux%27Laio%27s_Roost', 'long' => 'The Ancient Twisted Root', 'short' => 'The Ancient Twisted Root'),
'TATF' => array('id' => 'Xux%27Laio%27s_Roost', 'long' => 'The Ancient Twisted Fungus', 'short' => 'The Ancient Twisted Fungus'),
'CRUSHER' => array('id' => 'Xux%27Laio%27s_Roost', 'long' => 'The Crusher', 'short' => 'The Crusher'),
'XUXLAIOM' => array('id' => 'Xux%27Laio%27s_Roost', 'long' => 'Xux\'laio Master of the Fluttering Wing', 'short' => 'Xux\'laio Master of the Fluttering Wing'),

/******Cavern of the Crustaceans*****/
'CRUSTACEANS' => array('id' => 'Cavern_of_the_Crustaceans', 'long' => 'Cavern of the Crustaceans', 'short' => 'Cavern of the Crustaceans'),
'BONESPLITTER' => array('id' => 'Cavern_of_the_Crustaceans', 'long' => 'Bonesplitter', 'short' => 'Bonesplitter'),


/** kingdom of sky **/

/******Trials of the Awakened: Trial of Leadership*****/
'TOL' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#Gur.27gul_the_Warden', 'long' => 'Trials of Awakened: Trial of Leadership', 'short' => 'Trials of Awakened: Trial of Leadership'),
'GURGUL' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#Gur.27gul_the_Warden', 'long' => 'Gur\'gul the Warden', 'short' => 'Gur\'gul the Warden'),
'KOGURGUL' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#Keeper_of_Gur.27gul', 'long' => 'Keeper of Gur\'gul', 'short' => 'Keeper of Gur\'gul'),
'KOG' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#Keeper_of_the_Gate',  'long' => 'Keeper of the Gate', 'short' => 'Keeper of the Gate'),
'FINAL_WARDEN' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#The_Final_Warden', 'long' => 'The Final Warden', 'short' => 'The Final Warden'),
'GOL' => array('id' => 'Trials_of_Awakened:_Trial_of_Leadership#The_Guardian_of_Leadership', 'long' => 'The Guardian of Leadership', 'short' => 'The Guardian of Leadership'),


/******Temple of Scale*****/
'temple_of_scale' => array('id' => 'Temple_of_Scale', 'long' => 'Temple of Scale', 'short' => 'Temple of Scale'),
'scaled_enforcer' => array( 'id' => 'Temple_of_Scale#The_Scaled_Enforcer', 'long' => 'The Scaled Enforcer', 'short' => 'The Scaled Enforcer'),
'firanvious' => array('id' => 'Temple_of_Scale#Firanvious', 'long' => 'Firanvious', 'short' => 'Firanvious'),
'irolesk' => array('id' => 'Temple_of_Scale#Irolesk', 'long' => 'Irolesk', 'short' => 'Irolesk'),
'pantrilla' => array('id' => 'Temple_of_Scale#Pantrilla', 'long' => 'Pantrilla', 'short' => 'Pantrilla'),
'vraksakin' => array('id' => 'Temple_of_Scale#Vraksakin', 'long' => 'Vraksakin', 'short' => 'Vraksakin'),
'zantril' => array('id' => 'Temple_of_Scale#Zantril', 'long' => 'Zantril', 'short' => 'Zantril'),
'harladar' => array('id' => 'Temple_of_Scale#Harla_Dar', 'long' => 'Harla Dar', 'short' => 'Harla Dar'),


/******Lyceum of Abhorrence*****/
'lyceum' => array('id' => 'Lyceum_of_Abhorrence', 'long' => 'Lyceum of Abhorrence', 'short' => 'Lyceum of Abhorrence'),
'essence_of_fear' => array('id' => 'Lyceum_of_Abhorrence#Essence_of_Fear', 'long' => 'Essence of Fear', 'short' => 'Essence of Fear'),
'gnillaw' => array('id' => 'Lyceum_of_Abhorrence#Gnillaw_the_Demented', 'long' => 'Gnillaw the Demented', 'short' => 'Gnillaw the Demented'),
'gnorbl' => array('id' => 'Lyceum_of_Abhorrence#Gnorbl_the_Playful', 'long' => 'Gnorbl the Playful', 'short' => 'Gnorbl the Playful'),
'vilucidae' => array('id' => 'Lyceum_of_Abhorrence#Vilucidae_the_Priest_of_Thule', 'long' => 'Vilucidae the Priest of Thule', 'short' => 'Vilucidae the Priest of Thule'),


/******The Lab of Lord Vyemm*****/
'lab_of_vyemm' => array('id' => 'The_Laboratory_of_Lord_Vyemm', 'long' => 'The Laboratory of Lord Vyemm', 'short' => 'The Laboratory of Lord Vyemm'),
'alzid' => array('id' => 'The_Laboratory_of_Lord_Vyemm#The_Slavering_Alzid', 'long' => 'The Slaving Alzid', 'short' => 'The Slaving Alzid'),
'doomright' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Doomright_Vakrizt', 'long' => 'Doomright Vakrizt', 'short' => 'Doomright Vakrizt'),
'pardas' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Pardas_Predd', 'long' => 'Pardas Predd', 'short' => 'Pardas Predd'),
'kinvah' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Kin.27vah.2C_Doom_Ravager_Ru.27ystad.2C_Doom_Reaver_Cheyak', 'long' => 'Doom Prophet Kinvah', 'short' => 'Doom Prophet Kinvah'),
'ruystad' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Kin.27vah.2C_Doom_Ravager_Ru.27ystad.2C_Doom_Reaver_Cheyak', 'long' => 'Doom Ravager Ru\'ystad', 'short' => 'Doom Ravager Ru\'ystad'),
'cheyak' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Doom_Prophet_Kin.27vah.2C_Doom_Ravager_Ru.27ystad.2C_Doom_Reaver_Cheyak', 'long' => 'Doom Reaver Cheyak', 'short' => 'Doom Reaver Cheyak'),
'uncaged_alzid' => array('id' => 'The_Laboratory_of_Lord_Vyemm#The_Uncaged_Alzid', 'long' => 'The Uncaged Alzid', 'short' => 'The Uncaged Alzid'),
'uustalastus' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Uustalastus_Xiterrax', 'long' => 'Uustalastus Xiterrax', 'short' => 'Uustalastus Xiterrax'),
'doomsworn' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Doomsworn_Zatrakh', 'long' => 'Doomsworn Zatrakh', 'short' => 'Doomsworn Zatrakh'),
'corsolander' => array('id' => 'The_Laboratory_of_Lord_Vyemm#The_Corsolander', 'long' => 'The Corsolander', 'short' => 'The Corsolander'),
'amdaatk' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Euktrzkai_Amdaatk', 'long' => 'Euktrzkai Amdaatk', 'short' => 'Euktrzkai Amdaatk'),
'vyemm' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Lord_Vyemm', 'long' => 'Lord Vyemm', 'short' => 'Lord Vyemm'),
'alzid_prime' => array('id' => 'The_Laboratory_of_Lord_Vyemm#Alzid_Prime', 'long' => 'Alzid Prime', 'short' => 'Alzid Prime'),


/******Halls of the Seeing*****/
'halls_of_the_seeing' => array('id' => 'Halls_of_the_Seeing', 'long' => 'Halls of the Seeing', 'short' => 'Halls of the Seeing'),
'shadowy_presence' => array('id' => 'Halls_of_the_Seeing#A_Shadowy_Presence', 'long' => 'A Shadowy Presence', 'short' => 'A Shadowy Presence'),
'charged_presence' => array('id' => 'Halls_of_the_Seeing#A_Charged_Presence', 'long' => 'A Charged Presence', 'short' => 'A Charged Presence'),
'elemental_warder' => array('id' => 'Halls_of_the_Seeing#The_Elemental_Warder', 'long' => 'The Elemental Warder', 'short' => 'The Elemental Warder'),
'pain' => array('id' => 'Halls_of_the_Seeing#Pain', 'long' => 'Pain', 'short' => 'Pain'),
'suffering' => array('id' => 'Halls_of_the_Seeing#Suffering', 'long' => 'Suffering', 'short' => 'Suffering'),
'bloodbeast' => array('id' => 'Halls_of_the_Seeing#BloodBeast', 'long' => 'BloodBeast', 'short' => 'BloodBeast'),
'venekor' => array('id' => 'Halls_of_the_Seeing#Venekor', 'long' => 'Venekor', 'short' => 'Venekor'),

/*****Deathtoll*******/
'deathtoll' => array('id' => 'Deathtoll', 'long' => 'Deathtoll', 'short' => 'Deathtoll'),
'yitzik' => array('id' => 'Deathtoll#Yitzik_the_Hurler', 'long' => 'Yitzik the Hurler', 'short' => 'Yitzik the Hurler'),
'fitzpitzle' => array('id' => 'Deathtoll#Fitzpitzle', 'long' => 'Fitzpitzle', 'short' => 'Fitzpitzle'),
'amorphous' => array('id' => 'Deathtoll#Amorphous_Drake', 'long' => 'Amorphous Drake', 'short' => 'Amorphous Drake'),
'tarinax' => array('id' => 'Deathtoll#Tarinax_the_Destroyer', 'long' => 'Tarinax the Destroyer', 'short' => 'Tarinax the Destroyer'),
'cruor' => array('id' => 'Deathtoll#Cruor_Alluvium', 'long' => 'Cruor Alluvium', 'short' => 'Cruor Alluvium'),

/******Ascent of the Awakening x2*****/
'awakening' => array('id' => '', 'long' => 'Ascent of the Awakening', 'short' => 'Ascent of the Awakening'),
'slashing_talon' => array('id' => 'Ascent_of_the_Awakeningx2#Prophet_of_The_Slashing_Talon_.5B67_Epic_x2.5D', 'long' => 'Prophet of The Slashing Talon', 'short' => 'Prophet of The Slashing Talon'),
'flapping_wing' => array('id' => 'Ascent_of_the_Awakeningx2#Ancient_of_the_Flapping_Wing_.5B67_Epic_x2.5D', 'long' => 'Ancient of the Flapping Wingz', 'short' => 'Barz'),
'ireth' => array('id' => 'Ascent_of_the_Awakeningx2#Ireth_The_Cold_.5B70_epicx2.5D', 'long' => 'Ireth the Cold', 'short' => 'Ireth the Cold'),
'sharti' => array('id' => 'Ascent_of_the_Awakeningx2#Sharti_of_The_Flame_.5B70_Epic_x2.5D', 'long' => 'Sharti of the Flame', 'short' => 'Sharti of the Flame'),
'gorenaire' => array('id' => 'Ascent_of_the_Awakeningx2', 'long' => 'Gorenaire', 'short' => 'Gorenaire'),
'talendor' => array('id' => 'Ascent_of_the_Awakeningx2', 'long' => 'Talendor', 'short' => 'Talendor'),


/*
End EQ2 bosses
*/



));

?>
