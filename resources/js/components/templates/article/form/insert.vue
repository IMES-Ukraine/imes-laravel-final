<template>

    <div>
    <div class="row mb-4" v-if="textLocale">

        <div class="article-edit__text col-3">
            Заголовок
        </div>

        <div class="col-9">
            <div class="row">
                <div class="col-12">

                    <v-input-text
                        :name="'title'"
                        v-on:update:value="updateInsert(0, 'title', $event)"
                        :value="this.insert[0].title"
                    />

                    <div class="error" v-if="v.title.$error">
                        Заголовок обов'язковий
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="article-edit__text col-3">
            <div class="article-edit__btn-pos custom-checkbox">
                <input
                    class="custom-checkbox__input"
                    type="checkbox"
                    name="article-has-insert"
                    id="article-has-insert"
                    v-model="textLocale"
                    :checked="textLocale"
                    @onclick="getTextInsert"
                >
                <label for="article-has-insert" class="custom-checkbox__label"></label>
            </div>
            Вставка в текстi
        </div>

        <div class="col-9" v-if="textLocale">
            <v-textarea
                :rows="4"
                v-on:update:text="updateInsert(0, 'content', $event)"
                :text="this.insert[0].content"
            />
            <div class="error" v-if="v.text.$error">
                Текст вставки обов'язковий
            </div>
        </div>

    </div>

    <article-input-text
        v-if="textLocale"
        :v="v"
        :title="'Продовження статтi'"
        :error="'Контент обов\'язковий'"
        :text="this.insert[1].content"
        v-on:update:text="updateInsert(1, 'content', $event)"
    />
    </div>

</template>

<script>
import VTextarea from "../../inputs/textarea";
import VInputText from "../../inputs/text";
import ArticleInputText from "./text";
export default {
    name: "article-form-insert",
    data () {
        return {
            textLocale: this.textInsert,
            insertLocal: this.insert,
        }
    },
    components: {ArticleInputText, VInputText, VTextarea},
    props: {
        insert: {
            type: Array,
            require: true
        },
        textInsert: {
            type: Boolean,
            require: true
        },
        v: {
            type: Object,
            require: true
        }
    },
    methods: {
      /**
       *
       * @param index
       * @param field
       * @param value
       */
        updateInsert(index, field, value) {

            this.insertLocal[index][field] = value
            console.log(this.insertLocal)
            this.getInsert()
        },
        getInsert () {
            this.$emit('insert', this.insertLocal)
        },
        getTextInsert () {
            this.$emit('textInsert', this.textLocale)
        }
    },
    beforeMount() {
        if (this.insert[1].content || this.insert[0].content) {
            this.$set(this, 'textLocale', true)
        }
    }
}
</script>
