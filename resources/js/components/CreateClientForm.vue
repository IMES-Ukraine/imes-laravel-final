<template>
    <v-content>
        <template v-slot:sidebar>
            <SidebarUsers></SidebarUsers>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="db-edit card is-form">
                    <div class="card-body is-form">
                        <form
                            id="app"
                            @submit="checkForm"
                            :action="create()"
                            method="post"
                        >
                            <!--<div v-if="errors" class="errors">
                                <ul>
                                    <li v-for="error in errors">{{ error }}</li>
                                </ul>
                            </div>-->
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">ФИО</label>
                                    <input class="form-control db-edit-modal__input" v-model="name" name="name"
                                           type="text" value="">
                                    <div v-if="errors && errors.name" class="errors">{{ errors.name }}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Email</label>
                                    <input class="form-control db-edit-modal__input" name="email" type="email" v-model="email">
                                    <div v-if="errors && errors.email" class="errors">{{ errors.email }}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Телефон</label>
                                    <input class="form-control db-edit-modal__input js-input-phone-mask"
                                           ref="phone-input" name="phone" type="tel" value="" placeholder="+380 (**) *** ** **"
                                           maxlength="19">
                                    <div v-if="errors && errors.phone" class="errors">{{ errors.phone }}</div>
                                </div>
                                <!--<div class="form-group col-6">
                                    <label class="form-control__label">Город</label>
                                    <select class="form-control js-example-tags" name="city">
                                                                    </select>
                                </div>-->
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Пароль</label>
                                    <input class="form-control db-edit-modal__input" v-model="password" name="password"
                                           type="text" value="">
                                    <div v-if="errors && errors.password" class="errors">{{ errors.password }}</div>
                                </div>
                            </div>
                            <!--<div class="form-row mb-2">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Місце роботи</label>
                                    <select class="form-control js-example-tags" name="work">
                                                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-4">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Співробітник компанії</label>

                                    <select class="form-control js-example-tags" name="company_id">
                                                                    </select>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <!-- js обработчик в файле /admin/front/js/main.js - new account -->
                                    <button type="submit" class="btn btn-outline-primary js-send-new-account"
                                            data-attach-loading="">
                                        Отправить запрос <span class="icon-is-arrow icon-is-right"></span>
                                    </button>
                                    <!-- modal new user -->
                                    <div class="modal db-modal fade" id="new-account" tabindex="-1" role="dialog"
                                         aria-hidden="true"><!-- --2 -->
                                        <div class="modal-dialog modal-dialog-centered db-modal__dialog"
                                             role="document">
                                            <div class="db-modal__content modal-content">
                                                <div class="modal-header db-modal__header is-new-user"></div>
                                                <div class="modal-body db-modal__body is-new-user">
                                                    <div class="db-modal__text">
                                                        Анкета сохранена.<br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- modal new user -->
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
import SidebarUsers from "./templates/SidebarUsers"
import ClientsTable from './templates/clients/table-user'
import {USER} from "../api/endpoints"
import VPreloader from "./fragmets/preloader"

export default {
    name: "createClient",
    components: {
        VContent, SidebarUsers, ClientsTable, VPreloader
    },
    data() {
        return {
            errors: {
                type: Object,
                //require: true
            },
            name: '',
            phone: '',
            email: '',
            password: ''
        }
    },
    methods: {
        checkForm: function (e) {
            this.errors = {};

            if (!this.name) {
                this.errors.name = 'Укажите имя';
            }
            if (!this.email) {
                this.errors.email = 'Укажите email';
            }
            this.phone = this.$refs['phone-input'].value;
            if (!this.phone) {
                this.errors.phone = 'Укажите телефон';
            } /*else if (!this.validEmail(this.email)) {
                    this.errors.push('Укажите корректный адрес электронной почты.');
                }*/

            if (!this.password) {
                this.errors.password = 'Укажите пароль';
            }

            if (!Object.keys(this.errors).length) {
                return true;
            }

            e.preventDefault();
        },
        validEmail: function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        create() {
            return USER;
            //this.$post(USER).then()
        },
    }
}

$(document).ready(function () {
    $("input[type='tel']").mask("+380 (99) 999 99 99");
});
</script>
