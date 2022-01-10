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
                            v-on:onBlockUser="onBlockUserHandler"
                            v-on:onDeleteUser="onDeleteUserHandler"
                            v-on:onUnBlockUser="onUnBlockUserHandler"
                        ></clients-table>
                    </div>
                </div>
                <pagination :data="responseData" @pagination-change-page="loadClients"></pagination>
            </div>
        </div>
    </v-content>
</template>

<script>
import VContent from "./templates/Content";
import SidebarUsers from "./templates/SidebarUsers";
import ClientsTable from './templates/clients/table-user'
import { CLIENTS_BLOCK_USER, CLIENTS_DELETE_USER, CLIENTS_UNBLOCK_USER } from "../api/endpoints"
import VPreloader from "./fragmets/preloader"
import ModalMixin from "../ModalMixin";

export default {
    name: "Clients",
    components: {
        VContent,SidebarUsers,ClientsTable,VPreloader
    },
    mixins: [ModalMixin],
    computed: {
        requests() {
         return this.$store.state.clients;
        },
    },
    methods: {
        async onBlockUserHandler(id) {

            this.$get(CLIENTS_BLOCK_USER + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 0
                    return
                }
            }
        },
        async onUnBlockUserHandler(id) {

            this.$get(CLIENTS_UNBLOCK_USER + '/' + id).then();

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_activated = 1
                    return
                }
            }
        },
        async onDeleteUserHandler(id) {

            this.$delete(CLIENTS_DELETE_USER + '/' + id).then();
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
