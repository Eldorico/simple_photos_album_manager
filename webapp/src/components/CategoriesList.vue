<template>
  <div class="container">
      <div class="buttons-container">
      <span    v-bind:class="{'category-button': true, 'selected' : catSelected == category['id']}"
            v-bind:id="category['id']"
            v-for="category in this.input"
            @click="select(category['id'])">
            {{category['name']}}</span>
      </div>
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
          this.$emit('category-selected', id);
      }
  },
}
</script>

<style >
.container{
    width: 100%;
    height: 100%;
    background-color: #315e33;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center; /* align horizontal */
    align-items: center;
}

.buttons-container{
    width: 40%;
    height: 100%;
    justify-content: space-around;
    display: flex;
}

.category-button{
    background-color: inherit;
    color: white;
    cursor: pointer;
    margin: 0;
    padding: 0 5%;
    height: 100%;
}

.selected{
    background-color: white !important;
    color: black !important;
}

@media (max-width: 1000px){
    .buttons-container{
        width: 100%;
    }

    .category-button{
        font-size: 50px;
    }
}

</style>
