<?php
/**
 * @package bbguild
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace bbdkp\bbguild\controller;

class view_controller
{
	/** @var \phpbb\auth\auth */
	protected $auth;
	/** @var \phpbb\config\config */
	protected $config;
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\request\request */
	protected $request;
	/** @var \phpbb\user */
	protected $user;
	/** @var string phpEx */
	protected $phpEx;

	/**
	* Constructor
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config		$config
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	* @param string						$php_ext	phpEx
	*/
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
        \phpbb\controller\helper $helper,
        \phpbb\template\template $template,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
        $php_ext)
	{
		$this->auth 	= $auth;
		$this->config 	= $config;
		$this->helper 	= $helper;
		$this->template = $template;
		$this->db       = $db;
		$this->request  = $request;
		$this->user 	= $user;
		$this->php_ext 	= $php_ext;
	}

	private $valid_views = array('news', 'roster', 'standings', 'welcome', 'loothistory', 'lootdb',
		'listevents', 'stats', 'listraids', 'event',
		'item', 'raid', 'member', 'bossprogress', 'planner');

	/**
     * ViewController
	 *
	 * @param $guild_id
	 * @param $page
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function handleGuild($guild_id, $page)
    {

		if (in_array($page, $this->valid_views))
	    {
		    $Navigation = new \bbdkp\bbguild\views\viewNavigation($page, $this->request, $this->user);
		    $viewtype = "\\bbdkp\\bbguild\\views\\view". ucfirst($page);
		    return new $viewtype($Navigation);
	    }
	    else
	    {
		    if (isset($this->user->lang['NOVIEW']))
		    {
			    trigger_error(sprintf($this->user->lang['NOVIEW'], $page ));
		    }
	    }

    }

}
