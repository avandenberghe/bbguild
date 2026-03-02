# bbGuild - Extension Analysis

## Overview

**bbGuild** is a Guild Management System for phpBB 3.3+ designed for World of Warcraft gaming communities. It provides guild roster management, character tracking, achievements, recruitment, and integration with the Battle.net API.

- **Author:** Andreas Vandenberghe (Sajaki)
- **Version:** 2.0.0-a10 (alpha)
- **License:** GPL-2.0-only
- **Repository:** https://github.com/avandenberghe/bbguild

## Project Status

| Metric | Value |
|--------|-------|
| First commit | May 28, 2010 |
| Status | Revival in progress |
| Tracking issue | [#303](https://github.com/avandenberghe/bbguild/issues/303) |

## Requirements

- phpBB >= 3.3.0
- PHP >= 7.4.0
- GD extension
- cURL extension

## Architecture

### Directory Structure

```
bbguild/
├── acp/                    # Admin Control Panel modules
├── controller/             # Request controllers
├── ucp/                    # User Control Panel modules
├── model/                  # Business logic
│   ├── admin/             # Utilities (curl, log, constants)
│   ├── api/               # Battle.net API client
│   ├── games/             # Game definitions + installers
│   ├── player/            # Player, guild, rank management
│   └── blocks/            # Display blocks
├── event/                 # Event listeners
├── migrations/            # Database migrations (5 files)
├── config/                # Services and routing (YAML)
├── styles/                # Templates
├── language/              # Localization (en, fr, de, it)
├── images/                # Game icons
├── contrib/               # Changelog, docs, diagrams
├── views/                 # View helpers
└── tests/                 # Unit tests
```

### Controllers

| File | Purpose |
|------|---------|
| `view_controller.php` | Frontend (welcome, roster, achievements) |
| `admin_main.php` | ACP panel, config, logs |
| `admin_games.php` | Game management |
| `admin_guild.php` | Guild management |
| `admin_recruit.php` | Recruitment |
| `admin_player.php` | Player management (empty) |
| `admin_achievement.php` | Achievement management |
| `ajax.php` | AJAX endpoints |

### Database Tables (20+)

**Core:** bb_games, bb_guild, bb_players, bb_ranks, bb_logs, bb_news, bb_motd

**Game Content:** bb_classes, bb_races, bb_factions, bb_gameroles, bb_language

**Recruitment:** bb_recruit, bb_bosstable, bb_zonetable

**Achievements:** bb_achievement, bb_achievement_track, bb_achievement_criteria, bb_achievement_rewards, bb_criteria_track

### Supported Games

World of Warcraft (Battle.net API), GW2, LOTRO, DAOC, Vanguard, EverQuest I/II, Warhammer, FFXI, AION, Rift, SWTOR, Lineage 2, TERA, FFXIV, Custom

### Routes

- `/guild/{page}/{guild_id}` - Main pages (welcome, roster, achievements)
- `/getfaction` - AJAX faction selector
- `/getguildrank/{guild_id}` - AJAX rank selector
- `/getplayerList/{game_id}` - AJAX player list
- `/getclassrace/{game_id}` - AJAX class/race selector

### Permissions

- `a_bbguild` - Admin access
- `u_bbguild` - User view
- `u_charclaim`, `u_charadd`, `u_chardelete`, `u_charupdate` - Character management

## Known Bugs

## Completed Fixes

- **#301** - "Module not accessible" after adding guild: typo `aavathar` → `avathar` in `acp/guild_module.php:85`
- **#299** - ACP achievement list error: wrong array index in `acp/achievement_module.php:265`, `$GuildAchievements[0]` → `$GuildAchievements[2]`
- **#298** - ACP region and UCP errors: added null guards in `model/api/battlenet_resource.php`
- **Permission label swap** - `u_chardelete` / `u_charupdate` labels were swapped in `event/main_listener.php`
- **phpBB 3.3 compatibility** - version gates, extension manager fallback, removed `$user->theme['template_path']`, fixed language loading
- **PHP 8.x null-safety** - added `(string)` casts in `util.php`, `battlenet_resource.php`, `ucp/bbguild_module.php`
- **DKP guard** - UCP DKP query wrapped in `defined('PLAYER_DKP_TABLE')` check

## Incomplete Features (Must Have)

- #290 - UCP bbguild page
- #288 - Individual player page
- #278 - Achievements pane

## Compatibility Status

### phpBB 3.3 - Done
- Version gates updated
- Extension manager container fallback added
- Removed references to `$user->theme['template_path']`
- Language loading fixed (`add_lang_ext` instead of `mods/`)

### PHP 8.x - Partially done
- Null-safety fixes applied to key files
- More files may need review for strict type issues

### Battle.net API - Not started
- API endpoints changed since 2016-2019
- OAuth 2.0 authentication updates needed
- Data structure modifications needed

## Modernization Path

### Phase 1: Core compatibility (in progress)
1. ~~Fix critical bugs (#301, #299, #298)~~ All fixed
2. ~~Update PHP syntax for 8.x compatibility~~ Done (key files)
4. ~~Test/fix phpBB 3.3 compatibility~~ Done

### Phase 2: Later
- Update Battle.net API integration
- Complete must-have features (#290, #288, #278)
- Add unit tests (#244)

## Key Files

- `ext.php` - Extension entry point, requirement checks
- `config/services.yml` - Dependency injection
- `config/routing.yml` - URL routes
- `event/main_listener.php` - phpBB event hooks
- `composer.json` - Package metadata
