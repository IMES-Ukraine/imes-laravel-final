<template>
    <b-modal v-model="is_show" class="db-modal"  >
        <template #modal-header-close>
            <!-- Emulate built in modal header close button action -->
            <b-button size="sm" variant="outline-danger" @click="closeModal()" class="articles_create-close"></b-button>
            <h5>Особисті дані користувача {{request.id}}</h5>
        </template>
        <template #modal-title>
            <button class="articles_save" @click="saveData()" >Зберегти</button>
        </template>
                <div class="modal-body p-0">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">Ім'я</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.basic_information.name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">Прізвище</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.basic_information.surname">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">Email</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.email">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="form-control__label">Телефон</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.phone">
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

export default {
    name: "modal-user",
    props: {
        request: {
            type: Object,
            require: true
        },
        is_show: {
            type: Boolean,
            required: true
        },
        message: ''
    },
    computed: {
        data() {
            return {
                phone: this.request.phone,
                email: this.request.email,
                basic_information: this.request.basic_information || {},
                specialized_information: this.request.specialized_information || {},

            }
        },

    },
    methods: {
        saveData() {
            axios.put(CLIENTS + '/' + this.request.id, {
                data: this.data
            }).then( (res) => {
                this.closeModal();
                alert(res.data.message)
            });
        },
        closeModal() {
            this.$store.state.showUserModal = false;
        }
    }
}
</script>

<style scoped>

</style>
