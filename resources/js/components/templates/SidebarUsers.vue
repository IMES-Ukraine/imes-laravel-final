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
            <popup-button>
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
    export default {
        name: "withdrawal-form-sidebar",
        components: {RouterButton, PopupButton},
        data() {
            return {
                filterId: null
            }
        },
        methods: {
            findUser(id) {
                this.$store.state.filterId = id;
            }
        }
    }
</script>

<style scoped>

</style>
