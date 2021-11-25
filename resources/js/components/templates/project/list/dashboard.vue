<template>
    <div>
        <div class="sidebar_nav gap-10">
            <a class="sidebar_nav-button" href="/"><span class="icon-back">Проекты</span></a>
            <a class="sidebar_nav-button" :href="'/admin/api/v1/export-users/' + project_id"><span class="icon-download">Скачать отчёт</span></a>
        </div>
        <div class="sidebar_nav gap-10">
            <project-start v-if="(status && status == 'inactive')" @update="setStatus" />
            <project-stop v-else @update="setStatus" />
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
            },
            project_id: {
                type: Number,
                default: 0
            },
            status: {
                type: String,
                default: ''
            }
        },
        methods: {
            setStatus (value) {
                this.status = value;
            }
        }
    }
</script>
