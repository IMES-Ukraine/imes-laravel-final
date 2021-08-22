<template>
    <div class="col-4 mb-3 rectangle">
            {{ question }}
        <div class="line mb-2"/>
        <div class="rectangle__comment">
            {{ answer }}
        </div>
        <div class="line mb-2"/>
        <router-button v-on:click.native="process('ok', id)">
            ✔️ Прийняти
        </router-button>
        <router-button v-on:click.native="process('fail', id)">
            ❌ Вiдхилити
        </router-button>
    </div>
</template>

<script>
import Logotype from "../../../fragmets/logotype";
import RouterButton from "../../../fragmets/router-button";
export default {
    name: "project-list-card",
    components: {
        Logotype,
        RouterButton
    },
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
        answer: {
            type: String,
            require: true
        },
        question: {
            type: String,
            require: true
        },
    },
    methods: {
        process( status, id ) {
            this.$emit('process', status, id)
        },
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
