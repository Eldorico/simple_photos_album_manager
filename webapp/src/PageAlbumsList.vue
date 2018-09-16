<template>
<div>
    <div class="header">
    <categories-list :input="createCategoriesChoice()"
                     :defaultSelection="this.defaultCategorySelection">
                      </categories-list>
    <album-card v-for="album in getChoosenAlbums()"  :input="album">
        </album-card>
    <a><router-link to="/album">go to album view</router-link></a>
    </div>
</div>
</template>

<script>
import CategoriesList from './components/CategoriesList.vue';
import Data from './service.js';
import AlbumCard from './components/AlbumCard.vue';

export default{
    data : function(){
        return {
            dataService : Data,
            defaultCategorySelection : 0
        }
    },
    components : {
        'categories-list' : CategoriesList,
        'album-card' : AlbumCard
    },
    methods : {
        createCategoriesChoice : function(){
            var catToReturn = [{'name' : 'Tous', 'id': 0}];
            for(var i=0; i<this.dataService.categories.length; i++){
                catToReturn.push(this.dataService.categories[i]);
            }
            return catToReturn;
        },
        getChoosenAlbums : function(){
            console.log("PageAlbumsList.getChoosenAlbums() : allAlbums = ");
            console.log(this.dataService.allAlbums);
            return this.dataService.allAlbums;
        }
    }
}
</script>

<style>
.header{
    width: 100%;
    height: 40px;
    line-height: 40px;
}

@media (max-width: 1000px){
    .header{
        height: 120px;
        line-height: 120px;
    }
}
</style>
