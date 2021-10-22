<template>
    <!--<router-link
        :to="{name: routeName, params: {projectId: id}}"
        class="col-4 mb-3 rectangle"
    >-->
        <div class="articles_list__block" v-if="(this.$route.hash && this.$route.hash == tag) || (!this.$route.hash)">
            <router-link
                :to="{name: routeName, params: {projectId: id}}"
                class="articles_list__block-link"
            ></router-link>
            <div class="articles_list__block-content">
                <div class="articles_list__block-img" v-if="options.files && options.files.cover">
                    <cover :image="options.files.cover" :title="title" class="m-auto"/>
                </div>
                <ul v-if="items">
                    <template v-for="item in items">
                        <li v-if="item.item_type=='App\\Models\\TestQuestions'">
                            {{ item.data.title }}
                        </li>
                    </template>
                </ul>
            </div>
            <div class="articles_list__block-status" v-if="options.status == 'inactive'">
                <p class="done"><span>Проект завершен</span></p>
            </div>
        </div>
</template>

<script>
import Cover from "../../../fragmets/cover-project";
export default {
    name: "project-list-card",
    components: {Cover},
    data () {
        return {
            routeName: 'viewProject'
        }
    },
    props: {
        id: {
            type: Number,
            require: true
        },
        title: {
            type: String,
            require: true
        },
        tag: {
            type: String,
            require: false
        },
        options: {
            type: Object,
            require: true
        },
        items: {
            type: Array,
            require: true
        }
    }
}
</script>

<style scoped>
.rectangle {
    height: 200px;
    background: #FFFFFF;
    border: 1px solid #E5E6EA;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
    border-radius: 5px;
}
.logotype>img {
    width: 50%;
}
.rectangle__comment {
    font-family: Montserrat;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    color: #A1A1A1;
}
</style>
