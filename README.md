[![bbGuild](https://www.avathar.be/forum/images/site_logo.png)](https://www.avathar.be/forum)

### About
bbGuild is a World of Warcraft Guild manager for your phpBB3 Bulletin board. It was originally forked as bbDKP from EQDKP to phpBB 3.0 in 2008.
The 2.0 version is renamed bbGuild and rebuilt for phpBB 3.3.

Features:
- Guild Roster
- Guild News page
- WoW Armory link
- ACP and UCP modules
- Languages supported: English, French, German, Italian

See contrib/CHANGELOG.md for upcoming and past changes.

Further development requests, support questions are welcome at our [Support Forum](https://www.avathar.be/forum) or in the [phpbb.com extension development topic](https://www.phpbb.com/community/viewtopic.php?f=456&t=2258141)

### Installation
##### Requirements
1. phpBB >= 3.3.0
2. PHP >= 7.4.0
3. PHP Client URL Library (php_curl)
4. PHP GD library (php_gd2)

##### Install
1. Download the latest release.
2. In the `ext` directory of your phpBB board, create a new directory named `avathar` (if it does not already exist).
3. Copy the `bbguild` folder to `/ext/avathar/bbguild/` (if done correctly, you'll have the main extension class at (your forum root)/ext/avathar/bbguild/composer.json).
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Look for `bbGuild` under the Disabled Extensions list, and click its `Enable` link.

##### Uninstall
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `bbGuild` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/avathar/bbguild` folder.

## Community

Find support and more on
* Forums: [https://www.avathar.be/forum](https://www.avathar.be/forum)

### Contribute
You can see all the awesome people contributing to this project [here](https://github.com/avandenberghe/bbguild/graphs/contributors).

1. [Create a ticket](https://github.com/avandenberghe/bbguild/issues) (unless there already is one)
2. Make a pull request.

### License
[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)
