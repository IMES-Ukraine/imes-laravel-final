<template>
    <v-content>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card is-alert">
                    <div class="card-header is-alert">
                        Повідомлення
                    </div>
                    <div class="card-body is-alert">

                            <div class="row mb-4">
                                <div class="col-3 db-module__text">
                                    Всі акаунти
                                </div>
                                <div class="col-6">
                                    <textarea
                                        class="form-control is-module"
                                        rows="10"
                                        name="body"
                                        v-model="body"
                                    ></textarea>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <button
                                        class="btn btn-outline-primary is-small-size"
                                        data-attach-loading
                                        @click="send('all')"
                                    >
                                        Відправити <span class="icon-is-arrow icon-is-right"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-3 db-module__text">
                                    <div class="input-group is-small">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text is-semibold is-module is-dark">№</span>
                                        </div>
                                        <input type="text"
                                               class="form-control input-is-small is-dark is-text-size"
                                               placeholder="акаунта"
                                               aria-label="№ акаунта"
                                               name="to"
                                               v-model="account"
                                        >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <textarea
                                        class="form-control is-module"
                                        rows="10"
                                        name="body"
                                        v-model="body">
                                    </textarea>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <button
                                        class="btn btn-outline-primary is-small-size"
                                        data-attach-loading
                                        @click="send(false)"
                                    >
                                        Відправити <span class="icon-is-arrow icon-is-right"></span>
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </v-content>

</template>

<script>
    import VContent from "./templates/Content"
    import NotificationSidebar from "./templates/notification/sidebar";
    import {NOTIFICATION_ALL, NOTIFICATION_TO} from "../api/endpoints";

    export default {
    name: "Notification",
        components: {VContent,NotificationSidebar},
        data() {
            return {
                account: null,
                body: null,
            }
        },
        methods: {
            send(all) {
                if (all) {
                    this.$post(NOTIFICATION_ALL, {body: this.body}).then( r => {
                        console.log(r)
                    })
                } else {
                    this.$post(NOTIFICATION_TO, {to: this.account, body: this.body}).then( r => {
                        console.log(r)
                    })
                }

            }
        }
    }
</script>

<style scoped>

</style>
