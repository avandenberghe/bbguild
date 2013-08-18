<?php
// Get current and latest version
$errstr = '';
$errno = 0;
$installed_version = $config['bbdkp_version'];
$info = $this->curl('https://raw.github.com/Sajaki/bbDKP/v130/contrib/version.txt', false, false, false);
if ($info === false)
{
	// version file reference does not exist, show error
	$template->assign_vars(array(
			'S_UP_TO_DATE' => 1 ,
			'BBDKPVERSION' => $user->lang['YOURVERSION'] . $installed_version ,
			'UPDATEINSTR' => $user->lang['VERSION_NOTONLINE']));
}
else
{
	$info = explode("\n", $info);
	$latest_version = trim($info[0]);
	// is the installed version >= the latest version ? then you are up to date
		
	if (version_compare(
			str_replace('rc', 'RC', strtolower($installed_version)),
			str_replace('rc', 'RC', strtolower($latest_version)), '='))
	{
		// your version is the same than the official version
		$template->assign_vars(array(
				'S_UP_TO_DATE' => 0 ,
				'BBDKPVERSION' => 'bbDKP ' . $config['bbdkp_version']));
	}
	elseif (version_compare(
			str_replace('rc', 'RC', strtolower($installed_version)),
			str_replace('rc', 'RC', strtolower($latest_version)), '>'))
	{
		// you have a developmentversion
		$template->assign_vars(array(
				'S_UP_TO_DATE' => 1 ,
				'BBDKPVERSION' => $user->lang['YOURVERSION'] . $installed_version ,
				'UPDATEINSTR' => $user->lang['LATESTVERSION'] . $latest_version) );
	}
	else
	{
		// you have an old version
		$template->assign_vars(array(
				'S_UP_TO_DATE' => -1 ,
				'BBDKPVERSION' => $user->lang['YOURVERSION'] . $installed_version ,
				'UPDATEINSTR' => $user->lang['LATESTVERSION'] . $latest_version . ', <a href="' . $user->lang['WEBURL'] . '">' . $user->lang['DOWNLOAD'] . '</a>'));
	}
}

//begin bbdkp plugin look up and version find
//find out if apply is installed and get version number if it is
$apply_installed = isset($config['bbdkp_apply_version']) ? $config['bbdkp_apply_version'] : 0;
if ($apply_installed != 0)
{
	$template->assign_vars(array(
			'S_APPLY_INSTALLED' => true,
	));

	// Get current and latest version of apply
	$errstr = '';
	$errno = 0;
	$installed_version = $config['bbdkp_apply_version'];
	$info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version_apply.txt', $errstr, $errno);
	if ($info === false)
	{
		// version file reference does not exist, show error
		$template->assign_vars(array(
				'S_APPLY_UP_TO_DATE' => false ,
				'APPLYVERSION' => $installed_version ,
				'APPLYUPDATEINSTR' => $user->lang['PLUGIN_VERSION_NOTONLINE']));
	}
	else
	{
		$info = explode("\n", $info);
		$latest_version = trim($info[0]);
		// is the installed version >= the latest version ? then you are up to date
		$up_to_date = (version_compare(str_replace('rc', 'RC', strtolower($installed_version)), str_replace('rc', 'RC', strtolower($latest_version)), '>=')) ? true : false;
		if ($up_to_date)
		{
			// your version is the same or even newer than the official version
			$template->assign_vars(array(
					'S_APPLY_UP_TO_DATE' => true ,
					'APPLYVERSION' => $config['bbdkp_apply_version'],
					'APPLYLATEST' => $latest_version ));
		}
		else
		{
			// you have an old version
			$template->assign_vars(array(
					'S_APPLY_UP_TO_DATE' => false ,
					'APPLYVERSION' =>	$installed_version ,
					'APPLYUPDATEINSTR' => $user->lang['LATESTPLUGINVERSION'] . '<a href="' . $user->lang['PLUGINURL'] . '">' . $user->lang['DOWNLOAD_LATEST_PLUGINS'] . $latest_version . '</a>'));
		}
	}

}
else
{
	$template->assign_vars(array(
			'S_APPLY_INSTALLED' => false,
	));
}

