<template>
    <div class="articles_create__item-content">
        <div
            :class="['articles_create__item-file', 'width-auto buttonAddFile', {fileDisabled: disabled}, {has_file: haveImage}]">
            <input type="file" v-on:change="handleUpload" :name="type">
            <p><span>{{ haveImage ? model.file_name : 'Загрузить' }}</span>
            </p>
            <button @click="model=null; $emit('fileInput', null)" type="button" class="delete_file deleteFile"></button>
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
        value: Object,
        error: String,
        type: String,
        attachment: String
    },
    data() {
        return {
            model: this.value
        }
    },
    watch: {
        value() {
            this.model = this.value;
        }
    },
    computed: {
        haveImage() {
            return !!this.model && !!Object.keys(this.model).length && !!this.model.file_name
        },
    },
    methods: {
        /**
         * Handle changing of file input (cover, video, variants)
         * @param event
         */
        handleUpload(event) {
            this.coverError = '';
            let imageForm = new FormData();
            let input = event.target
            // if (!checkIsImage(input.value)) {
            //     this.errorArticleCover = this.notImageText;
            //     return;
            // }
            imageForm.append('file', input.files[0]);
            this.$post(PROJECT_IMAGE + this.type + '/' + (this.attachment ? this.attachment : 'test'),
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((file) => {
                console.log(file.data.content_type, this.type, file.data.content_type.includes(this.type));
                if (!file.data.content_type.includes(this.type)) {
                    this.$bvModal.msgBoxOk("Неверный тип файла");
                }
                else
                {
                    this.model = file.data;
                    this.$emit('fileInput', this.model)
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
