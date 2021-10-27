<template>
    <b-modal v-model="is_show"
             :noCloseOnBackdrop = true
             :noCloseOnEsc = true
             class="db-modal"  >
        <template #modal-header-close>
            <!-- Emulate built in modal header close button action -->
            <b-button size="sm" variant="outline-danger" @click="closeModal()" class="articles_create-close"></b-button>
            <h5>Дані картки {{request.id}}</h5>
        </template>
        <template #modal-title>
            <button class="articles_save" @click="saveData()" >Зберегти</button>
        </template>
        <template #modal-footer><p></p></template>
                <div class="modal-body p-0">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">* Назва</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">* Вартість</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.cost">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">* Короткий опис</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.short_description">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label class="form-control__label">Детальний опис</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.description">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">Фото</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.cover">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-control__label">Категорія</label>
                            <input class="form-control db-edit-modal__input" type="text"
                                   v-model="data.category_id">
                        </div>
                    </div>

                </div>
    </b-modal>
</template>

<script>
import {CARDS} from "../../api/endpoints";
import ModalMixin from "../../ModalMixin";

export default {
    name: "modal-card",
    mixins: [ModalMixin],
    computed: {
        is_show() {
            return this.$store.state.showUserModal;
        },
        request() {
            return this.$store.state.modalData;
        },
        data() {
            return {
                name: this.request.name,
                cost: this.request.cost,
                short_description: this.request.short_description,
                description: this.request.description,
                photo: this.request.photo,
                category_id: this.request.category_id,

            }
        },

    },
    methods: {
        saveData() {
            if (this.request.id) {
                axios.put(CARDS + '/' + this.request.id, this.data).then((res) => {
                    this.closeModal();
                    this.loadCards();
                    this.showMsgBox(res.data.status ? 'Дані картки оновлено' : 'Під час збереження даних виникла помилка');
                });
            }
            else {
                axios.post(CARDS, this.data).then((res) => {
                    this.loadCards();
                    this.closeModal();
                    this.showMsgBox(res.data.status ? 'Картку створено' : 'Під час збереження даних виникла помилка');
                });
            }
        },

    }
}
</script>

<style scoped>

</style>
