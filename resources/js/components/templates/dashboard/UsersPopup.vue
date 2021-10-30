<template>
    <div>

        <button class="dashboard_main__item-button" type="button" data-toggle="modal" data-target="#db-users-popup">Смотреть</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="db-users-popup" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto">
                    <p class="template_title">{{ title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <div class="study-box">
                        <div class="study__block">
                            <div class="study__block-content">
                                <div class="study__table">
                                    <div class="study__table-block study__table-block--head">
                                        <div class="study__table-item">
                                            <p class="study__table-title">№ п</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">ID</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">ФИО</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">Ответ</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls--title">
                                                <p>Принять</p>
                                                <p>Отклонить</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="study__table-block">
                                        <div class="study__table-item">
                                            <p class="study__table-number">1</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">358 110</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-name">Петров Іван Сергійович</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">Tempor sapien erat at purus in quam praesent quis risus. Ac proin vitae placerat amet, at est, tincidunt. Egestas blandit pretium vulputate eleifend eleifend imperdiet. Pretium id pretium nisi eget adipiscing suspendisse eget id mattis. Vitae ipsum mi faucibus non ac sit.</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls">
                                                <button class="study__table-button study__table-button--plus"></button>
                                                <button class="study__table-button study__table-button--minus"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="study__table-block">
                                        <div class="study__table-item">
                                            <p class="study__table-number">2</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">356 121</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-name">Василенко Сергій Іванович</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">Tempor sapien erat at purus in quam praesent quis risus. Ac proin vitae placerat amet, at est, tincidunt. Egestas blandit pretium vulputate eleifend eleifend imperdiet. Pretium id pretium nisi eget adipiscing suspendisse eget id mattis. Vitae ipsum mi faucibus non ac sit.</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls">
                                                <button class="study__table-button study__table-button--plus"></button>
                                                <button class="study__table-button study__table-button--minus"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="study__table-block">
                                        <div class="study__table-item">
                                            <p class="study__table-number">3</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">123 067</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-name">Леонович Лариса Степанівна</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">Tempor sapien erat at purus in quam praesent quis risus. Ac proin vitae placerat amet, at est, tincidunt. Egestas blandit pretium vulputate eleifend eleifend imperdiet. Pretium id pretium nisi eget adipiscing suspendisse eget id mattis. Vitae ipsum mi faucibus non ac sit.</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls">
                                                <button class="study__table-button study__table-button--plus"></button>
                                                <button class="study__table-button study__table-button--minus"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="study__table-block">
                                        <div class="study__table-item">
                                            <p class="study__table-number">4</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">755 333</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-name">Голобородько Василь Ігоревич</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">Tempor sapien erat at purus in quam praesent quis risus. Ac proin vitae placerat amet, at est, tincidunt. Egestas blandit pretium vulputate eleifend eleifend imperdiet. Pretium id pretium nisi eget adipiscing suspendisse eget id mattis. Vitae ipsum mi faucibus non ac sit. Est integer aenean eget neque vivamus quis pellentesque ac. Urna eget ullamcorper viverra et arcu. Nibh pellentesque tincidunt sed justo, tincidunt justo, mi nisl. Purus scelerisque habitant nisi mattis. Dictum purus a, id feugiat eu, cum maecenas nibh purus. Eget quam enim sed eget et varius purus consectetur. Id tristique velit amet diam pharetra, nunc. Sed faucibus condimentum scelerisque hendrerit. Nibh curabitur sed sagittis aliquet mollis.</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls">
                                                <button class="study__table-button study__table-button--plus"></button>
                                                <button class="study__table-button study__table-button--minus"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="study__table-block">
                                        <div class="study__table-item">
                                            <p class="study__table-number">5</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">001 738</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-name">Кожушко Ілля Олегович</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">Tempor sapien erat at purus in quam praesent quis risus. Ac proin vitae placerat amet, at est, tincidunt. Egestas blandit pretium vulputate eleifend eleifend imperdiet. Pretium id pretium nisi eget adipiscing suspendisse eget id mattis. Vitae ipsum mi faucibus non ac sit.</p>
                                        </div>
                                        <div class="study__table-item">
                                            <div class="study__table-controls">
                                                <button class="study__table-button study__table-button--plus"></button>
                                                <button class="study__table-button study__table-button--minus"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FragmentCloseItem from "../../fragmets/close-item"

    export default {
        name: "users-popup",
        components: {FragmentCloseItem},
        props: {
            title: {
                type: String,
                default: ''
            }
        },
        data () {
            return {
                isOpen: false,
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            }
        }
    }
</script>
