# --------------------------------------------------------------
# choose database to import here (db files are in api/databases)
# --------------------------------------------------------------
# DB_FILENAME_TO_IMPORT=api_base.sql
DB_FILENAME_TO_IMPORT=app_webapp_test1.sql
# DB_FILENAME_TO_IMPORT=app_webapp_test2.sql

# --------------------------------------------------------------
# leave the rest unchanged for dev
# --------------------------------------------------------------

# enable mod rewrite
a2enmod rewrite

# start the services 
service apache2 start
service mysql start

# create the database and populate it with DB_FILENAME_TO_IMPORT
DB_NAME=app_db
DB_USER=admin
DB_PASSWORD=admin
mysql -e "CREATE DATABASE $DB_NAME"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO $DB_USER@localhost IDENTIFIED BY '$DB_PASSWORD'"
mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < /var/databases/$DB_FILENAME_TO_IMPORT

# start showing the access log
tail -f /var/log/apache2/access.log
