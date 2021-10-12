<template>

    <div class="row mb-4">

        <div class="article-edit__text col-3">
            Заголовок
        </div>

        <div class="col-9">
            <div class="row">

                <div class="col-12 mb-2">
                    <label>
                        <input
                            class="form-control"
                            type="text"
                            name="title"
                            v-model="v.title.$model"
                        >
                    </label>
                    <div class="errors" v-if="v.title.$error">
                        Заголовок обов'язковий
                    </div>
                </div>

                <div class="col-12">
                    <v-radio
                        :id="this.name+'_1'"
                        :name="this.name"
                        :value="1"
                        :checked="(articleType==1)?true:false"
                        v-on:update:value="getType"
                    >
                        Новини
                    </v-radio>
                    <v-radio
                        :id="this.name+'_2'"
                        :name="this.name"
                        :value="2"
                        :checked="(articleType==2)?true:false"
                        v-on:update:value="getType"
                    >
                        Iнформацiя
                    </v-radio>
                </div>

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
            type: Boolean,
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
