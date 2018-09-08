# choose database to import here (db files are in api/databases)
DB_FILENAME_TO_IMPORT=api_base.sql

# leave the rest unchanged for dev
service apache2 start
service mysql start

DB_NAME=app_db
DB_USER=admin
DB_PASSWORD=admin
mysql -e "CREATE DATABASE $DB_NAME"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO $DB_USER@localhost IDENTIFIED BY '$DB_PASSWORD'"
mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < /var/databases/$DB_FILENAME_TO_IMPORT

while true; do sleep 1000; done
