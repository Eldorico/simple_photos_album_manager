<template>
  <div class="album-card">
      <img class="album-miniature clickable" :src="this.minURL" @click="clickedShowAlbum">
      <div>
          <span class="album-title clickable" @click="clickedShowAlbum">{{this.input['albumName']}}</span>
          <img class="edit-logo clickable" src="src/assets/pen.svg" @click="clickedEditAlbum"></img>
      </div>
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
        },
        clickedShowAlbum : function(){
            this.$emit('clicked-show-album', this.input['id']);
        },
        clickedEditAlbum : function(){
            console.log("cliqued on edit album: "+this.input['id']);
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
            if(albumInput.id == idAlbum){
                url = data.getAlbum(idAlbum)['miniatureURL'];
                card.refreshMinURL(url);
            }
        });
    },
    updated : function(){
        this.minURL = this.getMinURL();
    }
}
</script>

<style scoped>
    .album-card{
        border-style: solid;
        min-width: 25%;
        background-color: white;
        text-decoration: none;
        border-color: #f7f7f7;
    }

    .album-title{
        font-size: 25px;
        color: #315e33;
        margin-right: 3%;
    }

    .edit-logo{
        height: 15px;
    }

@media (max-width: 1000px){
    .album-miniature{
        height: 400px;
        width: auto;
    }

    .album-title{
        font-size: 80px;
        margin-right: 7%;
    }

    .edit-logo{
        height: 60px;
        width: 60px;
    }

    .album-card{
        min-width: 85%;
        font-size: 80px;
        margin: 3% 20%;
    }
}
</style>
