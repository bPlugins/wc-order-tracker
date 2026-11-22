# Contributing

## Branches

| Branch | Purpose | Direct push |
| --- | --- | --- |
| `main` | Released code only. Every merge into `main` with a new version is released automatically. | ❌ PR only |
| `develop` | Integration branch for the next release. | ❌ PR only |
| `feature/<short-name>` | New features. Branch from `develop`, PR into `develop`. | ✅ |
| `fix/<short-name>` | Non-urgent bug fixes. Branch from `develop`, PR into `develop`. | ✅ |
| `chore/<short-name>` | Tooling, CI, docs. Branch from `develop`, PR into `develop`. | ✅ |
| `release/<x.y.z>` | Release prep (version bump, changelog). Branch from `develop`, PR into `main`. | ✅ |
| `hotfix/<x.y.z>` | Urgent fix for the live version. Branch from `main`, PR into `main`. | ✅ |

`main` and `develop` are protected: changes go in through a pull request, and the **CI passed** check must be green.

## Release flow

1. `git switch develop && git pull && git switch -c release/1.3.0`
2. Bump the version everywhere. CI fails if any of these disagree:
   - `cbwct.php`: the `Version:` header and `CBWCT_TRACKER_VERSION`
   - `readme.txt`: `Stable tag:` plus a `= 1.3.0 =` changelog entry, which becomes the GitHub Release notes
   - `package.json`: `"version"`
3. Open a PR `release/1.3.0` → `main`. QA tests the production zip posted on the PR.
4. Merge with a **merge commit**. The **Release** workflow then:
   - builds `wc-order-tracker-1.3.0.zip`;
   - creates tag `v1.3.0` and a GitHub Release with the zip and its SHA-256;
   - opens a `main` → `develop` sync PR.
5. Merge the sync PR with a **merge commit** (not squash). `main` and `develop` are now in sync.

Hotfixes use the same flow, starting from `main` on a `hotfix/x.y.z` branch.

> Feature PRs into `develop` may be squash-merged. Release, hotfix and sync PRs must use **merge commits** so the two long-lived branches keep a shared history.

## CI (`.github/workflows/ci.yml`)

CI runs on every PR into `main`/`develop` and on every push to them.

- **PHP 8.0–8.4:** `php -l` syntax check, PHPUnit (Brain Monkey, no WordPress install needed), and PHPCS on 8.3.
- **JavaScript:** ESLint (`@wordpress/scripts`) and Jest.
- **Version check:** the version must match across every file above. PRs into `main` must also use a version that has no tag yet.
- **Production zip:** built with `bin/build-zip.sh` and uploaded as an artifact. The download link is posted as a PR comment for QA.
- **CI passed:** the single required status check.

## Local setup

```bash
composer install
npm install
composer lint      # PHP syntax
composer phpcs     # coding standards (composer phpcbf to auto-fix)
composer test      # PHPUnit
npm run lint:js
npm run test:js
npm run build:zip  # → build/wc-order-tracker-<version>.zip
```

### Coding standards

The PHPCS gate (`phpcs.xml.dist`) currently enforces the security, database, i18n, WP API, PHP-compatibility and correctness rules of WPCS. The full `WordPress` standard, including formatting and docblocks, will be switched on after a dedicated reformatting PR (`composer phpcbf`). Don't mix that reformat into feature PRs.

### Production zip contents

`bin/build-zip.sh` copies the plugin minus everything listed in `.distignore`, then adds `vendor/freemius`, the only runtime dependency. If you add a runtime Composer package or a JS build step, update the script.
