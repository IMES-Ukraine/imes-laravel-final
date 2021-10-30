<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th is-pd">№ п</th>
            <th class="db__th is-pd">№ аккаунта</th>
            <th class="db__th is-pd">Сумма перевода</th>
            <th class="db__th is-pd">Карточка</th>
            <th class="report__th">Статус</th>
            <!--<th class="report__th">Отклонить</th>-->
        </tr>
        <tbody class="search-results">
        <!-- line header -->
        <!-- line -->

            <item
                v-for="(request) in requests"
                v-bind:key="request.id"
                :id="request.id"
                :total="Math.trunc(request.cost)"
                :user="(request.user)?request.user:{}"
                :card="(request.card)?request.card:{}"
                :comment="request.comment"
                :status="request.is_active"
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
