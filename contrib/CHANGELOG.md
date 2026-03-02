# Changelog

## 2.0.0-a10 02/03/2026
  - [FIX] phpBB 3.3 compatibility: updated version gates in ext.php and composer.json
  - [FIX] PHP 8.x compatibility: null-safety casts in util.php, battlenet_resource.php, bbguild_module.php
  - [FIX] Extension manager fallback via container in game.php for phpBB 3.3
  - [FIX] Bug #298: guard against undefined region in battlenet_resource.php
  - [FIX] Swapped permission labels for u_chardelete and u_charupdate in main_listener.php
  - [FIX] UCP language loading: replaced removed mods/ path with add_lang_ext()
  - [FIX] UCP removed $user->theme['template_path'] references (removed in phpBB 3.3)
  - [FIX] UCP DKP table constants guarded for when bbDKP is not installed
  - [CHG] Minimum requirements: PHP >= 7.4.0, phpBB >= 3.3.0
  - [CHG] License identifier updated to GPL-2.0-only (SPDX)
  - [CHG] Updated author email and repository links

## 2.0.0-a9 26/08/2018

## 2.0.0a7 not released
  - [FIX] ext.php fixed.
  - [FIX] guild_module.php fixed. guild object was created too late.

## 2.0.0a6 not released

## 2.0.0a5 27/03/2016
  - [NEW] Front Page design updated to look like Blizzard Armory
  - [NEW] WoW emblem generator now makes 200px emblems
  - [NEW] GW2: initial support for GW2 api, added GW2 Revenant profession
  - [NEW] WoW: added Demon hunter class
  - [CHG] Faction id now added to Guild class. Can be set on Guild creation.
  - [NEW] Default game setting added.
  - [FIX] Roster member filter now works

## 2.0.0a4 13/03/2016
  - [NEW] Guild news page added, Blizzard news feed data

## 2.0.0a2 21/02/2016
  - [NEW] Viewcontroller is now done, with a first frontpage: the guild roster.

## 2.0.0a1 not released
  - [NEW] Conversion to extension
  - [CHG] Functionality reductions: DKP no longer part of core.
