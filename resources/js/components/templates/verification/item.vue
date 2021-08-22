<template>

    <tr class="db__row">
        <td class="db__td is-id">
            {{ email }}
        </td>
        <td class="db__td">
            <json-field v-bind:json=basic_information>
            </json-field>
        </td>
        <td class="db__td">
            <json-field v-bind:json=specialized_information>
            </json-field>
        </td>

        <td class="db__td">
            <div class="db__check">
                <a @click="$emit('process',{action: 'accept', id: user_id})">Верифицировать</a>
            </div>
        </td>

        <td class="db__td">
            <div class="db__check">
                <a @click="$emit('process', {action: 'decline', id: user_id})">Отклонить</a>
            </div>
        </td>
    </tr>

</template>

<script>
import jsonField from "./jsonField";
export default {
    name: "item",
    components: {jsonField},
    props: {
        user_id: {
            type: Number,
            require: true,
        },
        email: {
            type: String,
            require: true,
        },
        basic_information: {
            type: Object,
            require: true,
        },
        specialized_information: {
            type: Object,
            require: true,
        }
    }
}
</script>

<style scoped>

</style>
