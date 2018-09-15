
import Vue from 'vue';

let data;
export default data = {
    categories : [],
    getCategories : function() {
        Vue.http.get('categories')
          .then(response =>{ return response.json(); })
          .then(data =>{
              for(var i=0; i<data['categories'].length; i++){
                  this.categories.push({'id': data['categories'][i]['id'],
                  'name': data['categories'][i]['name']});
              }
        });
    },
    showCategories : function(){
        console.log(this.categories);
    }
}
