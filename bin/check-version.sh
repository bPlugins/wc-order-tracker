#!/usr/bin/env bash
# Verifies the plugin version is identical everywhere it is declared.
# Usage: bin/check-version.sh [expected-version]   (e.g. 1.3.0 or v1.3.0)
# Prints the version on success.
set -euo pipefail
cd "$(dirname "$0")/.."

header=$(sed -n 's/^[[:space:]]*Version:[[:space:]]*\([^[:space:]]*\).*/\1/p' cbwct.php | head -1)
constant=$(sed -n "s/.*define([[:space:]]*'CBWCT_TRACKER_VERSION',[[:space:]]*'\([^']*\)'.*/\1/p" cbwct.php | head -1)
stable=$(sed -n 's/^Stable tag:[[:space:]]*\([^[:space:]]*\).*/\1/p' readme.txt | head -1)
pkg=$(node -p "require('./package.json').version" 2>/dev/null || echo "")

fail=0
for pair in "CBWCT_TRACKER_VERSION:$constant" "readme.txt Stable tag:$stable" "package.json version:$pkg"; do
	name="${pair%%:*}"; value="${pair#*:}"
	if [ "$value" != "$header" ]; then
		echo "::error::Version mismatch — plugin header is '$header' but $name is '$value'" >&2
		fail=1
	fi
done

if [ "${1:-}" != "" ]; then
	expected="${1#v}"
	if [ "$expected" != "$header" ]; then
		echo "::error::Expected version '$expected' but plugin header is '$header'" >&2
		fail=1
	fi
fi

if ! grep -q "^= $header =" readme.txt; then
	echo "::warning::readme.txt changelog has no '= $header =' entry" >&2
fi

[ "$fail" -eq 0 ] || exit 1
echo "$header"
