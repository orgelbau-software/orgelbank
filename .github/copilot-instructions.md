# Copilot instructions

## Project overview

Orgelbank is a server-rendered PHP application for managing organ maintenance, congregations, contacts, projects, time tracking, and invoices. It uses MySQL, global PHP classes (not namespaces or Composer PSR-4 autoloading), and HTML templates under `web/tpl`.

## Architecture and change flow

- `index.php` loads `conf/config.inc.php`, starts the session, authenticates the user, and includes `src/controller/controller.Main.php`.
- `conf/classes.inc.php` explicitly includes application classes in dependency order. When adding a PHP class, add its include here; adding a source file alone does not make it available at runtime. Third-party packages are loaded separately through `vendor/autoload.php`.
- `src/controller/controller.Main.php` is the route registry: numeric `page` and `do` values map to controller methods and specify minimum user levels. Register new actions there and keep their access level consistent with the feature. `index.php` also uses the page number to select the navigation menu and page assets.
- Controllers in each feature area typically pass work to an action through `RequestHandler::handle()`. Actions implement the applicable GET/POST handler and validator interfaces, then validate, prepare, and execute the request; the resulting `Template` is rendered by the request handler.
- Keep feature code together in its existing `src/<feature>/` area. Persistent domain objects live in `src/entities/` and generally extend the database storage base classes in `src/core/db/`; these objects explicitly map database columns to properties in their load/save methods. Update the matching SQL schema or migration under `resources/db/` when changing persistent fields or tables.
- Runtime settings and database credentials are supplied by the site-local configuration included from `conf/config.inc.php`. The local config files are git-ignored; do not replace them with committed credentials.

## Repository conventions

- Follow the existing filename patterns, such as `class.Name.php`, `controller.Name.php`, and the corresponding `src/<feature>/` grouping. Classes are globally named and loaded by explicit includes.
- Keep route registration, implementation, view, and any browser-side assets in sync: route IDs and permissions in `src/controller/controller.Main.php`, controller/action code in the feature directory, templates in `web/tpl`, and JavaScript/CSS in `web/js` or `web/css`.
- Views use the project’s `Template`/`BufferedTemplate` classes and named placeholder replacement rather than a modern framework templating engine.
- Database options loaded through `ConstantLoader` are application settings stored in the database; use its existing accessors/setters rather than introducing duplicate hard-coded configuration.
- Treat `vendor/` and bundled third-party code under `lib/` as dependencies, not application source.

## Build, test, and lint

- Install PHP dependencies with `composer install`. The README’s deployment instructions target PHP 8.3.
- No first-party Composer scripts, automated test framework, or individual test selector are configured. `php selftest.php` is the repository’s integration smoke test; it requires the site-local configuration, a reachable database, and Google Maps/geocoding access. Run it only against an appropriate test environment.
- Syntax-check an individual PHP file with `php -l src\path\to\file.php` (for example, `php -l src\controller\controller.Main.php`). There is no configured project-wide linter.
