<template>
    <tr v-if="user.email" class="db__row">

        <td class="db__td is-id">
            {{ id }}
        </td>
        <td class="db__td is-account">
            {{ user.id }}
        </td>
        <td class="db__td is-sum">
            <button type="button" class="db__button" aria-label="переглянути реєстраційні дані" title="переглянути реєстраційні дані" data-toggle="modal" :data-target="'#db-modal--' + id"> <!-- --224 -->
                <span class="icon-is-doc"></span>
            </button>
            <span class="db__sum">{{ total }}</span>

            <div class="modal db-modal fade" :id="'db-modal--' + id" tabindex="-1" role="dialog"
                 aria-hidden="true"><!-- data -->
                <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
                    <div class="db-edit-modal__content modal-content">
                        <button class="articles_create-close" data-dismiss="modal"></button>
                        <div class="modal-body p-0">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Имя</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.name)?user.name:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Email</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.email)?user.email:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="form-control__label">Телефон</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.phone)?user.phone:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Спецификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.specification:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Квалификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.qualification:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Место работы</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.workplace:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Должность</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.position:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Номер лицензии</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.licenseNumber:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Период обучения</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.studyPeriod:'')" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Дополнительная квалификация</label>
                                    <input class="form-control db-edit-modal__input" type="text"
                                           :value="((user.specialized_information)?user.specialized_information.additional_qualification:'')" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td class="db__td is-comment">
            <b>{{ card.name }}</b>
            <p>{{ card.created_at.substr(0, 10) }}</p>
            <p>{{ card.short_description }}</p>
        </td>
        <td class="db__td report__check">
            <input class="custom__checkbox"
                   type="checkbox"
                   :name="'report-radio--' + id"
                   :checked="status == 1"
                   :id="'report-radio-on--'+ id"
                   @click="$emit('accept' , id)"
            > <!-- --1 -->
            <label class="custom__label"
                   :for="'report-radio-on--' + id"
                   aria-label="Прийняти"
                   title="Прийняти"></label> <!-- --1 -->
        </td>
        <!--<td class="db__td report__check">
            <input class="custom__checkbox"
                   type="radio"
                   :name="'report-radio--' + id"
                   :id="'report-radio-off--'+id "
                   :checked="status == 2"
                   @click="$emit('decline' ,id)">
            <label class="custom__label is-off"
                   :for="'report-radio-off--' + id"
                   aria-label="Відхилити"
                   title="Відхилити"></label>
        </td>-->
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
        status: {
            type: Number,
            require: true
        },
        total: {
            type: Number,
            require: true
        },
        user: {
            type: Array,
            require: true
        },
        card: {
            type: Array,
            require: true
        },
        comment: {
            type: String,
            require: true
        }
    },
}
</script>

<style scoped>

</style>
