<?php
/**
 * bbdkp LOTRO install data
 * @package bbDkp-installer
 * @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
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

function install_lotro($bbdkp_table_prefix)
{
    global  $db, $table_prefix, $umil, $user;
    
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'classes');
    $sql_ary = array();

    // class : 
    $sql_ary[] = array('class_id' => '0', 'class_name' => 'Unknown', 'class_armor_type' => 'Medium Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '1', 'class_name' => 'Burglar', 'class_armor_type' => 'Medium Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '2', 'class_name' => 'Captain', 'class_armor_type' => 'Heavy Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '3', 'class_name' => 'Champion', 'class_armor_type' => 'Heavy Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '4', 'class_name' => 'Guardian', 'class_armor_type' => 'Heavy Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '5', 'class_name' => 'Hunter', 'class_armor_type' => 'Medium Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '6', 'class_name' => 'Lore-master', 'class_armor_type' => 'Light Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '7', 'class_name' => 'Minstrel', 'class_armor_type' => 'Light Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '8', 'class_name' => 'Rune-keeper', 'class_armor_type' => 'Light Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );
    $sql_ary[] = array('class_id' => '9', 'class_name' => 'Warden', 'class_armor_type' => 'Medium Armour' , 'class_min_level' => 1 , 'class_max_level'  => 60 );   
    $db->sql_multi_insert( $bbdkp_table_prefix . 'classes', $sql_ary);
 	unset ($sql_ary); 
 
   	// factions
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'factions');
    $sql_ary = array();
    $sql_ary[] = array('faction_id' => 1, 'faction_name' => 'Normal' );
    $sql_ary[] = array('faction_id' => 2, 'faction_name' => 'MonsterPlay' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'factions', $sql_ary);
    unset ($sql_ary); 

      // races
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'races');
    $sql_ary = array();
    $sql_ary[] = array('race_id' => 1, 'race_name' => 'Man' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 2, 'race_name' => 'Hobbit' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 3, 'race_name' => 'Elf' , 'race_faction_id' => 1 );
    $sql_ary[] = array('race_id' => 4, 'race_name' => 'Dwarf' , 'race_faction_id' => 1 );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'races', $sql_ary);
    unset ($sql_ary); 
    
    // bossprogress for LOTRO
    unset ($sql_ary); 
	if ($umil->table_exists($bbdkp_table_prefix . 'bb_config'))
	{
	    $sql = 'TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_config';
		$db->sql_query($sql);
		
		$sql_ary[] = array('config_name'	=> 'bossInfo', 'config_value'	=> 'rname' );
		$sql_ary[] = array('config_name'	=> 'dynBoss', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'dynZone', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'nameDelim', 'config_value'	=> '-' );
		$sql_ary[] = array('config_name'	=> 'noteDelim', 'config_value'	=> ',' );
		$sql_ary[] = array('config_name'	=> 'showSB', 'config_value'	=> '1' );
		$sql_ary[] = array('config_name'	=> 'source', 'config_value'	=> 'database' );
		$sql_ary[] = array('config_name'	=> 'style', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'tables', 'config_value'	=> 'bbeqdkp' );
		$sql_ary[] = array('config_name'	=> 'zhiType', 'config_value'	=> '0' );
		$sql_ary[] = array('config_name'	=> 'zoneInfo', 'config_value'	=> 'rname' );

		//lotro_misc
		$sql_ary[] = array('config_name' =>  'pb_ferndur', 'config_value' => 'Ferndur'  );
		$sql_ary[] = array('config_name' =>  'pb_bogbereth', 'config_value' => 'Bogbereth'  );
		
		//Annuminas
		$sql_ary[] = array('config_name' =>  'pb_glinghant', 'config_value' => 'Glinghant'  );
		$sql_ary[] = array('config_name' =>  'pb_nengon', 'config_value' => 'Nengon'  );
		$sql_ary[] = array('config_name' =>  'pb_ost_elendil', 'config_value' => 'Annuminas - Ost Elendil'  );
		$sql_ary[] = array('config_name' =>  'pb_guloth', 'config_value' => 'Guloth'  );
		$sql_ary[] = array('config_name' =>  'pb_balhest', 'config_value' => 'Balhest'  );
		$sql_ary[] = array('config_name' =>  'pb_haudh_valandil', 'config_value' => 'Annuminas - Haudh Valandil'  );
		$sql_ary[] = array('config_name' =>  'pb_shingrinder', 'config_value' => 'Shingrinder'  );
		$sql_ary[] = array('config_name' =>  'pb_dolvaethor', 'config_value' => 'Dolvaethor'  );
		$sql_ary[] = array('config_name' =>  'pb_valandil', 'config_value' => 'Valandil of Arnor'  );

		//fornost
		$sql_ary[] = array('config_name' =>  'pb_brogadan', 'config_value' => 'Bragadan'  );
		$sql_ary[] = array('config_name' =>  'pb_megoriath', 'config_value' => 'Megoriath'  );
		$sql_ary[] = array('config_name' =>  'pb_rhavameldir', 'config_value' => 'Rhavameldir'  );
		$sql_ary[] = array('config_name' =>  'pb_warchief_burzghash', 'config_value' => 'Burzgash'  );
		$sql_ary[] = array('config_name' =>  'pb_zhurmat', 'config_value' => 'Zhurmat'  );
		$sql_ary[] = array('config_name' =>  'pb_riamul', 'config_value' => 'Riamul'  );
		$sql_ary[] = array('config_name' =>  'pb_zanthrug', 'config_value' => 'Zanthrug'  );
		$sql_ary[] = array('config_name' =>  'pb_krithmog', 'config_value' => 'Krithmog'  );
		$sql_ary[] = array('config_name' =>  'pb_einiora', 'config_value' => 'Einiora'  );
		$sql_ary[] = array('config_name' =>  'pb_remmenaeg', 'config_value' => 'Remmenaeg'  );
		
		
		// helegrod
		$sql_ary[] = array('config_name' =>  'pb_coldbear', 'config_value' => 'Coldbear'  );
		$sql_ary[] = array('config_name' =>  'pb_storvagun', 'config_value' => 'Storvagun'  );
		$sql_ary[] = array('config_name' =>  'pb_ansach', 'config_value' => 'Ansach'  );
		$sql_ary[] = array('config_name' =>  'pb_breosal', 'config_value' => 'Breosal'  );
		$sql_ary[] = array('config_name' =>  'pb_grisgart', 'config_value' => 'Grisgart'  );
		$sql_ary[] = array('config_name' =>  'pb_adhargul', 'config_value' => 'Adhargul'  );
		$sql_ary[] = array('config_name' =>  'pb_zaudru', 'config_value' => 'Zaudru'  );
		$sql_ary[] = array('config_name' =>  'pb_drugoth', 'config_value' => 'Drugoth The Death-Monger'  );
		$sql_ary[] = array('config_name' =>  'pb_thorog', 'config_value' => 'Thorog'  );
				
		// rift
		$sql_ary[] = array('config_name' =>  'pb_zurm', 'config_value' => 'Zurm'  );
		$sql_ary[] = array('config_name' =>  'pb_barz', 'config_value' => 'Barz'  );
		$sql_ary[] = array('config_name' =>  'pb_fruz', 'config_value' => 'Fruz'  );
		$sql_ary[] = array('config_name' =>  'pb_zogtark', 'config_value' => 'Zogtark'  );
		$sql_ary[] = array('config_name' =>  'pb_narnulubat', 'config_value' => 'Narnulubat'  );
		$sql_ary[] = array('config_name' =>  'pb_shadow_eater', 'config_value' => 'Shadow Eater'  );
		$sql_ary[] = array('config_name' =>  'pb_thrang', 'config_value' => 'Thrang'  );
		$sql_ary[] = array('config_name' =>  'pb_thaurlach', 'config_value' => 'Thaurlach'  );
		
		// urugarth
		$sql_ary[] = array('config_name' =>  'pb_sorkrank', 'config_value' => 'Sorkrank'  );
		$sql_ary[] = array('config_name' =>  'pb_burzfil', 'config_value' => 'Burzfil'  );
		$sql_ary[] = array('config_name' =>  'pb_dushkal', 'config_value' => 'Dushkal'  );
		$sql_ary[] = array('config_name' =>  'pb_akrur', 'config_value' => 'Akrur'  );
		$sql_ary[] = array('config_name' =>  'pb_kughurz', 'config_value' => 'Kughurz'  );
		$sql_ary[] = array('config_name' =>  'pb_lamkarn', 'config_value' => 'Lamkarn'  );
		$sql_ary[] = array('config_name' =>  'pb_athpukh', 'config_value' => 'Athpukh'  );
		$sql_ary[] = array('config_name' =>  'pb_lhugrien', 'config_value' => 'Lhugrien'  );
		$sql_ary[] = array('config_name' =>  'pb_morthrang', 'config_value' => 'Morthrang'  );
		$sql_ary[] = array('config_name' =>  'pb_gruglok', 'config_value' => 'Gruglok'  );
		$sql_ary[] = array('config_name' =>  'pb_lagmas', 'config_value' => 'Thaurlach'  );
		
		//barad_gularan
		$sql_ary[] = array('config_name' =>  'pb_forvengwath', 'config_value' => 'Forvengwath'  );
		$sql_ary[] = array('config_name' =>  'pb_wisdan', 'config_value' => 'Castellan Wisdan'  );
		$sql_ary[] = array('config_name' =>  'pb_udunion', 'config_value' => 'Udunion'  );
		
		//carn_dum
		$sql_ary[] = array('config_name' =>  'pb_urro', 'config_value' => 'Urro'  );
		$sql_ary[] = array('config_name' =>  'pb_barashal', 'config_value' => 'Barashal'  );
		$sql_ary[] = array('config_name' =>  'pb_helchgam', 'config_value' => 'Helchgam'  );
		$sql_ary[] = array('config_name' =>  'pb_salvakh', 'config_value' => 'Salvakh'  );
		$sql_ary[] = array('config_name' =>  'pb_azgoth', 'config_value' => 'Azgoth'  );
		$sql_ary[] = array('config_name' =>  'pb_tarlug', 'config_value' => 'Tarlug'  );
		$sql_ary[] = array('config_name' =>  'pb_mormoz', 'config_value' => 'Mormoz'  );
		$sql_ary[] = array('config_name' =>  'pb_rodakhan', 'config_value' => 'Rodakhan'  );
		$sql_ary[] = array('config_name' =>  'pb_mura', 'config_value' => 'Mura'  );
		$sql_ary[] = array('config_name' =>  'pb_gurthul', 'config_value' => 'Gurthul'  );
		$sql_ary[] = array('config_name' =>  'pb_mordirith', 'config_value' => 'Mordirith'  );
		
		//great_barrow
		$sql_ary[] = array('config_name' =>  'pb_gaerthel_gaerdring', 'config_value' => 'Gaerthel & Gaerdring'  );
		$sql_ary[] = array('config_name' =>  'pb_thadur', 'config_value' => 'Thadur the Ravager'  );
		$sql_ary[] = array('config_name' =>  'pb_sambrog', 'config_value' => 'Sambrog'  );
		
		//garth_agarwen
		$sql_ary[] = array('config_name' =>  'pb_temair', 'config_value' => 'Temair the Devoted'  );
		$sql_ary[] = array('config_name' =>  'pb_grimbark', 'config_value' => 'Grimbark'  );
		$sql_ary[] = array('config_name' =>  'pb_edan_esyld', 'config_value' => 'Edan & Esyld'  );
		$sql_ary[] = array('config_name' =>  'pb_ivar', 'config_value' => 'Ivar the Bloodhand'  );
		$sql_ary[] = array('config_name' =>  'pb_vatar', 'config_value' => 'Vatar'  );
		$sql_ary[] = array('config_name' =>  'pb_naruhel', 'config_value' => 'Naruhel - The Red Maid'  );
		
		// ettenmoors_creeps
		$sql_ary[] = array('config_name' =>  'pb_tharbil', 'config_value' => 'Tyrant Tharbil'  );
		$sql_ary[] = array('config_name' =>  'pb_burzgoth', 'config_value' => 'Tyrant Burzgoth'  );
		$sql_ary[] = array('config_name' =>  'pb_gundzor', 'config_value' => 'Tyrant Gundzor'  );
		$sql_ary[] = array('config_name' =>  'pb_durgrat', 'config_value' => 'Tyrant Durgrat'  );
		$sql_ary[] = array('config_name' =>  'pb_trintru', 'config_value' => 'Tyrant Trintru'  );
		$sql_ary[] = array('config_name' =>  'pb_barashish', 'config_value' => 'Tyrant Barashish'  );
		
		// ettenmoors_freeps
		$sql_ary[] = array('config_name' =>  'pb_bordagor', 'config_value' => 'Captain-General Bordagor'  );
		$sql_ary[] = array('config_name' =>  'pb_lainedhel', 'config_value' => 'Captain-General Lainedhel'  );
		$sql_ary[] = array('config_name' =>  'pb_verdantine', 'config_value' => 'Captain-General Verdantine'  );
		$sql_ary[] = array('config_name' =>  'pb_makan', 'config_value' => 'Captain-General Makan'  );
		$sql_ary[] = array('config_name' =>  'pb_meldun', 'config_value' => 'Captain-General Meldun'  );
		$sql_ary[] = array('config_name' =>  'pb_harvestgain', 'config_value' => 'Captain-General Harvestgain'  );
		
		$sql_ary[] = array('config_name' =>  'pz_lotro_misc', 'config_value' => 'Miscellaneous bosses'  );
		$sql_ary[] = array('config_name' =>  'pz_annuminas', 'config_value' => 'Annuminas - Glinghant'  );
		$sql_ary[] = array('config_name' =>  'pz_helegrod', 'config_value' => 'Helegrod'  );
		$sql_ary[] = array('config_name' =>  'pz_fornost', 'config_value' => 'Fornost'  );
		$sql_ary[] = array('config_name' =>  'pz_rift', 'config_value' => 'The Rift of Nrz Ghshu'  );
		$sql_ary[] = array('config_name' =>  'pz_urugarth', 'config_value' => 'Urugarth'  );
		$sql_ary[] = array('config_name' =>  'pz_barad_gularan', 'config_value' => 'Barad Gularan'  );
		$sql_ary[] = array('config_name' =>  'pz_carn_dum', 'config_value' => 'Carn Dum'  );
		$sql_ary[] = array('config_name' =>  'pz_great_barrow', 'config_value' => 'The Great Barrow'  );
		$sql_ary[] = array('config_name' =>  'pz_garth_agarwen', 'config_value' => 'Garth Agarwen'  );
		$sql_ary[] = array('config_name' =>  'pz_ettenmoors_creeps', 'config_value' => 'Ettenmoors creeps'  );
		$sql_ary[] = array('config_name' =>  'pz_ettenmoors_freeps', 'config_value' => 'Ettenmoors freeps'  );
		
		$sql_ary[] = array('config_name' =>  'sz_lotro_misc', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_annuminas', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_helegrod', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_fornost', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_rift', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_urugarth', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_barad_gularan', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_carn_dum', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_great_barrow', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_garth_agarwen', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_ettenmoors_creeps', 'config_value' => '1'  );
		$sql_ary[] = array('config_name' =>  'sz_ettenmoors_freeps', 'config_value' => '1'  );
	    	
		$db->sql_multi_insert( $bbdkp_table_prefix . 'bb_config' , $sql_ary);
	}

    unset ($sql_ary); 
    // boss list     
    $sql_ary = array();

    // Boss offsets
	$db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'bb_offsets');
	$sql_ary = array();

	
	// lotro_misc
	$sql_ary[] = array('name' => 'bogbereth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ferndur' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	//Annuminas
	$sql_ary[] = array('name' => 'annuminas' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'glinghant' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'nengon' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ost_elendil' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'guloth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'balhest' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'haudh_valandil' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'shingrinder' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dolvaethor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'valandil' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	// Fornost
	$sql_ary[] = array('name' => 'brogadan' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'megoriath' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'rhavameldir' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'warchief_burzghash' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zhurmat' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'riamul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zanthrug' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'krithmog' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'einiora' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'remmenaeg' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	// Helegrod
	$sql_ary[] = array('name' => 'coldbear' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'storvagun' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ansach' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'breosal' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'grisgart' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'adhargul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zaudru' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );	
	$sql_ary[] = array('name' => 'drugoth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thorog' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	//	The Rift of Nrz Ghshu 
	$sql_ary[] = array('name' => 'zurm' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'barz' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'fruz' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'zogtark' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'narnulubat' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'shadow_eater' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thrang' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thaurlach' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	//urugarth
	$sql_ary[] = array('name' => 'sorkrank' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'burzfil' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'dushkal' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'akrur' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'kughurz' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'lamkarn' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'athpukh' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'lhugrien' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'morthrang' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gruglok' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'lagmas' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	// Barad Gularan 
	$sql_ary[] = array('name' => 'forvengwath' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'wisdan' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'udunion' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	// carn dum
	$sql_ary[] = array('name' => 'urro' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'barashal' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'helchgam' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'salvakh' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'azgoth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'tarlug' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'mormoz' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'rodakhan' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'mura' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gurthul' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'mordirith' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	// great barrow
	$sql_ary[] = array('name' => 'gaerthel_gaerdring' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'thadur' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'sambrog' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	//Garth Agarwen
	$sql_ary[] = array('name' => 'temair' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'grimbark' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'edan_esyld' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ivar' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'vatar' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'naruhel' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );

	//Ettenmoors creeps
	$sql_ary[] = array('name' => 'tharbil' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'burzgoth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'gundzor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'durgrat' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'trintru' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'barashish' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	//Ettenmoors freeps
	$sql_ary[] = array('name' => 'bordagor' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'lainedhel' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'verdantine' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'makan' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'meldun' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'harvestgain' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	
	// zones
	$sql_ary[] = array('name' => 'lotro_misc' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'glinghant' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'helegrod' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'fornost' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'rift' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'urugarth' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'barad_gularan' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'carn_dum' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'great_barrow' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'garth_agarwen' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ettenmoors_creeps' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
	$sql_ary[] = array('name' => 'ettenmoors_freeps' ,  'fdate' => '1420063200' , 'ldate' => '946677600', 'counter' =>  '0' );
    $db->sql_multi_insert( $bbdkp_table_prefix . 'bb_offsets', $sql_ary);  
    unset ($sql_ary);
    
    // dkp system  set to default
    $db->sql_query('TRUNCATE TABLE ' . $bbdkp_table_prefix . 'dkpsystem');
	$sql_ary = array();
	$sql_ary[] = array('dkpsys_id' => '1' , 'dkpsys_name' => 'Default' , 'dkpsys_status' => 'Y', 'dkpsys_addedby' =>  'admin' , 'dkpsys_default' =>  'Y' ) ;
	$db->sql_multi_insert( $bbdkp_table_prefix . 'dkpsystem', $sql_ary);
    
}


/*
 * new boss progress data for vanguard
 * generated with the spreadsheet
 * 
 */
