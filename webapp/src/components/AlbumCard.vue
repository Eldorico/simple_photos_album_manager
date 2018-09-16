<template>
  <div>
      <img :src="this.minURL">
      <div>{{this.input['albumName']}}</div>
  </div>
</template>

<script>
import Data from '../service.js';
import  { EventBus } from '../EventBus.js';

export default {
    props: ['input'],
    data () {
        return {
            dataService : Data,
            minURL : 'WAINTING_FOR_URL'
        };
    },
    methods : {
        getMinURL : function(){
            return this.dataService.getAlbumMiniatureUrl(this.input['id']);
        },
        refreshMinURL : function(url){
            this.minURL = url;
            console.log("AlbumCard.refresehMinURL() : new min url for albuml "+this.input['id']+" = "+url);
        }
    },
    mounted : function(){
        this.minURL = this.getMinURL();
    },
    created(){
        // TODO: I'm sure we can remove some lines here...
        var data = this.dataService;
        var url = this.minURL;
        var card = this;
        var albumInput = this.input;
        EventBus.$on('albumMiniatureUrlChanged', function(idAlbum){
            // console.log("WESH MECCCCC BORDLE DE Q*");
            // console.log(albumInput);
            if(albumInput.id == idAlbum){
                url = data.getAlbum(idAlbum)['miniatureURL'];
                // console.log("AlbumCard: EventBus: albumMiniatureUrlChanged new URL for album "+idAlbum+" : "+ url);
                card.refreshMinURL(url);
            }
        });
    }
}
</script>
