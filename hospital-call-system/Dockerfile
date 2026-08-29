# ============================================================
#  HOSPITAL CALL SYSTEM — Docker Image  v3.1
#  King Khalid Hospital, Hail
#  ----------------------------------------------------------------
#  Apache + PHP 8.3 + mysqli extension
#  Designed for Render / Railway / any Docker host
#  Automatically binds to the PORT env var (Render requirement).
# ============================================================
FROM php:8.3-apache

# Install mysqli extension (required by the app)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite (for .htaccess)
RUN a2enmod rewrite headers

# Configure Apache to allow .htaccess overrides
RUN sed -ri -e 's!<Directory /var/www/>!<Directory /var/www/>\\n    AllowOverride All!' /etc/apache2/apache2.conf || true

# Copy application files into the web root
COPY --chown=www-data:www-data . /var/www/html/

# Ensure the audio directory is readable (chime files are required)
RUN chmod -R 755 /var/www/html/assets/audio

# Copy the startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Apache listens on port 80 by default; Render will inject PORT env var
EXPOSE 80

# Use the startup script as the container command
CMD ["docker-entrypoint.sh"]
