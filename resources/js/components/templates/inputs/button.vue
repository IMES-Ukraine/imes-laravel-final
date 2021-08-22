<template>
    <div class="row">
        <div class="col-12 text-center">
            <button type="button" class="btn btn-outline-primary" @click="$emit('click')">
                <slot>
                    Опубликовать
                </slot>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "v-button"
}
</script>
