<template>
    <div class="articles_create__radio_circle">
        <div class="articles_create__radio_circle-block">
            <v-radio
                :id="this.name+'_1'"
                :name="this.name"
                :value="1"
                :checked="(articleType==1)?true:false"
                v-on:update:value="getType"
            >Новости</v-radio>
            <!--<i></i>
            <p>Новости</p>-->
        </div>
        <div class="articles_create__radio_circle-block">
            <v-radio
                :id="this.name+'_2'"
                :name="this.name"
                :value="2"
                :checked="(articleType==2)?true:false"
                v-on:update:value="getType"
            >Информация</v-radio>
            <!--<i></i>
            <p>Информация</p>-->
        </div>
    </div>
</template>

<script>
import VRadio from "../../inputs/radio";

export default {
    name: "article-input-title",
    data() {
        return {
            name: 'typePublication'
        }
    },
    props: {
        type: {
            type: Number,
            require: true
        },
        articleType: {
            type: Number,
            default: 1
        },
    },
    components: {VRadio},
    methods: {
        getType (value) {
            this.$emit('update', value);
        }
    }
}
</script>
