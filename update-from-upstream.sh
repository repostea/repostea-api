#!/bin/bash

echo "🚀 Fetching changes from upstream..."
git fetch upstream

echo "🔀 Merging upstream/main into current branch..."
git merge upstream/main

if [ $? -ne 0 ]; then
  echo "⚠️  Merge conflict detected. Resolve it manually."
  exit 1
fi

echo "📦 Running composer install..."
composer install --no-interaction --prefer-dist

echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Update complete. You're up to date with upstream."
