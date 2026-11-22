## What & why

<!-- What does this PR change, and why? Link the issue if there is one. -->

## Type

- [ ] Feature (`feature/*` → `develop`)
- [ ] Bug fix (`fix/*` → `develop`)
- [ ] Hotfix (`hotfix/*` → `main`, version bumped)
- [ ] Release (`release/*` → `main`, version bumped)
- [ ] Chore / docs / CI

## QA

<!-- Steps for QA. The production zip link is posted on this PR automatically once CI passes. -->

1.

## Checklist

- [ ] `composer phpcs`, `composer test`, `npm run lint:js` and `npm run test:js` pass locally
- [ ] Release/hotfix only: version bumped in `cbwct.php` (header + `CBWCT_TRACKER_VERSION`), `readme.txt` (Stable tag + changelog) and `package.json`
