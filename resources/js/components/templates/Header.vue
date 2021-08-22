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
                <li class="nav-item">
                    <a href="#" class="nav-link form-control__label" @click.prevent="$auth.logout()">
                        Вийти
                    </a>
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
                    { name: 'Статті', path: '/articles' },
                    { name: 'Користувачi', path: '/clients' },
                    { name: 'Повідомлення', path: '/notices' },
                    { name: 'Підтримка', path: '/support' },
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
