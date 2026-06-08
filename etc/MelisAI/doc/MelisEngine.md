---
title: MelisEngine module
package: melisplatform/melis-engine
doc_type: module-documentation
audience: ai
language: en
module_version: unversioned   # no `version` field in composer.json; this doc tracks the current source
last_reviewed: 2026-06-08
maintainer: Melis Technology
keywords: [engine, cms, core, pages, templates, sites, languages, domains, seo, cache, table-gateway, rendering, melis]
screenshots_dir: ./images
---

# MelisEngine Module — Functional Documentation (for AI)

> **Purpose of this document**: describe, functionally and technically, the
> `melisplatform/melis-engine` module, so that an AI (or a developer) can understand
> *what the module does*, *which services and models it provides*, *how they work* and
> *where the corresponding code lives*.
>
> **Audience**: consumed by the **MelisAI** module (a MelisPlatform module that exposes an
> MCP function to answer user questions). MelisAI fetches this `.md` file on demand.
>
> **Status**: reviewed 2026-06-08 against the current source. The module carries no
> semantic version (no `version` in `composer.json`).
>
> **No screenshots**: MelisEngine is a **headless technical layer** — it has no back-office
> tool UI of its own, so this doc has no `images/`. The visible UIs that use it live in the
> [MelisCms](../../../melis-cms/etc/MelisAI/doc/MelisCms.md) (back-office) and
> [MelisFront](../../../melis-front/etc/MelisAI/doc/MelisFront.md) (front rendering) docs.

---

## 0. The MelisCms / MelisFront / MelisEngine trio

These three modules are the heart of the Melis website platform and **must be understood
together**:

- **MelisEngine** *(this module)* — the **shared technical layer**: it owns the **CMS
  database model** (pages, templates, sites, languages, domains, SEO, styles…), exposes it
  through **table gateways** and **services**, provides the **caching system**, and defines
  the **templating-plugin base class** that both siblings extend.
- **MelisFront** — the **front-office rendering system** that displays the public websites
  (routing a URL → a page, running the render pipeline, templating plugins, SEO, assets).
- **MelisCms** — the **back-office** to build/administer those websites (page tree, page
  editor, sites, templates, styles, languages…).

**Dependency / load order** (from the `composer.json` of each): `melis-core` → **melis-front**
→ **melis-engine** → **melis-cms**. Notably, **melis-engine requires melis-front**, and
**melis-cms requires both melis-engine and melis-front**. In practice: **MelisCms and
MelisFront both read and write the CMS data exclusively through MelisEngine** — neither owns
database tables; MelisEngine is the single source of truth for the page/site model.

```
            ┌─────────────┐   edits via services/tables   ┌──────────────┐
            │  MelisCms    │ ────────────────────────────▶ │              │
            │ (back-office)│                                │  MelisEngine │  owns the DB model
            └─────────────┘                                │  (data +     │  (pages, sites,
            ┌─────────────┐   renders via services/tables  │   services + │   templates, SEO…)
            │  MelisFront  │ ────────────────────────────▶ │   cache)     │  + plugin base class
            │ (front render)│                               └──────────────┘
            └─────────────┘
```

---

## 1. Overview

`MelisEngine` is the **shared data + services + caching foundation** of the Melis CMS. It
defines and owns the entire CMS database schema and provides a unified, cached access layer
over it (the **`MelisEngineTable*`** gateways and the **`MelisEngine*`** services), the
**page-rendering services** that resolve a page to its template/content, and the abstract
**`MelisTemplatingPlugin`** contract that all front-office and back-office content plugins
extend.

