<template>
    <v-content>

        <template v-slot:sidebar>
            {{this.name}}
        </template>

        <div class="row mb-4">
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="checkbox" v-bind:name="itemId" v-bind:id="itemId">
                    <label v-bind:for="itemId" class="custom-checkbox__label"></label>
                </div>
                Готовый ответ
            </div>
            <div class="col-1">
                {{ itemTitle }}
            </div>
            <div class="col-5">
                <div class="row">
                    <div class="col-12 mb-2">
                        <input class="form-control" type="text" name="title" v-model="$v.text.$model" placeholder="">
                        <div class="error" v-if="$v.text.$error">
                            Обязательно
                        </div>
                    </div>
                </div>
            </div>
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="radio" name="right-answer" v-bind:id="itemTitle" v-on:input="$emit('input', $event.target.value)">
                    <label v-bind:for="itemTitle" class="custom-checkbox__label"></label>
                </div>
                Правильный ответ
            </div>
        </div>
        <div class="row mb-3">
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="checkbox" name="article-has-insert" id="article-has-insert" v-on:input="$emit('input', $event.target.value)">
                    <label for="article-has-insert" class="custom-checkbox__label"></label>
                </div>
                Поле ввода ответа
            </div>
            <div class="col-9">
                <textarea class="form-control" rows="4" v-model="$v.text.$model"></textarea>
                <div class="error" v-if="$v.text.$error">
                    Текст ответа обязателен
                </div>
            </div>
        </div>
    </v-content>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import VContent from "../templates/Content"

    export default {
        name: 'SimpleTestVariant',
        components: {
            VContent
        },
        props: ['itemTitle', 'itemId'],

        validations: {
            text: {
                required
            }
        }
    }
</script>
