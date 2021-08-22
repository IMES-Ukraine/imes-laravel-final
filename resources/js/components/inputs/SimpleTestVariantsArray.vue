<template>
    <v-content>

        <template v-slot:sidebar>
            {{this.name}}
        </template>

        <div v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId" v-model.lazy="answer.type" value="variants">
                        <label v-bind:for="variant.itemId" class="custom-checkbox__label"></label>
                    </div>
                    Готовый ответ
                </div>
                <div class="col-1">
                    {{ variant.title }}
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input class="form-control" type="text" name="title" placeholder="" v-model.lazy="variant.variant">
                        </div>
                    </div>
                </div>
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="checkbox" name="right-answer" v-bind:id="variant.itemId + variant.title" v-model.lazy="answer.correct" :value="variant.title" >
                        <label v-bind:for="variant.itemId + variant.title" class="custom-checkbox__label"></label>
                    </div>
                    Правильный ответ
                </div>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId + '-field'" v-model.lazy="answer.type" value="text">
                        <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                    </div>
                    Поле ввода ответа
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId + '-media'" v-model.lazy="answer.type" value="card">
                        <label v-bind:for="variant.itemId + '-media'" class="custom-checkbox__label"></label>
                    </div>
                    Медиа
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span class="input-file-label">
                                <span class="icon-is-left icon-is-load-grey"></span><span v-if="variant.attachment">{{ variant.attachment.file_name }}</span><span v-else>Завантажити</span>
                            </span>
                        <!--                        <input type="file" name="testCover" img_type="button" class="input-file-hidden" v-on:change="$emit('file_сhange', $event)">-->
                        <input type="file" name="testCover" img_type="button" class="input-file-hidden" v-on:change="handleUpload(index, $event)">
                    </label>
                </div>
            </div>
        </div>
    </v-content>
</template>

<script>
import {required} from 'vuelidate/lib/validators'
import VContent from "../templates/Content"

export default {
    name: 'SimpleTestVariantsArray',
    components: {
        VContent
    },
    props: ['variants', 'answer'],
    methods: {
        handleFileChange(e) {
            let element = e.target
            this.$store.dispatch('addFiles', {file: element.files[0], id: element.getAttribute('variant_name')})
        }
    },
    validations: {
        text: {
            required
        }
    }
}
</script>
