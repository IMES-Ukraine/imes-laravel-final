<template>
    <div>
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button" href="/"><span class="icon-back">Проекты</span></a>
            <a class="sidebar_nav-button"><span class="icon-download">Скачать отчёт</span></a>
        </div>
        <div class="sidebar_nav gap-10">
            <template v-if="(options.status && options.status == 'inactive')">
                <project-start/>
            </template>
            <project-stop v-else/>
            <project-remove/>
        </div>
    </div>
</template>

<script>
    import RouterButton from "../../../fragmets/router-button"
    import ProjectRemove from "../../../templates/dashboard/Remove"
    import ProjectStop from "../../../templates/dashboard/Stop"
    import ProjectStart from "../../../templates/dashboard/Start"

    export default {
        name: "project-list-sidebar",
        components: {
            RouterButton,
            ProjectRemove,
            ProjectStop,
            ProjectStart
        },
        props: {
            options: {
                type: Object,
                default: {}
            }
        }
    }
</script>
