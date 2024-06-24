FROM ggmartinez/laravel:php-82

RUN apt-get update && apt-get install -y \
    libldap2-dev \
    libzip-dev \
    zip \
    unzip \
    php-ldap \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

RUN composer install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
