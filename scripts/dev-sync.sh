#!/usr/bin/env bash
set -euo pipefail

# Nutrilux: dev-sync.sh
# Syncs the local theme folder to a LocalWP (or other) WordPress theme directory.
# Usage:
#   ./scripts/dev-sync.sh /path/to/wp-content/themes/theme-nutrilux         # one-time sync
#   ./scripts/dev-sync.sh --watch /path/to/wp-content/themes/theme-nutrilux  # continuous sync (uses fswatch if available)

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$(cd "$SCRIPT_DIR/.." && pwd)/theme-nutrilux"

WATCH=false
DEST=""

if [[ $# -eq 0 ]]; then
  echo "Usage: $0 [--watch] /absolute/path/to/wp/wp-content/themes/theme-nutrilux"
  exit 1
fi

if [[ "$1" == "--watch" ]]; then
  WATCH=true
  shift 1 || true
fi

DEST="${1:-}"
if [[ -z "$DEST" ]]; then
  echo "Error: Destination path is required."
  exit 1
fi

if [[ ! -d "$DEST" ]]; then
  echo "Destination does not exist, creating: $DEST"
  mkdir -p "$DEST"
fi

RSYNC_OPTS=(
  -avz --delete
  --exclude '.git/'
  --exclude '.DS_Store'
  --exclude 'node_modules/'
  --exclude '*.zip'
  --exclude '*.psd'
  --exclude '*.ai'
)

sync_once() {
  rsync "${RSYNC_OPTS[@]}" "$SRC_DIR/" "$DEST/"
  echo "[dev-sync] Synced to: $DEST at $(date '+%H:%M:%S')"
}

sync_once

if $WATCH; then
  if command -v fswatch >/dev/null 2>&1; then
    echo "[dev-sync] Watching for changes with fswatch… (Ctrl-C to stop)"
    fswatch -o "$SRC_DIR" | while read -r _; do
      sync_once
    done
  else
    echo "[dev-sync] fswatch not found. Falling back to polling every 2s… (Ctrl-C to stop)"
    while true; do
      sync_once
      sleep 2
    done
  fi
fi
