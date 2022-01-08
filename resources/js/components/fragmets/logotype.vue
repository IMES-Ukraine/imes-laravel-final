<template>
    <img :src="image" class="img-fluid" alt="International Medical Ecosystem logotype" title="logotype">
</template>

<script>
import image from '../../assets/header-logo.svg';
export default {
    name: "logotype",
    data() {
        return {
            image
        }
    }
}
</script>