| Item | Value |
|---|---|
| Package name | `melisplatform/melis-engine` |
| Type | `melisplatform-module` |
| PHP namespace | `MelisEngine\` → `src/` (PSR-4) |
| Melis category | `cms` |
| License | OSL-3.0 |
| PHP required | `^8.1 | ^8.3` |
| dbdeploy | `true` |
| Cache | **laminas-cache** (filesystem + memory adapters) |

### Dependencies (`composer.json`)

- `melisplatform/melis-core` (`^5.2`) — base services, events, rights, the `MelisGeneralService` base
- `melisplatform/melis-front` (`^5.2`) — engine loads **after** front (see §0)
- `laminas/laminas-cache` + filesystem/memory storage adapters — the caching system

---

## 2. The CMS data model (owned by MelisEngine)

MelisEngine owns the CMS database tables. The most important domain tables:

| Table | Role |
|---|---|
| `melis_cms_page_tree` | The **page hierarchy** (parent `tree_father_page_id`, order) — the tree of every site |
| `melis_cms_page_published` | **Published** page version (live content shown on the front) |
| `melis_cms_page_saved` | **Saved/draft** page version (the working copy edited in the BO) |
| `melis_cms_page_lang` | Page ↔ language links (multilingual page versions) |
| `melis_cms_lang` | CMS languages/locales |
| `melis_cms_site` | **Sites** (the root container of pages) |
| `melis_cms_template` | **Templates** (layout/controller/action or PHP path) per site |
| `melis_cms_page_seo` | Per-page **SEO** (URL, redirect, 301, meta title/description, canonical) |
| `melis_cms_site_domain` | Site **domains** (per environment) |
| `melis_cms_site_301` / `melis_cms_site_404` | Site-level 301 redirects / 404 page mapping |
| `melis_cms_page_default_urls` | Pre-computed page URLs (cache table) |
| `melis_cms_style` / `melis_cms_page_style` | Site **styles (CSS)** and page↔style links |
| `melis_cms_platform_ids` | Page-id allocation ranges per environment |
| `melis_cms_site_config` / `melis_cms_site_home` / `melis_cms_site_langs` | Site config / home page per lang / active languages |
| `melis_cms_site_robot` | robots.txt content per domain (managed by the *site-robot* module, gateway here) |
| `melis_cms_mini_tpl_*` | Mini-template categories / templates / flags |
| `melis_cms_gdpr_texts` | GDPR banner texts per site/language |
| `melis_site_translation` / `_text` | Site-wide translation strings |

- Base structure: `install/sql/setup_structure.sql`; model: `install/sql/Model/`.

### Table gateways (`src/Model/Tables`)

Registered as `service_manager` aliases in `config/module.config.php`. Other modules read and
write **only** through these (never raw SQL). Key gateways:

`MelisEngineTablePageTree`, `MelisEngineTablePagePublished`, `MelisEngineTablePageSaved`,
`MelisEngineTablePageLang`, `MelisEngineTableCmsLang`, `MelisEngineTableSite`,
`MelisEngineTableTemplate`, `MelisEngineTablePageSeo`, `MelisEngineTableSiteDomain`,
`MelisEngineTableSite301`, `MelisEngineTableSite404`, `MelisEngineTableStyle`,
`MelisEngineTablePageStyle`, `MelisEngineTablePlatformIds`, `MelisEngineTablePageDefaultUrls`,
`MelisEngineTableCmsSiteHome`, `MelisEngineTableCmsSiteLangs`, `MelisEngineTableCmsSiteConfig`,
`MelisEngineTableRobot`, `MelisEngineTableFlaggedTemplate`, `MelisEngineTableCmsSiteBundle`.

They extend a generic table base (CRUD: `getEntryById`, `getEntryByField`, `save`,
`deleteById`, `fetchAll`, …).

---

## 3. Services (`src/Service`)

Registered as `service_manager` aliases. The most important:

| Service alias | Role |
|---|---|
| `MelisEnginePage` (`MelisPageService`) | **Resolve a page**: full page data (tree + template + SEO + lang + style) for a page id and a mode (`published` / `saved`), with caching — `getDatasPage()` |
| `MelisEngineTree` (`MelisTreeService`) | **Page-tree navigation**: `getPageChildren()`, `getPageFather()`, `getPageBreadcrumb()`, `getPageLink()`, search by value |
| `MelisEngineTemplateService` | Template lookup (`getTemplate($tplId)`) with cache |
| `MelisEngineSiteService` | Site data: `getSiteById()`, `getSiteDataByDomain()`, home page per lang |
| `MelisEngineSiteDomainService` | Domain → site resolution |
| `MelisEngineLang` (`MelisEngineLangService`) | Languages: available languages, locale ↔ lang id, site language |
| `MelisEngineSEOService` | Per-page SEO data (`getSEOById()`) |
| `MelisEnginePageDefaultUrlsService` | Pre-computed page URL lookups |
| `MelisEngineStyle` (`MelisEngineStyleService`) | Site styles / page CSS |
| `MelisEngineCacheSystem` (`MelisEngineCacheSystemService`) | **The cache orchestrator** (§5) |
| `MelisSearch` (`MelisSearchService`) | Full-text search index (Lucene-style) — used by front search |
| `MelisEngineSendMail` | Email sending utility |
| `MelisGdprService` / `MelisGdprAutoDeleteService` | GDPR banner texts / auto-delete framework |
| `MelisEngineComposer` | Composer/dependency operations |

Many services extend `MelisCore`'s `MelisGeneralService`, so they fire `*_start` / `*_end`
events (e.g. `melisengine_service_get_available_languages_start` / `_end`) that other modules
can hook.

---

## 4. The templating-plugin contract

`src/Controller/Plugin/MelisTemplatingPlugin.php` is the **abstract base class for every
content plugin** in the platform (the News/Slider/Category2 front plugins, the MelisFront
Tag/Menu/Breadcrumb plugins, etc.). It defines:

- `front()` (abstract) — render the content block on the **live site** (implemented by each plugin)
- `back()` — render the block's **back-office** container/edit view
- config persistence in the page XML (`loadDbXmlToPluginConfig()` / `savePluginConfigToXml()`),
  GET/POST data loading, preview mode and responsive width

This is the single seam through which **MelisFront renders** plugins and **MelisCms edits**
them — both call into subclasses of this engine class.

Engine also provides form factories used across the BO: `MelisEnginePluginTemplateSelect`
(template dropdown) and `MelisEngineSiteSelect` (site dropdown).

---

## 5. Caching

MelisEngine centralizes caching through `MelisEngineCacheSystem` over **laminas-cache**, with
two tiers configured in `config/module.config.php` (`caches`):

- **Memory** adapters (per-request): page/service/lang/plugin result caches
  (`engine_memory_cache`, `engine_page_services`, `engine_lang_services`, `templating_plugins`).
- **Filesystem** adapters (persistent, on disk under `../cache`): rendered page / template
  output (`engine_file_cache`, `meliscms_page`), with serializer + non-throwing exception
  handler plugins.

`getCacheByKey()` / `setCacheByKey()` / `deleteCacheByPrefix()` are the main entry points;
page caches are keyed by page id + mode. **MelisFront** caches rendered pages here and
**MelisCms** invalidates them on publish/save (see §7).

---

## 6. Controllers & listeners

- **Controllers**: only setup/maintenance controllers (`MelisSetup*` — install, post-download,
  post-update). Engine has no editing UI.
- **Listeners** (`src/Listener`): two micro-service listeners that hook
  `melis_core_microservice_amend_data` to expose tree/page service methods
  (`getPageChildren`, `getPageFather`, `getDomainByPageId`, `getDatasPage`) over the
  micro-service bus and inject domain-qualified URLs into page content.

---

## 7. How the siblings use MelisEngine (cross-module links)

### Front rendering (MelisFront → MelisEngine)
When a public URL is served, MelisFront resolves the page id, then asks engine:
`MelisEngineSiteDomainService`/`MelisEngineSiteService` (domain → site),
`MelisEnginePage::getDatasPage()` (page + template), `MelisEngineTemplateService` (template),
`MelisEngineTree` (menus/breadcrumbs/links), `MelisEngineSEOService` (title/description/
canonical), `MelisEngineStyle` (page CSS), and `MelisEngineCacheSystem` (cache the result).
The page template then runs plugins that all extend engine's `MelisTemplatingPlugin`.

### Back-office editing (MelisCms → MelisEngine)
The BO page editor reads/writes the page model through engine gateways
(`MelisEngineTablePageTree`, `…PageSaved`, `…PagePublished`, `…PageSeo`, `…PageLang`,
`…PageStyle`, `…Template`, `…Site`, `…SiteDomain`, `…CmsLang`, `…PlatformIds`, …) and engine
services. Publishing a page copies `melis_cms_page_saved` → `melis_cms_page_published` and the
engine page cache is invalidated.

### Module-data classification
Other tool modules also consume engine gateways — e.g. **melis-cms-site-robot** edits
`melis_cms_site_robot` via `MelisEngineTableRobot`; SEO-aware modules read `…PageSeo`.

---

## 8. Quick code map

```
melis-engine/
├── composer.json                 → deps (core + front + laminas-cache), category cms, dbdeploy
├── config/module.config.php      → service & table-gateway aliases, caches, form factories, routes
├── src/
│   ├── Module.php                → bootstrap, micro-service listeners
│   ├── Model/Tables/             → 30+ MelisEngineTable* gateways (the CMS data model)
│   ├── Model/Hydrator/           → page/result hydrators
│   ├── Service/ (+ Factory/)     → MelisEnginePage, MelisEngineTree, Template, Site, Lang, Cache, SEO, Style, Search, Gdpr…
│   ├── Controller/ (+ Plugin/)   → setup controllers + MelisTemplatingPlugin (the plugin base class)
│   ├── Form/Factory/             → MelisEnginePluginTemplateSelect, MelisEngineSiteSelect
│   └── Listener/                 → micro-service listeners (tree/page)
├── install/                      → SQL (setup_structure + dbdeploy) — the CMS schema
└── etc/                          → MarketPlace + MelisAI/doc (this doc)
```

---

## 9. Glossary (engine terms used across the trio)

- **Page (published vs saved)** — every page has a *published* row (live) and a *saved* row
  (draft being edited). Publishing copies saved → published.
- **Page tree** — the hierarchy of pages under a site (`melis_cms_page_tree`).
- **Template** — the layout/controller/action (or PHP file) that renders a page type.
- **Site** — the root of a page tree, bound to one or more domains.
- **Table gateway** — a `MelisEngineTable*` class wrapping one DB table; the only sanctioned
  data access path for other modules.
- **Templating plugin** — a content block extending `MelisTemplatingPlugin` with `front()` /
  `back()`.

---

*Document for AI consumption (MelisAI MCP) — describes the `melisplatform/melis-engine`
module and its place in the MelisCms / MelisFront / MelisEngine trio. Last reviewed
2026-06-08 against the current source.*
