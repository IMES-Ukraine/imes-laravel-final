<template>
    <v-content>

        <template v-slot:sidebar>
            <router-button :href="'/cards'">
                < Картки
            </router-button>
        </template>

        <div class="main">
            <div class="create_card">
                <banner-close/>
                <ValidationObserver ref="form" v-slot="{ handleSubmit }" class="width-full">
                    <div class="create_card-box">
                        <form class="width-full">
                            <div v-if="succesPost">
                                <div style="background: #a5d794; padding: 15px; margin-bottom: 10px; color: #fff;">{{ succesPost }}</div>
                            </div>
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Обложка</p>
                                <div class="articles_create__item-content direction-column">
                                    <div class="articles_create__item-file delete-style-2 width-auto buttonAddFile">
                                        <input type="file" name="image" accept="image/*" @change="handleUploadBanner">
                                        <p><span data-placeholder="Загрузить изображение">Загрузить изображение</span></p>
                                        <button class="delete_file deleteFile" type="button" @change="removeImage"></button>
                                        <!--<p class="note">до 5 kb</p>-->
                                    </div>
                                    <div class="errors">{{ errorFile }}</div>
                                </div>
                            </div>
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Ссылка</p>
                                <div class="articles_create__item-content direction-column">
                                    <ValidationProvider rules="required" v-slot="{ errors }" class="width-full">
                                        <input class="p-13" type="text" v-model="url" placeholder="https://">
                                        <div class="errors">{{ errors[0] }}</div>
                                    </ValidationProvider>
                                </div>
                            </div>
                        </form>
                    </div>
                    <button class="articles_create-submit button-border" type="button" @click="submitForm">Создать</button>
                </ValidationObserver>
            </div>

        </div>
    </v-content>
</template>

<script>
    import VContent from "./templates/Content"
    import RouterButton from "./fragmets/router-button"
    import BannerClose from "./templates/banner/Close"
    import { ValidationProvider, ValidationObserver } from 'vee-validate'
    import {BANNERS} from "./../api/endpoints"

    export default {
        name: 'BannerForm',
        components: {
            VContent,
            RouterButton,
            BannerClose,
            ValidationProvider,
            ValidationObserver,
        },
        data: () => ({
            url: '',
            image: '',
            errorFile: '',
            succesPost: ''
        }),
        methods: {
            loadBanner() {
                this.$get(BANNERS + '/card').then((res) => {
                    if (res) {
                        this.url = res.item[0].url
                        this.image = res.item[0].image
                    }
                })
            },
            submitForm() {
                this.$refs.form.validate().then( success => {
                    if (this.image == "") {
                        this.errorFile = 'Поле обязательно';
                    } else {
                        this.errorFile = '';
                    }

                    if( !success || this.errorFile != "" || this.url == "") {
                        return;
                    }

                    let imageForm = new FormData()
                    imageForm.append('image', this.image)
                    imageForm.append('url', this.url)

                    axios.post(BANNERS, imageForm, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((res) => {
                        this.succesPost = 'Банер успешно сохранен'
                    });
                })
            },
            handleUploadBanner() {
                /*if (event.target.files[0].size <= 5000) {
                    this.image = event.target.files[0]
                } else {
                    this.errorFile = 'Изображение слишком большое';
                }*/
                this.image = event.target.files[0]
            },
            removeImage() {
                this.image = '';
            }
        },
        mounted() {
            this.loadBanner();
        }
    }
</script>
