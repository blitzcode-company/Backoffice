FROM ggmartinez/laravel:php-82
RUN yum install -y \
    libldap2-devel \
    libzip-devel \
    zip \
    unzip \
    nodejs \
    npm \
    php-ldap \
    && yum clean all
WORKDIR /app
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
