<template>
  <div>
      <router-view></router-view>
  </div>
</template>

<script>
import Vue from 'vue';

// for http requests
import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.options.root  = "http://localhost:80" // local

import Data from './service.js';

export default {
    name: 'app',
    data () {
        return {};
    },
    beforeMount : function() {
        Data.getCategories();
    }
}
</script>
