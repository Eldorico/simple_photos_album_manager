#### Commandes utiles

Docker

```bash
# lancer les dockers
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

