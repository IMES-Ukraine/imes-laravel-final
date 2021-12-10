<template>

    <tr class="db__row">
        <td class="db__td is-id">
            {{ index }}
        </td>
        <td class="db__td is-id">
            {{ record.user.id }}
        </td>
        <td class="db__td is-account">
            {{ record.user.name }}
        </td>
        <td class="db__td">
            <button type="button" class="db__button"
                    @click="showModal(record.user)"
                    aria-label="переглянути реєстраційні дані"
                    title="переглянути реєстраційні дані" >
                <span class="icon-is-doc"></span>
            </button>
        </td>
        <td class="db__td">
            {{ record.user.balance }}
        </td>
        <td class="db__td is-action">
            <div class="db__check">
                <div class="db__check-info">Верифицировать</div>
                <input class="custom__checkbox" type="checkbox" :name="'db-block-' + record.user.id"
                       @click="$emit('onAcceptVerification', record.user.id)"
                       :id="'db-block-' + record.user.id"
                       data-request-flash>
                <label class="custom__label is-block" :for="'db-block-' + record.user.id" title="Верифицировать аккаунт"></label>
                <!--<a href="#" data-request="accountRequest::onAcceptVerification">Верифицировать</a>-->
            </div>

            <div class="db__check">
                <div class="db__check-info">Отклонить</div>
                <input class="custom__checkbox" type="checkbox" :name="'db-block' + record.user.id"
                       @click="$emit('onDeclineVerification', record.user.id)"
                       :id="'db-block-d-' + record.user.id" data-request-flash checked>
                <label class="custom__label is-block" :for="'db-block-' + record.user.id"
                       aria-label="Заблокувати аккаунт" title="Заблокувати аккаунт"></label>
            </div>
            <!--<div class="db__check">
                <a href="#" data-request="accountRequest::onDeclineVerification">Отклонить</a>
            </div>-->

            <div class="db__check" data-request-flash>
                <div class="db__check-info">Удалить</div>
                <button type="button" class="btn btn-outline-second is-sq-small" aria-label="видалити"
                        title="видалити" data-toggle="modal" :data-target="'#db-remove--' + record.user_id">
                    <span class="icon-is-x"></span>
                </button>

                <div class="modal db-modal fade" :id="'db-remove--' + record.user_id" tabindex="-1"
                     role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered db-modal__dialog" role="document">
                        <div class="db-modal__content modal-content">
                            <div class="modal-header db-modal__header">
                                <h5 class="modal-title db-modal__title">Видалити користувача?</h5>
                            </div>
                            <div class="modal-body db-modal__body is-remove">
                                <button type="button" class="db-modal__button is-close"
                                        data-dismiss="modal" aria-label="Close">Ні
                                </button>
                                <button class="db-modal__button is-remove" @click="$emit('onDeleteUser' , record.user_id)">Так</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </td>
    </tr>

    <!--<tr class="db__row">
        <td class="db__td is-id">
            {{ email }}
        </td>
        <td class="db__td">
            <json-field v-bind:json=basic_information>
            </json-field>
        </td>
        <td class="db__td">
            <json-field v-bind:json=specialized_information>
            </json-field>
        </td>

        <td class="db__td">
            <div class="db__check">
                <a @click="$emit('process',{action: 'accept', id: user_id})">Верифицировать</a>
            </div>
        </td>

        <td class="db__td">
            <div class="db__check">
                <a @click="$emit('process', {action: 'decline', id: user_id})">Отклонить</a>
            </div>
        </td>
    </tr>-->

</template>

<script>
import jsonField from "./jsonField"
import  ModalMixin from "../../../ModalMixin"

export default {
    name: "item",
    mixins: [ModalMixin],
    components: {jsonField},
    props: {
        index: Number,
        record: {
            type: Object,
            require: true,
        }
    }
}
</script>

<style scoped>

</style>
