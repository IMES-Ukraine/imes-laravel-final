<template>
    <ul class="p-0">
        <li
            class="mb-2 list-unstyled"
            v-for="(route, k) in routes"
            v-bind:key="route.path"
        >
            <router-link
                :to="{path: route.path}"
                :key="k"
                class="text-uppercase btn btn-outline-primary"
            >
                {{route.name}}
            </router-link>
        </li>
    </ul>
</template>

<script>
export default {
    name: "route-buttons-sidebar",
    props: {
        routes: {
            type: Array,
            require: true,
        }
    }
}
</script>
<style>
    ul li .btn.btn-outline-primary {
        width: 100%;
    }
</style>
