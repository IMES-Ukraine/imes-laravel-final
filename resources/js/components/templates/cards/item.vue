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
            <div class="db__check"@click="(request.is_active) ? $emit('onDisableCard', request.id) : $emit('onEnableCard', request.id)" >
                <div class="db__check-info" >{{ activeTextBlockCard(request.is_active) }}</div>
                <input class="custom__checkbox"
                       type="checkbox"
                       :checked="request.is_active" >
                <!-- checked -- активная карта -->
                <label class="custom__label is-block"  :aria-label="activeTextBlockCard(request.is_active) + ' картку' " :title="activeTextBlockCard(request.is_active) + ' картку' "></label>
            </div>

            <div class="db__check" >
                <div class="db__check-info">Видалити</div>
                <button type="button" class="btn btn-outline-second is-sq-small" aria-label="видалити" title="видалити" data-toggle="modal" :data-target="'#db-remove--' +request.id">
                    <span class="icon-is-x"></span>
                </button>

                <!-- modal remove -->
                <div class="modal fade" :id="'db-remove--' +request.id" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog max-width-500 modal-dialog-centered" role="document">
                        <div class="modal_delete modal-content">
                            <div class="modal_delete-title_wrap">
                                <p class="modal_delete-title">Видалити картку?</p>
                            </div>
                            <div class="modal_delete-controls">
                                <button type="button" class="modal_delete-btn is-close" data-dismiss="modal" aria-label="Close">Ні</button>
                                <button class="modal_delete-btn is-remove" @click="$emit('onDeleteCard', request.id)">Так</button>
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
            disableCard(id){
                console.log('disabled: ', id);
            }
        }
    }
</script>

<style scoped>
    .max-width-500 {
        max-width: 500px;
    }
</style>
