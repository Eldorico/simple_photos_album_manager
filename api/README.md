## Notes for developping

### Dev tools installation

TODO



### Adding database on dev

1. Create a database and export it in a .sql file in the *api/databases* folder. If you want to use phpmyadmin from the dockers launch the dockers and connect to http://localhost:8080/phpmyadmin using the accesses admin / admin. 

2. In the *tools/api_server_entrypoint.sh* : change the *DB_FILENAME_TO_IMPORT* with the correct database sql file you want to load. (dont bother the path, the *docker-compose.yml* file will use the api/databases folder to search the database file )

3. Rebuild the docker images: 

   ```bash
   tools $ docker-compose build
   ```

4. Run the dockers 

   ```bash
   tools $ docker-compose up -d
   ```

   ​



