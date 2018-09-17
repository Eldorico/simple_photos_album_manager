<template>
<div>
    PAGE ALBUM VIEW for album {{this.id}}. From category referer{{this.categoryReferer}}
    <a @click="returnToAlbumsList">go to albums list</a>
</div>
</template>

<script>
export default{
    props: ['id', 'categoryReferer'],
    methods : {
        returnToAlbumsList : function(){
            this.$router.push('/albums/'+this.categoryReferer);
        }
    }
}
</script>

<style>

</style>
