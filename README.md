[![bbDKP](http://www.avathar.be/bbdkp/images/site_logo.png)](http://www.avathar.be/bbdkp)


## About

bbDKP is a ‘Dragon Kill system’ for your phpBB3 Bulletin board. 
It was originally forked from EQDKP to phpBB in 2008. the 2.0 version is rebuild on the Symfony 2 framework to be compatible with phpBB.

[![Build Status](https://travis-ci.org/bbDKP/bbDKP.svg?branch=develop-2.0)](https://travis-ci.org/bbDKP/bbDKP)
[![experimental](http://badges.github.io/stability-badges/dist/experimental.svg)](http://github.com/badges/stability-badges)

Features : 
- Integration of Game, Guild and DKP management facilities; 
- Single sign on to dkp and phpBB3, 
- Clean report screens, 
- Multiple loot distribution systems supported : Multi-pool, Standard DKP, Time based DKP, Zero sum, EPGP. 
- Portal with widgets
- Leaderboard, Statistics, Events, Raids, Member, Items, Roster
- ACP and UCP modules. 
- Games supported : 
  - World of Warcraft, 
  - The Lord of the Rings Online, 
  - Dark Age of Camelot, 
  - Vanguard, 
  - EverQuest I/II,  
  - Warhammer Online, 
  - Final Fantasy XI, 
  - AION, 
  - Rift, 
  - SW:Tor, 
  - Lineage 2, 
  - TERA, 
  - FFXIV 
  - and Custom Game. 
- Languages supported : English, French, German, Italian. 
- Robust admin features, no Installation headaches, just copy into ext folder and enable the extension.  

There will be a number of extension plugins, Raidtracker, bbTips, Apply, Bossprogress, Raidplanner. 

Further development requests, support questions are welcome at http://www.avathar.be/bbdkp or @bbDKP on Twitter.",
	
	
## Current

2.0.0-DEV

This code is not ready for testing yet...

## Installation

#### Install
1. Download the latest release.
2. In the `ext` directory of your phpBB board, create a new directory named `sajaki` (if it does not already exist).
3. Copy the `bbdkp` folder to `/ext/sajaki/` (if done correctly, you'll have the main extension class at (your forum root)/ext/sajaki/bbdkp/composer.json).
4. Navigate in the ACP to `Customise -> Manage extensions`.
5. Look for `bbDKP Extension` under the Disabled Extensions list, and click its `Enable` link.

#### Uninstall
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `bbDKP Extension` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/sajaki/bbdkp` folder.


#### Requirements
1.	phpbb > 3.1.*
2.	ftp and acp access to your phpbb forum.  

   
## Community

Find support and more on 

*	Forums : [www.avathar.be/bbdkp](http://www.avathar.be/bbdkp)
*	IRC : [Freenode](https://webchat.freenode.net) #bbdkp
*	Twitter @bbDKP

## Changelog 

2.0.0 06-12-2015
- [NEW] Conversion to extension

1.4.3 11-11-2015
- [FIX] #260 MODX update to 1.4.2 was incomplete

1.4.3 (prerelease) 08-11-2015
-  [FIX] #259 forgot to commit guildnews backend code to build new welcome page

1.4.2 (prerelease) 25-10-2015

-   [NEW] #259 new welcome page
-   [CHG] #258 migrate versions url from Googlecode 
-   [FIX] #253 missing language entries in german/french/italian
-   [NEW] #252 Implement new WOW API
-   [CHG] #249 allow two users from different realms in the same guild
-	[NEW] #248 2 new races were added in SWToR : Cyborg and Cathar. they should be included in the installer.
- 	[CHG] #247 moved all js to subfolder /template/dkp/js 
-	[NEW] #246 Added Sliders to statistics page, for easier navigation
-	[FIX] missing language entries in german/french
-   [NEW] #242 enable icon uploads in acp
-   [FIX] #241 roster acp should use outer joins
-	[NEW] #240 update Aion game parameters game 
-	[CHG] #239 added correct classpath to rpblocks class in class_exists checker in block factory
-   [CHG] #226 move role acp from raidplanner to bbdkp : new Roles & Recruitment class/table: The roles are now in a separate class and table. A new 'recruitment' class/table holds number of positions and applicants. the Apply plugin will update the recruitment table and so you have a live view of recruitments through the recruitment block. Also, Raidplanner will use the bbdkp roles table to fill in raidroles.
-	[CHG] #223 update constants file : deprecated GAMES_TABLE, LOGS_TABLE, EVENTS_TABLE constant updated to BBGAMES_TABLE, BBLOGS_TABLE, BBEVENTS_TABLE constants
-	[NEW] #212 added Italian translation
-	[NEW] #196 Implemented Guildwars Guild API
-	[NEW] #192 Can now unlink a character from UCP

1.3.0.8 01-07-2014

-	[FIX] #239 fix raidplanner block file path reference in blockFactory class

1.3.0.7 30-06-2014

-	[FIX] #238 points transfer acp is now multi-guild
-	[CHG] game class no longer inherits from admin class
-	[FIX] #234 sql queries for guild, ranks, games, classes, race, faction are cached 
-	[FIX] #237 Statistics page was redone. Graphics are now responsive, Flot library updated to 0.8.3
-	[CHG] #237 Standings page renamed to Leaderboard, attendance, raidcount columns fixed 

1.3.0.6 14-06-2014

-	[FIX] #229 issues in ucp 
-	[FIX] issue in updating from 1.2.8


1.3.0.5 07-06-2014

-	[UPD] #224 paths adapted to Planner 0.12
-	[FIX] #222 sql error in UCP: fields were missing in ACP and UCP, affected every game.  
-	[UPD] Updated SWTOR max. level to 55.  

1.3.0.4 18-05-2014

-	[FIX] #221 ucp call to members class was missing memberfilter argument 

1.3.0.3 17-05-2014

-	[NEW] add new log types for armory down error, Guild updated
-	[FIX] #220 refactored acp_dkp_mm by extraction into methods
-	[FIX] refactored acp_dkp_guild by extraction into methods
-	[FIX] “activated” checkbox in acp_mm_addmember.html
-	[removed]  “joindate” columns in member list acp
-	[NEW] last_update column in member list acp
-	[NEW] top search criteria in in member list acp now apply for armory update
-	[NEW] added DEACTIVATED_BY_API deactivation reason
-	[NEW] Character api limited to 100 calls per time and to members where last_update was > 15 minutes ago
-	[NEW] lock status if member disabled by API call
-   FIX] #213 if wow guild not found on armory, show message. 
-   [NEW] new member table columns: inactivereason, last_update; 
-	[CHG] #219 leaderboard only hows 5 biggest accounts per class. 
-	[CHG] #219 leaderboard class names aligned to class image
-	[NEW] #218 add member search to roster
-	[NEW] #218 add member search to standings
-	[NEW] #218 add member search to listmembers, listaccounts acp -	[FIX] #216 Refactor standings page, show all accounts
-	[FIX] #215 add pagination to standings page
-	[FIX] fixed count, shading in adjustment acp, 
-	[FIX] #214 add pagination to dkp account acp. 


1.3.0.2 01-05-2014

-	[FIX] #203 calling wow charapi did not deduce inactive points from account
-	[FIX] #204 raid value was not added to account for duplicated raid
-	[FIX] #205 need to show adjustments on account page
-	[FIX] #206 raid count was not increased for duplicated raid
-	[FIX] #207 missing language "SPENTNET"
-	[FIX] #208 issue in viewraid and pbwow. Page is nudged to high. 
-	[FIX] #209 total of raid value column should not have equal sign
-	[FIX] #210 rewrite points transfer function

1.3.0.1 24-04-2014

-	[FIX] #200 Module issue when updating 1.2.8->1.3.0. 1.3 Umil changes were merged into 1 diff, omitting all betas and RC. fixes fix #100, #200

1.3.0 21-04-2014

-   [FIX] #197 fix bossprogress & raidplanner url in viewnavigation
-   [FIX] #195 guild emblem acp field is now editable 
-	[FIX] In news block, news_row template variable changed to 'postrow' to enable calling bbtips to build wowhead links 
-	[FIX] bbtips class call is moved to viewnavigation
   
1.3.0-RC4 12-04-2014

-   [FIX] #187 fix custom game installer
-   [FIX] #190 restyling recruitment block acp
   
1.3.0-RC3 08-04-2014

-   [FIX] #186 fix hardcoded filenames

1.3.0-RC2 07-04-2014

-   [FIX] #184 fix german language file error

1.3.0-RC1 06-04-2014

-   [NEW] refactored and moved program logic to namespaced viewfactory, controller and domain classes.
-   [NEW] minimum requirement is now php 5.3.20
-   [NEW] new hooks, less core phpbb changes
-   [NEW] games installers moved to acp.
-   [NEW] added lineage2, ffxiv and custom games installer. updated other Game installers to latest info.
-   [NEW] multi-guild aware. ranks and recruitment integrated in new Guild acp.
-   [NEW] integrated Battle.NET Api, now also pulls guild emblem.
-   [NEW] enhanced portal
-   [NEW] Deathwing, Jade and pbWoW2 style mods included
-   [CHG] UMIL was removed from core. user needs to install it before. 

1.2.8-pl2 30-07-2012

-	[NEW] new 'new members' portal block , portal images moved to subfolder
-	[NEW] event frontpage redone
-	[NEW] event status field, you can no longer add raids to disabled events
-	[NEW] added dummy icon to viewevent if event has no associated image
-	[NEW] new event setting view all or only events with raids
-	[NEW] changing dkp pool of an event now resynchronizes all points to new pool
-	[FIX] installers now add events for gw2, tera, wow, swtor, Rift
-	[NEW] add list of games installed to mainpage acp
-	[NEW] class coloring for all games
-	[NEW] Everquest : new class & roster icons
-	[NEW] added Aion race images & default aion PVE event, set level cap to 60
-	[NEW] Tera added
-	[NEW] Guildwars2 added
-	[FIX] #68@github now correctly works with Mysql 5.5
-	[FIX] scheduled tasks for decay only launched if there are raids
-	[CHG] scheduled tasks no longer logged as user triggered
-	[FIX] #200 now correcly determines mysql version during upgrade
-	[FIX] #197 recent topics acp setting fixed
-	[FIX] dkpmain.htmldouble quotes removed


1.2.7 03-06-2012

-	[UPD] jquery updated to 1.7.2
-	[NEW] MSSQL support
-	[NEW] postgresql support
-	[SEC] new permissions for UCP
-	[NEW] new UCP styling
-	[NEW] new wow monk classes and Pandaren race
-	[NEW] lotro Subnationalities added to races
-	[NEW] new icons for Rift, Vanguard
-	[NEW] added class colors for Rift, warhammer, lineage
-	[NEW] race images renamed (removed '_small)
-	[NEW] Plugin manager by Aerwin


1.2.6 12-03-2012


-	[UPD] jquery updated, compatibility mode added for use with mootools, uses "jquery" instead of "$" ...
-	[UPD] wow class images have a better icon
-	[FIX] News module now inserts line breaks properly (not as /n/n)
-	[FIX] updated file headers for Github move
-	[FIX] pagination fixed in viewmember. cumulated earnings/items now paginating correctly
-	[FIX] roster listing redesign, pagination, sorting and minimum level added
-	[FIX] standing pagination added
-	[FIX] removed hardcoded language in bbdkp credits footer
-	[FIX] German language fixes (@killerpommes)
-	[FIX] frontpages XHTML 1.1 compliant.
-	[FIX] inline css in stats.html moved to bbdkp.css
-	[FIX] statistics page attendance array sorting headers corrected
-	[UPD] Updated standings and viewmember for new adjustment decay
-	[UPD] swtor installer script now uses races for specialisation.
-	[UPD] new icons for LINEAGE2 (@sh1ny)
-	[UPD] DKP character info in in viewtopic.
-	[NEW] Removed any remaining hardcoded strings
-	[NEW] Membernames now unique by combination with guildname/realmname.

	Portal
    
-	[UPD] Links block updated
-	[FIX] functions_display inclusion bug fixed
-	[NEW] Recent topics Block
-	[NEW] REIMG Image Resizer compatible. just install Remg 2.0.1

	ACP

-	[FIX] Added game id to html form in race acp. this fixes a few reported problems
-	[NEW] Added the ability to have the date format be set by the ACP (@TheGadgetGuy Aerwin)
-	[FIX] w3c fix : if no data in item, raid, member listing then don't show table.
-	[FIX] raid duplication icon changed
-	[FIX] item acp reprogrammed from scratch, using master detail view, with new ajax item searcher
-	[FIX] javascript changes, now references id instead of element name
-	[FIX] ranks support special characters (#152)
-	[FIX] news & recruitment forum selector in indexpage config now works
-	[FIX] welcomeblock on/off switch now no longer targets recruitment block
-	[FIX] welcomeblock parses bbcodes
-	[NEW] DKP logging now shows phpbb membername
-	[NEW] Adjustment Decay with on/off switch per adjustment
-	[NEW] bbDKP cron job scheduler to automate decay calculation
-	[NEW] Minimum EP added
-	[FIX] ACP XHTML 1.1 compliant.
-	[NEW] Mass member delete added
-	[FIX] member delete button to the right
-	[NEW] Welcome block setting in ACP
-	[FIX] Member activation checkbox wasn't behaving


1.2.5 20-11-2011

-	[FIX] css fixes in the portal
-	[FIX] the acp front view now shows the membername instead of the id
-	[UPD] jqt 1.2.6 (from dev)
-	[NEW] member mass delete function in ACP
-	[UPD] links block update
-	[FIX] several blocks included functions_display. wrapped in function_exists
-	[FIX] Restored the dkpheader in overall_header instead of dip/dkpmain.html
-	[NEW] portal welcome block, with ACP
-	[NEW] Lineage II support
-	[NEW] better itemlisting browser with master-detail grid
-	[NEW] Raid Duplication function. this will not duplicate loot.
-	[NEW] new permissions for UCP added, default DKP permissions widened so that members get to see DKP by default.
-	[NEW] new UCP module for adding/updating or deleting own characters.
-	[NEW] DKP Synchronisation function added. in ACP.
-	[NEW] New event images for Firelands
-	[NEW] Roster now has Battle.NET portraits
-	[NEW] Roster now only shows active members.
-	[NEW] Added the rank in the Roster list view
-	[NEW] new parameters added for Max characters allowed per user, max number of members shown in Memberlisting.
-	[UPD] French/german language files updated
-	[FIX] leaderboard html class fix
-	[FIX] fixed pagination, sort order in attendance Stats
-	[FIX] Pagination fixes in raid, member acp and front modules
-	[FIX] About.html pointed to an old jquery file, donator list updated (https://github.com/bbDKP/bbDKP/commit/051f45f940)
-	[FIX] game_id now correctly stored when creating character (https://github.com/bbDKP/bbDKP/commit/c68f7a7)
-	[FIX] fixed bug 129. In phpbb member ACP account pulldown, the guests and bots are excluded, and it is ordered by username now. (https://github.com/bbDKP/bbDKP/commit/4658960)
-	[FIX] fixed bug 130 where item name was not retrieved from pulldown when adding loot manually.(https://github.com/bbDKP/bbDKP/commit/41dfc4af5)


1.2.4 18-09-2011

-	[DEL] Subsilver2 removed from package.
-	[NEW] jquery plot library in Statistics page, replaces pstat
-	[CHG] dkpheader is now included in dkpmain.html so no overall_header edit necessary
-	[NEW] default event icon added, wow event icons reduced to 64px (other event icons available as separate download)
-	[UPD] jquery updated to 1.6.2, jquery tools updated to 1.2.6-dev. This should solve This is needed for Raidplanner. A final jqt version is not available yet


1.2.3 09-06-2011

-	[NEW] Roster redone
-	[NEW] ucp permission
-	[NEW] statistics page redone, graphs added for Priority ratio, Attendance
-	[NEW] front end files removed, moved to /includes, and recoded as modules loaded from one accesspoint, dkp.php. Advantage is that there is less code, since the user access and session is now controlled from dkp.php.
-	[NEW] html style files are now included from 1 framework html file, with div panes, allowing future inclusion pf sidepanels
-	[NEW] news acp module removed, posting functions moved to new front-end rules/newspage.
-	[MODDB] w3c validation passed for all pages
-	[NEW] New games included : Rift and Swtor (Star Wars The old republic)
-	[NEW] Multi game support : you can install multiple games at once (wow, aion, swtor, eq, eq2, rift, lotro, vanguard ...)
-	[NEW] Event icon support added for Raidplanner, new plugin hooks.
-	[NEW] new event icons for Cataclysm, Wotlk


1.2.2 09-04-2011

-	[MODDB] removed config.php core edits and added new file configdkp.php (see bug #67724)
-	[DEL] removed reference to bossprogress block file
-	[DEL] full guild bank support in case of zero sum. (see bug #112, commit #14d9bf4)
-	[FIX] subsilver member list membername/class column switched
-	[FIX] leaderboard image path
-	[FIX] roster standard mode fix
-	[FIX] will reinstall Cata language table changes if user added them manually


1.2.1.1 03-04-2011 HOTFIX (see commit #a38e448 )

-	[FIX] correct guild deletion button
-	[FIX] set default GP to 0 instead of 100 at installation


1.2.1.0 30-03-2011 HOTFIX (see commit #4fc3b32 )

-	[FIX] added a nowrap in editraid, the icons in the itempane did not line up
-	[FIX] fixed the attendance functions
-	[FIX] renamed the death knight class icon for the roster


1.2.1 27-03-2011

-	[DEL] moved Bossprogress to plugin
-	[FIX] added missing sql_freeresult in event acp.
-	[FIX] added


1.2 25-03-2011

-	[NEW] redone table style in front pages
-	[NEW] Delete button in Listmember ACP
-	[NEW] css for positive and negative amounts
-	[NEW] new icons in bossprogress page
-	[NEW] front pages, acp pages all adapted to take new join rules and loot systems
-	[NEW] new $config values added for loot systems, settings added to main ACP epgp and zerosum cannot be combined. added synchronisation buttons.
-	[NEW] Loot systems Time bonus, Zerosum, EPGP, Item and Raid Decay
-	[NEW] Edit Raid ACP
-	[NEW] listmembers page now updates url correctlywhen selecting values from pulldown
-	[NEW] added switch in member acp to show/hide members on roster
-	[NEW] image added to race table and ACP, and also in all front and backend pages
-	[NEW] memberid, itemdecay, zerosum columns added to raid_items table
-	[NEW] item_dkpid, item_buyer removed from raid_items table
-	[NEW] raidvalue, timebonus, zerosumbonus, raiddecay columns added to raiddetail table
-	[NEW] raidvalue, timebonus, zerosumbonus, raiddecay, itemdecay columns added to member dkp table
-	[NEW] membername column removed from raiddetail table (ex attendees). member_id is enough.
-	[NEW] Cataclysm changes added in wow installer
-	[CHG] Classid is now the foreign key for the language table.
-	[CHG] event_id added to Raid table as foreign key to events table, and raid_end
-	[CHG] raid_dkpid, raid_value and raid_name removed from raid table. that info is now located in detail.
-	[CHG] image and color added to event table and acp.
-	[NEW] New events can now be set up using the data from the Bossprogress Zone
-	[NEW] added field in member table and member ACP to link guildmember to phpbb account. overrides ucp
-	[NEW] character profiler ucp so you can sign up to raidplanner
-	[CHG] portal.php no longer hides navbar
-	[CHG] the installer will now run installdkp.php for any new install; all previous release changes were moved to updatedkp.php
-	[CHG] tabe prefix changed to default phpbb_ for MOD DB validation
-	[CHG] simplified bossprogress and zone panel
-	[FIX] bugtracker #83, now the guild is pre selected as default
-	[FIX] bugtracker #82, now can rename zones


1.1.2.2 17-10-2010

-	[FIX] Zoneprogress detail acp did not load showzone config
-	[FIX] bp block tooltip now shows correct bottom corners
-	[NEW] bp block progressbar (prosilver only)
-	[NEW] bp block progressbar (prosilver only), with acp toggle off/on
-	[UPD] bp checkboxes graphics update
-	[FIX] bugtracker #70 recruitment block image no longer dependent on language
-	[FIX] bugtracker #72 viewraid now configured to read language tables correctly
-	[FIX] bugtracker #73 roster now reads language table for retrieving icon names
-	[NEW] [new] enhanced recruitment block (@author Blazeflack)
-	[UPD] jquery tools updated
-	[upd] adding classcolor and icon to list and viewitem, viewraid, stats frontpages
-	[fix] language translation corrections


1.1.2.1 27-09-2010

-	[FIX] Zoneprogress/Bossprogress ACP show/noshow settings were not taken into account in the bossprogress block and page. font size reduced in block.
-	[FIX] Installer did not correctly delete old bossprogress modules

1.1.2 26-09-2010

-	[NEW] Zoneprogress/Bossprogress ACP lets you create zones/bosses, No php file manipulation is necessary anymore, bb_zonetable and bb_bosstable
-	[REMOVED] Bossprogress language files were removed so they reside now in the database in new bb_language table
-	[NEW] Bossprogress portal block recoded. now has a popup with boss progression and is based on the new backend.
-	[NEW] Bossprogress frontpage based on the new backend.
-	[NEW] Multi language : French and German added as a second language.
-	[NEW] Faction, Race and Class edition in ACP
-	[CHANGE] class colors removed from bbdkp.css, they are now defined in the database and editable in ACP with a colorwheel
-	[NEW] 3darmory view in viewmember.php
-	[CHANGE] less ACP modules. all 'add' modules moved to list layout.
-	[FIX] raid ACP date selection now with a popup
-	[FIX] member dkp can now be deleted without error
-	[UPD] umil updated to 1.03


1.1.1 17-07-2010

-	[NEW] Ruby Sanctum and Halion pictures for Bossprogress
-	[FIX] attendance label and percentage percent fixed in viewmember
-	[FIX] the stats, items, and raid list page now show the default dkp pool instead of all.
-	[NEW] u_dkp user permission on frontpages, so now unallowed users will be forwarded to the portal when they're not in allowed usergroup.
-	[FIX] Raid date was ignored when entering or updating a raid manually
-	[UPD] Lotro game level is updated to 60
-	[NEW] makes distinction between heroic and normal items: loot block, listitems, viewitem, viewmember, viewraid pages now pass the itemid to bbTips when it is present in the item table
-	[NEW] item game item ID can be entered in the Item ACP when adding a new one
-	[NEW] acp list items normal/heroic mode. will now use game_id tag if it is > 0 instead of item name to look up in bbTips
-	[NEW] portal menu block on/off switch in ACP
-	[NEW] 1.0.8 updater
-	[FIX] 1.0.9rc1 updater is now more edge case friendly
-	[NEW] project logo visible in installer
-	[CHG] the portal is now delivered in two panes as standard. the left column is set to 0 width but you can change this in your css.


1.1.0 25-05-2010

-	[FIXED] Removed all hardcoded language from html and php
-	[FIXED] changed language files to uppercase, reorganised descriptions
-	[FIXED] removed L_ references from php files
-	[NEW] Added updater from 1.0.9rc1
-	[FIX] reduced installer complexity, combined RC1-RC2 changes
-	[CHANGE] All overall_header script inclusion moved to dkp_header.html, which has anchors for each plugin. jquery scripts are in this file now.
-	[NEW] Added plugin anchors in constants file
-	[NEW] recoded the Items, Raid ACP
-	[NEW] recruitment block now only shows classes that are open
-	[NEW] new bossprogress block with progress bars
-	[NEW] new bossprogress excel spreadsheet to facilitate config of new bosses
-	[NEW] backported new request_var function from phpbb 3.1 to 3.0 to allow Raidtracker to treat deep arrays
-	[UPD] updated UMIL to 1.0.2
-	[UPD] added form tokens in acp submit forms when no confirm box was present
-	[FIX] Fixed outstanding tickets


1.1.0-RC3 7-02-2010

-	[NEW] breadcrumbs menu navigation
-	[NEW] whoisonline block in Portal
-	[FIXED] ticket 9 guild id 1 cant be deleted anymore (http://www.bbdkp.com/viewtopic.php?f=54&t=1358#p6093)
-	[FIXED] ticket 8 logging, removed {}, no longer necessary to use mb_ereg_replace in function getaction
-	[FIXED] ticket 7 removing raidmembers didnt get deleted (svn 932-933)
-	[FIXED] ticket 5 : removing a guildmember would cause an error
-	[FIXED] ticket 4 (date zone checks) r906
-	[FIXED] ticket 3 guildname is now max 255 chars


1.1.0-RC2 24-01-2010

-	[NEW] about box now done with jQueryTools
-	[NEW] lotro bossprogress
-	[NEW] EQ2 bossprogress tier 8
-	[FIXED] tracker issue 2 :Addmember ACP rank dropdown, now updates when guild dropdown value changed, added ajax js
-	[FIXED] Member Raidattendance pct will now correct for members who joined within attendance period. new ratio = (memberattendance count within 30/90 days) / (number of raids within 30/90 days or since member joined)
-	[FIXED] RC1 member_ranks installer
-	[FIXED] Item search ACP
-	[FIXED] guild add ACP

1.1.0-RC1 17-01-2010

-	[FIX] listmembers now supports composite sorting on all columns
-	[NEW] Portal rewritten, now supports paging
-	[FIXED] bbdkp is now compatible with PHP 5.3.0
-	[NEW] Aion support
-	[NEW] Eq2 bossprogress till Kingdom of Sky
-	[NEW] Wow bossprogress till Icecrown
-	[NEW] Multiple guilds
-	[NEW] Itemstats removed, it is now a plugin (bbTips)
-	[NEW] Leaderboard works for all games
-	[NEW] Roster works for all games, but only has player portraits for Wow & Aion
-	[NEW] Player Stats page works for all games
-	[NEW] Database table backup, uses phpbb functionality
-	[NEW] Installer rewritten using UMIL class
-	[SPLIT] Game installer files are separate now
-	[CHANGED] Armoryplugin, Ctrt, Apply installer moved to plugin Installers using Umil.
-	[FIXED] includes all patches up to RC1 Patch 10, ensured all html is XHTML validated


1.0.9
-	not released 

1.0.9 RC2

-	not released 


1.0.9 RC1 04-2009


-	[CHANGE] changed roster table to reflect new blizzard xml
-	[CHANGE] Moved phpbb_itemstats inclusion to common.php to minimize patching.
-	[FIXED] removed all stripslashes to comply with coding guidelines because addslashes is not used since we use the phpbb mysqli_real_escape_string() wrapper
-	[FIXED] Warcraft leaderboard will now not show when another game is installed
-	[FIXED] Added css to change indexpage width to 100%
-	[NEW] Added bbdkp killswitch in config.php
-	[NEW] Bossprogress for Wow updated to Ulduar (thanks to Orshee for Ulduar sql)
-	[CHANGED] eqdkp validation class replaced by phpbb
-	[CHANGED] log system now uses xml format, no longer eval
-	[CHANGED] installer and updater now require you to be logged as founder
-	[NEW] now supports Automod (tested on v1.0.0b2)
-	[FIXED] php files now follow javadoc documentation guidelines
-	[CHANGED] link section removed in install.xml and all bbdkp plugins moved to their own package.
-	[FIXED] roster membercount
-	[CHANGED] moved dkp link query in to global $config (thanks DorEli)

1.0.9 Beta 4 02-2009


-	[CHANGED] changed variable constants from common.php to $config array, so less lines to edit in common.php
-	[CHANGED] moved /includesdkp/bossprogress to /includes/bossprogress
-	[CHANGED] rewritten bossprogress block, no more include errors.
-	[FIXED] corrected raid log bug in bossprogress (AnubRekhan - svn 292 - thanks digitaldew)
-	[ADDED] Armory character import added
-	[ADDED] Armory multiple guilds import added
-	[CHANGED] new Roster template per class
-	[CHANGED] new About.php
-	[CHANGED] moved logic in /includesdkp/komptab.class.php to about.php, since this is pure html.
-	[CHANGED] moved html part of about.php to /styles/stylename/template/dkp.about.html because of code clarity
-	[CHANGED] dropped bbeqdkp_config table and moved values to phpbb_config. the /includes/cache.php doen‚Äôt have to be patched anymore.
-	[ADDED] new file /includes/bbdkp/constants_bbdkp for storing all constants. this is cleaner than having to patch it all in common.php
-	[CHANGED] changed all root pathes to $phpbb_root_path = (defined(‚ÄôPHPBB_ROOT_PATH‚Äô)) ? PHPBB_ROOT_PATH : ‚Äò./‚Äô;
-	[CHANGED] replaced all $SID with append_sid() function (no more logouts if cookies are disabled!!!)
-	[FIXED] fixed pagination on viewmember.php, listitems.php, listmembers.php
-	[CHANGED] mpv validation tool changes : changed file endings to LF, removed center tags to Style align, used ‚Äú&‚Äù instead of plain ‚Äú&‚Äù, eliminated tags, moved them to a style section, fixed nonclosing UL and LI tags
-	[CHANGED] added IN_PHPBB checks
-	[FIXED] fixed the /adm/adm ACP link bug (due to forgotten braces in in /includes/functions.php patch)
-	[CHANGED] moved /itemstats to /includes/bbdkp/itemstats. changed all /include paths
-	[DELETED] itemstats sqlhelper.php is now deleted, since it uses the $dbal class from phpbb. all query calls have been changed to reflect this.
-	[DELETED] No more Itemstats/config.php since database connection is now handled through phpbb.
-	[CHANGED] new itemstats ACP to change providers and other preferences. no more editing config files
-	[CHANGED] permissions_phpbb.php doesn‚Äôt have to be patched anymore since there is a new permissions file now in /language/en/mods
-	[FIXED] and some other bugfixes


1.0.9 Beta 3 28-12-2008


-	[UPDATED] - Listmembers/leaderboard + stats redone - details see svn 226,230,241
-	[ADDED] - Subsilver2 support (svn 216,217,239)
-	[ADDED] - new ACP eqdkp config for attendance days : can change from 30-90 to something else (svn 228
-	[FIXED] - Item dkp price update, details see svn r227, r237
-	[FIXED] - listitems itemstats popup, itemstats config - details see svn r237
-	[FIXED] - Ctrt : import defaults to UTF-8, "dkp invalid string" when too long, Ctrt prefs options changed - details see svn r229,r237,r238
-	[FIXED] - Ctrt : Death Knights were created as 'unknown' with class id 0 (svn 231,238
-	[updated] - member transfers in ACP (svn 233,235,236
-	[FIXED] - header sorting in Listmemberdkp ACP (svn 234)
-	[FIXED] - armory transfer realms with apostrophes (svn 232)
-	[ADDED] - min. level armory transfer (svn 232,240)
-	[ADDED] - Apply : mandataory functionality added (svn 223,224,225)


1.0.9 Beta 2 15-12-2008

-	[FIXED] issue in armory parser (svn 205-206)
-	[FIXED] issue with updater and class_id =0 (svn 205-206)
-	[FIXED] issue in armory downloader (svn 204
-	[FIXED] added checks in apply plugin (svn 203)
-	[FIXED] issue with itemstats in listitems.php and viewmember.php (svn 200)



1.0.9 Beta 1 14-12-2008

-	[ADDED] - Wotlk update
-	[CHANGED]- DKP ACP Module link in Menu is now dynamic, no longer fixed to 190
-	[CHANGED]- bbDkp installer rewritten : plugin and bbdkp in one
-	[ADDED] - multiple dkp pools are now implemented. Dkp pool popup added to Stats, Listmembers, Items pages, and in ACP
-	[CHANGED] - Database structure : a new indexed field for DKP pools was added in transaction tables
-	[CHANGED] - Database structure : new tables for Member DKP, Member List, DKP Pools.
-	[ADDED]- bbDkp automated DB bbdkp and plugin updater from 1.08 to 1.09
-	[CHANGED] - Member screen allows keeping notes, guild entry and leave dates, based on armory import.
-	[CHANGED] - Leaderboard is now stretched over 2 lines (5+5) because 10 classes on one row became too cramped.
-	[CHANGED] - CTRT : updated for multiple dkp
-	[UPDATED] - Bossprogress : Added support for Eq2: Boss/instance arrays are in eq2_data.php,
-	[UPDATED] - Bossprogress: updated lotro_data.php with data from Annuminas to Carn Dum
-	[UPDATED] - Bossprogress : bossdate.php is complete with Wow, Eq2 and Lotro
-	[CHANGED] - Bossprogress : Installer will write offset data for all Eq2, Wow, Lotro Raids.So this means
    that all games are now supported, just need the right boss information, for Warhammer, FFXI, eq & others, i put dummy data.
-	[UPDATED] - Bossprogress : refreshed boss instance pictures for Wow, added Wotlk images.
-	[CHANGED] - Bossprogress : progressblock colour is now css based
-	[UPDATED] - Roster : Now shows different icons for class 70 / 80
-	[ADDED] - Roster : Deathknights added as class
-	[CHANGED] - Roster : acp redone : Transfer to Members Table tab will Insert new, Update existing and Set
    old members to inactive. You can also Edit Guildrank names and set some ranks to 'Hidden'
-	[UPDATED] - Recruitment block : Updated icon, added Death Knights.
-	[FIXED] - Recruitment block : now supports spaces, Hyphens removed in name.
-	[ADDED] - Application plugin : Posts a post in a forum specified in the ACP with the answers to the
    questions that can be added in the ACP. Also supports getting information from the armory like race, class, talents, professions.


1.0.8 Beta 5 revision 72 23.9.2008

-	[ADDED] - raidprogress block added


1.0.8 Beta 5 revision 60>70 15.9.2008

-	[FIXED] - correction in template footer
-	[FIXED] - copyright links in install process wasnt documented
-	[FIXED] - Bossprogress Kiljaeden ID was wrong
-	[FIXED] - ctrt importing raids with ' sign in eventname


1.0.8 Beta 5 revision 59 x.7.2008

-	[FIXED] - Intall process is redone, core phpbb3 files removed from bbdkp distribution
-	[NEW] - Itemstats FR/DE support
-	[FIXED] - viewmember.php item pagination is fixed
-	[FIXED] - Bossprogress showed Halazzi as Janalai kills and viceversa
-	[ADDED] - Roster shows level


1.0.8 Beta 4 - 25.4.2008

-	[ADDED] - Leaderboard under the standings page as well as Class Colors. :D
-	[ADDED] - Enhanced Recruitment Block (can now specify spec's, like Shadow Priest, Enhance Shamans, Fury Warriors etc...)
-	[ADDED] - Instructions on how to add [item] [/item] buttons to phpbb3 forums.
-	[ADDED] - "DKP" text & link to language file (now it can be multi-lingual) as well as many other variables to increase multi-lingual support :D .
-	[ADDED] - "Forum Index" link to both templates (so theres both an "Index" and "Forum Index" in the menus for easier navigation.
-	[ADDED] - Added SQL statements to index.php to make more forum data available for use (login times etc).
-	[ADDED] - Event & Raid Triggers for CTRA.
-	[ADDED] - Install Folder for plugins/main install for added security and vulnerabilities that can arise when files are not deleted after install.
-	[UPDATED] - CTRT Updated 1.16.8.
-	[UPDATED] - Bossprogress for Zul'Aman, and Sunwell
-	[UPDATED] - Raidnote/event triggers for ZA/Sunwell (SW Untested).
-	[UPDATED] - Project/Team Information Information.
-	[UPDATED] - Armory plugin now inserts ranks, and removes members who leave/quit/kicked from the guild.
-	[UPDATED] - Functions.php to work with phpbb3 3.0.1 updated
-	[UPDATED] - Updated Wowhead image directory for Itemstats
-	[CHANGED] - Changed the way you install/update plugins. Plugins are no longer installed on standard default installation of bbDKP.
-	[CHANGED] - WoW is the Default install for bbDKP (Mainly concerns items stats, But you can easily edit config files to change it to LOTRO.
-	[CHANGED] - When clicking the "Admin" button under the DKP section it will automatically take you to the DKP tab of the ACP.
-	[FIXED] - Raid Note Parsing error for CTRT when using an apostrophe.
-	[FIXED] - Main Installer updated, fixed syntax problem.
-	[FIXED] - Itemstats fix, wowhead default and function itemstats_decorate_name2($name, $buyer, $price, $class) is back alive.
-	[FIXED] - Fixed CTRT import error. (returning 0 no matter what).
-	[FIXED] - CTRT now works with special characters so that name like "Ani√´r", and works as intended.
-	[FIXED] - Roster support names like "Ani√´r", and should link to armory correctly
-	[FIXED] - Now can scroll through items if the users have more than X amount of items looted to them(20 is default).
-	[FIXED] - function GetRankIdByRankName.
-	[FIXED] - all mySQL tables should now be utf8 (including plugins).
-	[FIXED] - UCP auth fix.
-	[FIXED] - The ACP check for newest version should be less sensitive to test version being tested. and not always saying that a new version is out when there is not public version out.
-	[REMOVED] - dkp/overall_header_menu.html and dkp/overall_footer.html .Now you just use your standard template overall_header.html overall_footer for your new themes. :idea: :D
-	[REMOVED] - "Who is Online" menu box.
-	[REMOVED] - All old references to "phpbb3 + eqdkp Integrated" as project name, with the new project name of "bbDKP"


1.0.8 beta 1-3 - x.x.2007

-	installer
-	templates moved /dkp
-	eq, eq2, lotro, DAoC, Vanguard-SoH support


1.0.7 - 16.7.2007

-	php4 support to roster
-	bossprogress image update
-	plugin ctrt-1.16.3 (first test version


1.0.6 - 28.6.2007

-	Wowhead support
-	About page
-	DB fix: armory member_name from 30 to 100


1.0.5 - 10.6.2007

-	Hotfix


1.0.4 - 10.6.2007

-	Bossprogress first version
-	Attendees Parse Log
-	many fixes


1.0.3 - 23.5.2007

-	armory plugin fix


1.0.2 - 23.5.2007

-	armory plugin is now beta =), please give feedback
-	new table to DB PREFIX_armory_settings
-	add item is showing a blank page error fixed
-	add raid is showing a blank page error fixed
-	mm_ranks bug fixed
-	prosilver template fix


1.0.1 - 20.5.2007
-	add/update raid function update_player_status fixed
-	prefix changed eqdkp -> bbeqdkp
-	prosilver templates edited
-	adj manager links fixed 1.0.0


18.5.2007

-	first release
    

## contribute

You can see all the awesome people contributing to this project [here](https://github.com/bbdkp/bbdkp/graphs/contributors).

1. [Create a ticket (unless there already is one)] : https://github.com/bbDKP/bbDKP/issues or http://www.bbdkp.com/tracker.php
2. [Read our Git Contribution Guidelines](http://www.bbdkp.com/viewtopic.php?f=60&t=1854); if you're new to git, also read [Git Primer](http://www.bbdkp.com/viewtopic.php?f=60&t=1853)
3. Send us a pull request

## License

[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

This application is opensource software released under the GPL. Please see source code and the docs directory for more details. Powered by bbDkp (c) 2009 The bbDkp Project Team bbDkp
If you use this software and find it to be useful, we ask that you retain the copyright notice below. While not required for free use, it will help build interest in the bbDkp project and is required for obtaining support. 
bbDKP (c) 2014 Sajaki, Killerpommes, frederikkunze, Cecilius
bbDKP (c) 2011 Sajaki, Blazeflack
bbDKP (c) 2008, 2009 Sajaki, Malfate, Kapli, Hroar
bbDKP (c) 2007 Ippeh, Teksonic, Monkeytech, DWKN
EQDkp (c) 2003 The EqDkp Project Team 

## Paypal donation

[![Foo](https://www.paypal.com/en_US/BE/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sajaki9%40gmail%2ecom&lc=BE&item_name=bbDKP%20Guild%20management&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

