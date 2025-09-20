#!/bin/bash

# Nutrilux MVP Continuous Sync with fswatch
echo "🔄 Pokretanje kontinuiranog sync-a za Nutrilux..."

SOURCE_PATH="/Users/elmirbesirovic/Desktop/projects/nutrilux-mvp/theme-nutrilux"
TARGET_PATH="/Users/elmirbesirovic/Local Sites/nutrilux/app/public/wp-content/themes/theme-nutrilux"

# Provjera da li je fswatch instaliran
if ! command -v fswatch &> /dev/null; then
    echo "⚠️  fswatch nije instaliran. Instaliraj sa: brew install fswatch"
    echo "💡 Za sada pokrećem jedan sync..."
    ./sync-local.sh
    exit 1
fi

# Inicijalni sync
echo "🚀 Inicijalni sync..."
./sync-local.sh

echo ""
echo "👀 Pokretam file watcher..."
echo "💡 Pritisni Ctrl+C da zaustaviš"
echo ""

# Kontinuirani sync sa fswatch
fswatch -o "$SOURCE_PATH" | while read f; do
    echo "📝 Promjena detektovana - synciranje..."
    rsync -avz --delete "$SOURCE_PATH/" "$TARGET_PATH/"
    echo "✅ Sync završen $(date +%H:%M:%S)"
    echo ""
done