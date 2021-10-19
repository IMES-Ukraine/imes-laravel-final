<template>
    <div class="container-fluid main-content">
        <div class="row h-100">
            <v-sidebar>
                <slot name="sidebar" />
            </v-sidebar>

            <div class="col-10 main_content">
                <slot>
                    Content
                </slot>
            </div>
        </div>

    </div>
</template>

<script>
import VSidebar from "./Sidebar";
export default {
    name: "v-content",
    components: {VSidebar}
}
</script>
