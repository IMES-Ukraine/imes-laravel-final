<template>
    <div class="row mb-4">
        <div class="article-edit__text col-3">
            {{name}}
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-12 mb-2">
                    <slot>
                        --> input place <--
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "fragment-form-text",
    props: {
        name: {
            type: String,
            default: 'Название'
        }
    }
}
</script>
