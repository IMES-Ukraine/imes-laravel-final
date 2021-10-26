<template>
    <div class="articles_create-block">
        <div class="articles_create__item">
            <div class="articles_create__item-title has_radio">
                <input
                    type="checkbox"
                    name="article-has-insert"
                    id="article-has-insert"
                    v-model="textLocale"
                    :checked="textLocale"
                    @onclick="getTextInsert"
                >
                <i></i>
                <p>Вставка в тексте</p>
            </div>
            <div class="articles_create__item-content direction-column" v-if="textLocale">
                <v-input-text
                    :name="'title'"
                    v-on:update:value="updateInsert(0, 'title', $event)"
                    :value="this.insert[0].title"
                    placeholder="Заголовок"
                    :classes="'mb20'"
                />
                <v-textarea
                    :rows="4"
                    v-on:update:text="updateInsert(0, 'content', $event)"
                    :text="this.insert[0].content"
                    placeholder="Текст"
                />
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
