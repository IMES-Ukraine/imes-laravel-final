<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th is-pd">№ п</th>
            <th class="db__th is-pd">№ аккаунта</th>
            <th class="db__th is-pd">сумма</th>
            <th class="db__th is-pd">коментарий</th>
            <th class="report__th">Принять</th>
            <th class="report__th">Отклонить</th>
        </tr>
        <tbody class="search-results">
        <!-- line header -->
        <!-- line -->

            <item
                v-for="(request) in requests"
                v-bind:key="request.id"
                :id="request.id"
                :total="request.total"
                :user="(request.user)?request.user:{}"
                :comment="request.comment"
                :status="request.status"
                v-on="$listeners"
            ></item>


        <tr class="db__row">
<!--            <td class="db__td is-account">
                Переводов не найдено
            </td>-->
        </tr>
        </tbody>

    </table>
</template>

<script>
import Item from './item'

export default {
    name: "table",
    components: {Item},
    props: {
        requests: {
            type: Array,
            require: true
        }
    }
}
</script>

<style scoped>

</style>
