#!/usr/bin/env bash

set -euo pipefail

pushd "$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)"

if [[ "$(git branch --show-current)" != 'phpstan/develop' ]]; then
    echo 'Wrong branch. Consider a cup of coffee.' >&2
    exit 1
fi

cp revert-non-public-committed.sh \
     revert-non-public-untracked.sh

bash revert-non-public-untracked.sh
