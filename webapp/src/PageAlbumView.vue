<template>
<div>
    <div class="header">
        <div class="buttons-container">
            <a class="return-button" @click="returnToAlbumsList">Retour</a>
            <span class="album-title">{{albumTitle}}</span>
        </div>
    </div>
</div>
</template>

<script>
import Data from './service.js';
import { EventBus } from './EventBus.js';

export default{
    props: ['id', 'categoryReferer'],
    data : function(){
        return{
            albumTitle : Data.getAlbum(this.id)['albumName'],
        }
    },
    methods : {
        returnToAlbumsList : function(){
            this.$router.push('/albums/'+this.categoryReferer);
        }
    },
    created : function(){
        var THIS = this;
        EventBus.$on('albumInfosChanged', function(idAlbum){
            if(THIS.id == idAlbum){
                THIS.albumTitle = Data.getAlbum(THIS.id)['albumName'];
            }
        });
    }
}
</script>

<style scoped>
    a{
        cursor: pointer;
    }

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

    .buttons-container{
        width: 40%;
        height: 100%;
        justify-content: space-between;
        display: flex;
    }

    .return-button{
        background-color: white;
        color:  #315e33;
        padding: 0 3%;
    }

    .album-title{
        color: white;
    }

@media (max-width: 1000px){
    .header{
        height: 120px;
        line-height: 120px;
        font-size: 50px;
        display: block;
        font-size: 25px;
    }
    .buttons-container{
        width: 100%;
        height: 100%;
        justify-content: flex-start;
    }
    .return-button{
        line-height: 120px;
        margin-right: 5%;
    }
}

</style>
