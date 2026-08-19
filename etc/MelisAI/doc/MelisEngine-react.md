---
title: MelisEngine module — React back-office
package: melisplatform/melis-engine
doc_type: module-documentation-react
audience: [users, developers, ai]
language: en
module_version: unversioned
last_reviewed: 2026-08-19
maintainer: Melis Technology
keywords: [engine, cms, rendering, templates, zones, plugins, pages, sites, languages, table-gateway, infrastructure, react, back-office]
related_docs: [./MelisEngine.md]
---

# MelisEngine (React back-office) — Infrastructure Role Documentation (for AI)

> **What this is.** MelisEngine has **no React back-office tool and no UI of its own** — there
> is no `ui-react/` brick source, no `public/ui-react/brick.manifest.json`, no
> `config/react-api.php` and no `config/react.capabilities.php`. It never appears as a tool in
> `/melis-react`. This document explains its **infrastructure role** relative to the React
> back-office: MelisEngine is the **CMS rendering engine** and the **single source of truth for
> the page/site data model**. Every React CMS tool and the React page editor read and write CMS
> data **through the engine's table gateways and services**, and every page/zone/plugin shown in
> the React shell (page editor preview, iframe tools) is rendered **server-side by the engine**.
>
> For the full engine feature set (data model, table gateways, services, caching, the plugin
> base class), read the **[legacy doc](./MelisEngine.md)** — this file does not repeat it, it
> only covers the React relationship.
>
> **How this document is organised — two clearly separated parts:**
> - **[Part A — Functional](#part-a--functional)** — plain language: why an invisible module
>   underpins everything the React CMS tools show.
> - **[Part B — Technical](#part-b--technical)** — the real rendering mechanism and the engine
>   services/table gateways the React BO relies on, with accurate names.
>
> **Audience**: consumed by the **MelisAI** MCP. **Status**: reviewed 2026-08-19.

---

## 0. Where this lives in the React back-office — read this first

**Brick kind: none.** MelisEngine is **not migrated to React** and is **not meant to be** — it
is platform infrastructure (the CMS engine), not a back-office tool. It:

- has **no brick** (no `ui-react/` project, no `public/ui-react/brick.manifest.json`),
- exposes **no `react-api` endpoints** (no `config/react-api.php`),
- declares **no capabilities** (no `config/react.capabilities.php`),
- shows **no sidebar entry** and **no menu node** in `/melis-react`.

What it *does* for React: it is the layer beneath the React CMS tools. Two relationships matter:

1. **Data path** — React CMS controllers (`MelisReactApi*` in melis-cms and other modules) read
   and write CMS data (pages, sites, languages, templates, SEO…) **only** through MelisEngine's
   table gateways (`MelisEngineTable*`) and services (`MelisEnginePage`, `MelisEngineTree`,
   `MelisEngineLang`, …). MelisEngine owns those tables; the React BO never touches the CMS
   schema directly.
2. **Render path** — the CMS page content the React page editor and the iframe tool mechanism
   display is rendered **server-side** (pages → templates → zones → plugins). The engine
   resolves the page model and defines the plugin base class; MelisFront runs the render (the
   trio Core/Engine/Front — see §B4).

> ⚠ Because MelisEngine is the single source of truth for the page/site model, a broken engine
> service or a missing CMS table surfaces in the React BO as failed CMS tools (empty page trees,
> language dropdowns that don't populate, page editors that error), **not** as an engine screen —
> there is no engine screen. See the [legacy doc](./MelisEngine.md) §B3–B6 for the model/cache.

Cross-links: [MelisEngine legacy doc](./MelisEngine.md) · trio siblings
[MelisCms](../../../melis-cms/etc/MelisAI/doc/MelisCms.md) (the React CMS tools) and
[MelisFront](../../../melis-front/etc/MelisAI/doc/MelisFront.md) (front render + editable
preview). React shell served by **MelisReactOverride**, React JSON API in **MelisReactApi**
(both separate modules).

---
---

# PART A — Functional

## A1. What it is (and why you never see it)

End users and back-office admins **never open MelisEngine** — there is no screen for it, in the
legacy back-office *or* in `/melis-react`. It is the **engine room**: it stores all the website
data (pages published/draft, the page tree, sites, domains, templates, languages, SEO, styles)
and provides the machinery that renders pages. In the React back-office its relevant job is:
**be the data and rendering foundation** that the React CMS tools (Pages, Sites, Languages,
Templates…) are built on.

## A2. How it silently underpins the React CMS tools

The React back-office replaces the *presentation* of the CMS tools; it does **not** replace the
CMS logic or data. So when you use a React CMS tool:

- Opening a **page** in the React page editor asks the engine (server-side) for that page's data
  and renders its content through the engine's plugin framework.
- The **language** dropdowns in React CMS tools are filled from the engine's CMS languages
  (`melis_cms_lang`) — the same list the legacy tools used.
- **Sites**, **templates**, **SEO** shown or edited in React are read from and saved back into
  the engine's tables.
- Any **legacy tool** opened inside the React shell as an **iframe** (the tool mechanism of
  MelisReactOverride) still renders its page/zone content through the engine server-side.

So the split is: React draws the interface and calls JSON endpoints; **MelisEngine holds the data
and renders the CMS content underneath**.

## A3. The common symptom when it fails

Because it has no screen, engine problems appear *inside the React CMS tools*, e.g.: an **empty
page tree**, a **language selector that stays empty**, a **page editor that errors on load**, or
a legacy iframe tool that renders blank — typically caused by a **missing/behind CMS table**
(engine schema not migrated) or a failing engine service, not by anything in the React layer.

> **In short:** MelisEngine is invisible but load-bearing for the whole CMS side of
> `/melis-react`. If a React CMS tool can't list/show/save page or language data, look at the
> engine (tables, services, cache) — see the [legacy doc](./MelisEngine.md).

---
---

# PART B — Technical

## B1. Metadata & dependencies

| Item | Value |
|---|---|
| Package | `melisplatform/melis-engine` · type `melisplatform-module` · category `cms` · namespace `MelisEngine\` |
| Requires | `php` `^8.3\|^8.5`, `melisplatform/melis-core` `^6.0`, `melisplatform/melis-front` `^6.0`, `laminas/laminas-cache` (+ filesystem/memory adapters) |
| React presence | **None** — no brick, no `react-api`, no capabilities, no UI |
| React relationship | Owns the CMS data model + renders pages/templates/zones/plugins server-side; React CMS tools read/write via its table gateways & services |

## B2. The rendering mechanism (server-side, feeds the React BO)

MelisEngine is the CMS **rendering engine**. There is no React-specific code here — the React BO
consumes the same engine that the legacy BO and the front-office consume:

- **Page resolution** — `MelisEnginePage` (`src/Service/MelisPageService.php`, alias
  `MelisEnginePage`) resolves a page (tree + template + SEO + lang + style) by id and mode
  (`published`/`saved`) via `getDatasPage()`, cached.
- **Tree navigation** — `MelisEngineTree` (`MelisTreeService`): `getPageChildren()`,
  `getPageFather()`, `getPageBreadcrumb()`, `getPageLink()`.
- **Templates** — `MelisEngineTemplateService` (`getTemplate()`), cached.
- **Content blocks (zones/plugins)** — `src/Controller/Plugin/MelisTemplatingPlugin.php` is the
  **abstract base class every content plugin extends**. It defines `front()` (live render) and
  `back()` (BO container/edit view) and persists plugin config in the page XML. **MelisFront**
  runs the actual render of a page's zones by invoking each plugin's `front()`.
- **Caching** — `MelisEngineCacheSystem` (two tiers, memory + filesystem) keeps rendered
  page/template output ready; publishing/saving invalidates the relevant keys.

**Where this meets React:** the React page editor and the iframe tool mechanism
(`MelisReactOverride`, `/melis/react-tool-page?key=…`) display CMS content that is produced by
this server-side pipeline (engine resolves the page model + plugin framework; MelisFront renders
the zones). React does not re-render CMS content client-side — it embeds/represents the
server-rendered result. See [MelisFront](../../../melis-front/etc/MelisAI/doc/MelisFront.md) for
the render loop and the [legacy doc](./MelisEngine.md) §B5 for the plugin contract.

## B3. Engine services & table gateways the React BO relies on (real, verified)

React CMS controllers (`MelisReactApi*` living **in the CMS modules**, not in melis-engine) read
CMS data exclusively through MelisEngine. Verified consumers in the current source:

| Engine service / gateway (alias) | Backing class | Used by (React BO, verified) |
|---|---|---|
| `MelisEngineTableCmsLang` | `MelisEngine\Model\Tables\MelisCmsLangTable` | melis-cms-tags `MelisCmsTagsReactApiController` (`getLangId`/`getDefaultLangId`), melis-cms-user-account `MelisReactApiUserAccountController`, melis-core `MelisReactApiGdprController` (`fetchAll()` for the language list) |
| `MelisEngineLang` (`MelisEngineLangService`) | `MelisEngine\Service\MelisEngineLangService` | melis-cms `MelisReactApiCmsMenuManagerController`, `MelisReactApiCmsSitesController` (`getAvailableLanguages()` — same CMS languages as the legacy tunnel) |
| `MelisEnginePage` (`MelisPageService`) | `MelisEngine\Service\MelisPageService` | melis-cms `MelisReactApiPageController` (page data for the React page editor) |
| `MelisEngineTree` (`MelisTreeService`) | `MelisEngine\Service\MelisTreeService` | melis-cms `MelisReactApiPageController` (page tree navigation) |

> These are the **sanctioned data path**: other modules read/write CMS data **only** through the
> `MelisEngineTable*` gateways and the engine services (never raw SQL). The alias
> `MelisEngineTableCmsLang => MelisCmsLangTable::class` is registered in
> `config/module.config.php`. The full gateway/service inventory (30+ `MelisEngineTable*`,
> `MelisEngineSiteService`, `MelisEngineTemplateService`, `MelisEngineSEOService`,
> `MelisEngineStyle`, `MelisEngineCacheSystem`, …) is in the [legacy doc](./MelisEngine.md)
> §B3–B4.

**Illustrative call (server-side, from a React CMS controller — not an engine endpoint):**

```php
// Inside a MelisReactApi* controller of a CMS module — the React JSON layer
// delegates to the engine; MelisEngine exposes no react-api of its own.
$langTable = $this->getServiceManager()->get('MelisEngineTableCmsLang');
$langs     = $langTable->fetchAll()->toArray();   // fills a React language dropdown

$pageSvc = $this->getServiceManager()->get('MelisEnginePage');
$page    = $pageSvc->getDatasPage($idPage, 'saved');  // draft shown in the React page editor
```

## B4. The trio Core / Engine / Front (React context)

The React back-office does not change the trio; it sits on top of it:

- **MelisEngine** *(this module)* — owns the entire CMS DB model and exposes it via table
  gateways + services + cache; defines `MelisTemplatingPlugin`.
- **MelisFront** — renders pages from the engine's data (runs the content plugins) and powers the
  **editable preview** used inside the back-office (legacy *and* the React page editor / iframe
  tools).
- **MelisCms** — the CMS **back-office**; it owns **no tables** and edits everything through the
  engine. Its React CMS tools (`MelisReactApiPage`, `MelisReactApiCmsSites`,
  `MelisReactApiCmsMenuManager`, …) are the React consumers listed in §B3.

Load order: `melis-core` → `melis-front` → **`melis-engine`** → `melis-cms`. Neither MelisCms nor
MelisFront owns database tables — **MelisEngine is the single source of truth for the page/site
model**, in the React BO exactly as in the legacy BO.

## B5. Quick code map (React-relevant parts)

```
melis-engine/
├── composer.json                          → type melisplatform-module, category cms; requires core ^6.0 + front ^6.0
├── config/
│   └── module.config.php                  → engine service + table-gateway aliases (e.g. MelisEngineTableCmsLang => MelisCmsLangTable),
│                                            caches, form factories   (NO react-api / react.capabilities)
├── src/
│   ├── Model/Tables/                       → the CMS data model — MelisEngineTable* gateways the React CMS tools read/write through
│   │   └── MelisCmsLangTable.php            → alias MelisEngineTableCmsLang (React language dropdowns)
│   ├── Service/                            → the rendering/data services:
│   │   ├── MelisPageService.php             → alias MelisEnginePage  (page data for the React page editor)
│   │   ├── MelisTreeService.php             → alias MelisEngineTree   (page tree)
│   │   ├── MelisEngineLangService.php       → alias MelisEngineLang   (available CMS languages)
│   │   ├── MelisEngineTemplateService.php   → templates
│   │   └── MelisEngineCacheSystemService.php→ alias MelisEngineCacheSystem (rendered-page cache)
│   └── Controller/Plugin/
│       └── MelisTemplatingPlugin.php        → abstract base for every content plugin/zone (front()/back())
└── etc/MelisAI/doc/
    ├── MelisEngine.md                       → legacy/full doc (data model, services, cache, plugin base) — cross-linked
    └── MelisEngine-react.md                 → THIS doc (infrastructure role in the React BO)

(no ui-react/, no public/ui-react/, no config/react-api.php, no config/react.capabilities.php)

Consumed elsewhere (React CMS controllers that call the engine — in the CMS modules, not here):
melis-cms/src/Controller/MelisReactApiPageController.php        → MelisEnginePage, MelisEngineTree
melis-cms/src/Controller/MelisReactApiCmsSitesController.php    → MelisEngineLang
melis-cms/src/Controller/MelisReactApiCmsMenuManagerController.php → MelisEngineLang
melis-cms-tags / melis-cms-user-account / melis-core (Gdpr)     → MelisEngineTableCmsLang
```

---

*Document for AI consumption (MelisAI MCP) — infrastructure role of `melisplatform/melis-engine`
in the React back-office. This module has no React tool/UI; it is the CMS rendering engine and
the single source of truth for the page/site data model that the React CMS tools read/write
through (table gateways + services) and render through (server-side, via MelisFront). Full engine
reference: [./MelisEngine.md](./MelisEngine.md). Last reviewed 2026-08-19.*
