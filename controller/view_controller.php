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
	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\config\db_text */
	protected $config_text;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config
	* @param \phpbb\config\db_text		$config_text
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	* @param string						$php_ext	phpEx
	*/
	public function __construct(\phpbb\config\config $config,
                                \phpbb\config\db_text $config_text,
                                \phpbb\controller\helper $helper,
                                \phpbb\template\template $template,
                                \phpbb\user $user,
                                $php_ext)
	{
		$this->config 	= $config;
		$this->helper 	= $helper;
		$this->template = $template;
		$this->user 	= $user;
		$this->php_ext 	= $php_ext;
		$this->config_text = $config_text;
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
		    $Navigation = new \bbdkp\bbguild\views\viewNavigation($page);
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
