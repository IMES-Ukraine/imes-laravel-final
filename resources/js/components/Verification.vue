<template>
    <v-content>

        <template v-slot:sidebar>
            <ProjectFormSidebar></ProjectFormSidebar>
        </template>


        <div class="main-db">
            <div class="db__block">
                <div class="card">
                    <div class="card-body">
                        <verification-table v-if="hasRequests()"
                                        v-bind:requests="requests"
                                        v-on:process="process"
                        ></verification-table>
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
import VerificationTable from "./templates/verification/table"
import VPreloader from "./fragmets/preloader"
import {PROFILE_VERIFICATION,PROFILE_VERIFICATION_LIST} from "../api/endpoints"


export default {
name: "Verification",
    components: {ProjectFormSidebar, VContent, VerificationTable,VPreloader},
    data() {
        return {
            requests: [],
        }
    },
    methods: {
        hasRequests() {
            return !!Object.keys(this.requests).length
        },
        async loadRequests() {
            this.$get(PROFILE_VERIFICATION_LIST).then( response => {
                this.requests = response.data
            })
        },
        process( params) {
            console.log(params)
            this.$post(PROFILE_VERIFICATION + params.action, params).then( response => {
                this.requests = this.requests.filter( item => {
                    return item.user_id != params.id;
                })
            })
        }
    },
    mounted() {
        this.loadRequests()
    }
}
</script>

<style scoped>

</style>
