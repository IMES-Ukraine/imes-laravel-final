<template>
    <div>
        <a class="btn btn-outline-primary btn-block has-icon" data-toggle="modal" data-target="#db-balance">
            <slot></slot>
        </a>
        <!-- modal -->
        <div class="modal db-modal fade" tabindex="-1" role="dialog" id="db-balance" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog" role="document">
                <div class="db-modal__content modal-content">
                    <div class="modal-header db-modal__header">
                        <h5 class="modal-title db-modal__title">Персональная отправка данных</h5>
                    </div>
                    <div class="modal-body db-modal__body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label class="form-control__label">Пользователь</label>
                                <v-autocomplete :items="items" v-model="item" :get-label="getLabel" :component-item='template' @update-items="updateItems"></v-autocomplete>
                                <div id="users-result" v-if="userSelect">
                                    <div>
                                        <b @click="removeUser">X</b> {{userSelect}}
                                    </div>
                                </div>
                                <input type="hidden" id="user_id" value=""/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label class="form-control__label">Кол-во балов</label>
                                <input class="form-control db-edit-modal__input" id="user_balabce" name="balance" type="text" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <button type="button" class="btn btn-outline-primary" @click="changeBalance" data-toggle="modal" :data-target="'#db-add--' + id">
                                    Отправить
                                </button>
                            </div>
                        </div>
                    </div>
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
                userSelect: ''
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
                this.$post(CLIENT_CHANGE_BALANCE, {id: $('#user_id').val(), count: $('#user_balabce').val()}).then(
                    response => {
                        $('#db-balance').modal('hide');
                    }
                )
            }
        }
    }
</script>
