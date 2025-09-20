#!/usr/bin/env bash
set -euo pipefail

# Nutrilux: dev-link.sh
# Creates a symlink from this repo's theme-nutrilux to the target WordPress themes directory.
# Usage:
#   ./scripts/dev-link.sh /path/to/wp-content/themes

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SRC_DIR="$(cd "$SCRIPT_DIR/.." && pwd)/theme-nutrilux"
TARGET_THEMES_DIR="${1:-}"

if [[ -z "$TARGET_THEMES_DIR" ]]; then
  echo "Usage: $0 /absolute/path/to/wp/wp-content/themes"
  exit 1
fi

if [[ ! -d "$TARGET_THEMES_DIR" ]]; then
  echo "Error: Target themes dir does not exist: $TARGET_THEMES_DIR"
  exit 1
fi

LINK_PATH="$TARGET_THEMES_DIR/theme-nutrilux"

if [[ -L "$LINK_PATH" || -d "$LINK_PATH" ]]; then
  echo "Removing existing path: $LINK_PATH"
  rm -rf "$LINK_PATH"
fi

ln -s "$SRC_DIR" "$LINK_PATH"
echo "Symlink created: $LINK_PATH -> $SRC_DIR"
