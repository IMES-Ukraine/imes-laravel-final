<template>
    <tr class="db__row">
        <td class="db__td is-id">
            {{ id }}
        </td>
        <td class="db__td is-account text-left">
            {{ request.name }}
        </td>
        <td class="db__td is-mail">
            {{ request.email }}
        </td>
        <td class="db__td">
            <button type="button" class="db__button" aria-label="переглянути реєстраційні дані" title="переглянути реєстраційні дані" data-toggle="modal" :data-target="'#db-modal--' + id"> <!-- --{{ record.id }} -->
                <span class="icon-is-doc"></span>
            </button>
            <!-- modal data -->
            <div class="modal db-modal fade" :id="'db-modal--' + id" tabindex="-1" role="dialog" aria-hidden="true"><!-- --{{ record.id }} -->
                <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
                    <div class="db-edit-modal__content modal-content">
                        <button class="articles_create-close" data-dismiss="modal"></button>
                        <div class="modal-body p-0">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">ПІБ</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.name" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Email</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.email" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label class="form-control__label">Телефон</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.phone" readonly>
                                </div>
                                <div class="form-group col-6">
                                    <label class="form-control__label">Город</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.user_city.name" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Місце роботи</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.user_work.name" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label class="form-control__label">Сотрудник номер</label>
                                    <input class="form-control db-edit-modal__input" type="text" :value="request.company_id" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal data -->
        </td>
        <td class="db__td report__check">
            <input class="custom__checkbox" type="checkbox" :name="'report-radio--' + id" :id="'transfer-radio--'+ id" @click="$emit('onConfirmRequest' ,id)" data-request-flash> <!-- -- {{ record.id }} --> <!-- checked -->
            <label class="custom__label" :for="'transfer-radio--' + id" aria-label="Прийняти" title="Прийняти"></label> <!-- -- {{ record.id }} -->
        </td>
        <td class="db__td report__check">
            <input class="custom__checkbox" type="radio" :name="'report-radio--' + id" :id="'report-radio-off--'+ id" @click="$emit('onDeclineRequest' ,id)" data-request-flash=""> <!-- --{{ record.id }} -->
            <label class="custom__label is-off" :for="'report-radio-off--' + id" aria-label="Відхилити" title="Відхилити"></label> <!-- --{{ record.id }} -->
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
        },
    }
</script>

<style scoped>

</style>
