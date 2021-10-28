<template>
    <div>
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button" href="/"><span class="icon-back">Проекты</span></a>
            <a class="sidebar_nav-button"><span class="icon-download">Скачать отчёт</span></a>
        </div>
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button"><span>Остановить</span></a>
            <a class="sidebar_nav-button red" :href="'/project/remove/' + id"><span class="icon-delete">Удалить проект</span></a>
        </div>
    </div>
</template>

<script>
    import RouterButton from "../../../fragmets/router-button";

    export default {
        name: "project-list-sidebar",
        props: {
            id: {
                type: Number,
                require: true
            },
        },
        components: {RouterButton},
    }
</script>
