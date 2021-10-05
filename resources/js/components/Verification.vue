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
        }
    },
    methods: {
        async loadVerifications() {

            this.$get(PROFILE_VERIFICATION_LIST).then( response => {
                this.requests = response.data
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

            this.$get(PROFILE_ACCEPT_VERIFICATION + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.user_id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        },
        async onDeclineVerification(id) {

            this.$get(PROFILE_DECLINE_VERIFICATION + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.user_id === id) {
                    this.requests.splice(index, 1)
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
        this.loadVerifications()
    }
}
</script>

<style scoped>

</style>
