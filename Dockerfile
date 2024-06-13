# Utilisez l'image PHP officielle avec Apache
FROM php:7.4-apache

# Installez les dépendances nécessaires pour Composer et PDO
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiez le contenu de votre application dans le conteneur
COPY . /var/www/html
WORKDIR /var/www/html

# Exécutez Composer pour mettre à jour et installer les dépendances
RUN composer update
RUN composer install

# Réglez les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configurez Apache pour permettre l'accès au répertoire
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
<Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
</Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Activez les modules Apache nécessaires
RUN a2enmod rewrite

# Installez Node.js (ajouté à partir de votre configuration)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash \
    && apt-get install -y nodejs
