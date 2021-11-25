<template>
    <div class="articles_create__item-content">
        <div :class = "['articles_create__item-file', 'width-auto buttonAddFile', {fileDisabled: disabled}, {has_file: haveImage}]">
            <input type="file" v-on:change="handleUpload" :name="type">
            <p><span>{{ haveImage ? entity.media[type].disk_name : 'Загрузить' }}</span>
            </p>
            <button @click="entity.media[type]={}" type="button" class="delete_file deleteFile"></button>
        </div>
        <div v-if="error" class="errors">{{ error }}</div>
    </div>
</template>

<script>
import ProjectMixin from "../../ProjectMixin";
import {checkIsImage} from "../../utils";
import {PROJECT_IMAGE} from "../../api/endpoints";

export default {
    name: "file-input",
    mixins: [ProjectMixin],
    props: {
        disabled: Boolean,
        entity: Object,
        error: String,
        type: String
    },
    computed: {
      haveImage() {
          return !!this.entity.media[this.type] &&  !!Object.keys(this.entity.media[this.type]).length
      },
    },
    methods: {
        /**
         * Handle changing of file input (cover, video, variants)
         * @param event
         */
        handleUpload( event) {
            this.coverError = '';
            let imageForm = new FormData();
            let input = event.target
            if (! checkIsImage(input.value) ) {
                this.errorArticleCover = this.notImageText;
                return;
            }
            imageForm.append('file', input.files[0]);
            this.$post(PROJECT_IMAGE + this.type,
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((file) => {
                this.entity.media[this.type] = file.data;
            })
        },
    }
}
</script>

<style scoped>

</style>
