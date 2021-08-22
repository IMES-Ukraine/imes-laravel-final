<template>
    <router-link :to="to">
        <fragment-close-item/>
    </router-link>
</template>

<script>
import FragmentCloseItem from "../fragmets/close-item";
export default {
    name: "v-close",
    components: {FragmentCloseItem},
    props: {
        to: {
            type: Object,
            require: true
        }
    }
}
</script>
