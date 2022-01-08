<template>
    <div class="row mb-3">
        <div class="article-edit__text col-3">
            {{title}}
        </div>
        <div class="col-9">
            <label>
                <vue-upload-multiple-image
                    ref="multiple"
                    @upload-success="uploadImageSuccess"
                    @before-remove="beforeRemove"
                    @edit-image="editImage"
                    @data-change="dataChange"
                    dragText="Выберите картинки"
                    browseText="Мультизагрузка"
                    primaryText="Картинка"
                    :dataImages="images"
                ></vue-upload-multiple-image>
            </label>
        </div>
    </div>
</template>

<script>
    import VueUploadMultipleImage from 'vue-upload-multiple-image'

    export default {
        name: "article-multiple",
        components: {VueUploadMultipleImage},
        props: {
            v: {
                type: Object,
                require: true
            },
            title: {
                type: String,
                require: true
            },
            images: {
                type: Array,
                require: true
            }
        },
        methods: {
            uploadImageSuccess(formData, index, fileList) {
                console.log('data', formData, index, fileList)
                this.$emit('update', this.$refs.multiple.images);
                console.info('ggg', this.$refs.multiple.images);
                // Upload image api
                // axios.post('http://your-url-upload', formData).then(response => {
                //   console.log(response)
                // })
            },
            beforeRemove (index, done, fileList) {
                console.log('index', index, fileList)
                var r = confirm("remove image")
                if (r == true) {
                    done()
                } else {
                }
            },
            editImage (formData, index, fileList) {
                console.log('edit data', formData, index, fileList)
            },
            dataChange (value) {
            }
        }
    }
</script>
