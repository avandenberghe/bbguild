<?php
/**
 * @package bbDKP.acp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.2.9
 */

namespace includes\bbdkp;

/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

$phpEx = substr(strrchr(__FILE__, '.'), 1);
global $phpbb_root_path;
require_once ("{$phpbb_root_path}includes/bbdkp/ranks/iRanks.$phpEx");

use includes\bbdkp;

class Ranks implements iRanks
{
	public $RankName;
	public $RankId;
	public $GuildId;
	public $RankHide;
	public $RankPrefix;
	public $RankSuffix;

	public function __construct()
	{
		$this->RankId=0;
		$this->RankName='';
		$this->GuildId=0;
		$this->RankHide=0;
		$this->RankPrefix='';
		$this->RankSuffix='';
	}

	public function Get()
	{
	    global $user, $db, $config, $phpEx, $phpbb_root_path;
	    $sql = 'SELECT rank_name, rank_hide, rank_prefix, rank_suffix
    			FROM ' . MEMBER_RANKS_TABLE . '
    			WHERE rank_id = ' . (int) $this->RankId . ' and guild_id = ' . (int) $this->GuildId;
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
	 * @see \includes\bbdkp\iRanks::Make()
	 */
	public function Make()
	{
		global $user, $db, $config, $phpEx, $phpbb_root_path;

		if ($this->RankName == '')
		{
			trigger_error($user->lang('ERROR_RANK_NAME_EMPTY'), E_USER_WARNING);
		}

		//check if guildid is valid
		if ($this->GuildId == 0)
		{
			trigger_error($user->lang('ERROR_INVALID_GUILDID'), E_USER_WARNING);
		}

		$sql = 'SELECT count(*) as rankcount FROM ' . MEMBER_RANKS_TABLE . '
                   	WHERE rank_id != 99
                   	AND rank_id = ' . (int) $this->RankId . '
                   	AND guild_id = ' . (int) $this->GuildId . '
                   	ORDER BY rank_id, rank_hide ASC ';
		$result = $db->sql_query($sql);
		if ((int) $db->sql_fetchfield('rankcount', false, $result) == 1)
		{
			trigger_error(sprintf($user->lang('ERROR_RANK_EXISTS'),  $this->RankId ,  $this->GuildId) . $this->link, E_USER_WARNING);
		}

		// build insert array
		$query = $db->sql_build_array('INSERT', array(
				'rank_id' => (int) $this->RankId ,
				'rank_name' => $this->RankName ,
				'rank_hide' => $this->RankHide ,
				'rank_prefix' => $this->RankPrefix ,
				'rank_suffix' => $this->RankSuffix ,
				'guild_id' => (int) $this->GuildId));
		// insert new rank
		$db->sql_query('INSERT INTO ' . MEMBER_RANKS_TABLE . $query);
		// log the action

		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

		$log_action = array(
				'header' => 'L_ACTION_RANK_ADDED' ,
				'id' => (int)  $this->RankId ,
				'L_NAME' => $this->RankName ,
				'L_ADDED_BY' => $user->data['username']);

		$bbdkp->log_insert(array(
				'log_type' => $log_action['header'] ,
				'log_action' => $log_action));

		unset($bbdkp);



	}

	/**
	 * returns number of members with a certain rank
	 * @return unknown
	 */
	public function countmembers()
	{
		global $user, $db;

		$sql = 'SELECT count(*) as countm FROM ' . MEMBER_LIST_TABLE . '
			WHERE member_rank_id = ' . $this->RankId . ' and member_guild_id = ' . $this->GuildId;
		$result = $db->sql_query($sql);
		$countm = (int) $db->sql_fetchfield('countm');
		$db->sql_freeresult($result);

		return $countm;
	}


	/**
	 * (non-PHPdoc)
	 * @see \includes\bbdkp\iRanks::Delete()
	 */
	public function Delete($override)
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
            		 member_guild_id =  ' . (int) $this->GuildId;
			$result = $db->sql_query($sql);
			if ((int) $db->sql_fetchfield('rankcount') >= 1)
			{
				trigger('Cannot delete rank ' . $this->RankId . '. There are members with this rank in guild . ' . $this->GuildId, E_USER_WARNING);
			}
		}

		// hardcoded exclusion of ranks 90/99
		$sql = 'DELETE FROM ' . MEMBER_RANKS_TABLE . ' WHERE rank_id != 90 and rank_id != 99 and rank_id= ' .
				$this->RankId . ' and guild_id = ' . $this->GuildId;
		$db->sql_query($sql);

		// log the action

		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

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
	 * (non-PHPdoc)
	 * @see \includes\bbdkp\iRanks::Update()
	 */
	public function Update(Ranks $old_rank)
	{

		global $user, $db, $phpEx, $phpbb_root_path;

		$sql_ary = array(
				'rank_id' => $this->RankId ,
				'guild_id' => $this->GuildId ,
				'rank_name' => $this->RankName ,
				'rank_hide' => $this->RankHide ,
				'rank_prefix' => $this->RankPrefix ,
				'rank_suffix' => $this->RankSuffix
				);


		$sql = 'UPDATE ' . MEMBER_RANKS_TABLE . '
			SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE rank_id=' . (int) $old_rank->RankId . '
			AND guild_id = ' . (int) $old_rank->GuildId;
		$db->sql_query($sql);


		// log it
		if (!class_exists('bbDKP_Admin'))
		{
			require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
		}
		$bbdkp = new bbDKP_Admin();

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

}
