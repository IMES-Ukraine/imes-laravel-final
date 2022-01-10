<template>
    <label class="btn btn-outline-second btn-centered-content upload-cover is-small" role="button">

        <span class="input-file-label" role="button">
            <span v-if="name !== ''">
                {{ name }}
            </span>
            <span class="d-flex align-items-center" role="button" v-else>
                <span class="icon-is-left icon-is-load-grey"></span>
                Завантажити
            </span>
        </span>

        <input
            type="file"
            id="articleCover"
            name="addCover"
            class="input-file-hidden"
            v-on:change="handleUpload"
            :disabled="isInvalidType()"
            role="button"
        >

    </label>
</template>

<script>
    import {PROJECT_IMAGE} from "../../../api/endpoints";
    import axios from 'axios'
    export default {
        name: "v-file",
        data() {
            return {
                file: {},
                validTypes: [
                    'cover',
                    'video',
                    'variants'
                ]
            }
        },
        props: {
            type: {
                type: String,
                default: 'cover'
            },
            fileKey: {
                type: String,
                require: true
            },
            name: {
              type: String,
              default: ''
            },
        },
        methods: {
            handleUpload(event) {
                let imageForm = new FormData()
                imageForm.append('file', event.target.files[0])
                axios.post(
                    PROJECT_IMAGE + this.type,
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((file) => {
                    this.name = event.target.files[0].name
                    this.file[this.fileKey] = file.data.data
                    this.$emit('update:file', this.file);
                })
            },

            isInvalidType() {
                let result = true
                let type = this.type
                this.validTypes.forEach(function(value) {
                    if (type === value) {
                        result = false
                    }
                });
                return result
            },
        }
    }
</script>
