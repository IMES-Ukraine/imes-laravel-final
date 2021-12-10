<template>
    <div class="card__table">
        <div class="card__table-block card__table-block--head">
            <div class="card__table-item">
                <p class="card__table-title">№ п</p>
            </div>
            <div class="card__table-item">
                <p class="card__table-title">№ аккаунта</p>
            </div>
            <div class="card__table-item">
                <p class="card__table-title">Сума перевода</p>
            </div>
            <div class="card__table-item">
                <p class="card__table-title">Карточка</p>
            </div>
            <!--<div class="card__table-item">
                <p class="card__table-title">Статус</p>
            </div>-->
        </div>
        <item
            v-for="(request, index) in requests"
            v-bind:key="request.id"
            :id="request.id"
            :index="index + 1"
            :total="Math.trunc(request.cost)"
            :user="(request.user)?request.user:{}"
            :card="(request.card)?request.card:{}"
            :comment="request.comment"
            :status="request.is_active"
            v-on="$listeners"
        ></item>

        <!-- modal -->
        <modal-user></modal-user>
        <!-- modal -->

    </div>
</template>

<script>
import Item from './item'
import ModalUser from "../ModalUser"
import ModalMixin from "../../../ModalMixin"

export default {
    name: "table",
    components: {Item, ModalUser, ModalMixin},
    props: {
        requests: {
            type: Array,
            require: true
        }
    }
}
</script>
