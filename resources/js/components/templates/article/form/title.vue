<template>
    <div class="articles_create__item-content direction-column">
        <div class="articles_create__name-block">
            <input
                class="form-control"
                type="text"
                name="title"
                v-model="v.title.$model"
            >
            <div class="errors" v-if="v.title.$error">
                Заголовок обов'язковий
            </div>
        </div>
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
        v: {
            type: Object,
            require: true
        },
        type: {
            type: Number,
            require: true
        },
        articleType: {
            type: Number,
            default: 1
        }
    },
    components: {VRadio},
    methods: {
        getType (value) {
            this.$emit('update', value);
        }
    }
}
</script>