//find out if bbtips is installed and get version number if it is
$bbtips_installed = isset($config['bbdkp_plugin_bbtips_version']) ? $config['bbdkp_plugin_bbtips_version'] : 0;
if ($bbtips_installed != 0)
{
	$template->assign_vars(array(
			'S_BBTIPS_INSTALLED' => true,
	));

	// Get current and latest version of bbTips
	$errstr = '';
	$errno = 0;
	$installed_version = $config['bbdkp_plugin_bbtips_version'];
	$info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version_bbtips.txt', $errstr, $errno);
	if ($info === false)
	{
		// version file reference does not exist, show error
		$template->assign_vars(array(
				'S_BBTIPS_UP_TO_DATE' => false ,
				'BBTIPSVERSION' => $installed_version ,
				'BBTIPSUPDATEINSTR' => $user->lang['PLUGIN_VERSION_NOTONLINE']));
	}
	else
	{
		$info = explode("\n", $info);
		$latest_version = trim($info[0]);
		// is the installed version >= the latest version ? then you are up to date
		$up_to_date = (version_compare(str_replace('rc', 'RC', strtolower($installed_version)), str_replace('rc', 'RC', strtolower($latest_version)), '>=')) ? true : false;
		if ($up_to_date)
		{
			// your version is the same or even newer than the official version
			$template->assign_vars(array(
					'S_BBTIPS_UP_TO_DATE' => true ,
					'BBTIPSVERSION' => $config['bbdkp_plugin_bbtips_version'],
					'BBTIPSLATEST' => $latest_version ));
		}
		else
		{
			// you have an old version
			$template->assign_vars(array(
					'S_BBTIPS_UP_TO_DATE' => false ,
					'BBTIPSVERSION' =>$installed_version ,
					'BBTIPSUPDATEINSTR' => $user->lang['LATESTPLUGINVERSION'] . '<a href="' . $user->lang['PLUGINURL'] . '">' . $user->lang['DOWNLOAD_LATEST_PLUGINS'] . $latest_version . '</a>'));
		}
	}

}
else
{
	$template->assign_vars(array(
			'S_BBTIPS_INSTALLED' => false,
	));
}

//find out if bossprogress is installed and get version number if it is
$bossprogress_installed = isset($config['bbdkp_bp_version']) ? $config['bbdkp_bp_version'] : 0;
if ($bossprogress_installed != 0)
{
	$template->assign_vars(array(
			'S_BOSSPROGRESS_INSTALLED' => true,
	));

	// Get current and latest version of bossprogress
	$errstr = '';
	$errno = 0;
	$installed_version = $config['bbdkp_bp_version'];
	$info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version_bossprogress.txt', $errstr, $errno);
	if ($info === false)
	{
		// version file reference does not exist, show error
		$template->assign_vars(array(
				'S_BOSSPROGRESS_UP_TO_DATE' => false ,
				'BOSSPROGRESSVERSION' => $installed_version ,
				'BOSSPROGRESSUPDATEINSTR' => $user->lang['PLUGIN_VERSION_NOTONLINE'],
		));
	}
	else
	{
		$info = explode("\n", $info);
		$latest_version = trim($info[0]);
		// is the installed version >= the latest version ? then you are up to date
		$up_to_date = (version_compare(str_replace('rc', 'RC', strtolower($installed_version)), str_replace('rc', 'RC', strtolower($latest_version)), '>=')) ? true : false;
		if ($up_to_date)
		{
			// your version is the same or even newer than the official version
			$template->assign_vars(array(
					'S_BOSSPROGRESS_UP_TO_DATE' => true ,
					'BOSSPROGRESSVERSION' => $config['bbdkp_bp_version'],
					'BPLATEST' => $latest_version ));
		}
		else
		{
			// you have an old version
			$template->assign_vars(array(
					'S_BOSSPROGRESS_UP_TO_DATE' => false ,
					'BOSSPROGRESSVERSION' =>$installed_version ,
					'BOSSPROGRESSUPDATEINSTR' => $user->lang['LATESTPLUGINVERSION'] . '<a href="' . $user->lang['PLUGINURL'] . '">' . $user->lang['DOWNLOAD_LATEST_PLUGINS'] . $latest_version . '</a>'));
		}
	}

}
else
{
	$template->assign_vars(array(
			'S_BOSSPROGRESS_INSTALLED' => false,
	));
}

