<template>
<div>
    <div class="header">
        <span>Retour</span>
        <span class="album-title">{{albumTitle}}</span>
    </div>
    PAGE ALBUM VIEW for album {{this.id}}. From category referer{{this.categoryReferer}}
    <a @click="returnToAlbumsList">go to albums list</a>
</div>
</template>

<script>
import Data from './service.js';

export default{
    props: ['id', 'categoryReferer'],
    data : function(){
        return{
            albumTitle : Data.getAlbum(this.id)['albumName']
        }
    },
    methods : {
        returnToAlbumsList : function(){
            this.$router.push('/albums/'+this.categoryReferer);
        }
    }
}
</script>

<style>
    .header{
        background-color: #315e33;
        width: 100%;
        height: 40px;
        line-height: 40px;
        overflow: hidden;
        position: fixed;
        top: 0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center; /* align horizontal */
        align-items: center;

    }
    .album-title{
        background-color: white;
    }
</style>
