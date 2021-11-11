<template>
    <img :src="image" class="img-fluid" :alt="title" :title="title">
</template>

<script>
    export default {
        name: "coverProject",
        props: {
            image: {
                type: String,
                require: true
            },
            title: {
                type: String,
                require: true
            }
        }
    }
</script>
