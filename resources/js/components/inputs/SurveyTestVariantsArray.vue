<template>
    <div>
        <div v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    Вибір
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div className="row mb-4">
                                <div className="col-1">
                                    {{ variant.title }}
                                </div>
                                <div className="col-5">
                                    <div className="row">
                                        <div className="col-12 mb-2">
                                            <input className="form-control" type="text" name="title" placeholder=""
                                                   v-model.lazy="variant.variant">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import {PROJECT_IMAGE} from "../../api/endpoints";

    export default {
        name: 'SimpleTestVariantsArray',

        props: ['variants', 'answer'],

        methods: {

            /**
             * Handle changing of file input (cover, video, variants)
             * @param event
             */
            handleUpload(index, event) {

                let imageForm = new FormData();
                let input = event.target
                let type = input.getAttribute('img_type')


                imageForm.append('file', input.files[0]);

                this.$post(PROJECT_IMAGE + type,
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((file) => {
                    this.variants[index].file = file.data
                })
            },
        },
        validations: {
            text: {
                required
            }
        }
    }
</script>
