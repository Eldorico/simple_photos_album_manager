
import Vue from 'vue';
import  { EventBus } from './EventBus.js';

let data;
export default data = {
    categories : [],
    albumsSortedByCategory : {},
    allAlbums : [],
    albumsSortedById : {},
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
        Vue.http.get('albums')
          .then(response =>{ return response.json(); })
          .then(data =>{
              // sort albums by name
              data['albums'].sort(function(el1, el2){
                  if(el1.albumName < el2.albumName) return -1;
                  if(el2.albumName > el2.albumName) return 1;
                  return 0;
              });

              // create the data albums
              for(var i=0; i<this.categories.length; i++){
                  this.albumsSortedByCategory[this.categories[i]['id']] = [];
              }
              for(var i=0; i<data['albums'].length; i++){
                  this.allAlbums.push(data['albums'][i]);
                  this.albumsSortedByCategory[data['albums'][i]['category']].push(data['albums'][i]);
                  this.albumsSortedById[data['albums'][i]['id']] = data['albums'][i];
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
            console.err("getAlbumMiniatureUrl("+idAlbum+") : album doesnt exists");
            console.log("getAlbumMiniatureUrl("+idAlbum+") : album doesnt exists");
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
    }
}
