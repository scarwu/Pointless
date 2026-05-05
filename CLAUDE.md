# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Pointless is a static blog generator written in PHP (>=8.4) and distributed as a single phar binary (`bin/poi`). It is built on top of the **Oni** framework (`scarwu/oni`, vendored under `src/vendor/scarwu/oni`), which supplies the dual-stack Web + CLI dispatch the app uses for the blog generator (CLI) and the local preview/editor server (Web).

Oni's own architecture (Loader singleton, `Basic::_attr` config bag, controller/task path-router with `up()`/`run()`/`down()` lifecycle, three-level View chain `index → layout → content`, and IO argv buckets `arguments`/`options`/`configs`) is documented in `src/vendor/scarwu/oni/CLAUDE.md` — read that first when touching CLI/Web routing, View rendering, or argv parsing.

## Commands

### Development setup
- `./scripts/setup.sh` — `git submodule init/update` + `composer install` (vendor lands in `src/vendor` per `composer.json` `vendor-dir`)
- `./scripts/update-sub-module.sh` — copies the editor and theme dist trees from `subModules/` into `src/sample/` so they are bundled into the phar; rerun whenever submodules update

### Running the CLI in dev (no phar)
- `./src/shell.php <task> [args] [-options] [--configs]` — direct entry that defines `APP_ENV=development` and `APP_ROOT=src/` then requires `src/boot.php`. Use this for fast iteration; `bin/poi` is the *built* phar.
- Useful tasks: `blog`, `blog init`, `blog build`, `blog deploy`, `blog backup`, `blog config`, `post`, `post add|edit|delete`, `theme`, `theme install|uninstall`, `server start|stop`, `update`. The default task when none is given is `Intro` (banner + help).
- `server start` flags: `--host=`, `--port=` (default `localhost:3000`), and in development only `--theme=<dir>` and `--editor=<dir>` to override `BLOG_THEME` / `BLOG_EDITOR` via env. The server is a backgrounded `php -S` running `src/route.php`; PID is saved to `~/.pointless5/blog.json`.

### Building the phar
- `./scripts/build.php` — assembles `temp/` from a hard-coded subset of `src/` plus selected `vendor/` paths and writes `poi.phar` at the repo root. The phar stub sets `APP_ENV=production` and `APP_ROOT=phar://poi.phar`.
- `./scripts/build.php -r` — same, but moves the result to `bin/poi` (the released binary committed to the repo).
- `temp/` is a build artifact — do not edit; `scripts/build.php` deletes and re-creates it on every run.

### Tests
PHPUnit 13 is in `require-dev` (`phpunit/phpunit: ^13`), but the repo currently has **no `tests/` directory and no `phpunit.xml`** — the test target is unconfigured. If you add tests, follow the Oni framework's convention (`Oni\Tests\` PSR-4 namespace, fixtures under `tests/fixtures/`).

### Docker
`docker/image.sh build|push` builds `scarwu/pointless:latest` from `bin/poi` (image is Alpine + PHP). `docker/wrapper.sh` is a `docker run` shim that mounts `$HOME` and runs `poi $1`.

## Runtime layout & paths

The CLI distinguishes three roots, all defined as constants during `boot.php`:

