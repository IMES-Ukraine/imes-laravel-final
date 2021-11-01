<template>
    <v-content>


        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>

        <div class="template_box style_for_table">
            <div class="links_wrap right mb20">
                <a class="sidebar_nav-button width-auto height-35" href="/banner/new"><span>Баннер</span></a>
                <a class="sidebar_nav-button width-auto height-35 active" href="/cards"><span>Создать карточку</span></a>
            </div>
            <requests-table v-if="hasRequests()"
                v-bind:requests="requests"
                v-on:accept="accept"
                v-on:decline="decline"
            ></requests-table>
            <v-preloader v-else />
        </div>
    </v-content>

</template>

<script>

import VContent from "./templates/Content";
import SidebarUsers from "./templates/SidebarUsers";
import RequestsTable from './templates/withdrawal/table'
import { WITHDRAWAL, WITHDRAWAL_CONFIRMATION, WITHDRAWAL_DECLINE } from "../api/endpoints"
import VPreloader from "./fragmets/preloader"

export default {
    name: "Withdrawal",
    components: {
        VContent,SidebarUsers,RequestsTable,VPreloader
    },
    data() {
        return {
            requests: []
        }
    },
    methods: {
        async loadWithdrawals() {

            this.$get(WITHDRAWAL).then( response => {
                this.requests = response.data
            });
        },
        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        async accept(id) {

            this.$get(WITHDRAWAL_CONFIRMATION, {id: id}).then()
        },
        async decline(id) {

            this.$get(WITHDRAWAL_DECLINE, {id: id}).then()
        }
    },
    mounted() {
        this.loadWithdrawals();
    }

}
</script>

<style scoped>

</style>
