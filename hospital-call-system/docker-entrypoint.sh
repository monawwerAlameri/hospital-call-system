#!/bin/bash
# ============================================================
#  HOSPITAL CALL SYSTEM — Docker Entrypoint  v3.1
#  ----------------------------------------------------------------
#  Render and most cloud Docker hosts inject a PORT env var
#  (default 10000 on Render's free tier). We need Apache to
#  listen on that port at runtime, not at build time.
# ============================================================
set -e

# Default to port 80 if PORT is not set (local Docker / Railway uses 80)
PORT=${PORT:-80}
echo "[entrypoint] Starting Apache on port ${PORT}..."

# Update Apache ports.conf
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf

# Update the default vhost to listen on the same port
VHOST_FILE=/etc/apache2/sites-enabled/000-default.conf
if [ -f "$VHOST_FILE" ]; then
    sed -i "s/:80>/:${PORT}>/" "$VHOST_FILE"
    sed -i "s/:443>/:${PORT}>/" "$VHOST_FILE" || true
fi

# Show a friendly diagnostic line so the user can see what's happening
echo "[entrypoint] DB_HOST=${DB_HOST:-<not set>}  DB_NAME=${DB_NAME:-hospital_call_system}  DB_PORT=${DB_PORT:-3306}"

# Start Apache in the foreground (so Docker keeps the container alive)
exec apache2-foreground