//find out if raidplanner is installed and get version number if it is
$raidplanner_installed = isset($config['bbdkp_raidplanner']) ? $config['bbdkp_raidplanner'] : 0;
if ($raidplanner_installed != 0)
{
	$template->assign_vars(array(
			'S_RAID_PLANNER_INSTALLED' => true,
	));

	// Get current and latest version of raidplanner
	$errstr = '';
	$errno = 0;
	$installed_version = $config['bbdkp_raidplanner'];
	$info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version_raidplanner.txt', $errstr, $errno);
	if ($info === false)
	{
		// version file reference does not exist, show error
		$template->assign_vars(array(
				'S_RAIDPLANNER_UP_TO_DATE' => false ,
				'RAIDPLANNERVERSION' => $installed_version ,
				'RAIDPLANNERUPDATEINSTR' => $user->lang['PLUGIN_VERSION_NOTONLINE']));
	}
	else
	{
		$info = explode("\n", $info);
		$latest_version = trim($info[0]);
		// is the installed version >= the latest version ? then you are up to date
		$up_to_date = (version_compare(str_replace('rc', 'RC', strtolower($installed_version)), str_replace('rc', 'RC', strtolower($latest_version)), '>=')) ? true : false;
		if ($up_to_date)
		{
			// your version is the same or even newer than the official version
			$template->assign_vars(array(
					'S_RAIDPLANNER_UP_TO_DATE' => true ,
					'RAIDPLANNERVERSION' => $config['bbdkp_raidplanner'],
					'RAIDPLANNERLATEST' => $latest_version  ));
		}
		else
		{
			// you have an old version
			$template->assign_vars(array(
					'S_RAIDPLANNER_UP_TO_DATE' => false ,
					'RAIDPLANNERVERSION' =>$installed_version ,
					'RAIDPLANNERUPDATEINSTR' => $user->lang['LATESTPLUGINVERSION'] . '<a href="' . $user->lang['PLUGINURL'] . '">' . $user->lang['DOWNLOAD_LATEST_PLUGINS'] . $latest_version . '</a>'));
		}
	}

}
else
{
	$template->assign_vars(array(
			'S_RAID_PLANNER_INSTALLED' => false,
	));
}

//find out if raidtracker is installed and get version number if it is
$raidtracker_installed = isset($config['bbdkp_raidtracker']) ? $config['bbdkp_raidtracker'] : 0;
if ($raidtracker_installed != 0)
{
	$template->assign_vars(array(
			'S_RAID_TRACKER_INSTALLED' => true,
	));

	// Get current and latest version of raidtracker
	$errstr = '';
	$errno = 0;
	$installed_version = $config['bbdkp_raidtracker'];
	$info = get_remote_file('bbdkp.googlecode.com', '/svn/trunk', 'version_raidtracker.txt', $errstr, $errno);
	if ($info === false)
	{
		// version file reference does not exist, show error
		$template->assign_vars(array(
				'S_RAIDTRACKER_UP_TO_DATE' => false ,
				'RAIDTRACKVERSION' => $installed_version ,
				'RAIDTRACKUPDATEINSTR' => $user->lang['PLUGIN_VERSION_NOTONLINE']));
	}
	else
	{
		$info = explode("\n", $info);
		$latest_version = trim($info[0]);
		// is the installed version >= the latest version ? then you are up to date
		$up_to_date = (version_compare(str_replace('rc', 'RC', strtolower($installed_version)), str_replace('rc', 'RC', strtolower($latest_version)), '>=')) ? true : false;
		if ($up_to_date)
		{
			// your version is the same or even newer than the official version
			$template->assign_vars(array(
					'S_RAIDTRACKER_UP_TO_DATE' => true ,
					'RAIDTRACKVERSION' => $config['bbdkp_raidtracker'],
					'RAIDTRACKLATEST' => $latest_version  ));
		}
		else
		{
			// you have an old version
			$template->assign_vars(array(
					'S_RAIDTRACKER_UP_TO_DATE' => false ,
					'RAIDTRACKVERSION' =>$installed_version ,
					'RAIDTRACKUPDATEINSTR' => $user->lang['LATESTPLUGINVERSION'] . '<a href="' . $user->lang['PLUGINURL'] . '">' . $user->lang['DOWNLOAD_LATEST_PLUGINS'] . $latest_version . '</a>'));
		}
	}

}
else
{
	$template->assign_vars(array(
			'S_RAID_TRACKER_INSTALLED' => false,
	));
}

$plugins = array(
		0 => isset($config['bbdkp_plugin_bbtips_version']) ? $config['bbdkp_plugin_bbtips_version'] : false,
		1 => isset($config['bbdkp_raidtracker']) ? $config['bbdkp_raidtracker'] : false,
		2 => isset($config['bbdkp_bp_version']) ? $config['bbdkp_bp_version'] : false,
		3 => isset($config['bbdkp_apply_version']) ? $config['bbdkp_apply_version'] : false,
		4 => isset($config['bbdkp_raidplanner']) ? $config['bbdkp_raidplanner'] : false ,
);

$pluginsinstalled = count(array_filter($plugins));
$pluginsavailable = 6 - $pluginsinstalled;
if ($pluginsinstalled > 0)
{
	$template->assign_vars(array(
			'S_ANY_PLUGINS_INSTALLED' => false,
	));
}
else
{
	$template->assign_vars(array(
			'S_ANY_PLUGINS_INSTALLED' => true,
			'PLUGINLINK' => $user->lang['PLUGINURL']
	));
}

