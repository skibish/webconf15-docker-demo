FROM php:5.6-fpm

RUN apt-get update && apt-get install -y \
	libmcrypt-dev \
	libpq-dev \
	&& docker-php-ext-install mcrypt mbstring \
	&& docker-php-ext-install mysqli pdo pdo_mysql tokenizer

EXPOSE 9000

CMD ["php-fpm"]
