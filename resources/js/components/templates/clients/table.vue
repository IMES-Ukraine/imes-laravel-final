<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th">№ п</th>
            <th class="db__th">№ аккаунта</th>
            <th class="db__th">ФИО</th>
            <th class="db__th">Реєстраційні дані</th>
            <th class="db__th">Бали</th>
            <th class="db__th">Управлiння</th>
        </tr>

        <tbody class="search-results">
        <!-- line header -->
        <!-- line -->

        <item
            v-for="(request) in requests"
            v-bind:key="request.id"
            :id="request.id"
            :request="request"
            :basic_information="request.basic_information?JSON.parse(request.basic_information):null"
            v-on="$listeners"
        ></item>

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
            }
        },
        methods: {
            hasRequests() {
                return this.requests.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
