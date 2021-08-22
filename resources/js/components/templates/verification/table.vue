<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th">№ п</th>
            <th class="db__th">Базова iнформацiя</th>
            <th class="db__th">Спецiалiзована iнформацiя</th>
            <th class="db__th">Управлiння</th>
            <th class="db__th">Управлiння</th>
        </tr>
        <!-- line header -->
        <!-- line -->
        <tbody class="search-results">
        <!-- line -->
            <item v-for="(request) in requests"
                  v-bind:key=request.user_id
                  :user_id = request.user_id
                  :id= request.user.email
                  :email = request.user.email
                  :basic_information = request.user.basic_information
                  :specialized_information = request.user.specialized_information
                  v-on="$listeners"
            ></item>
        <!-- line -->
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
