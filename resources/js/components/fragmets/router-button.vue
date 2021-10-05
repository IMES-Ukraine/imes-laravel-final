<template>
    <a
        :class="[{ active: isActive }, icon]"
        class="btn btn-outline-primary btn-block has-icon"
        :href="url"
    >
        <slot></slot>
    </a>
</template>

<script>
export default {
    name: "router-button",
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        icon: {
            type: String,
            default: ''
        },
        url: {
            type: String,
            default: ''
        }
    },
}
</script>
