<template>
  <div class="container">
      <a    v-bind:class="{'category-button': true, 'selected' : catSelected == category['id']}"
            v-bind:id="category['id']"
            v-for="category in this.input"
            @click="select(category['id'])">
            {{category['name']}}</a>
  </div>
</template>

<script>

export default {
  props: ['input', 'defaultSelection'],
  data: function(){
      return {
          catSelected : this.defaultSelection
      };
  },
  methods : {
      select : function(id){
          this.catSelected = id;
          console.log("selected "+id);
      }
  },
}
</script>

<style>
.category-button{
    /*border-style: solid;*/
    background-color: #315e33;
    padding: 5px;
    color: white;
    text-align: center;
    cursor: pointer;
}

.selected{
    background-color: white !important;
    color: black !important;
}
</style>