function install_lotro_bb2($bbdkp_table_prefix)
{
	global $db, $table_prefix, $umil, $user;
	
	if ($umil->table_exists ( $bbdkp_table_prefix . 'bb_config' ) and ($umil->table_exists ( $bbdkp_table_prefix . 'bb_offsets' )))
	{
		$sql_ary = array ();

		$sql_ary[] = array( 'id' => 1 , 'zonename' => 'Miscellaneous bosses', 'zonename_short' =>  'Miscellaneous bosses' , 'imagename' =>  'lotro_misc' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 2 , 'zonename' => 'Annuminas - Glinghant', 'zonename_short' =>  'Annuminas - Glinghant' , 'imagename' =>  'annuminas' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 3 , 'zonename' => 'Fornost', 'zonename_short' =>  'Fornost' , 'imagename' =>  'fornost' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 4 , 'zonename' => 'Helegrod', 'zonename_short' =>  'Helegrod' , 'imagename' =>  'helegrod' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 5 , 'zonename' => 'The Rift of Nrz Ghshu', 'zonename_short' =>  'The Rift of Nrz Ghshu' , 'imagename' =>  'rift' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 6 , 'zonename' => 'Urugarth', 'zonename_short' =>  'Urugarth' , 'imagename' =>  'urugarth' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 7 , 'zonename' => 'Barad Gularan', 'zonename_short' =>  'Barad Gularan' , 'imagename' =>  'barad_gularan' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 8 , 'zonename' => 'Carn Dum', 'zonename_short' =>  'Carn Dum' , 'imagename' =>  'carn_dum' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 9 , 'zonename' => 'The Great Barrow', 'zonename_short' =>  'The Great Barrow' , 'imagename' =>  'great_barrow' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 10 , 'zonename' => 'Garth Agarwen', 'zonename_short' =>  'Garth Agarwen' , 'imagename' =>  'garth_agarwen' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 11 , 'zonename' => 'Ettenmoors creeps', 'zonename_short' =>  'Ettenmoors creeps' , 'imagename' =>  'ettenmoors_creeps' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		$sql_ary[] = array( 'id' => 12 , 'zonename' => 'Ettenmoors freeps', 'zonename_short' =>  'Ettenmoors freeps' , 'imagename' =>  'ettenmoors_freeps' , 'game' =>  lotro ,  'tier' =>  '' ,  'completed' =>  '0' ,  'completedate' =>  '0' ,  'webid' =>  '');
		
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_zonetable', $sql_ary );
		unset ( $sql_ary );
		
		$sql_ary[] = array('id' => 1 , 'bossname' => 'Bogbereth' , 'bossname_short' => 'Bogbereth', 'imagename' =>  'bogbereth' , 'game' =>  'lotro' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 2 , 'bossname' => 'Ferndur' , 'bossname_short' => 'Ferndur', 'imagename' =>  'ferndur' , 'game' =>  'lotro' , 'zoneid' =>  2 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 3 , 'bossname' => 'Annuminas - Glinghant' , 'bossname_short' => 'Annu - Garden', 'imagename' =>  'glinghant' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 4 , 'bossname' => 'Nengon' , 'bossname_short' => 'Nengon', 'imagename' =>  'nengon' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 5 , 'bossname' => 'Annuminas - Ost Elendil' , 'bossname_short' => 'Annu - Palace', 'imagename' =>  'ost_elendil' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 6 , 'bossname' => 'Guloth' , 'bossname_short' => 'Guloth', 'imagename' =>  'guloth' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 7 , 'bossname' => 'Balhest' , 'bossname_short' => 'Balhest', 'imagename' =>  'balhest' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 8 , 'bossname' => 'Annuminas - Haudh Valandil' , 'bossname_short' => 'Annu - Tomb', 'imagename' =>  'haudh_valandil' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 9 , 'bossname' => 'Shingrinder' , 'bossname_short' => 'Shingrinder', 'imagename' =>  'shingrinder' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 10 , 'bossname' => 'Dolvaethor' , 'bossname_short' => 'Dolvaethor', 'imagename' =>  'dolvaethor' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 11 , 'bossname' => 'Valandil of Arnor' , 'bossname_short' => 'Valandil', 'imagename' =>  'valandil' , 'game' =>  'lotro' , 'zoneid' =>  3 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 12 , 'bossname' => 'Brogadan' , 'bossname_short' => 'Bragadan', 'imagename' =>  'brogadan' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 13 , 'bossname' => 'Megoriath' , 'bossname_short' => 'Megoriath', 'imagename' =>  'megoriath' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 14 , 'bossname' => 'Rhavameldir' , 'bossname_short' => 'Rhavameldir', 'imagename' =>  'rhavameldir' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 15 , 'bossname' => 'Warchief Burzgash' , 'bossname_short' => 'Burzgash', 'imagename' =>  'warchief_burzghash' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 16 , 'bossname' => 'Zhurmat' , 'bossname_short' => 'Zhurmat', 'imagename' =>  'zhurmat' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 17 , 'bossname' => 'Riamul' , 'bossname_short' => 'Riamul', 'imagename' =>  'riamul' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 18 , 'bossname' => 'Zanthrug' , 'bossname_short' => 'Zanthrug', 'imagename' =>  'zanthrug' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 19 , 'bossname' => 'Krithmog' , 'bossname_short' => 'Krithmog', 'imagename' =>  'krithmog' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 20 , 'bossname' => 'Einiora' , 'bossname_short' => 'Einiora', 'imagename' =>  'einiora' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 21 , 'bossname' => 'Remmenaeg' , 'bossname_short' => 'Remmenaeg', 'imagename' =>  'remmenaeg' , 'game' =>  'lotro' , 'zoneid' =>  4 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 22 , 'bossname' => 'Coldbear' , 'bossname_short' => 'Coldbear', 'imagename' =>  'coldbear' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 23 , 'bossname' => 'Storvagun' , 'bossname_short' => 'Storvagun', 'imagename' =>  'storvagun' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 24 , 'bossname' => 'Ansach' , 'bossname_short' => 'Ansach', 'imagename' =>  'ansach' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 25 , 'bossname' => 'Breosal' , 'bossname_short' => 'Breosal', 'imagename' =>  'breosal' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 26 , 'bossname' => 'Grisgart' , 'bossname_short' => 'Grisgart', 'imagename' =>  'grisgart' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 27 , 'bossname' => 'Adhargul' , 'bossname_short' => 'Adhargul', 'imagename' =>  'adhargul' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 28 , 'bossname' => 'Zaudru' , 'bossname_short' => 'Zaudru', 'imagename' =>  'zaudru' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 29 , 'bossname' => 'Drugoth The Death-Monger' , 'bossname_short' => 'Drugoth', 'imagename' =>  'drugoth' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 30 , 'bossname' => 'Thorog' , 'bossname_short' => 'Thorog', 'imagename' =>  'thorog' , 'game' =>  'lotro' , 'zoneid' =>  5 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 31 , 'bossname' => 'Zurm' , 'bossname_short' => 'Zurm', 'imagename' =>  'zurm' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 32 , 'bossname' => 'Barz' , 'bossname_short' => 'Barz', 'imagename' =>  'barz' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 33 , 'bossname' => 'Fruz' , 'bossname_short' => 'Fruz', 'imagename' =>  'fruz' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 34 , 'bossname' => 'Zogtark' , 'bossname_short' => 'Zogtark', 'imagename' =>  'zogtark' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 35 , 'bossname' => 'Narnulubat' , 'bossname_short' => 'Narn˚lubat', 'imagename' =>  'narnulubat' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 36 , 'bossname' => 'Shadow Eater' , 'bossname_short' => 'Eater', 'imagename' =>  'shadow_eater' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 37 , 'bossname' => 'Thrang' , 'bossname_short' => 'Thrang', 'imagename' =>  'thrang' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 38 , 'bossname' => 'Thaurlach' , 'bossname_short' => 'Thaurlach', 'imagename' =>  'thaurlach' , 'game' =>  'lotro' , 'zoneid' =>  6 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 39 , 'bossname' => 'Sorkrank' , 'bossname_short' => 'Sorkrank', 'imagename' =>  'sorkrank' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 40 , 'bossname' => 'Burzfil' , 'bossname_short' => 'Burzfil', 'imagename' =>  'burzfil' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 41 , 'bossname' => 'Dushkal' , 'bossname_short' => 'Dushkal', 'imagename' =>  'dushkal' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 42 , 'bossname' => 'Akrur' , 'bossname_short' => 'Akrur', 'imagename' =>  'akrur' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 43 , 'bossname' => 'Kughurz' , 'bossname_short' => 'Kughurz', 'imagename' =>  'kughurz' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 44 , 'bossname' => 'Lamkarn' , 'bossname_short' => 'Lamkarn', 'imagename' =>  'lamkarn' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 45 , 'bossname' => 'Athpukh' , 'bossname_short' => 'Athpukh', 'imagename' =>  'athpukh' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 46 , 'bossname' => 'Lhugrien' , 'bossname_short' => 'Lhugrien', 'imagename' =>  'lhugrien' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 47 , 'bossname' => 'Morthrang' , 'bossname_short' => 'Morthrang', 'imagename' =>  'morthrang' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 48 , 'bossname' => 'Gruglok' , 'bossname_short' => 'Gruglok', 'imagename' =>  'gruglok' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 49 , 'bossname' => 'Lagmas' , 'bossname_short' => 'Lagmas', 'imagename' =>  'lagmas' , 'game' =>  'lotro' , 'zoneid' =>  7 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 50 , 'bossname' => 'Forvengwath' , 'bossname_short' => 'Forvengwath', 'imagename' =>  'forvengwath' , 'game' =>  'lotro' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 51 , 'bossname' => 'Castellan Wisdan' , 'bossname_short' => 'Wisdan', 'imagename' =>  'wisdan' , 'game' =>  'lotro' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 52 , 'bossname' => 'Udunion' , 'bossname_short' => 'Udunion', 'imagename' =>  'udunion' , 'game' =>  'lotro' , 'zoneid' =>  8 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 53 , 'bossname' => 'Urro' , 'bossname_short' => 'Urro', 'imagename' =>  'urro' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 54 , 'bossname' => 'Barashal' , 'bossname_short' => 'Barashal', 'imagename' =>  'barashal' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 55 , 'bossname' => 'Helchgam' , 'bossname_short' => 'Helchgam', 'imagename' =>  'helchgam' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 56 , 'bossname' => 'Salvakh' , 'bossname_short' => 'Salvakh', 'imagename' =>  'salvakh' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 57 , 'bossname' => 'Azgoth' , 'bossname_short' => 'Azgoth', 'imagename' =>  'azgoth' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 58 , 'bossname' => 'Tarlug' , 'bossname_short' => 'Tarlug', 'imagename' =>  'tarlug' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 59 , 'bossname' => 'Mormoz' , 'bossname_short' => 'Mormoz', 'imagename' =>  'mormoz' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 60 , 'bossname' => 'Rodakhan' , 'bossname_short' => 'Rodakhan', 'imagename' =>  'rodakhan' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 61 , 'bossname' => 'Mura' , 'bossname_short' => 'Mura', 'imagename' =>  'mura' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 62 , 'bossname' => 'Gurthul' , 'bossname_short' => 'Gurthul', 'imagename' =>  'gurthul' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 63 , 'bossname' => 'Mordirith' , 'bossname_short' => 'Mordirith', 'imagename' =>  'mordirith' , 'game' =>  'lotro' , 'zoneid' =>  9 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 64 , 'bossname' => 'Gaerthel & Gaerdring' , 'bossname_short' => 'G & G', 'imagename' =>  'gaerthel_gaerdring' , 'game' =>  'lotro' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 65 , 'bossname' => 'Thadur the Ravager' , 'bossname_short' => 'Thad˙r', 'imagename' =>  'thadur' , 'game' =>  'lotro' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 66 , 'bossname' => 'Sambrog' , 'bossname_short' => 'Sambrog', 'imagename' =>  'sambrog' , 'game' =>  'lotro' , 'zoneid' =>  10 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 67 , 'bossname' => 'Temair the Devoted' , 'bossname_short' => 'Temair', 'imagename' =>  'temair' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 68 , 'bossname' => 'Grimbark' , 'bossname_short' => 'Grimbark', 'imagename' =>  'grimbark' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 69 , 'bossname' => 'Edan & Esyld' , 'bossname_short' => 'E & E', 'imagename' =>  'edan_esyld' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 70 , 'bossname' => 'Ivar the Bloodhand' , 'bossname_short' => 'Ivar', 'imagename' =>  'ivar' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 71 , 'bossname' => 'Vatar' , 'bossname_short' => 'Vatar', 'imagename' =>  'vatar' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 72 , 'bossname' => 'Naruhel - The Red Maid' , 'bossname_short' => 'Naruhel', 'imagename' =>  'naruhel' , 'game' =>  'lotro' , 'zoneid' =>  11 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 73 , 'bossname' => 'Tyrant Tharbil' , 'bossname_short' => 'Tharbil', 'imagename' =>  'tharbil' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 74 , 'bossname' => 'Tyrant Burzgoth' , 'bossname_short' => 'Burzgoth', 'imagename' =>  'burzgoth' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 75 , 'bossname' => 'Tyrant Gundzor' , 'bossname_short' => 'Gundzor', 'imagename' =>  'gundzor' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 76 , 'bossname' => 'Tyrant Durgrat' , 'bossname_short' => 'Durgrat', 'imagename' =>  'durgrat' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 77 , 'bossname' => 'Tyrant Trintru' , 'bossname_short' => 'Trintru', 'imagename' =>  'trintru' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 78 , 'bossname' => 'Tyrant Barashish' , 'bossname_short' => 'Barashish', 'imagename' =>  'barashish' , 'game' =>  'lotro' , 'zoneid' =>  12 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 79 , 'bossname' => 'Captain-General Bordagor' , 'bossname_short' => 'Bordagor', 'imagename' =>  'bordagor' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 80 , 'bossname' => 'Captain-General Lainedhel' , 'bossname_short' => 'Lainedhel', 'imagename' =>  'lainedhel' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 81 , 'bossname' => 'Captain-General Verdantine' , 'bossname_short' => 'Verdantine', 'imagename' =>  'verdantine' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 82 , 'bossname' => 'Captain-General Makan' , 'bossname_short' => 'Makan', 'imagename' =>  'makan' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 83 , 'bossname' => 'Captain-General Meldun' , 'bossname_short' => 'Meldun', 'imagename' =>  'meldun' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );
		$sql_ary[] = array('id' => 84 , 'bossname' => 'Captain-General Harvestgain' , 'bossname_short' => 'Harvestgain', 'imagename' =>  'harvestgain' , 'game' =>  'lotro' , 'zoneid' =>  13 , 'type' =>  'npc'  , 'webid' =>  '' , 'killed' =>  '0' , 'killdate' =>  '0' , 'counter' =>  '0'    );

		
		$db->sql_multi_insert ( $bbdkp_table_prefix . 'bb_bosstable', $sql_ary );
		unset ( $sql_ary );
		
		
	}
}





    
?>