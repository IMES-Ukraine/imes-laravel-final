<template>
    <div class="articles_create__item">
        <p class="articles_create__item-title">{{title}}</p>
        <div class="articles_create__item-content">
            <div class="articles_create__name-block">
                <slot>
                    --> input place <--
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "fragment-form-text",
    props: {
        title: {
            type: String,
            default: 'Название'
        }
    }
}
</script>
