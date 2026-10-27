# WEb 2012-2017

My PHP framework WEb, the continuation of RudWEb 5.0, from its own Subversion repository (2012-03) to the last copy of its
trunk (2017-03): versions 5 to 7, with their libraries (ACL, router, security, I18n, mail, logging, database, forms), a
default app and Bash generators. A historical archive, kept read-only: PHP 5 code, not safe to deploy.

## Its place in the line

RudWEb → WEb is my PHP framework line, one archive per era:

1. [rudweb-2007-2009](https://github.com/webservco-legacy/rudweb-2007-2009): the first engine and RudWEb 0.1 to 0.8;
2. [rudweb-2009-2011](https://github.com/webservco-legacy/rudweb-2009-2011): RudWEb 1 to 4 in Subversion, then 5.0, the
   rewrite from scratch; [rudwi](https://github.com/webservco-legacy/rudwi), the web desktop, and
   [rudweb-extras](https://github.com/webservco-legacy/rudweb-extras), the tutorials, skins and logos, belong to it;
3. **web-2012-2017** (this one): WEb 5 to 7;
4. [web7](https://github.com/webservco-legacy/web7): WEb7 (the last revision here) as a git archive;
5. the framework in git since 2017: [framework, branch v9](https://github.com/webservco/framework/tree/v9), then
   [framework](https://github.com/webservco/framework) (v10 to v13) and the WebServCo components.

## History

| Date | Commit | |
|---|---|---|
| 2012-03-14 | 35b6919 | WEb in Subversion: "import WEb version 5.0" (the code of RudWEb 5.0, with a sandbox app) |
| 2013-07-05 | 999d548 | "Version 6!" |
| 2013-07-06 | 527f1b8 | "VERSION 7" |
| 2013-07-26 | 21f8314 | the branch `preWEb7`: a copy of the code before the WEb7 changes |
| 2014-04-26 | 4d99598 | WEb7's major changes ("backward compatible, providing only improvements and fixes") and the WEb7 default app |
| 2014-10-28 | 8546b90 | the branch `working-copy-2014-10`: a file that was only in my working copy |
| 2015-03-10 | a28fc95 | the last revision in the Subversion dump (r193) |
| 2015-07-13 to 2017-03-29 | 6815be4, 76f7893, 4317239, 33b166b | four later copies of the trunk, the last one at r231 |

In 2017-12 the framework went on in git: framework-v9, then v10 to v13 on the same root.

## What is in it

- `main`: the trunk, r1 to r193, then the four later copies; `src/WEb/` (the framework:
  `lib/`, `obj/`, `int/`, `i18n/`), `src/apps/DefaultApp/` (the default app: users, items, content; web and mobile
  skins; a MySQL Workbench model and the install SQL), `src/apps/SandboxApp/` (until 2013-07), `src/docs/tutorials/`
  (phpDocumentor tutorials), `bin/` (the Bash generators and menus), `extra/test/` (PHPUnit tests), `extra/docs/`
  (phpDocumentor's settings and output);
- `preWEb7`: the copy of 2013-07, before WEb7;
- `working-copy-2014-10`: a backup of the Workbench model that was only in my working copy.

Each Subversion revision is one commit, with its log message and a `git-svn-id:` line that gives its revision number,
but the seven revisions that only changed the files left out (below). Empty log messages read "(no log message)".

Left out of the whole history: the data of a default app installed for Italia Multimedia (two SQL files with user rows
and addresses), my guides for Italia Multimedia colleagues (internal documents) and a web font without a licence file. In
two early versions of the default app's settings a client's folder name reads `default_app`, and an address in the SMTP
settings reads `user@example.com`; passwords read `REDACTED`.

## How it was built

From the Subversion dump of the repository (193 revisions) and four later copies of the trunk, turned into git in 2026
(190 commits on `main`) with `git svn`, the dates and messages kept. The last commit before this README on each
branch sets LF line endings and indents with spaces (not in the third-party code).

## Third-party code

Bundled as it shipped: the Facebook PHP SDK 3.2.3 (Apache 2.0, with its CA bundle), jQuery 1.10.2 (MIT), Modernizr 2.6.2
(MIT), normalize.css (MIT), parts of HTML5 Boilerplate (MIT: the touch icons, `robots.txt`), and the Bitstream Vera Mono
font (Bitstream Vera licence). `extra/docs/output/` is phpDocumentor's output for my code, with its template's images.

## Licence

New BSD (see `LICENSE`), added in 2026. WEb shipped the New BSD licence from its first revision (`src/LICENSE`), as
RudWEb 5.0 did.
