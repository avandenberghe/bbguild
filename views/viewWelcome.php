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
class viewwelcome implements iViews
{
	private $navigation;
	public  $response;
	private $tpl;

	/**
	 * viewWelcome constructor.
	 *
	 * @param \bbdkp\bbguild\views\viewnavigation $navigation
	 */
	function __construct(viewnavigation $navigation)
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

		$data = $this->navigation->guild->GetApiInfo(array('news'));
		$this->navigation->guild->GetGuildNews($data);
		$newsarr =  $this->navigation->guild->guildnews;
		$welcometext = $this->GetWelcomeText();

		if (isset($newsarr))
		{
			$i=0;
			foreach ($newsarr as $id => $news)
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
						'TIMESTAMP' => (!empty($news['timestamp'])) ? $this->dateDiff($news['timestamp']) . '&nbsp;' : '&nbsp;',
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
						'TIMESTAMP'   => (!empty($news['timestamp'])) ? $this->dateDiff($news['timestamp']) . '&nbsp;' : '&nbsp;',
						'ACHIEVEMENT' => $news['achievement']['id'],
						'TITLE'       => $news['achievement']['title'],
						'POINTS'      => sprintf($user->lang['FORNPOINTS'], $news['achievement']['points']),
						)
					);
					break;
				}
				if ($i > 25)
				{
					break;
				}
			}
		}

		$template->assign_vars(
			array(
			'EMBLEM'                =>  $this->navigation->guild->emblempath,
			'GUILD_FACTION'         =>  $this->navigation->guild->factionname,
			'GUILD_REALM'           =>  $this->navigation->guild->realm,
			'GUILD_REGION'          =>  $this->navigation->guild->region,
			'GUILD_NAME'            =>  $this->navigation->guild->name,
			'S_GUILD_ALLIANCE'      => ( $this->navigation->guild->game_id == 'wow' &&  $this->navigation->guild->faction == 1) ? true: false,
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
	 * Message odf
	*
	 * @return mixed
	 */
	private function GetWelcomeText()
	{
		global $db;

		$text='';
		$sql = 'SELECT welcome_msg, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . WELCOME_MSG_TABLE;
		$db->sql_query($sql);
		$result = $db->sql_query($sql);
		while ( $row = $db->sql_fetchrow($result) )
		{
			$text = $row['welcome_msg'];
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
	private function dateDiff($epoch)
	{
		$epoch = $epoch / 1000;
		$datetime1 = new \DateTime("@$epoch");
		$datetime2 = new \DateTime();
		$interval = date_diff($datetime1, $datetime2);

		$min=$interval->format('%i');
		$sec=$interval->format('%s');
		$hour=$interval->format('%h');
		$mon=$interval->format('%m');
		$day=$interval->format('%d');
		$year=$interval->format('%y');
		if ($interval->format('%i%h%d%m%y')=="00000")
		{
			//echo $interval->format('%i%h%d%m%y')."<br>";
			$dateDiff= $sec." Seconds";

		}

		else if ($interval->format('%h%d%m%y')=="0000")
		{
			$dateDiff= $min." Minutes";
		}

		else if ($interval->format('%d%m%y')=="000")
		{
			$dateDiff= $hour." Hours";
		}

		else if ($interval->format('%m%y')=="00")
		{
			$dateDiff= $day." Days";
		}

		else if ($interval->format('%y')=="0")
		{
			$dateDiff= $mon." Months";
		}

		else
		{
			$dateDiff= $year." Years";
		}
		return $dateDiff;
	}

}
