FROM nginx:latest
#
COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

COPY ./docker/nginx/ssl/nginx-selfsigned.crt /etc/nginx/ssl/nginx-selfsigned.crt
COPY ./docker/nginx/ssl/nginx-selfsigned.key /etc/nginx/ssl/nginx-selfsigned.key

COPY ./../../public /var/www/html/symfony-fusionauth-task/public
