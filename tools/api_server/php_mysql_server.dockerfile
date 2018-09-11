FROM eldorico/lamp
RUN rm /var/www/html/index.html
RUN apt-get install php-imagick -y
ADD api_server_entrypoint.sh /api_server_entrypoint.sh
ADD php.ini /etc/php/7.2/apache2/php.ini
ADD conf_apache.conf /etc/apache2/sites-available/000-default.conf
