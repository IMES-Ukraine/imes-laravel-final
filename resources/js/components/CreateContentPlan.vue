<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <v-close :to="{name:'articleList'}"/>
                <swiper class="swiper articles_tabs articlesTabsNav" ref="swiper" :options="swiperOption" style="width: 100%;">
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-info">1</div>
                        <button class="articles_tabs-delete"></button>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-info">2</div>
                        <button class="articles_tabs-delete"></button>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <swiper-slide class="articles_tabs-item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
                <div class="articles_tabs__content articlesTabsContent">
                    <div class="articles_tabs__content-block">
                        <span>block-1</span>
                    </div>
                    <div class="articles_tabs__content-block">
                        <span>block-2</span>
                    </div>
                    <div class="articles_tabs__content-block">
                        <span>block-3</span>
                    </div>
                </div>
                <!--<div class="articles_tabs">
                    <div class="articles_tabs-item">
                        <div class="articles_tabs-info">1</div>
                        <button class="articles_tabs-delete"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <div class="articles_tabs-info">2</div>
                        <button class="articles_tabs-delete"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                    <div class="articles_tabs-item">
                        <button class="articles_tabs-add"></button>
                    </div>
                </div>-->
            </div>
        </div>

    </v-content>
</template>

<script>
import ArticleSidebar from "./templates/article/sidebar"
import VContent from "./templates/Content"
import VClose from "./templates/Close"
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'

export default {
    name: "CreateContentPlan",
    components: {
        VClose,
        VContent,
        ArticleSidebar,
        Swiper,
        SwiperSlide
    },
    data() {
        return {
            swiperOption: {
                spaceBetween: 30,
                slidesPerView: 'auto',
                direction: 'horizontal',
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                }
            }
        }
    }
}
</script>

<style scoped>
    .swiper-slide {
        width: 45px;
        height: 45px;
    }
    .swiper-container {
        padding: 5px 0;
    }
    .swiper-button-prev,
    .swiper-button-next {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        background: none;
        margin: 0;
        transform: translate(0, -50%);
    }

    .swiper-button-prev:after,
    .swiper-button-next:after {
        content: "";
        display: block;
        width: 8px;
        height: 11px;
        background-color: #BDBDBD;
        -webkit-mask-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='8' height='11' viewBox='0 0 8 11' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M2.18837 0.213756L6.9236 4.89361C7.12312 5.09061 7.12312 5.40888 6.9236 5.60639L2.18837 10.2862C1.90034 10.5713 1.43173 10.5713 1.1432 10.2862C0.855176 10.0012 0.855176 9.53868 1.1432 9.25367L5.19393 5.24974L1.1432 1.24684C0.855175 0.961325 0.855175 0.498764 1.1432 0.213756C1.43173 -0.0712523 1.90034 -0.0712524 2.18837 0.213756Z' fill='%23BDBDBD'/%3e%3c/svg%3e ");
        mask-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='8' height='11' viewBox='0 0 8 11' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M2.18837 0.213756L6.9236 4.89361C7.12312 5.09061 7.12312 5.40888 6.9236 5.60639L2.18837 10.2862C1.90034 10.5713 1.43173 10.5713 1.1432 10.2862C0.855176 10.0012 0.855176 9.53868 1.1432 9.25367L5.19393 5.24974L1.1432 1.24684C0.855175 0.961325 0.855175 0.498764 1.1432 0.213756C1.43173 -0.0712523 1.90034 -0.0712524 2.18837 0.213756Z' fill='%23BDBDBD'/%3e%3c/svg%3e ");
    }

    .swiper-button-prev {
        left: -20px;
    }

    .swiper-button-prev:after {
        -webkit-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        transform: rotate(-180deg);
    }

    .swiper-button-next {
        right: -20px;
    }
    .swiper-button-prev.swiper-button-disabled,
    .swiper-button-next.swiper-button-disabled {
        opacity: 0;
    }
</style>
