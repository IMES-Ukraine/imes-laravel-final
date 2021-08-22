<template>
    <div class="window-mask min-vw-100 min-vh-100">
        <div class="d-lg-table-cell align-middle">
            <div class="window-container p-1">

                <div class="window-header">
                    <slot name="header"></slot>
                </div>

                <div class="window-body">
                    <slot name="body">
                        default body
                    </slot>
                </div>

                <div class="window-footer">
                    <slot name="footer"></slot>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "vue-window"
}
</script>

<style scoped>
    .window-mask {
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
    }
    .window-container {
        max-width: 40%;
        width: 100%;
        margin: 0 auto;
        min-height: 203px;
        background-color: #FFFFFF;
        box-shadow: 0 0 4px #2C2C2C;
        border-radius: 5px;
    }
    .window-header {
        min-height: 20px;
    }
</style>
