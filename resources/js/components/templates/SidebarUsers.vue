<template>
    <div>
        <router-button :icon="'has-icon-plus'" :isActive="(this.$route.path === '/client/new')?true:false" :url="'/client/new'">
            Добавить
        </router-button>
        <router-button :icon="'has-icon-arrow'" :isActive="(this.$route.path === '/requests')?true:false" :url="'/requests'">
            Запросы
        </router-button>
        <router-button :icon="'has-icon-arrow'" :isActive="(this.$route.path === '/verification')?true:false" :url="'/verification'">
            Верификация
        </router-button>
        <router-button :icon="'has-icon-person'" :isActive="(this.$route.path === '/clients')?true:false" :url="'/clients'">
            Пользователи
        </router-button>
        <router-button :icon="'has-icon-dollar'" :isActive="(this.$route.path === '/withdrawal')?true:false" :url="'/withdrawal'">
            Обмен баллов
        </router-button>

        <div style="margin-top: 150px;">
            <popup-button @update="changeBalance">
                Отправить баллы
            </popup-button>
        </div>

        <div class="input-group input-group mt-5 mb-4">
            <input type="text" id="filterId" class="form-control input-is-small input-has-append"
                   placeholder="пошук по № аккаунта"
                   v-model="filterId"
                   v-on:keyup.enter="findUser(filterId)"
                   aria-label="пошук по № аккаунта" >
            <div class="input-group-append">
                <button class="button-group-input" aria-label="знайти"
                        @click="findUser(filterId)">
                    <span class="icon-is-search"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import RouterButton from "../fragmets/router-button";
    import PopupButton from "../fragmets/popup-button";
    import ModalMixin from "../../ModalMixin";

    export default {
        name: "withdrawal-form-sidebar",
        components: {RouterButton, PopupButton},
        mixins: [ModalMixin],
        data() {
            return {
                filterId: null
            }
        },
        methods: {
            findUser(id) {
                this.$store.dispatch('setFilter',  id);
            },
            changeBalance(user_id, count) {
                for (const [index, value] of Object.entries(this.$store.state.clients)) {
                    if (value.id == user_id) {
                        let balance = this.$store.state.clients[index].balance;
                        this.$store.state.clients[index].balance = parseInt(balance) + parseInt(count);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
