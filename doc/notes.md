#### Exemples curl pour l'api

```bash
# get categories 
$ curl -i  http://localhost:8080/categories

# create album 
$ curl -X POST -i http://localhost:8080/albums --data '{"albumName" : "Album Test", "category" : "1"}' --header "Content-Type: application/json" 

# upload image 
$ curl -X POST http://localhost:8080/images/1 -F "image=@/home/eldorico/Bureau/2011 - Ecusson Choiseul - Grand.jpg"

# get img url 
$ curl http://localhost:8080/images/singleImage/1?miniature=true 

# get album infos
$ curl http://localhost:8080/albums?category=1 -s
```



#### Commandes utiles

curl et jq : afficher au format JSON

```bash
$ curl http://localhost:8080/albums -s | jq .
```

Docker

```bash
# lancer les dockers
docker-compose build
docker-compose up -d

# stopper les dockers 
docker-compose down 

# lister les dockers (containers) en cours d'execution
docker container list

# entrer dans un docker 
docker exec -it ${containerId} bash

# supprimer un container 
docker container rm ${containerId}
```

Api renderer

```bash
# mettre à jour la doc
aglio -i documentation.apib  -o doc.html
```

Git

```bash
# pousser les commits sur le repo distant
git push 

# puller les commits depuis le repo distant
git pull 

# commiter les changements (ouvrir le gui)
git citool
```



#### Liens chouettes

- APIBlueprint: https://apiblueprint.org/
  - Renderer:  https://github.com/danielgtaylor/aglio
- Slim framework :  https://www.slimframework.com/
- Eloquent with Slim https://www.youtube.com/watch?v=AcdzW1hBa7o

