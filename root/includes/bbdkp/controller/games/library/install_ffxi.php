<?php
/**
 * bbdkp FFXI install data
 * 
 * @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.1
 *
 */
namespace bbdkp\controller\games;
use bbdkp\controller\games;

/**
 * @ignore
 */
if (! defined ( 'IN_PHPBB' ))
{
	exit ();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

if (!class_exists('\bbdkp\controller\games\GameInstall'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/games/library/GameInstall.$phpEx");
}

/**
 * FFXI Installer Class
 *   @package bbdkp
 *
 */
class install_ffxi extends GameInstall
{
    protected $bossbaseurl = 'http://ffxi.allakhazam.com/db/bestiary.html?fmob=%s';
    protected $zonebaseurl = 'http://ffxi.allakhazam.com/db/areas.html?farea=%s';

	/**
	 * Installs factions
	 */
    protected function Installfactions()
	{
		global  $db;

		// factions
		$db->sql_query('DELETE FROM ' . FACTION_TABLE . " where game_id = '".$this->game_id."'" );
		$sql_ary = array();
		$sql_ary[] = array('game_id' => '".$this->game_id."','faction_id' => 1, 'faction_name' => 'Bastok' );
		$sql_ary[] = array('game_id' => $this->game_id,'faction_id' => 2, 'faction_name' => 'San d’Oria' );
		$sql_ary[] = array('game_id' => $this->game_id,'faction_id' => 3, 'faction_name' => 'Windurst' );
		$sql_ary[] = array('game_id' => $this->game_id,'faction_id' => 4, 'faction_name' => 'Jueno' );
		$db->sql_multi_insert( FACTION_TABLE, $sql_ary);
		unset ($sql_ary);
		
	}
		
	/**
	 * Installs game classes
	*/
    protected function InstallClasses()
	{
		global  $db;


		// class :
		$db->sql_query('DELETE FROM ' . CLASS_TABLE . " where game_id = '".$this->game_id."'" );
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 0, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_unknown' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 1, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_warrior' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 2, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_monk' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 3, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_thief' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 4, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_white_mage' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 5, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_black_mage' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 6, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_blue_mage' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 7, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75, 'imagename' => 'ffxi_red_mage' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 8, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_paladin' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 9, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_dark_knight' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 10, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_dragoon' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 11, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_ninja' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 12, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_samurai' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 13, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_summoner' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 14, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_ranger' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 15, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_dancer' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 16, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_scholar' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 17, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_corsair' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 18, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_bard' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 19, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_beastmaster' );
		$sql_ary[] = array('game_id' => $this->game_id,'class_id' => 20, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 75 , 'imagename' => 'ffxi_puppetmaster' );
		$db->sql_multi_insert( CLASS_TABLE, $sql_ary);
		unset ($sql_ary);

		// Language table
		$db->sql_query('DELETE FROM ' . BB_LANGUAGE . "  where game_id = '".$this->game_id."' and attribute='class' ");
		
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id,'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 13, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 14, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 15, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 16, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 17, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 18, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 19, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 20, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );
		
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 13, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 14, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 15, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 16, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 17, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 18, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 19, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 20, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );
		
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Warrior' ,  'name_short' =>  'Warrior' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Monk' ,  'name_short' =>  'Monk' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Thief' ,  'name_short' =>  'Thief' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'White Mage' ,  'name_short' =>  'White Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Black Mage' ,  'name_short' =>  'Black Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Blue Mage' ,  'name_short' =>  'Blue Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Red Mage' ,  'name_short' =>  'Red Mage' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Paladin' ,  'name_short' =>  'Paladin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dark Knight' ,  'name_short' =>  'Dark Knight' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dragoon' ,  'name_short' =>  'Dragoon' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Ninja' ,  'name_short' =>  'Ninja' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Samurai' ,  'name_short' =>  'Samurai' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 13, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Summoner' ,  'name_short' =>  'Summoner' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 14, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 15, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Dancer' ,  'name_short' =>  'Dancer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 16, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Scholar' ,  'name_short' =>  'Scholar' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 17, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Corsair' ,  'name_short' =>  'Corsair' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 18, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Bard' ,  'name_short' =>  'Bard' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 19, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Beastmaster' ,  'name_short' =>  'Beastmaster' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 20, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Puppetmaster' ,  'name_short' =>  'Puppetmaster' );
		
		$db->sql_multi_insert (  BB_LANGUAGE  , $sql_ary );
		unset ( $sql_ary );
		
	}
	
	/**
	 * Installs races
	*/
    protected function InstallRaces()
	{
		global  $db;
		
		$db->sql_query('DELETE FROM ' .  RACE_TABLE . "  where game_id = '".$this->game_id."'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 1, 'race_faction_id' => 3 ); //Unknown
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 2, 'race_faction_id' => 1 ); //Galka
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 3, 'race_faction_id' => 1 ); //Hume
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 4, 'race_faction_id' => 2 ); ///Elvaan
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 5, 'race_faction_id' => 3 ); //Tarutaru
		$sql_ary[] = array('game_id' => $this->game_id,'race_id' => 6, 'race_faction_id' => 3 ); //Mithra
		$db->sql_multi_insert(  RACE_TABLE , $sql_ary);
		

		// Language table
		$db->sql_query('DELETE FROM ' . BB_LANGUAGE . "  where game_id = $this->game_id and attribute='race' ");
		$sql_ary = array();
		
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
		
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
		
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Galka' ,  'name_short' =>  'Galka' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Hume' ,  'name_short' =>  'Hume' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Elvaan' ,  'name_short' =>  'Elvaan' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Tarutaru' ,  'name_short' =>  'Tarutaru' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Mithra' ,  'name_short' =>  'Mithra' );
		$db->sql_multi_insert (  BB_LANGUAGE  , $sql_ary );
		unset ( $sql_ary );
		
	}


    /**
     * Event Groups
     * see parent function InstallEventGroup()
     */
    protected function  InstallEventGroup()
    {

    }


    /**
     * installs default game roles
     */
    protected function InstallRoles()
    {

        global $umil, $db;
        $db->sql_query('DELETE FROM ' .  BB_GAMEROLE_TABLE . " WHERE role_id < 3 and game_id = '" . $this->game_id . "'");

        $umil->table_row_insert(BB_GAMEROLE_TABLE, array(
            array(
                // dps
                'game_id'  		   => $this->game_id ,
                'role_id'    	   => 0,
                'role_color'       => '#FF4455',
                'role_icon'    	   => 'dps_icon',
            ),
            array(
                // healer
                'game_id'  		   => $this->game_id ,
                'role_id'    	   => 1,
                'role_color'       => '#11FF77',
                'role_icon'    	   => 'healer_icon',
            ),
            array(
                // tank
                'game_id'  		   => $this->game_id ,
                'role_id'    	   => 2,
                'role_color'       => '#c3834c',
                'role_icon'    	   => 'tank_icon',
            ),
        ));

        $db->sql_query('DELETE FROM ' .  BB_LANGUAGE . " WHERE attribute_id < 3 and  attribute = 'role' and game_id = '" . $this->game_id  . "'");

        //english
        $umil->table_row_insert( BB_LANGUAGE, array(
            array(
                // dps
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  0,
                'language'          => 'en',
                'attribute'    	    => 'role',
                'name'    	        => 'Damage',
                'name_short'        => 'DPS',
            ),
            array(
                // healer
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  1,
                'language'          => 'en',
                'attribute'    	    => 'role',
                'name'    	        => 'Healer',
                'name_short'        => 'HPS',
            ),
            array(
                // defense
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  2,
                'language'          => 'en',
                'attribute'    	    => 'role',
                'name'    	        => 'Defense',
                'name_short'        => 'DEF',
            ),
        ));

        //french
        $umil->table_row_insert( BB_LANGUAGE, array(
            array(
                // dps
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  0,
                'language'          => 'fr',
                'attribute'    	    => 'role',
                'name'    	        => 'Dégats',
                'name_short'        => 'DPS',
            ),
            array(
                // healer
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=> 1,
                'language'          => 'fr',
                'attribute'    	    => 'role',
                'name'    	        => 'Soigneur',
                'name_short'        => 'HPS',
            ),
            array(
                // tank
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=> 2,
                'language'          => 'fr',
                'attribute'    	    => 'role',
                'name'    	        => 'Défense',
                'name_short'        => 'DEF',
            ),
        ));

        //german
        $umil->table_row_insert(BB_LANGUAGE, array(
            array(
                // dps
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=> 0,
                'language'          => 'de',
                'attribute'    	    => 'role',
                'name'    	        => 'Kämpfer',
                'name_short'        => 'Schaden',
            ),
            array(
                // healer
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  1,
                'language'          => 'de',
                'attribute'    	    => 'role',
                'name'    	        => 'Heiler',
                'name_short'        => 'Heil',
            ),
            array(
                // tank
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=>  2,
                'language'          => 'de',
                'attribute'    	    => 'role',
                'name'    	        => 'Verteidigung',
                'name_short'        => 'Schutz',
            ),
        ));

        //Italian
        $umil->table_row_insert(BB_LANGUAGE, array(
            array(
                // dps
                'game_id'  		    => $this->game_id ,
                'attribute_id'    	=> 0,
                'language'          => 'it',
                'attribute'    	    => 'role',
                'name'    	        => 'Danni',
                'name_short'        => 'Danni',
            ),
            array(
                // healer
                'game_id'  		    =>  $this->game_id ,
                'attribute_id'    	=> 1,
                'language'          => 'it',
                'attribute'    	    => 'role',
                'name'    	        => 'Cura',
                'name_short'        => 'Cura',
            ),
            array(
                // tank
                'game_id'  		    =>  $this->game_id ,
                'attribute_id'    	=>  2,
                'language'          => 'it',
                'attribute'    	    => 'role',
                'name'    	        => 'Difeza',
                'name_short'        => 'Tank',
            ),
        ));

    }
	

}