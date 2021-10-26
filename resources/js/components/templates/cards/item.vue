<template>
    <tr class="db__row">

        <td class="db__td is-id">
            {{ index }}
        </td>

        <td class="db__td is-id">
            {{ request.id }}
        </td>

        <td class="db__td is-account" >
            {{ request.cost }}
        </td>

        <td class="db__td is-account" >
            {{ request.name }}
        </td>

        <td class="db__td is-account" >
            {{ request.short_description }}
        </td>
        <td class="db__td">
            <button type="button" class="db__button"
                    @click="showModal(request)"
                    aria-label="переглянути дані"
                    title="переглянути дані" >
                <span class="icon-is-doc"></span>
            </button>

        </td>
        <td class="db__td is-action">
            <div class="db__check" :data-request-data="request.id">
                <div class="db__check-info" >{{ activeTextBlockCard(request.is_active) }}</div>
                <input class="custom__checkbox"
                       type="checkbox"
                       @click="(request.is_enabled) ? $emit('onDisableCard', request.id) : $emit('onEnableCard', request.id)"
                       :checked="request.is_active"
                >
                <!-- checked -- активный аккаунт -->
                <label class="custom__label is-block"  aria-label="Деактивувати картку" title="Деактивувати картку"></label>
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
                                <button class="db-modal__button is-remove" @click="$emit('onDeleteCard', request.id)">Так</button>
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
            activeTextBlockCard (status) {
                return this.$store.state.checkbox[status];

            },
        }
    }
</script>

<style scoped>

</style>
