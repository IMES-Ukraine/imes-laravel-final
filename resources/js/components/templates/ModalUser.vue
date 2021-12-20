<template>
    <b-modal v-model="is_show" v-if="is_show"
             :noCloseOnBackdrop=true
             :noCloseOnEsc=true
             class="db-modal">
        <template #modal-header-close>
            <!-- Emulate built in modal header close button action -->
            <b-button size="sm" variant="outline-danger" @click="closeModal()" class="articles_create-close"></b-button>
        </template>
        <template #modal-title>
            <div class="space-between">
                <button class="button-border" @click="saveData()">Зберегти</button>
                <h5>Особисті дані користувача {{ request.id }}</h5>
            </div>
        </template>
        <template #modal-footer><p></p></template>
        <div class="modal-body p-0">
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">ПІБ</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.basic_information.name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Email</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.basic_information.email">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <label class="form-control__label">Телефон</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.basic_information.phone">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Спецификация</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.specification">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Квалификация</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.qualification">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Место работы</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.workplace">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Должность</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.position">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Номер лицензии</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.licenseNumber">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Период обучения</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.studyPeriod">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="form-control__label">Дополнительная квалификация</label>
                    <input class="form-control db-edit-modal__input" type="text"
                           v-model="data.specialized_information.additional_qualification">
                </div>
            </div>
        </div>
    </b-modal>
</template>

<script>
import {CLIENTS} from "../../api/endpoints";
import ModalMixin from "../../ModalMixin";

export default {
    name: "modal-user",
    mixins: [ModalMixin],
    computed: {
        request() {
            return this.$store.state.modalData || {};
        },
        is_show() {
            return this.$store.state.showUserModal;
        },
        data() {
            return {
                phone: this.request.basic_information.phone || '',
                email: this.request.basic_information.email || '',
                name: this.request.basic_information.name || '',
                basic_information: this.request.basic_information,
                specialized_information: this.request.specialized_information,

            }
        },

    },
    methods: {
        saveData() {
            let this_reference = this;
            console.log(this.data);
            axios.put(CLIENTS + '/' + this.request.id, {
                data: this.data
            }).then( (resp) => {
                if (resp.status === 200) {
                    this_reference.$bvModal.msgBoxOk('Пользователь успешно изменен')
                        .then(() => {
                            this_reference.closeModal();
                        });
                } else {
                    console.log(resp.data);
                }
            }).catch(resp => {
                this_reference.$bvModal.msgBoxOk("Возникла ошибка: " + resp.data.data.error);
            })
        },

    }
}
</script>

<style scoped>
.space-between {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 15px;
}

.space-between button {
    margin-right: 20px;
}
</style>
