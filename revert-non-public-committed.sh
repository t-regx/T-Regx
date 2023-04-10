#!/usr/bin/env bash

set -euo pipefail

pushd "$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)"

if [[ -n "$(git status --untracked-files=no --porcelain --short)" ]]; then
    echo 'Uncommitted changes. Please clean up.' >&2
    exit 1
fi

git checkout phpstan/master
git merge --no-ff --no-commit phpstan/develop || true

# Remove newly added files from the merge
git status --porcelain --untracked-files=no \
    | grep -e '^A ' -e '^DU ' \
    | sed -r 's/^[ADMU ?]{2} //' \
    | xargs git rm -f

internal_paths='^composer|^phpstan\.neon|/Internal/(?!EntryPoints\.php)|^test/'

# Remove changes in the internal files from the merge
git status --porcelain --untracked-files=no \
    | sed -r 's/^[ADMU ?]{2} //' \
    | grep --perl-regexp --regexp="$internal_paths" \
    | xargs git reset HEAD --

# Revert unstaged changes
git checkout .

# Commit merge
git commit
