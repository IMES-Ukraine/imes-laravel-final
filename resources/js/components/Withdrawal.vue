<template>
    <v-content>


        <template v-slot:sidebar>
            <SidebarUsers filter="true" @update="setFilterUsers"></SidebarUsers>
        </template>

        <div class="template_box style_for_table">
            <requests-table v-if="hasRequests()"
                v-bind:requests="requests.data"
                v-on:accept="accept"
                v-on:decline="decline"
            ></requests-table>
            <div class="articles_pagination center">
                <pagination :data="requests" @pagination-change-page="getResults"></pagination>
            </div>
            <!--<v-preloader v-else />-->
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
            requests: {},
            filter: 0
        }
    },
    methods: {
        /*async loadWithdrawals() {

            this.$get(WITHDRAWAL).then( response => {
                this.requests = response.data.data
            });
        },*/
        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        async accept(id) {

            this.$get(WITHDRAWAL_CONFIRMATION, {id: id}).then()
        },
        async decline(id) {

            this.$get(WITHDRAWAL_DECLINE, {id: id}).then()
        },
        getResults(page) {
            let filter_url = '';

            if (typeof page === 'undefined') {
                page = 1;
            }

            if (this.filter) {
                filter_url = '&filter=' + this.filter;
            }

            this.$get(WITHDRAWAL + '?page=' + page + filter_url)
                .then(response => {
                    this.requests = response.data;
                });
        },
        setFilterUsers(user_id) {
            this.filter = user_id;

            this.$get(WITHDRAWAL + '?page=1&filter=' + user_id)
                .then(response => {
                    this.requests = response.data;
                });
        }
    },
    mounted() {
        this.getResults();
    }

}
</script>

<style scoped>

</style>
