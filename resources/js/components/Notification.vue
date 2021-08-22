<template>
    <v-content>

        <template v-slot:sidebar>
            <notification-sidebar />
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card is-alert">
                    <div class="card-header is-alert">
                        Повідомлення
                    </div>
                    <div class="card-body is-alert">
                        <form
                            data-request="::onSendAll"
                            data-request-flash
                            data-request-files
                        >
                            <div class="row mb-4">
                                <div class="col-3 db-module__text">
                                    Всі акаунти
                                </div>
                                <div class="col-6">
                                    <textarea
                                        class="form-control is-module"
                                        rows="10"
                                        name="body"
                                    ></textarea>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <button
                                        type="submit"
                                        class="btn btn-outline-primary is-small-size"
                                        data-attach-loading
                                        @click=""
                                    >
                                        Відправити <span class="icon-is-arrow icon-is-right"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form
                            data-request="::onSendTo"
                            data-request-flash
                            data-request-files
                        >
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
                                        >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <textarea
                                        class="form-control is-module"
                                        rows="10"
                                        name="body">
                                    </textarea>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    <button
                                        type="submit"
                                        class="btn btn-outline-primary is-small-size"
                                        data-attach-loading
                                    >
                                        Відправити <span class="icon-is-arrow icon-is-right"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </v-content>

</template>

<script>
    import VContent from "./templates/Content"
    import NotificationSidebar from "./templates/notification/sidebar";

    export default {
    name: "Notification",
        components: {VContent,NotificationSidebar},
        methods: {
            send() {

            }
        }
    }
</script>

<style scoped>

</style>
