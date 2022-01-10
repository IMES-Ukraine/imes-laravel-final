<template>
    <select
        class="form-control"
        :name="name"
        v-model="local"
        @change="update"
    >
        <option
            v-for="item in options"
            :value="item[optionValue]"
            :key="item[optionKey]"
            :selected="local === item[optionKey]"
        >
            {{ item[optionView] }}
        </option>
    </select>
</template>

<script>
export default {
    name: "v-select",
    data () {
        return {
            local: this.currentKey !== '' ?
                this.currentKey :
                this.options[0][this.optionKey]
        }
    },
    props: {
        options: {
            type: Array,
            require: true
        },
        optionKey: {
            type: String,
            require: true
        },
        optionValue: {
            type: String,
            require: true
        },
        optionView: {
            type: String,
            require: true
        },
        name: {
            type: String,
            default: 'select'
        },
        currentKey: {
            default: ''
        },

    },
    methods: {
        update () {
            this.$emit('update:value', this.local)
        }
    }
}
</script>
