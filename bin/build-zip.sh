#!/usr/bin/env bash
# Builds the production zip: build/wc-order-tracker-<version>.zip
# The zip contains a single top-level "wc-order-tracker/" folder and can be
# uploaded directly in WordPress → Plugins → Add New → Upload Plugin.
# Usage: bin/build-zip.sh [suffix]   e.g. "pr-12" → wc-order-tracker-1.3.0-pr-12.zip
set -euo pipefail
cd "$(dirname "$0")/.."

SLUG="wc-order-tracker"
VERSION=$(bin/check-version.sh)
SUFFIX="${1:+-$1}"
DIST="build/dist"
STAGE="$DIST/$SLUG"
ZIP="build/$SLUG-$VERSION$SUFFIX.zip"

rm -rf "$DIST" "$ZIP"
mkdir -p "$STAGE"

# Plugin files, minus everything in .distignore.
rsync -a --exclude-from=.distignore ./ "$STAGE/"

# Runtime dependency shipped with the plugin.
mkdir -p "$STAGE/vendor"
rsync -a --exclude=.DS_Store vendor/freemius "$STAGE/vendor/"

# Sanity checks.
for required in cbwct.php readme.txt inc vendor/freemius/start.php; do
	[ -e "$STAGE/$required" ] || { echo "::error::Missing $required in build" >&2; exit 1; }
done
for forbidden in node_modules tests .github composer.json package.json vendor/autoload.php; do
	[ ! -e "$STAGE/$forbidden" ] || { echo "::error::$forbidden must not ship in the production zip" >&2; exit 1; }
done

( cd "$DIST" && zip -rqX "../$(basename "$ZIP")" "$SLUG" )

echo "Built $ZIP ($(du -h "$ZIP" | cut -f1))"
if [ -n "${GITHUB_OUTPUT:-}" ]; then
	{
		echo "version=$VERSION"
		echo "zip=$ZIP"
		echo "zip_name=$(basename "$ZIP" .zip)"
		echo "dist_dir=$DIST"
	} >> "$GITHUB_OUTPUT"
fi
