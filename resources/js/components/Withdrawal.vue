<template>
    <v-content>


        <template v-slot:sidebar>
            <ProjectFormSidebar></ProjectFormSidebar>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card">
                    <div class="card-body">
                        <requests-table v-if="hasRequests()"
                            v-bind:requests="requests"
                            v-on:accept="accept"
                            v-on:decline="decline"
                        ></requests-table>
                        <v-preloader v-else />
                    </div>
                </div>
            </div>
        </div>
    </v-content>

</template>

<script>

import VContent from "./templates/Content";
import ProjectFormSidebar from "./templates/project/form/sidebar";
import RequestsTable from './templates/withdrawal/table'
import { WITHDRAWAL, WITHDRAWAL_CONFIRMATION, WITHDRAWAL_DECLINE } from "../api/endpoints"
import VPreloader from "./fragmets/preloader"

export default {
    name: "Withdrawal",
    components: {
        VContent,ProjectFormSidebar,RequestsTable,VPreloader
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
