# Layout Refactor — Portal-First Architecture

## Status: DONE (2.0.0-b2)

All core layout changes are implemented. Old files kept for reference but are no longer used.

## Current Layout (implemented)

```
+------------------------------------------------------------------+
|  breadcrumb: Avathar > Guild > Board Index                       |
+------------------------------------------------------------------+
|                                                                   |
|  GUILD HEADER (normal flow, in main.html)                        |
|  +--------------------------------------------------------------+|
|  |  Test Guild                                                   ||
|  |  Alliance Guild, us-Test Realm. 0 Members                    ||
|  |  [achievement points]                                         ||
|  +--------------------------------------------------------------+|
|                                                                   |
|  PORTAL CONTENT (all modules, one page)                          |
|  +--------------------------------------------------------------+|
|  |                                                               ||
|  |  +-- portal-center -------+-- portal-right -+                ||
|  |  |                        |                  |                ||
|  |  | MESSAGE OF THE DAY     | RECRUITMENT      |                ||
|  |  | "Welcome to our        | Priest (2)       |                ||
|  |  |  guild! ..."           |                  |                ||
|  |  |                        |                  |                ||
|  |  +------------------------+                  |                ||
|  |  |                        |                  |                ||
|  |  | ROSTER                 |                  |                ||
|  |  | +--------------------+ |                  |                ||
|  |  | | [filter] [search]  | |                  |                ||
|  |  | +------+-----+--+---+ |                  |                ||
|  |  | | Name |Class|Rk|Lvl| |                  |                ||
|  |  | +------+-----+--+---+ |                  |                ||
|  |  | | Toon |Warr.|3 | 80| |                  |                ||
|  |  | | Alt  |Mage |5 | 72| |                  |                ||
|  |  | +------+-----+--+---+ |                  |                ||
|  |  | [pagination]          |                  |                ||
|  |  |                        |                  |                ||
|  |  +------------------------+                  |                ||
|  |  |                        |                  |                ||
|  |  | GUILD NEWS             |                  |                ||
|  |  | bbGuild Installed      |                  |                ||
|  |  |  admin - 9 min ago     |                  |                ||
|  |  |                        |                  |                ||
|  |  +------------------------+------------------+                ||
|  |                                                               ||
|  +--------------------------------------------------------------+|
+------------------------------------------------------------------+
```

## What Changed

### Guild header — moved to main.html (normal flow)
- Was: absolutely positioned from sidebar, overlapping portal content
- Now: in `main.html`, normal document flow above portal
- No more `padding-top:200px` hack

### Sidebar — removed
- Was: 175px float-left with guild header, nav links, guild chooser
- Now: removed entirely from main.html; guild chooser moved to guild header area
- Note: sidebar template `sidemenu.html` was removed from main.html include

### Roster — is a portal module
- Was: separate page at `/guild/roster/{guild_id}` with its own view class
- Now: `portal/modules/roster.php` renders as center-column portal block
- Filters (armor/class dropdown, player search) are inside the module template
- Pagination inside the module
- ACP controls visibility and position (drag to reorder)

### One page, not multiple
- Was: separate "welcome" and "roster" pages
- Now: everything is a portal module on one page

### view_controller.php — simplified
- `handleview()` reduced to: build guild_context, render portal, return response
- `$page` parameter kept for route compatibility but unused

### guild_context.php — replaced viewnavigation.php
- Keeps: guild resolution, guild data loading, guild dropdown, template vars
- Removed: all roster filter logic (armor, class, build_roster_navigation)

### Routes
- New: `/guild/{guild_id}` — primary route
- Kept: `/guild/{page}/{guild_id}` — backward compatibility (page param ignored)

## Files Created

| File | Purpose |
|------|---------|
| `views/guild_context.php` | Guild data, dropdown, header template vars |
| `portal/modules/roster.php` | Roster portal module (center-only) |
| `styles/all/template/portal/modules/roster_center.html` | Roster module template |

## Files Made Obsolete (still on disk, no longer used)

| File | Replaced by |
|------|-------------|
| `views/viewnavigation.php` | `views/guild_context.php` |
| `views/viewwelcome.php` | Logic inlined in `view_controller::handleview()` |
| `views/viewroster.php` | `portal/modules/roster.php` |
| `views/iviews.php` | No longer needed (interface for old view classes) |
| `styles/.../view/rostergrid.html` | `portal/modules/roster_center.html` |
| `styles/.../view/rosterlisting.html` | `portal/modules/roster_center.html` |

## Portal Module Order (default, configurable in ACP)

| # | Module | Column | Description |
|---|--------|--------|-------------|
| 1 | MOTD | center | Message of the day |
| 2 | Roster | center | Guild roster table with filters |
| 3 | Guild News | center | News items with BBCode |
| 4 | Activity Feed | center | Recent activity |
| 5 | Recruitment | right | Open recruitment slots |
| 6 | Custom Block | right | Admin-defined content |

## Cleanup TODO

- [ ] Delete obsolete files (`viewwelcome.php`, `viewroster.php`, `iviews.php`, `viewnavigation.php`, `rostergrid.html`, `rosterlisting.html`)
- [ ] Remove old CSS (`.profile-info`, `.profile-info-anchor`, `.welcome-top`, `.welcome-col1/col2` etc.)
- [ ] Verify guild chooser dropdown works without sidebar
