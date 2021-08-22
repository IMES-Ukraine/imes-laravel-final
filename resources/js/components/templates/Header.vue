<template>
    <header
        class="navbar navbar-expand-lg sticky-top flex-md-nowrap p-0 shadow-main"
    >
        <div class="container-fluid">
            <logotype class="navbar-brand mr-auto"/>

            <ul class="navbar-nav">
                <li class="nav-item" v-for="(route, k) in routes" v-bind:key="route.path">
                    <router-link
                            :to="{path: route.path}"
                            :key="k"
                            class="nav-link form-control__label"
                    >
                        {{route.name}}
                    </router-link>
                </li>
            </ul>
        </div>
    </header>
</template>

<script>
    import logotype from "../fragmets/logotype";
    export default {
        name: 'v-header',
        components: {logotype},
        data() {
            return {
                routes: [
                    { name: 'Проекти', path: '/' },
                    //{ name: 'Статті', path: '/articles' },
                    { name: 'Розсилка', path: '/notification' },
                    //{ name: 'Користувачi', path: '/clients' },
                    //{ name: 'Повідомлення', path: '/notices' },
                    { name: 'Верифiкацiя', path: '/verification' },
                    { name: 'Перекази', path: '/withdrawal' },
                    //{ name: 'Користувачi', path: '/user' },
                    { name: 'Модерувати вiдповiдь', path: '/moderation' },
                    //{ name: 'Підтримка', path: '/support' },
                ],
            }
        }
    }
</script>
<style>
    .navbar {
        background-color: #F7F7F9;
        border-bottom: 1px solid #E5E5E5;
    }
</style>
