<?php
/**
 * @package bbguild
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace bbdkp\bbguild\controller;

use bbdkp\bbguild\views\viewNavigation;

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
	/** @var string */
	protected $phpEx;
	/** @var \phpbb\pagination */
	protected $pagination;
	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;
	/** @var string */
	protected $ext_path;
	/** @var string */
	protected $ext_path_web;
	/** @var string */
	protected $ext_path_images;
	/** @var string */
	protected $root_path;

	/**
	 * view_controller constructor.
	 *
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\controller\helper          $helper
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\request\request            $request
	 * @param \phpbb\user                       $user
	 * @param \phpbb\pagination                 $pagination
	 * @param                                   $php_ext
	 * @param \phpbb\path_helper                $path_helper
	 * @param \phpbb\extension\manager          $phpbb_extension_manager
	 * @param                                   $root_path
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
        \phpbb\controller\helper $helper,
        \phpbb\template\template $template,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\pagination $pagination,
        $php_ext,
		\phpbb\path_helper $path_helper,
		\phpbb\extension\manager $phpbb_extension_manager,
		$root_path)
	{
		$this->auth 	    = $auth;
		$this->config 	    = $config;
		$this->helper 	    = $helper;
		$this->template     = $template;
		$this->db           = $db;
		$this->request      = $request;
		$this->user 	    = $user;
		$this->pagination 	= $pagination;
		$this->php_ext 	    = $php_ext;
		$this->path_helper	= $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->ext_path		    = $this->phpbb_extension_manager->get_extension_path('bbdkp/bbguild', true);
		$this->ext_path_web	    = $this->path_helper->get_web_root_path($this->ext_path);
		$this->ext_path_images	= $this->ext_path_web . 'ext/bbdkp/bbguild/images/';
		$this->root_path  = $root_path;
	}

	private $valid_views = array('roster', 'welcome');
	//private $valid_views = array('news', 'roster', 'standings', 'welcome', 'stats', 'player', 'raids');

    /**
    * View factory
    *
    * @param $guild_id
    * @param $page
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function handleGuild($guild_id, $page)
    {
		if (in_array($page, $this->valid_views))
		{
			$Navigation = new viewNavigation($page, $this->request, $this->user,
				$this->template, $this->db, $this->config, $this->helper, $this->pagination, $this->ext_path,
				$this->ext_path_web, $this->ext_path_images, $this->root_path, $guild_id);
		    $viewtype = "\\bbdkp\\bbguild\\views\\view". ucfirst($page);
			$view = new $viewtype($Navigation);
			$response = $view->response;
			return $response;
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
