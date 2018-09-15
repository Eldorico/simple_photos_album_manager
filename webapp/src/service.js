
import Vue from 'vue';

let data;
export default data = {
    categories : [],
    albumsSortedByCategory : {},
    allAlbums : [],
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
              }
          });
    },
    showCategories : function(){
        console.log(this.categories);
    }
}
