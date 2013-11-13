<?php
/**
 * bbDKP ucp class file
 * @package bbdkp
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2009 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */

/**
* @package ucp
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
// Include the base class
if (!class_exists('\bbdkp\Admin'))
{
	require("{$phpbb_root_path}includes/bbdkp/admin.$phpEx");
}
if (!class_exists('\bbdkp\controller\guilds\Guilds'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
}
// Include the member class
if (!class_exists('\bbdkp\controller\members\Members'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/members/Members.$phpEx");
}
// include ranks class
if (!class_exists('\bbdkp\controller\guilds\Ranks'))
{
	require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Ranks.$phpEx");
}
/**
 * bbDKP ucp class
 * @package bbdkp
 *
 */
class ucp_dkp extends \bbdkp\Admin
{
	/**
	 * module action
	 * @var string
	 */
	var $u_action;

	/**
	 * guild id
	 * @var int
	 */
	private $guild_id;
	/**
	 * game id
	 * @var string
	 */
	private $game_id;

	/**
	 * main ucp function
	 * @param int $id
	 * @param string $mode
	 */
	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		// Attach the language files
		$user->add_lang(array('mods/dkp_admin', 'mods/dkp_common', 'acp/common'));


		//guild dropdown
		//include the guilds class
		if (!class_exists('\bbdkp\controller\guilds\Guilds'))
		{
			require("{$phpbb_root_path}includes/bbdkp/controller/guilds/Guilds.$phpEx");
		}
		$guilds = new \bbdkp\controller\guilds\Guilds();

		$guildlist = $guilds->guildlist();

		foreach ($guildlist as $g)
		{
			//assign guild_id property
			if($this->guild_id == 0)
			{
				//if there is a default guild
				if($g['guilddefault'] == 1)
				{
					$this->guild_id = $g['id'];
				}

				//if member count > 0
				if($this->guild_id == 0 && $g['membercount'] > 1)
				{
					$this->guild_id = $g['id'];
				}

				//if guild id field > 0
				if($this->guild_id == 0 && $g['id'] > 0)
				{
					$this->guild_id = $g['id'];
				}

			}

			//populate guild popup
			if($g['id'] > 0) // exclude guildless
			{
				$template->assign_block_vars('guild_row', array(
						'VALUE' => $g['id'] ,
						'SELECTED' => ($g['id'] == $this->guild_id ) ? ' selected="selected"' : '' ,
						'OPTION' => (! empty($g['name'])) ? $g['name'] : '(None)'));
			}
		}
		$guilds->guildid = $this->guild_id;

