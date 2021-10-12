<template>
    <div class="col-2 sidebar">
        <div class="mt-4 mr-3 ml-3">
            <slot>
            </slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "v-sidebar"
}
</script>

<style>
    .sidebar {
        background-color: #F7F7F9;
        border-right: 1px solid #E5E6EA
    }
</style>
