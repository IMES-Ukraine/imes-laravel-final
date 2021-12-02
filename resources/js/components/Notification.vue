<template>
    <v-content>
        <div class="main">

            <div class="notifications template_box">
                <p class="notifications-title">Уведомления</p>
                <div class="notifications-box">
                    <ValidationObserver ref="form" v-slot="{ handleSubmit }" class="notifications-block">
                        <form>
                            <div v-if="successNotificationAll">
                                <div style="background: #a5d794; padding: 15px; margin-bottom: 10px; color: #fff;">{{ successNotificationAll }}</div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <div class="notifications__item-checkbox">
                                        <p>Заголовок</p>
                                    </div>
                                </div>
                                <div class="notifications__item-body">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <input class="notifications__item-field" type="text" v-model="all.title" placeholder="Заголовок">
                                        <div class="errors">{{ errors[0] }}</div>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <p class="notifications__item-title">Все пользователи</p>
                                </div>
                                <div class="notifications__item-body">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <textarea class="notifications__item-field" v-model="all.body" placeholder="Текст"></textarea>
                                        <div class="errors">{{ errors[0] }}</div>
                                    </ValidationProvider>
                                    <button class="notifications__item-button sidebar_nav-button active" type="button" @click="submitFormAll"><span>Отправить</span></button>
                                </div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <div class="notifications__item-checkbox">
                                        <input type="checkbox" v-model="all.is_action" :checked="all.is_action" v-on:change="updateTextAll">
                                        <i></i>
                                        <p>Уведомление-ссылка</p>
                                    </div>
                                </div>
                                <div class="notifications__item-body">
                                    <input type="text" name="text" v-model="all.action" v-on:change="updateCheckBoxAll" class="notifications__item-field">
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                    <ValidationObserver ref="form" v-slot="{ handleSubmit }" class="notifications-block">
                        <form>
                            <div v-if="successNotification">
                                <div style="background: #a5d794; padding: 15px; margin-bottom: 10px; color: #fff;">{{ successNotification }}</div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <div class="notifications__item-checkbox">
                                        <p>Заголовок</p>
                                    </div>
                                </div>
                                <div class="notifications__item-body">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <input class="notifications__item-field" type="text" v-model="one.title" placeholder="Заголовок">
                                        <div class="errors">{{ errors[0] }}</div>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <div class="notifications__item-number">
                                        <i>№</i>
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <input type="number" v-model="one.to">
                                            <div class="errors">{{ errors[0] }}</div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="notifications__item-body">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <textarea class="notifications__item-field" v-model="one.body" placeholder="Текст"></textarea>
                                        <div class="errors">{{ errors[0] }}</div>
                                    </ValidationProvider>
                                    <button class="notifications__item-button sidebar_nav-button active" type="button" @click="submitForm"><span>Отправить</span></button>
                                </div>
                            </div>
                            <div class="notifications__item">
                                <div class="notifications__item-head">
                                    <div class="notifications__item-checkbox">
                                        <input type="checkbox" v-model="one.is_action" :checked="one.is_action" v-on:change="updateText">
                                        <i></i>
                                        <p>Уведомление-ссылка</p>
                                    </div>
                                </div>
                                <div class="notifications__item-body">
                                    <input type="text" name="text" v-model="one.action" v-on:change="updateCheckBox" class="notifications__item-field">
                                </div>
                            </div>
                        </form>
                    </ValidationObserver>
                    <div class="notifications-block">
                        <button class="notifications-history" type="button"><span>История уведомлений</span></button>
                    </div>
                </div>
            </div>
        </div>

    </v-content>

</template>

<script>
    import VContent from "./templates/Content"
    import NotificationSidebar from "./templates/notification/sidebar"
    import {NOTIFICATION_ALL, NOTIFICATION_TO, TOKEN} from "../api/endpoints"
    import { ValidationProvider, ValidationObserver } from 'vee-validate'

    export default {
    name: "Notification",
        components: {
            VContent,
            NotificationSidebar,
            ValidationProvider,
            ValidationObserver
        },
        data() {
            return {
                all: {
                    title: '',
                    body: '',
                    action: '',
                    is_action: false
                },
                one: {
                    to: '',
                    title: '',
                    body: '',
                    action: '',
                    is_action: false
                },
                successNotificationAll: '',
                successNotification: ''
            }
        },
        methods: {
            submitFormAll(values) {
                this.$refs.form.validate().then( success => {
                    if (!success) {
                        return;
                    }

                    this.$post(NOTIFICATION_ALL, this.all, {
                        params: {
                            access_token: TOKEN
                        }
                    }).then(r => {
                        this.successNotificationAll = 'Уведомление успешно отправлено'
                    })
                });
            },
            updateCheckBoxAll() {
                if (this.all.action.length > 0) {
                    this.all.is_action = true;
                } else {
                    this.all.is_action = false;
                }
            },
            updateTextAll() {
                if (!this.all.is_action) {
                    this.all.action = ''
                }
            },
            submitForm(values) {
                this.$refs.form.validate().then( success => {
                    if (!success) {
                        return;
                    }

                    this.$post(NOTIFICATION_TO, this.one, {
                        params: {
                            access_token: TOKEN
                        }
                    }).then(r => {
                        this.successNotification = 'Уведомление успешно отправлено'
                    })
                });
            },
            updateCheckBox() {
                if (this.one.action.length > 0) {
                    this.one.is_action = true;
                } else {
                    this.one.is_action = false;
                }
            },
            updateText() {
                if (!this.one.is_action) {
                    this.one.action = ''
                }
            },
            send(all) {
                if (all) {
                    if (!error) {
                        this.$post(NOTIFICATION_ALL, {body: this.body_all, title: this.title_all}).then(r => {
                            console.log(r)
                        })
                    }
                } else {
                    this.$post(NOTIFICATION_TO, {to: this.account, body: this.body, title: this.title}).then( r => {
                        console.log(r)
                    })
                }

            }
        }
    }
</script>

<style scoped>

</style>
