<template>
    <v-content>
        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card">
                    <div class="card-body">
                        <clients-table
                            v-bind:requests="requests"
                            v-on:onBlockUser="onBlockUser"
                            v-on:onDeleteUser="onDeleteUser"
                            v-on:onUnBlockUser="onUnBlockUser"
                        ></clients-table>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
</template>

<script>
import VContent from "./templates/Content";
import SidebarUsers from "./templates/SidebarUsers";
import ClientsTable from './templates/clients/table'
import { CLIENTS, CLIENTS_BLOCK_USER, CLIENTS_DELETE_USER, CLIENTS_UNBLOCK_USER } from "../api/endpoints"
import VPreloader from "./fragmets/preloader"

export default {
    name: "Clients",
    components: {
        VContent,SidebarUsers,ClientsTable,VPreloader
    },
    data() {
        return {
            requests: []
        }
    },
    methods: {
        async loadClients() {

            this.$get(CLIENTS).then( response => {
                this.requests = response.data
            });
        },
        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        async onBlockUser(id) {

            this.$get(CLIENTS_BLOCK_USER + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 0
                    return
                }
            }
        },
        async onUnBlockUser(id) {

            this.$get(CLIENTS_UNBLOCK_USER + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 1
                    return
                }
            }
        },
        async onDeleteUser(id) {

            this.$delete(CLIENTS_DELETE_USER + '/' + id).then()
            $('#db-remove--' + id + ' .is-close').click();

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        }
    },
    mounted() {
        this.loadClients();
    }

}
</script>
