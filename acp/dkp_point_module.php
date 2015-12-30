<?php
/**
 * Points acp file
 *
 * @package bbdkp v2.0
 * @copyright 2015 bbdkp <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace sajaki\bbdkp\acp;

class dkp_point_module extends \sajaki\bbdkp\model\admin\Admin
{

    private $PointsController;
    public $u_action;
    public $link;

    function main ($id, $mode)
    {
        global $user, $template, $cache, $config, $phpbb_admin_path, $phpEx, $db;
        global $request, $phpbb_container;

        $link = '<br /><a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=\sajaki\bbdkp\acp\dkp_point_module&amp;mode=pointconfig') . '"><h3>' . $user->lang['RETURN_DKPINDEX'] . '</h3></a>';

        $this->PointsController = new \sajaki\bbdkp\model\points\PointsController;
        $this->page_title = 'ACP_DKP_POINT_CONFIG';
        $this->tpl_name = 'acp_dkp_' . $mode;

        $form_key = 'sajaki/bbdkp';
        add_form_key($form_key);

        switch ($mode)
        {
            case 'pointconfig':
                $submit = ($request->is_set_post('update')) ? true : false;
                if ($submit)
                {
                    if (! check_form_key('sajaki/bbdkp'))
                    {
                        trigger_error($user->lang['FV_FORMVALIDATION'], E_USER_WARNING);
                    }
                    //decay
                    $config->set('bbdkp_decay', $request->variable('decay_activate', 0), true);
                    $config->set('bbdkp_itemdecaypct', $request->variable('itemdecaypct', 0), true);
                    $config->set('bbdkp_raiddecaypct', $request->variable('raiddecaypct', 0), true);
                    $config->set('bbdkp_decayfrequency', $request->variable('decayfreq', 0), true);
                    $config->set('bbdkp_decayfreqtype', $request->variable('decayfreqtype', 0), true);
                    $config->set('bbdkp_adjdecaypct', $request->variable('adjdecaypct', 0), true);
                    //time
                    $config->set('bbdkp_timebased', $request->variable('timebonus_activate', 0.00), true);
                    $config->set('bbdkp_dkptimeunit', $request->variable('dkptimeunit', 0.00), true);
                    $config->set('bbdkp_timeunit', $request->variable('timeunit', 0.00), true);
                    $config->set('bbdkp_standardduration', $request->variable('standardduration', 0.00), true);
                    //zerosum
                    if ($request->variable('zerosum_activate', 0) == 0)
                    {
                        $config->set('bbdkp_zerosum', 0, true);
                    }
                    if ($request->variable('zerosum_activate', 0) == 1 && $request->variable('epgp_activate', 0) == 1)
                    {
                        $config->set('bbdkp_zerosum', 1, true);
                        //epgp and zerosum are mutually exclusive, zerosum will prevail if selected
                        $config->set('bbdkp_epgp', 0, true);
                    }
                    if ($request->variable('zerosum_activate', 0) == 0 && $request->variable('epgp_activate', 0) == 1)
                    {
                        $config->set('bbdkp_zerosum', 0, true);
                        $config->set('bbdkp_epgp', 1, true);
                    }
                    if ($request->variable('zerosum_activate', 0) == 1 && $request->variable('epgp_activate', 0) == 0)
                    {
                        $config->set('bbdkp_zerosum', 1, true);
                        $config->set('bbdkp_epgp', 0, true);
                    }
                    if ($request->variable('zerosum_activate', 0) == 0 && $request->variable('epgp_activate', 0) == 0)
                    {
                        $config->set('bbdkp_zerosum', 0, true);
                        $config->set('bbdkp_epgp', 0, true);
                    }
                    $config->set('bbdkp_bankerid', $request->variable('zerosumbanker', 0), true);
                    $config->set('bbdkp_zerosumdistother', $request->variable('zerosumdistother', 0), true);
                    $config->set('bbdkp_basegp', $request->variable('basegp', 0.0), true);
                    $config->set('bbdkp_minep', $request->variable('minep', 0.0), true);
                    $config->set('bbdkp_decaycron', $request->variable('decay_scheduler', 0), true);
                    $cache->destroy('config');
                    trigger_error('Settings saved.' . $link, E_USER_NOTICE);
                }
                $zerosum_synchronise = ($request->is_set_post('zerosum_synchronise')) ? true : false;
                $decay_synchronise = ($request->is_set_post('decay_synchronise')) ? true : false;
                $dkp_synchronise = ($request->is_set_post('syncdkp')) ? true : false;

                // resynchronise DKP
                if ($dkp_synchronise)
                {
                    if (confirm_box(true))
                    {
                        $message = sprintf($user->lang['ADMIN_DKPPOOLSYNC_SUCCESS'] , $this->PointsController->syncdkpsys());
                        trigger_error ( $message . $link , E_USER_NOTICE );
                    }
                    else
                    {
                        $s_hidden_fields = build_hidden_fields(array(
                            'syncdkp' => true));
                        $template->assign_vars(array(
                            'S_HIDDEN_FIELDS' => $s_hidden_fields));
                        confirm_box(false, sprintf($user->lang['RESYNC_DKP_CONFIRM']), $s_hidden_fields);
                    }
                }
                // recalculate zerosum
                if ($zerosum_synchronise)
                {
                    if (confirm_box(true))
                    {
                        $this->PointsController->sync_zerosum($config['bbdkp_zerosum']);
                    }
                    else
                    {
                        $s_hidden_fields = build_hidden_fields(array(
                            'zerosum_synchronise' => true));
                        $template->assign_vars(array(
                            'S_HIDDEN_FIELDS' => $s_hidden_fields));
                        confirm_box(false, sprintf($user->lang['RESYNC_ZEROSUM_CONFIRM']), $s_hidden_fields);
                    }
                }
                if ($decay_synchronise)
                {
                    if (confirm_box(true))
                    {
                        $count = $this->PointsController->sync_decay($config['bbdkp_decay']);
                        $count1 = $this->PointsController->sync_adjdecay($config['bbdkp_decay']);
                        trigger_error(sprintf($user->lang['RESYNC_DECAY_SUCCESS'], $count, $count1) . $link, E_USER_NOTICE);
                    }
                    else
                    {
                        $s_hidden_fields = build_hidden_fields(array('decay_synchronise' => true));
                        $template->assign_vars(array(
                            'S_HIDDEN_FIELDS' => $s_hidden_fields));
                        confirm_box(false, sprintf($user->lang['RESYNC_DECAY_CONFIRM']), $s_hidden_fields);
                    }
                }

                $freqtypes = array(
                    0 => $user->lang['FREQ0'] ,
                    1 => $user->lang['FREQ1'] ,
                    2 => $user->lang['FREQ2']);
                $s_freqtype_options = '';
                foreach ($freqtypes as $key => $type)
                {
                    $selected = ($config['bbdkp_decayfreqtype'] == $key) ? ' selected="selected"' : '';
                    $s_freqtype_options .= '<option value="' . $key . '" ' . $selected . '> ' . $type . '</option>';
                }

                $s_bankerlist_options = '';

                $sql = 'SELECT member_id, member_name FROM ' . MEMBER_LIST_TABLE . " WHERE member_status = '1' order by member_name asc";
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result))
                {
                    $selected = ($config['bbdkp_bankerid'] == $row['member_id']) ? ' selected="selected"' : '';
                    $s_bankerlist_options .= '<option value="' . $row['member_id'] . '" ' . $selected . '> ' . $row['member_name'] . '</option>';
                }
                $db->sql_freeresult($result);
                unset ($db, $result);

                $template->assign_vars(array(
                    'DKP_NAME' => $config['bbdkp_dkp_name'] ,
                    //epgp
                    'F_EPGPACTIVATE' => $config['bbdkp_epgp'] ,
                    'BASEGP' => $config['bbdkp_basegp'] ,
                    'MINEP' => $config['bbdkp_minep'] ,
                    //decay
                    'F_DECAYACTIVATE' => $config['bbdkp_decay'] ,
                    'ITEMDECAYPCT' => $config['bbdkp_itemdecaypct'] ,
                    'RAIDDECAYPCT' => $config['bbdkp_raiddecaypct'] ,
                    'ADJDECAYPCT' => $config['bbdkp_adjdecaypct'] ,
                    'DECAYFREQ' => $config['bbdkp_decayfrequency'] ,
                    'S_FREQTYPE_OPTIONS' => $s_freqtype_options ,
                    'F_DECAYSCHEDULER' => $config['bbdkp_decaycron'] ,
                    //time dkp
                    'F_TIMEBONUSACTIVATE' => $config['bbdkp_timebased'] ,
                    'DKPTIMEUNIT' => $config['bbdkp_dkptimeunit'] ,
                    'TIMEUNIT' => $config['bbdkp_timeunit'] ,
                    'STANDARDDURATION' => $config['bbdkp_standardduration'] ,
                    //zs
                    'F_ZEROSUMACTIVATE' => $config['bbdkp_zerosum'] ,
                    'S_BANKER_OPTIONS' => $s_bankerlist_options ,
                    'F_ZEROSUM_DISTOTHER' => $config['bbdkp_zerosumdistother'] ,
                    'DECAYIMGEXAMPLE' => $this->ext_path . "adm/images/decayexample.png"));

                break;
        }
    }
}
