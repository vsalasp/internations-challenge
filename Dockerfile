FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    zsh \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    g++ \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www
# Install extensions
RUN docker-php-ext-install intl opcache pdo pdo_mysql mysqli zip exif pcntl mbstring

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
#COPY docker /var/www

# Copy existing application directory permissions
COPY --chown=www:www docker /var/www

# Change current user to www
USER www

# Install OhMyZsh
RUN git clone https://github.com/ohmyzsh/ohmyzsh.git ~/.oh-my-zsh
RUN cp ~/.oh-my-zsh/templates/zshrc.zsh-template ~/.zshrc

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
