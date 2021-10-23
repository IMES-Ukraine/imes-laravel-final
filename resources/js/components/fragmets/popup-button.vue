<template>
    <div>
        <a class="btn btn-outline-primary btn-block has-icon" data-toggle="modal" data-target="#db-balance">
            <slot></slot>
        </a>
        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="db-balance" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="sending modal-content">
                    <p class="articles_create-title">Персональная отправка данных</p>
                    <button class="articles_create-close" type="button" data-dismiss="modal"></button>
                    <div class="sending-box">
                        <div class="sending__block">
                            <p class="sending__block-title">Пользователь</p>
                            <div class="sending__block-content">
                                <v-autocomplete :items="items" v-model="item" :get-label="getLabel" :input-attrs="{placeholder: 'Введите ID пользователя, ФИО, телефон, почта'}" :component-item='template' @update-items="updateItems"></v-autocomplete>
                                <div id="users-result" v-if="userSelect">
                                    <div>
                                        <b @click="removeUser"></b> {{userSelect}}
                                    </div>
                                </div>
                                <input type="hidden" id="user_id" value=""/>
                                <div v-if="errorUser" class="errors">{{ errorUser }}</div>
                            </div>
                        </div>
                        <div class="articles_create-line"></div>
                        <div class="sending__block">
                            <p class="sending__block-title">Кол-во балов</p>
                            <div class="sending__block-content">
                                <input id="user_balabce" name="balance" type="text" value="" class="template-field">
                                <div v-if="errorBalance" class="errors">{{ errorBalance }}</div>
                            </div>
                        </div>
                    </div>
                    <button class="articles_create-submit button-border" type="button" @click="changeBalance" :data-target="'#db-add--' + id">отправить</button>
                </div>
            </div>
        </div>
        <!-- modal -->
    </div>
</template>

<script>
    import ItemTemplate from '../templates/project/form/ItemTemplate.vue'
    import { SEARCH_USER, CLIENT_CHANGE_BALANCE } from "../../api/endpoints"

    export default {
        name: "popup-button",
        components: {
            ItemTemplate
        },
        data () {
            return {
                item: {},
                items: [],
                template: ItemTemplate,
                userSelect: '',
                errorUser: '',
                errorBalance: ''
            }
        },
        methods: {
            removeUser () {
                this.userSelect = ''
            },
            getLabel (item) {
                if (item) {
                    this.userSelect = item.name
                    $('#user_id').val(item.id);
                    return item.name
                }
            },
            async updateItems (text) {
                await fetch(SEARCH_USER + '/' + text).then( async(response) => {
                    this.items = await response.json()
                })
            },
            changeBalance () {
                this.errorUser = ''
                this.errorBalance = ''

                let user_id = $('#user_id').val()
                let count = $('#user_balabce').val()
                let error = false

                if (!user_id) {
                    this.errorUser = 'Поле обязательно'
                    error = true
                }

                if (count == '') {
                    this.errorBalance = 'Поле обязательно'
                    error = true
                }

                if (!error) {
                    this.$post(CLIENT_CHANGE_BALANCE, {id: user_id, count: count}).then(
                        response => {
                            $('#db-balance').modal('hide');
                        }
                    )
                }
            }
        }
    }
</script>
<style>
    .v-autocomplete-list {
        border-top: 1px solid #F2F2F2;
        padding: 9px 0 13px 0;
    }

    .v-autocomplete-list-item {
        font-weight: 500;
        font-size: 13px;
        line-height: 16px;
        letter-spacing: -0.0024em;
        color: #828282;
        padding-left: 22px;
        cursor: pointer;
    }

    .v-autocomplete-list-item abbr {
        font-weight: 600;
        color: #333;
    }
</style>
