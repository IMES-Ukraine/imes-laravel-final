<template>
    <ul>
        <li v-for="field in json">
            <template v-if="typeof field !== 'object'">
                {{ field }}
            </template>
            <template v-else>
                <a target="_blank" :href="field.path">Зображення документа</a>
            </template>
        </li>
    </ul>
</template>

<script>
export default {
    name: "jsonField",
    props: {
        json : {
            type: Object,
            require: true
        }
    }
}
</script>

<style scoped>

</style>