- **`APP_ROOT`** — the app's own code. Either the unpacked source tree (dev: set by `shell.php`/`route.php` to `src/`) or `phar://poi.phar` (production).
- **`HOME_ROOT`** — `$HOME/.pointless5`, created on first CLI run. Holds `blog.json` (which records the user's chosen blog path + last server PID) and the staged `route.php` used by the production preview server.
- **`BLOG_ROOT`** — the user's blog directory, read from `blog.json` (or the `BLOG_ROOT` env var, which `server start` sets when forking `php -S`). Inside `BlogCore::init()` this expands into `BLOG_BUILD`, `BLOG_DEPLOY`, `BLOG_BACKUP`, `BLOG_ASSET`, `BLOG_HANDLER`, `BLOG_EXTENSION`, `BLOG_POST`, `BLOG_THEME`, `BLOG_EDITOR`.

`BLOG_THEME` / `BLOG_EDITOR` fall back to the bundled samples under `APP_ROOT/sample/themes/Classic` and `APP_ROOT/sample/editor` if neither the blog directory nor the env var supplies them — this is what makes `server start --theme=...` work in dev for editing a theme in `subModules/`.

## Architecture

The Pointless app is a thin layer of Pointless namespaces registered onto Oni:

```
Pointless\Library    src/libraries     — BlogCore, Resource, Utility, CustomException
Pointless\Extend     src/extends       — abstract base classes: Task, Format, Extension, ThemeHandler
Pointless\Format     src/formats       — Article, Describe (post types)
Pointless\Handler    src/handlers      — Archive, Article, Category, Describe, Page, Tag (theme data assemblers)
Pointless\Extension  src/extensions    — Atom, Sitemap (extra files emitted at build time)
Pointless\Task       src/tasks         — CLI command tree
Pointless\Controller src/controllers   — Web routes for the preview/editor server
```

`boot.php` registers `Library`, `Extend`, `Format` with `Loader::append(...)` unconditionally. `Handler` and `Extension` are registered lazily — once for Web in `router/event/up`, and inside individual tasks (e.g. `BuildTask::up`) for CLI — and they are registered against **two paths each** so the user's blog directory (`BLOG_HANDLER`, `BLOG_EXTENSION`) can override or supplement the bundled ones.

### Resource (`Pointless\Library\Resource`) is the global service bag

Cross-cutting state is shared via four well-known keys, set during boot/init and read everywhere:

- `system:constant` — version + the canonical `formats` list (`['Article', 'Describe']` from `src/constant.php`). When you add a new post format, add its short name here.
- `blog:config` — the user's `BLOG_ROOT/config.php` (sample at `src/sample/config.php`). Includes the `theme`, `extension`, `post`, `deploy`, `backup`, `server` sub-trees.
- `theme:config` — the active theme's `config.php` (declares which `handlers`, `extensions`, and `views.side` / `views.container` lists to use during build). The build pipeline iterates exactly these arrays — adding a new handler/extension means editing the theme's config, not just dropping a file.
- `theme:constant` — the theme's `constant.php`.

### Two compiled flows

**Build (`Blog\BuildTask`)** — the headless render path that produces the deployable site:

1. Wipe `BLOG_BUILD`, copy `BLOG_ASSET` and `BLOG_THEME/assets` into it.
2. For each format in `system:constant.formats`, instantiate `Pointless\Format\<Name>`, load posts from `BLOG_POST/<type>/*.md` via `BlogCore::getPostList($type, true)` (which parses the markdown header + body), filter out non-public posts, and run `convertPost()` to produce theme-ready records. Result is `$postBundle[type][]`.
3. For each `handlers` entry in `theme:config`, instantiate the handler, call `initData([...all four resources..., postBundle])`, then `getContainerDataList()` to obtain `path => container` pairs.
4. Render each container through `Oni\Web\View` with `setContentPath("container/{name}")`. Pages that don't end in `.html`/`.xml` get written as `<path>/index.html`.
5. For each `extensions` entry in `theme:config`, call `Extension::render($data)` and write the file to its declared `path` (e.g. `atom.xml`).

**Web preview (`Pointless\Controller\MainController`)** — the live equivalent. `up()` rebuilds the same `$postBundle` + handler list per request (no caching), but here non-public posts are *kept* with a 🔒 prefix so the author can preview them. Each top-level URL segment maps to a method (`indexAction`, `articleAction`, `pageAction`, `archiveAction`, `categoryAction`, `tagAction`, `editorAction`) that looks up its container in the matching handler. `editorAction` swaps the View's index template to `BLOG_EDITOR/views/index` to host the bundled markdown editor SPA.

The implication: a content/handler/extension change should be exercised against **both** flows. The Web controller does not call the Build task, and vice versa — they reimplement the same assembly independently.

### Post format pattern

A `Format` declares `$type` (the directory under `BLOG_POST/`), `$name` (menu label), and `$questionList` (prompts shown by `post add`). It implements `convertInput($answers)` (turns interactive answers into the markdown header) and `convertPost($post)` (turns a parsed post into the data shape handlers expect). To add a new format: create the class in `src/formats/`, then add its short name to `src/constant.php`'s `formats` array — both `BuildTask` and `MainController` iterate that list.

### Theme handler pattern

A `ThemeHandler` (subclass of `Pointless\Extend\ThemeHandler`) declares `$type`, implements `initData($data)` (called once with the full bundle), and provides `getSideData()` and/or `getContainerDataList()`. The build/preview pipelines call them based on whether the theme lists the handler under `views.side` (sidebar widget) or `views.container` (renderable page). User-side handlers in `BLOG_HANDLER` shadow bundled ones because `Loader::append('Pointless\Handler', BLOG_HANDLER, ...)` registers blog dir before app dir.

### CLI task hierarchy

Top-level tasks (`BlogTask`, `PostTask`, `ThemeTask`, `ServerTask`, `UpdateTask`, `IntroTask`) live in `src/tasks/`; their subcommands live in `src/tasks/<Name>/<Sub>Task.php` and are reached by Oni's path-walking router (e.g. `poi blog build` → `Pointless\Task\Blog\BuildTask`). All extend `Pointless\Extend\Task` (which extends `Oni\CLI\Task`) and gain helpers like `showBanner()`, `selectFormatItem()`, `selectPostData($type)`, `selectThemeData()`, `editFile($path)`. Tasks that need a blog must call `BlogCore::init()` in their own `up()` — `BlogTask::up`, `BuildTask::up`, `Server\StartTask::up`, etc. all do this and bail out if init fails.

## Conventions

- Commit messages in this repo follow the pattern `1. <verb> <subject>` (e.g. `1. update components`, `1. refactor`). Match this style.
- The vendor tree (`src/vendor/`) is `.gitignore`d; do not commit it. `temp/` and `poi.phar` are also ignored. The compiled `bin/poi` *is* committed (it's the user-facing release artifact).
- Themes and editor assets ship as **git submodules** under `subModules/`. Editing a theme means editing it in its own repo; `update-sub-module.sh` then copies its `dist/` into `src/sample/themes/<Name>/` so the phar build can pick it up.
- `APP_ENV` gates a few behaviors beyond error reporting: only `development` exposes `--theme`/`--editor` overrides on `server start`, and the production phar uses a copied `route.php` in `HOME_ROOT` rather than the in-source one.
