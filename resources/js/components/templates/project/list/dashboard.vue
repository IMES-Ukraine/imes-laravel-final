<template>
    <div>
        <!--<router-button :url="'/'">
            Проекты
        </router-button>
        <router-button :url="'/'">
            Скачать отчёт
        </router-button>
        <router-button :url="'/'">
            Остановить
        </router-button>
        <router-button :url="'/'">
            Удалить проект
        </router-button>-->
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button" href="/"><span class="icon-back">Проекты</span></a>
            <a class="sidebar_nav-button"><span class="icon-download">Скачать отчёт</span></a>
        </div>
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button"><span>Остановить</span></a>
            <a class="sidebar_nav-button red"><span class="icon-delete">Удалить проект</span></a>
        </div>
    </div>
</template>

<script>
    import RouterButton from "../../../fragmets/router-button";

    export default {
        name: "project-list-sidebar",
        components: {RouterButton},
    }
</script>
