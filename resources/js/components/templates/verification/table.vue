<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th">№ п</th>
            <th class="db__th">№ аккаунта</th>
            <th class="db__th">ПІБ</th>
            <th class="db__th">Реєстраційні дані</th>
            <th class="db__th">Бали</th>
            <th class="db__th">Управлiння</th>
        </tr>
        <!-- line header -->
        <!-- line -->
        <tbody class="search-results">
        <!-- line -->
            <item v-for="(request, index) in requests"
                  v-bind:key=request.user_id
                  :user_id = request.user_id
                  :record = request
                  :id= request.user.email
                  :email = request.user.email
                  :basic_information = request.user.basic_information
                  :specialized_information = request.user.specialized_information
                  :index="index + 1"
                  v-on="$listeners"
            ></item>
        <!-- line -->
        </tbody>


    </table>
</template>

<script>
import Item from './item'

export default {
    name: "tableVerify",
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