//find if you have all 6 bbdkp mods installed and if now provide link to get more
if ($pluginsinstalled < 6 && $pluginsinstalled > 0)
{
	$template->assign_vars(array(
			'S_ANY_PLUGINS_AVAIL' => true,
			'PLUGINLINK' => $user->lang['PLUGINURL'],
			'NUMBOFPLUGINS' => $pluginsavailable
	));
}
else
{
	$template->assign_vars(array(
			'S_ANY_PLUGINS_AVAIL' => false,
	));
}

//end where to get bbdkp plugins if none are installed



<table cellspacing="1">
<col class="col1" /><col class="col2" />

<thead>
<tr>
<th>{L_PLUGINS_INSTALLED}</th>
<th>{L_PLUGINS_STATUS}</th>
</tr>
</thead>
<tbody>

<tr>
<!-- IF S_ANY_PLUGINS_INSTALLED -->
<td class="row1" style="width:50%"><B>{L_NO_PLUGINS_INSTALLED}</B></td>
<td class="row1" style="width:50%"><a href="{PLUGINLINK}"><B>{L_DOWNLOAD_PLUGINS}</B></a></td>
<!-- ENDIF -->
</tr>

<tr>
<!-- IF S_APPLY_INSTALLED -->
<td class="row2" style="width:50%">{L_APPLY_TITLE}: {APPLYVERSION}</td>
<!-- IF S_APPLY_UP_TO_DATE -->
<td class="row1" style="width:50%"><font color="green">{L_PLUGIN_UP_TO_DATE}</font></td>
	<!-- ELSE -->
	<td class="row1" style="width:50%"><font color="red">{APPLYUPDATEINSTR}</font></td>
	<!-- ENDIF -->
	<!-- ENDIF -->
	</tr>

	<tr>
	<!-- IF S_BBTIPS_INSTALLED -->
	<td class="row2" style="width:50%">{L_BBTIPS_TITLE}: {BBTIPSVERSION}</td>
			<!-- IF S_BBTIPS_UP_TO_DATE -->
			<td class="row1" style="width:50%"><font color="green">{L_PLUGIN_UP_TO_DATE}</font></td>
			<!-- ELSE -->
			<td class="row1" style="width:50%"><font color="red">{BBTIPSUPDATEINSTR}</font></td>
			<!-- ENDIF -->
			<!-- ENDIF -->
			</tr>

			<tr>
			<!-- IF S_BOSSPROGRESS_INSTALLED -->
			<td class="row2" style="width:50%">{L_BOSSPROGRESS_TITLE}: {BOSSPROGRESSVERSION}</td>
			<!-- IF S_BOSSPROGRESS_UP_TO_DATE -->
			<td class="row1" style="width:50%"><font color="green">{L_PLUGIN_UP_TO_DATE}</font></td>
	<!-- ELSE -->
	<td class="row1" style="width:50%"><font color="red">{BOSSPROGRESSUPDATEINSTR}</font></td>
    <!-- ENDIF -->
    <!-- ENDIF -->
    </tr>

    <tr>
    <!-- IF S_RAID_PLANNER_INSTALLED -->
    <td class="row2" style="width:50%">{L_RAIDPLANNER_TITLE}: {RAIDPLANNERVERSION}</td>
    <!-- IF S_RAIDPLANNER_UP_TO_DATE -->
    <td class="row1" style="width:50%"><font color="green">{L_PLUGIN_UP_TO_DATE}</font></td>
	<!-- ELSE -->
	<td class="row1" style="width:50%"><font color="red">{RAIDPLANNERUPDATEINSTR}</font></td>
	<!-- ENDIF -->
	<!-- ENDIF -->
	</tr>

	<tr>
	<!-- IF S_RAID_TRACKER_INSTALLED -->
	<td class="row2" style="width:50%">{L_RAIDTRACKER_TITLE}: {RAIDTRACKVERSION}</td>
	<!-- IF S_RAIDTRACKER_UP_TO_DATE -->
	<td class="row1" style="width:50%"><font color="green">{L_PLUGIN_UP_TO_DATE}</font></td>
	<!-- ELSE -->
	<td class="row1" style="width:50%"><font color="red">{RAIDTRACKUPDATEINSTR}</font></td>
	<!-- ENDIF -->
	<!-- ENDIF -->
			</tr>

			<tr>
<!-- IF S_ANY_PLUGINS_AVAIL -->
<td class="row1" style="width:50%"><B>{L_ADD_PLUGINS_AVAIL}: {NUMBOFPLUGINS}</B></td>
<td class="row1" style="width:50%"><a href="{PLUGINLINK}"><B>{L_DOWNLOAD_HERE}</B></a></td>
<!-- ENDIF -->
</tr>

		</tbody>
</table>
