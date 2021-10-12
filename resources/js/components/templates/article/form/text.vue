<template>
    <div class="row mb-3">
        <div class="article-edit__text col-3">
            {{title}}
        </div>
        <div class="col-9">
            <label>
                <v-textarea
                    :rows="4"
                    :text="text"
                    v-on:update:text="getText"
                />
            </label>
            <div class="errors" v-if="v.text.$error">
                {{error}}
            </div>
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
