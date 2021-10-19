<template>
    <!--<router-link
        :to="{name: routeName, params: {projectId: id}}"
        class="col-4 mb-3 rectangle"
    >-->
        <div class="articles_list__block">
            <router-link
                :to="{name: routeName, params: {projectId: id}}"
                class="articles_list__block-link"
            ></router-link>
            <div class="articles_list__block-content">
                <div class="articles_list__block-img">
                    <logotype class="m-auto"/>
                </div>
                <ul>
                    <li>Исследование №1</li>
                </ul>
            </div>
        </div>
        <!--<div class="logotype d-flex mb-2">
            <logotype class="m-auto"/>
        </div>
        <div class="line mb-2"/>
        <div class="rectangle__comment">
            {{ desc }}
        </div>-->
    <!--</router-link>-->
</template>

<script>
import Logotype from "../../../fragmets/logotype";
export default {
    name: "project-list-card",
    components: {Logotype},
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
        desc: {
            type: String,
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
