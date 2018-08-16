[![bbDKP](http://www.avathar.be/bbdkp/images/site_logo.png)](http://www.avathar.be/bbdkp)

ALPHA VERSION

### About
bbGuild is a MMO Guild manager for your phpBB3 Bulletin board. It was originally forked from EQDKP to phpBB in 2008. 2.0 version is rebuild on the Symfony 2 framework for phpBB 3.1. 

Please note that bbGuild is pre-Alpha software and is not intended for live installations. Use at your own risk.

[![Build Status](https://api.travis-ci.org/Sajaki/bbguild.svg)](https://travis-ci.org/Sajaki/bbguild)
[![experimental](http://badges.github.io/stability-badges/dist/experimental.svg)](http://github.com/badges/stability-badges)

Features : 
- Guild Roster
- Guild News page
- WoW Armory link
- ACP and UCP modules. 
- Games supported : 
- World of Warcraft, the Lord of the Rings Online, Dark Age of Camelot, Vanguard, EverQuest I/II,  Warhammer Online, Final Fantasy XI, AION, Rift, SW:Tor, Lineage 2, TERA, FFXIV and Custom Game. 
- Languages supported : English, French, German, Italian. 

See docs/changelog for upcoming and past changes. 

Further development requests, support questions are welcome at our [Support Forum](http://www.avathar.be/bbdkp) or in the [phpbb.com extension development topic](https://www.phpbb.com/community/viewtopic.php?f=456&t=2258141)
	
### Current
2.0.0-a8 2018/08

### Installation
##### Requirements
1.  phpbb > 3.2
2.  PHP >= 5.4.39
3.  PHP Client URL Library (php_curl.dll)
4.  PHP GD library (php_gd2.dll)


##### Install
1. Download the latest release.
2. In the `ext` directory of your phpBB board, create a new directory named `bbdkp` (if it does not already exist).
3. Copy the `bbguild` folder to `/ext/bbdkp/` (if done correctly, you'll have the main extension class at (your forum root)/ext/avathar/bbguild/composer.json).
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Look for `bbGuild Extension` under the Disabled Extensions list, and click its `Enable` link.

##### Uninstall
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `bbGuild Extension` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/avathar/bbguild` folder.
## Community

Find support and more on 
*	Forums : [www.avathar.be/bbdkp](http://www.avathar.be/bbdkp)
*	IRC : [Freenode](https://webchat.freenode.net) #bbdkp

### contribute
You can see all the awesome people contributing to this project [here](https://github.com/avathar/bbguild/graphs/contributors).
1. [Create a ticket (unless there already is one)] : https://github.com/Sajaki/bbGuild/issues
2. [Read our Git Contribution Guidelines](http://www.avathar.be/bbdkp/viewtopic.php?f=60&t=1854); if you're new to git, also read [Git Primer](http://www.avathar.be/bbdkp/viewtopic.php?f=60&t=1853)
3. Make a pull request.

### License
[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

### Paypal donation
[![Foo](https://www.paypal.com/en_US/BE/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sajaki9%40gmail%2ecom&lc=BE&item_name=bbDKP%20Guild%20management&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)
