<template>
    <v-content>
        <template v-slot:sidebar>
            <div>
                <div class="sidebar-content__block">
                    <!-- активная в данный момент ссылка с классом is-active -->
                    <button type="button" class="btn btn-outline-primary btn-block is-active">
                        Картки
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-block is-active"  @click="addCard()">
                        Додати карту
                    </button>
                    <div class="input-group input-group mt-5 mb-4">
                        <input type="text" id="filterId" class="form-control input-is-small input-has-append"
                               placeholder="пошук по № картки"
                               v-model="filterId"
                               v-on:keyup.enter="findCard(filterId)"
                               aria-label="пошук по № картки">
                        <div class="input-group-append">
                            <button class="button-group-input" aria-label="знайти" @click="findCard(filterId)">
                                <span class="icon-is-search"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="main-db">
            <div class="db__block">
                <div class="links_wrap right mb20">
                    <a class="sidebar_nav-button width-auto height-35" href="/banner/new"><span>Баннер</span></a>
                    <!--<a class="sidebar_nav-button width-auto height-35 active"><span>Создать карточку</span></a>-->
                </div>
                <div class="db-edit card">
                    <div class="card-body">
                        <cards-table
                            v-on:onDisableCard="onDisableCardHandler"
                            v-on:onEnableCard="onEnableCardHandler"
                            v-on:onDeleteCard="onDeleteCardHandler"
                        ></cards-table>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
</template>

<script>
import VContent from "./templates/Content";
import CardsTable from './templates/cards/table-card'
import {CARDS, CARD_ENABLE, CARD_DISABLE} from "../api/endpoints"
import VPreloader from "./fragmets/preloader"
import ModalMixin from "../ModalMixin";

export default {
    name: "Cards",
    components: {
        VContent, CardsTable, VPreloader
    },
    mixins: [ModalMixin],
    data() {
        return {
            filterId: null
        }
    },
    computed: {
        requests() {
            return this.$store.state.cards;
        }
    },
    methods: {
        findCard(id) {
            this.$store.state.filterId = id;
        },
        addCard() {
            this.showModal({})
        },
        async onDisableCardHandler(id) {
            this.$get(CARD_DISABLE + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_active = 0
                    return
                }
            }
        },
        async onEnableCardHandler(id) {

            this.$get(CARD_ENABLE + '/' + id).then()

            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    value.is_active = 1
                    return
                }
            }
        },
        async onDeleteCardHandler(id) {
            this.$delete(CARDS + '/' + id).then()
            $('#db-remove--' + id + ' .is-close').click();
            for (const [index, value] of Object.entries(this.requests)) {
                if (value.id === id) {
                    this.requests.splice(index, 1)
                    return
                }
            }
        }
    },
    mounted() {
        this.loadCards();
    }

}
</script>
