<template>
    <v-content>
        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card">
                    <div class="card-body">
                        <requests-table
                             v-bind:requests="requests"
                             v-on:onConfirmRequest="onConfirmRequest"
                             v-on:onDeclineRequest="onDeclineRequest"
                        ></requests-table>
                    </div>
                </div>
            </div>
        </div>
    </v-content>

</template>

<script>

    import VContent from "./templates/Content";
    import SidebarUsers from "./templates/SidebarUsers";
    import RequestsTable from './templates/request/table'
    import { REQUEST, REQUEST_CONFIRM, REQUEST_DECLINE } from "../api/endpoints"
    import VPreloader from "./fragmets/preloader"

    export default {
        name: "Request",
        components: {
            VContent,SidebarUsers,RequestsTable,VPreloader
        },
        data() {
            return {
                requests: []
            }
        },
        methods: {
            async loadRequests() {

                this.$get(REQUEST).then( response => {
                    this.requests = response.data
                });
            },
            hasRequests() {
                return !!Object.keys(this.requests).length
            },
            async onConfirmRequest(id) {

                this.$get(REQUEST_CONFIRM + '/' + id).then()

                for (const [index, value] of Object.entries(this.requests)) {
                    if (value.id === id) {
                        this.requests.splice(index, 1)
                        return
                    }
                }
            },
            async onDeclineRequest(id) {

                this.$get(REQUEST_DECLINE + '/' + id).then()

                for (const [index, value] of Object.entries(this.requests)) {
                    if (value.id === id) {
                        this.requests.splice(index, 1)
                        return
                    }
                }
            },
        },
        mounted() {
            this.loadRequests();
        }

    }
</script>

<style scoped>

</style>
