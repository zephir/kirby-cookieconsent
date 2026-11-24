# Ubuntu LTS (26.04 "Resolute Raccoon", ships PHP 8.5)
FROM ubuntu:26.04

# Kirby Starterkit version (git tag of getkirby/starterkit) to test against
ARG KIRBY_VERSION=6.0.0

# Add composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set timezone environment variable
ENV TZ=Europe/Berlin

# Set geographic area using above variable
# This is necessary, otherwise building the image doesn't work
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Remove annoying messages during package installation
ARG DEBIAN_FRONTEND=noninteractive

# Install packages: web server Apache, PHP and extensions
RUN apt-get update && apt-get install --no-install-recommends -y \
  apache2 \
  apache2-utils \
  ca-certificates \
  git \
  php \
  libapache2-mod-php \
  php-curl \
  php-dom \
  php-gd \
  php-intl \
  php-mbstring \
  php-xml \
  php-zip && \
  apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy virtual host configuration from current path onto existing 000-default.conf
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Remove default content (existing index.html)
RUN rm /var/www/html/*

# Clone the Kirby Starterkit
RUN git clone --depth 1 --branch "${KIRBY_VERSION}" https://github.com/getkirby/starterkit.git /var/www/html

# Fix files and directories ownership
RUN chown -R www-data:www-data /var/www/html/

# Activate Apache modules headers & rewrite
RUN a2enmod headers rewrite

# Tell container to listen to port 80 at runtime
EXPOSE 80

WORKDIR /var/www/html

COPY entrypoint.sh /usr/local/bin/
ENTRYPOINT ["entrypoint.sh"]

# Start Apache web server
CMD [ "/usr/sbin/apache2ctl", "-DFOREGROUND" ]