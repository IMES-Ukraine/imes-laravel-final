<template>
    <v-content>

        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>


        <div class="main-db">
            <div class="db__block">
                <div class="card">
                    <div class="card-body">
                        <verification-table
                            :key="tableKey"
                            v-bind:requests="requests"
                            v-on:process="process"
                            v-on:onAcceptVerification="onAcceptVerification"
                            v-on:onDeclineVerification="onDeclineVerification"
                            v-on:onDeleteUser="onDeleteUser"
                        ></verification-table>
                        <!--<v-preloader v-else />-->
                    </div>
                </div>
            </div>
        </div>

    </v-content>
</template>

<script>

import VContent from "./templates/Content";
import SidebarUsers from "./templates/SidebarUsers";
import VerificationTable from "./templates/verification/table"
import VPreloader from "./fragmets/preloader"
import {
    PROFILE_VERIFICATION,
    PROFILE_VERIFICATION_LIST,
    PROFILE_ACCEPT_VERIFICATION,
    PROFILE_DECLINE_VERIFICATION,
    CLIENTS_DELETE_USER
} from "../api/endpoints"


export default {
name: "Verification",
    components: {SidebarUsers, VContent, VerificationTable,VPreloader},
    data() {
        return {
            requests: [],
            tableKey: Math.random()
        }
    },
    methods: {
        async loadVerifications() {
            this.$get(PROFILE_VERIFICATION_LIST).then( response => {
                this.requests = response.data;
                this.tableKey =  Math.random();
            });
        },
        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        process( params) {
            console.log(params)
            this.$post(PROFILE_VERIFICATION + params.action, params).then( response => {
                this.requests = this.requests.filter( item => {
                    return item.user_id != params.id;
                })
            })
        },
        async onAcceptVerification(id) {

            this.$post(PROFILE_ACCEPT_VERIFICATION + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.user_id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        },
        async onDeclineVerification(id) {

            this.$post(PROFILE_DECLINE_VERIFICATION + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.user_id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        },
        onDeleteUser(id) {
            let this_reference = this;
            axios.delete(CLIENTS_DELETE_USER + '/' + id).then(() => {
                this_reference.loadVerifications();
                $('#db-remove--' + id + ' .is-close').click();
            });
        }
    },
    mounted() {
        this.loadVerifications()
    }
}
</script>

<style scoped>

</style>
