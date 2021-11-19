<template>
    <img :src="image.path" class="img-fluid" :alt="title" :title="title">
</template>

<script>
    export default {
        name: "coverProject",
        props: {
            image: {
                type: Object,
                require: true
            },
            title: {
                type: String,
                require: true
            }
        }
    }
</script>
