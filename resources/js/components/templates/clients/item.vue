<template>
    <tr class="db__row">

        <td class="db__td is-id">
            {{ id }}
        </td>
        <td class="db__td is-id">
            {{ request.email }}
        </td>
        <td class="db__td is-account" v-if="basic_information">
            {{ basic_information.name }} {{ basic_information.surname }}
        </td>
        <td class="db__td is-account" v-else="basic_information">
            {{ request.username}}
        </td>
        <td class="db__td">
            <button type="button" class="db__button" aria-label="переглянути реєстраційні дані" title="переглянути реєстраційні дані" data-toggle="modal" :data-target="'#db-modal--' + id"> <!-- --224 -->
                <span class="icon-is-doc"></span>
            </button>
            <!-- modal -->
            <div class="modal db-modal fade" :id="'db-modal--' + id" tabindex="-1" role="dialog" aria-modal="true" style="display: none;"><!-- 224 -->
                <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
                    <div class="db-edit-modal__content modal-content">
                        <div class="modal-body p-0">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">ПІБ</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="((basic_information)?basic_information.name:'')" readonly="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Email</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((basic_information)?basic_information.email:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="form-control__label">Телефон</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((basic_information)?basic_information.phone:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Спецификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.specification:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Квалификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.qualification:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Место работы</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.workplace:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Должность</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.position:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Номер лицензии</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.licenseNumber:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Период обучения</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.studyPeriod:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Дополнительная квалификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((specialized_information)?specialized_information.additional_qualification:'')" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->
        </td>
        <td class="db__td">
            {{ request.balance }}
        </td>
        <td class="db__td is-action">
            <div class="db__check" :data-request-data="id">
                <div class="db__check-info" :id="'check-info-' + id">{{ activeTextBlockUser(request.is_activated) }}</div>
                <input class="custom__checkbox"
                       type="checkbox"
                       :name="'db-block--' + id"
                       :id="'db-block--' + id"
                       @click="(request.is_activated)?$emit('onBlockUser', id):$emit('onUnBlockUser', id)"
                       :checked="request.is_activated"
                >
                <!-- checked -- активный аккаунт -->
                <label class="custom__label is-block" :for="'db-block--' + id" aria-label="Заблокувати аккаунт" title="Заблокувати аккаунт"></label>
            </div>
            <div class="db__check" :data-request-data="id" data-request="accountRequest::onBlockUser" data-request-flash="">
                <div class="db__check-info">Видалити</div>
                <button type="button" class="btn btn-outline-second is-sq-small" aria-label="видалити" title="видалити" data-toggle="modal" :data-target="'#db-remove--' + id">
                    <span class="icon-is-x"></span>
                </button>
                <!-- modal remove -->
                <div class="modal db-modal fade" :id="'db-remove--' + id" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered db-modal__dialog" role="document">
                        <div class="db-modal__content modal-content">
                            <div class="modal-header db-modal__header">
                                <h5 class="modal-title db-modal__title">Видалити користувача?</h5>
                            </div>
                            <div class="modal-body db-modal__body is-remove">
                                <button type="button" class="db-modal__button is-close" data-dismiss="modal" aria-label="Close">Ні</button>
                                <button class="db-modal__button is-remove" @click="$emit('onDeleteUser' ,id)">Так</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal remove -->
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "item",
        props: {
            id: {
                type: Number,
                require: true
            },
            request: {
                type: Array,
                require: true
            },
            basic_information: {
                type: Object,
                require: false
            }
        },
        methods: {
            activeTextBlockUser (status) {
                return this.$store.state.checkbox[status];
            }
        }
    }
</script>

<style scoped>

</style>
