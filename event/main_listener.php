<?php
/**
 * @package bbguild v2.0
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace sajaki\bbguild\event;


/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\config\config */
	protected $config;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template			$template	Template object
	*/
	public function __construct(\phpbb\controller\helper $helper,
                                \phpbb\template\template $template,
                                \phpbb\user $user,
                                \phpbb\config\config $config
                                )
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
	}


    /**
     * Assign functions defined in this class to event listeners in the core
     *
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            // for all defined events, write a function below
            'core.user_setup'						=> 'load_language_on_setup',
            'core.page_header'						=> 'add_page_header_link',
        );
    }

    /**
     * core.user_setup
     * @param $event
    */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'sajaki/bbguild',
			'lang_set' => array('common','admin') ,
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}


    /**
     * core.page_header
     * @param $event
    */
    public function add_page_header_link($event)
    {
        $this->template->assign_vars(array(
            'U_DKP'	=> $this->helper->route('sajaki_bbguild_01a',
                array('guild_id' => 1)),
        ));
    }


}
