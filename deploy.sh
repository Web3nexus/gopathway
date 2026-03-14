#!/bin/bash
# ============================================================
# GoPathway Deploy Script
# Run this locally whenever you have changes to deploy.
# ============================================================

set -e

echo "🏗  Building frontend..."
cd "$(dirname "$0")/frontend"
npm run build

echo "✅ Build complete. Committing compiled assets..."
cd ..
git add backend/public/assets/ backend/public/frontend.html -A
git commit -m "build: compile frontend assets" || echo "⚠️  Nothing to commit (assets unchanged)."

echo "🚀 Pushing to main..."
git push origin main

echo ""
echo "✅ Done! On the live server, simply run:"
echo "   git pull origin main"
echo ""
echo "Then optionally run migrations if there were schema changes:"
echo "   /opt/alt/php84/usr/bin/php artisan migrate --force"
