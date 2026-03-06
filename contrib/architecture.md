# bbGuild Architecture

## Overview

bbGuild is a guild management extension for phpBB 3.3+. It provides guild roster management, character tracking, achievements, recruitment, a portal system, and Battle.net API integration for World of Warcraft communities.

- **Vendor namespace:** `avathar\bbguild`
- **Entry point:** `ext.php`
- **Requirements:** PHP >= 7.4, phpBB >= 3.3, GD, cURL

## Directory Structure

```
bbguild/
├── acp/                    # ACP module definitions (*_info.php + *_module.php)
├── config/                 # DI services, routing, table parameters (YAML)
├── controller/             # Request controllers (ACP + frontend)
├── event/                  # phpBB event listeners
├── language/               # Localization (en, de, fr, it, nl, es_x_tu, pl)
├── migrations/             # Database migrations
│   ├── basics/             # Initial install (schema, data, config, permissions, modules)
│   └── v200b1/             # 2.0.0-b1 (portal data, ACP module, release stamp)
├── model/                  # Business logic and data access
│   ├── admin/              # Utilities: curl, log, constants, util
│   ├── api/                # Battle.net API client
│   ├── blocks/             # Display block helpers
│   ├── games/              # Game registry, abstract installer, custom game
│   └── player/             # Player, guild, rank models
├── portal/                 # Portal block engine
│   └── modules/            # Module infrastructure + built-in modules
├── styles/                 # Twig templates (prosilver)
├── ucp/                    # User Control Panel modules
├── views/                  # View helpers (guild_context)
├── images/                 # UI assets (emblems, icons, progressbar)
├── contrib/                # Documentation, diagrams, changelog
└── tests/                  # Unit tests
```

## Frontend Architecture (Portal-First)

The frontend uses a portal-first, single-page architecture:

1. **`view_controller::handleview()`** receives the request
2. **`guild_context`** resolves the guild, loads guild data, and assigns header/dropdown template vars
3. **`portal_renderer`** renders all enabled portal modules (MOTD, Roster, News, Recruitment, etc.)
4. **`main.html`** contains the guild header (normal flow) and includes `view/welcome.html` (portal renderer template)

All content is rendered as portal modules on a single page — there are no separate "welcome" and "roster" pages. The roster is a portal module (`portal/modules/roster.php`) displayed in the center column with filters and pagination.

### Template Structure

```
main.html
├── Guild header (name, faction, realm, member count, emblem)
├── view/welcome.html (portal renderer)
│   ├── portal-top (modules_top loop)
│   ├── portal-content-wrapper
│   │   ├── portal-right (modules_right loop)
│   │   │   └── recruitment_side.html, custom blocks, etc.
│   │   └── portal-center (modules_center loop)
│   │       ├── motd_center.html
│   │       ├── roster_center.html (filters, table, pagination)
│   │       └── news_center.html, activity, etc.
│   └── portal-bottom (modules_bottom loop)
└── overall_footer.html
```

## Service Layer

All services are defined in `config/services.yml` and `config/portal_services.yml`. Table names are parameterised in `config/tables.yml`.

### Core Services

| Service ID | Class | Purpose |
|---|---|---|
| `avathar.bbguild.controller` | `controller\view_controller` | Frontend routes (guild page) |
| `avathar.bbguild.admin.main` | `controller\admin_main` | ACP dashboard, settings, logs |
| `avathar.bbguild.admin.games` | `controller\admin_games` | Game/faction/race/class/role management |
| `avathar.bbguild.admin.guild` | `controller\admin_guild` | Guild/rank/player management |
| `avathar.bbguild.admin.portal` | `controller\admin_portal` | Portal module management |
| `avathar.bbguild.log` | `model\admin\log` | bbGuild activity log |
| `avathar.bbguild.curl` | `model\admin\curl` | HTTP client wrapper |
| `avathar.bbguild.util` | `model\admin\util` | Request utilities |
| `avathar.bbguild.game_registry` | `model\games\game_registry` | Game provider lookup |
| `avathar.bbguild.listener` | `event\main_listener` | phpBB event hooks |

### Portal Services

| Service ID | Class | Purpose |
|---|---|---|
| `avathar.bbguild.portal.renderer` | `portal\renderer` | Renders portal layout for a guild |
| `avathar.bbguild.portal.columns` | `portal\columns` | Column constants and helpers |
| `avathar.bbguild.portal.module_registry` | `portal\modules\module_registry` | Available module type lookup |
| `avathar.bbguild.portal.modules.manager` | `portal\modules\manager` | Module CRUD operations |
| `avathar.bbguild.portal.modules.database_handler` | `portal\modules\database_handler` | Module config persistence |

Built-in portal modules (tagged `bbguild.portal.module`):
- `portal_motd` - Message of the Day
- `portal_news` - Guild News
- `portal_recruit` - Recruitment Status
- `portal_roster` - Guild Roster (center-only, with filters and pagination)
- `portal_activity` - Activity Feed
- `portal_custom` - Custom HTML Block

## Routes

Defined in `config/routing.yml`:

| Route | Path | Controller |
|---|---|---|
| `avathar_bbguild_guild` | `/guild/{guild_id}` | `view_controller::handleview` |
| `avathar_bbguild_00` | `/guild/{page}/{guild_id}` | `view_controller::handleview` (compat) |
| `avathar_bbguild_01` | `/getfaction` | AJAX: faction selector |
| `avathar_bbguild_02` | `/getguildrank/{guild_id}` | AJAX: rank selector |
| `avathar_bbguild_03` | `/getplayerList/{game_id}` | AJAX: player list |
| `avathar_bbguild_04` | `/getclassrace/{game_id}` | AJAX: class/race selector |

