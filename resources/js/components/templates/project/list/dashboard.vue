<template>
    <div>
        <router-button :url="'/'">
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
        </router-button>
    </div>
</template>

<script>
    import RouterButton from "../../../fragmets/router-button";

    export default {
        name: "project-list-sidebar",
        components: {RouterButton},
    }
</script>
