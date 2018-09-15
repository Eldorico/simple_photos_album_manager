<template>
<div>
    <categories-list :input="dataService.categories"
                     :defaultSelection="1">
                      </categories-list>
    PAGE ALBUMS LIST
    <a><router-link to="/album">go to album view</router-link></a>
</div>
</template>

<script>
import CategoriesList from './components/CategoriesList.vue';
import Data from './service.js';

export default{
    data : function(){
        return {
            dataService : Data
        }
    },
    components : {
        'categories-list' : CategoriesList
    },
    methods : {
    }
}
</script>

<style>

</style>
