<?php
/**
 * Aion Installer file
 *
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.4.1
 *
 */
namespace sajaki\bbdkp\controller\games;
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
 * Aion Installer Class
 *
 * @author Sajaki
 *
 */
class install_aion extends GameInstall
{
    protected $bossbaseurl = 'http://db.aion.ign.com/npc/%s';
    protected $zonebaseurl = 'http://db.aion.ign.com/%s';

	/**
	 * Installs factions
	 */
    protected function Installfactions()
	{
		global $db;

		// factions
		$db->sql_query('DELETE FROM ' . FACTION_TABLE . " where game_id = '" . $this->game_id . "'" );
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 1, 'faction_name' => 'Light' );
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 2, 'faction_name' => 'Darkness' );
		$db->sql_multi_insert( FACTION_TABLE , $sql_ary);
		unset ($sql_ary);

	}

	/**
	 * Installs game classes
	 */
    protected function InstallClasses()
	{
		global $db;

		// class general
		$db->sql_query('DELETE FROM ' . CLASS_TABLE . " where game_id = '" . $this->game_id . "'" );
		$sql_ary = array();
		// sub classes, excluding the original 4 classes, which are irrelevant endgame
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 0, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_unknown' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 1, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_spiritmaster' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 2, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_sorcerer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 3, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_assassin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 4, 'class_armor_type' => 'LEATHER' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_ranger' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 5, 'class_armor_type' => 'CLOTH' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_chanter' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 6, 'class_armor_type' => 'MAIL' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_cleric' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 7, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_gladiator' );
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 8, 'class_armor_type' => 'PLATE' , 'class_min_level' => 1 , 'class_max_level'  => 60, 'imagename' => 'aion_templar' );
		$db->sql_multi_insert( CLASS_TABLE , $sql_ary);
		unset ($sql_ary);

		//language table (No races, only factions, dummy value)
		$db->sql_query('DELETE FROM ' . BB_LANGUAGE . " where game_id = '" . $this->game_id . "' and attribute = 'class' ");

		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Unknown' ,  'name_short' =>  'Unknown' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Spiritmaster' ,  'name_short' =>  'Spiritmaster' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Sorcerer' ,  'name_short' =>  'Sorcerer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Assassin' ,  'name_short' =>  'Assassin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Ranger' ,  'name_short' =>  'Ranger' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Chanter' ,  'name_short' =>  'Chanter' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Cleric' ,  'name_short' =>  'Cleric' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Gladiator' ,  'name_short' =>  'Gladiator' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'en' , 'attribute' =>  'class' , 'name' =>  'Templar' ,  'name_short' =>  'Templar' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Inconnu' ,  'name_short' =>  'Inconnu' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Spiritualiste' ,  'name_short' =>  'Spiritualiste' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Sorcier' ,  'name_short' =>  'Sorcier' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Assassin' ,  'name_short' =>  'Assassin' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Rôdeur' ,  'name_short' =>  'Rôdeur' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Aède' ,  'name_short' =>  'Aède' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Clerc' ,  'name_short' =>  'Clerc' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Gladiateur' ,  'name_short' =>  'Gladiateur' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'fr' , 'attribute' =>  'class' , 'name' =>  'Templier' ,  'name_short' =>  'Templier' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Unbekannt' ,  'name_short' =>  'Unbekannt' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Beschwörer' ,  'name_short' =>  'Beschwörer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Zauberer' ,  'name_short' =>  'Zauberer' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Assassinen' ,  'name_short' =>  'Assassinen' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Jäger' ,  'name_short' =>  'Jäger' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Kantor' ,  'name_short' =>  'Kantor' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Kleriker' ,  'name_short' =>  'Kleriker' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Gladiator' ,  'name_short' =>  'Gladiator' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'de' , 'attribute' =>  'class' , 'name' =>  'Templer' ,  'name_short' =>  'Templer' );

        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'ignoto' ,  'name_short' =>  'ignoto' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Incantatore' ,  'name_short' =>  'Incantatore' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Fattucchiere' ,  'name_short' =>  'Fattucchiere' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Assassino' ,  'name_short' =>  'Assassino' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Cacciatore' ,  'name_short' =>  'Cacciatore' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Cantore' ,  'name_short' =>  'Cantore' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Chierico' ,  'name_short' =>  'Chierico' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Gladiatore' ,  'name_short' =>  'Gladiatore' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' =>  'it' , 'attribute' =>  'class' , 'name' =>  'Templare' ,  'name_short' =>  'Templare' );

		$db->sql_multi_insert ( BB_LANGUAGE , $sql_ary );
		unset ( $sql_ary );


	}


	/**
	 * Installs races
	 */
    protected function InstallRaces()
	{
		global $db;
		// races (No races, only factions, dummy value)
		$db->sql_query('DELETE FROM ' . RACE_TABLE . "  where game_id = '" . $this->game_id . "' ");
		$sql_ary = array();
		$sql_ary [] = array ('game_id' => $this->game_id,'race_id' => 1, 'race_faction_id' => 1, 'image_female' => 'aion_elyos', 'image_male' => 'aion_elyos'  );
		$sql_ary [] = array ('game_id' => $this->game_id,'race_id' => 2, 'race_faction_id' => 2, 'image_female' => 'aion_asmodian',  'image_male' => 'aion_asmodian' );
		$db->sql_multi_insert( RACE_TABLE , $sql_ary);
		unset ($sql_ary);

		//language table (No races, only factions, dummy value)
		$db->sql_query('DELETE FROM ' . BB_LANGUAGE . "  where game_id = '" . $this->game_id . "' and attribute = 'race' ");
		$sql_ary = array ();
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Elyos' ,  'name_short' =>  'Elyos' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'en' , 'attribute' =>  'race' , 'name' =>  'Asmodian' ,  'name_short' =>  'Asmodian' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Elyséens' ,  'name_short' =>  'Elyséens' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'fr' , 'attribute' =>  'race' , 'name' =>  'Asmodiens' ,  'name_short' =>  'Asmodiens' );

		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Elyos' ,  'name_short' =>  'Elyos' );
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'de' , 'attribute' =>  'race' , 'name' =>  'Asmodier' , 'name_short' =>  'Asmodian' );

        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' =>  'it' , 'attribute' =>  'race' , 'name' =>  'Elisiani' ,  'name_short' =>  'Elisiani' );
        $sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' =>  'it' , 'attribute' =>  'race' , 'name' =>  'Asmodiani' , 'name_short' =>  'Asmodiani' );
		$db->sql_multi_insert ( BB_LANGUAGE , $sql_ary );
		unset ( $sql_ary );

	}

	/**
	 * dkp pool created for lotro
	 * @var int
	 */
	private $aiondkpid = 0;

	/**
	 * Install sample Event Groups
	 * an Event answers the 'what' question
	*/
    protected function InstallEventGroup()
	{
		global $db;

		$sql = 'SELECT dkpsys_id FROM ' . DKPSYS_TABLE . " WHERE dkpsys_name = 'Aion Events' ";
		$result = $db->sql_query($sql);
		$this->aiondkpid = (int) $db->sql_fetchfield('dkpsys_id');
		$db->sql_freeresult($result);

		if ($this->aiondkpid == 0)
		{
			$db->sql_query('DELETE FROM ' . EVENTS_TABLE . ' WHERE event_dkpid = ' . $this->aiondkpid);

			// dkp pool
			$sql_ary = array (
					'dkpsys_name' => 'Aion Events',
					'dkpsys_status' => 'Y',
					'dkpsys_addedby' => 'admin',
					'dkpsys_default' => 'N' );
			$sql = 'INSERT INTO ' . DKPSYS_TABLE . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
			$this->aiondkpid = $db->sql_nextid();
			$this->InstallEvents();
		}
	}


	/**
	 * Install sample Events and Events
	 * an Event answers the 'what' question
	*/
    private function InstallEvents()
	{
		global $db;

		$sql_ary = array(
				'event_dkpid' => $this->aiondkpid ,
				'event_name' => 'Aion PVE',
				'event_color' => '#888888',
				'event_value' => 5,
				'event_imagename' => 'aion_pve' );

		$sql = 'INSERT INTO ' . EVENTS_TABLE . $db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
	}


    /**
     * installs default game roles
     */
    protected function InstallRoles()
    {

        global $db;
        $umil = new \umil();
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

        $db->sql_query('DELETE FROM ' .  BB_LANGUAGE . " WHERE attribute_id < 3 and attribute = 'role' and game_id = '" . $this->game_id  . "'");

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

?>