		// GET processing logic
		add_form_key('digests');
		switch ($mode)
		{
			// this mode is shown to users in order to select the character with which they will raid
			case 'characters':
				$this->link = '';
				$submit = (isset($_POST['submit'])) ? true : false;
				$member = new \bbdkp\controller\members\Members();
				if ($submit)
				{
					if (!check_form_key('digests'))
					{
						trigger_error('FORM_INVALID');
					}
					$member_id = (int) request_var('memberlist', 0);
					$member->member_id = $member_id;
					$member->Getmember();
					$member->Claim_Member();
					// Generate confirmation page. It will redirect back to the calling page
					meta_refresh(2, $this->u_action);
					$message = sprintf($user->lang['CHARACTERS_UPDATED'], $member->member_name) . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
					unset($member);
					trigger_error($message);
				}

				$show_buttons = true;
				$show = true;
				$s_guildmembers = ' ';
				//if user has no access to claiming chars, don't show the add button.
				if(!$auth->acl_get('u_dkpucp'))
				{
					$show_buttons = false;
				}

				if($member->has_reached_maxbbdkpaccounts())
				{
					$show_buttons = false;
				}

				//if there are no chars at all, do not show add button
				$sql = 'SELECT count(*) AS mcount FROM ' . MEMBER_LIST_TABLE .' WHERE member_rank_id < 90  ';
				$result = $db->sql_query($sql, 0);
				$mcount = (int) $db->sql_fetchfield('mcount');
				if ($mcount == 0)
				{
					$show = false;
				}

				if ($show != false)
				{

					// list all characters bound to me
					$this->listmychars();

					// build popup for adding new chars to my phpbb account, get only those that are not assigned yet.
					// note if someone picks a guildmember that does not belong to them then the guild admin can override this in acp

					$member->listallmembers($guilds->guildid, true);
					foreach ($member->guildmemberlist as $id => $m  )
                    {
						$s_guildmembers .= '<option value="' . $m['member_id'] .'">'. $m['rank_name']  . ' ' . $m['member_name'] . '</option>';
                    }

				}
				$db->sql_freeresult ($result);
				unset($member);

				// These template variables are used on all the pages
				$template->assign_vars(array(
					'S_DKPMEMBER_OPTIONS'	=> $s_guildmembers,
					'S_SHOW'				=> $show,
					'S_SHOW_BUTTONS'		=> $show_buttons,
					'U_ACTION'  			=> $this->u_action,
					'LA_ALERT_AJAX' 		=> $user->lang['ALERT_AJAX'] ,
					'LA_ALERT_OLDBROWSER' 	=> $user->lang['ALERT_OLDBROWSER'] ,
					)
				);

				$this->tpl_name 	= 'dkp/ucp_dkp';
				$this->page_title 	= $user->lang['UCP_DKP_CHARACTERS'];

				break;
			case 'characteradd':
				//get member_id if selected from pulldown
				$member_id =  request_var('hidden_member_id',  request_var(URI_NAMEID, 0));
				$submit	 = (isset($_POST['add'])) ? true : false;
				$update	 = (isset($_POST['update'])) ? true : false;
				$delete	 = (isset($_POST['delete'])) ? true : false;
				if ( $submit || $update || $delete )
				{
					if($delete)
					{
						// check if user can delete character
						if(!$auth->acl_get('u_dkp_chardelete') )
						{
							trigger_error($user->lang['NOUCPDELCHARS']);
						}
						$this->delete_member($member_id);
					}

					if($submit)
					{
						if (!check_form_key('characteradd'))
						{
							trigger_error('FORM_INVALID');
						}
						$this->add_member();
					}

					if($update)
					{
						if (!check_form_key('characteradd'))
						{
							trigger_error('FORM_INVALID');
						}

						// check if user can update character
						if(!$auth->acl_get('u_dkp_charupdate') )
						{
							trigger_error($user->lang['NOUCPUPDCHARS']);
						}
						$this->update_member($member_id);
					}
				}

				//template fill
				$this->fill_addmember($member_id);

				$template->assign_vars(array(
					// javascript
					'LA_ALERT_AJAX'		  => $user->lang['ALERT_AJAX'],
					'LA_ALERT_OLDBROWSER' => $user->lang['ALERT_OLDBROWSER'],
					'LA_MSG_NAME_EMPTY'	  => $user->lang['FV_REQUIRED_NAME'],
				));
				$this->tpl_name 	= 'dkp/ucp_dkp_charadd';
				break;

		}
	}


	/**
	 * shows add/edit character form
	 *
	 * @param int $member_id
	 */
	private function fill_addmember($member_id)
	{
		global $db, $auth, $user, $template, $config, $phpbb_root_path;
		$members = new \bbdkp\controller\members\Members();
		// Attach the language file
		$user->add_lang('mods/dkp_common');
		$user->add_lang(array('mods/dkp_admin'));
		$show=true;
		if($member_id == 0)
		{
			// check if user can add character
			if(!$auth->acl_get('u_dkp_charadd') )
			{
				trigger_error($user->lang['NOUCPADDCHARS']);
			}

			if(!$auth->acl_get('u_dkpucp'))
			{
				trigger_error($user->lang['NOUCPADDCHARS']);
			}

			if($members->has_reached_maxbbdkpaccounts())
			{
				$show=false;
				$template->assign_vars(array(
				 	'MAX_CHARS_EXCEEDED' => sprintf($user->lang['MAX_CHARS_EXCEEDED'],$config['bbdkp_maxchars']),
				));

			}
			// set add mode
			$S_ADD = true;
		}
		else
		{
			$S_ADD = false;
			$members->member_id=$member_id;
			$members->Getmember();
		}

		//game dropdown
		foreach($this->games as $gameid => $gamename)
		{
			if ( !isset($this->game_id))
			{
				$this->game_id= isset($members->game_id) ? $members->game_id : $gameid;
			}
			$template->assign_block_vars('game_row', array(
					'VALUE' => $gameid,
					'SELECTED' => ( $members->game_id == $gameid ) ? ' selected="selected"' : '',
					'OPTION'   => $gamename,
			));

		}

		// Rank drop-down -> for initial load
		// reloading is done from ajax to prevent redraw
		$Ranks = new \bbdkp\controller\guilds\Ranks($this->guild_id);
		$result = $Ranks->listranks();
		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('rank_row', array(
				'VALUE' => $row['rank_id'] ,
				'SELECTED' => ($members->member_rank_id == $row['rank_id']) ? ' selected="selected"' : '' ,
				'OPTION' => (! empty($row['rank_name'])) ? $row['rank_name'] : '(None)'));
		}


		//race dropdown
		$sql_array = array(
		'SELECT'	=>	'  r.race_id, l.name as race_name ',
		'FROM'		=> array(
				RACE_TABLE		=> 'r',
				BB_LANGUAGE		=> 'l',
					),
		'WHERE'		=> " r.race_id = l.attribute_id
						AND r.game_id = '" . $this->game_id . "'
						AND l.attribute='race'
						AND l.game_id = r.game_id
						AND l.language= '" . $config['bbdkp_lang'] ."'",
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);

		if ($member_id > 0)
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('race_row', array(
				'VALUE' => $row['race_id'],
				'SELECTED' => ( $members->member_race_id == $row['race_id'] ) ? ' selected="selected"' : '',
				'OPTION'   => ( !empty($row['race_name']) ) ? $row['race_name'] : '(None)')
				);
			}

		}
		else
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('race_row', array(
				'VALUE' => $row['race_id'],
				'SELECTED' =>  '',
				'OPTION'   => ( !empty($row['race_name']) ) ? $row['race_name'] : '(None)')
				);
			}
		}

		// Class dropdown
		// reloading is done from ajax to prevent redraw
		$sql_array = array(
			'SELECT'	=>	' c.class_id, l.name as class_name, c.class_hide,
							  c.class_min_level, class_max_level, c.class_armor_type , c.imagename ',
			'FROM'		=> array(
				CLASS_TABLE		=> 'c',
				BB_LANGUAGE		=> 'l',
				),
			'WHERE'		=> " l.game_id = c.game_id  AND c.game_id = '" . $this->game_id . "'
			AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",
			);

		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( $row['class_min_level'] <= 1  )
			{
				 $option = ( !empty($row['class_name']) ) ? $row['class_name'] . "
				 Level (". $row['class_min_level'] . " - ".$row['class_max_level'].")" : '(None)';
			}
			else
			{
				 $option = ( !empty($row['class_name']) ) ? $row['class_name'] . "
				 Level ". $row['class_min_level'] . "+" : '(None)';
			}

			if ($member_id > 0)
			{
				$template->assign_block_vars('class_row', array(
				'VALUE' => $row['class_id'],
				'SELECTED' => ( $members->member_class_id == $row['class_id'] ) ? ' selected="selected"' : '',
				'OPTION'   => $option ));

			}
			else
			{
				$template->assign_block_vars('class_row', array(
				'VALUE' => $row['class_id'],
				'SELECTED' => '',
				'OPTION'   => $option ));
			}

		}
		$db->sql_freeresult($result);

		// set the genderdefault to male if a new form is opened, otherwise take rowdata.
		$genderid = $member_id > 0? $members->member_gender_id : '0';


		// build presets for joindate pulldowns
		$now = getdate();
		$s_memberjoin_day_options = '<option value="0"	>--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$day = isset($members->member_joindate_d) ? $members->member_joindate_d : $now['mday'] ;
			$selected = ($i == $day ) ? ' selected="selected"' : '';
			$s_memberjoin_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_memberjoin_month_options = '<option value="0">--</option>';
		for ($i = 1; $i < 13; $i++)
		{
			$month = isset($members->member_joindate_mo) ? $members->member_joindate_mo : $now['mon'] ;
			$selected = ($i == $month ) ? ' selected="selected"' : '';
			$s_memberjoin_month_options .= " <option value=\"$i\"$selected>$i</option>";
		}

		$s_memberjoin_year_options = '<option value="0">--</option>';
		for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
		{
			$yr = isset($members->member_joindate_y) ? $members->member_joindate_y : $now['year'] ;
			$selected = ($i == $yr ) ? ' selected="selected"' : '';
			$s_memberjoin_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		// check if user can add character
		$S_UPDATE = true;
		if(!$auth->acl_get('u_dkp_charupdate') )
		{
			$S_UPDATE = false;
		}

		$S_DELETE = true;
		if(!$auth->acl_get('u_dkp_chardelete') )
		{
			$S_DELETE = false;
		}

		$form_key = 'characteradd';
		add_form_key($form_key);
		$genderid = $members->member_id > 0 ? $members->member_gender_id : '0';

		$template->assign_vars(array(
			'STATUS'				=> $member_id > 0 ? (($members->member_status == 1) ? 'Checked ' : '' ): 'Checked ',
			'MEMBER_NAME'			=> $member_id > 0 ? $members->member_name : '',
			'MEMBER_ID'				=> $member_id > 0 ? $members->member_id : '',
			'MEMBER_LEVEL'			=> $member_id > 0 ? $members->member_level : '',
			'MALE_CHECKED'			=> ($genderid == '0') ? ' checked="checked"' : '' ,
			'FEMALE_CHECKED'		=> ($genderid == '1') ? ' checked="checked"' : '' ,
			'MEMBER_COMMENT'		=> $member_id > 0 ? $members->member_comment : '',

			'S_CAN_HAVE_ARMORY'		=>  $member_id > 0 ? ($members->game_id == 'wow' || $members->game_id == 'aion'  ? true : false) : false,
			'MEMBER_URL'			=>  $member_id > 0 ? $members->member_armory_url : '',
			'MEMBER_PORTRAIT'		=>  $member_id > 0 ? $members->member_portrait_url : '',

			'S_MEMBER_PORTRAIT_EXISTS'  => $member_id > 0 ? ((strlen( $members->member_portrait_url ) > 1) ? true : false) : false,
			'S_CAN_GENERATE_ARMORY'		=> $member_id > 0 ? ($members->game_id == 'wow' ? true : false) : false,

			'COLORCODE' 			=> $member_id > 0 ? (($members->colorcode == '') ? '#123456' : $members->colorcode) : '#F123456',

			'CLASS_IMAGE' 			=> $members->class_image,
			'S_CLASS_IMAGE_EXISTS' 	=> $member_id > 0 ? (strlen($members->class_image) > 1) ? true : false : false,

			'RACE_IMAGE' 			=> $members->race_image ,
			'S_RACE_IMAGE_EXISTS' => (strlen( $members->race_image) > 1) ? true : false ,

			'S_JOINDATE_DAY_OPTIONS'	=> $s_memberjoin_day_options,
			'S_JOINDATE_MONTH_OPTIONS'	=> $s_memberjoin_month_options,
			'S_JOINDATE_YEAR_OPTIONS'	=> $s_memberjoin_year_options,
			'S_SHOW' => $show,
			'S_ADD' => $S_ADD,
			'S_CANDELETE' => $S_DELETE,
			'S_CANUPDATE' => $S_UPDATE,
		));


	}

	/**
	 * gets user input, adds member
	 *
	 */
	private function add_member()
	{
		global $db, $config, $user, $phpbb_root_path, $phpEx;

		// check again if user exceeded allowed character count
		$sql = 'SELECT count(*) as charcount
				FROM ' . MEMBER_LIST_TABLE . '
				WHERE phpbb_user_id = ' .  (int) $user->data['user_id'];
		$result = $db->sql_query($sql);
		$countc = $db->sql_fetchfield('charcount');
		$db->sql_freeresult($result);
		if ($countc >= $config['bbdkp_maxchars'])
		{
			 trigger_error(sprintf($user->lang['MAX_CHARS_EXCEEDED'],$config['bbdkp_maxchars']) , E_USER_WARNING);
		}

		// get member name
		$member_name = utf8_normalize_nfc(request_var('member_name', '',true));

		// check if membername exists
		$sql = 'SELECT count(*) as memberexists
				FROM ' . MEMBER_LIST_TABLE . "
				WHERE UPPER(member_name)= UPPER('" . $db->sql_escape($member_name) . "')";
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('memberexists');
		$db->sql_freeresult($result);
		if ($countm != 0)
		{
			 trigger_error($user->lang['ERROR_MEMBEREXIST'], E_USER_WARNING);
		}

		// set member active
		$member_status = request_var('activated', 0) > 0 ? 1 : 0;

		$guild_id = request_var('member_guild_id', 0);

		// get rank
		$rank_id = request_var('member_rank_id',99);

		// check if rank exists
		$sql = 'SELECT COUNT(*) as rankccount
				FROM ' . MEMBER_RANKS_TABLE . '
				WHERE rank_id=' . (int) $rank_id . ' and guild_id = ' . (int) $guild_id;
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('rankccount');
		$db->sql_freeresult($result);
		if ( $countm == 0)
		{
			 trigger_error($user->lang['ERROR_INCORRECTRANK'], E_USER_WARNING);
		}

		$member_lvl = request_var('member_level', 0);
		// check level
		$sql = 'SELECT MAX(class_max_level) AS maxlevel FROM ' . CLASS_TABLE;
		$result = $db->sql_query($sql);
		$maxlevel = $db->sql_fetchfield('maxlevel');
		$db->sql_freeresult($result);
		if ( $member_lvl > $maxlevel)
		{
			$member_lvl = $maxlevel;
		}

		$game_id = request_var('game_id', '');
		$race_id = request_var('member_race_id', 0);
		$class_id = request_var('member_class_id', 0);
		$gender = isset($_POST['gender']) ? request_var('gender', '') : '0';

		$member_comment = utf8_normalize_nfc(request_var('member_comment', '', true));

		$joindate	= mktime(0,0,0,request_var('member_joindate_mo', 0), request_var('member_joindate_d', 0), request_var('member_joindate_y', 0));

		//is there leavedate?
		$achievpoints = 0;
		$url = utf8_normalize_nfc(request_var('member_armorylink', '', true));

		$phpbb_user_id = $user->data['user_id'];
		$leavedate = mktime ( 0, 0, 0, 12, 31, 2030 );
		$sql = 'SELECT realm, region FROM ' . GUILD_TABLE . ' WHERE id = ' . (int) $guild_id;
		$result = $db->sql_query($sql);
		$realm = ''; $region='';

		while ( $row = $db->sql_fetchrow($result) )
		{
			$realm = $row['realm'];
			$region = $row['region'];
		}

		if (! class_exists ( 'acp_dkp_mm' ))
		{
			include ($phpbb_root_path . 'includes/acp/acp_dkp_mm.' . $phpEx);
		}
		$acp_dkp_mm = new acp_dkp_mm ( );

		$member_id = $acp_dkp_mm->insertnewmember($member_name, $member_status, $member_lvl, $race_id, $class_id,
			$rank_id, $member_comment, $joindate, $leavedate, $guild_id, $gender, $achievpoints, $url, ' ', $realm, $game_id, $phpbb_user_id);

		if ($member_id > 0)
		{
			// record added.
			$success_message = sprintf($user->lang['ADMIN_ADD_MEMBER_SUCCESS'], ucwords($member_name));
			trigger_error($success_message, E_USER_NOTICE);
		}
		else
		{
			$failure_message = sprintf($user->lang['ADMIN_ADD_MEMBER_FAIL'], ucwords($member_name), $member_id);
			 trigger_error($failure_message, E_USER_WARNING);
		}

	}


	/**
	 * gets user input, updates member
	 * @param int $member_id
	 */
	private function update_member($member_id)
	{
		global $db, $user, $phpbb_root_path, $phpEx;

		// get member name
		$member_name = utf8_normalize_nfc(request_var('member_name', '',true));
		$sql = 'SELECT *
				FROM ' . MEMBER_LIST_TABLE . '
				WHERE member_id = ' . (int) $member_id;
		$result = $db->sql_query($sql);
		$oldmember = array();
		$oldmember = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		// check if membername exists
		$sql = 'SELECT COUNT(*) as memberexists
				FROM ' . MEMBER_LIST_TABLE . "
				WHERE UPPER(member_name)= UPPER('" . $db->sql_escape($member_name) . "')";
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('memberexists');
		$db->sql_freeresult($result);
		if (($countm != 0) && ($member_name != $oldmember['member_name']))
		{
			 meta_refresh(3, $this->u_action . '&amp;member_id=' . $member_id);
			 trigger_error($user->lang['ERROR_MEMBEREXIST'], E_USER_WARNING);

		}

		$member_status = request_var('activated', 0) > 0 ? 1 : 0;
		$guild_id = request_var('member_guild_id', 0);
		$rank_id = request_var('member_rank_id',99);
		$sql = 'SELECT count(*) as rankccount
				FROM ' . MEMBER_RANKS_TABLE . '
				WHERE rank_id=' . (int) $rank_id . ' and guild_id = ' . (int) $guild_id;
		$result = $db->sql_query($sql);
		$countm = $db->sql_fetchfield('rankccount');
		$db->sql_freeresult($result);
		if ( $countm == 0)
		{
			 meta_refresh(3, $this->u_action . '&amp;member_id=' . $member_id);
			 trigger_error($user->lang['ERROR_INCORRECTRANK'], E_USER_WARNING);
		}

		$member_lvl = request_var('member_level', 0);
		$sql = 'SELECT max(class_max_level) as maxlevel FROM ' . CLASS_TABLE;
		$result = $db->sql_query($sql);
		$maxlevel = $db->sql_fetchfield('maxlevel');
		$db->sql_freeresult($result);
		if ( $member_lvl > $maxlevel)
		{
			$member_lvl = $maxlevel;
		}

		$game_id = request_var('game_id', '');
		$race_id = request_var('member_race_id', 0);
		$class_id = request_var('member_class_id', 0);
		$gender = isset($_POST['gender']) ? request_var('gender', '') : '0';
		$member_comment = utf8_normalize_nfc(request_var('member_comment', '', true));
		//$joindate	= mktime(0,0,0,request_var('member_joindate_mo', 0), request_var('member_joindate_d', 0), request_var('member_joindate_y', 0));
		if (! class_exists ( 'acp_dkp_mm' ))
		{
			include ($phpbb_root_path . 'includes/acp/acp_dkp_mm.' . $phpEx);
		}
		$acp_dkp_mm = new acp_dkp_mm ( );

		if ($acp_dkp_mm->updatemember($member_id, $member_name, $member_lvl, $race_id, $class_id,
			$rank_id, $member_comment, $guild_id, $gender, 0, ' ' ,' ' , $game_id, $member_status))
		{
			// record updated.

			meta_refresh(3, $this->u_action . '&amp;member_id=' . $member_id);
			$success_message = sprintf($user->lang['ADMIN_UPDATE_MEMBER_SUCCESS'], ucwords($member_name))  . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($success_message, E_USER_NOTICE);
		}
		else
		{
			// update fail.
			meta_refresh(3, $this->u_action . '&amp;member_id=' . $member_id);
			$failure_message = sprintf($user->lang['ADMIN_UPDATE_MEMBER_FAIL'], ucwords($member_name)) . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($failure_message, E_USER_WARNING);
		}

	}

	/**
	 * deletes member
	 * @param int $member_id
	 */
	private function delete_member($member_id)
	{
		global $user, $db, $config, $phpbb_root_path, $phpEx;

		if (confirm_box(true))
		{
			// recall hidden vars
			$del_member = request_var('del_member_id', 0);
			$del_membername = utf8_normalize_nfc(request_var('del_member_name','',true));
			$log_action = array(
				'header'	=>	 sprintf( $user->lang['ACTION_MEMBER_DELETED'], $del_membername),
				'L_NAME'	=> $del_membername
			);

			if (! class_exists ( 'acp_dkp' ))
			{
				include ($phpbb_root_path . 'includes/acp/acp_dkp.' . $phpEx);
			}
			$acp_dkp = new acp_dkp ( );

			$acp_dkp->log_insert(array(
				'log_type'	 => $log_action['header'],
				'log_action' => $log_action)
			);

			$sql = 'DELETE FROM ' . RAID_DETAIL_TABLE . ' where member_id= ' . (int) $del_member;
			$db->sql_query($sql);
			$sql = 'DELETE FROM ' . RAID_ITEMS_TABLE . ' where member_id= ' . (int) $del_member;
			$db->sql_query($sql);
			$sql = 'DELETE FROM ' . MEMBER_DKP_TABLE . ' where member_id= ' . (int) $del_member;
			$db->sql_query($sql);
			$sql = 'DELETE FROM ' . ADJUSTMENTS_TABLE .' where member_id= ' . (int) $del_member;
			$db->sql_query($sql);
			$sql = 'DELETE FROM ' . MEMBER_LIST_TABLE . ' where member_id= ' . (int) $del_member;
			$db->sql_query($sql);

			$success_message = sprintf($user->lang['ADMIN_DELETE_MEMBERS_SUCCESS'], $del_membername);
			trigger_error($success_message);
		}
		else
		{

		    $sql = "SELECT member_name FROM " . MEMBER_LIST_TABLE . ' where member_id = ' . $member_id ;
		    $result = $db->sql_query($sql);
			$member_name = $db->sql_fetchfield('member_name', 1, $result);
			$db->sql_freeresult($result);

			$s_hidden_fields = build_hidden_fields(array(
				'delete'				=> true,
				'del_member_id'			=> $member_id,
				'del_member_name'		=> $member_name,
				)
			);

			confirm_box(false, sprintf($user->lang['CONFIRM_DELETE_MEMBER'], $member_name) , $s_hidden_fields);
		}
	}


	/**
	 * lists all my characters
	 *
	 */
	private function listmychars()
	{

		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;
		$members = new \bbdkp\controller\members\Members();

		$mycharacters = $members->getmemberlist(0, 'listing', false, false, '', '', 0, 0, 0, 0, 200, true);

		$lines = 0;
		foreach ($mycharacters[0] as $char)
		{
			$template->assign_block_vars('members_row', array(
					'U_EDIT'		=> append_sid("{$phpbb_root_path}ucp.$phpEx", "i=dkp&amp;mode=characteradd&amp;". URI_NAMEID . '=' . $char['member_id']),
					'GAME'			=> $char['game_id'],
					'COLORCODE'		=> $char['colorcode'],
					'CLASS'			=> $char['class_name'],
					'NAME'			=> $char['member_name'],
					'RACE'			=> $char['race_name'],
					'GUILD'			=> $char['guildname'],
					'REALM'			=> $char['realm'],
					'REGION'		=> $char['region'],
					'RANK'			=> $char['member_rank'],
					'LEVEL'			=> $char['member_level'],
					'ARMORY'		=> $char['member_armory_url'],
					'PHPBBUID'		=> $char['username'],
					'PORTRAIT'		=> $char['member_portrait_url'],
					'ACHIEVPTS'		=> $char['member_achiev'],
					'CLASS_IMAGE' 	=> $char['class_image'],
					'RACE_IMAGE' 	=> $char['race_image'],
			));


			$sql_array2 = array(
			    'SELECT'    => ' d.dkpsys_id, d.dkpsys_name,
				SUM(m.member_earned + m.member_adjustment) AS ep,
			    SUM(m.member_spent - m.member_item_decay + ( ' . max(0, $config['bbdkp_basegp']) . ')) AS gp,
     			SUM(m.member_earned + m.member_adjustment - m.member_spent + m.member_item_decay - ( ' . max(0, $config['bbdkp_basegp']) . ') ) AS member_current,
				CASE WHEN SUM(m.member_spent - m.member_item_decay) <= 0
					THEN SUM(m.member_earned + m.member_adjustment)
					ELSE ROUND( SUM(m.member_earned + m.member_adjustment) /  SUM(' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay) ,2)
				END AS pr',
			    'FROM'      => array(
				        MEMBER_DKP_TABLE 	=> 'm',
				        DKPSYS_TABLE 		=> 'd',
			    	),
			    'WHERE'     => " m.member_dkpid = d.dkpsys_id and m.member_id = " . $char['member_id'],
				'GROUP_BY'  => " d.dkpsys_id, d.dkpsys_name " ,
				'ORDER_BY'	=> " d.dkpsys_name ",
			    );

		    $sql2 = $db->sql_build_query('SELECT', $sql_array2);
		    $dkp_result = $db->sql_query($sql2);
			while ($row2 = $db->sql_fetchrow($dkp_result))
			{
				$template->assign_block_vars('members_row.dkp_row', array(
					'DKPSYS'        => $row2['dkpsys_name'],
					'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}dkp.$phpEx",
							"page=viewmember&amp;". URI_NAMEID . '=' . $row['member_id'] . '&amp;' . URI_DKPSYS . '= ' . $row2['dkpsys_id'] ),
					'EARNED'       => $row2['ep'],
					'SPENT'        => $row2['gp'],
					'PR'           => $row2['pr'],
					'CURRENT'      => $row2['member_current'],
					)
				);
			}
 					$db->sql_freeresult ($dkp_result);

		}
		$template->assign_vars(array(
			'S_SHOWEPGP' 	=> ($config['bbdkp_epgp'] == '1') ? true : false,
		));

	}



}
?>