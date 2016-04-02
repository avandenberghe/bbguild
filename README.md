[![bbDKP](http://www.avathar.be/bbdkp/images/site_logo.png)](http://www.avathar.be/bbdkp)

### About
bbGuild is a MMO Guild manager for your phpBB3 Bulletin board. It was originally forked from EQDKP to phpBB in 2008. 2.0 version is rebuild on the Symfony 2 framework for phpBB 3.1. 

[![Build Status](https://api.travis-ci.org/bbDKP/bbguild.svg)](https://travis-ci.org/bbDKP/bbguild)
[![experimental](http://badges.github.io/stability-badges/dist/experimental.svg)](http://github.com/badges/stability-badges)

Features : 
- Guild Roster
- Guild News page
- WoW Armory link
- ACP and UCP modules. 
- Games supported : 
- World of Warcraft, the Lord of the Rings Online, Dark Age of Camelot, Vanguard, EverQuest I/II,  Warhammer Online, Final Fantasy XI, AION, Rift, SW:Tor, Lineage 2, TERA, FFXIV and Custom Game. 
- Languages supported : English, French, German, Italian. 

There will be a number of extensions that enhance bbGuild
 - bbDKP manager, with different loot systems, and raid tracking.
 - bbRaidtracker Wow Lua plugin and extension
 - bbGameWorld Raid progress extension 
 - bbRaidCalendar extension. 

See todo.md for upcoming changes. 

Further development requests, support questions are welcome at our [Support Forum](http://www.avathar.be/bbdkp) or in the [phpbb.com extension development topic](https://www.phpbb.com/community/viewtopic.php?f=456&t=2258141)
	
### Current
2.0.0-a4

### Installation
##### Requirements
1.	phpbb > 3.1.*
2.	ftp and acp access to your phpbb forum.  

##### Install
1. Download the latest release.
2. In the `ext` directory of your phpBB board, create a new directory named `bbdkp` (if it does not already exist).
3. Copy the `bbguild` folder to `/ext/bbdkp/` (if done correctly, you'll have the main extension class at (your forum root)/ext/bbdkp/bbguild/composer.json).
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Look for `bbGuild Extension` under the Disabled Extensions list, and click its `Enable` link.

##### Uninstall
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `bbGuild Extension` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/bbdkp/bbguild` folder.
## Community

Find support and more on 
*	Forums : [www.avathar.be/bbdkp](http://www.avathar.be/bbdkp)
*	IRC : [Freenode](https://webchat.freenode.net) #bbdkp

### Changelog 
- 2.0.0a5 27/03/2016
  - [NEW] Front Page design updated to look like Blizzard Armory
  - [NEW] WoW emblem generator now makes 200px emblems (should be made configurable)    
  - [NEW] GW2 : initial support for GW2 api, added GW2 Revenant profession
  - [NEW] WoW : added Demon hunter class
  - [CHG] Faction id now added to Guild class. can be set on Guild creation. 
          For WoW, the front page design depends on the Faction of the Guild. 
          Horde guilds will have a watermark horde symbol. 
  - [NEW] Default game setting added. This is needed when populating the Faction drop down in 'new Guild' ACP.
  - [FIX] Roster member filter now works
     
- 2.0.0a4 13/03/2016
  - [NEW] guild news page added, Blizzard news feed data 
- 2.0.0a2 21/02/2016
  - [NEW] viewcontroller is now done, with a first frontpage : the guild roster. 
- 2.0.0a1 not released
  - [NEW] Conversion to extension
  - [CHG] Functionality reductions : DKP no longer part of core. 

### contribute
You can see all the awesome people contributing to this project [here](https://github.com/bbdkp/bbguild/graphs/contributors).
1. [Create a ticket (unless there already is one)] : https://github.com/bbDKP/bbGuild/issues
2. [Read our Git Contribution Guidelines](http://www.avathar.be/bbdkp/viewtopic.php?f=60&t=1854); if you're new to git, also read [Git Primer](http://www.avathar.be/bbdkp/viewtopic.php?f=60&t=1853)
3. Make a pull request.

### License
[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

### Paypal donation
[![Foo](https://www.paypal.com/en_US/BE/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sajaki9%40gmail%2ecom&lc=BE&item_name=bbDKP%20Guild%20management&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

