<template>
    <table class="db__table">
        <!-- line header -->
        <tr class="db__row is-th">
            <th class="db__th">№ п</th>
            <th class="db__th">Имя</th>
            <th class="db__th">E-mail</th>
            <th class="db__th">Реєстраційні дані</th>
            <th class="db__th">Прийняти</th>
            <th class="db__th">Відхилити</th>
        </tr>
        <tbody class="search-results">
            <!-- line header -->
            <!-- line -->

            <template>
                <item
                    v-for="(request) in requests"
                    v-bind:key="request.id"
                    :id="request.id"
                    :request="request"
                    v-on="$listeners"
                ></item>
            </template>

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
                //require: true
            }
        }
    }
</script>

<style scoped>

</style>
