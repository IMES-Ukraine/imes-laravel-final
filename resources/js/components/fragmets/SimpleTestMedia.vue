<template>
    <div v-if="media" class="articles_create__media-list">
        <div v-for="(file, index) in media" v-bind:key="file.itemId" class="articles_create__media-item">
            <div class="articles_create__media-img">
                <img :src="file.path" alt="">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "simple-test-media",
    props: ['media'],
}
</script>
