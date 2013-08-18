<?php
/**
 * @package 	bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 *
 */
namespace bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;

// Include the base class
if (!class_exists('\bbdkp\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/guilds/Guilds.$phpEx");
}


/**
 * Ranks
 * 
 * Manages Guildranks, extends the guild class
 * @package 	bbDKP
 * 
 */
class Ranks extends \bbdkp\Guilds
{
	public $RankName;
	public $RankId;
	public $RankGuild;
	public $RankHide;
	public $RankPrefix;
	public $RankSuffix;

	public function __construct($RankGuild, $RankId = 0)
	{
		if ($RankId >= 0)
		{
			$this->RankGuild=$RankGuild;
			$this->Getrank(); 	
		}
		else
		{
			$this->RankGuild=$RankGuild;
			$this->RankId=0;
			$this->RankName='';
			$this->RankHide=0;
			$this->RankPrefix='';
			$this->RankSuffix='';
		}
	}

	/**
	 * gets all info on one rank
	 * @see \bbdkp\iRanks::Getrank()
	 */
	public function Getrank()
	{
	    global $user, $db, $config, $phpEx, $phpbb_root_path;
	    $sql = 'SELECT rank_name, rank_hide, rank_prefix, rank_suffix
    			FROM ' . MEMBER_RANKS_TABLE . '
    			WHERE rank_id = ' . (int) $this->RankId . ' and guild_id = ' . (int) $this->RankGuild;
	    $result = $db->sql_query($sql);
	    while ($row = $db->sql_fetchrow($result))
	    {
	        $this->RankName = $row['rank_name'];
	        $this->RankHide	= $row['rank_hide'];
            $this->RankPrefix = $row['rank_prefix'];
            $this->RankSuffix = $row['rank_suffix'];
	    }
	    $db->sql_freeresult($result);

	}

	/**
	 * adds a rank
	 * @see \bbdkp\iRanks::Makerank()
	 */
	public function Makerank()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		if ($this->RankName == '')
		{
			trigger_error($user->lang('ERROR_RANK_NAME_EMPTY'), E_USER_WARNING);
		}

		//check if guildid is valid
		if ($this->RankGuild == 0)
		{
			trigger_error($user->lang('ERROR_INVALID_GUILDID'), E_USER_WARNING);
		}

		$sql = 'SELECT count(*) as rankcount FROM ' . MEMBER_RANKS_TABLE . '
                   	WHERE rank_id != 99
                   	AND rank_id = ' . (int) $this->RankId . '
                   	AND guild_id = ' . (int) $this->RankGuild . '
                   	ORDER BY rank_id, rank_hide ASC ';
		$result = $db->sql_query($sql);
		if ((int) $db->sql_fetchfield('rankcount', false, $result) == 1)
		{
			trigger_error(sprintf($user->lang('ERROR_RANK_EXISTS'),  $this->RankId ,  $this->RankGuild), E_USER_WARNING);
		}

		// build insert array
		$query = $db->sql_build_array('INSERT', array(
				'rank_id' => (int) $this->RankId ,
				'rank_name' => $this->RankName ,
				'rank_hide' => $this->RankHide ,
				'rank_prefix' => $this->RankPrefix ,
				'rank_suffix' => $this->RankSuffix ,
				'guild_id' => (int) $this->RankGuild));
		// insert new rank
		$db->sql_query('INSERT INTO ' . MEMBER_RANKS_TABLE . $query);
		// log the action

		
		$log_action = array(
				'header' => 'L_ACTION_RANK_ADDED',
				'id' => (int)  $this->RankId,
				'L_NAME' => $this->RankName,
				'L_USERCOLOUR' => $user->data['user_colour'],
				'L_ADDED_BY' => $user->data['username']);

		$this->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		unset($bbdkp);



	}


	/**
	 * (non-PHPdoc)
	 * @see \bbdkp\iRanks::Rankdelete()
	 */
	public function Rankdelete($override)
	{
		global $user, $db, $phpEx, $phpbb_root_path;

		if ($this->countmembers() > 0 )
		{
			return false;
		}

		if (! $override)
		{
			// check if rank is used
			$sql = 'SELECT count(*) as rankcount FROM ' . MEMBER_LIST_TABLE . ' WHERE
            		 member_rank_id   = ' . (int) $this->RankId . ' and
            		 member_guild_id =  ' . (int) $this->RankGuild;
			$result = $db->sql_query($sql);
			if ((int) $db->sql_fetchfield('rankcount') >= 1)
			{
				\trigger_error('Cannot delete rank ' . $this->RankId . '. There are members with this rank in guild . ' . $this->RankGuild, E_USER_WARNING);
			}
		}

		// hardcoded exclusion of ranks 90/99
		$sql = 'DELETE FROM ' . MEMBER_RANKS_TABLE . ' WHERE rank_id != 90 and rank_id != 99 and rank_id= ' .
				$this->RankId . ' and guild_id = ' . $this->RankGuild;
		$db->sql_query($sql);

		// log the action

		if (!class_exists('\bbdkp\Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new \bbdkp\Admin();

		$log_action = array(
				'header' => 'L_ACTION_RANK_DELETED' ,
				'id' => (int) $this->RankId  ,
				'L_NAME' => $this->RankName ,
				'L_ADDED_BY' => $user->data['username']);

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		unset($bbdkp);

		return true;

	}

	/**
	 * 
	 * @see \bbdkp\iRanks::Rankupdate()
	 */
	public function Rankupdate(Ranks $old_rank)
	{

		global $user, $db, $phpEx, $phpbb_root_path;

		$sql_ary = array(
				'rank_id' => $this->RankId ,
				'guild_id' => $this->RankGuild ,
				'rank_name' => $this->RankName ,
				'rank_hide' => $this->RankHide ,
				'rank_prefix' => $this->RankPrefix ,
				'rank_suffix' => $this->RankSuffix
				);


		$sql = 'UPDATE ' . MEMBER_RANKS_TABLE . '
			SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE rank_id=' . (int) $old_rank->RankId . '
			AND guild_id = ' . (int) $old_rank->RankGuild;
		$db->sql_query($sql);


		// log it
		if (!class_exists('\bbdkp\Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new \bbdkp\Admin();

		$log_action = array(
				'header' 		=> 'L_ACTION_RANK_UPDATED' ,
				'L_NAME_BEFORE' => $old_rank->RankName ,
				'L_HIDE_BEFORE' => $old_rank->RankHide ,
				'L_PREFIX_BEFORE' => $old_rank->RankPrefix ,
				'L_SUFFIX_BEFORE' => $old_rank->RankSuffix ,
				'L_NAME_AFTER' => $this->RankName,
				'L_HIDE_AFTER' => $this->RankHide ,
				'L_PREFIX_AFTER' => $this->RankPrefix ,
				'L_SUFFIX_AFTER' => $this->RankSuffix ,
				'L_UPDATED_BY' => $user->data['username']);

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		unset($bbdkp);
		return true;

	}
	
	/**
	 * returns rank array
	 * @return array
	 */
	public function listranks()
	{
		global $user, $db;
		// rank 99 is the out-rank
		$sql = 'SELECT rank_id, rank_name, rank_hide, rank_prefix, rank_suffix, guild_id FROM ' . MEMBER_RANKS_TABLE . '
	        		WHERE guild_id = ' . $this->RankGuild . ' AND rank_id < 99	
	        		ORDER BY rank_id, rank_hide  ASC ';
		
		$result = $db->sql_query($sql);
		return $result;
		
	}
	
	/**
	 * 
	 * @see \bbdkp\Guilds::countmembers()
	 */
	public function countmembers()
	{
		global $user, $db;

		$sql = 'SELECT count(*) as countm FROM ' . MEMBER_LIST_TABLE . '
			WHERE member_rank_id = ' . $this->RankId . ' and member_guild_id = ' . $this->RankGuild;
		$result = $db->sql_query($sql);
		$countm = (int) $db->sql_fetchfield('countm');
		$db->sql_freeresult($result);

		return $countm;
	}
	
	/**
	 * 
	 * @see \bbdkp\iRanks::WoWArmoryUpdate($memberdata, $guild_id, $region)
	 */
	public function WoWArmoryUpdate($memberdata, $guild_id, $region)
	{
		global $user, $db, $phpEx, $phpbb_root_path;
		
		$newranks = array();
		
		//init the rank counts per rank
		foreach ( $memberdata as $new )
		{
			$newranks[$new['rank']] = 0;
		}
		
		//count the number of members per rank
		foreach ( $memberdata as $new )
		{
			$newranks[$new['rank']] += 1;
		}
		
		ksort($newranks);
		
		/* GET OLD RANKS */
		$sql = ' select rank_id from ' . MEMBER_RANKS_TABLE . ' WHERE
				 guild_id =  ' . (int) $guild_id . ' and rank_id < 90 order by rank_id ASC';
		$result = $db->sql_query ($sql);
		$oldranks = array ();
		
		while ($row = $db->sql_fetchrow ($result))
		{
			$oldranks [(int) $row['rank_id']] = 0;
		}
		$db->sql_freeresult ( $result );
		$result = $db->sql_query ($sql);
		while ($row = $db->sql_fetchrow ($result))
		{
			$oldranks [(int) $row['rank_id']] += 1;
		}
		$db->sql_freeresult ( $result );
		
		// get the new ranks not yet created
		$diff = array_diff_key($newranks, $oldranks);
		
		foreach($diff as $key => $count)
		{
			$newrank = new \bbdkp\Ranks($guild_id);
			$newrank->RankName = 'Rank'.$key;
			$newrank->RankId = $key; 
			$newrank->RankGuild = $guild_id;
			$newrank->RankHide = 0; 
			$newrank->RankPrefix = ''; 
			$newrank->RankSuffix = ''; 
			$newrank->Makerank();
			unset($newrank); 
		}
		
		
		
	}
	

}
