---
title: MelisEngine module
package: melisplatform/melis-engine
doc_type: module-documentation
audience: [users, developers, ai]
language: en
module_version: unversioned   # no `version` field in composer.json; this doc tracks the current source
last_reviewed: 2026-06-08
maintainer: Melis Technology
keywords: [engine, cms, core, pages, templates, sites, languages, domains, seo, cache, table-gateway, rendering, plugin-base, melis]
screenshots_dir: ./images
---

# MelisEngine — Functional & Technical Documentation (for AI)

> **What this is.** MelisEngine is the **shared foundation** beneath the Melis website
> platform. It is **invisible to end-users** — it has no screen of its own — but it is what
> makes everything work: it **stores all the website data** (pages, sites, templates,
> languages, SEO, styles…), provides the **services** that read and render that data, runs the
> **cache** that keeps pages fast, and defines the **content-block (plugin) framework** that the
> back-office and the front-office both build on.
>
> **How this document is organised — two clearly separated parts:**
> - **[Part A — Functional Guide](#part-a--functional-guide)** — a short, plain-language
>   explanation of what MelisEngine is and why it matters (for the chat assistant to answer
>   "what is the engine / where is my page data / why are pages cached").
> - **[Part B — Technical Reference](#part-b--technical-reference)** — the substantial part: the
>   data model, table gateways, services, caching, the plugin base class, and how the siblings
>   consume it. **This is the doc to read before building anything in the platform.**
>
> **Audience**: consumed by the **MelisAI** module (an MCP that answers user questions and may be
> used by an AI to build things). **No screenshots** — MelisEngine has no UI.

---
---

# PART A — Functional Guide

*Short, because MelisEngine has no screens. It's the engine room.*

## A1. What MelisEngine is (in plain words)

Think of a Melis website as having three layers:

- **MelisCms** — the **back-office** where you build and manage sites and pages (the steering wheel).
- **MelisFront** — the system that **shows the website** to visitors (the bodywork & wheels).
- **MelisEngine** *(this module)* — the **engine and fuel tank**: it holds **all the data** and
  the machinery both of the others rely on.

You never click "MelisEngine" anywhere — but every time you create a page, switch a language,
choose a template, or see a page load quickly, MelisEngine is doing the work underneath.

## A2. What lives in MelisEngine (so you know where things are)

- **Your pages** — both the **published** (live) version and the **draft (saved)** version of
  every page.
- **The page tree** — the hierarchy of pages under each site.
- **Your sites** — names, **domains** (web addresses), and which **languages** they use.
- **Templates** (page layouts) and **styles** (CSS).
- **SEO** — each page's URL, redirects and meta tags.
- **Caching** — pre-rendered pages kept ready so visitors get fast responses.

So, e.g., "where is my page content stored?" → in MelisEngine's page tables; "why did my change
not appear?" → the engine's page **cache** refreshes when you **publish**.

## A3. Why this matters to you

- **Drafts are safe** — because the engine keeps *published* and *saved* versions separate, you
  can edit freely and only affect visitors when you **publish**.
- **Multilingual & multi-site** — the engine models pages per language and per site, so one
  installation can run many sites in many languages.
- **Speed** — the engine's cache is why pages load fast; publishing clears the relevant cache so
  your edits go live.

For anything beyond this — building, integrating, troubleshooting — use **Part B**.

---
---

# PART B — Technical Reference

*For developers and AI. This is the module that owns the CMS data model and the services the
whole platform builds on.*

## B1. Module metadata & dependencies

| Item | Value |
|---|---|
| Package name | `melisplatform/melis-engine` |
| Type | `melisplatform-module` |
| PHP namespace | `MelisEngine\` → `src/` (PSR-4) |
| Melis category | `cms` |
| License | OSL-3.0 |
| PHP required | `^8.1 | ^8.3` |
| dbdeploy | `true` |
| Cache | laminas-cache (filesystem + memory) |

Dependencies: `melisplatform/melis-core`, `melisplatform/melis-front` (engine loads after
front), `laminas/laminas-cache` + filesystem/memory adapters.

## B2. The trio & load order

`melis-core` → `melis-front` → **`melis-engine`** → `melis-cms`. **MelisEngine owns the CMS DB
model**; **MelisFront renders from it**; **MelisCms edits through it**. Neither sibling owns
tables — engine is the single source of truth. (See the
[MelisCms](../../../melis-cms/etc/MelisAI/doc/MelisCms.md) and
[MelisFront](../../../melis-front/etc/MelisAI/doc/MelisFront.md) docs.)

```
  MelisCms (BO) ─┐  read/write via gateways+services   ┌─ MelisEngine ─┐
                 ├────────────────────────────────────▶│  DB model +    │
  MelisFront ────┘  render via gateways+services        │  services +    │
                                                         │  cache + plugin│
                                                         │  base class    │
                                                         └────────────────┘
```

## B3. The CMS data model (owned here)

Key tables (base: `install/sql/setup_structure.sql`; model: `install/sql/Model/`):

| Table | Role |
|---|---|
| `melis_cms_page_tree` | Page hierarchy (parent `tree_father_page_id`, order) |
| `melis_cms_page_published` | Published (live) page version |
| `melis_cms_page_saved` | Saved/draft page version (edited in the BO) |
| `melis_cms_page_lang` | Page ↔ language links (multilingual versions) |
| `melis_cms_lang` | CMS languages/locales |
| `melis_cms_site` | Sites (root of pages) |
| `melis_cms_template` | Templates (layout/controller/action or PHP path) |
| `melis_cms_page_seo` | Per-page SEO (URL, redirect/301, meta title/description, canonical) |
| `melis_cms_site_domain` | Site domains per environment |
| `melis_cms_site_301` / `melis_cms_site_404` | Site 301 redirects / 404 mapping |
| `melis_cms_page_default_urls` | Pre-computed page URLs (cache table) |
| `melis_cms_style` / `melis_cms_page_style` | Styles (CSS) and page↔style links |
| `melis_cms_platform_ids` | Page-id allocation ranges per environment |
| `melis_cms_site_config` / `_home` / `_langs` | Site config / home per lang / active languages |
| `melis_cms_site_robot` | robots.txt per domain (edited by the *site-robot* module) |
| `melis_cms_mini_tpl_*` | Mini-template categories/templates/flags |
| `melis_cms_gdpr_texts` | GDPR banner texts per site/language |
| `melis_site_translation` / `_text` | Site-wide translation strings |

### Table gateways (`src/Model/Tables`) — the only sanctioned data path

Registered as `service_manager` aliases. Other modules read/write **only** through these (never
raw SQL): `MelisEngineTablePageTree`, `…PagePublished`, `…PageSaved`, `…PageLang`,
`MelisEngineTableCmsLang`, `…Site`, `…Template`, `…PageSeo`, `…SiteDomain`, `…Site301`, `…Site404`,
`…Style`, `…PageStyle`, `…PlatformIds`, `…PageDefaultUrls`, `…CmsSiteHome`, `…CmsSiteLangs`,
`…CmsSiteConfig`, `…Robot`, `…FlaggedTemplate`, `…CmsSiteBundle`. They extend a generic table base
(`getEntryById`, `getEntryByField`, `save`, `deleteById`, `fetchAll`…).

## B4. Services (`src/Service`)

| Service alias | Role |
|---|---|
| `MelisEnginePage` (`MelisPageService`) | Resolve a page (tree+template+SEO+lang+style) by id & mode (`published`/`saved`), cached — `getDatasPage()` |
| `MelisEngineTree` (`MelisTreeService`) | Tree nav: `getPageChildren()`, `getPageFather()`, `getPageBreadcrumb()`, `getPageLink()`, search |
| `MelisEngineTemplateService` | Template lookup (`getTemplate()`), cached |
| `MelisEngineSiteService` / `MelisEngineSiteDomainService` | Site data / domain → site |
| `MelisEngineLang` (`MelisEngineLangService`) | Languages: available, locale ↔ id, site language |
| `MelisEngineSEOService` | Per-page SEO (`getSEOById()`) |
| `MelisEnginePageDefaultUrlsService` | Pre-computed page URL lookups |
| `MelisEngineStyle` (`MelisEngineStyleService`) | Site styles / page CSS |
| `MelisEngineCacheSystem` | The cache orchestrator (§B6) |
| `MelisSearch` | Full-text (Lucene-style) index used by front search |
| `MelisEngineSendMail` | Email utility |
| `MelisGdprService` / `MelisGdprAutoDeleteService` | GDPR banner texts / auto-delete framework |
| `MelisEngineComposer` | Composer/dependency ops |

Many extend MelisCore's `MelisGeneralService`, firing `*_start` / `*_end` events (e.g.
`melisengine_service_get_available_languages_start`/`_end`) other modules can hook.

## B5. The templating-plugin contract (the plugin base class)

`src/Controller/Plugin/MelisTemplatingPlugin.php` is the **abstract base for every content
plugin** in the platform (MelisFront's Tag/Menu/Breadcrumb…, and tool-module plugins: News,
Slider, Category2, Prospects…). It defines `front()` (render on the live site, abstract),
`back()` (the BO container/edit view), config persistence in the page XML
(`loadDbXmlToPluginConfig()` / `savePluginConfigToXml()`), GET/POST loading, preview mode and
responsive width. **MelisFront renders** subclasses' `front()`; **MelisCms edits** via `back()`.
Engine also ships form factories used across the BO: `MelisEnginePluginTemplateSelect`,
`MelisEngineSiteSelect`.

## B6. Caching

`MelisEngineCacheSystem` over laminas-cache, two tiers (config `caches`):
- **Memory** (per-request): page/service/lang/plugin caches (`engine_memory_cache`,
  `engine_page_services`, `engine_lang_services`, `templating_plugins`).
- **Filesystem** (persistent, `../cache`): rendered page/template output (`engine_file_cache`,
  `meliscms_page`), with serializer + non-throwing exception handler.

API: `getCacheByKey()` / `setCacheByKey()` / `deleteCacheByPrefix()`. Page caches are keyed by
page id + mode. MelisFront caches rendered pages here; MelisCms invalidates them on publish/save.

## B7. Controllers & listeners

- **Controllers**: only setup/maintenance (`MelisSetup*`). No editing UI.
- **Listeners** (`src/Listener`): two micro-service listeners hooking
  `melis_core_microservice_amend_data` to expose tree/page methods (`getPageChildren`,
  `getPageFather`, `getDomainByPageId`, `getDatasPage`) and inject domain-qualified URLs.

## B8. How the siblings consume MelisEngine

- **Front render**: domain→site (`MelisEngineSiteDomainService`/`SiteService`) → page+template
  (`MelisEnginePage`, `MelisEngineTemplateService`) → menus/links (`MelisEngineTree`) →
  SEO/styles (`MelisEngineSEOService`, `MelisEngineStyle`) → cache (`MelisEngineCacheSystem`);
  plugins extend `MelisTemplatingPlugin`.
- **BO editing**: read/write the page model via gateways (`…PageTree`, `…PageSaved`,
  `…PagePublished`, `…PageSeo`, `…PageLang`, `…PageStyle`, `…Template`, `…Site`, …) + services;
  publish copies saved→published and clears the page cache.
- **Tool modules**: also consume gateways — e.g. *site-robot* via `MelisEngineTableRobot`.

## B-ex. Developer recipes (examples)

**Read a page and navigate the tree** (the two services you'll use most):

```php
$pageSvc = $sm->get('MelisEnginePage');
$page    = $pageSvc->getDatasPage($idPage);            // 'published' (live) by default
$draft   = $pageSvc->getDatasPage($idPage, 'saved');   // the working draft
// $page->getMelisPageTree(), ->getMelisPage(), ->getMelisPageSeo() … hydrated objects

$tree       = $sm->get('MelisEngineTree');
$children   = $tree->getPageChildren($idPage, 1);       // 1 = published only
$breadcrumb = $tree->getPageBreadcrumb($idPage);
$url        = $tree->getPageLink($idPage, true);        // true = absolute
```

**Read/write through a table gateway** (never raw SQL):

```php
$seoTable = $sm->get('MelisEngineTablePageSeo');
$seo      = $seoTable->getEntryByField('plang_page_id', $idPage)->current();   // a row
$seoTable->save(['seo_meta_title' => 'New title'], $existingSeoId);            // upsert
```

**Resolve a site from a domain / get a template:**

```php
$site = $sm->get('MelisEngineSiteDomainService')->getSiteByDomain('www.example.com');
$tpl  = $sm->get('MelisEngineTemplateService')->getTemplate($tplId);
```

**Cache a computed result:**

```php
$cache = $sm->get('MelisEngineCacheSystem');
$cache->setCacheByKey('mykey', 'my_cache_config', $value);
$value = $cache->getCacheByKey('mykey', 'my_cache_config');
$cache->deleteCacheByPrefix('page_' . $idPage, 'meliscms_page');   // invalidate a page
```

**Listen to a service event** (engine services extend `MelisGeneralService`):

```php
$sharedEvents->attach('MelisEngine', 'melisengine_page_getdatas_end', function ($e) {
    $p = $e->getParams();          // includes the page id and the 'results'
    // alter $p['results'] before it's returned, etc.
}, 50);
```

**Build a content block:** subclass `MelisEngine\Controller\Plugin\MelisTemplatingPlugin`,
implement `front()` (live render) and rely on the base for `back()` (BO container), config
XML persistence and preview. See the News/Slider module docs for end-to-end examples.

## B9. Quick code map

```
melis-engine/
├── composer.json                 → deps (core + front + laminas-cache), category cms, dbdeploy
├── config/module.config.php      → service & table-gateway aliases, caches, form factories
├── src/
│   ├── Module.php                → bootstrap, micro-service listeners
│   ├── Model/Tables/             → 30+ MelisEngineTable* gateways (the CMS data model)
│   ├── Model/Hydrator/           → page/result hydrators
│   ├── Service/ (+ Factory/)     → Page, Tree, Template, Site, Lang, Cache, SEO, Style, Search, Gdpr…
│   ├── Controller/ (+ Plugin/)   → setup controllers + MelisTemplatingPlugin (plugin base class)
│   ├── Form/Factory/             → MelisEnginePluginTemplateSelect, MelisEngineSiteSelect
│   └── Listener/                 → micro-service listeners (tree/page)
├── install/                      → SQL (setup_structure + dbdeploy) — the CMS schema
└── etc/                          → MarketPlace + MelisAI/doc (this doc)
```

## B10. Glossary

- **Published vs saved** — live vs draft version of a page; publishing copies saved→published.
- **Page tree** — the hierarchy under a site (`melis_cms_page_tree`).
- **Template** — layout/controller/action (or PHP file) rendering a page type.
- **Table gateway** — a `MelisEngineTable*` wrapping one table; the only data path for other modules.
- **Templating plugin** — content block extending `MelisTemplatingPlugin` (`front()`/`back()`).

---

*Document for AI consumption (MelisAI MCP) — `melisplatform/melis-engine`. Part A = short
functional intro; Part B = the technical reference to read before building. Part of the
MelisCms / MelisFront / MelisEngine trio. Last reviewed 2026-06-08.*
