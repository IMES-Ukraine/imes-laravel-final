<template>
    <a
        :class="{active: isActive}"
        class="btn btn-outline-primary btn-block text-uppercase"
        href="#"
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
        }
    }
}
</script>
