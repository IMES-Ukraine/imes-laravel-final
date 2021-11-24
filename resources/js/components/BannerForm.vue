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
                                    <div :class="['articles_create__item-file', 'width-auto buttonAddFile', {has_file: (banner.image)?banner.image.path:false} ]">
                                        <input type="file" name="image" accept="image/*" @change="handleUploadBanner">
                                        <p><span>{{(banner.image?banner.image.file_name:false) || "Загрузить изображение"}}</span></p>
                                        <button class="delete_file deleteFile" type="button" @click="banner.image={}"></button>
                                        <!--<p class="note">до 5 kb</p>-->
                                    </div>
                                    <div class="errors">{{ errorFile }}</div>
                                </div>
                            </div>
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Ссылка</p>
                                <div class="articles_create__item-content direction-column">
                                    <ValidationProvider rules="required" v-slot="{ errors }" class="width-full">
                                        <input class="p-13" type="text" v-model="banner.url" placeholder="https://">
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
    import { BANNERS, ARTICLE_COVER, TOKEN } from "./../api/endpoints"

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
            succesPost: '',
            banner: {
                url: '',
                image: {}
            }
        }),
        methods: {
            loadBanner() {
                this.$get(BANNERS + '/card').then((res) => {
                    if (res) {
                        this.banner.url = res.item[0].url
                        this.banner.image = res.item[0].image
                    }
                })
            },
            submitForm() {
                this.$refs.form.validate().then( success => {
                    if (this.banner.image == {}) {
                        this.errorFile = 'Поле обязательно';
                    } else {
                        this.errorFile = '';
                    }

                    if( !success || this.errorFile != "" || this.banner.url == "") {
                        return;
                    }

                    let imageForm = new FormData()
                    imageForm.append('image', this.banner.image)
                    imageForm.append('url', this.banner.url)

                    this.$post(BANNERS, this.banner).then((res) => {
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
                //this.image = event.target.files[0]
                let imageForm = new FormData()
                imageForm.append('file', event.target.files[0])

                axios.post(
                    ARTICLE_COVER + '/banners',
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        params: {
                            access_token: TOKEN
                        },
                    }
                ).then((file) => {
                    this.banner.image = file.data.data
                })
            }
        },
        mounted() {
            this.loadBanner();
        }
    }
</script>
