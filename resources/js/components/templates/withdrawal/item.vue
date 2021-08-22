<template>
    <tr class="db__row">

        <td class="db__td is-account">
            {{ email }}
        </td>
        <td class="db__td is-sum">
                            <span class="db__sum">
{{ total }}						</span>
            <!-- modal -->
            <div class="modal fade report-modal"
                 id="db-modal--1"
                 tabindex="-1"
                 role="dialog"
                 aria-hidden="true"> <!-- --1 -->
                <div class="modal-dialog modal-dialog-centered"
                     role="document">
                    <div class="report__content modal-content">
                        <div class="modal-body p-0 text-center">
                            <!-- img чекa -->
                            <img class="report__img" src="assets/report.jpg">
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
        email: {
            type: String,
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
