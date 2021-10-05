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
            <!-- modal -->
            <div class="modal db-modal fade" :id="'db-modal--' + id" tabindex="-1" role="dialog" aria-modal="true" style="display: none;"><!-- 224 -->
                <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
                    <div class="db-edit-modal__content modal-content">
                        <div class="modal-body p-0">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">ПІБ</label>
                                    <input class="form-control db-edit-modal__input" type="text" value="" readonly="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Email</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="user.email" readonly="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="form-control__label">Телефон</label>
                                    <input class="form-control db-edit-modal__input" type="text" value="" readonly="">
                                </div>
                                <div class="form-group col-6">
                                    <label class="form-control__label">Город</label>
                                    <input class="form-control db-edit-modal__input" type="text" value="" readonly="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Місце роботи</label>
                                    <input class="form-control db-edit-modal__input" type="text" value="" readonly="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Сотрудник номер</label>
                                    <input class="form-control db-edit-modal__input" type="text" value="" readonly="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->
        </td>
        <td class="db__td is-comment">
            {{ comment }}
        </td>
        <td class="db__td report__check">
            <input class="custom__checkbox"
                   type="radio"
                   :name="'report-radio--' + id"
                   :id="'report-radio-on--'+ id"
                   @click="$emit('accept' ,id)"
            > <!-- --1 -->
            <label class="custom__label"
                   :for="'report-radio-on--' + id"
                   aria-label="Прийняти"
                   title="Прийняти"></label> <!-- --1 -->
        </td>
        <td class="db__td report__check">
            <input class="custom__checkbox"
                   type="radio"
                   :name="'report-radio--' + id"
                   :id="'report-radio-off--'+id "
                   :checked="status == 2"
                   @click="$emit('decline' ,id)"> <!-- --1 -->
            <label class="custom__label is-off"
                   :for="'report-radio-off--' + id"
                   aria-label="Відхилити"
                   title="Відхилити"></label> <!-- --1 -->
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
        comment: {
            type: String,
            require: true
        }
    },
}
</script>

<style scoped>

</style>
