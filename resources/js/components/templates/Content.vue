<template>
    <div class="main-content">
        <v-sidebar>
            <slot name="sidebar" />
        </v-sidebar>

        <div class="main_content">
            <slot>
                Content
            </slot>
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
