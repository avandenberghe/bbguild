<?php
/**
 * welcome module
 *
 * @package   bbguild
 * @copyright 2016 bbDKP <https://github.com/bbDKP>
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */
namespace bbdkp\bbguild\views;

/**
 * Class viewwelcome
 *
 * @package bbdkp\bbguild\views
 */
class viewwelcome implements iviews
{
	private $navigation;
	public  $response;
	private $tpl;

	/**
	 * viewWelcome constructor.
	 *
	 * @param \bbdkp\bbguild\views\viewnavigation $navigation
	 */
	public function __construct(viewnavigation $navigation)
	{
		$this->navigation = $navigation;
		$this->buildpage();
	}

	/**
	 *prepare the rendering
	 */
	public function buildpage()
	{
		global $user, $template;
		$this->tpl = 'main.html';

		$welcometext = $this->get_message_of_the_day();

		if ($this->navigation->guild->isArmoryEnabled())
		{
			$data = $this->navigation->guild->get_api_info(array('news'));
			if ($data)
			{
				$this->navigation->guild->setGuildnews($data);
				$newsarr =  $this->navigation->guild->getGuildnews();
				if (isset($newsarr['news']))
				{
					$i=0;
					foreach ($newsarr['news'] as $id => $news)
					{
						$i++;
						switch ($news['type'])
						{
						case 'itemCraft' :
						case 'itemLoot' :
							$template->assign_block_vars(
								'activityfeed', array(
								'TYPE'      => 'ITEM',
								'ID'        => $id,
								'VERB'      => $user->lang('LOOTED'),
								'CHARACTER' => $news['character'],
								'TIMESTAMP' => (!empty($news['timestamp'])) ? $this->date_diff($news['timestamp']) . '&nbsp;' : '&nbsp;',
								'ITEM'      => isset($news['itemId']) ? $news['itemId'] : '',
								'CONTEXT'   => $news['context'],
								//trade-skill, quest-reward, raid-finder, vendor, dungeon-heroic, raid-normal , dungeon-normal
								)
							);
							break;
						case 'playerAchievement':
							$template->assign_block_vars(
								'activityfeed', array(
								'TYPE'        => 'ACHI',
								'ID'          => $id,
								'VERB'        => $user->lang('ACHIEVED'),
								'CHARACTER'   => $news['character'],
								'TIMESTAMP'   => (!empty($news['timestamp'])) ? $this->date_diff($news['timestamp']) . '&nbsp;' : '&nbsp;',
								'ACHIEVEMENT' => $news['achievement']['id'],
								'TITLE'       => $news['achievement']['title'],
								'POINTS'      => sprintf($user->lang['FORNPOINTS'], $news['achievement']['points']),
								)
							);
							break;


						default:
							$a=$news['type'];
							break;

						}
						if ($i > 25)
						{
							break;
						}
					}
				}
			}

		}


		$template->assign_vars(
			array(
			'EMBLEM'                =>  $this->navigation->guild->getEmblempath(),
			'GUILD_FACTION'         =>  $this->navigation->guild->getFactionname(),
			'WELCOME_MESSAGE'        => $welcometext,
			'S_DISPLAY_WELCOME'     => true,
			)
		);
		$title = $this->navigation->user->lang['WELCOME'];

		unset($newsarr);
		// fully rendered page source that will be output on the screen.
		$this->response = $this->navigation->helper->render($this->tpl, $title);

	}

	/**
	 * Message of the day
	*
	 * @return mixed
	 */
	private function get_message_of_the_day()
	{
		global $db;

		$text='';
		$sql = 'SELECT motd_msg, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . MOTD_TABLE;
		$db->sql_query($sql);
		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result) )
		{
			$text = $row['motd_msg'];
			$bbcode_uid = $row['bbcode_uid'];
			$bbcode_bitfield = $row['bbcode_bitfield'];
			$bbcode_options = $row['bbcode_options'];
		}
		$db->sql_freeresult($result);
		$message = generate_text_for_display($text, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
		return smiley_text($message);
	}

	/**
	 * return relative time difference
	 *
	 * @param  $epoch //in microtime
	 * @return string
	 */
	private function date_diff($epoch)
	{
		$epoch /= 1000;
		$datetime1 = new \DateTime("@$epoch");
		$datetime2 = new \DateTime();
		$interval = date_diff($datetime1, $datetime2);

		$min=$interval->format('%i');
		$sec=$interval->format('%s');
		$hour=$interval->format('%h');
		$mon=$interval->format('%m');
		$day=$interval->format('%d');
		$year=$interval->format('%y');
		if ($interval->format('%i%h%d%m%y')== '00000')
		{
			//echo $interval->format('%i%h%d%m%y')."<br>";
			$dateDiff= $sec. ' Seconds';

		}

		else if ($interval->format('%h%d%m%y')== '0000')
		{
			$dateDiff= $min. ' Minutes';
		}

		else if ($interval->format('%d%m%y')== '000')
		{
			$dateDiff= $hour. ' Hours';
		}

		else if ($interval->format('%m%y')== '00')
		{
			$dateDiff= $day. ' Days';
		}

		else if ($interval->format('%y')== '0')
		{
			$dateDiff= $mon. ' Months';
		}

		else
		{
			$dateDiff= $year. ' Years';
		}
		return $dateDiff;
	}

}
