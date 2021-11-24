<template>
    <tr class="db__row">

        <td class="db__td is-id">
            {{ index }}
        </td>
        <td class="db__td is-id">
            {{ request.id }}
        </td>
        <td class="db__td is-account" v-if="request.name">
            {{ request.name }}
        </td>
        <td class="db__td is-account" v-else>
            {{ request.username}}
        </td>
        <td class="db__td">
            <button type="button" class="db__button"
                    @click="showModal(request)"
                    aria-label="переглянути реєстраційні дані"
                    title="переглянути реєстраційні дані" >
                <span class="icon-is-doc"></span>
            </button>

        </td>
        <td class="db__td">
            {{ request.balance }}
        </td>
        <td class="db__td">
            {{ request.is_verified ? 'Так' : 'Ні' }}
        </td>
        <td class="db__td is-action">
            <div class="db__check" :data-request-data="request.id">
                <div class="db__check-info" :id="'check-info-' + request.id">{{ activeTextBlockUser(request.is_activated) }}</div>
                <input class="custom__checkbox"
                       type="checkbox"
                       :name="'db-block--' + request.id"
                       :id="'db-block--' + request.id"
                       @click="(request.is_activated) ? $emit('onBlockUser', request.id) : $emit('onUnBlockUser', request.id)"
                       :checked="request.is_activated"
                >
                <!-- checked -- активный аккаунт -->
                <label class="custom__label is-block" :for="'db-block--' + request.id" :aria-label="activeTextBlockUser(request.is_activated) + ' аккаунт' " :title="activeTextBlockUser(request.is_activated) + ' аккаунт' "></label>
            </div>
            <div class="db__check" >
                <div class="db__check-info">Видалити</div>
                <button type="button" class="btn btn-outline-second is-sq-small" aria-label="видалити" title="видалити" data-toggle="modal" :data-target="'#db-remove--' +request.id">
                    <span class="icon-is-x"></span>
                </button>
                <!-- modal remove -->
                <div class="modal db-modal fade" :id="'db-remove--' +request.id" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered db-modal__dialog" role="document">
                        <div class="db-modal__content modal-content">
                            <div class="modal-header db-modal__header">
                                <h5 class="modal-title db-modal__title">Видалити користувача?</h5>
                            </div>
                            <div class="modal-body db-modal__body is-remove">
                                <button type="button" class="db-modal__button is-close" data-dismiss="modal" aria-label="Close">Ні</button>
                                <button class="db-modal__button is-remove" @click="$emit('onDeleteUser', request.id)">Так</button>
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
import  ModalMixin from "../../../ModalMixin";

    export default {
        name: "item",
        mixins: [ModalMixin],

        props: {
            index: {
                type: Number,
                require:true
            },
            request: {
                type: Object,
                require: true
            },
        },
        methods: {
            activeTextBlockUser (status) {
                let state = 0;
                if (status) state = 1;

                return this.$store.state.checkbox[state];
            },
        }
    }
</script>

<style scoped>

</style>
