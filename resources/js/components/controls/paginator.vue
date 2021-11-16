<template>
    <div class="clearfix btn-group col-md-2 offset-md-5">
        <a :href="response.first_page_url" title="Первая страница"><button type="button" class="btn btn-sm btn-outline-secondary"  > << </button></a>
        <a v-for="link in response.links"><button type="button" class="btn btn-sm btn-outline-secondary" > {{pageNumber}} </button></a>
        <button type="button" @click="page++"  class="btn btn-sm btn-outline-secondary"> >> </button>
    </div>
</template>

<script>
export default {
    name: "paginator",
    props: ['response']
}
</script>

<style scoped>

</style>
