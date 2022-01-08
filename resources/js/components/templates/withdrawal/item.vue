<template>
    <div class="card__table-block" v-if="user.email">
        <div class="card__table-item">
            <p class="card__table-number">{{ index }}</p>
        </div>
        <div class="card__table-item">
            <p class="card__table-id">{{ user.id }}</p>
        </div>
        <div class="card__table-item">
            <div class="card__table-sum">
                <button type="button" class="db__button"
                        @click="showModal(user)"
                        aria-label="переглянути реєстраційні дані"
                        title="переглянути реєстраційні дані" >
                    <span class="icon-is-doc"></span>
                </button>
                <p>{{ total }}</p>
            </div>
        </div>
        <div class="card__table-item">
            <div class="card__table-content">
                <p><b>{{ card.name }}</b></p>
                <p>{{ card.created_at.substr(0, 10) }}</p>
                <p>{{ card.short_description }}</p>
            </div>
        </div>
        <!--<div class="card__table-item">
            <div class="card__table-checkbox">
                <input class="custom__checkbox"
                       type="checkbox"
                       :name="'report-radio--' + id"
                       :checked="status == 1"
                       :id="'report-radio-on--'+ id"
                       @click="$emit('accept' , id)"
                >
                <label class="custom__label"
                       :for="'report-radio-on--' + id"
                       aria-label="Прийняти"
                       title="Прийняти"></label>
            </div>
        </div>-->
    </div>
</template>

<script>
    import  ModalMixin from "../../../ModalMixin"

export default {
    name: "item",
    mixins: [ModalMixin],
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
            type: Object,
            require: true
        },
        card: {
            type: Object,
            require: true
        },
        comment: {
            type: String,
            require: true
        },
        index: {
            type: Number,
            require: true
        }
    },
}
</script>

<style scoped>

</style>