The primary route is `/guild/{guild_id}`. The legacy `/guild/{page}/{guild_id}` route is kept for backward compatibility but `$page` is ignored.

## ACP Modules

Registered via migrations in `migrations/basics/modules.php`:

| Category | Module | Controller |
|---|---|---|
| ACP_BBGUILD_MAINPAGE | Dashboard | `admin_main` |
| ACP_BBGUILD_MAINPAGE | Settings | `admin_main` |
| ACP_BBGUILD_MAINPAGE | Logs | `admin_main` |
| ACP_BBGUILD_MAINPAGE | Portal | `admin_portal` |
| ACP_BBGUILD_GUILD | Guilds | `admin_guild` |
| ACP_BBGUILD_GUILD | Ranks | `admin_guild` |
| ACP_BBGUILD_GUILD | Players | `admin_guild` |
| ACP_BBGUILD_GAME | Games | `admin_games` |
| ACP_BBGUILD_GAME | Factions | `admin_games` |
| ACP_BBGUILD_GAME | Races | `admin_games` |
| ACP_BBGUILD_GAME | Classes | `admin_games` |
| ACP_BBGUILD_GAME | Roles | `admin_games` |

## Database Schema

21 tables total (see `database.html` for full column details):

### Core Tables
| Table | Purpose |
|---|---|
| `bb_games` | Supported games |
| `bb_guild` | Guilds |
| `bb_players` | Player characters |
| `bb_ranks` | Guild ranks |
| `bb_logs` | Activity log |
| `bb_news` | Guild news items |
| `bb_motd` | Message of the Day |
| `bb_recruit` | Recruitment postings |

### Game Content Tables
| Table | Purpose |
|---|---|
| `bb_classes` | Character classes per game |
| `bb_races` | Races per game |
| `bb_factions` | Factions per game |
| `bb_gameroles` | Roles (tank, healer, dps, etc.) |
| `bb_language` | Game content localisation |

### Portal Tables
| Table | Purpose |
|---|---|
| `bb_portal_modules` | Module layout per guild (column, order, status) |
| `bb_portal_config` | Module config values per guild |

### Achievement Tables (WoW)
| Table | Purpose |
|---|---|
| `bb_achievement` | Achievement definitions |
| `bb_achievement_track` | Guild achievement progress |
| `bb_achievement_criteria` | Achievement criteria |
| `bb_achievement_rewards` | Achievement rewards |
| `bb_relations_table` | Achievement relationships |
| `bb_criteria_track` | Criteria progress tracking |

### Planned (schema TBD)
| Table | Purpose |
|---|---|
| `bb_bosstable` | Boss encounters |
| `bb_zonetable` | Raid zones |

## Game Plugin System

Games are external extensions at `ext/avathar/bbguild_<game>/`. Each plugin provides:

- `composer.json` + `ext.php` (standard phpBB extension)
- `game/<game>_provider.php` implementing `game_provider_interface`
- `game/<game>_installer.php` extending `abstract_game_install`
- `config/services.yml` tagging the provider with `bbguild.game_provider`
- Language files and game images

Providers are collected via `phpbb\di\service_collection` (tagged `bbguild.game_provider`) and accessed through `game_registry`.

### Available Plugins
| Plugin | Game | API |
|---|---|---|
| `bbguild_wow` | World of Warcraft | Battle.net |
| `bbguild_gw2` | Guild Wars 2 | - |
| `bbguild_lotro` | Lord of the Rings Online | - |
| `bbguild_eq` | EverQuest | - |
| `bbguild_eq2` | EverQuest 2 | - |
| `bbguild_ffxi` | Final Fantasy XI | - |
| `bbguild_ffxiv` | Final Fantasy XIV | - |
| `bbguild_swtor` | Star Wars: The Old Republic | - |
| `bbguild_lineage2` | Lineage 2 | - |

A built-in "Custom" game is included in core (`model/games/library/install_custom.php`).

## Log System

The log system (`model/admin/log.php`) follows the phpBB log design pattern:

- **Storage:** Each log entry stores a `log_type` (language key like `L_ACTION_GUILD_ADDED`) and `log_action` (serialized array of `vsprintf` substitution args)
- **Display:** The log viewer resolves the language key and applies args via `vsprintf()` at render time
- **Format strings:** `ACTION_*` keys are short labels; `VLOG_*` keys are verbose format strings with `%s` placeholders (first `%s` is always the username, resolved from `log_userid`)
- **Valid types:** Defined in `log::$valid_action_types` mapping constants to type names

## Permissions

| Permission | Scope | Purpose |
|---|---|---|
| `a_bbguild` | Admin | ACP access |
| `u_bbguild` | User | View guild pages |
| `u_charclaim` | User | Claim a character |
| `u_charadd` | User | Add a character |
| `u_chardelete` | User | Delete a character |
| `u_charupdate` | User | Update a character |

## Migration Chain

```
basics/schema -> basics/data -> basics/config -> basics/permissions -> basics/modules
    -> v200b1/portal_data -> v200b1/release_2_0_0_b1
```

## Future: DKP Plugin

The DKP (Dragon Kill Points) system is being developed as a separate extension at https://github.com/avatharbe/bbDKP. It will provide raid tracking, loot management, point pools, and its own log types. See issue [#321](https://github.com/avatharbe/bbguild/issues/321).
