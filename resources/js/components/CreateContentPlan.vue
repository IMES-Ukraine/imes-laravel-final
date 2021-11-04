<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <v-close :to="{name:'articleList'}"/>
                <swiper class="swiper articles_tabs" ref="swiper" :options="swiperOption" style="width: 100%;">
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
</style>
