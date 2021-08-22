<template>
    <div class="row mb-4">
        <div class="article-edit__text col-3">
            <slot name="title">Обложка</slot>
        </div>
        <div class="col-4">
            <slot name="before-file"></slot>
            <v-file
                v-on:update:file="getFile"
                :file-key="fileKey"
            />
            <slot name="after-file"></slot>
            <slot></slot>
        </div>
    </div>
</template>

<script>
import VFile from "../templates/inputs/file";
export default {
    name: "fragment-form-cover",
    components: {VFile},
    props: {
        file: {
            require: true
        },
        fileKey: {
            type: String,
            require: true
        }
    },
    methods: {
        getFile (file) {
            this.$emit('update:file', file);
        }
    }
}
</script>
