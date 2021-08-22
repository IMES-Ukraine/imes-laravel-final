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
                    <div class="error" v-if="v.title.$error">
                        Заголовок обязателен
                    </div>
                </div>

                <div class="col-12">
                    <v-radio
                        :id="this.name+'_1'"
                        :name="this.name"
                        :value="1"
                        :checked="true"
                        v-on:update:value="getType"
                    >
                        Новости
                    </v-radio>
                    <v-radio
                        :id="this.name+'_2'"
                        :name="this.name"
                        :value="2"
                        v-on:update:value="getType"
                    >
                        Информация
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
        }
    },
    components: {VRadio},
    methods: {
        getType (value) {
            this.$emit('update:type', value);
        }
    }
}
</script>
