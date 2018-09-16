<template>
<div>
    <div class="header">
        <categories-list :input="createCategoriesChoice()"
                         :defaultSelection="this.defaultCategorySelection"
                         v-on:category-selected="filterAlbums">
                          </categories-list>
    </div>
    <div class="page-content">
        <album-card v-for="album in this.displayedAlbums"
                    :input="album">
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
            defaultCategorySelection : 0,
            displayedAlbums : []
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
        filterAlbums : function(idCategory){
            if(idCategory == 0){
                this.displayedAlbums = this.dataService.allAlbums;
            }else{
                this.displayedAlbums = this.dataService.albumsSortedByCategory[idCategory];
            }
            console.log("Albums displayed: ");
            console.log(this.displayedAlbums);
        }
    },
    mounted : function(){
        this.filterAlbums(this.defaultCategorySelection);
    }
}
</script>

<style>
.header{
    width: 100%;
    height: 40px;
    line-height: 40px;
    overflow: hidden;
    position: fixed;
    top: 0;
}

.page-content{
    margin-top: 50px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: flex-end;
    text-align: center;
}

body{
    background-color: #fbfbfb;
}

@media (max-width: 1000px){
    .header{
        height: 120px;
        line-height: 120px;
    }

    .page-content{
        margin-top: 130px;
    }

    body{
        background-color: #f6f6f6;
    }
}
</style>
