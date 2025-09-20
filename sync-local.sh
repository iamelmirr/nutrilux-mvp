#!/bin/bash

# Nutrilux MVP Local Sync Script
echo "🚀 Synciranje Nutrilux tema sa LocalWP..."

SOURCE_PATH="/Users/elmirbesirovic/Desktop/projects/nutrilux-mvp/theme-nutrilux"
TARGET_PATH="/Users/elmirbesirovic/Local Sites/nutrilux/app/public/wp-content/themes/theme-nutrilux"

# Provjera da li postoji source
if [ ! -d "$SOURCE_PATH" ]; then
    echo "❌ Source direktorij ne postoji: $SOURCE_PATH"
    exit 1
fi

# Provjera da li postoji target
if [ ! -d "$TARGET_PATH" ]; then
    echo "❌ Target direktorij ne postoji: $TARGET_PATH"
    exit 1
fi

# Rsync sa verbose outputom
echo "📂 Source: $SOURCE_PATH"
echo "🎯 Target: $TARGET_PATH"
echo ""

rsync -avz --delete "$SOURCE_PATH/" "$TARGET_PATH/"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Sync završen uspješno!"
    echo "🌐 Možeš osvježiti LocalWP sajt sada"
else
    echo ""
    echo "❌ Greška tokom sync-a"
    exit 1
fi