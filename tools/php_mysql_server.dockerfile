FROM eldorico/lamp
RUN rm /var/www/html/index.html
ADD api_server_entrypoint.sh /api_server_entrypoint.sh
ADD php.ini /etc/php/7.2/apache2/php.ini
