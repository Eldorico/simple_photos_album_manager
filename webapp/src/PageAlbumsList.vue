<template>
<div>
    <div class="header">
    <categories-list :input="createCategoriesChoice()"
                     :defaultSelection="0">
                      </categories-list>
    PAGE ALBUMS LIST
    <a><router-link to="/album">go to album view</router-link></a>
    </div>
</div>
</template>

<script>
import CategoriesList from './components/CategoriesList.vue';
import Data from './service.js';

export default{
    data : function(){
        return {
            dataService : Data,
        }
    },
    components : {
        'categories-list' : CategoriesList
    },
    methods : {
        createCategoriesChoice : function(){
            var catToReturn = [{'name' : 'Tous', 'id': 0}];
            for(var i=0; i<this.dataService.categories.length; i++){
                catToReturn.push(this.dataService.categories[i]);
            }
            return catToReturn;
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
