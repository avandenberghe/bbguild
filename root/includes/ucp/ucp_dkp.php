<?php
/** 
*
* @package ucp
* @copyright (c) 2010 bbDKP 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
* @author Sajaki
* This is the user interface for the character dkp integration
*/
			
/**
* @package ucp
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class ucp_dkp
{
	var $u_action;
					
	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;
		$s_hidden_fields = '';
		
		// Attach the language file
		$user->add_lang('mods/dkp_common');
		$user->add_lang(array('mods/dkp_admin'));
			
		// GET processing logic
		add_form_key('digests');
		switch ($mode)
		{
			// this mode is shown to users in order to select the character with which they will raid
			case 'characters':
				$submit = (isset($_POST['submit'])) ? true : false;
				if ($submit)
				{
					// user pressed submit
					// Verify the form key is unchanged
					if (!check_form_key('digests'))
					{
						trigger_error('FORM_INVALID');
					}
					
					$sql_ary = array(
						'phpbb_user_id'	=> $user->data['user_id'], 
					);
					$sql = 'UPDATE ' . MEMBER_LIST_TABLE . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE member_id = ' . (int) request_var('dkpmember', 0);
					$db->sql_query($sql);
					
					// Generate confirmation page. It will redirect back to the calling page
					meta_refresh(3, $this->u_action);
					$message = $user->lang['CHARACTERS_UPDATED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
					trigger_error($message);
				}
				
				$show_buttons = false;
				$show = true;
				$s_guildmembers = ' '; 
				//if user has no access to claiming chars, don't show the add button.
				if(!$auth->acl_get('u_dkpucp'))
				{
					$show_buttons = false;
				}
				
				//if there are no chars at all, show a message and do not show add button
				$sql = 'SELECT count(*) AS mcount FROM ' . MEMBER_LIST_TABLE .' WHERE member_rank_id < 90  ';
				$result = $db->sql_query($sql, 0);
				$mcount = (int) $db->sql_fetchfield('mcount');
				if ( $mcount == 0)
				{
					$show = false;
				}
				else 
				{
					// list all characters bound to me
					$this->listmychars();
					
					// build popup for adding new chars to my phpbb account, get only those that are not assigned yet.
					// do not show if all accounts are assigned
					// if someone picks a guildmember that does not belong to them then the guild admin can override it in acp
					$sql = 'SELECT member_id, member_name FROM ' . MEMBER_LIST_TABLE .' WHERE 
						phpbb_user_id = 0 and member_rank_id != 90 order by member_name asc';
					$result = $db->sql_query($sql, 0);
					
					while ( $row = $db->sql_fetchrow($result) )
                    {
						$s_guildmembers .= '<option value="' . $row['member_id'] .'">' . $row['member_name'] . '</option>';
                    	$show_buttons = true;
                    }
				}
				$db->sql_freeresult ($result);
									
				// These template variables are used on all the pages
				$template->assign_vars(array(
					'S_DKPMEMBER_OPTIONS'	=> $s_guildmembers,
					'S_SHOW'				=> $show,
					'S_SHOW_BUTTONS'		=> $show_buttons,
					'U_ACTION'  			=> $this->u_action,
					)
				);
				
				$this->tpl_name 	= 'dkp/ucp_dkp';
				$this->page_title 	= $user->lang['UCP_DKP_CHARACTERS'];
				
				break;
			case 'characteradd':
				//SHOW ADD MEMBER FORM

				$submit	 = (isset($_POST['add'])) ? true : false;
				$update	 = (isset($_POST['update'])) ? true : false;
				if ( $submit || $update)
				{
					if (!check_form_key('mm_addmember'))
					{
						trigger_error('FORM_INVALID');
					}
				}
				
				//get member_id if selected from pulldown
				$member_id =  request_var('hidden_member_id',  request_var('s_member_id', 0)); 
				
				//template fill
				$this->fill_addmember($member_id);
				
				$this->tpl_name 	= 'dkp/ucp_dkp_charadd';
				break;
						
		}
	}
	

	/**
	 * shows add/edit character form
	 *
	 * @param unknown_type $member_id
	 */
	private function fill_addmember($member_id)
	{
		
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;
		$s_hidden_fields = '';
		
		// Attach the language file
		$user->add_lang('mods/dkp_common');
		$user->add_lang(array('mods/dkp_admin'));

		if($member_id == 0)
		{
			// add mode
			$S_ADD = true;
		}
		
		//guild dropdown
		$sql = 'SELECT a.id, a.name, a.realm, a.region 
		FROM '. GUILD_TABLE . ' a, ' . MEMBER_RANKS_TABLE . ' b 
		where a.id = b.guild_id
		group by a.id, a.name, a.realm, a.region
		order by a.id desc'; 
		$result = $db->sql_query($sql);
		if (isset ($this->member))
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('guild_row', 
				array(
				'VALUE' => $row['id'],
				'SELECTED' => (	 $this->member['member_guild_id'] == $row['id'] ) ? ' selected="selected"' : '',
				'OPTION'   => $row['name'] )
				);
			}
		}
		else 
		{
			$i=0;
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ($i==0)
				{
					$noguild_id = (int) $row['id']; 
				}
				$template->assign_block_vars('guild_row', array(
				'VALUE' => $row['id'],
				'SELECTED' =>  '',
				'OPTION'   => $row['name'] )
				);
				$i+=1;
			}
		}
		$db->sql_freeresult($result);

		// Rank drop-down -> for initial load
		// reloading is done from ajax to prevent redraw
		//
		// this only shows the VISIBLE RANKS
		// if you want to add someone to an unvisible rank make the rank visible first, 
		// add him and then make rank invisible again.
		if (isset($this->member['member_guild_id']))
		{
			$sql = 'SELECT rank_id, rank_name
			FROM ' . MEMBER_RANKS_TABLE . ' 
			WHERE rank_hide = 0  
			AND rank_id < 90  
			AND guild_id =	'. $this->member['member_guild_id'] . ' ORDER BY rank_id';
			$result = $db->sql_query($sql);
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('rank_row', array(
							'VALUE'	   => $row['rank_id'],
							'SELECTED' => ( $this->member['member_rank_id'] == $row['rank_id'] ) ? ' selected="selected"' : '',
							'OPTION'   => ( !empty($row['rank_name']) ) ? $row['rank_name'] : '(None)')
					);
			}
		}
		else 
		{
			// no member is set, get the ranks from the highest numbered guild
			$sql = 'SELECT rank_id, rank_name
			FROM ' . MEMBER_RANKS_TABLE . ' 
			WHERE rank_hide = 0 
			AND rank_id < 90 
			AND guild_id = ' . $noguild_id . ' ORDER BY rank_id desc';
			$result = $db->sql_query($sql);
			
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('rank_row', array(
							'VALUE'	   => $row['rank_id'],
							'SELECTED' => '',
							'OPTION'   => ( !empty($row['rank_name']) ) ? $row['rank_name'] : '(None)')
				);
			}
		}
		
		//game
		$games = array(
                  'wow'        => $user->lang['WOW'], 
                  'lotro'      => $user->lang['LOTRO'], 
                  'eq'         => $user->lang['EQ'], 
                  'daoc'       => $user->lang['DAOC'], 
                  'vanguard' 	 => $user->lang['VANGUARD'],
                  'eq2'        => $user->lang['EQ2'],
                  'warhammer'  => $user->lang['WARHAMMER'],
                  'aion'       => $user->lang['AION'],
                  'FFXI'       => $user->lang['FFXI'],
              	'rift'       => $user->lang['RIFT'],
              	'swtor'      => $user->lang['SWTOR']
              );
              $installed_games = array();
              foreach($games as $gameid => $gamename)
              {
              	//add value to dropdown when the game config value is 1
              	if ($config['bbdkp_games_' . $gameid] == 1)
              	{
              		$template->assign_block_vars('game_row', array(
				'VALUE' => $gameid,
				'SELECTED' => ' selected="selected"' ,
				'OPTION'   => $gamename, 
				));
				
              		$installed_games[] = $gameid; 
              	} 
              }
              
              //
		// Race dropdown
		// reloading is done from ajax to prevent redraw
              $gamepreset = (isset($this->member['game_id']) ? $this->member['game_id'] : $installed_games[0]);
              
		$sql_array = array(
		'SELECT'	=>	'  r.race_id, l.name as race_name ', 
		'FROM'		=> array(
				RACE_TABLE		=> 'r',
				BB_LANGUAGE		=> 'l',
					),
		'WHERE'		=> " r.race_id = l.attribute_id 
						AND r.game_id = '" . $gamepreset . "' 
						AND l.attribute='race' 
						AND l.game_id = r.game_id 
						AND l.language= '" . $config['bbdkp_lang'] ."'",
		);
		
		$sql = $db->sql_build_query('SELECT', $sql_array);

		$result = $db->sql_query($sql);
		
		if (isset ($this->member))
		{
			while ( $row = $db->sql_fetchrow($result) )
			{
				$template->assign_block_vars('race_row', array(
				'VALUE' => $row['race_id'],
				'SELECTED' => ( $this->member['member_race_id'] == $row['race_id'] ) ? ' selected="selected"' : '',
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
		
		
		//
		// Class dropdown
		// reloading is done from ajax to prevent redraw
		$sql_array = array(
			'SELECT'	=>	' c.class_id, l.name as class_name, c.class_hide,
							  c.class_min_level, class_max_level, c.class_armor_type , c.imagename ', 
			'FROM'		=> array(
				CLASS_TABLE		=> 'c',
				BB_LANGUAGE		=> 'l', 
				),
			'WHERE'		=> " l.game_id = c.game_id  AND c.game_id = '" . $gamepreset . "' 
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
			
			if (isset ($this->member))
			{
				$template->assign_block_vars('class_row', array(
				'VALUE' => $row['class_id'],
				'SELECTED' => ( $this->member['member_class_id'] == $row['class_id'] ) ? ' selected="selected"' : '',
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
		$genderid = isset($this->member) ? $this->member['member_gender_id'] : '0'; 
		
		
		// build presets for joindate pulldowns
		$now = getdate();
		$s_memberjoin_day_options = '<option value="0"	>--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$day = isset($this->member['member_joindate_d']) ? $this->member['member_joindate_d'] : $now['mday'] ;
			$selected = ($i == $day ) ? ' selected="selected"' : '';
			$s_memberjoin_day_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_memberjoin_month_options = '<option value="0">--</option>';
		for ($i = 1; $i < 13; $i++)
		{
			$month = isset($this->member['member_joindate_mo']) ? $this->member['member_joindate_mo'] : $now['mon'] ;
			$selected = ($i == $month ) ? ' selected="selected"' : '';
			$s_memberjoin_month_options .= "<option value=\"$i\"$selected>$i</option>";
		}

		$s_memberjoin_year_options = '<option value="0">--</option>';
		for ($i = $now['year'] - 10; $i <= $now['year']; $i++)
		{
			$yr = isset($this->member['member_joindate_y']) ? $this->member['member_joindate_y'] : $now['year'] ;
			$selected = ($i == $yr ) ? ' selected="selected"' : '';
			$s_memberjoin_year_options .= "<option value=\"$i\"$selected>$i</option>";
		}
              
              
		$form_key = 'characteradd';
		add_form_key($form_key);

		$template->assign_vars(array(
			'MEMBER_NAME'			=> isset($this->member) ? $this->member['member_name'] : '',
			'MEMBER_ID'				=> isset($this->member) ? $this->member['member_id'] : '',
			'MEMBER_LEVEL'			=> isset($this->member) ? $this->member['member_level'] : '',
			'MALE_CHECKED'			=> ($genderid == '0') ? ' checked="checked"' : '' , 
			'FEMALE_CHECKED'		=> ($genderid == '1') ? ' checked="checked"' : '' , 
			'MEMBER_COMMENT'		=> isset($this->member) ? $this->member['member_comment'] : '',
		
			'S_CAN_HAVE_ARMORY'		=>  isset($this->member) ? ($this->member['game_id'] == 'wow' || $this->member['game_id'] == 'aion'  ? true : false) : false, 
			'MEMBER_URL'			=>  isset($this->member) ? $this->member['member_armory_url'] : '',
			'MEMBER_PORTRAIT'		=>  isset($this->member) ? $this->member['member_portrait_url'] : '',

			//'S_MEMBER_PORTRAIT_EXISTS'  => (strlen( $this->member['member_portrait_url'] ) > 1) ? true : false,	
			'S_CAN_GENERATE_ARMORY'		=>  isset($this->member) ? ($this->member['game_id'] == 'wow' ? true : false) : false,
		
			//'COLORCODE' 			=> ($this->member['colorcode'] == '') ? '#123456' : $this->member['colorcode'],
               	//'CLASS_IMAGE' 			=> $this->member['class_image'],  
			//'S_CLASS_IMAGE_EXISTS'  => (strlen( $this->member['class_image'] ) > 1) ? true : false, 
               	//'RACE_IMAGE' 			=> $this->member['race_image'],  
			//'S_RACE_IMAGE_EXISTS' 	=> (strlen( $this->member['race_image'] ) > 1) ? true : false, 
		
			'S_JOINDATE_DAY_OPTIONS'	=> $s_memberjoin_day_options,
			'S_JOINDATE_MONTH_OPTIONS'	=> $s_memberjoin_month_options,
			'S_JOINDATE_YEAR_OPTIONS'	=> $s_memberjoin_year_options,

			// javascript
			'LA_ALERT_AJAX'		  => $user->lang['ALERT_AJAX'],
			'LA_ALERT_OLDBROWSER' => $user->lang['ALERT_OLDBROWSER'],
			'LA_MSG_NAME_EMPTY'	  => $user->lang['FV_REQUIRED_NAME'],
			'UA_FINDRANK'		  => append_sid("findrank.$phpEx"),
			'UA_FINDCLASSRACE'    => append_sid("findclassrace.$phpEx"),
		
			'S_ADD' => $S_ADD,
		));
		
		
	}

	/**
	 * lists all my characters
	 *
	 */
	private function listmychars()
	{
		
		global $db, $user, $auth, $template, $config, $phpbb_root_path, $phpEx;
		
		// make a listing of my own characters with dkp for each pool
		$sql_array = array(
		    'SELECT'    => 	'm.member_id, m.member_name, m.member_level, u.username, g.name as guildname,
		    				 m.member_gender_id, a.image_female_small, a.image_male_small, 
		    				 l.name as member_class , c.imagename, c.colorcode  ', 
		    'FROM'      => array(
		        MEMBER_LIST_TABLE 	=> 'm',
		        CLASS_TABLE  		=> 'c',
		        RACE_TABLE  		=> 'a',
		        BB_LANGUAGE			=> 'l', 
		        GUILD_TABLE  		=> 'g',
		        USERS_TABLE 		=> 'u', 
		    	),
		 
		    'WHERE'     =>  "  l.game_id = c.game_id and l.attribute_id = c.class_id AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class'
							  AND (m.member_guild_id = g.id) 
							  AND (m.member_class_id = c.class_id and m.game_id = c.game_id)
							  AND m.member_race_id =  a.race_id  and m.game_id = a.game_id
							  AND u.user_id = m.phpbb_user_id and u.user_id = " . $user->data['user_id']  ,
			'ORDER_BY'	=> " m.member_name ",
		    );

	    $sql = $db->sql_build_query('SELECT', $sql_array);
		if (!($members_result = $db->sql_query($sql)) )
		{
			trigger_error($user->lang['ERROR_MEMBERNOTFOUND'], E_USER_WARNING);
		}
		$lines = 0;
		$member_count = 0;
		while ( $row = $db->sql_fetchrow($members_result) )
		{
			++$member_count;
			$raceimage = (string) (($row['member_gender_id']==0) ? $row['image_male_small'] : $row['image_female_small']);
			$template->assign_block_vars('members_row', array(
				'COUNT'         => $member_count,
				'NAME'          => $row['member_name'],
				'LEVEL'         => $row['member_level'],
				'CLASS'         => $row['member_class'],
				'GUILD'         => $row['guildname'],
				'COLORCODE'  	=> ($row['colorcode'] == '') ? '#123456' : $row['colorcode'],
		        'CLASS_IMAGE' 	=> (strlen($row['imagename']) > 1) ? $phpbb_root_path . "images/class_images/" . $row['imagename'] . ".png" : '',  
				'S_CLASS_IMAGE_EXISTS' => (strlen($row['imagename']) > 1) ? true : false,
		       	'RACE_IMAGE' 	=> (strlen($raceimage) > 1) ? $phpbb_root_path . "images/race_images/" . $raceimage . ".png" : '',  
				'S_RACE_IMAGE_EXISTS' => (strlen($raceimage) > 1) ? true : false, 			 				
			
				)
			); 
			
			$sql_array2 = array(
			    'SELECT'    => ' d.dkpsys_id, d.dkpsys_name, 
				m.member_earned - m.member_raid_decay + m.member_adjustment AS ep,
			    m.member_spent - m.member_item_decay + ( ' . max(0, $config['bbdkp_basegp']) . ') AS gp, 
     					(m.member_earned - m.member_raid_decay + m.member_adjustment - m.member_spent + m.member_item_decay - ( ' . max(0, $config['bbdkp_basegp']) . ') ) AS member_current,

     					sum(case when m.member_spent - m.member_item_decay <= 0 
				then m.member_earned - m.member_raid_decay + m.member_adjustment  
				else round( (m.member_earned - m.member_raid_decay + m.member_adjustment) / (' . max(0, $config['bbdkp_basegp']) .' + m.member_spent - m.member_item_decay) ,2) end) as pr  
				', 
			    'FROM'      => array(
				        MEMBER_DKP_TABLE 	=> 'm', 
				        DKPSYS_TABLE 		=> 'd',
			    	),
			    'WHERE'     => " m.member_dkpid = d.dkpsys_id and m.member_id = " . $row['member_id'],
				'GROUP_BY'  => " d.dkpsys_name " , 
				'ORDER_BY'	=> " d.dkpsys_name ",
			    );

		    $sql2 = $db->sql_build_query('SELECT', $sql_array2);
		    $dkp_result = $db->sql_query($sql2); 
			while ($row2 = $db->sql_fetchrow($dkp_result))
			{
				$template->assign_block_vars('members_row.dkp_row', array(
					'DKPSYS'        => $row2['dkpsys_name'],
					'U_VIEW_MEMBER' => append_sid("{$phpbb_root_path}dkp.$phpEx", "page=viewmember&amp;". URI_NAMEID . '=' . $row['member_id'] . '&amp;' . URI_DKPSYS . '= ' . $row2['dkpsys_id'] ), 
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
		$db->sql_freeresult ($members_result);
		
		
		
	}

}
?>