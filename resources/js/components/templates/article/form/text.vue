<template>
    <div class="articles_create__item mb54">
        <p class="articles_create__item-title">{{title}}</p>
        <div class="articles_create__item-content">
            <v-textarea
                :rows="4"
                :text="text"
                v-on:update:text="getText"
                v-model="articles[0].text"
            />
        </div>
        <div class="errors" v-if="error">
            {{error}}
        </div>
    </div>
</template>

<script>
import VTextarea from "../../inputs/textarea";
export default {
    name: "article-input-text",
    components: {VTextarea},
    props: {
        v: {
            type: Object,
            require: true
        },
        error: {
            type: String,
            require: true
        },
        title: {
            type: String,
            require: true
        },
        text: {
            type: String,
            require: true
        }
    },
    methods: {
        getText (text) {
            this.$emit('update:text', text);
        }
    }
}
</script>
