
import Vue from 'vue';
import  { EventBus } from './EventBus.js';

let data;
export default data = {
    categories : [],
    albumsSortedByCategory : {},
    allAlbums : [],
    albumsSortedById : {},
    photosSortedById : {},
    allAlbumsPhotosUrls : {},

    getCategories : function() {
        Vue.http.get('categories')
          .then(response =>{ return response.json(); })
          .then(data =>{
              for(var i=0; i<data['categories'].length; i++){
                  this.categories.push({'id': data['categories'][i]['id'],
                  'name': data['categories'][i]['name']});
              }
              this.getAllAlbumsInfo();
        });
    },
    getAllAlbumsInfo : function(){
        var eventBus = EventBus;
        Vue.http.get('albums')
          .then(response =>{ return response.json(); })
          .then(data =>{
              // sort albums by name
              data['albums'].sort(function(el1, el2){
                  if(el1.albumName < el2.albumName) return -1;
                  if(el1.albumName > el2.albumName) return 1;
                  return 0;
              });

              // create the data albums
              for(var i=0; i<this.categories.length; i++){
                  this.albumsSortedByCategory[this.categories[i]['id']] = [];
              }
              for(var i=0; i<data['albums'].length; i++){
                  var album = data['albums'][i];
                  this.allAlbums.push(album);
                  this.albumsSortedByCategory[album['category']].push(album);
                  this.albumsSortedById[album['id']] = album;
                  this.allAlbumsPhotosUrls[album['id']] = [];

                  // emit album info changed
                  eventBus.$emit('albumInfosChanged', data['albums'][i]['id']);
              }

          });
    },
    getAlbum : function(albumId){
        if(albumId in this.albumsSortedById){
            return this.albumsSortedById[albumId];
        }else{
            return -1;
        }
    },
    getAlbumMiniatureUrl : function(idAlbum){
        var album = this.getAlbum(idAlbum);
        if(album == -1){
            console.error("getAlbumMiniatureUrl("+idAlbum+") : album doesnt exists");
            return;
        }

        // if there is allready a miniature in album, return the miniature.
        if('miniatureURL' in album && album['miniatureURL'] != 'NO_PHOTO_ERROR' && album['miniatureURL'] != 'WAITING_FOR_MINIATURE'){
            return album['miniatureURL'];
        }
        // if there is no photo in album, add "NO_PHOTO_ERROR" in album miniature and return
        if(album['photos'].length == 0){
            this.addAlbumMiniatureUrl(idAlbum, 'NO_PHOTO_ERROR');
            return this.getAlbum(idAlbum)['miniatureURL'];
        }

        // add the miniature album url
        Vue.http.get('images/singleImage/'+album['photos'][0]['photoId']+'?miniature=true')
            .then(response =>{ return response.json(); })
            .then(data =>{
                this.addAlbumMiniatureUrl(idAlbum, data['url']);
            });

        // return something while the miniature url is getting updated
        this.addAlbumMiniatureUrl(idAlbum, 'WAITING_FOR_MINIATURE');
        return this.getAlbum(idAlbum)['miniatureURL'];

    },
    addAlbumMiniatureUrl : function(idAlbum, url){
        this.albumsSortedById[idAlbum]['miniatureURL'] = url; // TODO: check if this adds in all albums list!
        EventBus.$emit('albumMiniatureUrlChanged', idAlbum);
    },
    getPhotoUrl : function(photoId, miniature){
        // if there is allready an url for this photo, return the url. If waiting for URL, return WAITING_FOR_URL
        if(miniature && photoId in this.photosSortedById && 'miniatureUrl' in this.photosSortedById[photoId]){
            if(this.photosSortedById['miniatureUrl'] == 'WAITING_FOR_URL'){
                return 'WAITING_FOR_URL';
            }else{
                return this.photosSortedById[photoId]['miniatureUrl'];
            }
        }
        if(!miniature && photoId in this.photosSortedById && 'normalUrl' in this.photosSortedById[photoId]){
            if(this.photosSortedById['normalUrl'] == 'WAITING_FOR_URL'){
                return 'WAITING_FOR_URL';
            }else{
                return this.photosSortedById[photoId]['normalUrl'];
            }
        }

        // getting here should mean that we haven't tried to get the url. so get it:
        Vue.http.get('images/singleImage/'+imageId+'?miniature='+miniature)
            .then(response =>{ return response.json(); })
            .then(data =>{
                this.addPhotoUrl(photoId, miniature, data['url']);
        });

    },
    addPhotoUrl : function(photoId, miniature, url){
        if(miniature){
            this.photosSortedById[photoId]['miniatureUrl'] = url;
            EventBus.$emit('photoMiniatureUrlChanged', photoId);
        }else{
            this.photosSortedById[photoId]['normalUrl'] = url;
            EventBus.$emit('photoNormalUrlChanged', photoId);
        }
    },
    getAllUrlsFromAlbum : function(albumId){
        var album = this.getAlbum(albumId);
        if(album == -1){
            console.error("getAllUrlsFromAlbum("+albumId+") : album doesnt exists");
            return;
        }
        if(!(albumId in this.allAlbumsPhotosUrls)){
            console.error("getAllUrlsFromAlbum("+albumId+") : album not in this.allAlbumsPhotosUrls");
            return;
        }

        // if there is no photo in album, return empty array
        if(album['photos'].length == 0){
            return [];
        }

        // if we allready have the urls of this album, return the urls
        if(this.allAlbumsPhotosUrls[albumId].length != 0 && this.allAlbumsPhotosUrls[albumId] != 'WAITING_FOR_URLS'){
            return this.allAlbumsPhotosUrls[albumId];
        }

        // else, we have to fetch the urls of this album
        Vue.http.get('images/urls/'+albumId)
            .then(response =>{ return response.json(); })
            .then(data =>{
                this.addUrlsToAlbum(albumId, data['urls']);
            });

        // return empty array while we are fetching the urls
        this.allAlbumsPhotosUrls[albumId] = 'WAITING_FOR_URLS';
        return [];
    },
    addUrlsToAlbum : function(albumId, urls){
        this.allAlbumsPhotosUrls[albumId] = urls;
        EventBus.$emit('albumPhotosUrlsChanged', albumId);
    }
}
